<?php

/**
 * Students Class file for the College Recruitment Application
 * Contains all the functions for the Student Class and handles all the student related tasks with the database.
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/16/2023
 *
 * @package RYM2
 * Filename: students.inc.php
 * @version 1.0.0
 * @requires PHP 7.2.5+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* Include the base application config file */
require_once(__DIR__ . '/../../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');

class Student
{
    //Reference to the database
    protected $mysqli;

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
     * Get all students from the database
     *
     * @return array
     */
    public function getStudents(): array
    {
        //SQL statement to get all students
        $sql = "SELECT * FROM student";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create an array to hold the students
        $students = array();

        //If the query returns a result
        if ($result) {
            //Loop through the result and add each row to the students array
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }

        //return the students array
        return $students;
    }

    /**
     * Get a student by their ID
     *
     * @param int $studentID
     * @return array
     */
    public function getStudentById(int $studentID): array
    {
        //SQL statement to get a student by their ID
        $sql = "SELECT * FROM student WHERE id = $studentID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create an array to hold the student
        $student = array();

        //If the query returns a result
        if ($result) {
            //check if the result has rows
            if ($result->num_rows > 0) {
                //add the student to the student array
                $student = $result->fetch_assoc();
            }
        }

        //return the student array
        return $student;
    }

    /**
     * Get a student by their email address
     *
     * @param string $email
     * @return array
     */
    public function getStudentByEmail(string $email): array
    {
        //clean the email variable
        $email = $this->mysqli->real_escape_string($email);

        //SQL statement to get a student by their email address
        $sql = "SELECT * FROM student WHERE email = '$email'";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create an array to hold the student
        $student = array();

        //If the query returns a result
        if ($result) {
            //check if the result has rows
            if ($result->num_rows > 0) {
                //Return the student
                $student = $result->fetch_assoc();
            }
        }

        //return the student array
        return $student;
    }

    /**
     * Get a student first name
     *
     * @param int $studentID
     * @return string
     */
    public function getStudentFirstName(int $studentID): string
    {
        //SQL statement to get a student first name
        $sql = "SELECT first_name FROM student WHERE id = $studentID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //placeholder for the first name
        $firstName = "";

        //If the query returns a result
        if ($result) {
            //set the student first name
            $firstName = $result->fetch_assoc()['first_name'];
        }

        //if the first name is empty or null, return an empty string
        if (empty($firstName) || $firstName == null) {
            $firstName = "";
        }

        //return the student first name
        return $firstName;
    }

    /**
     * Get a student last name
     *
     * @param int $studentID
     * @return string
     */
    public function getStudentLastName(int $studentID): string
    {
        //SQL statement to get a student last name
        $sql = "SELECT last_name FROM student WHERE id = $studentID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //placeholder for the last name
        $lastName = "";

        //If the query returns a result
        if ($result) {
            //set the student last name
            $lastName = $result->fetch_assoc()['last_name'];
        }

        //if the last name is empty or null, return an empty string
        if (empty($lastName) || $lastName == null) {
            $lastName = "";
        }

        //return the student last name
        return $lastName;
    }

    /**
     * Get a student full name
     *
     * @param int $studentID
     * @return string
     */
    public function getStudentFullName(int $studentID): string
    {
        //get the student first name
        $firstName = $this->getStudentFirstName($studentID);
        //get the student last name
        $lastName = $this->getStudentLastName($studentID);
        //format the student full name
        $fullName = $firstName . " " . $lastName;
        //return the student full name
        return $fullName;
    }

    /**
     * Get a student email
     *
     * @param int $studentID
     * @return string
     */
    public function getStudentEmail(int $studentID): string
    {
        //SQL statement to get a student email
        $sql = "SELECT email FROM student WHERE id = $studentID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //placeholder for the email
        $email = "";

        //If the query returns a result
        if ($result) {
            //set the student email
            $email = $result->fetch_assoc()['email'];
        }

        //return the student email
        return $email;
    }

    /**
     * Get a student phone number
     *
     * @param int $studentID
     * @return string
     */
    public function getStudentPhone(int $studentID): string
    {
        //SQL statement to get a student phone number
        $sql = "SELECT phone FROM student WHERE id = $studentID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //placeholder for the phone number
        $phone = "";

        //If the query returns a result
        if ($result) {
            //set the student phone number
            $phone = $result->fetch_assoc()['phone'];
        }

        //Return the student phone number
        return $phone;
    }

    /**
     * Get a students job type preference from the job ID
     *
     * @param int $studentID //student id
     * @return string //position - 'FULL', 'PART', 'INTERN'
     */
    public function getStudentPosition(int $studentID): string
    {
        //SQL statement to get a students job type preference from the job ID
        $sql = "SELECT position FROM student WHERE id = $studentID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //placeholder for the position
        $position = "";

        //If the query returns a result
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $position = $row['position'];
            }
        }
        //depending on the type, return the correct string
        switch ($position) {
            case 'FULL':
                return 'Full Time';
            case 'PART':
                return 'Part Time';
            case 'INTERN':
                return 'Internship';
            default:
                return 'Internship';
        }
    }

    /**
     * Set a student's first name
     *
     * @param int $studentID
     * @param string $firstName
     * @return bool
     */
    public function setStudentFirstName(int $studentID, string $firstName): bool
    {
        //SQL statement to set a student's first name
        $sql = "UPDATE students SET first_name = '$firstName' WHERE id = $studentID";

        //Prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the result
        $result = $stmt->get_result();

        //If the query is successful
        if ($result) {
            //Return true
            return true;
        }

        //Return false
        return false;
    }

    /**
     * Set a student's last name
     *
     * @param int $studentID
     * @param string $lastName
     * @return bool
     */
    public function setStudentLastName(int $studentID, string $lastName): bool
    {
        //SQL statement to set a student's last name
        $sql = "UPDATE students SET last_name = '$lastName' WHERE id = $studentID";

        //Prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the result
        $result = $stmt->get_result();

        //If the query is successful
        if ($result) {
            //Return true
            return true;
        }

        //Return false
        return false;
    }

    /**
     * Set a student's email address
     *
     * @param int $studentID
     * @param string $email
     * @return bool
     */
    public function setStudentEmail(int $studentID, string $email): bool
    {
        //SQL statement to set a student's email address
        $sql = "UPDATE students SET email = '$email' WHERE id = $studentID";

        //Prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the result
        $result = $stmt->get_result();

        //If the query is successful
        if ($result) {
            //Return true
            return true;
        }

        //Return false
        return false;
    }

    /**
     * Set a student's job type preference
     *
     * @param int $studentID
     * @param string $position
     * @return bool
     */
    public function setStudentPosition(int $studentID, string $position): bool
    {
        //SQL statement to set a student's job type preference
        $sql = "UPDATE students SET position = '$position' WHERE id = $studentID";

        //Prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the result
        $result = $stmt->get_result();

        //If the query is successful
        if ($result) {
            //Return true
            return true;
        }

        //Return false
        return false;
    }

    /**
     * Get a student's area of interest
     * @param int $studentID
     * @return ?int //area of interest id
     */
    public function getStudentInterest(int $studentID): ?int
    {
        //SQL statement to get a student's area of interest
        $sql = "SELECT interest FROM student WHERE id = $studentID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create a variable to hold the interest
        $interest = null;

        //If the query returns a result
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $interest = intval($row['interest']);
            }
        }
        //Return the interest
        return $interest;
    }

    /**
     * Add a new student to the database
     *
     * @param StudentData $studentData //student data
     * @return bool
     */
    public function addStudent(StudentData $studentData): bool
    {
        //get the student address object from the student data
        $studentAddress = $studentData->studentAddress;

        //get the student education object from the student data
        $studentEducation = $studentData->studentEducation;

        //get the student data, escape the strings to prevent SQL injection
        $firstName = $studentData->getEscapedString('firstName');
        $lastName = $studentData->getEscapedString('lastName');
        $email = $studentData->getEscapedString('email');
        $phone = $studentData->getEscapedString('phone');
        $address = $studentAddress->getEscapedString('address');
        $city = $studentAddress->getEscapedString('city');
        $state = $studentAddress->getEscapedString('state');
        $zip = $studentAddress->getEscapedString('zipcode');
        $degreeID = intval($studentData->studentEducation->degree);
        $majorID = intval($studentData->studentEducation->major);
        $school = intval($studentData->studentEducation->school);
        $graduation = $studentEducation->getEscapedString('graduation');
        $position = $studentData->getEscapedString('position');
        $areaID = intval($studentData->interest);

        //get current timestamp to set the created_at and updated_at fields
        $timestamp = date('Y-m-d H:i:s');

        //get current user id from session if available
        $session = new Session();
        $createdBy = intval($session->get('user_id')) ?? null; //if the session is not available, set the created_by to null

        //SQL statement to add a new student to the database
        $sql = "INSERT INTO student (first_name, last_name, email, phone, address, city, state, zipcode, degree, major, school, graduation, position, interest, created_at, updated_at, created_by) VALUES ('$firstName', '$lastName', '$email', '$phone', '$address', '$city', '$state', '$zip', '$degreeID', '$majorID', '$school', '$graduation', '$position', '$areaID', '$timestamp', '$timestamp', '$createdBy')";

        //Prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //check if the student was added, look for a student by their email
        $student = $this->getStudentByEmail($email);

        //if the student was added, return true
        if (!empty($student) && $student != null && isset($student)) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($createdBy, 'Added Student', 'Student ID: ' . $student['id'] . ' Student Name: ' . $student['first_name'] . ' ' . $student['last_name']);

            return true;
        }

        //if the student was not added, return false
        return false;
    }

    /**
     * Get Student Contact History
     * Get the contact history for a student
     * @param int $studentID
     * @return array
     */
    public function getStudentContactHistory(int $studentID): array
    {
        //include the contact class, so we can get the contact history
        $contactObject = new Contact();

        //get the contact history for the student
        $contactHistory = $contactObject->getStudentContactLog($studentID);

        //return the contact history
        return $contactHistory;
    }

    /**
     * Delete Student Contact History
     * Delete the contact history for a student
     * @param int $studentID
     * @return bool
     */
    private function deleteStudentContactHistory(int $studentID): bool
    {
        //include the contact class, so we can delete the contact history
        $contactObject = new Contact();

        //get all the contact history for the student
        $contactHistory = $this->getStudentContactHistory($studentID);

        //set the placeholder for the result
        $result = false;

        //if the student has any contact history, loop through the history and delete each record
        if (!empty($contactHistory)) {
            foreach ($contactHistory as $contact) {
                $contactID = intval($contact['id']);
                $result = $contactObject->removeContact($contactID);
            }
        }

        //log the activity if the contact history was deleted
        if ($result) {
            //get the student name
            $studentName = $this->getStudentFullName($studentID);

            //log the activity
            $activity = new Activity();
            //instance the session class
            $session = new Session();
            //get the current user id
            $userID = intval($session->get('user_id')) ?? null;
            $activity->logActivity($userID, 'Deleted Student Contact History', 'Student ID: ' . $studentID . ' Student Name: ' . $studentName);
        }

        //return the result
        return $result;
    }

    /**
     * Get Students by Interest
     *
     * @param int $interestID
     *
     * @return array
     */
    public function getStudentsByInterest(int $interestID): array
    {
        //SQL statement to get students by interest
        $sql = "SELECT * FROM student WHERE interest = $interestID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

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

    /**
     * Delete a student from the database
     *
     * @param int $studentID
     *
     * @return bool
     */
    public function deleteStudent(int $studentID): bool
    {
        //instance the student event class
        $studentEvent = new StudentEvent();

        //get the name of the student
        $studentName = $this->getStudentFullName($studentID);

        //set the placeholder for the result
        $result = false;

        //check if the student has attended any events
        $eventsAttended = $studentEvent->getEventAttendaceByStudent($studentID);

        //if the student has attended any events
        if (!empty($eventsAttended)) {
            //loop through the events
            foreach ($eventsAttended as $event) {
                //remove the student from the event
                $studentEvent->removeStudentFromEvent(intval($event['event_id']), $studentID);
            }
        }

        //check if the student has any contact history
        $contactHistory = $this->getStudentContactHistory($studentID);

        //if the student has any contact history
        if (!empty($contactHistory)) {
            $this->deleteStudentContactHistory($studentID);
        }

        //create the sql statement
        $sql = "DELETE FROM student WHERE id = $studentID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //check the result
        if ($stmt->affected_rows > 0) {
            $result = true;
        }

        //log the student activity if the student was deleted
        if ($result) {
            $activity = new Activity();
            //instance the session class
            $session = new Session();
            //get the current user id
            $userID = intval($session->get('user_id')) ?? null;
            $activity->logActivity($userID, 'Deleted Student', 'Student ID: ' . $studentID . ' Student Name: ' . $studentName);
        }

        //return the result
        return $result;
    }

    /**
     * Update a student in the database
     * @param int $studentID //student id to update
     * @param StudentData $studentData
     * @return bool
     */
    public function updateStudent(int $studentID, StudentData $studentData): bool
    {
        //get the student address object from the student data
        $studentAddress = $studentData->studentAddress;

        //get the student education object from the student data
        $studentEducation = $studentData->studentEducation;

        //get the student data, escape the strings to prevent SQL injection
        $firstName = $studentData->getEscapedString('firstName');
        $lastName = $studentData->getEscapedString('lastName');
        $email = $studentData->getEscapedString('email');
        $phone = $studentData->getEscapedString('phone');
        $address = $studentAddress->getEscapedString('address');
        $city = $studentAddress->getEscapedString('city');
        $state = $studentAddress->getEscapedString('state');
        $zip = $studentAddress->getEscapedString('zipcode');
        $degreeID = intval($studentData->studentEducation->degree);
        $majorID = intval($studentData->studentEducation->major);
        $school = intval($studentData->studentEducation->school);
        $graduation = $studentEducation->getEscapedString('graduation');
        $position = $studentData->getEscapedString('position');
        $areaID = intval($studentData->interest);

        //get current timestamp to set the updated_at field
        $timestamp = date('Y-m-d H:i:s');

        //get current user id from session if available
        $session = new Session();
        $updatedBy = intval($session->get('user_id')) ?? null; //if the session is not available, set the updated_by to null

        //SQL statement to update a student in the database
        $sql = "UPDATE student SET first_name = '$firstName', last_name = '$lastName', email = '$email', phone = '$phone', address = '$address', city = '$city', state = '$state', zipcode = '$zip', degree = '$degreeID', major = '$majorID', school = '$school', graduation = '$graduation', position = '$position', interest = '$areaID', updated_at = '$timestamp', updated_by = '$updatedBy' WHERE id = '$studentID'";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //check the result
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($updatedBy, 'Updated Student', 'Student ID: ' . $studentID . ' Student Name: ' . $firstName . ' ' . $lastName);
            return true;
        }

        //return false
        return false;
    }
};
