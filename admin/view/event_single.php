<?php
//event class
$event = new Event();

//school class
$school = new School();

//user class
$user = new User();

//student class
$student = new Student();

//get the event id from the url parameter
$event_id = $_GET['id'];
?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $event->getEventName($event_id); ?></h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-calendar-day"></i>
                Event Information
            </div>
            <div class="card-body">
                <!-- Single Event information -->
                <div class="row">
                    <div class="col-md-6">
                        <h3>Event Details</h3>
                        <p><strong>Event Name:</strong> <?php echo $event->getEventName($event_id); ?></p>
                        <p><strong>Event Date:</strong> <?php echo $event->getEventDate($event_id); ?></p>
                        <p><strong>Event Location:</strong>
                            <?php echo $school->getSchoolName(intval($event->getEventLocation($event_id))); ?></p>
                        <!-- Formatted School address -->
                        <p><strong>Event Address:</strong>
                            <?php echo $school->getFormattedSchoolAddress(intval($event->getEventLocation($event_id))); ?>
                        </p>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h3>Event Attendees</h3>
                        <div>
                            <!-- list of students that signed up at this event -->
                            <?php
                            //get the list of students that signed up at this event, and display them. If there are none, display a message.
                            $students = $student->getStudentEventAttendace($event_id);
                            ?>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <table id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Degree</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($students as $eventStudent) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $student->getStudentFirstName($eventStudent['student_id']); ?>
                                                    </td>
                                                    <td><?php echo $student->getStudentLastName($eventStudent['student_id']); ?>
                                                    </td>
                                                    <td><?php echo $student->getStudentEmail($eventStudent['student_id']); ?>
                                                    </td>
                                                    <td><?php echo $student->getStudentDegree($eventStudent['student_id']); ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>
    <?php ?>
