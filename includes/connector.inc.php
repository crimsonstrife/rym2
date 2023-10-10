<?php

/**
 * Database Connector File for the College Recruitment Application
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/10/2023
 *
 * @package RYM2
 * Filename: connector.inc.php
 * @version 1.0.0
 * @requires PHP 7.2.5+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* This function will connect to the MySQL database and return the connection object using the provided credentials */
function connectToDatabase(string $host, string $username, string $password, string $database, int $port): mysqli
{
    /* Setup the connection to MySQL */
    $mysqli = new mysqli($host, $username, $password, $database, $port);

    /* Attempt to connect to the MySQL database */
    if ($mysqli->connect_errno) {
        /* If the connection fails, throw an exception with the error */
        throw new Exception("Failed to connect to Database: (" . $mysqli->connect_errno . ")" . $mysqli->connect_error);
    }

    /* Return the connection object */
    return $mysqli;
}
