<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes redirect to the admin dashboard
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    header("location: admin/dashboard.php");
    exit;
}

// Include config file
require_once(__DIR__ . '/config/app.php');

// Define variables and initialize with empty values
$username = $password = "";
$username_error = $password_error = $login_error = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_error = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_error = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_error) && empty($password_error)) {
        //initialize the user class
        $user = new User();

        //check if the user exists by username, if so get the user ID
        $user_id = $user->getUserIdByUsername($username);

        //if the user exists, check the password
        if ($user_id) {
            //check the password
            if ($user->validateUserPassword($user_id, $password)) {
                //try to log the user in
                try {
                    $user->login($username, $password);
                } catch (Exception $e) {
                    // Log the error
                    error_log("Failed to log the user in: " . $e->getMessage());
                    // Display a generic error message
                    $login_error = "Invalid username or password.";
                }
            } else {
                // Password is not valid, display a generic error message
                $login_error = "Invalid username or password.";
            }
        } else {
            // Username doesn't exist, display a generic error message
            $login_error = "Invalid username or password.";
        }
    } else {
        // either username or password is not valid, display a generic error message
        $login_error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>

<body>
    <div>
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <?php
        if (!empty($login_error)) {
            echo '<div>' . $login_error . '</div>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $username; ?>">
                <span><?php echo $username_error; ?></span>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password">
                <span><?php echo $password_error; ?></span>
            </div>
            <div>
                <input type="submit" value="Login">
            </div>
        </form>
    </div>
</body>

</html>
