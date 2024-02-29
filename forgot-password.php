<?php
define('CAN_INCLUDE', true); // Define a constant to control access to the include files

// Initialize the session
session_start();

// Include config file
require_once(__DIR__ . '/config/app.php');
// Include the helpers file
require_once(__DIR__ . '/includes/utils/helpers.php');

//instance of the session class
$session = new Session();

// Check if the user is already logged in, if yes redirect to the admin dashboard
if ($session->get('logged_in') === true) {
    performRedirect('/admin/dashboard.php');
    exit;
}

// Define variables and initialize with empty values
$username = $password = $email = "";
$username_error = $password_error = $email_error = $requestError = "";
$canChangePassword = false;
$canSendEmail = false;


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Forgot Password</title>
    <?php echo includeHeader(); ?>
    <?php //if login-style.min.css exists, use it, otherwise use login-style.css
    if (file_exists(BASEPATH . '/public/content/assets/css/login-style.min.css')) {
        echo '<link rel="stylesheet" href="' . htmlspecialchars(getAssetPath()) . 'css/login-style.min.css">';
    } else {
        echo '<link rel="stylesheet" href="' . htmlspecialchars(getAssetPath()) . 'css/login-style.css">';
    } ?>
</head>

<body class="text-center">
</body>

<footer>
    <?php echo includeFooter(); ?>
</footer>

</html>

<?php ?>
