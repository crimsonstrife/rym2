<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
}

//auth class
$auth = new Authenticator();

//permissions class
$permissionsObject = new Permission();

//get the report type from the URL
if (isset($_GET['type'])) {
    $reportType = urldecode($_GET['type']);
} else {
    $reportType = 'all';
}

//set the report list title based on the report type
if ($reportType == 'all') {
    $reportListTitle = 'All Reports';
} else {
    $reportListTitle = $reportType . ' Reports';
}

//setup to include the report class
$reportClassName = str_replace(' ', '', ucwords($reportType)) . 'Report';
//also remove any hyphens
$reportClassName = str_replace('-', '', $reportClassName);

//include the report class
$reportClass = new $reportClassName();

/*confirm user has a role with read report permissions*/
//get the id of the read report permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('READ REPORT');

//boolean to track if the user has the read report permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
} else {

    //if the generate report button was clicked, generate the report
    if (isset($_POST['generate_report'])) {
        //get current user ID
        $user_id = intval($_SESSION['user_id']);
        $reportClass->generateReport($user_id);

        //redirect to the report list page
        header('Location: ' . APP_URL . '/admin/dashboard.php?view=reports&report=list&type=' . urlencode($reportType));
    }
?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $reportListTitle; ?></h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-table"></i>
                    Report List
                </div>
                <div class="card-tools">
                    <?php
                        //button to trigger the generate report function only if the report type is not all and the user has the create report permission
                        //get the id of the create report permission
                        $createReportPermissionID = $permissionsObject->getPermissionIdByName('CREATE REPORT');

                        //boolean to check if the user has the create report permission
                        $hasCreatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createReportPermissionID);

                        //if the user has the create report permission, display the generate report button
                        if ($hasCreatePermission) {
                            //if the report type is not all, display the generate report button
                            if ($reportType != 'all') { ?>
                    <form
                        action="<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=list&type=' . urlencode($reportType); ?>"
                        method="post">
                        <input type="hidden" name="generate_report" value="true">
                        <button type="submit" class="btn btn-primary">Generate Report</button>
                    </form>
                    <?php }
                        } ?>
                </div>
            </div>
            <div class="card-body">
                <div>
                    <!-- display the report list for the report type -->
                    <table id="dataTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Report ID</th>
                                <th>Report Type</th>
                                <th>Date Created</th>
                                <th>Created By</th>
                                <th>Date Updated</th>
                                <th>Updated By</th>
                                <th data-sortable="false">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                /* Setup datatable of reports */
                                //include the users class
                                $usersData = new User();
                                //get all reports
                                $reportsArray = $reportClass->getReports();
                                //sort the reports by date created
                                usort($reportsArray, function ($a, $b) {
                                    return strtotime($b['created_at']) - strtotime($a['created_at']);
                                });
                                //for each report, display it
                                foreach ($reportsArray as $report) {
                                ?>
                            <tr>
                                <td><?php echo $report['id']; ?></td>
                                <td><?php echo $report['report_type']; ?></td>
                                <td><?php echo $report['created_at']; ?></td>
                                <td><?php echo $report['created_by']; ?>
                                </td>
                                <td><?php echo $report['updated_at']; ?></td>
                                <td><?php echo $report['updated_by']; ?>
                                </td>
                                <td>
                                    <span class="td-actions">
                                        <?php /*confirm user has a role with read report permissions*/
                                                //get the read report permission id
                                                $updatePermissionID = $permissionsObject->getPermissionIdByName('READ REPORT');

                                                //boolean to check if the user has the read report permission
                                                $hasReadPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                                                //only show the edit button if the user has the read report permission
                                                if ($hasReadPermission) { ?>
                                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=single&type=' . urlencode($reportType) . '&id=' . $report['id']; ?>"
                                            class="btn btn-success">View</a>
                                        <?php } ?>
                                        <?php /*confirm user has a role with delete report permissions*/
                                                //get the delete report permission id
                                                $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE REPORT');

                                                //boolean to check if the user has the delete report permission
                                                $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                                                //only show the delete button if the user has the delete report permission
                                                if ($hasDeletePermission) { ?>
                                        <button type="button" id="openDeleteModal" class="btn btn-danger"
                                            data-bs-toggle="modal" data-bs-target="#deleteReportModal"
                                            onclick="setDeleteID(<?php echo $report['id']; ?>)">
                                            Delete Report
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
            <?php if ($hasDeletePermission) { ?>
            <div id="info" class="">
                <!-- Delete Report Modal-->
                <!-- Modal -->
                <div id="deleteReportModal" class="modal fade delete" tabindex="-1" role="dialog"
                    aria-labelledby="#reportDeleteModal" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="reportDeleteModal">Delete Report - <span
                                        id="reportName-Title">Report Name</span></h3>
                                <button type="button" class="btn-close close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this report?</p>
                                <p>This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                                <script>
                                var deleteBaseURL =
                                    "<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=single&action=delete'; ?>";
                                </script>
                                <form id="deleteReportForm" action="" method="post">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                        onclick="clearDeleteID()">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete Report</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php if ($hasDeletePermission) { ?>
    <script>
    //set the reports array to a javascript variable
    var reportsArray = <?php echo json_encode($reportsArray); ?>;

    //function to set the delete id on the action url of the delete modal based on which report is selected
    function setDeleteID(id) {
        //get the report name
        var reportName = reportsArray.find(report => report.id == id).report_type;

        //get the report type
        var reportType = reportsArray.find(report => report.id == id).report_type;

        //url encode the report type
        reportType = encodeURIComponent(reportType);

        //set the report name in the modal title
        document.getElementById("reportName-Title").innerHTML = reportName + " Report ID: " + id;
        //set the action url of the delete modal
        document.getElementById("deleteReportForm").action = deleteBaseURL + "&type=" + reportType + "&id=" + id;
    }

    function clearDeleteID() {
        //set the action url of the delete modal
        document.getElementById("deleteReportForm").action = "";
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
        type: "numeric",
        sortSequence: ["desc", "asc"]
    },
    {
        select: 1,
        sortSequence: ["desc", "asc"]
    },
    {
        select: 2,
        type: "date",
        format: "YYYY-MM-DD HH:mm:ss",
        sortSequence: ["desc", "asc"]
    },
    {
        select: 3,
        sortSequence: ["desc", "asc"]
    },
    {
        select: 4,
        type: "date",
        format: "YYYY-MM-DD HH:mm:ss",
        sortSequence: ["desc", "asc"]
    },
    {
        select: 5,
        sortSequence: ["desc", "asc"]
    },
    {
        select: 6,
        sortable: false,
        searchable: false
    }
];
var columnOrder = [0, 1, 2, 3, 4, 5, 6];
</script>
<?php } ?>
