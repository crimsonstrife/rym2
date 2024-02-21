<?php

/**
 * Event Media Class file for the College Recruitment Application
 * Contains all the functions for the Event Media Class and handles event media functions.
 *
 * Author: Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date: 10/16/2023
 *
 * @package RYM2
 * Filename: eventmedia.inc.php
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

class EventMedia extends Media
{
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
        $stmt = prepareStatement($this->mysqli, $sql);

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
        $stmt = prepareStatement($this->mysqli, $sql);

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
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("ii", $id, $logo);

        //execute the statement
        $stmt->execute();

        //if the statement was successful, log the activity
        if ($stmt) {
            //instance of the event class
            $event = new Event();
            //log the activity
            $activity = new Activity();
            $activity->logActivity(intval($_SESSION['user_id']), "Event Logo Added", "Logo Image added to Event ID: " . $id . " Event Name: " . $event->getEventName($id) . "");
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
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("ii", $id, $banner);

        //execute the statement
        $stmt->execute();

        //if the statement was successful, log the activity
        if ($stmt) {
            //instance of the event class
            $event = new Event();
            //log the activity
            $activity = new Activity();
            $activity->logActivity(intval($_SESSION['user_id']), "Event Banner Added", "Banner Image added to Event ID: " . $id . " Event Name: " . $event->getEventName($id) . "");
        }
    }

    /**
     * Set event logo and banner
     *
     * does both, helps with delays in the sql execution which appeared to keep them from adding when run back to back
     *
     * @param int $id event id
     * @param int $logo event logo
     * @param int $banner event banner
     */
    public function setEventLogoAndBanner(int $id, int $logo, int $banner)
    {
        //SQL statement to set the event logo
        $sql = "INSERT INTO event_branding (event_id, event_logo, event_banner) VALUES (?, ?, ?)";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

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
        $stmt = prepareStatement($this->mysqli, $sql);

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
        $stmt = prepareStatement($this->mysqli, $sql);

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
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("iii", $logo, $banner, $id);

        //execute the statement
        $stmt->execute();
    }

    /**
     * Get all the event ids using a banner or logo that matches a media id
     *
     * @param int $media_id media id
     *
     * @return array event ids
     */
    public function getEventsByMediaId(int $media_id): array
    {
        //SQL statement to get all the event ids using a banner or logo that matches a media id
        $sql = "SELECT event_id FROM event_branding WHERE event_logo = ? OR event_banner = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("ii", $media_id, $media_id);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //array to hold the event ids
        $event_ids = array();

        //if the result has rows, loop through the rows and add them to the event ids array
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $event_ids[] = $row['event_id'];
            }
        }

        //return the event ids array
        return $event_ids;
    }
}
