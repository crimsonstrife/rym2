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
     * Get all the schools from the database
     *
     * @return array $schools
     */
    public function getSchools(): array
    {
        //SQL statement to get all the schools from the database
        $sql = "SELECT * FROM school";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

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

        return $schools;
    }

    /**
     * Get a school from the database by the school id
     *
     * @param int $schoolID
     * @return array $school
     */
    public function getSchoolById(int $schoolID): array
    {
        //SQL statement to get a school from the database by the school id
        $sql = "SELECT * FROM school WHERE id = $schoolID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create an array to store the school
        $school = array();

        //if there is a school in the database, add it to the array
        if ($result->num_rows > 0) {
            $school = $result->fetch_assoc();
        }

        //return the school array
        return $school;
    }

    /**
     * Get the name of a school from the database by the school id
     *
     * @param int $schoolID
     * @return string $schoolName
     */
    public function getSchoolName(int $schoolID): string
    {
        //SQL statement to get the name of a school from the database by the school id
        $sql = "SELECT name FROM school WHERE id = $schoolID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //set the school name to an empty string
        $schoolName = "";

        //if there is a school in the database, get the name
        if ($result->num_rows > 0) {
            $schoolName = $result->fetch_assoc()['name'];
        }

        //return the school name
        return $schoolName;
    }

    /**
     * Get the address of a school from the database by the school id
     *
     * @param int $schoolID
     * @return string $schoolAddress
     */
    public function getSchoolAddress(int $schoolID): string
    {
        //SQL statement to get the address of a school from the database by the school id
        $sql = "SELECT address FROM school WHERE id = $schoolID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //set the school address to an empty string
        $schoolAddress = "";

        //if there is a school in the database, get the address
        if ($result->num_rows > 0) {
            //get the address
            $schoolAddress = $result->fetch_assoc()['address'];
        }

        //return the school address
        return $schoolAddress;
    }

    /**
     * Get the city of a school from the database by the school id
     *
     * @param int $schoolID
     * @return string $schoolCity
     */
    public function getSchoolCity(int $schoolID): string
    {
        //SQL statement to get the city of a school from the database by the school id
        $sql = "SELECT city FROM school WHERE id = $schoolID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //set the school city to an empty string
        $schoolCity = "";

        //if there is a school in the database, get the city
        if ($result->num_rows > 0) {
            //get the city
            $schoolCity = $result->fetch_assoc()['city'];
        }

        //return the school city
        return $schoolCity;
    }

    /**
     * Get the state of a school from the database by the school id
     *
     * @param int $schoolID
     * @return string $schoolState
     */
    public function getSchoolState(int $schoolID): string
    {
        //SQL statement to get the state of a school from the database by the school id
        $sql = "SELECT state FROM school WHERE id = $schoolID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //set the school state to an empty string
        $schoolState = "";

        //if there is a school in the database, get the state
        if ($result->num_rows > 0) {
            //get the state
            $schoolState = $result->fetch_assoc()['state'];
        }

        //return the school state
        return $schoolState;
    }

    /**
     * Get the zip code of a school from the database by the school id
     *
     * @param int $schoolID
     * @return string $schoolZip
     */
    public function getSchoolZip(int $schoolID): string
    {
        //SQL statement to get the zip code of a school from the database by the school id
        $sql = "SELECT zipcode FROM school WHERE id = $schoolID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //set the school zip to an empty string
        $schoolZip = "";

        //if there is a school in the database, get the zip code
        if ($result->num_rows > 0) {
            //get the zip code
            $schoolZip = $result->fetch_assoc()['zipcode'];
        }

        //return the school zip code
        return $schoolZip;
    }

    /**
     * Get the created date of a school
     *
     * @param int $schoolID
     * @return string $createdAt
     */
    public function getSchoolCreatedDate(int $schoolID): string
    {
        //sql to get the created date of a school
        $sql = "SELECT created_at FROM school WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $schoolID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //set the created date to an empty string
        $createdAt = "";

        //if the result has rows, get the created date
        if ($result->num_rows > 0) {
            $createdAt = $result->fetch_assoc()['created_at'];
        }

        //return the created date
        return $createdAt;
    }

    /**
     * Get the updated date of a school
     *
     * @param int $schoolID
     * @return string $updatedAt
     */
    public function getSchoolUpdatedDate(int $schoolID): string
    {
        //sql to get the updated date of a school
        $sql = "SELECT updated_at FROM school WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $schoolID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //set the updated date to an empty string
        $updatedAt = "";

        //if the result has rows, get the updated date
        if ($result->num_rows > 0) {
            $updatedAt = $result->fetch_assoc()['updated_at'];
        }

        //return the updated date
        return $updatedAt;
    }

    /**
     * Get the created by user id of a school
     *
     * @param int $schoolID
     * @return User $createdBy
     */
    public function getSchoolCreatedBy(int $schoolID): User
    {
        //create a new user object
        $createdBy = new User();

        //sql to get the user id of the user who created the school
        $sql = "SELECT created_by FROM school WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $schoolID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //array to hold the user
        $userArray = array();

        //if the result has rows, get the user id of the user who created the school
        if ($result->num_rows > 0) {
            // Get the user id of the user who created the school
            $userID = $result->fetch_assoc()['created_by'];
            // Add the user to the user array
            $userArray = $createdBy->getUserById($userID);
            // Set the user object to the user in the user array
            $createdBy = $userArray[0];
        }

        //return the user object
        return $createdBy;
    }

    /**
     * Get the updated by user id of a school
     *
     * @param int $schoolID
     * @return User $updatedBy
     */
    public function getSchoolUpdatedBy(int $schoolID): User
    {
        //create a new user object
        $updatedBy = new User();

        //sql to get the user id of the user who updated the school
        $sql = "SELECT updated_by FROM school WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $schoolID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //array to hold the user
        $userArray = array();

        //if the result has rows, get the user id of the user who updated the school
        if ($result->num_rows > 0) {
            // Get the user id of the user who updated the school
            $userID = $result->fetch_assoc()['updated_by'];
            // Add the user to the user array
            $userArray = $updatedBy->getUserById($userID);
            // Set the user object to the user in the user array
            $updatedBy = $userArray[0];
        }

        return $updatedBy;
    }

    /**
     * Get the school logo
     *
     * @param int $schoolID
     * @return ?int $schoolLogo ID
     */
    public function getSchoolLogo(int $schoolID): ?int
    {
        //sql to get the school logo
        $sql = "SELECT school_logo FROM school_branding WHERE school_id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $schoolID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //set the school logo to NULL
        $schoolLogo = NULL;

        //if the result has rows, get the school logo
        if ($result->num_rows > 0) {
            $schoolLogo = intval($result->fetch_assoc()['school_logo']);
        }

        return $schoolLogo;
    }

    /**
     * Set the school logo
     *
     * @param int $schoolID
     * @param int $logo logo media_id
     * @return boolean $result
     */
    public function setSchoolLogo(int $schoolID, int $logo): bool
    {
        //set the placeholder for the result
        $result = false;

        //set the default color to black
        $defaultColor = "#000000";

        //SQL to Check if the school branding exists
        $sql = "SELECT * FROM school_branding WHERE school_id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $schoolID);

        //execute the statement
        $stmt->execute();

        //if the result has rows, update the school branding
        if ($stmt->get_result()->num_rows > 0) {
            //SQL to Update the school branding
            $sql = "UPDATE school_branding SET school_logo = ? WHERE school_id = ?";

            //prepare the statement
            $stmt = prepareStatement($this->mysqli, $sql);

            //bind the parameters
            $stmt->bind_param("ii", $logo, $schoolID);

            //execute the statement
            $stmt->execute();

            //get the result
            $result = $stmt->affected_rows > 0;

            //return the result
            return $result;
        }
        //SQL to Create the school branding
        $sql = "INSERT INTO school_branding (school_id, school_logo, school_color) VALUES (?, ?, ?)";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("iis", $schoolID, $logo, $defaultColor);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->affected_rows > 0;

        return $result;
    }

    /**
     * Get the school color hex code
     *
     * @param int $schoolID
     * @return string $schoolColor
     */
    public function getSchoolColor(int $schoolID): string
    {
        //sql to get the school color
        $sql = "SELECT school_color FROM school_branding WHERE school_id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $schoolID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //set the school color to an empty string
        $schoolColor = "";

        //if the result has rows, get the school color
        if ($result->num_rows > 0) {
            $schoolColor = $result->fetch_assoc()['school_color'];
        }

        //return the school color
        return $schoolColor;
    }

    /**
     * Set the school color hex code
     *
     * @param int $schoolID
     * @param string $color
     * @return boolean $result
     */
    public function setSchoolColor(int $schoolID, string $color): bool
    {
        //set the placeholder for the result
        $result = false;

        //set the default logo to NULL
        $defaultLogo = NULL;

        // Check if the school branding exists
        $sql = "SELECT * FROM school_branding WHERE school_id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $schoolID);

        //execute the statement
        $stmt->execute();

        //if the result has rows, update the school branding
        if ($stmt->get_result()->num_rows > 0) {
            //SQL to Update the school branding
            $sql = "UPDATE school_branding SET school_color = ? WHERE school_id = ?";

            //prepare the statement
            $stmt = prepareStatement($this->mysqli, $sql);

            //bind the parameters
            $stmt->bind_param("si", $color, $schoolID);

            //execute the statement
            $stmt->execute();

            //get the result
            $result = $stmt->affected_rows > 0;

            //return the result
            return $result;
        }

        //if the result does not have rows, create the school branding
        $sql = "INSERT INTO school_branding (school_id, school_logo, school_color) VALUES (?, ?, ?)";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("iis", $schoolID, $defaultLogo, $color);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->affected_rows > 0;

        return $result;
    }

    /**
     * Update a school in the database
     *
     * @param int $schoolID
     * @param string $schoolName
     * @param string $schoolAddress
     * @param string $schoolCity
     * @param string $schoolState
     * @param string $schoolZip
     * @param int $updatedBy
     * @return boolean $result
     */
    public function updateSchool(int $schoolID, string $schoolName, string $schoolAddress, string $schoolCity, string $schoolState, string $schoolZip, int $updatedBy): bool
    {
        //get the current date and time
        $updatedAt = date("Y-m-d H:i:s");

        //create the sql statement
        $sql = "UPDATE school SET name = ?, address = ?, city = ?, state = ?, zipcode = ?, updated_at = ?, updated_by = ? WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("ssssssii", $schoolName, $schoolAddress, $schoolCity, $schoolState, $schoolZip, $updatedAt, $updatedBy, $schoolID);

        //execute the statement
        $stmt->execute();

        //set the placeholder for the result
        $result = false;

        //check the result
        if ($stmt->affected_rows > 0) {
            $result = true;
        }

        //log the school activity
        $activity = new Activity();
        $activity->logActivity($updatedBy, 'Updated School', 'School ' . $schoolName);

        //return the result
        return $result;
    }

    /**
     * Create a school in the database
     *
     * @param string $schoolName
     * @param string $schoolAddress
     * @param string $schoolCity
     * @param string $schoolState
     * @param string $schoolZip
     * @param int $createdBy
     * @return boolean $result
     */
    public function createSchool(string $schoolName, string $schoolAddress, string $schoolCity, string $schoolState, string $schoolZip, int $createdBy): bool
    {
        //get the current date and time
        $createdAt = date("Y-m-d H:i:s");

        //create the sql statement
        $sql = "INSERT INTO school (name, address, city, state, zipcode, created_at, created_by, updated_at, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("ssssssisi", $schoolName, $schoolAddress, $schoolCity, $schoolState, $schoolZip, $createdAt, $createdBy, $createdAt, $createdBy);

        //execute the statement
        $stmt->execute();

        //set the placeholder for the result
        $result = false;

        //check the result
        if ($stmt->affected_rows > 0) {
            $result = true;
        }

        //log the school activity
        $activity = new Activity();
        $activity->logActivity($createdBy, 'Created School', 'School ' . $schoolName);

        //return the result
        return $result;
    }

    /**
     * Get school ID by school name
     *
     * @param string $schoolName
     * @return int $schoolID
     */
    public function getSchoolIdByName(string $schoolName): int
    {
        $schoolID = 0;
        $sql = "SELECT id FROM school WHERE name = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("s", $schoolName);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $schoolID = intval($result->fetch_assoc()['id']);
        }
        return $schoolID;
    }

    /**
     * Delete a school from the database
     *
     * @param int $schoolID
     * @return boolean $result
     */
    public function deleteSchool(int $schoolID): bool
    {
        //get the name of the school
        $schoolName = $this->getSchoolName($schoolID);

        //set the placeholder for the result
        $result = false;

        //create the sql statement
        $sql = "DELETE FROM school WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $schoolID);

        //execute the statement
        $stmt->execute();

        //check the result
        if ($stmt->affected_rows > 0) {
            $result = true;
        }

        //log the school activity if the school was deleted
        if ($result) {
            $activity = new Activity();
            //instance the session
            $session = new Session();
            //get the user id from the session
            $userID = intval($session->get('user_id')) ?? null;
            $activity->logActivity($userID, 'Deleted School', 'School ID: ' . $schoolID . ' School Name: ' . $schoolName);
        }

        //return the result
        return $result;
    }

    /**
     * Get the school ids of all schools using a specific logo, by the logo/media id
     *
     * @param int $mediaID logo/media id
     *
     * @return array $schoolIDs
     */
    public function getSchoolsByMediaId(int $mediaID): array
    {
        //SQL statement to get all the school ids using a logo that matches a media id
        $sql = "SELECT school_id FROM school_branding WHERE school_logo = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $mediaID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //array to hold the school ids
        $schoolIDs = array();

        //if the result has rows, loop through the rows and add them to the school ids array
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $schoolIDs[] = $row['school_id'];
            }
        }

        //return the school ids array
        return $schoolIDs;
    }
};
