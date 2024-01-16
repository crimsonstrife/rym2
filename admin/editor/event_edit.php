<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//event class
$event = new Event();

//school class
$school = new School();

//get the schools list
$schools_list = $school->getSchools();
//for each item, set the id as the value and the name as the label
foreach ($schools_list as $key => $value) {
    //add an item to the array
    $schools_list[$key] = $arrayName = array(
        "value" => (string)$value['id'],
        "label" => (string)$value['name']
    );
}
//sort the schools list alphabetically
array_multisort(array_column($schools_list, 'label'), SORT_ASC, $schools_list);

//user class
$user = new User();

//auth class
$auth = new Authenticator();

//permissions class
$permissionsObject = new Permission();

//student class
$student = new Student();

//get the action from the url parameter
$action = $_GET['action'];

//if the action is edit, get the event id from the url parameter
if ($action == 'edit') {
    $event_id = $_GET['id'];
}

//get the event location if this is an event edit page
if ($action == 'edit') {
    $event_location = $school->getSchoolName($event->getEventLocationId($event_id));
} else {
    //if this is not an event edit page, set the event location to null
    $event_location = null;
}

//if the action is edit, show the event edit form
if ($action == 'edit') {

    /*confirm user has a role with update event permissions*/
    //get the id of the update event permission
    $updateEventPermissionID = $permissionsObject->getPermissionIdByName('UPDATE EVENT');

    //boolean to track if the user has the update event permission
    $hasEventUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updateEventPermissionID);

    //prevent the user from accessing the page if they do not have the relevant permission
    if (!$hasEventUpdatePermission) {
        die('Error: You do not have permission to perform this request.');
    } else {
?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $event->getEventName($event_id); ?></h1>
    <div class="row">
        <div class="card mb-4">
            <!-- Edit Form -->
            <form
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&event=' . $_GET['event'] . '&action=' . $_GET['action'] . '&id=' . $_GET['id']; ?>"
                method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-calendar-day"></i>
                        Edit Event
                    </div>
                    <div class="card-buttons">
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=list'; ?>"
                            class="btn btn-secondary">Back to Events</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><label for="eventName">Event Name:</label></strong></p>
                            <p><input type="text" id="eventName" name="event_name" class="form-control"
                                    value="<?php echo $event->getEventName($event_id); ?>"
                                    placeholder="<?php echo $event->getEventName($event_id); ?>" required></p>
                            <p><strong><label for="eventDate">Event Date:</label></strong></p>
                            <p><input type="date" id="eventDate" name="event_date" class="form-control"
                                    value="<?php echo $event->getEventDate($event_id); ?>"
                                    placeholder="<?php echo $event->getEventDate($event_id); ?>"></p>
                            <p><strong><label for="eventLocation">Event Location:</label></strong></p>
                            <div id="schoolParent" class="col-md-12 school-dropdown">
                                <select name="event_school" id="eventLocation"
                                    class="select2 select2-school form-control app-forms" style="width: 100%;">
                                    <?php
                                            //loop through the schools list
                                            foreach ($schools_list as $school => $value) {
                                                //get the key and value from the array and set the variables
                                                $school_id = (string) $value['value'];
                                                $school_label = (string) $value['label'];
                                                //check if the school matches the location of the event, if so, set the selected attribute, if not compare to the student's school
                                                if ($action == 'edit') {
                                                    if ($school_label == $event_location) {
                                                        //if it matches, set the selected attribute
                                                        echo '<option value="' . $school_id . '" selected>' . $school_label . '</option>';
                                                    } else if ($school_label == $event_location) {
                                                        //if it matches, set the selected attribute
                                                        echo '<option value="' . $school_id . '" selected>' . $school_label . '</option>';
                                                    } else {
                                                        //if it doesn't match, don't set the selected attribute
                                                        echo '<option value="' . $school_id . '">' . $school_label . '</option>';
                                                    }
                                                } else {
                                                    if ($school_label == $event_location) {
                                                        //if it matches, set the selected attribute
                                                        echo '<option value="' . $school_id . '" selected>' . $school_label . '</option>';
                                                    } else {
                                                        //if it doesn't match, don't set the selected attribute
                                                        echo '<option value="' . $school_id . '">' . $school_label . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Event Branding (optional) -->
                            <h4>Event Branding</h4>
                            <p>
                                <strong><label for="eventLogo">Event Logo:</label></strong>
                                <!-- if there is an existing logo, show the file -->
                                <?php
                                        if (!empty($event->getEventLogo($event_id))) {
                                            //render the file as an image
                                            echo '<div><img src="' . APP_URL . '/public/content/uploads/' . $event->getEventLogo($event_id) . '" alt="Event Logo" style="max-width: 200px; max-height: auto;"></div>';
                                            //show the file name
                                            echo '<div> ' . $event->getEventLogo($event_id) . '</div>';
                                        }
                                        ?>
                            </p>
                            <p><input type="file" id="eventLogo" name="event_logo" class="form-control"></p>
                            <p>
                                <strong><label for="eventBanner">Event Banner:</label></strong>
                                <!-- if there is an existing banner, show the file -->
                                <?php
                                        if (!empty($event->getEventBanner($event_id))) {
                                            //render the file as an image
                                            echo '<div><img src="' . APP_URL . '/public/content/uploads/' . $event->getEventBanner($event_id) . '" alt="Event Banner" style="max-width: 200px; max-height: auto;"></div>';
                                            //show the file name
                                            echo '<div> ' . $event->getEventBanner($event_id) . '</div>';
                                        }
                                        ?>
                            </p>
                            <p><input type="file" id="eventBanner" name="event_banner" class="form-control"></p>
                        </div>
                    </div>
                </div>
                <div class=" card-footer">
                    <button name="create_Button" type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=list'; ?>"
                        class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php }
} else if ($action == 'create') { //else if the action is create, show the event creation form

    /*confirm user has a role with create event permissions*/
    //get the id of the create event permission
    $createEventPermissionID = $permissionsObject->getPermissionIdByName('CREATE EVENT');

    //boolean to track if the user has the create event permission
    $hasEventCreatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createEventPermissionID);

    //prevent the user from accessing the page if they do not have the relevant permission
    if (!$hasEventCreatePermission) {
        die('Error: You do not have permission to perform this request.');
    } else {
    ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">New Event</h1>
    <div class="row">
        <div class="card mb-4">
            <!-- Event Create Form -->
            <form
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&event=' . $_GET['event'] . '&action=' . $_GET['action'] ?>"
                method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-calendar-day"></i>
                        Create Event
                    </div>
                    <div class="card-buttons">
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=list'; ?>"
                            class="btn btn-secondary">Back to Events</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Event Details -->
                        <div class="col-md-6">
                            <p><strong><label for="eventName">Event Name:</label></strong></p>
                            <p><input type="text" id="eventName" name="event_name" class="form-control"
                                    placeholder="Event Name" required></p>
                            <p><strong><label for="eventDate">Event Date:</label></strong></p>
                            <p><input type="date" id="eventDate" name="event_date" class="form-control"
                                    placeholder="Event Date"></p>
                            <p><strong><label for="eventLocation">Event Location:</label></strong></p>
                            <div id="schoolParent" class="col-md-12 school-dropdown">
                                <select name="event_school" id="eventLocation"
                                    class="select2 select2-school form-control app-forms" style="width: 100%;">
                                    <?php
                                            //loop through the schools list
                                            foreach ($schools_list as $school => $value) {
                                                //get the key and value from the array and set the variables
                                                $school_id = (string) $value['value'];
                                                $school_label = (string) $value['label'];
                                                //check if the school matches the location of the event, if so, set the selected attribute, if not compare to the student's school
                                                if ($action == 'edit') {
                                                    if ($school_label == $event_location) {
                                                        //if it matches, set the selected attribute
                                                        echo '<option value="' . $school_id . '" selected>' . $school_label . '</option>';
                                                    } else if ($school_label == $event_location) {
                                                        //if it matches, set the selected attribute
                                                        echo '<option value="' . $school_id . '" selected>' . $school_label . '</option>';
                                                    } else {
                                                        //if it doesn't match, don't set the selected attribute
                                                        echo '<option value="' . $school_id . '">' . $school_label . '</option>';
                                                    }
                                                } else {
                                                    if ($school_label == $event_location) {
                                                        //if it matches, set the selected attribute
                                                        echo '<option value="' . $school_id . '" selected>' . $school_label . '</option>';
                                                    } else {
                                                        //if it doesn't match, don't set the selected attribute
                                                        echo '<option value="' . $school_id . '">' . $school_label . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Event Branding (optional) -->
                            <h4>Event Branding</h4>
                            <p>
                                <strong><label for="eventLogo">Event Logo:</label></strong>
                            </p>
                            <p><input type="file" id="eventLogo" name="event_logo" class="form-control"></p>
                            <p>
                                <strong><label for="eventBanner">Event Banner:</label></strong>
                            </p>
                            <p><input type="file" id="eventBanner" name="event_banner" class="form-control"></p>
                        </div>
                    </div>
                </div>
                <div class=" card-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=list'; ?>"
                        class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php }
} ?>
