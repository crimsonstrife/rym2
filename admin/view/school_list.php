<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}
?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Schools</h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-table"></i>
                    School List
                </div>
                <div class="card-tools">
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=add&action=create' ?>"
                        class="btn btn-primary">Add School</a>
                </div>
            </div>
            <div class="card-body">
                <table id="dataTable">
                    <thead>
                        <tr>
                            <th>School Name</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Zip Code</th>
                            <th>Events Held</th>
                            <th>Date Created</th>
                            <th>Created By</th>
                            <th>Date Updated</th>
                            <th>Updated By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        /* Setup datatable of Schools */
                        //include the event class
                        $eventsData = new Event();
                        //include the school class
                        $schoolsData = new School();
                        //include the users class
                        $usersData = new User();
                        //get all schools
                        $schoolsArray = $schoolsData->getSchools();
                        //for each event, display it
                        foreach ($schoolsArray as $school) {
                        ?>
                        <tr>
                            <td><?php echo $school['name']; ?></td>
                            <td><?php echo $school['address']; ?></td>
                            <td><?php echo $school['city']; ?></td>
                            <td><?php echo $school['state']; ?></td>
                            <td><?php echo $school['zipcode']; ?></td>
                            <td><?php echo $eventsData->getHeldEvents(intval($school['id'])); ?></td>
                            <td><?php echo $school['created_at']; ?></td>
                            <td><?php echo $usersData->getUserUsername($school['created_by']); ?>
                            </td>
                            <td><?php echo $school['updated_at']; ?></td>
                            <td><?php echo $usersData->getUserUsername($school['updated_by']); ?>
                            </td>
                            <td>
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=single' ?>&id=<?php echo $school['id']; ?>"
                                    class="btn btn-success">View</a>
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=edit&action=edit&id=' . $school['id']; ?>"
                                    class="btn btn-primary">Edit</a>
                                <a href="/delete/delete_school.php?id=<?php echo $school['id']; ?>"
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
                    $csvArray = $schoolsArray;
                    //set the created by and updated by to the username
                    foreach ($csvArray as $key => $row) {
                        $csvArray[$key]['created_by'] = $usersData->getUserUsername(intval($row['created_by'])); //get the username of the user who created the event, and swap out the user id
                        $csvArray[$key]['updated_by'] = $usersData->getUserUsername(intval($row['updated_by'])); //get the username of the user who updated the event, and swap out the user id
                    }
                    //clean up the column headers to be more readable, i.e. remove underscores and capitalize
                    foreach ($csvArray as $key => $row) {
                        $csvArray[$key] = array(
                            'School Name' => $row['name'],
                            'Address' => $row['address'],
                            'City' => $row['city'],
                            'State' => $row['state'],
                            'Zip Code' => $row['zipcode'],
                            'Events Held' => $eventsData->getHeldEvents(intval($row['id'])),
                            'Date Created' => $row['created_at'],
                            'Created By' => $row['created_by'],
                            'Date Updated' => $row['updated_at'],
                            'Updated By' => $row['updated_by']
                        );
                    }
                    ?>
                    <form target="_blank"
                        action="<?php echo APP_URL . '/admin/download.php?payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>"
                        method="post" enctype="multipart/form-data">
                        <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ?>
