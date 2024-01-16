<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once('./includes/errors/errorMessage.inc.php');
} // idea from https://stackoverflow.com/a/409515 (user UnkwnTech)

//include the permissions class
$permissionsObject = new Permission();

//auth class
$auth = new Authenticator();

//check that the view dashboard permission is set
if (!isset($hasViewDashboardPermission)) {
    //set the error type
    $thisError = 'CONFIGURATION_ERROR';

    //include the error message file
    include_once('./includes/errors/errorMessage.inc.php');
} else {
    //check that the user has the view dashboard permission
    if (!$hasViewDashboardPermission) {
        //set the error type
        $thisError = 'DASHBOARD_PERMISSION_ERROR';

        //include the error message file
        include_once('./includes/errors/errorMessage.inc.php');
    } else {

        /*confirm user has a role with read contact permissions*/
        //get the id of the read contact permission
        $relevantPermissionID = $permissionsObject->getPermissionIdByName('READ CONTACT');

        //boolean to track if the user has the read contact permission
        $hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

        //prevent the user from accessing the page if they do not have the relevant permission
        if (!$hasPermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once('./includes/errors/errorMessage.inc.php');
        } else {
?>
<!-- main content -->
<div id="layout_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Contact Log</h1>
            <div class="row">
                <!-- Contact Log -->
                <div class="row">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fa-solid fa-table"></i>
                            Student Outreach Contact Log
                        </div>
                        <div class="card-body">
                            <div class="table-scroll table-fixedHead table-responsive">
                                <table id="dataTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Student Email</th>
                                            <th>School</th>
                                            <th>Degree</th>
                                            <th>Automatic Email?</th>
                                            <th>Send Date</th>
                                            <th>Sending User</th>
                                            <th>Subject</th>
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
                                                    //include the contact class
                                                    $contactsData = new Contact();
                                                    //include the user class
                                                    $userData = new User();
                                                    //get the contact log
                                                    $contactLogArray = $contactsData->getContactLog();
                                                    //sort the contact log by date
                                                    usort($contactLogArray, function ($a, $b) {
                                                        return $a['send_date'] <=> $b['send_date'];
                                                    });
                                                    foreach ($contactLogArray as $entry) {
                                                        //get the student id
                                                        $studentId = intval($entry['student']);
                                                        //get the student email
                                                        $studentEmail = $studentsData->getStudentEmail($studentId);
                                                        //get the student name
                                                        $studentName = $studentsData->getStudentFullName($studentId);
                                                        //get the school id from the student id
                                                        $schoolId = $studentsData->getStudentSchool($studentId);
                                                        //get the school name
                                                        $schoolName = $schoolsData->getSchoolName($schoolId);
                                                        //get the degree id from the student id
                                                        $degreeId = $studentsData->getStudentDegree($studentId);
                                                        //get the degree name
                                                        $degreeName = $studentsData->getStudentDegree($studentId);
                                                        //get the sending user id
                                                        $sendingUserId = $entry['sender'];
                                                        //get the sending user name, if the sending user is not null
                                                        if ($sendingUserId != NULL) {
                                                            $sendingUserName = $userData->getUserUsername(intval($sendingUserId));
                                                        } else {
                                                            $sendingUserName = "SYSTEM";
                                                        }
                                                        //email subject
                                                        $subject = $entry['subject'];
                                                        //email body
                                                        $body = $entry['message'];
                                                        //email send date
                                                        $sendDate = formatDate($entry['send_date']);
                                                        //automatic email int to boolean
                                                        $automaticEmail = intval($entry['auto']);
                                                        if ($automaticEmail == 1) {
                                                            $automaticEmail = "Automatic";
                                                        } else {
                                                            $automaticEmail = "Manual";
                                                        }
                                                    ?>
                                        <tr>
                                            <td><?php echo $studentName; ?></td>
                                            <td><?php echo $studentEmail; ?></td>
                                            <td><?php echo $schoolName; ?></td>
                                            <td><?php echo $degreeName; ?></td>
                                            <td><?php echo $automaticEmail; ?></td>
                                            <td><?php echo $sendDate; ?></td>
                                            <td><?php echo $sendingUserName; ?></td>
                                            <td><?php echo $subject; ?></td>
                                        </tr>
                                        <?php
                                                        //setup a download array
                                                        $studentContactArray[] = array(
                                                            'Student Name' => $studentName,
                                                            'Student Email' => $studentEmail,
                                                            'School' => $schoolName,
                                                            'Degree' => $degreeName,
                                                            'Automatic Email' => $automaticEmail,
                                                            'Sent Date' => $sendDate,
                                                            'Sending User' => $sendingUserName,
                                                            'subject' => $subject,
                                                            'Message' => $body
                                                        );
                                                    }
                                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <!-- Download CSV -->
                            <?php
                                        //prepare the user array for download
                                        $csvArray = $studentContactArray; ?>
                            <form target="_blank"
                                action="<?php echo APP_URL . '/admin/download.php?type=contact_log&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>"
                                method="post" enctype="multipart/form-data">
                                <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php }
    }
} ?>
