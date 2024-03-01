<?php
/**
 * Permissions Class file for the College Recruitment Application
 * Contains all the functions for the Permissions Class and handles all role permission functions.
 *
 * Author: Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date: 10/16/2023
 *
 * @package RYM2
 * Filename: permissions.inc.php
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
 * Permissions Class
 * Contains all the functions for the Permissions Class and handles all role permission functions.
 *
 * @package RYM2
 * @version 1.0.0
 */
class Permission
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

    //Get all the permissions
    public function getAllPermissions(): array
    {
        //SQL statement to get all the permissions
        $sql = "SELECT * FROM permissions";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create an array to hold the permissions
        $permissions = array();

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $permissions[] = $row;
        }

        //Return the array of permissions
        return $permissions;
    }

    //Get permission by ID
    public function getPermissionById(int $permissionID): array
    {
        //SQL statement to get the permission by ID
        $sql = "SELECT * FROM permissions WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the ID to the statement
        $stmt->bind_param("i", $permissionID);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create an array to hold the permission
        $permission = array();

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $permission[] = $row;
        }

        //Return the array of permissions
        return $permission;
    }

    //get permission name by ID
    public function getPermissionNameById(int $permissionID): string
    {
        //SQL statement to get the permission name by ID
        $sql = "SELECT name FROM permissions WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the ID to the statement
        $stmt->bind_param("i", $permissionID);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create a variable to hold the permission name
        $permissionName = "";

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $permissionName = $row['name'];
        }

        //Return the permission name
        return $permissionName;
    }

    /**
     * Get permission ID by name
     *
     * @param string $name The name of the permission
     *
     * @return int The ID of the permission
     */
    public function getPermissionIdByName(string $name): int
    {
        //SQL statement to get the permission ID by name
        $sql = "SELECT id FROM permissions WHERE name = ?";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the name to the statement
        $stmt->bind_param("s", $name);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create a variable to hold the permission ID
        $permissionID = null;

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $permissionID = $row['id'];
        }

        //Return the permission ID
        return intval($permissionID);
    }
}
