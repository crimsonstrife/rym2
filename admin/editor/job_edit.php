<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
}

//include the permissions class
$permissionsObject = new Permission();

//include the auth class
$auth = new Authenticator();

//job class
$job = new Job();

//user class
$user = new User();

echo '<script src="' . htmlspecialchars(getLibraryPath()) . 'ckeditor/ckeditor.js' . '"></script>';

//check that action is set in the URL parameters
if (isset($_GET['action'])) {
    //get the action from the URL parameters
    $action = $_GET['action'];

    //setup an array of the position types, each item will have a value and a label
    $positionType_list = JOBTYPES;

    //if the action is edit, show the job edit form
    if ($action == 'edit') {
        //get the update job permission id
        $updateJobPermissionID = $permissionsObject->getPermissionIdByName('UPDATE JOB');

        //boolean to check if the user has the update job permission
        $hasUpdateJobPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updateJobPermissionID);

        //if the user does not have the update job permission, prevent access to the editor
        if (!$hasUpdateJobPermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else {

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
                include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
            } else {
                //try to get the job information
                $object = $job->getJob(intval($job_id));

                //check if the job is empty
                if (empty($object)) {
                    //set the error type
                    $thisError = 'NOT_FOUND';

                    //include the error message file
                    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
                }
            }

            //if not empty, display the job information
            if (!empty($object)) {
?>
                <div class="container-fluid px-4">
                    <h1 class="mt-4"><?php echo htmlspecialchars($job->getJobTitle(intval($job_id))); ?></h1>
                    <div class="row">
                        <div class="card mb-4">
                            <!-- Edit Form -->
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . htmlspecialchars($_GET['view']) . '&job=' . htmlspecialchars($_GET['job']) . '&action=' . htmlspecialchars($_GET['action']) . '&id=' . htmlspecialchars($_GET['id']); ?>" method="post" enctype="multipart/form-data">
                                <div class="card-header">
                                    <div class="card-title">
                                        <i class="fa-solid fa-briefcase"></i>
                                        Edit Job
                                    </div>
                                    <div class="card-buttons">
                                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=list'; ?>" class="btn btn-secondary">Back to Jobs</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Form Information -->
                                        <div class="col-md-6">
                                            <div class="info">
                                                <p>
                                                    <span class="info-title"><strong>Instructions:</strong> </span>
                                                    <span class="info-text">Use this form to edit the job/internship listing,
                                                        <strong><span class="required">*</span></strong> denotes a required
                                                        field.</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <p>
                                                    <strong>
                                                        <label for="jobTitle">Job Title: <strong><span class="required">*</span></strong></label>
                                                    </strong>
                                                </p>
                                                <p>
                                                    <input type="text" id="jobTitle" name="job_title" class="form-control" value="<?php echo $job->getJobTitle(intval($job_id)); ?>" placeholder="<?php echo $job->getJobTitle(intval($job_id)); ?>" required>
                                                </p>
                                                <p><small id="jobTitleHelp" class="form-text text-muted">Enter a title for the
                                                        job.</small></p>
                                            </div>
                                            <div class="form-group">
                                                <p>
                                                    <strong>
                                                        <label for="jobSummary">Job Summary: <strong><span class="required">*</span></strong></label>
                                                    </strong>
                                                </p>
                                                <p>
                                                    <textarea id="jobSummary" name="job_summary" class="form-control" rows="3" maxlength="200" placeholder="<?php echo $job->getJobSummary(intval($job_id)); ?>" required><?php echo $job->getJobSummary(intval($job_id)); ?></textarea>
                                                </p>
                                                <p><small id="jobSummaryHelp" class="form-text text-muted">Enter a summary for
                                                        the job, under 200 characters.</small></p>
                                                <p>
                                                    <!-- counter for job summary -->
                                                <div id="counter hint-text"><span id="jobSummaryCounter"><small id="summaryCounterText" class="form-text text-muted">200/200</small><small class="form-text text-muted">
                                                            characters remaining</small></span></div>
                                                </p>
                                            </div>
                                            <div class="form-group">
                                                <p>
                                                    <strong>
                                                        <label for="jobDescription">Job Description: <strong><span class="required">*</span></strong></label>
                                                    </strong>
                                                </p>
                                                <p>
                                                    <textarea id="jobDescription" name="job_description" class="form-control wysiwyg-editor" rows="10" placeholder="<?php echo $job->getJobDescription(intval($job_id)); ?>" required><?php echo $job->getJobDescription(intval($job_id)); ?></textarea>
                                                </p>
                                                <p><small id="jobDescriptionHelp" class="form-text text-muted">Enter a
                                                        description for the job, can use HTML to style the text.</small></p>
                                            </div>
                                            <div class="form-group">
                                                <p>
                                                    <strong>
                                                        <label for="jobType">Job Type: <strong><span class="required">*</span></strong></label>
                                                    </strong>
                                                </p>
                                                <p>
                                                    <select id="jobType" name="job_type" class="form-control app-forms" required>
                                                        <?php
                                                        //for each item in the position type list, display it as an option
                                                        foreach ($positionType_list as $positionType) {
                                                            //check if the item in the list matches the job type
                                                            if ($positionType['value'] == $job->getJobTypeEnum(intval($job_id))) {
                                                                //if it matches, set the option to selected
                                                                echo '<option value="' . $positionType['value'] . '" selected>' . $positionType['label'] . '</option>';
                                                            } else {
                                                                //if it doesn't match, set the option to not selected
                                                                echo '<option value="' . $positionType['value'] . '">' . $positionType['label'] . '</option>';
                                                            }
                                                        } ?>
                                                    </select>
                                                </p>
                                                <p><small id="jobTypeHelp" class="form-text text-muted">Select the type of job
                                                        listing.</small></p>
                                            </div>
                                            <div class="form-group">
                                                <p>
                                                    <strong>
                                                        <label for="jobField">Job Field: <strong><span class="required">*</span></strong></label>
                                                    </strong>
                                                </p>
                                                <p>
                                                    <select id="jobField" name="job_field" class="form-control app-forms" required>
                                                        <?php
                                                        //include the job field class
                                                        $jobField = new JobField();
                                                        //get all job fields
                                                        $jobFieldArray = $jobField->getAllSubjects();
                                                        //for each job field, display it as an option
                                                        foreach ($jobFieldArray as $jobField) {
                                                            //check if the item in the list matches the job field
                                                            if (intval($jobField['id']) == $job->getJobField(intval($job_id))) {
                                                                //if it matches, set the option to selected
                                                                echo '<option value="' . $jobField['id'] . '" selected>' . $jobField['name'] . '</option>';
                                                            } else {
                                                                //if it doesn't match, set the option to not selected
                                                                echo '<option value="' . $jobField['id'] . '">' . $jobField['name'] . '</option>';
                                                            }
                                                        } ?>
                                                    </select>
                                                </p>
                                                <p><small id="jobFieldHelp" class="form-text text-muted">Select the field of the
                                                        job.</small></p>
                                            </div>
                                            <div class="form-group">
                                                <p>
                                                    <strong>
                                                        <label for="jobField">Job Skills:</label>
                                                    </strong>
                                                </p>
                                                <?php
                                                //get the job skills
                                                $jobSkills = $job->getJobSkills(intval($job_id));
                                                ?>
                                                <p>
                                                <div class="input-group mb-3">
                                                    <input type="text" id="jobSkills" name="job_skills" class="form-control" placeholder="Job Skills">
                                                    <!-- button to add a new skill to list -->
                                                    <input type="button" id="addSkill" name="add_skill" class="form-control btn btn btn-outline-success btn-skill-btn btn-add" value="Add Skill">
                                                </div>
                                                <div class="input-group mb-3">
                                                    <!-- list of skills -->
                                                    <ul id="jobSkillsList" name="job_skills_list" class="form-control list-group job-skill-list">
                                                        <?php
                                                        //for each skill in the job skills array, display it as a list item
                                                        foreach ($jobSkills as $skill) {
                                                            //create a unique id for the list item
                                                            $skillID = uniqid();
                                                            echo '<li id="' . $skillID . '" class="list-group-item job-skill-item">' . $skill . '</li>';
                                                        } ?>
                                                    </ul>
                                                    <!-- button to remove a skill from the list -->
                                                    <input type="button" id="removeSkill" name="remove_skill" class="form-control btn btn btn-outline-danger btn-skill-btn btn-remove" value="Remove Skill">
                                                    <!-- hidden field to store the list of skills as an array -->
                                                    <input type="hidden" id="jobSkillsArray" name="job_skills_array[]" hidden>
                                                </div>
                                                </p>
                                                <p><small id="jobSkillsHelp" class="form-text text-muted">Enter a comma
                                                        separated list of skills for the job.</small></p>
                                            </div>
                                            <div class="form-group">
                                                <p>
                                                    <strong>
                                                        <label for="jobField">Education Requirement: <strong><span class="required">*</span></strong></label>
                                                    </strong>
                                                </p>
                                                <p>
                                                    <select id="jobEducation" name="job_education" class="form-control app-forms" required>
                                                        <option value="0" <?php if ($job->getJobEducation(intval($job_id)) == 0) {
                                                                                echo 'selected';
                                                                            } ?>>None</option>
                                                        <?php
                                                        //include the degree level class
                                                        $degree = new Degree();
                                                        //get all education levels
                                                        $educationArray = $degree->getAllGrades();
                                                        //for each education level, display it as an option
                                                        foreach ($educationArray as $degreeLevel) {
                                                            //check if the item in the list matches the job education
                                                            if (intval($degreeLevel['id']) == $job->getJobEducation(intval($job_id))) {
                                                                //if it matches, set the option to selected
                                                                echo '<option value="' . $degreeLevel['id'] . '" selected>' . $degreeLevel['name'] . '</option>';
                                                            } else {
                                                                //if it doesn't match, set the option to not selected
                                                                echo '<option value="' . $degreeLevel['id'] . '">' . $degreeLevel['name'] . '</option>';
                                                            }
                                                        } ?>
                                                    </select>
                                                </p>
                                                <p><small id="jobEducationHelp" class="form-text text-muted">Select the
                                                        education requirement for the job.</small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class=" card-footer">
                                    <button name="create_Button" type="submit" class="btn btn-primary">Save Changes</button>
                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=list'; ?>" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php }
        }
    } else if ($action == 'create') { //else if the action is create, show the job creation form
        //get the create job permission id
        $createJobPermissionID = $permissionsObject->getPermissionIdByName('CREATE JOB');

        //boolean to check if the user has the create job permission
        $hasCreateJobPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createJobPermissionID);

        //if the user does not have the create job permission, prevent access to the editor
        if (!$hasCreateJobPermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else { //else if the action is create, show the job creation form

            //make sure there is at least one field in the job field table
            $jobField = new JobField();

            //get all job fields
            $jobFieldArray = $jobField->getAllSubjects();

            //if the job field array is empty, display an error message
            if (empty($jobFieldArray)) {
                //set the error type
                $thisError = 'DATA_MISSING';

                //include the error message file
                include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
            } else {
                //display the job creation form
            ?>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">New Job</h1>
                    <div class="row">
                        <div class="card mb-4">
                            <!-- Create Form -->
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . htmlspecialchars($_GET['view']) . '&job=' . htmlspecialchars($_GET['job']) . '&action=' . htmlspecialchars($_GET['action']); ?>" method="post" enctype="multipart/form-data">
                                <div class="card-header">
                                    <div class="card-title">
                                        <i class="fa-solid fa-briefcase"></i>
                                        Create Job
                                    </div>
                                    <div class="card-buttons">
                                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=list'; ?>" class="btn btn-secondary">Back to Jobs</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Form Information -->
                                        <div class="col-md-6">
                                            <div class="info">
                                                <p>
                                                    <span class="info-title"><strong>Instructions:</strong> </span>
                                                    <span class="info-text">Use this form to create a new job/internship listing,
                                                        <strong><span class="required">*</span></strong> denotes a required
                                                        field.</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <p>
                                                        <strong>
                                                            <label for="jobTitle">Job Title: <strong><span class="required">*</span></strong></label>
                                                        </strong>
                                                    </p>
                                                    <p>
                                                        <input type="text" id="jobTitle" name="job_title" class="form-control" placeholder="Job Title" required>
                                                    </p>
                                                    <p><small id="jobTitleHelp" class="form-text text-muted">Enter a title for the
                                                            job.</small></p>
                                                </div>
                                                <div class="form-group">
                                                    <p>
                                                        <strong>
                                                            <label for="jobSummary">Job Summary: <strong><span class="required">*</span></strong></label>
                                                        </strong>
                                                    </p>
                                                    <p>
                                                        <textarea id="jobSummary" name="job_summary" class="form-control" rows="3" maxlength="200" placeholder="Job Summary" required></textarea>
                                                    </p>
                                                    <p><small id="jobSummaryHelp" class="form-text text-muted">Enter a summary for
                                                            the job, under 200 characters.</small></p>
                                                    <p>
                                                        <!-- counter for job summary -->
                                                    <div id="counter hint-text"><span id="jobSummaryCounter"><small id="summaryCounterText" class="form-text text-muted">200/200</small><small class="form-text text-muted">
                                                                characters remaining</small></span></div>
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <p>
                                                        <strong>
                                                            <label for="jobDescription">Job Description: <strong><span class="required">*</span></strong></label>
                                                        </strong>
                                                    </p>
                                                    <p>
                                                        <textarea id="jobDescription" name="job_description" class="form-control wysiwyg-editor" rows="10" placeholder="Job Description" required></textarea>
                                                    </p>
                                                    <p><small id="jobDescriptionHelp" class="form-text text-muted">Enter a
                                                            description for the job, can use HTML to style the text.</small></p>
                                                </div>
                                                <div class="form-group">
                                                    <p>
                                                        <strong>
                                                            <label for="jobType">Job Type: <strong><span class="required">*</span></strong></label>
                                                        </strong>
                                                    </p>
                                                    <p>
                                                        <select id="jobType" name="job_type" class="form-control app-forms" required>
                                                            <?php
                                                            //for each item in the position type list, display it as an option
                                                            foreach ($positionType_list as $positionType) {
                                                                echo '<option value="' . $positionType['value'] . '">' . $positionType['label'] . '</option>';
                                                            } ?>
                                                        </select>
                                                    </p>
                                                    <p><small id="jobTypeHelp" class="form-text text-muted">Select the type of job
                                                            listing.</small></p>
                                                </div>
                                                <div class="form-group">
                                                    <p>
                                                        <strong>
                                                            <label for="jobField">Job Field: <strong><span class="required">*</span></strong></label>
                                                        </strong>
                                                    </p>
                                                    <p>
                                                        <select id="jobField" name="job_field" class="form-control app-forms" required>
                                                            <?php
                                                            //for each job field, display it as an option
                                                            foreach ($jobFieldArray as $jobField) {
                                                                echo '<option value="' . $jobField['id'] . '">' . $jobField['name'] . '</option>';
                                                            } ?>
                                                        </select>
                                                    </p>
                                                    <p><small id="jobFieldHelp" class="form-text text-muted">Select the field of the
                                                            job.</small></p>
                                                </div>
                                                <div class="form-group">
                                                    <p>
                                                        <strong>
                                                            <label for="jobField">Job Skills:</label>
                                                        </strong>
                                                    </p>
                                                    <p>
                                                    <div class="input-group mb-3">
                                                        <input type="text" id="jobSkills" name="job_skills" class="form-control" placeholder="Job Skills">
                                                        <!-- button to add a new skill to list -->
                                                        <input type="button" id="addSkill" name="add_skill" class="form-control btn btn btn-outline-success btn-skill-btn btn-add" value="Add Skill">
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <!-- list of skills -->
                                                        <ul id="jobSkillsList" name="job_skills_list" class="form-control list-group job-skill-list">
                                                        </ul>
                                                        <!-- button to remove a skill from the list -->
                                                        <input type="button" id="removeSkill" name="remove_skill" class="form-control btn btn btn-outline-danger btn-skill-btn btn-remove" value="Remove Skill">
                                                        <!-- hidden field to store the list of skills as an array -->
                                                        <input type="hidden" id="jobSkillsArray" name="job_skills_array[]" hidden>
                                                    </div>
                                                    </p>
                                                    <p><small id="jobSkillsHelp" class="form-text text-muted">Enter a comma
                                                            separated list of skills for the job.</small></p>
                                                </div>
                                                <div class="form-group">
                                                    <p>
                                                        <strong>
                                                            <label for="jobField">Education Requirement: <strong><span class="required">*</span></strong></label>
                                                        </strong>
                                                    </p>
                                                    <p>
                                                        <select id="jobEducation" name="job_education" class="form-control app-forms" required>
                                                            <option value="0">None</option>
                                                            <?php
                                                            //include the degree level class
                                                            $degree = new Degree();
                                                            //get all education levels
                                                            $educationArray = $degree->getAllGrades();
                                                            //for each education level, display it as an option
                                                            foreach ($educationArray as $degreeLevel) {
                                                                echo '<option value="' . $degreeLevel['id'] . '">' . $degreeLevel['name'] . '</option>';
                                                            } ?>
                                                        </select>
                                                    </p>
                                                    <p><small id="jobEducationHelp" class="form-text text-muted">Select the
                                                            education requirement for the job.</small></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" card-footer">
                                        <button name="create_Button" type="submit" class="btn btn-primary">Save</button>
                                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=list'; ?>" class="btn btn-secondary">Cancel</a>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
    <?php }
        }
    } ?>
    <script type="text/javascript" async="true">
        //add an input event listener to the summary text area
        document.getElementById("jobSummary").addEventListener("input", function() {
            //call the character count function
            characterCount("jobSummary", "summaryCounterText");
        });

        //add an event listener for when the document is loaded
        document.addEventListener("DOMContentLoaded", function() {
            //call the character count function
            characterCount("jobSummary", "summaryCounterText");
        });
    </script>
<?php } else {
    //set the action to null
    $action = null;

    //set the error type
    $thisError = 'ROUTING_ERROR';

    //include the error message file
    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
} ?>
