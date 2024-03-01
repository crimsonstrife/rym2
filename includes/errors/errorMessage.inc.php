<?php

/**
 * Error Message Template
 * Used to display error messages to the user
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/16/2023
 *
 * @package RYM2
 * @version 1.0.0
 * @requires PHP 8.1.2+
 */

//include the permissions class
$permissionsObject = new Permission();

//auth class
$auth = new Authenticator();

//activity log class
$activityLog = new Activity();

//check for the type of error message to display
if (isset($thisError)) {
    $errorType = $thisError;
} else {
    $errorType = 'DEFAULT_ERROR';
}

//variable to hold the error array
$errorArray = array();
//variable to hold the error string
$errorString = '';
//variable to hold the error code
$errorCode = '';

//switch statement to determine the error message and code to display.
$errorArray = constant($errorType);

//set the error code and message
$errorCode = $errorArray['code'];
$errorString = $errorArray['message'];

//if the error string is not empty, display the error content
if ($errorString !== '' && $errorCode !== '') {

    //get the user id if it is set in the session
    if (isset($_SESSION['user_id'])) {
        $user_id = intval($_SESSION['user_id']);
    } else {
        $user_id = null;
    }

    //get the current URL if it is set
    if (isset($_SERVER['REQUEST_URI'])) {
        $currentURL = $_SERVER['REQUEST_URI'];
    } else {
        $currentURL = 'NULL';
    }

    //format the error message
    $errorMessage = 'CODE: [' . $errorCode . ']- AT: ' . $currentURL . '';

    //attempt to log the error
    try {
        //log the error
        $activityLog->logActivity($user_id, 'ERROR', $errorMessage);
    } catch (Exception $e) {
        //error logging failed, display the error message
        $errorMessage .= ' - ERROR LOGGING FAILED: ' . $e->getMessage();
    }

    //ensure the error also displays in the PHP error log, in case the panel is not visible
    error_log($errorMessage);
?>
    <!-- main content -->
    <div id="layout_content" class="w-95 mx-auto">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Error</h1>
                <div class="row">
                    <!-- Error Message -->
                    <div class="row">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                Look's like something went wrong...
                            </div>
                            <div class="card-body">
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <!-- Error Icon -->
                                    <div class="flex-shrink-0">
                                        <i class="fa-solid fa-exclamation"></i>
                                    </div>
                                    <!-- Error Code -->
                                    <div class="ms-3">
                                        <div class="fw-bold"><?php echo $errorCode; ?> ERROR</div>
                                    </div>
                                    <!-- Error Message -->
                                    <div class="ms-3">
                                        <?php echo $errorString; ?>
                                    </div>
                                </div>
                                <br>
                                <!-- Error Guidance -->
                                <div class="alert d-flex align-items-center" role="info">
                                    <!-- Error Icon -->
                                    <div class="flex-shrink-0">
                                        <i class="fa-solid fa-info"></i>
                                    </div>
                                    <!-- Error Guidance -->
                                    <div class="ms-3">
                                        <div class="fw-bold">What should I do?</div>
                                        <ul>
                                            <li>Try refreshing the page</li>
                                            <li>Try again later</li>
                                            <li>Report the error to the site administrator, providing the error code above
                                                will help them identify the problem.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Error Message -->
                </div>
            </div>
        </main>
    </div>
<?php } ?>
