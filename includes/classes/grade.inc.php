<?php

/**
 * Grade Class file for the College Recruitment Application
 * Contains all the functions for the Grade Class and handles all the "degree level" related tasks with the database.
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/17/2023
 *
 * @package RYM2
 * Filename: grade.inc.php
 * @version 1.0.0
 * @requires PHP 7.2.5+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

abstract class Grade
{

    public int $lvl_id; //id from the degree levels table
    public string $lvl_name; //name of the degree level
    public string $created_at; //created_at date
    public string $updated_at; //updated_at date
    public int $created_by; //id from the users table
    public int $updated_by; //id from the users table

    /**
     * Grade constructor.
     *
     * @param int $id //id from the degree levels table
     * @param string $name //name of the degree level
     * @param string $created_at //created_at date
     * @param string $updated_at //updated_at date
     * @param int $created_by //id from the users table
     * @param int $updated_by //id from the users table
     *
     */
    public function __construct($id, $name, $created_at, $updated_at, $created_by, $updated_by)
    {
        $this->lvl_id = $id;
        $this->lvl_name = $name;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }

    /**
     * Get all the degree levels from the database
     * @return array
     */
    abstract public function getAllGrades(): array;

    /**
     * Get a single degree level from the database
     * @param int $lvl_id //id from the degree levels table
     * @return array
     */
    abstract public function getGrade(int $lvl_id): array;

    /**
     * Add a degree level to the database
     * @param string $lvl_name //name of the degree level
     * @return bool
     */
    abstract public function addGrade(string $lvl_name): bool;

    /**
     * Update a degree level in the database
     * @param int $lvl_id //id from the degree levels table
     * @param string $lvl_name //name of the degree level
     * @return bool
     */
    abstract public function updateGrade(int $lvl_id, string $lvl_name): bool;

    /**
     * Delete a degree level from the database
     * @param int $lvl_id //id from the degree levels table
     * @return bool
     */
    abstract public function deleteGrade(int $lvl_id): bool;

    /**
     * Check if a degree level exists in the database
     * @param int $lvl_id //id from the degree levels table
     * @return bool
     */
    abstract public function checkGradeById(int $lvl_id): bool;

    /**
     * Get name of a degree level from the database by id
     *
     * @param int $lvl_id //id from the degree levels table
     * @return string
     */
    abstract public function getGradeNameById(int $lvl_id): string;

    /**
     * Get the created_at date of a degree level from the database by id
     *
     * @param int $lvl_id //id from the degree levels table
     * @return string
     */
    abstract public function getGradeCreatedDate(int $lvl_id): string;

    /**
     * Get the updated_at date of a degree level from the database by id
     *
     * @param int $lvl_id //id from the degree levels table
     * @return string
     */
    abstract public function getGradeUpdatedDate(int $lvl_id): string;

    /**
     * Get the created_by user from the database by id
     *
     * @param int $lvl_id //id from the degree levels table
     * @return User
     */
    abstract public function getGradeCreatedBy(int $lvl_id): User;

    /**
     * Get the updated_by user from the database by id
     *
     * @param int $lvl_id //id from the degree levels table
     * @return User
     */
    abstract public function getGradeUpdatedBy(int $lvl_id): User;

    /**
     * Set the created_by user in the database by id
     *
     * @param int $lvl_id //id from the degree levels table
     * @param int $user_id //id from the users table
     * @return bool
     */
    abstract public function setGradeCreatedBy(int $lvl_id, int $user_id): bool;

    /**
     * Set the updated_by user in the database by id
     *
     * @param int $lvl_id //id from the degree levels table
     * @param int $user_id //id from the users table
     * @return bool
     */
    abstract public function setGradeUpdatedBy(int $lvl_id, int $user_id): bool;

    /**
     * Set the created_at date in the database by id
     *
     * @param int $lvl_id //id from the degree levels table
     * @param string $created_at //created_at date
     */
    abstract public function setGradeCreatedDate(int $lvl_id, string $created_at): bool;

    /**
     * Set the updated_at date in the database by id
     *
     * @param int $lvl_id //id from the degree levels table
     * @param string $updated_at //updated_at date
     */
    abstract public function setGradeUpdatedDate(int $lvl_id, string $updated_at): bool;
};
