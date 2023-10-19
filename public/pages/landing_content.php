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

// Define variables and initialize with empty values
$student_firstName = $student_lastName = $student_email = $student_degree = $student_major = $student_school = $student_graduationDate = $student_jobPosition = $student_areaOfInterest = "";
$student_firstName_error = $student_lastName_error = $student_email_error = $student_degree_error = $student_major_error = $student_school_error = $student_graduationDate_error = $student_jobPosition_error = $student_areaOfInterest_error = "";
$entry_error = false;

/**
 * Get the degree levels list from the database
 */
//initialize the degree class
$degrees = new Degree();
//get the degree levels list
$degree_list = $degrees->getAllGrades();
//for each item, set the id as the value and the name as the label
foreach ($degree_list as $key => $value) {
    $degree_list[$key] = $value['name'];
}
//sort the degree levels list alphabetically by name
sort($degree_list);

/**
 * Get the schools list from the database
 */
//initialize the school class
$school = new School();
//get the schools list
$schools_list = $school->getSchools();
//for each item, set the id as the value and the name as the label
foreach ($schools_list as $key => $value) {
    $schools_list[$key] = $value['name'];
}
//sort the schools list alphabetically
sort($schools_list);

/**
 * Get the majors list from the database
 */
//get the majors list
$majors_list = $degrees->getAllMajors();
//for each item, set the id as the value and the name as the label
foreach ($majors_list as $key => $value) {
    $majors_list[$key] = $value['name'];
}
//sort the majors list alphabetically
sort($majors_list);

/**
 * Get the job positions list from the database
 */
//initialize the job class
$job = new Job();
//get the job positions list
$job_list = $job->getAllJobs();
//for each item, set the id as the value and the name as the label
foreach ($job_list as $key => $value) {
    $job_list[$key] = $value['name'];
}
//sort the job positions list by most recent
rsort($job_list);

/**
 * Get the areas of interest list from the database
 */
//initialize the area of interest class
$areaOfInterest = new AreaOfInterest();
//get the areas of interest list
$areaOfInterest_list = $areaOfInterest->getAllSubjects();
//for each item, set the id as the value and the name as the label
foreach ($areaOfInterest_list as $key => $value) {
    $areaOfInterest_list[$key] = $value['name'];
}
//sort the areas of interest list alphabetically
sort($areaOfInterest_list);

/**
 * Setup the position type list
 * this is done with enums in the database, so no table to pull from.
 */
//setup an array of the position types, each item will have a value and a label
$positionType_list = array(
    array("value" => "FULL", "label" => "Full Time"),
    array("value" => "PART", "label" => "Part Time"),
    array("value" => "INTERN", "label" => "Internship")
);

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
            if (strtotime(trim($_POST["student_graduationDate"])) > date("Y-m-d")) {
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
    if ($entry_error && !empty($student_firstName) && !empty($student_lastName) && !empty($student_email) && !empty($student_degree) && !empty($student_major) && !empty($student_school) && !empty($student_graduationDate) && !empty($student_jobPosition) && !empty($student_areaOfInterest)) {
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
        $student_major = (int)$student_major;
        //prepare the student_school
        $student_school = prepareData($student_school);
        //prepare the student_graduationDate
        $student_graduationDate = prepareData($student_graduationDate);
        //prepare the student_jobPosition
        $student_jobPosition = prepareData($student_jobPosition);
        //prepare the student_areaOfInterest
        $student_areaOfInterest = prepareData($student_areaOfInterest);
        $student_areaOfInterest = (int)$student_areaOfInterest;
        //prepare the student_graduationDate
        $student_graduationDate = prepareData($student_graduationDate);

        //create the student
        $student->addStudent($student_firstName, $student_lastName, $student_email, $student_degree, $student_major, $student_school, $student_graduationDate, $student_jobPosition, 0, $student_areaOfInterest);

        //redirect to the thank you page
        header("location: thank_you.php");
    }
}

/* Setup HTML for page body */
?>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Welcome to the College Recruitment Application</h1>
                <p>Students will be able to enter their information and have it sent to the database.</p>
                <p>Uses the student class to create a new student object</p>
                <p>Author: Patrick Barnhardt</p>
                <p>Author Email: pbarnh1@wgu.edu</p>
            </div>
        </div>
    </div>
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
                        <input type="text" name="student_firstName" id="student_firstName" class="form-control" value="<?php echo $student_firstName; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="student_lastName">Last Name:<span class="text-danger">*</span></label>
                        <input type="text" name="student_lastName" id="student_lastName" class="form-control" value="<?php echo $student_lastName; ?>">
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
                        <input type="text" name="student_email" id="student_email" class="form-control" value="<?php echo $student_email; ?>">
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
                        <select name="student_degree" id="student_degree" class="form-control">
                            <option value="">Please select your degree</option>
                            <?php
                            //loop through the degree levels list
                            foreach ($degree_list as $degree) {
                                //check if the degree level matches the student's degree level
                                if ($student_degree == $degree) {
                                    //if it matches, set the selected attribute
                                    echo '<option value="' . $degree . '" selected>' . $degree . '</option>';
                                } else {
                                    //if it doesn't match, don't set the selected attribute
                                    echo '<option value="' . $degree . '">' . $degree . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="student_major">Please select or enter your major:<span class="text-danger">*</span></label>
                        <input type="text" name="student_major" id="student_major" class="form-control" list="student_major_data">
                        <datalist id="student_major_data">
                            <?php
                            //loop through the majors list
                            foreach ($majors_list as $major) {
                                //check if the major matches the student's major
                                if ($student_major == $major) {
                                    //if it matches, set the selected attribute
                                    echo '<option value="' . $major . '" selected>' . $major . '</option>';
                                } else {
                                    //if it doesn't match, don't set the selected attribute
                                    echo '<option value="' . $major . '">' . $major . '</option>';
                                }
                            }
                            ?>
                        </datalist>
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
                        <select name="student_school" id="student_school" class="form-control">
                            <option value="">Please select your school</option>
                            <?php
                            //loop through the schools list
                            foreach ($schools_list as $school) {
                                //check if the school matches the student's school
                                if ($student_school == $school) {
                                    //if it matches, set the selected attribute
                                    echo '<option value="' . $school . '" selected>' . $school . '</option>';
                                } else {
                                    //if it doesn't match, don't set the selected attribute
                                    echo '<option value="' . $school . '">' . $school . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="student_graduationDate"> Expected Graduation Date:<span class="text-danger">*</span></label>
                        <input type="date" name="student_graduationDate" id="student_graduationDate" class="form-control" min="<?php echo date("Y-m-d") ?>" value="<?php echo $student_graduationDate; ?>">
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
                        <select name="student_jobPosition" id="student_jobPosition" class="form-control">
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
                        <select name="student_areaOfInterest" id="student_areaOfInterest" class="form-control">
                            <option value="">Please select your field of interest</option>
                            <?php
                            //loop through the areas of interest list
                            foreach ($areaOfInterest_list as $areaOfInterest) {
                                //check if the area of interest matches the student's area of interest
                                if ($student_areaOfInterest == $areaOfInterest) {
                                    //if it matches, set the selected attribute
                                    echo '<option value="' . $areaOfInterest . '" selected>' . $areaOfInterest . '</option>';
                                } else {
                                    //if it doesn't match, don't set the selected attribute
                                    echo '<option value="' . $areaOfInterest . '">' . $areaOfInterest . '</option>';
                                }
                            }
                            ?>
                        </select>
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