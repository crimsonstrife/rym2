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

    //if the action is create, create the role
    if ($action == 'create') {
        //create the role
        $roleCreated = $role->createRole($role_name, intval($_SESSION['user_id']), $role_permissions);
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
                        if ($roleCreated) {
                            echo 'Role Created';
                        } else {
                            echo 'Error: Role Not Created';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
