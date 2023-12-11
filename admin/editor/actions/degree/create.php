<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//degree class
$degree = new Degree();

//user class
$user = new User();

//get the action from the url parameter
$action = $_GET['action'];

//if the action is edit, get the degree id from the url parameter
if ($action == 'edit') {
    $degree_id = $_GET['id'];
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //get the degree name from the form
    if (isset($_POST["degree_name"])) {
        $degree_name = trim($_POST["degree_name"]);
        //prepare the degree name
        $degree_name = prepareData($degree_name);
    }

    //if the action is create, create the degree
    if ($action == 'create') {
        //get current user ID
        $user_id = intval($_SESSION['user_id']);

        //create the degree
        $degreeCreated = $degree->addGrade($degree_name, $user_id);
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
                        if ($degreeCreated) {
                            echo 'Degree Created';
                        } else {
                            echo 'Error: Degree Not Created';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
