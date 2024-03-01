<?php
define('CAN_INCLUDE', true); // Define a constant to control access to the include files

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

// Check if the user is already logged in, if yes redirect to the admin dashboard
if ($session->get('logged_in') === true) {
    performRedirect('/admin/dashboard.php');
    exit;
}

// Define variables and initialize with empty values
$username = $password = $email = $userID = "";
$username_error = $password_error = $email_error = $requestError = "";
$canChangePassword = false;
$canSendEmail = false;

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

//Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_error = "Please enter your username or email address.";
    } else {
        $username = trim($_POST["username"]);
    }
    //escape the username
    $username = htmlspecialchars($username);

    //check if the username is an email address
    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $email = $username;
        $username = "";
    }

    //check if email or username is set
    if ($email != "" || $username != "") {
        //check if the email or username exists
        if ($email != "") {
            //check if the email exists
            $userID = $user->getUserByEmail($email);
        } else {
            //check if the username exists
            $userID = $user->getUserIdByUsername($username);
        }

        //check if the user id is not empty
        if ($userID != "" || $userID != null) {
            //approve the password change request
            $canChangePassword = true;
        }

        //check if password change request is approved
        if ($canChangePassword) {
            //create a password reset token, and send the user an email with the reset link to approve the generation of a new password
            //if openssl_random_pseudo_bytes is available, use it, otherwise use uniqid
            if (function_exists('openssl_random_pseudo_bytes')) {
                $token = bin2hex(openssl_random_pseudo_bytes(16));
            } else {
                $token = bin2hex(uniqid());
            }

            //set the password reset token
            $user->setPasswordResetToken(intval($userID), $token);

            //send the user an email with the reset link
            $email = $user->getUserEmail(intval($userID));

            //set the email subject
            $subject = "Password Reset Request";

            //set the email message
            $message = "Hello, <br><br> We received a request to reset your password. If you made this request, click the link below to reset your password. <br><br> <a href='" . APP_URL . "/reset-password.php?token=" . $token . "&user=" . $userID . "'>Reset Password</a> <br><br> If you did not make this request, you can ignore this email. <br><br>";

            //send the email
            $contact->sendUserEmail($email, $subject, $message, true);

            //redirect the user to the login page
            performRedirect('/login.php?error=' . urlencode(base64_encode(json_encode(array('login_error' => 'A password reset link has been sent to your email address.')))));
        } else {
            $requestError = "Sorry, we couldn't find an account with that username or email address.";
        }
    } else {
        $requestError = "Please enter your username or email address.";
    }
} ?>
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
    <div id="layout_content" class="w-50 mx-auto" style="margin-top: auto;">
        <main>
            <div class="container-fluid px-4">
                <div class="row align-items-center">
                    <!-- Application Logo -->
                    <?php if (!empty($logo) || $logo != null) { ?>
                        <img class="mb-4" src="<?php echo htmlspecialchars($logo); ?>" alt="Application Logo" width="150" height="150">
                    <?php } else { ?>
                        <i class="fa-solid fa-user-lock fa-5x"></i>
                    <?php } ?>
                </div>
                <div class="row align-items-center">
                    <div class="card mb-4">
                        <!-- Forgot Password Form -->
                        <?php
                        //check if email can be sent
                        if ($canSendEmail) { ?>
                            <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <h1 class="h3 mb-3 font-weight-normal">Forgot Password</h1>
                                <p>Enter your username or email address and we'll send you a link to reset your password.</p>
                                <div class="form-group">
                                    <input type="text" name="username" class="form-control" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>">
                                    <span class="help-block">Enter your username or email address</span>
                                    </span><?php echo $username_error; ?></span>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Submit">
                                </div>
                            </form>
                        <?php } else { ?>
                            <div class="alert alert-danger" role="alert">
                                <p>Sorry, the system is not properly configured to send emails. Please contact the system administrator.</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
<footer>
    <?php echo includeFooter(); ?>
</footer>

</html>
<?php ?>
