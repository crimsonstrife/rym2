<?php
// Initialize the session
session_start();

//instance of the session class
$session = new Session();

// Check if the user is logged in, otherwise redirect to login page
if ($session->check('logged_in') !== true || $session->get('logged_in') !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once(__DIR__ . '/config/app.php');
// Include the user class
require_once(BASEPATH . '/includes/classes/users.inc.php');

// Define variables and initialize with empty values
$current_password__error = "";
$current_password = "";
$new_password = $confirm_password = "";
$new_password_error = $confirm_password_error = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate current password
    if (empty(trim($_POST["current_password"]))) {
        $current_password_error = "Please enter the current password.";
    } else {
        $current_password = trim($_POST["current_password"]);
    }

    // Validate new password
    if (empty(trim($_POST["new_password"]))) {
        $new_password_error = "Please enter the new password.";
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $new_password_error = "Password must have at least 6 characters.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_error = "Please confirm the password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_error)  && ($new_password != $confirm_password)) {
            $confirm_password_error = "Password did not match.";
        }
    }

    // Check input errors, and to see if the current password matches the database before updating the database to the new password
    if (empty($current_password_error) && empty($new_password_error) && empty($confirm_password_error)) {
        //instance of the user class
        $user = new User();

        //instance of the user login class
        $userLogin = new UserLogin();

        // Get the user's information from the database
        $currentUser = $user->getUserById(intval($session->get('user_id')));

        // Check if the password entered matches the current hashed password in the database
        if ($userLogin->validateUserPassword(intval($session->get('user_id')), $current_password)) {
            // Password is correct, update the database with the new password
            $user->setUserPassword(intval($session->get('user_id')), $new_password);

            // Log the user out
            $userLogin->logout();

            // Redirect the user to the login page
            performRedirect(APP_URL . "/login.php");
            exit();
        } else {
            // Display an error message if the password entered does not match the current password in the database
            $current_password_error = "The password you entered was not valid.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>

<body>
    <div>
        <h2>Reset Password</h2>
        <p>Please fill out this form to reset your password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>Current Password</label>
                <input type="password" name="current_password" <?php echo (!empty($current_password_error)) ? 'is-invalid' : ''; ?>">
                <span><?php echo $current_password__error; ?></span>
            </div>
            <div>
                <label>New Password</label>
                <input type="password" name="new_password" <?php echo (!empty($new_password_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                <span><?php echo $new_password__error; ?></span>
            </div>
            <div>
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" <?php echo (!empty($confirm_password_error)) ? 'is-invalid' : ''; ?>">
                <span><?php echo $confirm_password__error; ?></span>
            </div>
            <div>
                <input type="submit" value="Submit">
                <a href="/admin/dashboard.php">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>
