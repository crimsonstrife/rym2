<?php
// Initialize the session
session_start();

define('CAN_INCLUDE', true); // Define a constant to control access to the include files

// Include config file
require_once(__DIR__ . '../../config/app.php');
// Include the helpers file
require_once(__DIR__ . '../../includes/utils/helpers.php');
// Include the validation file
require_once(__DIR__ . '../../includes/validateCookieSession.inc.php');

//include the authenticator class
$authenticator = new Authenticator();

// Define variables and initialize with empty values
$username = $password = "";
$username_error = $password_error = $login_error = "";

if (isset($_GET['login'])) {
    //log the result
    error_log("Login attempt: " . $_GET['login']);
    switch ($_GET['login']) {
        case 'true':
            //log the result
            error_log("Login: " . "true");
            // Processing form data when form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                //set the authentication flag to false
                $auth_flag = false;

                // Check if username is empty
                if (empty(trim($_POST["username"]))) {
                    $username_error = "Please enter username.";
                } else {
                    $username = trim($_POST["username"]);
                }

                // Check if password is empty
                if (empty(trim($_POST["password"]))) {
                    $password_error = "Please enter your password.";
                    //log the result
                    error_log("Password is empty.");
                } else {
                    $password = trim($_POST["password"]);
                }

                // Validate credentials
                if (empty($username_error) && empty($password_error)) {
                    //initialize the user class
                    $user = new User();

                    //initialize the user login class
                    $userLogin = new UserLogin();

                    //check if the user exists by username, if so get the user ID
                    $user_id = $user->getUserIdByUsername($username);

                    //if the user exists, check the password
                    if ($user_id) {
                        //check the password
                        if ($user->validateUserPassword($user_id, $password)) {
                            //try to log the user in
                            try {
                                $userLogin->login($username, $password);
                            } catch (Exception $e) {
                                // Display a generic error message
                                $login_error = "Invalid username or password.";
                            } finally {
                                //check for an error message
                                if (!empty($login_error)) {
                                    //set the authentication flag to false
                                    $auth_flag = false;
                                } else {
                                    //set the authentication flag to true
                                    $auth_flag = true;
                                }
                            }
                        } else {
                            // Password is not valid, display a generic error message
                            $login_error = "Invalid password.";
                        }
                    } else {
                        // Username doesn't exist, display a generic error message
                        $login_error = "Invalid username.";
                    }
                } else {
                    // either username or password is not valid, display a generic error message
                    $login_error = "Either username or password is not valid.";
                }

                //if there are any errors, set the authentication flag to false
                if (!empty($username_error) || !empty($password_error) || !empty($login_error)) {
                    $auth_flag = false;
                    $errorArray = array(['username_error' => $username_error, 'password_error' => $password_error, 'login_error' => $login_error], ['username' => $username]);
                    //redirect to the login page with the error payload
                    performRedirect('/login.php?error=' . base64_encode(urlencode(json_encode($errorArray))));
                }

                if ($auth_flag === true) {
                    //set the SESSION variables
                    $_SESSION["user_id"] = $user_id;

                    //if the remember me checkbox is checked, set the cookies
                    if (!empty($_POST["remember"])) {
                        //set the randomization variables
                        $random_selector = randomizeEncryption(32, 32);
                        $random_password = randomizeEncryption(16, 16);

                        //hash the randomization variables
                        $random_selector_hash = password_hash($random_selector, PASSWORD_DEFAULT);
                        $random_password_hash = password_hash($random_password, PASSWORD_DEFAULT);

                        //set the cookie expiry date
                        $cookie_expiry_date = date("Y-m-d H:i:s", $expiration_time);

                        //set the cookies
                        setcookies($user_id, $username, $random_password_hash, $random_selector_hash, $cookie_expiry_date);

                        //expire the existing token if it exists
                        $userToken = $authenticator->getAuthenticationToken($user_id, $username, 0);
                        if ($userToken) {
                            $authenticator->expireToken($userToken[0]["id"]);
                        }

                        //create the token
                        $authenticator->createToken($user_id, $username, $random_password_hash, $random_selector_hash, $cookie_expiry_date);
                    } else {
                        //clear the cookies
                        clearCookies();
                    }
                    $_SESSION["user_id"] = $user_id;
                    $_SESSION["logged_in"] = true;
                    performRedirect('/admin/dashboard.php?login=success&u=' . base64_encode($user_id));
                } else {
                    //set the login error
                    $login_error = "Auth Flag is false.";
                }
            } else if ($_SESSION['logged_in'] === true) {
                //check if the user is already logged in, if so redirect to the admin dashboard
                performRedirect('/admin/dashboard.php');
            } else {
                //assume the user is not logged in and this a direct access to this index, redirect to the login page
                performRedirect('/login.php');
            }
            break;
        case 'false':
            //clear the session
            session_unset();
            //destroy the session
            session_destroy();
            //clear the cookies
            clearCookies();
            //redirect to the login page
            performRedirect('/login.php');
            break;
        default:
            //clear the session
            session_unset();
            //destroy the session
            session_destroy();
            //clear the cookies
            clearCookies();
            //redirect to the login page
            performRedirect('/login.php');
            break;
    }
};
