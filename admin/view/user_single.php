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

//get the user id from the url
$userId = $_GET['id'];

//get the user data by id
$userData = $user->getUserById(intval($userId));

//get the roles data by user id
$rolesData = $user->getUserRoles(intval($userId));
?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $user->getUserUsername($userId); ?></h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-user"></i>
                    User Details
                </div>
                <div class="card-buttons">
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=list'; ?>" class="btn btn-primary btn-sm">Back to Users</a>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=edit&action=edit&id=' . $userId; ?>" class="btn btn-primary btn-sm">Edit User</a>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=delete&id=' . $userId; ?>" class="btn btn-danger btn-sm">Delete User</a>
                </div>
            </div>
            <div class="card-body">
                <!-- Single User Information -->
                <div class="row">
                    <!-- User Details -->
                    <div class="col-md-6" style="height: 100%;">
                        <h3>User Details</h3>
                        <div id="info" class="">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Username:</strong></p>
                                    <p><strong>Email:</strong></p>
                                    <p><strong>User Created:</strong></p>
                                    <p><strong>User Created By:</strong></p>
                                    <p><strong>User Updated:</strong></p>
                                    <p><strong>User Updated By:</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo $userData['username']; ?></p>
                                    <p><?php echo $userData['email']; ?></p>
                                    <p><?php echo $userData['created_at']; ?></p>
                                    <p><?php echo $user->getUserUsername(intval($userData['created_by'])); ?></p>
                                    <p><?php echo $userData['updated_at']; ?></p>
                                    <p><?php echo $user->getUserUsername(intval($userData['updated_by'])); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- User Roles -->
                    <div class="col-md-6" style="height: 100%;">
                        <h3>User Roles</h3>
                        <div id="info" class="">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Roles:</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        <?php
                                        //create a string to hold the roles
                                        $rolesString = "";
                                        //loop through the roles and add them to the string
                                        foreach ($rolesData as $role) {
                                            //if the string is empty, add the role name
                                            if ($rolesString == "") {
                                                $rolesString = $role['name'];
                                            } else {
                                                //if the string is not empty, add a comma and the role name
                                                $rolesString = $rolesString . ", " . $role['name'];
                                            }
                                        }
                                        echo $rolesString;
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
            </div>
        </div>
    </div>
</div>
