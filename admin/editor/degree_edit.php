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

//degree class
$degree = new Degree();

//user class
$user = new User();

//check that action is set in the URL parameters
if (isset($_GET['action'])) {
    //get the action from the URL parameters
    $action = $_GET['action'];

    //if the action is edit, show the degree edit form
    if ($action == 'edit') {
        //get the update degree permission id
        $updateDegreePermissionID = $permissionsObject->getPermissionIdByName('UPDATE DEGREE');

        //boolean to check if the user has the update degree permission
        $hasUpdateDegreePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updateDegreePermissionID);

        //if the user does not have the update degree permission, prevent access to the editor
        if (!$hasUpdateDegreePermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else {

            if (isset($_GET['id'])) {
                //get the degree id from the url parameter
                $degree_id = $_GET['id'];
            } else {
                //set the degree id to null
                $degree_id = null;
            }

            //confirm the id exists
            if (empty($degree_id) || $degree_id == null) {
                //set the error type
                $thisError = 'INVALID_REQUEST_ERROR';

                //include the error message file
                include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
            } else {
                //try to get the degree information
                $object = $degree->getGrade(intval($degree_id));

                //check if the degree is empty
                if (empty($object)) {
                    //set the error type
                    $thisError = 'NOT_FOUND';

                    //include the error message file
                    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
                }
            }

            //if not empty, display the event information
            if (!empty($object)) {
?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $degree->getGradeNameById($degree_id); ?></h1>
    <div class="row">
        <div class="card mb-4">
            <!-- Edit Form -->
            <form
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&degree=' . $_GET['degree'] . '&action=' . $_GET['action'] . '&id=' . $_GET['id']; ?>"
                method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-graduation-cap"></i>
                        Edit Degree
                    </div>
                    <div class="card-buttons">
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=degrees&degree=list'; ?>"
                            class="btn btn-secondary">Back to Degrees</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Form Information -->
                        <div class="col-md-6">
                            <div class="info">
                                <p>
                                    <span class="info-title"><strong>Instructions:</strong> </span>
                                    <span class="info-text">Use this form to edit the degree name, <strong><span
                                                class="required">*</span></strong> denotes a required field.</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <p><strong><label for="degreeName">Degree/Grade Name: <strong><span
                                                    class="required">*</span></strong></label></strong></label></strong>
                                </p>
                                <p><input type="text" id="degreeName" name="degree_name" class="form-control"
                                        value="<?php echo $degree->getGradeNameById($degree_id); ?>"
                                        placeholder="<?php echo $degree->getGradeNameById($degree_id); ?>" required></p>
                                <p><small id="degreeNameHelp" class="form-text text-muted">Enter a unique name for the
                                        degree.</small></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" card-footer">
                    <button name="create_Button" type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=degrees&degree=list'; ?>"
                        class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php }
        }
    } else if ($action == 'create') { //else if the action is create, show the degree creation form
        //get the create degree permission id
        $createDegreePermissionID = $permissionsObject->getPermissionIdByName('CREATE DEGREE');

        //boolean to check if the user has the create degree permission
        $hasCreateDegreePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createDegreePermissionID);

        //if the user does not have the create degree permission, prevent access to the editor
        if (!$hasCreateDegreePermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else {
            ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">New Degree</h1>
    <div class="row">
        <div class="card mb-4">
            <!-- Create Form -->
            <form
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&degree=' . $_GET['degree'] . '&action=' . $_GET['action']; ?>"
                method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-calendar-day"></i>
                        Create Degree
                    </div>
                    <div class="card-buttons">
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=degrees&degree=list'; ?>"
                            class="btn btn-secondary">Back to Degrees</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Form Information -->
                        <div class="col-md-6">
                            <div class="info">
                                <p>
                                    <span class="info-title"><strong>Instructions:</strong> </span>
                                    <span class="info-text">Use this form to add a new degree, <strong><span
                                                class="required">*</span></strong> denotes a required field.</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <p><strong><label for="degreeName">Degree/Grade Name: <strong><span
                                                    class="required">*</span></strong></label></strong></label></strong>
                                </p>
                                <p><input type="text" id="degreeName" name="degree_name" class="form-control"
                                        placeholder="example: AA - Associates of Arts" required></p>
                                <p><small id="degreeNameHelp" class="form-text text-muted">Enter a unique name for the
                                        degree.</small></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" card-footer">
                    <button name="create_Button" type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=degrees&degree=list'; ?>"
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
