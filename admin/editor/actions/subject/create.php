<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
}

//include the permissions class
$permissionsObject = new Permission();

//include the auth class
$auth = new Authenticator();

//subject class
$subject = new AreaOfInterest();

//user class
$user = new User();

//get the action from the url parameter
$action = $_GET['action'];

/*confirm user has a role with create school permissions*/
//get the id of the create school permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('CREATE SCHOOL');

//boolean to track if the user has the create school permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
} else {

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the subject name from the form
        if (isset($_POST["subject_name"])) {
            $subject_name = trim($_POST["subject_name"]);
            //prepare the subject name
            $subject_name = prepareData($subject_name);
        }

        //if the action is create, create the subject
        if ($action == 'create') {
            //get current user ID
            $user_id = intval($_SESSION['user_id']);

            //create the subject
            $subjectCreated = $subject->addSubject($subject_name, $user_id);
        }
    } ?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-check"></i>
                        <?php
                        if ($action == 'create') {
                            if ($subjectCreated) {
                                echo 'Subject Created';
                            } else {
                                echo 'Error: Subject Not Created';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
