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
class JobsByFieldReport extends Report
{
    //Reference to the database
    private $mysqli;

    //Instantiate the database connection
    public function __construct()
    {
        $this->mysqli = connectToDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    }

    //Close the database connection when the object is destroyed
    public function __destruct()
    {
        closeDatabaseConnection($this->mysqli);
    }

    /**
     * Get all reports from the Jobs by Field Report type
     * @return array
     */
    public function getReports(): array
    {
        //include the user class, so we can get the user name of the user that requested the report
        $userObject = new User();

        //create the SQL query
        $sql = "SELECT * FROM reports WHERE report_type = 'Jobs by Field'";

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

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
                'report_type' => 'Jobs by Field',
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
     * Get a report by the report id
     * @param int $id - the id of the report to get
     * @return array
     */
    public function getReportById(int $id): array
    {
        //include the user class, so we can get the user name of the user that requested the report
        $userObject = new User();

        //create the SQL query
        $sql = "SELECT * FROM reports WHERE id = ?";

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

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
            'report_type' => 'Jobs by Field',
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
     * Find a report by the search string
     * @param string $search - the search string to use to find the report
     * @return array
     */
    public function findReport(string $search): array
    {
        //include the user class, so we can get the user name of the user that requested the report
        $userObject = new User();

        //create the SQL query
        $sql = "SELECT * FROM reports WHERE report_type = 'Jobs by Field' AND (data LIKE ? OR created_at LIKE ? OR updated_at LIKE ?)";

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

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
                'report_type' => 'Jobs by Field',
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
     * @param string $report - the report data to store
     * @param int $created_by - the user id of the user that created the report
     * @return int
     */
    public function storeReport(string $report, int $created_by): int
    {
        //get the current date and time
        $date = date("Y-m-d H:i:s");

        //create the SQL query
        $sql = "INSERT INTO reports (report_type, data, created_by, created_at, updated_by, updated_at) VALUES ('Jobs by Field', ?, ?, ?, ?, ?)";

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

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
     * Generate Jobs by Field Report
     * Generates a new report based on the data for the jobs and job fields to show how many jobs are open in each field.
     * @param int $created_by // the user id of the user that requested the report
     * @return int The id of the report that was generated
     */
    public function generateReport(int $created_by): int
    {
        //include the job class, so we can get the job data
        $jobObject = new Job();

        //include the job field class, so we can get the job field data
        $jobFieldObject = new JobField();

        //include the user class, so we can get the user name of the user that requested the report
        $userObject = new User();

        //create an array to store the report
        $report = array();

        /* Create the SQL Query */
        //Count the number of times each field repeats in the jobs table
        $sql = "SELECT COUNT(*) AS count, field FROM jobs GROUP BY field";

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $countResult = $stmt->get_result();

        //create an array to store the job counts
        $jobCounts = array();

        //loop through the result to format the data
        while ($row = $countResult->fetch_assoc()) {
            //get the job count
            $count = $row['count'];

            //get the job field id
            $jobFieldId = intval($row['field']);

            //get the job field name
            $jobFieldName = $jobFieldObject->getSubjectName($jobFieldId);

            //assemble a new array with the job field name and the job count
            $jobCounts[] = array(
                'job_field_id' => $jobFieldId,
                'job_field_name' => $jobFieldName,
                'job_count' => $count,
            );
        }

        //loop through the job counts, for each job field, get the list of jobs
        foreach ($jobCounts as $jobCount) {
            //get the job field id
            $jobFieldId = $jobCount['job_field_id'];

            //get the job field name
            $jobFieldName = $jobCount['job_field_name'];

            //get the job count
            $count = $jobCount['job_count'];

            //get the list of jobs for the job field
            $jobs = $jobObject->getJobsByField($jobFieldId);

            //get the list of job names
            $jobNames = array_column($jobs, 'name');

            //convert the job names array into a string
            $jobNames = implode(', ', $jobNames);

            //assemble a new array with the job field name and the job count and the list of jobs
            $report[] = array(
                'jobs' => $jobNames,
                'job_count' => $count,
                'job_field_name' => $jobFieldName,
            );
        }

        //sort the report by the job count
        usort($report, function ($a, $b) {
            return $b['job_count'] <=> $a['job_count'];
        });

        //encode the report array to a JSON string
        $report = json_encode($report);

        //store the report in the database
        $reportId = $this->storeReport($report, $created_by);

        //log the report activity
        $this->logReportActivity($reportId, 'Generated Jobs by Field Report', $created_by);

        //return the id of the report that was generated
        return $reportId;
    }

    public function logReportActivity(int $report_id, string $action, int $user_id = null): bool
    {
        //log the report activity
        $activity = new Activity();
        $activity->logActivity($user_id, $action, 'Report ' . strval($report_id));

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
        $stmt = $this->mysqli->prepare($sql);

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
     * Get the chartable data for the report
     * @param int $id - the id of the report to get the chartable data for
     * @return array
     */
    public function getChartableReportData(int $id): array
    {
        //get the report by id
        $report = $this->getReportById($id);

        //get the report data
        $reportData = $report['data'];

        //declare the chart type
        $chartType = 'bar';

        //create an array to store the chart labels
        $labels = array();

        //create an array to store the chart data
        $data = array();

        //create an array to store the chart colors
        $colors = array();

        //loop through the report data to format the data, the jobs should appear as the hover text for each bar
        foreach ($reportData as $row) {
            //get the job field name
            $jobFieldName = $row['job_field_name'];

            //get the job count
            $jobCount = $row['job_count'];

            //get the list of jobs
            $jobs = $row['jobs'];

            //add the job field name to the labels array
            $labels[] = $jobFieldName;

            //add the job count to the data array
            $data[] = $jobCount;

            //add a random hex color to the colors array
            $colors[] = getRandomHexColor();
        }

        //create an array to store the chart data
        $chartData = array(
            'labels' => $labels,
            'datasets' => array(
                array(
                    'data' => $data,
                    'backgroundColor' => $colors,
                ),
            ),
            'type' => $chartType,
            'title' => 'Jobs by Field',
            'options' => array(
                'responsive' => true,
                'maintainAspectRatio' => true,
                'aspectRatio' => 2,
                'plugins' => array(
                    'legend' => array(
                        'position' => 'right',
                    ),
                    'title' => array(
                        'display' => true,
                        'text' => 'Jobs by Field',
                    ),
                ),
            ),
        );

        //return the chart data
        return $chartData;
    }
} {
}
