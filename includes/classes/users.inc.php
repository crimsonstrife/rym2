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
        return password_verify($password, $hash);
    }

    //validate the user's password
    public function validateUserPassword(int $user_id, string $password): bool
    {
        //trim the password
        $password = trim($password);

        //get the user's hashed password
        $user_password = $this->getUserPassword($user_id);

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
        //trim the password
        $password = trim($password);

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
            } else {
                // Password is not valid, display a generic error message
                throw new Exception("Invalid username or password.");
            }
        } catch (Exception $e) {
            // Log the error
            error_log("Failed to log the user in: " . $e->getMessage());
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
            $userRoles[] = $role->getRoleById($roleId['role_id']);
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
            $user[] = $row;
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

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $username = $row['username'];
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
    public function addUser(string $email, string $first_name, string $last_name, string $password, string $username): void
    {
        //hash the password
        $password = $this->hashPassword($password);

        //get current timestamp
        $timestamp = date("Y-m-d H:i:s");

        //SQL statement to add a user with current timestamp as created_at and updated_at
        $sql = "INSERT INTO users (email, first_name, last_name, password, username, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("sssssss", $email, $first_name, $last_name, $password, $username, $timestamp, $timestamp);

        //Execute the statement
        $stmt->execute();
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
    public function updateUser(int $id, string $email, string $first_name, string $last_name, string $password, string $username): void
    {
        //hash the password
        $password = $this->hashPassword($password);

        //get current timestamp
        $timestamp = date("Y-m-d H:i:s");

        //SQL statement to update a user
        $sql = "UPDATE users SET email = ?, first_name = ?, last_name = ?, password = ?, username = ?, updated_at = ? WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("ssssssi", $email, $first_name, $last_name, $password, $username, $timestamp, $id);

        //Execute the statement
        $stmt->execute();
    }

    //Add a role to a user
    public function giveRoleToUser(int $user_id, int $role_id): void
    {
        //get current timestamp
        $timestamp = date("Y-m-d H:i:s");

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
            $stmt->bind_param("iiss", $user_id, $role_id, $timestamp, $timestamp);

            //Execute the statement
            $stmt->execute();
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
}
