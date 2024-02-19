<?php
// Include config file
require_once(__DIR__ . '/config/app.php');
// Include the user class
require_once(BASEPATH . '/includes/classes/users.inc.php');

// Instantiate the user login class
$login = new UserLogin();

// Log the user out
$login->logout();

exit;
