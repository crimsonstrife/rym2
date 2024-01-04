<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//include the permissions class
$permissionsObject = new Permission();

//include the user class
$user = new User();

/*confirm user has a role with update school permissions*/
//get the id of the update school permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('UPDATE SCHOOL');

//boolean to track if the user has the update school permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    die('Error: You do not have permission to perform this request.');
} else {

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

    //user class
    $user = new User();

    //get the action from the url parameter
    $action = $_GET['action'];

    //other variables
    $target_file_logo = null;
    $imageFileType_logo = null;

    //if the action is edit, get the school id from the url parameter
    if ($action == 'edit') {
        $school_id = $_GET['id'];
    }

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the school name from the form
        if (isset($_POST["school_name"])) {
            $school_name = trim($_POST["school_name"]);
            //prepare the school name
            $school_name = prepareData($school_name);
        }
        //get the school address from the form
        if (isset($_POST["school_address"])) {
            $school_address = trim($_POST["school_address"]);
            //prepare the school address
            $school_address = prepareData($school_address);
        }
        //get the school city from the form
        if (isset($_POST["school_city"])) {
            $school_city = trim($_POST["school_city"]);
            //prepare the school city
            $school_city = prepareData($school_city);
        }
        //get the school state from the form
        if (isset($_POST["school_state"])) {
            $school_state = trim($_POST["school_state"]);
            //prepare the school state
            $school_state = prepareData($school_state);
        }
        //get the school zip from the form
        if (isset($_POST["school_zip"])) {
            $school_zip = trim($_POST["school_zip"]);
            //prepare the school zip
            $school_zip = prepareData($school_zip);
        }
        //get the school logo from the form
        if (isset($_FILES["school_logo"])) {
            $school_logo = $_FILES["school_logo"];
        }
        //get the school branding color from the form
        if (isset($_POST["school_color"])) {
            $school_color = trim($_POST["school_color"]);
            //prepare the school branding color
            $school_color = prepareData($school_color);
        }

        //if the school logo is empty, set the school logo to null
        if (empty($school_logo)) {
            $school_logo = null;
        }

        //if the school color is empty, set the school color to black
        if (empty($school_color)) {
            $school_color = '#000000';
        }

        //if there are files to upload, upload them
        if (!empty($school_logo)) {
            //Php upload script based loosely on https://www.w3schools.com/php/php_file_upload.asp
            $target_dir = dirname(__FILE__) . '/../../../../public/content/uploads/';
            //get the file names if they are not empty or null
            if (!empty($school_logo)) {
                $school_logo_file = basename($_FILES["school_logo"]["name"]);
                //log the file name
                //error_log('File name: ' . $school_logo_file);
            }
            //set the target file paths
            if (!empty($school_logo_file)) {
                $target_file_logo = $target_dir . $school_logo_file;
                //log the target file path
                //error_log('Target file: ' . $target_file_logo);
            }
            //upload status booleans
            $uploadOk_logo = 1;
            //if the logo target file is not empty, setup the type and size checks
            if (!empty($target_file_logo)) {
                $imageFileType_logo = strtolower(pathinfo($target_file_logo, PATHINFO_EXTENSION));
                $check_logo = getimagesize($_FILES["school_logo"]["tmp_name"]);
                if ($check_logo === false) {
                    $school_logo = null;
                    $uploadOk_logo = 0;
                } else {
                    $uploadOk_logo = 1;
                }
            }

            // Check if file already exists
            if (file_exists($target_file_logo)) {
                $school_logo = null;
                $uploadOk_logo = 0;
            }

            // Check file size
            if ($_FILES["school_logo"]["size"] > 500000) { //500kb
                $school_logo = null;
                $uploadOk_logo = 0;
            }

            // Allow certain file formats
            if (
                $imageFileType_logo != "jpg" && $imageFileType_logo != "png" && $imageFileType_logo != "jpeg"
            ) {
                $school_logo = null;
                $uploadOk_logo = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk_logo == 0) {
                $school_logo = null;
                // if everything is ok, try to upload file
            } else {
                if (!empty($target_file_logo)) {
                    if (move_uploaded_file($_FILES["school_logo"]["tmp_name"], $target_file_logo)) {
                        $school_logo = $school_logo_file;
                    } else {
                        $school_logo = null;
                    }
                }
            }

            //check if the school had an existing logo, if so, update the record
            if ($action == 'edit') {
                //if the logo is not empty, update the school logo
                if (!empty($school_logo) || $school_logo != null || isset($school_logo) || $school_logo != '') {
                    $existing_logo = $school->getSchoolLogo($school_id);
                    //if the existing logo is not empty, see if the id matches
                    if (!empty($existing_logo) || $existing_logo != '' || $existing_logo != null) {
                        //if the file names match, update the logo incase the file has changed
                        if ($existing_logo == $school_logo) {
                            //should not be needed, but just in case
                            $school->setSchoolLogo($school_id, $school_logo);
                        } else {
                            //if the file names do not match, set the logo
                            $school->setSchoolLogo($school_id, $school_logo);
                        }
                    } else {
                        //if the existing logo is empty, set the logo
                        $school->setSchoolLogo($school_id, $school_logo);
                    }
                } else if (empty($school_logo) || $school_logo == null || $school_logo == '') {
                    $existing_logo = $school->getSchoolLogo($school_id);
                    if (!empty($existing_logo) || $existing_logo != '' || $existing_logo != null) {
                        //if the file names match, update the logo incase the file has changed
                        if ($existing_logo == $school_logo) {
                            //should not be needed, but just in case
                            $school->setSchoolLogo($school_id, $existing_logo);
                        } else {
                            //if the file names do not match, set the logo
                            $school->setSchoolLogo($school_id, $existing_logo);
                        }
                    } else {
                        $school->setSchoolLogo($school_id, $existing_logo);
                    }
                }
            }
        }

        //check if the school had an existing branding color, if so, update the record
        if ($action == 'edit') {
            //if the color is empty, update the school color
            if (!empty($school_color)) {
                $existing_color = $school->getSchoolColor($school_id);
                //if the existing color is not empty, see if the id matches
                if (!empty($existing_color) || $existing_color != '' || $existing_color != null) {
                    //if the colors match, update the color incase the color has changed
                    if ($existing_color == $school_color) {
                        //should not be needed, but just in case
                        $school->setSchoolColor($school_id, $school_color);
                    } else {
                        //if the colors do not match, set the color
                        $school->setSchoolColor($school_id, $school_color);
                    }
                } else {
                    //if the existing color is empty, set the color
                    $school->setSchoolColor($school_id, $school_color);
                }
            } else if (!empty($school_color)) {
                $existing_color = $school->getSchoolColor($school_id);
                if (!empty($existing_color) || $existing_color != '' || $existing_color != null) {
                    $school->setSchoolColor($school_id, $school_color);
                } else {
                    $school->setSchoolColor($school_id, $school_color);
                }
            }
        }

        //if the action is edit, update the event
        if ($action == 'edit') {
            //get current user ID
            $user_id = intval($_SESSION['user_id']);
            //update the event
            $schoolUpdated = $school->updateSchool(intval($school_id), $school_name, $school_address, $school_city, $school_state, $school_zip, $user_id);
        }
    } ?>
<!-- Completion page content -->
<div class="container-fluid px-4">
    <div class="row">
        <div class="card mb-4">
            <!-- show completion message -->
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-check"></i>
                    <?php
                        if ($action == 'edit') {
                            if ($schoolUpdated) {
                                echo 'School Updated';
                            } else {
                                echo 'Error: School Not Updated';
                            }
                        }
                        ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
