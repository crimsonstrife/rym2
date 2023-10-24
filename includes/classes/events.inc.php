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
    private $mysqli;

    //Instantiate the database connection
    public function __construct()
    {
        $this->mysqli = connectToDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
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
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Loop through the result and add each row to the events array
            while ($row = $result->fetch_assoc()) {
                $events[] = $row;
            }
            //Return the events array
            return $events;
        } else {
            //If the query fails, return an empty array
            return array();
        }
    }

    /**
     * Get a single Event from the database by ID
     *
     * @param int $id The ID of the Event to get
     * @return array
     */
    public function getEventById(int $id): array
    {
        //SQL statement to get a single event by ID
        $sql = "SELECT * FROM event WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Return the event
            return $result->fetch_assoc();
        } else {
            //If the query fails, return an empty array
            return array();
        }
    }

    /**
     * Find Events in the database by search string
     *
     * @param string $search The string to search for
     * @return array
     */
    public function findEvent(string $search): array
    {
        //TODO: Implement findEvent() method.
        return array();
    }

    /**
     * Get event name
     *
     * @param int $id event id
     * @return string
     */
    public function getEventName(int $id): string
    {
        //SQL statement to get a single event by ID
        $sql = "SELECT name FROM event WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Return the event
            return $result->fetch_assoc()['name'];
        } else {
            //If the query fails, return an empty array
            return "";
        }
    }

    /**
     * Get event date
     *
     * @param int $id event id
     * @return string
     */
    public function getEventDate(int $id): string
    {
        //SQL statement to get a single event by ID
        $sql = "SELECT event_date FROM event WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Return the event
            return $result->fetch_assoc()['event_date'];
        } else {
            //If the query fails, return an empty array
            return "";
        }
    }

    /**
     * Get event location
     *
     * @param int $id event id
     * @return int //school id
     */
    public function getEventLocationId(int $id): int
    {
        //SQL statement to get a single event by ID
        $sql = "SELECT location FROM event WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Return the event
            return $result->fetch_assoc()['location'];
        } else {
            //If the query fails, return an empty array
            return 0;
        }
    }

    /**
     * Get event location
     *
     * @param int $id event id
     * @return string school name
     */
    public function getEventLocation(int $id): string
    {
        //instance of school class
        $school = new School();
        //get the event location id
        $locationId = $this->getEventLocationId($id);
        //get the school name using the location id as the school id
        $location = $school->getSchoolName($locationId);
        //return the school name
        return $location;
    }

    /**
     * Get the event creation date
     *
     * @param int $id event id
     * @return string
     */
    public function getEventCreationDate(int $id): string
    {
        //SQL statement to get a single event by ID
        $sql = "SELECT created_at FROM event WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Return the event
            return $result->fetch_assoc()['created_at'];
        } else {
            //If the query fails, return an empty array
            return "";
        }
    }

    /**
     * Get the event last updated date
     *
     * @param int $id event id
     * @return string
     */
    public function getEventLastUpdatedDate(int $id): string
    {
        //SQL statement to get a single event by ID
        $sql = "SELECT updated_at FROM event WHERE id = $id";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Return the event
            return $result->fetch_assoc()['updated_at'];
        } else {
            //If the query fails, return an empty array
            return "";
        }
    }

    /**
     * Get the event created by user
     *
     * @param int $id event id
     * @return User
     */
    public function getEventCreatedBy(int $id): User
    {
        //SQL statement to get a single event by ID
        $sql = "SELECT created_by FROM event WHERE id = $id";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $created_by = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $created_by = $row['created_by'];
            }
        }

        //instantiate the user class
        $user = new User();
        //get the matching users from the user class
        $userArray = $user->getUserById($created_by);
        //should only be one matching user, so set the first one
        $user = $userArray[0];
        //return the user
        return $user;
    }

    /**
     * Get the event last updated by user
     *
     * @param int $id event id
     * @return User
     */
    public function getEventLastUpdatedBy(int $id): User
    {
        //SQL statement to get a single event by ID
        $sql = "SELECT updated_by FROM event WHERE id = $id";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $updated_by = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $updated_by = $row['updated_by'];
            }
        }

        //instantiate the user class
        $user = new User();
        //get the matching users from the user class
        $userArray = $user->getUserById($updated_by);
        //should only be one matching user, so set the first one
        $user = $userArray[0];
        //return the user
        return $user;
    }

    /**
     * Get the most recent event
     *
     * @return Event
     */
    public function getMostRecentEvent(): Event
    {
        //instance of event class
        $event = new Event();
        //get all events
        $events = $event->getEvents();
        //sort the events by event_date
        usort($events, function ($a, $b) {
            return strtotime($a['event_date']) - strtotime($b['event_date']);
        });
        //return compare the dates to the current date, get the most recent passed event
        foreach ($events as $event) {
            if (strtotime($event['event_date']) < strtotime(date("Y-m-d"))) {
                return $event;
            }
        }
        //if no events have passed, return an empty event
        return new Event();
    }

    /**
     * Get upcoming events
     *
     * @return array
     */
    public function getUpcomingEvents(): array
    {
        //instance of event class
        $event = new Event();
        //get all events
        $events = $event->getEvents();
        //sort the events by event_date
        usort($events, function ($a, $b) {
            return strtotime($a['event_date']) - strtotime($b['event_date']);
        });
        //array to hold upcoming events
        $upcomingEvents = array();
        //compare the dates to the current date, get the any that have not occurred yet
        foreach ($events as $event) {
            if (strtotime($event['event_date']) > strtotime(date("Y-m-d"))) {
                $upcomingEvents[] = $event;
            }
        }
        //if not empty, sort the upcoming events by event_date to make sure they are in order by soonest to current date
        if (!empty($upcomingEvents)) {
            usort($upcomingEvents, function ($a, $b) {
                return strtotime($a['event_date']) - strtotime($b['event_date']);
            });
        }
        //return the upcoming events
        return $upcomingEvents;
    }

    /**
     * Is there an upcoming event within X days
     *
     * @param int $days //number of days to check
     * @return bool
     */
    public function isUpcomingEvent(int $days): bool
    {
        //instance of event class
        $event = new Event();
        //get all events
        $events = $event->getEvents();
        //sort the events by event_date
        usort($events, function ($a, $b) {
            return strtotime($a['event_date']) - strtotime($b['event_date']);
        });
        //compare the dates to the current date, get the any that have not occurred yet
        foreach ($events as $event) {
            if (strtotime($event['event_date']) > strtotime(date("Y-m-d"))) {
                //if the event date is within the number of days, return true
                if (strtotime($event['event_date']) <= strtotime(date("Y-m-d", strtotime("+$days days")))) {
                    return true;
                }
            }
        }
        //if no upcoming events, return false
        return false;
    }

    /**
     * Slugify the event name and add the slug to the database
     *
     * @param int $id event id
     * @return bool
     */
    public function slugifyEvent(int $id): bool
    {
        //instance of event class
        $event = new Event();
        //get the event name
        $name = $event->getEventName($id);
        //slugify the name
        $slug = toSlug($name);
        //check if the slug already exists using event id and slug
        $slugExists = $this->checkEventSlug($id, $slug);

        //if the slug already exists, update the slug
        if ($slugExists) {
            return $this->updateEventSlug($id, $slug);
        } else {
            //if the slug does not exist, create the slug
            return $this->createEventSlug($id);
        }
    }

    /**
     * Check if the event slug already exists
     *
     * @param int $id event id
     * @param string $slug event slug
     * @return bool
     */
    public function checkEventSlug(int $id, string $slug): bool
    {
        //SQL statement to check if the event slug already exists
        $sql = "SELECT * FROM event_slugs WHERE event_id = ? AND slug = ?";
        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);
        //bind the parameters
        $stmt->bind_param("is", $id, $slug);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //if the result has rows, return true
        if ($result->num_rows > 0) {
            return true;
        } else {
            //if no results, return false
            return false;
        }
    }

    /**
     * Update the event slug
     *
     * @param int $id event id
     * @param string $slug event slug
     * @return bool
     */
    public function updateEventSlug(int $id, string $slug): bool
    {
        //SQL statement to update the event slug
        $sql = "UPDATE event_slugs SET slug = ? WHERE event_id = ?";
        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);
        //bind the parameters
        $stmt->bind_param("si", $slug, $id);
        //execute the statement
        $stmt->execute();
        //if the statement was successful, return true
        if ($stmt) {
            return true;
        } else {
            //if the statement failed, return false
            return false;
        }
    }

    /**
     * Get the event slug
     *
     * @param int $id event id
     * @return string
     */
    public function getEventSlug(int $id): string
    {
        //SQL statement to get the event slug
        $sql = "SELECT slug FROM event_slugs WHERE event_id = ?";
        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);
        //bind the parameters
        $stmt->bind_param("i", $id);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //if the result has rows, return the slug
        if ($result->num_rows > 0) {
            return $result->fetch_assoc()['slug'];
        } else {
            //if no results, return an empty string
            return "";
        }
    }

    /**
     * Create the event slug
     *
     * @param int $id event id
     * @return bool
     */
    public function createEventSlug(int $id): bool
    {
        //SQL statement to create the event slug
        $sql = "INSERT INTO event_slugs (event_id, slug) VALUES (?, ?)";
        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);
        //get the event name
        $name = $this->getEventName($id);
        //slugify the name
        $slug = toSlug($name);
        //bind the parameters
        $stmt->bind_param("is", $id, $slug);
        //execute the statement
        $stmt->execute();
        //if the statement was successful, return true
        if ($stmt) {
            return true;
        } else {
            //if the statement failed, return false
            return false;
        }
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
        $stmt = $this->mysqli->prepare($sql);
        //bind the parameters
        $stmt->bind_param("s", $slug);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //if the result has rows, return the event
        if ($result->num_rows > 0) {
            //get the event id
            $id = $result->fetch_assoc()['event_id'];
            //get the event
            $event = $this->getEventById($id);
            //return the event
            return $event;
        } else {
            //if no results, return an empty array
            return array();
        }
    }
}
