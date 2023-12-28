<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//autoload composer dependencies
require_once __DIR__ . '/../../vendor/autoload.php';

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

//get the student id from the url parameter
$student_id = $_GET['id'];

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
    $contact->sendEmail($student_id, $student_email, $student_name, $subject, $message, $sender_id);
}
?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $student->getStudentFullName($student_id); ?></h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-calendar-day"></i>
                    Student Information
                </div>
                <div class="card-buttons">
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=list'; ?>" class="btn btn-primary btn-sm">Back to Students</a>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=delete&id=' . $student_id; ?>" class="btn btn-danger btn-sm">Delete Student</a>
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
                                $address = $student->getStudentFormattedAddress($student_id);
                                $address = urlencode($address);
                                ?>
                                <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $address; ?>" target="_blank"><?php echo $student->getStudentFormattedAddress($student_id); ?></a>
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
                            //get the contact history for the student
                            $contactHistoryArray = $student->getStudentContactHistory($student_id);
                            //if there is contact history, display it in a table
                            if ($contactHistoryArray) {
                            ?>
                                <div class="table-scroll">
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
                        <div id="info" class="">
                            <!-- Contact Student Form Modal-->
                            <button type="button" id="openContactModal" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#contactStudentModal">
                                Contact Student
                            </button>
                            <!-- Modal -->
                            <div id="contactStudentModal" class="modal fade contact" tabindex="-1" role="dialog" aria-labelledby="#studentContactForm" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title" id="studentContactForm">Contact Student -
                                                <?php echo $student_name; ?></h3>
                                            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        </div>
                                        <form id=" contactForm" action="#" method="post">
                                            <div class="modal-body">
                                                <label for="studentName">Student Name:</label>
                                                <input class="form-control" type="text" id="studentName" name="studentName" value="<?php echo $student_name; ?>" placeholder="<?php echo $student_name; ?>" disabled required>

                                                <label for="studentEmail">Student Email:</label>
                                                <input class="form-control" type="email" id="studentEmail" name="studentEmail" value="<?php echo $student_email; ?>" placeholder="<?php echo $student_email; ?>" disabled required>

                                                <label for="subject">Subject:</label>
                                                <input class="form-control" type="text" id="subject" name="subject" required>

                                                <label for="message">Message:</label>
                                                <textarea class="form-control" id="message" name="message" required></textarea>

                                                <input class="form-control" type="hidden" id="studentId" name="studentId" value="<?php echo $student_id; ?>">
                                                <input class="form-control" type="hidden" id="studentName" name="studentName" value="<?php echo $student_name; ?>">
                                                <input class="form-control" type="hidden" id="studentEmail" name="studentEmail" value="<?php echo $student_email; ?>">
                                                <input class="form-control" type="hidden" id="senderId" name="senderId" value="<?php echo $_SESSION['user_id']; ?>">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <input class="form-control" type="submit" id="submitContact" name="submitContact" value="Send">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=list'; ?>" class="btn btn-primary btn-sm">Back to Students</a>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=delete&id=' . $student_id; ?>" class="btn btn-danger btn-sm">Delete Student</a>
                </div>
            </div>
        </div>
    </div>
</div>
