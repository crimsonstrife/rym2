<?php

/**
 * Job Field Class file for the College Recruitment Application
 * Contains all the functions for the Job Field Class.
 *
 * Author: Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date: 10/17/2023
 *
 * @package RYM2
 * Filename: jobfield.inc.php
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
 * Job Field Class, extends the Subjects Class, contains relevant functions.
 */
class JobField extends Subject
{
    //Reference to the database
    private $mysqli;

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
     * Get all the subjects from the database
     *
     * @return array
     */
    public function getAllSubjects(): array
    {
        $sql = "SELECT * FROM aoi";
        $result = $this->mysqli->query($sql);
        $subjects = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $subjects[] = $row;
            }
        }
        return $subjects;
    }

    /**
     * Get a single subject from the database
     *
     * @param int $subjectID //id from the areas of interest table
     * @return array
     */
    public function getSubject(int $subjectID): array
    {
        $sql = "SELECT * FROM aoi WHERE id = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("i", $subjectID);
        $stmt->execute();
        $result = $stmt->get_result();
        $subject = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $subject[] = $row;
            }
        }
        return $subject;
    }

    /**
     * Add a subject to the database
     *
     * @param string $subjectName //name of the subject
     * @param int $userID //id from the users table
     * @return bool
     */
    public function addSubject(string $subjectName, int $userID): bool
    {
        //create the sql statement
        $sql = "INSERT INTO aoi (name) VALUES (?)";
        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);
        //bind the parameters
        $stmt->bind_param("s", $subjectName);
        //execute the statement
        $stmt->execute();
        //check the result
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, "Subject Added", "Subject " . $subjectName . " was added to the database.");
            return true;
        }

        return false;
    }

    /**
     * Update a subject in the database
     *
     * @param int $subjectID //id from the areas of interest table
     * @param string $subjectName //name of the subject
     * @param int $userID //id from the users table
     * @return bool
     */
    public function updateSubject(int $subjectID, string $subjectName, int $userID): bool
    {
        //create the sql statement
        $sql = "UPDATE aoi SET name = ? WHERE id = ?";
        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);
        //bind the parameters
        $stmt->bind_param("si", $subjectName, $subjectID);
        //execute the statement
        $stmt->execute();
        //check the result
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, "Subject Updated", "Subject " . $subjectName . " was updated in the database.");
            return true;
        }

        return false;
    }

    /**
     * Delete a subject from the database
     *
     * @param int $subjectID //id from the areas of interest table
     * @return bool
     */
    public function deleteSubject(int $subjectID): bool
    {
        //get the name of the subject
        $subjectName = $this->getSubjectName($subjectID);

        //set the placeholder for the result
        $result = false;

        //create the sql statement
        $sql = "DELETE FROM aoi WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $subjectID);

        //execute the statement
        $stmt->execute();

        //check the result
        if ($stmt->affected_rows > 0) {
            $result = true;
        }

        //log the subject activity if the subject was deleted
        if ($result) {
            //instantiate the activity and session classes
            $activity = new Activity();
            $session = new Session();
            //get the user id from the session
            $userID = intval($session->get('user_id'));
            //log the activity
            $activity->logActivity($userID, 'Deleted Subject', 'Subject ID: ' . $subjectID . ' Subject Name: ' . $subjectName);
        }

        //return the result
        return $result;
    }

    /**
     * Get Subject Name by ID
     *
     * @param int $subjectID //id from the areas of interest table
     * @return string
     */
    public function getSubjectName(int $subjectID): string
    {
        //create the sql statement
        $sql = "SELECT name FROM aoi WHERE id = ?";
        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);
        //bind the parameters
        $stmt->bind_param("i", $subjectID);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //set the subject name
        $subject = "";
        //check the result
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                //set the subject name
                $subject = $row['name'];
            }
        }
        //return the subject name
        return $subject;
    }

    /**
     * Get the created date of a subject
     *
     * @param int $subjectID //id from the areas of interest table
     * @return string
     */
    public function getSubjectCreatedDate(int $subjectID): string
    {
        //create the sql statement
        $sql = "SELECT created_at FROM aoi WHERE id = ?";
        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);
        //bind the parameters
        $stmt->bind_param("i", $subjectID);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //set the created date
        $createdAt = "";
        //check the result
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                //set the created date
                $createdAt = $row['created_at'];
            }
        }
        //return the created date
        return $createdAt;
    }

    /**
     * Get the last updated date of a subject
     *
     * @param int $subjectID //id from the areas of interest table
     * @return string
     */
    public function getSubjectLastUpdatedDate(int $subjectID): string
    {
        //create the sql statement
        $sql = "SELECT updated_at FROM aoi WHERE id = ?";
        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);
        //bind the parameters
        $stmt->bind_param("i", $subjectID);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //set the updated date
        $updatedAt = "";
        //check the result
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                //set the updated date
                $updatedAt = $row['updated_at'];
            }
        }
        //return the updated date
        return $updatedAt;
    }

    /**
     * Get the created by user of a subject
     *
     * @param int $subjectID //id from the areas of interest table
     * @return User //user object
     */
    public function getSubjectCreatedBy(int $subjectID): User
    {
        //create the sql statement
        $sql = "SELECT created_by FROM aoi WHERE id = ?";
        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);
        //bind the parameters
        $stmt->bind_param("i", $subjectID);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //set the created by user id
        $createdBy = null;
        //check the result
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                //set the created by user id
                $createdBy = $row['created_by'];
            }
        }
        //Instantiate the user class
        $user = new User();
        //Get User by id
        $userArray = $user->getUserById($createdBy);
        //Get the first user in the array, should only be one match
        $user = $userArray[0];
        //Return the user object
        return $user;
    }

    /**
     * Get the last updated by user of a subject
     *
     * @param int $subjectID //id from the areas of interest table
     * @return User //user object
     */
    public function getSubjectLastUpdatedBy(int $subjectID): User
    {
        //create the sql statement
        $sql = "SELECT updated_by FROM aoi WHERE id = ?";
        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);
        //bind the parameters
        $stmt->bind_param("i", $subjectID);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //set the updated by user id
        $updatedBy = null;
        //check the result
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                //set the updated by user id
                $updatedBy = $row['updated_by'];
            }
        }
        //Instantiate the user class
        $user = new User();
        //Get User by id
        $userArray = $user->getUserById($updatedBy);
        //Get the first user in the array, should only be one match
        $user = $userArray[0];
        //Return the user object
        return $user;
    }

    /**
     * Get the total number of subjects in the database
     *
     * @return int
     */
    public function getSubjectsCount(): int
    {
        //create the sql statement
        $sql = "SELECT COUNT(*) FROM aoi";
        //execute the statement
        $result = $this->mysqli->query($sql);
        //set the count
        $count = 0;
        //check the result
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                //set the count
                $count = $row['COUNT(*)'];
            }
        }
        //return the count
        return $count;
    }

    /**
     * Set the updated by user of a subject
     *
     * @param int $subjectID //id from the areas of interest table
     * @param int $userID //id from the users table
     * @return bool
     */
    public function setSubjectLastUpdatedBy(int $subjectID, int $userID): bool
    {
        //create the sql statement
        $sql = "UPDATE aoi SET updated_by = ? WHERE id = ?";
        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);
        //bind the parameters
        $stmt->bind_param("ii", $userID, $subjectID);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        if ($result) {
            //return true if the result is true
            return true;
        }

        //return false if the result is false
        return false;
    }

    /**
     * Set the created by user of a subject
     *
     * @param int $subjectID //id from the areas of interest table
     * @param int $userID //id from the users table
     * @return bool
     */
    public function setSubjectCreatedBy(int $subjectID, int $userID): bool
    {
        //create the sql statement
        $sql = "UPDATE aoi SET created_by = ? WHERE id = ?";
        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);
        //bind the parameters
        $stmt->bind_param("ii", $userID, $subjectID);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        if ($result) {
            //return true if the result is true
            return true;
        }

        //return false if the result is false
        return false;
    }

    /**
     * Check if a subject exists in the database
     *
     * @param int $subjectID //id from the areas of interest table
     * @return bool
     */
    public function subjectExists(int $subjectID): bool
    {
        //create the sql statement
        $sql = "SELECT * FROM aoi WHERE id = ?";
        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);
        //bind the parameters
        $stmt->bind_param("i", $subjectID);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //check the result
        if ($result->num_rows > 0) {
            //return true if the result is true
            return true;
        }

        //return false if the result is false
        return false;
    }
};
