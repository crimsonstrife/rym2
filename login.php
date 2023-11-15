<?php
define('CAN_INCLUDE', true); // Define a constant to control access to the include files

// Include config file
require_once(__DIR__ . '/config/app.php');
// Include the helpers file
require_once(__DIR__ . '/includes/utils/helpers.php');

// Check if the user is already logged in, if yes redirect to the admin dashboard
if (isset($_SESSION["logged_in"])) {
    //if the user is logged in, redirect to the admin dashboard
    if ($_SESSION["logged_in"] === true) {
        //is the user set?
        if (isset($_SESSION['user_id'])) {
            //get the user id
            $user_id = $_SESSION['user_id'];
            //redirect to the admin dashboard
            performRedirect('/admin/dashboard.php?login=success&u=' . base64_encode($user_id));
            exit;
        } else {
            //clear the session
            session_unset();
            //destroy the session
            session_destroy();
            //redirect to the login page
            performRedirect('/login.php?error=' . urlencode(base64_encode(json_encode(array('login_error' => 'Please Login.')))));
        }
    } else {
        //clear the session
        session_unset();
        //destroy the session
        session_destroy();
    }
}

// Define variables and initialize with empty values
$username = $password = "";
$username_error = $password_error = $login_error = "";

//check url parameters for error messages
if (isset($_GET['error'])) {
    $errorArray = base64_decode(urldecode(json_decode($_GET['error'], true)));
}

// if the error array is not empty, set the error messages
if (!empty($errorArray)) {
    if (isset($errorArray['username'])) {
        $username_error = $errorArray['username'];
    }
    if (isset($errorArray['username_error'])) {
        $username_error = $errorArray['username_error'];
    }
    if (isset($errorArray['password_error'])) {
        $password_error = $errorArray['password_error'];
    }
    if (isset($errorArray['login_error'])) {
        $login_error = $errorArray['login_error'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login</title>
    <?php echo includeHeader(); ?>
    <link href="<?php echo getAssetPath(); ?>css/login-style.css" rel="stylesheet">
</head>

<body class="text-center">
    <form class="form-signin" action="<?php echo APP_URL . '/admin/index.php?login=true' ?>" method="post">
        <h1 class="h3 mb-3 font-weight-normal">Login</h1>
        <p>Please fill in your credentials to login.</p>
        <?php
        if (!empty($login_error)) {
            echo '<div>' . $login_error . '</div>';
        }
        ?>
        <div>
            <label for="inputUsername" class="sr-only">Username</label>
            <input id="inputUsername" class="form-control" type="text" name="username" value="<?php echo $username; ?>" placeholder="Username" required autofocus>
            <span><?php echo $username_error; ?></span>
        </div>
        <div>
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
            <span><?php echo $password_error; ?></span>
        </div>
        <div class="checkbox mb-3">
            <label>Remember Me</label>
            <input type="checkbox" name="remember">
        </div>
        <div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        </div>
    </form>
</body>

</html>

<?php ?>
