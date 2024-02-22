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

    public int $gradeID; //id from the degree levels table
    public string $gradeName; //name of the degree level
    public string $createdAt; //created_at date
    public string $updatedAt; //updated_at date
    public int $createdBy; //id from the users table
    public int $updatedBy; //id from the users table

    /**
     * Grade constructor.
     *
     * @param int $lvlID //id from the degree levels table
     * @param string $name //name of the degree level
     * @param string $createdAt //created_at date
     * @param string $updatedAt //updated_at date
     * @param int $createdBy //id from the users table
     * @param int $updatedBy //id from the users table
     *
     */
    public function __construct($lvlID, $name, $createdAt, $updatedAt, $createdBy, $updatedBy)
    {
        $this->gradeID = $lvlID;
        $this->gradeName = $name;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->createdBy = $createdBy;
        $this->updatedBy = $updatedBy;
    }

    /**
     * Get all the degree levels from the database
     * @return array
     */
    abstract public function getAllGrades(): array;

    /**
     * Get a single degree level from the database
     * @param int $gradeID //id from the degree levels table
     * @return array
     */
    abstract public function getGrade(int $gradeID): array;

    /**
     * Add a degree level to the database
     * @param string $gradeName //name of the degree level
     * @param int $userID //id from the users table
     * @return bool
     */
    abstract public function addGrade(string $gradeName, int $userID): bool;

    /**
     * Update a degree level in the database
     * @param int $gradeID //id from the degree levels table
     * @param string $gradeName //name of the degree level
     * @param int $userID //id from the users table
     * @return bool
     */
    abstract public function updateGrade(int $gradeID, string $gradeName, int $userID): bool;

    /**
     * Delete a degree level from the database
     * @param int $gradeID //id from the degree levels table
     * @return bool
     */
    abstract public function deleteGrade(int $gradeID): bool;

    /**
     * Check if a degree level exists in the database
     * @param int $gradeID //id from the degree levels table
     * @return bool
     */
    abstract public function checkGradeById(int $gradeID): bool;

    /**
     * Get name of a degree level from the database by id
     *
     * @param int $gradeID //id from the degree levels table
     * @return string
     */
    abstract public function getGradeNameById(int $gradeID): string;

    /**
     * Get the created_at date of a degree level from the database by id
     *
     * @param int $gradeID //id from the degree levels table
     * @return string
     */
    abstract public function getGradeCreatedDate(int $gradeID): string;

    /**
     * Get the updated_at date of a degree level from the database by id
     *
     * @param int $gradeID //id from the degree levels table
     * @return string
     */
    abstract public function getGradeUpdatedDate(int $gradeID): string;

    /**
     * Get the created_by user from the database by id
     *
     * @param int $gradeID //id from the degree levels table
     * @return User
     */
    abstract public function getGradeCreatedBy(int $gradeID): User;

    /**
     * Get the updated_by user from the database by id
     *
     * @param int $gradeID //id from the degree levels table
     * @return User
     */
    abstract public function getGradeUpdatedBy(int $gradeID): User;

    /**
     * Set the created_by user in the database by id
     *
     * @param int $gradeID //id from the degree levels table
     * @param int $userID //id from the users table
     * @return bool
     */
    abstract public function setGradeCreatedBy(int $gradeID, int $userID): bool;

    /**
     * Set the updated_by user in the database by id
     *
     * @param int $gradeID //id from the degree levels table
     * @param int $userID //id from the users table
     * @return bool
     */
    abstract public function setGradeUpdatedBy(int $gradeID, int $userID): bool;

    /**
     * Set the created_at date in the database by id
     *
     * @param int $gradeID //id from the degree levels table
     * @param string $createdAt //created_at date
     */
    abstract public function setGradeCreatedDate(int $gradeID, string $createdAt): bool;

    /**
     * Set the updated_at date in the database by id
     *
     * @param int $gradeID //id from the degree levels table
     * @param string $updatedAt //updated_at date
     */
    abstract public function setGradeUpdatedDate(int $gradeID, string $updatedAt): bool;
};
