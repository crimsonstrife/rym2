<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}
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
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=add&action=create' ?>"
                        class="btn btn-primary">Add Event</a>
                </div>
            </div>
            <div class="card-body">
                <table id="dataTable">
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
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=single' ?>&id=<?php echo $event['id']; ?>"
                                    class="btn btn-success">View</a>
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=edit&action=edit&id=' . $event['id']; ?>"
                                    class="btn btn-primary">Edit</a>
                                <a href="/delete/delete_event.php?id=<?php echo $event['id']; ?>"
                                    class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="card-footer">
                    <!-- Download CSV -->
                    <?php
                    //prepare the events array for download
                    //set the created by and updated by to the username
                    foreach ($eventsArray as $key => $event) {
                        $eventsArray[$key]['created_by'] = $usersData->getUserUsername(intval($event['created_by']));
                        $eventsArray[$key]['updated_by'] = $usersData->getUserUsername(intval($event['updated_by']));
                    }
                    //set the location to the school name
                    foreach ($eventsArray as $key => $event) {
                        $eventsArray[$key]['location'] = $schoolsData->getSchoolById(intval($event['location']))['name'];
                    }
                    //add a column for the total number of attendees for each event
                    array_push($eventsArray, array('student_count' => ''));
                    //get the total number of attendees for each event
                    foreach ($eventsArray as $key => $event) {
                        $eventsArray[$key]['student_count'] = $eventsData->getStudentCount(intval($event['id']));
                    }
                    //clean up the column headers to be more readable, i.e. remove underscores and capitalize
                    foreach ($eventsArray as $key => $event) {
                        $eventsArray[$key] = array(
                            'Event Name' => $event['name'],
                            'Event Date' => $event['event_date'],
                            'School' => $event['location'],
                            'Date Created' => $event['created_at'],
                            'Created By' => $event['created_by'],
                            'Date Updated' => $event['updated_at'],
                            'Updated By' => $event['updated_by'],
                            'Student Count' => $event['student_count']
                        );
                    }
                    ?>
                    <form target="_blank"
                        action="<?php echo APP_URL . '/admin/download.php?payload=' . base64_encode(urlencode(json_encode($eventsArray))); ?>"
                        method="post" enctype="multipart/form-data">
                        <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ?>
