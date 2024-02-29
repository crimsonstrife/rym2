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
 * @requires PHP 8.1.2+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/**
 * Subjects Class, contains all the functions for the interests table, which can be extended into both areas of interest, and job fields.
 */
abstract class Subject
{
    public int $subjectID; //id from the areas of interest table

    public string $subjectName; //name of the subject

    public string $subjectCreateDate; //created_at date

    public string $subjectUpdateDate; //updated_at date

    public int $subjectCreateUser; //id from the users table

    public int $subjectUpdateUser; //id from the users table

    /**
     * Subjects constructor.
     *
     */
    public function __construct(int $aoiID, string $aoiName, string $createdAt, string $updatedAt, int $createdBy, int $updatedBy)
    {
        $this->subjectID = $aoiID;
        $this->subjectName = $aoiName;
        $this->subjectCreateDate = $createdAt;
        $this->subjectUpdateDate = $updatedAt;
        $this->subjectCreateUser = $createdBy;
        $this->subjectUpdateUser = $updatedBy;
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
     * @param int $aoiID //id from the areas of interest table
     * @return array
     */
    abstract public function getSubject(int $aoiID): array;

    /**
     * Add a subject to the database
     *
     * @param string $aoiName //name of the subject
     * @param int $userID //id from the users table *optional
     * @return bool
     */
    abstract public function addSubject(string $aoiName, int $userID): bool;

    /**
     * Update a subject in the database
     *
     * @param int $aoiID //id from the areas of interest table
     * @param string $aoiName //name of the subject
     * @param int $userID //id from the users table *optional
     * @return bool
     */
    abstract public function updateSubject(int $aoiID, string $aoiName, int $userID): bool;

    /**
     * Delete a subject from the database
     *
     * @param int $aoiID //id from the areas of interest table
     * @return bool
     */
    abstract public function deleteSubject(int $aoiID): bool;

    /**
     * Get subject name by id
     *
     * @param int $aoiID //id from the areas of interest table
     * @return string
     */
    abstract public function getSubjectName(int $aoiID): string;

    /**
     * Get the created date of a subject
     *
     * @param int $aoiID //id from the areas of interest table
     * @return string
     */
    abstract public function getSubjectCreatedDate(int $aoiID): string;

    /**
     * Get the last updated date of a subject
     *
     * @param int $aoiID //id from the areas of interest table
     * @return string
     */
    abstract public function getSubjectLastUpdatedDate(int $aoiID): string;

    /**
     * Get the created by user of a subject
     *
     * @param int $aoiID //id from the areas of interest table
     * @return User //user object
     */
    abstract public function getSubjectCreatedBy(int $aoiID): User;

    /**
     * Get the last updated by user of a subject
     *
     * @param int $aoiID //id from the areas of interest table
     * @return User //user object
     */
    abstract public function getSubjectLastUpdatedBy(int $aoiID): User;

    /**
     * Get the total number of subjects in the database
     *
     * @return int
     */
    abstract public function getSubjectsCount(): int;

    /**
     * Set the updated by user of a subject
     *
     * @param int $aoiID //id from the areas of interest table
     * @param int $userID //id from the users table
     * @return bool
     */
    abstract public function setSubjectLastUpdatedBy(int $aoiID, int $userID): bool;

    /**
     * Set the created by user of a subject
     *
     * @param int $aoiID //id from the areas of interest table
     * @param int $userID //id from the users table
     * @return bool
     */
    abstract public function setSubjectCreatedBy(int $aoiID, int $userID): bool;

    /**
     * Check if a subject exists in the database
     *
     * @param int $aoiID //id from the areas of interest table
     * @return bool
     */
    abstract public function subjectExists(int $aoiID): bool;
};
