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

//instance of the authentication class
$auth = new Authenticator();

//instance of the media class
$media = new Media();

//instance of the settings class
$settings = new Settings();

// Check if the user is already logged in, if yes redirect to the admin dashboard
if ($session->check('logged_in') === true) {
    //if the user is logged in, redirect to the admin dashboard
    if ($session->get('logged_in') === true) {
        //is the user set?
        if ($session->check('user_id') === true) {
            //get the user id
            $user_id = $session->get('user_id');
            //check for login tokens that are expired
            $haveTokensExpired = $auth->getExpiredTokens(intval($user_id));

            //if the user has expired tokens, clear them
            if ($haveTokensExpired !== false) {
                //should be an array, so loop through and clear the tokens
                foreach ($haveTokensExpired as $token) {
                    $auth->expireToken(intval($token['id']));
                }

                //clear expired tokens
                $auth->clearExpiredTokens(intval($user_id));

                //check for valid tokens
                //get username
                $username = $user->getUserUsername(intval($user_id));
                $validTokens = $auth->getAuthenticationToken(intval($user_id), $username, 0);

                //if the user has valid tokens, redirect to the admin dashboard
                if ($validTokens !== false) {
                    //redirect to the admin dashboard
                    performRedirect('/admin/dashboard.php?login=success&u=' . base64_encode($user_id));
                    exit;
                } else {
                    //clear the session
                    session_unset();
                    //destroy the session
                    session_destroy();
                    //redirect to the login page
                    performRedirect('/login.php?error=' . urlencode(base64_encode(json_encode(array('login_error' => 'Sessions have expired, Please Login.')))));
                }
            }
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
    $errorArray = base64_decode(urldecode(($_GET['error'])));
}
if (isset($_GET['msg'])) {
    $message = base64_decode(urldecode(($_GET['msg'])));
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

//get the application logo id
$logoID = $settings->getAppLogo();

//if the logo id is not empty, get the logo
if (!empty($logoID)) {
    $logo = $media->getMediaFilePath(intval($logoID));
} else {
    $logo = null;
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
                        <!-- Login Form -->
                        <form class="form-signin" action="<?php echo APP_URL . '/admin/index.php?login=true' ?>" method="post">
                            <h1 class="h3 mb-3 font-weight-normal">Login</h1>
                            <p>Please fill in your credentials to login.</p>
                            <!-- Display error or info messages -->
                            <?php if (isset($errorArray)) { ?>
                                <div class="alert d-flex align-items-center" role="info">
                                    <!-- Error Icon -->
                                    <div class="flex-shrink-0">
                                        <i class="fa-solid fa-info"></i>
                                    </div>
                                    <!-- Error Guidance -->
                                    <div class="ms-3">
                                        <div class="fw-bold">There's been an error.</div>
                                        <ul>
                                            <?php if (isset($errorArray['username_error'])) { ?>
                                                <li><?php echo htmlspecialchars($errorArray['username_error']); ?></li>
                                            <?php } ?>
                                            <?php if (isset($errorArray['password_error'])) { ?>
                                                <li><?php echo htmlspecialchars($errorArray['password_error']); ?></li>
                                            <?php } ?>
                                            <?php if (isset($errorArray['login_error'])) { ?>
                                                <li><?php echo htmlspecialchars($errorArray['login_error']); ?></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (isset($message)) { ?>
                                <div class="alert d-flex align-items-center" role="info">
                                    <!-- Error Icon -->
                                    <div class="flex-shrink-0">
                                        <i class="fa-solid fa-info"></i>
                                    </div>
                                    <!-- Error Guidance -->
                                    <div class="ms-3">
                                        <div class="fw-bold">Attention:</div>
                                        <ul>
                                            <?php if (isset($message)) { ?>
                                                <li><?php echo htmlspecialchars($message); ?></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label for="inputUsername" class="sr-only">Username</label>
                                <input id="inputUsername" class="form-control" type="text" name="username" value="<?php echo $username; ?>" placeholder="Username" required autofocus>
                                <span><?php echo htmlspecialchars($username_error); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword" class="sr-only">Password</label>
                                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
                                <span><?php echo htmlspecialchars($password_error); ?></span>
                            </div>
                            <div class="checkbox mb-3">
                                <label>Remember Me</label>
                                <input type="checkbox" name="remember">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
                            </div>
                            <p class="mt-5 mb-3 text-muted">Copyright &copy; Patrick Barnhardt 2023</p>
                            <!-- Forgot Password -->
                            <a href="<?php echo APP_URL . '/forgot-password.php' ?>">Forgot Password?</a>
                        </form>
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
