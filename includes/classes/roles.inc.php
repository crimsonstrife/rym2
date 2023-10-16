<?php

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/**
 * Roles Class file for the College Recruitment Application
 * Contains all the functions for the Roles Class and handles all user role functions.
 *
 * Author: Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date: 10/16/2023
 *
 * @package RYM2
 * Filename: roles.inc.php
 * @version 1.0.0
 * @requires PHP 7.2.5+
 */

/* Include the base application config file */
require_once(__DIR__ . '/../../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');
// include the permissions class file
require_once(BASEPATH . '/includes/classes/permissions.inc.php');

/**
 * Roles Class
 * Contains all the functions for the Roles Class and handles all user role functions.
 *
 * @package RYM2
 * @version 1.0.0
 */
class Roles
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

    //Get all the roles
    public function getAllRoles(): array
    {
        //SQL statement to get all the roles
        $sql = "SELECT * FROM roles";

        //Prepare the SQL statement for execution
        $role_statement = $this->mysqli->prepare($sql);

        //Execute the statement
        $role_statement->execute();

        //Get the results
        $result = $role_statement->get_result();

        //Create an array to hold the roles
        $roles = array();

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $roles[] = $row;
        }

        //Return the array of roles
        return $roles;
    }

    //Get a role by ID
    public function getRoleById(int $id): array
    {
        //SQL statement to get the role by ID
        $sql = "SELECT * FROM roles WHERE id = ?";

        //Prepare the SQL statement for execution
        $role_statement = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $role_statement->bind_param("i", $id);

        //Execute the statement
        $role_statement->execute();

        //Get the results
        $result = $role_statement->get_result();

        //Create an array to hold the role
        $role = array();

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $role[] = $row;
        }

        //Return the array of roles
        return $role;
    }

    //Get a role name by ID
    public function getRoleNameById(int $id): string
    {
        //SQL statement to get the role by ID
        $sql = "SELECT name FROM roles WHERE id = ?";

        //Prepare the SQL statement for execution
        $role_statement = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $role_statement->bind_param("i", $id);

        //Execute the statement
        $role_statement->execute();

        //Get the results
        $result = $role_statement->get_result();

        //Create a variable to hold the role name
        $roleName = "";

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $roleName = $row['name'];
        }

        //Return the array of roles
        return $roleName;
    }

    //get permissions IDs by role ID, uses the role_has_permission table
    private function getPermissionsIdByRoleId(int $id): array
    {
        //SQL statement to get the permissions by role ID
        $sql = "SELECT permission_id FROM role_has_permission WHERE role_id = ?";

        //Prepare the SQL statement for execution
        $permission_statement = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $permission_statement->bind_param("i", $id);

        //Execute the statement
        $permission_statement->execute();

        //Get the results
        $result = $permission_statement->get_result();

        //Create an array to hold the permissions
        $permissions = array();

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $permissions[] = $row['permission_id'];
        }

        //Return the array of permissions
        return $permissions;
    }

    //get list of permission objects using the permissions class and the getPermissionById function
    public function getRolePermissions(int $id): array
    {
        //new permissions array
        $permissions = array();
        //get the permissions IDs by role ID
        $permissions = $this->getPermissionsIdByRoleId($id);

        //new permission class
        $permission = new Permission();

        //new permissions array
        $rolePermissions = array();

        //loop through the permissions IDs
        foreach ($permissions as $permissionId) {
            //get the permission by ID
            $rolePermissions[] = $permission->getPermissionById($permissionId);
        }

        //return the permissions array
        return $rolePermissions;
    }

    //validate a role exists by ID
    public function validateRoleById(int $id): bool
    {
        //SQL statement to get the role by ID
        $sql = "SELECT * FROM roles WHERE id = ?";

        //Prepare the SQL statement for execution
        $role_statement = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $role_statement->bind_param("i", $id);

        //Execute the statement
        $role_statement->execute();

        //Get the results
        $result = $role_statement->get_result();

        //Create a variable to hold the role name
        $roleName = "";

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $roleName = $row['name'];
        }

        //if the role name is empty, return false
        if (empty($roleName)) {
            return false;
        }

        //return true
        return true;
    }

    //give a role a permission
    public function giveRolePermission(int $roleId, int $permissionId): bool
    {
        //get list of permissions for the role
        $permissions = $this->getPermissionsIdByRoleId($roleId);

        //check if the permission is already assigned to the role
        if (in_array($permissionId, $permissions)) {
            //return false
            return false;
        } else {
            //check if the permission exists
            $allPermissions = new Permission();
            $permissionsList = $allPermissions->getAllPermissions();
            //is the permission ID in the list of permissions
            if (!in_array($permissionId, $permissionsList)) {
                //return false
                return false;
            } else {
                //SQL statement to give a role a permission
                $sql = "INSERT INTO role_has_permission (role_id, permission_id) VALUES (?, ?)";

                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Bind the parameters to the SQL statement
                $role_statement->bind_param("ii", $roleId, $permissionId);

                //Execute the statement
                $role_statement->execute();

                //if the statement was successful, return true
                if ($role_statement) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
}
