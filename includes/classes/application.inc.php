<?php

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* Include the base application config file */
require_once(__DIR__ . '/../../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');

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

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {

                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {

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
        } else {
            //log the error
            error_log('Error: The database connection is not set.');
            //throw an exception
            throw new Exception("The database connection is not set.");
        }
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

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {

                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $stmt = prepareStatement($this->mysqli, $sql);

                //Execute the statement
                $stmt->execute();

                //Get the results
                $result = $stmt->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the setting is set or not
                    if (isset($row[$setting])) {
                        //Return the setting
                        return $row[$setting];
                    } else {
                        //Return an empty string
                        return '';
                    }
                } else {
                    //Return an empty string
                    return '';
                }
            }
        } else {
            //log the error
            error_log('Error: The database connection is not set.');
            //throw an exception
            throw new Exception("The database connection is not set.");
        }
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

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {

                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $stmt = prepareStatement($this->mysqli, $sql);

                //Execute the statement
                $stmt->execute();

                //Get the results
                $result = $stmt->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the privacy_policy is set or not
                    if (isset($row['privacy_policy'])) {
                        //set the privacy policy
                        $privacy_policy = $row['privacy_policy'];

                        //Return the privacy_policy
                        return $privacy_policy;
                    } else {
                        //get the default privacy policy
                        $privacy_policy = strval(PRIVACY_POLICY);

                        //Return the privacy_policy
                        return $privacy_policy;
                    }
                } else {
                    //Return an empty string
                    return '';
                }
            }
        } else {
            //log the error
            error_log('Error: The database connection is not set.');
            //throw an exception
            throw new Exception("The database connection is not set.");
        }
    }

    /**
     * Set the Privacy Policy Content
     * Set the privacy policy content in the settings table
     *
     * @param string $privacy_policy //the privacy policy content, passed in as Markdown
     * @return bool //true if the privacy policy content was set, false if not
     */
    public function setPrivacyPolicy($privacy_policy = null)
    {
        //SQL statement to update the privacy policy content
        $sql = "UPDATE settings SET privacy_policy = ? WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters
        $stmt->bind_param('s', $privacy_policy);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity(intval($_SESSION['user_id']), 'Privacy Policy Updated', 'The privacy policy was changed.');
            //Return true
            return true;
        } else {
            //Return false
            return false;
        }
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

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {

                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $stmt = prepareStatement($this->mysqli, $sql);

                //Execute the statement
                $stmt->execute();

                //Get the results
                $result = $stmt->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the terms_conditions is set or not
                    if (isset($row['terms_conditions'])) {
                        //set the terms
                        $terms = $row['terms_conditions'];

                        //Return the terms
                        return $terms;
                    } else {
                        //get the default terms and conditions
                        $terms = strval(TERMS_CONDITIONS);

                        //Return the terms
                        return $terms;
                    }
                } else {
                    //Return an empty string
                    return '';
                }
            }
        } else {
            //log the error
            error_log('Error: The database connection is not set.');
            //throw an exception
            throw new Exception("The database connection is not set.");
        }
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

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {

                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
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
                    $activity->logActivity(intval($_SESSION['user_id']), 'Terms and Conditions Updated', 'The terms and conditions were changed.');
                    //Return true
                    return true;
                } else {
                    //Return false
                    return false;
                }
            }
        } else {
            //log the error
            error_log('Error: The database connection is not set.');
            //throw an exception
            throw new Exception("The database connection is not set.");
        }
    }
}
