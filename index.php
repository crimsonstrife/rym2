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

/* Check if the setup.php file exists */
if (!file_exists(BASEPATH . '/ready.php')) {
    /* If the setup.php file exists, launch the installation process */
    require_once(BASEPATH . '/setup.php');
    die();
} else {
    /* If the setup.php file does not exist, launch the application */
    require_once(BASEPATH . '/public/index.php');
}