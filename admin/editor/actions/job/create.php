<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

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
    //get the job description from the form
    if (isset($_POST["job_description"])) {
        $job_description = trim($_POST["job_description"]);
        //prepare the job description
        $job_description = prepareData($job_description);
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
        $jobCreated = $job->addJob($job_title, $job_description, $job_type, intval($job_field), $user_id);
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
