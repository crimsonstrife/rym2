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
     * Log report
     * Stores the report as a JSON string in the database reports table
     * @param string $report
     * @param int $created_by
     * @return int The id of the report that was logged
     */
    public function logReport(string $report, int $created_by): int
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

        //log the report and get the id of the report that was logged
        $reportId = $this->logReport($report, $created_by);

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
        $sql = "SELECT * FROM reports WHERE report_type = 'Top Degree by School' AND data LIKE ?";
        $search = '%' . $search . '%';

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

        //bind the parameters
        $stmt->bind_param('s', $search);

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
}
