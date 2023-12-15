<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
} ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Students</h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-table"></i>
                    Student List
                </div>
            </div>
            <div class="card-body">
                <table id="dataTable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Zipcode</th>
                            <th>Field</th>
                            <th>Position Type</th>
                            <th>Degree</th>
                            <th>Major</th>
                            <th>Graduation Date</th>
                            <th>School</th>
                            <th>Date Submitted</th>
                            <!-- <th>Created By</th> -->
                            <!-- <th>Date Updated</th> -->
                            <!-- <th>Updated By</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        /* Setup datatable of Students */
                        //include the student class
                        $studentData = new Student();
                        //get all the students
                        $studentArray = $studentData->getStudents();
                        //include the schools class
                        $schoolsData = new School();
                        //include the Degrees class
                        $degreesData = new Degree();
                        //include the Fields class
                        $fieldsData = new JobField();
                        //for each student, display it
                        foreach ($studentArray as $student) {
                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=single' ?>&id=<?php echo $student['id']; ?>"
                                    class="btn btn-success">View</a>
                            </td>
                            <td><?php echo $student['first_name']; ?></td>
                            <td><?php echo $student['last_name']; ?></td>
                            <td><?php echo formatEmail($student['email']); ?></td>
                            <td><?php echo formatPhone($student['phone']); ?></td>
                            <td><?php echo $student['address']; ?></td>
                            <td><?php echo $student['city']; ?></td>
                            <td><?php echo $student['state']; ?></td>
                            <td><?php echo $student['zipcode']; ?></td>
                            <td><?php echo $fieldsData->getSubjectName($student['interest']); ?></td>
                            <td><?php echo $student['position']; ?></td>
                            <td><?php echo $degreesData->getGradeNameById($student['degree']); ?></td>
                            <td><?php echo $degreesData->getMajorNameById($student['major']); ?></td>
                            <td><?php echo $student['graduation']; ?></td>
                            <td><?php echo $schoolsData->getSchoolName($student['school']); ?></td>
                            <td><?php echo $student['created_at']; ?></td>
                            <td>
                                <a href="/delete/delete_student.php?id=<?php echo $student['id']; ?>"
                                    class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="card-footer">
                    <!-- Download CSV -->
                    <?php
                    //prepare the user array for download
                    $csvArray = $studentArray;
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
                    <form target="_blank"
                        action="<?php echo APP_URL . '/admin/download.php?type=students&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>"
                        method="post" enctype="multipart/form-data">
                        <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ?>
