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
     * Get all activity
     *
     * @return array
     */
    public function getAllActivity(): array
    {
        //sql statement to get all activity
        $sql = "SELECT * FROM activity_log ORDER BY action_date DESC";

        //prepare the sql statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create an array to hold the activity
        $activity = [];

        //if there are rows in the result, loop through them and add them to the activity array
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $activity[] = $row;
            }
        }

        //return the activity
        return $activity;
    }

    /**
     * Get all activity by user
     *
     * @param int $userID
     * @return array
     */
    public function getAllActivityByUser(int $userID): array
    {
        //sql statement to get all activity by user
        $sql = "SELECT * FROM activity_log WHERE user_id = ? ORDER BY action_date DESC";

        //prepare the sql statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the user id to the statement
        $stmt->bind_param('i', $userID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create an array to hold the activity
        $activity = [];

        //loop through the result and add each row to the activity array
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $activity[] = $row;
            }
        }

        //return the activity
        return $activity;
    }

    /**
     * Get all activity by user last 30 days
     *
     * @param int $userID
     *
     * @return array
     */
    public function getLast30DaysByUser(int $userID): array
    {
        //sql statement to get all activity by user in the last 30 days
        $sql = "SELECT * FROM activity_log WHERE user_id = ? AND action_date >= DATE_SUB(NOW(), INTERVAL 30 DAY) ORDER BY action_date DESC";

        //prepare the sql statement
        $stmt = prepareStatement($this->mysqli, $sql);

        //bind the user id to the statement
        $stmt->bind_param('i', $userID);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //create an array to hold the activity
        $activity = [];

        //loop through the result and add each row to the activity array
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $activity[] = $row;
            }
        }

        //return the activity
        return $activity;
    }

    /**
     * Log activity
     *
     * @param int $userID, can be null
     * @param string $action, the action performed
     * @param string $performedOn, the item the action was performed on
     */
    public function logActivity(int $userID = null, string $action, string $performedOn): void
    {
        //get the current date and time
        $actionDate = date('Y-m-d H:i:s');

        //simplify the action to an enum, if a string is found in the action
        $action = $this->simplifyActionEnum($action);

        //keep the performedOn under 535 characters
        if (strlen($performedOn) > 535) {
            $performedOn = substr($performedOn, 0, 535);
        }

        // Prepare the SQL statement
        $sql = "INSERT INTO activity_log (user_id, action_date, action, performed_on) VALUES (?, ?, ?, ?)";
        $stmt = prepareStatement($this->mysqli, $sql);

        // Bind the parameters based on the user ID
        $stmt->bind_param('isss' , $userID, $actionDate, $action, $performedOn);

        // Execute the statement
        $stmt->execute();

        //close the statement
        $stmt->close();

        //log the activity
        error_log('Activity Logged: ' . $action . ' ' . $performedOn);

        //if the action is an error, throw an exception
        if ($action == 'ERROR') {
            throw new Exception('Error: ' . $action . ' ' . $performedOn);
        }
    }

    /**
     * Choose an action enum based on the action string
     *
     * @param string $action
     * @return string $enum
     */
    private function simplifyActionEnum(string $action): string
    {
        $action = strtolower($action); //convert the action to lowercase for easier comparison
        $enum = 'OTHER'; //default enum
        $actionArray = LOGGING_ACTIONS_ARRAY; //get the array of logging actions from the config file

        //loop through the array of logging actions, comparing the provided action to strings in the array
        //each sub array contains an action key and a strings key that contains an array of strings to compare to
        foreach ($actionArray as $actionItem) {
            if (in_array($action, $actionItem['strings'])) {
                $enum = $actionItem['action'];
                break;
            }
        }

        return $enum;
    }
}
