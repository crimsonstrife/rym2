<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
} // idea from https://stackoverflow.com/a/409515 (user UnkwnTech)

//check that the view dashboard permission is set
if (!isset($hasViewDashboardPermission)) {
    //set the error type
    $thisError = 'CONFIGURATION_ERROR';

    //include the error message file
    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
} else {
    //check that the user has the view dashboard permission
    if (!$hasViewDashboardPermission) {
        //set the error type
        $thisError = 'DASHBOARD_PERMISSION_ERROR';

        //include the error message file
        include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
    } else {

        //include the permissions class
        $permissionsObject = new Permission();

        //user class
        $user = new User();

        //auth class
        $auth = new Authenticator();

        //session class
        $session = new Session();
?>
        <style>
            a.datatable-sorter {
                display: block;
                color: inherit;
                text-decoration: inherit;
            }

            a.page-link {
                cursor: pointer;
            }
        </style>
        <!-- main content -->
        <div id="layout_content" class="w-95 mx-auto">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <?php /*check if the user has the read events permission */
                    //get the id of the read events permission
                    $readEventsPermissionId = $permissionsObject->getPermissionIdByName('READ EVENT');

                    //boolean to check if the user has the read events permission
                    $hasReadEventsPermission = $auth->checkUserPermission(intval($session->get('user_id')), $readEventsPermissionId);

                    //only display the events table if the user has the read events permission
                    if ($hasReadEventsPermission) {
                    ?>
                        <div class="row">
                            <!-- Upcoming Events -->
                            <div id="upcoming-events" class="card mb-4">
                                <div class="card-header">
                                    <h3>Upcoming Events</h3>
                                </div>
                                <div class="card-body table-scroll">
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
                    <?php } ?>
                    <?php /*check if the user has the read students permission */
                    //get the id of the read students permission
                    $readStudentsPermissionId = $permissionsObject->getPermissionIdByName('READ STUDENT');

                    //boolean to check if the user has the read students permission
                    $hasReadStudentsPermission = $auth->checkUserPermission(intval($session->get('user_id')), $readStudentsPermissionId);

                    //only display the students table if the user has the read students permission
                    if ($hasReadStudentsPermission) {
                    ?>
                        <div class="row">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fa-solid fa-table"></i>
                                    Student Records
                                </div>
                                <div class="card-body">
                                    <div>
                                        <table id="dataTable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Email</th>
                                                    <th>Degree</th>
                                                    <th>School</th>
                                                    <th data-sortable="false">Actions</th>
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
                                                        <td><?php echo getDegreeProgram($student['degree'], $student['major']); ?>
                                                        </td>
                                                        <td><?php echo $schoolsData->getSchoolById($student['school'])['name']; ?></td>
                                                        <td>
                                                            <span class="td-actions">
                                                                <?php /*check if the user has the read students permission */
                                                                //get the id of the read students permission
                                                                $readStudentsPermissionId = $permissionsObject->getPermissionIdByName('READ STUDENT');

                                                                //boolean to check if the user has the read students permission
                                                                $hasReadStudentsPermission = $auth->checkUserPermission(intval($session->get('user_id')), $readStudentsPermissionId);

                                                                //only display the students table if the user has the read students permission
                                                                if ($hasReadStudentsPermission) {
                                                                ?>
                                                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=single' ?>&id=<?php echo $student['id']; ?>" class="btn btn-success">View</a>
                                                                <?php } ?>
                                                                <?php /*check if the user has the delete students permission */
                                                                //get the id of the delete students permission
                                                                $deleteStudentsPermissionId = $permissionsObject->getPermissionIdByName('DELETE STUDENT');

                                                                //boolean to check if the user has the delete students permission
                                                                $hasDeleteStudentsPermission = $auth->checkUserPermission(intval($session->get('user_id')), $deleteStudentsPermissionId);

                                                                //only display the students table if the user has the delete students permission
                                                                if ($hasDeleteStudentsPermission) {
                                                                ?>
                                                                    <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteStudentModal" onclick="setDeleteID(<?php echo $student['id']; ?>)">
                                                                        Delete Student
                                                                    </button>
                                                                <?php } ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <!-- Download CSV -->
                                    <?php //check if the user has permission to download the csv of students

                                    //get the id of the export student permission
                                    $exportStudentsPermissionId = $permissionsObject->getPermissionIdByName('EXPORT STUDENT');

                                    //boolean to check if the user has the export student permission
                                    $hasExportStudentsPermission = $auth->checkUserPermission(intval($session->get('user_id')), $exportStudentsPermissionId);

                                    if ($hasExportStudentsPermission) {
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
                                    <?php } else { ?>
                                        <p class="text-danger">You do not have permission to download the CSV of students.</p>
                                        <button class="btn btn-success" disabled>Export to CSV</button>
                                    <?php } ?>
                                </div>
                                <?php if ($hasDeleteStudentsPermission) { ?>
                                    <div id="info" class="">
                                        <!-- Delete Student Modal-->
                                        <!-- Modal -->
                                        <div id="deleteStudentModal" class="modal fade delete" tabindex="-1" role="dialog" aria-labelledby="#studentDeleteModal" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id="studentDeleteModal">Delete Student - <span id="studentName-Title">Student Name</span></h3>
                                                        <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="fa-solid fa-times"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this student?</p>
                                                        <p>This action cannot be undone.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <script>
                                                            var deleteBaseURL =
                                                                "<?php echo APP_URL . '/admin/dashboard.php?view=students&student=single&action=delete&id='; ?>";
                                                        </script>
                                                        <form id="deleteStudentForm" action="" method="post">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="clearDeleteID()">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Delete Student</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if ($hasDeleteStudentsPermission) {
                            //combine the first and last name into a single key value pair for the students array
                            foreach ($studentsArray as $key => $row) {
                                $studentsArray[$key]['name'] = $row['first_name'] . ' ' . $row['last_name'];
                            }
                        ?>
                            <script>
                                //set the students array to a javascript variable
                                var studentsArray = <?php echo json_encode($studentsArray); ?>;

                                //function to set the delete id on the action url of the delete modal based on which student is selected
                                function setDeleteID(id) {
                                    //get the student name
                                    var studentName = studentsArray.find(student => student.id == id).name;
                                    //set the student name in the modal title
                                    document.getElementById("studentName-Title").innerHTML = studentName;
                                    //set the action url of the delete modal
                                    document.getElementById("deleteStudentForm").action = deleteBaseURL + id;
                                }

                                function clearDeleteID() {
                                    //set the action url of the delete modal
                                    document.getElementById("deleteStudentForm").action = "";
                                }
                            </script>
                        <?php } ?>
                    <?php } ?>
                </div>
            </main>
            <script type="text/javascript">
                //variables for the datatable
                var tableHeight = "50vh";
                var rowNav = true;
                var pageSelect = [5, 10, 15, ["All", -1]];
                var columnArray = [{
                        select: 0,
                        sortSequence: ["desc", "asc"]
                    },
                    {
                        select: 1,
                        sortSequence: ["desc", "asc"]
                    },
                    {
                        select: 3,
                        sortSequence: ["desc", "asc"]
                    },
                    {
                        select: 4,
                        sortSequence: ["desc", "asc"]
                    },
                    {
                        select: 5,
                        sortSequence: ["desc", "asc"]
                    }, {
                        select: 6,
                        sortable: false,
                        searchable: false
                    }
                ];
                var columnOrder = [0, 1, 2, 3, 4, 5];
            </script>
        </div>
<?php }
} ?>
