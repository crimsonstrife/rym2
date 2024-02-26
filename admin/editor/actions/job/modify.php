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

/*confirm user has a role with update school permissions*/
//get the id of the update school permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('UPDATE SCHOOL');

//boolean to track if the user has the update school permission
$hasPermission = $auth->checkUserPermission(intval($session->get('user_id')), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
} else {

    //job class
    $job = new Job();

    //user class
    $user = new User();

    //get the action from the url parameter
    $action = $_GET['action'];

    //if the action is edit, get the job id from the url parameter
    if ($action == 'edit') {
        $job_id = $_GET['id'];
    }

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the job title from the form
        if (isset($_POST["job_title"])) {
            $job_title = trim($_POST["job_title"]);
            //prepare the job title
            $job_title = prepareData($job_title);
        }

        //placeholder for job summary and description
        $job_summary = '';
        $job_description = '';

        //get the job summary from the form
        if (isset($_POST["job_summary"])) {
            $job_summary = trim($_POST["job_summary"]);
            //prepare the job summary
            $job_summary = prepareData($job_summary);
        }

        //get the job description from the form
        if (isset($_POST["job_description"])) {
            $job_description = $_POST["job_description"];
        }

        //create an array to hold the job description and job summary
        $job_info = array(
            'job_summary' => $job_summary,
            'job_description' => $job_description
        );

        //get the job skills from the form
        if (isset($_POST["job_skills_array"])) {
            $job_skillItems = $_POST["job_skills_array"];
        }

        //create an array to hold the job skills
        $job_skills = array();

        //if the job skill items are set, explode the string into an array
        if (isset($job_skillItems)) {
            $jobSkillsString = $job_skillItems[0];

            //explode the string into an array
            $job_skills = explode(',', $jobSkillsString);
        }

        //get the job type from the form
        if (isset($_POST["job_type"])) {
            $job_type = trim($_POST["job_type"]);
            //prepare the job type
            $job_type = prepareData($job_type);
        }
        //get the job field from the form
        if (isset($_POST["job_field"])) {
            $job_field = trim($_POST["job_field"]);
            //prepare the job field
            $job_field = prepareData($job_field);
        }

        //get the job education from the form
        if (isset($_POST["job_education"])) {
            $job_education = trim($_POST["job_education"]);
            //prepare the job education
            $job_education = prepareData($job_education);
        }

        //if the action is edit, update the job
        if ($action == 'edit') {
            //get current user ID
            $user_id = intval($session->get('user_id'));

            //update the job
            $jobUpdated = $job->updateJob($job_id, $job_title, $job_info, $job_type, intval($job_field), intval($job_education), $job_skills, $user_id);
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
                            if ($action == 'edit') {
                                if ($jobUpdated) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Job Updated';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Job Not Updated';
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
                            if ($action == 'edit') {
                                if ($jobUpdated) {
                                    echo '<p>The job: ' . $job_title . ' has been updated.</p>';
                                } else {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo '<p>The job: ' . $job_title . ' could not be updated.</p>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <!-- show error messages -->
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($action == 'edit') {
                                if (!$canCreate) {
                                    echo '<p>The job: ' . $job_title . ' could not be updated due to an error.</p>';
                                } else {
                                    echo '<p>The job: ' . $job_title . ' has been updated.</p>';
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
                                if ($action == 'edit') {
                                    if ($jobUpdated) {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=jobs&job=list" class="btn btn-primary">Return to Job List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=jobs&job=single&id=' . $job_id . '" class="btn btn-secondary">Go to Job</a></span>';
                                    } else {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=jobs&job=list" class="btn btn-primary">Return to Job List</a></span>';
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
