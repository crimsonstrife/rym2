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

/*confirm user has a role with delete major permissions*/
//get the id of the delete major permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('DELETE MAJOR');

//boolean to track if the user has the delete major permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
} else {
    //degree class
    $major = new Degree();

    //student class
    $student = new Student();

    //student education class
    $studentEducation = new StudentEducation();

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the action from the url parameter
        $action = $_GET['action'];

        //if the action is delete, get the major id from the url parameter
        if ($action == 'delete') {
            $major_id = $_GET['id'];
        }

        //get the intvalue of the major id
        $major_id = intval($major_id);

        //get the major name
        $major_name = $major->getMajorNameById($major_id);

        //boolean to track if the major can be deleted
        $canDelete = true;

        //check if there are any students associated with the major in the student table
        $studentsWithMajor = $studentEducation->getStudentsByMajor($major_id);

        //if there are more than 0 records in the array, the major cannot be deleted so set the canDelete boolean to false
        if (count($studentsWithMajor) > 0) {
            $canDelete = false;
        }

        //if the canDelete boolean is true, delete the major
        if ($canDelete) {
            $majorDeleted = $major->deleteMajor($major_id);
        } else {
            $majorDeleted = false;
        }
    }
?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <div>
                            <?php
                            if ($action == 'delete') {
                                if ($majorDeleted) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Major Deleted';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Major Not Deleted';
                                }
                            }
                            ?>
                        </div>
                        <div>
                            <?php
                            if ($action == 'delete') {
                                if ($canDelete && !$majorDeleted) {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo 'The major: ' . $major_name . ', could not be deleted because of an unknown error.';
                                } else if (!$canDelete && !$majorDeleted) {
                                    echo 'The major: ' . $major_name . ', could not be deleted because of an error: ';
                                    echo '<ul>';
                                    if (count($studentsWithMajor) > 0) {
                                        echo '<li>There are ' . strval(count($studentsWithMajor)) . ' students associated with the major</li>';
                                    }
                                    echo '</ul>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
