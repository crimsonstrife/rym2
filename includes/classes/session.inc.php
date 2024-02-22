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
    {
        $this->sessionVars = $_SESSION; //Get the session variables, will still trigger a phpmd warning.
        //Check if the session is not set
        if (!isset($_SESSION)) {
            //Start the session
            session_start();
        } else {
            //Get the session variables
            $this->sessionVars = $_SESSION;
        }
    }
}