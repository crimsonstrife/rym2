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

use User;
use Activity;

class Job
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
     * Get all the jobs from the database
     *
     * @return array
     */
    public function getAllJobs(): array
    {
        //sql statement to get all the jobs
        $sql = "SELECT * FROM jobs";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the results
        $result = $stmt->get_result();

        //placeholder for the jobs
        $jobs = [];

        //if there are results
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $jobs[] = $row;
            }
        }

        //return the jobs
        return $jobs;
    }

    /**
     * Get a single job from the database
     *
     * @param int $jobID //job id
     * @return array
     */
    public function getJob(int $jobID): array
    {
        //sql statement to get the job
        $sql = "SELECT * FROM jobs WHERE id = $jobID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the results
        $result = $stmt->get_result();

        //placeholder for the job
        $job = [];

        //if there are results
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $job = $row;
            }
        }

        //return the job
        return $job;
    }

    /**
     * Get a jobs field from the field ID
     *
     * @param int $jobID //field id from the jobs table
     * @return int $fieldID //field id from the areas of interest table
     */
    public function getJobField(int $jobID): int
    {
        //sql statement to get the field id
        $sql = "SELECT field FROM jobs WHERE id = $jobID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the results
        $result = $stmt->get_result();

        //placeholder for the field id
        $fieldID = 0;

        //if there are results
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $fieldID = $row['field'];
            }
        }

        //return the field id
        return intval($fieldID);
    }

    /**
     * Get a jobs description from the job ID
     *
     * @param int $jobID //job id
     * @return string
     */
    public function getJobDescription(int $jobID): string
    {
        //sql statement to get the job description
        $sql = "SELECT description FROM jobs WHERE id = $jobID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the results
        $result = $stmt->get_result();

        //placeholder for the description
        $description = '';

        //if there are results
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $description = $row['description'];
            }
        }

        //return the description
        return $description;
    }

    /**
     * Get a jobs title from the job ID
     *
     * @param int $jobID //job id
     * @return string
     */
    public function getJobTitle(int $jobID): string
    {
        //sql statement to get the job title
        $sql = "SELECT name FROM jobs WHERE id = $jobID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the results
        $result = $stmt->get_result();

        //placeholder for the title
        $title = "";

        //if there are results
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $title = $row['name'];
            }
        }

        //return the title
        return $title;
    }

    /**
     * Get a jobs type from the job ID
     *
     * @param int $jobID //job id
     * @return string //job type - 'Full Time', 'Part Time', 'Internship'
     */
    public function getJobType(int $jobID): string
    {
        //get the type from the job table
        $type = $this->getJobTypeEnum($jobID);

        //placeholder for the string
        $typeString = "";

        //depending on the type, set the correct string
        switch ($type) {
            case 'FULL':
                $typeString = 'Full Time';
                break;
            case 'PART':
                $typeString = 'Part Time';
                break;
            case 'INTERN':
                $typeString = 'Internship';
                break;
            default:
                $typeString = 'Unknown';
                break;
        }

        //return the type string
        return $typeString;
    }

    /**
     * Get a jobs type enum from the job ID
     *
     * @param int $jobID //job id
     * @return string //job type - 'FULL', 'PART', 'INTERN'
     */
    public function getJobTypeEnum(int $jobID): string
    {
        //sql statement to get the job type
        $sql = "SELECT type FROM jobs WHERE id = $jobID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the results
        $result = $stmt->get_result();

        //placeholder for the type
        $type = "";

        //if there are results
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $type = $row['type'];
            }
        }

        //return the type
        return $type;
    }

    /**
     * Get job creation date from the job ID
     *
     * @param int $jobID //job id
     * @return string
     */
    public function getJobCreatedDate(int $jobID): string
    {
        //sql statement to get the job creation date
        $sql = "SELECT created_at FROM jobs WHERE id = $jobID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the results
        $result = $stmt->get_result();

        //placeholder for the created date
        $createdAt = "";

        //if there are results
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $createdAt = $row['created_at'];
            }
        }

        //return the created date
        return $createdAt;
    }

    /**
     * Get job last updated date from the job ID
     *
     * @param int $jobID //job id
     * @return string
     */
    public function getJobLastUpdatedDate(int $jobID): string
    {
        //get the last updated date from the job table
        $sql = "SELECT updated_at FROM jobs WHERE id = $jobID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the results
        $result = $stmt->get_result();

        //placeholder for the last updated date
        $updatedAt = "";

        //if there are results
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $updatedAt = $row['updated_at'];
            }
        }

        //return the last updated date
        return $updatedAt;
    }

    /**
     * Get the job creator from the job ID
     *
     * @param int $jobID //job id
     * @return User //user object
     */
    public function getJobCreatedBy(int $jobID): User
    {
        //get the created by user id from the job table
        $sql = "SELECT created_by FROM jobs WHERE id = $jobID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the results
        $result = $stmt->get_result();

        //placeholder for the user id
        $createdBy = 0;

        //if there are results
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $createdBy = $row['created_by'];
            }
        }

        //instantiate the user class
        $user = new User();
        //get the matching users from the user class
        $userArray = $user->getUserById($createdBy);
        //should only be one matching user, so set the first one
        $user = $userArray[0];
        //return the user
        return $user;
    }

    /**
     * Get the job last updated by user from the job ID
     *
     * @param int $jobID //job id
     * @return User //user object
     */
    public function getJobLastUpdatedBy(int $jobID): User
    {
        //get the last updated by user id from the job table
        $sql = "SELECT updated_by FROM jobs WHERE id = $jobID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the results
        $result = $stmt->get_result();

        //placeholder for the user id
        $updatedBy = 0;

        //if there are results
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $updatedBy = $row['updated_by'];
            }
        }

        //instantiate the user class
        $user = new User();
        //get the matching users from the user class
        $userArray = $user->getUserById($updatedBy);
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
     * @param int $education //degree level
     * @param array $skills //required job skills
     * @param int $createdBy //user id of the user creating the job
     * @return bool
     */
    public function addJob(string $name, array $description, string $type, int $field, int $education, array $skills, int $createdBy): bool
    {
        //split the description array into the summary and description
        $summary = $description['job_summary'];
        $description = $description['job_description'];

        //convert the skills array to a JSON string
        $skillsString = json_encode($skills);

        //get the current date and time
        $date = date('Y-m-d H:i:s');

        //if the education is empty or 0, set it to null
        $education = ($education == 0 || $education == null) ? null : $education;

        //prepare the sql statement
        $sql = "INSERT INTO jobs (name, description, summary, type, field, education, skills, created_at, updated_at, created_by, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("ssssiisssii", $name, $description, $summary, $type, $field, $education, $skillsString, $date, $date, $createdBy, $createdBy);

        //execute the statement
        $stmt->execute();

        //check if the query was successful
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($createdBy, 'JOB', 'CREATED' . $name);
            return true;
        }

        return false;
    }

    /**
     * Update a job in the database
     *
     * @param int $jobID //job id
     * @param string $name //job name
     * @param array $description //job description
     * @param string $type //job type
     * @param int $field //job field
     * @param int $education //degree level
     * @param array $skills //required job skills
     * @param int $updatedBy //user id of the user updating the job
     * @return bool
     */
    public function updateJob(int $jobID, string $name, array $description, string $type, int $field, int $education, array $skills, int $updatedBy): bool
    {
        //split the description array into the summary and description
        $summary = $description['job_summary'];
        $description = $description['job_description'];

        //convert the skills array to a JSON string
        $skillsString = json_encode($skills);

        //get the current date and time
        $date = date('Y-m-d H:i:s');

        //if the education is empty or 0, set it to null
        if ($education == 0 || $education == null) {
            $education = null;
        }

        //prepare the sql statement
        $sql = "UPDATE jobs SET name = ?, description = ?, summary = ?, type = ?, field = ?, education = ?, skills = ?, updated_at = ?, updated_by = ? WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("ssssiissii", $name, $description, $summary, $type, $field, $education, $skillsString, $date, $updatedBy, $jobID);

        //execute the statement
        $stmt->execute();

        //check if the query was successful
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($updatedBy, 'JOB', 'UPDATED' . $name);
            return true;
        }

        return false;
    }

    /**
     * Get Jobs by field
     * @param int $field //field id
     * @return array
     */
    public function getJobsByField(int $field): array
    {
        //sql statement to get the jobs by field
        $sql = "SELECT * FROM jobs WHERE field = $field";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the results
        $result = $stmt->get_result();

        //placeholder for the jobs
        $jobs = [];

        //if there are results
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $jobs[] = $row;
            }
        }

        //return the jobs
        return $jobs;
    }

    /**
     * Delete a job from the database
     *
     * @param int $jobID //job id
     * @return boolean $result
     */
    public function deleteJob(int $jobID): bool
    {
        //get the name of the job
        $jobName = $this->getJobTitle($jobID);

        //set the placeholder for the result
        $result = false;

        //create the sql statement
        $sql = "DELETE FROM jobs WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $jobID);

        //execute the statement
        $stmt->execute();

        //check the result
        if ($stmt->affected_rows > 0) {
            $result = true;
        }

        //log the job activity if the job was deleted
        if ($result) {
            $activity = new Activity();
            $activity->logActivity(intval($_SESSION['user_id']), 'Deleted Job', 'Job ID: ' . $jobID . ' Job Name: ' . $jobName);
        }

        //return the result
        return $result;
    }

    /**
     * Get the job skills from the job ID
     *
     * @param int $jobID //job id
     * @return array
     */
    public function getJobSkills(int $jobID): array
    {
        //sql statement to get the job skills
        $sql = "SELECT skills FROM jobs WHERE id = $jobID";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the results
        $result = $stmt->get_result();

        //placeholder for the skills
        $skills = '';

        //if there are results
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $skills = $row['skills'];
            }
        }

        //decode the JSON string
        $skillArray = json_decode($skills, true);

        //if the skills is empty, return an empty array
        if (empty($skills) || $skills == null) {
            return array();
        }

        //return the skills
        return $skillArray;
    }

    /**
     * Set the job skills for a job
     *
     * @param int $jobID //job id
     * @param array $skills //job skills
     *
     * @return bool
     */
    public function setJobSkills(int $jobID, array $skills): bool
    {
        //convert the skills array to a JSON string
        $skillsString = json_encode($skills);

        //get the current date and time
        $date = date('Y-m-d H:i:s');

        //prepare the sql statement
        $sql = "UPDATE jobs SET skills = ?, updated_at = ? WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("ssi", $skillsString, $date, $jobID);

        //execute the statement
        $stmt->execute();

        //check if the query was successful
        if ($stmt->affected_rows > 0) {
            return true;
        }

        return false;
    }

    /**
     * Get the job summary from the job ID
     *
     * @param int $jobID //job id
     *
     * @return string
     */
    public function getJobSummary(int $jobID): string
    {
        //placeholder for the summary
        $summary = '';

        //sql statement to get the job summary
        $sql = "SELECT summary FROM jobs WHERE id = $jobID";

        //execute the sql statement
        $result = $this->mysqli->query($sql);

        //if there are results
        if ($result->num_rows > 0) {
            //loop through the results
            while ($row = $result->fetch_assoc()) {
                //set the summary
                $summary = $row['summary'];
            }
        }

        //return the summary
        return $summary;
    }

    /**
     * Get the job education/ degree level from the job ID
     *
     * @param int $jobID //job id
     *
     * @return int
     */
    public function getJobEducation(int $jobID): int
    {
        //placeholder for the education
        $education = 0;

        //sql statement to get the job education
        $sql = "SELECT education FROM jobs WHERE id = $jobID";

        //execute the sql statement
        $result = $this->mysqli->query($sql);

        //if there are results
        if ($result->num_rows > 0) {
            //loop through the results
            while ($row = $result->fetch_assoc()) {
                //set the education
                $education = $row['education'];
            }
        }

        //if the education is null, return 0
        if ($education == null) {
            return 0;
        }

        //return the education
        return intval($education);
    }

    /**
     * Set the job education/ degree level for a job
     *
     * @param int $jobID //job id
     * @param int $education //degree level
     * @param int $updatedBy //user id of the user updating the job
     *
     * @return bool
     */
    public function setJobEducation(int $jobID, int $education, int $updatedBy = null): bool
    {
        //get the current date and time
        $date = date('Y-m-d H:i:s');

        //sql statement to update the job education
        $sql = "UPDATE jobs SET education = ?, updated_at = ?, updated_by = ? WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("isii", $education, $date, $updatedBy, $jobID);

        //execute the statement
        $stmt->execute();

        //check if the query was successful
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($updatedBy, 'JOB', 'UPDATED EDUCATION LEVEL FOR JOB ID: ' . $jobID);
            return true;
        }

        return false;
    }
}
