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
                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=single&id=' . $event['id']; ?>" id="event-card-<?php echo $event['id']; ?>" class="link-offset-2 link-underline link-underline-opacity-0 event-list-item dash-event-cards">
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
                                    </a>
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
                                //include the field class
                                $fieldsData = new AreaOfInterest();
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
                                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=single' ?>&id=<?php echo $student['id']; ?>" class="btn btn-success">View</a>
                                            <a href="/delete/delete_student.php?id=<?php echo $student['id']; ?>" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <!-- Download CSV -->
                        <?php
                        //prepare the user array for download
                        $csvArray = $studentsArray;
                        //substitute the school id for the school name, degree id for the degree name, field id for the field name, and the major id for the major name
                        foreach ($csvArray as $key => $row) {
                            $csvArray[$key]['school'] = $schoolsData->getSchoolName($row['school']);
                            $csvArray[$key]['degree'] = $degreesData->getGradeNameById($row['degree']);
                            $csvArray[$key]['major'] = $degreesData->getMajorNameById($row['major']);
                            $csvArray[$key]['interest'] = $fieldsData->getSubjectName($row['interest']);
                        }
                        //clean up the column headers to be more readable, i.e. remove underscores and capitalize
                        foreach ($csvArray as $key => $row) {
                            $csvArray[$key] = array(
                                'First Name' => $row['first_name'],
                                'Last Name' => $row['last_name'],
                                'Email' => $row['email'],
                                'Phone' => $row['phone'],
                                'Address' => $row['address'],
                                'City' => $row['city'],
                                'State' => $row['state'],
                                'Zipcode' => $row['zipcode'],
                                'Field' => $row['interest'],
                                'Position Type' => $row['position'],
                                'Degree' => $row['degree'],
                                'Major' => $row['major'],
                                'Graduation Date' => $row['graduation'],
                                'School' => $row['school'],
                                'Date Submitted' => $row['created_at']
                            );
                        } ?>
                        <form target="_blank" action="<?php echo APP_URL . '/admin/download.php?type=students&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>" method="post" enctype="multipart/form-data">
                            <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
