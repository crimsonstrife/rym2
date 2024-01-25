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

//include the user class
$user = new User();

//include the media class
$media = new Media();

/*confirm user has a role with create school permissions*/
//get the id of the create school permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('CREATE SCHOOL');

//boolean to track if the user has the create school permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
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

    //get the action from the url parameter
    $action = $_GET['action'];

    //other variables
    $target_file_logo = null;
    $imageFileType_logo = null;
    $media_id = null;

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

        //if the action is create, create the school
        if ($action == 'create') {
            //get current user ID
            $user_id = intval($_SESSION['user_id']);
            //create the school
            $schoolCreated = $school->createSchool($school_name, $school_address, $school_city, $school_state, $school_zip, $user_id);
        }

        //get the school logo from the form
        if (isset($_POST["school_logoSelect"])) {
            $school_logoSelection = $_POST["school_logoSelect"];

            //if the logo selection is empty, blank, or zero
            if (empty($school_logoSelection) || $school_logoSelection == '' || $school_logoSelection == 0) {
                //try to get the file from the file input
                if (isset($_FILES["school_logoUpload"])) {
                    $uploaded_file = $_FILES["school_logoUpload"];

                    //if the file is empty, set the uploaded file to null
                    if (empty($uploaded_file) || $uploaded_file == '' || $uploaded_file == null) {
                        $uploaded_file = null;
                    } else {
                        //set the school logo to the uploaded file
                        $school_logo = $uploaded_file;
                    }
                }
            } else {
                //set the uploaded file to null
                $uploaded_file = null;
                //set the school logo to the selection
                $school_logo = intval($school_logoSelection);
            }
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

        //get the school id using the school name
        $school_id = $school->getSchoolIdByName($school_name);

        //if there are files to upload, upload them
        if (!empty($school_logo) && $uploaded_file != null) {
            //check if the school logo is an array
            if (is_array($school_logo)) {
                //upload the school logo, and get the media id
                $media_id = $media->uploadMedia($school_logo, intval($_SESSION['user_id']));

                //get the file name
                $target_file_logo = $media->getMediaFileName($media_id);

                //get the file type
                $imageFileType_logo = strtolower(pathinfo($target_file_logo, PATHINFO_EXTENSION));
            } else if (!empty($school_logo) && $uploaded_file = null) {
                //assume the school logo is an integer
                $media_id = $school_logo;

                //get the file name
                $target_file_logo = $media->getMediaFileName($media_id);

                //get the file type
                $imageFileType_logo = strtolower(pathinfo($target_file_logo, PATHINFO_EXTENSION));
            }
        }

        if (!empty($school_logo) || $school_logo != null || isset($school_logo)) {
            //set the school logo
            $schoolLogoSet = $school->setSchoolLogo($school_id, $media_id);
        } else {
            $schoolLogoSet = false;
        }

        if (!empty($school_color) || $school_color != null || isset($school_color)) {
            //set the school color
            $schoolColorSet = $school->setSchoolColor($school_id, $school_color);
        } else {
            $schoolColorSet = false;
        }
    } ?>
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
                                if ($schoolCreated) {
                                    echo 'School Created';
                                } else {
                                    echo 'Error: School Not Created';
                                }
                            }
                            ?>
                    </div>
                    <div>
                        <?php
                            if ($schoolLogoSet && !empty($school_logo)) {
                                echo 'School Logo Set';
                            } else {
                                if ($school_logo == null || empty($school_logo)) {
                                    //do nothing
                                } else {
                                    if ($schoolLogoSet) {
                                        echo 'School Logo Set';
                                    } else {
                                        echo 'Error: School Logo Not Set';
                                    }
                                }
                            }
                            ?>
                    </div>
                    <div>
                        <?php
                            if ($schoolColorSet && !empty($school_color)) {
                                echo 'School Color Set';
                            } else {
                                if ($school_color == null || empty($school_color)) {
                                    //do nothing
                                } else {
                                    if ($schoolColorSet) {
                                        echo 'School Color Set';
                                    } else {
                                        echo 'Error: School Color Not Set';
                                    }
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
