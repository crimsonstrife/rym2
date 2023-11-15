<?php

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

// Initialize the session
session_start();

define('CAN_INCLUDE', true); // Define a constant to control access to the include files

// Include config file
require_once(__DIR__ . '../../config/app.php');

//check post request for login status and uid.
if (isset($_GET['login']) && isset($_GET['u'])) {
    switch ($_GET['login']) {
        case 'success':
            //set the login status to true
            $_SESSION['logged_in'] = true;
            //set the user id
            $_SESSION['user_id'] = base64_decode($_GET['u']);
            break;
        default:
            //set the login status to false
            $_SESSION['logged_in'] = false;
            //set the user id to null
            $_SESSION['user_id'] = null;
            break;
    }
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        //include the validation file
        require_once(__DIR__ . '../../includes/validateCookieSession.inc.php');
        $logged_in = true;
    } else {
        $logged_in = false;
    }
}

//if the user is not logged in, redirect to the login page
if (isset($_SESSION['user_id'])) {
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
    if (isset($_GET['view'])) {
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
    } else {
        include_once('admin_content.php');
    }

    //include the footer
    include_once('footer.php');
} else {
    performRedirect('/login.php');
}
