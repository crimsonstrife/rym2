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
            //Loop through the result and add each row to the events array
            while ($row = $result->fetch_assoc()) {
                $event[] = $row;
            }
            //if the event array is not empty, return the event
            if (!empty($event)) {
                return $event;
            } else {
                //if the event array is empty, return an empty array
                return array();
            }
        } else {
            //If the query fails, return an empty array
            return array();
        }
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
            return intval($result->fetch_assoc()['location']);
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
        $stmt = $this->mysqli->prepare($sql);

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
            //log the activity
            $activity = new Activity();
            $activity->logActivity(intval($_SESSION['user_id']), "Event Sluggified", "Event Slug for Event:" . $event->getEventName($id) . " already exists, incrementing slug");
            return $this->updateEventSlug($id, $slug);
        } else {
            //log the activity
            $activity = new Activity();
            $activity->logActivity(intval($_SESSION['user_id']), "Event Sluggified", "Event Slug for Event:" . $event->getEventName($id) . " does not exist, creating slug");
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
     * Check if the event slug already exists from any event id
     * This is used when updating the event slug
     *
     * @param string $slug event slug
     * @return bool
     */
    public function checkEventSlugExists(string $slug): bool
    {
        //SQL statement to check if the event slug already exists
        $sql = "SELECT * FROM event_slugs WHERE slug = ?";
        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);
        //bind the parameters
        $stmt->bind_param("s", $slug);
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
     * Get the event id by slug
     *
     * @param string $slug event slug
     * @return int
     */
    public function getEventIdBySlug(string $slug): int
    {
        //SQL statement to get the event id by slug
        $sql = "SELECT event_id FROM event_slugs WHERE slug = ?";
        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);
        //bind the parameter
        $stmt->bind_param("s", $slug);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //if the result has rows, return the id
        if ($result->num_rows > 0) {
            return intval($result->fetch_assoc()['event_id']);
        } else {
            //if no results, return 0
            return 0;
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
        //check if the slug already exists using on any event id
        $slugExists = $this->checkEventSlugExists($slug);
        //if the slug already exists, check if the event id matches the current event id
        if ($slugExists) {
            //get the event id by slug
            $event_id = $this->getEventIdBySlug($slug);
            //if the event id matches the current event id, update the slug
            if ($event_id == $id) {
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
                    //log the activity
                    $activity = new Activity();
                    $activity->logActivity(intval($_SESSION['user_id']), "Event Slug Updated", "Event ID: " . $id . " Event Name: " . $this->getEventName($id) . " was sluggified with Slug: " . $slug . "");
                    return true;
                } else {
                    //if the statement failed, return false
                    return false;
                }
            } else {
                //if the event id does not match the current event id, it is a duplicate slug, so increment the slug and try again
                $slug = $this->incrementSlug($slug);
                //update the slug
                return $this->updateEventSlug($id, $slug);
            }
        } else {
            //if the slug does not exist, update the slug
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
                //log the activity
                $activity = new Activity();
                $activity->logActivity(intval($_SESSION['user_id']), "Event Slug Updated", "Event ID: " . $id . " Event Name: " . $this->getEventName($id) . " was sluggified with Slug: " . $slug . "");
                return true;
            } else {
                //if the statement failed, return false
                return false;
            }
        }
    }

    /**
     * Increment the slug in case of duplicate slugs
     * adds or increments a number at the end of the slug
     * @param string $slug event slug
     * @return string
     */
    public function incrementSlug(string $slug): string
    {
        //get the last character of the slug
        $lastChar = substr($slug, -1);
        //if the last character is a number, increment it
        if (is_numeric($lastChar)) {
            $lastChar++;
        } else {
            //if the last character is not a number, add a hyphen and a 1
            $slug .= "-1";
        }
        //keep incrementing until the slug does not exist
        $slugExists = $this->checkEventSlugExists($slug);
        while ($slugExists) {
            //get the last character of the slug
            $lastChar = substr($slug, -1);
            //if the last character is a number, increment it
            if (is_numeric($lastChar)) {
                $lastChar++;
            } else {
                //if the last character is not a number, add a hyphen and a 1
                $slug .= "-1";
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
        //check if the slug already exists on any event id
        $slugExists = $this->checkEventSlugExists($slug);
        //if the slug already exists, increment the slug
        if ($slugExists) {
            $slug = $this->incrementSlug($slug);
            //bind the parameters
            $stmt->bind_param("is", $id, $slug);
            //execute the statement
            $stmt->execute();
            //if the statement was successful, return true
            if ($stmt) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity(intval($_SESSION['user_id']), "Event Slug Created", "Event ID: " . $id . " Event Name: " . $this->getEventName($id) . " was sluggified with Slug: " . $slug . "");
                return true;
            } else {
                //if the statement failed, return false
                return false;
            }
        } else {
            //if the slug does not exist, create the slug
            //bind the parameters
            $stmt->bind_param("is", $id, $slug);
            //execute the statement
            $stmt->execute();
            //if the statement was successful, return true
            if ($stmt) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity(intval($_SESSION['user_id']), "Event Slug Created", "Event ID: " . $id . " Event Name: " . $this->getEventName($id) . " was sluggified with Slug: " . $slug . "");
                return true;
            } else {
                //if the statement failed, return false
                return false;
            }
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

    /**
     * Get event logo
     *
     * @param int $id event id
     * @return int media id
     */
    public function getEventLogo(int $id): int
    {
        //placeholder for the media id
        $media_id = 0;

        //SQL statement to get the event logo
        $sql = "SELECT * FROM event_branding WHERE event_id = ?";

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

        //bind the parameters
        $stmt->bind_param("i", $id);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();
        //if the result has rows, set the media id to the event logo
        if ($result->num_rows > 0) {
            $media_id = intval($result->fetch_assoc()['event_logo']);
        }

        //return the media id
        return $media_id;
    }

    /**
     * Get event banner image
     *
     * @param int $id event id
     * @return int media id
     */
    public function getEventBanner(int $id): int
    {
        //placeholder for the media id
        $media_id = 0;

        //SQL statement to get the event banner
        $sql = "SELECT * FROM event_branding WHERE event_id = ?";

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

        //bind the parameters
        $stmt->bind_param("i", $id);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //if the result has rows, set the media id to the event banner
        if ($result->num_rows > 0) {
            $media_id = intval($result->fetch_assoc()['event_banner']);
        }

        //return the media id
        return $media_id;
    }

    /**
     * Set event logo
     *
     * @param int $id event id
     * @param int $logo media id
     *
     */
    public function setEventLogo(int $id, int $logo)
    {
        //SQL statement to set the event logo
        $sql = "INSERT INTO event_branding (event_id, event_logo) VALUES (?, ?)";

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

        //bind the parameters
        $stmt->bind_param("ii", $id, $logo);

        //execute the statement
        $stmt->execute();

        //if the statement was successful, log the activity
        if ($stmt) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity(intval($_SESSION['user_id']), "Event Logo Added", "Logo Image added to Event ID: " . $id . " Event Name: " . $this->getEventName($id) . "");
        }
    }

    /**
     * Set event banner
     *
     * @param int $id event id
     * @param int $banner media id
     */
    public function setEventBanner(int $id, int $banner)
    {
        //SQL statement to set the event banner
        $sql = "INSERT INTO event_branding (event_id, event_banner) VALUES (?, ?)";

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

        //bind the parameters
        $stmt->bind_param("ii", $id, $banner);

        //execute the statement
        $stmt->execute();

        //if the statement was successful, log the activity
        if ($stmt) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity(intval($_SESSION['user_id']), "Event Banner Added", "Banner Image added to Event ID: " . $id . " Event Name: " . $this->getEventName($id) . "");
        }
    }

    /**
     * Set event logo and banner
     *
     * does both, helps with delays in the sql execution which appeared to keep them from adding when run back to back
     *
     * @param int $id event id
     * @param int $logo event logo path
     * @param int $banner event banner path
     */
    public function setEventLogoAndBanner(int $id, int $logo, int $banner)
    {
        //SQL statement to set the event logo
        $sql = "INSERT INTO event_branding (event_id, event_logo, event_banner) VALUES (?, ?, ?)";

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

        //bind the parameters
        $stmt->bind_param("iii", $id, $logo, $banner);

        //execute the statement
        $stmt->execute();
    }

    /**
     * Update event logo
     *
     * @param int $id event id
     * @param int $logo media id
     */
    public function updateEventLogo(int $id, int $logo)
    {
        //SQL statement to update the event logo
        $sql = "UPDATE event_branding SET event_logo = ? WHERE event_id = ?";

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

        //bind the parameters
        $stmt->bind_param("ii", $logo, $id);

        //execute the statement
        $stmt->execute();
    }

    /**
     * Update event banner
     *
     * @param int $id event id
     * @param int $banner media id
     */
    public function updateEventBanner(int $id, int $banner)
    {
        //SQL statement to update the event banner
        $sql = "UPDATE event_branding SET event_banner = ? WHERE event_id = ?";

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

        //bind the parameters
        $stmt->bind_param("ii", $banner, $id);

        //execute the statement
        $stmt->execute();
    }

    /**
     * Update event logo and banner
     * does both, helps with delays in the sql execution which appeared to keep them from adding when run back to back
     *
     * @param int $id event id
     * @param int $logo event logo path
     * @param int $banner event banner path
     */
    public function updateEventLogoAndBanner(int $id, int $logo, int $banner)
    {
        //SQL statement to update the event logo
        $sql = "UPDATE event_branding SET event_logo = ?, event_banner = ? WHERE event_id = ?";

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

        //bind the parameters
        $stmt->bind_param("iii", $logo, $banner, $id);

        //execute the statement
        $stmt->execute();
    }

    /**
     * Update event
     *
     * @param int $id event id
     * @param string $name event name
     * @param string $date event date
     * @param int $location event location id
     * @param int $updated_by user id of the user updating the event
     * @return bool
     */
    public function updateEvent(int $id, string $name, string $date, int $location, int $updated_by): bool
    {
        //SQL statement to update the event
        $sql = "UPDATE event SET name = ?, event_date = ?, location = ?, updated_by = ? WHERE id = ?";
        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);
        //bind the parameters
        $stmt->bind_param("ssiii", $name, $date, $location, $updated_by, $id);
        //execute the statement
        $stmt->execute();
        //if the statement was successful, return true
        if ($stmt) {
            //slugify the event
            $sluggified = $this->slugifyEvent($id);
            if ($sluggified) {
                //get the event name
                $event_name = $this->getEventName($id);
                //set the event id
                $event_id = $id;
                //log the activity
                $activity = new Activity();
                $activity->logActivity(intval($_SESSION['user_id']), "Event Updated", 'Event ID: ' . $event_id . ' Event Name: ' . $event_name);
                return true;
            } else {
                return false;
            }
        } else {
            //if the statement failed, return false
            return false;
        }
    }

    /**
     * Create event
     *
     * @param string $name event name
     * @param string $date event date
     * @param int $location event location id
     * @param int $created_by user id of the user creating the event
     *
     * @return bool
     */
    public function createEvent(string $name, string $date, int $location, int $created_by): bool
    {
        //get the current date and time
        $created_at = date("Y-m-d H:i:s");
        //SQL statement to create the event
        $sql = "INSERT INTO event (name, event_date, location, created_at, created_by, updated_at, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?)";
        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);
        //bind the parameters
        $stmt->bind_param("ssisiss", $name, $date, $location, $created_at, $created_by, $created_at, $created_by);
        //execute the statement
        $stmt->execute();
        //if the statement was successful, return true
        if ($stmt) {
            //slugify the event
            $sluggified = $this->slugifyEvent($this->getEventIdByName($name));
            if ($sluggified) {
                //get the event id
                $event_id = $this->getEventIdByName($name);
                //get the event name
                $event_name = $this->getEventName($event_id);
                //log the activity
                $activity = new Activity();
                $activity->logActivity(intval($_SESSION['user_id']), "Event Created", 'Event ID: ' . $event_id . ' Event Name: ' . $event_name);
                return true;
            } else {
                return false;
            }
        } else {
            //if the statement failed, return false
            return false;
        }
    }

    /**
     * Get event ID by name
     *
     * @param string $name event name
     * @return int
     */
    public function getEventIdByName(string $name): int
    {
        //SQL statement to get the event id by name
        $sql = "SELECT id FROM event WHERE name = ?";
        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);
        //bind the parameter
        $stmt->bind_param("s", $name);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //if the result has rows, return the id
        if ($result->num_rows > 0) {
            return intval($result->fetch_assoc()['id']);
        } else {
            //if no results, return 0
            return 0;
        }
    }

    /**
     * Get the number of students attending an event
     * @param int $id event id
     * @return int count
     */
    public function getStudentCount(int $id): int
    {
        //SQL statement to get the number of students attending an event
        $sql = "SELECT COUNT(*) FROM student_at_event WHERE event_id = ?";
        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);
        //bind the parameter
        $stmt->bind_param("i", $id);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //if the result has rows, return the count
        if ($result->num_rows > 0) {
            return intval($result->fetch_assoc()['COUNT(*)']);
        } else {
            //if no results, return 0
            return 0;
        }
    }

    /**
     * Get the number of events held at a school
     *
     * @param int $id school id
     * @return int count
     */
    public function getHeldEvents(int $id): int
    {
        //SQL statement to get the number of events held at a school
        $sql = "SELECT COUNT(*) FROM event WHERE location = ?";
        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);
        //bind the parameter
        $stmt->bind_param("i", $id);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();
        //if the result has rows, return the count
        if ($result->num_rows > 0) {
            return intval($result->fetch_assoc()['COUNT(*)']);
        } else {
            //if no results, return 0
            return 0;
        }
    }

    /**
     * Search Events
     * Search events by name, date, or location, gets the location name from the school database
     *
     * @param string $searchTerm search string
     *
     * @return array
     */
    public function searchEvents(string $searchTerm): array
    {
        //SQL statement to search events, cross referencing the location with the school database id
        $sql = "SELECT event.id, event.name, event.event_date, school.name AS location FROM event INNER JOIN school ON event.location = school.id WHERE event.name LIKE ? OR event.event_date LIKE ? OR school.name LIKE ?";
        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);
        //setup the search term
        $searchTerm = "%" . $searchTerm . "%";
        //bind the parameters
        $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
        //execute the statement
        $stmt->execute();
        //get the result
        $result = $stmt->get_result();

        //array to hold the events
        $events = array();

        //if the result has rows, loop through the rows and add them to the events array
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $events[] = $row;
            }
        }

        //return the events array
        return $events;
    }

    /**
     * Delete event
     * Delete an event from the database
     *
     * @param int $event_id
     * @return boolean $result
     */
    public function deleteEvent(int $event_id): bool
    {
        //get the current date and time
        $date = date("Y-m-d H:i:s");

        //get the event name
        $event_name = $this->getEventName($event_id);

        //set the placeholder for the result
        $result = false;

        //create the sql statement
        $sql = "DELETE FROM event WHERE id = ?";

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

        //bind the parameters
        $stmt->bind_param("i", $event_id);

        //execute the statement
        $stmt->execute();

        //check the result
        if ($stmt->affected_rows > 0) {
            $result = true;
        } else {
            $result = false;
        }

        //log the event activity if the event was deleted
        if ($result) {
            $activity = new Activity();
            $activity->logActivity(intval($_SESSION['user_id']), 'Deleted Event', 'Event ID: ' . $event_id . ' Event Name: ' . $event_name);
        }

        //return the result
        return $result;
    }
}
