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

//include the degree class
$degrees = new Degree();

//include the contact class
$contact = new Contact();

/*confirm user has a role with update student permissions*/
//get the id of the update student permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('UPDATE STUDENT');

//boolean to track if the user has the update student permission
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
    $student_id = $student_firstName = $student_lastName = $student_email = $student_phone = $student_address = $student_city = $student_state = $student_zip = $student_degree = $student_major = $student_school = $student_graduationDate = $student_jobPosition = $student_areaOfInterest = "";
    $student_firstName_error = $student_lastName_error = $student_email_error = $student_phone_error = $student_address_error = $student_city_error = $student_state_error = $student_zip_error = $student_degree_error = $student_major_error = $student_school_error = $student_graduationDate_error = $student_jobPosition_error = $student_areaOfInterest_error = "";
    $entry_error = false;

    //other variables
    $canEdit = true;
    $emailSent = null;
    $studentUpdated = false;
    $attemptedStudentEdit = false;

    //if the action is edit, get the student id from the url parameter
    if ($action == 'edit') {
        $student_id = $_GET['id'];
    }

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
            //determine if the provided major is an id or a name
            if (is_numeric($student_major)) {
                $student_major = (int) $student_major;
                //check if the major is in the database, and assign it to the student_major variable, if not, set an error
                //get the major by id
                $major = $degrees->getMajor($student_major);
                //if the major is not in the database, set an error
                if (!$major || empty($major) || $major == null) {
                    $student_major_error = "Please select a valid major.";
                    $entry_error = true;
                }
            } else {
                $student_major = (string) $student_major;
                //check if the major is in the database, if not add it
                if (!$degrees->getMajorByName($student_major)) {
                    $degrees->addMajor($student_major, intval($session->get('user_id')));
                    //once the major is added, get the id, as a string
                    $student_major = (string) $degrees->getMajorIdByName($student_major);
                    $student_major = (int) $student_major;
                } else {
                    //if the major is in the database, get the id, as a string
                    $student_major = (string) $degrees->getMajorIdByName($student_major);
                    $student_major = (int) $student_major;
                }
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

            //update the student, check if the update was successful
            if ($student->updateStudent(intval($student_id), $newStudent)) {
                //set the update attempted variable and the student updated variable to true
                $attemptedStudentEdit = true;
                $studentUpdated = true;
            } else {
                //if the add was not successful, set the student updated variable to false
                $studentUpdated = false;
                //set the update attempted variable to true
                $attemptedStudentEdit = true;
            }

            //get the student's full name
            $student_name = $student_firstName . ' ' . $student_lastName;
        }
    }
?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?php echo htmlspecialchars($student_name); ?></h1>
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <div>
                            <?php
                            if ($action == 'edit') {
                                if ($studentUpdated) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Student Updated';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Student Not Updated';
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
                                if ($studentUpdated) {
                                    echo '<p>The student: ' . htmlspecialchars($student_name) . ' has been updated.</p>';
                                } else {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo '<p>The student: ' . htmlspecialchars($student_name) . ' could not be updated.</p>';
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
                                if (!$studentUpdated) {
                                    echo '<p>The student: ' . htmlspecialchars($student_name) . ' could not be updated due to an error.</p>';
                                } else {
                                    echo '<p>The student: ' . htmlspecialchars($student_name) . ' has been updated.</p>';
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
                                    if ($studentUpdated) {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=students&student=list" class="btn btn-primary">Return to Student List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=students&student=single&id=' . htmlspecialchars($student_id) . '" class="btn btn-secondary">Go to Student</a></span>';
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
<?php } ?>
