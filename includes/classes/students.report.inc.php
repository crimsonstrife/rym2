<?php

class StudentReports extends Report
{
    /**
     * Summary of Students sorted by date created
     * @return array
     */
    public function getReport(): array
    {
        //instantiates a new student object
        $students = new Student();
        //calls the getStudents method from the Students class
        $students_array = $students->getStudents();
        //sorts the students by date created, newest to oldest
        usort($students_array, function ($a, $b) {
            return $b['date_created'] <=> $a['date_created'];
        });
        //returns the sorted students array
        return $students_array;
    }

    /**
     * Save report to database
     * @param array $report
     * @return bool
     */
    public function saveReport(array $report): bool
    {
        //TODO: Implement saveReport() method.
        return true;
    }

    public function getReportById(int $id): array
    {
        return array();
    }

    public function findReport(string $search): array
    {
        return array();
    }
}
