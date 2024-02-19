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
 * User Login Class
 * This class is used to handle user login and logout
 *
 * @package RYM2
 * @version 1.0.0
 */
class UserLogin extends User implements Login
{
    /**
     * Login the user by username and password
     *
     * @param string $username
     * @param string $password
     * @throws \Exception
     * @return void
     */
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

            //check if the password is correct
            if (password_verify($password, $user_password)) {
                //initialize the session
                session_start();

                // Set session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["user_id"] = $user_id;
                $_SESSION["username"] = $username;

                // Redirect user to welcome page
                redirectUser(APP_URL . "/index.php");
            } else {
                // Log the error
                error_log("Failed to log the user in: Invalid password.");
                // log the activity
                $activity = new Activity();
                $activity->logActivity(null, "Login Error Invalid Password", 'User ID: ' . strval($user_id) . ' Username: ' . $username . ' failed to log in with invalid password.');
                // Display a generic error message
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

    /**
     * Logout the user
     * @return never
     */
    public function logout(): void
    {
        //initialize the session
        session_start();

        // Unset all of the session variables
        $_SESSION = array();

        // Destroy the session.
        session_destroy();

        // Redirect to login page
        redirectUser(APP_URL . "/login.php");
    }
}
