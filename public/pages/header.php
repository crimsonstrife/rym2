<?php

/**
 * This is the default header for pages of the College Recruitment Application
 * Students will be able to enter their information and have it sent to the database.
 * Uses the student class to create a new student object
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/18/2023
 *
 * @package RYM2
 * Filename: header.php
 * @version 1.0.0
 * @requires PHP 7.2.5+
 * @requires Bootstrap 5.3.2+
 * @requires Font Awesome 6.4.2+
 * @requires jQuery 3.7.1+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* include the base application config file */
require_once(__DIR__ . '/../../config/app.php');

/* include autoloader */
require_once(__DIR__ . '/../../vendor/autoload.php');

use Bootstrap\Bootstrap;

/* create a new student object */

$student = new Student();

/* Setup HTML for page header */

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>
        <?php
        /* Get the APP_NAME from the constants, if not set use default */
        echo (defined('APP_NAME') ? APP_NAME : 'College Recruitment Application');
        ?> | Student Registration
    </title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo getLibraryPath(); ?>bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo getLibraryPath(); ?>fontawesome/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo getAssetPath(); ?>css/style.css">
</head>
<?php
/* End of file header.php */
/* Location: public/pages/header.php */
/* See public/pages/landing_content.php for implementation of this file */
?>
