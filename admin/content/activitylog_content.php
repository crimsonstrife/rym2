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
                        <h1 class="mt-4">Activity Log</h1>
                        <div class="row">
                            <!-- Site Activity Log -->
                            <div class="row">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fa-solid fa-table"></i>
                                        Activity Log
                                    </div>
                                    <div class="card-body">
                                        <div class="table-scroll table-fixedHead table-responsive">
                                            <table id="dataTable" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>User</th>
                                                        <th>Action</th>
                                                        <th>Details</th>
                                                        <th>Action Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    /* Setup datatable of activity */
                                                    //include the activity class
                                                    $activity = new Activity();
                                                    //include the user class
                                                    $user = new User();
                                                    //include the school class
                                                    $school = new School();
                                                    //include the degree class
                                                    $degree = new Degree();
                                                    //include the student class
                                                    $student = new Student();
                                                    //include the event class
                                                    $event = new Event();
                                                    //include the role class
                                                    $role = new Roles();
                                                    //get the activity log
                                                    $activityArray = $activity->getAllActivity();
                                                    foreach ($activityArray as $entry) {
                                                        //get the user id
                                                        $userId = intval($entry['user_id']);
                                                        //get the user name
                                                        $userName = $user->getUserUsername($userId);
                                                        //get the action
                                                        $action = $entry['action'];
                                                        //get the details
                                                        $details = $entry['performed_on'];

                                                        //get the action date
                                                        $actionDate = $entry['action_date'];
                                                        //display the activity log
                                                        echo '<tr>';
                                                        echo '<td>' . $userName . '</td>';
                                                        echo '<td>' . $action . '</td>';
                                                        echo '<td>' . $details . '</td>';
                                                        echo '<td>' . $actionDate . '</td>';
                                                        echo '</tr>';
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
                                        //swap the user id for the username
                                        foreach ($activityArray as $key => $entry) {
                                            //get the user id
                                            $userId = intval($entry['user_id']);
                                            //get the user name
                                            $userName = $user->getUserUsername($userId);
                                            //swap the user id for the username
                                            $activityArray[$key]['user_id'] = $userName;
                                        }
                                        $csvArray = $activityArray; ?>
                                        <form target="_blank" action="<?php echo APP_URL . '/admin/download.php?type=activity_log&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>" method="post" enctype="multipart/form-data">
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
