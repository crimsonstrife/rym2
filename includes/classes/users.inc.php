<?php

/**
 * User Class file for the College Recruitment Application
 * Contains all the functions for the User Class and handles all the user related tasks with the database.
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/16/2023
 *
 * @package RYM2
 * Filename: users.inc.php
 * @version 1.0.0
 * @requires PHP 7.2.5+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* Include the base application config file */

require_once(__DIR__ . '/../../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');

/**
 * User Class
 * Contains all the functions for the User Class and handles all the user related tasks with the database.
 *
 * @package RYM2
 * @version 1.0.0
 */
class User implements Login
{
    //Reference to the database
    private $mysqli;

    //Instantiate the database connection
    public function __construct()
    {
        $this->mysqli = connectToDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    }

    //Close the database connection when the object is destroyed
    public function __destruct()
    {
        closeDatabaseConnection($this->mysqli);
    }

    //hash the password
    private function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    //verify the password
    private function verifyPassword(string $password, string $hash): bool
    {
        $isValid = password_verify($password, $hash);

        //log the result for debugging
        error_log("Password is : " . strval($isValid));

        //return the result
        if ($isValid) {
            return true;
        } else {
            return false;
        }
    }

    //validate the user's password
    public function validateUserPassword(int $user_id, string $password): bool
    {
        //get the user's hashed password
        $user_password = $this->getUserPassword($user_id);

        error_log("User password (hashed): " . $user_password);

        //hash the password to compare
        $attempted_password = $this->hashPassword($password);

        error_log("Attempted password (hashed): " . $attempted_password);

        //verify the password
        if ($this->verifyPassword($password, $user_password)) {
            return true;
        } else {
            return false;
        }
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

    //Login the user with the username and password provided, set the session variables
    public function login($username, $password)
    {
        //trim the email
        $username = trim($username);

        //try to login
        try {
            //get the user ID by username
            $user_id = $this->getUserIdByUsername($username);

            //get the user's hashed password
            $user_password = $this->getUserPassword($user_id);

            //verify the password
            if ($this->verifyPassword($password, $user_password)) {
                //set the session variables
                $_SESSION["logged_in"] = true;
                $_SESSION["user_id"] = $user_id;
                $_SESSION["username"] = $username;
                $_SESSION["user_roles"] = $this->getUserRoles($user_id);

                // log the activity
                $activity = new Activity();
                $activity->logActivity(null, "User logged in.", 'User ' . strval($user_id));
            } else {
                // Password is not valid, display a generic error message
                throw new Exception("Invalid username or password.");
            }
        } catch (Exception $e) {
            // Log the error
            error_log("Failed to log the user in: " . $e->getMessage());
            // log the activity
            $activity = new Activity();
            $activity->logActivity(null, "Failed to log the user in: " . $e->getMessage(), 'User ' . strval($user_id));
            // Display a generic error message
            throw new Exception("Invalid username or password.");
        }
    }

    //redirect user
    public function redirectUser($location)
    {
        header("location: " . $location);
    }

    //logout the user
    public function logout()
    {
        //initialize the session
        session_start();

        // Unset all of the session variables
        $_SESSION = array();

        // Destroy the session.
        session_destroy();

        // Redirect to login page
        $this->redirectUser(APP_URL . "/login.php");
    }

    //Get the user roles by user ID from the user_has_role table
    private function getRoleIDsByUserID(int $id): array
    {
        //SQL statement to get the role by ID
        $sql = "SELECT role_id FROM user_has_role WHERE user_id = ?";

        //Prepare the SQL statement for execution
        $role_statement = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $role_statement->bind_param("i", $id);

        //Execute the statement
        $role_statement->execute();

        //Get the results
        $result = $role_statement->get_result();

        //Create an array to hold the role
        $role_ids = array();

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $role_ids[] = $row;
        }

        //Return the array of roles
        return $role_ids;
    }

    //Get list of role objects using the roles class and the getRoleIdsByUserID function
    public function getUserRoles(int $id): array
    {
        //new roles array
        $roles = array();
        //get the role IDs by user ID
        $roles = $this->getRoleIDsByUserID($id);

        //new roles class
        $role = new Roles();

        //new array to hold the role objects
        $userRoles = array();

        //loop through the roles array and get the role objects
        foreach ($roles as $roleId) {
            $roleObject = $role->getRoleById($roleId['role_id']);
            //add the role object to the array
            $userRoles[] = $roleObject;
        }

        //return the roles array
        return $userRoles;
    }

    //Get all the users
    public function getAllUsers(): array
    {
        //SQL statement to get all the users
        $sql = "SELECT * FROM users";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create an array to hold the users
        $users = array();

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        //Return the array of users
        return $users;
    }

    //Get a user by ID
    public function getUserById(int $id): array
    {
        //SQL statement to get the user by ID
        $sql = "SELECT * FROM users WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the ID to the statement
        $stmt->bind_param("i", $id);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create an array to hold the user
        $user = array();

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $user = $row;
        }

        //Return the array of users
        return $user;
    }

    //Get a user's email
    public function getUserEmail(int $id): string
    {
        //SQL statement to get the user's email by ID
        $sql = "SELECT email FROM users WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the ID to the statement
        $stmt->bind_param("i", $id);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create a variable to hold the user's email
        $email = "";

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $email = $row['email'];
        }

        //Return the user's email
        return $email;
    }

    //Get a user's first name
    public function getUserFirstName(int $id): string
    {
        //SQL statement to get the user's first name by ID
        $sql = "SELECT first_name FROM users WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the ID to the statement
        $stmt->bind_param("i", $id);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create a variable to hold the user's first name
        $first_name = "";

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $first_name = $row['first_name'];
        }

        //Return the user's first name
        return $first_name;
    }

    //Get a user's last name
    public function getUserLastName(int $id): string
    {
        //SQL statement to get the user's last name by ID
        $sql = "SELECT last_name FROM users WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the ID to the statement
        $stmt->bind_param("i", $id);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create a variable to hold the user's last name
        $last_name = "";

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $last_name = $row['last_name'];
        }

        //Return the user's last name
        return $last_name;
    }

    //Get a user's full name
    public function getUserFullName(int $id): string
    {
        //get the user's first name
        $first_name = $this->getUserFirstName($id);

        //get the user's last name
        $last_name = $this->getUserLastName($id);

        //trim the first and last name
        $first_name = trim($first_name);
        $last_name = trim($last_name);

        //create the full name
        $full_name = $first_name . " " . $last_name;

        //return the full name
        return $full_name;
    }

    //Get a user's (hashed) password
    public function getUserPassword(int $id): string
    {
        //SQL statement to get the user's password by ID
        $sql = "SELECT password FROM users WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the ID to the statement
        $stmt->bind_param("i", $id);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create a variable to hold the user's password
        $password = "";

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $password = $row['password'];
        }

        //Return the user's password
        return $password;
    }

    //Get a user's username
    public function getUserUsername(int $id): string
    {
        //SQL statement to get the user's username by ID
        $sql = "SELECT username FROM users WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the ID to the statement
        $stmt->bind_param("i", $id);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create a variable to hold the user's username
        $username = "";

        //if the user id is not null, 0, or empty, get the username
        if ($id != null && $id != 0 && !empty($id)) {
            //Loop through the results and add them to the array
            while ($row = $result->fetch_assoc()) {
                $username = $row['username'];
            }
        } else {
            //if the user id is null, 0, or empty, set the username to "SYSTEM"
            $username = "SYSTEM";
        }

        //Return the user's username
        return $username;
    }

    //Get a user ID by username
    public function getUserIdByUsername(string $username): int
    {
        //SQL statement to get the user's ID by username
        $sql = "SELECT id FROM users WHERE username = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the username to the statement
        $stmt->bind_param("s", $username);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create a variable to hold the user's ID
        $id = 0;

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
        }

        //Return the user's ID
        return $id;
    }

    //Set a user's email
    public function setUserEmail(int $id, string $email): void
    {
        //SQL statement to set the user's email by ID
        $sql = "UPDATE users SET email = ? WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("si", $email, $id);

        //Execute the statement
        $stmt->execute();
    }

    //Set a user's first name
    public function setUserFirstName(int $id, string $first_name): void
    {
        //SQL statement to set the user's first name by ID
        $sql = "UPDATE users SET first_name = ? WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("si", $first_name, $id);

        //Execute the statement
        $stmt->execute();
    }

    //Set a user's last name
    public function setUserLastName(int $id, string $last_name): void
    {
        //SQL statement to set the user's last name by ID
        $sql = "UPDATE users SET last_name = ? WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("si", $last_name, $id);

        //Execute the statement
        $stmt->execute();
    }

    //Set a user's password
    public function setUserPassword(int $id, string $password): void
    {
        //hash the password
        $password = $this->hashPassword($password);

        //SQL statement to set the user's password by ID
        $sql = "UPDATE users SET password = ? WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("si", $password, $id);

        //Execute the statement
        $stmt->execute();
    }

    //Set a user's username
    public function setUserUsername(int $id, string $username): void
    {
        //SQL statement to set the user's username by ID
        $sql = "UPDATE users SET username = ? WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("si", $username, $id);

        //Execute the statement
        $stmt->execute();
    }

    //Add a user
    public function addUser(string $email, string $password, string $username, int $created_by = null): int
    {
        //hash the password
        $password = $this->hashPassword($password);

        //get current timestamp
        $timestamp = date("Y-m-d H:i:s");

        //SQL statement to add a user with current timestamp as created_at and updated_at
        $sql = "INSERT INTO users (email, password, username, created_at, updated_at, created_by, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?)";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("sssssii", $email, $password, $username, $timestamp, $timestamp, $created_by, $created_by);

        //Execute the statement
        $stmt->execute();

        //get the user ID
        $user_id = $stmt->insert_id;

        //return the user ID as an integer
        return intval($user_id);
    }

    //Delete a user
    public function deleteUser(int $id): void
    {
        //SQL statement to delete a user
        $sql = "DELETE FROM users WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the ID to the statement
        $stmt->bind_param("i", $id);

        //Execute the statement
        $stmt->execute();
    }

    //Update a user
    public function updateUser(int $id, string $email = null, string $password = null, string $username = null, int $updated_by): void
    {
        //if the email is null, get the current email
        if ($email == null) {
            $email = $this->getUserEmail($id);
        }

        //if the password is null, get the current password
        if ($password == null) {
            $password = $this->getUserPassword($id);
        } else {
            //if the password is not null, hash the password
            $password = $this->hashPassword($password);
        }

        //if the username is null, get the current username
        if ($username == null) {
            $username = $this->getUserUsername($id);
        }

        //get current date and time
        $date = date("Y-m-d H:i:s");

        //SQL statement to update a user
        $sql = "UPDATE users SET email = ?, password = ?, username = ?, updated_at = ?, updated_by = ? WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("ssssii", $email, $password, $username, $date, $updated_by, $id);

        //Execute the statement
        $stmt->execute();
    }

    //Add a role to a user
    public function giveRoleToUser(int $user_id, int $role_id): void
    {
        //get current date and time
        $date = date("Y-m-d H:i:s");

        //boolean to check if the role exists
        $roleExists = false;

        //validate a role exists by ID
        $role = new Roles();
        $roleExists = $role->validateRoleById($role_id);

        //if the role exists, add it to the user
        if ($roleExists == true) {
            //SQL statement to add a role to a user
            $sql = "INSERT INTO user_has_role (user_id, role_id, created_at, updated_at) VALUES (?, ?, ?, ?)";

            //Prepare the SQL statement for execution
            $stmt = $this->mysqli->prepare($sql);

            //Bind the parameters to the SQL statement
            $stmt->bind_param("iiss", $user_id, $role_id, $date, $date);

            //Execute the statement
            $stmt->execute();

            // log the activity
            $activity = new Activity();
            $activity->logActivity(null, "Role " . strval($role_id) . " added to user " . strval($user_id), "Role " . strval($role_id) . " added to user " . strval($user_id));
        } else {
            //if the role does not exist, throw an exception
            throw new Exception("Role does not exist.");
        }
    }

    //Remove a role from a user
    public function removeRoleFromUser(int $user_id, int $role_id): void
    {
        //SQL statement to remove a role from a user
        $sql = "DELETE FROM user_has_role WHERE user_id = ? AND role_id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("ii", $user_id, $role_id);

        //Execute the statement
        $stmt->execute();
    }

    //User exists by ID
    public function validateUserById(int $id): bool
    {
        //SQL statement to validate a user exists by ID
        $sql = "SELECT id FROM users WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the ID to the statement
        $stmt->bind_param("i", $id);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //if the user exists, return true
        if ($result->num_rows > 0) {
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
        //SQL statement to validate a user exists by username
        $sql = "SELECT id FROM users WHERE username = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the username to the statement
        $stmt->bind_param("s", $username);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //if the user exists, return true
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    //User exists by email
    public function validateUserByEmail(string $email): bool
    {
        //SQL statement to validate a user exists by email
        $sql = "SELECT id FROM users WHERE email = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the email to the statement
        $stmt->bind_param("s", $email);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //if the user exists, return true
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Create a user and assign them a role
     *
     * @param string $email The user's email
     * @param string $username The user's username
     * @param string $password The user's password
     * @param int $created_by The user ID of the user who created the user, default is null which will set the user ID to the system, or null
     * @param array $roles The roles to assign to the user, default is an empty array which will assign the user no roles
     *
     * @return bool True if the user was created, false if not
     */
    public function createUser(string $email, string $username, string $password, int $created_by = null, array $roles = array()): bool
    {
        //get the current date and time
        $date = date("Y-m-d H:i:s");

        //trim the email
        $email = trim($email);

        //trim the username
        $username = trim($username);

        //add the user
        $user_id = $this->addUser($email, $password, $username, $created_by);

        //if the user was created, assign the roles
        if ($user_id > 0 && !empty($user_id) && $user_id != null) {
            //if the roles array is not empty, assign the roles
            if (!empty($roles)) {
                //loop through the roles array and assign the roles
                foreach ($roles as $role) {
                    $this->giveRoleToUser($user_id, intval($role));
                }
            } else {
                //do nothing, roles should remain null
            }

            //if the user was created, notify the user
            $this->notifyUserCreated($email, $username, $password);

            //log the activity
            $activity = new Activity();
            $activity->logActivity($created_by, "User Created.", 'User ' . $username);

            //return true
            return true;
        } else if ($user_id == 0 || empty($user_id) || $user_id == null) {
            //if no user was created, return false
            return false;
        }

        //if no user was created, return false
        return false;
    }

    /**
     * Notifies the user of their account creation, sends an email to the user with their username and password
     *
     * @param string $email The user's email
     * @param string $username The user's username
     * @param string $password The user's password
     *
     * @return bool True if the email was sent, false if not
     */
    public function notifyUserCreated(string $email, string $username, string $password): bool
    {
        //include the contact class
        $contact = new Contact();

        //trim the email
        $email = trim($email);

        //trim the username
        $username = trim($username);

        //send the email
        $mail = $contact->sendAccountCreationEmail($email, $username, $password);

        //if the email was sent, return true
        if ($mail == true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Modify a user and or their roles
     *
     * @param int $id The user ID of the user to modify
     * @param string $email The user's email
     * @param string $username The user's username
     * @param string $password The user's password
     * @param int $updated_by The user ID of the user who updated the user, default is null which will set the user ID to the system, or null
     * @param array $roles The roles to assign to the user, default is an empty array which will assign the user no roles
     *
     * @return bool True if the user was modified, false if not
     */
    public function modifyUser(int $id, string $email = null, string $username = null, string $password = null, int $updated_by = null, array $roles = array()): bool
    {
        //get the current date and time
        $date = date("Y-m-d H:i:s");

        //if the email is null, get the current email
        if ($email == null) {
            $email = $this->getUserEmail($id);
        } else {
            //trim the email
            $email = trim($email);
        }

        //if the username is null, get the current username
        if ($username == null) {
            $username = $this->getUserUsername($id);
        } else {
            //trim the username
            $username = trim($username);
        }

        //if the password is null, get the current password
        if ($password == null) {
            $password = $this->getUserPassword($id);
        }

        //update the user
        $this->updateUser($id, $email, $password, $username, $updated_by);

        //get the user's current roles
        $currentRoles = $this->getUserRoles($id);

        //get just the role IDs from the current roles
        $currentRoleIDs = array();

        //loop through the current roles and get the role IDs
        foreach ($currentRoles as $role) {
            $currentRoleIDs[] = $role['id'];
        }

        //if the roles array is not empty, compare the roles to the id of the current roles and assign or remove roles as needed
        if (!empty($roles)) {
            //loop through the roles array and assign the roles
            foreach ($roles as $role) {
                //if the role is not in the current roles, assign the role
                if (!in_array($role, $currentRoleIDs)) {
                    $this->giveRoleToUser($id, intval($role));
                }
            }

            //loop through the current roles and remove the roles that are not in the roles array
            foreach ($currentRoleIDs as $currentRole) {
                //if the current role is not in the roles array, remove the role
                if (!in_array($currentRole, $roles)) {
                    $this->removeRoleFromUser($id, intval($currentRole));
                }
            }
        } else {
            //if the roles array is empty, remove all the roles from the user
            foreach ($currentRoleIDs as $currentRole) {
                $this->removeRoleFromUser($id, intval($currentRole));
            }
        }

        // log the activity
        $activity = new Activity();
        $activity->logActivity($updated_by, "User Modified.", 'User ' . $username);

        //if the user was modified, return true
        return true;
    }
}
