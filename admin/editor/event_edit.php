<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//event class
$event = new Event();

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

//student class
$student = new Student();

//get the action from the url parameter
$action = $_GET['action'];

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
    $event_name = trim($_POST["event_name"]);
    //prepare the event name
    $event_name = prepareData($event_name);
    //get the event date from the form
    $event_date = trim($_POST["event_date"]);
    //prepare the event date
    $event_date = prepareData($event_date);
    //get the event location from the form
    $event_location = trim($_POST["event_school"]);
    //prepare the student_school
    $event_location = prepareData($event_location);
    $event_location = (int)$event_location;
    //get the event logo from the form
    $event_logo = $_FILES["event_logo"];
    //get the event banner from the form
    $event_banner = $_FILES["event_banner"];

    //if the event logo is empty, set the event logo to null
    if (empty($event_logo)) {
        $event_logo = null;
    }

    //if the event banner is empty, set the event banner to null
    if (empty($event_banner)) {
        $event_banner = null;
    }

    //if there are files to upload, upload them
    if (!empty($event_logo) || !empty($event_banner)) {
        //Php upload script based loosely on https://www.w3schools.com/php/php_file_upload.asp
        $target_dir = dirname(__FILE__) . '/../../public/content/uploads/';
        //get the file names if they are not empty or null
        if (!empty($event_logo)) {
            $event_logo_file = basename($_FILES["event_logo"]["name"]);
            //log the file name
            error_log('File name: ' . $event_logo_file);
        }
        if (!empty($event_banner)) {
            $event_banner_file = basename($_FILES["event_banner"]["name"]);
            //log the file name
            error_log('File name: ' . $event_banner_file);
        }
        //set the target file paths
        if (!empty($event_logo_file)) {
            $target_file_logo = $target_dir . $event_logo_file;
            //log the target file path
            error_log('Target file: ' . $target_file_logo);
        }
        if (!empty($event_banner_file)) {
            $target_file_banner = $target_dir . $event_banner_file;
            //log the target file path
            error_log('Target file: ' . $target_file_banner);
        }
        //upload status booleans
        $uploadOk_logo = 1;
        $uploadOk_banner = 1;
        //if the logo target file is not empty, setup the type and size checks
        if (!empty($target_file_logo)) {
            $imageFileType_logo = strtolower(pathinfo($target_file_logo, PATHINFO_EXTENSION));
            $check_logo = getimagesize($_FILES["event_logo"]["tmp_name"]);
            if ($check_logo === false) {
                $event_logo = null;
                $uploadOk_logo = 0;
            } else {
                $uploadOk_logo = 1;
            }
        }
        //if the banner target file is not empty, setup the type and size checks
        if (!empty($target_file_banner)) {
            $imageFileType_banner = strtolower(pathinfo($target_file_banner, PATHINFO_EXTENSION));
            $check_banner = getimagesize($_FILES["event_banner"]["tmp_name"]);
            if ($check_banner === false) {
                $event_banner = null;
                $uploadOk_banner = 0;
            } else {
                $uploadOk_banner = 1;
            }
        }

        // Check if file already exists
        if (file_exists($target_file_logo)) {
            $event_logo = null;
            $uploadOk_logo = 0;
        }
        if (file_exists($target_file_banner)) {
            $event_banner = null;
            $uploadOk_banner = 0;
        }

        // Check file size
        if ($_FILES["event_logo"]["size"] > 500000) { //500kb
            $event_logo = null;
            $uploadOk_logo = 0;
        }
        if ($_FILES["event_banner"]["size"] > 500000) { //500kb
            $event_banner = null;
            $uploadOk_banner = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType_logo != "jpg" && $imageFileType_logo != "png" && $imageFileType_logo != "jpeg"
            && $imageFileType_banner != "jpg" && $imageFileType_banner != "png" && $imageFileType_banner != "jpeg"
        ) {
            $event_logo = null;
            $event_banner = null;
            $uploadOk_logo = 0;
            $uploadOk_banner = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk_logo == 0) {
            $event_logo = null;
            // if everything is ok, try to upload file
        } else {
            if (!empty($target_file_logo)) {
                if (move_uploaded_file($_FILES["event_logo"]["tmp_name"], $target_file_logo)) {
                    $event_logo = $event_logo_file;
                } else {
                    $event_logo = null;
                }
            }
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk_banner == 0) {
            $event_banner = null;
            // if everything is ok, try to upload file
        } else {
            if (!empty($target_file_banner)) {
                if (move_uploaded_file($_FILES["event_banner"]["tmp_name"], $target_file_banner)) {
                    $event_banner = $event_banner_file;
                } else {
                    $event_banner = null;
                }
            }
        }

        //check if the event had an existing logo or banner, if so, update the record
        if ($action == 'edit') {
            //if neither the logo or banner are empty, update the event logo and banner
            if (!empty($event_logo) && !empty($event_banner)) {
                $existing_logo = $event->getEventLogo($event_id);
                $existing_banner = $event->getEventBanner($event_id);
                //if the existing logo and banner are not empty, see if the event_ids match
                if (!empty($existing_logo) || $existing_logo != '' || $existing_logo != null) {
                    //if the event_ids match, update the logo and banner
                    if ($existing_logo == $event_id) {
                        $event->updateEventLogoAndBanner($event_id, $event_logo, $event_banner);
                    } else {
                        //if the event_ids don't match, run them individually - this should never happen
                        $event->updateEventLogo($event_id, $event_logo);
                        $event->updateEventBanner($event_id, $event_banner);
                    }
                } else {
                    //if the existing logo and banner are empty, set the logo and banner
                    $event->setEventLogoAndBanner($event_id, $event_logo, $event_banner);
                }
            } else if (!empty($event_logo)) {
                $existing_logo = $event->getEventLogo($event_id);
                if (!empty($existing_logo) || $existing_logo != '' || $existing_logo != null) {
                    $event->updateEventLogo($event_id, $event_logo);
                } else {
                    $event->setEventLogo($event_id, $event_logo);
                }
            } else if (!empty($event_banner)) {
                $existing_banner = $event->getEventBanner($event_id);
                if (!empty($existing_banner) || $existing_banner != '' || $existing_banner != null) {
                    $event->updateEventBanner($event_id, $event_banner);
                } else {
                    $event->setEventBanner($event_id, $event_banner);
                }
            }
        }
    }

    //if the action is edit, update the event
    if ($action == 'edit') {
        //get current user ID
        $user_id = $_SESSION['user_id'];
        //update the event
        $event->updateEvent($event_id, $event_name, $event_date, $event_location, $user_id);
        //sluggify the event name
        $event_slug = toSlug($event_name);
        //update the event slug
        $event->updateEventSlug($event_id, $event_slug);
        //redirect to the event list
        performRedirect('/admin/dashboard.php?view=events&event=list');
        exit();
    } else if ($action == 'create') {
        //get current user ID
        $user_id = $_SESSION['user_id'];
        //create the event
        $eventCreated = $event->createEvent($event_name, $event_date, $event_location, $user_id);
        //if the event was created, get the event id
        if ($eventCreated) {
            $event_id = $event->getEventIdByName($event_name);
            //sluggify the event name
            $event_slug = toSlug($event_name);
            //update the event slug
            $event->updateEventSlug($event_id, $event_slug);
        }
        //redirect to the event list
        performRedirect('/admin/dashboard.php?view=events&event=list');
        exit();
    }
}

//if the action is edit, show the event edit form
if ($action == 'edit') { ?>
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?php echo $event->getEventName($event_id); ?></h1>
        <div class="row">
            <div class="card mb-4">
                <!-- Edit Form -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&event=' . $_GET['event'] . '&action=' . $_GET['action'] . '&id=' . $_GET['id']; ?>" method="post" enctype="multipart/form-data">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa-solid fa-calendar-day"></i>
                            Edit Event
                        </div>
                        <div class="card-buttons">
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=list'; ?>" class="btn btn-primary btn-sm">Back to Events</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><label for="eventName">Event Name:</label></strong></p>
                                <p><input type="text" id="eventName" name="event_name" class="form-control" value="<?php echo $event->getEventName($event_id); ?>" placeholder="<?php echo $event->getEventName($event_id); ?>" required></p>
                                <p><strong><label for="eventDate">Event Date:</label></strong></p>
                                <p><input type="date" id="eventDate" name="event_date" class="form-control" value="<?php echo $event->getEventDate($event_id); ?>" placeholder="<?php echo $event->getEventDate($event_id); ?>"></p>
                                <p><strong><label for="eventLocation">Event Location:</label></strong></p>
                                <div id="schoolParent" class="col-md-12 school-dropdown">
                                    <select name="event_school" id="eventLocation" class="select2 select2-school form-control app-forms" style="width: 100%;">
                                        <?php
                                        //loop through the schools list
                                        foreach ($schools_list as $school => $value) {
                                            //get the key and value from the array and set the variables
                                            $school_id = (string)$value['value'];
                                            $school_label = (string)$value['label'];
                                            //check if the school matches the location of the event, if so, set the selected attribute, if not compare to the student's school
                                            if ($action == 'edit') {
                                                if ($school_label == $event_location) {
                                                    //if it matches, set the selected attribute
                                                    echo '<option value="' . $school_id . '" selected>' . $school_label . '</option>';
                                                } else if ($school_label == $event_location) {
                                                    //if it matches, set the selected attribute
                                                    echo '<option value="' . $school_id . '" selected>' . $school_label . '</option>';
                                                } else {
                                                    //if it doesn't match, don't set the selected attribute
                                                    echo '<option value="' . $school_id . '">' . $school_label . '</option>';
                                                }
                                            } else {
                                                if ($school_label == $event_location) {
                                                    //if it matches, set the selected attribute
                                                    echo '<option value="' . $school_id . '" selected>' . $school_label . '</option>';
                                                } else {
                                                    //if it doesn't match, don't set the selected attribute
                                                    echo '<option value="' . $school_id . '">' . $school_label . '</option>';
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Event Branding (optional) -->
                                <h4>Event Branding</h4>
                                <p>
                                    <strong><label for="eventLogo">Event Logo:</label></strong>
                                    <!-- if there is an existing logo, show the file -->
                                    <?php
                                    if (!empty($event->getEventLogo($event_id))) {
                                        //render the file as an image
                                        echo '<div><img src="' . APP_URL . '/public/content/uploads/' . $event->getEventLogo($event_id) . '" alt="Event Logo" style="max-width: 200px; max-height: auto;"></div>';
                                        //show the file name
                                        echo '<div> ' . $event->getEventLogo($event_id) . '</div>';
                                    }
                                    ?>
                                </p>
                                <p><input type="file" id="eventLogo" name="event_logo" class="form-control"></p>
                                <p>
                                    <strong><label for="eventBanner">Event Banner:</label></strong>
                                    <!-- if there is an existing banner, show the file -->
                                    <?php
                                    if (!empty($event->getEventBanner($event_id))) {
                                        //render the file as an image
                                        echo '<div><img src="' . APP_URL . '/public/content/uploads/' . $event->getEventBanner($event_id) . '" alt="Event Banner" style="max-width: 200px; max-height: auto;"></div>';
                                        //show the file name
                                        echo '<div> ' . $event->getEventBanner($event_id) . '</div>';
                                    }
                                    ?>
                                </p>
                                <p><input type="file" id="eventBanner" name="event_banner" class="form-control"></p>
                            </div>
                        </div>
                    </div>
                    <div class=" card-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=list'; ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    <?php } ?>
