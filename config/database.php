<?php

/**
 * Configuration File for the College Recruitment Application
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/10/2023
 *
 * Description: This file contains the database configuration information for the College Recruitment App created for The Pipe and Foundry - a mock company created for the WGU C868 course.
 *
 * @package RYM2
 * Filename: database.php
 * @version 1.0.0
 * @requires PHP 7.2.5+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.
/* Include the base application config file */
require_once(__DIR__ . '/app.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');

if (file_exists(BASEPATH . '/.env')) {
    /* Use the phpdotenv package to read the .env file */
    $dotenv = Dotenv\Dotenv::createImmutable(BASEPATH);
    $dotenv->safeLoad(); // Load the .env file if it exists.

    /* Get ENV variables, if they are not set or do not meet requirements, throw an exception */
    $dotenv->required(['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'])->notEmpty();
    $dotenv->required('DB_PORT')->isInteger();

    /* Define the database constants */
    define('DB_HOST', $_ENV['DB_HOST']); // Define the DB_HOST constant, this is the host of the database.
    define('DB_PORT', $_ENV['DB_PORT']); // Define the DB_PORT constant, this is the port of the database.
    define('DB_DATABASE', $_ENV['DB_DATABASE']); // Define the DB_DATABASE constant, this is the name of the database.
    define('DB_USERNAME', $_ENV['DB_USERNAME']); // Define the DB_USERNAME constant, this is the username of the database.
    define('DB_PASSWORD', $_ENV['DB_PASSWORD']); // Define the DB_PASSWORD constant, this is the password of the database.
} else {
    /*load the .env.example file if the .env file does not exist */
    $dotenv = Dotenv\Dotenv::createImmutable(BASEPATH, '.env.example');
    $dotenv->safeLoad();

    /* Define the database constants */
    define('DB_HOST', $_ENV['DB_HOST']); // Define the DB_HOST constant, this is the host of the database.
    define('DB_PORT', $_ENV['DB_PORT']); // Define the DB_PORT constant, this is the port of the database.
    define('DB_DATABASE', $_ENV['DB_DATABASE']); // Define the DB_DATABASE constant, this is the name of the database.
    define('DB_USERNAME', $_ENV['DB_USERNAME']); // Define the DB_USERNAME constant, this is the username of the database.
    define('DB_PASSWORD', $_ENV['DB_PASSWORD']); // Define the DB_PASSWORD constant, this is the password of the database.
}

/* Attempt to connect to the MySQL database */
try {
    $testConnection = testDatabaseConnection(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
} catch (Exception $e) {
    $mysqli = connectToDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    /* If the connection fails, log the error */
    error_log("Failed to connect to the database: (" . $mysqli->connect_errno . ")" . $mysqli->connect_error);
    //we'll do more with this later
}
/* If the connection fails, log the error */
if (!$testConnection) {
    //we'll do more with this later
}
