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
        if (stripos($action, 'created') !== false) {
            $action = 'CREATE';
        } elseif (stripos($action, 'generated') !== false) {
            $action = 'CREATE';
        } elseif (stripos($action, 'permission') !== false) {
            $action = 'MODIFY';
        } elseif (stripos($action, 'updated') !== false) {
            $action = 'MODIFY';
        } elseif (stripos($action, 'deleted') !== false) {
            $action = 'DELETE';
        } elseif (stripos($action, 'logged in') !== false) {
            $action = 'LOGIN';
        } elseif (stripos($action, 'logged out') !== false) {
            $action = 'LOGOUT';
        } elseif (stripos($action, 'Failed to log the user in') !== false) {
            $action = 'LOGIN FAILED';
        } elseif (stripos($action, 'added to') !== false) {
            $action = 'MODIFY';
        } elseif (stripos($action, 'modified') !== false) {
            $action = 'MODIFY';
        } elseif (stripos($action, 'removed from') !== false) {
            $action = 'MODIFY';
        } elseif (stripos($action, 'assigned') !== false) {
            $action = 'MODIFY';
        } elseif (stripos($action, 'unassigned') !== false) {
            $action = 'MODIFY';
        } elseif (stripos($action, 'changed') !== false) {
            $action = 'MODIFY';
        } elseif (stripos($action, 'reset') !== false) {
            $action = 'RESET';
        } elseif (stripos($action, 'uploaded') !== false) {
            $action = 'UPLOAD';
        } elseif (stripos($action, 'downloaded') !== false) {
            $action = 'DOWNLOAD';
        } elseif (stripos($action, 'exported') !== false) {
            $action = 'DOWNLOAD';
        } elseif (stripos($action, 'error') !== false) {
            $action = 'ERROR';
        } elseif (stripos($action, 'email') !== false) {
            $action = 'EMAIL';
        } else {
            $action = 'OTHER';
        }

        //keep the performed_on under 535 characters
        if (strlen($performed_on) > 535) {
            $performed_on = substr($performed_on, 0, 535);
        }

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
    }
}
