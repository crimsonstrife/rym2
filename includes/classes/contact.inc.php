<?php

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
        $this->mysqli = connectToDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    }

    //Close the database connection when the object is destroyed
    public function __destruct()
    {
        closeDatabaseConnection($this->mysqli);
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
        $stmt = $this->mysqli->prepare($sql);

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
        $stmt = $this->mysqli->prepare($sql);

        //bind the parameters
        $stmt->bind_param('iiisss', $studentId, $auto, $senderId, $date, $subject, $message);

        //execute the statement
        $stmt->execute();

        //check if the statement was successful
        if ($stmt->affected_rows > 0) {
            //log the activity
            $activity = new Activity();
            $studentClass = new Student();
            $activity->logActivity($senderId, 'Contacted Student', $studentClass->getStudentFullName($studentId));
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
        $stmt = $this->mysqli->prepare($sql);

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
        $stmt = $this->mysqli->prepare($sql);

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
        //include the application class
        $APP = new Application();
        //Create a new PHPMailer instance
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        //Tell what protocol to use
        $mail->Mailer = MAIL_MAILER;
        //Set the hostname of the mail server
        $mail->Host = MAIL_HOST;
        //Set the port number - likely to be 25, 465 or 587
        $mail->Port = MAIL_PORT;
        //Set if authentication is required
        $mail->SMTPAuth = MAIL_AUTH_REQ;

        //Set the encryption system to use - ssl (deprecated) or tls
        if (MAIL_ENCRYPTION == 'ssl') {
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        } else if (MAIL_ENCRYPTION == 'tls') {
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        }

        //if authentication is required, set the username and password
        if (MAIL_AUTH_REQ == 'true') {
            $mail->Username = MAIL_USERNAME;
            //if the password was set in the database
            if ($APP->getMailerPassword() != null || $APP->getMailerPassword() != '') {
                //if openssl is installed, decrypt the password
                if (OPENSSL_INSTALLED) {
                    $mail->Password = openssl_decrypt(MAIL_PASSWORD, 'AES-128-ECB', MAILER_PASSWORD_ENCRYPTION_KEY);
                } else {
                    $mail->Password = MAIL_PASSWORD;
                }
            } else {
                $mail->Password = MAIL_PASSWORD;
            }
        }

        //include the student class
        $studentData = new Student();

        //get student data by email
        $student = $studentData->getStudentByEmail($email);

        //get current date and time
        $date = date('Y-m-d H:i:s');

        //Set who the message is to be sent from (the server will need to be configured to authenticate with this address, or to have send as permissions)
        $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);

        //Set who the message is to be sent to
        $mail->addAddress($email, $name);

        //Set the subject line
        $mail->Subject = $subject;

        //Don't use HTML
        $mail->isHTML(false);

        //Set the body
        $mail->Body = $message;

        //send the message, check for errors
        if (!$mail->send()) {
            //if there is an error, return false
            return false;
        } else {
            //if there is no error, log the email and return true
            $studentData->logContactHistory(intval($student['id']), $date, 1, NULL, $subject, $message);
            return true;
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

        //include the student class
        $studentData = new Student();

        //include the user class
        $userObject = new User();

        //get the email address of the user that sent the email
        $senderEmail = $userObject->getUserEmail($senderId);

        //include the application class
        $APP = new Application();
        //Create a new PHPMailer instance
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        //Tell what protocol to use
        $mail->Mailer = MAIL_MAILER;
        //Set the hostname of the mail server
        $mail->Host = MAIL_HOST;
        //Set the port number - likely to be 25, 465 or 587
        $mail->Port = MAIL_PORT;
        //Set if authentication is required
        $mail->SMTPAuth = MAIL_AUTH_REQ;

        //Set the encryption system to use - ssl (deprecated) or tls
        if (MAIL_ENCRYPTION == 'ssl') {
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        } else if (MAIL_ENCRYPTION == 'tls') {
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        }

        //if authentication is required, set the username and password
        if (MAIL_AUTH_REQ == 'true') {
            $mail->Username = MAIL_USERNAME;
            //if the password was set in the database
            if ($APP->getMailerPassword() != null || $APP->getMailerPassword() != '') {
                //if openssl is installed, decrypt the password
                if (OPENSSL_INSTALLED) {
                    $mail->Password = openssl_decrypt(MAIL_PASSWORD, 'AES-128-ECB', MAILER_PASSWORD_ENCRYPTION_KEY);
                } else {
                    $mail->Password = MAIL_PASSWORD;
                }
            } else {
                $mail->Password = MAIL_PASSWORD;
            }
        }

        //Set who the message is to be sent from (the server will need to be configured to authenticate with this address, or to have send as permissions)
        $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);

        //Set who the message is to be sent to
        $mail->addAddress($email, $name);

        //Set the subject line
        $mail->Subject = $subject;

        //Don't use HTML
        $mail->isHTML(false);

        //Set the body
        $mail->Body = $message;

        //send the message, check for errors
        if (!$mail->send()) {
            //if there is an error, return false
            return false;
        } else {
            //if there is no error, log the email and return true
            $studentData->logContactHistory($studentId, $date, 0, $senderId, $subject, $message);
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
        //include the application class
        $APP = new Application();
        //Create a new PHPMailer instance
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        //Tell what protocol to use
        $mail->Mailer = MAIL_MAILER;
        //Set the hostname of the mail server
        $mail->Host = MAIL_HOST;
        //Set the port number - likely to be 25, 465 or 587
        $mail->Port = MAIL_PORT;
        //Set if authentication is required
        $mail->SMTPAuth = MAIL_AUTH_REQ;

        //Set the encryption system to use - ssl (deprecated) or tls
        if (MAIL_ENCRYPTION == 'ssl') {
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        } else if (MAIL_ENCRYPTION == 'tls') {
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        }

        //if authentication is required, set the username and password
        if (MAIL_AUTH_REQ == 'true') {
            $mail->Username = MAIL_USERNAME;
            //if the password was set in the database
            if ($APP->getMailerPassword() != null || $APP->getMailerPassword() != '') {
                //if openssl is installed, decrypt the password
                if (OPENSSL_INSTALLED) {
                    $mail->Password = openssl_decrypt(MAIL_PASSWORD, 'AES-128-ECB', MAILER_PASSWORD_ENCRYPTION_KEY);
                } else {
                    $mail->Password = MAIL_PASSWORD;
                }
            } else {
                $mail->Password = MAIL_PASSWORD;
            }
        }

        //Set who the message is to be sent from (the server will need to be configured to authenticate with this address, or to have send as permissions)
        $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);

        //Set who the message is to be sent to
        $mail->addAddress($email);

        //Set the subject line
        $mail->Subject = 'Account Created';

        //Don't use HTML
        $mail->isHTML(false);

        //Set the body
        $mail->Body = "Your account has been created. Your username is: " . $username . " and your password is: " . $password;

        //send the message, check for errors
        if (!$mail->send()) {
            //log the error
            $activity = new Activity();
            $activity->logActivity(null, 'Error Sending Account Creation Email', $mail->ErrorInfo);
            //if there is an error, return false
            return false;
        } else {
            //log the activity
            $activity = new Activity();
            $activity->logActivity(null, 'Account Creation Email Sent', $email);
            //if there is no error, return true
            return true;
        }
    }
}
