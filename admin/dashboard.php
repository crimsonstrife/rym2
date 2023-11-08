<?php

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

// Initialize the session
session_start();

define('CAN_INCLUDE', true); // Define a constant to control access to the include files

// Include config file
require_once(__DIR__ . '../../config/app.php');

// Include the authentication file
require_once(__DIR__ . '../../includes/validateCookieSession.inc.php');

//if the user is not logged in, redirect to the login page
if ($logged_in === true) {
    // Define a constant to control the access of the include files
    define('ISVALIDUSER', true); // idea from https://stackoverflow.com/a/409515 (user UnkwnTech)

    //include the header
    include_once('header.php');
?>
    <div id="layout">
    <?php
    //include the sidebar
    include_once('sidebar.php');

    //switch content based on the parameter in the url
    switch ($_GET['view']) {
        case 'events':
            include_once('events_content.php');
            break;
        case 'schools':
            include_once('schools_content.php');
            break;
        case 'majors':
            include_once('majors_content.php');
            break;
        case 'degrees':
            include_once('degrees_content.php');
            break;
        default:
            include_once('admin_content.php');
            break;
    }

    //include the footer
    include_once('footer.php');
} else {
    performRedirect('/login.php');
}
