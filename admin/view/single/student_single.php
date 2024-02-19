<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
}

//autoload composer dependencies
require_once __DIR__ . '/../../../vendor/autoload.php';

//include the permissions class
$permissionsObject = new Permission();

//auth class
$auth = new Authenticator();

/*confirm user has a role with read student permissions*/
//get the id of the read student permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('READ STUDENT');

//boolean to track if the user has the read student permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
} else {

    //student class
    $student = new Student();

    //fields class
    $fieldsData = new JobField();

    //schools class
    $schoolsData = new School();

    //events class
    $eventsData = new Event();

    //contact class
    $contact = new Contact();

    //user class
    $user = new User();

    if (isset($_GET['id'])) {
        //get the student id from the url parameter
        $student_id = $_GET['id'];
    } else {
        //set the student id to null
        $student_id = null;
    }

    //confirm the id exists
    if (empty($student_id) || $student_id == null) {
        //set the error type
        $thisError = 'INVALID_REQUEST_ERROR';

        //include the error message file
        include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
    } else {
        //try to get the student information
        $object = $student->getStudentById(intval($student_id));

        //check if the student is empty
        if (empty($object)) {
            //set the error type
            $thisError = 'NOT_FOUND';

            //include the error message file
            include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
        }
    }

    //if not empty, display the student information
    if (!empty($object)) {

        //variable for the mail result
        $mailResult = null;
        $mailSend = false;

        //if the contact form has been submitted, send the email
        if (isset($_POST['submitContact'])) {
            //get the form data
            $student_id = $_POST['studentId'];
            $student_name = $_POST['studentName'];
            $student_email = $_POST['studentEmail'];
            $sender_id = $_POST['senderId'];
            $subject = $_POST['subject'];
            $message = $_POST['message'];

            //send the email
            $mailResult = $contact->sendEmail($student_id, $student_email, $student_name, $subject, $message, $sender_id);

            //as long as an attempt was completed, set the mailSend variable to true
            if (!$mailResult || $mailResult) {
                $mailSend = true;
            }
        }
?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $student->getStudentFullName($student_id); ?></h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-person"></i>
                    Student Information
                </div>
                <div class="card-buttons">
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=list'; ?>"
                        class="btn btn-secondary">Back to Students</a>
                    <?php /*confirm user has a role with delete student permissions*/
                            //get the delete student permission id
                            $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE STUDENT');

                            //boolean to check if the user has the delete student permission
                            $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                            //only show the delete button if the user has the delete student permission
                            if ($hasDeletePermission) { ?>
                    <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteStudentModal">
                        Delete Student
                    </button>
                    <?php } ?>
                </div>
            </div>
            <div class="card-body">
                <!-- Single Student information -->
                <div class="row">
                    <!-- Student Details -->
                    <div class="col-md-6" style="height: 100%;">
                        <h3>Student Details</h3>
                        <div id="info" class="">
                            <p>
                                <strong>First Name:</strong> <?php echo $student->getStudentFirstName($student_id); ?>
                            </p>
                            <p>
                                <strong>Last Name:</strong> <?php echo $student->getStudentLastName($student_id); ?>
                            </p>
                            <p>
                                <strong>Email:</strong>
                                <?php echo formatEmail($student->getStudentEmail($student_id)); ?>
                            </p>
                            <p>
                                <strong>Phone:</strong>
                                <?php echo formatPhone($student->getStudentPhone($student_id)); ?>
                            </p>
                            <p>
                                <strong>Address:</strong>
                                <?php
                                        //encode the address as a url for google maps - this will be used to link to google maps per Google documentation https://developers.google.com/maps/documentation/urls/get-started
                                        $street = $student->getStudentAddress($student_id);
                                        $city = $student->getStudentCity($student_id);
                                        $state = $student->getStudentState($student_id);
                                        $zip = $student->getStudentZip($student_id);
                                        $address = formatAddress($street, $city, $state, $zip);
                                        $address = urlencode($address);
                                        ?>
                                <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $address; ?>"
                                    target="_blank"><?php echo $address; ?></a>
                            </p>
                            <p>
                                <strong>Field of Study/Area of Interest:</strong>
                                <?php echo $fieldsData->getSubjectName($student->getStudentInterest($student_id)); ?>
                            </p>
                            <p>
                                <strong>Position Type:</strong>
                                <?php echo $student->getStudentPosition($student_id); ?>
                            </p>
                            <p>
                                <strong>Degree:</strong>
                                <?php echo $student->getStudentDegree($student_id); ?>
                            </p>
                            <p>
                                <strong>Graduation Date:</strong>
                                <?php echo formatDate($student->getStudentGraduation($student_id)); ?>
                            </p>
                            <p>
                                <strong>School:</strong>
                                <?php echo $schoolsData->getSchoolName($student->getStudentSchool($student_id)); ?>
                            </p>
                        </div>
                    </div>
                    <!-- Student Event Attendance -->
                    <div class="col-md-6" style="height: 100%;">
                        <h3>Event Attendance</h3>
                        <?php /*confirm user has a role with read events permissions*/
                                //get the read event permission id
                                $readEventPermissionID = $permissionsObject->getPermissionIdByName('READ EVENT');

                                //boolean to check if the user has the read event permission
                                $hasReadEventPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readEventPermissionID);

                                //only show the event attendance info if the user has the read event permission
                                if ($hasReadEventPermission) { ?>
                        <div id="info" class="">
                            <?php
                                        //get the events the student has attended
                                        $events = $student->getStudentEventAttendace($student_id);
                                        //if there are events, display them
                                        if ($events) {
                                            foreach ($events as $event) {
                                        ?>
                            <p>
                                <i class="fa-solid fa-calendar-day"></i>
                                <strong><?php echo $eventsData->getEventName($event['event_id']) ?></strong>
                                <br />
                                <?php echo $eventsData->getEventLocation($event['event_id']); ?>
                                <br />
                                <?php echo formatDate($eventsData->getEventDate($event['event_id'])); ?>
                            </p>
                            <?php
                                            }
                                        } else {
                                            //otherwise, display a message
                                            echo "<p>This student has not attended any specific events.</p>";
                                        }
                                        ?>
                        </div>
                        <?php } else { ?>
                        <div id="info" class="">
                            <p>You do not have permission to view event attendance.</p>
                        </div>
                        <?php } ?>
                    </div>
                    <!-- Student Notes -->
                    <?php
                            //TODO: Add notes functionality
                            ?>
                </div>
                <!-- Student Contact Log -->
                <div class="row">
                    <div class="col-md-12">
                        <h3>Contact Log</h3>
                        <div id="info" class="">
                            <?php
                                    /*confirm user has a role with read contact permissions*/
                                    //get the read contact permission id
                                    $readContactPermissionID = $permissionsObject->getPermissionIdByName('READ CONTACT');

                                    //boolean to check if the user has the read contact permission
                                    $hasReadContactPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readContactPermissionID);

                                    //only show the contact log if the user has the read contact permission
                                    if ($hasReadContactPermission) {
                                        //get the contact history for the student
                                        $contactHistoryArray = $student->getStudentContactHistory($student_id);
                                        //if there is contact history, display it in a table
                                        if ($contactHistoryArray) {
                                    ?>
                            <div>
                                <table id="dataTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Subject</th>
                                            <th>Automated?</th>
                                            <th>Sending User</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                                        //for each contact history, display it in a table row
                                                        foreach ($contactHistoryArray as $contactHistory) {
                                                        ?>
                                        <tr>
                                            <td><?php echo formatDate($contactHistory['send_date']); ?></td>
                                            <td><?php echo formatTime($contactHistory['send_date']); ?></td>
                                            <td><?php echo $contactHistory['subject']; ?></td>
                                            <?php
                                                                //if the contact history is automated, display yes, otherwise display no
                                                                if ($contactHistory['auto'] == 1) {
                                                                    $contactHistory['auto'] = "Yes";
                                                                } else {
                                                                    $contactHistory['auto'] = "No";
                                                                }
                                                                ?>
                                            <td><?php echo $contactHistory['auto']; ?></td>
                                            <?php
                                                                //if the user id is NULL, display "SYSTEM", otherwise display the user's username
                                                                if ($contactHistory['sender'] == NULL) {
                                                                    $contactHistory['sender'] = "SYSTEM";
                                                                } else {
                                                                    $contactHistory['sender'] = $user->getUserUsername($contactHistory['sender']);
                                                                }
                                                                ?>
                                            <td><?php echo $contactHistory['sender']; ?></td>
                                        </tr>
                                        <?php
                                                        }
                                                        ?>
                                    </tbody>
                                </table>
                                <?php
                                        } else {
                                            //otherwise, display a message
                                            echo "<p>This student has not been contacted.</p>";
                                        }
                                    } else { ?>
                                <p>You do not have permission to view contact history.</p>
                                <?php }
                                        ?>
                            </div>
                        </div>
                    </div>
                    <!-- Contact Menu -->
                    <?php
                            //set student name and email variables
                            $student_name = $student->getStudentFullName($student_id);
                            $student_email = $student->getStudentEmail($student_id);
                            ?>
                    <div class="col-md-6">
                        <h5>Contact Student</h5>
                        <?php /*confirm user has a role with contact student permissions*/
                                //get the contact student permission id
                                $contactPermissionID = $permissionsObject->getPermissionIdByName('CONTACT STUDENT');

                                //boolean to check if the user has the contact student permission
                                $hasContactPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $contactPermissionID);

                                //only show the contact form if the user has the contact student permission
                                if ($hasContactPermission) { ?>
                        <div id="info" class="">
                            <!-- Contact Student Form Modal-->
                            <button type="button" id="openContactModal" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#contactStudentModal">
                                Contact Student
                            </button>
                            <!-- Modal -->
                            <div id="contactStudentModal" class="modal fade contact" tabindex="-1" role="dialog"
                                aria-labelledby="#studentContactForm" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title" id="studentContactForm">Contact Student -
                                                <?php echo $student_name; ?></h3>
                                            <button type="button" class="btn-close close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        </div>
                                        <form id=" contactForm" action="#" method="post">
                                            <div class="modal-body">
                                                <label for="studentName">Student Name:</label>
                                                <input class="form-control" type="text" id="studentName"
                                                    name="studentName" value="<?php echo $student_name; ?>"
                                                    placeholder="<?php echo $student_name; ?>" disabled required>

                                                <label for="studentEmail">Student Email:</label>
                                                <input class="form-control" type="email" id="studentEmail"
                                                    name="studentEmail" value="<?php echo $student_email; ?>"
                                                    placeholder="<?php echo $student_email; ?>" disabled required>

                                                <label for="subject">Subject:</label>
                                                <input class="form-control" type="text" id="subject" name="subject"
                                                    required>

                                                <label for="message">Message:</label>
                                                <textarea class="form-control" id="message" name="message"
                                                    required></textarea>

                                                <input class="form-control" type="hidden" id="studentId"
                                                    name="studentId" value="<?php echo $student_id; ?>">
                                                <input class="form-control" type="hidden" id="studentName"
                                                    name="studentName" value="<?php echo $student_name; ?>">
                                                <input class="form-control" type="hidden" id="studentEmail"
                                                    name="studentEmail" value="<?php echo $student_email; ?>">
                                                <input class="form-control" type="hidden" id="senderId" name="senderId"
                                                    value="<?php echo $_SESSION['user_id']; ?>">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <input class="form-control" type="submit" id="submitContact"
                                                    name="submitContact" value="Send">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="info" class="">
                            <!-- Contact Result Modal-->
                            <div id="contactResultModal" class="modal fade contact" tabindex="-1" role="dialog"
                                aria-labelledby="#studentContactResult" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title" id="studentContactResult">Contact Student -
                                                <?php echo $student_name; ?> : Result</h3>
                                            <button type="button" class="btn-close close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <?php
                                                        //check that mailResult is set
                                                        if (isset($mailResult)) {
                                                            //if the mailResult is false, display an error message
                                                            if ((!$mailResult && $mailResult !== null) || $mailResult == false) {
                                                                echo "<p>There was an error sending the email.</p>";
                                                                //set the error type
                                                                $thisError = 'SEND_MAIL_ERROR';

                                                                //include the error message file
                                                                include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
                                                            } else {
                                                                echo "<p>The email was sent successfully.</p>";
                                                            }
                                                        }
                                                        ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Dismiss</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div id="info" class="">
                            <p>You do not have permission to contact students.</p>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php if ($hasDeletePermission) { ?>
                <div id="info" class="">
                    <!-- Delete Student Modal-->
                    <!-- Modal -->
                    <div id="deleteStudentModal" class="modal fade delete" tabindex="-1" role="dialog"
                        aria-labelledby="#studentDeleteModal" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title" id="studentDeleteModal">Delete Student -
                                        <?php echo $student->getStudentFullName(intval($student_id)); ?></h3>
                                    <button type="button" class="btn-close close" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this student?</p>
                                    <p>This action cannot be undone.</p>
                                </div>
                                <div class="modal-footer">
                                    <form
                                        action="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=single&action=delete&id=' . $student_id; ?>"
                                        method="post">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Delete Student</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="card-footer">
                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=list'; ?>"
                    class="btn btn-secondary">Back to Students</a>
                <?php /*confirm user has a role with delete student permissions*/
                        //get the delete student permission id
                        $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE STUDENT');

                        //boolean to check if the user has the delete student permission
                        $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                        //only show the delete button if the user has the delete student permission
                        if ($hasDeletePermission) { ?>
                <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal"
                    data-bs-target="#deleteStudentModal">
                    Delete Student
                </button>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
            //if mailSend is true, display the contact result modal
            if ($mailSend) { ?>
    <script>
    $(document).ready(function() {
        $('#contactResultModal').modal('show');
    });
    </script>
    <?php } ?>
</div>
<script type="text/javascript">
//variables for the datatable
var tableHeight = "50vh";
var rowNav = true;
var pageSelect = [5, 10, 15, 20, 25, 50, ["All", -1]];
var columnArray = [{
        select: 0,
        type: "date",
        format: "MM/DD/YYYY",
        sortSequence: ["desc", "asc"]
    },
    {
        select: 1,
        type: "date",
        format: "h:i A",
        sortSequence: ["desc", "asc"]
    },
    {
        select: 2,
        sortSequence: ["desc", "asc"]
    },
    {
        select: 3,
        sortSequence: ["desc", "asc"]
    },
    {
        select: 4,
        sortSequence: ["desc", "asc"]
    }
];
var columnOrder = [0, 1, 2, 3, 4];
</script>
<?php }
} ?>
