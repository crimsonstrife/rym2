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
        //sql statement to get all the subjects
        $sql = "SELECT * FROM aoi";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create an array to hold the subjects
        $subjects = [];

        //loop through the result and add the subjects to the array
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $subjects[] = $row;
            }
        }

        //return the array of subjects
        return $subjects;
    }

    /**
     * Get a single subject from the database
     *
     * @param int $aoiID //id from the areas of interest table
     * @return array
     */
    public function getSubject(int $aoiID): array
    {
        //sql statement to get the subject
        $sql = "SELECT * FROM aoi WHERE id = $aoiID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create an array to hold the subject
        $subject = [];

        //loop through the result and add the subject to the array
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $subject[] = $row;
            }
        }

        //return the array of subjects
        return $subject;
    }

    /**
     * Add a subject to the database
     *
     * @param string $aoiName //name of the subject
     * @param int $userID //id from the users table *optional
     * @return bool
     */
    public function addSubject(string $aoiName, int $userID = null): bool
    {
        //get current date and time
        $currentDateTime = date('Y-m-d H:i:s');

        //create the sql statement
        $sql = "INSERT INTO aoi (name, created_at, created_by, updated_at, updated_by) VALUES (?, ?, ?, ?, ?)";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("ssisi", $aoiName, $currentDateTime, $userID, $currentDateTime, $userID);

        //execute the statement
        $stmt->execute();

        //check for affected rows
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, "Added a new subject: " . $aoiName, "Subject");
            return true;
        }

        //return false if no rows were affected
        return false;
    }

    /**
     * Update a subject in the database
     *
     * @param int $aoiID //id from the areas of interest table
     * @param string $aoiName //name of the subject
     * @param int $userID //id from the users table *optional
     * @return bool
     */
    public function updateSubject(int $aoiID, string $aoiName, int $userID = null): bool
    {
        //get current date and time
        $currentDateTime = date('Y-m-d H:i:s');

        //create the sql statement
        $sql = "UPDATE aoi SET name = ?, updated_at = ?, updated_by = ? WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("ssii", $aoiName, $currentDateTime, $userID, $aoiID);

        //execute the statement
        $stmt->execute();

        //check for affected rows
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, "Updated subject: " . $aoiName, "Subject");
            return true;
        }

        //return false if no rows were affected
        return false;
    }

    /**
     * Delete a subject/field from the database
     *
     * @param int $subjectId //id from the areas of interest table
     * @return bool
     */
    public function deleteSubject(int $subjectId): bool
    {
        //get the name of the subject
        $subjectName = $this->getSubjectName($subjectId);

        //set the placeholder for the result
        $result = false;

        //create the sql statement
        $sql = "DELETE FROM aoi WHERE id = $subjectId";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //check the result
        if ($stmt->affected_rows > 0) {
            $result = true;
        }

        //log the subject activity if the subject was deleted
        if ($result) {
            $activity = new Activity();
            $session = new Session();
            $userID = $session->get('user_id');
            $activity->logActivity(intval($userID), 'Deleted Subject', 'Subject ID: ' . $subjectId . ' Subject Name: ' . $subjectName);
        }

        //return the result
        return $result;
    }

    /**
     * Get subject name by id
     *
     * @param int $aoiID //id from the areas of interest table
     * @return string
     */
    public function getSubjectName(int $aoiID): string
    {
        //sql statement to get the subject name by id
        $sql = "SELECT name FROM aoi WHERE id = $aoiID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //set the subject name to an empty string
        $subjectName = "";

        //loop through the result and add the subject name to the variable
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $subjectName = $row['name'];
            }
        }

        //return the subject name
        return $subjectName;
    }

    /**
     * Get the created date of a subject
     *
     * @param int $aoiID //id from the areas of interest table
     * @return string
     */
    public function getSubjectCreatedDate(int $aoiID): string
    {
        //sql to get the created date of the subject
        $sql = "SELECT created_at FROM aoi WHERE id = $aoiID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //set the created date to an empty string
        $createdAt = "";

        //loop through the result and add the created date to the variable
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $createdAt = $row['created_at'];
            }
        }

        //return the created date
        return $createdAt;
    }

    /**
     * Get the last updated date of a subject
     *
     * @param int $aoiID //id from the areas of interest table
     * @return string
     */
    public function getSubjectLastUpdatedDate(int $aoiID): string
    {
        //sql to get the last updated date of the subject
        $sql = "SELECT updated_at FROM aoi WHERE id = $aoiID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //set the last updated date to an empty string
        $updatedAt = "";

        //loop through the result and add the last updated date to the variable
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $updatedAt = $row['updated_at'];
            }
        }

        //return the last updated date
        return $updatedAt;
    }

    /**
     * Get the created by user of a subject
     *
     * @param int $aoiID //id from the areas of interest table
     * @return User //user object
     */
    public function getSubjectCreatedBy(int $aoiID): User
    {
        //sql to get the created by user of the subject
        $sql = "SELECT created_by FROM aoi WHERE id = $aoiID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //set the created by user to 0
        $createdBy = 0;

        //loop through the result and add the created by user to the variable
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
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
     * @param int $aoiID //id from the areas of interest table
     * @return User //user object
     */
    public function getSubjectLastUpdatedBy(int $aoiID): User
    {
        //sql to get the last updated by user of the subject
        $sql = "SELECT updated_by FROM aoi WHERE id = $aoiID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //set the last updated by user to 0
        $updatedBy = 0;

        //loop through the result and add the last updated by user to the variable
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
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
        //sql to get the total number of subjects
        $sql = "SELECT COUNT(*) FROM aoi";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //set the count to 0
        $count = 0;

        //loop through the result and add the count to the variable
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $count = $row['COUNT(*)'];
            }
        }

        //return the count
        return $count;
    }

    /**
     * Set the updated by user of a subject
     *
     * @param int $aoiID //id from the areas of interest table
     * @param int $userID //id from the users table
     * @return bool
     */
    public function setSubjectLastUpdatedBy(int $aoiID, int $userID): bool
    {
        //sql to set the updated by user of the subject
        $sql = "UPDATE aoi SET updated_by = ? WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("ii", $userID, $aoiID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //return true if the result is true
        if ($result) {
            return true;
        }

        //return false if the result is false
        return false;
    }

    /**
     * Set the created by user of a subject
     *
     * @param int $aoiID //id from the areas of interest table
     * @param int $userID //id from the users table
     * @return bool
     */
    public function setSubjectCreatedBy(int $aoiID, int $userID): bool
    {
        //sql to set the created by user of the subject
        $sql = "UPDATE aoi SET created_by = ? WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("ii", $userID, $aoiID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //return true if the result is true
        if ($result) {
            return true;
        }

        //return false if the result is false
        return false;
    }

    /**
     * Check if a subject exists in the database
     *
     * @param int $aoiID //id from the areas of interest table
     * @return bool
     */
    public function subjectExists(int $aoiID): bool
    {
        //sql to check if the subject exists
        $sql = "SELECT * FROM aoi WHERE id = $aoiID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //return true if the result has more than 0 rows
        if ($result->num_rows > 0) {
            return true;
        }

        //return false if the result has 0 rows
        return false;
    }
}
