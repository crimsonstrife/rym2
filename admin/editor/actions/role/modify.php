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

//role class
$role = new Roles();

//user class
$user = new User();

//get all the users
$users = $user->getAllUsers();

//permission class
$permission = new Permission();

//session class
$session = new Session();

//get the permissions
$permissionsArray = $permission->getAllPermissions();

//get the action from the url parameter
$action = $_GET['action'];

//if the action is edit, get the role id from the url parameter
if ($action == 'edit') {
    $roleId = $_GET['id'];
}

$override = 'false';
$editAll = 'false';

//check if the override parameter is set
if (isset($_GET['override'])) {
    $override = $_GET['override'];
}

//boolean to track if the role can be updated
$canUpdate = false;

//if the override parameter is set to true, set the canUpdate boolean to true
if ($override == 'true') {
    $canUpdate = true;
}

//boolean to track if the role was updated
$roleUpdated = false;

//error strings
$roleNameError = '';
$permissionsError = '';
$permissionsWarning = '';

/*confirm user has a role with update role permissions*/
//get the id of the update role permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('UPDATE ROLE');

//boolean to track if the user has the update role permission
$hasPermission = $auth->checkUserPermission(intval($session->get('user_id')), $relevantPermissionID);

//get the is admin permission id
$isAdminPermissionID = $permissionsObject->getPermissionIdByName('IS ADMIN');

//boolean to check if the user has the is admin permission
$hasIsAdminPermission = $auth->checkUserPermission(intval($session->get('user_id')), $isAdminPermissionID);

//get the is super admin permission id
$isSuperAdminPermissionID = $permissionsObject->getPermissionIdByName('IS SUPERADMIN');

//boolean to check if the user has the is super admin permission
$hasIsSuperAdminPermission = $auth->checkUserPermission(intval($session->get('user_id')), $isSuperAdminPermissionID);

//get all users
$usersArray = $users;

//count the number of users
$userCount = count($usersArray);

//for each user, check if they are a super admin and increment the count
$superAdminCount = 0;
foreach ($usersArray as $userData) {
    //check if the user is a super admin
    $userIsSuperAdmin = $auth->checkUserPermission(intval($userData['id']), $isSuperAdminPermissionID);
    //if the user is a super admin, increment the count
    if ($userIsSuperAdmin) {
        $superAdminCount++;
    }
}

//for each user, check if they are an admin and increment the count
$adminCount = 0;
foreach ($usersArray as $userData) {
    //check if the user is an admin
    $userIsAdmin = $auth->checkUserPermission(intval($userData['id']), $isAdminPermissionID);
    //if the user is an admin, increment the count
    if ($userIsAdmin) {
        $adminCount++;
    }
}

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
} else {

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the editAll parameter from the form
        if (isset($_POST["editAll"])) {
            $editAll = $_POST["editAll"];
        }

        //if the editAll parameter is set to true, set the canUpdate boolean to true
        if ($editAll == 'true') {
            $canUpdate = true;
        } else {
            $canUpdate = false;
        }

        //get the role name from the form
        if (isset($_POST["role_name"])) {
            $role_name = trim($_POST["role_name"]);
            //prepare the role name
            $role_name = prepareData($role_name);
        }
        //get the permissions from the form
        if (isset($_POST["role_permissions"])) {
            $role_permissions = $_POST["role_permissions"];

            //role has super admin permission
            $roleIsSuperAdmin = false;

            //role has admin permission
            $roleIsAdmin = false;

            //get existing permissions from the database
            $existingPermissions = $role->getRolePermissions($roleId);

            //check if the role has the super admin permission
            foreach ($existingPermissions as $existingPermission) {
                foreach ($existingPermission as $permissionObject) {
                    //if the role has the super admin permission
                    if (intval($permissionObject['id']) == $isSuperAdminPermissionID) {
                        //increment the super admin count
                        $roleIsSuperAdmin = true;
                    } else {
                        $roleIsSuperAdmin = false;
                    }
                }
            }

            //check if the role has the admin permission
            foreach ($existingPermissions as $existingPermission) {
                foreach ($existingPermission as $permissionObject) {
                    //if the role has the admin permission
                    if (intval($permissionObject['id']) == $isAdminPermissionID) {
                        //increment the admin count
                        $roleIsAdmin = true;
                    } else {
                        $roleIsAdmin = false;
                    }
                }
            }

            //if user has the is super admin permission
            if ($hasIsSuperAdminPermission) {
                //if the user is the only super admin, check if the user is trying to remove the super admin role
                if ($superAdminCount <= 1) {
                    //check if the role was already a super admin
                    if ($roleIsSuperAdmin) {
                        //check if the user is trying to remove the super admin role
                        if (!in_array($isSuperAdminPermissionID, $role_permissions)) {
                            //set the canUpdate boolean to false
                            $canUpdate = false;
                            //set the permissions error
                            $permissionsError = 'This role is the only super admin role. Please assign another role as a super admin before removing this role.';
                        } else {
                            //set the canUpdate boolean to true
                            $canUpdate = true;
                        }
                    } else {
                        //set the canUpdate boolean to true
                        $canUpdate = true;
                    }
                }
            } else {
                //check if the role was already a super admin
                if ($roleIsSuperAdmin) {
                    //check if the user is trying to remove the super admin role/or if the role was left out of the permissions array because it was hidden
                    if (!in_array($isSuperAdminPermissionID, $role_permissions)) {
                        //add the super admin permission to the permissions array, should keep the super admin permission no matter what
                        array_push($role_permissions, $isSuperAdminPermissionID);
                        //set the canUpdate boolean to true
                        $canUpdate = true;
                    }
                    //set the canUpdate boolean to true
                    $canUpdate = true;
                }
            }

            //if user has the is admin or super admin permission
            if ($hasIsAdminPermission || $hasIsSuperAdminPermission) {
                //check if the role was already an admin
                if ($roleIsAdmin) {
                    //check if the user is trying to remove the admin role
                    if (!in_array($isAdminPermissionID, $role_permissions)) {
                        //check if the override parameter is set to true
                        if ($override == 'true') {
                            //set the canUpdate boolean to true
                            $canUpdate = true;
                        } else {
                            //set the permissions warning
                            $permissionsWarning = 'Removing the admin permission from this role will remove the admin permission from all users with this role.';
                            //set the canUpdate boolean to false
                            $canUpdate = false;
                        }
                    } else {
                        //set the canUpdate boolean to true
                        $canUpdate = true;
                    }
                }
            } else {
                //check if canUpdate is already false
                if (!$canUpdate) {
                    //skip this, since the update will already fail
                } else {
                    //check if the role was already an admin
                    if ($roleIsAdmin) {
                        //check if the user is trying to remove the admin role/or if the role was left out of the permissions array because it was hidden
                        if (!in_array($isAdminPermissionID, $role_permissions)) {
                            //add the admin permission to the permissions array, should keep the admin permission no matter what
                            array_push($role_permissions, $isAdminPermissionID);
                            //set the canUpdate boolean to true
                            $canUpdate = true;
                        }
                        //set the canUpdate boolean to true
                        $canUpdate = true;
                    }
                }
            }
        }

        //check if canUpdate is already false
        if (!$canUpdate) {
            //skip this, since the update will already fail
        } else {
            //validate the role name, make sure there is not already a role with the same name
            if (empty($role_name)) {
                //set the role name error
                $roleNameError = 'Please enter a role name.';
                //set the canUpdate boolean to false
                $canUpdate = false;
            } else {
                //get the role id
                $role_Id = $role->getRoleByName($role_name);

                //check if the role id is not equal to the role id from the url parameter
                if ($role_Id != $roleId) {
                    //if the role id is not null, there is already a role with the same name
                    if ($role_Id != null || $role_Id != false || $role_Id != 0) {
                        //set the role name error
                        $roleNameError = 'A role with this name already exists.';
                        //set the canUpdate boolean to false
                        $canUpdate = false;
                    }
                } else {
                    //if the role id is equal to the role id from the url parameter, the role name is the same so there is no error
                    $roleNameError = '';
                    //set the canUpdate boolean to true
                    $canUpdate = true;
                }
            }
        }

        //if the action is Edit, update the role
        if ($action == 'edit') {
            //check if the canUpdate boolean is true
            if ($canUpdate) {
                //check for errors
                if ((!$roleNameError == '' or !$roleNameError == NULL) || (!$permissionsError == '' or !$permissionsError == NULL) || (!$permissionsWarning == '' or !$permissionsWarning == NULL)) {
                    if ($canUpdate && $override == 'true') {
                        //update the role
                        $roleUpdated = $role->updateRole($roleId, intval($session->get('user_id')), $role_name, $role_permissions);
                    } elseif ($canUpdate && !$override == 'true') {
                        //update the role
                        $roleUpdated = $role->updateRole($roleId, intval($session->get('user_id')), $role_name, $role_permissions);
                    } elseif ($canUpdate) {
                        //update the role
                        $roleUpdated = $role->updateRole($roleId, intval($session->get('user_id')), $role_name, $role_permissions);
                    } else {
                        //set the role updated boolean to false
                        $roleUpdated = false;
                    }
                } elseif (!$permissionsWarning == '' && $override == 'true') {
                    //update the role
                    $roleUpdated = $role->updateRole($roleId, intval($session->get('user_id')), $role_name, $role_permissions);
                } elseif (!$permissionsWarning == '' && !$override == 'true') {
                    //set the role updated boolean to false
                    $roleUpdated = false;
                } elseif (!$permissionsWarning == '' or !$permissionsError == '' or !$roleNameError == '') {
                    //set the role updated boolean to false
                    $roleUpdated = $role->updateRole($roleId, intval($session->get('user_id')), $role_name, $role_permissions);
                } elseif (($roleNameError == '' || $roleNameError == NULL) && ($permissionsError == '' || $permissionsError == NULL) && ($permissionsWarning == '' || $permissionsWarning == NULL)) {
                    //update the role
                    $roleUpdated = $role->updateRole($roleId, intval($session->get('user_id')), $role_name, $role_permissions);
                } else {
                    //set the role updated boolean to false
                    $roleUpdated = false;
                }
            } else {
                //set the role updated boolean to false
                $roleUpdated = false;
            }
        }
    } ?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <?php
                        if ($action == 'edit') {
                            if ($roleUpdated) {
                                echo '<i class="fa-solid fa-check"></i>';
                                echo 'Role Updated';
                            } else if (!$roleUpdated && $permissionsWarning != '') {
                                echo '<i class="fa-solid fa-triangle-exclamation"></i>';
                                echo 'Notice: Role Not Updated';
                                echo '<br><br><strong>Warning:</strong><br>';
                            } else {
                                echo '<i class="fa-solid fa-x"></i>';
                                echo 'Error: Role Not Updated';
                                //get any failed permission changes from the session variable if it is set
                                if ($session->check('permissions_set_failed') === true) {
                                    $failedPermissions = $session->get('permissions_set_failed');

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
                <div class="card-body">
                    <?php
                    if ($action == 'edit') {
                        if ($roleUpdated) {
                            echo '<p>The role was updated successfully.</p>';
                        } else {
                            echo '<p>There was an error updating the role.</p>';
                        }
                    }
                    ?>
                    <br>
                    <!-- error message -->
                    <?php
                    if ($roleNameError != '') {
                        echo '<div class="alert alert-danger" role="alert">';
                        echo $roleNameError;
                        echo '</div>';
                    }
                    ?>
                    <br>
                    <?php
                    if ($permissionsError != '') {
                        echo '<div class="alert alert-danger" role="alert">';
                        echo $permissionsError;
                        echo '</div>';
                    }
                    ?>
                    <br>
                    <!-- warning message -->
                    <?php
                    if ($permissionsWarning != '') {
                        echo '<div class="alert alert-warning" role="alert">';
                        echo $permissionsWarning;
                        echo '</div>';
                    } ?>
                    <br>
                    <!-- form -->
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($action == 'edit') {
                                if ($permissionsWarning != '') { ?>
                                    <p>If you would like to update all associated records, click the button below.</p>
                                    <form action="<?php echo htmlspecialchars(APP_URL . '/admin/dashboard.php?view=roles&role=edit&action=edit&id=' . strval($roleId) . '&override=true'); ?>" method="post">
                                        <input type="hidden" name="editAll" value="true" required>
                                        <input type="hidden" name="role_name" value="<?php echo htmlspecialchars($role_name); ?>" required>
                                        <select multiple class="hidden" type="hidden" name="role_permissions[]" required>
                                            <?php //loop through the role permissions and mark the selected permissions as the value
                                            foreach ($role_permissions as $permission) {
                                                echo '<option value="' . htmlspecialchars($permission) . '" selected>' . htmlspecialchars($permission) . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <button type="submit" class="btn btn-danger">Update All Associated Records</button>
                                    </form>
                            <?php }
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
                                    if ($roleUpdated) {
                                        echo '<a href="' . APP_URL . '/admin/dashboard.php?view=roles&role=list" class="btn btn-primary">Return to Role List</a>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=roles&role=single&id=' . $role_id . '" class="btn btn-secondary">Return to Role</a></span>';
                                    } else {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=roles&role=list" class="btn btn-primary">Return to Role List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=roles&role=single&id=' . $role_id . '" class="btn btn-secondary">Return to Role</a></span>';
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
