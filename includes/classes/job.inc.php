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
     * @return JobField
     */
    public function getJobField(int $id): JobField
    {
        //instantiate the job field class
        $jobField = new JobField();
        //get the matching fields from the job field class
        $matchingFields = $jobField->getSubject($id);
        //should only be one matching field, so set the first one
        $jobField = $matchingFields[0];
        //return the job field
        return $jobField;
    }

    /**
     * Get a jobs description from the job ID
     *
     * @param int $id //job id
     * @return string
     */
    public function getJobDescription(int $id): string
    {
        $sql = "SELECT description FROM jobs WHERE id = $id";
        $result = $this->mysqli->query($sql);
        $description = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $description = $row['description'];
            }
        }
        return $description;
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
     * @return string //job type - 'FULL', 'PART', 'INTERN'
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
}
