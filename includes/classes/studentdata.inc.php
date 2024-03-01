<?php
/**
 * Students Data Class file for the College Recruitment Application
 * Contains all the functions for the Student Data Class and handles all the student Data related tasks with the database.
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/16/2023
 *
 * @package RYM2
 * Filename: studentdata.inc.php
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
 * Class StudentData
 * This class is used to store student data
 */
class StudentData extends Student
{
    public ?int $studentID = null;
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?string $email = null;
    public ?string $phone = null;
    public ?StudentAddress $studentAddress = null;
    public ?StudentEducation $studentEducation = null;
    public ?string $position = null;
    public ?int $interest = null;
    public ?string $createdAt = null;
    public ?string $updatedAt = null;

    /**
     * Gets all non-null properties of the StudentData class as an array
     * @return array
     */
    public function getStudentDataArray(): array
    {
        //create an array to hold the student data
        $studentDataArray = array();

        //loop through the properties of the class
        foreach ($this as $key => $value) {
            //if the value is not null, add it to the student data array
            if ($value !== null) {
                $studentDataArray[$key] = $value;
            }
        }

        //return the student data array
        return $studentDataArray;
    }

    /**
     * Get the escaped string of a student data property
     * @param string $property
     * @return string
     */
    public function getEscapedString(string $property): string
    {
        //get the value of the property
        $value = $this->$property;

        //escape the string
        $escapedString = $this->mysqli->real_escape_string($value);

        //return the escaped string
        return $escapedString;
    }
}
