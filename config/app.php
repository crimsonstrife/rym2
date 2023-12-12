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
 * @requires Bootstrap 5.3.2+
 * @requires Font Awesome 6.4.2+
 * @requires jQuery 3.7.1+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.
/* Define the BASEPATH of the application, this is the root directory of the application since this file is in a subdirectory. */

define('BASEPATH', dirname(__DIR__, 1));
/* Get the composer autoloader from the vendor directory */
require_once(BASEPATH . '/vendor/autoload.php');
/* Get the constants file */
require_once(BASEPATH . '/includes/constants.php');
/* Get the helpers file */
require_once(BASEPATH . '/includes/utils/helpers.php');

if (file_exists(BASEPATH . '/.env')) {
    /* Use the phpdotenv package to read the .env file */
    $dotenv = Dotenv\Dotenv::createImmutable(BASEPATH);
    $dotenv->safeLoad(); // Load the .env file if it exists.

    /* Get ENV variables, if they are not set or do not meet requirements, throw an exception */
    $dotenv->required(['APP_ENV', 'APP_URL', 'APP_NAME', 'APP_DEBUG'])->notEmpty();
    $dotenv->required(['APP_ENV'])->allowedValues(['LOCAL', 'PRODUCTION', 'TEST']);
    $dotenv->required(['APP_DEBUG'])->allowedValues(['true', 'false', '1', '0', 'TRUE', 'FALSE']);
    $dotenv->required(['APP_URL'])->allowedRegexValues('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|(([^\s()<>]+|(([^\s()<>]+)))*))+(?:(([^\s()<>]+|(([^\s()<>]+)))*)|[^\s`!()[]{};:\'\".,<>?«»“”‘’]))/');  // Regex to validate URL format, https://stackoverflow.com/questions/206059/php-validation-regex-for-url

    /* Define the application constants */
    define('APP_URL', $_ENV['APP_URL']); // Define the APP_URL constant, this is the root URL of the application.
    define('APP_NAME', $_ENV['APP_NAME']); // Define the APP_NAME constant, this is the name of the application.
    define('APP_VERSION', "1.0.0"); // Define the APP_VERSION constant, this is the version of the application.
    define('APP_ENV', $_ENV['APP_ENV']); // Define the APP_ENV constant, this is the environment the application is running in i.e LOCAL, PRODUCTION, TEST.
    define('APP_DEBUG', $_ENV['APP_DEBUG']); // Define the APP_DEBUG constant, this is the debug mode of the application.
} else {
    /*load the .env.example file if the .env file does not exist */
    $dotenv = Dotenv\Dotenv::createImmutable(BASEPATH, '.env.example');
    $dotenv->safeLoad();

    /* Define the application constants */
    define('APP_URL', $_ENV['APP_URL']); // Define the APP_URL constant, this is the root URL of the application.
    define('APP_NAME', $_ENV['APP_NAME']); // Define the APP_NAME constant, this is the name of the application.
    define('APP_VERSION', "1.0.0"); // Define the APP_VERSION constant, this is the version of the application.
    define('APP_ENV', $_ENV['APP_ENV']); // Define the APP_ENV constant, this is the environment the application is running in i.e LOCAL, PRODUCTION, TEST.
    define('APP_DEBUG', $_ENV['APP_DEBUG']); // Define the APP_DEBUG constant, this is the debug mode of the application.
}

/**
 * Get the asset path for the application
 * Returns the asset path for the application with trailing slash
 *
 * @return string asset path for the application
 */
function getAssetPath(): string
{
    /* Define the asset path for the application */
    $assetPath = APP_URL . '/public/content/assets/';

    /* Return the asset path */
    return $assetPath;
}

/**
 * Get the library path for the application
 * Returns the library path for the application with trailing slash
 *
 * @return string library path for the application
 */
function getLibraryPath(): string
{
    /* Define the library path for the application */
    $libraryPath = APP_URL . '/public/content/libs/';

    /* Return the library path */
    return $libraryPath;
}

/**
 * Get vendor path for the application
 * Returns the vendor path for the application with trailing slash
 *
 * @return string vendor path for the application
 */
function getVendorPath(): string
{
    /* Define the vendor path for the application */
    $vendorPath = BASEPATH . '/vendor/';

    /* Return the vendor path */
    return $vendorPath;
}

/**
 * Validate email address
 * Returns true if the email address is valid, false if not
 *
 * @param string $email email address to validate
 * @return bool true if the email address is valid, false if not
 */
function validateEmail(string $email): bool
{
    /* Validate the email address */
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        /* Return true if the email address is valid */
        return true;
    } else {
        /* Return false if the email address is not valid */
        return false;
    }
}

/**
 * Validate phone number
 * Returns true if the phone number is valid, false if not
 *
 * @param string $phone phone number to validate
 * @return bool true if the phone number is valid, false if not
 */
function validatePhone(string $phone): bool
{
    /* Validate the phone number */
    if (preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $phone)) {
        /* Return true if the phone number is valid */
        return true;
    } else {
        /* Return false if the phone number is not valid */
        return false;
    }
}

/**
 * Validate zip code
 * Returns true if the zip code is valid, false if not
 *
 * @param string $zip zip code to validate
 * @return bool true if the zip code is valid, false if not
 */
function validateZip(string $zip): bool
{
    /* Validate the zip code */
    if (preg_match("/^[0-9]{5}$/", $zip)) {
        /* Return true if the zip code is valid */
        return true;
    } else {
        /* Return false if the zip code is not valid */
        return false;
    }
}

/**
 * Validate URL
 *
 * @param string $url URL to validate
 * @return bool true if the URL is valid, false if not
 */
function validateUrl(string $url): bool
{
    /* Validate the URL */
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        /* Return true if the URL is valid */
        return true;
    } else {
        /* Return false if the URL is not valid */
        return false;
    }
}

/**
 * Validate Timestamp
 *
 * @param string $timestamp timestamp to validate
 * @return bool true if the timestamp is valid, false if not
 */
function validateTimestamp(string $timestamp): bool
{
    /* Validate the timestamp */
    if (strtotime($timestamp)) {
        /* Return true if the timestamp is valid */
        return true;
    } else {
        /* Return false if the timestamp is not valid */
        return false;
    }
}

/**
 * Validate Date
 *
 * @param string $date date to validate
 * @return bool true if the date is valid, false if not
 */
function validateDate(string $date): bool
{
    /* Validate the date */
    if (strtotime($date)) {
        /* Return true if the date is valid */
        return true;
    } else {
        /* Return false if the date is not valid */
        return false;
    }
}

/**
 * Prepare data for insertion
 * need to verify proper data formats, and escape special characters, trim strings, etc
 * Returns the prepared data
 */
function prepareData($data)
{
    /* if string, trim and escape special characters */
    if (is_string($data)) {
        $data = trim($data);
        $data = htmlspecialchars($data);
    }
    /* if array, loop through and trim and escape special characters */
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = trim($value);
            $data[$key] = htmlspecialchars($value);
        }
    }
    /* if date, validate and format */
    if (validateDate($data)) {
        $data = date('Y-m-d', strtotime($data));
    }
    /* if timestamp, validate and format */
    if (validateTimestamp($data)) {
        $data = date('Y-m-d H:i:s', strtotime($data));
    }
    /* return the prepared data */
    return $data;
}

/**
 * Include the header for the application
 * Returns the header for the application
 *
 * @return string header for the application
 */
function includeHeader(): string
{
    /* CSS for the application */
    $boostrapCSS = '<link rel="stylesheet" href="' . getLibraryPath() . 'bootstrap/css/bootstrap.min.css">';
    $datatablesCSS = '<link rel="stylesheet" href="' . getLibraryPath() . 'simple-datatables/style.css">';
    $select2CSS = '<link rel="stylesheet" href="' . getLibraryPath() . 'select2/css/select2.min.css">';
    $fontawesomeCSS = '<link rel="stylesheet" href="' . getLibraryPath() . 'fontawesome/css/all.min.css">';
    $CSS = '<link rel="stylesheet" href="' . getAssetPath() . 'css/style.css">';

    /* JS that needs to be loaded in the header */

    /* Assemble the header for the application */
    $header = $boostrapCSS . $datatablesCSS . $select2CSS . $fontawesomeCSS . $CSS;

    /* Return the header for the application */
    return $header;
}

/**
 * Include the footer for the application
 * Returns the footer for the application
 *
 * @return string footer for the application
 */
function includeFooter(): string
{
    /* JS for the application */
    $boostrapJS = '<script type="text/javascript" src="' . getLibraryPath() . 'bootstrap/js/bootstrap.bundle.min.js"></script>';
    $fontawesomeJS = '<script type="text/javascript" src="' . getLibraryPath() . 'fontawesome/js/all.min.js"></script>';
    $jQuery = '<script type="text/javascript" src="' . getLibraryPath() . 'jquery/jquery.min.js"></script>';
    $jqueryMigrate = '<script type="text/javascript" src="' . getLibraryPath() . 'jquery-migrate/jquery-migrate.min.js"></script>';
    $datatablesJS = '<script type="text/javascript" src="' . getLibraryPath() . 'simple-datatables/umd/simple-datatables.js"></script>';
    $chartJS = '<script type="text/javascript" src="' . getLibraryPath() . 'chart.js/chart.umd.js"></script>';
    $tether = '<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>';
    $select2JS = '<script type="text/javascript" src="' . getLibraryPath() . 'select2/js/select2.min.js"></script>';
    $JS = '<script type="text/javascript" src="' . getAssetPath() . 'js/scripts.js"></script>';
    $dataTableJS = '<script type="text/javascript" src="' . getLibraryPath() . 'datatable-master/js/datatable.min.js"></script>';
    $dataTableJqueryJS = '<script type="text/javascript" src="' . getLibraryPath() . 'datatable-master/js/datatable.jquery.min.js"></script>';

    $jqueryNoConflict = '<script>var $j = jQuery.noConflict();</script>';

    /* Assemble the footer for the application */
    $footer = $jQuery . $jqueryMigrate . $datatablesJS . $tether . $select2JS . $chartJS . $dataTableJS . $dataTableJqueryJS . $boostrapJS . $fontawesomeJS . $JS;

    /* Return the footer for the application */
    return $footer;
}

/**
 * Automated emails function
 * E.x Sends a welcome email to the student when they register, also notifies the admin that a new student has registered
 *
 * @param string $email email address to send the email to
 * @param string $name name of the student
 * @param string $message message to send to the student
 * @return bool true if the email was sent, false if not
 */
function sendAutoEmail(string $email, string $name, string $subject, string $message): bool
{
    //Create a new PHPMailer instance
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    //Tell what protocol to use
    $mail->Mailer = $_ENV['MAIL_MAILER'];
    //Set the hostname of the mail server
    $mail->Host = $_ENV['MAIL_HOST'];
    //Set the port number - likely to be 25, 465 or 587
    $mail->Port = $_ENV['MAIL_PORT'];
    //Set if authentication is required
    $mail->SMTPAuth = $_ENV['MAIL_AUTH_REQ'];

    //Set the encryption to use
    $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];

    //if authentication is required, set the username and password
    if ($_ENV['MAIL_AUTH_REQ'] == true) {
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
    } else if ($_ENV['MAIL_AUTH_REQ'] == false) {
        $mail->Username = null;
        $mail->Password = null;
    }

    //Set who the message is to be sent from (the server will need to be configured to authenticate with this address, or to have send as permissions)
    $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);

    //Set who the message is to be sent to
    $mail->addAddress($email, $name);

    //Set the subject line
    $mail->Subject = $subject;

    //Don't use HTML
    $mail->isHTML(false);

    //Set the body
    $mail->Body = $message;

    //send the message, check for errors
    if (!$mail->send()) {
        //if there is an error, return false
        return false;
    } else {
        //if there is no error, return true
        return true;
    }
}

/**
 * Function to turn a provided string, such as a page title, into a slug for use in the URL
 *
 * @param string $string string to convert to a slug
 * @return string slug
 */
function toSlug(string $string): string
{
    //replace spaces with hyphens
    $string = str_replace(' ', '-', $string);
    //convert to ASCII
    $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    //remove any special characters
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    //convert to lowercase
    $string = strtolower($string);
    //return the slug
    return $string;
}
