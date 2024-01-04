<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//include the permissions class
$permissionsObject = new Permission();

//include the auth class
$auth = new Authenticator();

//include the user class
$user = new User();

//school class
$school = new School();

//get the schools list
$schools_list = $school->getSchools();
//for each item, set the id as the value and the name as the label
foreach ($schools_list as $key => $value) {
    //add an item to the array
    $schools_list[$key] = $arrayName = array(
        "value" => (string)$value['id'],
        "label" => (string)$value['name']
    );
}
//sort the schools list alphabetically
array_multisort(array_column($schools_list, 'label'), SORT_ASC, $schools_list);

//create an array of states
$stateArray = STATES;

//user class
$user = new User();

//get the action from the url parameter
$action = $_GET['action'];

//if the action is edit, get the school id from the url parameter
if ($action == 'edit') {
    $school_id = $_GET['id'];
}

//if the action is edit, show the school edit form
if ($action == 'edit') {

    //get the update school permission id
    $updateSchoolPermissionID = $permissionsObject->getPermissionIdByName('UPDATE SCHOOL');

    //boolean to check if the user has the update school permission
    $hasUpdateSchoolPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updateSchoolPermissionID);

    //if the user does not have the update school permission, prevent access to the editor
    if (!$hasUpdateSchoolPermission) {
        //die with an error message
        die('Error: You do not have permission to access this content, contact the Administrator.');
    } else { ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $school->getSchoolName(intval($school_id)); ?></h1>
    <div class="row">
        <div class="card mb-4">
            <!-- Edit Form -->
            <form
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&school=' . $_GET['school'] . '&action=' . $_GET['action'] . '&id=' . $_GET['id']; ?>"
                method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-school"></i>
                        Edit School
                    </div>
                    <div class="card-buttons">
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=list'; ?>"
                            class="btn btn-primary btn-sm">Back to Schools</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><label for="schoolName">School Name:</label></strong></p>
                            <p><input type="text" id="schoolName" name="school_name" class="form-control"
                                    value="<?php echo $school->getSchoolName(intval($school_id)); ?>"
                                    placeholder="<?php echo $school->getSchoolName(intval($school_id)); ?>" required>
                            </p>
                            <p><strong><label for="schoolAddress">School Address:</label></strong></p>
                            <p><input type="text" id="schoolAddress" name="school_address" class="form-control"
                                    value="<?php echo $school->getSchoolAddress(intval($school_id)); ?>"
                                    placeholder="<?php echo $school->getSchoolAddress(intval($school_id)); ?>" required>
                            </p>
                            <p><strong><label for="schoolCity">School City:</label></strong></p>
                            <p><input type="text" id="schoolCity" name="school_city" class="form-control"
                                    value="<?php echo $school->getSchoolCity(intval($school_id)); ?>"
                                    placeholder="<?php echo $school->getSchoolCity(intval($school_id)); ?>" required>
                            </p>
                            <p><strong><label for="schoolState">School State:</label></strong></p>
                            <div id="schoolParent" class="col-md-12 school-dropdown">
                                <select type="select" id="schoolState" name="school_state"
                                    class="select2 select2-school form-control app-forms" required>
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
                            <p><strong><label for="schoolZip">School Zip:</label></strong></p>
                            <p><input type="text" id="schoolZip" name="school_zip" class="form-control"
                                    value="<?php echo $school->getSchoolZip(intval($school_id)); ?>"
                                    placeholder="<?php echo $school->getSchoolZip(intval($school_id)); ?>" required>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <!-- School Branding (optional) -->
                            <h4>School Branding</h4>
                            <p>
                                <strong><label for="schoolLogo">School Logo:</label></strong>
                                <!-- if there is an existing logo, show the file -->
                                <?php
                                        if (!empty($school->getSchoolLogo(intval($school_id)))) {
                                            //render the file as an image
                                            echo '<div><img src="' . APP_URL . '/public/content/uploads/' . $school->getSchoolLogo(intval($school_id)) . '" alt="School Logo" style="max-width: 200px; max-height: auto;"></div>';
                                            //show the file name
                                            echo '<div> ' . $school->getSchoolLogo(intval($school_id)) . '</div>';
                                        }
                                        ?>
                            </p>
                            <p><input type="file" id="schoolLogo" name="school_logo" class="form-control"></p>
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
                            <p><input type="text" id="schoolColor" name="school_color" class="form-control"
                                    value="<?php echo $school->getSchoolColor(intval($school_id)); ?>"
                                    placeholder="<?php echo $school->getSchoolColor(intval($school_id)); ?>"></p>
                            <p>
                            <div id="values"></div>
                            </p>
                            <p>
                            <div id="color-picker"></div>
                            </p>
                            <?php echo "<script>var currentColor = '$currentColor';</script>"; ?>
                        </div>
                    </div>
                </div>
                <div class=" card-footer">
                    <button name="create_Button" type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=list'; ?>"
                        class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php }
} else if ($action == 'create') { //else if the action is create, show the school creation form
    //get the create school permission id
    $createSchoolPermissionID = $permissionsObject->getPermissionIdByName('CREATE SCHOOL');

    //boolean to check if the user has the create school permission
    $hasCreateSchoolPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createSchoolPermissionID);

    //if the user does not have the create school permission, prevent access to the editor
    if (!$hasCreateSchoolPermission) {
        //die with an error message
        die('Error: You do not have permission to access this content, contact the Administrator.');
    } else {
    ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">New School</h1>
    <div class="row">
        <div class="card mb-4">
            <!-- Create Form -->
            <form
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&school=' . $_GET['school'] . '&action=' . $_GET['action']; ?>"
                method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-school"></i>
                        Create School
                    </div>
                    <div class="card-buttons">
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=list'; ?>"
                            class="btn btn-primary btn-sm">Back to Schools</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- School Information -->
                            <h4>School Information</h4>
                            <p><strong><label for="schoolName">School Name:</label></strong></p>
                            <p><input type="text" id="schoolName" name="school_name" class="form-control"
                                    placeholder="School Name" required></p>
                            <p><strong><label for="schoolAddress">School Address:</label></strong></p>
                            <p><input type="text" id="schoolAddress" name="school_address" class="form-control"
                                    placeholder="School Address" required></p>
                            <p><strong><label for="schoolCity">School City:</label></strong></p>
                            <p><input type="text" id="schoolCity" name="school_city" class="form-control"
                                    placeholder="School City" required></p>
                            <p><strong><label for="schoolState">School State:</label></strong></p>
                            <div id="schoolParent" class="col-md-12 school-dropdown">
                                <select type="select" id="schoolState" name="school_state"
                                    class="select2 select2-school form-control app-forms" required>
                                    <option value="">Select a State</option>
                                    <?php
                                            //loop through the state array
                                            foreach ($stateArray as $key => $value) {
                                                echo '<option value="' . $value['value'] . '">' . $value['label'] . '</option>';
                                            }
                                            ?>
                                </select>
                            </div>
                            <p><strong><label for="schoolZip">School Zip:</label></strong></p>
                            <p><input type="text" id="schoolZip" name="school_zip" class="form-control"
                                    placeholder="School Zip" required></p>
                        </div>
                        <div class="col-md-6">
                            <!-- School Branding (optional) -->
                            <h4>School Branding</h4>
                            <p><strong><label for="schoolLogo">School Logo:</label></strong></p>
                            <p><input type="file" id="schoolLogo" name="school_logo" class="form-control"></p>
                            <p>
                                <?php $currentColor = '#000000'; ?>
                                <strong><label for="schoolColor">School Primary Color:</label></strong>
                                <!-- if there is an existing color, show the color -->
                                <?php
                                        //render the color as a div
                                        echo '<div id="color-block" style="width: 100px; height: 100px; background-color: ' . $currentColor . ';"></div>';
                                        ?>
                            </p>
                            <p><input type="text" id="schoolColor" name="school_color" class="form-control"
                                    value="<?php echo $currentColor; ?>" placeholder="<?php echo $currentColor; ?>"></p>
                            <p>
                            <div id="values"></div>
                            </p>
                            <p>
                            <div id="color-picker"></div>
                            </p>
                            <?php echo "<script>var currentColor = '$currentColor';</script>"; ?>
                        </div>
                    </div>
                </div>
                <div class=" card-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=list'; ?>"
                        class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo getLibraryPath() . 'irojs/iro.min.js'; ?>">
</script>
<script type="text/javascript" src="<?php echo getAssetPath() . 'js/color-picker.js'; ?>"></script>
<?php }
} ?>
