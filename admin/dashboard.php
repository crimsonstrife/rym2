<?php
// Initialize the session
session_start();

// Include config file
require_once(__DIR__ . '../../config/app.php');

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("location: " . APP_URL . "/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>

<body>
    <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to the dashboard.</h1>
    <p>
        <!-- Reset password link -->
        <a href="<?php echo APP_URL; ?>/reset-password.php">Reset Your Password</a>
        <!-- Logout link -->
        <a href="<?php echo APP_URL; ?>/logout.php">Sign Out of Your Account</a>
    </p>
</body>

</html>
