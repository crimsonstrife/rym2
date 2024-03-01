<?php
/**
 * Contact Class file for the College Recruitment Application
 * Contains all the functions for the Contact Class and handles all the contact related tasks for students or users with the database.
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/16/2023
 *
 * @package RYM2
 * Filename: contact.inc.php
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

/**
 * The Contact Class
 *
 * Contains functions for contacting students and logging interactions
 *
 * @package RYM2
 */
class Contact
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
     * Send an email
     * @param string $recipientEmail email address to send the email to
     * @param string $recipientName name of the recipient
     * @param string $senderEmail email address of the sender
     * @param string $senderName name of the sender
     * @param string $subject subject of the email
     * @param string $message message to send to the student
     * @param ?bool $useHTML whether to use HTML in the email
     *
     * @return mixed true if the email was sent, false if not
     */
    public function initiateEmailSend(string $recipientEmail, string $recipientName, string $senderEmail, string $senderName, string $subject, string $message, ?bool $useHTML = false): mixed
    {
        //is the email HTML?, defaults to false if not set
        if ($useHTML == true) {
            $useHTML = true;
        } else {
            $useHTML = false;
        }

        //include the application class
        $settings = new MailerSettings();

        //Create a new PHPMailer instance
        $mail = new PHPMailer\PHPMailer\PHPMailer();

        //Tell what protocol to use
        $mail->Mailer = MAIL_MAILER;

        //Set PHPMailer to use SMTP if the mailer is set to use SMTP in the settings
        if (MAIL_MAILER == 'smtp') {
            $mail->isSMTP();
        }

        //Set the hostname of the mail server
        $mail->Host = MAIL_HOST;

        //Set the port number - likely to be 25, 465 or 587
        $mail->Port = MAIL_PORT;

        //Set if authentication is required
        $mail->SMTPAuth = MAIL_AUTH_REQ;

        //try to set the encryption system to use - ssl (deprecated) or tls based on the port number in case the user set it wrong
        if ($mail->Port == 465 && MAIL_ENCRYPTION != 'ssl' && OPENSSL_INSTALLED == true) {
            $mail->SMTPSecure = $mail::ENCRYPTION_SMTPS;
        } else if ($mail->Port == 587 && MAIL_ENCRYPTION != 'tls' && OPENSSL_INSTALLED == true) {
            $mail->SMTPSecure = $mail::ENCRYPTION_STARTTLS;
        }

        //Set the encryption system to use - ssl (deprecated) or tls
        if (MAIL_ENCRYPTION == 'ssl' || OPENSSL_INSTALLED == true) {
            $mail->SMTPSecure = $mail::ENCRYPTION_SMTPS;
        } else if (MAIL_ENCRYPTION == 'tls' || OPENSSL_INSTALLED == true) {
            $mail->SMTPSecure = $mail::ENCRYPTION_STARTTLS;
        } else {
            $mail->SMTPSecure = false;
        }

        //log errors if the encryption is set to ssl or tls and openssl is not installed
        if (MAIL_ENCRYPTION == 'ssl' && OPENSSL_INSTALLED == false) {
            error_log('Error: OpenSSL is not installed, cannot use SSL encryption');
        } else if (MAIL_ENCRYPTION == 'tls' && OPENSSL_INSTALLED == false) {
            error_log('Error: OpenSSL is not installed, cannot use TLS encryption');
        }

        //if MAIL_ENCRYPTION is set to null, remove any encryption
        if (MAIL_ENCRYPTION == null) {
            $mail->SMTPSecure = false;
        }

        //if the app_env is set to local, allow insecure connections for self-signed certificates
        if (APP_ENV == 'local' || APP_ENV == 'development' || APP_ENV == 'testing' || APP_ENV == 'LOCAL' || APP_ENV == 'DEVELOPMENT' || APP_ENV == 'TESTING') {
            //if ssl is set, allow insecure connections
            if (MAIL_ENCRYPTION == 'ssl') {
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
            } else if (MAIL_ENCRYPTION == 'tls') {
                $mail->SMTPOptions = array(
                    'tls' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
            } else {
                //empty the options
                $mail->SMTPOptions = array();
            }
        }

        //set debug
        if (APP_DEBUG == true) {
            $mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
        } else {
            $mail->SMTPDebug = 0;
        }

        //if authentication is required, set the username and password
        if (MAIL_AUTH_REQ == 'true') {
            $mail->Username = MAIL_USERNAME;
            //if the password was set in the database
            if ($settings->getMailerPassword() != null || $settings->getMailerPassword() != '') {
                //if openssl is installed, decrypt the password
                if (OPENSSL_INSTALLED) {
                    $mail->Password = openssl_decrypt(MAIL_PASSWORD, 'AES-128-ECB', MAILER_PASSWORD_ENCRYPTION_KEY);
                } else {
                    $mail->Password = MAIL_PASSWORD;
                }
            } else {
                $mail->Password = MAIL_PASSWORD;
            }
        } else {
            $mail->SMTPAuth = false;
            $mail->SMTPSecure = false;
        }

        //Set who the message is to be sent from (the server will need to be configured to authenticate with this address, or to have send as permissions)
        //check if the sender email matches the from address in constants
        if ($senderEmail == MAIL_FROM_ADDRESS) {
            $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
        } else {
            $mail->setFrom($senderEmail, $senderName);
        }

        //Set who the message is to be sent to
        $mail->addAddress($recipientEmail, $recipientName);

        //Set the subject line
        $mail->Subject = $subject;

        //set if the email is HTML
        $mail->isHTML($useHTML);

        //Set the body
        $mail->Body = $message;

        //send the message
        $result = $mail->send();

        //return the result
        return $result;
    }

    /**
     * Get the contact log from the database
     *
     * @return array
     */
    public function getContactLog(): array
    {
        //create the SQL query
        $sql = "SELECT * FROM contact_log";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create an array to store the contact log
        $contactLog = array();

        //loop through the result
        while ($row = $result->fetch_assoc()) {
            //add the row to the contact log array
            $contactLog[] = $row;
        }

        //return the contact log
        return $contactLog;
    }

    /**
     * Log a contact
     *
     * @param int $studentId - the id of the student that was contacted
     * @param bool $auto - whether the contact was automatic or not
     * @param int $senderId - the id of the user that sent the contact - NULL if automatic
     * @param string $date - the date the contact was sent
     * @param string $subject - the subject of the message
     * @param string $message - the message that was sent
     *
     * @return bool
     */
    public function logContact(int $studentId, bool $auto, int $senderId = NULL, string $date, string $subject, string $message): bool
    {
        //create the SQL query
        $sql = "INSERT INTO contact_log (student, auto, sender, send_date, subject, message) VALUES (?, ?, ?, ?, ?, ?)";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param('iiisss', $studentId, $auto, $senderId, $date, $subject, $message);

        //execute the statement
        $stmt->execute();

        //check if the statement was successful
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $studentClass = new Student();
            $userClass = new User();
            //get the user name of the user that sent the email, or set it to SYSTEM if it was automatic
            if ($senderId != NULL) {
                $senderName = $userClass->getUserUsername($senderId);
            } else {
                $senderName = 'SYSTEM';
            }
            $activity->logActivity($senderId, 'Email Sent', 'Sent ' . $studentClass->getStudentFullName($studentId) . ' @ ' . $studentClass->getStudentEmail($studentId) . ' - Subject: ' . $subject . ' by ' . $senderName);
            //return true
            return true;
        } else {
            //return false
            return false;
        }
    }

    /**
     * Get the contact log for a specific student
     *
     * @param int $studentId - the id of the student to get the contact log for
     *
     * @return array
     */
    public function getStudentContactLog(int $studentId): array
    {
        //create the SQL query
        $sql = "SELECT * FROM contact_log WHERE student = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param('i', $studentId);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create an array to store the contact log
        $contactLog = array();

        //loop through the result
        while ($row = $result->fetch_assoc()) {
            //add the row to the contact log array
            $contactLog[] = $row;
        }

        //return the contact log
        return $contactLog;
    }

    /**
     * Remove a contact from the contact log
     *
     * @param int $contactId - the id of the contact to remove
     *
     * @return bool
     */
    public function removeContact(int $contactId): bool
    {
        //create the SQL query
        $sql = "DELETE FROM contact_log WHERE id = ?";

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param('i', $contactId);

        //execute the statement
        $stmt->execute();

        //check if the statement was successful
        if ($stmt->affected_rows > 0) {
            //return true
            return true;
        } else {
            //return false
            return false;
        }
    }

    /**
     * Get the sending history for a specific user
     *
     * @param int $userId - the id of the user to get the sending history for, if not specified, get the sending history for the automatically sent messages
     *
     * @return array
     */
    public function getUserSendingHistory(int $userId = NULL): array
    {
        //include the user class
        $userObject = new User();

        //create the SQL query
        $sql = "SELECT * FROM contact_log WHERE sender = ?";

        //create a placeholder for the sender parameter
        $sender = NULL;

        //check if the user id is specified
        if ($userId != NULL) {
            //get the user name of the user
            $userName = $userObject->getUserUsername($userId);

            //set the sender parameter to the userID
            $sender = $userId;
        } else {
            //set the user name to SYSTEM
            $userName = 'SYSTEM';

            //set the sender parameter to NULL
            $sender = NULL;
        }

        //prepare the statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the parameters
        $stmt->bind_param('i', $sender);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create an array to store the sending history
        $sendingHistory = array();

        //loop through the result, adding the user name to the row
        while ($row = $result->fetch_assoc()) {
            //add the row to the sending history array
            $sendingHistory[] = array(
                'id' => $row['id'],
                'student' => $row['student'],
                'auto' => $row['auto'],
                'sender' => $userName,
                'send_date' => $row['send_date'],
                'subject' => $row['subject'],
                'message' => $row['message'],
            );
        }

        //return the sending history
        return $sendingHistory;
    }

    /**
     * Automated emails function
     * E.x Sends a welcome email to the student when they register, also notifies the admin that a new student has registered
     *
     * @param string $email email address to send the email to
     * @param string $name name of the student
     * @param string $message message to send to the student
     * @return bool true if the email was sent, false if not
     */
    function sendAutoEmail(string $email, string $name, string $subject, string $message): bool
    {
        //include the student class
        $studentData = new Student();

        //get student data by email
        $student = $studentData->getStudentByEmail($email);

        //get current date and time
        $date = date('Y-m-d H:i:s');

        //initiate the email send
        $mailResult = $this->initiateEmailSend($email, $name, MAIL_FROM_ADDRESS, MAIL_FROM_NAME, $subject, $message);

        //check for errors
        if ($mailResult == true) {
            //log the contact
            $this->logContactHistory($student['id'], $date, 1, NULL, $subject, $message);
            //log the activity
            $activity = new Activity();
            $activity->logActivity(NULL, 'Email Sent', 'Sent ' . $name . ' @ ' . $email . ' - Subject: ' . $subject);
            return true;
        } else {
            //log the error
            $activity = new Activity();
            $activity->logActivity(NULL, 'Error Sending Email', $email);
            return false;
        }
    }

    /**
     * Send an email to a student
     *
     * @param int $studentId - the id of the student to send the email to
     * @param string $email - the email address to send the email to
     * @param string $name - the name of the student
     * @param string $subject - the subject of the email
     * @param string $message - the message to send to the student
     * @param int $senderId - the id of the user that sent the email
     *
     * @return bool
     */
    public function sendEmail(int $studentId, string $email, string $name, string $subject, string $message, int $senderId): bool
    {
        //get the current date and time
        $date = date('Y-m-d H:i:s');

        //include the user class
        $userObject = new User();

        //get the email address of the user that sent the email
        $senderEmail = $userObject->getUserEmail($senderId);

        //get the name of the user that sent the email
        $senderName = $userObject->getUserUsername($senderId);

        //initiate the email send
        $result = $this->initiateEmailSend($email, $name, $senderEmail, $senderName, $subject, $message);

        //check for errors
        if (!$result) {
            //if there is an error, log it to the activity log and return false
            $activity = new Activity();
            $activity->logActivity($senderId, 'Error Sending Email', $result->ErrorInfo . ' - ' . $email);
            return false;
        } else {
            //if there is no error, log the email and return true
            $this->logContactHistory($studentId, $date, 0, $senderId, $subject, $message);
            //log the activity
            $activity = new Activity();
            $activity->logActivity($senderId, 'Email Sent', 'Sent ' . $name . ' @ ' . $email . ' - Subject: ' . $subject . ' by ' . $senderName);
            return true;
        }
    }

    /**
     * Send Account Creation Email to User
     * Sends an email to the user with their username and password
     * @param string $email - the email address to send the email to
     * @param string $username - the username of the user
     * @param string $password - the password of the user
     *
     * @return bool
     */
    public function sendAccountCreationEmail(string $email, string $username, string $password): bool
    {
        $subject = 'Account Created';

        //Set the body
        $body = "Your account has been created. Your username is: " . $username . " and your password is: " . $password;

        //initiate the email send
        $result = $this->initiateEmailSend($email, $username, MAIL_FROM_ADDRESS, MAIL_FROM_NAME, $subject, $body);

        //check for errors
        if ($result == false) {
            //log the error
            $activity = new Activity();
            //instance of the session class
            $session = new Session();
            $userID = intval($session->get('user_id')) ?? null;
            $activity->logActivity($userID, 'Error Sending Account Creation Email', $result->ErrorInfo);

            //debugging
            error_log('Error: ' . $result->ErrorInfo);

            //if there is an error, return false
            return false;
        }

        //log the activity
        $activity = new Activity();
        //instance of the session class
        $session = new Session();
        $userID = intval($session->get('user_id')) ?? null;
        $activity->logActivity($userID, 'Account Creation Email Sent', $email);

        //debugging
        error_log('Email Sent: ' . $email);

        //if there is no error, return true
        return true;
    }

    /**
     * Notifies the user of their account creation, sends an email to the user with their username and password
     *
     * @param string $email The user's email
     * @param string $username The user's username
     * @param string $password The user's password
     *
     * @return bool True if the email was sent, false if not
     */
    public function notifyUserCreated(string $email, string $username, string $password): bool
    {
        //trim the email
        $email = trim($email);

        //trim the username
        $username = trim($username);

        //send the email
        $mail = $this->sendAccountCreationEmail($email, $username, $password);

        //if the email was sent, return true
        if ($mail == true) {
            return true;
        }

        //if the email was not sent, return false
        return false;
    }

    /**
     * Send an email to a user
     *
     * @param string $email - the email address to send the email to
     * @param string $subject - the subject of the email
     * @param string $message - the message to send to the user
     * @param bool $useHTML - whether to use HTML in the email
     *
     * @return bool
     */
    public function sendUserEmail(string $email, string $subject, string $message, bool $useHTML = false): bool
    {
        //initiate the email send
        $result = $this->initiateEmailSend($email, $email, MAIL_FROM_ADDRESS, MAIL_FROM_NAME, $subject, $message, $useHTML);

        //check for errors
        if (!$result) {
            //log the error
            $activity = new Activity();
            $activity->logActivity(NULL, 'Error Sending Email', $email);
            //debugging
            error_log('Error Sending Email: ' . $email);

            //return false
            return false;
        } else {
            //log the activity
            $activity = new Activity();
            $activity->logActivity(NULL, 'Email Sent', 'Sent ' . $email . ' - Subject: ' . $subject);
            //debugging
            error_log('Email Sent: ' . $email . ' - Subject: ' . $subject);

            //return true
            return true;
        }
    }

    /**
     * Add Student Contact History
     * Add a new contact log entry for a student
     *
     * @param int $studentID
     * @param string $dateTime
     * @param int $isAuto is the email automated or manual, 1 = automated, 0 = manual
     * @param int $senderID is the id of the user that sent the email, will be null if automated
     * @param string $subject
     * @param string $message
     * @return bool
     */
    public function logContactHistory(int $studentID, string $dateTime, int $isAuto, int $senderID = NULL, string $subject, string $message): bool
    {
        //convert the isAuto value to a boolean
        if ($isAuto == 1) {
            $isAuto = true;
        } else {
            $isAuto = false;
        }

        //placeholder for the result
        $result = false;

        //if the sender id is null, do not include it in the query
        if ($senderID == NULL) {
            //use the contact class to log the contact
            $result = $this->logContact($studentID, $isAuto, NULL, $dateTime, $subject, $message);

            //return the result
            return $result;
        }

        //use the contact class to log the contact
        $result = $this->logContact($studentID, $isAuto, $senderID, $dateTime, $subject, $message);

        //return the result
        return $result;
    }
}
