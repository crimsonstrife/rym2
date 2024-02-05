<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
}

//include the permissions class
$permissionsObject = new Permission();

//include the auth class
$auth = new Authenticator();

//include the media class
$media = new Media();

/*confirm user has a role with read media permissions*/
//get the id of the read media permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('READ MEDIA');

//boolean to track if the user has the read media permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
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
        include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
    } else {
        //get the media data by id
        $mediaData = $media->getMediaByID(intval($media_id));

        //check if the media is empty
        if (empty($mediaData)) {
            //set the error type
            $thisError = 'NOT_FOUND';

            //include the error message file
            include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
        }
    }

    //if not empty, display the event information
    if (!empty($mediaData)) {
?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $media->getMediaFileName(intval($media_id)); ?></h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-photo-film"></i>
                    Media Details
                </div>
                <div class="card-buttons">
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=media&media=list'; ?>"
                        class="btn btn-secondary">Back to Media</a>
                    <?php /*confirm user has a role with update media permissions*/
                            //get the update media permission id
                            $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE MEDIA');

                            //boolean to check if the user has the update media permission
                            $hasUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                            //only show the edit button if the user has the update media permission
                            if ($hasUpdatePermission) { ?>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=media&media=edit&action=edit&id=' . $media_id; ?>"
                        class="btn btn-primary">Edit Media</a>
                    <?php } ?>
                    <?php /*confirm user has a role with delete media permissions*/
                            //get the delete media permission id
                            $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE MEDIA');

                            //boolean to check if the user has the delete media permission
                            $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                            //only show the delete button if the user has the delete media permission
                            if ($hasDeletePermission) { ?>
                    <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteMediaModal">
                        Delete Media
                    </button>
                    <?php } ?>
                </div>
            </div>
            <div class="card-body">
                <!-- Single Media Information -->
                <div class="row">
                    <!-- Media Preview -->
                    <div class="col-md-6" style="height: 100%;">
                        <h3>Media Preview</h3>
                        <div id="info" class="">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="img-container"
                                        style="background-image: url('<?php echo getAssetPath() . 'img/transparency.svg' ?>'); background-size:cover;">
                                        <img src="<?php echo $media->getMediaFilePath(intval($media_id)); ?>"
                                            alt="<?php echo $mediaData['filename']; ?>" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Media Details -->
                    <div class="col-md-6" style="height: 100%;">
                        <h3>Media Details</h3>
                        <div id="info" class="">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Media Name:</strong></p>
                                    <p><strong>Media File Type:</strong></p>
                                    <p><strong>Media File Size:</strong></p>
                                    <p><strong>Media Dimensions:</strong></p>
                                    <p><strong>Media Created:</strong></p>
                                    <p><strong>Media Created By:</strong></p>
                                    <p><strong>Media Updated:</strong></p>
                                    <p><strong>Media Updated By:</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo $mediaData['filename']; ?></p>
                                    <p><?php echo "." . strtoupper($mediaData['filetype']); ?></p>
                                    <p>
                                        <?php if ($mediaData['filesize'] != NULL) {
                                                    echo formatFilesize($mediaData['filesize']);
                                                } else {
                                                    echo "N/A";
                                                } ?>
                                    </p>
                                    <?php
                                            $imagePath = __DIR__ . '/../../../public/content/uploads/' . $mediaData['filename'];
                                            $imageDimensions = file_exists($imagePath) ? getImageDimensions($imagePath) : null;
                                            ?>
                                    <p>
                                        <?php if ($imageDimensions != NULL) {
                                                    echo strval($imageDimensions[0]) . "x" . strval($imageDimensions[1]);
                                                } else {
                                                    echo "N/A";
                                                } ?>
                                    </p>
                                    <p><?php echo $mediaData['created_at']; ?></p>
                                    <p><?php echo $user->getUserUsername(intval($mediaData['created_by'])); ?></p>
                                    <p><?php echo $mediaData['updated_at']; ?></p>
                                    <p><?php echo $user->getUserUsername(intval($mediaData['updated_by'])); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Media Usage -->
                <div class="row">
                    <div class="col-md-12">
                        <h3>Media Usage</h3>
                        <?php
                                //get the media usage data
                                $mediaUsageData = $media->getMediaUsage(intval($media_id));

                                //placeholders for the event and school data
                                $eventUsageData = null;
                                $schoolUsageData = null;

                                if ($mediaUsageData != NULL) {
                                    //get the event data if the media is used by an event
                                    if (count($mediaUsageData['events']) > 0) {
                                        $eventUsageData = $mediaUsageData['events'];
                                    } else {
                                        $eventUsageData = array();
                                    }

                                    //get the school data if the media is used by a school
                                    if (count($mediaUsageData['schools']) > 0) {
                                        $schoolUsageData = $mediaUsageData['schools'];
                                    } else {
                                        $schoolUsageData = array();
                                    }
                                }

                                if ($mediaUsageData != NULL && (count($eventUsageData) > 0 || count($schoolUsageData) > 0)) { ?>
                        <div id="info" class="">
                            <?php if (count($eventUsageData) > 0) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Events</h4>
                                    <div class="table-scroll">
                                        <table id="dataTable" class="table table-striped table-bordered">
                                            <?php /*confirm user has a role with read event permissions*/
                                                            //get the id of the read event permission
                                                            $readEventPermissionID = $permissionsObject->getPermissionIdByName('READ EVENT');

                                                            //boolean to track if the user has the read event permission
                                                            $hasReadEventPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readEventPermissionID);

                                                            //only show the event table if the user has the read event permission
                                                            if ($hasReadEventPermission) {

                                                                //include the event class
                                                                $event = new Event();
                                                            ?>
                                            <thead>
                                                <tr>
                                                    <th scope="col">Event Name</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($eventUsageData as $eventUsage) {
                                                                        //set the event id as the value from the array
                                                                        $event_id = intval($eventUsage); ?>
                                                <tr>
                                                    <td><?php echo $event->getEventName(intval($event_id)); ?></td>
                                                    <td><a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=single&id=' . $event_id; ?>"
                                                            class="btn btn-primary">View Event</a></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                            <?php } else { ?>
                                            <thead>
                                                <tr>
                                                    <th scope="col">Event Name</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="3">You do not have permission to view this data.</td>
                                                </tr>
                                            </tbody>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if (count($schoolUsageData) > 0) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Schools</h4>
                                    <div class="table-scroll">
                                        <table id="dataTable" class="table table-striped table-bordered">
                                            <?php /*confirm user has a role with read school permissions*/
                                                            //get the id of the read school permission
                                                            $readSchoolPermissionID = $permissionsObject->getPermissionIdByName('READ SCHOOL');

                                                            //boolean to track if the user has the read school permission
                                                            $hasReadSchoolPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readSchoolPermissionID);

                                                            //only show the school table if the user has the read school permission
                                                            if ($hasReadSchoolPermission) {

                                                                //include the school class
                                                                $school = new School();
                                                            ?>
                                            <thead>
                                                <tr>
                                                    <th scope="col">School Name</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($schoolUsageData as $schoolUsage) {
                                                                        //set the school id as the value from the array
                                                                        $school_id = intval($schoolUsage); ?>
                                                <tr>
                                                    <td><?php echo $school->getSchoolName(intval($school_id)); ?></td>
                                                    <td><a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=single&id=' . $school_id; ?>"
                                                            class="btn btn-primary">View School</a></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                            <?php } else { ?>
                                            <thead>
                                                <tr>
                                                    <th scope="col">School Name</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="3">You do not have permission to view this data.</td>
                                                </tr>
                                            </tbody>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <?php } else { ?>
                        <p>This media is not currently being used by any events or schools.</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="card-footer">
            </div>
            <?php if ($hasDeletePermission) { ?>
            <div id="info" class="">
                <!-- Delete Media Modal-->
                <!-- Modal -->
                <div id="deleteMediaModal" class="modal fade delete" tabindex="-1" role="dialog"
                    aria-labelledby="#mediaDeleteModal" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="mediaDeleteModal">Delete Media -
                                    <?php echo $media->getMediaFileName(intval($media_id)); ?></h3>
                                <button type="button" class="btn-close close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this media?</p>
                                <p>This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                                <form
                                    action="<?php echo APP_URL . '/admin/dashboard.php?view=media&media=single&action=delete&id=' . $media_id; ?>"
                                    method="post">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete Media</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
    <script type="text/javascript">
    //variables for the datatable
    var tableHeight = "50vh";
    var rowNav = true;
    var pageSelect = [5, 10, 15, 20, 25, 50, ["All", -1]];
    var columnArray = [{
            select: 0,
            sortSequence: ["desc", "asc"]
        },
        {
            select: 1,
            sortable: false,
            searchable: false
        }
    ];
    var columnOrder = [0, 1];
    </script>
    <?php } ?>
