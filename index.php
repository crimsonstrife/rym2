<?php

/**
 * Index File for the College Recruitment Application
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/10/2023
 *
 * Description: main index file for the College Recruitment App, it will launch the installation process if the install script still exists, otherwise it will launch the application.
 *
 * @package RYM2
 * Filename: index.php
 * @version 1.0.0
 * @requires PHP 7.2.5+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* include the base application config file */
require_once(__DIR__ . '/config/app.php');

/* Check if the ready.php file exists */
if (!file_exists(BASEPATH . '/ready.php')) {
    /* If the ready.php file doesn't exist, launch the installation process */
    //check that setup.php exists
    if (file_exists(BASEPATH . '/setup.php')) {
        //include the setup file
        require_once(BASEPATH . '/setup.php');
    } else {
        //set the error type
        $thisError = 'CRITICAL';

        //include the error message file
        include_once(BASEPATH . '/includes/errors/errorMessage.inc.php');
    }
} else {
    //check the url for an event slug
    if (isset($_GET['event'])) {
        $event_slug = $_GET['event'];
    }

    /* If the ready.php file does not exist, launch the application */
    require_once(BASEPATH . '/public/index.php');
}
