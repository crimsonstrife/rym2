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

//include the authenticator class
$auth = new Authenticator();

//degree class
$degree = new Degree();

//user class
$user = new User();

//get the action from the url parameter
$action = $_GET['action'];

//if the action is edit, get the degree id from the url parameter
if ($action == 'edit') {
    $degree_id = $_GET['id'];
}

/*confirm user has a role with update degree permissions*/
//get the id of the update degree permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('UPDATE DEGREE');

//boolean to track if the user has the update degree permission
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
        //get the degree name from the form
        if (isset($_POST["degree_name"])) {
            $degree_name = trim($_POST["degree_name"]);
            //prepare the degree name
            $degree_name = prepareData($degree_name);
        }

        //if the action is edit, edit the degree
        if ($action == 'edit') {
            //get current user ID
            $user_id = intval($_SESSION['user_id']);

            //edit the degree
            $degreeUpdated = $degree->updateGrade($degree_id, $degree_name, $user_id);
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
                        if ($action == 'edit') {
                            if ($degreeUpdated) {
                                echo 'Degree Updated';
                            } else {
                                echo 'Error: Degree Not Updated';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
