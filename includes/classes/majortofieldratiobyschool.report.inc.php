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
 * It extends the Report class, and generates a report based on the ratio of Majors to Fields of Study by School.
 * Stores the report in an array, and logs the report to the database as a JSON string.
 *
 * @category   Admin
 *
 */
class MajorToFieldRatioBySchoolReport extends Report
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
     * Get all reports from the Major to Field Ratio by School Report type
     * @return array
     */
    public function getReports(): array
    {
        //include the user class, so we can get the user name of the user that requested the report
        $userObject = new User();

        //create the SQL query
        $sql = "SELECT * FROM reports WHERE report_type = 'Major to Field Ratio by School'";

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
                'report_type' => 'Major to Field Ratio by School',
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
     * @param int $id
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

        //create an array to store the reports
        $report = array();

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
            $report = array(
                'id' => $id,
                'report_type' => 'Major to Field Ratio by School',
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
     * Find a report by search term
     * @param string $search
     * @return array
     */
    public function findReport(string $search): array
    {
        //include the user class, so we can get the user name of the user that requested the report
        $userObject = new User();

        //create the SQL query
        $sql = "SELECT * FROM reports WHERE report_type = 'Major to Field Ratio by School' AND (data LIKE ? OR created_at LIKE ? OR updated_at LIKE ?)";

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

            //get the user id of the user that created the report
            $createdBy = $row['created_by'];

            //get the user name of the user that created the report
            $createdByName = $userObject->getUserUsername($createdBy);

            //assemble a new array with the report id and the report data and the user id of the user that created the report
            $reports[] = array(
                'id' => $id,
                'report_type' => 'Major to Field Ratio by School',
                'data' => $data,
                'created_by' => $createdByName,
            );
        }

        //return the reports
        return $reports;
    }

    /**
     * Store report
     * Stores the report as a JSON string in the database reports table
     * @param string $report
     * @param int $created_by
     * @return int The id of the report that was stored.
     */
    public function storeReport(string $report, int $created_by): int
    {
        //get the current date and time
        $date = date("Y-m-d H:i:s");

        //create the SQL query
        $sql = "INSERT INTO reports (report_type, data, created_by, created_at, updated_by, updated_at) VALUES ('Major to Field Ratio by School', ?, ?, ?, ?, ?)";

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
     * Generate Major to Field Ratio by School Report
     * Generates a new report based on the ratio of Majors to Fields of Study by School.
     * @param int $created_by // the user id of the user that requested the report
     * @return int The id of the report that was generated
     */
    public function generateReport(int $created_by): int
    {
        //include the job field class, so we can get the job field name
        $jobFieldObject = new JobField();

        //include the school class, so we can get the school name
        $schoolObject = new School();

        //include the degree class, so we can get the degree name
        $degreeObject = new Degree();

        //include the user class, so we can get the user name of the user that requested the report
        $userObject = new User();

        //create an array to store the report
        $report = array();

        /* Create the SQL Query */
        $sql = "SELECT s.school, s.major, s.interest, COUNT(*) AS total_students, ROUND(COUNT(*) / t.total_students_in_school, 2) AS ratio_in_school FROM student s JOIN ( SELECT school, COUNT(*) AS total_students_in_school FROM student GROUP BY school ) t ON s.school = t.school GROUP BY s.school, s.major, s.interest ORDER BY s.school, s.major, s.interest;";

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //loop through the result to format the data
        while ($row = $result->fetch_assoc()) {
            //get the school id
            $schoolId = intval($row['school']);

            //get the school name
            $schoolName = $schoolObject->getSchoolName($schoolId);

            //get the major id
            $majorId = intval($row['major']);

            //get the major name
            $majorName = $degreeObject->getMajorNameById($majorId);

            //get the interest id
            $interestId = intval($row['interest']);

            //get the field name
            $fieldName = $jobFieldObject->getSubjectName($interestId);

            //get the ratio
            $ratio = floatval($row['ratio_in_school']);

            //assemble a new array with the school name, major name, field name, and ratio
            $report[] = array(
                'school' => $schoolName,
                'major' => $majorName,
                'field' => $fieldName,
                'ratio' => $ratio,
            );
        }

        //store the report as a JSON string
        $report = json_encode($report);

        //store the report in the database
        $reportId = $this->storeReport($report, $created_by);

        //log the report activity
        $this->logReportActivity($reportId, 'Generated Major to Field Ratio Report', $created_by);

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
     * Get chartable report data
     * formats the report data into a format that can be used by chart.js for a chart
     * this report is a stacked bar chart
     * @param int $id - the id of the report to get the data for
     * @return array
     */
    public function getChartableReportData(int $id): array
    {
        //get the report
        $report = $this->getReportById($id);

        //get the report data
        $reportData = $report['data'];

        /*prepare the report data for the chart*/
        $formattedReportData = array();

        //loop through the report data
        foreach ($reportData as $row) {
            $school = $row['school'];
            $major = $row['major'];
            $field = $row['field'];
            $ratio = $row['ratio'];

            //check if the school is already in the array
            if (!isset($formattedReportData[$school])) {
                //if the school is not in the array, add it
                $formattedReportData[$school] = array(
                    'labels' => array(), //fields will be the labels
                    'datasets' => array(),
                );
            }

            //check if the major is already in the array
            if (!isset($formattedReportData[$school]['datasets'][$major])) {
                //if the major is not in the array, add it
                $formattedReportData[$school]['datasets'][$major] = array(
                    'school' => $school,
                    'label' => $major,
                    'data' => array(), //the ratio will be the data
                    'backgroundColor' => getRandomHexColor(),
                );
            }

            //add the field to the labels array
            if (!in_array($field, $formattedReportData[$school]['labels'])) {
                $formattedReportData[$school]['labels'][] = $field;
            }

            //add the ratio to the data array for the appropriate dataset
            $formattedReportData[$school]['datasets'][$major]['data'][] = $ratio;
        }

        //chart type
        $chartType = 'bar';

        //create an array to store the chart data
        $chartData = array(
            'type' => $chartType,
            'labels' => array(),
            'datasets' => array(),
            'title' => 'Major to Field Ratio by School',
            'options' => array(
                'responsive' => 'true',
                'maintainAspectRatio' => 'true',
                'aspectRatio' => 2,
                'plugins' => array(
                    'legend' => array(
                        'position' => 'right',
                    ),
                    'title' => array(
                        'display' => 'true',
                        'text' => 'Major to Field Ratio by School',
                    ),
                ),
                'scales' => array(
                    'x' => array(
                        'stacked' => 'false',
                    ),
                    'y' => array(
                        'stacked' => 'false',
                    ),
                ),
            ),
        );

        //loop through the formatted report data
        foreach ($formattedReportData as $school => $data) {
            //add the school to the chart labels
            $chartData['labels'][] = $school;
            //variable for the stack number
            $stack = 0;
            //loop through the datasets, and increment the stack number
            foreach ($data['datasets'] as $dataset) {
                //add the dataset to the chart data
                $chartData['datasets'][] = array(
                    //set the key as the label
                    'label' => array_keys($data['datasets'])[$stack],
                    'data' => $dataset['data'],
                    'backgroundColor' => $dataset['backgroundColor'],
                    'stack' => strval($stack),
                );
                //increment the stack number
                foreach ($dataset['school'] as $data) {
                    //if the school changes, increment the stack number
                    if ($data != $school) {
                        $stack++;
                    }
                }
            }
        }

        //debug
        error_log(print_r($chartData, true));

        //return the chart data
        return $chartData;
    }
}
