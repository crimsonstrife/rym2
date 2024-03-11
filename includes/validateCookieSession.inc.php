<?php
/**
 * Validate Cookie Session
 * Used to validate the user's session and cookies to determine if they are logged in
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/16/2023
 *
 * @package RYM2
 * @version 1.0.0
 * @requires PHP 8.1.2+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

//Prevent direct access to this file by checking if the constant CAN_INCLUDE is defined.
if (!defined('CAN_INCLUDE')) {
    //set the error type
    $thisError = 'CONFIGURATION_ERROR';

    //include the error message file
    include_once(__DIR__ . '/errors/errorMessage.inc.php');
} // idea from https://stackoverflow.com/a/409515 (user UnkwnTech)

/* Include the base application config file */
require_once(__DIR__ . '/../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');
// include the auth class file
require_once(BASEPATH . '/includes/classes/auth.inc.php');
// include helpers file
require_once(BASEPATH . '/includes/utils/helpers.php');

//initialize the auth class
$auth = new Authenticator();

//get the current date and time
$current_time = time();
$current_date = date("Y-m-d H:i:s", $current_time);

// Set Cookie expiration for 30 days
$expiration_time = $current_time + (30 * 24 * 60 * 60);

//default the logged in boolean to false
$logged_in = false;

//check if the session is set
if (!empty($_SESSION["user_id"])) {
    $logged_in = true;
} else if (!empty($_COOKIE["user_id"]) && !empty($_COOKIE["user_name"]) && !empty($_COOKIE["user_password"]) && !empty($_COOKIE["user_password_selector"])) {
    //initialize the verification variables to false
    $user_id_verified = false;
    $user_password_verified = false;
    $user_password_selector_verified = false;
    $session_expiry_verified = false;

    //Get authentication token
    $auth_token = $auth->getAuthenticationToken(intval($_COOKIE["user_id"]), $_COOKIE["user_name"], 0);

    //check if the authentication token is not empty
    if (!empty($auth_token) || $auth_token != null) {
        //is auth_token an array?
        if (is_array($auth_token)) {
            //user ID cookie verification
            if (!empty($auth_token[0]["user_id"]) && hash_equals($_COOKIE["user_id"], strval($auth_token[0]["user_id"]))) {
                $user_id_verified = true;
            }

            //password cookie verification
            if (password_verify($_COOKIE["user_password"], strval($auth_token[0]["password_hash"]) ?? '')) {
                $user_password_verified = true;
            }

            //password selector cookie verification
            if (hash_equals($_COOKIE["user_password_selector"], strval($auth_token[0]["selector_hash"]) ?? '')) {
                $user_password_selector_verified = true;
            }

            //session expiry verification
            if ($auth_token[0]["expiry_date"] >= $current_date) {
                $session_expiry_verified = true;
            }

            //if all the verification variables are true, redirect. Else, expire the token and clear the cookies
            if ($user_id_verified && $user_password_verified && $user_password_selector_verified && $session_expiry_verified) {
                //set the logged in boolean to true
                $logged_in = true;
            } else {
                if (!empty($auth_token[0]["id"])) {
                    //expire the token
                    $auth->expireToken($auth_token[0]["id"]);
                }
                //clear the cookies
                clearCookies();
            }
        } else {
            //auth_token is not an array, should be a boolean of false indicating no token was found
            //clear the cookies
            clearCookies();
        }
    }
}
