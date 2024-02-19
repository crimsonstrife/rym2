<?php

/**
 * Area of Interests Class file for the College Recruitment Application
 * Contains all the functions for the Area of Interests Class.
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/17/2023
 *
 * @package RYM2
 * Filename: areaofinterest.inc.php
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
 * Area of Interests Class, extends the Subjects Class, contains relevant functions.
 */
class AreaOfInterest extends Subject
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
     * @param int $aoi_id //id from the areas of interest table
     * @return array
     */
    public function getSubject(int $aoi_id): array
    {
        $sql = "SELECT * FROM aoi WHERE id = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("i", $aoi_id);
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
     * @param string $aoi_name //name of the subject
     * @param int $user_id //id from the users table *optional
     * @return bool
     */
    public function addSubject(string $aoi_name, int $user_id = null): bool
    {
        //get current date and time
        $currentDateTime = date('Y-m-d H:i:s');
        $sql = "INSERT INTO aoi (name, created_at, created_by, updated_at, updated_by) VALUES (?, ?, ?, ?, ?)";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("ssisi", $aoi_name, $currentDateTime, $user_id, $currentDateTime, $user_id);
        $stmt->execute();

        //check for affected rows
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($user_id, "Added a new subject: " . $aoi_name, "Subject");
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update a subject in the database
     *
     * @param int $aoi_id //id from the areas of interest table
     * @param string $aoi_name //name of the subject
     * @param int $user_id //id from the users table *optional
     * @return bool
     */
    public function updateSubject(int $aoi_id, string $aoi_name, int $user_id = null): bool
    {
        //get current date and time
        $currentDateTime = date('Y-m-d H:i:s');
        $sql = "UPDATE aoi SET name = ?, updated_at = ?, updated_by = ? WHERE id = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("ssii", $aoi_name, $currentDateTime, $user_id, $aoi_id);
        $stmt->execute();

        //check for affected rows
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($user_id, "Updated subject: " . $aoi_name, "Subject");
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete a subject/field from the database
     *
     * @param int $subject_id //id from the areas of interest table
     * @return bool
     */
    public function deleteSubject(int $subject_id): bool
    {
        //get the current date and time
        $date = date("Y-m-d H:i:s");

        //get the name of the subject
        $subject_name = $this->getSubjectName($subject_id);

        //set the placeholder for the result
        $result = false;

        //create the sql statement
        $sql = "DELETE FROM aoi WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $subject_id);

        //execute the statement
        $stmt->execute();

        //check the result
        if ($stmt->affected_rows > 0) {
            $result = true;
        } else {
            $result = false;
        }

        //log the subject activity if the subject was deleted
        if ($result) {
            $activity = new Activity();
            $activity->logActivity(intval($_SESSION['user_id']), 'Deleted Subject', 'Subject ID: ' . $subject_id . ' Subject Name: ' . $subject_name);
        }

        //return the result
        return $result;
    }

    /**
     * Get subject name by id
     *
     * @param int $aoi_id //id from the areas of interest table
     * @return string
     */
    public function getSubjectName(int $aoi_id): string
    {
        $sql = "SELECT name FROM aoi WHERE id = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("i", $aoi_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $subject = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $subject = $row['name'];
            }
        }
        return $subject;
    }

    /**
     * Get the created date of a subject
     *
     * @param int $aoi_id //id from the areas of interest table
     * @return string
     */
    public function getSubjectCreatedDate(int $aoi_id): string
    {
        $sql = "SELECT created_at FROM aoi WHERE id = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("i", $aoi_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $created_at = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $created_at = $row['created_at'];
            }
        }
        return $created_at;
    }

    /**
     * Get the last updated date of a subject
     *
     * @param int $aoi_id //id from the areas of interest table
     * @return string
     */
    public function getSubjectLastUpdatedDate(int $aoi_id): string
    {
        $sql = "SELECT updated_at FROM aoi WHERE id = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("i", $aoi_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $updated_at = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $updated_at = $row['updated_at'];
            }
        }
        return $updated_at;
    }

    /**
     * Get the created by user of a subject
     *
     * @param int $aoi_id //id from the areas of interest table
     * @return User //user object
     */
    public function getSubjectCreatedBy(int $aoi_id): User
    {
        $sql = "SELECT created_by FROM aoi WHERE id = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("i", $aoi_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $created_by = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $created_by = $row['created_by'];
            }
        }
        //Instantiate the user class
        $user = new User();
        //Get User by id
        $userArray = $user->getUserById($created_by);
        //Get the first user in the array, should only be one match
        $user = $userArray[0];
        //Return the user object
        return $user;
    }

    /**
     * Get the last updated by user of a subject
     *
     * @param int $aoi_id //id from the areas of interest table
     * @return User //user object
     */
    public function getSubjectLastUpdatedBy(int $aoi_id): User
    {
        $sql = "SELECT updated_by FROM aoi WHERE id = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("i", $aoi_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $updated_by = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $updated_by = $row['updated_by'];
            }
        }
        //Instantiate the user class
        $user = new User();
        //Get User by id
        $userArray = $user->getUserById($updated_by);
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
        $sql = "SELECT COUNT(*) FROM aoi";
        $result = $this->mysqli->query($sql);
        $count = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $count = $row['COUNT(*)'];
            }
        }
        return $count;
    }

    /**
     * Set the updated by user of a subject
     *
     * @param int $aoi_id //id from the areas of interest table
     * @param int $user_id //id from the users table
     * @return bool
     */
    public function setSubjectLastUpdatedBy(int $aoi_id, int $user_id): bool
    {
        $sql = "UPDATE aoi SET updated_by = ? WHERE id = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("ii", $user_id, $aoi_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set the created by user of a subject
     *
     * @param int $aoi_id //id from the areas of interest table
     * @param int $user_id //id from the users table
     * @return bool
     */
    public function setSubjectCreatedBy(int $aoi_id, int $user_id): bool
    {
        $sql = "UPDATE aoi SET created_by = ? WHERE id = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("ii", $user_id, $aoi_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if a subject exists in the database
     *
     * @param int $aoi_id //id from the areas of interest table
     * @return bool
     */
    public function subjectExists(int $aoi_id): bool
    {
        $sql = "SELECT * FROM aoi WHERE id = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("i", $aoi_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}
