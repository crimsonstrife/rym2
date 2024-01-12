<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//include the permissions class
$permissionsObject = new Permission();

//include the authenticator class
$auth = new Authenticator();

//include the user class
$user = new User();

/*confirm user has a role with delete user permissions*/
//get the id of the delete user permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('DELETE USER');

//boolean to track if the user has the delete user permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    die('Error: You do not have permission to perform this request.');
} else {
    //user class
    $user = new User();

    //student class
    $student = new Student();

    //event class
    $event = new Event();

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the action from the url parameter
        $action = $_GET['action'];

        //if the action is delete, get the user id from the url parameter
        if ($action == 'delete') {
            $user_id = $_GET['id'];
        }

        //get the intvalue of the user id
        $user_id = intval($user_id);

        //get the user name
        $user_name = $user->getUserUsername($user_id);

        //boolean to track if the user can be deleted
        $canDelete = true;

        //if the canDelete boolean is true, delete the user
        if ($canDelete) {
            $userDeleted = $user->deleteUser($user_id);
        } else {
            $userDeleted = false;
        }
    } ?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <div>
                            <?php
                            if ($action == 'delete') {
                                if ($userDeleted) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'User Deleted';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: User Not Deleted';
                                }
                            }
                            ?>
                        </div>
                        <div>
                            <?php
                            if ($action == 'delete') {
                                if ($canDelete && !$userDeleted) {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo 'The user: ' . $user_name . ', could not be deleted because of an unknown error.';
                                } else if (!$canDelete && !$userDeleted) {
                                    echo 'The user: ' . $user_name . ', could not be deleted because of an error: ';
                                    echo '<ul>';
                                    echo '</ul>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
