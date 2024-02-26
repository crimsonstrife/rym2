<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
}

//include the permissions class
$permissionsObject = new Permission();

//include the auth class
$auth = new Authenticator();

/* confirm user has a role with read student permissions */
//get the read student permission id
$readPermissionID = $permissionsObject->getPermissionIdByName('READ STUDENT');

//boolean to check if the user has the read student permission
$hasReadPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readPermissionID);

//if the user does not have the read student permission, display an error message and do not display the page
if (!$hasReadPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
} else {
?>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Students</h1>
        <div class="row">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-table"></i>
                        Student List
                    </div>
                    <div class="card-tools">
                        <?php
                        /*confirm user has a role with create student permissions*/
                        //get the id of the create student permission
                        $createStudentPermissionID = $permissionsObject->getPermissionIdByName('CREATE STUDENT');

                        //boolean to check if the user has the create student permission
                        $hasCreatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createStudentPermissionID);

                        //if the user has the create student permission, display the add student button
                        if ($hasCreatePermission) {
                        ?>
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=add&action=create' ?>" class="btn btn-primary">Add Student</a>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <table id="dataTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Field</th>
                                    <th>Position Type</th>
                                    <th>Degree</th>
                                    <th>Major</th>
                                    <th>Graduation Date</th>
                                    <th>School</th>
                                    <th>Date Submitted</th>
                                    <th data-sortable="false">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                /* Setup datatable of Students */
                                //include the student class
                                $studentData = new Student();
                                //include the student address class
                                $studentAddress = new StudentAddress();
                                //get all the students
                                $studentsArray = $studentData->getStudents();
                                //include the schools class
                                $schoolsData = new School();
                                //include the Degrees class
                                $degreesData = new Degree();
                                //include the Fields class
                                $fieldsData = new JobField();
                                //for each student, display it
                                foreach ($studentsArray as $student) {
                                    //get the student's address
                                    $address = $studentAddress->getStudentAddress(intval($student['id']));
                                    //get the student's city
                                    $city = $studentAddress->getStudentCity(intval($student['id']));
                                    //get the student's state
                                    $state = $studentAddress->getStudentState(intval($student['id']));
                                    //get the student's zipcode
                                    $zipcode = $studentAddress->getStudentZip(intval($student['id']));
                                ?>
                                    <tr>
                                        <td><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></td>
                                        <td><?php echo formatAddress($address, $city, $state, $zipcode); ?></td>
                                        <td><?php echo $fieldsData->getSubjectName($student['interest']); ?></td>
                                        <td><?php echo $student['position']; ?></td>
                                        <td><?php echo $degreesData->getGradeNameById($student['degree']); ?></td>
                                        <td><?php echo $degreesData->getMajorNameById($student['major']); ?></td>
                                        <td><?php echo $student['graduation']; ?></td>
                                        <td><?php echo $schoolsData->getSchoolName($student['school']); ?></td>
                                        <td><?php echo $student['created_at']; ?></td>
                                        <td>
                                            <span class="td-actions">
                                                <?php /*confirm user has a role with read student permissions*/
                                                //only show the view button if the user has the read student permission
                                                if ($hasReadPermission) { ?>
                                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=single' ?>&id=<?php echo $student['id']; ?>" class="btn btn-success">View Student</a>
                                                <?php } ?>
                                                <?php /*confirm user has a role with update student permissions*/
                                                //get the update student permission id
                                                $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE STUDENT');

                                                //boolean to check if the user has the update student permission
                                                $hasUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                                                //only show the edit button if the user has the update student permission
                                                if ($hasUpdatePermission) { ?>
                                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=edit&action=edit&id=' . $student['id']; ?>" class="btn btn-primary">Edit Student</a>
                                                <?php } ?>
                                                <?php /*confirm user has a role with delete student permissions*/
                                                //get the delete student permission id
                                                $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE STUDENT');

                                                //boolean to check if the user has the delete student permission
                                                $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                                                //only show the delete button if the user has the delete student permission
                                                if ($hasDeletePermission) { ?>
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
                    <?php /*confirm user has a role with export students permissions*/
                    //get the id of the export students permission
                    $exportStudentsPermissionID = $permissionsObject->getPermissionIdByName('EXPORT STUDENT');

                    //boolean to check if the user has the export students permission
                    $hasExportStudentsPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $exportStudentsPermissionID);

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
                <?php if ($hasDeletePermission) { ?>
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
        <?php if ($hasDeletePermission) {
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
    </div>
    <script type="text/javascript">
        //variables for the datatable
        var tableHeight = "50vh";
        var rowNav = true;
        var pageSelect = [5, 10, 15, 20, 25, 50, ["All", -1]];
        var columnArray = [{
                select: 0,
                sortSequence: ["desc", "asc"]
            },
            {
                select: 1,
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
            },
            {
                select: 5,
                sortSequence: ["desc", "asc"]
            },
            {
                select: 6,
                type: "date",
                format: "YYYY-MM-DD",
                sortSequence: ["desc", "asc"]
            },
            {
                select: 7,
                sortSequence: ["desc", "asc"]
            },
            {
                select: 8,
                type: "date",
                format: "YYYY-MM-DD HH:mm:ss",
                sortSequence: ["desc", "asc"]
            },
            {
                select: 9,
                sortable: false,
                searchable: false
            }
        ];
        var columnOrder = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
    </script>
<?php } ?>
