<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//include the permissions class
$permissionsObject = new Permission();

//include the auth class
$auth = new Authenticator();

//subject class
$subject = new AreaOfInterest();

//user class
$user = new User();

//get the action from the url parameter
$action = $_GET['action'];

//if the action is edit, get the subject id from the url parameter
if ($action == 'edit') {
    $subject_id = $_GET['id'];
}

//if the action is edit, show the subject edit form
if ($action == 'edit') {

    //get the update subject permission id
    $updateSubjectPermissionID = $permissionsObject->getPermissionIdByName('UPDATE SUBJECT');

    //boolean to check if the user has the update subject permission
    $hasUpdateSubjectPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updateSubjectPermissionID);

    //if the user does not have the update subject permission, prevent access to the editor
    if (!$hasUpdateSubjectPermission) {
        //die with an error message
        die('Error: You do not have permission to access this content, contact the Administrator.');
    } else { ?>
        <div class="container-fluid px-4">
            <h1 class="mt-4"><?php echo $subject->getSubjectName($subject_id); ?></h1>
            <div class="row">
                <div class="card mb-4">
                    <!-- Edit Form -->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&subject=' . $_GET['subject'] . '&action=' . $_GET['action'] . '&id=' . $_GET['id']; ?>" method="post" enctype="multipart/form-data">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fa-solid fa-calendar-day"></i>
                                Edit Subject
                            </div>
                            <div class="card-buttons">
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=subjects&subject=list'; ?>" class="btn btn-primary btn-sm">Back to Subjects</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><label for="subjectName">Subject/Field Name:</label></strong></p>
                                    <p><input type="text" id="subjectName" name="subject_name" class="form-control" value="<?php echo $subject->getSubjectName($subject_id); ?>" placeholder="<?php echo $subject->getSubjectName($subject_id); ?>" required></p>
                                </div>
                            </div>
                        </div>
                        <div class=" card-footer">
                            <button name="create_Button" type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=subjects&subject=list'; ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php }
} else if ($action == 'create') { //else if the action is create, show the subject creation form
    //get the create subject permission id
    $createSubjectPermissionID = $permissionsObject->getPermissionIdByName('CREATE SUBJECT');

    //boolean to check if the user has the create subject permission
    $hasCreateSubjectPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createSubjectPermissionID);

    //if the user does not have the create subject permission, prevent access to the editor
    if (!$hasCreateSubjectPermission) {
        //die with an error message
        die('Error: You do not have permission to access this content, contact the Administrator.');
    } else {
    ?>
        <div class="container-fluid px-4">
            <h1 class="mt-4">New Subject</h1>
            <div class="row">
                <div class="card mb-4">
                    <!-- Create Form -->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&subject=' . $_GET['subject'] . '&action=' . $_GET['action']; ?>" method="post" enctype="multipart/form-data">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fa-solid fa-calendar-day"></i>
                                Create Subject
                            </div>
                            <div class="card-buttons">
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=subjects&subject=list'; ?>" class="btn btn-primary btn-sm">Back to Subjects</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><label for="subjectName">Subject/Field Name:</label></strong></p>
                                    <p><input type="text" id="subjectName" name="subject_name" class="form-control" placeholder="example: Accounting" required></p>
                                </div>
                            </div>
                        </div>
                        <div class=" card-footer">
                            <button name="create_Button" type="submit" class="btn btn-primary">Save</button>
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=subjects&subject=list'; ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php
    }
} ?>
