<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
}

//include the permissions class
$permissionsObject = new Permission();

//include the auth class
$auth = new Authenticator();

//user class
$user = new User();

/* confirm user has a role with read job permissions */
//get the read job permission id
$readPermissionID = $permissionsObject->getPermissionIdByName('READ JOB');

//boolean to check if the user has the read job permission
$hasReadPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readPermissionID);

/*confirm user has a role with delete job permissions*/
//get the delete job permission id
$deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE JOB');

//boolean to check if the user has the delete job permission
$hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

//if the user does not have the read job permission, display an error message and do not display the page
if (!$hasReadPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
} else {
    //include the job class
    $job = new Job();

    if (isset($_GET['id'])) {
        //get the job id from the url parameter
        $job_id = $_GET['id'];
    } else {
        //set the job id to null
        $job_id = null;
    }

    //confirm the id exists
    if (empty($job_id) || $job_id == null) {
        //set the error type
        $thisError = 'INVALID_REQUEST_ERROR';

        //include the error message file
        include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
    } else {
        //try to get the job information
        $object = $job->getJob(intval($job_id));

        //check if the job is empty
        if (empty($object)) {
            //set the error type
            $thisError = 'NOT_FOUND';

            //include the error message file
            include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
        }
    }

    //if not empty, display the job information
    if (!empty($object)) {
?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $job->getJobTitle(intval($job_id)); ?></h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-briefcase"></i>
                    Job Information
                </div>
                <div class="card-buttons">
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=list'; ?>"
                        class="btn btn-secondary">Back to Jobs</a>
                    <?php /*confirm user has a role with update job permissions*/
                            //get the update job permission id
                            $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE JOB');

                            //boolean to check if the user has the update job permission
                            $hasUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                            //only show the edit button if the user has the update job permission
                            if ($hasUpdatePermission) { ?>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=edit&action=edit&id=' . $job_id; ?>"
                        class="btn btn-primary">Edit Job</a>
                    <?php } ?>
                    <?php /*confirm user has a role with delete job permissions*/
                            //get the delete job permission id
                            $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE JOB');

                            //boolean to check if the user has the delete job permission
                            $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                            //only show the delete button if the user has the delete job permission
                            if ($hasDeletePermission) { ?>
                    <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteJobModal">
                        Delete Job
                    </button>
                    <?php } ?>
                </div>
            </div>
            <div class="card-body">
                <!-- Single Job information -->
                <div class="row">
                    <div class="col-md-6" style="height: 100%;">
                        <h3>Job Details</h3>
                        <div id="info" class="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Job Title:</strong></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><?php echo $job->getJobTitle($job_id); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Posted By:</strong></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo $user->getUserUserName(intval($object['created_by'])); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Date Posted:</strong></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo formatDate($object['created_at']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p><strong>Job Summary:</strong></p>
                                    </div>
                                    <div class="col-md-12">
                                        <p><?php echo $job->getJobSummary($job_id); ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Job Type:</strong></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo $job->getJobType($job_id); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php
                                            //include the job field class
                                            $job_field = new JobField();
                                            //get the job field id
                                            $job_field_id = $job->getJobField($job_id);

                                            //get the job field name
                                            $job_field_name = $job_field->getSubjectName($job_field_id);
                                            ?>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Job Field:</strong></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo $job_field_name; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p><strong>Job Description:</strong></p>
                                    </div>
                                    <div class="col-md-12">
                                        <p><?php echo $job->getJobDescription($job_id); ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php
                                            //get the job skills
                                            $job_skills = $job->getJobSkills($job_id);
                                            ?>
                                    <div class="col-md-12">
                                        <p><strong>Job Skills:</strong></p>
                                    </div>
                                    <div class="col-md-12">
                                        <ul id="jobSkillsList" name="job_skills_list" class="list-group job-skill-list">
                                            <?php
                                                    //if the job skills are not empty, loop through the array and display the skills
                                                    if (!empty($job_skills)) {
                                                        foreach ($job_skills as $skill) { ?>
                                            <li class="list-group-item job-skill-item" style="border-top-width: 1px;">
                                                <?php echo $skill; ?></li>
                                            <?php }
                                                    } else { ?>
                                            <li style="list-style: none;">No skills listed</li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                    </div>
                    <?php if ($hasDeletePermission) { ?>
                    <div id="info" class="">
                        <!-- Delete Job Modal-->
                        <!-- Modal -->
                        <div id="deleteJobModal" class="modal fade delete" tabindex="-1" role="dialog"
                            aria-labelledby="#jobDeleteModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="jobDeleteModal">Delete Job -
                                            <?php echo $job->getJobTitle($job_id); ?></h3>
                                        <button type="button" class="btn-close close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this job?</p>
                                        <p>This action cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form
                                            action="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=single&action=delete&id=' . $job_id; ?>"
                                            method="post">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Delete Job</button>
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
    <?php }
} ?>
