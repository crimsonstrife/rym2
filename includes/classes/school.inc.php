<?php

/**
 * School Class file for the College Recruitment Application
 * Contains all the functions for the School Class and handles all the school related tasks with the database.
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/16/2023
 *
 * @package RYM2
 * Filename: school.inc.php
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

class School
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
     * Get all the schools from the database
     *
     * @return array $schools
     */
    public function getSchools(): array
    {
        $schools = array();
        $sql = "SELECT * FROM school";
        $result = $this->mysqli->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($schools, $row);
            }
        }
        return $schools;
    }

    /**
     * Get a school from the database by the school id
     *
     * @param int $school_id
     * @return array $school
     */
    public function getSchoolById(int $school_id): array
    {
        $school = array();
        $sql = "SELECT * FROM school WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $school_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $school = $result->fetch_assoc();
        }
        return $school;
    }

    /**
     * Get the name of a school from the database by the school id
     *
     * @param int $school_id
     * @return string $school_name
     */
    public function getSchoolName(int $school_id): string
    {
        $school_name = "";
        $sql = "SELECT name FROM school WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $school_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $school_name = $result->fetch_assoc()['name'];
        }
        return $school_name;
    }

    /**
     * Get the address of a school from the database by the school id
     *
     * @param int $school_id
     * @return string $school_address
     */
    public function getSchoolAddress(int $school_id): string
    {
        $school_address = "";
        $sql = "SELECT address FROM school WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $school_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $school_address = $result->fetch_assoc()['address'];
        }
        return $school_address;
    }

    /**
     * Get the city of a school from the database by the school id
     *
     * @param int $school_id
     * @return string $school_city
     */
    public function getSchoolCity(int $school_id): string
    {
        $school_city = "";
        $sql = "SELECT city FROM school WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $school_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $school_city = $result->fetch_assoc()['city'];
        }
        return $school_city;
    }

    /**
     * Get the state of a school from the database by the school id
     *
     * @param int $school_id
     * @return string $school_state
     */
    public function getSchoolState(int $school_id): string
    {
        $school_state = "";
        $sql = "SELECT state FROM school WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $school_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $school_state = $result->fetch_assoc()['state'];
        }
        return $school_state;
    }

    /**
     * Get the zip code of a school from the database by the school id
     *
     * @param int $school_id
     * @return string $school_zip
     */
    public function getSchoolZip(int $school_id): string
    {
        $school_zip = "";
        $sql = "SELECT zipcode FROM school WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $school_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $school_zip = $result->fetch_assoc()['zipcode'];
        }
        return $school_zip;
    }

    /**
     * Get a formatted address of a school
     *
     * @param int $school_id
     * @return string $school_address
     */
    public function getFormattedSchoolAddress(int $school_id): string
    {
        $formatted_address = "";
        $school_address = $this->getSchoolAddress($school_id);
        $school_city = $this->getSchoolCity($school_id);
        $school_state = $this->getSchoolState($school_id);
        $school_zip = $this->getSchoolZip($school_id);
        // Format the address
        $formatted_address = $school_address . ", " . $school_city . ", " . $school_state . " " . $school_zip;
        return $formatted_address;
    }

    /**
     * Get the created date of a school
     *
     * @param int $school_id
     * @return string $created_at
     */
    public function getSchoolCreatedDate(int $school_id): string
    {
        $created_at = "";
        $sql = "SELECT created_at FROM school WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $school_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $created_at = $result->fetch_assoc()['created_at'];
        }
        return $created_at;
    }

    /**
     * Get the updated date of a school
     *
     * @param int $school_id
     * @return string $updated_at
     */
    public function getSchoolUpdatedDate(int $school_id): string
    {
        $updated_at = "";
        $sql = "SELECT updated_at FROM school WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $school_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $updated_at = $result->fetch_assoc()['updated_at'];
        }
        return $updated_at;
    }

    /**
     * Get the created by user id of a school
     *
     * @param int $school_id
     * @return User $created_by
     */
    public function getSchoolCreatedBy(int $school_id): User
    {
        $created_by = new User();
        $sql = "SELECT created_by FROM school WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $school_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_array = array();
        if ($result->num_rows > 0) {
            // Get the user id of the user who created the school
            $user_id = $result->fetch_assoc()['created_by'];
            // Add the user to the user array
            $user_array = $created_by->getUserById($user_id);
            // Set the user object to the user in the user array
            $created_by = $user_array[0];
        }
        return $created_by;
    }

    /**
     * Get the updated by user id of a school
     *
     * @param int $school_id
     * @return User $updated_by
     */
    public function getSchoolUpdatedBy(int $school_id): User
    {
        $updated_by = new User();
        $sql = "SELECT updated_by FROM school WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $school_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_array = array();
        if ($result->num_rows > 0) {
            // Get the user id of the user who updated the school
            $user_id = $result->fetch_assoc()['updated_by'];
            // Add the user to the user array
            $user_array = $updated_by->getUserById($user_id);
            // Set the user object to the user in the user array
            $updated_by = $user_array[0];
        }
        return $updated_by;
    }
};
