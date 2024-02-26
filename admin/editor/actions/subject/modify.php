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

//subject class
$subject = new AreaOfInterest();

//include the user class
$user = new User();

//include the session class
$session = new Session();

//get the action from the url parameter
$action = $_GET['action'];

//if the action is edit, get the subject id from the url parameter
if ($action == 'edit') {
    $subject_id = $_GET['id'];
}

/*confirm user has a role with update subject permissions*/
//get the id of the update subject permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('UPDATE SUBJECT');

//boolean to track if the user has the update subject permission
$hasPermission = $auth->checkUserPermission(intval($session->get('user_id')), $relevantPermissionID);

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

        //if the action is edit, edit the subject
        if ($action == 'edit') {
            //get current user ID
            $user_id = intval($session->get('user_id'));

            //boolean to track if the subject can be edited
            $canEdit = true;

            //check if the degree name is being changed
            if ($subject_name != $subject->getSubjectName($subject_id)) {
                //check if the subject already exists by name
                $existingSubjects = $subject->getAllSubjects(); //get all subjects currently in the database
                foreach ($existingSubjects as $existingSubject) {
                    //if the subject name already exists, set canEdit to false
                    if ($existingSubject['name'] == $subject_name) {
                        $canEdit = false;
                    }
                }
            }

            //if the subject can be edited, edit the subject
            if ($canEdit) {
                //update the subject
                $subjectUpdated = $subject->updateSubject($subject_id, $subject_name, $user_id);
            }
        }
    } ?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?php echo $subject_name ?></h1>
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <div>
                            <?php
                            if ($action == 'edit') {
                                if ($subjectUpdated) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Subject Updated';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Subject Not Updated';
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
                            if ($action == 'edit') {
                                if ($subjectUpdated) {
                                    echo '<p>The subject: ' . $subject_name . ' has been updated.</p>';
                                } else {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo '<p>The subject: ' . $subject_name . ' could not be updated.</p>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <!-- show error messages -->
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($action == 'edit') {
                                if (!$canEdit) {
                                    echo '<p>The subject: ' . $subject_name . ' cannot be updated because a subject with the same name already exists.</p>';
                                    echo '<p>Please enter a different subject name and try again.</p>';
                                } else if ($canEdit && !$subjectUpdated) {
                                    echo '<p>The subject: ' . $subject_name . ' could not be updated due to an unknown error.</p>';
                                } else {
                                    echo '<p>The subject: ' . $subject_name . ' has been updated.</p>';
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
                                if ($action == 'edit') {
                                    if ($subjectUpdated) {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=subjects&subject=list" class="btn btn-primary">Return to Subject List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=subjects&subject=single&id=' . $subject_id . '" class="btn btn-secondary">Go to Subject</a></span>';
                                    } else {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=subjects&subject=list" class="btn btn-primary">Return to Subject List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=subjects&subject=single&id=' . $subject_id . '" class="btn btn-secondary">Go to Subject</a></span>';
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
