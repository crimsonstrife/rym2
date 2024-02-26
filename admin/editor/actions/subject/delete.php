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

//include the session class
$session = new Session();

/*confirm user has a role with delete subject permissions*/
//get the id of the delete subject permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('DELETE SUBJECT');

//boolean to track if the user has the delete subject permission
$hasPermission = $auth->checkUserPermission(intval($session->get('user_id')), $relevantPermissionID);

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
        <h1 class="mt-4"><?php echo $subject_name; ?></h1>
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
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- show completion message -->
                        <div class="col-md-12">
                            <?php
                            if ($action == 'delete') {
                                if ($subjectDeleted) {
                                    echo '<p>The subject: ' . $subject_name . ' has been deleted.</p>';
                                } else {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo '<p>The subject: ' . $subject_name . ' could not be deleted.</p>';
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
                                    echo '<p>The subject: ' . $subject_name . ' cannot be deleted because they have associated records in the system.</p>';
                                    echo '<p>Please delete the subject\'s associated student or job records or re-associated them to other subjects before attempting to delete this one.</p>';
                                    echo '<ul>';
                                    if (count($studentsWithInterest) > 0) {
                                        echo '<li>There are ' . strval(count($studentsWithInterest)) . ' students associated with the subject</li>';
                                    }
                                    if (count($jobsInField) > 0) {
                                        echo '<li>There are ' . strval(count($jobsInField)) . ' jobs associated with the subject</li>';
                                    }
                                    echo '</ul>';
                                } else if ($canDelete && !$subjectDeleted) {
                                    echo '<p>The subject: ' . $subject_name . ' could not be deleted, due to an unknown error.</p>';
                                } else {
                                    echo '<p>All associated records for the subject: ' . $subject_name . ' have been deleted.</p>';
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
                                    if ($subjectDeleted) {
                                        echo '<a href="' . APP_URL . '/admin/dashboard.php?view=subjects&subject=list" class="btn btn-primary">Return to Subject List</a>';
                                    } else {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=subjects&subject=list" class="btn btn-primary">Return to Subject List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=subjects&subject=single&id=' . $subject_id . '" class="btn btn-secondary">Return to Subject</a></span>';
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
