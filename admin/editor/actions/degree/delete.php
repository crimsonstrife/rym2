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

//include the session class
$session = new Session();

/*confirm user has a role with delete degree permissions*/
//get the id of the delete degree permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('DELETE DEGREE');

//boolean to track if the user has the delete degree permission
$hasPermission = $auth->checkUserPermission(intval($session->get('user_id')), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
} else {
    //degree class
    $degree = new Degree();

    //student class
    $student = new Student();

    //student education class
    $studentEducation = new StudentEducation();

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
        $studentsWithDegree = $studentEducation->getStudentsByGrade($degree_id);

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
        <h1 class="mt-4"><?php echo $degree_name; ?></h1>
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
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- show completion message -->
                        <div class="col-md-12">
                            <?php
                            if ($action == 'delete') {
                                if ($degreeDeleted) {
                                    echo '<p>The degree: ' . $degree_name . ' has been deleted.</p>';
                                } else {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo '<p>The degree: ' . $degree_name . ' could not be deleted.</p>';
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
                                if (!$canDelete) {
                                    echo '<p>The degree: ' . $degree_name . ' cannot be deleted because they have associated records in the system.</p>';
                                    echo '<p>Please delete the degree\'s associated student records or re-associated them to other degrees before attempting to delete this one.</p>';
                                    echo '<ul>';
                                    if (count($studentsWithDegree) > 0) {
                                        echo '<li>There are ' . strval(count($studentsWithDegree)) . ' students associated with the degree.</li>';
                                    }
                                    echo '</ul>';
                                } else if ($canDelete && !$degreeDeleted) {
                                    echo '<p>The degree: ' . $degree_name . ' could not be deleted, due to an unknown error.</p>';
                                } else {
                                    echo '<p>All associated records for the degree: ' . $degree_name . ' have been deleted.</p>';
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
                                    if ($degreeDeleted) {
                                        echo '<a href="' . APP_URL . '/admin/dashboard.php?view=degrees&degree=list" class="btn btn-primary">Return to Degree List</a>';
                                    } else {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=degrees&degree=list" class="btn btn-primary">Return to Degree List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=degrees&degree=single&id=' . $degree_id . '" class="btn btn-secondary">Return to Degree</a></span>';
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
