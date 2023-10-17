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
        $sql = "SELECT * FROM students";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Loop through the result and add each row to the students array
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
            //Return the students array
            return $students;
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
        $sql = "SELECT * FROM students WHERE id = $id";
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
        $sql = "SELECT * FROM students WHERE email = '$email'";
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
        $sql = "SELECT first_name FROM students WHERE id = $id";
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
        $sql = "SELECT last_name FROM students WHERE id = $id";
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
        $sql = "SELECT email FROM students WHERE id = $id";
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
     * Get a student degree id
     *
     * @param int $id
     * @return int
     */
    public function getStudentDegreeId(int $id): int
    {
        //SQL statement to get a student degree id
        $sql = "SELECT degree FROM students WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Return the student degree id
            return $result->fetch_assoc()['degree'];
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
        $sql = "SELECT major FROM students WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Return the student major id
            return $result->fetch_assoc()['major'];
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
        $sql = "SELECT graduation FROM students WHERE id = $id";
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
        $sql = "SELECT position FROM students WHERE id = $id";
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
};
