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

/*confirm user has a role with delete event permissions*/
//get the id of the delete event permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('DELETE EVENT');

//boolean to track if the user has the delete event permission
$hasPermission = $auth->checkUserPermission(intval($session->get('user_id')), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
} else {
    //school class
    $school = new School();

    //student class
    $student = new Student();

    //event class
    $event = new Event();

    //student event class
    $studentEvent = new StudentEvent();

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the action from the url parameter
        $action = $_GET['action'];

        //if the action is delete, get the event id from the url parameter
        if ($action == 'delete') {
            $event_id = $_GET['id'];
        }

        //get the intvalue of the event id
        $event_id = intval($event_id);

        //get the event name
        $event_name = $event->getEventName($event_id);

        //boolean to track if the event can be deleted
        $canDelete = true;

        //check if there are any students associated with the event in the student table
        $studentsAtEvent = $studentEvent->getStudentEventAttendace($event_id);

        //if there are more than 0 records in the array, the event cannot be deleted so set the canDelete boolean to false
        if (count($studentsAtEvent) > 0) {
            $canDelete = false;
        }

        //check if the event can be deleted, if so, delete the event
        if ($canDelete) {
            //boolean to track if the event was deleted
            $eventDeleted = $event->deleteEvent($event_id);
        } else {
            $eventDeleted = false;
        }
    } ?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?php echo $event_name; ?></h1>
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
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- show completion message -->
                        <div class="col-md-12">
                            <?php
                            if ($action == 'delete') {
                                if ($eventDeleted) {
                                    echo '<p>The event: ' . $event_name . ' has been deleted.</p>';
                                } else {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo '<p>The event: ' . $event_name . ' could not be deleted.</p>';
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
                                    echo '<p>The event: ' . $event_name . ' cannot be deleted because they have associated records in the system.</p>';
                                    echo '<p>Please delete the event\'s associated student records or re-associated them to other events before attempting to delete this one.</p>';
                                    echo '<ul>';
                                    if (count($studentsAtEvent) > 0) {
                                        echo '<li>There are ' . strval(count($studentsAtEvent)) . ' students associated with the event</li>';
                                    }
                                    echo '</ul>';
                                } else if ($canDelete && !$eventDeleted) {
                                    echo '<p>The event: ' . $event_name . ' could not be deleted, due to an unknown error.</p>';
                                } else {
                                    echo '<p>All associated records for the event: ' . $event_name . ' have been deleted.</p>';
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
                                    if ($eventDeleted) {
                                        echo '<a href="' . APP_URL . '/admin/dashboard.php?view=events&event=list" class="btn btn-primary">Return to Event List</a>';
                                    } else {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=events&event=list" class="btn btn-primary">Return to Event List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=events&event=single&id=' . $event_id . '" class="btn btn-secondary">Return to Event</a></span>';
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
