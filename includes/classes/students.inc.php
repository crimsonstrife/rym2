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
     * Get all students from the database
     *
     * @return array
     */
    public function getStudents(): array
    {
        //SQL statement to get all students
        $sql = "SELECT * FROM student";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Loop through the result and add each row to the students array
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
            //if the students array is not empty
            if (!empty($students)) {
                //Return the students array
                return $students;
            } else {
                //If the students array is empty, return an empty array
                return array();
            }
        } else {
            //If the query fails, return an empty array
            return array();
        }
    }

    /**
     * Get a student by their ID
     *
     * @param int $id
     * @return array
     */
    public function getStudentById(int $id): array
    {
        //SQL statement to get a student by their ID
        $sql = "SELECT * FROM student WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Return the student
            return $result->fetch_assoc();
        } else {
            //If the query fails, return an empty array
            return array();
        }
    }

    /**
     * Get a student by their email address
     *
     * @param string $email
     * @return array
     */
    public function getStudentByEmail(string $email): array
    {
        //SQL statement to get a student by their email address
        $sql = "SELECT * FROM student WHERE email = '$email'";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Return the student
            return $result->fetch_assoc();
        } else {
            //If the query fails, return an empty array
            return array();
        }
    }

    /**
     * Get a student first name
     *
     * @param int $id
     * @return string
     */
    public function getStudentFirstName(int $id): string
    {
        //SQL statement to get a student first name
        $sql = "SELECT first_name FROM student WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Return the student first name
            return $result->fetch_assoc()['first_name'];
        } else {
            //If the query fails, return an empty string
            return "";
        }
    }

    /**
     * Get a student last name
     *
     * @param int $id
     * @return string
     */
    public function getStudentLastName(int $id): string
    {
        //SQL statement to get a student last name
        $sql = "SELECT last_name FROM student WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Return the student last name
            return $result->fetch_assoc()['last_name'];
        } else {
            //If the query fails, return an empty string
            return "";
        }
    }

    /**
     * Get a student full name
     *
     * @param int $id
     * @return string
     */
    public function getStudentFullName(int $id): string
    {
        //get the student first name
        $first_name = $this->getStudentFirstName($id);
        //get the student last name
        $last_name = $this->getStudentLastName($id);
        //format the student full name
        $full_name = $first_name . " " . $last_name;
        //return the student full name
        return $full_name;
    }

    /**
     * Get a student email
     *
     * @param int $id
     * @return string
     */
    public function getStudentEmail(int $id): string
    {
        //SQL statement to get a student email
        $sql = "SELECT email FROM student WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Return the student email
            return $result->fetch_assoc()['email'];
        } else {
            //If the query fails, return an empty string
            return "";
        }
    }

    /**
     * Get a student phone number
     *
     * @param int $id
     * @return string
     */
    public function getStudentPhone(int $id): string
    {
        //SQL statement to get a student phone number
        $sql = "SELECT phone FROM student WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        $phone = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $phone = $row['phone'];
            }
        }
        //Return the student phone number
        return $phone;
    }

    /**
     * Get a formatted student phone number
     *
     * @param int $id
     * @return string
     */
    public function getStudentFormattedPhone(int $id): string
    {
        //get the student phone number
        $phone = $this->getStudentPhone($id);
        //format the student phone number
        $formatted_phone = "(" . substr($phone, 0, 3) . ") " . substr($phone, 3, 3) . "-" . substr($phone, 6, 4);
        //return the student phone number
        return $formatted_phone;
    }

    /**
     * Get a student address
     *
     * @param int $id
     * @return string
     */
    public function getStudentAddress(int $id): string
    {
        //SQL statement to get a student address
        $sql = "SELECT address FROM student WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        $address = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $address = $row['address'];
            }
        }
        //Return the student address
        return $address;
    }

    /**
     * Get a student city
     *
     * @param int $id
     * @return string
     */
    public function getStudentCity(int $id): string
    {
        //SQL statement to get a student city
        $sql = "SELECT city FROM student WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        $city = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $city = $row['city'];
            }
        }
        //Return the student city
        return $city;
    }

    /**
     * Get a student state
     *
     * @param int $id
     * @return string
     */
    public function getStudentState(int $id): string
    {
        //SQL statement to get a student state
        $sql = "SELECT state FROM student WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        $state = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $state = $row['state'];
            }
        }
        //Return the student state
        return $state;
    }

    /**
     * Get a student zip code
     *
     * @param int $id
     * @return string
     */
    public function getStudentZip(int $id): string
    {
        //SQL statement to get a student zip code
        $sql = "SELECT zip FROM student WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        $zip = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $zip = $row['zip'];
            }
        }
        //Return the student zip code
        return $zip;
    }

    /**
     * Get a formatted student address
     *
     * @param int $id
     * @return string
     */
    public function getStudentFormattedAddress(int $id): string
    {
        //get the student address
        $address = $this->getStudentAddress($id);
        //get the student city
        $city = $this->getStudentCity($id);
        //get the student state
        $state = $this->getStudentState($id);
        //get the student zip code
        $zip = $this->getStudentZip($id);
        //format the student address
        $formatted_address = $address . " " . $city . ", " . $state . " " . $zip;
        //return the student address
        return $formatted_address;
    }

    /**
     * Get a student degree id
     *
     * @param int $id
     * @return int
     */
    public function getStudentDegreeId(int $id): int
    {
        //SQL statement to get a student degree id
        $sql = "SELECT degree FROM student WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Return the student degree id
            return intval($result->fetch_assoc()['degree']);
        } else {
            //If the query fails, return an empty string
            return 0;
        }
    }

    /**
     * Get a student major id
     *
     * @param int $id
     * @return int
     */
    public function getStudentMajorId(int $id): int
    {
        //SQL statement to get a student major id
        $sql = "SELECT major FROM student WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Return the student major id
            return intval($result->fetch_assoc()['major']);
        } else {
            //If the query fails, return an empty string
            return 0;
        }
    }

    /**
     * Get a student's degree program
     *
     * @param int $id
     * @return string
     */
    public function getStudentDegree(int $id): string
    {
        //create a string to hold the degree program
        $degree = "";
        //instantiate a new degree object
        $degreeProgram = new Degree();
        //get the student's degree id
        $degree_id = $this->getStudentDegreeId($id);
        //get the student's major id
        $major_id = $this->getStudentMajorId($id);

        //get the degree program by the degree id and major id
        $degree = $degreeProgram->getDegreeProgram($degree_id, $major_id);

        //return the degree program
        return $degree;
    }

    /**
     * Get a student's major by their id
     *
     * @param int $id
     * @return array
     */
    public function getStudentMajor(int $id): array
    {
        //create an array to hold the major
        $major = array();
        //instantiate a new major object
        $majorProgram = new Degree();
        //get the student's major id
        $major_id = $this->getStudentMajorId($id);
        //get the major by the major id
        $major = $majorProgram->getMajor($major_id);
        //return the major
        return $major;
    }

    /**
     * Get a student's degree level by their id
     *
     * @param int $id
     * @return array
     */
    public function getStudentDegreeLevel(int $id): array
    {
        //create an array to hold the degree level
        $degree_level = array();
        //instantiate a new degree object
        $degreeProgram = new Degree();
        //get the student's degree id
        $degree_id = $this->getStudentDegreeId($id);
        //get the degree level by the degree id
        $degree_level = $degreeProgram->getGrade($degree_id);
        //return the degree level
        return $degree_level;
    }

    /**
     * Get student graduation year
     *
     * @param int $id
     * @return string
     */
    public function getStudentGraduation(int $id): string
    {
        //SQL statement to get a student graduation year
        $sql = "SELECT graduation FROM student WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Return the student graduation year
            return $result->fetch_assoc()['graduation'];
        } else {
            //If the query fails, return an empty string
            return "";
        }
    }

    /**
     * Get a students job type preference from the job ID
     *
     * @param int $id //student id
     * @return string //position - 'FULL', 'PART', 'INTERN'
     */
    public function getStudentPosition(int $id): string
    {
        //SQL statement to get a students job type preference from the job ID
        $sql = "SELECT position FROM student WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        $position = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $position = $row['position'];
            }
        }
        //depending on the type, return the correct string
        if (
            $position == 'FULL'
        ) {
            return 'Full Time';
        } elseif (
            $position == 'PART'
        ) {
            return 'Part Time';
        } elseif ($position == 'INTERN') {
            return 'Internship';
        } else {
            return 'Internship';
        }
    }

    /**
     * Set a student's first name
     *
     * @param int $id
     * @param string $first_name
     * @return bool
     */
    public function setStudentFirstName(int $id, string $first_name): bool
    {
        //SQL statement to set a student's first name
        $sql = "UPDATE students SET first_name = '$first_name' WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query is successful
        if ($result) {
            //Return true
            return true;
        } else {
            //If the query fails, return false
            return false;
        }
    }

    /**
     * Set a student's last name
     *
     * @param int $id
     * @param string $last_name
     * @return bool
     */
    public function setStudentLastName(int $id, string $last_name): bool
    {
        //SQL statement to set a student's last name
        $sql = "UPDATE students SET last_name = '$last_name' WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query is successful
        if ($result) {
            //Return true
            return true;
        } else {
            //If the query fails, return false
            return false;
        }
    }

    /**
     * Set a student's email address
     *
     * @param int $id
     * @param string $email
     * @return bool
     */
    public function setStudentEmail(int $id, string $email): bool
    {
        //SQL statement to set a student's email address
        $sql = "UPDATE students SET email = '$email' WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query is successful
        if ($result) {
            //Return true
            return true;
        } else {
            //If the query fails, return false
            return false;
        }
    }

    /**
     * Set a student's degree id
     *
     * @param int $id
     * @param int $degree_id
     * @return bool
     */
    public function setStudentDegreeId(int $id, int $degree_id): bool
    {
        //SQL statement to set a student's degree level id
        $sql = "UPDATE students SET degree = $degree_id WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query is successful
        if ($result) {
            //Return true
            return true;
        } else {
            //If the query fails, return false
            return false;
        }
    }

    /**
     * Set a student's major id
     *
     * @param int $id
     * @param int $major_id
     * @return bool
     */
    public function setStudentMajorId(int $id, int $major_id): bool
    {
        //SQL statement to set a student's major id
        $sql = "UPDATE students SET major = $major_id WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query is successful
        if ($result) {
            //Return true
            return true;
        } else {
            //If the query fails, return false
            return false;
        }
    }

    /**
     * Set a student's graduation year
     *
     * @param int $id
     * @param string $graduation
     * @return bool
     */
    public function setStudentGraduation(int $id, string $graduation): bool
    {
        //SQL statement to set a student's graduation year
        $sql = "UPDATE students SET graduation = '$graduation' WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query is successful
        if ($result) {
            //Return true
            return true;
        } else {
            //If the query fails, return false
            return false;
        }
    }

    /**
     * Set a student's job type preference
     *
     * @param int $id
     * @param string $position
     * @return bool
     */
    public function setStudentPosition(int $id, string $position): bool
    {
        //SQL statement to set a student's job type preference
        $sql = "UPDATE students SET position = '$position' WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query is successful
        if ($result) {
            //Return true
            return true;
        } else {
            //If the query fails, return false
            return false;
        }
    }

    /**
     * Add a new student to the database
     *
     * @param string $first_name
     * @param string $last_name
     * @param string $email
     * @param string $phone
     * @param string $address
     * @param string $city
     * @param string $state
     * @param string $zip
     * @param int $degree_id
     * @param int $major_id
     * @param string $school
     * @param string $graduation
     * @param string $position
     * @param int $area_id
     * @return bool
     */
    public function addStudent(string $first_name, string $last_name, string $email, string $phone = NULL, string $address, string $city, string $state, string $zip, int $degree_id, int $major_id, string $school, string $graduation, string $position, $event_id, int $area_id): bool
    {
        //Escape the data to prevent SQL injection attacks
        $first_name = $this->mysqli->real_escape_string($first_name);
        $last_name = $this->mysqli->real_escape_string($last_name);
        $email = $this->mysqli->real_escape_string($email);
        $phone = $this->mysqli->real_escape_string($phone);
        $address = $this->mysqli->real_escape_string($address);
        $city = $this->mysqli->real_escape_string($city);
        $state = $this->mysqli->real_escape_string($state);
        $zip = $this->mysqli->real_escape_string($zip);
        //get current timestamp to set the created_at and updated_at fields
        $timestamp = date('Y-m-d H:i:s');
        //SQL statement to add a new student to the database
        $sql = "INSERT INTO student (first_name, last_name, email, phone, address, city, state, zip, degree, major, school, graduation, position, created_at, updated_at) VALUES ('$first_name', '$last_name', '$email', '$phone', '$address', '$city', '$state', '$zip', '$degree_id', '$major_id', '$school', '$graduation', '$position', '$timestamp', '$timestamp')";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query is successful
        if ($result) {
            //TODO: send email to student with a thank you.
            //Return true
            return true;
        } else {
            //If the query fails, return false
            return false;
        }
    }

    /**
     * Student attendance
     * Add a student to an event
     *
     * @param int $event_id
     * @param int $student_id
     * @return bool
     */
    public function addStudentToEvent(int $event_id, int $student_id): bool
    {
        //SQL statement to add a student to an event
        $sql = "INSERT INTO student_at_event (event_id, student_id) VALUES ('$event_id', '$student_id')";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query is successful
        if ($result) {
            //Return true
            return true;
        } else {
            //If the query fails, return false
            return false;
        }
    }

    /**
     * Student attendance
     * Remove a student from an event
     *
     * @param int $event_id
     * @param int $student_id
     * @return bool
     */
    public function removeStudentFromEvent(int $event_id, int $student_id): bool
    {
        //SQL statement to remove a student from an event
        $sql = "DELETE FROM student_at_event WHERE event_id = $event_id AND student_id = $student_id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query is successful
        if ($result) {
            //Return true
            return true;
        } else {
            //If the query fails, return false
            return false;
        }
    }

    /**
     * Student attendance
     * Get a list of students that attended an event
     *
     * @param int $event_id
     * @return array
     */
    public function getStudentEventAttendace(int $event_id): array
    {
        //SQL statement to get a list of students that attended an event
        $sql = "SELECT * FROM student_at_event WHERE event_id = $event_id";
        //Query the database
        $result = $this->mysqli->query($sql);
        $students = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }
        //Return the students array
        return $students;
    }
};
