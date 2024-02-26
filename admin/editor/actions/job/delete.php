<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
}

//include the permissions class
$permissionsObject = new Permission();

//include the authenticator class
$auth = new Authenticator();

//session class
$session = new Session();

/*confirm user has a role with delete job permissions*/
//get the id of the delete job permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('DELETE JOB');

//boolean to track if the user has the delete job permission
$hasPermission = $auth->checkUserPermission(intval($session->get('user_id')), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
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

        //boolean to track if the job was deleted
        $jobDeleted = false;

        //if the canDelete boolean is true, delete the job
        if ($canDelete) {
            $jobDeleted = $job->deleteJob($job_id);
        }
    } ?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?php echo $job_title; ?></h1>
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
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- show completion message -->
                        <div class="col-md-12">
                            <?php
                            if ($action == 'delete') {
                                if ($jobDeleted) {
                                    echo '<p>The job: ' . $job_title . ' has been deleted.</p>';
                                } else {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo '<p>The job: ' . $job_title . ' could not be deleted.</p>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <!-- show error messages -->
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($action == 'delete') {
                                if ($canDelete && !$jobDeleted) {
                                    echo '<p>The job: ' . $job_title . ' could not be deleted, due to an unknown error.</p>';
                                } else {
                                    echo '<p>All associated records for the job: ' . $job_title . ' have been deleted.</p>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <!-- show back buttons -->
                        <div class="col-md-12">
                            <div class="card-buttons">
                                <?php
                                if ($action == 'delete') {
                                    if ($jobDeleted) {
                                        echo '<a href="' . APP_URL . '/admin/dashboard.php?view=jobs&job=list" class="btn btn-primary">Return to Job List</a>';
                                    } else {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=jobs&job=list" class="btn btn-primary">Return to Job List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=jobs&job=single&id=' . $job_id . '" class="btn btn-secondary">Return to Job</a></span>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
