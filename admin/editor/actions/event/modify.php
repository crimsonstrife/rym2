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

//include the session class
$session = new Session();

/*confirm user has a role with update event permissions*/
//get the id of the update event permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('UPDATE EVENT');

//boolean to track if the user has the update event permission
$hasPermission = $auth->checkUserPermission(intval($session->get('user_id')), $relevantPermissionID);

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

    //event media class
    $eventMedia = new EventMedia();

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
    $mediaChanged = false;
    $logoMedia_id = null;
    $bannerMedia_id = null;
    $event_logo = null;
    $event_banner = null;
    $eventUpdated = false;
    $haslogo = false;
    $hasbanner = false;
    $uploaded_file = null;
    $updateEventMedia = false;

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

        //reset the uploaded file to null if it is not
        if ($uploaded_file != null) {
            $uploaded_file = null;
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

        //reset the uploaded file to null if it is not
        if ($uploaded_file != null) {
            $uploaded_file = null;
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
                    $logoMedia_id = $media->uploadMedia($event_logo, intval($session->get('user_id')));
                } else {
                    //if the event logo is not an array, set the media id to the event logo int
                    $logoMedia_id = $event_logo;
                }
            }
        }

        //if the event banner is not empty or null, try to upload it
        if (!empty($event_banner) && $event_banner != null) {
            //if the event banner is an array, upload the file
            if (is_array($event_banner)) {
                $bannerMedia_id = $media->uploadMedia($event_banner, intval($session->get('user_id')));
            } else {
                //if the event banner is not an array, set the media id to the event banner int
                $bannerMedia_id = $event_banner;
            }
        }

        //if the action is edit, update the event
        if ($action == 'edit') {
            //get current user ID
            $user_id = intval($session->get('user_id'));
            //update the event
            $eventUpdated = $event->updateEvent($event_id, $event_name, $event_date, $event_location, $user_id);

            //if the logo media id is not null, see if it is different from the current logo media id
            if ($logoMedia_id != null) {
                //get the current logo media id
                $currentLogoMedia_id = $eventMedia->getEventLogo($event_id);
                //if the new logo media id is different from the current logo media id, update the event logo
                if ($logoMedia_id != $currentLogoMedia_id) {
                    $eventMedia->updateEventLogo($event_id, $logoMedia_id);
                    //set the update event media flag to true
                    $updateEventMedia = true;
                }
            }

            //if the banner media id is not null, see if it is different from the current banner media id
            if ($bannerMedia_id != null) {
                //get the current banner media id
                $currentBannerMedia_id = $eventMedia->getEventBanner($event_id);
                //if the new banner media id is different from the current banner media id, update the event banner
                if ($bannerMedia_id != $currentBannerMedia_id) {
                    $eventMedia->updateEventBanner($event_id, $bannerMedia_id);
                    //set the update event media flag to true
                    $updateEventMedia = true;
                }
            }
        }
    } ?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?php echo $event_name; ?></h1>
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <div>
                            <?php
                            if ($action == 'edit') {
                                if ($eventUpdated) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Event Updated';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Event Not Updated';
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
                            if ($action == 'edit') {
                                if ($eventUpdated) {
                                    echo '<p>The event: ' . $event_name . ' has been updated.</p>';
                                } else {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo '<p>The event: ' . $event_name . ' could not be updated.</p>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <!-- show error messages -->
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($action == 'edit') {
                                if (!$eventUpdated) {
                                    echo '<p>The event: ' . $event_name . ' could not be updated due to an error.</p>';
                                } else {
                                    echo '<p>The event: ' . $event_name . ' has been updated.</p>';
                                }
                                //if the event was updated and there are files to add, show the completion message
                                if ($eventUpdated && $updateEventMedia) {
                                    //check if the logo and banner were updated
                                    $haslogo = $eventMedia->getEventLogo($event_id);
                                    $hasbanner = $eventMedia->getEventBanner($event_id);

                                    if ($haslogo && $hasbanner) {
                                        echo '<p>The event logo and banner have been updated.</p>';
                                    } else if ($haslogo) {
                                        echo '<p>The event logo has been updated.</p>';
                                    } else if ($hasbanner) {
                                        echo '<p>The event banner has been updated.</p>';
                                    } else {
                                        echo '<p>The event logo and/or banner could not be updated.</p>';
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <!-- show back buttons -->
                        <div class="col-md-12">
                            <div class="card-buttons">
                                <?php
                                if ($action == 'edit') {
                                    if ($eventUpdated) {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=events&event=list" class="btn btn-primary">Return to Event List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=events&event=single&id=' . $event_id . '" class="btn btn-secondary">Go to Event</a></span>';
                                    } else {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=events&event=list" class="btn btn-primary">Return to Event List</a></span>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
