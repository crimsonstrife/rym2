<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
}

//auth class
$auth = new Authenticator();

//permissions class
$permissionsObject = new Permission();

//event class
$event = new Event();

//school class
$school = new School();

//user class
$user = new User();

//student class
$student = new Student();

//include the media class
$media = new Media();

//include the event media class
$eventMedia = new EventMedia();

//create an array of available media
$mediaArray = $media->getMedia();

//check that action is set in the URL parameters
if (isset($_GET['action'])) {
    //get the action from the URL parameters
    $action = $_GET['action'];

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

    //if the action is edit, show the event edit form
    if ($action == 'edit') {

        /*confirm user has a role with update event permissions*/
        //get the id of the update event permission
        $updateEventPermissionID = $permissionsObject->getPermissionIdByName('UPDATE EVENT');

        //boolean to track if the user has the update event permission
        $hasEventUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updateEventPermissionID);

        //prevent the user from accessing the page if they do not have the relevant permission
        if (!$hasEventUpdatePermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else {

            if (isset($_GET['id'])) {
                //get the event id from the url parameter
                $event_id = $_GET['id'];
            } else {
                //set the event id to null
                $event_id = null;
            }

            //confirm the id exists
            if (empty($event_id) || $event_id == null) {
                //set the error type
                $thisError = 'INVALID_REQUEST_ERROR';

                //include the error message file
                include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
            } else {
                //try to get the event information
                $object = $event->getEventById(intval($event_id));

                //check if the event is empty
                if (empty($object)) {
                    //set the error type
                    $thisError = 'NOT_FOUND';

                    //include the error message file
                    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
                }
            }

            //if not empty, display the event information
            if (!empty($object)) {

                //verify that the event location exists
                if ($school->getSchoolName($event->getEventLocationId($event_id)) == null) {
                    //set the error type
                    $thisError = 'VALIDATION_ERROR';

                    //include the error message file
                    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
                } else {
                    $event_location = $school->getSchoolName($event->getEventLocationId($event_id));
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
                        <!-- Form Information -->
                        <div class="col-md-6">
                            <div class="info">
                                <p>
                                    <span class="info-title"><strong>Instructions:</strong> </span>
                                    <span class="info-text">Use this form to edit the event information, <strong><span
                                                class="required">*</span></strong> denotes a required field.</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Event Information</h4>
                            <div class="form-group">
                                <p><strong><label for="eventName">Event Name: <strong><span
                                                    class="required">*</span></strong></label></strong></p>
                                <p><input type="text" id="eventName" name="event_name" class="form-control"
                                        value="<?php echo $event->getEventName($event_id); ?>"
                                        placeholder="<?php echo $event->getEventName($event_id); ?>" required></p>
                                <p><small id="eventNameHelp" class="form-text text-muted">Enter a unique name for the
                                        event.</small></p>
                                <div class="alert alert-warning" role="alert"><span class="note-icon"><i
                                            class="fa-solid fa-circle-exclamation"></i></span>
                                    <span class="note-text">While events can share the same name, the URL links are
                                        generated from the name and must be unique, when multiple events share a name,
                                        the
                                        slug
                                        may be dramatically altered to ensure it is unique.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <p><strong><label for="eventDate">Event Date: <strong><span
                                                    class="required">*</span></strong></label></strong></p>
                                <p><input type="date" id="eventDate" name="event_date" class="form-control"
                                        value="<?php echo $event->getEventDate($event_id); ?>"
                                        placeholder="<?php echo $event->getEventDate($event_id); ?>" required></p>
                                <p><small id="eventDateHelp" class="form-text text-muted">Enter the date of the event,
                                        must
                                        be
                                        a date in the future.</small></p>
                            </div>
                            <div class="form-group">
                                <p><strong><label for="eventLocation">Event Location: <strong><span
                                                    class="required">*</span></strong></label></strong></p>
                                <div id="schoolParent" class="col-md-12 school-dropdown">
                                    <select name="event_school" id="eventLocation"
                                        class="select2 select2-school form-control app-forms" style="width: 100%;"
                                        required>
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
                                <p><small id="eventLocationHelp" class="form-text text-muted">Select the location
                                        (school) of
                                        the
                                        event, if the school does not yet exist, create it in the Schools
                                        section.</small></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Event Branding (optional) -->
                            <h4>Event Branding</h4>
                            <div class="alert alert-info" role="alert"><span class="note-icon"><i
                                        class="fa-solid fa-circle-info"></i></span>
                                <span class="note-text">If you do not have a logo or banner to upload, you can
                                    create
                                    the
                                    event and add them later.</span>
                            </div>
                            <div class="form-group">
                                <p>
                                    <strong><label for="eventLogo">Event Logo:</label></strong>
                                    <!-- if there is an existing logo, show the file -->
                                    <?php
                                                        if (!empty($eventMedia->getEventLogo($event_id))) {
                                                            //render the file as an image
                                                            echo '<div><img src="' . getUploadPath() . $media->getMediaFileName($eventMedia->getEventLogo($event_id)) . '" alt="Event Logo" style="max-width: 200px; max-height: auto;"></div>';
                                                            //show the file name
                                                            echo '<div> ' . $media->getMediaFileName($eventMedia->getEventLogo($event_id)) . '</div>';
                                                        }
                                                        ?>
                                </p>
                                <?php //allow the user to either select a file from the mediaArray or upload a new file if they have upload permissions
                                                    if (!empty($mediaArray)) { ?>
                                <p><strong><label for="eventLogo">Select a New Logo:</label></strong></p>
                                <p>
                                    <select id="eventLogoSelect" name="event_logoSelect" class="form-control">
                                        <option value="">Select a Logo</option>
                                        <?php /* check if the user has permission to upload media */
                                                                if ($auth->checkUserPermission(intval($_SESSION['user_id']), $permissionsObject->getPermissionIdByName('CREATE MEDIA'))) { ?>
                                        <option value="0">Upload a New Logo</option>
                                        <?php } ?>
                                        <?php foreach ($mediaArray as $key => $value) { ?>
                                        <option value="<?php echo $value['id'] ?>"><?php echo $value['filename']; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                    <br />
                                    <!-- if the user selects to upload a new file, show the file upload input -->
                                    <input type="file" id="eventLogoUpload" name="event_logoUpload" class="form-control"
                                        disabled hidden>
                                </p>
                                <small id="eventLogoHelp" class="form-text text-muted">To upload a new file, select
                                    "Upload
                                    a New Logo" from the dropdown. To use an existing file, select the
                                    filename.</small>
                                <div class="alert alert-warning" role="alert"><span class="note-icon"><i
                                            class="fa-solid fa-circle-exclamation"></i></span>
                                    <span class="note-text">If the upload option is not available, contact the
                                        administrator
                                        for assistance.</span>
                                </div>
                                <p></p>
                                <?php } else if (empty($mediaArray)) { //if there are no media files, show the file upload input if the user has upload permissions
                                                        if ($auth->checkUserPermission(intval($_SESSION['user_id']), $permissionsObject->getPermissionIdByName('CREATE MEDIA'))) { ?>
                                <p><strong><label for="eventLogo">Upload a New Logo:</label></strong></p>
                                <p><input type="file" id="eventLogoUpload" name="event_logoUpload" class="form-control"
                                        required></p>
                                <?php } else { ?>
                                <p><strong><label for="eventLogo">No Media Available</label></strong></p>
                                <p>You lack permissions to upload new media files and none currently exist, contact
                                    the
                                    administrator.</p>
                                <?php }
                                                    } ?>
                            </div>
                            <div class="form-group">
                                <p>
                                    <strong><label for="eventBanner">Event Banner:</label></strong>
                                    <!-- if there is an existing banner, show the file -->
                                    <?php
                                                        if (!empty($eventMedia->getEventBanner($event_id))) {
                                                            //render the file as an image
                                                            echo '<div><img src="' . getUploadPath() . $media->getMediaFileName($eventMedia->getEventBanner($event_id)) . '" alt="Event Banner" style="max-width: 200px; max-height: auto;"></div>';
                                                            //show the file name
                                                            echo '<div> ' . $media->getMediaFileName($eventMedia->getEventBanner($event_id)) . '</div>';
                                                        }
                                                        ?>
                                </p>
                                <?php //allow the user to either select a file from the mediaArray or upload a new file if they have upload permissions
                                                    if (!empty($mediaArray)) { ?>
                                <p><strong><label for="eventBanner">Select a New Banner:</label></strong></p>
                                <p>
                                    <select id="eventBannerSelect" name="event_bannerSelect" class="form-control">
                                        <option value="">Select a Banner</option>
                                        <?php /* check if the user has permission to upload media */
                                                                if ($auth->checkUserPermission(intval($_SESSION['user_id']), $permissionsObject->getPermissionIdByName('CREATE MEDIA'))) { ?>
                                        <option value="0">Upload a New Banner</option>
                                        <?php } ?>
                                        <?php foreach ($mediaArray as $key => $value) { ?>
                                        <option value="<?php echo $value['id'] ?>"><?php echo $value['filename']; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                    <br />
                                    <!-- if the user selects to upload a new file, show the file upload input -->
                                    <input type="file" id="eventBannerUpload" name="event_bannerUpload"
                                        class="form-control" disabled hidden>
                                </p>
                                <?php } else if (empty($mediaArray)) { //if there are no media files, show the file upload input if the user has upload permissions
                                                        if ($auth->checkUserPermission(intval($_SESSION['user_id']), $permissionsObject->getPermissionIdByName('CREATE MEDIA'))) { ?>
                                <p><strong><label for="eventBanner">Upload a New Banner:</label></strong></p>
                                <p><input type="file" id="eventBannerUpload" name="event_bannerUpload"
                                        class="form-control" required></p>
                                <?php } else { ?>
                                <p><strong><label for="eventBanner">No Media Available</label></strong></p>
                                <p>You lack permissions to upload new media files and none currently exist, contact
                                    the
                                    administrator.</p>
                                <?php }
                                                    } ?>
                                <small id="eventBannerHelp" class="form-text text-muted">To upload a new file,
                                    select
                                    "Upload
                                    a New Banner" from the dropdown. To use an existing file, select the
                                    filename.</small>
                                <div class="alert alert-warning" role="alert"><span class="note-icon"><i
                                            class="fa-solid fa-circle-exclamation"></i></span>
                                    <span class="note-text">If the upload option is not available, contact the
                                        administrator
                                        for assistance.</span>
                                </div>
                            </div>
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
            }
        }
    } else if ($action == 'create') { //else if the action is create, show the event creation form

        /*confirm user has a role with create event permissions*/
        //get the id of the create event permission
        $createEventPermissionID = $permissionsObject->getPermissionIdByName('CREATE EVENT');

        //boolean to track if the user has the create event permission
        $hasEventCreatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createEventPermissionID);

        //prevent the user from accessing the page if they do not have the relevant permission
        if (!$hasEventCreatePermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else {
            //make sure there is at least one school in the school list to choose from
            if (empty($schools_list)) {
                //set the error type
                $thisError = 'DATA_MISSING';

                //include the error message file
                include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
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
                        <!-- Form Information -->
                        <div class="col-md-6">
                            <div class="info">
                                <p>
                                    <span class="info-title"><strong>Instructions:</strong> </span>
                                    <span class="info-text">Use this form to enter the new event information,
                                        <strong><span class="required">*</span></strong> denotes a required
                                        field.</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Event Details -->
                        <div class="col-md-6">
                            <h4>Event Information</h4>
                            <div class="form-group">
                                <p><strong><label for="eventName">Event Name: <strong><span
                                                    class="required">*</span></strong></label></strong></p>
                                <p><input type="text" id="eventName" name="event_name" class="form-control"
                                        placeholder="Event Name" required></p>
                                <p><small id="eventNameHelp" class="form-text text-muted">Enter a unique name for the
                                        event.</small></p>
                                <div class="alert alert-warning" role="alert"><span class="note-icon"><i
                                            class="fa-solid fa-circle-exclamation"></i></span>
                                    <span class="note-text">While events can share the same name, the URL links are
                                        generated from the name and must be unique, when multiple events share a name,
                                        the
                                        slug
                                        may be dramatically altered to ensure it is unique.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <p><strong><label for="eventDate">Event Date: <strong><span
                                                    class="required">*</span></strong></label></strong></p>
                                <p><input type="date" id="eventDate" name="event_date" class="form-control"
                                        placeholder="Event Date" required></p>
                                <p><small id="eventDateHelp" class="form-text text-muted">Enter the date of the event,
                                        must
                                        be
                                        a date in the future.</small></p>
                            </div>
                            <div class="form-group">
                                <p><strong><label for="eventLocation">Event Location: <strong><span
                                                    class="required">*</span></strong></label></strong></p>
                                <div id="schoolParent" class="col-md-12 school-dropdown">
                                    <select name="event_school" id="eventLocation"
                                        class="select2 select2-school form-control app-forms" style="width: 100%;"
                                        required>
                                        <?php
                                                        //loop through the schools list
                                                        foreach ($schools_list as $school => $value) {
                                                            //get the key and value from the array and set the variables
                                                            $school_id = (string) $value['value'];
                                                            $school_label = (string) $value['label'];
                                                            //set the option values
                                                            echo '<option value="' . $school_id . '">' . $school_label . '</option>';
                                                        }
                                                        ?>
                                    </select>
                                </div>
                                <p><small id="eventLocationHelp" class="form-text text-muted">Select the location
                                        (school) of
                                        the
                                        event, if the school does not yet exist, create it in the Schools
                                        section.</small></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Event Branding (optional) -->
                            <h4>Event Branding</h4>
                            <div class="alert alert-info" role="alert"><span class="note-icon"><i
                                        class="fa-solid fa-circle-info"></i></span>
                                <span class="note-text">If you do not have a logo or banner to upload, you can create
                                    the
                                    event and add them later.</span>
                            </div>
                            <div class="form-group">
                                <p>
                                    <strong><label for="eventLogo">Event Logo:</label></strong>
                                </p>
                                <?php //allow the user to either select a file from the mediaArray or upload a new file if they have upload permissions
                                                if (!empty($mediaArray)) { ?>
                                <p><strong><label for="eventLogo">Select a New Logo:</label></strong></p>
                                <p>
                                    <select id="eventLogoSelect" name="event_logoSelect" class="form-control">
                                        <option value="">Select a Logo</option>
                                        <?php /* check if the user has permission to upload media */
                                                            if ($auth->checkUserPermission(intval($_SESSION['user_id']), $permissionsObject->getPermissionIdByName('CREATE MEDIA'))) { ?>
                                        <option value="0">Upload a New Logo</option>
                                        <?php } ?>
                                        <?php foreach ($mediaArray as $key => $value) { ?>
                                        <option value="<?php echo $value['id'] ?>"><?php echo $value['filename']; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                    <br />
                                    <!-- if the user selects to upload a new file, show the file upload input -->
                                    <input type="file" id="eventLogoUpload" name="event_logoUpload" class="form-control"
                                        disabled hidden>
                                </p>
                                <?php } else if (empty($mediaArray)) { //if there are no media files, show the file upload input if the user has upload permissions
                                                    if ($auth->checkUserPermission(intval($_SESSION['user_id']), $permissionsObject->getPermissionIdByName('CREATE MEDIA'))) { ?>
                                <p><strong><label for="eventLogo">Upload a New Logo:</label></strong></p>
                                <p><input type="file" id="eventLogoUpload" name="event_logoUpload" class="form-control"
                                        required></p>
                                <?php } else { ?>
                                <p><strong><label for="eventLogo">No Media Available</label></strong></p>
                                <p>You lack permissions to upload new media files and none currently exist, contact the
                                    administrator.</p>
                                <?php }
                                                } ?>
                                <small id="eventLogoHelp" class="form-text text-muted">To upload a new file, select
                                    "Upload
                                    a New Logo" from the dropdown. To use an existing file, select the filename.</small>
                                <div class="alert alert-warning" role="alert"><span class="note-icon"><i
                                            class="fa-solid fa-circle-exclamation"></i></span>
                                    <span class="note-text">If the upload option is not available, contact the
                                        administrator
                                        for assistance.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <p>
                                    <strong><label for="eventBanner">Event Banner:</label></strong>
                                </p>
                                <?php //allow the user to either select a file from the mediaArray or upload a new file if they have upload permissions
                                                if (!empty($mediaArray)) { ?>
                                <p><strong><label for="eventBanner">Select a New Banner:</label></strong></p>
                                <p>
                                    <select id="eventBannerSelect" name="event_bannerSelect" class="form-control">
                                        <option value="">Select a Banner</option>
                                        <?php /* check if the user has permission to upload media */
                                                            if ($auth->checkUserPermission(intval($_SESSION['user_id']), $permissionsObject->getPermissionIdByName('CREATE MEDIA'))) { ?>
                                        <option value="0">Upload a New Banner</option>
                                        <?php } ?>
                                        <?php foreach ($mediaArray as $key => $value) { ?>
                                        <option value="<?php echo $value['id'] ?>"><?php echo $value['filename']; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                    <br />
                                    <!-- if the user selects to upload a new file, show the file upload input -->
                                    <input type="file" id="eventBannerUpload" name="event_bannerUpload"
                                        class="form-control" disabled hidden>
                                </p>
                                <?php } else if (empty($mediaArray)) { //if there are no media files, show the file upload input if the user has upload permissions
                                                    if ($auth->checkUserPermission(intval($_SESSION['user_id']), $permissionsObject->getPermissionIdByName('CREATE MEDIA'))) { ?>
                                <p><strong><label for="eventBanner">Upload a New Banner:</label></strong></p>
                                <p><input type="file" id="eventBannerUpload" name="event_bannerUpload"
                                        class="form-control" required></p>
                                <?php } else { ?>
                                <p><strong><label for="eventBanner">No Media Available</label></strong></p>
                                <p>You lack permissions to upload new media files and none currently exist, contact the
                                    administrator.</p>
                                <?php }
                                                } ?>
                                <small id="eventBannerHelp" class="form-text text-muted">To upload a new file, select
                                    "Upload
                                    a New Banner" from the dropdown. To use an existing file, select the
                                    filename.</small>
                                <div class="alert alert-warning" role="alert"><span class="note-icon"><i
                                            class="fa-solid fa-circle-exclamation"></i></span>
                                    <span class="note-text">If the upload option is not available, contact the
                                        administrator
                                        for assistance.</span>
                                </div>
                            </div>
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
        }
    } ?>
<script>
//hide or show the file upload input based on the user selection
$(document).ready(function() {
    $('#eventLogoSelect').change(function() {
        if ($(this).val() == '0') {
            $('#eventLogoUpload').prop('disabled', false).show();
            $('#eventLogoUpload').prop('hidden', false).show();
        } else {
            $('#eventLogoUpload').prop('disabled', true).hide();
            $('#eventLogoUpload').prop('hidden', true).hide();
        }
    });
});
</script>
<script>
//hide or show the file upload input based on the user selection
$(document).ready(function() {
    $('#eventBannerSelect').change(function() {
        if ($(this).val() == '0') {
            $('#eventBannerUpload').prop('disabled', false).show();
            $('#eventBannerUpload').prop('hidden', false).show();
        } else {
            $('#eventBannerUpload').prop('disabled', true).hide();
            $('#eventBannerUpload').prop('hidden', true).hide();
        }
    });
});
</script>
<?php } else {
    //set the action to null
    $action = null;

    //set the error type
    $thisError = 'ROUTING_ERROR';

    //include the error message file
    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
} ?>
