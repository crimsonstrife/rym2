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

/*confirm user has a role with delete subject permissions*/
//get the id of the delete subject permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('DELETE SUBJECT');

//boolean to track if the user has the delete subject permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
} else {
    //subject class
    $subject = new AreaOfInterest();

    //student class
    $student = new Student();

    //job class
    $jobs = new Job();

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the action from the url parameter
        $action = $_GET['action'];

        //if the action is delete, get the subject id from the url parameter
        if ($action == 'delete') {
            $subject_id = $_GET['id'];
        }

        //get the intvalue of the subject id
        $subject_id = intval($subject_id);

        //get the subject name
        $subject_name = $subject->getSubjectName($subject_id);

        //boolean to track if the subject can be deleted
        $canDelete = true;

        //check if there are any students associated with the subject in the student table
        $studentsWithInterest = $student->getStudentsByInterest($subject_id);

        //if there are more than 0 records in the array, the subject cannot be deleted so set the canDelete boolean to false
        if (count($studentsWithInterest) > 0) {
            $canDelete = false;
        }

        //check if there are any jobs associated with the subject in the job table
        $jobsInField = $jobs->getJobsByField($subject_id);

        //if there are more than 0 records in the array, the subject cannot be deleted so set the canDelete boolean to false
        if (count($jobsInField) > 0) {
            $canDelete = false;
        }

        //if the canDelete boolean is true, delete the subject
        if ($canDelete) {
            $subjectDeleted = $subject->deleteSubject($subject_id);
        } else {
            $subjectDeleted = false;
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
                                if ($subjectDeleted) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Subject Deleted';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Subject Not Deleted';
                                }
                            }
                            ?>
                        </div>
                        <div>
                            <?php
                            if ($action == 'delete') {
                                if ($canDelete && !$subjectDeleted) {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo 'The subject: ' . $subject_name . ', could not be deleted because of an unknown error.';
                                } else if (!$canDelete && !$subjectDeleted) {
                                    echo 'The subject: ' . $subject_name . ', could not be deleted because of an error: ';
                                    echo '<ul>';
                                    if (count($studentsWithInterest) > 0) {
                                        echo '<li>There are ' . strval(count($studentsWithInterest)) . ' students associated with the subject</li>';
                                    }
                                    if (count($jobsInField) > 0) {
                                        echo '<li>There are ' . strval(count($jobsInField)) . ' jobs associated with the subject</li>';
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
