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

/* Get ENV variables, if they are not set or do not meet requirements, throw an exception */
$dotenv->required(['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'])->notEmpty();

/* Define the database constants */
define('DB_HOST', $_ENV['DB_HOST']); // Define the DB_HOST constant, this is the host of the database.
define('DB_PORT', $_ENV['DB_PORT']); // Define the DB_PORT constant, this is the port of the database.
define('DB_DATABASE', $_ENV['DB_DATABASE']); // Define the DB_DATABASE constant, this is the name of the database.
define('DB_USERNAME', $_ENV['DB_USERNAME']); // Define the DB_USERNAME constant, this is the username of the database.
define('DB_PASSWORD', $_ENV['DB_PASSWORD']); // Define the DB_PASSWORD constant, this is the password of the database.
