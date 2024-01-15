<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//include the permissions class
$permissionsObject = new Permission();

//include the authenticator class
$auth = new Authenticator();

/*confirm user has a role with delete report permissions*/
//get the id of the delete report permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('DELETE REPORT');

//boolean to track if the user has the delete report permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    die('Error: You do not have permission to perform this request.');
} else {
    //boolean to track if the job can be deleted
    $canDelete = false;

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //get the report type from the URL
        if (isset($_GET['type'])) {
            $reportType = urldecode($_GET['type']);
        } else {
            $reportType = 'all';
        }

        //if the report type is all, set the canDelete boolean to false
        if ($reportType == 'all') {
            $canDelete = false;
        }

        //set the report title based on the report type
        if ($reportType == 'all') {
            $reportListTitle = 'All Reports';
        } else {
            $reportListTitle = $reportType . ' Report';
        }

        //if the title is all reports, set the canDelete boolean to false
        if ($reportListTitle == 'All Reports') {
            $canDelete = false;
        }

        //setup to include the report class
        $reportClassName = str_replace(' ', '', ucwords($reportType)) . 'Report';
        //also remove any hyphens
        $reportClassName = str_replace('-', '', $reportClassName);

        //include the report class
        $reportClass = new $reportClassName();

        //get the action from the url parameter
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        } else {
            $action = 'none';
        }

        //if the action is delete, get the report id from the url parameter
        if ($action == 'delete') {
            //get the report id from the URL
            if (isset($_GET['id'])) {
                $reportId = intval($_GET['id']);
            } else {
                $reportId = 0;
            }

            //if the report id is 0, set the canDelete boolean to false
            if ($reportId == 0) {
                $canDelete = false;
            } else {
                $canDelete = true;
            }
        } else {
            $reportId = 0;
            $canDelete = false;
        }

        //if the canDelete boolean is true, delete the report
        if ($canDelete) {
            $reportDeleted = $reportClass->deleteReport($reportId);
        } else {
            $reportDeleted = false;
        }
    }
?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <div>
                            <?php
                            if ($action == 'delete') {
                                if ($reportDeleted) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'Report Deleted';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: Report Not Deleted';
                                }
                            }
                            ?>
                        </div>
                        <div>
                            <?php
                            if ($action == 'delete') {
                                if ($canDelete && !$reportDeleted) {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo 'The report with ID: ' . strval($reportId) . ', could not be deleted because of an unknown error.';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
