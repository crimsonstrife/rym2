<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
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
    die('Error: You do not have permission to perform this request.');
} else {
    //student class
    $student = new Student();

    //event class
    $event = new Event();

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the action from the url parameter
        $action = $_GET['action'];

        //if the action is delete, get the student id from the url parameter
        if ($action == 'delete') {
            $student_id = $_GET['id'];
        }

        //get the intvalue of the student id
        $student_id = intval($student_id);

        //get the student name
        $student_name = $student->getStudentFullName($student_id);

        //boolean to track if the student can be deleted
        $canDelete = true;

        //check if the student has any event attendance records
        $studentHasEventAttendance = $student->getEventAttendaceByStudent($student_id);

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
                    <div>
                        <?php
                            if ($action == 'delete') {
                                if ($canDelete && !$studentDeleted) {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo 'The student: ' . $student_name . ', could not be deleted because of an unknown error.';
                                } else if (!$canDelete && !$studentDeleted) {
                                    echo 'The student: ' . $student_name . ', could not be deleted because of an error: ';
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
            </div>
        </div>
    </div>
</div>
<?php } ?>
