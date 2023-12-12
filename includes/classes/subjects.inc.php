<?php

/**
 * Subjects Class file for the College Recruitment Application
 * Contains all the functions for the Subjects Class.
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/17/2023
 *
 * @package RYM2
 * Filename: subjects.inc.php
 * @version 1.0.0
 * @requires PHP 7.2.5+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/**
 * Subjects Class, contains all the functions for the interests table, which can be extended into both areas of interest, and job fields.
 */
abstract class Subject
{
    public int $aoi_id; //id from the areas of interest table

    public string $aoi_name; //name of the subject

    public string $created_at; //created_at date

    public string $updated_at; //updated_at date

    public int $created_by; //id from the users table

    public int $updated_by; //id from the users table

    /**
     * Subjects constructor.
     *
     */
    public function __construct($id, $name, $created_at, string $updated_at, $created_by, $updated_by)
    {
        $this->aoi_id = $id;
        $this->aoi_name = $name;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }

    /**
     * Get all the subjects from the database
     *
     * @return array
     */
    abstract public function getAllSubjects(): array;

    /**
     * Get a single subject from the database
     *
     * @param int $aoi_id //id from the areas of interest table
     * @return array
     */
    abstract public function getSubject(int $aoi_id): array;

    /**
     * Add a subject to the database
     *
     * @param string $aoi_name //name of the subject
     * @param int $user_id //id from the users table *optional
     * @return bool
     */
    abstract public function addSubject(string $aoi_name, int $user_id): bool;

    /**
     * Update a subject in the database
     *
     * @param int $aoi_id //id from the areas of interest table
     * @param string $aoi_name //name of the subject
     * @param int $user_id //id from the users table *optional
     * @return bool
     */
    abstract public function updateSubject(int $aoi_id, string $aoi_name, int $user_id): bool;

    /**
     * Delete a subject from the database
     *
     * @param int $aoi_id //id from the areas of interest table
     * @return bool
     */
    abstract public function deleteSubject(int $aoi_id): bool;

    /**
     * Get subject name by id
     *
     * @param int $aoi_id //id from the areas of interest table
     * @return string
     */
    abstract public function getSubjectName(int $aoi_id): string;

    /**
     * Get the created date of a subject
     *
     * @param int $aoi_id //id from the areas of interest table
     * @return string
     */
    abstract public function getSubjectCreatedDate(int $aoi_id): string;

    /**
     * Get the last updated date of a subject
     *
     * @param int $aoi_id //id from the areas of interest table
     * @return string
     */
    abstract public function getSubjectLastUpdatedDate(int $aoi_id): string;

    /**
     * Get the created by user of a subject
     *
     * @param int $aoi_id //id from the areas of interest table
     * @return User //user object
     */
    abstract public function getSubjectCreatedBy(int $aoi_id): User;

    /**
     * Get the last updated by user of a subject
     *
     * @param int $aoi_id //id from the areas of interest table
     * @return User //user object
     */
    abstract public function getSubjectLastUpdatedBy(int $aoi_id): User;

    /**
     * Get the total number of subjects in the database
     *
     * @return int
     */
    abstract public function getSubjectsCount(): int;

    /**
     * Set the updated by user of a subject
     *
     * @param int $aoi_id //id from the areas of interest table
     * @param int $user_id //id from the users table
     * @return bool
     */
    abstract public function setSubjectLastUpdatedBy(int $aoi_id, int $user_id): bool;

    /**
     * Set the created by user of a subject
     *
     * @param int $aoi_id //id from the areas of interest table
     * @param int $user_id //id from the users table
     * @return bool
     */
    abstract public function setSubjectCreatedBy(int $aoi_id, int $user_id): bool;

    /**
     * Check if a subject exists in the database
     *
     * @param int $aoi_id //id from the areas of interest table
     * @return bool
     */
    abstract public function subjectExists(int $aoi_id): bool;
};
