<?php

/**
 * Auth Class file for the College Recruitment Application
 * This class is used to manage authentication for the application and store related data in the database
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/16/2023
 *
 * @category Authentication
 * @package RYM2
 * Filename: auth.inc.php
 * @version 1.0.0
 * @requires PHP 8.1.2+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* Include the base application config file */
require_once(__DIR__ . '/../../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');

class Authenticator extends User
{
    //Instantiate the database connection
    public function __construct()
    {
        try {
            $this->mysqli = connectToDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
        } catch (Exception $e) {
            //log the error
            error_log('Error: ' . $e->getMessage());
        }
    }

    //Close the database connection when the object is destroyed
    public function __destruct()
    {
        closeDatabaseConnection($this->mysqli);
    }

    /**
     * Check if the user is logged in
     *
     * @return bool True if the user is logged in, false if not
     */
    public function isLoggedIn(): bool
    {
        //instantiate the session class
        $session = new Session();

        //get the logged in status from the session
        $isLoggedIn = $session->get("logged_in") ?? false;

        // Check if the user is logged in, if not then redirect them to the login page
        if (!isset($isLoggedIn) || $isLoggedIn !== true) {
            return false;
        }

        //return the logged in status, which should otherwise be true
        return true;
    }

    /**
     * Get the user data by username
     *
     * @param string $username The username to check
     * @return array The user data
     */
    function getUserByUsername(string $username) : array
    {
        //set user class object
        $user = new User();

        //get the user ID by username
        $userID = $user->getUserIdByUsername($username);

        //create array to hold the user data
        $userData = array();

        //if the user exists, get the user data and assign it to the user_data array
        if ($userID) {
            //add the user ID to the user data array
            $userData['user_id'] = $userID;
            $userData['username'] = $username;
            $userData['password'] = $user->getUserPassword($userID);
            $userData['email'] = $user->getUserEmail($userID);

            //get user roles
            $userRoles = $user->getUserRoles($userID);

            //add the user roles array to the user data
            $userData['roles'] = $userRoles;
        }

        //return the user data array
        return $userData;
    }

    /**
     * Get the authentication token
     * @param int $userID
     * @param string $username
     * @param mixed $expired
     * @return array|bool
     */
    function getAuthenticationToken(int $userID, string $username, $expired)
    {
        //SQL statement to get the authentication token
        $sql = "SELECT * FROM user_token_auth WHERE user_id = ? AND user_name = ? AND is_expired = ?";

        //prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters to the SQL statement
        $stmt->bind_param("isi", $userID, $username, $expired);

        //execute the SQL statement
        $stmt->execute();

        //get the result of the SQL statement
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            //return the result
            return $result->fetch_all(MYSQLI_ASSOC);;
        }

        //return false if no token is found
        return false;
    }

    /**
     * Expire the authentication token
     * @param int $tokenID
     * @return bool if the token was expired
     */
    function expireToken(int $tokenID)
    {
        //SQL statement to expire the token
        $sql = "UPDATE user_token_auth SET is_expired = 1 WHERE id = ?";

        //prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters to the SQL statement
        $stmt->bind_param("i", $tokenID);

        //execute the SQL statement
        $stmt->execute();

        //if there were more than 0 rows affected, return true
        if ($this->mysqli->affected_rows > 0) {
            return true;
        }

        //return false if no rows were affected
        return false;
    }

    /**
     * Expire the authentication token by user ID
     * @param int $userID
     * @return bool if the token was expired
     */
    function expireTokenByUserID(int $userID)
    {
        //SQL statement to expire the token
        $sql = "UPDATE user_token_auth SET is_expired = 1 WHERE user_id = ?";

        //prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters to the SQL statement
        $stmt->bind_param("i", $userID);

        //execute the SQL statement
        $stmt->execute();

        //if there were more than 0 rows affected, return true
        if ($this->mysqli->affected_rows > 0) {
            return true;
        }

        //return false if no rows were affected
        return false;
    }

    /**
     * Create the authentication token
     * @param int $userID
     * @param string $username
     * @param string $passwordHash
     * @param string $selectorHash
     * @param string $expireDate
     * @return bool
     */
    function createToken(int $userID, string $username, string $passwordHash, string $selectorHash, $expireDate)
    {
        //SQL statement to create the token
        $sql = "INSERT INTO user_token_auth (user_id, user_name, password_hash, selector_hash, expiry_date) VALUES (?, ?, ?, ?, ?)";

        //prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters to the SQL statement
        $stmt->bind_param("issss", $userID, $username, $passwordHash, $selectorHash, $expireDate);

        //execute the SQL statement
        $stmt->execute();

        //get the result of the SQL statement
        $result = $stmt->get_result();

        //if the result is true, return true
        if ($result) {
            return true;
        }

        //return false if the result is false
        return false;
    }

    /**
     * Check if any of the user's roles have the specified permission
     *
     * @param int $userID The user ID
     * @param int $permissionID The permission ID
     *
     * @return bool True if the user has the permission, false if not
     */
    function checkUserPermission(int $userID, int $permissionID)
    {
        //include the role class
        $rolesObject = new Roles();

        //reference the user class
        $user = new User();

        //get the user's roles
        $userRoles = $user->getUserRoles($userID);

        //boolean to check if the user has the permission
        $hasPermission = false;

        //loop through the user's roles, for each role, get the permissions
        foreach ($userRoles as $role) {
            $rolePermissions = $rolesObject->getRolePermissions(intval($role['id']));

            //loop through the permissions and check if the user has the relevant permission
            foreach ($rolePermissions as $permission) {
                foreach ($permission as $key => $value) {
                    //if hasPermission is true, break out of the loop
                    if (!$hasPermission) {
                        //get the id of the permission
                        $comparedID = intval($value['id']);

                        //if the permission id matches the relevant permission id, set the hasPermission boolean to true
                        if ($comparedID == $permissionID) {
                            $hasPermission = true;
                        }
                    }
                    break;
                }
            }
        }

        //return the hasPermission boolean
        return $hasPermission;
    }

    /**
     * Get tokens by user ID which have an expiry date that has passed
     * @param int $userID
     *
     * @return array|bool
     */
    function getExpiredTokens(int $userID) {
        //SQL statement to get the expired tokens
        $sql = "SELECT * FROM user_token_auth WHERE user_id = ? AND expiry_date < ?";

        //prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //get the current date
        $currentDate = date('Y-m-d H:i:s');

        //bind the parameters to the SQL statement
        $stmt->bind_param("is", $userID, $currentDate);

        //execute the SQL statement
        $stmt->execute();

        //get the result of the SQL statement
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            //return the result
            return $result->fetch_all(MYSQLI_ASSOC);;
        }

        //return false if no token is found
        return false;
    }

    /**
     * Clear the user's expired tokens
     * @param int $userID
     *
     * @return bool
     */
    function clearExpiredTokens(int $userID)
    {
        //SQL statement to clear the expired tokens
        $sql = "DELETE FROM user_token_auth WHERE user_id = ? AND is_expired = 1 AND expiry_date < ?";

        //prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //get the current date
        $currentDate = date('Y-m-d H:i:s');

        //bind the parameters to the SQL statement
        $stmt->bind_param("is", $userID, $currentDate);

        //execute the SQL statement
        $stmt->execute();

        //if there were more than 0 rows affected, return true
        if ($this->mysqli->affected_rows > 0) {
            return true;
        }

        //return false if no rows were affected
        return false;
    }

    /**
     * Validate the user by ID
     *
     * @param int $userID The user ID
     *
     * @return bool True if the user exists, false if not
     */
    public function validateUserById(int $userID): bool
    {
        //try to get the user object (array) by ID
        try {
            $user = $this->getUserById($userID);
        } catch (Exception $e) {
            //log the error
            error_log('Error: ' . $e->getMessage());
        }

        //if the user exists (ie the array is not empty), return true
        if ($user && !empty($user)) {
            return true;
        }

        //return false if the user does not exist
        return false;
    }

    /**
     * User exists by username
     *
     * @param string $username The username to check
     *
     * @return bool True if the user exists, false if not
     */
    public function validateUserByUsername(string $username): bool
    {
        //placeholder for the user ID
        $userID = null;

        //try to get the user ID by username
        try {
            $userID = $this->getUserIdByUsername($username);
        } catch (Exception $e) {
            //log the error
            error_log('Error: ' . $e->getMessage());
        }

        //if the user ID exists, and is not null, or 0, return true
        if ($userID && $userID != null && $userID != 0) {
            return true;
        }

        //return false if the user does not exist
        return false;
    }

    //User exists by email
    public function validateUserByEmail(string $email): bool
    {
        //placeholder for the user ID
        $userID = null;

        //try to get the user ID by email
        try {
            $userID = $this->getUserByEmail($email);
        } catch (Exception $e) {
            //log the error
            error_log('Error: ' . $e->getMessage());
        }

        //if the user ID exists, and is not null, or 0, return true
        if ($userID && $userID != null && $userID != 0) {
            return true;
        }

        //return false if the user does not exist
        return false;
    }

    /**
     * Validate a role exists by ID
     *
     * @param int $userID
     * @return bool
     */
    public function validateRoleById(int $userID): bool
    {
        //reference the role class
        $roleClass = new Roles();

        //try to get the role by ID
        try {
            $role = $roleClass->getRoleById($userID);
        } catch (Exception $e) {
            //log the error
            error_log('Error: ' . $e->getMessage());
        }

        //if the role exists (ie the array is not empty), return true
        if ($role && !empty($role)) {
            return true;
        }

        //return false if the role does not exist
        return false;
    }
}
