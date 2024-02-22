<?php

/**
 * Events Class file for the College Recruitment Application
 * Contains all the functions for the Event Class and handles all the Event related tasks with the database.
 *
 * Author: Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date: 10/16/2023
 *
 * @package RYM2
 * Filename: events.inc.php
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

/**
 * Summary of Event Class
 *
 * This class handles all the Event related tasks with the database. Events are entered by company admins ahead of college visits.
 * Each represents a trip to a college or job fair to recruit students.
 */
class Event
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
     * Get all Events from the database
     *
     * @return array
     */
    public function getEvents(): array
    {
        //SQL statement to get all events
        $sql = "SELECT * FROM event";

        //Prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the result
        $result = $stmt->get_result();

        //Array to hold the events
        $events = array();

        //If the query returns a result
        if ($result) {
            //Loop through the result and add each row to the events array
            while ($row = $result->fetch_assoc()) {
                $events[] = $row;
            }
        }

        //Return the events array
        return $events;
    }

    /**
     * Get a single Event from the database by ID
     *
     * @param int $eventID The ID of the Event to get
     * @return array
     */
    public function getEventById(int $eventID): array
    {
        //SQL statement to get a single event by ID
        $sql = "SELECT * FROM event WHERE id = $eventID";

        //Prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the result
        $result = $stmt->get_result();

        //Array to hold the event
        $event = array();

        //If the query returns a result
        if ($result) {
            //Loop through the result and add each row to the events array
            while ($row = $result->fetch_assoc()) {
                $event = $row;
            }
        }

        //Return the event
        return $event;
    }

    /**
     * Get event name
     *
     * @param int $eventID event id
     * @return string
     */
    public function getEventName(int $eventID): string
    {
        //SQL statement to get a single event by ID
        $sql = "SELECT name FROM event WHERE id = $eventID";

        //Prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the result
        $result = $stmt->get_result();

        //Create a placeholder for the event name
        $eventName = "";

        //If the query returns a result
        if ($result) {
            //set the event name
            $eventName = $result->fetch_assoc()['name'];
        }

        //Return the event name
        return $eventName;
    }

    /**
     * Get event location
     *
     * @param int $eventID event id
     * @return ?int //school id
     */
    public function getEventLocationId(int $eventID): ?int
    {
        //SQL statement to get a single event by ID
        $sql = "SELECT location FROM event WHERE id = $eventID";

        //Prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the result
        $result = $stmt->get_result();

        //placeholder for the location id
        $locationId = null;

        //If the query returns a result
        if ($result) {
            //set the event id
            $locationId = intval($result->fetch_assoc()['location']);
        }

        //Return the location id
        return $locationId;
    }

    /**
     * Get event location
     *
     * @param int $eventID event id
     * @return string school name
     */
    public function getEventLocation(int $eventID): string
    {
        //instance of school class
        $school = new School();

        //get the event location id
        $locationId = $this->getEventLocationId($eventID);

        //get the school name using the location id as the school id
        $location = $school->getSchoolName($locationId);

        //return the school name
        return $location;
    }

    /**
     * Get events by location
     * returns an array of events at a specific location
     *
     * @param int $locationId school id
     * @return array
     */
    public function getEventsByLocation(int $locationId): array
    {
        //SQL statement to get all events by location
        $sql = "SELECT * FROM event WHERE location = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $locationId);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //array to hold the events
        $events = array();

        //if the result has rows
        if (
            $result->num_rows > 0
        ) {
            //loop through the rows
            while ($row = $result->fetch_assoc()) {
                //add the row to the students array
                $events[] = $row;
            }
        }

        //return the events array
        return $events;
    }

    /**
     * Get the event creation date
     *
     * @param int $eventID event id
     * @return string
     */
    public function getEventCreationDate(int $eventID): string
    {
        //SQL statement to get a single event by ID
        $sql = "SELECT created_at FROM event WHERE id = $eventID";

        //Prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters
        $stmt->bind_param("i", $eventID);

        //Execute the statement
        $stmt->execute();

        //Get the result
        $result = $stmt->get_result();

        //placeholder for the created at date
        $createdAt = "";

        //If the query returns a result
        if ($result) {
            //set the created at date
            $createdAt = $result->fetch_assoc()['created_at'];
        }

        //Return the created at date
        return $createdAt;
    }

    /**
     * Get the event last updated date
     *
     * @param int $eventID event id
     * @return string
     */
    public function getEventLastUpdatedDate(int $eventID): string
    {
        //SQL statement to get a single event by ID
        $sql = "SELECT updated_at FROM event WHERE id = $eventID";

        //Prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the result
        $result = $stmt->get_result();

        //placeholder for the updated at date
        $updatedAt = "";

        //If the query returns a result
        if ($result) {
            //Return the event
            $updatedAt = $result->fetch_assoc()['updated_at'];
        }

        //Return the updated at date
        return $updatedAt;
    }

    /**
     * Get the event created by user
     *
     * @param int $eventID event id
     * @return User
     */
    public function getEventCreatedBy(int $eventID): User
    {
        //SQL statement to get a single event by ID
        $sql = "SELECT created_by FROM event WHERE id = $eventID";

        //Prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters
        $stmt->bind_param("i", $eventID);

        //Execute the statement
        $stmt->execute();

        //Get the result
        $result = $stmt->get_result();

        //Placeholder for the created by user id
        $createdBy = null;

        //If the query returns a result
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $createdBy = $row['created_by'];
            }
        }

        //placeholder for the user
        $user = new User();

        //if the created by user id is not 0 or null, get the user by id
        if ($createdBy > 0 && $createdBy != null) {
            //instantiate the user class
            $userObject = new User();

            //get the matching users from the user class
            $userArray = $userObject->getUserById($createdBy);

            //should only be one matching user, so set the first one
            $user = $userArray[0];
        }

        //return the user
        return $user;
    }

    /**
     * Get the event last updated by user
     *
     * @param int $eventID event id
     * @return User
     */
    public function getEventLastUpdatedBy(int $eventID): User
    {
        //SQL statement to get a single event by ID
        $sql = "SELECT updated_by FROM event WHERE id = $eventID";

        //Prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the result
        $result = $stmt->get_result();

        //Placeholder for the created by user id
        $updatedBy = null;

        //If the query returns a result
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $updatedBy = $row['created_by'];
            }
        }

        //placeholder for the user
        $user = new User();

        //if the updated by user id is not 0, get the user by id
        if ($updatedBy > 0 && $updatedBy != null) {
            //instantiate the user class
            $userObject = new User();

            //get the matching users from the user class
            $userArray = $userObject->getUserById($updatedBy);

            //should only be one matching user, so set the first one
            $user = $userArray[0];
        }

        //return the user
        return $user;
    }

    /**
     * Slugify the event name and add the slug to the database
     *
     * @param int $eventID event id
     * @return bool
     */
    private function slugifyEvent(int $eventID): bool
    {
        //instance of activity class
        $activity = new Activity();

        //instance of session class
        $session = new Session();

        //get the current user id
        $userID = intval($session->get('user_id')) ?? null;

        //get the event name
        $name = $this->getEventName($eventID);

        //slugify the name
        $slug = toSlug($name);

        //check if the slug already exists using event id and slug
        $slugExists = $this->checkEventSlug($eventID, $slug);

        //if the slug already exists, update the slug
        if ($slugExists) {
            //log the activity
            $activity->logActivity($userID, "Event Sluggified", "Event Slug for Event:" . $this->getEventName($eventID) . " already exists, incrementing slug");
            return $this->updateEventSlug($eventID, $slug);
        }

        //log the activity
        $activity->logActivity($userID, "Event Sluggified", "Event Slug for Event:" . $this->getEventName($eventID) . " does not exist, creating slug");
        //if the slug does not exist, create the slug
        return $this->createEventSlug($eventID);
    }

    /**
     * Check if the event slug already exists
     *
     * @param int $eventID event id
     * @param string $slug event slug
     * @return bool
     */
    private function checkEventSlug(int $eventID, string $slug): bool
    {
        //SQL statement to check if the event slug already exists
        $sql = "SELECT * FROM event_slugs WHERE event_id = ? AND slug = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("is", $eventID, $slug);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //if the result has rows, return true
        if ($result->num_rows > 0) {
            return true;
        }

        //if no results, return false
        return false;
    }

    /**
     * Check if the event slug already exists from any event id
     * This is used when updating the event slug
     *
     * @param string $slug event slug
     * @return bool
     */
    private function checkEventSlugExists(string $slug): bool
    {
        //SQL statement to check if the event slug already exists
        $sql = "SELECT * FROM event_slugs WHERE slug = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("s", $slug);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //if the result has rows, return true
        if ($result->num_rows > 0) {
            return true;
        }

        //if no results, return false
        return false;
    }

    /**
     * Get the event id by slug
     *
     * @param string $slug event slug
     * @return ?int
     */
    private function getEventIdBySlug(string $slug): ?int
    {
        //SQL statement to get the event id by slug
        $sql = "SELECT event_id FROM event_slugs WHERE slug = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameter
        $stmt->bind_param("s", $slug);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //placeholder for the event id
        $eventID = null;

        //if the result has rows, return the id
        if ($result->num_rows > 0) {
            $eventID = intval($result->fetch_assoc()['event_id']);
        }

        //return the event id
        return $eventID;
    }

    /**
     * Update the event slug
     *
     * @param int $eventID event id
     * @param string $slug event slug
     * @return bool
     */
    private function updateEventSlug(int $eventID, string $slug): bool
    {
        //instance of activity class
        $activity = new Activity();

        //instance of session class
        $session = new Session();

        //get the current user id
        $userID = intval($session->get('user_id')) ?? null;

        //check if the slug already exists using on any event id
        $slugExists = $this->checkEventSlugExists($slug);

        //placeholder for the result
        $isUpdated = false;

        //if the slug already exists, check if the event id matches the current event id
        if ($slugExists) {
            //get the event id by slug
            $slugEventID = $this->getEventIdBySlug($slug);
            //if the event id matches the current event id, update the slug
            if ($slugEventID == $eventID) {
                //SQL statement to update the event slug
                $sql = "UPDATE event_slugs SET slug = ? WHERE event_id = ?";
                //prepare the statement
                $stmt = prepareStatement($this->mysqli, $sql);
                //bind the parameters
                $stmt->bind_param("si", $slug, $eventID);
                //execute the statement
                $stmt->execute();

                //check the result
                if ($stmt->affected_rows > 0) {
                    $isUpdated = true;
                }

                //if the statement was successful, return true
                if ($isUpdated) {
                    //log the activity
                    $activity->logActivity($userID, "Event Slug Updated", "Event ID: " . $eventID . " Event Name: " . $this->getEventName($eventID) . " was sluggified with Slug: " . $slug . "");
                    return true;
                }
            }
            //if the event id does not match the current event id, it is a duplicate slug, so increment the slug and try again
            $slug = $this->incrementSlug($slug);

            //update the slug
            return $this->updateEventSlug($eventID, $slug);
        }

        //if the slug does not exist, update the slug
        //SQL statement to update the event slug
        $sql = "UPDATE event_slugs SET slug = ? WHERE event_id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("si", $slug, $eventID);

        //execute the statement
        $stmt->execute();

        //if the statement was successful, return true
        if ($stmt) {
            //log the activity
            $activity->logActivity($userID, "Event Slug Updated", "Event ID: " . $eventID . " Event Name: " . $this->getEventName($eventID) . " was sluggified with Slug: " . $slug . "");
            return true;
        }

        //if the statement failed, return false
        return false;
    }

    /**
     * Increment the slug in case of duplicate slugs
     * adds or increments a number at the end of the slug
     * @param string $slug event slug
     * @return string
     */
    private function incrementSlug(string $slug): string
    {
        //get the last character of the slug
        $lastChar = substr($slug, -1);


        //if the last character is a number, increment it
        if (is_numeric($lastChar)) {
            $lastChar++; //increment the last character
        }

        //if the last character is not a number, add a hyphen and a 1
        $slug .= "-1";

        //check if the updated slug exists
        $slugExists = $this->checkEventSlugExists($slug);

        //if the slug already exists, increment the slug again
        while ($slugExists) {
            //get the last character of the slug
            $lastChar = substr($slug, -1);

            //if the last character is a number, increment it
            if (is_numeric($lastChar)) {
                $lastChar++;
            }

            //if the last character is not a number, add a hyphen and a 1
            $slug .= "-1";

            //check if the slug already exists
            $slugExists = $this->checkEventSlugExists($slug);

            //check if the slug is under 30 characters
            if (strlen($slug) > 30) {
                //if the slug is over 30 characters, truncate it to 20 characters
                $slug = substr($slug, 0, 20);
            }

            //check if the slug already exists
            $slugExists = $this->checkEventSlugExists($slug);
        } //end while

        //return the slug
        return $slug;
    }

    /**
     * Get the event slug
     *
     * @param int $eventID event id
     * @return string
     */
    public function getEventSlug(int $eventID): string
    {
        //SQL statement to get the event slug
        $sql = "SELECT slug FROM event_slugs WHERE event_id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $eventID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //placeholder for the slug
        $slug = "";

        //if the result has rows, return the slug
        if ($result->num_rows > 0) {
            $slug = $result->fetch_assoc()['slug'];
        }

        //return the slug
        return $slug;
    }

    /**
     * Create the event slug
     *
     * @param int $eventID event id
     * @return bool
     */
    private function createEventSlug(int $eventID): bool
    {
        //instance of activity class
        $activity = new Activity();

        //instance of session class
        $session = new Session();

        //get the current user id
        $userID = intval($session->get('user_id')) ?? null;

        //SQL statement to create the event slug
        $sql = "INSERT INTO event_slugs (event_id, slug) VALUES (?, ?)";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //get the event name
        $name = $this->getEventName($eventID);

        //slugify the name
        $slug = toSlug($name);

        //check if the slug already exists on any event id
        $slugExists = $this->checkEventSlugExists($slug);

        //if the slug already exists, increment the slug
        if ($slugExists) {
            $slug = $this->incrementSlug($slug);

            //bind the parameters
            $stmt->bind_param("is", $eventID, $slug);

            //execute the statement
            $stmt->execute();

            //if the statement was successful, return true
            if ($stmt) {
                //log the activity
                $activity->logActivity($userID, "Event Slug Created", "Event ID: " . $eventID . " Event Name: " . $this->getEventName($eventID) . " was sluggified with Slug: " . $slug . "");
                return true;
            }
        }
        //if the slug does not exist, create the slug
        //bind the parameters
        $stmt->bind_param("is", $eventID, $slug);

        //execute the statement
        $stmt->execute();

        //if the statement was successful, return true
        if ($stmt) {
            //log the activity
            $activity->logActivity($userID, "Event Slug Created", "Event ID: " . $eventID . " Event Name: " . $this->getEventName($eventID) . " was sluggified with Slug: " . $slug . "");
            return true;
        }

        //if the statement failed, return false
        return false;
    }

    /**
     * Get event by slug
     *
     * @param string $slug event slug
     * @return array event object
     */
    public function getEventBySlug(string $slug): array
    {
        //SQL statement to get the event by slug
        $sql = "SELECT * FROM event_slugs WHERE slug = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("s", $slug);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //placeholder for the event
        $event = array();

        //if the result has rows, return the event
        if ($result->num_rows > 0) {
            //get the event id
            $eventID = $result->fetch_assoc()['event_id'];

            //get the event
            $event = $this->getEventById($eventID);
        }

        //return the event
        return $event;
    }

    /**
     * Update event
     *
     * @param int $eventID event id
     * @param string $name event name
     * @param string $date event date
     * @param int $location event location id
     * @param int $updatedBy user id of the user updating the event
     * @return bool
     */
    public function updateEvent(int $eventID, string $name, string $date, int $location, int $updatedBy): bool
    {
        //instance of activity class
        $activity = new Activity();

        //instance of session class
        $session = new Session();

        //get the current user id
        $userID = intval($session->get('user_id')) ?? null;

        //SQL statement to update the event
        $sql = "UPDATE event SET name = ?, event_date = ?, location = ?, updated_by = ? WHERE id = ?";
        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);
        //bind the parameters
        $stmt->bind_param("ssiii", $name, $date, $location, $updatedBy, $eventID);
        //execute the statement
        $stmt->execute();
        //if the statement was successful, return true
        if ($stmt) {
            //slugify the event
            $sluggified = $this->slugifyEvent($eventID);
            if ($sluggified) {
                //get the event name
                $eventName = $this->getEventName($eventID);

                //log the activity
                $activity->logActivity($userID, "Event Updated", 'Event ID: ' . $eventID . ' Event Name: ' . $eventName);
                return true;
            }
        }

        //if the statement failed, return false
        return false;
    }

    /**
     * Create event
     *
     * @param string $name event name
     * @param string $date event date
     * @param int $location event location id
     * @param int $createdBy user id of the user creating the event
     *
     * @return bool
     */
    public function createEvent(string $name, string $date, int $location, int $createdBy): bool
    {
        //instance of activity class
        $activity = new Activity();

        //instance of session class
        $session = new Session();

        //get the current user id
        $userID = intval($session->get('user_id')) ?? null;

        //get the current date and time
        $createdAt = date("Y-m-d H:i:s");

        //SQL statement to create the event
        $sql = "INSERT INTO event (name, event_date, location, created_at, created_by, updated_at, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?)";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("ssisiss", $name, $date, $location, $createdAt, $createdBy, $createdAt, $createdBy);

        //execute the statement
        $stmt->execute();

        //if the statement was successful, return true
        if ($stmt) {
            //slugify the event
            $sluggified = $this->slugifyEvent($this->getEventIdByName($name));
            if ($sluggified) {
                //get the event id
                $eventID = $this->getEventIdByName($name);
                //get the event name
                $eventName = $this->getEventName($eventID);
                //log the activity
                $activity->logActivity($userID, "Event Created", 'Event ID: ' . $eventID . ' Event Name: ' . $eventName);
                return true;
            }
        }

        //if the statement failed, return false
        return false;
    }

    /**
     * Get event ID by name
     *
     * @param string $name event name
     * @return ?int
     */
    public function getEventIdByName(string $name): ?int
    {
        //SQL statement to get the event id by name
        $sql = "SELECT id FROM event WHERE name = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameter
        $stmt->bind_param("s", $name);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //placeholder for the event id
        $eventID = null;

        //if the result has rows, return the id
        if ($result->num_rows > 0) {
            $eventID = intval($result->fetch_assoc()['id']);
        }

        //return the event id
        return $eventID;
    }

    /**
     * Get the number of students attending an event
     * @param int $eventID event id
     * @return int count
     */
    public function getStudentCount(int $eventID): int
    {
        //SQL statement to get the number of students attending an event
        $sql = "SELECT COUNT(*) FROM student_at_event WHERE event_id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameter
        $stmt->bind_param("i", $eventID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //placeholder for the count
        $count = 0;

        //if the result has rows, return the count
        if ($result->num_rows > 0) {
            $count = intval($result->fetch_assoc()['COUNT(*)']);
        }

        //return the count
        return $count;
    }

    /**
     * Get the number of events held at a school
     *
     * @param int $eventID school id
     * @return int count
     */
    public function getHeldEvents(int $eventID): int
    {
        //SQL statement to get the number of events held at a school
        $sql = "SELECT COUNT(*) FROM event WHERE location = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameter
        $stmt->bind_param("i", $eventID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //placeholder for the count
        $count = 0;

        //if the result has rows, return the count
        if ($result->num_rows > 0) {
            $count = intval($result->fetch_assoc()['COUNT(*)']);
        }

        //return the count
        return $count;
    }

    /**
     * Delete event
     * Delete an event from the database
     *
     * @param int $eventID
     * @return boolean $result
     */
    public function deleteEvent(int $eventID): bool
    {
        //get the event name
        $eventName = $this->getEventName($eventID);

        //set the placeholder for the result
        $result = false;

        //create the sql statement
        $sql = "DELETE FROM event WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $eventID);

        //execute the statement
        $stmt->execute();

        //check the result
        if ($stmt->affected_rows > 0) {
            $result = true;
        }

        //log the event activity if the event was deleted
        if ($result) {
            $activity = new Activity();
            $session = new Session();
            //get the current user id
            $userID = intval($session->get('user_id')) ?? null;
            //log the activity
            $activity->logActivity($userID, 'Deleted Event', 'Event ID: ' . $eventID . ' Event Name: ' . $eventName);
        }

        //return the result
        return $result;
    }
}
