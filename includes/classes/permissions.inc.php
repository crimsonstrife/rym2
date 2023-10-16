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
 * @requires PHP 7.2.5+
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
        $this->mysqli = connectToDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
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
        $stmt = $this->mysqli->prepare($sql);

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
    public function getPermissionById(int $id): array
    {
        //SQL statement to get the permission by ID
        $sql = "SELECT * FROM permissions WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the ID to the statement
        $stmt->bind_param("i", $id);

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
    public function getPermissionNameById(int $id): string
    {
        //SQL statement to get the permission name by ID
        $sql = "SELECT name FROM permissions WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the ID to the statement
        $stmt->bind_param("i", $id);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create a variable to hold the permission name
        $permission_name = "";

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $permission_name = $row['name'];
        }

        //Return the permission name
        return $permission_name;
    }
}
