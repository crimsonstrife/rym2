<?php

/**
 * Job Class file for the College Recruitment Application
 * Contains all the functions for the Job Class and handles all the job related tasks with the database.
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/17/2023
 *
 * @package RYM2
 * Filename: job.inc.php
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

class Job
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
     * Get all the jobs from the database
     *
     * @return array
     */
    public function getAllJobs(): array
    {
        $sql = "SELECT * FROM jobs";
        $result = $this->mysqli->query($sql);
        $jobs = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $jobs[] = $row;
            }
        }
        return $jobs;
    }

    /**
     * Get a single job from the database
     *
     * @param int $id
     * @return array
     */
    public function getJob(int $id): array
    {
        $sql = "SELECT * FROM jobs WHERE id = $id";
        $result = $this->mysqli->query($sql);
        $job = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $job[] = $row;
            }
        }
        return $job;
    }

    /**
     * Get a jobs field from the field ID
     *
     * @param int $id //field id from the jobs table
     * @return int $fieldID //field id from the areas of interest table
     */
    public function getJobField(int $id): int
    {
        $sql = "SELECT field FROM jobs WHERE id = $id";
        $result = $this->mysqli->query($sql);
        $fieldID = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $fieldID = $row['field'];
            }
        }
        return intval($fieldID);
    }

    /**
     * Get a jobs description from the job ID
     *
     * @param int $id //job id
     * @return array
     */
    public function getJobDescription(int $id): array
    {
        $sql = "SELECT description FROM jobs WHERE id = $id";
        $result = $this->mysqli->query($sql);
        $description = '';
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $description = $row['description'];
            }
        }

        //if the description is empty, return an empty array
        if (empty($description) || $description == null) {
            return array();
        } else {
            //parse the JSON string into an array
            return json_decode($description, true);
        }
    }

    /**
     * Get a jobs title from the job ID
     *
     * @param int $id //job id
     * @return string
     */
    public function getJobTitle(int $id): string
    {
        $sql = "SELECT name FROM jobs WHERE id = $id";
        $result = $this->mysqli->query($sql);
        $title = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $title = $row['name'];
            }
        }
        return $title;
    }

    /**
     * Get a jobs type from the job ID
     *
     * @param int $id //job id
     * @return string //job type - 'Full Time', 'Part Time', 'Internship'
     */
    public function getJobType(int $id): string
    {
        $sql = "SELECT type FROM jobs WHERE id = $id";
        $result = $this->mysqli->query($sql);
        $type = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $type = $row['type'];
            }
        }
        //depending on the type, return the correct string
        if ($type == 'FULL') {
            return 'Full Time';
        } elseif ($type == 'PART') {
            return 'Part Time';
        } elseif ($type == 'INTERN') {
            return 'Internship';
        } else {
            return 'Internship';
        }
    }

    /**
     * Get a jobs type enum from the job ID
     *
     * @param int $id //job id
     * @return string //job type - 'FULL', 'PART', 'INTERN'
     */
    public function getJobTypeEnum(int $id): string
    {
        $sql = "SELECT type FROM jobs WHERE id = $id";
        $result = $this->mysqli->query($sql);
        $type = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $type = $row['type'];
            }
        }
        return $type;
    }

    /**
     * Get the job count from the database
     *
     * @return int
     */
    public function getJobCount(): int
    {
        $sql = "SELECT COUNT(*) FROM jobs";
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
     * Get job creation date from the job ID
     *
     * @param int $id //job id
     * @return string
     */
    public function getJobCreatedDate(int $id): string
    {
        $sql = "SELECT created_at FROM jobs WHERE id = $id";
        $result = $this->mysqli->query($sql);
        $created_at = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $created_at = $row['created_at'];
            }
        }
        return $created_at;
    }

    /**
     * Get job last updated date from the job ID
     *
     * @param int $id //job id
     * @return string
     */
    public function getJobLastUpdatedDate(int $id): string
    {
        $sql = "SELECT updated_at FROM jobs WHERE id = $id";
        $result = $this->mysqli->query($sql);
        $updated_at = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $updated_at = $row['updated_at'];
            }
        }
        return $updated_at;
    }

    /**
     * Get the job creator from the job ID
     *
     * @param int $id //job id
     * @return User //user object
     */
    public function getJobCreatedBy(int $id): User
    {
        //get the created by user id from the job table
        $sql = "SELECT created_by FROM jobs WHERE id = $id";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $created_by = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $created_by = $row['created_by'];
            }
        }

        //instantiate the user class
        $user = new User();
        //get the matching users from the user class
        $userArray = $user->getUserById($created_by);
        //should only be one matching user, so set the first one
        $user = $userArray[0];
        //return the user
        return $user;
    }

    /**
     * Get the job last updated by user from the job ID
     *
     * @param int $id //job id
     * @return User //user object
     */
    public function getJobLastUpdatedBy(int $id): User
    {
        //get the last updated by user id from the job table
        $sql = "SELECT updated_by FROM jobs WHERE id = $id";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $updated_by = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $updated_by = $row['updated_by'];
            }
        }

        //instantiate the user class
        $user = new User();
        //get the matching users from the user class
        $userArray = $user->getUserById($updated_by);
        //should only be one matching user, so set the first one
        $user = $userArray[0];
        //return the user
        return $user;
    }

    /**
     * Add a job to the database
     *
     * @param string $name //job name
     * @param array $description //job description
     * @param string $type //job type
     * @param int $field //job field
     * @param array $skills //required job skills
     * @param int $created_by //user id of the user creating the job
     * @return bool
     */
    public function addJob(string $name, array $description, string $type, int $field, array $skills, int $created_by): bool
    {
        //convert the description array to a JSON string
        $description = json_encode($description);

        //convert the skills array to a JSON string
        $skills = json_encode($skills);

        //get the current date and time
        $date = date('Y-m-d H:i:s');
        //prepare the sql statement
        $sql = "INSERT INTO jobs (name, description, type, field, skills, created_at, updated_at, created_by, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("sssssssii", $name, $description, $type, $field, $skills, $date, $date, $created_by, $created_by);
        $stmt->execute();

        //check if the query was successful
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($created_by, 'JOB', 'CREATED' . $name);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update a job in the database
     *
     * @param int $id //job id
     * @param string $name //job name
     * @param array $description //job description
     * @param string $type //job type
     * @param int $field //job field
     * @param array $skills //required job skills
     * @param int $updated_by //user id of the user updating the job
     * @return bool
     */
    public function updateJob(int $id, string $name, array $description, string $type, int $field, array $skills, int $updated_by): bool
    {
        //convert the description array to a JSON string
        $description = json_encode($description);
        //convert the skills array to a JSON string
        $skills = json_encode($skills);
        //get the current date and time
        $date = date('Y-m-d H:i:s');
        //prepare the sql statement
        $sql = "UPDATE jobs SET name = ?, description = ?, type = ?, field = ?, skills = ?, updated_at = ?, updated_by = ? WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ssssssii", $name, $description, $type, $field, $skills, $date, $updated_by, $id);
        $stmt->execute();

        //check if the query was successful
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($updated_by, 'JOB', 'UPDATED' . $name);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get Jobs by field
     * @param int $field //field id
     * @return array
     */
    public function getJobsByField(int $field): array
    {
        $sql = "SELECT * FROM jobs WHERE field = $field";
        $result = $this->mysqli->query($sql);
        $jobs = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $jobs[] = $row;
            }
        }
        return $jobs;
    }

    /**
     * Delete a job from the database
     *
     * @param int $job_id
     * @return boolean $result
     */
    public function deleteJob(int $job_id): bool
    {
        //get the current date and time
        $date = date("Y-m-d H:i:s");

        //get the name of the job
        $job_name = $this->getJobTitle($job_id);

        //set the placeholder for the result
        $result = false;

        //create the sql statement
        $sql = "DELETE FROM jobs WHERE id = ?";

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

        //bind the parameters
        $stmt->bind_param("i", $job_id);

        //execute the statement
        $stmt->execute();

        //check the result
        if ($stmt->affected_rows > 0) {
            $result = true;
        } else {
            $result = false;
        }

        //log the job activity if the job was deleted
        if ($result) {
            $activity = new Activity();
            $activity->logActivity(intval($_SESSION['user_id']), 'Deleted Job', 'Job ID: ' . $job_id . ' Job Name: ' . $job_name);
        }

        //return the result
        return $result;
    }

    /**
     * Get the job skills from the job ID
     *
     * @param int $id //job id
     * @return array
     */
    public function getJobSkills(int $id): array
    {
        $sql = "SELECT skills FROM jobs WHERE id = $id";
        $result = $this->mysqli->query($sql);
        $skills = '';
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $skills = $row['skills'];
            }
        }

        //if the skills is empty, return an empty array
        if (empty($skills) || $skills == null) {
            return array();
        } else {
            //parse the JSON string into an array
            return json_decode($skills, true);
        }
    }

    /**
     * Set the job skills for a job
     *
     * @param int $id //job id
     * @param array $skills //job skills
     *
     * @return bool
     */
    public function setJobSkills(int $id, array $skills): bool
    {
        //convert the skills array to a JSON string
        $skills = json_encode($skills);
        //get the current date and time
        $date = date('Y-m-d H:i:s');
        //prepare the sql statement
        $sql = "UPDATE jobs SET skills = ?, updated_at = ? WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ssi", $skills, $date, $id);
        $stmt->execute();

        //check if the query was successful
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}
