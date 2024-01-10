<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//include the permissions class
$permissionsObject = new Permission();

//include the authenticator class
$auth = new Authenticator();

/*confirm user has a role with delete degree permissions*/
//get the id of the delete degree permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('DELETE DEGREE');

//boolean to track if the user has the delete degree permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    die('Error: You do not have permission to perform this request.');
} else {
    //degree class
    $degree = new Degree();

    //student class
    $student = new Student();

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the action from the url parameter
        $action = $_GET['action'];

        //if the action is delete, get the degree id from the url parameter
        if ($action == 'delete') {
            $degree_id = $_GET['id'];
        }

        //get the intvalue of the degree id
        $degree_id = intval($degree_id);

        //get the degree name
        $degree_name = $degree->getGradeNameById($degree_id);

        //boolean to track if the degree can be deleted
        $canDelete = true;

        //check if there are any students associated with the degree in the student table
        $studentsWithDegree = $student->getStudentsByGrade($degree_id);

        //if there are more than 0 records in the array, the degree cannot be deleted so set the canDelete boolean to false
        if (count($studentsWithDegree) > 0) {
            $canDelete = false;
        }

        //if the canDelete boolean is true, delete the degree
        if ($canDelete) {
            $degreeDeleted = $degree->deleteGrade($degree_id);
        } else {
            $degreeDeleted = false;
        }
    }
?>
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
                                if ($degreeDeleted) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Degree Deleted';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Degree Not Deleted';
                                }
                            }
                            ?>
                    </div>
                    <div>
                        <?php
                            if ($action == 'delete') {
                                if ($canDelete && !$degreeDeleted) {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo 'The degree: ' . $degree_name . ', could not be deleted because of an unknown error.';
                                } else if (!$canDelete && !$degreeDeleted) {
                                    echo 'The degree: ' . $degree_name . ', could not be deleted because of an error: ';
                                    echo '<ul>';
                                    if (count($studentsWithDegree) > 0) {
                                        echo '<li>There are ' . strval(count($studentsWithDegree)) . ' students associated with the degree</li>';
                                    }
                                    echo '</ul>';
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
