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

//if the action is edit, show the role edit form
if ($action == 'edit') { ?>
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?php echo $role->getRoleNameById(intval($roleId)); ?></h1>
        <div class="row">
            <div class="card mb-4">
                <!-- Edit Form -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&role=' . $_GET['role'] . '&action=' . $_GET['action'] . '&id=' . $_GET['id']; ?>" method="post" enctype="multipart/form-data">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa-solid fa-scale-balanced"></i>
                            Edit Role
                        </div>
                        <div class="card-buttons">
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=list'; ?>" class="btn btn-primary btn-sm">Back to Roles</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <p>
                                        <strong>
                                            <label for="roleName">Role Name:</label>
                                        </strong>
                                    </p>
                                    <p>
                                        <input type="text" id="roleName" name="role_name" class="form-control" value="<?php echo $role->getRoleNameById(intval($roleId)); ?>" placeholder="<?php echo $role->getRoleNameById(intval($roleId)); ?>" required>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <p>
                                        <strong>
                                            <label for="rolePermissions">Role Permissions:</label>
                                        </strong>
                                    </p>
                                    <p>
                                        <select multiple class="form-control form-control-lg" id="rolePermissions" name="role_permissions" required>
                                            <?php
                                            //get the role permissions
                                            $rolePermissions = $role->getRolePermissions(intval($roleId));

                                            //if the role permissions array is empty, show all the permissions
                                            if (empty($rolePermissions)) {
                                                //sort the arrays by id
                                                sort($permissionsArray);
                                                //loop through the permissionsArray and display the permissions
                                                foreach ($permissionsArray as $permission) {
                                                    echo '<option value="' . $permission['id'] . '">' . $permission['name'] . '</option>';
                                                }
                                            } else {
                                                //move the role permissions into an array
                                                foreach ($rolePermissions as $permissionArray) {
                                                    //append the permission id to the array
                                                    foreach ($permissionArray as $permission) {
                                                        $rolePermissionsArray[] = $permission;
                                                    }
                                                }

                                                //sort the arrays by id
                                                sort($permissionsArray);
                                                sort($rolePermissionsArray);

                                                //loop through the permissionsArray and display the permissions, checking if the role has the permission in the rolePermissionsArray and setting the selected attribute if it does
                                                foreach ($permissionsArray as $permission) {
                                                    if (in_array($permission, $rolePermissionsArray)) {
                                                        echo '<option value="' . $permission['id'] . '" selected>' . $permission['name'] . '</option>';
                                                    } else {
                                                        echo '<option value="' . $permission['id'] . '">' . $permission['name'] . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" card-footer">
                        <button name="create_Button" type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=list'; ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } else if ($action == 'create') { //else if the action is create, show the role creation form
?>
    <div class="container-fluid px-4">
        <h1 class="mt-4">New Role</h1>
        <div class="row">
            <div class="card mb-4">
                <!-- Create Form -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&role=' . $_GET['role'] . '&action=' . $_GET['action']; ?>" method="post" enctype="multipart/form-data">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa-solid fa-scale-balanced"></i>
                            Create Role
                        </div>
                        <div class="card-buttons">
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=list'; ?>" class="btn btn-primary btn-sm">Back to Roles</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <p>
                                        <strong>
                                            <label for="roleName">Role Name:</label>
                                        </strong>
                                    </p>
                                    <p>
                                        <input type="text" id="roleName" name="role_name" class="form-control" placeholder="Role Name" required>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <p>
                                        <strong>
                                            <label for="rolePermissions">Role Permissions:</label>
                                        </strong>
                                    </p>
                                    <p>
                                        <select multiple class="form-control form-control-lg" id="rolePermissions" name="role_permissions" required>
                                            <?php
                                            //sort the arrays by id
                                            sort($permissionsArray);
                                            //loop through the permissionsArray and display the permissions
                                            foreach ($permissionsArray as $permission) {
                                                echo '<option value="' . $permission['id'] . '">' . $permission['name'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" card-footer">
                        <button name="create_Button" type="submit" class="btn btn-primary">Save</button>
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=list'; ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>
