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

//media class
$media = new Media();

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
                                    <p><span><strong>Event URL Slug:</strong> <a href="<?php echo APP_URL . '/index.php?event=' . $event->getEventSlug($event_id); ?>"><?php echo $event->getEventSlug($event_id); ?></a>&nbsp;&nbsp;<a href="<?php echo APP_URL . '/index.php?path=qrcode&event=' . $event->getEventSlug($event_id) ?>" target="_blank" class="btn btn-info btn-sm">QRCode Display Page <i class="fa-solid fa-arrow-up-right-from-square"></i></a></span>
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
                                        <a href="<?php echo APP_URL . '/index.php?path=qrcode&event=' . $event->getEventSlug($event_id) ?>" target="_blank">
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
                                    <div class="thumbnail-container" style="background-image: url('<?php echo getAssetPath() . 'img/transparency.svg' ?>'); background-size:cover;">
                                        <img id="thumbnail" class="img-thumbnail" src="<?php echo getUploadPath() . $media->getMediaThumbnail($event->getEventLogo($event_id)); ?>" alt="Event Logo Image">
                                    </div>
                                    <p><strong>Event Banner:</strong></p>
                                    <div class="thumbnail-container" style="background-image: url('<?php echo getAssetPath() . 'img/transparency.svg' ?>'); background-size:cover;">
                                        <img id="thumbnail" class="img-thumbnail" src="<?php echo getUploadPath() . $media->getMediaThumbnail($event->getEventBanner($event_id)); ?>" alt="Event Banner Image">
                                    </div>
                                    <p><strong>School Logo:</strong></p>
                                    <div class="thumbnail-container" style="background-image: url('<?php echo getAssetPath() . 'img/transparency.svg' ?>'); background-size:cover;">
                                        <img id="thumbnail" class="img-thumbnail" src="<?php echo getUploadPath() . $media->getMediaThumbnail($school->getSchoolLogo(intval($event->getEventLocationId($event_id)))); ?>" alt="School Logo Image">
                                    </div>
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
    <?php }
    } ?>
    <script type="module">
        /** import the simple-datatables module, implementation based on the demos/documentation from @fiduswriter/simple-datatables
         * from https://fiduswriter.github.io/simple-datatables/documentation/
         **/
        import {
            DataTable
        } from "<?php echo getLibraryPath() . 'simple-datatables/module.js' ?>"
        const dt = new DataTable("table", {
            scrollY: "50%",
            rowNavigation: true,
            perPageSelect: [5, 10, 15, 20, 25, 50, ["All", -1]],
            classes: {
                active: "active",
                disabled: "disabled",
                selector: "form-select",
                paginationList: "pagination",
                paginationListItem: "page-item",
                paginationListItemLink: "page-link"
            },
            columns: [{
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
            ],
            template: options => `<div class='${options.classes.top} '>
    ${
    options.paging && options.perPageSelect ?
        `<div class='${options.classes.dropdown} bs-bars float-left'>
            <label>
                <select class='${options.classes.selector}'></select>
            </label>
        </div>` :
        ""
}
    ${
    options.searchable ?
        `<div class='${options.classes.search} float-right search btn-group'>
            <input class='${options.classes.input} form-control search-input' placeholder='Search' type='search' title='Search within table'>
        </div>` :
        ""
}
</div>
<div class='${options.classes.container}'${options.scrollY.length ? ` style='height: ${options.scrollY}; overflow-Y: auto;'` : ""}></div>
<div class='${options.classes.bottom} '>
    ${
    options.paging ?
        `<div class='${options.classes.info}'></div>` :
        ""
}
    <nav class='${options.classes.pagination}'></nav>
</div>`,
            tableRender: (_data, table, _type) => {
                const thead = table.childNodes[0]
                thead.childNodes[0].childNodes.forEach(th => {
                    //if the th is not sortable, don't add the sortable class
                    if (th.options?.sortable === false) {
                        return
                    } else {
                        if (!th.attributes) {
                            th.attributes = {}
                        }
                        th.attributes.scope = "col"
                        const innerHeader = th.childNodes[0]
                        if (!innerHeader.attributes) {
                            innerHeader.attributes = {}
                        }
                        let innerHeaderClass = innerHeader.attributes.class ?
                            `${innerHeader.attributes.class} th-inner` : "th-inner"

                        if (innerHeader.nodeName === "a") {
                            innerHeaderClass += " sortable sortable-center both"
                            if (th.attributes.class?.includes("desc")) {
                                innerHeaderClass += " desc"
                            } else if (th.attributes.class?.includes("asc")) {
                                innerHeaderClass += " asc"
                            }
                        }
                        innerHeader.attributes.class = innerHeaderClass
                    }
                })

                return table
            }
        })
        dt.columns.add({
            data: dt.data.data.map((_row, index) => index),
            heading: "#",
            render: (_data, td, _index, _cIndex) => {
                if (!td.attributes) {
                    td.attributes = {}
                }
                td.attributes.scope = "row"
                td.nodeName = "TH"
                return td
            }
        })
        dt.columns.order([0, 1, 2])
        window.dt = dt
    </script>
<?php } ?>
