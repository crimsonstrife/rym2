<?php
/**
 * Student Event Class file for the College Recruitment Application
 * Contains all the functions for the Student Event Class and handles all the student event attendance related tasks with the database.
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/16/2023
 *
 * @package RYM2
 * Filename: studentevent.inc.php
 * @version 1.0.0
 * @requires PHP 8.1.2+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* Include the base application config file */
require_once(__DIR__ . '/../../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');

/**
 * Student Event Class
 * This class is used to store and handle student event data
 */
class StudentEvent extends Student
{
    /**
     * Add a student to an event attendance list
     * Add a student to an event
     *
     * @param int $eventID
     * @param int $studentID
     * @return bool
     */
    public function addStudentToEvent(int $eventID, int $studentID): bool
    {
        //SQL statement to add a student to an event
        $sql = "INSERT INTO student_at_event (event_id, student_id) VALUES ('$eventID', '$studentID')";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query is successful
        if ($result) {
            //log the activity
            $activity = new Activity();
            $event = new Event();
            $student = new Student();
            $activity->logActivity(null, 'Student Added to Event', $student->getStudentFullName($studentID) . ' Added to Event ' . $event->getEventName($eventID));
            //Return true
            return true;
        }

        //If the query fails, return false
        return false;
    }

    /**
     * Remove a student from an event attendance list
     * Remove a student from an event
     *
     * @param int $eventID
     * @param int $studentID
     * @return bool
     */
    public function removeStudentFromEvent(int $eventID, int $studentID): bool
    {
        //SQL statement to remove a student from an event
        $sql = "DELETE FROM student_at_event WHERE event_id = $eventID AND student_id = $studentID";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query is successful
        if ($result) {
            //get the student name
            $studentName = $this->getStudentFullName($studentID);

            //get the event name
            $event = new Event();
            $eventName = $event->getEventName($eventID);

            //log the activity
            $activity = new Activity();
            //instance the session
            $session = new Session();
            //get the user id from the session
            $userID = intval($session->get('user_id')) ?? null;
            $activity->logActivity($userID, 'Deleted Student', 'Student ID: ' . $studentID . ' Student Name: ' . $studentName . ' from Event ID: ' . $eventID . ' Event Name: ' . $eventName);

            //Return true
            return true;
        }

        //If the query fails, return false
        return false;
    }

    /**
     * Student attendance
     * Get a list of students that attended an event
     *
     * @param int $eventID
     * @return array
     */
    public function getStudentEventAttendace(int $eventID): array
    {
        //SQL statement to get a list of students that attended an event
        $sql = "SELECT * FROM student_at_event WHERE event_id = $eventID";

        //Prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the result
        $result = $stmt->get_result();

        //create an array to hold the students
        $students = array();

        //if the result has rows
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }
        //Return the students array
        return $students;
    }

    /**
     * Student attendance
     * Get a list of events that a student attended
     *
     * @param int $studentID
     * @return array
     */
    public function getEventAttendaceByStudent(int $studentID): array
    {
        //SQL statement to get a list of events that a student attended
        $sql = "SELECT * FROM student_at_event WHERE student_id = $studentID";

        //Prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the result
        $result = $stmt->get_result();

        //create an array to hold the events
        $events = array();

        //if the result has rows
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $events[] = $row;
            }
        }
        //Return the events array
        return $events;
    }
}
