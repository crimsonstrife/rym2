<?php

/**
 * This is the content for the student landing page of the College Recruitment Application
 * Students will be able to enter their information and have it sent to the database.
 * Uses the student class to create a new student object
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/18/2023
 *
 * @package RYM2
 * Filename: landing_content.php
 * @version 1.0.0
 * @requires PHP 7.2.5+
 * @requires Bootstrap 5.3.2+
 * @requires Font Awesome 6.4.2+
 * @requires jQuery 3.7.1+
 */

//include the header
include_once('header.php');

// instance the event class
$event = new Event();

// instance the contact class
$contact = new Contact();

// instance of the school class
$school = new School();

//check the event slug
if (isset($event_slug)) {
    //get the event by the slug
    $this_event = $event->getEventBySlug($event_slug);
} else {
    $this_event = null;
}

//if the event is set, get the event styling data
if (isset($this_event)) {
    $event_brandingColor = $school->getSchoolColor(intval($this_event['location']));
} else {
    $event_brandingColor = null;
}

//if the event color is set and not null, echo the style tag
if (isset($event_brandingColor) && !empty($event_brandingColor) && !is_null($event_brandingColor)) {
    echo '<style>';
    echo '.schoolBrandedNav {'; //style the nav bar
    echo 'background-color: ' . $event_brandingColor . ' !important;';
    echo '}';
    echo '</style>';
}

//get the event variables if the event is set
if (isset($this_event)) {
    $isEventPage = true;
    $event_id = $this_event['id'];
    $event_name = $this_event['name'];
    $event_date = $this_event['event_date'];
    $event_location_id = $this_event['location'];
    $event_created_at = $this_event['created_at'];
    $event_updated_at = $this_event['updated_at'];
} else {
    //if no event is set, set the variables to null
    $isEventPage = false;
    $event_id = null;
    $event_name = null;
    $event_date = null;
    $event_location_id = null;
    $event_created_at = null;
    $event_updated_at = null;
    $event_location_id = null;
}

// Define variables and initialize with empty values
$student_firstName = $student_lastName = $student_email = $student_phone = $student_address = $student_city = $student_state = $student_zip = $student_degree = $student_major = $student_school = $student_graduationDate = $student_jobPosition = $student_areaOfInterest = "";
$student_firstName_error = $student_lastName_error = $student_email_error = $student_phone_error = $student_address_error = $student_city_error = $student_state_error = $student_zip_error = $student_degree_error = $student_major_error = $student_school_error = $student_graduationDate_error = $student_jobPosition_error = $student_areaOfInterest_error = "";
$entry_error = false;

/* create a new student object */
$student = new Student();

//create an array of states
$stateArray = STATES;

/**
 * Get the degree levels list from the database
 */
//initialize the degree class
$degrees = new Degree();
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
//initialize the school class
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

//get the event location if this is an event page
if ($isEventPage) {
    $event_location = $school->getSchoolName($event_location_id);
} else {
    //if this is not an event page, set the event location to null
    $event_location = null;
}

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
 * Get the job positions list from the database
 */
//initialize the job class
$job = new Job();
//get the job positions list
$job_list = $job->getAllJobs();
//for each item, set the id as the value and the name as the label
foreach ($job_list as $key => $value) {
    //add an item to the array
    $job_list[$key] = $arrayName = array(
        "value" => (string)$value['id'],
        "label" => (string)$value['name'],
        "date" => (string)$value['created_at']
    );
}
//sort the job positions list by most recent created
array_multisort(array_column($job_list, 'date'), SORT_DESC, $job_list);

/**
 * Get the areas of interest list from the database
 */
//initialize the area of interest class
$areaOfInterest = new AreaOfInterest();
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

/**
 * Setup the position type list
 * this is done with enums in the database, so no table to pull from.
 */
//setup an array of the position types, each item will have a value and a label
$positionType_list = JOBTYPES;

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if student_firstName is empty
    if (empty(trim($_POST["student_firstName"]))) {
        $student_firstName_error = "Please enter your first name.";
        $entry_error = true;
    } else {
        $student_firstName = trim($_POST["student_firstName"]);
    }
    // Check if student_lastName is empty
    if (empty(trim($_POST["student_lastName"]))) {
        $student_lastName_error = "Please enter your last name.";
        $entry_error = true;
    } else {
        $student_lastName = trim($_POST["student_lastName"]);
    }
    // Check if student_email is empty
    if (empty(trim($_POST["student_email"]))) {
        $student_email_error = "Please enter your email.";
        $entry_error = true;
    } else {
        //validate the email address
        if (validateEmail(trim($_POST["student_email"]))) {
            $student_email = trim($_POST["student_email"]);
        } else {
            $student_email_error = "Please enter a valid email address.";
            $entry_error = true;
        }
    }
    // Check if student_phone is empty
    if (empty(trim($_POST["student_phone"]))) {
        $student_phone = "";
    } else {
        //validate the phone number
        if (validatePhone(trim($_POST["student_phone"]))) {
            $student_phone = trim($_POST["student_phone"]);
        } else {
            $student_phone_error = "Please enter a valid phone number.";
            $entry_error = true;
        }
    }
    // Check if student_address is empty
    if (empty(trim($_POST["student_address"]))) {
        $student_address = "";
    } else {
        $student_address = trim($_POST["student_address"]);
    }
    // Check if student_city is empty
    if (empty(trim($_POST["student_city"]))) {
        $student_city = "";
    } else {
        $student_city = trim($_POST["student_city"]);
    }
    // Check if student_state is empty
    if (empty(trim($_POST["student_state"]))) {
        $student_state = "";
    } else {
        $student_state = trim($_POST["student_state"]);
    }
    // Check if student_zip is empty
    if (empty(trim($_POST["student_zip"]))) {
        $student_zip = "";
    } else {
        //validate the zip code
        if (validateZip(trim($_POST["student_zip"]))) {
            $student_zip = trim($_POST["student_zip"]);
        } else {
            $student_zip_error = "Please enter a valid zip code.";
            $entry_error = true;
        }
    }
    // Check if student_degree is empty
    if (empty(trim($_POST["student_degree"]))) {
        $student_degree_error = "Please select your degree.";
        $entry_error = true;
    } else {
        $student_degree = trim($_POST["student_degree"]);
    }
    // Check if student_major is empty
    if (empty(trim($_POST["student_major"]))) {
        $student_major_error = "Please enter your major.";
        $entry_error = true;
    } else {
        $student_major = trim($_POST["student_major"]);
    }
    // Check if student_school is empty
    if (empty(trim($_POST["student_school"]))) {
        $student_school_error = "Please select your school.";
        $entry_error = true;
    } else {
        $student_school = trim($_POST["student_school"]);
    }
    // Check if student_graduationDate is empty
    if (empty(trim($_POST["student_graduationDate"]))) {
        $student_graduationDate_error = "Please enter your graduation date.";
        $entry_error = true;
    } else {
        //validate the date
        if (validateDate(trim($_POST["student_graduationDate"]))) {
            //check that the date is in the future
            if (strtotime(trim($_POST["student_graduationDate"])) > strtotime(date("Y-m-d"))) {
                $student_graduationDate = trim($_POST["student_graduationDate"]);
            } else {
                $student_graduationDate_error = "Please enter a date in the future.";
                $entry_error = true;
            }
        } else {
            $student_graduationDate_error = "Please enter a valid date.";
            $entry_error = true;
        }
    }
    // Check if student_jobPosition is empty
    if (empty(trim($_POST["student_jobPosition"]))) {
        $student_jobPosition_error = "Please select your preferred job type.";
        $entry_error = true;
    } else {
        $student_jobPosition = trim($_POST["student_jobPosition"]);
    }
    // Check if student_areaOfInterest is empty
    if (empty(trim($_POST["student_areaOfInterest"]))) {
        $student_areaOfInterest_error = "Please select your area of interest.";
        $entry_error = true;
    } else {
        $student_areaOfInterest = trim($_POST["student_areaOfInterest"]);
    }
    //check if there were any errors, and that the fields are not empty
    if ($entry_error == false && !empty($student_firstName) && !empty($student_lastName) && !empty($student_email) && !empty($student_phone) && !empty($student_address) && !empty($student_city) && !empty($student_state) && !empty($student_zip) && !empty($student_degree) && !empty($student_major) && !empty($student_school) && !empty($student_graduationDate) && !empty($student_jobPosition) && !empty($student_areaOfInterest)) {
        //initialize the student class
        $student = new Student();
        //prepare the student_firstName
        $student_firstName = prepareData($student_firstName);
        //prepare the student_lastName
        $student_lastName = prepareData($student_lastName);
        //prepare the student_email
        $student_email = prepareData($student_email);
        //prepare the student_phone
        $student_phone = prepareData($student_phone);
        //prepare the student_address
        $student_address = prepareData($student_address);
        //prepare the student_city
        $student_city = prepareData($student_city);
        //prepare the student_state
        $student_state = prepareData($student_state);
        //prepare the student_zip
        $student_zip = prepareData($student_zip);
        //prepare the student_degree
        $student_degree = prepareData($student_degree);
        $student_degree = (int)$student_degree;
        //prepare the student_major
        $student_major = prepareData($student_major);
        //check if the major is in the database, if not add it
        if (!$degrees->getMajorByName($student_major)) {
            $degrees->addMajor($student_major, 0);
            //once the major is added, get the id, as a string
            $student_major = (string)$degrees->getMajorIdByName($student_major);
            $student_major = (int)$student_major;
        } else {
            //if the major is in the database, get the id, as a string
            $student_major = (string)$degrees->getMajorIdByName($student_major);
            $student_major = (int)$student_major;
        }
        //prepare the student_school
        $student_school = prepareData($student_school);
        $student_school = (int)$student_school;
        //prepare the student_graduationDate
        $student_graduationDate = prepareData($student_graduationDate);
        //prepare the student_jobPosition
        $student_jobPosition = prepareData($student_jobPosition);
        //prepare the student_areaOfInterest
        $student_areaOfInterest = prepareData($student_areaOfInterest);
        $student_areaOfInterest = (int)$student_areaOfInterest;
        //prepare the student_graduationDate
        $student_graduationDate = prepareData($student_graduationDate);

        //check for an event slug
        if (isset($_POST['event'])) {
            $event_slug = $_POST['event'];
        }

        //if there is an event slug
        if (isset($event_slug)) {
            //get the event by the slug
            $this_event = $event->getEventBySlug($event_slug);
            //get the event variables
            $event_id = $this_event['id'];
            //set the event page variable to true
            $isEventPage = true;
        }

        //if this is an event page, set the event id
        if ($isEventPage) {
            $student_event_id = $event_id;
            $student_event_id = (int) $student_event_id;
        } else {
            //if this is not an event page, set the event id to null
            $student_event_id = NULL;
        }

        //add the student, check if the add was successful
        if ($student->addStudent($student_firstName, $student_lastName, $student_email, $student_phone, $student_address, $student_city, $student_state, $student_zip, $student_degree, $student_major, $student_school, $student_graduationDate, $student_jobPosition, $student_areaOfInterest)) {
            //if the add was successful, get the student id
            $studentArray = $student->getStudentByEmail($student_email);
            $student_id = $studentArray['id'];

            //if this is an event page, add the student to the event
            if ($isEventPage) {
                $student_event_id = (int) $event_id;
                $student_id = (int) $student_id;
                //initialize the student class
                $studentObject = new Student();
                //add the student to the event
                if ($studentObject->addStudentToEvent($student_event_id, $student_id)) {
                    //log the result
                } else {
                    //log the result
                }
            }

            //display a success message
            echo '<div class="container"> <div class="row"> <div class="col-md-12"> <div class="alert alert-success"> Student added successfully. </div> </div> </div> </div>';

            //Setup the email
            $to = $student_email;
            $student_name = $student_firstName . " " . $student_lastName;
            $subject = "Thank you " . $student_firstName . ", for registering!";
            $message = "Thank you for registering for the College Recruitment Program. We will be in touch with you soon."; // TODO: add more information to the email

            //send the email
            $contact->sendAutoEmail($to, $student_name, $subject, $message);

            //if this is an event page, redirect to the event page
            if ($isEventPage) {
                header("location: index.php?event=" . $event_slug);
            } else {
                //if this is not an event page, redirect to the home page
                header("location: index.php");
            }
        } else {
            //if the add was not successful, display an error message
            echo '<div class="container"> <div class="row"> <div class="col-md-12"> <div class="alert alert-danger"> There was an error adding the student. </div> </div> </div> </div>';
        }
    }
}

/* Setup HTML for page body */
?>

<div id="layout">
    <!-- main content -->
    <main>
        <div id="layout_content" class="nav-less">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1>Welcome to the <?php if (!$event_location == null) {
                                                echo $event_location;
                                            } else {
                                                echo "College";
                                            } ?> Recruitment Application</h1>
                        <p>Students will be able to enter their information and have it sent to the database.</p>
                        <p>Uses the student class to create a new student object</p>
                        <p>Author: Patrick Barnhardt</p>
                        <p>Author Email: pbarnh1@wgu.edu</p>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <!-- List of available jobs -->
                    <div class="col-md-12">
                        <h2>Available Jobs</h2>
                        <p>Below is a list of available jobs.</p>
                        <p>Click on a job to view more information.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        /* check if there are any jobs in the list, if so show a scrollable table, if not show a message and an empty table */
                        if (count($job_list) > 0) {
                            //initialize the job class
                            $jobObject = new Job();
                        ?>
                            <div>
                                <table id="dataTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope=" col">Job Title</th>
                                            <th scope="col">Job Description</th>
                                            <th scope="col">Job Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($job_list as $job) {
                                            //get the job type
                                            $type = $jobObject->getJobType($job['value']);
                                            //get the job description
                                            $description = $jobObject->getJobSummary($job['value']);
                                        ?>
                                            <tr>
                                                <td><a href="<?php echo APP_URL . '/index.php?path=job'; ?>&id=<?php echo $job['value']; ?>"><?php echo $job['label']; ?></a>
                                                </td>
                                                <td><?php echo $description; ?></td>
                                                <td><?php echo $type; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-info">
                                There are no jobs available at this time. But you can still register below.
                            </div>
                            <div class="table-responsive-md">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Job Title</th>
                                            <th scope="col">Job Description</th>
                                            <th scope="col">Job Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php } ?>
                            </div>
                    </div>
                </div>
                <!-- Registration Form -->
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 id="registrationForm">Student Registration</h2>
                            <p>Please fill in your information to register.</p>
                            <p>You will be contacted after the event.</p>
                            <p><span class="text-danger">* Required field.</span></p>
                        </div>
                    </div>
                </div>
                <?php if ($entry_error) { ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger">
                                    Please correct the errors below and try again.
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>#registrationForm" class="needs-validation <?php if ($entry_error) {
                                                                                                                                                echo 'was-validated';
                                                                                                                                            } ?>" method="post" novalidate>
                                <!-- hidden field for event id or other parameters -->
                                <?php
                                $keys = array('event');
                                foreach ($keys as $name) {
                                    if (!isset($_GET[$name])) {
                                        continue;
                                    }
                                    $value = htmlspecialchars($_GET[$name]);
                                    $name = htmlspecialchars($name);
                                    echo '<input type="hidden" name="' . $name . '" value="' . $value . '">';
                                } ?>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="student_firstName">First Name:<span class="text-danger">*</span></label>
                                        <input type="text" name="student_firstName" id="student_firstName" class="form-control" placeholder="First Name" value="<?php echo $student_firstName; ?>" required>
                                        <!-- errors for name -->
                                        <div class="invalid-feedback">
                                            <span class="text-danger">
                                                <?php echo $student_firstName_error; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="student_lastName">Last Name:<span class="text-danger">*</span></label>
                                        <input type="text" name="student_lastName" id="student_lastName" class="form-control" placeholder="Last Name" value="<?php echo $student_lastName; ?>" required>
                                        <!-- errors for name -->
                                        <div class="invalid-feedback">
                                            <span class="text-danger"><?php echo $student_lastName_error; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="student_email">Email:<span class="text-danger">*</span></label>
                                        <input type="text" name="student_email" id="student_email" class="form-control" placeholder="Email" value="<?php echo $student_email; ?>" required>
                                        <!-- errors for email -->
                                        <div class="invalid-feedback">
                                            <span class="text-danger"><?php echo $student_email_error; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="student_phone">Phone:<span class="text-danger">*</span></label>
                                        <input type="text" name="student_phone" id="student_phone" class="form-control" placeholder="Phone Number" value="<?php echo $student_phone; ?>" required>
                                        <!-- errors for phone -->
                                        <div class="invalid-feedback">
                                            <span class="text-danger"><?php echo $student_phone_error; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-1">
                                        <label for="student_address">Address:<span class="text-danger">*</span></label>
                                        <input type="text" name="student_address" id="student_address" class="form-control" placeholder="Street" value="<?php echo $student_address; ?>" required>
                                        <!-- errors for address -->
                                        <div class="invalid-feedback">
                                            <span class="text-danger"><?php echo $student_address_error; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="student_city">City:<span class="text-danger">*</span></label>
                                        <input type="text" name="student_city" id="student_city" class="form-control" placeholder="City" value="<?php echo $student_city; ?>" required>
                                        <!-- errors for city -->
                                        <div class="invalid-feedback">
                                            <span class="text-danger"><?php echo $student_city_error; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="student_state">State:<span class="text-danger">*</span></label>
                                        <select name="student_state" id="student_state" class="form-control" style="width: 100%;" required>
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
                                        <!-- errors for state -->
                                        <div class="invalid-feedback">
                                            <span class="text-danger"><?php echo $student_state_error; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="student_zip">Zip:<span class="text-danger">*</span></label>
                                        <input type="text" name="student_zip" id="student_zip" class="form-control" placeholder="Zip" value="<?php echo $student_zip; ?>" required>
                                        <div class="invalid-feedback">
                                            <span class="text-danger"><?php echo $student_zip_error; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="student_degree">Degree:<span class="text-danger">*</span></label>
                                        <div id="degreeParent" class="col-md-12 degree-dropdown">
                                            <select name="student_degree" id="student_degree" class="form-control select2 select2-degree" style="width: 100%;" required>
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
                                            <!-- errors for degree -->
                                            <div class="invalid-feedback">
                                                <span class="text-danger"><?php echo $student_degree_error; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="student_major">Please select or enter your major:<span class="text-danger">*</span></label>
                                        <!-- Select2 dropdown, used to allow users to add custom entries alongside what is pulled -->
                                        <div id="majorsParent" class="col-md-12 majors-dropdown">
                                            <select name="student_major" id="student_major" class="form-control select2 select2-major" style="width: 100%;" required>
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
                                            <!-- errors for major -->
                                            <div class="invalid-feedback">
                                                <span class="text-danger"><?php echo $student_major_error; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="student_school">School:<span class="text-danger">*</span></label>
                                        <div id="schoolParent" class="col-md-12 school-dropdown">
                                            <select name="student_school" id="student_school" class="form-control select2 select2-school" style="width: 100%;">
                                                <?php
                                                //loop through the schools list
                                                foreach ($schools_list as $school => $value) {
                                                    //get the key and value from the array and set the variables
                                                    $school_id = (string)$value['value'];
                                                    $school_label = (string)$value['label'];
                                                    //check if the school matches the location of the event, if so, set the selected attribute, if not compare to the student's school
                                                    if ($isEventPage == true) {
                                                        if ($school_label == $event_location) {
                                                            //if it matches, set the selected attribute
                                                            echo '<option value="' . $school_id . '" selected>' . $school_label . '</option>';
                                                        } else if ($school_label == $student_school) {
                                                            //if it matches, set the selected attribute
                                                            echo '<option value="' . $school_id . '" selected>' . $school_label . '</option>';
                                                        } else {
                                                            //if it doesn't match, don't set the selected attribute
                                                            echo '<option value="' . $school_id . '">' . $school_label . '</option>';
                                                        }
                                                    } else {
                                                        if ($school_label == $student_school) {
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
                                            <!-- errors for school -->
                                            <div class="invalid-feedback">
                                                <span class="text-danger"><?php echo $student_school_error; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="student_graduationDate"> Expected Graduation Date:<span class="text-danger">*</span></label>
                                        <input type="date" name="student_graduationDate" id="student_graduationDate" class="form-control" min="<?php echo date("Y-m-d") ?>" value="<?php echo (!empty($student_graduationDate) ? $student_graduationDate : date("Y-m-d")); ?>">
                                        <div class="invalid-feedback">
                                            <span class="text-danger"><?php echo $student_graduationDate_error; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="student_jobPosition">Preferred Job Type:<span class="text-danger">*</span></label>
                                        <select name="student_jobPosition" id="student_jobPosition" class="form-control" style="width: 100%;" required>
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
                                        <!-- errors for job position -->
                                        <div class="invalid-feedback">
                                            <span class="text-danger"><?php echo $student_jobPosition_error; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="student_areaOfInterest">Field:<span class="text-danger">*</span></label>
                                        <div id="aoiParent" class="col-md-12 aoi-dropdown">
                                            <select name="student_areaOfInterest" id="student_areaOfInterest" class="form-control select2 select2-aoi" style="width: 100%;" required>
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
                                            <!-- errors for area of interest -->
                                            <div class="invalid-feedback">
                                                <span class="text-danger"><?php echo $student_areaOfInterest_error; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- empty row for spacing -->
                                <div class="row">
                                    <div class="col-md-12">
                                        &nbsp;
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="submit" class="btn btn-primary" value="Submit">
                                    </div>
                                </div>
                            </form>
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
                        </div>
                    </div>
                </div>
            </div>
    </main>
    <script type="module">
        /** import the simple-datatables module, implementation based on the demos/documentation from @fiduswriter/simple-datatables
         * from https://fiduswriter.github.io/simple-datatables/documentation/
         **/
        import {
            DataTable
        } from "<?php echo getLibraryPath() . 'simple-datatables/module.js' ?>"
        const dt = new DataTable("table", {
            scrollY: "40vh",
            scrollX: "100%",
            rowNavigation: true,
            perPageSelect: [5, 10, 15, 20, 25, 50, ["All", -1]],
            classes: {
                active: "active",
                disabled: "disabled",
                selector: "form-select",
                paginationList: "pagination",
                paginationListItem: "page-item",
                paginationListItemLink: "page-link"
            },
            columns: [{
                    select: 0,
                    sortSequence: ["desc", "asc"]
                },
                {
                    select: 1,
                    sortSequence: ["desc", "asc"]
                },
                {
                    select: 2,
                    sortSequence: ["desc", "asc"]
                }
            ],
            template: options => `<div class='${options.classes.top} '>
    ${
    options.paging && options.perPageSelect ?
        `<div class='${options.classes.dropdown} bs-bars float-left'>
            <label>
                <select class='${options.classes.selector}'></select>
            </label>
        </div>` :
        ""
}
    ${
    options.searchable ?
        `<div class='${options.classes.search} float-right search btn-group'>
            <input class='${options.classes.input} form-control search-input' placeholder='Search' type='search' title='Search within table'>
        </div>` :
        ""
}
</div>
<div class='${options.classes.container}' style='${options.scrollY.length ? ` height: ${options.scrollY}; overflow-Y: auto;` : ""} ${options.scrollX.length ? ` width: ${options.scrollX}; overflow-X: auto;` : ""}'></div>
<div class='${options.classes.bottom} '>
    ${
    options.paging ?
        `<div class='${options.classes.info}'></div>` :
        ""
}
    <nav class='${options.classes.pagination}'></nav>
</div>`,
            tableRender: (_data, table, _type) => {
                const thead = table.childNodes[0]
                thead.childNodes[0].childNodes.forEach(th => {
                    //if the th is not sortable, don't add the sortable class
                    if (th.options?.sortable === false) {
                        return
                    } else {
                        if (!th.attributes) {
                            th.attributes = {}
                        }
                        th.attributes.scope = "col"
                        const innerHeader = th.childNodes[0]
                        if (!innerHeader.attributes) {
                            innerHeader.attributes = {}
                        }
                        let innerHeaderClass = innerHeader.attributes.class ?
                            `${innerHeader.attributes.class} th-inner` : "th-inner"

                        if (innerHeader.nodeName === "a") {
                            innerHeaderClass += " sortable sortable-center both"
                            if (th.attributes.class?.includes("desc")) {
                                innerHeaderClass += " desc"
                            } else if (th.attributes.class?.includes("asc")) {
                                innerHeaderClass += " asc"
                            }
                        }
                        innerHeader.attributes.class = innerHeaderClass
                    }
                })

                return table
            }
        })
        dt.columns.add({
            data: dt.data.data.map((_row, index) => index),
            heading: "#",
            render: (_data, td, _index, _cIndex) => {
                if (!td.attributes) {
                    td.attributes = {}
                }
                td.attributes.scope = "row"
                td.nodeName = "TH"
                return td
            }
        })
        dt.columns.order([0, 1, 2])
        window.dt = dt
    </script>
    <?php
    include_once('footer.php');
    ?>
