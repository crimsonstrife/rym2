<?php
/**
 * User Login Class file for the College Recruitment Application
 * Contains all the functions for the User Login Class and handles all the user related tasks with the database.
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/16/2023
 *
 * @package RYM2
 * Filename: users.inc.php
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
     * @return void
     */
    public function login($username, $password)
    {
        //try to login
        try {
            //get the user ID by username
            $userID = $this->getUserIdByUsername($username);

            //get the user's hashed password
            $userPassword = $this->getUserPassword($userID);

            //check if the password is correct
            if (password_verify($password, $userPassword)) {
                // Store data in session variables
                $session = new Session();

                // Set session variables
                $session->set("logged_in", true);
                $session->set("user_id", $userID);
                $session->set("username", $username);

                //log the activity
                $activity = new Activity();
                $activity->logActivity(null, "Login Success", 'User ID: ' . strval($userID) . ' Username: ' . $username . ' logged in successfully.');

                // Redirect user to the dashboard
                performRedirect("/admin/dashboard.php");
            } else {
                //if the password is incorrect
                // log the activity
                $activity = new Activity();
                $activity->logActivity(null, "Login Error Invalid Password", 'User ID: ' . strval($userID) . ' Username: ' . $username . ' failed to log in with invalid password.');
                // Display a generic error message
                throw new Exception("Invalid username or password.");
            }
        } catch (Exception $e) {
            // log the activity
            $activity = new Activity();
            $activity->logActivity(null, "Login Error " . $e->getMessage(), 'User ID: ' . strval($userID) . ' Username: ' . $username . ' failed to log in with error message: ' . $e->getMessage());
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
        //get the auth class
        $auth = new Authenticator();

        //get the session variables
        $session = new Session();

        //expire user's token(s)
        $auth->expireTokenByUserID(intval($session->get("user_id")));

        //Get all session variables, and remove them
        $sessionVars = $session->getAll();

        //remove all session variables
        foreach ($sessionVars as $vars => $value) {
            //get the name of the session variable as a string
            $name = strval($vars);

            //remove the session variable
            $session->remove($name);
        }

        // Destroy the session.
        session_destroy();

        // Redirect to login page
        performRedirect("/login.php");
    }

    /**
     * Validate the user's password using the user's ID and the password hash
     * This is a public function that can be called from outside the class
     * @param int $userID
     * @param string $password
     * @return bool
     */
    public function validateUserPassword(int $userID, string $password): bool
    {
        //get the user's hashed password
        $userPassword = $this->getUserPassword($userID);

        //hash the password to compare
        $attemptedPassword = $password; //removed the hash function, it is not needed here as the native password_verify function will handle the hashing

        //verify the password
        if ($this->verifyPassword($attemptedPassword, $userPassword)) {
            return true;
        }

        return false;
    }
}
