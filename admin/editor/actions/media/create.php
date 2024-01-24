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


/*confirm user has a role with create media permissions*/
//get the id of the create media permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('CREATE MEDIA');

//boolean to track if the user has the create media permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

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

    //boolean to track if the media was created
    $mediaCreated = false;

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the action from the url parameter
        $action = $_GET['action'];

        //if the action is create, get the mediaFile from the form
        if ($action == 'create') {
            //make sure the file exists
            if (isset($_FILES)) {
                //get the mediaFile from the form
                $mediaFile = $_FILES['media_file'];
            }
        }

        //if the mediaFile is not set, set the error type
        if (!isset($mediaFile)) {
            //set the error type
            $thisError = 'INVALID_REQUEST_ERROR';

            //include the error message file
            include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
        } else {
            //get the media name from the file name
            $media_name = $mediaFile['name'];

            //see if the media name already exists
            $mediaExists = $media->getMediaIDByFileName($media_name);

            //if the media exists, set the error type
            if ($mediaExists != Null && $mediaExists > 0) {
                //set the error type
                $thisError = 'FILE_EXISTS_ERROR';

                //include the error message file
                include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
            } else {
                //try to upload the media
                $uploaded = $media->uploadMedia($mediaFile, intval($_SESSION['user_id']));

                //if the media was uploaded, set the media id
                if (isset($uploaded) && ($uploaded != null || $uploaded != 0)) {
                    $media_id = $uploaded;
                }
            }
        }
    }

    //if the media id is set, set the media created boolean to true
    if (!isset($media_id) && ($media_id = NULL || $media_id == 0)) {
        $mediaCreated = false;
    } else {
        $mediaCreated = true;
?>
        <!-- Completion page content -->
        <div class="container-fluid px-4">
            <div class="row">
                <div class="card mb-4">
                    <!-- show completion message -->
                    <div class="card-header">
                        <div class="card-title">
                            <div>
                                <i class="fa-solid fa-check"></i>
                                <?php
                                if ($action == 'create') {
                                    if ($mediaCreated) {
                                        echo 'Media Created';
                                    } else {
                                        echo 'Error: Media Not Created';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php }
} ?>
