<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.

use function PHPSTORM_META\override;

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

//include the user class
$user = new User();

/*confirm user has a role with delete student permissions*/
//get the id of the delete student permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('DELETE STUDENT');

//boolean to track if the user has the delete student permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
} else {
    //student class
    $student = new Student();

    //event class
    $event = new Event();

    //student event class
    $studentEvent = new StudentEvent();

    //variables
    $action = null;
    $student_id = null;
    $override = null;

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the action from the url parameter
        $action = $_GET['action'];

        //if the action is delete, get the student id from the url parameter
        if ($action == 'delete') {
            $student_id = $_GET['id'];
        }

        //if the action is delete, check if the override parameter is set
        if ($action == 'delete') {
            if (isset($_GET['override'])) {
                $override = $_GET['override'];
            }
        }

        //get the intvalue of the student id
        $student_id = intval($student_id);

        //get the student name
        $student_name = $student->getStudentFullName($student_id);

        //boolean to track if the student can be deleted
        $canDelete = true;

        //check if the student has any event attendance records
        $studentHasEventAttendance = $studentEvent->getEventAttendaceByStudent($student_id);

        //if the student has more than 0 event attendance records, set the canDelete boolean to false
        if (count($studentHasEventAttendance) > 0) {
            $canDelete = false;
        }

        //check if the student has any records in their contact log
        $studentHasContactLog = $student->getStudentContactHistory($student_id);

        //if the student has more than 0 contact log records, set the canDelete boolean to false
        if (count($studentHasContactLog) > 0) {
            $canDelete = false;
        }

        //if the override parameter is set to true, set the canDelete boolean to true
        if ($override == 'true') {
            $canDelete = true;
        }

        //if the canDelete boolean is true, delete the student
        if ($canDelete) {
            $studentDeleted = $student->deleteStudent($student_id);
        } else {
            $studentDeleted = false;
        }
    } ?>
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
                                if ($studentDeleted) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Student Deleted';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Student Not Deleted';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- show completion message -->
                        <div class="col-md-12">
                            <?php
                            if ($action == 'delete') {
                                if ($studentDeleted) {
                                    echo '<p>The student ' . $student_name . ' has been deleted.</p>';
                                } else {
                                    echo '<p>The student ' . $student_name . ' could not be deleted.</p>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <!-- show error messages -->
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($action == 'delete') {
                                if (!$canDelete) {
                                    echo '<p>The student ' . $student_name . ' cannot be deleted because they have associated records in the system.</p>';
                                    echo '<p>Please delete the student\'s event attendance records and contact log records before attempting to delete the student.</p>';
                                    echo '<ul>';
                                    if (count($studentHasEventAttendance) > 0) {
                                        echo '<li>There are ' . strval(count($studentHasEventAttendance)) . ' events associated with the student</li>';
                                    }
                                    if (count($studentHasContactLog) > 0) {
                                        echo '<li>There are ' . strval(count($studentHasContactLog)) . ' contact records associated with the student</li>';
                                    }
                                    echo '</ul>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <!-- present option to delete all associated records if necessary -->
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($action == 'delete') {
                                if (!$canDelete) {
                                    echo '<p>If you would like to delete all associated records, click the button below.</p>';
                                    echo '<form action="' . APP_URL . '/admin/dashboard.php?view=students&student=single&action=delete&id=' . strval($student_id) . '&override=true" method="post">';
                                    echo '<input type="hidden" name="deleteAll" value="true">';
                                    echo '<button type="submit" class="btn btn-danger">Delete All Associated Records</button>';
                                    echo '</form>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <!-- show back buttons -->
                        <div class="col-md-12">
                            <?php
                            if ($action == 'delete') {
                                if ($studentDeleted) {
                                    echo '<a href="' . APP_URL . '/admin/dashboard.php?view=students&student=list" class="btn btn-primary">Return to Student List</a>';
                                } else {
                                    echo '<a href="' . APP_URL . '/admin/dashboard.php?view=students&student=list" class="btn btn-primary">Return to Student List</a>';
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
