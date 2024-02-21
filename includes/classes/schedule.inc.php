<?php

/**
 * Schedule Class file for the College Recruitment Application
 * Contains all the functions for the Schedule Class and handles all the scheduling related tasks for events with the database.
 *
 * Author: Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date: 10/16/2023
 *
 * @package RYM2
 * Filename: schedule.inc.php
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
 * Summary of Schedule Class
 *
 * This class handles all the Schedule related tasks with the database.
 */
class Schedule extends Event
{
    //Reference to the database
    private $mysqli;

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
        usort($events, function ($a,
            $b
        ) {
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
        usort($events, function ($a,
            $b
        ) {
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
        usort($events, function ($a,
            $b
        ) {
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
}
