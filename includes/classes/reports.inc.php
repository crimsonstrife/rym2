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
        $this->mysqli = connectToDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
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
     * @param int $id
     * @return array
     */
    public abstract function getReportById(int $id): array;

    /**
     * Find report
     * @param string $search
     * @return array
     */
    public abstract function findReport(string $search): array;

    /**
     * Log report
     * Stores the report as a JSON string in the database reports table
     * @param string $report
     * @param int $created_by
     * @return int The id of the report that was logged
     */
    public abstract function logReport(string $report, int $created_by): int;

    /**
     * Generate report
     * Returns the id of the report that was generated
     *
     * @param int $created_by
     * @return int The id of the report that was generated
     */
    public abstract function generateReport(int $created_by): int;
}
