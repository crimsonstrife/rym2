<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//include the permissions class
$permissionsObject = new Permission();

//include the auth class
$auth = new Authenticator();

/* confirm user has a role with read event permissions */
//get the read event permission id
$readPermissionID = $permissionsObject->getPermissionIdByName('READ EVENT');

//boolean to check if the user has the read event permission
$hasReadPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readPermissionID);

//if the user does not have the read event permission, display an error message and do not display the page
if (!$hasReadPermission) {
    die('Error: You do not have permission to perform this request.');
} else {
?>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Events</h1>
        <div class="row">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-table"></i>
                        Event List
                    </div>
                    <div class="card-tools">
                        <?php
                        /*confirm user has a role with create event permissions*/
                        //get the id of the create event permission
                        $createEventPermissionID = $permissionsObject->getPermissionIdByName('CREATE EVENT');

                        //boolean to check if the user has the create event permission
                        $hasCreatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createEventPermissionID);

                        //if the user has the create event permission, display the add event button
                        if ($hasCreatePermission) {
                        ?>
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=add&action=create' ?>" class="btn btn-primary">Add Event</a>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-body table-scroll">
                    <table id="dataTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Event Title</th>
                                <th>Event Date</th>
                                <th>School</th>
                                <th>Date Created</th>
                                <th>Created By</th>
                                <th>Date Updated</th>
                                <th>Updated By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            /* Setup datatable of events */
                            //include the event class
                            $eventsData = new Event();
                            //include the school class
                            $schoolsData = new School();
                            //include the users class
                            $usersData = new User();
                            //get all events
                            $eventsArray = $eventsData->getEvents();
                            //for each event, display it
                            foreach ($eventsArray as $event) {
                            ?>
                                <tr>
                                    <td><?php echo $event['name']; ?></td>
                                    <td><?php echo $event['event_date']; ?></td>
                                    <td><?php echo $schoolsData->getSchoolById($event['location'])['name']; ?></td>
                                    <td><?php echo $event['created_at']; ?></td>
                                    <td><?php echo $usersData->getUserUsername($event['created_by']); ?>
                                    </td>
                                    <td><?php echo $event['updated_at']; ?></td>
                                    <td><?php echo $usersData->getUserUsername($event['updated_by']); ?>
                                    </td>
                                    <td>
                                        <?php /*confirm user has a role with read event permissions*/
                                        //get the read event permission id
                                        $updatePermissionID = $permissionsObject->getPermissionIdByName('READ EVENT');

                                        //boolean to check if the user has the read event permission
                                        $hasReadPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                                        //only show the edit button if the user has the read event permission
                                        if ($hasReadPermission) { ?>
                                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=single' ?>&id=<?php echo $event['id']; ?>" class="btn btn-success">View Event</a>
                                        <?php } ?>
                                        <?php /*confirm user has a role with update event permissions*/
                                        //get the update event permission id
                                        $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE EVENT');

                                        //boolean to check if the user has the update event permission
                                        $hasUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                                        //only show the edit button if the user has the update event permission
                                        if ($hasUpdatePermission) { ?>
                                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=edit&action=edit&id=' . $event['id']; ?>" class="btn btn-primary">Edit Event</a>
                                        <?php } ?>
                                        <?php /*confirm user has a role with delete event permissions*/
                                        //get the delete event permission id
                                        $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE EVENT');

                                        //boolean to check if the user has the delete event permission
                                        $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                                        //only show the delete button if the user has the delete event permission
                                        if ($hasDeletePermission) { ?>
                                            <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteEventModal" onclick="setDeleteID(<?php echo $event['id']; ?>)">
                                                Delete Event
                                            </button>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <!-- Download CSV -->
                    <?php
                    /*confirm user has a role with export events permissions*/
                    //get the id of the export events permission
                    $exportEventsPermissionID = $permissionsObject->getPermissionIdByName('EXPORT EVENT');

                    //boolean to check if the user has the export events permission
                    $hasExportEventsPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $exportEventsPermissionID);

                    if ($hasExportEventsPermission) {
                        //prepare the events array for download
                        $csvArray = $eventsArray;
                        //set the created by and updated by to the username
                        foreach ($csvArray as $key => $row) {
                            $csvArray[$key]['created_by'] = $usersData->getUserUsername(intval($row['created_by'])); //get the username of the user who created the event, and swap out the user id
                            $csvArray[$key]['updated_by'] = $usersData->getUserUsername(intval($row['updated_by'])); //get the username of the user who updated the event, and swap out the user id
                            $csvArray[$key]['student_count'] = $eventsData->getStudentCount(intval($row['id'])); //get the number of students attending the event
                            $csvArray[$key]['location'] = $schoolsData->getSchoolById(intval($row['location']))['name'];
                        }
                        //clean up the column headers to be more readable, i.e. remove underscores and capitalize
                        foreach ($csvArray as $key => $row) {
                            $csvArray[$key] = array(
                                'Event Name' => $row['name'],
                                'Event Date' => $row['event_date'],
                                'School' => $row['location'],
                                'Date Created' => $row['created_at'],
                                'Created By' => $row['created_by'],
                                'Date Updated' => $row['updated_at'],
                                'Updated By' => $row['updated_by'],
                                'Student Count' => $row['student_count']
                            );
                        }
                    ?>
                        <form target="_blank" action="<?php echo APP_URL . '/admin/download.php?type=events&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>" method="post" enctype="multipart/form-data">
                            <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                        </form>
                    <?php } else { ?>
                        <p class="text-danger">You do not have permission to download the CSV of events.</p>
                        <button class="btn btn-success" disabled>Export to CSV</button>
                    <?php } ?>
                </div>
                <?php if ($hasDeletePermission) { ?>
                    <div id="info" class="">
                        <!-- Delete Event Modal-->
                        <!-- Modal -->
                        <div id="deleteEventModal" class="modal fade delete" tabindex="-1" role="dialog" aria-labelledby="#eventDeleteModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="eventDeleteModal">Delete Event - <span id="eventName-Title">Event Name</span></h3>
                                        <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this event?</p>
                                        <p>This action cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <script>
                                            var deleteBaseURL =
                                                "<?php echo APP_URL . '/admin/dashboard.php?view=events&event=single&action=delete&id='; ?>";
                                        </script>
                                        <form id="deleteEventForm" action="" method="post">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="clearDeleteID()">Cancel</button>
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
        <?php if ($hasDeletePermission) { ?>
            <script>
                //set the events array to a javascript variable
                var eventsArray = <?php echo json_encode($eventsArray); ?>;

                //function to set the delete id on the action url of the delete modal based on which event is selected
                function setDeleteID(id) {
                    //get the event name
                    var eventName = eventsArray.find(event => event.id == id).name;
                    //set the event name in the modal title
                    document.getElementById("eventName-Title").innerHTML = eventName;
                    //set the action url of the delete modal
                    document.getElementById("deleteEventForm").action = deleteBaseURL + id;
                }

                function clearDeleteID() {
                    //set the action url of the delete modal
                    document.getElementById("deleteEventForm").action = "";
                }
            </script>
        <?php } ?>
    </div>
<?php } ?>
