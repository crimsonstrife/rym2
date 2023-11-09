<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//event class
$event = new Event();

//school class
$school = new School();

//user class
$user = new User();

//student class
$student = new Student();

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
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&events=list'; ?>"
                        class="btn btn-primary btn-sm">Back to Events</a>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&events=edit&id=' . $event_id; ?>"
                        class="btn btn-primary btn-sm">Edit Event</a>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&events=delete&id=' . $event_id; ?>"
                        class="btn btn-danger btn-sm">Delete Event</a>
                </div>
            </div>
            <div class="card-body">
                <!-- Single Event information -->
                <div class="row">
                    <div class="col-md-6" style="height: 100%;">
                        <h3>Event Details</h3>
                        <div id="info" class="">
                            <p><strong>Event Name:</strong> <?php echo $event->getEventName($event_id); ?></p>
                            <p><strong>Event Date:</strong> <?php echo $event->getEventDate($event_id); ?></p>
                            <p><strong>Event Location:</strong> <?php echo $event->getEventLocation($event_id); ?></p>
                            <!-- Formatted School address -->
                            <p><strong>Event Address:</strong>
                                <?php
                                //encode the address as a url for google maps - this will be used to link to google maps per Google documentation https://developers.google.com/maps/documentation/urls/get-started
                                $address = $school->getFormattedSchoolAddress(intval($event->getEventLocationId($event_id)));
                                $address = urlencode($address);
                                ?>
                                <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $address; ?>"
                                    target="_blank"><?php echo $school->getFormattedSchoolAddress(intval($event->getEventLocationId($event_id))); ?></a>
                            </p>
                        </div>
                        <div id="map"></div>
                        <br>
                        <hr>
                        <br>
                        <div id="eventBranding">
                            <h3>Event Branding</h3>
                            <p><strong>Event Logo:</strong></p>
                            <img src="#" alt="Event Logo" style="max-width: 100%; max-height: 100%;">
                            <p><strong>Event Banner:</strong></p>
                            <img src="#" alt="Event Banner" style="max-width: 100%; max-height: 100%;">
                            <p><strong>School Logo:</strong></p>
                            <img src="#" alt="School Logo" style="max-width: 100%; max-height: 100%;">
                            <p><strong>School Primary Color:</strong></p>
                            <div style="width: 100px; height: 100px; background-color: #000000;"></div>
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
                                <div class="card-body">
                                    <table id="dataTable">
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
            </div>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo getLibraryPath() . 'leaflet/leaflet.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo getLibraryPath() . 'leaflet-geosearch/geosearch.umd.js'; ?>">
    </script>
    <script type="module" src="<?php echo getAssetPath() . 'js/event-map.js'; ?>"></script>
    <?php ?>
