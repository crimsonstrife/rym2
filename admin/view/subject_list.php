<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
} ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Subjects/Fields</h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-table"></i>
                    Subjects List
                </div>
                <div class="card-tools">
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=subjects&subject=add&action=create' ?>"
                        class="btn btn-primary">Add Subject</a>
                </div>
            </div>
            <div class="card-body">
                <table id="dataTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Subject/Field Name</th>
                            <th>Date Created</th>
                            <th>Created By</th>
                            <th>Date Updated</th>
                            <th>Updated By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        /* Setup datatable of Subjects */
                        //include the Area of Interest class
                        $subjectsData = new AreaOfInterest();
                        //include the users class
                        $usersData = new User();
                        //get all subjects
                        $subjectArray = $subjectsData->getAllSubjects();
                        //for each event, display it
                        foreach ($subjectArray as $subject) {
                        ?>
                        <tr>
                            <td><?php echo $subject['name']; ?></td>
                            <td><?php echo $subject['created_at']; ?></td>
                            <td><?php echo $usersData->getUserUsername($subject['created_by']); ?></td>
                            <td><?php echo $subject['updated_at']; ?></td>
                            <td><?php echo $usersData->getUserUsername($subject['updated_by']); ?></td>
                            <td>
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=subjects&subject=edit&action=edit&id=' . $subject['id']; ?>"
                                    class="btn btn-primary">Edit</a>
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=subjects&subject=delete&action=delete&id=' . $subject['id']; ?>"
                                    class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="card-footer">
                    <!-- Download CSV -->
                    <?php
                    //prepare the degree array for download
                    $csvArray = $subjectArray;
                    //set the created by and updated by to the username
                    foreach ($csvArray as $key => $row) {
                        $csvArray[$key]['created_by'] = $usersData->getUserUsername(intval($row['created_by'])); //get the username of the user who created the subject, and swap out the user id
                        $csvArray[$key]['updated_by'] = $usersData->getUserUsername(intval($row['updated_by'])); //get the username of the user who updated the subject, and swap out the user id
                    }
                    //clean up the column headers to be more readable, i.e. remove underscores and capitalize
                    foreach ($csvArray as $key => $row) {
                        $csvArray[$key] = array(
                            'Subject/Field Name' => $row['name'],
                            'Date Created' => $row['created_at'],
                            'Created By' => $row['created_by'],
                            'Date Updated' => $row['updated_at'],
                            'Updated By' => $row['updated_by']
                        );
                    }
                    ?>
                    <form target="_blank"
                        action="<?php echo APP_URL . '/admin/download.php?type=subjects&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>"
                        method="post" enctype="multipart/form-data">
                        <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ?>
