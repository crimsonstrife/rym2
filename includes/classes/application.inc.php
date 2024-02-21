<?php

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* Include the base application config file */
require_once(__DIR__ . '/../../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');

use Exception;
use Activity;
use Session;

/**
 * The Application Class
 *
 * Contains functions for adjusting system features and settings
 *
 * @package RYM2
 */
class Application
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
     * Get all Settings
     * get all settings from the database settings table
     *
     * @return array
     */
    public function getAllSettings()
    {
        //SQL statement to get all the settings
        $sql = "SELECT * FROM settings";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create an array to hold the settings
        $settings = array();

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $settings[] = $row;
        }

        //Return the array of settings
        return $settings;
    }

    /**
     * Get Setting
     *
     * Get a setting from the database settings table
     *
     * @param string $setting //the setting to get
     * @return string //the setting value
     */
    public function getSetting($setting)
    {
        //SQL statement to get the setting
        $sql = "SELECT $setting FROM settings WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //placeholder for the setting
        $settingValue = '';

        //check if the setting is set or not
        if (isset($row[$setting])) {
            //set the setting
            $settingValue = $row[$setting];
        }

        //Return the setting
        return $settingValue;
    }

    /**
     * Get the Privacy Policy Content
     * Get the privacy policy content from the settings table, and coverts it from Markdown to HTML,
     * returns a string with the HTML content
     *
     * @return string //the privacy policy content
     */
    public function getPrivacyPolicy()
    {
        //SQL statement to get the privacy policy content
        $sql = "SELECT privacy_policy FROM settings WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //placeholder for the privacy policy
        $privacyPolicy = '';

        //check if the privacy policy is set or not
        $privacyPolicy = $row['privacy_policy'] ?? strval(PRIVACY_POLICY) ?? '';

        //Return the privacy policy
        return $privacyPolicy;
    }

    /**
     * Set the Privacy Policy Content
     * Set the privacy policy content in the settings table
     *
     * @param string $privacyPolicy //the privacy policy content
     * @return bool //true if the privacy policy content was set, false if not
     */
    public function setPrivacyPolicy($privacyPolicy = null)
    {
        //SQL statement to update the privacy policy content
        $sql = "UPDATE settings SET privacy_policy = ? WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters
        $stmt->bind_param('s', $privacyPolicy);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $session = new Session();
            $userID = intval($session->sessionVars['user_id']);
            $activity->logActivity(intval($userID), 'Privacy Policy Updated', 'The privacy policy was changed.');
            //Return true
            return true;
        }

        //Return false
        return false;
    }

    /**
     * Get the Terms and Conditions Content
     * Get the terms and conditions content from the settings table, and coverts it from Markdown to HTML,
     * returns a string with the HTML content
     *
     * @return string //the terms content
     */
    public function getTerms()
    {
        //SQL statement to get the terms and conditions content
        $sql = "SELECT terms_conditions FROM settings WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //placeholder for the terms
        $terms = '';

        //check if the privacy policy is set or not
        $terms = $row['terms_conditions'] ?? strval(TERMS_CONDITIONS) ?? '';

        //Return the terms
        return $terms;
    }

    /**
     * Set the Terms and Conditions Content
     * Set the terms and conditions content in the settings table
     *
     * @param string $terms //the terms and conditions content, passed in as Markdown
     * @return bool //true if the terms and conditions content was set, false if not
     */
    public function setTerms($terms = null)
    {
        //SQL statement to update the terms and conditions content
        $sql = "UPDATE settings SET terms_conditions = ? WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters
        $stmt->bind_param('s', $terms);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $session = new Session();
            $userID = $session->sessionVars['user_id'];
            $activity->logActivity(intval($userID), 'Terms and Conditions Updated', 'The terms and conditions were changed.');
            //Return true
            return true;
        }

        //Return false
        return false;
    }
}
