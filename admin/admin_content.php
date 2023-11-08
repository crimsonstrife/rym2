<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
} // idea from https://stackoverflow.com/a/409515 (user UnkwnTech)
?>
<!-- main content -->
<div id="layout_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard</h1>
            <div class="row">
                <!-- Upcoming Events -->
                <div id="upcoming-events" class="card mb-4">
                    <div class="card-header">
                        <h3>Upcoming Events</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group events-list">
                            <?php
                            /* Setup upcoming events */
                            //include the event class
                            $eventsData = new Event();
                            //include the school class
                            $eventSchoolsData = new School();
                            //get all events
                            $eventsArray = $eventsData->getEvents();
                            //for each event, if the date is in the future, display it
                            foreach ($eventsArray as $event) {
                                if (strtotime($event['event_date']) > strtotime(date('Y-m-d'))) {
                            ?>
                                    <li class="list-group-item list-group-item-action flex-column align-items-start event-list-item">
                                        <div class="d-flex w-100 justify-content-between event-list-item-content">
                                            <h4 class="mb-1"><?php echo $event['name']; ?></h4>
                                            <p><?php echo $eventSchoolsData->getSchoolById($event['location'])['name']; ?>
                                            </p>
                                            <small>
                                                <?php echo formatDate($event['event_date']); ?>
                                            </small>
                                        </div>
                                    </li>
                            <?php }
                            } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fa-solid fa-table"></i>
                        Student Records
                    </div>
                    <div class="card-body">
                        <table id="dataTable">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Degree</th>
                                    <th>School</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                /* Setup datatable of students */
                                //include the student class
                                $studentsData = new Student();
                                //include the school class
                                $schoolsData = new School();
                                //include the degree class
                                $degreesData = new Degree();
                                //get all students
                                $studentsArray = $studentsData->getStudents();
                                //order the students by most recent
                                $studentsArray = array_reverse($studentsArray);
                                foreach ($studentsArray as $student) {
                                ?>
                                    <tr>
                                        <td><?php echo $student['first_name']; ?></td>
                                        <td><?php echo $student['last_name']; ?></td>
                                        <td><?php echo $student['email']; ?></td>
                                        <td><?php echo $degreesData->getDegreeProgram($student['degree'], $student['major']); ?>
                                        </td>
                                        <td><?php echo $schoolsData->getSchoolById($student['school'])['name']; ?></td>
                                        <td>
                                            <a href="view_student.php?id=<?php echo $student['id']; ?>" class="btn btn-success">View</a>
                                            <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn btn-primary">Edit</a>
                                            <a href="delete_student.php?id=<?php echo $student['id']; ?>" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <div class="card-footer">
                            <!-- Download CSV -->
                            <a href="#" class="btn btn-primary">Download CSV</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
