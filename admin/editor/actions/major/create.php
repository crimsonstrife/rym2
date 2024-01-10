<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//include the permissions class
$permissionsObject = new Permission();

//include the auth class
$auth = new Authenticator();

//degree class
$degree = new Degree();

//user class
$user = new User();

//get the action from the url parameter
$action = $_GET['action'];

//if the action is edit, get the major id from the url parameter
if ($action == 'edit') {
    $major_id = $_GET['id'];
}

/*confirm user has a role with create major permissions*/
//get the id of the create major permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('CREATE MAJOR');

//boolean to track if the user has the create major permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    die('Error: You do not have permission to perform this request.');
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
            $user_id = intval($_SESSION['user_id']);

            //create the major
            $majorCreated = $degree->addMajor($major_name, $user_id);
        }
    } ?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-check"></i>
                        <?php
                        if ($action == 'create') {
                            if ($majorCreated) {
                                echo 'Major Created';
                            } else {
                                echo 'Error: Major Not Created';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
