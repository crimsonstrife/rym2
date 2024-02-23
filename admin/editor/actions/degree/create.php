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

//degree class
$degree = new Degree();

//user class
$user = new User();

//session class
$session = new Session();

//get the action from the url parameter
$action = $_GET['action'];

/*confirm user has a role with create major permissions*/
//get the id of the create major permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('CREATE MAJOR');

//boolean to track if the user has the create major permission
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

        //if the action is create, create the degree
        if ($action == 'create') {
            //get current user ID
            $user_id = intval($session->get('user_id'));

            //boolean to track if the degree can be created
            $canCreate = true;

            //check if the degree already exists by name
            $existingDegrees = $degree->getAllGrades(); //get all degrees currently in the system
            foreach ($existingDegrees as $existingDegree) {
                //if the degree already exists, set canCreate to false
                if ($existingDegree['name'] == $degree_name) {
                    $canCreate = false;
                }
            }

            //if the degree can be created, create the degree
            if ($canCreate) {
                //create the degree
                $degreeCreated = $degree->addGrade($degree_name, $user_id);
            }

            //placeholder for the degree id
            $degree_id = null;

            //if the degree was created, get the degree id
            if ($degreeCreated) {
                //get all degrees
                $degrees = $degree->getAllGrades();
                //get the degree id by finding the degree with the same name
                foreach ($degrees as $degree) {
                    if ($degree['name'] == $degree_name) {
                        $degree_id = $degree['id'];
                    }
                }
            }
        }
    } ?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?php echo $degree_name; ?></h1>
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <div>
                            <?php
                            if ($action == 'create') {
                                if ($degreeCreated) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Degree Created';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Degree Not Created';
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
                                if ($degreeCreated) {
                                    echo '<p>The degree: ' . $degree_name . ' has been created.</p>';
                                } else {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo '<p>The degree: ' . $degree_name . ' could not be created.</p>';
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
                                    echo '<p>The degree: ' . $degree_name . ' cannot be created because a degree with the same name already exists.</p>';
                                    echo '<p>Please enter a different degree name and try again.</p>';
                                } else if ($canCreate && !$degreeCreated) {
                                    echo '<p>The degree: ' . $degree_name . ' could not be created due to an unknown error.</p>';
                                } else {
                                    echo '<p>The degree: ' . $degree_name . ' has been created.</p>';
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
                                    if ($degreeCreated) {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=degrees&degree=list" class="btn btn-primary">Return to Degree List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=degrees&degree=single&id=' . $degree_id . '" class="btn btn-secondary">Go to Degree</a></span>';
                                    } else {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=degrees&degree=list" class="btn btn-primary">Return to Degree List</a></span>';
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
