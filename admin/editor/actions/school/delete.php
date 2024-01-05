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

/*confirm user has a role with delete school permissions*/
//get the id of the delete school permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('DELETE SCHOOL');

//boolean to track if the user has the delete school permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    die('Error: You do not have permission to perform this request.');
} else {
    //school class
    $school = new School();

    //student class
    $student = new Student();

    //event class
    $event = new Event();

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the action from the url parameter
        $action = $_GET['action'];

        //if the action is delete, get the school id from the url parameter
        if ($action == 'delete') {
            $school_id = $_GET['id'];
        }

        //get the intvalue of the school id
        $school_id = intval($school_id);

        //boolean to track if the school can be deleted
        $canDelete = true;

        //check if there are any students associated with the school in the student table
        $studentsAtSchool = $student->getStudentsBySchool($school_id);

        //if there are more than 0 records in the array, the school cannot be deleted so set the canDelete boolean to false
        if (count($studentsAtSchool) > 0) {
            $canDelete = false;
        }

        //check if there are any events associated with the school in the event table
        $eventsAtSchool = $event->getEventsByLocation($school_id);

        //if there are more than 0 records in the array, the school cannot be deleted so set the canDelete boolean to false
        if (count($eventsAtSchool) > 0) {
            $canDelete = false;
        }

        //if the canDelete boolean is true, delete the school
        if ($canDelete) {
            $schoolDeleted = $school->deleteSchool($school_id);
        } else {
            $schoolDeleted = false;
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
                                if ($schoolDeleted) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'School Deleted';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: School Not Deleted';
                                }
                            }
                            ?>
                        </div>
                        <div>
                            <?php
                            if ($action == 'delete') {
                                if ($canDelete && !$schoolDeleted) {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo 'The school: ' . $school_name . ', could not be deleted because of an unknown error.';
                                } else if (!$canDelete && !$schoolDeleted) {
                                    echo 'The school: ' . $school_name . ', could not be deleted because of an error: ';
                                    echo '<ul>';
                                    if (count($studentsAtSchool) > 0) {
                                        echo '<li>There are ' . strval(count($studentsAtSchool)) . ' students associated with the school</li>';
                                    }
                                    if (count($eventsAtSchool) > 0) {
                                        echo '<li>There are ' . strval(count($eventsAtSchool)) . ' events associated with the school</li>';
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
