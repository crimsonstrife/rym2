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

/* include the application class */
$APP = new Application();

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
    //Check if the app_url is set in the database, if not set it to the value in the .env file
    if ($APP->getAppUrl() != null || $APP->getAppUrl() != '') {
        //define the app_url constant
        define('APP_URL', $APP->getAppUrl());
    } else {
        define('APP_URL', $_ENV['APP_URL']);
    } // Define the APP_URL constant, this is the root URL of the application.
    //Check if the app_name is set in the database, if not set it to the value in the .env file
    if ($APP->getAppName() != null || $APP->getAppName() != '') {
        //define the app_name constant
        define('APP_NAME', $APP->getAppName());
    } else {
        define('APP_NAME', $_ENV['APP_NAME']);
    } // Define the APP_NAME constant, this is the name of the application.
    define('APP_VERSION', "1.0.0"); // Define the APP_VERSION constant, this is the version of the application.
    define('APP_ENV', $_ENV['APP_ENV']); // Define the APP_ENV constant, this is the environment the application is running in i.e LOCAL, PRODUCTION, TEST.
    define('APP_DEBUG', $_ENV['APP_DEBUG']); // Define the APP_DEBUG constant, this is the debug mode of the application.
    define('OPENSSL_INSTALLED', extension_loaded('openssl')); // Define the OPENSSL_INSTALLED constant, this is whether or not the openssl extension is installed.

    /* Define the mail constants */
    //Check if the mail_mailer is set in the database, if not set it to the value in the .env file
    if ($APP->getMailerType() != null || $APP->getMailerType() != '') {
        //define the mail_mailer constant
        define('MAIL_MAILER', $APP->getMailerType());
    } else {
        define('MAIL_MAILER', $_ENV['MAIL_MAILER']);
    } // Define the MAIL_MAILER constant, this is the mail protocol to use.
    //Check if the mail_host is set in the database, if not set it to the value in the .env file
    if ($APP->getMailerHost() != null || $APP->getMailerHost() != '') {
        //define the mail_host constant
        define('MAIL_HOST', $APP->getMailerHost());
    } else {
        define('MAIL_HOST', $_ENV['MAIL_HOST']);
    } // Define the MAIL_HOST constant, this is the hostname of the mail server.
    //Check if the mail_port is set in the database, if not set it to the value in the .env file
    if ($APP->getMailerPort() != null || $APP->getMailerPort() != '') {
        //define the mail_port constant
        define('MAIL_PORT', $APP->getMailerPort());
    } else {
        define('MAIL_PORT', $_ENV['MAIL_PORT']);
    } // Define the MAIL_PORT constant, this is the port number of the mail server.
    //Check if the mail_auth_req is set in the database, if not set it to the value in the .env file
    if ($APP->getMailerAuthRequired() != null || $APP->getMailerAuthRequired() != '') {
        //if true, set to string true, if false, set to string false
        if ($APP->getMailerAuthRequired() == true) {
            $required = 'true';
        } else if ($APP->getMailerAuthRequired() == false) {
            $required = 'false';
        }
        //define the mail_auth_req constant
        define('MAIL_AUTH_REQ', $required);
    } else {
        define('MAIL_AUTH_REQ', $_ENV['MAIL_AUTH_REQ']);
    } // Define the MAIL_AUTH_REQ constant, this is whether or not authentication is required.
    //Check if the mail_encryption is set in the database, if not set it to the value in the .env file
    if ($APP->getMailerEncryption() != null || $APP->getMailerEncryption() != '') {
        //define the mail_encryption constant
        define('MAIL_ENCRYPTION', $APP->getMailerEncryption());
    } else {
        define('MAIL_ENCRYPTION', $_ENV['MAIL_ENCRYPTION']);
    } // Define the MAIL_ENCRYPTION constant, this is the encryption to use.
    //Check if the mail_username is set in the database, if not set it to the value in the .env file
    if ($APP->getMailerUsername() != null || $APP->getMailerUsername() != '') {
        //define the mail_username constant
        define('MAIL_USERNAME', $APP->getMailerUsername());
    } else {
        define('MAIL_USERNAME', $_ENV['MAIL_USERNAME']);
    } // Define the MAIL_USERNAME constant, this is the username to use for authentication.
    //Check if the mail_password is set in the database, if not set it to the value in the .env file
    if ($APP->getMailerPassword() != null || $APP->getMailerPassword() != '') {
        //define the mail_password constant
        define('MAIL_PASSWORD', $APP->getMailerPassword());
    } else {
        define('MAIL_PASSWORD', $_ENV['MAIL_PASSWORD']);
    } // Define the MAIL_PASSWORD constant, this is the password to use for authentication.
    //Check if the mail_from_address is set in the database, if not set it to the value in the .env file
    if ($APP->getMailerFromAddress() != null || $APP->getMailerFromAddress() != '') {
        //define the mail_from_address constant
        define('MAIL_FROM_ADDRESS', $APP->getMailerFromAddress());
    } else {
        define('MAIL_FROM_ADDRESS', $_ENV['MAIL_FROM_ADDRESS']);
    } // Define the MAIL_FROM_ADDRESS constant, this is the email address to send the email from.
    //Check if the mail_from_name is set in the database, if not set it to the value in the .env file
    if ($APP->getMailerFromName() != null || $APP->getMailerFromName() != '') {
        //define the mail_from_name constant
        define('MAIL_FROM_NAME', $APP->getMailerFromName());
    } else {
        define('MAIL_FROM_NAME', $_ENV['MAIL_FROM_NAME']);
    } // Define the MAIL_FROM_NAME constant, this is the name to send the email from.
    define('MAILER_PASSWORD_ENCRYPTION_KEY', $_ENV['MAILER_PASSWORD_ENCRYPTION_KEY']); // Define the MAILER_PASSWORD_ENCRYPTION_KEY constant, this is the encryption key to use for encrypting passwords.
} elseif (file_exists(BASEPATH . '/.env.example')) {
    /*load the .env.example file if the .env file does not exist */
    $dotenv = Dotenv\Dotenv::createImmutable(BASEPATH, '.env.example');
    $dotenv->safeLoad();

    /* Define the application constants */
    //Check if the app_url is set in the database, if not set it to the value in the .env file
    if ($APP->getAppUrl() != null || $APP->getAppUrl() != '') {
        //define the app_url constant
        define('APP_URL', $APP->getAppUrl());
    } else {
        define('APP_URL', $_ENV['APP_URL']);
    } // Define the APP_URL constant, this is the root URL of the application.
    //Check if the app_name is set in the database, if not set it to the value in the .env file
    if ($APP->getAppName() != null || $APP->getAppName() != '') {
        //define the app_name constant
        define('APP_NAME', $APP->getAppName());
    } else {
        define('APP_NAME', $_ENV['APP_NAME']);
    } // Define the APP_NAME constant, this is the name of the application.
    define('APP_VERSION', "1.0.0"); // Define the APP_VERSION constant, this is the version of the application.
    define('APP_ENV', $_ENV['APP_ENV']); // Define the APP_ENV constant, this is the environment the application is running in i.e LOCAL, PRODUCTION, TEST.
    define('APP_DEBUG', $_ENV['APP_DEBUG']); // Define the APP_DEBUG constant, this is the debug mode of the application.
    define('OPENSSL_INSTALLED', extension_loaded('openssl')); // Define the OPENSSL_INSTALLED constant, this is whether or not the openssl extension is installed.

    /* Define the mail constants */
    //Check if the mail_mailer is set in the database, if not set it to the value in the .env file
    if ($APP->getMailerType() != null || $APP->getMailerType() != '') {
        //define the mail_mailer constant
        define('MAIL_MAILER', $APP->getMailerType());
    } else {
        define('MAIL_MAILER', $_ENV['MAIL_MAILER']);
    } // Define the MAIL_MAILER constant, this is the mail protocol to use.
    //Check if the mail_host is set in the database, if not set it to the value in the .env file
    if ($APP->getMailerHost() != null || $APP->getMailerHost() != '') {
        //define the mail_host constant
        define('MAIL_HOST', $APP->getMailerHost());
    } else {
        define('MAIL_HOST', $_ENV['MAIL_HOST']);
    } // Define the MAIL_HOST constant, this is the hostname of the mail server.
    //Check if the mail_port is set in the database, if not set it to the value in the .env file
    if ($APP->getMailerPort() != null || $APP->getMailerPort() != '') {
        //define the mail_port constant
        define('MAIL_PORT', $APP->getMailerPort());
    } else {
        define('MAIL_PORT', $_ENV['MAIL_PORT']);
    } // Define the MAIL_PORT constant, this is the port number of the mail server.
    //Check if the mail_auth_req is set in the database, if not set it to the value in the .env file
    if ($APP->getMailerAuthRequired() != null || $APP->getMailerAuthRequired() != '') {
        //if true, set to string true, if false, set to string false
        if ($APP->getMailerAuthRequired() == true) {
            $required = 'true';
        } else if ($APP->getMailerAuthRequired() == false) {
            $required = 'false';
        }
        //define the mail_auth_req constant
        define('MAIL_AUTH_REQ', $required);
    } else {
        define('MAIL_AUTH_REQ', $_ENV['MAIL_AUTH_REQ']);
    } // Define the MAIL_AUTH_REQ constant, this is whether or not authentication is required.
    //Check if the mail_encryption is set in the database, if not set it to the value in the .env file
    if ($APP->getMailerEncryption() != null || $APP->getMailerEncryption() != '') {
        //define the mail_encryption constant
        define('MAIL_ENCRYPTION', $APP->getMailerEncryption());
    } else {
        define('MAIL_ENCRYPTION', $_ENV['MAIL_ENCRYPTION']);
    } // Define the MAIL_ENCRYPTION constant, this is the encryption to use.
    //Check if the mail_username is set in the database, if not set it to the value in the .env file
    if ($APP->getMailerUsername() != null || $APP->getMailerUsername() != '') {
        //define the mail_username constant
        define('MAIL_USERNAME', $APP->getMailerUsername());
    } else {
        define('MAIL_USERNAME', $_ENV['MAIL_USERNAME']);
    } // Define the MAIL_USERNAME constant, this is the username to use for authentication.
    //Check if the mail_password is set in the database, if not set it to the value in the .env file
    if ($APP->getMailerPassword() != null || $APP->getMailerPassword() != '') {
        //define the mail_password constant
        define('MAIL_PASSWORD', $APP->getMailerPassword());
    } else {
        define('MAIL_PASSWORD', $_ENV['MAIL_PASSWORD']);
    } // Define the MAIL_PASSWORD constant, this is the password to use for authentication.
    //Check if the mail_from_address is set in the database, if not set it to the value in the .env file
    if ($APP->getMailerFromAddress() != null || $APP->getMailerFromAddress() != '') {
        //define the mail_from_address constant
        define('MAIL_FROM_ADDRESS', $APP->getMailerFromAddress());
    } else {
        define('MAIL_FROM_ADDRESS', $_ENV['MAIL_FROM_ADDRESS']);
    } // Define the MAIL_FROM_ADDRESS constant, this is the email address to send the email from.
    //Check if the mail_from_name is set in the database, if not set it to the value in the .env file
    if ($APP->getMailerFromName() != null || $APP->getMailerFromName() != '') {
        //define the mail_from_name constant
        define('MAIL_FROM_NAME', $APP->getMailerFromName());
    } else {
        define('MAIL_FROM_NAME', $_ENV['MAIL_FROM_NAME']);
    } // Define the MAIL_FROM_NAME constant, this is the name to send the email from.
    define('MAILER_PASSWORD_ENCRYPTION_KEY', $_ENV['MAILER_PASSWORD_ENCRYPTION_KEY']); // Define the MAILER_PASSWORD_ENCRYPTION_KEY constant, this is the encryption key to use for encrypting passwords.
} else {
    //set the error message
    $thisError = 'CONFIGURATION_ERROR';

    //include the error message file
    include_once(__DIR__ . '/../includes/errors/errorMessage.inc.php');
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
 * Get the upload path for the application
 * Returns the media upload path for the application with trailing slash
 *
 * @return string media upload path for the application
 */
function getUploadPath(): string
{
    /* Define the media upload path for the application */
    $uploadPath = APP_URL . '/public/content/uploads/';

    /* Return the media upload path for the application */
    return $uploadPath;
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
    if (preg_match("/^(\+\d{1,2}\s?)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/", $phone)) { // Regex to validate phone formatting, by Ravi K Thapliyal, https://stackoverflow.com/questions/16699007/regular-expression-to-match-standard-10-digit-phone-number
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
    $select2BootstrapCSS = '<link rel="stylesheet" href="' . getLibraryPath() . 'select2/css/select2-bootstrap.min.css">';
    $fontawesomeCSS = '<link rel="stylesheet" href="' . getLibraryPath() . 'fontawesome/css/all.min.css">';
    //if style.min.css exists, load it, otherwise load the original
    if (file_exists(BASEPATH . '/public/content/assets/css/style.min.css')) {
        $styleCSS = '<link rel="stylesheet" href="' . getAssetPath() . 'css/style.min.css">';
    } else {
        $styleCSS = '<link rel="stylesheet" href="' . getAssetPath() . 'css/style.css">';
    }
    //if responsive.min.css exists, load it, otherwise load the original
    if (file_exists(BASEPATH . '/public/content/assets/css/responsive.min.css')) {
        $responsiveCSS = '<link rel="stylesheet" href="' . getAssetPath() . 'css/responsive.min.css">';
    } else {
        $responsiveCSS = '<link rel="stylesheet" href="' . getAssetPath() . 'css/responsive.css">';
    }

    /* JS that needs to be loaded in the header */
    $jQuery = '<script type="text/javascript" src="' . getLibraryPath() . 'jquery/jquery.min.js"></script>';
    $jqueryMigrate = '<script type="text/javascript" src="' . getLibraryPath() . 'jquery-migrate/jquery-migrate.min.js"></script>';

    /* Assemble the header for the application */
    $header = $boostrapCSS . $datatablesCSS . $select2CSS . $select2BootstrapCSS . $fontawesomeCSS . $styleCSS . $responsiveCSS . $jQuery . $jqueryMigrate;

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
    //$jQuery = '<script type="text/javascript" src="' . getLibraryPath() . 'jquery/jquery.min.js"></script>';
    //$jqueryMigrate = '<script type="text/javascript" src="' . getLibraryPath() . 'jquery-migrate/jquery-migrate.min.js"></script>';
    $datatablesJS = '<script type="text/javascript" src="' . getLibraryPath() . 'simple-datatables/umd/simple-datatables.js"></script>';
    $chartJS = '<script type="text/javascript" src="' . getLibraryPath() . 'chart.js/chart.umd.js"></script>';
    $tether = '<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>';
    $select2JS = '<script type="text/javascript" src="' . getLibraryPath() . 'select2/js/select2.min.js"></script>';
    $select2BootstrapEnabler = '<script type="text/javascript">$.fn.select2.defaults.set( "theme", "bootstrap" );</script>';
    //if scripts.min.js exists, load it, otherwise load the original
    if (file_exists(BASEPATH . '/public/content/assets/js/scripts.min.js')) {
        $JS = '<script type="text/javascript" src="' . getAssetPath() . 'js/scripts.min.js"></script>';
    } else {
        $JS = '<script type="text/javascript" src="' . getAssetPath() . 'js/scripts.js"></script>';
    }
    $dataTableJS = '<script type="text/javascript" src="' . getLibraryPath() . 'datatable-master/js/datatable.min.js"></script>';
    $dataTableJqueryJS = '<script type="text/javascript" src="' . getLibraryPath() . 'datatable-master/js/datatable.jquery.min.js"></script>';
    //if tables.min.js exists, load it, otherwise load tables.js
    if (file_exists(BASEPATH . '/public/content/assets/js/tables.min.js')) {
        $tablesJS = '<script type="module" src="' . getAssetPath() . 'js/tables.min.js"></script>';
    } else {
        $tablesJS = '<script type="module" src="' . getAssetPath() . 'js/tables.js"></script>';
    }

    $jqueryNoConflict = '<script>var $j = jQuery.noConflict();</script>';

    /* Assemble the footer for the application */
    $footer = $datatablesJS . $tether . $select2JS . $select2BootstrapEnabler . $chartJS . $dataTableJS . $dataTableJqueryJS . $tablesJS . $boostrapJS . $fontawesomeJS . $JS;

    /* Return the footer for the application */
    return $footer;
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
    //limit to 25 characters to fit in the varchar(25) field in the database
    $string = substr($string, 0, 25);
    //return the slug
    return $string;
}
