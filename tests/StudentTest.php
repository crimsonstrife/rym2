<?php

use PHPUnit\Framework\TestCase;

require_once 'includes/boostrap.php';

/**
 * Test case for Student class
 */
class StudentTest extends TestCase
{
    public function testAddStudent()
    {
        $studentClass = new Student();
        $studentDataClass = new StudentData();
        $studentAddressClass = new StudentAddress();
        $studentEducationClass = new StudentEducation();
        $firstName = 'John';
        $lastName = 'Doe';
        $email = 'jdoe@test.email';
        $phone = '123-456-7890';
        $position = 'FULL';
        $interest = 1; //default interest to the first entry in the database
        $createdAt = date('Y-m-d H:i:s');
        $updatedAt = date('Y-m-d H:i:s');
        $studentAddressClass->address = '123 Main St';
        $studentAddressClass->city = 'Anytown';
        $studentAddressClass->state = 'NY';
        $studentAddressClass->zipcode = '12345';
        $studentEducationClass->school = 1; //default school to the first entry in the database
        $studentEducationClass->degree = 1; //default degree to the first entry in the database
        $studentEducationClass->major = 1; //default major to the first entry in the database
        //set the graduation date to a date in the future by 30 days
        $studentEducationClass->graduation = date('Y-m-d', strtotime('+30 days')); //default graduation to 30 days from now
        $expectedResult = true;

        //set the student data
        $studentDataClass->firstName = $firstName;
        $studentDataClass->lastName = $lastName;
        $studentDataClass->email = $email;
        $studentDataClass->phone = $phone;
        $studentDataClass->studentAddress = $studentAddressClass;
        $studentDataClass->studentEducation = $studentEducationClass;
        $studentDataClass->position = $position;
        $studentDataClass->interest = $interest;
        $studentDataClass->createdAt = $createdAt;
        $studentDataClass->updatedAt = $updatedAt;

        //add the student
        $result = $studentClass->addStudent($studentDataClass);

        //check if the student was added
        $this->assertEquals($expectedResult, $result);
    }
}
