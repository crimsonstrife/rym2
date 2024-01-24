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

//include the authenticator class
$auth = new Authenticator();

/*confirm user has a role with update event permissions*/
//get the id of the update event permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('UPDATE EVENT');

//boolean to track if the user has the update event permission
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
    $target_file_banner = null;
    $target_file_logo = null;
    $imageFileType_logo = null;
    $imageFileType_banner = null;
    $logo_media_id = null;
    $banner_media_id = null;

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
        if (isset($_FILES["event_logo"])) {
            $event_logo = $_FILES["event_logo"];
        }
        //get the event banner from the form
        if (isset($_FILES["event_banner"])) {
            $event_banner = $_FILES["event_banner"];
        }

        //if the event logo is empty, set the event logo to null
        if (empty($event_logo)) {
            $event_logo = null;
        }

        //if the event banner is empty, set the event banner to null
        if (empty($event_banner)) {
            $event_banner = null;
        }

        //if there are files to upload, upload them
        if ((!empty($event_logo) || !empty($event_banner)) && ($event_logo != '' || $event_banner != '') && ($event_logo != null || $event_banner != null)) {
            if ((!empty($event_logo)) && $event_logo != '' && $event_logo != null) {
                //upload the event logo
                $logo_media_id = $media->uploadMedia($event_logo, intval($_SESSION['user_id']));
            }
            if ((!empty($event_banner)) && $event_banner != '' && $event_banner != null) {
                //upload the event banner
                $banner_media_id = $media->uploadMedia($event_banner, intval($_SESSION['user_id']));
            }

            //debugging
            error_log('Event Banner Media ID: ' . strval($banner_media_id));
            error_log('Event Logo Media ID: ' . strval($logo_media_id));

            //check if the event had an existing logo or banner, if so, update the record
            if ($action == 'edit') {
                //if neither the logo or banner are empty, update the event logo and banner
                if (!empty($event_logo) && !empty($event_banner)) {
                    $existing_logo = $event->getEventLogo($event_id);
                    $existing_banner = $event->getEventBanner($event_id);

                    //debug
                    error_log('existing logo ID: ' . strval($existing_logo));
                    error_log('existing banner ID: ' . strval($existing_banner));

                    //if the existing logo and banner are not empty, see if they match the media ids for the uploaded files
                    if ((!empty($existing_logo) || $existing_logo != '' || $existing_logo != null || $existing_logo != 0) && (!empty($existing_banner) || $existing_banner != '' || $existing_banner != null || $existing_banner != 0)) {
                        //if the ids match, update the logo and banner
                        if (($existing_logo == $logo_media_id) && ($existing_banner == $banner_media_id)) {
                            $event->updateEventLogoAndBanner($event_id, $logo_media_id, $banner_media_id);
                        } else {
                            //if the ids don't match, run them individually
                            $event->updateEventLogo($event_id, $logo_media_id);
                            $event->updateEventBanner($event_id, $banner_media_id);
                        }
                    } else {
                        //if the existing logo and banner are empty or 0, set the logo and banner
                        $event->setEventLogoAndBanner($event_id, $logo_media_id, $banner_media_id);
                    }
                } else if (!empty($event_logo) && empty($event_banner)) {
                    $existing_logo = $event->getEventLogo($event_id);
                    if (!empty($existing_logo) || $existing_logo != '' || $existing_logo != null || $existing_logo != 0) {
                        $event->updateEventLogo($event_id, $banner_media_id);
                    } else {
                        $event->setEventLogo($event_id, $banner_media_id);
                    }
                } else if (!empty($event_banner) && empty($event_logo)) {
                    $existing_banner = $event->getEventBanner($event_id);
                    if (!empty($existing_banner) || $existing_banner != '' || $existing_banner != null || $existing_banner != 0) {
                        $event->updateEventBanner($event_id, $banner_media_id);
                    } else {
                        $event->setEventBanner($event_id, $banner_media_id);
                    }
                }
            }
        }

        //if the action is edit, update the event
        if ($action == 'edit') {
            //get current user ID
            $user_id = intval($_SESSION['user_id']);
            //update the event
            $event->updateEvent($event_id, $event_name, $event_date, $event_location, $user_id);
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
                            echo 'Event Updated';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
