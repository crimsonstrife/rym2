<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
} // idea from https://stackoverflow.com/a/409515 (user UnkwnTech)
//check for the event id in the url parameter
?>
<!-- main content -->
<div id="layout_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Events</h1>
            <div class="row">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fa-solid fa-table"></i>
                        Event List
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
                                        <td><?php echo $usersData->getUserById($event['created_by'])['username']; ?>
                                        </td>
                                        <td><?php echo $event['updated_at']; ?></td>
                                        <td><?php echo $usersData->getUserById($event['updated_by'])['username']; ?>
                                        </td>
                                        <td>
                                            <a href="/view/event_single.php?id=<?php echo $event['id']; ?>" class="btn btn-success">View</a>
                                            <a href="/editor/edit_event.php?id=<?php echo $event['id']; ?>" class="btn btn-primary">Edit</a>
                                            <a href="/delete/delete_event.php?id=<?php echo $event['id']; ?>" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <div class="card-footer">
                            <!-- Download CSV -->
                            <a href="#" class="btn btn-primary">Download CSV</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
