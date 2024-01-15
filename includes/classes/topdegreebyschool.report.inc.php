<?php

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
        $this->mysqli = connectToDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
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
     * @param int $created_by
     * @return int The id of the report that was stored.
     */
    public function storeReport(string $report, int $created_by): int
    {
        //get the current date and time
        $date = date("Y-m-d H:i:s");

        //create the SQL query
        $sql = "INSERT INTO reports (report_type, data, created_by, created_at, updated_by, updated_at) VALUES ('Top Degree by School', ?, ?, ?, ?, ?)";

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
     * Generate Top Degree by School Report
     * Generates a new report based on the data for the degree levels, majors, and schools using the student counts.
     * @param int $created_by // the user id of the user that requested the report
     * @return int The id of the report that was generated
     */
    public function generateReport(int $created_by): int
    {
        //include the degree class
        $degreesObject = new Degree(); //contains the degree levels and majors

        //include the school class
        $schoolsObject = new School();

        //include the user class, so we can get the user name of the user that requested the report
        $userObject = new User();

        //create an array to store the report
        $report = array();

        /* Create the SQL Query */
        //count the number of students that have each degree level and major combination by school, and sort by the student count
        $sql = "SELECT school, degree, major, COUNT(*) AS student_count FROM student GROUP BY school, degree, major ORDER BY student_count DESC";

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

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
        usort($report, function ($a, $b) {
            return $b['student_count'] <=> $a['student_count'];
        });

        //prepare the report for JSON encoding
        $report = json_encode($report);

        //store the report and get the id of the report that was stored
        $reportId = $this->storeReport($report, $created_by);

        //log the report activity
        $this->logReportActivity($reportId, 'Generated Top Degree by School Report', $created_by);

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
     * @param int $report_id
     * @param string $action
     * @param int $user_id
     * @return bool
     */
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
     * Get chartable report data
     * formats the report data into a format that can be used by chart.js for a chart
     * this report is a pie chart
     * @param int $id - the id of the report to get the data for
     * @return array
     */
    public function getChartableReportData(int $id): array
    {
        //include the school class
        $schoolsObject = new School();

        //get the report by id
        $report = $this->getReportById($id);

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

            //get the school id
            $schoolId = $schoolsObject->getSchoolIdByName($school);

            //get the school color
            $color = $schoolsObject->getSchoolColor($schoolId);

            //add the school color to the colors array
            //$colors[] = $color;

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
