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
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=list&type=' . urlencode($reportType); ?>" class="btn btn-primary">Back to Report List</a>
                    <form action="<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=list&type=' . urlencode($reportType); ?>" method="post">
                        <input type="hidden" name="generate_report" value="true">
                        <button type="submit" class="btn btn-primary">Generate Updated Report</button>
                    </form>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=delete&type=' . urlencode($reportType) . '&id=' . $reportId; ?>" class="btn btn-danger">Delete Report</a>
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
                        <table id="dataTable striped">
                            <thead>
                                <tr>
                                    <?php
                                    //get the report data headers
                                    $reportDataHeaders = array_keys($reportData[0]);

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
                <form target="_blank" action="<?php echo APP_URL . '/admin/download.php?type=reports&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>" method="post" enctype="multipart/form-data">
                    <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                </form>
            </div>
            <script type="text/javascript" src="<?php echo getLibraryPath() . 'chart.js/chart.umd.js'; ?>"></script>
            <script type="text/javascript">
                //get the chart element
                const ctx = document.getElementById('reportChartJS');

                <?php
                //get the report's chart data
                $chartData = $reportClass->getChartableReportData($reportId);

                //get the report's chart labels
                $reportChartLabels = $chartData['labels'];

                //get the report's chart data
                $reportChartData = $chartData['datasets'];

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
