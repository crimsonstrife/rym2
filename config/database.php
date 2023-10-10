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

/* Define the database constants, if they're not set in the ENV variables, set them here */
if (!isset($_ENV['DB_HOST'])) {
    $_ENV['DB_HOST'] = "localhost";
}
if (!isset($_ENV['DB_PORT'])) {
    $_ENV['DB_PORT'] = "3306";
}
if (!isset($_ENV['DB_DATABASE'])) {
    $_ENV['DB_DATABASE'] = "capstone";
}
if (!isset($_ENV['DB_USERNAME'])) {
    $_ENV['DB_USERNAME'] = "capstone";
}
if (!isset($_ENV['DB_PASSWORD'])) {
    $_ENV['DB_PASSWORD'] = "capstone";
}
$dotenv->required(['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD']);
