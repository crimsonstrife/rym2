<?php
// Initialize the session
session_start();

// Include config file
require_once(__DIR__ . '/config/app.php');
// Include the helpers file
require_once(__DIR__ . '/includes/utils/helpers.php');
// Include the validation file
require_once(__DIR__ . '/includes/validateCookieSession.inc.php');

//include the authenticator class
$authenticator = new Authenticator();

// Check if the user is already logged in, if yes redirect to the admin dashboard
if ($logged_in === true) {
    performRedirect('/admin/dashboard.php');
}

// Define variables and initialize with empty values
$username = $password = "";
$username_error = $password_error = $login_error = "";

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
                } finally {
                    //check for an error message
                    if (empty($login_error)) {
                        //set the authentication flag to false
                        $auth_flag = false;
                    }
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
        performRedirect('/admin/dashboard.php');
    } else {
        //set the login error
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
                <label>Remember Me</label>
                <input type="checkbox" name="remember">
            </div>
            <div>
                <input type="submit" value="Login">
            </div>
        </form>
    </div>
</body>

</html>
