<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//school class
$school = new School();

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //get the action from the url parameter
    $action = $_GET['action'];

    //if the action is delete, get the school id from the url parameter
    if ($action == 'delete') {
        $school_id = $_GET['id'];
    }

    //get the intvalue of the school id
    $school_id = intval($school_id);

    //delete the school
    $school->deleteSchool($school_id);
    //redirect to the schools list
    header("location: " . APP_URL . "/admin/dashboard.php?view=schools&school=list");
    exit();
}
