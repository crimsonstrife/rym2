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

//role class
$role = new Roles();

//user class
$user = new User();

//permission class
$permission = new Permission();

//include the session class
$session = new Session();

//get the permissions
$permissionsArray = $permission->getAllPermissions();

//get the action from the url parameter
$action = $_GET['action'];

/*confirm user has a role with create role permissions*/
//get the id of the create role permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('CREATE ROLE');

//boolean to track if the user has the create role permission
$hasPermission = $auth->checkUserPermission(intval($session->get('user_id')), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
} else {

    //boolean to track if the role was created
    $roleCreated = false;

    //error strings
    $roleNameError = '';

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the role name from the form
        if (isset($_POST["role_name"])) {
            $role_name = trim($_POST["role_name"]);
            //prepare the role name
            $role_name = prepareData($role_name);
        }
        //get the permissions from the form
        if (isset($_POST["role_permissions"])) {
            $role_permissions = $_POST["role_permissions"];
            //prepare the permissions
            //$role_permissions = prepareData($role_permissions);
        }

        //validate the role name, make sure there is not already a role with the same name
        if (empty($role_name)) {
            $roleNameError = 'Please enter a role name.';
        } else {
            //get the role id
            $roleId = $role->getRoleByName($role_name);
            //if the role id is not null, there is already a role with the same name
            if ($roleId != null || $roleId != false || $roleId != 0) {
                $roleNameError = 'A role with this name already exists.';
            }
        }

        //if the action is create, create the role
        if ($action == 'create') {
            //check for errors
            if (!$roleNameError == '') {
                //create the role
                $roleCreated = $role->createRole($role_name, intval($session->get('user_id')), $role_permissions);
            } else {
                //set the role created boolean to false
                $roleCreated = false;
            }
        }
    } ?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?php echo htmlspecialchars($role_name); ?></h1>
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <div>
                            <?php
                            if ($action == 'create') {
                                if ($roleCreated) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Role Created';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Role Not Created';
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
                                if ($roleCreated) {
                                    echo '<p>The role: ' . htmlspecialchars($role_name) . ' has been created.</p>';
                                } else {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo '<p>The role: ' . htmlspecialchars($role_name) . ' could not be created.</p>';
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
                                if ($roleNameError != '') {
                                    echo '<div class="alert alert-danger" role="alert">';
                                    echo $roleNameError;
                                    echo '</div>';
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
                                    if ($roleCreated) {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=roles&role=list" class="btn btn-primary">Return to Role List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=roles&role=single&id=' . htmlspecialchars($role_id) . '" class="btn btn-secondary">Go to Role</a></span>';
                                    } else {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=roles&role=list" class="btn btn-primary">Return to Role List</a></span>';
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
