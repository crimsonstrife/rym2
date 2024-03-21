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

    //Get all the roles
    public function getAllRoles(): array
    {
        //SQL statement to get all the roles
        $sql = "SELECT * FROM roles";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $results = $stmt->get_result();

        //Create an array to hold the roles
        $roles = array();

        //if there are results, loop through them and add them to the array
        if ($results) {
            while ($row = $results->fetch_assoc()) {
                $roles[] = $row;
            }
        }

        //Return the array of roles
        return $roles;
    }

    //Get a role by ID
    public function getRoleById(int $roleID): array
    {
        //SQL statement to get the role by ID
        $sql = "SELECT * FROM roles WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("i", $roleID);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

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
    public function getRoleNameById(int $roleID): string
    {
        //SQL statement to get the role by ID
        $sql = "SELECT name FROM roles WHERE id = ?";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("i", $roleID);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create a variable to hold the role name
        $roleName = "";

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $roleName = $row['name'];
        }

        //Return the array of roles
        return $roleName;
    }

    /**
     * Get Role Permissions
     * Get a list of permission IDs for a role by role ID
     *
     * @param int $roleID The ID of the role to get the permissions for
     * @return array An array of permissions for the role
     */
    private function getPermissionsIdByRoleId(int $roleID): array
    {
        //SQL statement to get the permissions by role ID
        $sql = "SELECT permission_id FROM role_has_permission WHERE role_id = ?";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("i", $roleID);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create an array to hold the permissions
        $permissions = array();

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $permissions[] = $row['permission_id'];
        }

        //Return the array of permissions
        return $permissions;
    }

    /**
     * Get Role Permissions
     * @param int $roleID
     * @return array
     */
    public function getRolePermissions(int $roleID): array
    {
        //new permissions array
        $permissions = array();

        //get the permissions IDs by role ID
        $permissions = $this->getPermissionsIdByRoleId($roleID);

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

    /**
     * Add a Permission to a Role
     *
     * @param int $roleId The ID of the role to add the permission to
     * @param int $permissionId The ID of the permission to add to the role
     * @param int $userId The ID of the user adding the permission to the role
     *
     * @return bool True if the permission was added, false if not
     */
    private function giveRolePermission(int $roleId, int $permissionId, int $userId): bool
    {
        // Get the current date and time
        $date = date('Y-m-d H:i:s');

        // Check if the permission is already assigned to the role
        if ($this->isPermissionAssigned($roleId, $permissionId)) {
            return true;
        }

        // Check if the permission exists
        if (!$this->isPermissionExists($permissionId)) {
            return false;
        }

        // SQL statement to give a role a permission
        $sql = "INSERT INTO role_has_permission (role_id, permission_id, created_at, created_by, updated_at, updated_by) VALUES (?, ?, ?, ?, ?, ?)";

        // Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        // Bind the parameters to the SQL statement
        $stmt->bind_param("iisisi", $roleId, $permissionId, $date, $userId, $date, $userId);

        // Execute the statement
        $stmt->execute();

        // Check the result
        $result = $stmt->affected_rows > 0;

        // Log the activity if the statement was successful
        if ($result) {
            $this->logRoleActivity($roleId, "Permission Added", $userId);
        }

        return $result;
    }

    /**
     * Check if a permission is already assigned to a role
     *
     * @param int $roleId The ID of the role
     * @param int $permissionId The ID of the permission
     *
     * @return bool True if the permission is assigned to the role, false otherwise
     */
    private function isPermissionAssigned(int $roleId, int $permissionId): bool
    {
        $permissions = $this->getPermissionsIdByRoleId($roleId);
        return in_array($permissionId, $permissions);
    }

    /**
     * Check if a permission exists
     *
     * @param int $permissionId The ID of the permission
     *
     * @return bool True if the permission exists, false otherwise
     */
    private function isPermissionExists(int $permissionId): bool
    {
        $allPermissions = new Permission();
        $permissionsList = $allPermissions->getAllPermissions();
        $permissionsIds = array_column($permissionsList, 'id');
        return in_array($permissionId, $permissionsIds);
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
    private function removeRolePermission(int $roleId, int $permissionId, int $userId): bool
    {
        // SQL statement to remove a permission from a role
        $sql = "DELETE FROM role_has_permission WHERE role_id = ? AND permission_id = ?";

        // Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        // Bind the parameters to the SQL statement
        $stmt->bind_param("ii", $roleId, $permissionId);

        // Execute the statement
        $stmt->execute();

        // Check the result for rows
        $result = $stmt->affected_rows > 0;

        // Log the activity if the statement was successful
        if ($result) {
            $this->logRoleActivity($roleId, "Permission Removed", $userId);
        }

        return $result;
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
        // Check if the role name already exists
        if ($this->isRoleNameExists($roleName)) {
            return false;
        }

        // Create the role
        $roleId = $this->insertRole($roleName, $createdBy);

        if (!$roleId) {
            return false;
        }

        // Assign permissions to the role
        $this->assignPermissionsToRole($roleId, $permissions, $createdBy);

        // Log the activity
        $this->logRoleActivity($roleId, "Role Created", $createdBy);

        return true;
    }

    /**
     * Check if the role name already exists
     *
     * @param string $roleName The name of the role to check
     *
     * @return bool True if the role name exists, false if not
     */
    private function isRoleNameExists(string $roleName): bool
    {
        //SQL statement to check if the role name exists
        $sql = "SELECT COUNT(*) FROM roles WHERE name = ?";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("s", $roleName);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Get the count of the results
        $count = $result->fetch_row()[0];

        //Return true if the count is greater than 0, false if not
        return $count > 0;
    }

    /**
     * Insert a new role into the database
     *
     * @param string $roleName The name of the role to insert
     * @param int $createdBy The ID of the user creating the role
     *
     * @return int|bool The ID of the inserted role if successful, false if not
     */
    private function insertRole(string $roleName, int $createdBy)
    {
        //get current date and time
        $date = date('Y-m-d H:i:s');

        //SQL statement to insert a new role
        $sql = "INSERT INTO roles (name, created_by, created_at, updated_by, updated_at) VALUES (?, ?, ?, ?, ?)";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("sisis", $roleName, $createdBy, $date, $createdBy, $date);

        //Execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->affected_rows > 0;

        //if the statement was successful, return the ID of the inserted role
        if ($result) {
            $this->logRoleActivity($stmt->insert_id, "Role Created", $createdBy);
            return $stmt->insert_id;
        }

        return false;
    }

    /**
     * Assign permissions to the role
     *
     * @param int $roleId The ID of the role to assign permissions to
     * @param array $permissions The array of permissions to assign
     * @param int $createdBy The ID of the user assigning the permissions
     *
     * @return void
     */
    private function assignPermissionsToRole(int $roleId, array $permissions, int $createdBy): void
    {
        foreach ($permissions as $permission) {
            $this->giveRolePermission($roleId, intval($permission), $createdBy);
        }
    }

    /**
     * Log the activity for the created role
     *
     * @param int $roleId The ID of the created role
     * @param string $action The action that was performed
     * @param int $createdBy The ID of the user creating the role
     *
     * @return void
     */
    private function logRoleActivity(int $roleId, string $action, int $createdBy): void
    {
        $activity = new Activity();
        $activity->logActivity($createdBy, $action, "Role " . strval($roleId) . " was " . $action);
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
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("ssii", $roleName, $date, $roleId, $userId);

        //Execute the statement
        $stmt->execute();

        //If the statement was successful, return true
        if ($stmt) {
            return true;
        }

        return false;
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
        // Get the current permissions for the role
        $currentPermissions = $this->getPermissionsIdByRoleId($roleId);

        // Find the permissions to add and remove
        $permissionsToAdd = array_diff($permissions, $currentPermissions);
        $permissionsToRemove = array_diff($currentPermissions, $permissions);

        // Add new permissions
        foreach ($permissionsToAdd as $permission) {
            $this->giveRolePermission($roleId, intval($permission), $userId);
        }

        // Remove old permissions
        foreach ($permissionsToRemove as $permission) {
            $this->removeRolePermission($roleId, intval($permission), $userId);
        }

        // Return the permissions that were set and not set
        return [
            'set' => ['permissions' => $permissionsToAdd],
            'not_set' => ['permissions' => $permissionsToRemove]
        ];
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
        // Check if role name has changed
        $currentRoleName = $this->getRoleNameById($roleId);

        // If the role name has changed, try to set the role name
        if ($currentRoleName != $roleName) {
            // See if the name already exists
            $roleNameExists = $this->getRoleByName($roleName);

            // If the role name does not exist, set the role name
            if ($roleNameExists == 0) {
                $this->setRoleName($roleId, $userId, $roleName);
            }
        }

        // Set the role permissions if not empty
        if (!empty($permissions)) {
            $rolePermSetArray = $this->setRolePermissions($roleId, $userId, $permissions);

            // Check if the role permissions were set
            if (!empty($rolePermSetArray['not_set']['permissions'])) {
                $this->reportPermissionsSetFailure($rolePermSetArray['not_set']['permissions']);
            }
        }

        return true;
    }

    /**
     * Report Permissions Set Failure
     * Logs the permissions that were not set for a role to a session variable
     *
     * @param array $permissionsSet The array of permissions that were not set
     *
     * @return void
     */
    private function reportPermissionsSetFailure(array $permissionsSet): void
    {
        //instance of the session class
        $session = new Session();

        //set the session variable
        $session->set('permissions_set_failed', $permissionsSet);
    }

    /**
     * Get Role by Name
     *
     * @param string $roleName The name of the role to get
     *
     * @return ?int The ID of the role
     */
    public function getRoleByName(string $roleName): ?int
    {
        //SQL statement to get the role by name
        $sql = "SELECT id FROM roles WHERE name = ?";

        //Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        //Bind the parameters to the SQL statement
        $stmt->bind_param("s", $roleName);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create a variable to hold the role ID
        $roleId = null;

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $roleId = intval($row['id']);
        }

        //Return the role ID
        return $roleId;
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
        //instance of the session class
        $session = new Session();

        //instance of the authenticator class
        $auth = new Authenticator();

        // Check if the role exists
        if (!$auth->validateRoleById($roleId)) {
            return false;
        }

        // Get the current user ID
        $userId = $session->get('user_id') ?? null;

        // Get the permissions for the role
        $permissions = $this->getPermissionsIdByRoleId(intval($roleId));

        // Remove the permissions
        foreach ($permissions as $permission) {
            $this->removeRolePermission(intval($roleId), $permission, intval($userId));
        }

        // SQL statement to delete a role
        $sql = "DELETE FROM roles WHERE id = ?";

        // Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        // Ensure the role ID is an integer
        $roleId = intval($roleId);

        // Bind the parameters to the SQL statement
        $stmt->bind_param("i", $roleId);

        // Execute the statement
        $stmt->execute();

        // Check the result
        if ($stmt->affected_rows > 0) {
            // Log the activity
            $this->logRoleActivity(intval($roleId), "Role Deleted", intval($userId));
            return true;
        }

        return false;
    }
}

/**
 * Role Data Class
 * Contains all the functions for the Role Data Class and handles all role data functions, like meta-data.
 */
class RoleData extends Roles
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
     * Get the date a role was granted a permission
     *
     * @param int $roleId The ID of the role to get the date for
     * @param int $permissionId The ID of the permission to get the date for
     *
     * @return string The date the role was granted the permission
     */
    public function getPermissionGrantDate(int $roleId, int $permissionId): string
    {
        // SQL statement to get the date a role was granted a permission
        $sql = "SELECT created_at FROM role_has_permission WHERE role_id = ? AND permission_id = ?";

        // Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        // Bind the parameters to the SQL statement
        $stmt->bind_param("ii", $roleId, $permissionId);

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = $stmt->get_result();

        //Create a variable to hold the date
        $date = "";

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $date = $row['created_at'];
        }

        // Return the date
        return strval($date);
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
        $stmt = prepareStatement($this->mysqli, $sql);

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
        $stmt = prepareStatement($this->mysqli, $sql);

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
        $stmt = prepareStatement($this->mysqli, $sql);

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
}
