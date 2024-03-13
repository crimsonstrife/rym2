<?php

use PHPUnit\Framework\TestCase;

require_once 'includes/boostrap.php';

/**
 * Test case for Event class
 */
class EventTest extends TestCase
{
    /**
     * Test the createEvent method to add an event to the database
     */
    public function testCreateEvent()
    {
        $eventClass = new Event();
        $eventName = 'Test Event';
        $eventDate = date('Y-m-d', strtotime('+30 days')); //default event date to 30 days from now
        $location = 1; //default location to the first school entry in the database
        $createdBy = 1; //default created by to the first user entry in the database
        $expectedResult = true;

        //add the event
        $result = $eventClass->createEvent($eventName, $eventDate, $location, $createdBy);

        //check if the event was added
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test the getEvent methods to get an event from the database
     */
    public function testGetEvent()
    {
        $eventClass = new Event;
        $eventName = 'Test Event';

        //get the event ID
        $eventID = $eventClass->getEventIdByName($eventName);

        //check if the event ID was found
        $this->assertNotFalse($eventID);
        $this->assertNotNull($eventID);

        //get the event
        $event = $eventClass->getEventById($eventID);

        //check if the event was found
        $this->assertNotFalse($event);
        $this->assertNotNull($event);
        $this->assertNotEmpty($event);

        //check if the event name is correct
        $this->assertEquals($eventName, $event['name']);
    }

    /**
     * Test the updateEvent method to update an event in the database
     */
    public function testUpdateEvent()
    {
        $eventClass = new Event();
        $eventName = 'Test Event';

        //get the event ID
        $eventID = $eventClass->getEventIdByName($eventName);

        //check if the event ID was found
        $this->assertNotFalse($eventID);
        $this->assertNotNull($eventID);

        //prepare the updated event data
        $updatedEventName = 'Updated Test Event';
        $updatedEventDate = date('Y-m-d', strtotime('+30 days')); //default event date to 30 days from now
        $updatedLocation = 1; //default location to the first school entry in the database
        $updatedUpdatedBy = 1; //default updated by to the first user entry in the database
        $expectedResult = true;

        //update the event
        $result = $eventClass->updateEvent($eventID, $updatedEventName, $updatedEventDate, $updatedLocation, $updatedUpdatedBy);

        //check if the event was updated
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test the deleteEvent method to delete an event from the database
     */
    public function testDeleteEvent()
    {
        $eventClass = new Event();
        $eventName = 'Updated Test Event';
        $expectedResult = true;

        //get the event ID
        $eventID = $eventClass->getEventIdByName($eventName);

        //check if the event ID was found
        $this->assertNotFalse($eventID);
        $this->assertNotNull($eventID);

        //delete the event
        $result = $eventClass->deleteEvent($eventID);

        //check if the event was deleted
        $this->assertEquals($expectedResult, $result);
    }
}
