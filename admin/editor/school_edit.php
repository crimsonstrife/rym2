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

//include the user class
$user = new User();

//school class
$school = new School();

//include the media class
$media = new Media();

//include the session class
$session = new Session();

//create an array of available media
$mediaArray = $media->getMedia();

//create an array of states
$stateArray = STATES;

//check that action is set in the URL parameters
if (isset($_GET['action'])) {
    //get the action from the URL parameters
    $action = $_GET['action'];

    //if the action is edit, show the school edit form
    if ($action == 'edit') {

        //get the update school permission id
        $updateSchoolPermissionID = $permissionsObject->getPermissionIdByName('UPDATE SCHOOL');

        //boolean to check if the user has the update school permission
        $hasUpdateSchoolPermission = $auth->checkUserPermission(intval($session->get('user_id')), $updateSchoolPermissionID);

        //if the user does not have the update school permission, prevent access to the editor
        if (!$hasUpdateSchoolPermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else {

            if (isset($_GET['id'])) {
                //get the school id from the url parameter
                $school_id = $_GET['id'];
            } else {
                //set the school id to null
                $school_id = null;
            }

            //confirm the id exists
            if (empty($school_id) || $school_id == null) {
                //set the error type
                $thisError = 'INVALID_REQUEST_ERROR';

                //include the error message file
                include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
            } else {
                //try to get the school information
                $object = $school->getSchoolById(intval($school_id));

                //check if the school is empty
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
                    <h1 class="mt-4"><?php echo $school->getSchoolName(intval($school_id)); ?></h1>
                    <div class="row">
                        <div class="card mb-4">
                            <!-- Edit Form -->
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . htmlspecialchars($_GET['view']) . '&school=' . htmlspecialchars($_GET['school']) . '&action=' . htmlspecialchars($_GET['action']) . '&id=' . htmlspecialchars($_GET['id']); ?>" method="post" enctype="multipart/form-data">
                                <div class="card-header">
                                    <div class="card-title">
                                        <i class="fa-solid fa-school"></i>
                                        Edit School
                                    </div>
                                    <div class="card-buttons">
                                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=list'; ?>" class="btn btn-secondary">Back to Schools</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Form Information -->
                                        <div class="col-md-6">
                                            <div class="info">
                                                <p>
                                                    <span class="info-title"><strong>Instructions:</strong> </span>
                                                    <span class="info-text">Use this form to edit the school information, <strong><span class="required">*</span></strong> denotes a required field.</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4>School Information</h4>
                                            <div class="form-group">
                                                <p><strong><label for="schoolName">School Name: <strong><span class="required">*</span></strong></label></strong></p>
                                                <p><input type="text" id="schoolName" name="school_name" class="form-control" value="<?php echo $school->getSchoolName(intval($school_id)); ?>" placeholder="<?php echo $school->getSchoolName(intval($school_id)); ?>" required>
                                                </p>
                                                <p><small id="schoolNameHelp" class="form-text text-muted">Enter a unique name for the
                                                        school.</small></p>
                                            </div>
                                            <div class="form-group">
                                                <p><strong><label for="schoolAddress">School Address: <strong><span class="required">*</span></strong></label></strong></p>
                                                <p><input type="text" id="schoolAddress" name="school_address" class="form-control" value="<?php echo $school->getSchoolAddress(intval($school_id)); ?>" placeholder="<?php echo $school->getSchoolAddress(intval($school_id)); ?>" required>
                                                </p>
                                                <p><small id="schoolAddressHelp" class="form-text text-muted">Enter the street address
                                                        of
                                                        the school.</small></p>
                                            </div>
                                            <div class="form-group">
                                                <p><strong><label for="schoolCity">School City: <strong><span class="required">*</span></strong></label></strong></p>
                                                <p><input type="text" id="schoolCity" name="school_city" class="form-control" value="<?php echo $school->getSchoolCity(intval($school_id)); ?>" placeholder="<?php echo $school->getSchoolCity(intval($school_id)); ?>" required>
                                                </p>
                                                <p><small id="schoolCityHelp" class="form-text text-muted">Enter the city of the
                                                        school address.</small></p>
                                            </div>
                                            <div class="form-group">
                                                <p><strong><label for="schoolState">School State: <strong><span class="required">*</span></strong></label></strong></p>
                                                <p>
                                                <div id="schoolParent" class="col-md-12 school-dropdown">
                                                    <select type="select" id="schoolState" name="school_state" class="select2 select2-school form-control app-forms" required>
                                                        <option value="">Select a State</option>
                                                        <?php
                                                        //loop through the state array
                                                        foreach ($stateArray as $key => $value) {
                                                            //if the state matches the school state, set the selected attribute
                                                            if ($value['value'] == $school->getSchoolState(intval($school_id))) {
                                                                echo '<option value="' . $value['value'] . '" selected>' . $value['label'] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $value['value'] . '">' . $value['label'] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                </p>
                                                <p><small id="schoolStateHelp" class="form-text text-muted">Select the state for the
                                                        school address.</small></p>
                                            </div>
                                            <div class="form-group">
                                                <p><strong><label for="schoolZip">School Zip: <strong><span class="required">*</span></strong></label></strong></p>
                                                <p><input type="text" id="schoolZip" name="school_zip" class="form-control" value="<?php echo $school->getSchoolZip(intval($school_id)); ?>" placeholder="<?php echo $school->getSchoolZip(intval($school_id)); ?>" required>
                                                </p>
                                                <p><small id="schoolZipHelp" class="form-text text-muted">Enter the zip/postal code of
                                                        the
                                                        school address.</small></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- School Branding (optional) -->
                                            <h4>School Branding</h4>
                                            <div class="alert alert-info" role="alert"><span class="note-icon"><i class="fa-solid fa-circle-info"></i></span>
                                                <span class="note-text">If you do not have a logo to upload, you can
                                                    create
                                                    the
                                                    school and add them later.</span>
                                            </div>
                                            <div class="form-group">
                                                <p>
                                                    <strong><label for="schoolLogo">School Logo:</label></strong>
                                                    <!-- if there is an existing logo, show the file -->
                                                    <?php
                                                    if (!empty($school->getSchoolLogo(intval($school_id)))) {
                                                        //render the file as an image
                                                        echo '<div><img src="' . htmlspecialchars(getUploadPath()) . htmlspecialchars($media->getMediaFileName($school->getSchoolLogo(intval($school_id)))) . '" alt="School Logo" style="max-width: 200px; max-height: auto;"></div>';
                                                        //show the file name
                                                        echo '<div> ' . $media->getMediaFileName($school->getSchoolLogo(intval($school_id))) . '</div>';
                                                    }
                                                    ?>
                                                </p>
                                                <?php //allow the user to either select a file from the mediaArray or upload a new file if they have upload permissions
                                                if (!empty($mediaArray)) { ?>
                                                    <p><strong><label for="schoolLogo">Select a New Logo:</label></strong></p>
                                                    <p>
                                                        <select id="schoolLogoSelect" name="school_logoSelect" class="form-control">
                                                            <option value="">Select a Logo</option>
                                                            <?php /* check if the user has permission to upload media */
                                                            if ($auth->checkUserPermission(intval($session->get('user_id')), $permissionsObject->getPermissionIdByName('CREATE MEDIA'))) { ?>
                                                                <option value="0">Upload a New Logo</option>
                                                            <?php } ?>
                                                            <?php foreach ($mediaArray as $key => $value) { ?>
                                                                <option value="<?php echo $value['id'] ?>"><?php echo $value['filename']; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                        <br />
                                                        <!-- if the user selects to upload a new file, show the file upload input -->
                                                        <input type="file" id="schoolLogoUpload" name="school_logoUpload" class="form-control" disabled hidden>
                                                    <p>
                                                        <small id="schoolLogoHelp" class="form-text text-muted">To upload a new file, select
                                                            "Upload
                                                            a New Logo" from the dropdown. To use an existing file, select the
                                                            filename.</small>
                                                    </p>
                                                    </p>
                                                    <?php } else if (empty($mediaArray)) { //if there are no media files, show the file upload input if the user has upload permissions
                                                    if ($auth->checkUserPermission(intval($session->get('user_id')), $permissionsObject->getPermissionIdByName('CREATE MEDIA'))) { ?>
                                                        <p><strong><label for="schoolLogo">Upload a New Logo:</label></strong></p>
                                                        <p><input type="file" id="schoolLogoUpload" name="school_logoUpload" class="form-control" required></p>
                                                        <p>
                                                            <small id="schoolLogoHelp" class="form-text text-muted">To upload a new file, select
                                                                "Upload
                                                                a New Logo" from the dropdown. To use an existing file, select the
                                                                filename.</small>
                                                        </p>
                                                        <div class="alert alert-warning" role="alert"><span class="note-icon"><i class="fa-solid fa-circle-exclamation"></i></span>
                                                            <span class="note-text">If the upload option is not available, contact the
                                                                administrator
                                                                for assistance.</span>
                                                        </div>
                                                    <?php } else { ?>
                                                        <p><strong><label for="schoolLogo">No Media Available</label></strong></p>
                                                        <p>You lack permissions to upload new media files and none currently exist, contact the
                                                            administrator.</p>
                                                <?php }
                                                } ?>
                                            </div>
                                            <div class="form-group">
                                                <p>
                                                    <?php $currentColor = $school->getSchoolColor(intval($school_id)) ?? '#000000'; ?>
                                                    <strong><label for="schoolColor">School Primary Color:</label></strong>
                                                    <!-- if there is an existing color, show the color -->
                                                    <?php
                                                    if (!empty($school->getSchoolColor(intval($school_id)))) {
                                                        //render the color as a div
                                                        echo '<div id="color-block" style="width: 100px; height: 100px; background-color: ' . $school->getSchoolColor(intval($school_id)) . ';"></div>';
                                                    } else {
                                                        //render the color as a div
                                                        echo '<div id="color-block" style="width: 100px; height: 100px; background-color: ' . $currentColor . ';"></div>';
                                                    }
                                                    ?>
                                                </p>
                                                <p><input type="text" id="schoolColor" name="school_color" class="form-control" value="<?php echo $school->getSchoolColor(intval($school_id)); ?>" placeholder="<?php echo $school->getSchoolColor(intval($school_id)); ?>"></p>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p>
                                                        <div id="color-picker"></div>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p>
                                                        <div id="values"></div>
                                                        </p>
                                                    </div>
                                                </div>
                                                <?php echo "<script>var currentColor = '$currentColor';</script>"; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class=" card-footer">
                                    <button name="create_Button" type="submit" class="btn btn-primary">Save Changes</button>
                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=list'; ?>" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php }
        }
    } else if ($action == 'create') { //else if the action is create, show the school creation form
        //get the create school permission id
        $createSchoolPermissionID = $permissionsObject->getPermissionIdByName('CREATE SCHOOL');

        //boolean to check if the user has the create school permission
        $hasCreateSchoolPermission = $auth->checkUserPermission(intval($session->get('user_id')), $createSchoolPermissionID);

        //if the user does not have the create school permission, prevent access to the editor
        if (!$hasCreateSchoolPermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else {
            ?>
            <div class="container-fluid px-4">
                <h1 class="mt-4">New School</h1>
                <div class="row">
                    <div class="card mb-4">
                        <!-- Create Form -->
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . htmlspecialchars($_GET['view']) . '&school=' . htmlspecialchars($_GET['school']) . '&action=' . htmlspecialchars($_GET['action']); ?>" method="post" enctype="multipart/form-data">
                            <div class="card-header">
                                <div class="card-title">
                                    <i class="fa-solid fa-school"></i>
                                    Create School
                                </div>
                                <div class="card-buttons">
                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=list'; ?>" class="btn btn-secondary">Back to Schools</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Form Information -->
                                    <div class="col-md-6">
                                        <div class="info">
                                            <p>
                                                <span class="info-title"><strong>Instructions:</strong> </span>
                                                <span class="info-text">Use this form to enter the new school information,
                                                    <strong><span class="required">*</span></strong> denotes a required
                                                    field.</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- School Information -->
                                            <h4>School Information</h4>
                                            <div class="form-group">
                                                <p><strong><label for="schoolName">School Name: <strong><span class="required">*</span></strong></label></strong></p>
                                                <p><input type="text" id="schoolName" name="school_name" class="form-control" placeholder="School Name" required></p>
                                                <p><small id="schoolNameHelp" class="form-text text-muted">Enter a unique name for
                                                        the school.</small></p>
                                            </div>
                                            <div class="form-group">
                                                <p><strong><label for="schoolAddress">School Address: <strong><span class="required">*</span></strong></label></strong></p>
                                                <p><input type="text" id="schoolAddress" name="school_address" class="form-control" placeholder="School Address" required></p>
                                                <p><small id="schoolAddressHelp" class="form-text text-muted">Enter the street
                                                        address
                                                        of
                                                        the school.</small></p>
                                            </div>
                                            <div class="form-group">
                                                <p><strong><label for="schoolCity">School City: <strong><span class="required">*</span></strong></label></strong></p>
                                                <p><input type="text" id="schoolCity" name="school_city" class="form-control" placeholder="School City" required></p>
                                                <p><small id="schoolCityHelp" class="form-text text-muted">Enter the city of the
                                                        school address.</small></p>
                                            </div>
                                            <div class="form-group">
                                                <p><strong><label for="schoolState">School State: <strong><span class="required">*</span></strong></label></strong></p>
                                                <div id="schoolParent" class="col-md-12 school-dropdown">
                                                    <select type="select" id="schoolState" name="school_state" class="select2 select2-school form-control app-forms" required>
                                                        <option value="">Select a State</option>
                                                        <?php
                                                        //loop through the state array
                                                        foreach ($stateArray as $key => $value) {
                                                            echo '<option value="' . $value['value'] . '">' . $value['label'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <p><small id="schoolStateHelp" class="form-text text-muted">Select the state for the
                                                        school address.</small></p>
                                            </div>
                                            <div class="form-group">
                                                <p><strong><label for="schoolZip">School Zip: <strong><span class="required">*</span></strong></label></strong></p>
                                                <p><input type="text" id="schoolZip" name="school_zip" class="form-control" placeholder="School Zip" required></p>
                                                <p></p><small id="schoolZipHelp" class="form-text text-muted">Enter the zip/postal
                                                    code
                                                    of
                                                    the
                                                    school address.</small></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- School Branding (optional) -->
                                            <h4>School Branding</h4>
                                            <div class="alert alert-info" role="alert"><span class="note-icon"><i class="fa-solid fa-circle-info"></i></span>
                                                <span class="note-text">If you do not have a logo to upload, you can
                                                    create
                                                    the
                                                    school and add them later.</span>
                                            </div>
                                            <div class="form-group">
                                                <?php //allow the user to either select a file from the mediaArray or upload a new file if they have upload permissions
                                                if (!empty($mediaArray)) { ?>
                                                    <p><strong><label for="schoolLogo">Select a New Logo:</label></strong></p>
                                                    <p>
                                                        <select id="schoolLogoSelect" name="school_logoSelect" class="form-control">
                                                            <option value="">Select a Logo</option>
                                                            <?php /* check if the user has permission to upload media */
                                                            if ($auth->checkUserPermission(intval($session->get('user_id')), $permissionsObject->getPermissionIdByName('CREATE MEDIA'))) { ?>
                                                                <option value="0">Upload a New Logo</option>
                                                            <?php } ?>
                                                            <?php foreach ($mediaArray as $key => $value) { ?>
                                                                <option value="<?php echo $value['id'] ?>"><?php echo $value['filename']; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                        <br />
                                                        <!-- if the user selects to upload a new file, show the file upload input -->
                                                        <input type="file" id="schoolLogoUpload" name="school_logoUpload" class="form-control" disabled hidden>
                                                    </p>
                                                    <p>
                                                        <small id="schoolLogoHelp" class="form-text text-muted">To upload a new file,
                                                            select
                                                            "Upload
                                                            a New Logo" from the dropdown. To use an existing file, select the
                                                            filename.</small>
                                                    </p>
                                                    <?php } else if (empty($mediaArray)) { //if there are no media files, show the file upload input if the user has upload permissions
                                                    if ($auth->checkUserPermission(intval($session->get('user_id')), $permissionsObject->getPermissionIdByName('CREATE MEDIA'))) { ?>
                                                        <p><strong><label for="schoolLogo">Upload a New Logo:</label></strong></p>
                                                        <p><input type="file" id="schoolLogoUpload" name="school_logoUpload" class="form-control" required></p>
                                                        <p>
                                                            <small id="schoolLogoHelp" class="form-text text-muted">To upload a new file,
                                                                select
                                                                "Upload
                                                                a New Logo" from the dropdown. To use an existing file, select the
                                                                filename.</small>
                                                        </p>
                                                        <div class="alert alert-warning" role="alert"><span class="note-icon"><i class="fa-solid fa-circle-exclamation"></i></span>
                                                            <span class="note-text">If the upload option is not available, contact the
                                                                administrator
                                                                for assistance.</span>
                                                        </div>
                                                    <?php } else { ?>
                                                        <p><strong><label for="schoolLogo">No Media Available</label></strong></p>
                                                        <p>You lack permissions to upload new media files and none currently exist, contact
                                                            the
                                                            administrator.</p>
                                                <?php }
                                                } ?>
                                            </div>
                                            <div class="form-group">
                                                <p>
                                                    <?php $currentColor = '#000000'; ?>
                                                    <strong><label for="schoolColor">School Primary Color:</label></strong>
                                                    <!-- if there is an existing color, show the color -->
                                                    <?php
                                                    //render the color as a div
                                                    echo '<div id="color-block" style="width: 100px; height: 100px; background-color: ' . $currentColor . ';"></div>';
                                                    ?>
                                                </p>
                                                <p><input type="text" id="schoolColor" name="school_color" class="form-control" value="<?php echo $currentColor; ?>" placeholder="<?php echo $currentColor; ?>">
                                                </p>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p>
                                                        <div id="color-picker"></div>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p>
                                                        <div id="values"></div>
                                                        </p>
                                                    </div>
                                                </div>
                                                <?php echo "<script>var currentColor = '$currentColor';</script>"; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class=" card-footer">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=list'; ?>" class="btn btn-secondary">Cancel</a>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
    <?php
        }
    } ?>
    <script type="text/javascript" src="<?php echo htmlspecialchars(getLibraryPath()) . 'irojs/iro.min.js'; ?>">
    </script>
    <script>
        //hide or show the file upload input based on the user selection
        $(document).ready(function() {
            $('#schoolLogoSelect').change(function() {
                if ($(this).val() == '0') {
                    $('#schoolLogoUpload').prop('disabled', false).show();
                    $('#schoolLogoUpload').prop('hidden', false).show();
                } else {
                    $('#schoolLogoUpload').prop('disabled', true).hide();
                    $('#schoolLogoUpload').prop('hidden', true).hide();
                }
            });
        });
    </script>
<?php
    //if color-picker.min.js exists, use it, otherwise use color-picker.js
    if (file_exists(BASEPATH . '/public/content/assets/js/color-picker.min.js')) {
        echo '<script type="text/javascript" src="' . htmlspecialchars(getAssetPath()) . 'js/color-picker.min.js"></script>';
    } else {
        echo '<script type="text/javascript" src="' . htmlspecialchars(getAssetPath()) . 'js/color-picker.js"></script>';
    }
} else {
    //set the action to null
    $action = null;

    //set the error type
    $thisError = 'ROUTING_ERROR';

    //include the error message file
    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
} ?>
