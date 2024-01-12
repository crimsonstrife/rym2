<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
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

//include the activity class
$activity = new Activity();

//get the user id from the url
$userId = $_GET['id'];

$isOwnProfile = false;

//check if the current user is the same as the user being viewed, always let the user view their own profile
if (intval($_SESSION['user_id']) == intval($userId)) {
    $isOwnProfile = true;
}

/*confirm user has a role with read user permissions*/
//get the id of the read user permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('READ USER');

//boolean to track if the user has the read user permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if ((!$hasPermission && !$isOwnProfile)) {
    die('Error: You do not have permission to perform this request.');
} else {

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
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=list'; ?>"
                        class="btn btn-secondary">Back to Users</a>
                    <?php /*confirm user has a role with update user permissions*/
                        //get the update user permission id
                        $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE USER');

                        //boolean to check if the user has the update user permission
                        $hasUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                        //only show the edit button if the user has the update user permission
                        if ($hasUpdatePermission || $isOwnProfile) { ?>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=edit&action=edit&id=' . $userId; ?>"
                        class="btn btn-primary">Edit User</a>
                    <?php } ?>
                    <?php /*confirm user has a role with delete user permissions*/
                        //get the delete user permission id
                        $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE USER');

                        //boolean to check if the user has the delete user permission
                        $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                        //only show the delete button if the user has the delete user permission, do not let the user delete their own account
                        if ($hasDeletePermission && !$isOwnProfile) { ?>
                    <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteUserModal">
                        Delete User
                    </button>
                    <?php } ?>
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
                <div class="row">
                    <!-- User Activity -->
                    <div class="col-md-12" style="height: 100%;">
                        <h3 id="activity_log">User Activity</h3>
                        <div id="info" class="">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-scroll">
                                        <table id="dataTable" class="table table-striped table-bordered">
                                            <?php /*confirm user has a role with read activity permissions*/
                                                //get the id of the read activity permission
                                                $readActivityPermissionID = $permissionsObject->getPermissionIdByName('READ ACTIVITY');

                                                //boolean to track if the user has the read activity permission
                                                $hasReadActivityPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readActivityPermissionID);

                                                //only show the activity log if the user has the read activity permission
                                                if ($hasReadActivityPermission || $isOwnProfile) { ?>
                                            <thead>
                                                <tr>
                                                    <th scope="col">Date</th>
                                                    <th scope="col">Action</th>
                                                    <th scope="col">Performed On</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                        //get the activity data by user id
                                                        $activityData = $activity->getAllActivityByUser(intval($userId));
                                                        //loop through the activity data and display it
                                                        foreach ($activityData as $activity) {
                                                            echo "<tr>";
                                                            echo "<td>" . $activity['action_date'] . "</td>";
                                                            echo "<td>" . $activity['action'] . "</td>";
                                                            echo "<td>" . $activity['performed_on'] . "</td>";
                                                            echo "</tr>";
                                                        }
                                                        ?>
                                            </tbody>
                                            <?php } else { ?>
                                            <thead>
                                                <tr>
                                                    <th scope="col">Date</th>
                                                    <th scope="col">Action</th>
                                                    <th scope="col">Performed On</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="3">You do not have permission to view this data.</td>
                                                </tr>
                                            </tbody>
                                            <?php } ?>
                                        </table>
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
                <!-- Delete User Modal-->
                <!-- Modal -->
                <div id="deleteUserModal" class="modal fade delete" tabindex="-1" role="dialog"
                    aria-labelledby="#userDeleteModal" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="userDeleteModal">Delete User -
                                    <?php echo $user->getUserUsername($userId); ?></h3>
                                <button type="button" class="btn-close close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this user?</p>
                                <p>This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                                <form
                                    action="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=single&action=delete&id=' . $userId; ?>"
                                    method="post">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete User</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php } ?>
