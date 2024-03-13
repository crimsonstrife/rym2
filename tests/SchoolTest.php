<?php

use PHPUnit\Framework\TestCase;

require_once 'includes/boostrap.php';

/**
 * Test case for School class
 */
class SchoolTest extends TestCase
{

    /**
     * Test the createSchool method to add a school to the database
     */
    public function testCreateSchool()
    {
        $schoolClass = new School();
        $name = 'Test School';
        $address = '123 Main St';
        $city = 'Anytown';
        $state = 'NY';
        $zipcode = '12345';
        $createdBy = 1; //default created by to the first entry in the database
        $expectedResult = true;

        //create the school
        $result = $schoolClass->createSchool($name, $address, $city, $state, $zipcode, $createdBy);

        //check if the school was created
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test the getSchool methods to get a school from the database
     */
    public function testGetSchool()
    {
        $schoolClass = new School();
        $expectedResult = 'Test School';

        //get the school ID
        $result = $schoolClass->getSchoolIdByName('Test School');

        //check that the result is not empty, null or false
        $this->assertNotEmpty($result);
        $this->assertNotNull($result);

        //set the ID
        $id = $result;

        //get the school by ID
        $school = $schoolClass->getSchoolById($id);

        //get the school name from the array
        $result = $school['name'];

        //check if the school was retrieved
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test the updateSchool method to update a school in the database
     */
    public function testUpdateSchool()
    {
        $schoolClass = new School();
        //get the school ID for the test school
        $result = $schoolClass->getSchoolIdByName('Test School');

        //check that the result is not empty, null or false
        $this->assertNotEmpty($result);
        $this->assertNotNull($result);

        //set the ID
        $id = $result;

        //set the updated school data
        $name = 'Updated Test School';
        $address = '456 Main St';
        $city = 'Anytown';
        $state = 'NY';
        $zipcode = '57891';
        $updatedBy = 1; //default updated by to the first entry in the database
        $expectedResult = true;

        //update the school
        $result = $schoolClass->updateSchool($id, $name, $address, $city, $state, $zipcode, $updatedBy);

        //check if the school was updated
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test the deleteSchool method to delete a school from the database
     */
    public function testDeleteSchool()
    {
        $schoolClass = new School();
        //get the school ID for the updated test school
        $result = $schoolClass->getSchoolIdByName('Updated Test School');

        //check that the result is not empty, null or false
        $this->assertNotEmpty($result);
        $this->assertNotNull($result);

        //set the ID
        $id = $result;
        $expectedResult = true;

        //delete the school
        $result = $schoolClass->deleteSchool($id);

        //check if the school was deleted
        $this->assertEquals($expectedResult, $result);
    }

}
