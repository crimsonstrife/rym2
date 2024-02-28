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

/* include the settings class */
$settings = new Settings();
$mailSettings = new MailerSettings();
$companySettings = new CompanySettings();
$trackerSettings = new TrackerSettings();
$googleAnalyticsSettings = new GoogleAnalyticsTracker();
$hotjarSettings = new HotjarTracker();

if (file_exists(BASEPATH . '/.env')) {
    /* Use the phpdotenv package to read the .env file */
    $dotenv = Dotenv\Dotenv::createImmutable(BASEPATH);
    $dotenv->safeLoad(); // Load the .env file if it exists.

    /* Get ENV variables, if they are not set or do not meet requirements, throw an exception */
    $dotenv->required(['APP_ENV', 'APP_URL', 'APP_NAME', 'APP_DEBUG'])->notEmpty();
    $dotenv->required(['APP_ENV'])->allowedValues(['LOCAL', 'PRODUCTION', 'TEST']);
    $dotenv->required(['APP_DEBUG'])->allowedValues(['true', 'false', '1', '0', 'TRUE', 'FALSE']);
    $dotenv->required(['APP_URL']); // removed the regex validation for the app_url, as it was causing issues with the automated deployment and special characters

    /* Define the application constants */
    $app_url = null;
    //try to get the app_url
    try {
        $app_url = $settings->getAppUrl();
    } catch (Exception $e) {
        $app_url = null;
    }
    //Check if the app_url is set in the database, if not set it to the value in the .env file
    if ($app_url != null || $app_url != '') {
        //define the app_url constant
        define('APP_URL', $app_url);
    } else {
        //get the app_url from the .env file
        $app_url_var = $_ENV['APP_URL'];
        //format the app_url to include the protocol if it is not included
        if (strpos($app_url_var, 'http://') !== false || strpos($app_url_var, 'https://') !== false) {
            define('APP_URL', $app_url_var);
        } else {
            define('APP_URL', 'https://' . $app_url_var);
        }
    }

    $app_name = null;
    //try to get the app_name
    try {
        $app_name = $settings->getAppName();
    } catch (Exception $e) {
        $app_name = null;
    }
    //Check if the app_name is set in the database, if not set it to the value in the .env file
    if ($app_name != null || $app_name != '') {
        //define the app_name constant
        define('APP_NAME', $app_name);
    } else {
        define('APP_NAME', $_ENV['APP_NAME']);
    }
    define('APP_VERSION', "1.0.0"); // Define the APP_VERSION constant, this is the version of the application.
    define('APP_ENV', $_ENV['APP_ENV']); // Define the APP_ENV constant, this is the environment the application is running in i.e LOCAL, PRODUCTION, TEST.
    define('APP_DEBUG', $_ENV['APP_DEBUG']); // Define the APP_DEBUG constant, this is the debug mode of the application.
    define('OPENSSL_INSTALLED', extension_loaded('openssl')); // Define the OPENSSL_INSTALLED constant, this is whether or not the openssl extension is installed.

    /* Define the company constants constants */
    $company_name = null;
    //try to get the company_name
    try {
        $company_name = $companySettings->getCompanyName();
    } catch (Exception $e) {
        $company_name = null;
    }
    //Check if the company_name is set in the database, if not set it to the value in the .env file
    if ($company_name != null || $company_name != '') {
        //define the company_name constant
        define('COMPANY_NAME', $company_name);
    } else {
        define('COMPANY_NAME', $_ENV['COMPANY_NAME']);
    }

    /* Define the mail constants */
    $mailer_type = null;
    //try to get the mailer_type
    try {
        $mailer_type = $mailSettings->getMailerType();
    } catch (Exception $e) {
        $mailer_type = null;
    }
    //Check if the mail_mailer is set in the database, if not set it to the value in the .env file
    if ($mailer_type != null || $mailer_type != '') {
        //define the mail_mailer constant
        define('MAIL_MAILER', $mailer_type);
    } else {
        define('MAIL_MAILER', $_ENV['MAIL_MAILER']);
    }

    $mailer_host = null;
    //try to get the mailer_host
    try {
        $mailer_host = $mailSettings->getMailerHost();
    } catch (Exception $e) {
        $mailer_host = null;
    }
    //Check if the mail_host is set in the database, if not set it to the value in the .env file
    if ($mailer_host != null || $mailer_host != '') {
        //define the mail_host constant
        define('MAIL_HOST', $mailer_host);
    } else {
        define('MAIL_HOST', $_ENV['MAIL_HOST']);
    }

    $mailer_port = null;
    //try to get the mailer_port
    try {
        $mailer_port = $mailSettings->getMailerPort();
    } catch (Exception $e) {
        $mailer_port = null;
    }
    //Check if the mail_port is set in the database, if not set it to the value in the .env file
    if ($mailer_port != null || $mailer_port != '') {
        //define the mail_port constant
        define('MAIL_PORT', $mailer_port);
    } else {
        define('MAIL_PORT', $_ENV['MAIL_PORT']);
    }

    $mailer_auth_required = null;
    //try to get the mailer_auth_required
    try {
        $mailer_auth_required = $mailSettings->getMailerAuthRequired();
    } catch (Exception $e) {
        $mailer_auth_required = null;
    }
    //Check if the mail_auth_req is set in the database, if not set it to the value in the .env file
    if ($mailer_auth_required != null || $mailer_auth_required != '') {
        //if true, set to string true, if false, set to string false
        if ($mailer_auth_required == true || $mailer_auth_required == 'true') {
            $required = 'true';
        } else if ($mailer_auth_required == false || $mailer_auth_required == 'false') {
            $required = 'false';
        }
        //define the mail_auth_req constant
        define('MAIL_AUTH_REQ', $required);
    } else {
        define('MAIL_AUTH_REQ', $_ENV['MAIL_AUTH_REQ']);
    }

    $mailer_encryption = null;
    //try to get the mailer_encryption
    try {
        $mailer_encryption = $mailSettings->getMailerEncryption();
    } catch (Exception $e) {
        $mailer_encryption = null;
    }
    //Check if the mail_encryption is set in the database, if not set it to the value in the .env file
    if ($mailer_encryption != null || $mailer_encryption != '') {
        //define the mail_encryption constant
        define('MAIL_ENCRYPTION', $mailer_encryption);
    } else {
        define('MAIL_ENCRYPTION', $_ENV['MAIL_ENCRYPTION']);
    }

    $mailer_username = null;
    //try to get the mailer_username
    try {
        $mailer_username = $mailSettings->getMailerUsername();
    } catch (Exception $e) {
        $mailer_username = null;
    }
    //Check if the mail_username is set in the database, if not set it to the value in the .env file
    if ($mailer_username != null || $mailer_username != '') {
        //define the mail_username constant
        define('MAIL_USERNAME', $mailer_username);
    } else {
        define('MAIL_USERNAME', $_ENV['MAIL_USERNAME']);
    }

    $mailer_from_address = null;
    //try to get the mailer_from_address
    try {
        $mailer_from_address = $mailSettings->getMailerFromAddress();
    } catch (Exception $e) {
        $mailer_from_address = null;
    }
    //Check if the mail_from_address is set in the database, if not set it to the value in the .env file
    if ($mailer_from_address != null || $mailer_from_address != '') {
        //define the mail_from_address constant
        define('MAIL_FROM_ADDRESS', $mailer_from_address);
    } else {
        define('MAIL_FROM_ADDRESS', $_ENV['MAIL_FROM_ADDRESS']);
    }

    $mailer_from_name = null;
    //try to get the mailer_from_name
    try {
        $mailer_from_name = $mailSettings->getMailerFromName();
    } catch (Exception $e) {
        $mailer_from_name = null;
    }
    //Check if the mail_from_name is set in the database, if not set it to the value in the .env file
    if ($mailer_from_name != null || $mailer_from_name != '') {
        //define the mail_from_name constant
        define('MAIL_FROM_NAME', $mailer_from_name);
    } else {
        define('MAIL_FROM_NAME', $_ENV['MAIL_FROM_NAME']);
    }
    define('MAILER_PASSWORD_ENCRYPTION_KEY', $_ENV['MAILER_PASSWORD_ENCRYPTION_KEY']); // Define the MAILER_PASSWORD_ENCRYPTION_KEY constant, this is the encryption key to use for encrypting passwords.

    $mailer_password = null;
    //try to get the mailer_password
    try {
        $mailer_password = $mailSettings->getMailerPassword();
    } catch (Exception $e) {
        $mailer_password = null;
    }
    //Check if the mail_password is set in the database, if not set it to the value in the .env file
    if ($mailer_password != null || $mailer_password != '') {
        //define the mail_password constant
        define('MAIL_PASSWORD', $mailer_password);
    } else {
        $mailerPassword = $_ENV['MAIL_PASSWORD'];
        //if OPENSSL is installed, encrypt the password
        if (OPENSSL_INSTALLED) {
            //Encrypt the password
            $password = openssl_encrypt($mailerPassword, 'AES-128-ECB', MAILER_PASSWORD_ENCRYPTION_KEY);
        }

        if (!OPENSSL_INSTALLED) {
            //store the password as plain text
            $password = $mailerPassword;
        }
        define('MAIL_PASSWORD', $mailerPassword);
    }
} elseif (file_exists(BASEPATH . '/.env.example')) {
    /*load the .env.example file if the .env file does not exist */
    $dotenv = Dotenv\Dotenv::createImmutable(BASEPATH, '.env.example');
    $dotenv->safeLoad();

    /* Define the application constants */
    $app_url = null;
    //try to get the app_url
    try {
        $app_url = $settings->getAppUrl();
    } catch (Exception $e) {
        $app_url = null;
    }
    //Check if the app_url is set in the database, if not set it to the value in the .env file
    if ($app_url != null || $app_url != '') {
        //define the app_url constant
        define('APP_URL', $app_url);
    } else {
        //get the app_url from the .env file
        $app_url_var = $_ENV['APP_URL'];
        //format the app_url to include the protocol if it is not included
        if (strpos($app_url_var, 'http://') !== false || strpos($app_url_var, 'https://') !== false) {
            define('APP_URL', $app_url_var);
        } else {
            define('APP_URL', 'https://' . $app_url_var);
        }
    }

    $app_name = null;
    //try to get the app_name
    try {
        $app_name = $settings->getAppName();
    } catch (Exception $e) {
        $app_name = null;
    }
    //Check if the app_name is set in the database, if not set it to the value in the .env file
    if ($app_name != null || $app_name != '') {
        //define the app_name constant
        define('APP_NAME', $app_name);
    } else {
        define('APP_NAME', $_ENV['APP_NAME']);
    }
    define('APP_VERSION', "1.0.0"); // Define the APP_VERSION constant, this is the version of the application.
    define('APP_ENV', $_ENV['APP_ENV']); // Define the APP_ENV constant, this is the environment the application is running in i.e LOCAL, PRODUCTION, TEST.
    define('APP_DEBUG', $_ENV['APP_DEBUG']); // Define the APP_DEBUG constant, this is the debug mode of the application.
    define('OPENSSL_INSTALLED', extension_loaded('openssl')); // Define the OPENSSL_INSTALLED constant, this is whether or not the openssl extension is installed.

    /* Define the company constants constants */
    $company_name = null;
    //try to get the company_name
    try {
        $company_name = $companySettings->getCompanyName();
    } catch (Exception $e) {
        $company_name = null;
    }
    //Check if the company_name is set in the database, if not set it to the value in the .env file
    if ($company_name != null || $company_name != '') {
        //define the company_name constant
        define('COMPANY_NAME', $company_name);
    } else {
        define('COMPANY_NAME', $_ENV['COMPANY_NAME']);
    }

    /* Define the mail constants */
    $mailer_type = null;
    //try to get the mailer_type
    try {
        $mailer_type = $mailSettings->getMailerType();
    } catch (Exception $e) {
        $mailer_type = null;
    }
    //Check if the mail_mailer is set in the database, if not set it to the value in the .env file
    if ($mailer_type != null || $mailer_type != '') {
        //define the mail_mailer constant
        define('MAIL_MAILER', $mailer_type);
    } else {
        define('MAIL_MAILER', $_ENV['MAIL_MAILER']);
    }

    $mailer_host = null;
    //try to get the mailer_host
    try {
        $mailer_host = $mailSettings->getMailerHost();
    } catch (Exception $e) {
        $mailer_host = null;
    }
    //Check if the mail_host is set in the database, if not set it to the value in the .env file
    if ($mailer_host != null || $mailer_host != '') {
        //define the mail_host constant
        define('MAIL_HOST', $mailer_host);
    } else {
        define('MAIL_HOST', $_ENV['MAIL_HOST']);
    }

    $mailer_port = null;
    //try to get the mailer_port
    try {
        $mailer_port = $mailSettings->getMailerPort();
    } catch (Exception $e) {
        $mailer_port = null;
    }
    //Check if the mail_port is set in the database, if not set it to the value in the .env file
    if ($mailer_port != null || $mailer_port != '') {
        //define the mail_port constant
        define('MAIL_PORT', $mailer_port);
    } else {
        define('MAIL_PORT', $_ENV['MAIL_PORT']);
    }

    $mailer_auth_required = null;
    //try to get the mailer_auth_required
    try {
        $mailer_auth_required = $mailSettings->getMailerAuthRequired();
    } catch (Exception $e) {
        $mailer_auth_required = null;
    }
    //Check if the mail_auth_req is set in the database, if not set it to the value in the .env file
    if ($mailer_auth_required != null || $mailer_auth_required != '') {
        //if true, set to string true, if false, set to string false
        if ($mailer_auth_required == true || $mailer_auth_required == 'true') {
            $required = 'true';
        } else if ($mailer_auth_required == false || $mailer_auth_required == 'false') {
            $required = 'false';
        }
        //define the mail_auth_req constant
        define('MAIL_AUTH_REQ', $required);
    } else {
        define('MAIL_AUTH_REQ', $_ENV['MAIL_AUTH_REQ']);
    }

    $mailer_encryption = null;
    //try to get the mailer_encryption
    try {
        $mailer_encryption = $mailSettings->getMailerEncryption();
    } catch (Exception $e) {
        $mailer_encryption = null;
    }
    //Check if the mail_encryption is set in the database, if not set it to the value in the .env file
    if ($mailer_encryption != null || $mailer_encryption != '') {
        //define the mail_encryption constant
        define('MAIL_ENCRYPTION', $mailer_encryption);
    } else {
        define('MAIL_ENCRYPTION', $_ENV['MAIL_ENCRYPTION']);
    }

    $mailer_username = null;
    //try to get the mailer_username
    try {
        $mailer_username = $mailSettings->getMailerUsername();
    } catch (Exception $e) {
        $mailer_username = null;
    }
    //Check if the mail_username is set in the database, if not set it to the value in the .env file
    if ($mailer_username != null || $mailer_username != '') {
        //define the mail_username constant
        define('MAIL_USERNAME', $mailer_username);
    } else {
        define('MAIL_USERNAME', $_ENV['MAIL_USERNAME']);
    }

    $mailer_password = null;
    //try to get the mailer_password
    try {
        $mailer_password = $mailSettings->getMailerPassword();
    } catch (Exception $e) {
        $mailer_password = null;
    }
    //Check if the mail_password is set in the database, if not set it to the value in the .env file
    if ($mailer_password != null || $mailer_password != '') {
        //define the mail_password constant
        define('MAIL_PASSWORD', $mailer_password);
    } else {
        $mailerPassword = $_ENV['MAIL_PASSWORD'];
        //if OPENSSL is installed, encrypt the password
        if (OPENSSL_INSTALLED) {
            //Encrypt the password
            $password = openssl_encrypt($mailerPassword, 'AES-128-ECB', MAILER_PASSWORD_ENCRYPTION_KEY);
        }

        if (!OPENSSL_INSTALLED) {
            //store the password as plain text
            $password = $mailerPassword;
        }
        define('MAIL_PASSWORD', $mailerPassword);
    }

    $mailer_from_address = null;
    //try to get the mailer_from_address
    try {
        $mailer_from_address = $mailSettings->getMailerFromAddress();
    } catch (Exception $e) {
        $mailer_from_address = null;
    }
    //Check if the mail_from_address is set in the database, if not set it to the value in the .env file
    if ($mailer_from_address != null || $mailer_from_address != '') {
        //define the mail_from_address constant
        define('MAIL_FROM_ADDRESS', $mailer_from_address);
    } else {
        define('MAIL_FROM_ADDRESS', $_ENV['MAIL_FROM_ADDRESS']);
    }

    $mailer_from_name = null;
    //try to get the mailer_from_name
    try {
        $mailer_from_name = $mailSettings->getMailerFromName();
    } catch (Exception $e) {
        $mailer_from_name = null;
    }
    //Check if the mail_from_name is set in the database, if not set it to the value in the .env file
    if ($mailer_from_name != null || $mailer_from_name != '') {
        //define the mail_from_name constant
        define('MAIL_FROM_NAME', $mailer_from_name);
    } else {
        define('MAIL_FROM_NAME', $_ENV['MAIL_FROM_NAME']);
    }
    define('MAILER_PASSWORD_ENCRYPTION_KEY', $_ENV['MAILER_PASSWORD_ENCRYPTION_KEY']); // Define the MAILER_PASSWORD_ENCRYPTION_KEY constant, this is the encryption key to use for encrypting passwords.

    $mailer_password = null;
    //try to get the mailer_password
    try {
        $mailer_password = $mailSettings->getMailerPassword();
    } catch (Exception $e) {
        $mailer_password = null;
    }
    //Check if the mail_password is set in the database, if not set it to the value in the .env file
    if ($mailer_password != null || $mailer_password != '') {
        //define the mail_password constant
        define('MAIL_PASSWORD', $mailer_password);
    } else {
        $mailerPassword = $_ENV['MAIL_PASSWORD'];
        //if OPENSSL is installed, encrypt the password
        if (OPENSSL_INSTALLED) {
            //Encrypt the password
            $password = openssl_encrypt($mailerPassword, 'AES-128-ECB', MAILER_PASSWORD_ENCRYPTION_KEY);
        }

        if (!OPENSSL_INSTALLED) {
            //store the password as plain text
            $password = $mailerPassword;
        }
        define('MAIL_PASSWORD', $mailerPassword);
    }

} else {
    //set the error message
    $thisError = 'CONFIGURATION_ERROR';

    //include the error message file
    include_once(__DIR__ . '/../includes/errors/errorMessage.inc.php');
}

/* Get the Hotjar settings and set the constants */
$hotjar_enabled = null;
//try to get the hotjar_enabled setting
try {
    $hotjar_enabled = $hotjarSettings->getHotjarStatus();
} catch (Exception $e) {
    $hotjar_enabled = null;
}

//Check if the hotjar_enabled is set in the database, if not set it to null
if ($hotjar_enabled != null || $hotjar_enabled != '') {
    //define the hotjar_enabled constant
    define('HOTJAR_ENABLED', $hotjar_enabled);
} else {
    define('HOTJAR_ENABLED', null);
}

$hotjar_id = null;
$hotjar_version = null;
//if hotjar is enabled, try to get the hotjar_id and hotjar_version
if (HOTJAR_ENABLED == true) {
    try {
        $hotjar_id = $hotjarSettings->getHotjarId();
        $hotjar_version = $hotjarSettings->getHotjarVersion();
    } catch (Exception $e) {
        $hotjar_id = null;
        $hotjar_version = null;
    }
}

//Check if the hotjar_id is set in the database, if not set it to null
if ($hotjar_id != null || $hotjar_id != '') {
    //define the hotjar_id constant
    define('HOTJAR_ID', $hotjar_id);
} else {
    define('HOTJAR_ID', null);
}

//Check if the hotjar_version is set in the database, if not set it to null
if ($hotjar_version != null || $hotjar_version != '') {
    //define the hotjar_version constant
    define('HOTJAR_VERSION', $hotjar_version);
} else {
    define('HOTJAR_VERSION', null);
}

/* Get the Google Analytics settings and set the constants */
$google_analytics_enabled = null;
//try to get the google_analytics_enabled setting
try {
    $google_analytics_enabled = $googleAnalyticsSettings->getGoogleAnalyticsStatus();
} catch (Exception $e) {
    $google_analytics_enabled = null;
}

//Check if the google_analytics_enabled is set in the database, if not set it to null
if ($google_analytics_enabled != null || $google_analytics_enabled != '') {
    //define the google_analytics_enabled constant
    define('GOOGLE_ANALYTICS_ENABLED', $google_analytics_enabled);
} else {
    define('GOOGLE_ANALYTICS_ENABLED', null);
}

$google_analytics_tag = null;
//if google analytics is enabled, try to get the google_analytics_tag
if (GOOGLE_ANALYTICS_ENABLED == true) {
    try {
        $google_analytics_tag = $googleAnalyticsSettings->getGoogleAnalyticsID();
    } catch (Exception $e) {
        $google_analytics_tag = null;
    }
}

//Check if the google_analytics_tag is set in the database, if not set it to null
if ($google_analytics_tag != null || $google_analytics_tag != '') {
    //define the google_analytics_tag constant
    define('GOOGLE_ANALYTICS_ID', $google_analytics_tag);
} else {
    define('GOOGLE_ANALYTICS_ID', null);
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
    $assetPath = checkSecureRequest() . '/public/content/assets/';

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
    $libraryPath = checkSecureRequest() . '/public/content/libs/';

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
    $uploadPath = checkSecureRequest() . '/public/content/uploads/';

    /* Return the media upload path for the application */
    return $uploadPath;
}

/**
 * Check if the request is secure via HTTPS or HTTP, adjust the URL accordingly
 * returns the URL with the correct protocol, either HTTP or HTTPS, to avoid CORS issues
 *
 * @return string URL with the correct protocol
 */
function checkSecureRequest(): string
{
    //Set the protocol to an empty string
    $protocol = "";

    //Check if the request is secure
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        //Set the URL to HTTPS
        $protocol = "https://";
    } else {
        //Set the URL to HTTP
        $protocol = "http://";
    }

    //Get the set URL constant
    $urlConstant = APP_URL;

    //Check if the URL constant includes the protocol, if not, add it and if it does adjust as needed
    if (strpos($urlConstant, 'http://') !== false) {
        //Remove the protocol from the URL constant
        $urlConstant = str_replace('http://', '', $urlConstant);
    } else if (strpos($urlConstant, 'https://') !== false) {
        //Remove the protocol from the URL constant
        $urlConstant = str_replace('https://', '', $urlConstant);
    }

    //check if the hostname in the app_url is localhost, if so check if the request host is also localhost, if not, set the app_url to the request host - should help with CORS issues during install
    if (strpos($urlConstant, 'localhost') !== false) {
        if ($_SERVER['HTTP_HOST'] != 'localhost') {
            $urlConstant = $_SERVER['HTTP_HOST'];
        }
    }

    //Set the URL to the correct protocol and add the URL constant
    $url = $protocol . $urlConstant;

    //Return the URL
    return $url;
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
    //if compatibility.min.css exists, load it, otherwise load the original
    if (file_exists(BASEPATH . '/public/content/assets/css/compatibility.min.css')) {
        $compatibilityCSS = '<link rel="stylesheet" href="' . getAssetPath() . 'css/compatibility.min.css">';
    } else {
        $compatibilityCSS = '<link rel="stylesheet" href="' . getAssetPath() . 'css/compatibility.css">';
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
    $hotjar_activation = null;
    //check if hotjar is enabled
    if (HOTJAR_ENABLED == true) {
        //setup the hotjar activation script
        $hotjar_activation = "<!-- Hotjar Tracking Code as documented on hotjar.com -->
        <script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:" . HOTJAR_ID .",hjsv:" . HOTJAR_VERSION ."};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>";
    }
    $google_analytics_activation = null;
    //check if google analytics is enabled
    if (GOOGLE_ANALYTICS_ENABLED == true) {
        //setup the google analytics activation script
        $google_analytics_activation = "<!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src='https://www.googletagmanager.com/gtag/js?id=" . GOOGLE_ANALYTICS_ID . "'></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '" . GOOGLE_ANALYTICS_ID . "');
        </script>";
    }

    /* Assemble the header for the application */
    $header = $boostrapCSS . $datatablesCSS . $select2CSS . $select2BootstrapCSS . $fontawesomeCSS . $styleCSS . $compatibilityCSS . $responsiveCSS . $jQuery . $jqueryMigrate . $hotjar_activation . $google_analytics_activation;

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
    //if skill-list.min.js exists, load it, otherwise load skill-list.js
    if (file_exists(BASEPATH . '/public/content/assets/js/skill-list.min.js')) {
        $skillListJS = '<script type="module" src="' . getAssetPath() . 'js/skill-list.min.js"></script>';
    } else {
        $skillListJS = '<script type="module" src="' . getAssetPath() . 'js/skill-list.js"></script>';
    }
    //if wysiwyg-editor.min.js exists, load it, otherwise load wysiwyg-editor.js
    if (file_exists(BASEPATH . '/public/content/assets/js/wysiwyg-editor.min.js')) {
        $wysiwygEditorJS = '<script type="module" src="' . getAssetPath() . 'js/wysiwyg-editor.min.js"></script>';
    } else {
        $wysiwygEditorJS = '<script type="module" src="' . getAssetPath() . 'js/wysiwyg-editor.js"></script>';
    }
    //if counter.min.js exists, load it, otherwise load counter.js
    if (file_exists(BASEPATH . '/public/content/assets/js/counter.min.js')) {
        $counterJS = '<script type="text/javascript" src="' . getAssetPath() . 'js/counter.min.js"></script>';
    } else {
        $counterJS = '<script type="text/javascript" src="' . getAssetPath() . 'js/counter.js"></script>';
    }

    $jqueryNoConflict = '<script>var $j = jQuery.noConflict();</script>';

    /* Assemble the footer for the application */
    $footer = $datatablesJS . $tether . $select2JS . $select2BootstrapEnabler . $chartJS . $dataTableJS . $dataTableJqueryJS . $tablesJS . $boostrapJS . $fontawesomeJS . $JS . $skillListJS . $wysiwygEditorJS . $counterJS;

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
