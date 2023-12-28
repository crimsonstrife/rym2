<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
} ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Roles</h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-table"></i>
                    Role List
                </div>
                <div class="card-tools">
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=add&action=create' ?>"
                        class="btn btn-primary">Add Role</a>
                </div>
            </div>
            <div class="card-body">
                <table id="dataTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Role Name</th>
                            <th>Permissions</th>
                            <th>Role Created</th>
                            <th>Role Created By</th>
                            <th>Role Updated</th>
                            <th>Role Updated By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        /* Setup datatable of Roles */
                        //include the Roles class
                        $rolesData = new Roles();
                        //include the Permissions class
                        $permissionsData = new Permission();
                        //include the User class
                        $userData = new User();
                        //get all the roles
                        $rolesArray = $rolesData->getAllRoles();
                        //for each user, display it
                        foreach ($rolesArray as $role) {
                            //get the permissions for the role
                            $permissionsArray = $rolesData->getRolePermissions(intval($role['id']));

                            //create a string of the permissions
                            $permissionsString = "";

                            //check if the permissions array is empty
                            if (!empty($permissionsArray)) {
                                foreach ($permissionsArray as $permission) {
                                    foreach ($permission as $key => $value) {
                                        //get the permissions
                                        $permissions = $permissionsData->getPermissionById(intval($value['id']));
                                        foreach ($permissions as $permission) {
                                            //add the permission to the string, followed by a new line
                                            $permissionsString .= $permission['name'] . "<br>";
                                        }
                                    }
                                }
                            } else {
                                //if the permissions array is empty, add a message to the string
                                $permissionsString = "No permissions assigned";
                            }
                        ?>
                        <tr>
                            <td><?php echo $role['name']; ?></td>
                            <td><?php echo $permissionsString; ?></td>
                            <td><?php echo $role['created_at']; ?></td>
                            <td><?php echo $userData->getUserUsername(intval($role['created_by'])); ?></td>
                            <td><?php echo $role['updated_at']; ?></td>
                            <td><?php echo $userData->getUserUsername(intval($role['updated_by'])); ?></td>
                            <td>
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=single' ?>&id=<?php echo $role['id']; ?>"
                                    class="btn btn-success">View</a>
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=edit&action=edit&id=' . $role['id']; ?>"
                                    class="btn btn-primary">Edit</a>
                                <a href="/delete/delete_role.php?id=<?php echo $role['id']; ?>"
                                    class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>
</div>
<?php ?>
