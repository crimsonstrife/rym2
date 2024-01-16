<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
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

//get the permissions
$permissionsArray = $permission->getAllPermissions();

//get the action from the url parameter
$action = $_GET['action'];

//if the action is edit, get the role id from the url parameter
if ($action == 'edit') {
    $roleId = $_GET['id'];
}

//boolean to check for the super admin role
$roleHasSuperPermission = false;

//count of how many roles have the super admin permission
$superAdminCount = 0;

//get the is admin permission id
$isAdminPermissionID = $permissionsObject->getPermissionIdByName('IS ADMIN');

//boolean to check if the user has the is admin permission
$hasIsAdminPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $isAdminPermissionID);

//get the is super admin permission id
$isSuperAdminPermissionID = $permissionsObject->getPermissionIdByName('IS SUPERADMIN');

//boolean to check if the user has the is super admin permission
$hasIsSuperAdminPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $isSuperAdminPermissionID);

//role array to count
$rolesArray = $role->getAllRoles();

//if the action is edit, get the role id from the url parameter
if ($action == 'edit') {
    //loop through the roles array, and count how many roles have the super admin permission
    foreach ($rolesArray as $roleArray) {
        foreach ($roleArray as $roleObject) {
            //get the role permissions
            $rolePermissions = $role->getRolePermissions(intval($roleId));

            //loop through the role permissions
            foreach ($rolePermissions as $permissionArray) {
                foreach ($permissionArray as $permission) {
                    //if the role has the super admin permission
                    if ($permission['id'] == $isSuperAdminPermissionID) {
                        //increment the super admin count
                        $superAdminCount++;
                    }
                }
            }
        }
    }
}

//if the action is edit, show the role edit form
if ($action == 'edit') {

    //get the update role permission id
    $updateRolePermissionID = $permissionsObject->getPermissionIdByName('UPDATE ROLE');

    //boolean to check if the user has the update role permission
    $hasUpdateRolePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updateRolePermissionID);

    //if the user does not have the update role permission, prevent access to the editor
    if (!$hasUpdateRolePermission) {
        //die with an error message
        die('Error: You do not have permission to access this content, contact the Administrator.');
    } else { ?>
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
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=list'; ?>" class="btn btn-secondary">Back to Roles</a>
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
                                            <select multiple class="form-control form-control-lg" id="rolePermissions" name="role_permissions[]" required>
                                                <?php
                                                //get the role permissions
                                                $rolePermissions = $role->getRolePermissions(intval($roleId));

                                                //if the role permissions array is empty, show all the permissions
                                                if (empty($rolePermissions)) {
                                                    //sort the arrays by id
                                                    sort($permissionsArray);
                                                    //loop through the permissionsArray and display the permissions
                                                    foreach ($permissionsArray as $permission) {
                                                        //check if the current user has the super admin role, if not, check if they have the admin role
                                                        if ($hasIsSuperAdminPermission) {
                                                            echo '<option value="' . $permission['id'] . '">' . $permission['name'] . '</option>';
                                                        } else if ($hasIsAdminPermission) {
                                                            //check if the permission is the super admin or admin permissions, if not super admin, display the role
                                                            $permissionIsSuperAdmin = false;
                                                            if ($permission['id'] == $isSuperAdminPermissionID) {
                                                                $permissionIsSuperAdmin = true;
                                                            } else {
                                                                $permissionIsSuperAdmin = false;
                                                            }
                                                            if (!$permissionIsSuperAdmin) {
                                                                echo '<option value="' . $permission['id'] . '">' . $permission['name'] . '</option>';
                                                            }
                                                        } else {
                                                            if ($permission['id'] != $isSuperAdminPermissionID && $permission['id'] != $isAdminPermissionID) {
                                                                echo '<option value="' . $permission['id'] . '">' . $permission['name'] . '</option>';
                                                            }
                                                        }
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
                                                            //check if the current user has the super admin role, if not, check if they have the admin role
                                                            if ($hasIsSuperAdminPermission) {
                                                                echo '<option value="' . $permission['id'] . '" selected>' . $permission['name'] . '</option>';
                                                            } else if ($hasIsAdminPermission) {
                                                                //check if the permission is the super admin or admin permissions, if not super admin, display the role
                                                                $permissionIsSuperAdmin = false;
                                                                if ($permission['id'] == $isSuperAdminPermissionID) {
                                                                    $permissionIsSuperAdmin = true;
                                                                } else {
                                                                    $permissionIsSuperAdmin = false;
                                                                }
                                                                if (!$permissionIsSuperAdmin) {
                                                                    echo '<option value="' . $permission['id'] . '" selected>' . $permission['name'] . '</option>';
                                                                }
                                                            } else {
                                                                if ($permission['id'] != $isSuperAdminPermissionID && $permission['id'] != $isAdminPermissionID) {
                                                                    echo '<option value="' . $permission['id'] . '" selected>' . $permission['name'] . '</option>';
                                                                }
                                                            }
                                                        } else {
                                                            //check if the current user has the super admin role, if not, check if they have the admin role
                                                            if ($hasIsSuperAdminPermission) {
                                                                echo '<option value="' . $permission['id'] . '">' . $permission['name'] . '</option>';
                                                            } else if ($hasIsAdminPermission) {
                                                                //check if the permission is the super admin or admin permissions, if not super admin, display the role
                                                                $permissionIsSuperAdmin = false;
                                                                if ($permission['id'] == $isSuperAdminPermissionID) {
                                                                    $permissionIsSuperAdmin = true;
                                                                } else {
                                                                    $permissionIsSuperAdmin = false;
                                                                }
                                                                if (!$permissionIsSuperAdmin) {
                                                                    echo '<option value="' . $permission['id'] . '">' . $permission['name'] . '</option>';
                                                                }
                                                            } else {
                                                                if ($permission['id'] != $isSuperAdminPermissionID && $permission['id'] != $isAdminPermissionID) {
                                                                    echo '<option value="' . $permission['id'] . '">' . $permission['name'] . '</option>';
                                                                }
                                                            }
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
    <?php }
} else if ($action == 'create') { //else if the action is create, show the role creation form
    //get the create role permission id
    $createRolePermissionID = $permissionsObject->getPermissionIdByName('CREATE ROLE');

    //boolean to check if the user has the create role permission
    $hasCreateRolePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createRolePermissionID);

    //if the user does not have the create role permission, prevent access to the editor
    if (!$hasCreateRolePermission) {
        //die with an error message
        die('Error: You do not have permission to access this content, contact the Administrator.');
    } else {
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
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=list'; ?>" class="btn btn-secondary">Back to Roles</a>
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
                                            <select multiple class="form-control form-control-lg" id="rolePermissions" name="role_permissions[]" required>
                                                <?php
                                                //sort the arrays by id
                                                sort($permissionsArray);
                                                //loop through the permissionsArray and display the permissions
                                                foreach ($permissionsArray as $permission) {
                                                    //check if the current user has the super admin role, if not, check if they have the admin role
                                                    if ($hasIsSuperAdminPermission) {
                                                        echo '<option value="' . $permission['id'] . '">' . $permission['name'] . '</option>';
                                                    } else if ($hasIsAdminPermission) {
                                                        //check if the permission is the super admin or admin permissions, if not super admin, display the role
                                                        $permissionIsSuperAdmin = false;
                                                        if ($permission['id'] == $isSuperAdminPermissionID) {
                                                            $permissionIsSuperAdmin = true;
                                                        } else {
                                                            $permissionIsSuperAdmin = false;
                                                        }
                                                        if (!$permissionIsSuperAdmin) {
                                                            echo '<option value="' . $permission['id'] . '">' . $permission['name'] . '</option>';
                                                        }
                                                    } else {
                                                        if ($permission['id'] != $isSuperAdminPermissionID && $permission['id'] != $isAdminPermissionID) {
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
                            <button name="create_Button" type="submit" class="btn btn-primary">Save</button>
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=list'; ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php
    }
} ?>
