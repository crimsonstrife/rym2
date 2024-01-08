<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}
?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Open Jobs/Internships</h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-table"></i>
                    Jobs List
                </div>
                <div class="card-tools">
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=add&action=create' ?>"
                        class="btn btn-primary">Add Job</a>
                </div>
            </div>
            <div class="card-body table-scroll">
                <table id="dataTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Position Type</th>
                            <th>Field</th>
                            <th>Date Created</th>
                            <th>Created By</th>
                            <th>Date Updated</th>
                            <th>Updated By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        /* Setup datatable of Jobs */
                        //include the job class
                        $jobsData = new Job();
                        //include the users class
                        $usersData = new User();
                        //include the areas of interest class
                        $fieldData = new JobField();
                        //get all jobs
                        $jobArray = $jobsData->getAllJobs();
                        //for each job, display it
                        foreach ($jobArray as $job) { ?>
                        <tr>
                            <td><?php echo $job['name']; ?></td>
                            <td><?php echo $job['description']; ?></td>
                            <td><?php echo $jobsData->getJobType($job['id']); ?></td>
                            <td><?php echo $fieldData->getSubject($job['field'])[0]['name']; ?></td>
                            <td><?php echo $job['created_at']; ?></td>
                            <td><?php echo $usersData->getUserUsername($job['created_by']); ?></td>
                            <td><?php echo $job['updated_at']; ?></td>
                            <td><?php echo $usersData->getUserUsername($job['updated_by']); ?></td>
                            <td>
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=edit&action=edit&id=' . $job['id']; ?>"
                                    class="btn btn-primary">Edit</a>
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=delete&action=delete&id=' . $job['id']; ?>"
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
                //prepare the Major array for download
                $csvArray = $jobArray;
                //set the created by and updated by to the username
                foreach ($csvArray as $key => $row) {
                    $csvArray[$key]['created_by'] = $usersData->getUserUsername(intval($row['created_by'])); //get the username of the user who created the degree, and swap out the user id
                    $csvArray[$key]['updated_by'] = $usersData->getUserUsername(intval($row['updated_by'])); //get the username of the user who updated the degree, and swap out the user id
                }
                //set the field to the name of the area of interest
                foreach ($csvArray as $key => $row) {
                    $csvArray[$key]['field'] = $fieldData->getSubject(intval($row['field']))[0]['name']; //get the name of the area of interest, and swap out the id
                }
                //clean up the column headers to be more readable, i.e. remove underscores and capitalize
                foreach ($csvArray as $key => $row) {
                    $csvArray[$key] = array(
                        'Job Title' => $row['name'],
                        'Description' => $row['description'],
                        'Position Type' => $row['type'],
                        'Field' => $row['field'],
                        'Date Created' => $row['created_at'],
                        'Created By' => $row['created_by'],
                        'Date Updated' => $row['updated_at'],
                        'Updated By' => $row['updated_by']
                    );
                }
                ?>
                <form target="_blank"
                    action="<?php echo APP_URL . '/admin/download.php?type=jobs&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>"
                    method="post" enctype="multipart/form-data">
                    <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                </form>
            </div>
        </div>
    </div>
</div>
