<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//autoload composer dependencies
require_once __DIR__ . '/../../vendor/autoload.php';

//include the permissions class
$permissionsObject = new Permission();

//auth class
$auth = new Authenticator();

//user class
$user = new User();

/*confirm user has a role with read school permissions*/
//get the id of the read school permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('READ SCHOOL');

//boolean to track if the user has the read school permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    die('Error: You do not have permission to perform this request.');
} else {

    //school class
    $school = new School();

    //get the school id from the url parameter
    $school_id = $_GET['id'];
?>
    <link rel="stylesheet" href="<?php echo getLibraryPath() . 'leaflet/leaflet.css'; ?>">
    <link rel="stylesheet" href="<?php echo getLibraryPath() . 'leaflet-geosearch/geosearch.css'; ?>">
    <script>
        var mapLocationTitle = "<?php echo $school->getSchoolName(intval($school_id)); ?>";
        var address = "<?php echo $school->getFormattedSchoolAddress(intval($school_id)); ?>";
    </script>
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?php echo $school->getSchoolName(intval($school_id)); ?></h1>
        <div class="row">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-calendar-day"></i>
                        School Information
                    </div>
                    <div class="card-buttons">
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=list'; ?>" class="btn btn-secondary">Back to Schools</a>
                        <?php /*confirm user has a role with update school permissions*/
                        //get the update school permission id
                        $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE SCHOOL');

                        //boolean to check if the user has the update school permission
                        $hasUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                        //only show the edit button if the user has the update school permission
                        if ($hasUpdatePermission) { ?>
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=edit&action=edit&id=' . $school_id; ?>" class="btn btn-primary">Edit School</a>
                        <?php } ?>
                        <?php /*confirm user has a role with delete school permissions*/
                        //get the delete school permission id
                        $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE SCHOOL');

                        //boolean to check if the user has the delete school permission
                        $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                        //only show the delete button if the user has the delete school permission
                        if ($hasDeletePermission) { ?>
                            <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSchoolModal">
                                Delete School
                            </button>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Single School information -->
                    <div class="row">
                        <div class="col-md-6" style="height: 100%;">
                            <h3>School Details</h3>
                            <div id="info" class="">
                                <p><strong>School Name:</strong> <?php echo $school->getSchoolName(intval($school_id)); ?>
                                </p>
                                <!-- Formatted School address -->
                                <div>
                                    <p><strong>School Address:</strong>
                                        <?php
                                        //encode the address as a url for google maps - this will be used to link to google maps per Google documentation https://developers.google.com/maps/documentation/urls/get-started
                                        $address = $school->getFormattedSchoolAddress(intval($school_id));
                                        $address = urlencode($address);
                                        ?>
                                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $address; ?>" target="_blank"><?php echo $school->getFormattedSchoolAddress(intval($school_id)); ?></a>
                                    </p>
                                </div>
                                <div id="map"></div>
                            </div>
                            <div id="eventBranding">
                                <h3>School Branding</h3>
                                <p><strong>School Logo:</strong></p>
                                <img src="<?php echo APP_URL . "/public/content/uploads/" . $school->getSchoolLogo(intval($school_id)); ?>" alt="School Logo" style="max-width: 200px; max-height: auto;">
                                <p><strong>School Primary Color:</strong></p>
                                <div style="width: 100px; height: 100px; background-color: <?php echo $school->getSchoolColor(intval($school_id)) ?? '#000000'; ?>;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                    </div>
                    <?php if ($hasDeletePermission) { ?>
                        <div id="info" class="">
                            <!-- Delete School Modal-->
                            <!-- Modal -->
                            <div id="deleteSchoolModal" class="modal fade delete" tabindex="-1" role="dialog" aria-labelledby="#schoolDeleteModal" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title" id="schoolDeleteModal">Delete School -
                                                <?php echo $school->getSchoolName(intval($school_id)); ?></h3>
                                            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this school?</p>
                                            <p>This action cannot be undone.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=single&action=delete&id=' . $school_id; ?>" method="post">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete School</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo getLibraryPath() . 'leaflet/leaflet.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo getLibraryPath() . 'leaflet-geosearch/geosearch.umd.js'; ?>">
    </script>
    <script type="text/javascript" src="<?php echo getLibraryPath() . 'irojs/iro-core.umd.js'; ?>">
    </script>
    <script type="module" src="<?php echo getAssetPath() . 'js/event-map.js'; ?>">
    </script>
    <script type="text/javascript" src="<?php echo getAssetPath() . 'js/color-picker.js'; ?>"></script>
<?php } ?>
