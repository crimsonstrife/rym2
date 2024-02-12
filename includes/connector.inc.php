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
        //check that all the required fields are filled in and not empty, if not return empty mysqli object
        if (empty($host) || empty($username) || empty($password) || empty($database) || empty($port)) {
            //throw an exception if the required fields are not filled in
            throw new Exception("Failed to connect to Database: Missing required connection parameters");
        } else {
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
}

/* This function will close the connection to the MySQL database */
if (!function_exists('closeDatabaseConnection')) {
    function closeDatabaseConnection(mysqli $mysqli = null): void
    {
        // If the connection is not null, close the connection
        if ($mysqli != null) {
            $mysqli->close();
        }
    }
}

/* This function just tests the connection to the MySQL database, but only returns a boolean */
if (!function_exists('testDatabaseConnection')) {
    function testDatabaseConnection(string $host, string $username, string $password, string $database, string $port): bool
    {
        //check that all the required fields are filled in and not empty, if not return false
        if (empty($host) || empty($username) || empty($password) || empty($database) || empty($port)) {
            return false;
        } else {
            // convert port to int
            $port = (int) $port;

            /* Try to connect to the database */
            try {
                $mysqli = new mysqli($host, $username, $password, $database, $port);
                //catch any errors and return false
            } catch (Exception $e) {
                // Log the error
                error_log("Failed to connect to the database: " . $e->getMessage());
                // if the database is unknown, return false
                if ($mysqli->connect_errno == 1049) {
                    //log the error
                    error_log("Database not found: " . $mysqli->connect_error);
                    return false;
                }
                return false;
            }

            /* Close the connection to the database */
            $mysqli->close();
            return true;
        }
    }
}
