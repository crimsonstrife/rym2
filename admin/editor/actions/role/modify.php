<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//role class
$role = new Roles();

//user class
$user = new User();

//permission class
$permission = new Permission();

//get the permissions
$permissionsArray = $permission->getAllPermissions();

//get the action from the url parameter
$action = $_GET['action'];

//if the action is edit, get the role id from the url parameter
if ($action == 'edit') {
    $roleId = $_GET['id'];
}

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

    //if the action is Edit, update the role
    if ($action == 'edit') {
        //update the role
        $roleUpdated = $role->updateRole($roleId, intval($_SESSION['user_id']), $role_name, $role_permissions);
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
                    if ($action == 'edit') {
                        if ($roleUpdated) {
                            echo 'Role Updated';
                        } else {
                            echo 'Error: Role Not Updated';
                            //get any failed permission changes from the session variable if it is set
                            if (isset($_SESSION['permissions_set_failed'])) {
                                $failedPermissions = $_SESSION['permissions_set_failed'];

                                //if the failed permissions array is not empty, display the failed permissions
                                if (!empty($failedPermissions)) {
                                    echo '<br><br><strong>Failed Permission Changes:</strong><br>';
                                    foreach ($failedPermissions as $failedPermission) {
                                        echo $failedPermission . '<br>';
                                    }
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
