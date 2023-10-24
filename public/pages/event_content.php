<?php

/**
 * This is the content for the event page of the College Recruitment Application
 * Students will be able to enter their information and have it sent to the database, but associated to a specific event.
 * Uses the student class to create a new student object
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/23/2023
 *
 * @package RYM2
 * Filename: event_content.php
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

//check the event slug
if (isset($event_slug)) {
    //get the event by the slug
    $this_event = $event->getEventBySlug($event_slug);
} else {
    //if no event slug is set, redirect back to the landing page
}

//get the event variables if the event is set
if (isset($this_event)) {
    $event_id = $this_event['id'];
    $event_name = $this_event['name'];
    $event_date = $this_event['event_date'];
    $event_location_id = $this_event['location'];
    $event_created_at = $this_event['created_at'];
    $event_updated_at = $this_event['updated_at'];
} else {
    //if the event is not set, redirect back to the landing page
}

// Define variables and initialize with empty values
$student_firstName = $student_lastName = $student_email = $student_degree = $student_major = $student_school = $student_graduationDate = $student_jobPosition = $student_areaOfInterest = "";
$student_firstName_error = $student_lastName_error = $student_email_error = $student_degree_error = $student_major_error = $student_school_error = $student_graduationDate_error = $student_jobPosition_error = $student_areaOfInterest_error = "";
$entry_error = false;

/* create a new student object */
$student = new Student();

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

//get the event location
$event_location = $school->getSchoolName($event_location_id);

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
    if ($entry_error == false && !empty($student_firstName) && !empty($student_lastName) && !empty($student_email) && !empty($student_degree) && !empty($student_major) && !empty($student_school) && !empty($student_graduationDate) && !empty($student_jobPosition) && !empty($student_areaOfInterest)) {
        //initialize the student class
        $student = new Student();
        //prepare the student_firstName
        $student_firstName = prepareData($student_firstName);
        //prepare the student_lastName
        $student_lastName = prepareData($student_lastName);
        //prepare the student_email
        $student_email = prepareData($student_email);
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

        //add the student, check if the add was successful
        if ($student->addStudent($student_firstName, $student_lastName, $student_email, $student_degree, $student_major, $student_school, $student_graduationDate, $student_jobPosition, 1, $student_areaOfInterest)) {
            //if the add was successful, display a success message
            echo '<div class="container"> <div class="row"> <div class="col-md-12"> <div class="alert alert-success"> Student added successfully. </div> </div> </div> </div>';

            //Setup the email
            $to = $student_email;
            $student_name = $student_firstName . " " . $student_lastName;
            $subject = "Thank you " . $student_firstName . ", for registering!";
            $message = "Thank you for registering for the College Recruitment Program. We will be in touch with you soon."; // TODO: add more information to the email

            //send the email
            sendAutoEmail($to, $student_name, $subject, $message);
        } else {
            //if the add was not successful, display an error message
            echo '<div class="container"> <div class="row"> <div class="col-md-12"> <div class="alert alert-danger"> There was an error adding the student. </div> </div> </div> </div>';
        }
    }
}

/* Setup HTML for page body */
?>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Welcome to the <?php echo $event_location ?> Recruitment Application</h1>
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
            <div class="col-md-6">
                <h2>Available Jobs</h2>
                <p>Below is a list of available jobs.</p>
                <p>Click on a job to view more information.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
                <?php
                /* check if there are any jobs in the list, if so show a scrollable table, if not show a message and an empty table */
                if (count($job_list) > 0) {
                    //initialize the job class
                    $jobObject = new Job();
                ?>
                    <div class="table-responsive-md table-wrapper">
                        <table class="table">
                            <thead class="sticky-top">
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
                                    $description = $jobObject->getJobDescription($job['value']);
                                ?>
                                    <tr>
                                        <td><a href="job.php?id=<?php echo $job['value']; ?>"><?php echo $job['label']; ?></a>
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
            <h2>Student Registration</h2>
            <p>Please fill in your information to register.</p>
            <p>You will be contacted after the event.</p>
            <p><span class="text-danger">* Required field.</span></p>
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
                <!-- Start of form -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="student_firstName">First Name:<span class="text-danger">*</span></label>
                            <input type="text" name="student_firstName" id="student_firstName" class="form-control app-forms" value="<?php echo $student_firstName; ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="student_lastName">Last Name:<span class="text-danger">*</span></label>
                            <input type="text" name="student_lastName" id="student_lastName" class="form-control app-forms" value="<?php echo $student_lastName; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <!-- errors for name -->
                        <div class="col-md-6">
                            <span class="text-danger"><?php echo $student_firstName_error; ?></span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-danger"><?php echo $student_lastName_error; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="student_email">Email:<span class="text-danger">*</span></label>
                            <input type="text" name="student_email" id="student_email" class="form-control app-forms" value="<?php echo $student_email; ?>">
                        </div>
                    </div>
                    <!-- errors for email -->
                    <div class="row">
                        <div class="col-md-12">
                            <span class="text-danger"><?php echo $student_email_error; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="student_degree">Degree:<span class="text-danger">*</span></label>
                            <div id="degreeParent" class="col-md-12 degree-dropdown">
                                <select name="student_degree" id="student_degree" class="select2 select2-degree form-control app-forms" style="width: 100%;">
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
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="student_major">Please select or enter your major:<span class="text-danger">*</span></label>
                            <!-- Select2 dropdown, used to allow users to add custom entries alongside what is pulled -->
                            <div id="majorsParent" class="col-md-12 majors-dropdown">
                                <select name="student_major" id="student_major" class="select2 select2-major form-control app-forms" style="width: 100%;">
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
                        </div>
                    </div>
                    <div class="row">
                        <!-- errors for degree and major -->
                        <div class="col-md-6">
                            <span class="text-danger"><?php echo $student_degree_error; ?></span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-danger"><?php echo $student_major_error; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="student_school">School:<span class="text-danger">*</span></label>
                            <div id="schoolParent" class="col-md-12 school-dropdown">
                                <select name="student_school" id="student_school" class="select2 select2-school form-control app-forms" style="width: 100%;">
                                    <?php
                                    //loop through the schools list
                                    foreach ($schools_list as $school => $value) {
                                        //get the key and value from the array and set the variables
                                        $school_id = (string)$value['value'];
                                        $school_label = (string)$value['label'];
                                        //check if the school matches the location of the event, if so, set the selected attribute, if not compare to the student's school
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
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="student_graduationDate"> Expected Graduation Date:<span class="text-danger">*</span></label>
                            <input type="date" name="student_graduationDate" id="student_graduationDate" class="form-control app-forms" min="<?php echo date("Y-m-d") ?>" value="<?php echo (!empty($student_graduationDate) ? $student_graduationDate : date("Y-m-d")); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <!-- errors for school and graduation date -->
                        <div class="col-md-6">
                            <span class="text-danger"><?php echo $student_school_error; ?></span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-danger"><?php echo $student_graduationDate_error; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="student_jobPosition">Preferred Job Type:<span class="text-danger">*</span></label>
                            <select name="student_jobPosition" id="student_jobPosition" class="form-control app-forms" style="width: 100%;">
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
                        </div>
                        <div class="col-md-6">
                            <label for="student_areaOfInterest">Field:<span class="text-danger">*</span></label>
                            <div id="aoiParent" class="col-md-12 aoi-dropdown">
                                <select name="student_areaOfInterest" id="student_areaOfInterest" class="select2 select2-aoi form-control app-forms" style="width: 100%;">
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
                        </div>
                    </div>
                    <div class="row">
                        <!-- errors for job position -->
                        <div class="col-md-6">
                            <span class="text-danger"><?php echo $student_jobPosition_error; ?></span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-danger"><?php echo $student_areaOfInterest_error; ?></span>
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
            </div>
        </div>
</body>
<?php
include_once('footer.php');
?>