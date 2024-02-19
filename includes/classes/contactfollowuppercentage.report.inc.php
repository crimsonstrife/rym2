<?php

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* Include the base application config file */
require_once(__DIR__ . '/../../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');

/**
 * This class is used to generate the Top Field by School Report
 * It extends the Report class, and generates a report based on the data for the areas of interest, and schools using the student counts.
 * Stores the report in an array, and logs the report to the database as a JSON string.
 *
 * @category   Admin
 *
 */
class ContactFollowUpPercentageReport extends Report
{
    //Reference to the database
    private $mysqli;

    //Instantiate the database connection
    public function __construct()
    {
        try {
            $this->mysqli = connectToDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
        } catch (Exception $e) {
            //log the error
            error_log('Error: ' . $e->getMessage());
        }
    }

    //Close the database connection when the object is destroyed
    public function __destruct()
    {
        closeDatabaseConnection($this->mysqli);
    }

    /**
     * Get the report data from the database
     *
     * @return array
     */
    public function getReports(): array
    {
        //include the user class, so we can get the user name of the user that requested the report
        $userObject = new User();

        //create the SQL query
        $sql = "SELECT * FROM reports WHERE report_type = 'Contact Follow-Up Percentage'";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create an array to store the reports
        $reports = array();

        //loop through the result to format the data
        while ($row = $result->fetch_assoc()) {
            //get the report id
            $id = $row['id'];

            //get the report data
            $data = $row['data'];

            //decode the JSON string to an array
            $data = json_decode($data, true);

            //get the user id of the user that created the report as an integer
            $createdBy = intval($row['created_by']);

            //get the user id of the user that updated the report as an integer
            $updatedBy = intval($row['updated_by']);

            //get the user name of the user that created the report
            $createdByName = $userObject->getUserUsername($createdBy);

            //get the user name of the user that updated the report
            $updatedByName = $userObject->getUserUsername($updatedBy);

            //assemble a new array with the report id and the report data and the user id of the user that created the report
            $reports[] = array(
                'id' => $id,
                'report_type' => 'Contact Follow-Up Percentage',
                'data' => $data,
                'created_by' => $createdByName,
                'created_at' => $row['created_at'],
                'updated_by' => $updatedByName,
                'updated_at' => $row['updated_at'],
            );
        }

        //return the reports
        return $reports;
    }

    /**
     * Get a single report by id
     *
     * @param int $id - the id of the report to get
     *
     * @return array
     */
    public function getReportById(int $id): array
    {
        //include the user class, so we can get the user name of the user that requested the report
        $userObject = new User();

        //create the SQL query
        $sql = "SELECT * FROM reports WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param('i', $id);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //get the report data
        $row = $result->fetch_assoc();

        //get the report id
        $id = $row['id'];

        //get the report data
        $data = $row['data'];

        //decode the JSON string to an array
        $data = json_decode($data, true);

        //get the user id of the user that created the report as an integer
        $createdBy = intval($row['created_by']);

        //get the user id of the user that updated the report as an integer
        $updatedBy = intval($row['updated_by']);

        //get the user name of the user that created the report
        $createdByName = $userObject->getUserUsername($createdBy);

        //get the user name of the user that updated the report
        $updatedByName = $userObject->getUserUsername($updatedBy);

        //assemble a new array with the report id and the report data and the user id of the user that created the report
        $report = array(
            'id' => $id,
            'report_type' => 'Contact Follow-Up Percentage',
            'data' => $data,
            'created_by' => $createdByName,
            'created_at' => $row['created_at'],
            'updated_by' => $updatedByName,
            'updated_at' => $row['updated_at'],
        );

        //return the report
        return $report;
    }

    /**
     * Find a report by the search term
     *
     * @param string $search - the search term to search for
     *
     * @return array
     */
    public function findReport(string $search): array
    {
        //include the user class, so we can get the user name of the user that requested the report
        $userObject = new User();

        //create the SQL query
        $sql = "SELECT * FROM reports WHERE report_type = 'Contact Follow-Up Percentage' AND (data LIKE ? OR created_at LIKE ? OR updated_at LIKE ?)";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $search = '%' . $search . '%';
        $stmt->bind_param('sss', $search, $search, $search);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create an array to store the reports
        $reports = array();

        //loop through the result to format the data
        while ($row = $result->fetch_assoc()) {
            //get the report id
            $id = $row['id'];

            //get the report data
            $data = $row['data'];

            //decode the JSON string to an array
            $data = json_decode($data, true);

            //get the user id of the user that created the report as an integer
            $createdBy = intval($row['created_by']);

            //get the user id of the user that updated the report as an integer
            $updatedBy = intval($row['updated_by']);

            //get the user name of the user that created the report
            $createdByName = $userObject->getUserUsername($createdBy);

            //get the user name of the user that updated the report
            $updatedByName = $userObject->getUserUsername($updatedBy);

            //assemble a new array with the report id and the report data and the user id of the user that created the report
            $reports[] = array(
                'id' => $id,
                'report_type' => 'Contact Follow-Up Percentage',
                'data' => $data,
                'created_by' => $createdByName,
                'created_at' => $row['created_at'],
                'updated_by' => $updatedByName,
                'updated_at' => $row['updated_at'],
            );
        }

        //return the reports
        return $reports;
    }

    /**
     * Store the report in the database
     *
     * @param string $report - the report data to store
     * @param int $created_by - the id of the user that created the report
     *
     * @return int
     */
    public function storeReport(string $report, int $created_by): int
    {
        //get the current date and time
        $date = date("Y-m-d H:i:s");

        //create the SQL query
        $sql = "INSERT INTO reports (report_type, data, created_by, created_at, updated_by, updated_at) VALUES ('Contact Follow-Up Percentage', ?, ?, ?, ?, ?)";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param('sisis', $report, $created_by, $date, $created_by, $date);

        //execute the statement
        $stmt->execute();

        //get the id of the report that was logged
        $reportId = $stmt->insert_id;

        //return the id of the report that was logged
        return $reportId;
    }

    /**
     * Generate Contact Follow-Up Percentage Report
     * Generates a new report with the percentage of students that were sent automatic email (indicated by the sender being NULL in the contact_log table),
     * who were then followed up with by a staff member (indicated by the sender being NOT NULL in the contact_log table).
     * @param int $created_by // the user id of the user that requested the report
     * @return int The id of the report that was generated
     */
    public function generateReport(int $created_by): int
    {
        //include the user class, so we can get the user name of the user that requested the report
        $userObject = new User();

        //include the student class, so we can get the student name of the student that was contacted
        $studentObject = new Student();

        //include the contact log class, so we can get the contact log data
        $contactLogObject = new Contact();

        //create an array to store the report
        $report = array();

        /* Create the SQL Queries */
        $percentageQuery = "SELECT (COUNT(CASE WHEN sender IS NOT NULL THEN 1 END) * 100.0 / COUNT(*)) AS percentage_follow_up FROM contact_log";
        $totalQuery = "SELECT COUNT(*) AS total FROM contact_log";
        $topSendingUserQuery = "SELECT sender, COUNT(*) AS total FROM contact_log WHERE sender IS NOT NULL GROUP BY sender ORDER BY total DESC LIMIT 1";

        //prepare the statement
        $percentageStmt = $this->mysqli->prepare($percentageQuery);
        //execute the statement
        $percentageStmt->execute();
        //get the results
        $percentageResult = $percentageStmt->get_result();
        //get the percentage of students that were followed up with
        $percentage = $percentageResult->fetch_assoc()['percentage_follow_up'];

        //prepare the statement
        $totalStmt = $this->mysqli->prepare($totalQuery);
        //execute the statement
        $totalStmt->execute();
        //get the results
        $totalResult = $totalStmt->get_result();
        //get the total number of contact attempts
        $total = $totalResult->fetch_assoc()['total'];

        //prepare the statement
        $topSendingUserStmt = $this->mysqli->prepare($topSendingUserQuery);
        //execute the statement
        $topSendingUserStmt->execute();
        //get the results
        $topSendingUserResult = $topSendingUserStmt->get_result();
        //get the top sending user - that is not the automatic email sender
        $topSendingUser = $topSendingUserResult->fetch_assoc()['sender'];

        //get the user name of the top sending user
        $topSendingUserName = $userObject->getUserUsername(intval($topSendingUser));

        //assemble the report data
        $report[] = array(
            'percentage' => $percentage,
            'total' => $total,
            'top_sending_user' => $topSendingUserName,
        );

        //encode the report data as a JSON string
        $report = json_encode($report);

        //store the report in the database
        $reportId = $this->storeReport($report, $created_by);

        //log the report activity
        $this->logReportActivity($reportId, 'Generated Contact Follow-Up Percentage Report', $created_by);

        //return the id of the report that was generated
        return $reportId;
    }

    public function logReportActivity(int $report_id, string $action, int $user_id = null): bool
    {
        //string to hold the report "title"
        $reportTitle = '';

        //string to hold the report date
        $reportDate = '';

        //get the report data
        $report = $this->getReportById($report_id);

        $reportDate = formatDate($report['created_at']);

        //setup the report title
        $reportTitle = $report['report_type'];

        //log the report activity
        $activity = new Activity();
        $activity->logActivity($user_id, $action, 'Report:  ' . $reportTitle . ' - ID: ' . strval($report_id) . ' Date: ' . $reportDate);

        //return true
        return true;
    }

    /**
     * Delete a report by id
     *
     * @param int $id - the id of the report to delete
     *
     * @return bool
     */
    public function deleteReport(int $id): bool
    {
        //get the current date and time
        $date = date("Y-m-d H:i:s");

        //current user id
        $user_id = intval($_SESSION['user_id']);

        //boolean to track if the report was deleted
        $result = false;

        //create the SQL query
        $sql = "DELETE FROM reports WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param('i', $id);

        //execute the statement
        $stmt->execute();

        //check the result
        if ($stmt->affected_rows > 0) {
            $result = true;
        } else {
            $result = false;
        }

        //log the report activity and return the result
        if ($result) {
            //log the report activity
            $activity = new Activity();
            $activity->logActivity($user_id, 'Deleted Report', 'Report ' . strval($id));

            //return result
            return $result;
        } else {
            //return result
            return $result;
        }
    }

    /**
     * Get the report data for the chart
     * Create a pie chart with the percentage of students that were sent automatic email (indicated by the sender being NULL in the contact_log table),
     *
     * @param int $id - the id of the report to get the chart data for
     *
     * @return array
     */
    public function getChartableReportData(int $id): array
    {
        //get the report
        $report = $this->getReportById($id);

        //get the report data
        $reportData = $report['data'][0];

        //get the percentage of students that were followed up with
        $percentage = $reportData['percentage'];

        //get the percentage of students that were not followed up with
        $notFollowedUpWith = 100 - intval($percentage);

        //get the total number of contact attempts
        $total = $reportData['total'];

        //get the top sending user - that is not the automatic email sender
        $topSendingUser = $reportData['top_sending_user'];

        //declare the chart type
        $chartType = 'pie';

        //create an array to store the chart labels
        $labels = array();

        //create an array to store the chart data
        $data = array();

        //create an array to store the chart colors
        $colors = array();

        //setup the chart data for the percentage of students that were followed up with and not followed up with
        $labels[] = 'Followed Up With';
        $labels[] = 'Not Followed Up With';
        $data[] = $percentage;
        $data[] = $notFollowedUpWith;

        //setup the chart colors
        $colors[] = getRandomHexColor();
        $colors[] = getRandomHexColor();

        //setup the chart title
        $title = 'Percentage of Students Followed Up With';

        //setup the chart options
        $options = array(
            'responsive' => true,
            'maintainAspectRatio' => true,
            'aspectRatio' => 2,
            'legend' => array(
                'position' => 'left',
            ),
            'title' => array(
                'display' => true,
                'text' => $title,
            ),
        );

        //assemble the chart data
        $chartData = array(
            'type' => $chartType,
            'labels' => $labels,
            'datasets' => array(
                array(
                    'data' => $data,
                    'backgroundColor' => $colors,
                ),
            ),
            'title' => $title,
            'options' => $options,
        );

        //return the chart data
        return $chartData;
    }
}
