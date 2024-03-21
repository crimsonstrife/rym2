<?php
/**
 * Session Class file for the College Recruitment Application
 * This class is used to manage session data for the application
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/16/2023
 *
 * @category Authentication
 * @package RYM2
 * Filename: session.inc.php
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
 * Session Class
 * Used to access and manage session data
 */
class Session
{
    protected $sessionVars;

    public function __construct()
    { //Get the session variables, will still trigger a phpmd warning.
        //Check if the session is not set
        if (!isset($_SESSION)) {
            //Start the session
            session_start();
            //Set the session variables
            $this->sessionVars = $_SESSION;
        } else {
            //Set the session variables
            $this->sessionVars = $_SESSION;
        }
    }

    /**
     * Set a session variable
     * @param string $name The name of the session variable
     * @param mixed $value The value of the session variable
     * @return void
     */
    public function set(string $name, $value): void
    {
        if (isset($_SESSION)) {
            //Set the session variables
            $_SESSION[$name] = $value;

            //Set the property
            $this->sessionVars = $_SESSION;
        } else {
            //Start the session
            $this->sessionVars = session_start();

            //Set the session variables
            $_SESSION[$name] = $value;

            //Set the property
            $this->sessionVars = $_SESSION;

            //debug
            error_log("Session started, variable set");
        }
    }

    /**
     * Get a session variable
     * @param string $name The name of the session variable
     * @return mixed The value of the session variable
     */
    public function get(string $name)
    {
        //Get the property
        $session = $this->sessionVars;

        //Check if the session variable exists
        if (isset($session[$name])) {
            return $session[$name];
        }

        //debug
        error_log("Session variable" . $name . "not found");
        return null;
    }

    /**
     * Check if a session variable exists
     * @param string $name The name of the session variable
     * @return bool True if the session variable exists, false if not
     */
    public function check(string $name): bool
    {
        //Get the property
        $session = $this->sessionVars;

        //Check if the session variable exists
        if (isset($session[$name])) {
            return true;
        }

        //debug
        error_log("Session variable " . $name . " not found/or not set");
        return false;
    }

    /**
     * Remove a session variable
     * @param string $name The name of the session variable
     * @return void
     */
    public function remove(string $name): void
    {
        //Get the property
        $session = $this->sessionVars;

        //Check if the session variable exists
        if (isset($session[$name])) {
            //Unset the session variable
            unset($_SESSION[$name]);

            //Set the property
            $this->sessionVars = $_SESSION;
        }

        //debug
        error_log("Session variable " . $name . " removed");
    }

    /**
     * Get all session variables
     *
     * @return array An array of all session variables
     */
    public function getAll(): array
    {
        //Get the property
        $session = $this->sessionVars;

        //Return the session variables
        return $session;
    }
}
