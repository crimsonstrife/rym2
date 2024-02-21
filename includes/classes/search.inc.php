<?php

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* Include the base application config file */
require_once(__DIR__ . '/../../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');

/**
 * The Search Class
 *
 * Contains functions for searching the other objects in the database
 *
 * @package RYM2
 */
class Search {
    /**
     * Search the database with a given search term or string
     *
     * @param string $searchTerm - the search term to search for
     *
     * @return array
     */
    public function search(string $searchTerm): array
    {
        //include the student class
        $studentData = new StudentSearch();

        //include the school class
        $schoolData = new School();

        //include the event class
        $eventData = new EventSearch();

        //create an array to store the results
        $results = array(
            'students' => array(),
            'schools' => array(),
            'events' => array(),
            'jobs' => array(),
            'wildcard' => array(
                'students' => array(),
                'schools' => array(),
                'events' => array(),
                'jobs' => array()
            )
        );

        //search the students table
        $studentResults = $studentData->searchStudents($searchTerm);

        //search the schools table
        $schoolResults = $schoolData->searchSchools($searchTerm);

        //search the events table
        $eventResults = $eventData->searchEvents($searchTerm);

        //TODO: search the jobs table

        //loop through the student results
        foreach ($studentResults as $studentResult) {
            //add the student result to the results array
            array_push($results['students'], $studentResult);
        }

        //loop through the school results
        foreach ($schoolResults as $schoolResult) {
            //add the school result to the results array
            array_push($results['schools'], $schoolResult);
        }

        //loop through the event results
        foreach ($eventResults as $eventResult) {
            //add the event result to the results array
            array_push($results['events'], $eventResult);
        }

        //TODO: loop through the job results

        //set the wildcard search term, replacing spaces with % signs
        $wildcardSearchTerm = str_replace(' ', '%', $searchTerm);

        //search the students table
        $wildcardStudentResults = $studentData->searchStudents($wildcardSearchTerm);

        //search the schools table
        $wildcardSchoolResults = $schoolData->searchSchools($wildcardSearchTerm);

        //search the events table
        $wildcardEventResults = $eventData->searchEvents($wildcardSearchTerm);

        //TODO: search the jobs table

        //loop through the student results
        foreach ($wildcardStudentResults as $wildcardStudentResult) {
            //add the student result to the results array
            array_push($results['wildcard']['students'], $wildcardStudentResult);
        }

        //loop through the school results
        foreach ($wildcardSchoolResults as $wildcardSchoolResult) {
            //add the school result to the results array
            array_push($results['wildcard']['schools'], $wildcardSchoolResult);
        }

        //loop through the event results
        foreach ($wildcardEventResults as $wildcardEventResult) {
            //add the event result to the results array
            array_push($results['wildcard']['events'], $wildcardEventResult);
        }

        //TODO: loop through the wildcard job results

        //return the results array
        return $results;
    }
}
