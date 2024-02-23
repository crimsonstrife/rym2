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

//session class
$session = new Session();

//get the action from the url parameter
$action = $_GET['action'];

//if the action is edit, get the major id from the url parameter
if ($action == 'edit') {
    $major_id = $_GET['id'];
}

/*confirm user has a role with update major permissions*/
//get the id of the update major permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('UPDATE MAJOR');

//boolean to track if the user has the update major permission
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
        //get the major name from the form
        if (isset($_POST["major_name"])) {
            $major_name = trim($_POST["major_name"]);
            //prepare the major name
            $major_name = prepareData($major_name);
        }

        //if the action is edit, edit the major
        if ($action == 'edit') {
            //get current user ID
            $user_id = intval($session->get('user_id'));

            //boolean to track if the major can be updated.
            $canEdit = true;

            //check if the major name is being changed
            if ($degree->getMajorNameById($major_id) != $major_name) {
                //check if the major already exists by name
                $existingMajors = $degree->getAllMajors(); //get all majors currently in the system
                foreach ($existingMajors as $existingMajor) {
                    //if the major already exists, set canEdit to false
                    if (
                        $existingMajor['name'] == $major_name
                    ) {
                        $canEdit = false;
                    }
                }
            }

            //if the major can be updated, update the major
            if ($canEdit) {
                //edit the major
                $majorUpdated = $degree->updateMajor($major_id, $major_name, $user_id);
            }
        }
    } ?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?php echo $degree->getMajorNameById($major_id); ?></h1>
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <div>
                            <?php
                            if ($action == 'edit') {
                                if ($majorUpdated) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Major Updated';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Major Not Updated';
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
                                if ($majorUpdated) {
                                    echo '<p>The major: ' . $major_name . ' has been updated.</p>';
                                } else {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo '<p>The major: ' . $major_name . ' could not be updated.</p>';
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
                                    echo '<p>The major: ' . $major_name . ' cannot be updated because a major with the same name already exists.</p>';
                                    echo '<p>Please enter a different major name and try again.</p>';
                                } else if ($canEdit && !$majorUpdated) {
                                    echo '<p>The major: ' . $major_name . ' could not be updated due to an unknown error.</p>';
                                } else {
                                    echo '<p>The major: ' . $major_name . ' has been updated.</p>';
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
                                    if ($majorUpdated) {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=majors&major=list" class="btn btn-primary">Return to Major List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=majors&major=single&id=' . $major_id . '" class="btn btn-secondary">Go to Major</a></span>';
                                    } else {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=majors&major=list" class="btn btn-primary">Return to Major List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=majors&major=single&id=' . $major_id . '" class="btn btn-secondary">Go to Major</a></span>';
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
