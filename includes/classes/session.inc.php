<?php

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
 * @package RYM2
 */
class Session
{
    public $sessionVars;

    public function __construct()
    { //Get the session variables, will still trigger a phpmd warning.
        //Check if the session is not set
        if (!isset($_SESSION)) {
            //Start the session
            session_start();
            //Set the session variables
            $this->sessionVars = $_SESSION;
            //debug
            error_log("Session started");
        } else {
            //Set the session variables
            $this->sessionVars = $_SESSION;
            //debug
            error_log("Session already started");
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
            $_SESSION[$name] = $value;
        } else {
            //Start the session
            $this->sessionVars = session_start();
            //Set the session variables
            $_SESSION[$name] = $value;
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
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
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
        if (isset($_SESSION[$name])) {
            return true;
        }

        //debug
        error_log("Session variable" . $name . "not found/or not set");
        return false;
    }

    /**
     * Remove a session variable
     * @param string $name The name of the session variable
     * @return void
     */
    public function remove(string $name): void
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
    }
}
