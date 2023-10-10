<?php

/**
 * Configuration File for the College Recruitment Application
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/10/2023
 *
 * Description: This file contains the main configuration information for the College Recruitment App created for The Pipe and Foundry - a mock company created for the WGU C868 course.
 *
 * @package RYM2
 * Filename: app.php
 * @version 1.0.0
 * @requires PHP 7.2.5+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.
/* Define the BASEPATH of the application, this is the root directory of the application since this file is in a subdirectory. */
define('BASEPATH', dirname(__DIR__, 1));
/* Get the composer autoloader from the vendor directory */
require_once(BASEPATH . '/vendor/autoload.php');

/* Use the phpdotenv package to read the .env file */
$dotenv = Dotenv\Dotenv::createImmutable(BASEPATH); // Create a new instance of the Dotenv class, load the .env file from the BASEPATH.
$dotenv->safeLoad(); // Load the .env file if it exists.

/* Define application constants, if they're not set in the ENV variables, set them here */
if (!isset($_ENV['APP_ENV'])) {
    $_ENV['APP_ENV'] = "LOCAL";
} else if ($_ENV['APP_ENV'] != "LOCAL" && $_ENV['APP_ENV'] != "PROD" && $_ENV['APP_ENV'] != "TEST") {
    $_ENV['APP_ENV'] = "LOCAL";
}
if (!isset($_ENV['APP_URL'])) {
    if ($_ENV['ENVIRONMENT'] == "LOCAL") {
        $_ENV['APP_URL'] = "http://localhost";
    } else if ($_ENV['ENVIRONMENT'] == "PROD") {
        $_ENV['APP_URL'] = "https://capstone.hostedprojects.net";
    } else if ($_ENV['ENVIRONMENT'] == "TEST") {
        $_ENV['APP_URL'] = "https://testing.hostedprojects.net";
    }
}
if (!isset($_ENV['APP_NAME'])) {
    $_ENV['APP_NAME'] = "College Recruitment Application";
}
if (!isset($_ENV['APP_DEBUG'])) {
    $_ENV['APP_DEBUG'] = false;
}
$dotenv->required(['APP_ENV', 'APP_URL', 'APP_NAME', 'APP_DEBUG']);
define('APP_URL', $_ENV['APP_URL']); // Define the APP_URL constant, this is the root URL of the application.
define('APP_NAME', $_ENV['APP_NAME']); // Define the APP_NAME constant, this is the name of the application.
define('APP_VERSION', "1.0.0"); // Define the APP_VERSION constant, this is the version of the application.
define('APP_ENV', $_ENV['APP_ENV']); // Define the APP_ENV constant, this is the environment the application is running in i.e LOCAL, PROD, TEST.
define('APP_DEBUG', $_ENV['APP_DEBUG']); // Define the APP_DEBUG constant, this is the debug mode of the application.
