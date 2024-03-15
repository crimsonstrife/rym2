<?php

use PHPUnit\Framework\TestCase;

require_once 'includes/boostrap.php';

/**
 * Test case for Student class
 */
class StudentTest extends TestCase
{
    /**
     * Properties
     */
    protected $studentClass;
    protected $studentDataClass;
    protected $studentAddressClass;
    protected $studentEducationClass;
    //variable to hold the test student ID
    public $testStudentId;
    //variable for today's date
    protected $today;
    //values for the test student
    protected $firstName;
    protected $lastName;
    protected $email;
    protected $phone;
    protected $position;
    protected $address;
    protected $city;
    protected $state;
    protected $zipcode;
    protected $school;
    protected $degree;
    protected $major;
    protected $graduation;
    protected $interest;

    /**
     * Set up the test case
     */
    public function setUp(): void
    {
        $this->studentClass = new Student();
        $this->studentDataClass = new StudentData();
        $this->studentAddressClass = new StudentAddress();
        $this->studentEducationClass = new StudentEducation();
        $this->today = date('Y-m-d H:i:s');
        $this->firstName = 'John';
        $this->lastName = 'Doe';
        $this->email = 'jdoe@test.email';
        $this->phone = '123-456-7890';
        $this->address = '123 Main St';
        $this->city = 'Anytown';
        $this->state = 'NY';
        $this->zipcode = '12345';
        $this->school = 1; //default school to the first entry in the database
        $this->degree = 1; //default degree to the first entry in the database
        $this->major = 1; //default major to the first entry in the database
        $this->interest = 1; //default interest to the first entry in the database
        $this->graduation = date('Y-m-d', strtotime('+30 days')); //default graduation to 30 days from now
        $this->position = 'FULL';

        //set the test student ID to null
        $this->testStudentId = null;

        //check if the test student already exists in the database
        $result = $this->studentClass->getStudentByEmail($this->email);

        //if the test student exists, assign the student ID to the test student ID
        //check if the result is not empty, null or false
        if (!empty($result) && $result != null && $result != false) {
            $this->testStudentId = intval($result['id']);
        }
    }

    /**
     * Test the addStudent method to add a student to the database
     * and get the student ID for the test student
     *
     * @covers Student::addStudent
     * @covers Student::getStudentByEmail
     */
    public function test1_AddStudent()
    {
        //set the Address and Education data
        $this->studentAddressClass->address = $this->address;
        $this->studentAddressClass->city = $this->city;
        $this->studentAddressClass->state = $this->state;
        $this->studentAddressClass->zipcode = $this->zipcode;
        $this->studentEducationClass->school = $this->school;
        $this->studentEducationClass->degree = $this->degree;
        $this->studentEducationClass->major = $this->major;
        $this->studentEducationClass->graduation = $this->graduation;

        //set the expected result
        $expectedResult = true;

        //set the student data
        $this->studentDataClass->firstName = $this->firstName;
        $this->studentDataClass->lastName = $this->lastName;
        $this->studentDataClass->email = $this->email;
        $this->studentDataClass->phone = $this->phone;
        $this->studentDataClass->studentAddress = $this->studentAddressClass;
        $this->studentDataClass->studentEducation = $this->studentEducationClass;
        $this->studentDataClass->position = $this->position;
        $this->studentDataClass->interest = $this->interest;
        $this->studentDataClass->createdAt = $this->today;
        $this->studentDataClass->updatedAt = $this->today;

        //add the student
        $result = $this->studentClass->addStudent($this->studentDataClass);

        //check if the student was added
        $this->assertEquals($expectedResult, $result);

        //get the student ID for the test student
        $result = $this->studentClass->getStudentByEmail($this->email);

        //check that the result is not empty, null or false
        $this->assertNotEmpty($result);
        $this->assertNotNull($result);
        $this->assertNotFalse($result);

        //get the student id from the array
        $this->testStudentId = intval($result['id']);

        //check if the student ID is an integer
        $this->assertIsInt($this->testStudentId);

        //check if the student ID is greater than 0
        $this->assertGreaterThan(0, $this->testStudentId);

        //set the test student ID
        $this->setTestStudentId($this->testStudentId);
    }

    /**
     * Set the test student ID after the test student is added to the database
     */
    public function setTestStudentId($testStudentId)
    {
        $this->testStudentId = $testStudentId;
    }

    /**
     * Test the getStudent methods to get a student from the database
     * and check if the student information matches the expected values
     *
     * @covers Student::getStudentById
     */
    public function test2_GetStudent()
    {
        //get the student ID
        $id = $this->testStudentId;

        //assert that the student ID is an integer
        $this->assertIsInt($id);

        //assert that the student ID is greater than 0
        $this->assertGreaterThan(0, $id);

        //get the student by ID
        $student = $this->studentClass->getStudentById($id);

        //check that the student is not empty, null or false
        $this->assertNotEmpty($student);
        $this->assertNotNull($student);

        //get some of the student information from the array
        $resultFirstName = $student['first_name'];
        $resultLastName = $student['last_name'];
        $resultEmail = $student['email'];
        $resultPhone = $student['phone'];
        $resultAddress = $student['address'];
        $resultCity = $student['city'];
        $resultState = $student['state'];
        $resultZipcode = $student['zipcode'];
        $resultSchool = intval($student['school']);

        //check if the student information matches the expected values
        $this->assertEquals($this->firstName, $resultFirstName);
        $this->assertEquals($this->lastName, $resultLastName);
        $this->assertEquals($this->email, $resultEmail);
        $this->assertEquals($this->phone, $resultPhone);
        $this->assertEquals($this->address, $resultAddress);
        $this->assertEquals($this->city, $resultCity);
        $this->assertEquals($this->state, $resultState);
        $this->assertEquals($this->zipcode, $resultZipcode);
        $this->assertEquals($this->school, $resultSchool);
    }

    /**
     * Test the updateStudent method to update a student in the database
     * and check if the student was updated
     *
     * @covers Student::updateStudent
     */
    public function test3_UpdateStudent()
    {
        //get the student ID for the test student
        $id = $this->testStudentId;

        //check if the student ID is an integer
        $this->assertIsInt($id);

        //check if the student ID is greater than 0
        $this->assertGreaterThan(0, $id);

        //set the updated student data
        $firstName = 'Jane';
        $phone = '987-654-3210';
        $this->studentAddressClass->address = '456 Main St';
        $this->studentAddressClass->city = 'Othertown';
        $this->studentAddressClass->state = 'CA';
        $this->studentAddressClass->zipcode = '67890';
        $this->studentEducationClass->school = $this->school; //default school to the first entry in the database
        $this->studentEducationClass->degree = $this->degree; //default degree to the first entry in the database
        $this->studentEducationClass->major = $this->major; //default major to the first entry in the database
        //set the graduation date to a date in the future by 60 days
        $this->studentEducationClass->graduation = date('Y-m-d', strtotime('+60 days')); //default graduation to 60 days from now
        $expectedResult = true;

        //set the student data
        $this->studentDataClass->firstName = $firstName;
        $this->studentDataClass->lastName = $this->lastName;  //last name is not being updated
        $this->studentDataClass->phone = $phone;
        $this->studentDataClass->email = $this->email;  //email is not being updated
        $this->studentDataClass->studentAddress = $this->studentAddressClass;
        $this->studentDataClass->studentEducation = $this->studentEducationClass;
        $this->studentDataClass->position = $this->position;
        $this->studentDataClass->interest = $this->interest;
        $this->studentDataClass->updatedAt = date('Y-m-d H:i:s'); //update the updated at date/time

        //update the student
        $result = $this->studentClass->updateStudent($id, $this->studentDataClass);

        //check if the student was updated
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test the deleteStudent method to delete a student from the database
     *
     * @covers Student::deleteStudent
     */
    public function test4_DeleteStudent()
    {
        $expectedResult = true;

        //get the student ID for the test student
        $id = $this->testStudentId;

        //check if the student ID is an integer
        $this->assertIsInt($id);

        //check if the student ID is greater than 0
        $this->assertGreaterThan(0, $id);

        //delete the student
        $result = $this->studentClass->deleteStudent($id);

        //check if the student was deleted
        $this->assertEquals($expectedResult, $result);
    }
}
