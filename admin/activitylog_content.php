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
                            </table>
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
