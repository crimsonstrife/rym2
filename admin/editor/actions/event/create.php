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

//include the media class
$media = new Media();

/*confirm user has a role with create event permissions*/
//get the id of the create event permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('CREATE EVENT');

//boolean to track if the user has the create event permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
} else {

    //event class
    $event = new Event();

    //school class
    $school = new School();

    //media class
    $media = new Media();

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

    //student class
    $student = new Student();

    //get the action from the url parameter
    $action = $_GET['action'];

    //other variables
    $addMediaToNewEvent = false;
    $logoMedia_id = null;
    $bannerMedia_id = null;
    $event_logo = null;
    $event_banner = null;
    $eventCreated = false;

    //if the action is edit, get the event id from the url parameter
    if ($action == 'edit') {
        $event_id = $_GET['id'];
    }

    //get the event location if this is an event edit page
    if ($action == 'edit') {
        $event_location = $school->getSchoolName($event->getEventLocationId($event_id));
    } else {
        //if this is not an event edit page, set the event location to null
        $event_location = null;
    }

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the event name from the form
        if (isset($_POST["event_name"])) {
            $event_name = trim($_POST["event_name"]);
            //prepare the event name
            $event_name = prepareData($event_name);
        }
        //get the event date from the form
        if (isset($_POST["event_date"])) {
            $event_date = trim($_POST["event_date"]);
            //prepare the event date
            $event_date = prepareData($event_date);
        }

        //get the event location from the form
        if (isset($_POST["event_school"])) {
            $event_location = trim($_POST["event_school"]);
            //prepare the student_school
            $event_location = prepareData($event_location);
            $event_location = (int) $event_location;
        }

        //get the event logo from the form
        if (isset($_POST["event_logoSelect"])) {
            $event_logoSelection = $_POST["event_logoSelect"];

            //if the logo selection is empty, blank, or zero
            if (empty($event_logoSelection) || $event_logoSelection == '' || $event_logoSelection == 0) {
                //try to get the file from the file input
                if (isset($_FILES["event_logoUpload"])) {
                    $uploaded_file = $_FILES["event_logoUpload"];

                    //if the file is empty, set the uploaded file to null
                    if (empty($uploaded_file) || $uploaded_file == '' || $uploaded_file == null) {
                        $uploaded_file = null;
                    } else {
                        //set the event logo to the uploaded file
                        $event_logo = $uploaded_file;
                    }
                }
            } else {
                //set the uploaded file to null
                $uploaded_file = null;
                //set the event logo to the selection
                $event_logo = intval($event_logoSelection);
            }
        }

        //if the event logo is empty, set the event logo to null
        if (empty($event_logo)) {
            $event_logo = null;
        }

        //get the event banner from the form
        if (isset($_POST["event_bannerSelect"])) {
            $event_bannerSelection = $_POST["event_bannerSelect"];

            //if the banner selection is empty, blank, or zero
            if (empty($event_bannerSelection) || $event_bannerSelection == '' || $event_bannerSelection == 0) {
                //try to get the file from the file input
                if (isset($_FILES["event_bannerUpload"])) {
                    $uploaded_file = $_FILES["event_bannerUpload"];

                    //if the file is empty, set the uploaded file to null
                    if (empty($uploaded_file) || $uploaded_file == '' || $uploaded_file == null) {
                        $uploaded_file = null;
                    } else {
                        //set the event banner to the uploaded file
                        $event_banner = $uploaded_file;
                    }
                }
            } else {
                //set the uploaded file to null
                $uploaded_file = null;
                //set the event banner to the selection
                $event_banner = intval($event_bannerSelection);
            }
        }

        //if the event banner is empty, set the event banner to null
        if (empty($event_banner)) {
            $event_banner = null;
        }

        //if there are files to upload, upload them
        if (!empty($event_logo) || !empty($event_banner)) {
            //if the event logo is not empty or null, try to upload it
            if (!empty($event_logo) && $event_logo != null) {
                //if the event logo is an array, upload the file
                if (is_array($event_logo)) {
                    $logoMedia_id = $media->uploadMedia($event_logo, intval($_SESSION['user_id']));
                } else {
                    //if the event logo is not an array, set the media id to the event logo int
                    $logoMedia_id = $event_logo;
                }
            }

            //if the event banner is not empty or null, try to upload it
            if (!empty($event_banner) && $event_banner != null) {
                //if the event banner is an array, upload the file
                if (is_array($event_banner)) {
                    $bannerMedia_id = $media->uploadMedia($event_banner, intval($_SESSION['user_id']));
                } else {
                    //if the event banner is not an array, set the media id to the event banner int
                    $bannerMedia_id = $event_banner;
                }
            }

            //if the action is create, check if there are files to add after the event is created
            if ($action == 'create') {
                //if neither the logo or banner are empty, set a variable to call later after the event is created.
                if (!empty($logoMedia_id) && !empty($bannerMedia_id)) {
                    $addMediaToNewEvent = true;
                } else if (!empty($logoMedia_id)) {
                    $addMediaToNewEvent = true;
                } else if (!empty($bannerMedia_id)) {
                    $addMediaToNewEvent = true;
                }
            }
        }

        //if the action is create, create the event
        if ($action == 'create') {
            //get current user ID
            $user_id = intval($_SESSION['user_id']);
            //create the event
            $eventCreated = $event->createEvent($event_name, $event_date, $event_location, $user_id);
            //if the event was created, get the event id
            if ($eventCreated) {
                $event_id = $event->getEventIdByName($event_name);
            }
            //if the event was created and there are files to add, add them
            if ($eventCreated && $addMediaToNewEvent) {
                //if neither the logo or banner are empty, update the event logo and banner
                if (!empty($logoMedia_id) && !empty($bannerMedia_id)) {
                    $event->setEventLogoAndBanner($event_id, $logoMedia_id, $bannerMedia_id);
                } else if (!empty($logoMedia_id)) {
                    $event->setEventLogo($event_id, $logoMedia_id);
                } else if (!empty($bannerMedia_id)) {
                    $event->setEventBanner($event_id, $bannerMedia_id);
                }
            }
        }
    } ?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <?php
                        if ($action == 'create') {
                            if ($eventCreated) {
                                echo '<i class="fa-solid fa-check"></i>';
                                echo 'Event Created';
                            } else {
                                echo '<i class="fa-solid fa-x"></i>';
                                echo 'Error: Event Not Created';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
