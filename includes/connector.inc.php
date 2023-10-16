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
if (!function_exists('connectToDatabase')) {
    function connectToDatabase(string $host, string $username, string $password, string $database, string $port): mysqli
    {
        //convert port to int
        $port = (int) $port;
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
}

/* This function will close the connection to the MySQL database */
if (!function_exists('closeDatabaseConnection')) {
    function closeDatabaseConnection(mysqli $mysqli): void
    {
        /* Close the connection to the database */
        $mysqli->close();
    }
}

/* This function just tests the connection to the MySQL database, but only returns a boolean */
function testDatabaseConnection(string $host, string $username, string $password, string $database, string $port): bool
{
    // convert port to int
    $port = (int) $port;

    /* Try to connect to the database */
    try {
        $mysqli = new mysqli($host, $username, $password, $database, $port);
        //catch any errors and return false
    } catch (Exception $e) {
        // Log the error
        error_log("Failed to connect to the database: (" . $mysqli->connect_errno . ")" . $mysqli->connect_error);
        // if the database is unknown, return false
        if ($mysqli->connect_errno == 1049) {
            return false;
        }
        return false;
    }

    /* Close the connection to the database */
    $mysqli->close();
    return true;
}
