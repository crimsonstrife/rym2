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

//include the user class
$user = new User();

//include the session class
$session = new Session();

/*confirm user has a role with delete school permissions*/
//get the id of the delete school permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('DELETE SCHOOL');

//boolean to track if the user has the delete school permission
$hasPermission = $auth->checkUserPermission(intval($session->get('user_id')), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
} else {
    //school class
    $school = new School();

    //student class
    $student = new Student();

    //student education class
    $studentEducation = new StudentEducation();

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

        //get the school name
        $school_name = $school->getSchoolName($school_id);

        //boolean to track if the school can be deleted
        $canDelete = true;

        //check if there are any students associated with the school in the student table
        $studentsAtSchool = $studentEducation->getStudentsBySchool($school_id);

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
        <h1 class="mt-4"><?php echo $school_name; ?></h1>
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
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- show completion message -->
                        <div class="col-md-12">
                            <?php
                            if ($action == 'delete') {
                                if ($schoolDeleted) {
                                    echo '<p>The school: ' . $school_name . ' has been deleted.</p>';
                                } else {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo '<p>The school: ' . $school_name . ' could not be deleted.</p>';
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
                                    echo '<p>The school: ' . $school_name . ' cannot be deleted because they have associated records in the system.</p>';
                                    echo '<p>Please delete the school\'s associated student and event records or re-associated them to others before attempting to delete this one.</p>';
                                    echo '<ul>';
                                    if (count($studentsAtSchool) > 0) {
                                        echo '<li>There are ' . strval(count($studentsAtSchool)) . ' students associated with the school</li>';
                                    }
                                    if (count($eventsAtSchool) > 0) {
                                        echo '<li>There are ' . strval(count($eventsAtSchool)) . ' events associated with the school</li>';
                                    }
                                    echo '</ul>';
                                } else if ($canDelete && !$schoolDeleted) {
                                    echo '<p>The school: ' . $school_name . ' could not be deleted, due to an unknown error.</p>';
                                } else {
                                    echo '<p>All associated records for the school: ' . $school_name . ' have been deleted.</p>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <!-- show back buttons -->
                        <div class="col-md-12">
                            <div class="card-buttons">
                                <?php
                                if ($action == 'delete') {
                                    if ($schoolDeleted) {
                                        echo '<a href="' . APP_URL . '/admin/dashboard.php?view=schools&school=list" class="btn btn-primary">Return to School List</a>';
                                    } else {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=schools&school=list" class="btn btn-primary">Return to School List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=schools&school=single&id=' . $school_id . '" class="btn btn-secondary">Return to School</a></span>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
