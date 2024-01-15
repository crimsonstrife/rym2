<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//include the permissions class
$permissionsObject = new Permission();

//include the authenticator class
$auth = new Authenticator();

/*confirm user has a role with delete role permissions*/
//get the id of the delete role permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('DELETE ROLE');

//boolean to track if the user has the delete role permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//get the is admin permission id
$isAdminPermissionID = $permissionsObject->getPermissionIdByName('IS ADMIN');

//boolean to check if the user has the is admin permission
$hasIsAdminPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $isAdminPermissionID);

//get the is super admin permission id
$isSuperAdminPermissionID = $permissionsObject->getPermissionIdByName('IS SUPERADMIN');

//boolean to check if the user has the is super admin permission
$hasIsSuperAdminPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $isSuperAdminPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    die('Error: You do not have permission to perform this request.');
} else {
    //role class
    $role = new Roles();

    //user class
    $userObject = new User();

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the action from the url parameter
        $action = $_GET['action'];

        //if the action is delete, get the role id from the url parameter
        if ($action == 'delete') {
            $role_id = $_GET['id'];
        }

        //get the intvalue of the role id
        $role_id = intval($role_id);

        //get the role name
        $role_name = $role->getRoleNameById($role_id);

        //boolean to track if the role can be deleted
        $canDelete = true;

        //get list of users
        $users = $userObject->getAllUsers();

        //count the number of users with the role
        $usersWithRole = 0;

        //loop through users and check if any have the role
        foreach ($users as $user) {
            //get the user id
            $user_id = $user['id'];

            //check if the user has the role
            $userRoles = $userObject->getUserRoles($user_id);

            //boolean to track if the user has the role
            $hasRole = false;

            //loop through the user roles
            foreach ($userRoles as $userRole) {
                //get the role id
                $userRoleID = intval($userRole['role_id']);

                //if the user has the role, set the hasRole boolean to true
                if ($userRoleID == $role_id) {
                    $hasRole = true;
                }
            }

            //if the user has the role, increment the number of users with the role
            if ($hasRole) {
                $usersWithRole++;
            }
        }

        //if there are more than 0 users, the role cannot be deleted so set the canDelete boolean to false
        if ($usersWithRole > 0) {
            $canDelete = false;
        }

        //if the role can be deleted, delete the role
        if ($canDelete) {
            $roleDeleted = $role->deleteRole($role_id);
        }
    }
?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <div>
                            <?php
                            if ($action == 'delete') {
                                if ($roleDeleted) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Role Deleted';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Role Not Deleted';
                                }
                            }
                            ?>
                        </div>
                        <div>
                            <?php
                            if ($action == 'delete') {
                                if ($canDelete && !$roleDeleted) {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo 'The role: ' . $role_name . ', could not be deleted because of an unknown error.';
                                } else if (!$canDelete && !$roleDeleted) {
                                    echo 'The role: ' . $role_name . ', could not be deleted because of an error: ';
                                    echo '<ul>';
                                    if ($usersWithRole > 0) {
                                        echo '<li>There are ' . strval($usersWithRole) . ' users associated with the role</li>';
                                    }
                                    echo '</ul>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
