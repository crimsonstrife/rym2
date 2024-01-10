<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Output\QROutputInterface;

//autoload composer dependencies
require_once __DIR__ . '/../../../vendor/autoload.php';

//event class
$event = new Event();

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

/*confirm user has a role with read event permissions*/
//get the id of the read event permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('READ EVENT');

//boolean to track if the user has the read event permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    die('Error: You do not have permission to perform this request.');
} else {

    //get the event id from the url parameter
    $event_id = $_GET['id'];
?>
    <link rel="stylesheet" href="<?php echo getLibraryPath() . 'leaflet/leaflet.css'; ?>">
    <link rel="stylesheet" href="<?php echo getLibraryPath() . 'leaflet-geosearch/geosearch.css'; ?>">
    <script>
        var mapLocationTitle = "<?php echo $event->getEventLocation($event_id); ?>";
        var address = "<?php echo $school->getFormattedSchoolAddress(intval($event->getEventLocationId($event_id))); ?>";
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
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=list'; ?>" class="btn btn-secondary">Back to Events</a>
                        <?php /*confirm user has a role with update event permissions*/
                        //get the update event permission id
                        $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE EVENT');

                        //boolean to check if the user has the update event permission
                        $hasUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                        //only show the edit button if the user has the update event permission
                        if ($hasUpdatePermission) { ?>
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=edit&action=edit&id=' . $event_id; ?>" class="btn btn-primary">Edit Event</a>
                        <?php } ?>
                        <?php /*confirm user has a role with delete event permissions*/
                        //get the delete event permission id
                        $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE EVENT');

                        //boolean to check if the user has the delete event permission
                        $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                        //only show the delete button if the user has the delete event permission
                        if ($hasDeletePermission) { ?>
                            <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteEventModal">
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
                                <p><strong>Event URL Slug:</strong> <a href="<?php echo APP_URL . '/index.php?event=' . $event->getEventSlug($event_id); ?>"><?php echo $event->getEventSlug($event_id); ?></a>
                                </p>
                                <p><strong>Event Date:</strong> <?php echo $event->getEventDate($event_id); ?></p>
                                <p><strong>Event Location:</strong> <?php echo $event->getEventLocation($event_id); ?></p>
                                <!-- Formatted School address -->
                                <div>
                                    <p><strong>Event Address:</strong>
                                        <?php
                                        //encode the address as a url for google maps - this will be used to link to google maps per Google documentation https://developers.google.com/maps/documentation/urls/get-started
                                        $address = $school->getFormattedSchoolAddress(intval($event->getEventLocationId($event_id)));
                                        $address = urlencode($address);
                                        ?>
                                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $address; ?>" target="_blank"><?php echo $school->getFormattedSchoolAddress(intval($event->getEventLocationId($event_id))); ?></a>
                                    </p>
                                </div>
                                <div id="map"></div>
                            </div>
                            <br>
                            <div>
                                <p><strong>Event QRCode:</strong> (Links to the event page)</p>
                                <div>
                                    <!-- QRCode -->
                                    <?php
                                    $qrCodeData = APP_URL . '/index.php?event=' . $event->getEventSlug($event_id);
                                    $qrCodeOptions = new QROptions;
                                    $qrCodeOptions->version = 7;
                                    $qrCodeOptions->outputType = QROutputInterface::GDIMAGE_PNG;
                                    $qrCodeOptions->scale = 20;
                                    $qrCodeOptions->outputBase64 = true;
                                    $qrCode = (new QRCode($qrCodeOptions))->render($qrCodeData); // per the documentation, https://php-qrcode.readthedocs.io/en/main/Usage/Quickstart.html
                                    //output the QRCode JPG
                                    header('Content-type: image/png');
                                    echo '<img src="' . $qrCode . '" alt="QRCode" style="max-width: 200px; max-height: auto;">';
                                    ?>
                                </div>
                            </div>
                            <hr>
                            <br>
                            <div id="eventBranding">
                                <h3>Event Branding</h3>
                                <p><strong>Event Logo:</strong></p>
                                <img src="<?php echo APP_URL . "/public/content/uploads/" . $event->getEventLogo($event_id); ?>" alt="Event Logo" style="max-width: 200px; max-height: auto;">
                                <p><strong>Event Banner:</strong></p>
                                <img src="<?php echo APP_URL . "/public/content/uploads/" . $event->getEventBanner($event_id); ?>" alt="Event Banner" style="max-width: 200px; max-height: auto;">
                                <p><strong>School Logo:</strong></p>
                                <img src="<?php echo APP_URL . "/public/content/uploads/" . $school->getSchoolLogo(intval($event->getEventLocationId($event_id))); ?>" alt="School Logo" style="max-width: 200px; max-height: auto;">
                                <p><strong>School Primary Color:</strong></p>
                                <div style="width: 100px; height: 100px; background-color: <?php echo $school->getSchoolColor(intval($event->getEventLocationId($event_id))) ?? '#000000'; ?>;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" style="height: 100%;">
                            <h3>Event Attendees</h3>
                            <div>
                                <!-- list of students that signed up at this event -->
                                <?php
                                //get the list of students that signed up at this event, and display them. If there are none, display a message.
                                $students = $student->getStudentEventAttendace($event_id);
                                ?>
                                <div class="card mb-4">
                                    <div class="card-body table-scroll">
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
                                                        <th>First Name</th>
                                                        <th>Last Name</th>
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
                                                                <td><?php echo $student->getStudentFirstName($eventStudent['student_id']); ?>
                                                                </td>
                                                                <td><?php echo $student->getStudentLastName($eventStudent['student_id']); ?>
                                                                </td>
                                                                <td><?php echo $student->getStudentEmail($eventStudent['student_id']); ?>
                                                                </td>
                                                                <td><?php echo $student->getStudentDegree($eventStudent['student_id']); ?>
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
                    <div class="card-footer">
                    </div>
                    <?php if ($hasDeletePermission) { ?>
                        <div id="info" class="">
                            <!-- Delete Event Modal-->
                            <!-- Modal -->
                            <div id="deleteEventModal" class="modal fade delete" tabindex="-1" role="dialog" aria-labelledby="#eventDeleteModal" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title" id="eventDeleteModal">Delete Event -
                                                <?php echo $event->getEventName($event_id); ?></h3>
                                            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this event?</p>
                                            <p>This action cannot be undone.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=single&action=delete&id=' . $event_id; ?>" method="post">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete Event</button>
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
    </div>
    <script type="text/javascript" src="<?php echo getLibraryPath() . 'leaflet/leaflet.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo getLibraryPath() . 'leaflet-geosearch/geosearch.umd.js'; ?>">
    </script>
    <?php
    //if event-map.min.js exists, load it. Otherwise, load event-map.js
    if (file_exists(BASEPATH . '/public/content/assets/js/event-map.min.js')) {
    ?>
        <script type="module" src="<?php echo getAssetPath() . 'js/event-map.min.js'; ?>"></script>
    <?php
    } else {
    ?>
        <script type="module" src="<?php echo getAssetPath() . 'js/event-map.js'; ?>"></script>
<?php
    }
} ?>
