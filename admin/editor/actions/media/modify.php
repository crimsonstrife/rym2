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

//include the session class
$session = new Session();

/*confirm user has a role with update media permissions*/
//get the id of the update media permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('UPDATE MEDIA');

//boolean to track if the user has the update media permission
$hasPermission = $auth->checkUserPermission(intval($session->get('user_id')), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
} else {

    //include the media class
    $media = new Media();

    //placeholder for the media id
    $media_id = null;

    //placeholder for the media name
    $media_name = null;

    //placeholder for if a media file exists
    $mediaExists = null;

    //placeholder for the current media name
    $currentMediaName = null;

    //boolean to track if the media was updated
    $mediaUpdated = false;

    //boolean to track if the file name is being changed
    $fileNameChanged = false;

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the action from the url parameter
        $action = $_GET['action'];

        //if the action is edit, get the media id from the url parameter
        if ($action == 'edit') {
            $media_id = $_GET['id'];
        }

        //if the action is edit, get the mediaFile from the form
        if ($action == 'edit') {
            //make sure the file exists
            if (isset($_FILES)) {
                //get the mediaFile from the form
                $mediaFile = $_FILES['media_file'];
            }

            //if the file name is set, get the name from the form
            if (isset($_POST['media_name'])) {
                $media_name = trim(basename($_POST["media_name"]));
                //prepare the media name
                $media_name = prepareData($media_name);

                //sanitize the media name
                $media_name = htmlspecialchars($media_name);
            }
        }

        //if the media name is set, check if the name is being changed
        if (isset($media_name)) {
            //get the current media name
            $currentMediaName = $media->getMediaFileName($media_id);

            //add the file extension to the media name
            $currentMediaName = $currentMediaName . '.' . $media->getMediaFileType($media_id);

            //if the current media name is different from the new media name, set the boolean to true
            if ($currentMediaName != $media_name) {
                $fileNameChanged = true;
            }
        }

        //if the mediaFile is not set, see if the media name is being changed
        if (!isset($mediaFile)) {
            //see if the media name was set
            if (!isset($media_name)) {
                //set the error type
                $thisError = 'INVALID_REQUEST_ERROR';

                //include the error message file
                include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
            } else {
                //if the media name is being changed, update the media name
                $mediaUpdated = $fileNameChanged ? $media->renameMedia($media_id, htmlspecialchars(prepareData($media_name)), intval($session->get('user_id'))) : true;
            }
        } else {
            //get the media name from the file name
            $mediaFile_name = $mediaFile['name'];

            //see if the media name already exists
            $mediaExists = $media->getMediaIDByFileName($mediaFile_name);

            //check if the file name is being changed
            if ($fileNameChanged) {
                //compare the media file name to the new media name
                if ($mediaFile_name != $media_name) {
                    //try to upload the media
                    $uploaded = $media->uploadMedia($mediaFile, intval($session->get('user_id')));

                    //if the media was uploaded, set the media id
                    if (isset($uploaded) && ($uploaded != null || $uploaded != 0)) {
                        $media_id = $uploaded;
                    }

                    //if the media id is set, update the media name
                    if (isset($media_id) && ($media_id != null || $media_id != 0)) {
                        $mediaUpdated = $media->renameMedia($media_id, htmlspecialchars(prepareData($media_name)), intval($session->get('user_id')));
                    }
                } else {
                    //replace the media file
                    $mediaUpdated = $media->updateMediaFile($media_id, $mediaFile, intval($session->get('user_id')));
                }
            } else {
                //replace the media file
                $mediaUpdated = $media->updateMediaFile($media_id, $mediaFile, intval($session->get('user_id')));
            }
        }
    }
?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?php echo htmlspecialchars($media_name); ?></h1>
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <div>
                            <?php
                            if ($action == 'edit') {
                                if ($mediaUpdated) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Media Updated';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Media Not Updated';
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
                            if ($action == 'edit') {
                                if ($mediaUpdated) {
                                    echo '<p>The media: ' . htmlspecialchars($media_name) . ' has been updated.</p>';
                                } else {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo '<p>The media: ' . htmlspecialchars($media_name) . ' could not be updated.</p>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <!-- show error messages -->
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($action == 'edit') {
                                if ($mediaExists && !$mediaUpdated) {
                                    echo '<p>The media: ' . htmlspecialchars($media_name) . ' cannot be updated because a media with the same name already exists.</p>';
                                    echo '<p>Please enter a different media name and try again.</p>';
                                } else if (!$mediaExists && !$mediaUpdated) {
                                    echo '<p>The media: ' . htmlspecialchars($media_name) . ' could not be updated due to an unknown error.</p>';
                                } else {
                                    echo '<p>The media: ' . htmlspecialchars($media_name) . ' has been updated.</p>';
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
                                if ($action == 'edit') {
                                    if ($mediaUpdated) {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=media&media=list" class="btn btn-primary">Return to Media List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=media&media=single&id=' . htmlspecialchars($media_id) . '" class="btn btn-secondary">Go to Media</a></span>';
                                    } else {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=media&media=list" class="btn btn-primary">Return to Media List</a></span>';
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
