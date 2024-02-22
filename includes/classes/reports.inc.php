<?php

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* Include the base application config file */
require_once(__DIR__ . '/../../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');

abstract class Report
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
     * Get all reports
     * @return array
     */
    public abstract function getReports(): array;

    /**
     * Get report by id
     * @param int $reportID
     * @return array
     */
    public abstract function getReportById(int $reportID): array;

    /**
     * Find report
     * @param string $search
     * @return array
     */
    public abstract function findReport(string $search): array;

    /**
     * Store report
     * Stores the report as a JSON string in the database reports table
     * @param string $report
     * @param int $createdBy
     * @return int The id of the report that was stored
     */
    public abstract function storeReport(string $report, int $createdBy): int;

    /**
     * Generate report
     * Returns the id of the report that was generated
     *
     * @param int $createdBy
     * @return int The id of the report that was generated
     */
    public abstract function generateReport(int $createdBy): int;

    /**
     * Log report activity
     * @param int $reportID
     * @param string $action
     * @param int $userID
     * @return bool
     */
    public abstract function logReportActivity(int $reportID, string $action, int $userID = null): bool;

    /**
     * Delete report
     * @param int $reportID
     * @return bool
     */
    public abstract function deleteReport(int $reportID): bool;

    /**
     * Get chartable report data
     * formats the report data into a format that can be used by chart.js for a bar chart
     * @param int $reportID - the id of the report to get the data for
     * @return array
     */
    public abstract function getChartableReportData(int $reportID): array;
}
