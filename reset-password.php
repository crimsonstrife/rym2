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

//instance of the user class
$user = new User();

//instance of the contact class
$contact = new Contact();

// Define variables and initialize with empty values
$tokenVerified = false;
$userID = $token = "";
$canSendEmail = false;
$allowReset = false;

//get the token from the url if it is set
if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    //check if the token is set in the post data
    if (isset($_POST['token'])) {
        $token = $_POST['token'];
    } else {
        //if the token is not set, redirect to the login page
        performRedirect('/login.php?error=' . urlencode(base64_encode(json_encode(array('login_error' => 'No reset token.')))));
    }
}

//get the user id from the url if it is set
if (isset($_GET['id'])) {
    $userID = $_GET['id'];
} else {
    //check if the user id is set in the post data
    if (isset($_POST['id'])) {
        $userID = $_POST['id'];
    } else {
        //if the user id is not set, redirect to the login page
        performRedirect('/login.php?error=' . urlencode(base64_encode(json_encode(array('login_error' => 'No user id.')))));
    }
}

//escape the user id
$userID = htmlspecialchars(strip_tags($userID));

//escape the token
$token = htmlspecialchars(strip_tags($token));

//get the user's stored token
$storedToken = $user->getPasswordResetToken(intval($userID));

//verify the token
if ($token !== $storedToken) {
    //if the token is not valid, redirect to the login page
    performRedirect('/login.php?error=' . urlencode(base64_encode(json_encode(array('login_error' => 'Invalid reset token.')))));
} else {
    //if the token is valid, set tokenVerified to true
    $tokenVerified = true;
}

//check if the mail_mailer constant is defined
if (defined('MAIL_MAILER')) {
    //check if the mail_mailer constant is set to smtp
    if (MAIL_MAILER === 'smtp') {
        //check if the bare minimum smtp constants are defined
        if (defined('MAIL_HOST') && defined('MAIL_PORT') && defined('MAIL_FROM_ADDRESS') && defined('MAIL_FROM_NAME')) {
            //check if the bare minimum smtp constants are not empty
            if (MAIL_HOST !== "" && MAIL_PORT !== "" && MAIL_FROM_ADDRESS !== "" && MAIL_FROM_NAME !== "") {
                $canSendEmail = true;
            } else {
                //if the smtp constants are empty, set canSendEmail to false
                $canSendEmail = false;
            }
        } else {
            //if the smtp constants are not defined, set canSendEmail to false
            $canSendEmail = false;
        }
    } else {
        //currently, only smtp is supported, so if the mail_mailer constant is not set to smtp, set canSendEmail to false
        $canSendEmail = false;
    }
}

//if the token is valid, check if email can be sent, if so show a form to approve the password reset
if ($canSendEmail && $tokenVerified) {
    //check if the user has approved the password reset
    if (isset($_POST['approve'])) {
        //approve the password reset
        $allowReset = true;

        //if the password reset is approved, generate a new password
        if ($allowReset) {
            //generate a new password
            $newPassword = $user->generatePassword();

            //update the user's password - it will be hashed
            $user->setUserPassword(intval($userID), $newPassword);

            //get the user's email address
            $email = $user->getUserEmail(intval($userID));

            //set the email subject
            $subject = "Your password has been reset";

            //set the email message
            $message = "Your password has been reset. Your new password is: " . $newPassword;

            //send the email
            $contact->sendUserEmail($email, $subject, $message);

            //clear the password reset token
            $user->clearPasswordResetToken(intval($userID));

            //redirect to the login page
            performRedirect('/login.php?error=' . urlencode(base64_encode(json_encode(array('login_error' => 'Password reset approved.')))));
        }
    }
    if (isset($_POST['deny'])) {
        //clear the password reset token
        $user->clearPasswordResetToken(intval($userID));
        //deny the password reset
        performRedirect('/login.php?error=' . urlencode(base64_encode(json_encode(array('login_error' => 'Password reset denied.')))));
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Reset Password</title>
        <?php echo includeHeader(); ?>
        <?php //if login-style.min.css exists, use it, otherwise use login-style.css
        if (file_exists(BASEPATH . '/public/content/assets/css/login-style.min.css')) {
            echo '<link rel="stylesheet" href="' . htmlspecialchars(getAssetPath()) . 'css/login-style.min.css">';
        } else {
            echo '<link rel="stylesheet" href="' . htmlspecialchars(getAssetPath()) . 'css/login-style.css">';
        } ?>
    </head>

    <body class="text-center">
        <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?token=' . htmlspecialchars($token) . '&id=' . htmlspecialchars($userID); ?>" method="post">
            <h1 class="h3 mb-3 font-weight-normal">Reset Password</h1>
            <p>Are you sure you want to reset your password?</p>
            <p>A new password will be generated and emailed to you, this cannot be undone.</p>
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="hidden" name="id" value="<?php echo $userID; ?>">
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="approve">Yes</button>
            <button class="btn btn-lg btn-secondary btn-block" type="button" name="deny">No</button>
        </form>
    </body>

    <?php } else {
    if (!$canSendEmail) { ?>
        <div class="alert alert-danger" role="alert">
            <p>Sorry, the system is not properly configured to send emails. Please contact the system administrator.</p>
        </div>
    <?php } else { ?>
        <div class="alert alert-danger" role="alert">
            <p>Sorry, the token is not valid.</p>
        </div>
<?php }
} ?>
