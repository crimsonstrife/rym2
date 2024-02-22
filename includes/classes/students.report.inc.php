<?php

use Activity;

class StudentReports extends Report
{
    public function getReports(): array
    {
        // implementation goes here
        return array();
    }

    public function getReportById(int $reportID): array
    {
        // implementation goes here
        return array();
    }

    public function findReport(string $search): array
    {
        // implementation goes here
        return array();
    }

    public function storeReport(string $report, int $createdBy): int
    {
        // implementation goes here
        return 0;
    }

    public function generateReport(int $createdBy): int
    {
        // implementation goes here
        return 0;
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
        //log the report activity
        $activity = new Activity();
        $activity->logActivity($userID, $action, 'Report ' . strval($reportID));

        //return true
        return true;
    }

    public function deleteReport(int $reportID): bool
    {
        // implementation goes here
        return false;
    }

    public function getChartableReportData(int $reportID): array
    {
        // implementation goes here
        return array();
    }
} {
}
