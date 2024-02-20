<?php

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* Include the base application config file */
require_once(__DIR__ . '/../../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');

class Authenticator extends User
{
    //Reference to the database
    private $mysqli;

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

    //Check if the user is logged in
    public function isLoggedIn(): bool
    {
        // Check if the user is logged in, if not then redirect them to the login page
        if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
            return false;
        } else {
            return true;
        }
    }

    //Get user by username
    function getUserByUsername(string $username)
    {
        //set user class object
        $user = new User();

        //get the user ID by username
        $user_id = $user->getUserIdByUsername($username);

        //create array to hold the user data
        $user_data = array();

        //if the user exists, get the user data and assign it to the user_data array
        if ($user_id) {
            //add the user ID to the user data array
            $user_data['user_id'] = $user_id;
            $user_data['username'] = $username;
            $user_data['password'] = $user->getUserPassword($user_id);
            $user_data['email'] = $user->getUserEmail($user_id);

            //get user roles
            $user_roles = $user->getUserRoles($user_id);

            //add the user roles array to the user data
            $user_data['roles'] = $user_roles;
        }

        //return the user data array
        return $user_data;
    }

    //Get authentication token by user ID and username
    function getAuthenticationToken(int $user_id, string $username, $expired)
    {
        //SQL statement to get the authentication token
        $sql = "SELECT * FROM user_token_auth WHERE user_id = ? AND user_name = ? AND is_expired = ?";

        //prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters to the SQL statement
        $stmt->bind_param("isi", $user_id, $username, $expired);

        //execute the SQL statement
        $stmt->execute();

        //get the result of the SQL statement
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            //return the result
            return $result->fetch_all(MYSQLI_ASSOC);;
        } else {
            return false;
        }
    }

    //Expire authentication token
    function expireToken(int $token_id)
    {
        //SQL statement to expire the token
        $sql = "UPDATE user_token_auth SET is_expired = 1 WHERE id = ?";

        //prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters to the SQL statement
        $stmt->bind_param("i", $token_id);

        //execute the SQL statement
        $stmt->execute();

        //get the result of the SQL statement
        $result = $stmt->get_result();

        //return the result
        return $result;
    }

    //Create authentication token
    function createToken(int $user_id, string $username, string $password_hash, string $selector_hash, $expire_date)
    {
        //SQL statement to create the token
        $sql = "INSERT INTO user_token_auth (user_id, user_name, password_hash, selector_hash, expiry_date) VALUES (?, ?, ?, ?, ?)";

        //prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters to the SQL statement
        $stmt->bind_param("issss", $user_id, $username, $password_hash, $selector_hash, $expire_date);

        //execute the SQL statement
        $stmt->execute();

        //get the result of the SQL statement
        $result = $stmt->get_result();

        //return the result
        return $result;
    }

    /**
     * Check if any of the user's roles have the specified permission
     *
     * @param int $user_id The user ID
     * @param int $permission_id The permission ID
     *
     * @return bool True if the user has the permission, false if not
     */
    function checkUserPermission(int $user_id, int $permission_id)
    {
        //include the role class
        $rolesObject = new Roles();

        //reference the user class
        $user = new User();

        //get the user's roles
        $userRoles = $user->getUserRoles($user_id);

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
                        $permissionID = intval($value['id']);

                        //if the permission id matches the relevant permission id, set the hasPermission boolean to true
                        if ($permissionID == $permission_id) {
                            $hasPermission = true;
                        } else {
                            $hasPermission = false;
                        }
                    } else {
                        break;
                    }
                }
            }
        }

        //return the hasPermission boolean
        return $hasPermission;
    }

    //User exists by ID
    public function validateUserById(int $id): bool
    {
        //try to get the user object (array) by ID
        try {
            $user = $this->getUserById($id);
        } catch (Exception $e) {
            //log the error
            error_log('Error: ' . $e->getMessage());
        }

        //if the user exists (ie the array is not empty), return true
        if ($user && !empty($user)) {
            return true;
        } else {
            return false;
        }
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
        $user_id = null;

        //try to get the user ID by username
        try {
            $user_id = $this->getUserIdByUsername($username);
        } catch (Exception $e) {
            //log the error
            error_log('Error: ' . $e->getMessage());
        }

        //if the user ID exists, and is not null, or 0, return true
        if ($user_id && $user_id != null && $user_id != 0) {
            return true;
        } else {
            return false;
        }
    }

    //User exists by email
    public function validateUserByEmail(string $email): bool
    {
        //try to get the user ID by email
        try {
            $user_id = $this->getUserByEmail($email);
        } catch (Exception $e) {
            //log the error
            error_log('Error: ' . $e->getMessage());
        }

        //if the user ID exists, and is not null, or 0, return true
        if ($user_id && $user_id != null && $user_id != 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Validate a role exists by ID
     *
     * @param int $id
     * @return bool
     */
    public function validateRoleById(int $id): bool
    {
        //reference the role class
        $roleClass = new Roles();

        //try to get the role by ID
        try {
            $role = $roleClass->getRoleById($id);
        } catch (Exception $e) {
            //log the error
            error_log('Error: ' . $e->getMessage());
        }

        //if the role exists (ie the array is not empty), return true
        if ($role && !empty($role)) {
            return true;
        } else {
            return false;
        }
    }
}
