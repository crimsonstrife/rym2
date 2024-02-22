<?php

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

// Initialize the session
session_start();

define('CAN_INCLUDE', true); // Define a constant to control access to the include files

// Include config file
require_once(__DIR__ . '../../config/app.php');

//include the permissions class
$permissionsObject = new Permission();

//auth class
$auth = new Authenticator();

//user class
$user = new User();

//session class
$session = new Session();

//check post request for login status and uid.
if (isset($_GET['login']) && isset($_GET['u'])) {
    switch ($_GET['login']) {
        case 'success':
            //set the login status to true
            $session->set('logged_in', true);
            //set the user id
            $session->set('user_id', base64_decode($_GET['u']));
            break;
        default:
            //set the login status to false
            $session->set('logged_in', false);
            //set the user id to null
            $session->set('user_id', null);
            break;
    }
    if ($session->check('logged_in') === true && $session->get('logged_in') === true) {
        //include the validation file
        require_once(__DIR__ . '../../includes/validateCookieSession.inc.php');
        $logged_in = true;
    } else {
        $logged_in = false;
    }
}

// Check if the user is already logged in, if yes redirect to the admin dashboard
if ($session->check('user_id') === true) {
    // Define a constant to control the access of the include files
    define('ISVALIDUSER', true); // idea from https://stackoverflow.com/a/409515 (user UnkwnTech)

    //get the id of the view dashboard permission
    $viewDashboardPermissionId = $permissionsObject->getPermissionIdByName('VIEW DASHBOARD');

    //boolean to check if the user has the view dashboard permission
    $hasViewDashboardPermission = $auth->checkUserPermission(intval($session->get('user_id')), $viewDashboardPermissionId);

    //if the user does not have the view dashboard permission, prevent access to the dashboard
    if (!$hasViewDashboardPermission) {
        //include the header
        include_once('./header.php');
        //set the error type
        $thisError = 'DASHBOARD_PERMISSION_ERROR'; ?>
<div id="layout">
    <?php
            //include the sidebar
            include_once('./sidebar.php');

            //include the error message file
            include_once(__DIR__ . '../../includes/errors/errorMessage.inc.php');

            //include the footer
            include_once('./footer.php'); ?>
</div>
<?php } else {
        //include the header
        include_once('./header.php');
    ?>
<div id="layout" class="w-75 mx-auto">
    <?php
            //include the sidebar
            include_once('./sidebar.php');

            //switch content based on the parameter in the url
            if (isset($_GET['view'])) {
                switch ($_GET['view']) {
                    case 'events':
                        include_once('content/events_content.php');
                        break;
                    case 'schools':
                        include_once('content/schools_content.php');
                        break;
                    case 'majors':
                        include_once('content/majors_content.php');
                        break;
                    case 'degrees':
                        include_once('content/degrees_content.php');
                        break;
                    case 'users':
                        include_once('content/users_content.php');
                        break;
                    case 'roles':
                        include_once('content/roles_content.php');
                        break;
                    case 'settings':
                        include_once('content/settings_content.php');
                        break;
                    case 'jobs':
                        include_once('content/jobs_content.php');
                        break;
                    case 'subjects':
                        include_once('content/subjects_content.php');
                        break;
                    case 'reports':
                        include_once('content/reports_content.php');
                        break;
                    case 'students':
                        include_once('content/students_content.php');
                        break;
                    case 'contact-log':
                        include_once('content/contactlog_content.php');
                        break;
                    case 'activity-log':
                        include_once('content/activitylog_content.php');
                        break;
                    case 'search':
                        include_once('search.php');
                        break;
                    case 'media':
                        include_once('content/media_content.php');
                        break;
                    default:
                        include_once('content/admin_content.php');
                        break;
                }
            } else {
                include_once('content/admin_content.php');
            } ?>
</div>
<?php
        //include the footer
        include_once('./footer.php');
    }
} else {
    performRedirect('/login.php');
}
