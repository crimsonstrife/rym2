<?php

/**
 * Students Address Class file for the College Recruitment Application
 * Contains all the functions for the Student Address Class and handles all the student address related tasks with the database.
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/16/2023
 *
 * @package RYM2
 * Filename: studentaddress.inc.php
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

/**
 * Class Student Address
 * This class is used to store student address data
 */
class StudentAddress extends Student
{
    public ?string $address = null;
    public ?string $city = null;
    public ?string $state = null;
    public ?string $zipcode = null;

    /**
     * Gets all non-null properties of the StudentAddress class as an array
     * @return array
     */
    public function getStudentAddressArray(): array
    {
        $studentAddressArray = array();
        foreach ($this as $key => $value) {
            if ($value !== null) {
                $studentAddressArray[$key] = $value;
            }
        }
        return $studentAddressArray;
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
     * Get a student address
     *
     * @param int $studentID
     * @return string
     */
    public function getStudentAddress(int $studentID): string
    {
        //SQL statement to get a student address
        $sql = "SELECT address FROM student WHERE id = $studentID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create a variable to hold the address
        $address = "";

        //If the query returns a result
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
     * @param int $studentID
     * @return string
     */
    public function getStudentCity(int $studentID): string
    {
        //SQL statement to get a student city
        $sql = "SELECT city FROM student WHERE id = $studentID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create a variable to hold the city
        $city = "";

        //If the query returns a result
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
     * @param int $studentID
     * @return string
     */
    public function getStudentState(int $studentID): string
    {
        //SQL statement to get a student state
        $sql = "SELECT state FROM student WHERE id = $studentID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create a variable to hold the state
        $state = "";

        //If the query returns a result
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
     * @param int $studentID
     * @return string
     */
    public function getStudentZip(int $studentID): string
    {
        //SQL statement to get a student zip code
        $sql = "SELECT zipcode FROM student WHERE id = $studentID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create a variable to hold the zip code
        $zip = "";

        //If the query returns a result
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $zip = $row['zipcode'];
            }
        }
        //Return the student zip code
        return $zip;
    }
}
