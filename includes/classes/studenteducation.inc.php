<?php

/**
 * Students Education Class file for the College Recruitment Application
 * Contains all the functions for the Student Education Class and handles all the student education related tasks with the database.
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/16/2023
 *
 * @package RYM2
 * Filename: studenteducation.inc.php
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

use Degree;

/**
 * Class StudentEducation
 * This class is used to store student education data
 */
class StudentEducation extends Student
{
    public ?int $degree = null;
    public ?int $major = null;
    public ?int $school = null;
    public ?string $graduation = null;

    /**
     * Gets all non-null properties of the StudentEducation class as an array
     * @return array
     */
    public function getStudentEducationArray(): array
    {
        //create an array to hold the student education data
        $studentEdArray = array();

        //loop through the properties of the class
        foreach ($this as $key => $value) {
            //if the value is not null, add it to the student education array
            if ($value !== null) {
                $studentEdArray[$key] = $value;
            }
        }

        //return the student education array
        return $studentEdArray;
    }

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
     * Get a student degree id
     *
     * @param int $studentID
     * @return ?int
     */
    public function getStudentDegreeId(int $studentID): ?int
    {
        //SQL statement to get a student degree id
        $sql = "SELECT degree FROM student WHERE id = $studentID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //Placeholder for the degree id
        $degreeID = null;

        //If the query returns a result
        if ($result) {
            //set the degree id
            $degreeID = intval($result->fetch_assoc()['degree']);
        }

        //Return the student degree id
        return $degreeID;
    }

    /**
     * Get a student major id
     *
     * @param int $studentID
     * @return ?int
     */
    public function getStudentMajorId(int $studentID): ?int
    {
        //SQL statement to get a student major id
        $sql = "SELECT major FROM student WHERE id = $studentID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //Placeholder for the major id
        $majorID = null;

        //If the query returns a result
        if ($result) {
            //Set the major id
            $majorID = intval($result->fetch_assoc()['major']);
        }

        //Return the student major id
        return $majorID;
    }

    /**
     * Get a student's degree program
     *
     * @param int $studentID
     * @return string
     */
    public function getStudentDegree(int $studentID): string
    {
        //create a string to hold the degree program
        $degreeProgram = "";

        //get the student's degree id
        $degreeID = $this->getStudentDegreeId($studentID);

        //get the student's major id
        $majorID = $this->getStudentMajorId($studentID);

        //get the degree program by the degree id and major id
        $degreeProgram = getDegreeProgram($degreeID, $majorID);

        //return the degree program
        return $degreeProgram;
    }

    /**
     * Get a student's major by their id
     *
     * @param int $studentID
     * @return array
     */
    public function getStudentMajor(int $studentID): array
    {
        //create an array to hold the major
        $major = array();
        //instantiate a new major object
        $majorProgram = new Degree();
        //get the student's major id
        $majorID = $this->getStudentMajorId($studentID);
        //get the major by the major id
        $major = $majorProgram->getMajor($majorID);
        //return the major
        return $major;
    }

    /**
     * Get all students by a major id
     *
     * @param int $majorID
     * @return array
     */
    public function getStudentsByMajor(int $majorID): array
    {
        //SQL statement to get all students by a major id
        $sql = "SELECT * FROM student WHERE major = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $majorID);

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
     * Get a student's degree level by their id
     *
     * @param int $studentID
     * @return array
     */
    public function getStudentDegreeLevel(int $studentID): array
    {
        //create an array to hold the degree level
        $grade = array();
        //instantiate a new degree object
        $degree = new Degree();

        //get the student's degree id
        $degreeID = $this->getStudentDegreeId($studentID);

        //get the degree level by the degree id
        $grade = $degree->getGrade($degreeID);

        //return the degree level
        return $grade;
    }

    /**
     * Get all students by a degree level id
     *
     * @param int $degreeID
     * @return array
     */
    public function getStudentsByGrade(int $degreeID): array
    {
        //SQL statement to get all students by a degree level id
        $sql = "SELECT * FROM student WHERE degree = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $degreeID);

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
     * Get student graduation year
     *
     * @param int $studentID
     * @return string
     */
    public function getStudentGraduation(int $studentID): string
    {
        //SQL statement to get a student graduation year
        $sql = "SELECT graduation FROM student WHERE id = $studentID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create a variable to hold the graduation year
        $graduation = "";

        //If the query returns a result
        if ($result) {
            //Return the student graduation year
            $graduation = $result->fetch_assoc()['graduation'];
        }

        //Return the student graduation year
        return $graduation;
    }

    /**
     * Get a student's school id
     *
     * @param int $studentID
     * @return ?int
     */
    public function getStudentSchool(int $studentID): ?int
    {
        //SQL statement to get a student school id
        $sql = "SELECT school FROM student WHERE id = $studentID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create a variable to hold the school id
        $schoolID = null;

        //If the query returns a result
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                //set the school id
                $schoolID = intval($row['school']);
            }
        }

        //Return the student school id
        return $schoolID;
    }

    /**
     * Get students by school id
     * Get a list of students associated with a school by the school id
     *
     * @param int $schoolID
     * @return array
     */
    public function getStudentsBySchool(int $schoolID): array
    {
        //SQL statement to get a list of students associated with a school by the school id
        $sql = "SELECT * FROM student WHERE school = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $schoolID);

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
     * Set a student's degree id
     *
     * @param int $studentID
     * @param int $degreeID
     * @return bool
     */
    public function setStudentDegreeId(int $studentID, int $degreeID): bool
    {
        //SQL statement to set a student's degree level id
        $sql = "UPDATE students SET degree = $degreeID WHERE id = $studentID";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query is successful
        if ($result) {
            //Return true
            return true;
        }

        //If the query fails, return false
        return false;
    }

    /**
     * Set a student's major id
     *
     * @param int $studentID
     * @param int $majorID
     * @return bool
     */
    public function setStudentMajorId(int $studentID, int $majorID): bool
    {
        //SQL statement to set a student's major id
        $sql = "UPDATE students SET major = $majorID WHERE id = $studentID";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query is successful
        if ($result) {
            //Return true
            return true;
        }

        //If the query fails, return false
        return false;
    }

    /**
     * Set a student's graduation date
     *
     * @param int $studentID
     * @param string $graduation
     * @return bool
     */
    public function setStudentGraduation(int $studentID, string $graduation): bool
    {
        //SQL statement to set a student's graduation year
        $sql = "UPDATE students SET graduation = '$graduation' WHERE id = $studentID";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query is successful
        if ($result) {
            //Return true
            return true;
        }

        //If the query fails, return false
        return false;
    }
}
