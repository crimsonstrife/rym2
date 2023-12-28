<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//autoload composer dependencies
require_once __DIR__ . '/../../vendor/autoload.php';

//include the roles class
$role = new Roles();

//user class
$user = new User();

//get all the users
$users = $user->getAllUsers();

//get all the roles
$roles = $role->getAllRoles();

//get the action from the url parameter
$action = $_GET['action'];

//get the logged in user id from the session
$currentUser = intval($_SESSION['user_id']);

//if the action is edit, get the user id from the url parameter
if ($action == 'edit') {
    $userId = intval($_GET['id']);
}

//variable for checking if the logged in user is the same as the user being edited
$editingSelf = false;

//if the action is edit, get the user id from the url parameter
if ($action == 'edit') {
    //check if the logged in user is the same as the user being edited
    if ($currentUser == $userId) {
        //set the editingSelf variable to true
        $editingSelf = true;
    } else {
        //set the editingSelf variable to false
        $editingSelf = false;
    }
}

//if the userId is set, get the user data by id
if (isset($userId)) {
    $userData = $user->getUserById(intval($userId));
}

//if the action is edit, show the user edit form
if ($action == 'edit') { ?>
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?php echo $userData['username']; ?></h1>
        <div class="row">
            <div class="card mb-4">
                <!-- Edit Form -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&user=' . $_GET['user'] . '&action=' . $_GET['action'] . '&id=' . $_GET['id']; ?>" method="post" enctype="multipart/form-data" class="needs-validation">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa-solid fa-user"></i>
                            Edit User
                        </div>
                        <div class="card-buttons">
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=list'; ?>" class="btn btn-primary btn-sm">Back to Users</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- User Details -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <p>
                                        <strong>
                                            <label for="username">Username:</label>
                                        </strong>
                                    </p>
                                    <p>
                                        <input type="text" id="username" name="user_name" class="form-control" value="<?php echo $user->getUserUsername($userId); ?>" placeholder="<?php echo $user->getUserUsername($userId); ?>" required disabled readonly>
                                    </p>
                                    <p>
                                        <strong>
                                            <label for="email">Email:</label>
                                        </strong>
                                    </p>
                                    <p>
                                        <input type="email" id="email" name="user_email" class="form-control" value="<?php echo $user->getUserEmail($userId); ?>" placeholder="<?php echo $user->getUserEmail($userId); ?>" required>
                                    </p>
                                    <p>
                                        <strong>
                                            <label for="password">Password:</label>
                                        </strong>
                                    </p>
                                    <?php if ($editingSelf) {
                                        //hidden input to be used to check if the user is editing their own account on submission
                                        echo '<input type="hidden" name="editing_self" value="true">';

                                        //get the user password by id
                                        $userPass = $user->getUserPassword($userId);

                                        //mask the password with asterisks
                                        $userPass = str_repeat("*", strlen($userPass));
                                    ?>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <!-- current password -->
                                                <input type="password" id="currentPassword_view" name="current_password_view" class="form-control" value="<?php echo $userPass ?>" placeholder="<?php echo $userPass ?>" disabled readonly>
                                            </div>
                                            <br />
                                            <p class="form-text text-muted">Leave blank if you do not wish to change your
                                                password.</p>
                                            <div class="input-group">
                                                <input type="password" id="currentPassword" name="current_password" class="form-control" value="" placeholder="Enter current password">
                                                <button type="button" class="btn btn-secondary" id="showCurrentPassword" onclick="showCurrentPasswordValue()">Show</button>
                                            </div>
                                            <br />
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="password" id="password" name="user_password" class="form-control" value="" placeholder="Enter new password" aria-describedby="passwordHelpBlock">
                                                    <button type="button" class="btn btn-secondary" id="showPassword" onclick="showPasswordValue()">Show</button>
                                                </div>
                                                <small id="passwordHelpBlock" class="form-text text-muted">
                                                    Must be 8-20 characters long.
                                                </small>
                                            </div>
                                            <!-- button to generate a random password -->
                                            <button type="button" class="btn btn-secondary" id="generatePassword" onclick="generateRandomPassword()">Generate Password</button>
                                            <br />
                                            <br />
                                            <div class="input-group">
                                                <input type="password" id="confirmPassword" name="confirm_password" class="form-control" value="" placeholder="Confirm new password">
                                                <button type="button" class="btn btn-secondary" id="showConfirmPassword" onclick="showConfirmPasswordValue()">Show</button>
                                            </div>
                                        </div>
                                    <?php } else {
                                        echo '<input type="hidden" name="editing_self" value="false">';

                                        //get the user password by id
                                        $userPass = $user->getUserPassword($userId);

                                        //mask the password with asterisks
                                        $userPass = str_repeat("*", strlen($userPass));
                                    ?>
                                        <p>
                                            <input type="password" id="password" name="user_password" class="form-control" value="<?php echo $userPass ?>" placeholder="<?php echo $userPass; ?>" disabled>
                                        </p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- User Roles -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <br />
                                    <p>
                                        <strong>
                                            <label for="userRoles">User Roles:</label>
                                        </strong>
                                    </p>
                                    <p>
                                        <select multiple class="form-control form-control-lg" id="userRoles" name="user_roles[]" required>
                                            <?php
                                            //get the user roles
                                            $userRoles = $user->getUserRoles(intval($userId));

                                            //if the user roles array is empty, show all the roles
                                            if (empty($userRoles)) {

                                                //loop through the roles and display the roles
                                                foreach ($roles as $role) {
                                                    echo '<option value="' . $role['id'] . '">' . $role['name'] . '</option>';
                                                }
                                            } else {
                                                //loop through the roles and display the roles, checking if the user has the role in the userRolesArray and setting the selected attribute if it does
                                                foreach ($roles as $role) {
                                                    if (in_array($role, $userRoles)) {
                                                        echo '<option value="' . $role['id'] . '" selected>' . $role['name'] . '</option>';
                                                    } else {
                                                        echo '<option value="' . $role['id'] . '">' . $role['name'] . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" card-footer">
                        <button name="create_Button" type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=list'; ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } else if ($action == 'create') { //else if the action is create, show the user creation form
?>
    <div class="container-fluid px-4">
        <h1 class="mt-4">New User</h1>
        <div class="row">
            <div class="card mb-4">
                <!-- Create Form -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&user=' . $_GET['user'] . '&action=' . $_GET['action']; ?>" method="post" enctype="multipart/form-data" class="needs-validation">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa-solid fa-user"></i>
                            Create User
                        </div>
                        <div class="card-buttons">
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=list'; ?>" class="btn btn-primary btn-sm">Back to Users</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- User Details -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <p>
                                        <strong>
                                            <label for="username">Username:</label>
                                        </strong>
                                    </p>
                                    <p>
                                        <input type="text" id="username" name="user_name" class="form-control" placeholder="Enter username" autocomplete="username" required>
                                    </p>
                                    <p>
                                        <strong>
                                            <label for="email">Email:</label>
                                        </strong>
                                    </p>
                                    <p>
                                        <input type="email" id="email" name="user_email" class="form-control" placeholder="Enter email" autocomplete="email" required>
                                    </p>
                                    <p>
                                        <strong>
                                            <label for="password">Password:</label>
                                        </strong>
                                    </p>
                                    <p>
                                    <div class="input-group">
                                        <input type="password" id="password" name="user_password" class="form-control" placeholder="Enter password" autocomplete="current-password" required>
                                        <button type="button" class="btn btn-secondary" id="showPassword" onclick="showPasswordValue()">Show</button>
                                    </div>
                                    <!-- button to generate a random password -->
                                    <button type="button" class="btn btn-secondary" id="generatePassword" onclick="generateRandomPassword()">Generate Password</button>
                                    </p>
                                    <p>
                                        <strong>
                                            <label for="confirmPassword">Confirm Password:</label>
                                        </strong>
                                    </p>
                                    <p>
                                    <div class="input-group">
                                        <input type="password" id="confirmPassword" name="confirm_password" class="form-control" placeholder="Confirm password" autocomplete="current-password" required>
                                        <button type="button" class="btn btn-secondary" id="showConfirmPassword" onclick="showConfirmPasswordValue()">Show</button>
                                    </div>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <p>
                                        <strong>
                                            <label for="userRoles">User Roles:</label>
                                        </strong>
                                    </p>
                                    <p>
                                        <select multiple class="form-control form-control-lg" id="userRoles" name="user_roles[]" required>
                                            <?php
                                            //loop through the roles and display the roles
                                            foreach ($roles as $role) {
                                                echo '<option value="' . $role['id'] . '">' . $role['name'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" card-footer">
                        <button name="create_Button" type="submit" class="btn btn-primary">Create User</button>
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=list'; ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>
<script src="<?php echo getAssetPath() . 'js/passwords.js'; ?>" type="text/javascript"></script>
