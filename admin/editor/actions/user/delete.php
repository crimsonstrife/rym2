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

//include the user class
$user = new User();

//include the session class
$session = new Session();

/*confirm user has a role with delete user permissions*/
//get the id of the delete user permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('DELETE USER');

//boolean to track if the user has the delete user permission
$hasPermission = $auth->checkUserPermission(intval($session->get('user_id')), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
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

        //check if the user is the last user
        $userCount = count($user->getAllUsers());

        //if there is only one user, the user cannot be deleted so set the canDelete boolean to false
        if ($userCount <= 1) {
            $canDelete = false;
        }

        //if the canDelete boolean is true, delete the user
        if ($canDelete) {
            $userDeleted = $user->deleteUser($user_id);
        } else {
            $userDeleted = false;
        }
    } ?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?php echo $user_name; ?></h1>
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
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- show completion message -->
                        <div class="col-md-12">
                            <?php
                            if ($action == 'delete') {
                                if ($userDeleted) {
                                    echo '<p>The user: ' . $user_name . ' has been deleted.</p>';
                                } else {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo '<p>The user: ' . $user_name . ' could not be deleted.</p>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <!-- show error messages -->
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($action == 'delete') {
                                if (!$canDelete) {
                                    echo '<p>The user: ' . $user_name . ' cannot be deleted because of an error.</p>';
                                    echo '<p>Please resolve the errors below and try again.</p>';
                                    echo '<ul>';
                                    if ($userCount <= 1) {
                                        //show error message when the user is the last user
                                        echo '<li>The user: ' . $user_name . ' cannot be deleted because it is the last user.</li>';
                                    }
                                    echo '</ul>';
                                } else if ($canDelete && !$userDeleted) {
                                    echo '<p>The user: ' . $user_name . ' could not be deleted, due to an unknown error.</p>';
                                } else {
                                    echo '<p>All associated records for the user: ' . $user_name . ' have been deleted.</p>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <!-- show back buttons -->
                        <div class="col-md-12">
                            <div class="card-buttons">
                                <?php
                                if ($action == 'delete') {
                                    if ($userDeleted) {
                                        echo '<a href="' . APP_URL . '/admin/dashboard.php?view=users&user=list" class="btn btn-primary">Return to User List</a>';
                                    } else {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=users&user=list" class="btn btn-primary">Return to User List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=users&user=single&id=' . $user_id . '" class="btn btn-secondary">Return to User</a></span>';
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
