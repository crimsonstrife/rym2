<?php
//include the permissions class
$permissionsObject = new Permission();

//auth class
$auth = new Authenticator();

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
switch ($errorType) {
    case 'PERMISSION_ERROR_ACCESS':
        $errorArray = PERMISSION_ERROR_ACCESS;
        break;
    case 'CONFIGURATION_ERROR':
        $errorArray = CONFIGURATION_ERROR;
        break;
    case 'INVALID_REQUEST_ERROR':
        $errorArray = INVALID_REQUEST_ERROR;
        break;
    case 'ROUTING_ERROR':
        $errorArray = ROUTING_ERROR;
        break;
    case 'DATABASE_ERROR':
        $errorArray = DATABASE_ERROR;
        break;
    case 'AUTHENTICATION_ERROR':
        $errorArray = AUTHENTICATION_ERROR;
        break;
    case 'AUTHORIZATION_ERROR':
        $errorArray = AUTHORIZATION_ERROR;
        break;
    case 'VALIDATION_ERROR':
        $errorArray = VALIDATION_ERROR;
        break;
    case 'SUDDEN_ERROR':
        $errorArray = SUDDEN_ERROR;
        break;
    case 'DEFAULT_ERROR':
        $errorArray = DEFAULT_ERROR;
        break;
    case 'TIMEOUT':
        $errorArray = TIMEOUT;
        break;
    case 'RESTART':
        $errorArray = RESTART;
        break;
    case 'NOT_FOUND':
        $errorArray = NOT_FOUND;
        break;
    case 'NOT_IMPLEMENTED':
        $errorArray = NOT_IMPLEMENTED;
        break;
    case 'SERVICE_UNAVAILABLE':
        $errorArray = SERVICE_UNAVAILABLE;
        break;
    case 'CRITICAL':
        $errorArray = CRITICAL;
        break;
    case 'INVALID_USER_REQUEST':
        $errorArray = INVALID_USER_REQUEST;
        break;
    default:
        $errorArray = DEFAULT_ERROR;
        break;
}

//set the error code and message
$errorCode = $errorArray['code'];
$errorString = $errorArray['message'];

//if the error string is not empty, display the error content
if ($errorString !== '' && $errorCode !== '') { ?>
    <!-- main content -->
    <div id="layout_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Error</h1>
                <div class="row">
                    <!-- Error Message -->
                    <div class="row">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fa-solid fa-table"></i>
                                Error Message
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="dataTable" class="table">
                                        <thead>
                                            <tr>
                                                <th>Error Code</th>
                                                <th>Error Message</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?php echo $errorCode; ?></td>
                                                <td><?php echo $errorString; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
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
