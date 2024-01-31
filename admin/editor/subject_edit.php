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

//subject class
$subject = new AreaOfInterest();

//user class
$user = new User();

//check that action is set in the URL parameters
if (isset($_GET['action'])) {
    //get the action from the URL parameters
    $action = $_GET['action'];

    //if the action is edit, show the subject edit form
    if ($action == 'edit') {

        //get the update subject permission id
        $updateSubjectPermissionID = $permissionsObject->getPermissionIdByName('UPDATE SUBJECT');

        //boolean to check if the user has the update subject permission
        $hasUpdateSubjectPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updateSubjectPermissionID);

        //if the user does not have the update subject permission, prevent access to the editor
        if (!$hasUpdateSubjectPermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else {

            if (isset($_GET['id'])) {
                //get the subject id from the url parameter
                $subject_id = $_GET['id'];
            } else {
                //set the subject id to null
                $subject_id = null;
            }

            //confirm the id exists
            if (empty($subject_id) || $subject_id == null) {
                //set the error type
                $thisError = 'INVALID_REQUEST_ERROR';

                //include the error message file
                include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
            } else {
                //try to get the subject information
                $object = $subject->getSubject(intval($subject_id));

                //check if the subject is empty
                if (empty($object)) {
                    //set the error type
                    $thisError = 'NOT_FOUND';

                    //include the error message file
                    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
                }
            }

            //if not empty, display the school information
            if (!empty($object)) { ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $subject->getSubjectName($subject_id); ?></h1>
    <div class="row">
        <div class="card mb-4">
            <!-- Edit Form -->
            <form
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&subject=' . $_GET['subject'] . '&action=' . $_GET['action'] . '&id=' . $_GET['id']; ?>"
                method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-book"></i>
                        Edit Subject
                    </div>
                    <div class="card-buttons">
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=subjects&subject=list'; ?>"
                            class="btn btn-secondary">Back to Subjects</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Form Information -->
                        <div class="col-md-6">
                            <div class="info">
                                <p>
                                    <span class="info-title"><strong>Instructions:</strong> </span>
                                    <span class="info-text">Use this form to edit the subject name, <strong><span
                                                class="required">*</span></strong> denotes a required field.</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <p><strong><label for="subjectName">Subject/Field Name: <strong><span
                                                    class="required">*</span></strong></label></strong></p>
                                <p><input type="text" id="subjectName" name="subject_name" class="form-control"
                                        value="<?php echo $subject->getSubjectName($subject_id); ?>"
                                        placeholder="<?php echo $subject->getSubjectName($subject_id); ?>" required></p>
                                <p><small id="subjectNameHelp" class="form-text text-muted">Enter a unique name for the
                                        subject.</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" card-footer">
                    <button name="create_Button" type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=subjects&subject=list'; ?>"
                        class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php }
        }
    } else if ($action == 'create') { //else if the action is create, show the subject creation form
        //get the create subject permission id
        $createSubjectPermissionID = $permissionsObject->getPermissionIdByName('CREATE SUBJECT');

        //boolean to check if the user has the create subject permission
        $hasCreateSubjectPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createSubjectPermissionID);

        //if the user does not have the create subject permission, prevent access to the editor
        if (!$hasCreateSubjectPermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else {
            ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">New Subject</h1>
    <div class="row">
        <div class="card mb-4">
            <!-- Create Form -->
            <form
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&subject=' . $_GET['subject'] . '&action=' . $_GET['action']; ?>"
                method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-book"></i>
                        Create Subject
                    </div>
                    <div class="card-buttons">
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=subjects&subject=list'; ?>"
                            class="btn btn-secondary">Back to Subjects</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Form Information -->
                        <div class="col-md-6">
                            <div class="info">
                                <p>
                                    <span class="info-title"><strong>Instructions:</strong> </span>
                                    <span class="info-text">Use this form to add a new subject, <strong><span
                                                class="required">*</span></strong> denotes a required field.</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <p><strong><label for="subjectName">Subject/Field Name: <strong><span
                                                    class="required">*</span></strong></label></strong></p>
                                <p><input type="text" id="subjectName" name="subject_name" class="form-control"
                                        placeholder="example: Accounting" required></p>
                                <p><small id="subjectNameHelp" class="form-text text-muted">Enter a unique name for the
                                        subject.</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" card-footer">
                    <button name="create_Button" type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=subjects&subject=list'; ?>"
                        class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
        }
    }
} else {
    //set the action to null
    $action = null;

    //set the error type
    $thisError = 'ROUTING_ERROR';

    //include the error message file
    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
} ?>
