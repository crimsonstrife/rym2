<?php
/**
 * User Class file for the College Recruitment Application
 * Contains all the functions for the User Class and handles all the user related tasks with the database.
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/16/2023
 *
 * @package RYM2
 * Filename: users.inc.php
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
 * User Class
 * Contains all the functions for the User Class and handles all the user related tasks with the database.
 *
 * @package RYM2
 * @version 1.0.0
 */
class User
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
     * Password hashing function, returns a hashed password using the PASSWORD_DEFAULT algorithm
     * @param string $password
     * @return string
     */
    protected function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify the user's password
     * This is a protected function
     * @param string $password
     * @param string $hash
     * @return bool
     */
    protected function verifyPassword(string $password, string $hash): bool
    {
        $isValid = password_verify($password, $hash);

        //return the result
        if ($isValid) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get just the IDs of the roles for a specific user, returns an array of role IDs
     * @param int $id
     * @throws \Exception
     * @return array
     */
    private function getRoleIDsByUserID(int $id): array
    {
        //SQL statement to get the role IDs by user ID
        $sql = "SELECT role_id FROM user_has_role WHERE user_id = ?";

        //new array to hold the role IDs
        $role_ids = [];

        //Check that mysqli is set
        if (!$this->mysqli) {
            throw new Exception("Failed to connect to the database.");
        }

        //Prepare the SQL statement for execution
        $role_statement = prepareStatement($this->mysqli, $sql);

        //Bind the user ID to the statement
        $role_statement->bind_param("i", $id);

        //Execute the statement
        $role_statement->execute();

        //Get the results
        $result = $role_statement->get_result();

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $role_ids[] = $row['role_id'];
        }

        //return the role IDs
        return $role_ids;
    }

    /**
     * Get the provided user's roles, returns an array of role objects
     * @param int $id
     * @return array
     */
    public function getUserRoles(int $id): array
    {
        //new roles array
        $roles = array();

        //get the role IDs by user ID
        $roles = $this->getRoleIDsByUserID($id);

        //new roles class
        $role = new Roles();

        //new array to hold the role objects
        $userRoles = array();

        //loop through the roles array and get the role objects
        foreach ($roles as $roleId) {
            $roleObject = $role->getRoleById(intval($roleId));
            //add the role object to the array
            $userRoles[] = $roleObject;
        }

        //return the roles array
        return $userRoles;
    }

    /**
     * Get all users from the database
     *
     * @throws \Exception
     * @return array
     */
    public function getAllUsers(): array
    {
        //SQL statement to get all users
        $sql = "SELECT * FROM users";

        //Check that mysqli is set
        if (!$this->mysqli) {
            throw new Exception("Failed to connect to the database.");
        }

        //Prepare the SQL statement for execution
        $stmt = preparestatement($this->mysqli, $sql);

        //Execute the statement
        $stmt->execute();

        //Get the results
        $result = $stmt->get_result();

        //Create an array to hold the users
        $users = [];

        //Loop through the results and add them to the array
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        //Return the array of users
        return $users;
    }

    /**
     * Get a user by ID
     *
     * @param int $id
     * @throws \Exception
     * @return array
     */
    public function getUserById(int $id): array
    {
        // SQL statement to get the user by ID
        $sql = "SELECT * FROM users WHERE id = ?";

        //Check that mysqli is set
        if (!$this->mysqli) {
            throw new Exception("Failed to connect to the database.");
        }

        // Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        // Bind the ID to the statement
        $stmt->bind_param("i", $id);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the user
        $user = $result->fetch_assoc();

        // Return the user
        return $user ?: [];
    }

    /**
     * Get a user's email by ID
     *
     * @param int $id
     * @throws \Exception
     * @return string
     */
    public function getUserEmail(int $id): string
    {
        // SQL statement to get the user's email by ID
        $sql = "SELECT email FROM users WHERE id = ?";

        //Check that mysqli is set
        if (!$this->mysqli) {
            throw new Exception("Failed to connect to the database.");
        }

        // Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        // Bind the ID to the statement
        $stmt->bind_param("i", $id);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the user's email
        $row = $result->fetch_assoc();
        $email = $row['email'] ?? '';

        // Return the user's email
        return $email;
    }

    /**
     * Get user by email
     *
     * @param string $email
     * @return int $user_id
     * @throws \Exception
     */
    public function getUserByEmail(string $email): int
    {
        // SQL statement to get the user's ID by email
        $sql = "SELECT id FROM users WHERE email = ?";

        //Check that mysqli is set
        if (!$this->mysqli) {
            throw new Exception("Failed to connect to the database.");
        }

        // Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        // Bind the email to the statement
        $stmt->bind_param("s", $email);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the user's ID
        $row = $result->fetch_assoc();
        $user_id = $row['id'] ?? 0;

        // Return the user's ID
        return intval($user_id);
    }

    /**
     * Get a user's (hashed) password by ID
     *
     * @param int $id
     * @return string
     * @throws Exception
     */
    public function getUserPassword(int $id): string
    {
        // SQL statement to get the user's password by ID
        $sql = "SELECT password FROM users WHERE id = ?";

        //Check that mysqli is set
        if (!$this->mysqli) {
            throw new Exception("Failed to connect to the database.");
        }

        // Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        // Bind the ID to the statement
        $stmt->bind_param("i", $id);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the first row from the result
        $row = $result->fetch_assoc();

        // Return the user's password or an empty string if the password is not set
        return $row['password'] ?? '';
    }

    /**
     * Get a user's username by ID
     *
     * @param int $id
     * @throws \Exception
     * @return string
     */
    public function getUserUsername(int $id): string
    {
        // SQL statement to get the user's username by ID
        $sql = "SELECT username FROM users WHERE id = ?";

        //Check that mysqli is set
        if (!$this->mysqli) {
            throw new Exception("Failed to connect to the database.");
        }

        // Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        // Bind the ID to the statement
        $stmt->bind_param("i", $id);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the first row from the result
        $row = $result->fetch_assoc();

        // Return the user's username or "SYSTEM" if not found
        return $row['username'] ?? "SYSTEM";
    }

    /**
     * Get a user's ID by username
     *
     * @param string $username
     * @return int
     * @throws \Exception
     */
    public function getUserIdByUsername(string $username): int
    {
        // SQL statement to get the user's ID by username
        $sql = "SELECT id FROM users WHERE username = ? LIMIT 1";

        //Check that mysqli is set
        if (!$this->mysqli) {
            throw new Exception("Failed to connect to the database.");
        }

        // Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        // Bind the username to the statement
        $stmt->bind_param("s", $username);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the first row from the result
        $row = $result->fetch_assoc();

        // Return the user's ID or 0 if not found
        return $row['id'] ?? 0;
    }

    /**
     * Set a user's email
     * @param int $id
     * @param string $email
     * @throws \Exception
     * @return void
     */
    public function setUserEmail(int $id, string $email): void
    {
        // SQL statement to set the user's email by ID
        $sql = "UPDATE users SET email = ? WHERE id = ?";

        //Check that mysqli is set
        if (!$this->mysqli) {
            throw new Exception("Failed to connect to the database.");
        }

        // Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        // Bind the parameters to the SQL statement
        $stmt->bind_param("si", $email, $id);

        // Execute the statement
        $stmt->execute();

        // Log the activity
        $activity = new Activity();
        $activity->logActivity(intval($_SESSION['user_id']), 'Updated User Email', 'User ID: ' . strval($id) . ' User Name: ' . $this->getUserUsername($id) . ' Email: ' . $email);
    }

    /**
     * Set a user's password to a new value
     * @param int $id
     * @param string $password
     * @throws \Exception
     * @return void
     */
    public function setUserPassword(int $id, string $password): void
    {
        // Hash the password
        $password = $this->hashPassword($password);

        // SQL statement to set the user's password by ID
        $sql = "UPDATE users SET password = ? WHERE id = ?";

        //Check that mysqli is set
        if (!$this->mysqli) {
            throw new Exception("Failed to connect to the database.");
        }

        // Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        // Bind the parameters to the SQL statement
        $stmt->bind_param("si", $password, $id);

        // Execute the statement
        $stmt->execute();

        // Log the activity
        $activity = new Activity();
        $activity->logActivity(intval($_SESSION['user_id']), 'Updated User Password', 'User ID: ' . strval($id) . ' User Name: ' . $this->getUserUsername($id));
    }

    /**
     * Set a user's username to a new value
     * @param int $id
     * @param string $username
     * @throws \Exception
     * @return void
     */
    public function setUserUsername(int $id, string $username): void
    {
        // SQL statement to set the user's username by ID
        $sql = "UPDATE users SET username = ? WHERE id = ?";

        // Get the old username
        $old_username = $this->getUserUsername($id);

        //Check that mysqli is set
        if (!$this->mysqli) {
            throw new Exception("Failed to connect to the database.");
        }

        // Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        // Bind the parameters to the SQL statement
        $stmt->bind_param("si", $username, $id);

        // Execute the statement
        $stmt->execute();

        // Log the activity
        $activity = new Activity();
        $activity->logActivity(intval($_SESSION['user_id']), 'Updated User Username', 'User ID: ' . strval($id) . ' User Name: ' . $username . ' Old Username: ' . $old_username);
    }

    /**
     * Add a new user to the database
     * @param string $email
     * @param string $password
     * @param string $username
     * @param int $created_by
     * @throws \Exception
     * @return int
     */
    private function addUser(string $email, string $password, string $username, int $created_by = null): int
    {
        // Hash the password
        $password = $this->hashPassword($password);

        // Check that mysqli is set
        if (!isset($this->mysqli)) {
            throw new Exception("Failed to connect to the database");
        }

        // SQL statement to add a user
        $sql = "INSERT INTO users (email, password, username, created_at, updated_at, created_by, updated_by)
                VALUES (?, ?, ?, NOW(), NOW(), ?, ?)";

        // Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        // Bind the parameters to the SQL statement
        $stmt->bind_param("ssssii", $email, $password, $username, $created_by, $created_by);

        // Execute the statement
        $stmt->execute();

        // Return the user ID
        return $stmt->insert_id;
    }

    /**
     * Delete a user by ID
     * @param int $user_id
     * @return bool
     * @throws \Exception
     */
    public function deleteUser(int $user_id): bool
    {
        // Check that mysqli is set
        if (!isset($this->mysqli)) {
            throw new Exception("Failed to connect to the database");
        }

        // Prepare the SQL statement
        $sql = "DELETE FROM users WHERE id = ?";

        // prepare the sql statement for execution
        $stmt = preparestatement($this->mysqli, $sql);

        // Bind the parameter
        $stmt->bind_param("i", $user_id);

        // Execute the statement
        $stmt->execute();

        // Check the result
        $result = $stmt->affected_rows > 0;

        // Log the user activity if the user was deleted
        if ($result) {
            $activity = new Activity();
            $activity->logActivity(intval($_SESSION['user_id']), 'Deleted User', 'User ID: ' . $user_id . ' User Name: ' . $this->getUserUsername($user_id));
        }

        // Return the result
        return $result;
    }

    /**
     * Set the user's information
     * @param int $id
     * @param string $email
     * @param string $password
     * @param string $username
     * @param int $updated_by
     * @throws \Exception
     * @return void
     */
    public function setUserInfo(int $id, string $email = null, string $password = null, string $username = null, int $updated_by): void
    {
        // Get the current user information if the corresponding parameter is null
        $email = $this->getEmailIfNull($email, $id);
        $username = $this->getUsernameIfNull($username, $id);

        //does the password need to be hashed?
        if ($password != null) {
            $password = $this->hashPassword($password);
        } else {
            $password = $this->getPasswordIfNull($password, $id);
        }

        // Get the current date and time
        $date = date("Y-m-d H:i:s");

        // Prepare the SQL statement
        $sql = "UPDATE users SET email = ?, password = ?, username = ?, updated_at = ?, updated_by = ? WHERE id = ?";

        // Check that mysqli is set
        if (!isset($this->mysqli)) {
            throw new Exception("Failed to connect to the database");
        }

        // Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        // Bind the parameters to the SQL statement
        $stmt->bind_param("ssssii", $email, $password, $username, $date, $updated_by, $id);

        // Execute the statement
        $stmt->execute();

        // Log the activity
        $this->logUpdateActivity($updated_by, $username);
    }

    /**
     * Assign a role to a user by ID
     * @param int $user_id
     * @param int $role_id
     * @throws \Exception
     * @return void
     */
    private function giveRoleToUser(int $user_id, int $role_id): void
    {
        // Get current date and time
        $date = date("Y-m-d H:i:s");

        // Check that mysqli is set
        if (!isset($this->mysqli)) {
            throw new Exception("Failed to connect to the database");
        }

        // Validate if the role exists by ID
        $auth = new Authenticator();
        if (!$auth->validateRoleById($role_id)) {
            throw new Exception("Role does not exist.");
        }

        // SQL statement to add a role to a user
        $sql = "INSERT INTO user_has_role (user_id, role_id, created_at, updated_at) VALUES (?, ?, ?, ?)";

        // prepare the sql statement for execution
        $stmt = preparestatement($this->mysqli, $sql);

        // Bind the parameters to the SQL statement
        $stmt->bind_param("iiss", $user_id, $role_id, $date, $date);

        // Execute the statement
        $stmt->execute();

        // Log the activity
        $role = new Roles();
        $activity = new Activity();
        $activity->logActivity(null, "User Updated", "Role ID: " . strval($role_id) . " Role Name: " . $role->getRoleNameById($role_id) . " added to User ID: " . strval($user_id) . " User Name: " . $this->getUserUsername($user_id));
    }

    /**
     * Remove a role from a user
     *
     * @param int $user_id The user ID
     * @param int $role_id The role ID
     * @throws \Exception
     * @return void
     */
    private function removeRoleFromUser(int $user_id, int $role_id): void
    {
        // Check that mysqli is set
        if (!isset($this->mysqli)) {
            throw new Exception("Failed to connect to the database.");
        }

        // SQL statement to remove a role from a user
        $sql = "DELETE FROM user_has_role WHERE user_id = ? AND role_id = ?";

        // Prepare the SQL statement for execution
        $stmt = prepareStatement($this->mysqli, $sql);

        // Bind the parameters to the SQL statement
        $stmt->bind_param("ii", $user_id, $role_id);

        // Execute the statement
        $stmt->execute();

        // Log the activity
        $activity = new Activity();
        $activity->logActivity(null, "User Updated", "Role ID: " . strval($role_id) . " removed from User ID: " . strval($user_id) . " User Name: " . $this->getUserUsername($user_id) . "");
    }

    /**
     * Create a user and assign them a role
     *
     * @param string $email The user's email
     * @param string $username The user's username
     * @param string $password The user's password
     * @param int $created_by The user ID of the user who created the user, default is null which will set the user ID to the system, or null
     * @param array $roles The roles to assign to the user, default is an empty array which will assign the user no roles
     *
     * @return bool True if the user was created, false if not
     */
    public function createUser(string $email, string $username, string $password, int $created_by = null, array $roles = array()): bool
    {
        $email = trim($email);
        $username = trim($username);
        $contact = new Contact();
        $user_id = $this->addUser($email, $password, $username, $created_by);

        if ($user_id > 0 && !empty($user_id) && $user_id != null) {
            $this->assignRoles($user_id, $roles);
            $contact->notifyUserCreated($email, $username, $password);
            $this->logUserCreationActivity($created_by, $username);
            return true;
        }

        return false;
    }

    /**
     * Log the user creation activity
     *
     * @param int $created_by The user ID of the user who created the user
     * @param string $username The user's username
     */
    private function logUserCreationActivity(int $created_by, string $username): void
    {
        $activity = new Activity();
        $activity->logActivity($created_by, "User Created.", 'User ' . $username);
    }

    /**
     * Modify a user and or their roles
     *
     * @param int $id The user ID of the user to modify
     * @param string $email The user's email
     * @param string $username The user's username
     * @param string $password The user's password
     * @param int $updated_by The user ID of the user who updated the user, default is null which will set the user ID to the system, or null
     * @param array $roles The roles to assign to the user, default is an empty array which will assign the user no roles
     *
     * @return bool True if the user was modified, false if not
     */
    public function modifyUser(int $id, string $email = null, string $username = null, string $password = null, int $updated_by = null, array $roles = array()): bool
    {
        $email = $this->getEmailIfNull($email, $id);
        $username = $this->getUsernameIfNull($username, $id);
        $password = $this->getPasswordIfNull($password, $id);

        $this->setUserInfo($id, $email, $password, $username, $updated_by);
        $this->assignOrRemoveRoles($id, $roles);

        $this->logUpdateActivity($updated_by, $username);

        return true;
    }

    /**
     * Get a user's email
     *
     * @param int $id The user ID of the user to get the email for
     *
     * @return string The user's email
     */
    private function getEmailIfNull(?string $email, int $id): string
    {
        if ($email === null || empty($email) || $email === "") {
            return $this->getUserEmail($id);
        }

        return trim($email);
    }

    /**
     * Get a user's username
     *
     * @param int $id The user ID of the user to get the username for
     *
     * @return string The user's username
     */
    private function getUsernameIfNull(?string $username, int $id): string
    {
        if ($username === null || empty($username) || $username === "") {
            return $this->getUserUsername($id);
        }

        return trim($username);
    }

    /**
     * Get a user's password
     *
     * @param int $id The user ID of the user to get the password for
     *
     * @return string The user's password
     */
    private function getPasswordIfNull(?string $password, int $id): string
    {
        if ($password === null || empty($password) || $password === "") {
            return $this->getUserPassword($id);
        }

        return $password;
    }

    /**
     * Assign or remove roles from a user
     *
     * @param int $id The user ID of the user to assign or remove roles from
     * @param array $roles The roles to assign to the user
     */
    private function assignOrRemoveRoles(int $id, array $roles): void
    {
        $currentRoleIDs = $this->getRoleIDsByUserID($id);

        if (!empty($roles)) {
            $this->assignRoles($id, $roles, $currentRoleIDs);
            $this->removeRoles($id, $roles, $currentRoleIDs);
        } else {
            $this->removeAllRoles($id, $currentRoleIDs);
        }
    }

    /**
     * Assign roles to a user
     *
     * @param int $id The user ID of the user to assign roles to
     * @param array $roles The roles to assign to the user
     * @param array $currentRoleIDs The current roles for the user, null for new users
     */
    private function assignRoles(int $id, array $roles, array $currentRoleIDs = null ): void
    {
        //check if there are any current roles
        if ($currentRoleIDs === null) {
            foreach ($roles as $role) {
                $this->giveRoleToUser($id, intval($role));
            }
        } else {
            foreach ($roles as $role) {
                if (!in_array($role, $currentRoleIDs)) {
                    $this->giveRoleToUser($id, intval($role));
                }
            }
        }
    }

    /**
     * Remove roles from a user
     *
     * @param int $id The user ID of the user to remove roles from
     * @param array $roles The roles to remove from the user
     * @param array $currentRoleIDs The current roles for the user
     */
    private function removeRoles(int $id, array $roles, array $currentRoleIDs): void
    {
        foreach ($currentRoleIDs as $currentRole) {
            if (!in_array($currentRole, $roles)) {
                $this->removeRoleFromUser($id, intval($currentRole));
            }
        }
    }

    /**
     * Remove all roles from a user
     *
     * @param int $id The user ID of the user to remove all roles from
     * @param array $currentRoleIDs The current roles for the user
     */
    private function removeAllRoles(int $id, array $currentRoleIDs): void
    {
        foreach ($currentRoleIDs as $currentRole) {
            $this->removeRoleFromUser($id, intval($currentRole));
        }
    }

    /**
     * Log the user update activity
     *
     * @param int $updated_by The user ID of the user who updated the user
     * @param string $username The username of the user who was updated
     */
    private function logUpdateActivity(?int $updated_by, string $username): void
    {
        $activity = new Activity();
        $activity->logActivity($updated_by, "User Updated.", 'User: ' . $username . ' updated by User: ' . strval($updated_by));
    }
}
