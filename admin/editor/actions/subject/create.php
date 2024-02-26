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

//include the user class
$user = new User();

//include the session class
$session = new Session();

//get the action from the url parameter
$action = $_GET['action'];

/*confirm user has a role with create school permissions*/
//get the id of the create school permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('CREATE SCHOOL');

//boolean to track if the user has the create school permission
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

        //if the action is create, create the subject
        if ($action == 'create') {
            //get current user ID
            $user_id = intval($session->get('user_id'));

            //boolean to track if the subject can be created
            $canCreate = true;

            //check if the subject already exists by name
            $existingSubjects = $subject->getAllSubjects();
            foreach ($existingSubjects as $existingSubject) {
                if ($existingSubject['name'] == $subject_name) {
                    $canCreate = false;
                }
            }

            //Placeholder for the subject id
            $subject_id = null;

            //if the subject can be created, create the subject
            if ($canCreate) {
                $subjectCreated = $subject->addSubject($subject_name, $user_id);
            }

            //if the subject was created, get the subject id
            if ($subjectCreated) {
                //get all subjects
                $subjects = $subject->getAllSubjects();
                //get the subject id by finding the subject with the matching name
                foreach ($subjects as $subject) {
                    if ($subject['name'] == $subject_name) {
                        $subject_id = $subject['id'];
                    }
                }
            }
        }
    } ?>
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
                            if ($action == 'create') {
                                if ($subjectCreated) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Subject Created';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Subject Not Created';
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
                            if ($action == 'create') {
                                if ($subjectCreated) {
                                    echo '<p>The subject: ' . $subject_name . ' has been created.</p>';
                                } else {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo '<p>The subject: ' . $subject_name . ' could not be created.</p>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <!-- show error messages -->
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($action == 'create') {
                                if (!$canCreate) {
                                    echo '<p>The subject: ' . $subject_name . ' cannot be created because a subject with the same name already exists.</p>';
                                    echo '<p>Please enter a different subject name and try again.</p>';
                                } else if ($canCreate && !$subjectCreated) {
                                    echo '<p>The subject: ' . $subject_name . ' could not be created due to an unknown error.</p>';
                                } else {
                                    echo '<p>The subject: ' . $subject_name . ' has been created.</p>';
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
                                if ($action == 'create') {
                                    if ($subjectCreated) {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=subjects&subject=list" class="btn btn-primary">Return to Subject List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=subjects&subject=single&id=' . $subject_id . '" class="btn btn-secondary">Go to Subject</a></span>';
                                    } else {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=subjects&subject=list" class="btn btn-primary">Return to Subject List</a></span>';
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
