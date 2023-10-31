<?php

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

// Initialize the session
session_start();

// Include config file
require_once(__DIR__ . '../../config/app.php');

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("location: " . APP_URL . "/login.php");
    exit;
}

// Include the admin content
include_once('admin_content.php');
