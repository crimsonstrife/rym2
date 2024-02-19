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

    //hash the password
    private function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    //verify the password
    private function verifyPassword(string $password, string $hash): bool
    {
        $isValid = password_verify($password, $hash);

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

        //hash the password to compare
        $attempted_password = $this->hashPassword($password);

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
                $activity->logActivity(null, "User logged in.", 'User ' . $this->getUserUsername($user_id));
            } else {
                // Password is not valid, display a generic error message
                throw new Exception("Invalid username or password.");
            }
        } catch (Exception $e) {
            // Log the error
            error_log("Failed to log the user in: " . $e->getMessage());
            // log the activity
            $activity = new Activity();
            $activity->logActivity(null, "Login Error " . $e->getMessage(), 'User ID: ' . strval($user_id) . ' Username: ' . $this->getUserUsername($user_id) . ' failed to log in with error message: ' . $e->getMessage());
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

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
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
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
        }
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

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {

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
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
        }
    }

    //Get a user by ID
    public function getUserById(int $id): array
    {
        //SQL statement to get the user by ID
        $sql = "SELECT * FROM users WHERE id = ?";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
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
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
        }
    }

    //Get a user's email
    public function getUserEmail(int $id): string
    {
        //SQL statement to get the user's email by ID
        $sql = "SELECT email FROM users WHERE id = ?";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
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
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
        }
    }

    /**
     * Get user by email
     *
     * @param string $email
     * @return int $user_id
     */
    public function getUserByEmail(string $email): int
    {
        //SQL statement to get the user's ID by email
        $sql = "SELECT id FROM users WHERE email = ?";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $stmt = $this->mysqli->prepare($sql);

                //Bind the email to the statement
                $stmt->bind_param("s", $email);

                //Execute the statement
                $stmt->execute();

                //Get the results
                $result = $stmt->get_result();

                //Create a variable to hold the user's ID
                $user_id = 0;

                //Loop through the results and add them to the array
                while ($row = $result->fetch_assoc()) {
                    $user_id = $row['id'];
                }

                //Return the user's ID
                return intval($user_id);
            }
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
        }
    }

    //Get a user's first name
    public function getUserFirstName(int $id): string
    {
        //SQL statement to get the user's first name by ID
        $sql = "SELECT first_name FROM users WHERE id = ?";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
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
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
        }
    }

    //Get a user's last name
    public function getUserLastName(int $id): string
    {
        //SQL statement to get the user's last name by ID
        $sql = "SELECT last_name FROM users WHERE id = ?";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
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
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
        }
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

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
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
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
        }
    }

    //Get a user's username
    public function getUserUsername(int $id): string
    {
        //SQL statement to get the user's username by ID
        $sql = "SELECT username FROM users WHERE id = ?";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
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
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
        }
    }

    //Get a user ID by username
    public function getUserIdByUsername(string $username): int
    {
        //SQL statement to get the user's ID by username
        $sql = "SELECT id FROM users WHERE username = ?";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
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
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
        }
    }

    //Set a user's email
    public function setUserEmail(int $id, string $email): void
    {
        //SQL statement to set the user's email by ID
        $sql = "UPDATE users SET email = ? WHERE id = ?";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $stmt = $this->mysqli->prepare($sql);

                //Bind the parameters to the SQL statement
                $stmt->bind_param("si", $email, $id);

                //Execute the statement
                $stmt->execute();

                // log the activity
                $activity = new Activity();
                $activity->logActivity(intval($_SESSION['user_id']), 'Updated User Email', 'User ID: ' . strval($id) . ' User Name: ' . $this->getUserUsername($id) . ' Email: ' . $email);
            }
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
        }
    }

    //Set a user's first name
    public function setUserFirstName(int $id, string $first_name): void
    {
        //SQL statement to set the user's first name by ID
        $sql = "UPDATE users SET first_name = ? WHERE id = ?";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $stmt = $this->mysqli->prepare($sql);

                //Bind the parameters to the SQL statement
                $stmt->bind_param("si", $first_name, $id);

                //Execute the statement
                $stmt->execute();

                // log the activity
                $activity = new Activity();
                $activity->logActivity(intval($_SESSION['user_id']), 'Updated User First Name', 'User ID: ' . strval($id) . ' User Name: ' . $this->getUserUsername($id) . ' First Name: ' . $first_name);
            }
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
        }
    }

    //Set a user's last name
    public function setUserLastName(int $id, string $last_name): void
    {
        //SQL statement to set the user's last name by ID
        $sql = "UPDATE users SET last_name = ? WHERE id = ?";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $stmt = $this->mysqli->prepare($sql);

                //Bind the parameters to the SQL statement
                $stmt->bind_param("si", $last_name, $id);

                //Execute the statement
                $stmt->execute();

                // log the activity
                $activity = new Activity();
                $activity->logActivity(intval($_SESSION['user_id']), 'Updated User Last Name', 'User ID: ' . strval($id) . ' User Name: ' . $this->getUserUsername($id) . ' Last Name: ' . $last_name);
            }
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
        }
    }

    //Set a user's password
    public function setUserPassword(int $id, string $password): void
    {
        //hash the password
        $password = $this->hashPassword($password);

        //SQL statement to set the user's password by ID
        $sql = "UPDATE users SET password = ? WHERE id = ?";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $stmt = $this->mysqli->prepare($sql);

                //Bind the parameters to the SQL statement
                $stmt->bind_param("si", $password, $id);

                //Execute the statement
                $stmt->execute();

                // log the activity
                $activity = new Activity();
                $activity->logActivity(intval($_SESSION['user_id']), 'Updated User Password', 'User ID: ' . strval($id) . ' User Name: ' . $this->getUserUsername($id));
            }
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
        }
    }

    //Set a user's username
    public function setUserUsername(int $id, string $username): void
    {
        //SQL statement to set the user's username by ID
        $sql = "UPDATE users SET username = ? WHERE id = ?";

        //old username
        $old_username = $this->getUserUsername($id);

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $stmt = $this->mysqli->prepare($sql);

                //Bind the parameters to the SQL statement
                $stmt->bind_param("si", $username, $id);

                //Execute the statement
                $stmt->execute();

                // log the activity
                $activity = new Activity();
                $activity->logActivity(intval($_SESSION['user_id']), 'Updated User Username', 'User ID: ' . strval($id) . ' User Name: ' . $username . ' Old Username: ' . $old_username);
            }
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
        }
    }

    //Add a user
    private function addUser(string $email, string $password, string $username, int $created_by = null): int
    {
        //hash the password
        $password = $this->hashPassword($password);

        //get current timestamp
        $timestamp = date("Y-m-d H:i:s");

        //SQL statement to add a user with current timestamp as created_at and updated_at
        $sql = "INSERT INTO users (email, password, username, created_at, updated_at, created_by, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?)";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {

                //Prepare the SQL statement for execution
                $stmt = $this->mysqli->prepare($sql);

                //Bind the parameters to the SQL statement
                $stmt->bind_param("sssssii", $email, $password, $username, $timestamp, $timestamp, $created_by, $created_by);

                //Execute the statement
                $stmt->execute();

                //count the number of rows affected
                $rows = $stmt->affected_rows;

                //if the number of rows affected is greater than 0, return the user ID
                if ($rows > 0) {
                    //get the user ID
                    $user_id = $stmt->insert_id;

                    //log the user activity
                    $activity = new Activity();
                    $activity->logActivity(intval($_SESSION['user_id']), 'Added User', 'User Name: ' . $username . ' with User ID: ' . strval($user_id));

                    //return the user ID as an integer
                    return intval($user_id);
                } else {
                    return 0;
                }
            }
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
        }
    }

    //Delete a user
    public function deleteUser(int $user_id): bool
    {
        //get the current date and time
        $date = date("Y-m-d H:i:s");

        //get the name of the user
        $user_name = $this->getUserUsername($user_id);

        //set the placeholder for the result
        $result = false;

        //create the sql statement
        $sql = "DELETE FROM users WHERE id = ?";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //prepare the statement
                $stmt = $this->mysqli->prepare($sql);

                //bind the parameters
                $stmt->bind_param("i", $user_id);

                //execute the statement
                $stmt->execute();

                //check the result
                if ($stmt->affected_rows > 0) {
                    $result = true;
                } else {
                    $result = false;
                }

                //log the user activity if the user was deleted
                if ($result) {
                    $activity = new Activity();
                    $activity->logActivity(intval($_SESSION['user_id']), 'Deleted User', 'User ID: ' . $user_id . ' User Name: ' . $user_name);
                }

                //return the result
                return $result;
            }
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
        }
    }

    //Update a user
    public function setUserInfo(int $id, string $email = null, string $password = null, string $username = null, int $updated_by): void
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

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $stmt = $this->mysqli->prepare($sql);

                //Bind the parameters to the SQL statement
                $stmt->bind_param("ssssii", $email, $password, $username, $date, $updated_by, $id);

                //Execute the statement
                $stmt->execute();

                //get the number of rows affected, if the number of rows affected is greater than 0, the user was updated
                if ($stmt->affected_rows > 0) {
                    // log the activity
                    $activity = new Activity();
                    $activity->logActivity($updated_by, "User Updated.", 'User: ' . $username . ' updated by User: ' . strval($updated_by));
                } else {
                    //if the number of rows affected is 0, the user was not updated
                    // log the activity
                    $activity = new Activity();
                    $activity->logActivity($updated_by, "User Update Failed.", 'User: ' . $username . ' failed to update by User: ' . strval($updated_by));
                }
            }
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
        }
    }

    //Add a role to a user
    private function giveRoleToUser(int $user_id, int $role_id): void
    {
        //get current date and time
        $date = date("Y-m-d H:i:s");

        //boolean to check if the role exists
        $roleExists = false;

        //validate a role exists by ID
        $role = new Roles();
        $roleExists = $role->validateRoleById($role_id);

        //get role name
        $role_name = $role->getRoleNameById($role_id);

        //if the role exists, add it to the user
        if ($roleExists == true) {
            //SQL statement to add a role to a user
            $sql = "INSERT INTO user_has_role (user_id, role_id, created_at, updated_at) VALUES (?, ?, ?, ?)";

            //Check that mysqli is set
            if (isset($this->mysqli)) {
                //check that the mysqli object is not null
                if ($this->mysqli->connect_error) {
                    print_r($this->mysqli->connect_error);
                    //log the error
                    error_log('Error: ' . $this->mysqli->connect_error);
                    //throw an exception
                    throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
                } else {
                    //Prepare the SQL statement for execution
                    $stmt = $this->mysqli->prepare($sql);

                    //Bind the parameters to the SQL statement
                    $stmt->bind_param("iiss", $user_id, $role_id, $date, $date);

                    //Execute the statement
                    $stmt->execute();

                    // log the activity
                    $activity = new Activity();
                    $activity->logActivity(null, "User Updated", "Role ID: " . strval($role_id) . " Role Name: " . $role_name . " added to User ID: " . strval($user_id) . " User Name: " . $this->getUserUsername($user_id) . "");
                }
            } else {
                //if the mysqli object is null, throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            }
        } else {
            //if the role does not exist, throw an exception
            throw new Exception("Role does not exist.");
        }
    }

    //Remove a role from a user
    private function removeRoleFromUser(int $user_id, int $role_id): void
    {
        //SQL statement to remove a role from a user
        $sql = "DELETE FROM user_has_role WHERE user_id = ? AND role_id = ?";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $stmt = $this->mysqli->prepare($sql);

                //Bind the parameters to the SQL statement
                $stmt->bind_param("ii", $user_id, $role_id);

                //Execute the statement
                $stmt->execute();

                // log the activity
                $activity = new Activity();
                $activity->logActivity(null, "User Updated", "Role ID: " . strval($role_id) . " removed from User ID: " . strval($user_id) . " User Name: " . $this->getUserUsername($user_id) . "");
            }
        } else {
            //if the mysqli object is null, throw an exception
            throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
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
    private function notifyUserCreated(string $email, string $username, string $password): bool
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
        $email = $this->getEmailIfNull($email, $id);
        $username = $this->getUsernameIfNull($username, $id);
        $password = $this->getPasswordIfNull($password, $id);

        $this->setUserInfo($id, $email, $password, $username, $updated_by);
        $this->assignOrRemoveRoles($id, $roles);

        $this->logUpdateActivity($updated_by, $username);

        return true;
    }

    /**
     * Get a user's email
     *
     * @param int $id The user ID of the user to get the email for
     *
     * @return string The user's email
     */
    private function getEmailIfNull(?string $email, int $id): string
    {
        if ($email === null || empty($email) || $email === "") {
            return $this->getUserEmail($id);
        }

        return trim($email);
    }

    /**
     * Get a user's username
     *
     * @param int $id The user ID of the user to get the username for
     *
     * @return string The user's username
     */
    private function getUsernameIfNull(?string $username, int $id): string
    {
        if ($username === null || empty($username) || $username === "") {
            return $this->getUserUsername($id);
        }

        return trim($username);
    }

    /**
     * Get a user's password
     *
     * @param int $id The user ID of the user to get the password for
     *
     * @return string The user's password
     */
    private function getPasswordIfNull(?string $password, int $id): string
    {
        if ($password === null || empty($password) || $password === "") {
            return $this->getUserPassword($id);
        }

        return $password;
    }

    /**
     * Assign or remove roles from a user
     *
     * @param int $id The user ID of the user to assign or remove roles from
     * @param array $roles The roles to assign to the user
     */
    private function assignOrRemoveRoles(int $id, array $roles): void
    {
        $currentRoleIDs = $this->getCurrentRoleIDs($id);

        if (!empty($roles)) {
            $this->assignRoles($id, $roles, $currentRoleIDs);
            $this->removeRoles($id, $roles, $currentRoleIDs);
        } else {
            $this->removeAllRoles($id, $currentRoleIDs);
        }
    }

    /**
     * Get the current roles for a user
     *
     * @param int $id The user ID of the user to get the roles for
     *
     * @return array The current roles for the user
     */
    private function getCurrentRoleIDs(int $id): array
    {
        $currentRoles = $this->getUserRoles($id);
        $currentRoleIDs = [];

        foreach ($currentRoles as $role) {
            $currentRoleIDs[] = $role['id'];
        }

        return $currentRoleIDs;
    }

    /**
     * Assign roles to a user
     *
     * @param int $id The user ID of the user to assign roles to
     * @param array $roles The roles to assign to the user
     * @param array $currentRoleIDs The current roles for the user
     */
    private function assignRoles(int $id, array $roles, array $currentRoleIDs): void
    {
        foreach ($roles as $role) {
            if (!in_array($role, $currentRoleIDs)) {
                $this->giveRoleToUser($id, intval($role));
            }
        }
    }

    /**
     * Remove roles from a user
     *
     * @param int $id The user ID of the user to remove roles from
     * @param array $roles The roles to remove from the user
     * @param array $currentRoleIDs The current roles for the user
     */
    private function removeRoles(int $id, array $roles, array $currentRoleIDs): void
    {
        foreach ($currentRoleIDs as $currentRole) {
            if (!in_array($currentRole, $roles)) {
                $this->removeRoleFromUser($id, intval($currentRole));
            }
        }
    }

    /**
     * Remove all roles from a user
     *
     * @param int $id The user ID of the user to remove all roles from
     * @param array $currentRoleIDs The current roles for the user
     */
    private function removeAllRoles(int $id, array $currentRoleIDs): void
    {
        foreach ($currentRoleIDs as $currentRole) {
            $this->removeRoleFromUser($id, intval($currentRole));
        }
    }

    /**
     * Log the user update activity
     *
     * @param int $updated_by The user ID of the user who updated the user
     * @param string $username The username of the user who was updated
     */
    private function logUpdateActivity(?int $updated_by, string $username): void
    {
        $activity = new Activity();
        $activity->logActivity($updated_by, "User Updated.", 'User: ' . $username . ' updated by User: ' . strval($updated_by));
    }

}
