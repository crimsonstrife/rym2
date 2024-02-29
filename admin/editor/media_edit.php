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

//include the media class
$media = new Media();

//check that action is set in the URL parameters
if (isset($_GET['action'])) {
    //get the action from the URL parameters
    $action = $_GET['action'];

    //if the action is edit, show the media edit form
    if ($action == 'edit') {

        //get the update media permission id
        $updateMediaPermissionID = $permissionsObject->getPermissionIdByName('UPDATE MEDIA');

        //boolean to check if the user has the update media permission
        $hasUpdateMediaPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updateMediaPermissionID);

        //if the user does not have the update media permission, prevent access to the editor
        if (!$hasUpdateMediaPermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else {

            if (isset($_GET['id'])) {
                //get the media id from the url parameter
                $media_id = $_GET['id'];
            } else {
                //set the media id to null
                $media_id = null;
            }

            //confirm the id exists
            if (empty($media_id) || $media_id == null) {
                //set the error type
                $thisError = 'INVALID_REQUEST_ERROR';

                //include the error message file
                include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
            } else {
                //try to get the media information
                $object = $media->getMediaById(intval($media_id));

                //check if the media is empty
                if (empty($object)) {
                    //set the error type
                    $thisError = 'NOT_FOUND';

                    //include the error message file
                    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
                }
            }

            //if not empty, display the event information
            if (!empty($object)) { ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $media->getMediaFileName(intval($media_id)); ?></h1>
    <div class="row">
        <div class="card mb-4">
            <!-- Edit Form -->
            <form
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . htmlspecialchars($_GET['view']) . '&media=' . htmlspecialchars($_GET['media']) . '&action=' . htmlspecialchars($_GET['action']) . '&id=' . htmlspecialchars($_GET['id']); ?>"
                method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-calendar-day"></i>
                        Edit Media
                    </div>
                    <div class="card-buttons">
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=media&media=list'; ?>"
                            class="btn btn-secondary">Back to Media</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Form Information -->
                        <div class="col-md-6">
                            <div class="info">
                                <p>
                                    <span class="info-title"><strong>Instructions:</strong> </span>
                                    <span class="info-text">Use this form to edit the media, <strong><span
                                                class="required">*</span></strong> denotes a required field.</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Update Media File Name -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <p><strong><label for="mediaName">Media File Name: <strong><span
                                                    class="required">*</span></strong></label></strong></p>
                                <p>
                                <div class="input-group">
                                    <?php
                                                    //get the media file name without the extension
                                                    $mediaFileName = $media->getMediaFileName(intval($media_id));
                                                    $mediaFileNameComponents = explode('.', $mediaFileName);
                                                    $mediaFileName = $mediaFileNameComponents[0];
                                                    ?>
                                    <input type="text" id="mediaName" name="media_name" class="form-control"
                                        value="<?php echo $mediaFileName; ?>"
                                        placeholder="<?php echo $mediaFileName; ?>" required>
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <?php echo '.' . $media->getMediaFileType(intval($media_id)); ?></div>
                                    </div>
                                </div>
                                </p>
                                <p><small id="mediaNameHelp" class="form-text text-muted">Enter a unique name for the
                                        media, without the extension.</small></p>
                                <div class="alert alert-warning" role="alert"><span class="note-icon"><i
                                            class="fa-solid fa-circle-exclamation"></i></span>
                                    <span class="note-text">Changing the media name will result in renaming the file on
                                        the server.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Update Media File -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <p><strong><label for="mediaFile">New Media File:</label></strong></p>
                                <p><input type="file" id="mediaFile" name="media_file" class="form-control"
                                        value="<?php echo $media->getMediaFileName(intval($media_id)); ?>"
                                        placeholder="<?php echo $media->getMediaFileName(intval($media_id)); ?>">
                                </p>
                                <p><small id="mediaFileHelp" class="form-text text-muted">Select a new media file to
                                        replace the current file.</small></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" card-footer">
                    <button name="create_Button" type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=media&media=list'; ?>"
                        class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php }
        }
    } else if ($action == 'create') { //else if the action is create, show the media creation form
        //get the create media permission id
        $createMediaPermissionID = $permissionsObject->getPermissionIdByName('CREATE MEDIA');

        //boolean to check if the user has the create media permission
        $hasCreateMediaPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createMediaPermissionID);

        //if the user does not have the create media permission, prevent access to the editor
        if (!$hasCreateMediaPermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else {
            ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Create Media</h1>
    <div class="row">
        <div class="card mb-4">
            <!-- New Form -->
            <form
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . htmlspecialchars($_GET['view']) . '&media=' . htmlspecialchars($_GET['media']) . '&action=' . htmlspecialchars($_GET['action']); ?>"
                method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-calendar-day"></i>
                        New Media
                    </div>
                    <div class="card-buttons">
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=media&media=list'; ?>"
                            class="btn btn-secondary">Back to Media</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Form Information -->
                        <div class="col-md-6">
                            <div class="info">
                                <p>
                                    <span class="info-title"><strong>Instructions:</strong> </span>
                                    <span class="info-text">Use this form to create a new media object, <strong><span
                                                class="required">*</span></strong> denotes a required field.</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Media File Name -->
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><label for="mediaName">Media File Name:</label></strong></p>
                            <p><input type="text" id="mediaName" name="media_name" class="form-control" value=""
                                    placeholder="TO BE GENERATED FROM FILENAME" disabled>
                            </p>
                            <p><small id="mediaNameHelp" class="form-text text-muted">The media file name will be taken
                                    from the uploaded file.</small></p>
                        </div>
                    </div>
                    <!-- Upload Media File -->
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><label for="mediaFile">Media File: <strong><span
                                                class="required">*</span></strong></label></strong></p>
                            <p><input type="file" id="mediaFile" name="media_file" class="form-control" value=""
                                    placeholder="" required>
                            </p>
                            <p><small id="mediaFileHelp" class="form-text text-muted">Select a media file to
                                    upload.</small></p>
                        </div>
                    </div>
                </div>
                <div class=" card-footer">
                    <button name="create_Button" type="submit" class="btn btn-primary">Upload & Save</button>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=media&media=list'; ?>"
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
