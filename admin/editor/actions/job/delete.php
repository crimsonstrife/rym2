<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//include the permissions class
$permissionsObject = new Permission();

//include the authenticator class
$auth = new Authenticator();

/*confirm user has a role with delete job permissions*/
//get the id of the delete job permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('DELETE JOB');

//boolean to track if the user has the delete job permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    die('Error: You do not have permission to perform this request.');
} else {
    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the action from the url parameter
        $action = $_GET['action'];

        //include the job class
        $job = new Job();

        //if the action is delete, get the job id from the url parameter
        if ($action == 'delete') {
            $job_id = $_GET['id'];
        }

        //get the intvalue of the job id
        $job_id = intval($job_id);

        //get the job name
        $job_name = $job->getJobTitle($job_id);

        //boolean to track if the job can be deleted
        $canDelete = true;

        //if the canDelete boolean is true, delete the job
        if ($canDelete) {
            $jobDeleted = $job->deleteJob($job_id);
        } else {
            $jobDeleted = false;
        }
    } ?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <div>
                            <?php
                            if ($action == 'delete') {
                                if ($jobDeleted) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Job Deleted';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Job Not Deleted';
                                }
                            }
                            ?>
                        </div>
                        <div>
                            <?php
                            if ($action == 'delete') {
                                if ($canDelete && !$jobDeleted) {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo 'The job: ' . $job_name . ', could not be deleted because of an unknown error.';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
