<?php

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* Include the base application config file */
require_once(__DIR__ . '/../../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');

use Michelf\Markdown;

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
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {

                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

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
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $setting_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $setting_statement->execute();

                //Get the results
                $result = $setting_statement->get_result();

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

    //General App Settings

    /**
     * Get Application Name
     *
     * Get the application name from the settings table
     *
     * @return string //the name string
     */
    public function getAppName()
    {
        //SQL statement to get the application name
        $sql = "SELECT app_name FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the app_name is set or not
                    if (isset($row['app_name'])) {
                        //Return the app_name
                        return $row['app_name'];
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
     * Set Application Name
     *
     * Set the application name in the settings table
     *
     * @param string $app_name //the application name
     * @return bool //true if the application name was set, false if not
     */
    public function setAppName($app_name = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the application name is set in the settings table already
                if ($this->getAppName() != '' || $this->getAppName() != null) {
                    //SQL statement to update the application name
                    $sql = "UPDATE settings SET app_name = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $app_name);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Application Name Changed', 'The application name was changed to ' . $app_name . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the application name, where isSet is SET
                    $sql = "UPDATE settings SET app_name = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $app_name);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Application Name Changed', 'The application name was changed to ' . $app_name . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get Application URL
     *
     * Get the application URL from the settings table
     *
     * @return string //the URL string
     */
    public function getAppURL()
    {
        //SQL statement to get the application URL
        $sql = "SELECT app_url FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {

                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the app_url is set or not
                    if (isset($row['app_url'])) {
                        //Return the app_url
                        return $row['app_url'];
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
     * Set Application URL
     *
     * Set the application URL in the settings table
     *
     * @param string $app_url //the application URL
     * @return bool //true if the application URL was set, false if not
     */
    public function setAppURL($app_url = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the application URL is set in the settings table already
                if ($this->getAppURL() != '' || $this->getAppURL() != null) {
                    //SQL statement to update the application URL
                    $sql = "UPDATE settings SET app_url = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $app_url);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Application URL Changed', 'The application URL was changed to ' . $app_url . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the application URL, where isSet is SET
                    $sql = "UPDATE settings SET app_url = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $app_url);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Application URL Changed', 'The application URL was changed to ' . $app_url . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get Application Logo
     * Get the application logo from the settings table
     *
     * @return int //the logo media id
     */
    public function getAppLogo()
    {
        //SQL statement to get the application logo
        $sql = "SELECT app_logo FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the app_logo is set or not
                    if (isset($row['app_logo'])) {
                        //Return the app_logo
                        return intval($row['app_logo']);
                    } else {
                        //Return an empty string
                        return intval(null);
                    }
                } else {
                    //Return an empty string
                    return intval(null);
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
     * Set Application Logo
     *
     * Set the application logo in the settings table
     *
     * @param int $app_logo //the application logo media id
     * @return bool //true if the application logo was set, false if not
     */
    public function setAppLogo(int $app_logo = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the application logo is set in the settings table already
                if ($this->getAppLogo() != '' || $this->getAppLogo() != null) {
                    //SQL statement to update the application logo
                    $sql = "UPDATE settings SET app_logo = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('i', $app_logo);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Application Logo Changed', 'The application logo was changed.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the application logo, where isSet is SET
                    $sql = "UPDATE settings SET app_logo = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('i', $app_logo);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Application Logo Changed', 'The application logo was changed.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get Contact Email
     *
     * Get the contact email from the settings table
     *
     * @return string //the email string
     */
    public function getContactEmail()
    {
        //SQL statement to get the contact email
        $sql = "SELECT contact_email FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the contact_email is set or not
                    if (isset($row['contact_email'])) {
                        //Return the contact_email
                        return $row['contact_email'];
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
     * Set Contact Email
     *
     * Set the contact email in the settings table
     *
     * @param string $contact_email //the contact email
     * @return bool //true if the contact email was set, false if not
     */
    public function setContactEmail($contact_email = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the contact email is set in the settings table already
                if ($this->getContactEmail() != '' || $this->getContactEmail() != null) {
                    //SQL statement to update the contact email
                    $sql = "UPDATE settings SET contact_email = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $contact_email);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Contact Email Changed', 'The contact email was changed to ' . $contact_email . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the contact email, where isSet is SET
                    $sql = "UPDATE settings SET contact_email = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $contact_email);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Contact Email Changed', 'The contact email was changed to ' . $contact_email . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                }
            }
        } else {
            //log the error
            error_log('Error: The database connection is not set.');
            //throw an exception
            throw new Exception("The database connection is not set.");
        }
    }

    //Company Branding

    /**
     * Get Company Name
     *
     * Get the company name from the settings table
     *
     * @return string //the name string
     */
    public function getCompanyName()
    {
        //SQL statement to get the company name
        $sql = "SELECT company_name FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the company_name is set or not
                    if (isset($row['company_name'])) {
                        //Return the company_name
                        return $row['company_name'];
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
     * Set Company Name
     *
     * Set the company name in the settings table
     *
     * @param string $company_name //the company name
     * @return bool //true if the company name was set, false if not
     */
    public function setCompanyName($company_name = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the company name is set in the settings table already
                if ($this->getCompanyName() != '' || $this->getCompanyName() != null) {
                    //SQL statement to update the company name
                    $sql = "UPDATE settings SET company_name = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $company_name);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Company Name Changed', 'The company name was changed to ' . $company_name . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the company name, where isSet is SET
                    $sql = "UPDATE settings SET company_name = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $company_name);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Company Name Changed', 'The company name was changed to ' . $company_name . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get Company Logo
     *
     * Get the company logo from the settings table
     *
     * @return int //the logo media id
     */
    public function getCompanyLogo()
    {
        //SQL statement to get the company logo
        $sql = "SELECT company_logo FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the company_logo is set or not
                    if (isset($row['company_logo'])) {
                        //Return the company_logo
                        return intval($row['company_logo']);
                    } else {
                        //Return an empty string
                        return intval(null);
                    }
                } else {
                    //Return an empty string
                    return intval(null);
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
     * Set Company Logo
     *
     * Set the company logo in the settings table
     *
     * @param int $company_logo //the company logo media id
     * @return bool //true if the company logo was set, false if not
     */
    public function setCompanyLogo($company_logo = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the company logo is set in the settings table already
                if ($this->getCompanyLogo() != '' || $this->getCompanyLogo() != null) {
                    //SQL statement to update the company logo
                    $sql = "UPDATE settings SET company_logo = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('i', $company_logo);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Company Logo Changed', 'The company logo was changed.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the company logo, where isSet is SET
                    $sql = "UPDATE settings SET company_logo = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('i', $company_logo);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Company Logo Changed', 'The company logo was changed.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                }
            }
        } else {
            //log the error
            error_log('Error: The database connection is not set.');
            //throw an exception
            throw new Exception("The database connection is not set.");
        }
    }

    //Contact Information

    /**
     * Get Company Address
     *
     * Get the company address from the settings table
     *
     * @return string //the address string
     */
    public function getCompanyAddress()
    {
        //SQL statement to get the company address
        $sql = "SELECT company_address FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the company_address is set or not
                    if (isset($row['company_address'])) {
                        //Return the company_address
                        return $row['company_address'];
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
     * Set Company Address
     *
     * Set the company address in the settings table
     *
     * @param string $company_address //the company address
     * @return bool //true if the company address was set, false if not
     */
    public function setCompanyAddress($company_address = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the company address is set in the settings table already
                if ($this->getCompanyAddress() != '' || $this->getCompanyAddress() != null) {
                    //SQL statement to update the company address
                    $sql = "UPDATE settings SET company_address = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $company_address);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Company Address Changed', 'The company address was changed to ' . $company_address . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the company address, where isSet is SET
                    $sql = "UPDATE settings SET company_address = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $company_address);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Company Address Changed', 'The company address was changed to ' . $company_address . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get Company City
     *
     * Get the company city from the settings table
     *
     * @return string //the city string
     */
    public function getCompanyCity()
    {
        //SQL statement to get the company city
        $sql = "SELECT company_city FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the company_city is set or not
                    if (isset($row['company_city'])) {
                        //Return the company_city
                        return $row['company_city'];
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
     * Set Company City
     *
     * Set the company city in the settings table
     *
     * @param string $company_city //the company city
     * @return bool //true if the company city was set, false if not
     */
    public function setCompanyCity($company_city = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the company city is set in the settings table already
                if ($this->getCompanyCity() != '' || $this->getCompanyCity() != null) {
                    //SQL statement to update the company city
                    $sql = "UPDATE settings SET company_city = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $company_city);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Company City Changed', 'The company city was changed to ' . $company_city . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the company city, where isSet is SET
                    $sql = "UPDATE settings SET company_city = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $company_city);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Company City Changed', 'The company city was changed to ' . $company_city . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get Company State
     *
     * Get the company state from the settings table
     *
     * @return string //the state string
     */
    public function getCompanyState()
    {
        //SQL statement to get the company state
        $sql = "SELECT company_state FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the company_state is set or not
                    if (isset($row['company_state'])) {
                        //Return the company_state
                        return $row['company_state'];
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
     * Set Company State
     *
     * Set the company state in the settings table
     *
     * @param string $company_state //the company state
     * @return bool //true if the company state was set, false if not
     */
    public function setCompanyState($company_state = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the company state is set in the settings table already
                if ($this->getCompanyState() != '' || $this->getCompanyState() != null) {
                    //SQL statement to update the company state
                    $sql = "UPDATE settings SET company_state = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $company_state);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Company State Changed', 'The company state was changed to ' . $company_state . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the company state, where isSet is SET
                    $sql = "UPDATE settings SET company_state = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $company_state);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Company State Changed', 'The company state was changed to ' . $company_state . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get Company Zip
     *
     * Get the company zip from the settings table
     *
     * @return string //the zip string
     */
    public function getCompanyZip()
    {
        //SQL statement to get the company zip
        $sql = "SELECT company_zip FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the company_zip is set or not
                    if (isset($row['company_zip'])) {
                        //Return the company_zip
                        return $row['company_zip'];
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
     * Set Company Zip
     *
     * Set the company zip in the settings table
     *
     * @param string $company_zip //the company zip
     * @return bool //true if the company zip was set, false if not
     */
    public function setCompanyZip($company_zip = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the company zip is set in the settings table already
                if ($this->getCompanyZip() != '' || $this->getCompanyZip() != null) {
                    //SQL statement to update the company zip
                    $sql = "UPDATE settings SET company_zip = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $company_zip);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Company Zip Changed', 'The company zip was changed to ' . $company_zip . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the company zip, where isSet is SET
                    $sql = "UPDATE settings SET company_zip = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $company_zip);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Company Zip Changed', 'The company zip was changed to ' . $company_zip . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get Formatted Company Address
     * Get the formatted company address from the values in the settings table
     *
     * @return string //the formatted address string
     */
    public function getFormattedCompanyAddress()
    {
        //Get the company address
        $address = $this->getCompanyAddress();

        //Get the company city
        $city = $this->getCompanyCity();

        //Get the company state
        $state = $this->getCompanyState();

        //Get the company zip
        $zip = $this->getCompanyZip();

        //Check if the address, city, state, and zip are set
        if ($address != '' && $city != '' && $state != '' && $zip != '') {
            //Return the formatted address
            return $address . ', ' . $city . ', ' . $state . ' ' . $zip;
        } else {
            //Return an empty string
            return '';
        }
    }

    /**
     * Get Company Phone
     *
     * Get the company phone from the settings table
     *
     * @return string //the phone string
     */
    public function getCompanyPhone()
    {
        //SQL statement to get the company phone
        $sql = "SELECT company_phone FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the company_phone is set or not
                    if (isset($row['company_phone'])) {
                        //Return the company_phone
                        return $row['company_phone'];
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
     * Set Company Phone
     *
     * Set the company phone in the settings table
     *
     * @param string $company_phone //the company phone
     * @return bool //true if the company phone was set, false if not
     */
    public function setCompanyPhone($company_phone = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the company phone is set in the settings table already
                if ($this->getCompanyPhone() != '' || $this->getCompanyPhone() != null) {
                    //SQL statement to update the company phone
                    $sql = "UPDATE settings SET company_phone = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $company_phone);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Company Phone Changed', 'The company phone was changed to ' . $company_phone . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the company phone, where isSet is SET
                    $sql = "UPDATE settings SET company_phone = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $company_phone);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Company Phone Changed', 'The company phone was changed to ' . $company_phone . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get Company URL
     *
     * Get the company URL from the settings table
     *
     * @return string //the URL string
     */
    public function getCompanyURL()
    {
        //SQL statement to get the company URL
        $sql = "SELECT company_url FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the company_url is set or not
                    if (isset($row['company_url'])) {
                        //Return the company_url
                        return $row['company_url'];
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
     * Set Company URL
     *
     * Set the company URL in the settings table
     *
     * @param string $company_url //the company URL
     * @return bool //true if the company URL was set, false if not
     */
    public function setCompanyURL($company_url = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the company URL is set in the settings table already
                if ($this->getCompanyURL() != '' || $this->getCompanyURL() != null) {
                    //SQL statement to update the company URL
                    $sql = "UPDATE settings SET company_url = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $company_url);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Company URL Changed', 'The company URL was changed to ' . $company_url . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the company URL, where isSet is SET
                    $sql = "UPDATE settings SET company_url = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $company_url);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Company URL Changed', 'The company URL was changed to ' . $company_url . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                }
            }
        } else {
            //log the error
            error_log('Error: The database connection is not set.');
            //throw an exception
            throw new Exception("The database connection is not set.");
        }
    }

    //MAIL Settings

    /**
     * Get the Mailer Type
     *
     * Get the mailer from the settings table
     *
     * @return string //the mailer host string
     */
    public function getMailerType()
    {
        //SQL statement to get the mailer type
        $sql = "SELECT mail_mailer FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the mailer_type is set or not
                    if (isset($row['mail_mailer'])) {
                        //Return the mailer_type
                        return $row['mail_mailer'];
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
     * Set the Mailer Type
     *
     * Set the mailer type in the settings table
     *
     * @param string $mailer_type //the mailer type
     * @return bool //true if the mailer type was set, false if not
     */
    public function setMailerType($mailer_type = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the mailer type is set in the settings table already
                if ($this->getMailerType() != '' || $this->getMailerType() != null) {
                    //SQL statement to update the mailer type
                    $sql = "UPDATE settings SET mail_mailer = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $mailer_type);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Mailer Type Changed', 'The mailer type was changed to ' . $mailer_type . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the mailer type, where isSet is SET
                    $sql = "UPDATE settings SET mail_mailer = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $mailer_type);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Mailer Type Changed', 'The mailer type was changed to ' . $mailer_type . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get the Mailer Host
     *
     * Get the mailer host from the settings table
     *
     * @return string //the mailer host string
     */
    public function getMailerHost()
    {
        //SQL statement to get the mailer host
        $sql = "SELECT mail_host FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the mailer_host is set or not
                    if (isset($row['mail_host'])) {
                        //Return the mailer_host
                        return $row['mail_host'];
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
     * Set the Mailer Host
     *
     * Set the mailer host in the settings table
     *
     * @param string $mailer_host //the mailer host
     * @return bool //true if the mailer host was set, false if not
     */
    public function setMailerHost($mailer_host = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the mailer host is set in the settings table already
                if ($this->getMailerHost() != '' || $this->getMailerHost() != null) {
                    //SQL statement to update the mailer host
                    $sql = "UPDATE settings SET mail_host = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $mailer_host);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Mailer Host Changed', 'The mailer host was changed to ' . $mailer_host . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the mailer host, where isSet is SET
                    $sql = "UPDATE settings SET mail_host = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $mailer_host);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Mailer Host Changed', 'The mailer host was changed to ' . $mailer_host . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get the Mailer Port
     *
     * Get the mailer port from the settings table
     *
     * @return string //the mailer port string
     */
    public function getMailerPort()
    {
        //SQL statement to get the mailer port
        $sql = "SELECT mail_port FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the mailer_port is set or not
                    if (isset($row['mail_port'])) {
                        //Return the mailer_port
                        return $row['mail_port'];
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
     * Set the Mailer Port
     *
     * Set the mailer port in the settings table
     *
     * @param string $mailer_port //the mailer port
     * @return bool //true if the mailer port was set, false if not
     */
    public function setMailerPort($mailer_port = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the mailer port is set in the settings table already
                if ($this->getMailerPort() != '' || $this->getMailerPort() != null) {
                    //SQL statement to update the mailer port
                    $sql = "UPDATE settings SET mail_port = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('i', $mailer_port);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Mailer Port Changed', 'The mailer port was changed to ' . $mailer_port . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the mailer port, where isSet is SET
                    $sql = "UPDATE settings SET mail_port = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('i', $mailer_port);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Mailer Port Changed', 'The mailer port was changed to ' . $mailer_port . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get the Mailer Username
     *
     * Get the mailer username from the settings table
     *
     * @return string //the mailer username string
     */
    public function getMailerUsername()
    {
        //SQL statement to get the mailer username
        $sql = "SELECT mail_username FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the mailer_username is set or not
                    if (isset($row['mail_username'])) {
                        //Return the mailer_username
                        return $row['mail_username'];
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
     * Set the Mailer Username
     *
     * Set the mailer username in the settings table
     *
     * @param string $mailer_username //the mailer username
     * @return bool //true if the mailer username was set, false if not
     */
    public function setMailerUsername($mailer_username = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the mailer username is set in the settings table already
                if ($this->getMailerUsername() != '' || $this->getMailerUsername() != null) {
                    //SQL statement to update the mailer username
                    $sql = "UPDATE settings SET mail_username = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $mailer_username);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Mailer Username Changed', 'The mailer username was changed to ' . $mailer_username . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the mailer username, where isSet is SET
                    $sql = "UPDATE settings SET mail_username = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $mailer_username);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Mailer Username Changed', 'The mailer username was changed to ' . $mailer_username . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get the Mailer Password
     *
     * Get the mailer password from the settings table
     *
     * @return string //the mailer password string
     */
    public function getMailerPassword()
    {
        //SQL statement to get the mailer password
        $sql = "SELECT mail_password FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the mailer_password is set or not
                    if (isset($row['mail_password'])) {
                        //if OPENSSL is installed, decrypt the password
                        if (OPENSSL_INSTALLED) {
                            //Decrypt the password
                            $decrypted_password = openssl_decrypt($row['mail_password'], 'AES-128-ECB', MAILER_PASSWORD_ENCRYPTION_KEY);
                        } else {
                            //store the password as plain text
                            $decrypted_password = $row['mail_password'];
                        }
                        //Return the mailer_password
                        return $decrypted_password;
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
     * Set the Mailer Password
     *
     * Set the mailer password in the settings table
     *
     * @param string $mailer_password //the mailer password
     * @return bool //true if the mailer password was set, false if not
     */
    public function setMailerPassword($mailer_password = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the mailer password is set in the settings table already
                if ($this->getMailerPassword() != '' || $this->getMailerPassword() != null) {
                    //SQL statement to update the mailer password
                    $sql = "UPDATE settings SET mail_password = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //if OPENSSL is installed, encrypt the password
                    if (OPENSSL_INSTALLED) {
                        //Encrypt the password
                        $encrypted_password = openssl_encrypt($mailer_password, 'AES-128-ECB', MAILER_PASSWORD_ENCRYPTION_KEY);
                    } else {
                        //store the password as plain text
                        $encrypted_password = $mailer_password;
                    }

                    //set the password
                    $password = $encrypted_password;

                    //Bind the parameters
                    $role_statement->bind_param('s', $password);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Mailer Password Changed', 'The mailer password was changed.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //if OPENSSL is installed, encrypt the password
                    if (OPENSSL_INSTALLED) {
                        //Encrypt the password
                        $encrypted_password = openssl_encrypt($mailer_password, 'AES-128-ECB', MAILER_PASSWORD_ENCRYPTION_KEY);
                    } else {
                        //store the password as plain text
                        $encrypted_password = $mailer_password;
                    }

                    //set the password
                    $password = $encrypted_password;

                    //SQL statement to update the mailer password, where isSet is SET
                    $sql = "UPDATE settings SET mail_password = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $password);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Mailer Password Changed', 'The mailer password was changed.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get the Mailer Encryption
     *
     * Get the mailer encryption from the settings table
     *
     * @return string //the mailer encryption string
     */
    public function getMailerEncryption()
    {
        //SQL statement to get the mailer encryption
        $sql = "SELECT mail_encryption FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the mailer_encryption is set or not
                    if (isset($row['mail_encryption'])) {
                        //Return the mailer_encryption
                        return $row['mail_encryption'];
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
     * Set the Mailer Encryption
     *
     * Set the mailer encryption in the settings table
     *
     * @param string $mailer_encryption //the mailer encryption
     * @return bool //true if the mailer encryption was set, false if not
     */
    public function setMailerEncryption($mailer_encryption = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the mailer encryption is set in the settings table already
                if ($this->getMailerEncryption() != '' || $this->getMailerEncryption() != null) {
                    //SQL statement to update the mailer encryption
                    $sql = "UPDATE settings SET mail_encryption = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $mailer_encryption);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Mailer Encryption Changed', 'The mailer encryption was changed to ' . $mailer_encryption . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the mailer encryption, where isSet is SET
                    $sql = "UPDATE settings SET mail_encryption = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $mailer_encryption);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Mailer Encryption Changed', 'The mailer encryption was changed to ' . $mailer_encryption . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get the Mailer From Address
     *
     * Get the mailer from address from the settings table
     *
     * @return string //the mailer from address string
     */
    public function getMailerFromAddress()
    {
        //SQL statement to get the mailer from address
        $sql = "SELECT mail_from_address FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the mailer_from_address is set or not
                    if (isset($row['mail_from_address'])) {
                        //Return the mailer_from_address
                        return $row['mail_from_address'];
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
     * Set the Mailer From Address
     *
     * Set the mailer from address in the settings table
     *
     * @param string $mailer_from_address //the mailer from address
     * @return bool //true if the mailer from address was set, false if not
     */
    public function setMailerFromAddress($mailer_from_address = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the mailer from address is set in the settings table already
                if ($this->getMailerFromAddress() != '' || $this->getMailerFromAddress() != null) {
                    //SQL statement to update the mailer from address
                    $sql = "UPDATE settings SET mail_from_address = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $mailer_from_address);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Mailer From Address Changed', 'The mailer from address was changed to ' . $mailer_from_address . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the mailer from address, where isSet is SET
                    $sql = "UPDATE settings SET mail_from_address = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $mailer_from_address);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Mailer From Address Changed', 'The mailer from address was changed to ' . $mailer_from_address . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get the Mailer From Name
     *
     * Get the mailer from name from the settings table
     *
     * @return string //the mailer from name string
     */
    public function getMailerFromName()
    {
        //SQL statement to get the mailer from name
        $sql = "SELECT mail_from_name FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the mailer_from_name is set or not
                    if (isset($row['mail_from_name'])) {
                        //Return the mailer_from_name
                        return $row['mail_from_name'];
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
     * Set the Mailer From Name
     *
     * Set the mailer from name in the settings table
     *
     * @param string $mailer_from_name //the mailer from name
     * @return bool //true if the mailer from name was set, false if not
     */
    public function setMailerFromName($mailer_from_name = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the mailer from name is set in the settings table already
                if ($this->getMailerFromName() != '' || $this->getMailerFromName() != null) {
                    //SQL statement to update the mailer from name
                    $sql = "UPDATE settings SET mail_from_name = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $mailer_from_name);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Mailer From Name Changed', 'The mailer from name was changed to ' . $mailer_from_name . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the mailer from name, where isSet is SET
                    $sql = "UPDATE settings SET mail_from_name = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $mailer_from_name);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Mailer From Name Changed', 'The mailer from name was changed to ' . $mailer_from_name . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get the Mailer Authentication Required Status
     *
     * Get the mailer authentication required status from the settings table
     *
     * @return bool //the mailer authentication required status bool
     */
    public function getMailerAuthRequired()
    {
        //SQL statement to get the mailer authentication required status
        $sql = "SELECT mail_auth_req FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the mailer_auth_required is set or not
                    if (isset($row['mail_auth_required'])) {
                        if ($row['mail_auth_required'] == 1) {
                            //Return true
                            return true;
                        } else {
                            //Return false
                            return false;
                        }
                    } else {
                        //Return false
                        return false;
                    }
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

    /**
     * Set the Mailer Authentication Required Status
     *
     * Set the mailer authentication required status in the settings table
     *
     * @param bool $mailer_auth_required //the mailer authentication required status
     * @return bool //true if the mailer authentication required status was set, false if not
     */
    public function setMailerAuthRequired($mailer_auth_required = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the mailer authentication required status is set in the settings table already
                if ($this->getMailerAuthRequired() != '' || $this->getMailerAuthRequired() != null) {
                    //SQL statement to update the mailer authentication required status
                    $sql = "UPDATE settings SET mail_auth_req = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    if ($mailer_auth_required) {
                        //set the status to 1
                        $status = 1;
                    } else {
                        //set the status to 0
                        $status = 0;
                    }

                    //Bind the parameters
                    $role_statement->bind_param('i', $status);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        if ($mailer_auth_required) {
                            //log the activity
                            $activity = new Activity();
                            $activity->logActivity(intval($_SESSION['user_id']), 'Mailer Authentication Required Status Changed', 'The mailer authentication required status was changed to' . $mailer_auth_required . '.');
                            //Return true
                            return true;
                        } else {
                            //Return false
                            return false;
                        }
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the mailer authentication required status, where isSet is SET
                    $sql = "UPDATE settings SET mail_auth_req = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    if ($mailer_auth_required) {
                        //set the status to 1
                        $status = 1;
                    } else {
                        //set the status to 0
                        $status = 0;
                    }

                    //Bind the parameters
                    $role_statement->bind_param('i', $status);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0 && $mailer_auth_required) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Mailer Authentication Required Status Changed', 'The mailer authentication required status was changed to' . $mailer_auth_required . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                }
            }
        } else {
            //log the error
            error_log('Error: The database connection is not set.');
            //throw an exception
            throw new Exception("The database connection is not set.");
        }
    }

    //Hotjar Settings

    /**
     * Get Hotjar Enabled Status
     * Get the hotjar enabled status from the settings table
     *
     * @return bool //the hotjar enabled status bool
     */
    public function getHotjarEnabled()
    {
        //SQL statement to get the hotjar enabled status
        $sql = "SELECT hotjar_enable FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the hotjar_enabled is set or not
                    if (isset($row['hotjar_enable'])) {
                        if ($row['hotjar_enable'] == 1) {
                            //Return true
                            return true;
                        } elseif ($row['hotjar_enable'] == 0) {
                            //Return false
                            return false;
                        } else {
                            //Return false
                            return false;
                        }
                    } else {
                        //Return false
                        return false;
                    }
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

    /**
     * Set Hotjar Enabled Status
     * Set the hotjar enabled status in the settings table
     *
     * @param bool $hotjar_enabled //the hotjar enabled status
     * @return bool //true if the hotjar enabled status was set, false if not
     */
    public function setHotjarEnabled($hotjar_enabled = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the hotjar enabled status is set in the settings table already
                if ($this->getHotjarEnabled() != '' || $this->getHotjarEnabled() != null) {
                    //SQL statement to update the hotjar enabled status
                    $sql = "UPDATE settings SET hotjar_enable = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    if ($hotjar_enabled) {
                        //set the status to 1
                        $status = 1;
                    } else {
                        //set the status to 0
                        $status = 0;
                    }

                    //Bind the parameters
                    $role_statement->bind_param('i', $status);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        if ($hotjar_enabled) {
                            //log the activity
                            $activity = new Activity();
                            $activity->logActivity(intval($_SESSION['user_id']), 'Hotjar Enabled Status Changed', 'The hotjar enabled status was changed to' . $hotjar_enabled . '.');
                            //Return true
                            return true;
                        } else {
                            //Return false
                            return false;
                        }
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the hotjar enabled status, where isSet is SET
                    $sql = "UPDATE settings SET hotjar_enable = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    if ($hotjar_enabled) {
                        //set the status to 1
                        $status = 1;
                    } else {
                        //set the status to 0
                        $status = 0;
                    }

                    //Bind the parameters
                    $role_statement->bind_param('i', $status);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0 && $hotjar_enabled) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Hotjar Enabled Status Changed', 'The hotjar enabled status was changed to' . $hotjar_enabled . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get Hotjar Site ID
     * Get the hotjar site ID from the settings table
     *
     * @return string //the hotjar site ID
     */
    public function getHotjarSiteID()
    {
        //SQL statement to get the hotjar site ID
        $sql = "SELECT hotjar_siteid FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the hotjar_site_id is set or not
                    if (isset($row['hotjar_siteid'])) {
                        //Return the hotjar_site_id
                        return $row['hotjar_siteid'];
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
     * Set Hotjar Site ID
     * Set the hotjar site ID in the settings table
     *
     * @param string $hotjar_site_id //the hotjar site ID
     * @return bool //true if the hotjar site ID was set, false if not
     */
    public function setHotjarSiteID($hotjar_site_id = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the hotjar site ID is set in the settings table already
                if ($this->getHotjarSiteID() != '' || $this->getHotjarSiteID() != null) {
                    //SQL statement to update the hotjar site ID
                    $sql = "UPDATE settings SET hotjar_siteid = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //convert the site ID to an integer
                    $hotjar_site_id = intval($hotjar_site_id);

                    //Bind the parameters
                    $role_statement->bind_param('i', $hotjar_site_id);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Hotjar Site ID Changed', 'The hotjar site ID was changed to ' . $hotjar_site_id . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the hotjar site ID, where isSet is SET
                    $sql = "UPDATE settings SET hotjar_siteid = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('s', $hotjar_site_id);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Hotjar Site ID Changed', 'The hotjar site ID was changed to ' . $hotjar_site_id . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Get Hotjar Version
     * Get the hotjar version from the settings table
     *
     * @return int|null //the hotjar version
     */
    public function getHotjarVersion(): ?int
    {
        //SQL statement to get the hotjar version
        $sql = "SELECT hotjar_version FROM settings WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Execute the statement
                $role_statement->execute();

                //Get the results
                $result = $role_statement->get_result();

                //Get the row
                $row = $result->fetch_assoc();

                //Check if the row exists
                if ($row) {
                    //check if the hotjar_version is set or not
                    if (isset($row['hotjar_version'])) {
                        //Return the hotjar_version
                        return intval($row['hotjar_version']);
                    } else {
                        //Return null
                        return null;
                    }
                } else {
                    //Return null
                    return null;
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
     * Set Hotjar Version
     * Set the hotjar version in the settings table
     *
     * @param int $hotjar_version //the hotjar version
     * @return bool //true if the hotjar version was set, false if not
     */
    public function setHotjarVersion($hotjar_version = null)
    {
        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Check if the hotjar version is set in the settings table already
                if ($this->getHotjarVersion() != '' || $this->getHotjarVersion() != null) {
                    //SQL statement to update the hotjar version
                    $sql = "UPDATE settings SET hotjar_version = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('i', $hotjar_version);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Hotjar Version Changed', 'The hotjar version was changed to ' . $hotjar_version . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
                } else {
                    //SQL statement to update the hotjar version, where isSet is SET
                    $sql = "UPDATE settings SET hotjar_version = ? WHERE isSet = 'SET'";

                    //Prepare the SQL statement for execution
                    $role_statement = $this->mysqli->prepare($sql);

                    //Bind the parameters
                    $role_statement->bind_param('i', $hotjar_version);

                    //Execute the statement
                    $role_statement->execute();

                    //Check if the statement was executed successfully
                    if ($role_statement->affected_rows > 0) {
                        //log the activity
                        $activity = new Activity();
                        $activity->logActivity(intval($_SESSION['user_id']), 'Hotjar Version Changed', 'The hotjar version was changed to ' . $hotjar_version . '.');
                        //Return true
                        return true;
                    } else {
                        //Return false
                        return false;
                    }
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
     * Search the database with a given search term or string
     *
     * @param string $searchTerm - the search term to search for
     *
     * @return array
     */
    public function search(string $searchTerm): array
    {
        //include the student class
        $studentData = new Student();

        //include the school class
        $schoolData = new School();

        //include the event class
        $eventData = new Event();

        //create an array to store the results
        $results = array(
            'students' => array(),
            'schools' => array(),
            'events' => array(),
            'jobs' => array(),
            'wildcard' => array(
                'students' => array(),
                'schools' => array(),
                'events' => array(),
                'jobs' => array()
            )
        );

        //search the students table
        $studentResults = $studentData->searchStudents($searchTerm);

        //search the schools table
        $schoolResults = $schoolData->searchSchools($searchTerm);

        //search the events table
        $eventResults = $eventData->searchEvents($searchTerm);

        //TODO: search the jobs table

        //loop through the student results
        foreach ($studentResults as $studentResult) {
            //add the student result to the results array
            array_push($results['students'], $studentResult);
        }

        //loop through the school results
        foreach ($schoolResults as $schoolResult) {
            //add the school result to the results array
            array_push($results['schools'], $schoolResult);
        }

        //loop through the event results
        foreach ($eventResults as $eventResult) {
            //add the event result to the results array
            array_push($results['events'], $eventResult);
        }

        //TODO: loop through the job results

        //set the wildcard search term, replacing spaces with % signs
        $wildcardSearchTerm = str_replace(' ', '%', $searchTerm);

        //search the students table
        $wildcardStudentResults = $studentData->searchStudents($wildcardSearchTerm);

        //search the schools table
        $wildcardSchoolResults = $schoolData->searchSchools($wildcardSearchTerm);

        //search the events table
        $wildcardEventResults = $eventData->searchEvents($wildcardSearchTerm);

        //TODO: search the jobs table

        //loop through the student results
        foreach ($wildcardStudentResults as $wildcardStudentResult) {
            //add the student result to the results array
            array_push($results['wildcard']['students'], $wildcardStudentResult);
        }

        //loop through the school results
        foreach ($wildcardSchoolResults as $wildcardSchoolResult) {
            //add the school result to the results array
            array_push($results['wildcard']['schools'], $wildcardSchoolResult);
        }

        //loop through the event results
        foreach ($wildcardEventResults as $wildcardEventResult) {
            //add the event result to the results array
            array_push($results['wildcard']['events'], $wildcardEventResult);
        }

        //TODO: loop through the wildcard job results

        //return the results array
        return $results;
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
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $stmt = $this->mysqli->prepare($sql);

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
        $stmt = $this->mysqli->prepare($sql);

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
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $stmt = $this->mysqli->prepare($sql);

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
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $stmt = $this->mysqli->prepare($sql);

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

    /**
     * Reset all of the settings to their default values
     *
     * @return bool //true if the settings were reset, false if not
     */
    public function resetSettings()
    {
        //SQL statement to reset the settings
        $sql = "UPDATE settings SET app_name = null, app_url = null, company_name = null, company_url = null, company_logo = null, company_address = null, company_phone = null, app_logo = null, contact_email = null, mail_host = null, mail_port = null, mail_username = null, mail_password = null, mail_encryption = null, mail_from_address = null, mail_from_name = null, mail_auth_req = null, privacy_policy = ?, terms_conditions = ?, hotjar_enable = null, hotjar_siteid = null, hotjar_version = null, WHERE isSet = 'SET'";

        //Check that mysqli is set
        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
                //throw an exception
                throw new Exception("Failed to connect to the database: (" . $this->mysqli->connect_errno . ")" . $this->mysqli->connect_error);
            } else {
                //Prepare the SQL statement for execution
                $stmt = $this->mysqli->prepare($sql);

                //set the privacy policy and terms and conditions to their default values
                $terms_conditions = strval(TERMS_CONDITIONS);
                $privacy_policy = strval(PRIVACY_POLICY);

                //Bind the parameters
                $stmt->bind_param('ss', $privacy_policy, $terms_conditions);

                //Execute the statement
                $stmt->execute();

                //Check if the statement was executed successfully
                if ($stmt->affected_rows > 0) {
                    //log the activity
                    $activity = new Activity();
                    $activity->logActivity(intval($_SESSION['user_id']), 'Settings Reset', 'The settings were reset to their default values.');
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
