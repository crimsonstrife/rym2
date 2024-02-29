<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
}

//autoload composer dependencies
require_once __DIR__ . '/../../../vendor/autoload.php';

//event class
$event = new Event();

//schedule class
$schedule = new Schedule();

//school class
$school = new School();

//auth class
$auth = new Authenticator();

//permissions class
$permissionsObject = new Permission();

//user class
$user = new User();

//student class
$student = new Student();

//student event class
$studentEvent = new StudentEvent();

//student education class
$studentEducation = new StudentEducation();

//media class
$media = new Media();

//event media class
$eventMedia = new EventMedia();

/*confirm user has a role with read event permissions*/
//get the id of the read event permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('READ EVENT');

//boolean to track if the user has the read event permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
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
        include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
    } else {
        //try to get the event information
        $object = $event->getEventById(intval($event_id));

        //check if the event is empty
        if (empty($object)) {
            //set the error type
            $thisError = 'NOT_FOUND';

            //include the error message file
            include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
        }
    }

    //if not empty, display the event information
    if (!empty($object)) {

        //get the event location information
        $location = $event->getEventLocation($event_id);
        $streetAddress = $school->getSchoolAddress(intval($event->getEventLocationId($event_id)));
        $city = $school->getSchoolCity(intval($event->getEventLocationId($event_id)));
        $state = $school->getSchoolState(intval($event->getEventLocationId($event_id)));
        $zip = $school->getSchoolZip(intval($event->getEventLocationId($event_id)));
?>
<link rel="stylesheet" href="<?php echo htmlspecialchars(getLibraryPath() . 'leaflet/leaflet.css', ENT_QUOTES, 'UTF-8'); ?>">
<link rel="stylesheet" href="<?php echo htmlspecialchars(getLibraryPath() . 'leaflet-geosearch/geosearch.css', ENT_QUOTES, 'UTF-8'); ?>">
<script>
var mapLocationTitle = "<?php echo $location; ?>";
var address = "<?php echo formatAddress($streetAddress, $city, $state, $zip); ?>";
</script>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $event->getEventName($event_id); ?></h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-calendar-day"></i>
                    Event Information
                </div>
                <div class="card-buttons">
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=list'; ?>"
                        class="btn btn-secondary">Back to Events</a>
                    <?php /*confirm user has a role with update event permissions*/
                            //get the update event permission id
                            $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE EVENT');

                            //boolean to check if the user has the update event permission
                            $hasUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                            //only show the edit button if the user has the update event permission
                            if ($hasUpdatePermission) { ?>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=edit&action=edit&id=' . htmlspecialchars($event_id); ?>"
                        class="btn btn-primary">Edit Event</a>
                    <?php } ?>
                    <?php /*confirm user has a role with delete event permissions*/
                            //get the delete event permission id
                            $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE EVENT');

                            //boolean to check if the user has the delete event permission
                            $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                            //only show the delete button if the user has the delete event permission
                            if ($hasDeletePermission) { ?>
                    <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteEventModal">
                        Delete Event
                    </button>
                    <?php } ?>
                </div>
            </div>
            <div class="card-body">
                <!-- Single Event information -->
                <div class="row">
                    <div class="col-md-6" style="height: 100%;">
                        <h3>Event Details</h3>
                        <div id="info" class="">
                            <p><strong>Event Name:</strong> <?php echo $event->getEventName($event_id); ?></p>
                            <p><span><strong>Event URL Slug:</strong> <a
                                        href="<?php echo APP_URL . '/index.php?event=' . $event->getEventSlug($event_id); ?>"><?php echo $event->getEventSlug($event_id); ?></a>&nbsp;&nbsp;<a
                                        href="<?php echo APP_URL . '/index.php?path=qrcode&event=' . $event->getEventSlug($event_id) ?>"
                                        target="_blank" class="btn btn-info btn-sm">QRCode Display Page <i
                                            class="fa-solid fa-arrow-up-right-from-square"></i></a></span>
                            </p>
                            <p><strong>Event Date:</strong> <?php echo htmlspecialchars($schedule->getEventDate($event_id)); ?></p>
                            <p><strong>Event Location:</strong> <?php echo $location; ?></p>
                            <!-- Formatted School address -->
                            <div>
                                <p><strong>Event Address:</strong>
                                    <?php
                                            //encode the address as a url for google maps - this will be used to link to google maps per Google documentation https://developers.google.com/maps/documentation/urls/get-started
                                            $formattedAddress = formatAddress($streetAddress, $city, $state, $zip);
                                            $address = urlencode($formattedAddress);
                                            ?>
                                    <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $address; ?>"
                                        target="_blank"><?php echo $formattedAddress; ?></a>
                                </p>
                            </div>
                            <div id="map"></div>
                        </div>
                        <br>
                        <div>
                            <p><strong>Event QRCode:</strong> (Links to the event page)</p>
                            <div>
                                <a href="<?php echo APP_URL . '/index.php?path=qrcode&event=' . $event->getEventSlug($event_id) ?>"
                                    target="_blank">
                                    <!-- QRCode -->
                                    <?php $qrcode_max_width = '200px';
                                            include_once(__DIR__ . '/../qrcode_display.php');
                                            ?>
                                </a>
                            </div>
                        </div>
                        <hr>
                        <br>
                        <div id="eventBranding">
                            <h3>Event Branding</h3>
                            <p><strong>Event Logo:</strong></p>
                            <div class="thumbnail-container"
                                style="background-image: url('<?php echo htmlspecialchars(getAssetPath()) . 'img/transparency.svg' ?>'); background-size:cover;">
                                <img id="thumbnail" class="img-thumbnail"
                                    src="<?php echo htmlspecialchars(getUploadPath()) . htmlspecialchars($media->getMediaThumbnail($eventMedia->getEventLogo($event_id))); ?>"
                                    alt="Event Logo Image">
                            </div>
                            <p><strong>Event Banner:</strong></p>
                            <div class="thumbnail-container"
                                style="background-image: url('<?php echo htmlspecialchars(getAssetPath()) . 'img/transparency.svg' ?>'); background-size:cover;">
                                <img id="thumbnail" class="img-thumbnail"
                                    src="<?php echo htmlspecialchars(getUploadPath()) . htmlspecialchars($media->getMediaThumbnail($eventMedia->getEventBanner($event_id))); ?>"
                                    alt="Event Banner Image">
                            </div>
                            <p><strong>School Logo:</strong></p>
                            <div class="thumbnail-container"
                                style="background-image: url('<?php echo htmlspecialchars(getAssetPath()) . 'img/transparency.svg' ?>'); background-size:cover;">
                                <img id="thumbnail" class="img-thumbnail"
                                    src="<?php echo getUploadPath() . htmlspecialchars($media->getMediaThumbnail($school->getSchoolLogo(intval($event->getEventLocationId($event_id))))); ?>"
                                    alt="School Logo Image">
                            </div>
                            <p><strong>School Primary Color:</strong></p>
                            <div
                                style="width: 100px; height: 100px; background-color: <?php echo $school->getSchoolColor(intval($event->getEventLocationId($event_id))) ?? '#000000'; ?>;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" style="height: 100%;">
                        <h3>Event Attendees</h3>
                        <div>
                            <!-- list of students that signed up at this event -->
                            <?php
                                    //get the list of students that signed up at this event, and display them. If there are none, display a message.
                                    $students = $studentEvent->getStudentEventAttendace($event_id);
                                    ?>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div>
                                        <table id="dataTable" class="table table-striped table-bordered">
                                            <thead>
                                                <?php
                                                        if (empty($students)) {
                                                        ?>
                                                <tr>
                                                    <th>Students List</th>
                                                </tr>
                                                <?php
                                                        } else {
                                                        ?>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Degree</th>
                                                </tr>
                                                <?php
                                                        }
                                                        ?>
                                            </thead>
                                            <tbody>
                                                <?php
                                                        if (empty($students)) {
                                                            echo '<tr><td colspan="4">No students have signed up for this event, or this event has not occurred.</td></tr>';
                                                        } else {
                                                            //check if the user has the permission to read students
                                                            $readStudentPermissionID = $permissionsObject->getPermissionIdByName('READ STUDENT');

                                                            //boolean to check if the user has the read student permission
                                                            $hasReadStudentPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readStudentPermissionID);

                                                            //if the user has the read student permission, display the student information
                                                            if ($hasReadStudentPermission) {
                                                                foreach ($students as $eventStudent) {
                                                        ?>
                                                <tr>
                                                    <td><?php echo $student->getStudentFullName($eventStudent['student_id']); ?>
                                                    </td>
                                                    <td><?php echo $student->getStudentEmail($eventStudent['student_id']); ?>
                                                    </td>
                                                    <td><?php echo $studentEducation->getStudentDegree($eventStudent['student_id']); ?>
                                                    </td>
                                                </tr>
                                                <?php }
                                                            } else {
                                                                echo '<tr><td colspan="4">You do not have permission to view student information.</td></tr>';
                                                            }
                                                        } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($hasDeletePermission) { ?>
                <div id="info" class="">
                    <!-- Delete Event Modal-->
                    <!-- Modal -->
                    <div id="deleteEventModal" class="modal fade delete" tabindex="-1" role="dialog"
                        aria-labelledby="#eventDeleteModal" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title" id="eventDeleteModal">Delete Event -
                                        <?php echo $event->getEventName($event_id); ?></h3>
                                    <button type="button" class="btn-close close" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this event?</p>
                                    <p>This action cannot be undone.</p>
                                </div>
                                <div class="modal-footer">
                                    <form
                                        action="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=single&action=delete&id=' . htmlspecialchars($event_id); ?>"
                                        method="post">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Delete Event</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="card-footer">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo htmlspecialchars(getLibraryPath()) . 'leaflet/leaflet.js'; ?>"></script>
<script type="text/javascript" src="<?php echo htmlspecialchars(getLibraryPath()) . 'leaflet-geosearch/geosearch.umd.js'; ?>">
</script>
<?php
        //if event-map.min.js exists, load it. Otherwise, load event-map.js
        if (file_exists(BASEPATH . '/public/content/assets/js/event-map.min.js')) {
        ?>
<script type="module" src="<?php echo htmlspecialchars(getAssetPath()) . 'js/event-map.min.js'; ?>"></script>
<?php
        } else {
        ?>
<script type="module" src="<?php echo htmlspecialchars(getAssetPath()) . 'js/event-map.js'; ?>"></script>
<?php }
    } ?>
<script type="text/javascript">
//variables for the datatable
var tableHeight = "50vh";
var rowNav = true;
var pageSelect = [5, 10, 15, 20, 25, 50, ["All", -1]];
var columnArray = [{
        select: 0,
        sortSequence: ["desc", "asc"]
    },
    {
        select: 1,
        sortSequence: ["desc", "asc"]
    },
    {
        select: 2,
        sortSequence: ["desc", "asc"]
    }
];
var columnOrder = [0, 1, 2];
</script>
<?php } ?>
