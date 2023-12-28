<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//autoload composer dependencies
require_once __DIR__ . '/../../vendor/autoload.php';

//include the roles class
$role = new Roles();

//user class
$user = new User();

//get the role id from the url
$roleId = $_GET['id'];

//get the role data by id
$roleData = $role->getRoleById(intval($roleId));

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
                        class="btn btn-primary btn-sm">Back to Roles</a>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=edit&action=edit&id=' . $roleId; ?>"
                        class="btn btn-primary btn-sm">Edit Role</a>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=delete&id=' . $roleId; ?>"
                        class="btn btn-danger btn-sm">Delete Role</a>
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
                                    <?php foreach ($rolePermissions as $permissionArray) {
                                        foreach ($permissionArray as $permission) { ?>
                                    <p><?php echo $permission['name']; ?></p>
                                    <?php }
                                    } ?>
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
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
