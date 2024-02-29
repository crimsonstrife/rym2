<?php

/**
 * Top Degree by School Report Class
 * This class is used to generate the Top Degree by School Report
 * It extends the Report class, and generates a report based on the data for the degree levels, majors, and schools using the student counts.
 * Stores the report in an array, and logs the report to the database as a JSON string.
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/16/2023
 *
 * @category   Admin
 * @package    RYM2
 * Filename:  topdegreebyschool.report.inc.php
 * @version 1.0.0
 * @requires PHP 8.1.2+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* Include the base application config file */
require_once(__DIR__ . '/../../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');

/**
 * This class is used to generate the Top Degree by School Report
 * It extends the Report class, and generates a report based on the data for the degree levels, majors, and schools using the student counts.
 * Stores the report in an array, and logs the report to the database as a JSON string.
 *
 * @category   Admin
 *
 */
class TopDegreeBySchoolReport extends Report
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
     * Get all reports from the Top Degree by School Report type
     * @return array
     */
    public function getReports(): array
    {
        //include the user class, so we can get the user name of the user that requested the report
        $userObject = new User();

        //create the SQL query
        $sql = "SELECT * FROM reports WHERE report_type = 'Top Degree by School'";

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
            $reportID = $row['id'];

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
                'id' => $reportID,
                'report_type' => 'Top Degree by School',
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
     * Get report by id
     * @param int $reportID
     * @return array
     */
    public function getReportById(int $reportID): array
    {
        //include the user class, so we can get the user name of the user that requested the report
        $userObject = new User();

        //create the SQL query
        $sql = "SELECT * FROM reports WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param('i', $reportID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create an array to store the reports
        $report = array();

        //loop through the result to format the data
        while ($row = $result->fetch_assoc()) {
            //get the report id
            $reportID = $row['id'];

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
                'id' => $reportID,
                'report_type' => 'Top Degree by School',
                'data' => $data,
                'created_by' => $createdByName,
                'created_at' => $row['created_at'],
                'updated_by' => $updatedByName,
                'updated_at' => $row['updated_at'],
            );
        }

        //return the report
        return $report;
    }

    /**
     * Store report
     * Stores the report as a JSON string in the database reports table
     * @param string $report
     * @param int $createdBy
     * @return int The id of the report that was stored.
     */
    public function storeReport(string $report, int $createdBy): int
    {
        //get the current date and time
        $date = date("Y-m-d H:i:s");

        //create the SQL query
        $sql = "INSERT INTO reports (report_type, data, created_by, created_at, updated_by, updated_at) VALUES ('Top Degree by School', ?, ?, ?, ?, ?)";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param('sisis', $report, $createdBy, $date, $createdBy, $date);

        //execute the statement
        $stmt->execute();

        //get the id of the report that was logged
        $reportId = $stmt->insert_id;

        //return the id of the report that was logged
        return $reportId;
    }

    /**
     * Generate Top Degree by School Report
     * Generates a new report based on the data for the degree levels, majors, and schools using the student counts.
     * @param int $createdBy // the user id of the user that requested the report
     * @return int The id of the report that was generated
     */
    public function generateReport(int $createdBy): int
    {
        //include the degree class
        $degreesObject = new Degree(); //contains the degree levels and majors

        //include the school class
        $schoolsObject = new School();

        //create an array to store the report
        $report = array();

        /* Create the SQL Query */
        //count the number of students that have each degree level and major combination by school, and sort by the student count
        $sql = "SELECT school, degree, major, COUNT(*) AS student_count FROM student GROUP BY school, degree, major ORDER BY student_count DESC";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //loop through the result to format the data
        while ($row = $result->fetch_assoc()) {
            //get the school id
            $school = $row['school'];

            //get the degree level id
            $degree = $row['degree'];

            //get the major id
            $major = $row['major'];

            //get the student count
            $studentCount = $row['student_count'];

            //get the school name
            $schoolName = $schoolsObject->getSchoolName(intval($school));

            //get the degree level name
            $degreeName = $degreesObject->getGradeNameById(intval($degree));

            //get the major name
            $majorName = $degreesObject->getMajorNameById(intval($major));

            //add the school name, degree level name, major name, and student count to the report
            $report[] = array(
                'school' => $schoolName,
                'degree' => $degreeName,
                'major' => $majorName,
                'student_count' => $studentCount,
            );
        }

        //ensure the report is still sorted by student count in descending order
        usort($report, function ($reportA, $reportB) {
            return $reportB['student_count'] <=> $reportA['student_count'];
        });

        //prepare the report for JSON encoding
        $report = json_encode($report);

        //store the report and get the id of the report that was stored
        $reportId = $this->storeReport($report, $createdBy);

        //log the report activity
        $this->logReportActivity($reportId, 'Generated Top Degree by School Report', $createdBy);

        //return the id of the report that was generated
        return $reportId;
    }

    /**
     * Find a report by search term
     * @param string $search
     * @return array
     */
    public function findReport(string $search): array
    {
        //include the user class, so we can get the user name of the user that requested the report
        $userObject = new User();

        //create the SQL query
        $sql = "SELECT * FROM reports WHERE report_type = 'Top Degree by School' AND (data LIKE ? OR created_at LIKE ? OR updated_at LIKE ?)";

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
            $reportID = $row['id'];

            //get the report data
            $data = $row['data'];

            //decode the JSON string to an array
            $data = json_decode($data, true);

            //get the user id of the user that created the report
            $createdBy = $row['created_by'];

            //get the user name of the user that created the report
            $createdByName = $userObject->getUserUsername($createdBy);

            //assemble a new array with the report id and the report data and the user id of the user that created the report
            $reports[] = array(
                'id' => $reportID,
                'report_type' => 'Top Degree by School',
                'data' => $data,
                'created_by' => $createdByName,
            );
        }

        //return the reports
        return $reports;
    }

    /**
     * Log report activity
     * @param int $reportID
     * @param string $action
     * @param int $userID
     * @return bool
     */
    public function logReportActivity(int $reportID, string $action, int $userID = null): bool
    {
        //string to hold the report "title"
        $reportTitle = '';

        //string to hold the report date
        $reportDate = '';

        //get the report data
        $report = $this->getReportById($reportID);

        $reportDate = formatDate($report['created_at']);

        //setup the report title
        $reportTitle = $report['report_type'];

        //log the report activity
        $activity = new Activity();
        $activity->logActivity($userID, $action, 'Report:  ' . $reportTitle . ' - ID: ' . strval($reportID) . ' Date: ' . $reportDate);

        //return true
        return true;
    }

    /**
     * Delete a report by id
     *
     * @param int $reportID - the id of the report to delete
     *
     * @return bool
     */
    public function deleteReport(int $reportID): bool
    {
        //instance of the session class
        $session = new Session();

        //current user id
        $userID = intval($session->get('user_id'));

        //boolean to track if the report was deleted
        $result = false;

        //create the SQL query
        $sql = "DELETE FROM reports WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param('i', $reportID);

        //execute the statement
        $stmt->execute();

        //check the result
        if ($stmt->affected_rows > 0) {
            $result = true;
        }

        //log the report activity and return the result
        if ($result) {
            //log the report activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Deleted Report', 'Report ' . strval($reportID));

            //return result
            return $result;
        }

        //return result
        return $result;
    }

    /**
     * Get chartable report data
     * formats the report data into a format that can be used by chart.js for a chart
     * this report is a pie chart
     * @param int $reportID - the id of the report to get the data for
     * @return array
     */
    public function getChartableReportData(int $reportID): array
    {
        //get the report by id
        $report = $this->getReportById($reportID);

        //get the report data
        $reportData = $report['data'];

        //declare the chart type
        $chartType = 'pie';

        //create an array to store the chart labels
        $labels = array();

        //create an array to store the chart data
        $data = array();

        //create an array to store the chart colors
        $colors = array();

        //loop through the report data to format the data for the chart
        foreach ($reportData as $row) {
            //get the school name
            $school = $row['school'];

            //get the degree level name
            $degree = $row['degree'];

            //get the major name
            $major = $row['major'];

            //get the student count
            $studentCount = $row['student_count'];

            //setup the label to be the school name, degree level name, and major name
            $label = $school . ' - ' . $degree . ' - ' . $major;

            //add the label to the labels array
            $labels[] = $label;

            //add the student count to the data array
            $data[] = $studentCount;

            //add a random hex color to the colors array
            $colors[] = getRandomHexColor();
        }

        //create an array to store the chart data
        $chartData = array(
            'type' => $chartType,
            'labels' => $labels,
            'datasets' => array(
                array(
                    'label' => 'Top Degree by School',
                    'data' => $data,
                    'backgroundColor' => $colors,
                ),
            ),
            'title' => 'Top Degree by School',
            'colors' => $colors,
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
                        'text' => 'Top Degree by School',
                    ),
                ),
            ),
        );

        //return the chart data
        return $chartData;
    }
}
