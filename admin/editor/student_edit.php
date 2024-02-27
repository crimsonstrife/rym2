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

//include the student class
$student = new Student();

//include the student address class
$studentAddress = new StudentAddress();

//include the student education class
$studentEducation = new StudentEducation();

//include the session class
$session = new Session();

//include the degree class
$degrees = new Degree();

//include the school class
$school = new School();

//include the area of interest class
$areaOfInterest = new AreaOfInterest();

//create an array of states
$stateArray = STATES;

/**
 * Setup the position type list
 * this is done with enums in the database, so no table to pull from.
 */
//setup an array of the position types, each item will have a value and a label
$positionType_list = JOBTYPES;

/**
 * Get the degree levels list from the database
 */
//get the degree levels list
$degree_list = $degrees->getAllGrades();
//for each item, set the ID as the value and the name as the label
foreach ($degree_list as $key => $value) {
    //add an item to the array
    $degree_list[$key] = $arrayName = array(
        "value" => (string)$value['id'],
        "label" => (string)$value['name']
    );
}
//sort the degree levels list alphabetically by name
array_multisort(array_column($degree_list, 'label'), SORT_ASC, $degree_list);

/**
 * Get the schools list from the database
 */
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

/**
 * Get the majors list from the database
 */
//get the majors list
$majors_list = $degrees->getAllMajors();
//for each item, set the id as the value and the name as the label
foreach ($majors_list as $key => $value) {
    //add an item to the array
    $majors_list[$key] = $arrayName = array(
        "value" => (string)$value['id'],
        "label" => (string)$value['name']
    );
}
//sort the majors list alphabetically
array_multisort(array_column($majors_list, 'label'), SORT_ASC, $majors_list);

/**
 * Get the areas of interest list from the database
 */
//get the areas of interest list
$areaOfInterest_list = $areaOfInterest->getAllSubjects();
//for each item, set the id as the value and the name as the label
foreach ($areaOfInterest_list as $key => $value) {
    //add an item to the array
    $areaOfInterest_list[$key] = $arrayName = array(
        "value" => (string)$value['id'],
        "label" => (string)$value['name']
    );
}
//sort the areas of interest list alphabetically
array_multisort(array_column($areaOfInterest_list, 'label'), SORT_ASC, $areaOfInterest_list);

// Define variables and initialize with false values
$student_firstName = $student_lastName = $student_email = $student_phone = $student_address = $student_city = $student_state = $student_zip = $student_degree = $student_major = $student_school = $student_graduationDate = $student_jobPosition = $student_areaOfInterest = "";
$student_firstName_error = $student_lastName_error = $student_email_error = $student_phone_error = $student_address_error = $student_city_error = $student_state_error = $student_zip_error = $student_degree_error = $student_major_error = $student_school_error = $student_graduationDate_error = $student_jobPosition_error = $student_areaOfInterest_error = "";
$entry_error = false;

//check that action is set in the URL parameters
if (isset($_GET['action'])) {
    //get the action from the URL parameters
    $action = $_GET['action'];

    //if the action is edit, show the student edit form
    if ($action == 'edit') {

        //get the update student permission id
        $updateStudentPermissionID = $permissionsObject->getPermissionIdByName('UPDATE STUDENT');

        //boolean to check if the user has the update student permission
        $hasUpdateStudentPermission = $auth->checkUserPermission(intval($session->get('user_id')), $updateStudentPermissionID);

        //if the user does not have the update student permission, prevent access to the editor
        if (!$hasUpdateStudentPermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else {

            if (isset($_GET['id'])) {
                //get the student id from the url parameter
                $student_id = $_GET['id'];
            } else {
                //set the student id to null
                $student_id = null;
            }

            //confirm the id exists
            if (empty($student_id) || $student_id == null) {
                //set the error type
                $thisError = 'INVALID_REQUEST_ERROR';

                //include the error message file
                include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
            } else {
                //try to get the student information
                $object = $student->getStudentById(intval($student_id));

                //check if the student is empty
                if (empty($object)) {
                    //set the error type
                    $thisError = 'NOT_FOUND';

                    //include the error message file
                    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
                }
            }

            //if the student is not empty, get the student information
            if (!empty($object)) {
                //get the student's first name
                $student_firstName = $student->getStudentFirstName(intval($student_id));

                //get the student's last name
                $student_lastName = $student->getStudentLastName(intval($student_id));

                //get the student's email
                $student_email = $student->getStudentEmail(intval($student_id));

                //get the student's phone
                $student_phone = $student->getStudentPhone(intval($student_id));

                //get the student's address
                $student_address = $studentAddress->getStudentAddress(intval($student_id));

                //get the student's city
                $student_city = $studentAddress->getStudentCity(intval($student_id));

                //get the student's state
                $student_state = $studentAddress->getStudentState(intval($student_id));

                //get the student's zip
                $student_zip = $studentAddress->getStudentZip(intval($student_id));

                //get the student's degree
                $student_degree = $studentEducation->getStudentDegreeLevel(intval($student_id));
                //set the student degree to the id of the degree level
                $student_degree = $student_degree['id'];

                //get the student's major
                $student_major = $studentEducation->getStudentMajor(intval($student_id));
                //set the student major to the id of the major
                $student_major = $student_major['id'];

                //get the student's school
                $student_school = $studentEducation->getStudentSchool(intval($student_id));

                //get the student's graduation date
                $student_graduationDate = $studentEducation->getStudentGraduation(intval($student_id));

                //get the student's job position
                $student_jobPosition = $student->getStudentPosition(intval($student_id));

                //get the student's area of interest
                $student_areaOfInterest = $student->getStudentInterest(intval($student_id));
            }

            //if not empty, display the student information
            if (!empty($object)) { ?>
                <div class="container-fluid px-4">
                    <h1 class="mt-4"><?php echo $student->getStudentFullName(intval($student_id)); ?></h1>
                    <div class="row">
                        <div class="card mb-4">
                            <!-- Edit Form -->
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&student=' . $_GET['student'] . '&action=' . $_GET['action'] . '&id=' . $_GET['id']; ?>" class="needs-validation <?php if ($entry_error) {
                                                                                                                                                                                                                        echo 'was-validated';
                                                                                                                                                                                                                    } ?>" method="post" enctype="multipart/form-data" novalidate>
                                <div class="card-header">
                                    <div class="card-title">
                                        <i class="fa-solid fa-person"></i>
                                        Edit Student
                                    </div>
                                    <div class="card-buttons">
                                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=list'; ?>" class="btn btn-secondary">Back to Students</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="student_firstName">First Name:<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" name="student_firstName" id="student_firstName" value="<?php echo $student_firstName; ?>" class="form-control<?php if ($student_firstName_error != null) {
                                                                                                                                                                                                                        echo " is-invalid";
                                                                                                                                                                                                                    } ?>" placeholder="First Name" value="<?php echo $student_firstName; ?>" required>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <?php if ($student_firstName_error != null) { ?>
                                                <!-- errors for name -->
                                                <div id="student_firstName_error" class="invalid-feedback" style="display: unset;">
                                                    <span class="text-danger">
                                                        <?php echo $student_firstName_error; ?>
                                                    </span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="student_lastName">Last Name:<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" name="student_lastName" id="student_lastName" value="<?php echo $student_lastName; ?>" class="form-control<?php if ($student_lastName_error != null) {
                                                                                                                                                                                                                        echo " is-invalid";
                                                                                                                                                                                                                    } ?>"" placeholder=" Last Name" value="<?php echo $student_lastName; ?>" required>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <!-- errors for name -->
                                            <div id="student_lastName_error" class="invalid-feedback" style="display: unset;">
                                                <span class="text-danger"><?php echo $student_lastName_error; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="student_email">Email:<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" name="student_email" id="student_email" value="<?php echo $student_email; ?>" class="form-control<?php if ($student_email_error != null) {
                                                                                                                                                                                                            echo " is-invalid";
                                                                                                                                                                                                        } ?>" placeholder="Email" value="<?php echo $student_email; ?>" required>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <!-- errors for email -->
                                            <div id="student_email_error" class="invalid-feedback" style="display: unset;">
                                                <span class="text-danger"><?php echo $student_email_error; ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="student_phone">Phone:<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" name="student_phone" id="student_phone" value="<?php echo $student_phone; ?>" class="form-control<?php if ($student_phone_error != null) {
                                                                                                                                                                                                            echo " is-invalid";
                                                                                                                                                                                                        } ?>" placeholder="Phone Number" value="<?php echo $student_phone; ?>" required>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <!-- errors for phone -->
                                            <div id="student_phone_error" class="invalid-feedback" style="display: unset;">
                                                <span class="text-danger"><?php echo $student_phone_error; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-1">
                                            <div class="form-group">
                                                <label for="student_address">Address:<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" name="student_address" id="student_address" value="<?php echo $student_address; ?>" class="form-control<?php if ($student_address_error != null) {
                                                                                                                                                                                                                            echo " is-invalid";
                                                                                                                                                                                                                        } ?>" placeholder="Street" value="<?php echo $student_address; ?>" required>
                                                    <span></span>
                                                </div>
                                                <!-- help text for address -->
                                                <small id="addressHelp" class="form-text text-muted">Please enter the Student's
                                                    street address, no P.O. Boxes.</small>
                                            </div>
                                            <!-- errors for address -->
                                            <div id="student_address_error" class="invalid-feedback" style="display: unset;">
                                                <span class="text-danger"><?php echo $student_address_error; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label for="student_city">City:<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" name="student_city" id="student_city" value="<?php echo $student_city; ?>" class="form-control<?php if ($student_city_error != null) {
                                                                                                                                                                                                                echo " is-invalid";
                                                                                                                                                                                                            } ?>" placeholder="City" value="<?php echo $student_city; ?>" required>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <!-- errors for city -->
                                            <div id="student_city_error" class="invalid-feedback" style="display: unset;">
                                                <span class="text-danger"><?php echo $student_city_error; ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="form-group">
                                                <label for="student_state">State:<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <select name="student_state" id="student_state" class="form-control<?php if ($student_state_error != null) { echo " is-invalid"; } ?>" style="width: 100%;" required>
                                                        <?php
                                                        //loop through the states list
                                                        foreach ($stateArray as $state) {
                                                            //check if the state matches the student's state
                                                            if ($student_state == $state['value']) {
                                                                //if it matches, set the selected attribute
                                                                echo '<option value="' . $state['value'] . '" selected>' . $state['label'] . '</option>';
                                                            } else {
                                                                //if it doesn't match, don't set the selected attribute
                                                                echo '<option value="' . $state['value'] . '">' . $state['label'] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <!-- errors for state -->
                                            <div id="student_state_error" class="invalid-feedback" style="display: unset;">
                                                <span class="text-danger"><?php echo $student_state_error; ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="form-group">
                                                <label for="student_zip">Zip:<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" name="student_zip" id="student_zip" class="form-control<?php if ($student_zip_error != null) { echo " is-invalid"; } ?>" placeholder="Zip" value="<?php echo $student_zip; ?>" required>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <!-- errors for zip -->
                                            <div id="student_zip_error" class="invalid-feedback" style="display: unset;">
                                                <span class="text-danger"><?php echo $student_zip_error; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="student_degree">Degree:<span class="text-danger">*</span></label>
                                                <div id="degreeParent" class="col-md-12 degree-dropdown">
                                                    <div class="input-group">
                                                        <select name="student_degree" id="student_degree" class="form-control select2 select2-degree <?php if ($student_degree_error != null) { echo " is-invalid"; } else if ($student_degree_error == null && $entry_error == false) { echo " is-valid"; } ?>" style="width: 100%;" required>
                                                            <?php
                                                            //loop through the degree levels list
                                                            foreach ($degree_list as $degree => $value) {
                                                                //get the key and value from the array and set the variables
                                                                $degree_id = (string)$value['value'];
                                                                $degree_label = (string)$value['label'];
                                                                //check if the degree level matches the student's degree level
                                                                if (intval($student_degree) == intval($degree_id)) {
                                                                    //if it matches, set the selected attribute
                                                                    echo '<option value="' . $degree_id . '" selected>' . $degree_label . '</option>';
                                                                } else {
                                                                    //if it doesn't match, don't set the selected attribute
                                                                    echo '<option value="' . $degree_id . '">' . $degree_label . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                        <span></span>
                                                    </div>
                                                </div>
                                                <!-- help text for degree -->
                                                <small id="degreeHelp" class="form-text text-muted">Please select the Student's
                                                    degree level.</small>
                                            </div>
                                            <!-- errors for degree -->
                                            <div id="student_degree_error" class="invalid-feedback" style="display: unset;">
                                                <span class="text-danger"><?php echo $student_degree_error; ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="student_major">Please select or enter the Student's major:<span class="text-danger">*</span></label>
                                                <!-- Select2 dropdown, used to allow users to add custom entries alongside what is pulled -->
                                                <div id="majorsParent" class="col-md-12 majors-dropdown">
                                                    <select name="student_major" id="student_major" class="form-control select2 select2-major <?php if ($student_major_error != null) {
                                                                                                                                                    echo " is-invalid";
                                                                                                                                                } else if ($student_major_error == null && $entry_error == false) {
                                                                                                                                                    echo " is-valid";
                                                                                                                                                } ?>" style="width: 100%;" required>
                                                        <?php
                                                        //loop through the majors list
                                                        foreach ($majors_list as $major => $value) {
                                                            //get the key and value from the array and set the variables
                                                            $major_id = (string)$value['value'];
                                                            $major_label = (string)$value['label'];
                                                            //check if the major matches the student's major
                                                            if (intval($student_major) == intval($major_id)) {
                                                                //if it matches, set the selected attribute
                                                                echo '<option value="' . $major_id . '" selected>' . $major_label . '</option>';
                                                            } else {
                                                                //if it doesn't match, don't set the selected attribute
                                                                echo '<option value="' . $major_id . '">' . $major_label . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <!-- help text for major -->
                                                <small id="majorHelp" class="form-text text-muted">Please select or enter the student's major.</small>
                                            </div>
                                            <!-- errors for major -->
                                            <div id="student_major_error" class="invalid-feedback" style="display: unset;">
                                                <span class="text-danger"><?php echo $student_major_error; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="student_school">School:<span class="text-danger">*</span></label>
                                                <div id="schoolParent" class="col-md-12 school-dropdown">
                                                    <select name="student_school" id="student_school" class="form-control select2 select2-school <?php if ($student_school_error != null) {
                                                                                                                                                        echo " is-invalid";
                                                                                                                                                    } else if ($student_school_error == null && $entry_error == false) {
                                                                                                                                                        echo " is-valid";
                                                                                                                                                    } ?>" style="width: 100%;">
                                                        <?php
                                                        //loop through the schools list
                                                        foreach ($schools_list as $school => $value) {
                                                            //get the key and value from the array and set the variables
                                                            $school_id = (string)$value['value'];
                                                            $school_label = (string)$value['label'];
                                                            //check if the school matches the student's school
                                                                if (intval($school_id) == intval($student_school)) {
                                                                    //if it matches, set the selected attribute
                                                                    echo '<option value="' . $school_id . '" selected>' . $school_label . '</option>';
                                                                } else {
                                                                    //if it doesn't match, don't set the selected attribute
                                                                    echo '<option value="' . $school_id . '">' . $school_label . '</option>';
                                                                }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <!-- help text for school -->
                                                <small id="schoolHelp" class="form-text text-muted">Please select the Student's school.</small>
                                            </div>
                                            <!-- errors for school -->
                                            <div id="student_school_error" class="invalid-feedback" style="display: unset;">
                                                <span class="text-danger"><?php echo $student_school_error; ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="student_graduationDate"> Expected Graduation Date:<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="date" name="student_graduationDate" id="student_graduationDate" class="form-control <?php if ($student_graduationDate_error != null) { echo " is-invalid"; } ?>" min="<?php echo date(JS_DATE_FORMAT) ?>">
                                                    <span></span>
                                                </div>
                                                <!-- help text for graduation date -->
                                                <small id="graduationDateHelp" class="form-text text-muted">Please enter
                                                    the Student's expected graduation date.</small>
                                            </div>
                                            <!-- errors for graduation date -->
                                            <div id="student_graduationDate_error" class="invalid-feedback" style="display: unset;">
                                                <span class="text-danger"><?php echo $student_graduationDate_error; ?></span>
                                            </div>
                                        </div>
                                        <?php
                                        //if the graduation date is set, display the date in the input
                                        if ($student_graduationDate != null) {
                                            echo '<script>document.getElementById("student_graduationDate").value = "' . $student_graduationDate . '";</script>';
                                        } ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="student_jobPosition">Preferred Job Type:<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <select name="student_jobPosition" id="student_jobPosition" class="form-control <?php if ($student_jobPosition_error != null) {
                                                                                                                                        echo " is-invalid";
                                                                                                                                    } ?>" style="width: 100%;" required>
                                                        <?php foreach ($positionType_list as $positionType) {
                                                            //check if the job position matches the student's job position
                                                            if ($student_jobPosition == $positionType['value']) {
                                                                //if it matches, set the selected attribute
                                                                echo '<option value="' . $positionType['value'] . '" selected>' . $positionType['label'] . '</option>';
                                                            } else {
                                                                //if it doesn't match, don't set the selected attribute
                                                                echo '<option value="' . $positionType['value'] . '">' . $positionType['label'] . '</option>';
                                                            }
                                                        } ?>
                                                    </select>
                                                    <span></span>
                                                </div>
                                                <!-- help text for job position -->
                                                <small id="jobPositionHelp" class="form-text text-muted">Please select the Student's employment preference.</small>
                                            </div>
                                            <!-- errors for job position -->
                                            <div id="student_jobPosition_error" class="invalid-feedback" style="display: unset;">
                                                <span class="text-danger"><?php echo $student_jobPosition_error; ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="student_areaOfInterest">Field:<span class="text-danger">*</span></label>
                                                <div id="aoiParent" class="col-md-12 aoi-dropdown">
                                                    <select name="student_areaOfInterest" id="student_areaOfInterest" class="form-control select2 select2-aoi <?php if ($student_areaOfInterest_error != null) {
                                                                                                                                                                    echo " is-invalid";
                                                                                                                                                                } else if ($student_areaOfInterest_error == null && $entry_error == false) {
                                                                                                                                                                    echo " is-valid";
                                                                                                                                                                } ?>" style="width: 100%;" required>
                                                        <?php
                                                        //loop through the areas of interest list
                                                        foreach ($areaOfInterest_list as $areaOfInterest => $value) {
                                                            //get the key and value from the array and set the variables
                                                            $areaOfInterest_id = (string)$value['value'];
                                                            $areaOfInterest_label = (string)$value['label'];
                                                            //check if the area of interest matches the student's area of interest
                                                            if (intval($student_areaOfInterest) == intval($areaOfInterest_id)) {
                                                                //if it matches, set the selected attribute
                                                                echo '<option value="' . $areaOfInterest_id . '" selected>' . $areaOfInterest_label . '</option>';
                                                            } else {
                                                                //if it doesn't match, don't set the selected attribute
                                                                echo '<option value="' . $areaOfInterest_id . '">' . $areaOfInterest_label . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <!-- help text for area of interest -->
                                                <small id="aoiHelp" class="form-text text-muted">Please select the Student's subject of interest.</small>
                                            </div>
                                            <!-- errors for area of interest -->
                                            <div id="student_areaOfInterest_error" class="invalid-feedback" style="display: unset;">
                                                <span class="text-danger"><?php echo $student_areaOfInterest_error; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- empty row for spacing -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            &nbsp;
                                        </div>
                                    </div>
                                </div>
                                <div class=" card-footer">
                                    <button name="create_Button" type="submit" class="btn btn-primary">Save Changes</button>
                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=list'; ?>" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php }
        }
    } else if ($action == 'create') { //else if the action is create, show the student creation form
        //get the create student permission id
        $createStudentPermissionID = $permissionsObject->getPermissionIdByName('CREATE STUDENT');

        //boolean to check if the user has the create student permission
        $hasCreateStudentPermission = $auth->checkUserPermission(intval($session->get('user_id')), $createStudentPermissionID);

        //if the user does not have the create student permission, prevent access to the editor
        if (!$hasCreateStudentPermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else {
            ?>
            <div class="container-fluid px-4">
                <h1 class="mt-4">New Student</h1>
                <div class="row">
                    <div class="card mb-4">
                        <!-- Create Form -->
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&student=' . $_GET['student'] . '&action=' . $_GET['action']; ?>" class="needs-validation <?php if ($entry_error) { echo 'was-validated'; } ?>" method="post" enctype="multipart/form-data" novalidate>
                            <div class="card-header">
                                <div class="card-title">
                                    <i class="fa-solid fa-person"></i>
                                    Create Student
                                </div>
                                <div class="card-buttons">
                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=list'; ?>" class="btn btn-secondary">Back to Students</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="student_firstName">First Name:<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" name="student_firstName" id="student_firstName" class="form-control<?php if ($student_firstName_error != null) { echo " is-invalid"; } ?>" placeholder="First Name" required>
                                                <span></span>
                                            </div>
                                        </div>
                                        <?php if ($student_firstName_error != null) { ?>
                                            <!-- errors for name -->
                                            <div id="student_firstName_error" class="invalid-feedback" style="display: unset;">
                                                <span class="text-danger">
                                                    <?php echo $student_firstName_error; ?>
                                                </span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="student_lastName">Last Name:<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" name="student_lastName" id="student_lastName" class="form-control<?php if ($student_lastName_error != null) {
                                                                                                                                        echo " is-invalid";
                                                                                                                                    } ?>"" placeholder=" Last Name" value="<?php echo $student_lastName; ?>" required>
                                                <span></span>
                                            </div>
                                        </div>
                                        <!-- errors for name -->
                                        <div id="student_lastName_error" class="invalid-feedback" style="display: unset;">
                                            <span class="text-danger"><?php echo $student_lastName_error; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="student_email">Email:<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" name="student_email" id="student_email" class="form-control<?php if ($student_email_error != null) { echo " is-invalid"; } ?>" placeholder="Email" required>
                                                <span></span>
                                            </div>
                                        </div>
                                        <!-- errors for email -->
                                        <div id="student_email_error" class="invalid-feedback" style="display: unset;">
                                            <span class="text-danger"><?php echo $student_email_error; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="student_phone">Phone:<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" name="student_phone" id="student_phone" class="form-control<?php if ($student_phone_error != null) { echo " is-invalid"; } ?>" placeholder="Phone Number" required>
                                                <span></span>
                                            </div>
                                        </div>
                                        <!-- errors for phone -->
                                        <div id="student_phone_error" class="invalid-feedback" style="display: unset;">
                                            <span class="text-danger"><?php echo $student_phone_error; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-1">
                                        <div class="form-group">
                                            <label for="student_address">Address:<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" name="student_address" id="student_address" class="form-control<?php if ($student_address_error != null) { echo " is-invalid"; } ?>" placeholder="Street" required>
                                                <span></span>
                                            </div>
                                            <!-- help text for address -->
                                            <small id="addressHelp" class="form-text text-muted">Please enter the Student's
                                                street address, no P.O. Boxes.</small>
                                        </div>
                                        <!-- errors for address -->
                                        <div id="student_address_error" class="invalid-feedback" style="display: unset;">
                                            <span class="text-danger"><?php echo $student_address_error; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="student_city">City:<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" name="student_city" id="student_city" class="form-control<?php if ($student_city_error != null) { echo " is-invalid"; } ?>" placeholder="City" required>
                                                <span></span>
                                            </div>
                                        </div>
                                        <!-- errors for city -->
                                        <div id="student_city_error" class="invalid-feedback" style="display: unset;">
                                            <span class="text-danger"><?php echo $student_city_error; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="student_state">State:<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select name="student_state" id="student_state" class="form-control<?php if ($student_state_error != null) {
                                                                                                                        echo " is-invalid";
                                                                                                                    } ?>" style="width: 100%;" required>
                                                    <?php
                                                    //loop through the states list
                                                    foreach ($stateArray as $state) {
                                                        //check if the state matches the student's state
                                                        if ($student_state == $state['value']) {
                                                            //if it matches, set the selected attribute
                                                            echo '<option value="' . $state['value'] . '" selected>' . $state['label'] . '</option>';
                                                        } else {
                                                            //if it doesn't match, don't set the selected attribute
                                                            echo '<option value="' . $state['value'] . '">' . $state['label'] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <span></span>
                                            </div>
                                        </div>
                                        <!-- errors for state -->
                                        <div id="student_state_error" class="invalid-feedback" style="display: unset;">
                                            <span class="text-danger"><?php echo $student_state_error; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="student_zip">Zip:<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" name="student_zip" id="student_zip" class="form-control<?php if ($student_zip_error != null) { echo " is-invalid"; } ?>" placeholder="Zip" required>
                                                <span></span>
                                            </div>
                                        </div>
                                        <!-- errors for zip -->
                                        <div id="student_zip_error" class="invalid-feedback" style="display: unset;">
                                            <span class="text-danger"><?php echo $student_zip_error; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="student_degree">Degree:<span class="text-danger">*</span></label>
                                            <div id="degreeParent" class="col-md-12 degree-dropdown">
                                                <div class="input-group">
                                                    <select name="student_degree" id="student_degree" class="form-control select2 select2-degree <?php if ($student_degree_error != null) { echo " is-invalid"; } else if ($student_degree_error == null && $entry_error == false) { echo " is-valid"; } ?>" style="width: 100%;" required>
                                                        <?php
                                                        //loop through the degree levels list
                                                        foreach ($degree_list as $degree => $value) {
                                                            //get the key and value from the array and set the variables
                                                            $degree_id = (string)$value['value'];
                                                            $degree_label = (string)$value['label'];
                                                            //check if the degree level matches the student's degree level
                                                            if ($student_degree == $degree_label) {
                                                                //if it matches, set the selected attribute
                                                                echo '<option value="' . $degree_id . '" selected>' . $degree_label . '</option>';
                                                            } else {
                                                                //if it doesn't match, don't set the selected attribute
                                                                echo '<option value="' . $degree_id . '">' . $degree_label . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <!-- help text for degree -->
                                            <small id="degreeHelp" class="form-text text-muted">Please select the Student's
                                                degree level.</small>
                                        </div>
                                        <!-- errors for degree -->
                                        <div id="student_degree_error" class="invalid-feedback" style="display: unset;">
                                            <span class="text-danger"><?php echo $student_degree_error; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="student_major">Please select or enter the Student's major:<span class="text-danger">*</span></label>
                                            <!-- Select2 dropdown, used to allow users to add custom entries alongside what is pulled -->
                                            <div id="majorsParent" class="col-md-12 majors-dropdown">
                                                <select name="student_major" id="student_major" class="form-control select2 select2-major <?php if ($student_major_error != null) { echo " is-invalid"; } else if ($student_major_error == null && $entry_error == false) { echo " is-valid"; } ?>" style="width: 100%;" required>
                                                    <?php
                                                    //loop through the majors list
                                                    foreach ($majors_list as $major => $value) {
                                                        //get the key and value from the array and set the variables
                                                        $major_id = (string)$value['value'];
                                                        $major_label = (string)$value['label'];
                                                        //check if the major matches the student's major
                                                        if ($student_major == $major_label) {
                                                            //if it matches, set the selected attribute
                                                            echo '<option value="' . $major_label . '" selected>' . $major_label . '</option>';
                                                        } else {
                                                            //if it doesn't match, don't set the selected attribute
                                                            echo '<option value="' . $major_label . '">' . $major_label . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <!-- help text for major -->
                                            <small id="majorHelp" class="form-text text-muted">Please select or enter the student's major.</small>
                                        </div>
                                        <!-- errors for major -->
                                        <div id="student_major_error" class="invalid-feedback" style="display: unset;">
                                            <span class="text-danger"><?php echo $student_major_error; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="student_school">School:<span class="text-danger">*</span></label>
                                            <div id="schoolParent" class="col-md-12 school-dropdown">
                                                <select name="student_school" id="student_school" class="form-control select2 select2-school <?php if ($student_school_error != null) { echo " is-invalid"; } else if ($student_school_error == null && $entry_error == false) { echo " is-valid"; } ?>" style="width: 100%;">
                                                    <?php
                                                    //loop through the schools list
                                                    foreach ($schools_list as $school => $value) {
                                                        //get the key and value from the array and set the variables
                                                        $school_id = (string)$value['value'];
                                                        $school_label = (string)$value['label'];
                                                        if ($school_label == $student_school) {
                                                            //if it matches, set the selected attribute
                                                            echo '<option value="' . $school_id . '" selected>' . $school_label . '</option>';
                                                        } else {
                                                            //if it doesn't match, don't set the selected attribute
                                                            echo '<option value="' . $school_id . '">' . $school_label . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <!-- help text for school -->
                                            <small id="schoolHelp" class="form-text text-muted">Please select the Student's school.</small>
                                        </div>
                                        <!-- errors for school -->
                                        <div id="student_school_error" class="invalid-feedback" style="display: unset;">
                                            <span class="text-danger"><?php echo $student_school_error; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="student_graduationDate"> Expected Graduation Date:<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="date" name="student_graduationDate" id="student_graduationDate" class="form-control <?php if ($student_graduationDate_error != null) { echo " is-invalid"; } ?>" min="<?php echo date(JS_DATE_FORMAT) ?>" value="<?php echo (!empty($student_graduationDate) ? formatDate($student_graduationDate) : date(JS_DATE_FORMAT)); ?>">
                                                <span></span>
                                            </div>
                                            <!-- help text for graduation date -->
                                            <small id="graduationDateHelp" class="form-text text-muted">Please enter
                                                the Student's expected graduation date.</small>
                                        </div>
                                        <!-- errors for graduation date -->
                                        <div id="student_graduationDate_error" class="invalid-feedback" style="display: unset;">
                                            <span class="text-danger"><?php echo $student_graduationDate_error; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="student_jobPosition">Preferred Job Type:<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select name="student_jobPosition" id="student_jobPosition" class="form-control <?php if ($student_jobPosition_error != null) { echo " is-invalid"; } ?>" style="width: 100%;" required>
                                                    <?php foreach ($positionType_list as $positionType) {
                                                        //check if the job position matches the student's job position
                                                        if ($student_jobPosition == $positionType['value']) {
                                                            //if it matches, set the selected attribute
                                                            echo '<option value="' . $positionType['value'] . '" selected>' . $positionType['label'] . '</option>';
                                                        } else {
                                                            //if it doesn't match, don't set the selected attribute
                                                            echo '<option value="' . $positionType['value'] . '">' . $positionType['label'] . '</option>';
                                                        }
                                                    } ?>
                                                </select>
                                                <span></span>
                                            </div>
                                            <!-- help text for job position -->
                                            <small id="jobPositionHelp" class="form-text text-muted">Please select the Student's employment preference.</small>
                                        </div>
                                        <!-- errors for job position -->
                                        <div id="student_jobPosition_error" class="invalid-feedback" style="display: unset;">
                                            <span class="text-danger"><?php echo $student_jobPosition_error; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="student_areaOfInterest">Field:<span class="text-danger">*</span></label>
                                            <div id="aoiParent" class="col-md-12 aoi-dropdown">
                                                <select name="student_areaOfInterest" id="student_areaOfInterest" class="form-control select2 select2-aoi <?php if ($student_areaOfInterest_error != null) {
                                                                                                                                                                                                                                                    echo " is-invalid";
                                                                                                                                                                                                                                                } else if ($student_areaOfInterest_error == null && $entry_error == false) {
                                                                                                                                                                                                                                                    echo " is-valid";
                                                                                                                                                                                                                                                } ?>" style="width: 100%;" required>
                                                    <?php
                                                    //loop through the areas of interest list
                                                    foreach ($areaOfInterest_list as $areaOfInterest => $value) {
                                                        //get the key and value from the array and set the variables
                                                        $areaOfInterest_id = (string)$value['value'];
                                                        $areaOfInterest_label = (string)$value['label'];
                                                        //check if the area of interest matches the student's area of interest
                                                        if ($student_areaOfInterest == $areaOfInterest_label) {
                                                            //if it matches, set the selected attribute
                                                            echo '<option value="' . $areaOfInterest_id . '" selected>' . $areaOfInterest_label . '</option>';
                                                        } else {
                                                            //if it doesn't match, don't set the selected attribute
                                                            echo '<option value="' . $areaOfInterest_id . '">' . $areaOfInterest_label . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <!-- help text for area of interest -->
                                            <small id="aoiHelp" class="form-text text-muted">Please select the Student's subject of interest.</small>
                                        </div>
                                        <!-- errors for area of interest -->
                                        <div id="student_areaOfInterest_error" class="invalid-feedback" style="display: unset;">
                                            <span class="text-danger"><?php echo $student_areaOfInterest_error; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- empty row for spacing -->
                                <div class="row">
                                    <div class="col-md-12">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class=" card-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=list'; ?>" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    <?php }
    } ?>
    <!-- script to handle the form validation, disables submission if there are invalid fields -->
    <script>
        // based on the bootstrap validation documentation from https://getbootstrap.com/docs/4.0/components/forms/#validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var form = document.getElementsByClassName('needs-validation');

                //check the fields and prevent submission if there are errors
                var validation = Array.prototype.filter.call(form, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
    <!-- Select2 script -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2-major').select2({
                dropdownParent: $('#majorsParent'),
                tags: true
            });
            $('.select2-degree').select2({
                dropdownParent: $('#degreeParent'),
            });
            $('.select2-school').select2({
                dropdownParent: $('#schoolParent'),
            });
            $('.select2-aoi').select2({
                dropdownParent: $('#aoiParent'),
            });
        });
    </script>
<?php } else {
    //set the action to null
    $action = null;

    //set the error type
    $thisError = 'ROUTING_ERROR';

    //include the error message file
    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
} ?>
