<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//get the report type from the URL
if (isset($_GET['type'])) {
    $reportType = urldecode($_GET['type']);
} else {
    $reportType = 'all';
}

//set the report title based on the report type
if ($reportType == 'all') {
    $reportListTitle = 'All Reports';
} else {
    $reportListTitle = $reportType . ' Report';
}

//setup to include the report class
$reportClassName = str_replace(' ', '', ucwords($reportType)) . 'Report';

//include the report class
$reportClass = new $reportClassName();

//get the report id from the URL
if (isset($_GET['id'])) {
    $reportId = intval($_GET['id']);
} else {
    $reportId = 0;
}

//get the report data
$report = $reportClass->getReportById($reportId);
?>
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
                        class="btn btn-primary">Back to Report List</a>
                    <form
                        action="<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=list&type=' . urlencode($reportType); ?>"
                        method="post">
                        <input type="hidden" name="generate_report" value="true">
                        <button type="submit" class="btn btn-primary">Generate Updated Report</button>
                    </form>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=delete&type=' . urlencode($reportType) . '&id=' . $reportId; ?>"
                        class="btn btn-danger">Delete Report</a>
                </div>
            </div>
            <div class="card-body">
                <!-- Single Report information -->
                <div class="row">
                    <div class="col-md-12">
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
                        <table id="dataTable striped">
                            <thead>
                                <tr>
                                    <?php
                                    //get the report data headers
                                    $reportDataHeaders = array_keys($reportData[0]);

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
            <div class="card-footer">
                <!-- Download CSV -->
                <?php
                //prepare the report array for download
                $csvArray[] = $report;

                //debug
                echo '<pre>';
                print_r($csvArray);
                echo '</pre>';
                ?>
                <form target="_blank"
                    action="<?php echo APP_URL . '/admin/download.php?type=reports&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>"
                    method="post" enctype="multipart/form-data">
                    <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                </form>
            </div>
        </div>
    </div>
</div>
