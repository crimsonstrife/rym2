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

//set the report list title based on the report type
if ($reportType == 'all') {
    $reportListTitle = 'All Reports';
} else {
    $reportListTitle = $reportType . ' Reports';
}

//setup to include the report class
$reportClassName = str_replace(' ', '', ucwords($reportType)) . 'Report';

//include the report class
$reportClass = new $reportClassName();

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
                    //button to trigger the generate report function only if the report type is not all
                    if ($reportType != 'all') { ?>
                        <form action="<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=list&type=' . urlencode($reportType); ?>" method="post">
                            <input type="hidden" name="generate_report" value="true">
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                        </form>
                    <?php } ?>
                </div>
            </div>
            <div class="card-body">
                <!-- display the report list for the report type -->
                <table id="dataTable">
                    <thead>
                        <tr>
                            <th>Report ID</th>
                            <th>Report Type</th>
                            <th>Date Created</th>
                            <th>Created By</th>
                            <th>Date Updated</th>
                            <th>Updated By</th>
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
                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=single&type=' . urlencode($reportType) . '&id=' . $report['id']; ?>" class="btn btn-success">View</a>
                                    <a href="/delete/delete_report.php?id=<?php echo $report['id']; ?>" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
