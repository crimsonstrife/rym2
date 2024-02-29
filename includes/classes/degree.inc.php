<?php
/**
 * Degree Class file for the College Recruitment Application
 * Contains all the functions for the Degree Class.
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/17/2023
 *
 * @package RYM2
 * Filename: degree.inc.php
 * @version 1.0.0
 * @requires PHP 8.1.2+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* Include the base application config file */
require_once(__DIR__ . '/../../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');

/**
 * Degree Class, contains all the functions for the degree table.
 */
class Degree extends Grade implements Major
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
     * Get all the degree levels from the database
     * @return array
     */
    public function getAllGrades(): array
    {
        //initialize an empty array to store the degree levels
        $grades = array();

        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT * FROM degree_lvl");

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //loop through the result and add the degree levels to the array
        while ($row = $result->fetch_assoc()) {
            $grades[] = $row;
        }

        //return the array of degree levels
        return $grades;
    }

    /**
     * Get a single degree level from the database
     * @param int $gradeID //id from the degree levels table
     * @return array
     */
    public function getGrade(int $gradeID): array
    {
        //initialize an empty array to store the degree level
        $grade = array();

        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT * FROM degree_lvl WHERE id = ?");

        //bind the parameters
        $stmt->bind_param('i', $gradeID);

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //loop through the result and add the degree level to the array
        while ($row = $result->fetch_assoc()) {
            $grade = $row;
        }

        //return the array of degree levels
        return $grade;
    }

    /**
     * Get all the majors from the database
     * @return array
     */
    public function getAllMajors(): array
    {
        //initialize an empty array to store the majors
        $majors = array();

        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT * FROM major");

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //loop through the result and add the majors to the array
        while ($row = $result->fetch_assoc()) {
            $majors[] = $row;
        }

        //return the array of majors
        return $majors;
    }

    /**
     * Get a single major from the database
     * @param int $majorID //id from the majors table
     * @return array
     */
    public function getMajor(int $majorID): array
    {
        //initialize an empty array to store the major
        $major = array();

        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT * FROM major WHERE id = ?");

        //bind the parameters
        $stmt->bind_param('i', $majorID);

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //loop through the result and add the major to the array
        while ($row = $result->fetch_assoc()) {
            $major = $row;
        }

        //return the array of majors
        return $major;
    }

    /**
     * Add a degree level to the database
     * @param string $gradeName //name of the degree level
     * @param int $userID //id from the users table
     * @return bool
     */
    public function addGrade(string $gradeName, int $userID): bool
    {
        //get the current date and time
        $createdAt = date('Y-m-d H:i:s');

        //prepare the query
        $stmt = $this->mysqli->prepare("INSERT INTO degree_lvl (name, created_at, updated_at, created_by, updated_by) VALUES (?, ?, ?, ?, ?)");

        //bind the parameters
        $stmt->bind_param('sssss', $gradeName, $createdAt, $createdAt, $userID, $userID);

        //execute the query
        $stmt->execute();

        //check if the query was successful
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, "Added degree level", "degree_lvl");
            //return true if successful
            return true;
        }

        //return false if unsuccessful
        return false;
    }

    /**
     * Add a major to the database
     * @param string $majorName //name of the major
     * @param ?int $createdBy //id from the users table
     * @return bool
     */
    public function addMajor(string $majorName, ?int $createdBy = null): bool
    {
        //instance the session object
        $session = new Session();

        //get the user id from the session
        $userID = $session->get('user_id') ?? null;

        //check if the user id matches the created by id
        if ($userID !== $createdBy) {
            //use the provided user id
            $userID = $createdBy;
        }

        //make sure the user id is either null, or not 0
        if ($userID === 0) {
            $userID = null;
        }

        //get the current date and time
        $createdAt = date('Y-m-d H:i:s');

        //alter the sql statement if the created by id is not null
        if ($userID !== null) {
            //prepare the query
            $stmt = $this->mysqli->prepare("INSERT INTO major (name, created_at, updated_at, created_by, updated_by) VALUES (?, ?, ?, ?, ?)");
            //bind the parameters
            $stmt->bind_param('sssii', $majorName, $createdAt, $createdAt, $userID, $userID);
        } else {
            //prepare the query
            $stmt = $this->mysqli->prepare("INSERT INTO major (name, created_at, updated_at) VALUES (?, ?, ?)");
            //bind the parameters
            $stmt->bind_param('sss', $majorName, $createdAt, $createdAt);
        }

        //execute the query
        $stmt->execute();

        //check if the query was successful
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, "Added major", "major");
            //return true if successful
            return true;
        }

        //return false if unsuccessful
        return false;
    }

    /**
     * Update a degree level in the database
     * @param int $gradeID //id from the degree levels table
     * @param string $gradeName //name of the degree level
     * @param int $userID //id from the users table
     * @return bool
     */
    public function updateGrade(int $gradeID, string $gradeName, int $userID): bool
    {
        //get the current date and time
        $updatedAt = date('Y-m-d H:i:s');

        //prepare the query
        $stmt = $this->mysqli->prepare("UPDATE degree_lvl SET name = ?, updated_at = ?, updated_by = ? WHERE id = ?");

        //bind the parameters
        $stmt->bind_param('sssi', $gradeName, $updatedAt, $userID, $gradeID);

        //execute the query
        $stmt->execute();

        //check if the query was successful
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, "Updated degree level", "degree_lvl");
            //return true if successful
            return true;
        }

        //return false if unsuccessful
        return false;
    }

    /**
     * Update a major in the database
     * @param int $majorID //id from the majors table
     * @param string $majorName //name of the major
     * @param int $updatedBy //id from the users table
     * @return bool
     */
    public function updateMajor(int $majorID, string $majorName, int $updatedBy): bool
    {
        //get the current date and time
        $updatedAt = date('Y-m-d H:i:s');

        //prepare the query
        $stmt = $this->mysqli->prepare("UPDATE major SET name = ?, updated_by = ?, updated_at = ? WHERE id = ?");

        //bind the parameters
        $stmt->bind_param('sisi', $majorName, $updatedBy, $updatedAt, $majorID);

        //execute the query
        $stmt->execute();

        //check if the query was successful
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($updatedBy, "Updated major", "major");
            //return true if successful
            return true;
        }

        //return false if unsuccessful
        return false;
    }

    /**
     * Delete a degree level from the database
     * @param int $gradeID //id from the degree levels table
     * @return bool
     */
    public function deleteGrade(int $gradeID): bool
    {
        //get the name of the degree level
        $gradeName = $this->getGradeNameById($gradeID);

        //set the placeholder for the result
        $result = false;

        //create the sql statement
        $sql = "DELETE FROM degree_lvl WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $gradeID);

        //execute the statement
        $stmt->execute();

        //check the result
        if ($stmt->affected_rows > 0) {
            $result = true;
        }

        //log the degree level activity if the degree level was deleted
        if ($result) {
            $activity = new Activity();
            //instance the session object
            $session = new Session();
            //get the user id from the session
            $userID = intval($session->get('user_id')) ?? null;
            $activity->logActivity($userID, 'Deleted Degree Level', 'Degree Level ID: ' . $gradeID . ' Degree Level Name: ' . $gradeName);
        }

        //return the result
        return $result;
    }

    /**
     * Delete a major from the database
     * @param int $majorID //id from the majors table
     * @return bool
     */
    public function deleteMajor(int $majorID): bool
    {
        //get the name of the major
        $majorName = $this->getMajorNameById($majorID);

        //set the placeholder for the result
        $result = false;

        //create the sql statement
        $sql = "DELETE FROM major WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $majorID);

        //execute the statement
        $stmt->execute();

        //check the result
        if ($stmt->affected_rows > 0) {
            $result = true;
        }

        //log the major activity if the major was deleted
        if ($result) {
            $activity = new Activity();
            //instance the session object
            $session = new Session();
            //get the user id from the session
            $userID = intval($session->get('user_id')) ?? null;
            $activity->logActivity($userID, 'Deleted Major', 'Major ID: ' . $majorID . ' Major Name: ' . $majorName);
        }

        //return the result
        return $result;
    }

    /**
     * Get the name of a degree level by the id
     * @param int $gradeID //id from the degree levels table
     * @return string
     */
    public function getGradeNameById(int $gradeID): string
    {
        //initialize an empty string to store the degree level name
        $gradeName = "";

        //create the sql statement
        $sql = "SELECT name FROM degree_lvl WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param('i', $gradeID);

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //check if the query returned any results
        if ($result->num_rows > 0) {
            //loop through the results
            while ($row = $result->fetch_assoc()) {
                //set the degree level name
                $gradeName = $row['name'];
            }
        }

        //return the string
        return $gradeName;
    }

    /**
     * Get the name of a major by the id
     * @param int $majorID //id from the majors table
     * @return string
     */
    public function getMajorNameById(int $majorID): string
    {
        //initialize an empty string to store the major name
        $majorName = "";

        //create the sql statement
        $sql = "SELECT name FROM major WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param('i', $majorID);

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //check if the query returned any results
        if ($result->num_rows > 0) {
            //loop through the results
            while ($row = $result->fetch_assoc()) {
                //set the major name
                $majorName = $row['name'];
            }
        }

        //return the string
        return $majorName;
    }

    /**
     * Get the created date of a degree level by the id
     * @param int $gradeID //id from the degree levels table
     * @return string
     */
    public function getGradeCreatedDate(int $gradeID): string
    {
        //initialize an empty string to store the created date
        $createdAt = "";

        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT created_at FROM degree_lvl WHERE id = ?");
        //bind the parameters
        $stmt->bind_param('i', $gradeID);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //there should only be one result, so get the first item in the array
        $row = $result->fetch_assoc();
        //set the created date
        $createdAt = $row['created_at'];

        //return the string
        return $createdAt;
    }

    /**
     * Get the created date of a major by the id
     * @param int $majorID //id from the majors table
     * @return string
     */
    public function getMajorCreatedDate(int $majorID): string
    {
        //initialize an empty string to store the created date
        $createdAt = "";

        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT created_at FROM major WHERE id = ?");
        //bind the parameters
        $stmt->bind_param('i', $majorID);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //there should only be one result, so get the first item in the array
        $row = $result->fetch_assoc();
        //set the created date
        $createdAt = $row['created_at'];

        //return the string
        return $createdAt;
    }

    /**
     * Get the updated date of a degree level by the id
     * @param int $gradeID //id from the degree levels table
     * @return string
     */
    public function getGradeUpdatedDate(int $gradeID): string
    {
        //initialize an empty string to store the updated date
        $updatedAt = "";

        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT updated_at FROM degree_lvl WHERE id = ?");
        //bind the parameters
        $stmt->bind_param('i', $gradeID);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //there should only be one result, so get the first item in the array
        $row = $result->fetch_assoc();
        //set the updated date
        $updatedAt = $row['updated_at'];

        //return the string
        return $updatedAt;
    }

    /**
     * Get the updated date of a major by the id
     * @param int $majorID //id from the majors table
     * @return string
     */
    public function getMajorUpdatedDate(int $majorID): string
    {
        //initialize an empty string to store the updated date
        $updatedAt = "";

        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT updated_at FROM major WHERE id = ?");
        //bind the parameters
        $stmt->bind_param('i', $majorID);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //there should only be one result, so get the first item in the array
        $row = $result->fetch_assoc();
        //set the updated date
        $updatedAt = $row['updated_at'];

        //return the string
        return $updatedAt;
    }

    /**
     * Get the created by user of a degree level by the id
     * @param int $gradeID //id from the degree levels table
     * @return User
     */
    public function getGradeCreatedBy(int $gradeID): User
    {
        //initialize an empty user object
        $user = new User();

        //create the sql statement
        $sql = "SELECT updated_by FROM degree_lvl WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $gradeID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //initialize the created by variable
        $createdBy = null;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $createdBy = $row['created_by'];
            }
        }

        //Get User by id
        $userArray = $user->getUserById($createdBy);

        //Get the first user in the array, should only be one match
        $user = $userArray[0];

        //return the user object
        return $user;
    }

    /**
     * Get the created by user of a major by the id
     * @param int $majorID //id from the majors table
     * @return User
     */
    public function getMajorCreatedBy(int $majorID): User
    {
        //initialize an empty user object
        $user = new User();

        $sql = "SELECT updated_by FROM major WHERE id = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("i", $majorID);
        $stmt->execute();
        $result = $stmt->get_result();
        $createdBy = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $createdBy = $row['created_by'];
            }
        }
        //Get User by id
        $userArray = $user->getUserById($createdBy);
        //Get the first user in the array, should only be one match
        $user = $userArray[0];

        //return the user object
        return $user;
    }

    /**
     * Get the updated by user of a degree level by the id
     * @param int $gradeID //id from the degree levels table
     * @return User
     */
    public function getGradeUpdatedBy(int $gradeID): User
    {
        //initialize an empty user object
        $user = new User();

        $sql = "SELECT updated_by FROM degree_lvl WHERE id = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("i", $gradeID);
        $stmt->execute();
        $result = $stmt->get_result();
        $updatedBy = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $updatedBy = $row['updated_by'];
            }
        }
        //Get User by id
        $userArray = $user->getUserById($updatedBy);
        //Get the first user in the array, should only be one match
        $user = $userArray[0];

        //return the user object
        return $user;
    }

    /**
     * Get the updated by user of a major by the id
     * @param int $majorID //id from the majors table
     * @return User
     */
    public function getMajorUpdatedBy(int $majorID): User
    {
        //initialize an empty user object
        $user = new User();

        $sql = "SELECT updated_by FROM major WHERE id = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("i", $majorID);
        $stmt->execute();
        $result = $stmt->get_result();
        $updatedBy = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $updatedBy = $row['updated_by'];
            }
        }
        //Get User by id
        $userArray = $user->getUserById($updatedBy);
        //Get the first user in the array, should only be one match
        $user = $userArray[0];

        //return the user object
        return $user;
    }

    /**
     * Set the created by user of a degree level by the id
     * @param int $gradeID //id from the degree levels table
     * @param int $userID //id from the users table
     * @return bool
     */
    public function setGradeCreatedBy(int $gradeID, int $userID): bool
    {
        $sql = "UPDATE degree_lvl SET created_by = ? WHERE id = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("ii", $userID, $gradeID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * Set the created by user of a major by the id
     * @param int $majorID //id from the majors table
     * @param int $userID //id from the users table
     * @return bool
     */
    public function setMajorCreatedBy(int $majorID, int $userID): bool
    {
        $sql = "UPDATE major SET created_by = ? WHERE id = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("ii", $userID, $majorID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * Set the updated by user of a degree level by the id
     * @param int $gradeID //id from the degree levels table
     * @param int $userID //id from the users table
     * @return bool
     */
    public function setGradeUpdatedBy(int $gradeID, int $userID): bool
    {
        $sql = "UPDATE degree_lvl SET updated_by = ? WHERE id = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("ii", $userID, $gradeID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * Set the updated by user of a major by the id
     * @param int $majorID //id from the majors table
     * @param int $userID //id from the users table
     * @return bool
     */
    public function setMajorUpdatedBy(int $majorID, int $userID): bool
    {
        $sql = "UPDATE major SET updated_by = ? WHERE id = ?";
        $stmt = prepareStatement($this->mysqli, $sql);
        $stmt->bind_param("ii", $userID, $majorID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * Check if a degree level exists in the database by id
     *
     * @param int $gradeID //id from the degree levels table
     * @return bool
     */
    public function checkGradeById($gradeID): bool
    {
        //prepare sql statement
        $sql = "SELECT * FROM degree_lvl WHERE id = ?";
        //prepare the query
        $stmt = prepareStatement($this->mysqli, $sql);
        //bind the parameters
        $stmt->bind_param('i', $gradeID);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //check if the query returned any results
        if ($result->num_rows > 0) {
            //return true if the degree level exists
            return true;
        }

        //return false if the degree level does not exist
        return false;
    }

    /**
     * Set the major created date in the database by id
     *
     * @param int $majorID //id from the majors table
     * @param string $createdAt //date created
     * @return bool
     */
    public function setMajorCreatedDate(int $majorID, string $createdAt): bool
    {
        //prepare the query
        $sql = "UPDATE major SET created_at = ? WHERE id = ?";
        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);
        //bind the parameters
        $stmt->bind_param('si', $createdAt, $majorID);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //check if the query was successful
        if ($result) {
            //return true if successful
            return true;
        }

        //return false if unsuccessful
        return false;
    }

    /**
     * Set the major updated date in the database by id
     *
     * @param int $majorID //id from the majors table
     * @param string $updatedAt //date updated
     * @return bool
     */
    public function setMajorUpdatedDate(int $majorID, string $updatedAt): bool
    {
        //prepare the query
        $sql = "UPDATE major SET updated_at = ? WHERE id = ?";
        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);
        //bind the parameters
        $stmt->bind_param('si', $updatedAt, $majorID);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //check if the query was successful
        if ($result) {
            //return true if successful
            return true;
        }

        //return false if unsuccessful
        return false;
    }

    /**
     * Set the grade created date in the database by id
     *
     * @param int $gradeID //id from the degree levels table
     * @param string $createdAt //date created
     * @return bool
     */
    public function setGradeCreatedDate(int $gradeID, string $createdAt): bool
    {
        //prepare the query
        $sql = "UPDATE degree_lvl SET created_at = ? WHERE id = ?";
        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);
        //bind the parameters
        $stmt->bind_param('si', $createdAt, $gradeID);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //check if the query was successful
        if ($result) {
            //return true if successful
            return true;
        }

        //return false if unsuccessful
        return false;
    }

    /**
     * Set the grade updated date in the database by id
     *
     * @param int $gradeID //id from the degree levels table
     * @param string $updatedAt //date updated
     * @return bool
     */
    public function setGradeUpdatedDate(int $gradeID, string $updatedAt): bool
    {
        //prepare the query
        $sql = "UPDATE degree_lvl SET updated_at = ? WHERE id = ?";
        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);
        //bind the parameters
        $stmt->bind_param('si', $updatedAt, $gradeID);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //check if the query was successful
        if ($result) {
            //return true if successful
            return true;
        }

        //return false if unsuccessful
        return false;
    }

    /**
     * Get a major by the name
     *
     * @param string $majorName //name of the major
     * @return bool //true if the major exists, false if not
     */
    public function getMajorByName(string $majorName): bool
    {
        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT * FROM major WHERE name = ?");

        //bind the parameters
        $stmt->bind_param('s', $majorName);

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //check if the query returned any results
        if ($result->num_rows > 0) {
            //return true if the major exists
            return true;
        }

        //return false if the major does not exist
        return false;
    }

    /**
     * Get a major ID by the name
     *
     * @param string $gradeName //name of major
     * @return int //id of the major
     */
    public function getMajorIdByName(string $gradeName): int
    {
        //initialize an empty string to store the major id
        $majorID = 0;

        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT id FROM major WHERE name = ?");

        //bind the parameters
        $stmt->bind_param('s', $gradeName);

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //loop through the result and add the major id to the array
        while ($row = $result->fetch_assoc()) {
            $majorID = $row['id'];
        }

        //return the array of majors
        return $majorID;
    }
}
