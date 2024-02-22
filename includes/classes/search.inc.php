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
        $schoolData = new SchoolSearch();

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

/**
 * Student Search Class
 * This class is used to search for students
 */
class StudentSearch extends Student
{
    /**
     * Search Students
     * Search for students by their first name, last name, email, or phone number using a search term
     *
     * @param string $searchTerm
     * @return array
     */
    public function searchStudents(string $searchTerm): array
    {
        //SQL statement to search for students by their first name, last name, email, or phone number using a search term
        $sql = "SELECT * FROM student WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ?";
        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);
        //setup the search term
        $searchTerm = "%" . $searchTerm . "%";
        //bind the parameters
        $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();

        //create an array to hold the students
        $students = array();

        //if the result has rows
        if ($result->num_rows > 0) {
            //loop through the rows
            while ($row = $result->fetch_assoc()) {
                //add the row to the students array
                $students[] = $row;
            }
        }

        //Return the students array
        return $students;
    }
}

/**
 * School Search Class
 * This class is used to search for schools
 */
class SchoolSearch extends School
{
    /**
     * Search Schools
     * Search the schools table for a school name, address, city, state, or zip code using a search term
     *
     * @param string $searchTerm
     *
     * @return array $schools
     */
    public function searchSchools(string $searchTerm): array
    {
        //SQL statement to search for a school name, address, city, state, or zip code using a search term
        $sql = "SELECT * FROM school WHERE name LIKE ? OR address LIKE ? OR city LIKE ? OR state LIKE ? OR zipcode LIKE ?";
        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);
        //setup the search term
        $searchTerm = "%" . $searchTerm . "%";
        //bind the parameters
        $stmt->bind_param("sssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();

        //create an array to store the schools
        $schools = array();

        //if there are schools in the database, add them to the array
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($schools, $row);
            }
        }

        //return the schools array
        return $schools;
    }
}

/**
 * Event Search Class
 * Contains functions for searching the events in the database
 */
class EventSearch extends Event
{
    /**
     * Search Events
     * Search events by name, date, or location, gets the location name from the school database
     *
     * @param string $searchTerm search string
     *
     * @return array
     */
    public function searchEvents(string $searchTerm): array
    {
        //SQL statement to search events, cross referencing the location with the school database id
        $sql = "SELECT event.id, event.name, event.event_date, school.name AS location FROM event INNER JOIN school ON event.location = school.id WHERE event.name LIKE ? OR event.event_date LIKE ? OR school.name LIKE ?";
        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);
        //setup the search term
        $searchTerm = "%" . $searchTerm . "%";
        //bind the parameters
        $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();

        //array to hold the events
        $events = array();

        //if the result has rows, loop through the rows and add them to the events array
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $events[] = $row;
            }
        }

        //return the events array
        return $events;
    }
}
