<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}
?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Degree Levels</h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-table"></i>
                    Degree List
                </div>
                <div class="card-tools">
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=degrees&degree=add&action=create' ?>"
                        class="btn btn-primary">Add Degree</a>
                </div>
            </div>
            <div class="card-body table-scroll">
                <table id="dataTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Degree Name/Title</th>
                            <th>Date Created</th>
                            <th>Created By</th>
                            <th>Date Updated</th>
                            <th>Updated By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        /* Setup datatable of Degree Levels */
                        //include the degree class
                        $degreesData = new Degree();
                        //include the users class
                        $usersData = new User();
                        //get all degree levels
                        $degreeArray = $degreesData->getAllGrades();
                        //for each event, display it
                        foreach ($degreeArray as $degree) {
                        ?>
                        <tr>
                            <td><?php echo $degree['name']; ?></td>
                            <td><?php echo $degree['created_at']; ?></td>
                            <td><?php echo $usersData->getUserUsername($degree['created_by']); ?></td>
                            <td><?php echo $degree['updated_at']; ?></td>
                            <td><?php echo $usersData->getUserUsername($degree['updated_by']); ?></td>
                            <td>
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=degrees&degree=edit&action=edit&id=' . $degree['id']; ?>"
                                    class="btn btn-primary">Edit</a>
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=degrees&degree=delete&action=delete&id=' . $degree['id']; ?>"
                                    class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <!-- Download CSV -->
                <?php
                //prepare the degree array for download
                $csvArray = $degreeArray;
                //set the created by and updated by to the username
                foreach ($csvArray as $key => $row) {
                    $csvArray[$key]['created_by'] = $usersData->getUserUsername(intval($row['created_by'])); //get the username of the user who created the degree, and swap out the user id
                    $csvArray[$key]['updated_by'] = $usersData->getUserUsername(intval($row['updated_by'])); //get the username of the user who updated the degree, and swap out the user id
                }
                //clean up the column headers to be more readable, i.e. remove underscores and capitalize
                foreach ($csvArray as $key => $row) {
                    $csvArray[$key] = array(
                        'Degree Name' => $row['name'],
                        'Date Created' => $row['created_at'],
                        'Created By' => $row['created_by'],
                        'Date Updated' => $row['updated_at'],
                        'Updated By' => $row['updated_by']
                    );
                }
                ?>
                <form target="_blank"
                    action="<?php echo APP_URL . '/admin/download.php?type=degrees&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>"
                    method="post" enctype="multipart/form-data">
                    <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                </form>
            </div>
        </div>
    </div>
</div>
<?php ?>
