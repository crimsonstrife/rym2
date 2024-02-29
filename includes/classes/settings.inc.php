<?php

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* Include the base application config file */
require_once(__DIR__ . '/../../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');

/**
 * The Settings Class
 *
 * Contains functions for adjusting system features and settings, extends the application class
 *
 * @package RYM2
 */
class Settings extends Application
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
     * Reset all of the settings to their default values
     *
     * @return bool //true if the settings were reset, false if not
     */
    public function resetSettings()
    {
        //SQL statement to reset the settings
        $sql = "UPDATE settings SET app_name = null, app_url = null, company_name = null, company_url = null, company_logo = null, company_address = null, company_phone = null, app_logo = null, contact_email = null, mail_host = null, mail_port = null, mail_username = null, mail_password = null, mail_encryption = null, mail_from_address = null, mail_from_name = null, mail_auth_req = null, privacy_policy = ?, terms_conditions = ?, ga_enable = null, ga_id = null, hotjar_enable = null, hotjar_id = null, hotjar_version = null, WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //set the privacy policy and terms and conditions to their default values
        $termsConditions = strval(TERMS_CONDITIONS) ?? null;
        $privacyPolicy = strval(PRIVACY_POLICY) ?? null;

        //Bind the parameters
        $stmt->bind_param('ss', $privacyPolicy, $termsConditions);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            //instance of the session class
            $session = new Session();
            //get the user id from the session
            $userID = intval($session->get('user_id')) ?? null;
            $activity->logActivity($userID, 'Settings Reset', 'The settings were reset to their default values.');
            //Return true
            return true;
        }

        //Return false
        return false;
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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //placeholder for the app name
        $appName = '';

        //Check if the row exists
        if ($row) {
            //check if the app_name is set or not
            if (isset($row['app_name'])) {
                //set the app name
                $appName = $row['app_name'];
            }
        }

        //Return the app name
        return $appName;
    }

    /**
     * Set Application Name
     *
     * Set the application name in the settings table
     *
     * @param string $appName //the application name
     * @return bool //true if the application name was set, false if not
     */
    public function setAppName($appName = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the application name is set in the settings table already
        if ($this->getAppName() != '' || $this->getAppName() != null) {
            //SQL statement to update the application name
            $sql = "UPDATE settings SET app_name = ? WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Bind the parameters
            $stmt->bind_param('s', $appName);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Application Name Changed', 'The application name was changed to ' . $appName . '.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }

        //SQL statement to update the application name, where isSet is SET
        $sql = "UPDATE settings SET app_name = ? WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters
        $stmt->bind_param('s', $appName);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Application Name Changed', 'The application name was changed to ' . $appName . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //placeholder for the app URL
        $appURL = '';

        //Check if the row exists
        if ($row) {
            //check if the app_url is set or not
            if (isset($row['app_url'])) {
                //set the app URL
                $appURL = $row['app_url'];
            }
        }

        //Return the app URL
        return $appURL;
    }

    /**
     * Set Application URL
     *
     * Set the application URL in the settings table
     *
     * @param string $appUrl //the application URL
     * @return bool //true if the application URL was set, false if not
     */
    public function setAppURL($appUrl = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the application URL is set in the settings table already
        if ($this->getAppURL() != '' || $this->getAppURL() != null) {
            //SQL statement to update the application URL
            $sql = "UPDATE settings SET app_url = $appUrl WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Application URL Changed', 'The application URL was changed to ' . $appUrl . '.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }

        //SQL statement to update the application URL, where isSet is SET
        $sql = "UPDATE settings SET app_url = $appUrl WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Application URL Changed', 'The application URL was changed to ' . $appUrl . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
    }

    /**
     * Get Application Logo
     * Get the application logo from the settings table
     *
     * @return ?int //the logo media id
     */
    public function getAppLogo(): ?int
    {
        //SQL statement to get the application logo
        $sql = "SELECT app_logo FROM settings WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //placeholder for the app logo id
        $appLogo = null;

        //Check if the row exists
        if ($row) {
            //check if the app_logo is set or not
            if (isset($row['app_logo'])) {
                //set the app logo
                $appLogo = intval($row['app_logo']);
            }
        }

        //Return the app logo
        return $appLogo;
    }

    /**
     * Set Application Logo
     *
     * Set the application logo in the settings table
     *
     * @param int $appLogo //the application logo media id
     * @return bool //true if the application logo was set, false if not
     */
    public function setAppLogo(int $appLogo = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the application logo is set in the settings table already
        if ($this->getAppLogo() != '' || $this->getAppLogo() != null) {
            //SQL statement to update the application logo
            $sql = "UPDATE settings SET app_logo = ? WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Bind the parameters
            $stmt->bind_param('i', $appLogo);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Application Logo Changed', 'The application logo was changed.');
                //Return true
                return true;
            }
            //Return false
            return false;
        }

        //SQL statement to update the application logo, where isSet is SET
        $sql = "UPDATE settings SET app_logo = ? WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters
        $stmt->bind_param('i', $appLogo);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Application Logo Changed', 'The application logo was changed.');
            //Return true
            return true;
        }

        //Return false
        return false;
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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //placeholder for the contact email
        $contactEmail = '';

        //Check if the row exists
        if ($row) {
            //check if the contact_email is set or not
            if (isset($row['contact_email'])) {
                //set the contact email
                $contactEmail = $row['contact_email'];
            }
        }

        //Return the contact email
        return $contactEmail;
    }

    /**
     * Set Contact Email
     *
     * Set the contact email in the settings table
     *
     * @param string $contactEmail //the contact email
     * @return bool //true if the contact email was set, false if not
     */
    public function setContactEmail($contactEmail = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the contact email is set in the settings table already
        if ($this->getContactEmail() != '' || $this->getContactEmail() != null) {
            //SQL statement to update the contact email
            $sql = "UPDATE settings SET contact_email = ? WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Bind the parameters
            $stmt->bind_param('s', $contactEmail);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Contact Email Changed', 'The contact email was changed to ' . $contactEmail . '.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }

        //SQL statement to update the contact email, where isSet is SET
        $sql = "UPDATE settings SET contact_email = ? WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters
        $stmt->bind_param('s', $contactEmail);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Contact Email Changed', 'The contact email was changed to ' . $contactEmail . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
    }
}

/**
 * Mailer Settings Class
 * The MailerSettings class contains methods for getting and setting mailer settings in the settings table
 */
class MailerSettings extends Settings
{
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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //placeholder for the mailer type
        $mailerType = '';

        //Check if the row exists
        if ($row) {
            //check if the mailer_type is set or not
            if (isset($row['mail_mailer'])) {
                //set the mailer type
                $mailerType = $row['mail_mailer'];
            }
        }

        //Return the mailer type
        return $mailerType;
    }

    /**
     * Set the Mailer Type
     *
     * Set the mailer type in the settings table
     *
     * @param string $mailerType //the mailer type
     * @return bool //true if the mailer type was set, false if not
     */
    public function setMailerType($mailerType = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the mailer type is set in the settings table already
        if ($this->getMailerType() != '' || $this->getMailerType() != null) {
            //SQL statement to update the mailer type
            $sql = "UPDATE settings SET mail_mailer = $mailerType WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Mailer Type Changed', 'The mailer type was changed to ' . $mailerType . '.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }
        //SQL statement to update the mailer type, where isSet is SET
        $sql = "UPDATE settings SET mail_mailer = $mailerType WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Mailer Type Changed', 'The mailer type was changed to ' . $mailerType . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the mailer host
        $mailerHost = '';

        //Check if the row exists
        if ($row) {
            //check if the mailer_host is set or not
            if (isset($row['mail_host'])) {
                //Set the mailer host
                $mailerHost = $row['mail_host'];
            }
        }

        //Return the mailer host
        return $mailerHost;
    }

    /**
     * Set the Mailer Host
     *
     * Set the mailer host in the settings table
     *
     * @param string $mailerHost //the mailer host
     * @return bool //true if the mailer host was set, false if not
     */
    public function setMailerHost($mailerHost = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the mailer host is set in the settings table already
        if ($this->getMailerHost() != '' || $this->getMailerHost() != null) {
            //SQL statement to update the mailer host
            $sql = "UPDATE settings SET mail_host = $mailerHost WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Mailer Host Changed', 'The mailer host was changed to ' . $mailerHost . '.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }
        //SQL statement to update the mailer host, where isSet is SET
        $sql = "UPDATE settings SET mail_host = $mailerHost WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Mailer Host Changed', 'The mailer host was changed to ' . $mailerHost . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the mailer port
        $mailerPort = '';

        //Check if the row exists
        if ($row) {
            //check if the mailer_port is set or not
            if (isset($row['mail_port'])) {
                //Set the mailer port
                $mailerPort = $row['mail_port'];
            }
        }

        //Return the mailer port
        return $mailerPort;
    }

    /**
     * Set the Mailer Port
     *
     * Set the mailer port in the settings table
     *
     * @param string $mailerPort //the mailer port
     * @return bool //true if the mailer port was set, false if not
     */
    public function setMailerPort($mailerPort = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the mailer port is set in the settings table already
        if ($this->getMailerPort() != '' || $this->getMailerPort() != null) {
            //SQL statement to update the mailer port
            $sql = "UPDATE settings SET mail_port = $mailerPort WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Mailer Port Changed', 'The mailer port was changed to ' . $mailerPort . '.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }
        //SQL statement to update the mailer port, where isSet is SET
        $sql = "UPDATE settings SET mail_port = $mailerPort WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Mailer Port Changed', 'The mailer port was changed to ' . $mailerPort . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the mailer username
        $mailerUsername = '';

        //Check if the row exists
        if ($row) {
            //check if the mailer_username is set or not
            if (isset($row['mail_username'])) {
                //Set the mailer username
                $mailerUsername = $row['mail_username'];
            }
        }

        //Return the mailer username
        return $mailerUsername;
    }

    /**
     * Set the Mailer Username
     *
     * Set the mailer username in the settings table
     *
     * @param string $mailerUsername //the mailer username
     * @return bool //true if the mailer username was set, false if not
     */
    public function setMailerUsername($mailerUsername = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the mailer username is set in the settings table already
        if ($this->getMailerUsername() != '' || $this->getMailerUsername() != null) {
            //SQL statement to update the mailer username
            $sql = "UPDATE settings SET mail_username = $mailerUsername WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Mailer Username Changed', 'The mailer username was changed to ' . $mailerUsername . '.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }
        //SQL statement to update the mailer username, where isSet is SET
        $sql = "UPDATE settings SET mail_username = $mailerUsername WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Mailer Username Changed', 'The mailer username was changed to ' . $mailerUsername . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the mailer password
        $mailerPassword = '';

        //Check if the row exists
        if ($row) {
            //check if the mailer_password is set or not
            if (isset($row['mail_password'])) {
                //if OPENSSL is installed, decrypt the password
                if (OPENSSL_INSTALLED) {
                    //Decrypt the password
                    $mailerPassword = openssl_decrypt($row['mail_password'], 'AES-128-ECB', MAILER_PASSWORD_ENCRYPTION_KEY);

                    //return the decrypted password
                    return $mailerPassword;
                }
                //store the password as plain text
                $mailerPassword = $row['mail_password'];
            }
        }

        //Return the mailer password
        return $mailerPassword;
    }

    /**
     * Set the Mailer Password
     *
     * Set the mailer password in the settings table
     *
     * @param string $mailerPassword //the mailer password
     * @return bool //true if the mailer password was set, false if not
     */
    public function setMailerPassword($mailerPassword = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the mailer password is set in the settings table already
        if ($this->getMailerPassword() != '' || $this->getMailerPassword() != null) {
            //SQL statement to update the mailer password
            $sql = "UPDATE settings SET mail_password = ? WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //if OPENSSL is installed, encrypt the password
            if (OPENSSL_INSTALLED) {
                //Encrypt the password
                $password = openssl_encrypt($mailerPassword, 'AES-256-CBC', MAILER_PASSWORD_ENCRYPTION_KEY);
            }

            if (!OPENSSL_INSTALLED) {
                //store the password as plain text
                $password = $mailerPassword;
            }

            //Bind the parameters
            $stmt->bind_param('s', $password);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Mailer Password Changed', 'The mailer password was changed.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }
        //if OPENSSL is installed, encrypt the password
        if (OPENSSL_INSTALLED) {
            //Encrypt the password
            $password = openssl_encrypt($mailerPassword, 'AES-256-CBC', MAILER_PASSWORD_ENCRYPTION_KEY);
        }

        if (!OPENSSL_INSTALLED) {
            //store the password as plain text
            $password = $mailerPassword;
        }

        //SQL statement to update the mailer password, where isSet is SET
        $sql = "UPDATE settings SET mail_password = ? WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters
        $stmt->bind_param('s', $password);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Mailer Password Changed', 'The mailer password was changed.');
            //Return true
            return true;
        }

        //Return false
        return false;
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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the mailer encryption
        $mailerEncryption = '';

        //Check if the row exists
        if ($row) {
            //check if the mailer_encryption is set or not
            if (isset($row['mail_encryption'])) {
                //Set the mailer encryption
                $mailerEncryption = $row['mail_encryption'];
            }
        }

        //Return the mailer encryption
        return $mailerEncryption;
    }

    /**
     * Set the Mailer Encryption
     *
     * Set the mailer encryption in the settings table
     *
     * @param string $mailerEncryption //the mailer encryption
     * @return bool //true if the mailer encryption was set, false if not
     */
    public function setMailerEncryption($mailerEncryption = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the mailer encryption is set in the settings table already
        if ($this->getMailerEncryption() != '' || $this->getMailerEncryption() != null) {
            //SQL statement to update the mailer encryption
            $sql = "UPDATE settings SET mail_encryption = $mailerEncryption WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Mailer Encryption Changed', 'The mailer encryption was changed to ' . $mailerEncryption . '.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }
        //SQL statement to update the mailer encryption, where isSet is SET
        $sql = "UPDATE settings SET mail_encryption = $mailerEncryption WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Mailer Encryption Changed', 'The mailer encryption was changed to ' . $mailerEncryption . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the mailer from address
        $fromAddress = '';

        //Check if the row exists
        if ($row) {
            //check if the mailer_from_address is set or not
            if (isset($row['mail_from_address'])) {
                //Set the mailer from address
                $fromAddress = $row['mail_from_address'];
            }
        }

        //Return the mailer from address
        return $fromAddress;
    }

    /**
     * Set the Mailer From Address
     *
     * Set the mailer from address in the settings table
     *
     * @param string $fromAddress //the mailer from address
     * @return bool //true if the mailer from address was set, false if not
     */
    public function setMailerFromAddress($fromAddress = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the mailer from address is set in the settings table already
        if ($this->getMailerFromAddress() != '' || $this->getMailerFromAddress() != null) {
            //SQL statement to update the mailer from address
            $sql = "UPDATE settings SET mail_from_address = $fromAddress WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Mailer From Address Changed', 'The mailer from address was changed to ' . $fromAddress . '.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }
        //SQL statement to update the mailer from address, where isSet is SET
        $sql = "UPDATE settings SET mail_from_address = $fromAddress WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Mailer From Address Changed', 'The mailer from address was changed to ' . $fromAddress . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the mailer from name
        $fromName = '';

        //Check if the row exists
        if ($row) {
            //check if the mailer_from_name is set or not
            if (isset($row['mail_from_name'])) {
                //Set the mailer from name
                $fromName = $row['mail_from_name'];
            }
        }

        //Return the mailer from name
        return $fromName;
    }

    /**
     * Set the Mailer From Name
     *
     * Set the mailer from name in the settings table
     *
     * @param string $fromName //the mailer from name
     * @return bool //true if the mailer from name was set, false if not
     */
    public function setMailerFromName($fromName = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the mailer from name is set in the settings table already
        if ($this->getMailerFromName() != '' || $this->getMailerFromName() != null) {
            //SQL statement to update the mailer from name
            $sql = "UPDATE settings SET mail_from_name = $fromName WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Mailer From Name Changed', 'The mailer from name was changed to ' . $fromName . '.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }
        //SQL statement to update the mailer from name, where isSet is SET
        $sql = "UPDATE settings SET mail_from_name = $fromName WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Mailer From Name Changed', 'The mailer from name was changed to ' . $fromName . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the mailer authentication required status
        $isAuthRequired = false;

        //Check if the row exists
        if ($row) {
            //check if the mailer_auth_required is set or not
            if (isset($row['mail_auth_required'])) {
                if ($row['mail_auth_required'] == 1) {
                    //Set the mailer authentication required status
                    $isAuthRequired = true;
                }
            }
        }

        //Return the mailer authentication required status
        return $isAuthRequired;
    }

    /**
     * Set the Mailer Authentication Required Status
     *
     * Set the mailer authentication required status in the settings table
     *
     * @param bool $isAuthRequired //the mailer authentication required status
     * @return bool //true if the mailer authentication required status was set, false if not
     */
    public function setMailerAuthRequired($isAuthRequired = null)
    {
        //Check if the mailer authentication required status is set in the settings table already
        if ($this->isMailerAuthRequiredSet()) {
            //Get the mailer authentication required status
            $status = $this->getMailerAuthRequiredStatus($isAuthRequired);

            //Update the mailer authentication required status
            $result = $this->updateMailerAuthRequiredStatus($status, $isAuthRequired);

            //Return the result
            return $result;
        }

        return false;
    }

    /**
     * Check if the Mailer Authentication Required Status is Set
     *
     * Check if the mailer authentication required status is set in the settings table
     *
     * @return bool //true if the mailer authentication required status is set, false if not
     */
    private function isMailerAuthRequiredSet()
    {
        return ($this->getMailerAuthRequired() != '' || $this->getMailerAuthRequired() != null);
    }

    /**
     * Get the Mailer Authentication Required Status
     *
     * Get the mailer authentication required status from the settings table
     *
     * @param bool $isAuthRequired //the mailer authentication required status
     * @return int //the mailer authentication required status int
     */
    private function getMailerAuthRequiredStatus($isAuthRequired)
    {
        return $isAuthRequired ? 1 : 0;
    }

    /**
     * Update the Mailer Authentication Required Status
     *
     * Update the mailer authentication required status in the settings table
     *
     * @param int $status //the mailer authentication required status int
     * @param bool $isAuthRequired //the mailer authentication required status
     * @return bool //true if the mailer authentication required status was updated, false if not
     */
    private function updateMailerAuthRequiredStatus($status, $isAuthRequired)
    {
        //SQL statement to update the mailer authentication required status
        $sql = "UPDATE settings SET mail_auth_req = $status WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully and if the mailer authentication required status is set
        if (
            $stmt->affected_rows > 0 && $isAuthRequired
        ) {
            $activity = new Activity();
            //instance of the session class
            $session = new Session();
            //get the user id from the session
            $userID = intval($session->get('user_id')) ?? null;
            $activity->logActivity($userID, 'Mailer Authentication Required Status Changed', 'The mailer authentication required status was changed to' . $isAuthRequired . '.');
            return true;
        }

        return false;
    }
}

/**
 * Company Settings Class
 *
 */
class CompanySettings extends Settings
{
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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the company name
        $companyName = '';

        //Check if the row exists
        if ($row) {
            //check if the company_name is set or not
            if (isset($row['company_name'])) {
                //Set the company name
                $companyName = $row['company_name'];
            }
        }

        //Return the company name
        return $companyName;
    }

    /**
     * Set Company Name
     *
     * Set the company name in the settings table
     *
     * @param string $companyName //the company name
     * @return bool //true if the company name was set, false if not
     */
    public function setCompanyName($companyName = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the company name is set in the settings table already
        if ($this->getCompanyName() != '' || $this->getCompanyName() != null) {
            //SQL statement to update the company name
            $sql = "UPDATE settings SET company_name = $companyName WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Company Name Changed', 'The company name was changed to ' . $companyName . '.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }
        //SQL statement to update the company name, where isSet is SET
        $sql = "UPDATE settings SET company_name = $companyName WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Company Name Changed', 'The company name was changed to ' . $companyName . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
    }

    /**
     * Get Company Logo
     *
     * Get the company logo from the settings table
     *
     * @return ?int //the logo media id
     */
    public function getCompanyLogo(): ?int
    {
        //SQL statement to get the company logo
        $sql = "SELECT company_logo FROM settings WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the company logo
        $companyLogo = null;

        //Check if the row exists
        if ($row) {
            //check if the company_logo is set or not
            if (isset($row['company_logo'])) {
                //Set the company logo
                $companyLogo = $row['company_logo'];
            }
        }

        //Return the company logo
        return $companyLogo;
    }

    /**
     * Set Company Logo
     *
     * Set the company logo in the settings table
     *
     * @param int $companyLogo //the company logo media id
     * @return bool //true if the company logo was set, false if not
     */
    public function setCompanyLogo($companyLogo = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the company logo is set in the settings table already
        if ($this->getCompanyLogo() != '' || $this->getCompanyLogo() != null) {
            //SQL statement to update the company logo
            $sql = "UPDATE settings SET company_logo = $companyLogo WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Company Logo Changed', 'The company logo was changed.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }
        //SQL statement to update the company logo, where isSet is SET
        $sql = "UPDATE settings SET company_logo = $companyLogo WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Company Logo Changed', 'The company logo was changed.');
            //Return true
            return true;
        }

        //Return false
        return false;
    }

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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the company address
        $companyAddress = '';

        //Check if the row exists
        if ($row) {
            //check if the company_address is set or not
            if (isset($row['company_address'])) {
                //Set the company address
                $companyAddress = $row['company_address'];
            }
        }

        //Return the company address
        return $companyAddress;
    }

    /**
     * Set Company Address
     *
     * Set the company address in the settings table
     *
     * @param string $companyAddress //the company address
     * @return bool //true if the company address was set, false if not
     */
    public function setCompanyAddress($companyAddress = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the company address is set in the settings table already
        if ($this->getCompanyAddress() != '' || $this->getCompanyAddress() != null) {
            //SQL statement to update the company address
            $sql = "UPDATE settings SET company_address = $companyAddress WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Company Address Changed', 'The company address was changed to ' . $companyAddress . '.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }
        //SQL statement to update the company address, where isSet is SET
        $sql = "UPDATE settings SET company_address = $companyAddress WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Company Address Changed', 'The company address was changed to ' . $companyAddress . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the company city
        $companyCity = '';

        //Check if the row exists
        if ($row) {
            //check if the company_city is set or not
            if (isset($row['company_city'])) {
                //Set the company city
                $companyCity = $row['company_city'];
            }
        }

        //Return the company city
        return $companyCity;
    }

    /**
     * Set Company City
     *
     * Set the company city in the settings table
     *
     * @param string $companyCity //the company city
     * @return bool //true if the company city was set, false if not
     */
    public function setCompanyCity($companyCity = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the company city is set in the settings table already
        if ($this->getCompanyCity() != '' || $this->getCompanyCity() != null) {
            //SQL statement to update the company city
            $sql = "UPDATE settings SET company_city = $companyCity WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Company City Changed', 'The company city was changed to ' . $companyCity . '.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }
        //SQL statement to update the company city, where isSet is SET
        $sql = "UPDATE settings SET company_city = $companyCity WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Company City Changed', 'The company city was changed to ' . $companyCity . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the company state
        $companyState = '';

        //Check if the row exists
        if ($row) {
            //check if the company_state is set or not
            if (isset($row['company_state'])) {
                //Set the company state
                $companyState = $row['company_state'];
            }
        }

        //Return the company state
        return $companyState;
    }

    /**
     * Set Company State
     *
     * Set the company state in the settings table
     *
     * @param string $companyState //the company state
     * @return bool //true if the company state was set, false if not
     */
    public function setCompanyState($companyState = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the company state is set in the settings table already
        if ($this->getCompanyState() != '' || $this->getCompanyState() != null) {
            //SQL statement to update the company state
            $sql = "UPDATE settings SET company_state = $companyState WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Company State Changed', 'The company state was changed to ' . $companyState . '.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }
        //SQL statement to update the company state, where isSet is SET
        $sql = "UPDATE settings SET company_state = $companyState WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Company State Changed', 'The company state was changed to ' . $companyState . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the company zip
        $companyZip = '';

        //Check if the row exists
        if ($row) {
            //check if the company_zip is set or not
            if (isset($row['company_zip'])) {
                //Set the company zip
                $companyZip = $row['company_zip'];
            }
        }

        //Return the company zip
        return $companyZip;
    }

    /**
     * Set Company Zip
     *
     * Set the company zip in the settings table
     *
     * @param string $companyZip //the company zip
     * @return bool //true if the company zip was set, false if not
     */
    public function setCompanyZip($companyZip = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the company zip is set in the settings table already
        if ($this->getCompanyZip() != '' || $this->getCompanyZip() != null) {
            //SQL statement to update the company zip
            $sql = "UPDATE settings SET company_zip = $companyZip WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Company Zip Changed', 'The company zip was changed to ' . $companyZip . '.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }
        //SQL statement to update the company zip, where isSet is SET
        $sql = "UPDATE settings SET company_zip = $companyZip WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Company Zip Changed', 'The company zip was changed to ' . $companyZip . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the company phone
        $companyPhone = '';

        //Check if the row exists
        if ($row) {
            //check if the company_phone is set or not
            if (isset($row['company_phone'])) {
                //Set the company phone
                $companyPhone = $row['company_phone'];
            }
        }

        //Return the company phone
        return $companyPhone;
    }

    /**
     * Set Company Phone
     *
     * Set the company phone in the settings table
     *
     * @param string $companyPhone //the company phone
     * @return bool //true if the company phone was set, false if not
     */
    public function setCompanyPhone($companyPhone = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the company phone is set in the settings table already
        if ($this->getCompanyPhone() != '' || $this->getCompanyPhone() != null) {
            //SQL statement to update the company phone
            $sql = "UPDATE settings SET company_phone = $companyPhone WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Company Phone Changed', 'The company phone was changed to ' . $companyPhone . '.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }
        //SQL statement to update the company phone, where isSet is SET
        $sql = "UPDATE settings SET company_phone = $companyPhone WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Company Phone Changed', 'The company phone was changed to ' . $companyPhone . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
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

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the company URL
        $companyURL = '';

        //Check if the row exists
        if ($row) {
            //check if the company_url is set or not
            if (isset($row['company_url'])) {
                //Set the company URL
                $companyURL = $row['company_url'];
            }
        }

        //Return the company URL
        return $companyURL;
    }

    /**
     * Set Company URL
     *
     * Set the company URL in the settings table
     *
     * @param string $companyURL //the company URL
     * @return bool //true if the company URL was set, false if not
     */
    public function setCompanyURL($companyURL = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the company URL is set in the settings table already
        if ($this->getCompanyURL() != '' || $this->getCompanyURL() != null) {
            //SQL statement to update the company URL
            $sql = "UPDATE settings SET company_url = $companyURL WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, 'Company URL Changed', 'The company URL was changed to ' . $companyURL . '.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }
        //SQL statement to update the company URL, where isSet is SET
        $sql = "UPDATE settings SET company_url = $companyURL WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, 'Company URL Changed', 'The company URL was changed to ' . $companyURL . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
    }
}

/**
 * Tracker Settings Class
 * Functions to get and set the tracker settings
 * e.g. Google Analytics, Hotjar, etc.
 *
 */
class TrackerSettings extends Settings
{
    //The tracker id e.g. UA-123456789-1 for google analytics or 1234567 for hotjar
    protected $trackerID;
    //The tracker name to identify the tracker e.g. Google Analytics, Hotjar, etc.
    protected $trackerName;
    //The tracker type e.g. ga, hotjar, etc. used for the column names in the settings table
    protected $trackerType;
    //The tracker status e.g. true or false to enable or disable the tracker
    protected $trackerStatus;

    /**
     * Get Tracker ID
     *
     * Get the tracker id from the settings table
     *
     * @return string //the tracker id
     */
    public function getTrackerID()
    {
        //SQL statement to get the tracker id
        $sql = "SELECT " . $this->trackerType . "_id FROM settings WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the tracker id
        $trackerID = '';

        //Check if the row exists
        if ($row) {
            //check if the tracker_id is set or not
            if (isset($row[$this->trackerType . '_id'])) {
                //Set the tracker id
                $trackerID = $row[$this->trackerType . '_id'];
            }
        }

        //Return the tracker id
        return $trackerID;
    }

    /**
     * Set Tracker ID
     *
     * Set the tracker id in the settings table
     *
     * @param string $trackerID //the tracker id
     * @return bool //true if the tracker id was set, false if not
     */
    public function setTrackerID($trackerID = null)
    {
        //instance of the session class
        $session = new Session();
        //get the user id from the session
        $userID = intval($session->get('user_id')) ?? null;
        //Check if the tracker id is set in the settings table already
        if ($this->getTrackerID() != '' || $this->getTrackerID() != null) {
            //SQL statement to update the tracker id
            $sql = "UPDATE settings SET " . $this->trackerID . "_id = ? WHERE isSet = 'SET'";

            //Prepare the SQL statement for execution
            $stmt = prepareStatement($this->mysqli, $sql);

            //Bind the parameters
            $stmt->bind_param('s', $trackerID);

            //Execute the statement
            $stmt->execute();

            //Check if the statement was executed successfully
            if ($stmt->affected_rows > 0) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($userID, $this->trackerName . ' ID Changed', 'The ' . $this->trackerName . ' ID was changed to ' . $trackerID . '.');
                //Return true
                return true;
            }

            //Return false
            return false;
        }
        //SQL statement to update the tracker id, where isSet is SET
        $sql = "UPDATE settings SET " . $this->trackerType . "_id = ? WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters
        $stmt->bind_param('s', $trackerID);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userID, $this->trackerName . ' ID Changed', 'The ' . $this->trackerName . ' ID was changed to ' . $trackerID . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
    }

    /**
     * Get Tracker Status
     * Get the tracker status from the settings table
     *
     * @return bool //the tracker status
     */
    public function getTrackerStatus()
    {
        //SQL statement to get the tracker status
        $sql = "SELECT " . $this->trackerType . "_enable FROM settings WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the tracker status
        $trackerStatus = false;

        //Check if the row exists
        if ($row) {
            //check if the tracker_enable is set or not
            if (isset($row[$this->trackerType . '_enable'])) {
                //Set the tracker status
                $trackerStatus = boolval($row[$this->trackerType . '_enable']);
            }
        }

        //Return the tracker status
        return $trackerStatus;
    }

    /**
     * Set Tracker Status
     *
     * Set the tracker status in the settings table
     *
     * @param bool $trackerStatus //the tracker status
     * @return bool //true if the tracker status was set, false if not
     */
    public function setTrackerStatus($trackerStatus = null)
    {
        //SQL statement to update the tracker status
        $sql = "UPDATE settings SET " . $this->trackerType . "_enable = ? WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters
        $stmt->bind_param('i', $trackerStatus);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            //instance of the session class
            $session = new Session();
            //get the user id from the session
            $userID = intval($session->get('user_id')) ?? null;
            $activity->logActivity($userID, $this->trackerName . ' Status Changed', 'The ' . $this->trackerName . ' status was changed to ' . $trackerStatus . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
    }

    /**
     * Get If Any Tracker Is Enabled
     * Get if any tracker is enabled from the settings table
     */
    public function getIfAnyTrackerIsEnabled()
    {
        //SQL statement to get if any tracker is enabled
        $sql = "SELECT ga_enable, hotjar_enable FROM settings WHERE isSet = 'SET'";

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
            //check if the ga_enable or hotjar_enable is set or not
            if (isset($row['ga_enable']) || isset($row['hotjar_enable'])) {
                //check if the ga_enable is true or the hotjar_enable is true
                if ($row['ga_enable'] == 1 || $row['hotjar_enable'] == 1) {
                    //Return true
                    return true;
                }
            }
        }

        //Return false
        return false;
    }
}

/**
 * Hotjar Tracker Class
 * Functions to get and set the hotjar tracker settings
 */
class HotjarTracker extends TrackerSettings
{
    //The tracker name to identify the tracker e.g. Google Analytics, Hotjar, etc.
    protected $trackerName = 'Hotjar';
    //The tracker type e.g. ga, hotjar, etc. used for the column names in the settings table
    protected $trackerType = 'hotjar';

    /**
     * Get Hotjar ID
     *
     * Get the hotjar id from the settings table
     *
     * @return string //the hotjar id
     */
    public function getHotjarID()
    {
        return $this->getTrackerID();
    }

    /**
     * Set Hotjar ID
     *
     * Set the hotjar id in the settings table
     *
     * @param string $hotjarID //the hotjar id
     * @return bool //true if the hotjar id was set, false if not
     */
    public function setHotjarID($hotjarID = null)
    {
        return $this->setTrackerID($hotjarID);
    }

    /**
     * Get Hotjar Version
     *
     * Get the hotjar version from the settings table
     *
     * @return ?int //the hotjar version
     */
    public function getHotjarVersion()
    {
        //SQL statement to get the hotjar version
        $sql = "SELECT hotjar_version FROM settings WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the row
        $row = $result->fetch_assoc();

        //Placeholder for the hotjar version
        $hotjarVersion = null;

        //Check if the row exists
        if ($row) {
            //check if the hotjar_version is set or not
            if (isset($row['hotjar_version'])) {
                //Set the hotjar version
                $hotjarVersion = intval($row['hotjar_version']);
            }
        }

        //Return the hotjar version
        return $hotjarVersion;
    }

    /**
     * Set Hotjar Version
     *
     * Set the hotjar version in the settings table
     *
     * @param int $hotjarVersion //the hotjar version
     * @return bool //true if the hotjar version was set, false if not
     */
    public function setHotjarVersion($hotjarVersion = null)
    {
        //SQL statement to update the hotjar version
        $sql = "UPDATE settings SET hotjar_version = ? WHERE isSet = 'SET'";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters
        $stmt->bind_param('i', $hotjarVersion);

        //Execute the statement
        $stmt->execute();

        //Check if the statement was executed successfully
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            //instance of the session class
            $session = new Session();
            //get the user id from the session
            $userID = intval($session->get('user_id')) ?? null;
            $activity->logActivity($userID, 'Hotjar Version Changed', 'The hotjar version was changed to ' . $hotjarVersion . '.');
            //Return true
            return true;
        }

        //Return false
        return false;
    }

    /**
     * Get Hotjar Status
     *
     * Get the hotjar status from the settings table
     *
     * @return bool //the hotjar status
     */
    public function getHotjarStatus()
    {
        return $this->getTrackerStatus();
    }

    /**
     * Set Hotjar Status
     *
     * Set the hotjar status in the settings table
     *
     * @param bool $hotjarStatus //the hotjar status
     * @return bool //true if the hotjar status was set, false if not
     */
    public function setHotjarStatus($hotjarStatus = null)
    {
        return $this->setTrackerStatus($hotjarStatus);
    }
}

/**
 * Google Analytics Tracker Class
 * Functions to get and set the google analytics tracker settings
 */
class GoogleAnalyticsTracker extends TrackerSettings
{
    //The tracker name to identify the tracker e.g. Google Analytics, Hotjar, etc.
    protected $trackerName = 'Google Analytics';
    //The tracker type e.g. ga, hotjar, etc. used for the column names in the settings table
    protected $trackerType = 'ga';

    /**
     * Get Google Analytics ID
     *
     * Get the google analytics id from the settings table
     *
     * @return string //the google analytics id
     */
    public function getGoogleAnalyticsID()
    {
        return $this->getTrackerID();
    }

    /**
     * Set Google Analytics ID
     *
     * Set the google analytics id in the settings table
     *
     * @param string $gaID //the google analytics id
     * @return bool //true if the google analytics id was set, false if not
     */
    public function setGoogleAnalyticsID($gaID = null)
    {
        return $this->setTrackerID($gaID);
    }

    /**
     * Get Google Analytics Status
     *
     * Get the google analytics status from the settings table
     *
     * @return bool //the google analytics status
     */
    public function getGoogleAnalyticsStatus()
    {
        return $this->getTrackerStatus();
    }

    /**
     * Set Google Analytics Status
     *
     * Set the google analytics status in the settings table
     *
     * @param bool $gaStatus //the google analytics status
     * @return bool //true if the google analytics status was set, false if not
     */
    public function setGoogleAnalyticsStatus($gaStatus = null)
    {
        return $this->setTrackerStatus($gaStatus);
    }
}
