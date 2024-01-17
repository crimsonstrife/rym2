<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
}

//autoload composer dependencies
require_once __DIR__ . '/../../../vendor/autoload.php';

//include the permissions class
$permissionsObject = new Permission();

//auth class
$auth = new Authenticator();

//include the roles class
$role = new Roles();

//user class
$user = new User();

/*confirm user has a role with read role permissions*/
//get the id of the read role permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('READ ROLE');

//boolean to track if the user has the read role permission
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
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
} else {
    if (isset($_GET['id'])) {
        //get the role id from the url parameter
        $roleId = $_GET['id'];
    } else {
        //set the role id to null
        $roleId = null;
    }

    //confirm the id exists
    if (empty($roleId) || $roleId == null) {
        //set the error type
        $thisError = 'INVALID_REQUEST_ERROR';

        //include the error message file
        include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
    } else {
        //get the role data by id
        $roleData = $role->getRoleById(intval($roleId));

        //check if the role is empty
        if (empty($roleData)) {
            //set the error type
            $thisError = 'NOT_FOUND';

            //include the error message file
            include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
        }
    }

    //if not empty, display the event information
    if (!empty($roleData)) {

        //role array to count
        $rolesArray = $role->getAllRoles();

        //count of how many roles have the super admin permission
        $superAdminCount = 0;

        //boolean to check for the super admin role
        $roleHasSuperPermission = false;

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
?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $role->getRoleNameById(intval($roleId)); ?></h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-scale-balanced"></i>
                    Role Details
                </div>
                <div class="card-buttons">
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=list'; ?>"
                        class="btn btn-secondary">Back to Roles</a>
                    <?php /*confirm user has a role with update role permissions*/
                            //get the update role permission id
                            $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE ROLE');

                            //boolean to check if the user has the update role permission
                            $hasUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                            //only show the edit button if the user has the update role permission
                            if ($hasUpdatePermission) { ?>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=edit&action=edit&id=' . $roleId; ?>"
                        class="btn btn-primary">Edit Role</a>
                    <?php } ?>
                    <?php /*confirm user has a role with delete role permissions*/
                            //get the delete role permission id
                            $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE ROLE');

                            //boolean to check if the user has the delete role permission
                            $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                            //only show the delete button if the user has the delete role permission
                            if ($hasDeletePermission) {

                                //if there are 1 or fewer super admins
                                if ($superAdminCount <= 1) {
                                    //if the role is the super admin role, do not show the delete button
                                    if ($roleHasSuperPermission) {
                                        //do not show the delete button
                                    } else { ?>
                    <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteRoleModal">
                        Delete Role
                    </button>
                    <?php }
                                }
                            } ?>
                </div>
            </div>
            <div class="card-body">
                <!-- Single Role Information -->
                <div class="row">
                    <!-- Role Details -->
                    <div class="col-md-6" style="height: 100%;">
                        <h3>Role Details</h3>
                        <div id="info" class="">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Role Name:</strong></p>
                                    <p><strong>Role Created:</strong></p>
                                    <p><strong>Role Created By:</strong></p>
                                    <p><strong>Role Updated:</strong></p>
                                    <p><strong>Role Updated By:</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo $roleData['name']; ?></p>
                                    <p><?php echo $roleData['created_at']; ?></p>
                                    <p><?php echo $user->getUserUsername(intval($roleData['created_by'])); ?></p>
                                    <p><?php echo $roleData['updated_at']; ?></p>
                                    <p><?php echo $user->getUserUsername(intval($roleData['updated_by'])); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" style="height: 100%;">
                        <h3>Role Permissions</h3>
                        <div id="info" class="">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php $rolePermissions = $role->getRolePermissions(intval($roleId)); ?>
                                    <p><strong>Permissions:</strong></p>
                                    <!-- Permissions Table -->
                                    <div class="card-body">
                                        <div class="table-scroll table-fixedHead table-responsive">
                                            <table id="dataTable" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Permission</th>
                                                        <th>Date Granted</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($rolePermissions as $permissionArray) {
                                                                foreach ($permissionArray as $permission) { ?>
                                                    <tr>
                                                        <td><?php echo $permission['name']; ?></td>
                                                        <td><?php echo $role->getPermissionGrantDate(intval($roleId), intval($permission['id'])); ?>
                                                        </td>
                                                    </tr>
                                                    <?php }
                                                            } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- Users with this role -->
                        <h3>Users with this role</h3>
                        <div id="info" class="">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php $usersWithRole = $role->getUsersWithRole(intval($roleId));
                                            if (!empty($usersWithRole)) { ?>
                                    <p><strong>Users:</strong></p>
                                    <div class="card-body">
                                        <div class="table-scroll table-fixedHead table-responsive">
                                            <table id="dataTable" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Username</th>
                                                        <th>Email</th>
                                                        <th>Created</th>
                                                        <th>Created By</th>
                                                        <th>Updated</th>
                                                        <th>Updated By</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($usersWithRole as $userHasRole) {
                                                                    $userDetails = $user->getUserById(intval($userHasRole['user_id'])); ?>
                                                    <tr>
                                                        <td><?php echo $userDetails['username']; ?></td>
                                                        <td><?php echo $userDetails['email']; ?></td>
                                                        <td><?php echo $role->getUserRoleGivenDate(intval($userDetails['id']), intval($roleId)); ?>
                                                        </td>
                                                        <td><?php echo $user->getUserUsername(intval($userHasRole['created_by'])); ?>
                                                        </td>
                                                        <td><?php echo $role->getUserRoleModifiedDate(intval($userDetails['id']), intval($roleId)); ?>
                                                        </td>
                                                        <td><?php echo $user->getUserUsername(intval($userHasRole['updated_by'])); ?>
                                                        </td>
                                                    </tr>
                                                    <?php }
                                                            } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
            </div>
            <?php if ($hasDeletePermission) { ?>
            <div id="info" class="">
                <!-- Delete Role Modal-->
                <!-- Modal -->
                <div id="deleteRoleModal" class="modal fade delete" tabindex="-1" role="dialog"
                    aria-labelledby="#roleDeleteModal" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="roleDeleteModal">Delete Role -
                                    <?php echo $role->getRoleNameById(intval($roleId)); ?></h3>
                                <button type="button" class="btn-close close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this role?</p>
                                <p>This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                                <form
                                    action="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=single&action=delete&id=' . $roleId; ?>"
                                    method="post">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete Role</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php }
} ?>
