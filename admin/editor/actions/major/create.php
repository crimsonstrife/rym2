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
        //get the major name from the form
        if (isset($_POST["major_name"])) {
            $major_name = trim($_POST["major_name"]);
            //prepare the major name
            $major_name = prepareData($major_name);
        }

        //if the action is create, create the major
        if ($action == 'create') {
            //get current user ID
            $user_id = intval($session->get('user_id'));

            //boolean to track if the major can be created
            $canCreate = true;

            //check if the major already exists by name
            $existingMajors = $degree->getAllMajors(); //get all majors currently in the system
            foreach ($existingMajors as $existingMajor) {
                //if the major already exists, set canCreate to false
                if (
                    $existingMajor['name'] == $major_name
                ) {
                    $canCreate = false;
                }
            }

            //if the major can be created, create the major
            if ($canCreate) {
                //create the major
                $majorCreated = $degree->addMajor($major_name, $user_id);
            }

            //placeholder for the major id
            $major_id = null;

            //if the major was created, get the major id
            if ($majorCreated) {
                $major_id = $degree->getMajorIdByName($major_name);
            }
        }
    } ?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?php echo htmlspecialchars($major_name); ?></h1>
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <div>
                            <?php
                            if ($action == 'create') {
                                if ($majorCreated) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Major Created';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Major Not Created';
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
                                if ($majorCreated) {
                                    echo '<p>The major: ' . htmlspecialchars($major_name) . ' has been created.</p>';
                                } else {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo '<p>The major: ' . htmlspecialchars($major_name) . ' could not be created.</p>';
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
                                    echo '<p>The major: ' . htmlspecialchars($major_name) . ' cannot be created because a major with the same name already exists.</p>';
                                    echo '<p>Please enter a different major name and try again.</p>';
                                } else if ($canCreate && !$majorCreated) {
                                    echo '<p>The major: ' . htmlspecialchars($major_name) . ' could not be created due to an unknown error.</p>';
                                } else {
                                    echo '<p>The major: ' . htmlspecialchars($major_name) . ' has been created.</p>';
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
                                    if ($majorCreated) {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=majors&major=list" class="btn btn-primary">Return to Major List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=majors&major=single&id=' . htmlspecialchars($major_id) . '" class="btn btn-secondary">Go to Major</a></span>';
                                    } else {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=majors&major=list" class="btn btn-primary">Return to Major List</a></span>';
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
