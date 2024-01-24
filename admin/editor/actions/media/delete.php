<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//include the permissions class
$permissionsObject = new Permission();

//include the authenticator class
$auth = new Authenticator();

/*confirm user has a role with delete media permissions*/
//get the id of the delete media permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('DELETE MEDIA');

//boolean to track if the user has the delete media permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    die('Error: You do not have permission to perform this request.');
} else {
    //include the media class
    $media = new Media();

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the action from the url parameter
        $action = $_GET['action'];

        //if the action is delete, get the media id from the url parameter
        if ($action == 'delete') {
            $media_id = $_GET['id'];
        }

        //if the action is delete, check if the override parameter is set
        if ($action == 'delete') {
            if (isset($_GET['override'])) {
                $override = $_GET['override'];
            }
        }

        //boolean to track if the media can be deleted
        $canDelete = true;

        //boolean to track if we can override any errors and delete the media
        $override = false;

        //get the intvalue of the media id
        $media_id = intval($media_id);

        //get the media name
        $media_name = $media->getMediaFileName($media_id);

        //check if the media can be deleted, need to check if the media is associated with any other records
        $mediaUsageData = $media->getMediaUsage($media_id);

        //placeholders for the count of records associated with the media
        $mediaUsageDataBySchool = 0;
        $mediaUsageDataByEvent = 0;

        //if there are more than 0 records in the school subarray, the media cannot be deleted
        if (count($mediaUsageData['schools']) > 0) {
            $canDelete = false;
            //set the count of records associated with the media
            $mediaUsageDataBySchool = count($mediaUsageData['schools']);
        }

        //if there are more than 0 records in the event subarray, the media cannot be deleted
        if (count($mediaUsageData['events']) > 0) {
            $canDelete = false;
            //set the count of records associated with the media
            $mediaUsageDataByEvent = count($mediaUsageData['events']);
        }

        //if canDelete is true, delete the media
        if ($canDelete) {
            $mediaDeleted = $media->deleteMedia($media_id);
        } else {
            $mediaDeleted = false;
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
                                if ($mediaDeleted) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Media Deleted';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Media Not Deleted';
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
                                if ($mediaDeleted) {
                                    echo '<p>The media ' . $media_name . ' has been deleted.</p>';
                                } else {
                                    echo '<p>The media ' . $media_name . ' could not be deleted.</p>';
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
                                    echo '<p>The media ' . $media_name . ' cannot be deleted because it has associated records in the system.</p>';
                                    echo '<p>Please delete the media\'s event event and/or school records before attempting to delete the media.</p>';
                                    echo '<ul>';
                                    if ($mediaUsageDataBySchool > 0) {
                                        echo '<li>There are ' . strval($mediaUsageDataBySchool) . ' schools associated with the media</li>';
                                    }
                                    if ($mediaUsageDataByEvent > 0) {
                                        echo '<li>There are ' . strval($mediaUsageDataByEvent) . ' event records associated with the media</li>';
                                    }
                                    echo '</ul>';
                                }
                            }
                            ?>
                    </div>
                </div>
                <!-- present option to delete all associated records if necessary -->
                <div class="row">
                    <div class="col-md-12">
                        <?php
                            if ($action == 'delete') {
                                if (!$canDelete) {
                                    echo '<p>If you would like to delete all associated records, click the button below.</p>';
                                    echo '<form action="' . APP_URL . '/admin/dashboard.php?view=media&media=single&action=delete&id=' . strval($media_id) . '&override=true" method="post">';
                                    echo '<input type="hidden" name="deleteAll" value="true">';
                                    echo '<button type="submit" class="btn btn-danger">Delete All Associated Records</button>';
                                    echo '</form>';
                                }
                            }
                            ?>
                    </div>
                </div>
                <div class="row">
                    <!-- show back buttons -->
                    <div class="col-md-12">
                        <?php
                            if ($action == 'delete') {
                                if ($mediaDeleted) {
                                    echo '<a href="' . APP_URL . '/admin/dashboard.php?view=media&media=list" class="btn btn-primary">Return to Media List</a>';
                                } else {
                                    echo '<a href="' . APP_URL . '/admin/dashboard.php?view=media&media=list" class="btn btn-primary">Return to Media List</a>';
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
