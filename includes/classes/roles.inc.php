<?php

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

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* Include the base application config file */

require_once(__DIR__ . '/../../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');

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
            $role = $row;
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

    /**
     * Add a Permission to a Role
     *
     * @param int $roleId The ID of the role to add the permission to
     * @param int $permissionId The ID of the permission to add to the role
     * @param int $userId The ID of the user adding the permission to the role
     *
     * @return bool True if the permission was added, false if not
     */
    public function giveRolePermission(int $roleId, int $permissionId, int $userId): bool
    {
        //get the current date and time
        $date = date('Y-m-d H:i:s');

        //get list of permissions for the role
        $permissions = $this->getPermissionsIdByRoleId($roleId);

        //debug
        error_log("Permissions: " . print_r($permissions, true));

        //check if the permission is already assigned to the role
        if (in_array($permissionId, $permissions)) {

            //return true as the permission is already assigned to the role
            return true;
        } else {
            //check if the permission exists
            $allPermissions = new Permission();
            $permissionsList = $allPermissions->getAllPermissions();

            //trim out the permission IDs
            foreach ($permissionsList as $key => $value) {
                $permissionsList[$key] = $value['id'];
            }

            //is the permission ID in the list of permissions
            if (!in_array($permissionId, $permissionsList)) {

                //return false
                return false;
            } else {
                //SQL statement to give a role a permission
                $sql = "INSERT INTO role_has_permission (role_id, permission_id, created_at, created_by, updated_at, updated_by) VALUES (?, ?, ?, ?, ?, ?)";

                //Prepare the SQL statement for execution
                $role_statement = $this->mysqli->prepare($sql);

                //Bind the parameters to the SQL statement
                $role_statement->bind_param("iisisi", $roleId, $permissionId, $date, $userId, $date, $userId);

                //Execute the statement
                $role_statement->execute();

                //check the result
                if ($role_statement->affected_rows > 0) {
                    $result = true;
                } else {
                    $result = false;
                }

                //if the statement was successful, return true
                if ($result) {
                    //log the activity
                    $activity = new Activity();
                    $activity->logActivity($userId, "Role " . strval($roleId) . " was given permission " . strval($permissionId), "Role " . strval($roleId) . " was given permission " . strval($permissionId));
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Remove a Permission from a Role
     *
     * @param int $roleId The ID of the role to remove the permission from
     * @param int $permissionId The ID of the permission to remove from the role
     * @param int $userId The ID of the user removing the permission from the role
     *
     * @return bool True if the permission was removed, false if not
     */
    public function removeRolePermission(int $roleId, int $permissionId, int $userId): bool
    {
        //get the current date and time
        $date = date('Y-m-d H:i:s');

        //SQL statement to remove a permission from a role
        $sql = "DELETE FROM role_has_permission WHERE role_id = ? AND permission_id = ?";

        //Prepare the SQL statement for execution
        $role_statement = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $role_statement->bind_param("ii", $roleId, $permissionId);

        //Execute the statement
        $role_statement->execute();

        //check the result
        if ($role_statement->affected_rows > 0) {
            $result = true;
        } else {
            $result = false;
        }

        //if the statement was successful, return true
        if ($result) {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($userId, "Role " . strval($roleId) . " was removed permission " . strval($permissionId), "Role " . strval($roleId) . " was removed permission " . strval($permissionId));
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get Users with Role
     * Get a list of users with a specific role by role ID
     *
     * @param int $roleId The ID of the role to get the users for
     * @return array An array of users with the role
     */
    public function getUsersWithRole(int $roleId): array
    {
        //SQL statement to get the users with a specific role
        $sql = "SELECT * FROM user_has_role WHERE role_id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("i", $roleId);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create an array to hold the users
        $users = array();

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        //Return the array of users
        return $users;
    }

    /**
     * Get the Date a Role was Given to a User
     *
     * @param int $userId The ID of the user to get the role date for
     * @param int $roleId The ID of the role to get the date for
     *
     * @return string The date the role was given to the user
     */
    public function getUserRoleGivenDate(int $userId, int $roleId): string
    {
        //SQL statement to get the date a role was given to a user
        $sql = "SELECT created_at FROM user_has_role WHERE user_id = ? AND role_id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("ii", $userId, $roleId);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create a variable to hold the date
        $date = "";

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $date = $row['created_at'];
        }

        //Return the date
        return $date;
    }

    /**
     * Get the Date a Role was Modified on a User
     *
     * @param int $userId The ID of the user to get the role date for
     * @param int $roleId The ID of the role to get the date for
     *
     * @return string The date the role was modified on the user
     */
    public function getUserRoleModifiedDate(int $userId, int $roleId): string
    {
        //SQL statement to get the date a role was modified on a user
        $sql = "SELECT updated_at FROM user_has_role WHERE user_id = ? AND role_id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("ii", $userId, $roleId);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create a variable to hold the date
        $date = "";

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $date = $row['updated_at'];
        }

        //Return the date
        return $date;
    }

    /**
     * Create a Role
     *
     * @param string $roleName The name of the role to create
     * @param int $createdBy The ID of the user creating the role
     * @param array $permissions The array of permissions to assign to the role
     *
     * @return bool True if the role was created, false if not
     */
    public function createRole(string $roleName, int $createdBy, array $permissions): bool
    {
        //get current date and time
        $date = date('Y-m-d H:i:s');

        //check if the role name already exists
        $roleNameExists = $this->getRoleByName($roleName);

        //if the role name does not exist, create the role
        if ($roleNameExists == 0 || $roleNameExists == "" || $roleNameExists == null) {
            //SQL statement to create a role
            $sql = "INSERT INTO roles (name, created_by, created_at, updated_by, updated_at) VALUES (?, ?, ?, ?, ?)";

            //Prepare the SQL statement for execution
            $stmt = $this->mysqli->prepare($sql);

            //Bind the parameters to the SQL statement
            $stmt->bind_param("sisis", $roleName, $createdBy, $date, $createdBy, $date);

            //Execute the statement
            $stmt->execute();

            //Get the ID of the role
            $roleId = $stmt->insert_id;

            //Loop through the permissions and assign them to the role
            foreach ($permissions as $permission) {
                //debug
                //error_log("Permission: " . $permission);

                $permissionAdded = $this->giveRolePermission($roleId, intval($permission), $createdBy);
                //debug
                //error_log("Permission Added: " . strval($permissionAdded));
                //log the activity
                $activity = new Activity();
                if ($permissionAdded) {
                    $activity->logActivity($createdBy, "Role Updated", "Role " . strval($roleId) . " was given permission " . strval($permission));
                } else {
                    $activity->logActivity($createdBy, "Role Not Updated", "Role " . strval($roleId) . " was not given permission " . strval($permission));
                }
            }

            //If the statement was successful, return true
            if ($stmt) {
                //log the activity
                $activity = new Activity();
                $activity->logActivity($createdBy, "Role Created", "Role " . strval($roleId) . " was created");
                //return true
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Set Role Name
     *
     * @param int $roleId The ID of the role to set the name for
     * @param int $userId The ID of the user setting the role name
     * @param string $roleName The name of the role to set
     *
     * @return bool True if the role name was set, false if not
     */
    public function setRoleName(int $roleId, int $userId, string $roleName): bool
    {
        //get current date and time
        $date = date('Y-m-d H:i:s');

        //SQL statement to set the role name
        $sql = "UPDATE roles SET name = ?, updated_at = ?, updated_by = ? WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("ssii", $roleName, $date, $roleId, $userId);

        //Execute the statement
        $stmt->execute();

        //If the statement was successful, return true
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set Role Permissions
     *
     * @param int $roleId The ID of the role to set the permissions for
     * @param int $userId The ID of the user setting the permissions
     * @param array $permissions The array of permissions to set for the role
     *
     * @return array if the role permissions were set, and which ones were set or not.
     */
    public function setRolePermissions(int $roleId, int $userId, array $permissions): array
    {
        //get current date and time
        $date = date('Y-m-d H:i:s');

        //array of permissions that were set or not
        $permissionsSet = array(
            "set" => array(
                "permissions" => array()
            ),
            "not_set" => array(
                "permissions" => array()
            )
        );

        //get the current permissions for the role
        $currentPermissions = $this->getPermissionsIdByRoleId($roleId);

        /*loop through the permissions, determine if they are being added or removed*/
        //for each permission, if the permission is not in the current permissions, add it
        foreach ($permissions as $permission) {
            //if the permission is not in the current permissions, add it
            if (!in_array($permission, $currentPermissions)) {
                //give the role the permission
                $successful = $this->giveRolePermission($roleId, intval($permission), $userId);

                //if the permission was set, add it to the permissionsSet array
                if ($successful) {
                    $permissionsSet['set']['permissions'][] = $permission;
                } else {
                    $permissionsSet['not_set']['permissions'][] = $permission;
                }
            }
        }

        //for each of the current permissions, if the permission is not in the new permissions, remove it
        foreach ($currentPermissions as $permission) {
            //if the permission is not in the new permissions, remove it
            if (!in_array($permission, $permissions)) {
                //remove the permission from the role
                $successful = $this->removeRolePermission($roleId, intval($permission), $userId);

                //if the permission was set, add it to the permissionsSet array
                if ($successful) {
                    $permissionsSet['set']['permissions'][] = $permission;
                } else {
                    $permissionsSet['not_set']['permissions'][] = $permission;
                }
            }
        }

        //return the permissionsSet array
        return $permissionsSet;
    }

    /**
     * Update Role
     *
     * @param int $roleId The ID of the role to update
     * @param int $userId The ID of the user updating the role
     * @param string $roleName The name of the role to update
     * @param array $permissions The array of permissions to update for the role
     *
     * @return bool True if the role was updated, false if not
     */
    public function updateRole(int $roleId, int $userId, string $roleName, array $permissions): bool
    {
        //check if role name has changed
        $currentRoleName = $this->getRoleNameById($roleId);

        //if the role name has changed, try to set the role name
        if ($currentRoleName != $roleName) {
            //see if the name already exists
            $roleNameExists = $this->getRoleByName($roleName);

            //if the role name does not exist, set the role name
            if ($roleNameExists == 0) {
                $roleNameSet = $this->setRoleName($roleId, $userId, $roleName);
            } else {
                $roleNameSet = false;
            }
        } else {
            $roleNameSet = true;
        }

        //debug
        error_log("Role Name Set?: " . strval($roleNameSet));

        //set the role permissions if not empty
        if (!empty($permissions)) {
            $rolePermissionsSetArray = $this->setRolePermissions($roleId, $userId, $permissions);

            //check if the role permissions were set
            if (empty($rolePermissionsSetArray['not_set']['permissions'])) {
                $rolePermissionsSet = true;
            } else {
                //as long as some of the permissions were set, return true
                if (count($rolePermissionsSetArray['set']['permissions']) > 0) {
                    $rolePermissionsSet = true;
                    $this->reportPermissionsSetFailure($rolePermissionsSetArray['not_set']['permissions']);
                } else {
                    $rolePermissionsSet = false;
                }
            }
        } else {
            $rolePermissionsSet = false;
        }

        //if the role name and permissions were set or the permissions were empty, return true
        if ($roleNameSet && ($rolePermissionsSet || empty($permissions))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Report Permissions Set Failure
     * Logs the permissions that were not set for a role to a session variable
     *
     * @param array $permissionsSet The array of permissions that were not set
     *
     * @return void
     */
    public function reportPermissionsSetFailure(array $permissionsSet): void
    {
        //set the session variable
        $_SESSION['permissions_set_failed'] = $permissionsSet;
    }

    /**
     * Get Role by Name
     *
     * @param string $roleName The name of the role to get
     *
     * @return int The ID of the role
     */
    public function getRoleByName(string $roleName): int
    {
        //SQL statement to get the role by name
        $sql = "SELECT id FROM roles WHERE name = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("s", $roleName);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create a variable to hold the role ID
        $roleId = 0;

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $roleId = $row['id'];
        }

        //Return the role ID
        return intval($roleId);
    }

    /**
     * Get the date a role was granted a permission
     *
     * @param int $roleId The ID of the role to get the date for
     * @param int $permissionId The ID of the permission to get the date for
     *
     * @return string The date the role was granted the permission
     */
    public function getPermissionGrantDate(int $roleId, int $permissionId): string
    {
        //SQL statement to get the date a role was granted a permission
        $sql = "SELECT created_at, updated_at FROM role_has_permission WHERE role_id = ? AND permission_id = ?";

        //Prepare the SQL statement for execution
        $stmt = $this->mysqli->prepare($sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("ii", $roleId, $permissionId);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create a variable to hold the date
        $date = "";

        //loop through the results, and get the most recent date
        while ($row = $result->fetch_assoc()) {
            $creationDate = '';
            $updateDate = '';
            //if the created_at date is not empty, set the date
            if (!empty($row['created_at'])) {
                $creationDate = $row['created_at'];
            }
            //if the updated_at date is not empty, set the date
            if (!empty($row['updated_at'])) {
                $updateDate = $row['updated_at'];
            }

            if ($creationDate != '' && $updateDate != '') {

                //find the most recent date
                if ($creationDate > $updateDate) {
                    $date = strval($creationDate);
                } else {
                    $date = strval($updateDate);
                }
            } else {
                //if the creation date is not empty, set the date
                if ($creationDate != '') {
                    $date = $creationDate;
                }
                //if the update date is not empty, set the date
                if ($updateDate != '') {
                    $date = $updateDate;
                }
            }
        }

        //Return the date
        return $date;
    }

    /**
     * Delete a Role
     * Deletes a role by ID, also removes all permissions from the role
     *
     * @param int $roleId The ID of the role to delete
     *
     * @return bool True if the role was deleted, false if not
     */
    public function deleteRole(int $roleId): bool
    {
        //get current date and time
        $date = date('Y-m-d H:i:s');

        //get current user ID
        $userId = intval($_SESSION['user_id']);

        //check if the role exists
        $roleExists = $this->validateRoleById($roleId);

        //boolean for the result
        $result = false;

        //if the role exists, check if it has any permissions
        if ($roleExists) {
            //get the permissions for the role
            $permissions = $this->getPermissionsIdByRoleId($roleId);

            //if the role has permissions, remove them
            if (!empty($permissions)) {
                //loop through the permissions and remove them
                foreach ($permissions as $permission) {
                    $this->removeRolePermission($roleId, $permission, $userId);
                }

                //SQL statement to delete a role
                $sql = "DELETE FROM roles WHERE id = ?";

                //Prepare the SQL statement for execution
                $stmt = $this->mysqli->prepare($sql);

                //Bind the parameters to the SQL statement
                $stmt->bind_param("i", $roleId);

                //Execute the statement
                $stmt->execute();

                //check the result
                if ($stmt->affected_rows > 0) {
                    $result = true;
                    //log the activity
                    $activity = new Activity();
                    $activity->logActivity($userId, "Role Deleted", "Role " . strval($roleId) . " was deleted");
                } else {
                    $result = false;
                }

                //return the result
                return $result;
            } else {
                //if the role has no permissions, delete the role
                //SQL statement to delete a role
                $sql = "DELETE FROM roles WHERE id = ?";

                //Prepare the SQL statement for execution
                $stmt = $this->mysqli->prepare($sql);

                //Bind the parameters to the SQL statement
                $stmt->bind_param("i", $roleId);

                //Execute the statement
                $stmt->execute();

                //check the result
                if ($stmt->affected_rows > 0) {
                    $result = true;
                    //log the activity
                    $activity = new Activity();
                    $activity->logActivity($userId, "Role Deleted", "Role " . strval($roleId) . " was deleted");
                } else {
                    $result = false;
                }

                //return the result
                return $result;
            }
        } else {
            return $result;
        }
    }
}
