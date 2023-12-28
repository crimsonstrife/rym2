<?php

class StudentReports extends Report
{
    public function getReports(): array
    {
        // implementation goes here
        return array();
    }

    public function getReportById(int $id): array
    {
        // implementation goes here
        return array();
    }

    public function findReport(string $search): array
    {
        // implementation goes here
        return array();
    }

    public function storeReport(string $report, int $created_by): int
    {
        // implementation goes here
        return 0;
    }

    public function generateReport(int $created_by): int
    {
        // implementation goes here
        return 0;
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

    public function deleteReport(int $id): bool
    {
        // implementation goes here
        return false;
    }

    public function getChartableReportData(int $id): array
    {
        // implementation goes here
        return array();
    }
} {
}
