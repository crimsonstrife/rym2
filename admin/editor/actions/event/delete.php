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

/*confirm user has a role with delete event permissions*/
//get the id of the delete event permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('DELETE EVENT');

//boolean to track if the user has the delete event permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    die('Error: You do not have permission to perform this request.');
} else {
?>
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
                                if ($eventDeleted) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Event Deleted';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Event Not Deleted';
                                }
                            }
                            ?>
                    </div>
                    <div>
                        <?php
                            if ($action == 'delete') {
                                if ($canDelete && !$eventDeleted) {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo 'The event: ' . $event_name . ', could not be deleted because of an unknown error.';
                                } else if (!$canDelete && !$eventDeleted) {
                                    echo 'The event: ' . $event_name . ', could not be deleted because of an error: ';
                                    echo '<ul>';
                                    if (count($studentsAtEvent) > 0) {
                                        echo '<li>There are ' . strval(count($studentsAtEvent)) . ' students associated with the event</li>';
                                    }
                                    if (count($schoolsWithEvent) > 0) {
                                        echo '<li>There are ' . strval(count($schoolsWithEvent)) . ' schools associated with the event</li>';
                                    }
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
