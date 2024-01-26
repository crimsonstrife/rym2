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

//include the auth class
$auth = new Authenticator();

//job class
$job = new Job();

//user class
$user = new User();

/*confirm user has a role with create job permissions*/
//get the id of the create job permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('CREATE JOB');

//boolean to track if the user has the create job permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
} else {

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

        //if the action is create, create the job
        if ($action == 'create') {
            //get current user ID
            $user_id = intval($_SESSION['user_id']);

            //create the job
            $jobCreated = $job->addJob($job_title, $job_info, $job_type, intval($job_field), $job_skills, $user_id);
        }
    } ?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-check"></i>
                        <?php
                        if ($action == 'create') {
                            if ($jobCreated) {
                                echo 'Job Created';
                            } else {
                                echo 'Error: Job Not Created';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
