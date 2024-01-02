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
        $this->mysqli = connectToDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
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
            //SQL statement to insert the application name, but also set the isSet column to SET
            $sql = "INSERT INTO settings (app_name, isSet) VALUES (?, 'SET')";

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
            //SQL statement to insert the application URL, but also set the isSet column to SET
            $sql = "INSERT INTO settings (app_url, isSet) VALUES (?, 'SET')";

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
            //SQL statement to insert the mailer type, but also set the isSet column to SET
            $sql = "INSERT INTO settings (mail_mailer, isSet) VALUES (?, 'SET')";

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
            //SQL statement to insert the mailer host, but also set the isSet column to SET
            $sql = "INSERT INTO settings (mail_host, isSet) VALUES (?, 'SET')";

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
            //SQL statement to insert the mailer port, but also set the isSet column to SET
            $sql = "INSERT INTO settings (mail_port, isSet) VALUES (?, 'SET')";

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
            //SQL statement to insert the mailer username, but also set the isSet column to SET
            $sql = "INSERT INTO settings (mail_username, isSet) VALUES (?, 'SET')";

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

            //SQL statement to insert the mailer password, but also set the isSet column to SET
            $sql = "INSERT INTO settings (mail_password, isSet) VALUES (?, 'SET')";

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
            //SQL statement to insert the mailer encryption, but also set the isSet column to SET
            $sql = "INSERT INTO settings (mail_encryption, isSet) VALUES (?, 'SET')";

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
            //SQL statement to insert the mailer from address, but also set the isSet column to SET
            $sql = "INSERT INTO settings (mail_from_address, isSet) VALUES (?, 'SET')";

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
            //SQL statement to insert the mailer from name, but also set the isSet column to SET
            $sql = "INSERT INTO settings (mail_from_name, isSet) VALUES (?, 'SET')";

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
            //SQL statement to insert the mailer authentication required status, but also set the isSet column to SET
            $sql = "INSERT INTO settings (mail_auth_req, isSet) VALUES (?, 'SET')";

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
        //include the php-markdown library
        require_once 'vendor/autoload.php';

        //SQL statement to get the privacy policy content
        $sql = "SELECT privacy_policy FROM settings WHERE isSet = 'SET'";

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
                //convert the privacy policy content from Markdown to HTML
                $privacy_policy = Markdown::defaultTransform($row['privacy_policy']);

                //Return the privacy_policy
                return $privacy_policy;
            } else {
                //get the default privacy policy
                $privacy_policy = strval(PRIVACY_POLICY);

                //convert the privacy policy content from Markdown to HTML
                $privacy_policy = Markdown::defaultTransform($privacy_policy);

                //Return the privacy_policy
                return $privacy_policy;
            }
        } else {
            //Return an empty string
            return '';
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
        //include the php-markdown library
        require_once 'vendor/autoload.php';

        //SQL statement to get the terms and conditions content
        $sql = "SELECT terms_conditions FROM settings WHERE isSet = 'SET'";

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
                //convert the terms_conditions content from Markdown to HTML
                $terms = Markdown::defaultTransform($row['terms_conditions']);

                //Return the terms
                return $terms;
            } else {
                //get the default terms and conditions
                $terms = strval(TERMS_CONDITIONS);

                //convert the terms_conditions content from Markdown to HTML
                $terms = Markdown::defaultTransform($terms);

                //Return the terms
                return $terms;
            }
        } else {
            //Return an empty string
            return '';
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

    /**
     * Reset all of the settings to their default values
     *
     * @return bool //true if the settings were reset, false if not
     */
    public function resetSettings()
    {
        //SQL statement to reset the settings
        $sql = "UPDATE settings SET mail_host = null, mail_port = null, mail_username = null, mail_password = null, mail_encryption = null, mail_from_address = null, mail_from_name = null, mail_auth_req = null, privacy_policy = ?, terms_conditions = ? WHERE isSet = 'SET'";

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
}
