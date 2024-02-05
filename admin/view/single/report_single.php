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

    //get the report id from the URL
    if (isset($_GET['id'])) {
        $reportId = intval($_GET['id']);
    } else {
        $reportId = null;
    }

    //get the report type from the URL
    if (isset($_GET['type'])) {
        $reportType = urldecode($_GET['type']);
    } else {
        $reportType = null;
    }

    //set the report title based on the report type
    if ($reportType == null) {
        $reportListTitle = null;
    } else {
        $reportListTitle = $reportType . ' Report';
    }

    if ($reportListTitle !== null) {
        //setup to include the report class
        $reportClassName = str_replace(' ', '', ucwords($reportType)) . 'Report';
        //also remove any hyphens
        $reportClassName = str_replace('-', '', $reportClassName);
    } else {
        $reportClassName = null;
    }

    if ($reportClassName !== null) {
        //check if the report class exists
        if (class_exists($reportClassName)) {
            //include the report class
            $reportClass = new $reportClassName();
        } else {
            $reportClass = null;
        }
    }

    if ($reportClass == null || $reportId == null || $reportType == null) {
        if ($reportClass == null) {
            //set the error type
            $thisError = 'CRITICAL';
        } elseif ($reportId == null) {
            //set the error type
            $thisError = 'INVALID_REQUEST_ERROR';
        } elseif ($reportType == null) {
            //set the error type
            $thisError = 'INVALID_REQUEST_ERROR';
        } else {
            //set the error type
            $thisError = 'ROUTING_ERROR';
        }

        //include the error message file
        include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
    } else {
        //get the report data
        $report = $reportClass->getReportById($reportId);

        //check if the report exists, is report empty?
        if (empty($report)) {
            //set the error type
            $thisError = 'NOT_FOUND';

            //include the error message file
            include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
        } else { ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $reportListTitle . ' - ' . formatDate($report['created_at']) ?></h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-file-invoice"></i>
                    Report
                </div>
                <div class="card-buttons">
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=list&type=' . urlencode($reportType); ?>"
                        class="btn btn-secondary">Back to Report List</a>
                    <form
                        action="<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=list&type=' . urlencode($reportType); ?>"
                        method="post">
                        <input type="hidden" name="generate_report" value="true">
                        <button type="submit" class="btn btn-primary">Generate Updated Report</button>
                    </form>
                    <?php /*confirm user has a role with delete report permissions*/
                                //get the delete report permission id
                                $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE REPORT');

                                //boolean to check if the user has the delete report permission
                                $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                                //only show the delete button if the user has the delete report permission
                                if ($hasDeletePermission) { ?>
                    <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteReportModal">
                        Delete Report
                    </button>
                    <?php } ?>
                </div>
            </div>
            <div class="card-body">
                <!-- Single Report information -->
                <div class="row">
                    <div class="col-md-6">
                        <h3>Report Metadata</h3>
                        <div id="info" class="">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Report ID:</strong> <?php echo $report['id']; ?></p>
                                    <p><strong>Report Type:</strong> <?php echo $report['report_type']; ?></p>
                                    <p><strong>Report Created:</strong> <?php echo $report['created_at']; ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Report Created By:</strong> <?php echo $report['created_by']; ?></p>
                                    <p><strong>Report Updated:</strong> <?php echo $report['updated_at']; ?></p>
                                    <p><strong>Report Updated By:</strong> <?php echo $report['updated_by']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Report Data</h3>
                        <!-- display the report datatable -->
                        <?php $reportData = $report['data'] ?>
                        <div class="card-body">
                            <div>
                                <table id="dataTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <?php
                                                        //get the report data headers
                                                        $reportDataHeaders = array_keys($reportData[0]);

                                                        //count of the number of headers
                                                        $headerCount = count($reportDataHeaders);

                                                        //clean up the report data headers to be more readable, i.e. remove underscores and capitalize
                                                        foreach ($reportDataHeaders as $key => $header) {
                                                            $reportDataHeaders[$key] = ucwords(str_replace('_', ' ', $header));
                                                        }

                                                        //display the report data headers
                                                        foreach ($reportDataHeaders as $header) {
                                                            echo '<th>' . $header . '</th>';
                                                        }
                                                        ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                                    //display the report data
                                                    foreach ($reportData as $row) {
                                                        echo '<tr>';
                                                        foreach ($row as $column) {
                                                            echo '<td>' . $column . '</td>';
                                                        }
                                                        echo '</tr>';
                                                    }
                                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- display the report chart -->
                    <div class="col-md-10">
                        <h3>Report Chart</h3>
                        <canvas id="reportChartJS" class="center"></canvas>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <!-- Download CSV -->
                <?php
                            /*confirm user has a role with export reports permissions*/
                            //get the id of the export reports permission
                            $exportReportsPermissionID = $permissionsObject->getPermissionIdByName('EXPORT REPORT');

                            //boolean to check if the user has the export reports permission
                            $hasExportReportsPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $exportReportsPermissionID);

                            if ($hasExportReportsPermission) {
                                //prepare the report array for download
                                $csvArray = $reportData;

                                //clean up the key headers to be more readable, i.e. remove underscores and capitalize
                                foreach ($csvArray as $key => $row) {
                                    foreach ($row as $column => $value) {
                                        $csvArray[$key][ucwords(str_replace('_', ' ', $column))] = $value;
                                        unset($csvArray[$key][$column]);
                                    }
                                }
                            ?>
                <form target="_blank"
                    action="<?php echo APP_URL . '/admin/download.php?type=reports&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>"
                    method="post" enctype="multipart/form-data">
                    <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                </form>
                <?php } else { ?>
                <p class="text-danger">You do not have permission to download the CSV from this report.</p>
                <button class="btn btn-success" disabled>Export to CSV</button>
                <?php } ?>
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
                                <h3 class="modal-title" id="reportDeleteModal">Delete Report -
                                    <?php echo $report['report_type'] . ' With ID: ' . strval($reportId); ?></h3>
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
                                <form
                                    action="<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=single&action=delete&type=' . urlencode($reportType) . '&id=' . $reportId; ?>"
                                    method="post">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete Report</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <script type="text/javascript" src="<?php echo getLibraryPath() . 'chart.js/chart.umd.js'; ?>"></script>
            <script type="text/javascript">
            //get the chart element
            const ctx = document.getElementById('reportChartJS');

            <?php
                            //get the report's chart data
                            $chartData = $reportClass->getChartableReportData($reportId);

                            //check how the chart data is structured
                            if (isset($chartData['labels'])) {
                                //get the report's chart labels
                                $reportChartLabels = $chartData['labels'];
                            } else {
                                //get the report's chart labels
                                $reportChartLabels = $chartData['data']['labels'];
                            }
                            if (isset($chartData['labels'])) {
                                //get the report's chart data
                                $reportChartData = $chartData['datasets'];
                            } else {
                                //get the report's chart data
                                $reportChartData = $chartData['data']['datasets'];
                            }

                            //format the report's chart data
                            $reportChartData = json_encode($reportChartData);

                            //get the report's chart type
                            $reportChartType = $chartData['type'];

                            //get the report's chart title
                            $reportChartTitle = $chartData['title'];

                            //get the report's chart options
                            $reportChartOptions = $chartData['options'];

                            //format the report's chart options
                            $reportChartOptions = json_encode($reportChartOptions);
                            ?>
            //setup the chart
            new Chart(ctx, {
                type: '<?php echo $reportChartType; ?>',
                data: {
                    labels: <?php echo json_encode($reportChartLabels); ?>,
                    datasets: <?php echo $reportChartData; ?>
                },
                options: <?php echo $reportChartOptions; ?>
            });
            </script>
        </div>
    </div>
</div>
<script type="text/javascript">
//variables for the datatable
var tableHeight = "50vh";
var rowNav = true;
var pageSelect = [5, 10, 15, 20, 25, ["All", -1]];
var columnArray = [
    <?php
                    //for the number of headers, add a column for each header
                    for ($i = 0; $i < $headerCount; $i++) {
                        echo '{select: ' . $i . ', sortSequence: ["desc", "asc"]}';
                        if ($i < $headerCount - 1) {
                            echo ',';
                        }
                    }
                    ?>
];
var columnOrder = [<?php
                                    //for the number of headers, add a column for each header
                                    for ($i = 0; $i < $headerCount; $i++) {
                                        echo $i;
                                        if ($i < $headerCount - 1) {
                                            echo ',';
                                        }
                                    }
                                    ?>];
</script>
<?php }
    }
} ?>
