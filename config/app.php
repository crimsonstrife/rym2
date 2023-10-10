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
$dotenv = Dotenv\Dotenv::createImmutable(BASEPATH);
$dotenv->safeLoad(); // Load the .env file if it exists.

/* Get ENV variables, if they are not set or do not meet requirements, throw an exception */
$dotenv->required(['APP_ENV', 'APP_URL', 'APP_NAME', 'APP_DEBUG'])->notEmpty();
$dotenv->required(['APP_ENV'])->allowedValues(['LOCAL', 'PRODUCTION', 'TEST']);
$dotenv->required(['APP_DEBUG'])->isBoolean();
$dotenv->required(['APP_URL'])->allowedRegexValues('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|(([^\s()<>]+|(([^\s()<>]+)))*))+(?:(([^\s()<>]+|(([^\s()<>]+)))*)|[^\s`!()[]{};:\'\".,<>?«»“”‘’]))/');  // Regex to validate URL format, https://stackoverflow.com/questions/206059/php-validation-regex-for-url

/* Define the application constants */
define('APP_URL', $_ENV['APP_URL']); // Define the APP_URL constant, this is the root URL of the application.
define('APP_NAME', $_ENV['APP_NAME']); // Define the APP_NAME constant, this is the name of the application.
define('APP_VERSION', "1.0.0"); // Define the APP_VERSION constant, this is the version of the application.
define('APP_ENV', $_ENV['APP_ENV']); // Define the APP_ENV constant, this is the environment the application is running in i.e LOCAL, PRODUCTION, TEST.
define('APP_DEBUG', $_ENV['APP_DEBUG']); // Define the APP_DEBUG constant, this is the debug mode of the application.
