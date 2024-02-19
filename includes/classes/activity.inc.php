<?php

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* Include the base application config file */
require_once(__DIR__ . '/../../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');

/**
 * The Activity Class
 *
 * Contains functions for logging user and system activity
 *
 * @package RYM2
 */
class Activity
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
     * Get all activity
     *
     * @return array
     */
    public function getAllActivity(): array
    {
        $sql = "SELECT * FROM activity_log ORDER BY action_date DESC";
        $result = $this->mysqli->query($sql);
        $activity = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $activity[] = $row;
            }
        }
        return $activity;
    }

    /**
     * Get all activity by user
     *
     * @param int $user_id
     * @return array
     */
    public function getAllActivityByUser(int $user_id): array
    {
        $sql = "SELECT * FROM activity_log WHERE user_id = ? ORDER BY action_date DESC";
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
                $stmt = $this->mysqli->prepare($sql);
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $activity = [];
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $activity[] = $row;
                    }
                }
                return $activity;
            }
        } else {
            //log the error
            error_log('Error: The database connection is null');
            //throw an exception
            throw new Exception('Error: The database connection is null');
        }
    }

    /**
     * Get all activity by user last 30 days
     *
     * @param int $user_id
     *
     * @return array
     */
    public function getLast30DaysByUser(int $user_id): array
    {
        $sql = "SELECT * FROM activity_log WHERE user_id = ? AND action_date >= DATE_SUB(NOW(), INTERVAL 30 DAY) ORDER BY action_date DESC";
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
                $stmt = $this->mysqli->prepare($sql);
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $activity = [];
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $activity[] = $row;
                    }
                }
                return $activity;
            }
        } else {
            //log the error
            error_log('Error: The database connection is null');
            //throw an exception
            throw new Exception('Error: The database connection is null');
        }
    }

    /**
     * Log activity
     *
     * @param int $user_id, can be null
     * @param string $action, the action performed
     * @param string $performed_on, the item the action was performed on
     */
    public function logActivity(int $user_id = null, string $action, string $performed_on): void
    {
        //get the current date and time
        $action_date = date('Y-m-d H:i:s');

        //simplify the action to an enum, if a string is found in the action
        $action = $this->simplifyActionEnum($action);

        //keep the performed_on under 535 characters
        if (strlen($performed_on) > 535) {
            $performed_on = substr($performed_on, 0, 535);
        }

        if (isset($this->mysqli)) {
            //check that the mysqli object is not null
            if ($this->mysqli->connect_error) {
                print_r($this->mysqli->connect_error);
                //log the error
                error_log('Error: ' . $this->mysqli->connect_error);
            } else {
                //check if the user id is null
                if ($user_id == null) {
                    //prepare the sql statement
                    $sql = "INSERT INTO activity_log (action_date, action, performed_on) VALUES (?, ?, ?)";
                    $stmt = $this->mysqli->prepare($sql);
                    $stmt->bind_param('sss', $action_date, $action, $performed_on);
                    $stmt->execute();
                } else {
                    //prepare the sql statement
                    $sql = "INSERT INTO activity_log (user_id, action_date, action, performed_on) VALUES (?, ?, ?, ?)";
                    $stmt = $this->mysqli->prepare($sql);
                    $stmt->bind_param('isss', $user_id, $action_date, $action, $performed_on);
                    $stmt->execute();
                }

                //close the statement
                $stmt->close();

                //log the activity
                error_log('Activity Logged: ' . $action . ' ' . $performed_on);

                //if the action is an error, throw an exception
                if ($action == 'ERROR') {
                    throw new Exception('Error: ' . $action . ' ' . $performed_on);
                }
            }
        } else {
            //log the error
            error_log('Error: The database connection is null');
            //throw an exception
            throw new Exception('Error: The database connection is null');
        }
    }

    /**
     * Choose an action enum based on the action string
     *
     * @param string $action
     * @return string $enum
     */
    private function simplifyActionEnum(string $action): string {
        switch (true) {
            case stripos($action, 'created') !== false:
            case stripos($action, 'generated') !== false:
                $action = 'CREATE';
                break;
            case stripos($action, 'permission') !== false:
            case stripos($action, 'updated') !== false:
            case stripos($action, 'added to') !== false:
            case stripos($action, 'modified') !== false:
            case stripos($action, 'removed from') !== false:
            case stripos($action, 'assigned') !== false:
            case stripos($action, 'unassigned') !== false:
            case stripos($action, 'changed') !== false:
                $action = 'MODIFY';
                break;
            case stripos($action, 'deleted') !== false:
                $action = 'DELETE';
                break;
            case stripos($action, 'logged in') !== false:
                $action = 'LOGIN';
                break;
            case stripos($action, 'logged out') !== false:
                $action = 'LOGOUT';
                break;
            case stripos($action, 'Failed to log the user in') !== false:
                $action = 'LOGIN FAILED';
                break;
            case stripos($action, 'reset') !== false:
                $action = 'RESET';
                break;
            case stripos($action, 'uploaded') !== false:
            case stripos($action, 'exported') !== false:
                $action = 'UPLOAD';
                break;
            case stripos($action, 'downloaded') !== false:
                $action = 'DOWNLOAD';
                break;
            case stripos($action, 'error') !== false:
                $action = 'ERROR';
                break;
            case stripos($action, 'email') !== false:
                $action = 'EMAIL';
                break;
            default:
                $action = 'OTHER';
                break;
        }

        return $action;
    }
}
