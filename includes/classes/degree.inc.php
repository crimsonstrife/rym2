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
 * Degree Class, contains all the functions for the degree table.
 */
class Degree extends Grade implements Major
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
     * @param int $lvl_id //id from the degree levels table
     * @return array
     */
    public function getGrade(int $lvl_id): array
    {
        //initialize an empty array to store the degree level
        $grade = array();

        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT * FROM degree_lvl WHERE id = ?");

        //bind the parameters
        $stmt->bind_param('i', $lvl_id);

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //loop through the result and add the degree level to the array
        while ($row = $result->fetch_assoc()) {
            $grade[] = $row;
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
     * @param int $major_id //id from the majors table
     * @return array
     */
    public function getMajor(int $major_id): array
    {
        //initialize an empty array to store the major
        $major = array();

        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT * FROM major WHERE id = ?");

        //bind the parameters
        $stmt->bind_param('i', $major_id);

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //loop through the result and add the major to the array
        while ($row = $result->fetch_assoc()) {
            $major[] = $row;
        }

        //return the array of majors
        return $major;
    }

    /**
     * Add a degree level to the database
     * @param string $lvl_name //name of the degree level
     * @param int $user_id //id from the users table
     * @return bool
     */
    public function addGrade(string $lvl_name, int $user_id): bool
    {
        //get the current date and time
        $created_at = date('Y-m-d H:i:s');

        //prepare the query
        $stmt = $this->mysqli->prepare("INSERT INTO degree_lvl (name, created_at, updated_at, created_by, updated_by) VALUES (?, ?, ?, ?, ?)");

        //bind the parameters
        $stmt->bind_param('sssss', $lvl_name, $created_at, $created_at, $user_id, $user_id);

        //execute the query
        $stmt->execute();

        //check if the query was successful
        if ($stmt->affected_rows > 0) {
            //return true if successful
            return true;
        } else {
            //return false if unsuccessful
            return false;
        }
    }

    /**
     * Add a major to the database
     * @param string $major_name //name of the major
     * @param int $created_by //id from the users table
     * @return bool
     */
    public function addMajor(string $major_name, int $created_by): bool
    {
        //initialize the user class
        $user = new User();

        //validate the user id
        if (!$user->validateUserById($created_by)) {
            //if the user id is invalid, then the major is being created by a student submitting a form, so set the user to NULL
            $creationUser = NULL;
        } else {
            //if the user id is valid, then the major is being created by an admin, so set the user to the user id
            $creationUser = $created_by;
        }

        //get the current date and time
        $created_at = date('Y-m-d H:i:s');

        //prepare the query
        $stmt = $this->mysqli->prepare("INSERT INTO major (name, created_by, created_at, updated_by, updated_at) VALUES (?, ?, ?, ?, ?)");

        //bind the parameters
        $stmt->bind_param('sisis', $major_name, $creationUser, $created_at, $creationUser, $created_at);

        //execute the query
        $stmt->execute();

        //check if the query was successful
        if ($stmt->affected_rows > 0) {
            //return true if successful
            return true;
        } else {
            //return false if unsuccessful
            return false;
        }
    }

    /**
     * Update a degree level in the database
     * @param int $lvl_id //id from the degree levels table
     * @param string $lvl_name //name of the degree level
     * @param int $user_id //id from the users table
     * @return bool
     */
    public function updateGrade(int $lvl_id, string $lvl_name, int $user_id): bool
    {
        //get the current date and time
        $updated_at = date('Y-m-d H:i:s');

        //prepare the query
        $stmt = $this->mysqli->prepare("UPDATE degree_lvl SET name = ?, updated_at = ?, updated_by = ? WHERE id = ?");

        //bind the parameters
        $stmt->bind_param('sssi', $lvl_name, $updated_at, $user_id, $lvl_id);

        //execute the query
        $stmt->execute();

        //check if the query was successful
        if ($stmt->affected_rows > 0) {
            //return true if successful
            return true;
        } else {
            //return false if unsuccessful
            return false;
        }
    }

    /**
     * Update a major in the database
     * @param int $major_id //id from the majors table
     * @param string $major_name //name of the major
     * @param int $updated_by //id from the users table
     * @return bool
     */
    public function updateMajor(int $major_id, string $major_name, int $updated_by): bool
    {
        //get the current date and time
        $updated_at = date('Y-m-d H:i:s');

        //prepare the query
        $stmt = $this->mysqli->prepare("UPDATE major SET name = ?, updated_by = ?, updated_at = ? WHERE id = ?");

        //bind the parameters
        $stmt->bind_param('sisi', $major_name, $updated_by, $updated_at, $major_id);

        //execute the query
        $stmt->execute();

        //check if the query was successful
        if ($stmt->affected_rows > 0) {
            //return true if successful
            return true;
        } else {
            //return false if unsuccessful
            return false;
        }
    }

    /**
     * Delete a degree level from the database
     * @param int $lvl_id //id from the degree levels table
     * @return bool
     */
    public function deleteGrade(int $lvl_id): bool
    {
        //prepare the query
        $stmt = $this->mysqli->prepare("DELETE FROM degree_lvl WHERE id = ?");

        //bind the parameters
        $stmt->bind_param('i', $lvl_id);

        //execute the query
        $stmt->execute();

        //check if the query was successful
        if ($stmt->affected_rows > 0) {
            //return true if successful
            return true;
        } else {
            //return false if unsuccessful
            return false;
        }
    }

    /**
     * Delete a major from the database
     * @param int $major_id //id from the majors table
     * @return bool
     */
    public function deleteMajor(int $major_id): bool
    {
        //prepare the query
        $stmt = $this->mysqli->prepare("DELETE FROM major WHERE id = ?");

        //bind the parameters
        $stmt->bind_param('i', $major_id);

        //execute the query
        $stmt->execute();

        //check if the query was successful
        if ($stmt->affected_rows > 0) {
            //return true if successful
            return true;
        } else {
            //return false if unsuccessful
            return false;
        }
    }

    /**
     * Get the name of a degree level by the id
     * @param int $lvl_id //id from the degree levels table
     * @return string
     */
    public function getGradeNameById(int $lvl_id): string
    {
        //initialize an empty string to store the degree level name
        $grade_name = "";

        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT name FROM degree_lvl WHERE id = ?");
        //bind the parameters
        $stmt->bind_param('i', $lvl_id);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //there should only be one result, so get the first item in the array
        $row = $result->fetch_assoc();
        //set the degree level name
        $grade_name = $row['name'];

        //return the string
        return $grade_name;
    }

    /**
     * Get the name of a major by the id
     * @param int $major_id //id from the majors table
     * @return string
     */
    public function getMajorNameById(int $major_id): string
    {
        //initialize an empty string to store the major name
        $major_name = "";

        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT name FROM major WHERE id = ?");
        //bind the parameters
        $stmt->bind_param('i', $major_id);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //there should only be one result, so get the first item in the array
        $row = $result->fetch_assoc();
        //set the major name
        $major_name = $row['name'];

        //return the string
        return $major_name;
    }

    /**
     * Get the created date of a degree level by the id
     * @param int $lvl_id //id from the degree levels table
     * @return string
     */
    public function getGradeCreatedDate(int $lvl_id): string
    {
        //initialize an empty string to store the created date
        $created_at = "";

        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT created_at FROM degree_lvl WHERE id = ?");
        //bind the parameters
        $stmt->bind_param('i', $lvl_id);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //there should only be one result, so get the first item in the array
        $row = $result->fetch_assoc();
        //set the created date
        $created_at = $row['created_at'];

        //return the string
        return $created_at;
    }

    /**
     * Get the created date of a major by the id
     * @param int $major_id //id from the majors table
     * @return string
     */
    public function getMajorCreatedDate(int $major_id): string
    {
        //initialize an empty string to store the created date
        $created_at = "";

        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT created_at FROM major WHERE id = ?");
        //bind the parameters
        $stmt->bind_param('i', $major_id);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //there should only be one result, so get the first item in the array
        $row = $result->fetch_assoc();
        //set the created date
        $created_at = $row['created_at'];

        //return the string
        return $created_at;
    }

    /**
     * Get the updated date of a degree level by the id
     * @param int $lvl_id //id from the degree levels table
     * @return string
     */
    public function getGradeUpdatedDate(int $lvl_id): string
    {
        //initialize an empty string to store the updated date
        $updated_at = "";

        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT updated_at FROM degree_lvl WHERE id = ?");
        //bind the parameters
        $stmt->bind_param('i', $lvl_id);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //there should only be one result, so get the first item in the array
        $row = $result->fetch_assoc();
        //set the updated date
        $updated_at = $row['updated_at'];

        //return the string
        return $updated_at;
    }

    /**
     * Get the updated date of a major by the id
     * @param int $major_id //id from the majors table
     * @return string
     */
    public function getMajorUpdatedDate(int $major_id): string
    {
        //initialize an empty string to store the updated date
        $updated_at = "";

        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT updated_at FROM major WHERE id = ?");
        //bind the parameters
        $stmt->bind_param('i', $major_id);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //there should only be one result, so get the first item in the array
        $row = $result->fetch_assoc();
        //set the updated date
        $updated_at = $row['updated_at'];

        //return the string
        return $updated_at;
    }

    /**
     * Get the created by user of a degree level by the id
     * @param int $lvl_id //id from the degree levels table
     * @return User
     */
    public function getGradeCreatedBy(int $lvl_id): User
    {
        //initialize an empty user object
        $user = new User();

        $sql = "SELECT updated_by FROM degree_lvl WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $lvl_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $created_by = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $created_by = $row['created_by'];
            }
        }
        //Get User by id
        $userArray = $user->getUserById($created_by);
        //Get the first user in the array, should only be one match
        $user = $userArray[0];

        //return the user object
        return $user;
    }

    /**
     * Get the created by user of a major by the id
     * @param int $major_id //id from the majors table
     * @return User
     */
    public function getMajorCreatedBy(int $major_id): User
    {
        //initialize an empty user object
        $user = new User();

        $sql = "SELECT updated_by FROM major WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $major_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $created_by = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $created_by = $row['created_by'];
            }
        }
        //Get User by id
        $userArray = $user->getUserById($created_by);
        //Get the first user in the array, should only be one match
        $user = $userArray[0];

        //return the user object
        return $user;
    }

    /**
     * Get the updated by user of a degree level by the id
     * @param int $lvl_id //id from the degree levels table
     * @return User
     */
    public function getGradeUpdatedBy(int $lvl_id): User
    {
        //initialize an empty user object
        $user = new User();

        $sql = "SELECT updated_by FROM degree_lvl WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $lvl_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $updated_by = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $updated_by = $row['updated_by'];
            }
        }
        //Get User by id
        $userArray = $user->getUserById($updated_by);
        //Get the first user in the array, should only be one match
        $user = $userArray[0];

        //return the user object
        return $user;
    }

    /**
     * Get the updated by user of a major by the id
     * @param int $major_id //id from the majors table
     * @return User
     */
    public function getMajorUpdatedBy(int $major_id): User
    {
        //initialize an empty user object
        $user = new User();

        $sql = "SELECT updated_by FROM major WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $major_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $updated_by = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $updated_by = $row['updated_by'];
            }
        }
        //Get User by id
        $userArray = $user->getUserById($updated_by);
        //Get the first user in the array, should only be one match
        $user = $userArray[0];

        //return the user object
        return $user;
    }

    /**
     * Set the created by user of a degree level by the id
     * @param int $lvl_id //id from the degree levels table
     * @param int $user_id //id from the users table
     * @return bool
     */
    public function setGradeCreatedBy(int $lvl_id, int $user_id): bool
    {
        $sql = "UPDATE degree_lvl SET created_by = ? WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ii", $user_id, $lvl_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set the created by user of a major by the id
     * @param int $major_id //id from the majors table
     * @param int $user_id //id from the users table
     * @return bool
     */
    public function setMajorCreatedBy(int $major_id, int $user_id): bool
    {
        $sql = "UPDATE major SET created_by = ? WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ii", $user_id, $major_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set the updated by user of a degree level by the id
     * @param int $lvl_id //id from the degree levels table
     * @param int $user_id //id from the users table
     * @return bool
     */
    public function setGradeUpdatedBy(int $lvl_id, int $user_id): bool
    {
        $sql = "UPDATE degree_lvl SET updated_by = ? WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ii", $user_id, $lvl_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set the updated by user of a major by the id
     * @param int $major_id //id from the majors table
     * @param int $user_id //id from the users table
     * @return bool
     */
    public function setMajorUpdatedBy(int $major_id, int $user_id): bool
    {
        $sql = "UPDATE major SET updated_by = ? WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ii", $user_id, $major_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the total number of degree levels in the database
     * @return int
     */
    public function getGradesCount(): int
    {
        //prepare sql statement
        $sql = "SELECT COUNT(*) FROM degree_lvl";
        //execute the query
        $result = $this->mysqli->query($sql);
        //initialize a variable to store the count
        $count = 0;
        //check if the query returned any results
        if ($result->num_rows > 0) {
            //loop through the results
            while ($row = $result->fetch_assoc()) {
                //set the count
                $count = $row['COUNT(*)'];
            }
        }
        //return the count
        return $count;
    }

    /**
     * Get the total number of majors in the database
     * @return int
     */
    public function getMajorCount(): int
    {
        //prepare sql statement
        $sql = "SELECT COUNT(*) FROM major";
        //execute the query
        $result = $this->mysqli->query($sql);
        //initialize a variable to store the count
        $count = 0;
        //check if the query returned any results
        if ($result->num_rows > 0) {
            //loop through the results
            while ($row = $result->fetch_assoc()) {
                //set the count
                $count = $row['COUNT(*)'];
            }
        }
        //return the count
        return $count;
    }

    /**
     * Check if a degree level exists in the database by id
     *
     * @param int $lvl_id //id from the degree levels table
     * @return bool
     */
    public function checkGradeById($lvl_id): bool
    {
        //prepare sql statement
        $sql = "SELECT * FROM degree_lvl WHERE id = ?";
        //prepare the query
        $stmt = $this->mysqli->prepare($sql);
        //bind the parameters
        $stmt->bind_param('i', $lvl_id);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //check if the query returned any results
        if ($result->num_rows > 0) {
            //return true if the degree level exists
            return true;
        } else {
            //return false if the degree level does not exist
            return false;
        }
    }

    /**
     * Set the major created date in the database by id
     *
     * @param int $major_id //id from the majors table
     * @param string $created_at //date created
     * @return bool
     */
    public function setMajorCreatedDate(int $major_id, string $created_at): bool
    {
        //prepare the query
        $sql = "UPDATE major SET created_at = ? WHERE id = ?";
        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);
        //bind the parameters
        $stmt->bind_param('si', $created_at, $major_id);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //check if the query was successful
        if ($result) {
            //return true if successful
            return true;
        } else {
            //return false if unsuccessful
            return false;
        }
    }

    /**
     * Set the major updated date in the database by id
     *
     * @param int $major_id //id from the majors table
     * @param string $updated_at //date updated
     * @return bool
     */
    public function setMajorUpdatedDate(int $major_id, string $updated_at): bool
    {
        //prepare the query
        $sql = "UPDATE major SET updated_at = ? WHERE id = ?";
        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);
        //bind the parameters
        $stmt->bind_param('si', $updated_at, $major_id);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //check if the query was successful
        if ($result) {
            //return true if successful
            return true;
        } else {
            //return false if unsuccessful
            return false;
        }
    }

    /**
     * Set the grade created date in the database by id
     *
     * @param int $lvl_id //id from the degree levels table
     * @param string $created_at //date created
     * @return bool
     */
    public function setGradeCreatedDate(int $lvl_id, string $created_at): bool
    {
        //prepare the query
        $sql = "UPDATE degree_lvl SET created_at = ? WHERE id = ?";
        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);
        //bind the parameters
        $stmt->bind_param('si', $created_at, $lvl_id);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //check if the query was successful
        if ($result) {
            //return true if successful
            return true;
        } else {
            //return false if unsuccessful
            return false;
        }
    }

    /**
     * Set the grade updated date in the database by id
     *
     * @param int $lvl_id //id from the degree levels table
     * @param string $updated_at //date updated
     * @return bool
     */
    public function setGradeUpdatedDate(int $lvl_id, string $updated_at): bool
    {
        //prepare the query
        $sql = "UPDATE degree_lvl SET updated_at = ? WHERE id = ?";
        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);
        //bind the parameters
        $stmt->bind_param('si', $updated_at, $lvl_id);
        //execute the query
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //check if the query was successful
        if ($result) {
            //return true if successful
            return true;
        } else {
            //return false if unsuccessful
            return false;
        }
    }

    /**
     * Get the degree program for a student
     *
     * @param int $students_degreeId //id from the students table
     * @param int $students_majorId //id from the students table
     * @return string
     */
    public function getDegreeProgram(int $students_degreeId, int $students_majorId): string
    {
        //initialize an empty string to store the degree program
        $degree_program = "";
        //get the degree level and major
        $major = $this->getMajorNameById($students_majorId);
        $degree = $this->getGradeNameById($students_degreeId);
        //format the string
        $degree_program = $degree . ", " . $major;

        //return the string
        return $degree_program;
    }

    /**
     * Get a major by the name
     *
     * @param string $major_name //name of the major
     * @return bool //true if the major exists, false if not
     */
    public function getMajorByName(string $major_name): bool
    {
        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT * FROM major WHERE name = ?");

        //bind the parameters
        $stmt->bind_param('s', $major_name);

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //check if the query returned any results
        if ($result->num_rows > 0) {
            //return true if the major exists
            return true;
        } else {
            //return false if the major does not exist
            return false;
        }
    }

    /**
     * Get a major ID by the name
     *
     * @param string $lvl_name //name of major
     * @return int //id of the major
     */
    public function getMajorIdByName(string $lvl_name): int
    {
        //initialize an empty string to store the major id
        $major_id = 0;

        //prepare the query
        $stmt = $this->mysqli->prepare("SELECT id FROM major WHERE name = ?");

        //bind the parameters
        $stmt->bind_param('s', $lvl_name);

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //loop through the result and add the major id to the array
        while ($row = $result->fetch_assoc()) {
            $major_id = $row['id'];
        }

        //return the array of majors
        return $major_id;
    }
}
