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

//if the action is edit, get the degree id from the url parameter
if ($action == 'edit') {
    $degree_id = $_GET['id'];
}

/*confirm user has a role with update degree permissions*/
//get the id of the update degree permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('UPDATE DEGREE');

//boolean to track if the user has the update degree permission
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
        //get the degree name from the form
        if (isset($_POST["degree_name"])) {
            $degree_name = trim($_POST["degree_name"]);
            //prepare the degree name
            $degree_name = prepareData($degree_name);
        }

        //if the action is edit, edit the degree
        if ($action == 'edit') {
            //get current user ID
            $user_id = intval($session->get('user_id'));

            //boolean to track if the degree can be updated.
            $canEdit = true;

            //check if the degree name is being changed
            if ($degree->getGradeNameById($degree_id) != $degree_name) {
                //check if the degree already exists by name
                $existingDegrees = $degree->getAllGrades(); //get all degrees currently in the system
                foreach ($existingDegrees as $existingDegree) {
                    //if the degree already exists, set canEdit to false
                    if (
                        $existingDegree['name'] == $degree_name
                    ) {
                        $canEdit = false;
                    }
                }
            }

            //if the degree can be updated, update the degree
            if ($canEdit) {
                //edit the degree
                $degreeUpdated = $degree->updateGrade($degree_id, $degree_name, $user_id);
            }
        }
    } ?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?php echo $degree->getGradeNameById($degree_id); ?></h1>
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <div>
                            <?php
                            if ($action == 'edit') {
                                if ($degreeUpdated) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Degree Updated';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Degree Not Updated';
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
                                if ($degreeUpdated) {
                                    echo "<p>The degree: " . htmlspecialchars($degree_name) . " has been updated.</p>";
                                } else {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo '<p>The degree: ' . htmlspecialchars($degree_name) . ' could not be updated.</p>';
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
                                    echo '<p>The degree: ' . htmlspecialchars($degree_name) . ' cannot be updated because a degree with the same name already exists.</p>';
                                    echo '<p>Please enter a different degree name and try again.</p>';
                                } else if ($canEdit && !$degreeUpdated) {
                                    echo '<p>The degree: ' . htmlspecialchars($degree_name) . ' could not be updated due to an unknown error.</p>';
                                } else {
                                    echo '<p>The degree: ' . htmlspecialchars($degree_name) . ' has been updated.</p>';
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
                                    if ($degreeUpdated) {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=degrees&degree=list" class="btn btn-primary">Return to Degree List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=degrees&degree=single&id=' . htmlspecialchars($degree_id) . '" class="btn btn-secondary">Go to Degree</a></span>';
                                    } else {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=degrees&degree=list" class="btn btn-primary">Return to Degree List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=degrees&degree=single&id=' . htmlspecialchars($degree_id) . '" class="btn btn-secondary">Go to Degree</a></span>';
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
