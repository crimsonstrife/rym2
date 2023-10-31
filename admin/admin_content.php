<?php

//include the header
include_once('header.php');
?>
<div id="layout">
    <?php
    //include the sidebar
    include_once('sidebar.php');
    ?>
    <!-- main content -->
    <div id="layout_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard</h1>
                <div class="row">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fa-solid fa-table"></i>
                            Student Records
                        </div>
                        <div class="row">
                            <!-- Upcoming Events -->
                            <div>
                            </div>
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
                                            <a href="view_student.php?id=<?php echo $student['id']; ?>"
                                                class="btn btn-success">View</a>
                                            <a href="edit_student.php?id=<?php echo $student['id']; ?>"
                                                class="btn btn-primary">Edit</a>
                                            <a href="delete_student.php?id=<?php echo $student['id']; ?>"
                                                class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <div class="card-footer">
                                <!-- Download CSV -->
                                <a href="<?php echo APP_URL . '/admin/download_csv.php?students=all' ?>"
                                    class="btn btn-primary">Download CSV</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php
        //include the footer
        include_once('footer.php');
        ?>
