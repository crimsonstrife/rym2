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

    /**
     * Get the school logo
     *
     * @param int $school_id
     * @return int $school_logo ID
     */
    public function getSchoolLogo(int $school_id): int
    {
        $school_logo = "";
        $sql = "SELECT school_logo FROM school_branding WHERE school_id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $school_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $school_logo = $result->fetch_assoc()['school_logo'];
        }

        return intval($school_logo);
    }

    /**
     * Set the school logo
     *
     * @param int $school_id
     * @param int $logo logo media_id
     * @return boolean $result
     */
    public function setSchoolLogo(int $school_id, int $logo): bool
    {
        $result = false;
        $defaultColor = "#000000";
        // Check if the school branding exists
        $sql = "SELECT * FROM school_branding WHERE school_id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $school_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            // Update the school branding
            $sql = "UPDATE school_branding SET school_logo = ? WHERE school_id = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("si", $logo, $school_id);
            $stmt->execute();
            $result = true;
        } else {
            // Create the school branding
            $sql = "INSERT INTO school_branding (school_id, school_logo, school_color) VALUES (?, ?, ?)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("iis", $school_id, $logo, $defaultColor);
            $stmt->execute();
            $result = true;
        }
        return $result;
    }

    /**
     * Get the school color hex code
     *
     * @param int $school_id
     * @return string $school_color
     */
    public function getSchoolColor(int $school_id): string
    {
        $school_color = "";
        $sql = "SELECT school_color FROM school_branding WHERE school_id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $school_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $school_color = $result->fetch_assoc()['school_color'];
        }
        return $school_color;
    }

    /**
     * Set the school color hex code
     *
     * @param int $school_id
     * @param string $color
     * @return boolean $result
     */
    public function setSchoolColor(int $school_id, string $color): bool
    {
        $result = false;
        $defaultLogo = NULL;
        // Check if the school branding exists
        $sql = "SELECT * FROM school_branding WHERE school_id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $school_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            // Update the school branding
            $sql = "UPDATE school_branding SET school_color = ? WHERE school_id = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("si", $color, $school_id);
            $stmt->execute();
            $result = true;
        } else {
            // Create the school branding
            $sql = "INSERT INTO school_branding (school_id, school_logo, school_color) VALUES (?, ?, ?)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("iis", $school_id, $defaultLogo, $color);
            $stmt->execute();
            $result = true;
        }
        return $result;
    }

    /**
     * Update a school in the database
     *
     * @param int $school_id
     * @param string $school_name
     * @param string $school_address
     * @param string $school_city
     * @param string $school_state
     * @param string $school_zip
     * @param int $updated_by
     * @return boolean $result
     */
    public function updateSchool(int $school_id, string $school_name, string $school_address, string $school_city, string $school_state, string $school_zip, int $updated_by): bool
    {
        //get the current date and time
        $updated_at = date("Y-m-d H:i:s");
        $result = false;
        $sql = "UPDATE school SET name = ?, address = ?, city = ?, state = ?, zipcode = ?, updated_at = ?, updated_by = ? WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ssssssii", $school_name, $school_address, $school_city, $school_state, $school_zip, $updated_at, $updated_by, $school_id);
        $stmt->execute();
        $result = true;

        //log the school activity
        $activity = new Activity();
        $activity->logActivity($updated_by, 'Updated School', 'School ' . $school_name);
        return $result;
    }

    /**
     * Create a school in the database
     *
     * @param string $school_name
     * @param string $school_address
     * @param string $school_city
     * @param string $school_state
     * @param string $school_zip
     * @param int $created_by
     * @return boolean $result
     */
    public function createSchool(string $school_name, string $school_address, string $school_city, string $school_state, string $school_zip, int $created_by): bool
    {
        //get the current date and time
        $created_at = date("Y-m-d H:i:s");
        $result = false;
        $sql = "INSERT INTO school (name, address, city, state, zipcode, created_at, created_by, updated_at, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ssssssisi", $school_name, $school_address, $school_city, $school_state, $school_zip, $created_at, $created_by, $created_at, $created_by);
        $stmt->execute();
        $result = true;

        //log the school activity
        $activity = new Activity();
        $activity->logActivity($created_by, 'Created School', 'School ' . $school_name);
        return $result;
    }

    /**
     * Get school ID by school name
     *
     * @param string $school_name
     * @return int $school_id
     */
    public function getSchoolIdByName(string $school_name): int
    {
        $school_id = 0;
        $sql = "SELECT id FROM school WHERE name = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $school_name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $school_id = intval($result->fetch_assoc()['id']);
        }
        return $school_id;
    }

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
        $stmt = $this->mysqli->prepare($sql);
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

    /**
     * Delete a school from the database
     *
     * @param int $school_id
     * @return boolean $result
     */
    public function deleteSchool(int $school_id): bool
    {
        //get the current date and time
        $date = date("Y-m-d H:i:s");

        //get the name of the school
        $school_name = $this->getSchoolName($school_id);

        //set the placeholder for the result
        $result = false;

        //create the sql statement
        $sql = "DELETE FROM school WHERE id = ?";

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

        //bind the parameters
        $stmt->bind_param("i", $school_id);

        //execute the statement
        $stmt->execute();

        //check the result
        if ($stmt->affected_rows > 0) {
            $result = true;
        } else {
            $result = false;
        }

        //log the school activity if the school was deleted
        if ($result) {
            $activity = new Activity();
            $activity->logActivity(intval($_SESSION['user_id']), 'Deleted School', 'School ID: ' . $school_id . ' School Name: ' . $school_name);
        }

        //return the result
        return $result;
    }
};
