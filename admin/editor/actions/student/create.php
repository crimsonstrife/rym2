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

//include the session class
$session = new Session();

//include the student class
$student = new Student();

//include the student education class
$studentEducation = new StudentEducation();

//include the student address class
$studentAddress = new StudentAddress();

/*confirm user has a role with create student permissions*/
//get the id of the create student permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('CREATE STUDENT');

//boolean to track if the user has the create student permission
$hasPermission = $auth->checkUserPermission(intval($session->get('user_id')), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
} else {

    //get the action from the url parameter
    $action = $_GET['action'];

    // Define variables and initialize with false values
    $student_firstName = $student_lastName = $student_email = $student_phone = $student_address = $student_city = $student_state = $student_zip = $student_degree = $student_major = $student_school = $student_graduationDate = $student_jobPosition = $student_areaOfInterest = "";
    $student_firstName_error = $student_lastName_error = $student_email_error = $student_phone_error = $student_address_error = $student_city_error = $student_state_error = $student_zip_error = $student_degree_error = $student_major_error = $student_school_error = $student_graduationDate_error = $student_jobPosition_error = $student_areaOfInterest_error = "";
    $entry_error = false;

    //other variables
    $canCreate = true;
    $emailSent = null;
    $studentCreated = false;
    $attemptedStudentSubmission = false;

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //check if the email form was what was submitted
        if (isset($_POST['send_email'])) {
            //get the student id from the form
            $student_id = intval($_POST['student_id']);
            //get the student first name from the form
            $student_firstName = $_POST['student_firstName'];
            //get the student last name from the form
            $student_lastName = $_POST['student_lastName'];
            //get the student email from the form
            $student_email = $_POST['student_email'];
            //get the user id from the form
            $user_id = intval($_POST['user_id']);

            //Setup the email
            $send_to = $student_email;
            $student_name = $student_firstName . " " . $student_lastName;
            $subject = "Thank you " . $student_firstName . ", for registering!";
            $message = "Thank you for registering for the College Recruitment Program. We will be in touch with you soon."; // TODO: add more information to the email

            //send the email
            $emailSent = $contact->sendEmail($student_id, $send_to, $student_name, $subject, $message, $user_id);
        } else {

            //set the entry error to false
            $entry_error = false;

            /* Validate and sanitize input */
            // Check if student_firstName is set
            if (isset($_POST["student_firstName"])) {
                // Check if student_firstName is empty
                if (empty(trim($_POST["student_firstName"]))) {
                    $student_firstName_error = "Please enter your first name.";
                    $entry_error = true;
                } else {
                    $student_firstName = trim($_POST["student_firstName"]);
                }
            } else {
                $student_firstName_error = "Please enter your first name.";
                $entry_error = true;
            }

            //if no entry error, set the student_name variable
            if (!$entry_error) {
                $student_name = $student_firstName;
            }

            // Check if student_lastName is set
            if (isset($_POST["student_lastName"])) {
                // Check if student_lastName is empty
                if (empty(trim($_POST["student_lastName"]))) {
                    $student_lastName_error = "Please enter your last name.";
                    $entry_error = true;
                } else {
                    $student_lastName = trim($_POST["student_lastName"]);
                }
            } else {
                $student_lastName_error = "Please enter your last name.";
                $entry_error = true;
            }

            //if no entry error, set the student_name variable to add the last name
            if (!$entry_error) {
                $student_name = $student_firstName . ' ' . $student_lastName;
            }

            // Check if student_email is set
            if (isset($_POST["student_email"])) {
                // Check if student_email is empty
                if (empty(trim($_POST["student_email"]))) {
                    $student_email_error = "Please enter your email.";
                    $entry_error = true;
                } else {
                    //validate the email
                    if (validateEmail(trim($_POST["student_email"]))) {
                        $student_email = trim($_POST["student_email"]);
                    } else {
                        $student_email_error = "Please enter a valid email.";
                        $entry_error = true;
                    }
                }
            } else {
                $student_email_error = "Please enter your email.";
                $entry_error = true;
            }

            // Check if student_phone is set
            if (isset($_POST["student_phone"])) {
                // Check if student_phone is empty
                if (empty(trim($_POST["student_phone"]))) {
                    $student_phone_error = "Please enter your phone number.";
                    $entry_error = true;
                } else {
                    //validate the phone number
                    if (validatePhone(trim($_POST["student_phone"]))) {
                        $student_phone = trim($_POST["student_phone"]);
                    } else {
                        $student_phone_error = "Please enter a valid phone number.";
                        $entry_error = true;
                    }
                }
            } else {
                $student_phone_error = "Please enter your phone number.";
                $entry_error = true;
            }

            // Check if student_address is set
            if (isset($_POST["student_address"])) {
                // Check if student_address is empty
                if (empty(trim($_POST["student_address"]))) {
                    $student_address_error = "Please enter your address.";
                    $entry_error = true;
                } else {
                    $student_address = trim($_POST["student_address"]);
                }
            } else {
                $student_address_error = "Please enter your address.";
                $entry_error = true;
            }

            // Check if student_city is set
            if (isset($_POST["student_city"])) {
                // Check if student_city is empty
                if (empty(trim($_POST["student_city"]))) {
                    $student_city_error = "Please enter your city.";
                    $entry_error = true;
                } else {
                    $student_city = trim($_POST["student_city"]);
                }
            } else {
                $student_city_error = "Please enter your city.";
                $entry_error = true;
            }

            // Check if student_state is set
            if (isset($_POST["student_state"])) {
                // Check if student_state is empty
                if (empty(trim($_POST["student_state"]))) {
                    $student_state_error = "Please select your state.";
                    $entry_error = true;
                } else {
                    $student_state = trim($_POST["student_state"]);
                }
            } else {
                $student_state_error = "Please select your state.";
                $entry_error = true;
            }

            // Check if student_zip is set
            if (isset($_POST["student_zip"])) {
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
            } else {
                $student_zip_error = "Please enter your zip code.";
                $entry_error = true;
            }

            // Check if student_degree is set
            if (isset($_POST["student_degree"])) {
                // Check if student_degree is empty
                if (empty(trim($_POST["student_degree"]))) {
                    $student_degree_error = "Please select your degree level.";
                    $entry_error = true;
                } else {
                    $student_degree = trim($_POST["student_degree"]);
                }
            } else {
                $student_degree_error = "Please select your degree level.";
                $entry_error = true;
            }

            // Check if student_major is set
            if (isset($_POST["student_major"])) {
                // Check if student_major is empty
                if (empty(trim($_POST["student_major"]))) {
                    $student_major_error = "Please select your major.";
                    $entry_error = true;
                } else {
                    $student_major = trim($_POST["student_major"]);
                }
            } else {
                $student_major_error = "Please select your major.";
                $entry_error = true;
            }

            // Check if student_school is set
            if (isset($_POST["student_school"])) {
                // Check if student_school is empty
                if (empty(trim($_POST["student_school"]))) {
                    $student_school_error = "Please select your school.";
                    $entry_error = true;
                } else {
                    $student_school = trim($_POST["student_school"]);
                }
            } else {
                $student_school_error = "Please select your school.";
                $entry_error = true;
            }

            // Check if student_graduationDate is set
            if (isset($_POST["student_graduationDate"])) {
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
            } else {
                $student_graduationDate_error = "Please enter your graduation date.";
                $entry_error = true;
            }

            // Check if student_jobPosition is set
            if (isset($_POST["student_jobPosition"])) {
                // Check if student_jobPosition is empty
                if (empty(trim($_POST["student_jobPosition"]))) {
                    $student_jobPosition_error = "Please select your preferred job type.";
                    $entry_error = true;
                } else {
                    $student_jobPosition = trim($_POST["student_jobPosition"]);
                }
            } else {
                $student_jobPosition_error = "Please select your preferred job type.";
                $entry_error = true;
            }

            // Check if student_areaOfInterest is set
            if (isset($_POST["student_areaOfInterest"])) {
                // Check if student_areaOfInterest is empty
                if (empty(trim($_POST["student_areaOfInterest"]))) {
                    $student_areaOfInterest_error = "Please select your area of interest.";
                    $entry_error = true;
                } else {
                    $student_areaOfInterest = trim($_POST["student_areaOfInterest"]);
                }
            } else {
                $student_areaOfInterest_error = "Please select your area of interest.";
                $entry_error = true;
            }

            //check if there were any errors, and that the fields are not empty
            if ($entry_error == false && !empty($student_firstName) && !empty($student_lastName) && !empty($student_email) && !empty($student_phone) && !empty($student_address) && !empty($student_city) && !empty($student_state) && !empty($student_zip) && !empty($student_degree) && !empty($student_major) && !empty($student_school) && !empty($student_graduationDate) && !empty($student_jobPosition) && !empty($student_areaOfInterest)) {
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
                $student_degree = (int) $student_degree;
                //prepare the student_major
                $student_major = prepareData($student_major);
                //check if the major is in the database, if not add it
                if (!$degrees->getMajorByName($student_major)) {
                    $degrees->addMajor($student_major, 0);
                    //once the major is added, get the id, as a string
                    $student_major = (string) $degrees->getMajorIdByName($student_major);
                    $student_major = (int) $student_major;
                } else {
                    //if the major is in the database, get the id, as a string
                    $student_major = (string) $degrees->getMajorIdByName($student_major);
                    $student_major = (int) $student_major;
                }
                //prepare the student_school
                $student_school = prepareData($student_school);
                $student_school = (int) $student_school;
                //prepare the student_graduationDate
                $student_graduationDate = prepareData($student_graduationDate);
                //prepare the student_jobPosition
                $student_jobPosition = prepareData($student_jobPosition);
                //prepare the student_areaOfInterest
                $student_areaOfInterest = prepareData($student_areaOfInterest);
                $student_areaOfInterest = (int) $student_areaOfInterest;
                //prepare the student_graduationDate
                $student_graduationDate = prepareData($student_graduationDate);

                //set the student education object properties
                $studentEducation->degree = intval($student_degree);
                $studentEducation->major = intval($student_major);
                $studentEducation->school = intval($student_school);
                $studentEducation->graduation = $student_graduationDate;

                //set the student address object properties
                $studentAddress->address = $student_address;
                $studentAddress->city = $student_city;
                $studentAddress->state = $student_state;
                $studentAddress->zipcode = $student_zip;

                //create a new student data object
                $newStudent = new StudentData();

                //set the student object properties
                $newStudent->firstName = $student_firstName;
                $newStudent->lastName = $student_lastName;
                $newStudent->email = $student_email;
                $newStudent->phone = $student_phone;
                $newStudent->studentAddress = $studentAddress;
                $newStudent->studentEducation = $studentEducation;
                $newStudent->position = $student_jobPosition;
                $newStudent->interest = intval($student_areaOfInterest);

                //add the student, check if the add was successful
                if ($student->addStudent($newStudent)) {
                    //if the add was successful, get the student id
                    $studentArray = $student->getStudentByEmail($student_email);
                    $student_id = $studentArray['id'];

                    //set the submission attempted variable and the student added variable to true
                    $attemptedStudentSubmission = true;
                    $studentCreated = true;
                } else {
                    //if the add was not successful, set the student added variable to false and the submission attempted variable to true
                    $studentCreated = false;
                    $attemptedStudentSubmission = true;
                }
            }
        }
    }
    if (isset($emailSent) && $emailSent != null) { ?>
        <!-- Completion page content -->
        <div class="container-fluid px-4">
            <h1 class="mt-4"><?php echo $student_name; ?></h1>
            <div class="row">
                <div class="card mb-4">
                    <!-- show email completion message -->
                    <div class="card-header">
                        <div class="card-title">
                            <div>
                                <?php
                                if ($emailSent) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Email Sent';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Email Not Sent';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- show email completion message -->
                            <div class="col-md-12">
                                <?php
                                if ($emailSent) {
                                    echo '<p>The confirmation email has been sent to ' . $student_name . ' at ' . $student_email . '.</p>';
                                } else {
                                    echo '<p>The confirmation email could not be sent to ' . $student_name . ' at ' . $student_email . '.</p>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <!-- show back buttons -->
                            <div class="col-md-12">
                                <div class="card-buttons">
                                    <span><a href="<?php echo APP_URL; ?>/admin/dashboard.php?view=students&student=list" class="btn btn-primary">Return to Student List</a></span>
                                    <span><a href="<?php echo APP_URL; ?>/admin/dashboard.php?view=students&student=single&id=<?php echo $student_id; ?>" class="btn btn-secondary">Go to Student</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <!-- Completion page content -->
        <div class="container-fluid px-4">
            <h1 class="mt-4"><?php echo $student_name; ?></h1>
            <div class="row">
                <div class="card mb-4">
                    <!-- show completion message -->
                    <div class="card-header">
                        <div class="card-title">
                            <div>
                                <?php
                                if ($action == 'create') {
                                    if ($studentCreated) {
                                        echo '<i class="fa-solid fa-check"></i>';
                                        echo 'Student Created';
                                    } else {
                                        echo '<i class="fa-solid fa-x"></i>';
                                        echo 'Error: Student Not Created';
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
                                if ($action == 'create') {
                                    if ($studentCreated) {
                                        echo '<p>The student: ' . $student_name . ' has been created.</p>';
                                    } else {
                                        echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                        echo '<p>The student: ' . $student_name . ' could not be created.</p>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <!-- show error messages -->
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                if ($action == 'create') {
                                    if (!$studentCreated) {
                                        echo '<p>The student: ' . $student_name . ' could not be created due to an error.</p>';
                                    } else {
                                        echo '<p>The student: ' . $student_name . ' has been created.</p>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <!-- show option to send email -->
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                if ($action == 'create') {
                                    if ($studentCreated) {
                                        echo '<p>Would you like to send a confirmation email to ' . $student_name . '?</p>';
                                        //confirmation email form - student values are hidden
                                        echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '?view=' . $_GET['view'] . '" method="post">';
                                        echo '<input type="hidden" name="student_id" value="' . $student_id . '">';
                                        echo '<input type="hidden" name="student_firstName" value="' . $student_firstName . '">';
                                        echo '<input type="hidden" name="student_lastName" value="' . $student_lastName . '">';
                                        echo '<input type="hidden" name="student_email" value="' . $student_email . '">';
                                        //current user id from session
                                        echo '<input type="hidden" name="user_id" value="' . $session->get('user_id') . '">';
                                        echo '<input type="submit" class="btn btn-primary" name="send_email" value="Send Email">';
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
                                    if ($action == 'create') {
                                        if ($studentCreated) {
                                            echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=students&student=list" class="btn btn-primary">Return to Student List</a></span>';
                                            echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=students&student=single&id=' . $student_id . '" class="btn btn-secondary">Go to Student</a></span>';
                                        } else {
                                            echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=students&student=list" class="btn btn-primary">Return to Student List</a></span>';
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
<?php }
} ?>
