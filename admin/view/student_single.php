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

//user class
$user = new User();

//get the student id from the url parameter
$student_id = $_GET['id'];
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
                                <table id="dataTable">
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
                                                <td><?php echo $user->getUserUsername($contactHistory['sender']); ?></td>
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
                    <!-- Contact Menu -->
                    <div class="col-md-6">
                        <h5>Contact Student</h5>
                        <div id="info" class="">
                            <a href="#" class="btn btn-primary btn-sm">Contact Student</a>
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