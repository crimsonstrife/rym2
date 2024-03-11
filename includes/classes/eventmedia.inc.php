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
 * @requires PHP 8.1.2+
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
     * Does event have branding?
     * Searches for event branding by event id, either logo or banner. Returns an array of the media id's if found.
     *
     * @param int $eventID event id
     *
     * @return null|array media id's
     */
    public function doesEventHaveBranding(int $eventID): null|array
    {
        //SQL statement to get the event branding
        $sql = "SELECT * FROM event_branding WHERE event_id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $eventID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //array to hold the media id's
        $mediaIDs = null;

        //if the result has rows, loop through the rows and add them to the media id's array
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $mediaIDs = array(
                'logo' => intval($row['event_logo']),
                'banner' => intval($row['event_banner'])
            );
        }

        //return the media id's array
        return $mediaIDs;
    }

    /**
     * Get event logo
     *
     * @param int $eventID event id
     * @return ?int media id
     */
    public function getEventLogo(int $eventID): ?int
    {
        //placeholder for the media id
        $mediaID = null;

        //SQL statement to get the event logo
        $sql = "SELECT * FROM event_branding WHERE event_id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $eventID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();
        //if the result has rows, set the media id to the event logo
        if ($result->num_rows > 0) {
            $mediaID = intval($result->fetch_assoc()['event_logo']);
        }

        //return the media id
        return $mediaID;
    }

    /**
     * Get event banner image
     *
     * @param int $eventID event id
     * @return int media id
     */
    public function getEventBanner(int $eventID): int
    {
        //placeholder for the media id
        $mediaID = 0;

        //SQL statement to get the event banner
        $sql = "SELECT * FROM event_branding WHERE event_id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("i", $eventID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //if the result has rows, set the media id to the event banner
        if ($result->num_rows > 0) {
            $mediaID = intval($result->fetch_assoc()['event_banner']);
        }

        //return the media id
        return $mediaID;
    }

    /**
     * Set event logo
     *
     * @param int $eventID event id
     * @param int $logo media id
     *
     */
    public function setEventLogo(int $eventID, int $logo)
    {
        //SQL statement to set the event logo
        $sql = "INSERT INTO event_branding (event_id, event_logo) VALUES (?, ?)";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("ii", $eventID, $logo);

        //execute the statement
        $stmt->execute();

        //if the statement was successful, log the activity
        if ($stmt) {
            //instance of the event class
            $event = new Event();
            //instance of the activity class
            $activity = new Activity();
            //instance of the session class
            $session = new Session();
            //get the user id from the session
            $userID = intval($session->get('user_id'));
            //log the activity
            $activity->logActivity($userID, "Event Logo Added", "Logo Image added to Event ID: " . $eventID . " Event Name: " . $event->getEventName($eventID) . "");
        }
    }

    /**
     * Set event banner
     *
     * @param int $eventID event id
     * @param int $banner media id
     */
    public function setEventBanner(int $eventID, int $banner)
    {
        //SQL statement to set the event banner
        $sql = "INSERT INTO event_branding (event_id, event_banner) VALUES (?, ?)";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("ii", $eventID, $banner);

        //execute the statement
        $stmt->execute();

        //if the statement was successful, log the activity
        if ($stmt) {
            //instance of the event class
            $event = new Event();
            //instance of the activity class
            $activity = new Activity();
            //instance of the session class
            $session = new Session();
            //get the user id from the session
            $userID = intval($session->get('user_id'));
            //log the activity
            $activity->logActivity($userID, "Event Banner Added", "Banner Image added to Event ID: " . $eventID . " Event Name: " . $event->getEventName($eventID) . "");
        }
    }

    /**
     * Set event logo and banner
     *
     * does both, helps with delays in the sql execution which appeared to keep them from adding when run back to back
     *
     * @param int $eventID event id
     * @param int $logo event logo
     * @param int $banner event banner
     */
    public function setEventLogoAndBanner(int $eventID, int $logo, int $banner)
    {
        //SQL statement to set the event logo
        $sql = "INSERT INTO event_branding (event_id, event_logo, event_banner) VALUES (?, ?, ?)";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("iii", $eventID, $logo, $banner);

        //execute the statement
        $stmt->execute();
    }

    /**
     * Update event logo
     *
     * @param int $eventID event id
     * @param int $logo media id
     */
    public function updateEventLogo(int $eventID, int $logo)
    {
        //instance of the event class
        $event = new Event();
        //instance of the activity class
        $activity = new Activity();
        //instance of the session class
        $session = new Session();

        //SQL statement to update the event logo
        $sql = "UPDATE event_branding SET event_logo = ? WHERE event_id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("ii", $logo, $eventID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //get the user id from the session
        $userID = intval($session->get('user_id'));

        //if the result has rows, log the activity
        if ($result) {
            //log the activity
            $activity->logActivity($userID, "Event Logo Updated", "Logo Image updated for Event ID: " . $eventID . " Event Name: " . $event->getEventName($eventID) . "");
        } else {
            //since it is possible there was nothing to update, attempt to insert an entry for the event and logo
            $this->setEventLogo($eventID, $logo);
        }
    }

    /**
     * Update event banner
     *
     * @param int $eventID event id
     * @param int $banner media id
     */
    public function updateEventBanner(int $eventID, int $banner)
    {
        //instance of the event class
        $event = new Event();
        //instance of the activity class
        $activity = new Activity();
        //instance of the session class
        $session = new Session();

        //SQL statement to update the event banner
        $sql = "UPDATE event_branding SET event_banner = ? WHERE event_id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("ii", $banner, $eventID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //get the user id from the session
        $userID = intval($session->get('user_id'));

        //if the result has rows, log the activity
        if ($result) {
            //log the activity
            $activity->logActivity($userID, "Event Banner Updated", "Banner Image updated for Event ID: " . $eventID . " Event Name: " . $event->getEventName($eventID) . "");
        } else {
            //since it is possible there was nothing to update, attempt to insert an entry for the event and banner
            $this->setEventBanner($eventID, $banner);
        }
    }

    /**
     * Update event logo and banner
     * does both, helps with delays in the sql execution which appeared to keep them from adding when run back to back
     *
     * @param int $eventID event id
     * @param int $logo event logo path
     * @param int $banner event banner path
     */
    public function updateEventLogoAndBanner(int $eventID, int $logo, int $banner)
    {
        //instance of the event class
        $event = new Event();
        //instance of the activity class
        $activity = new Activity();
        //instance of the session class
        $session = new Session();

        //SQL statement to update the event logo
        $sql = "UPDATE event_branding SET event_logo = ?, event_banner = ? WHERE event_id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("iii", $logo, $banner, $eventID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //get the user id from the session
        $userID = intval($session->get('user_id'));

        //if the result has rows, log the activity
        if ($result) {
            //log the activity
            $activity->logActivity($userID, "Event Logo and Banner Updated", "Logo and Banner Images updated for Event ID: " . $eventID . " Event Name: " . $event->getEventName($eventID) . "");
        } else {
            //log the failure to update
            $activity->logActivity($userID, "Event Logo and Banner Update Failed", "Logo and Banner Images update failed for Event ID: " . $eventID . " Event Name: " . $event->getEventName($eventID) . "");
        }
    }

    /**
     * Get all the event ids using a banner or logo that matches a media id
     *
     * @param int $mediaID media id
     *
     * @return array event ids
     */
    public function getEventsByMediaId(int $mediaID): array
    {
        //SQL statement to get all the event ids using a banner or logo that matches a media id
        $sql = "SELECT event_id FROM event_branding WHERE event_logo = ? OR event_banner = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param("ii", $mediaID, $mediaID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //array to hold the event ids
        $eventIDs = array();

        //if the result has rows, loop through the rows and add them to the event ids array
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $eventIDs[] = $row['event_id'];
            }
        }

        //return the event ids array
        return $eventIDs;
    }
}
