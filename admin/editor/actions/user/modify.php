<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
}

//include the permissions class
$permissionsObject = new Permission();

//include the authenticator class
$auth = new Authenticator();

//include the roles class
$role = new Roles();

//include the user class
$user = new User();

//include the session class
$session = new Session();

//get all the users
$users = $user->getAllUsers();

//get all the roles
$roles = $role->getAllRoles();

//get the action from the url parameter
$action = $_GET['action'];

//placeholders for errors
$usernameError = "";
$emailError = "";
$passwordError = "";
$roleError = "";
$emailTaken = false;
$usernameTaken = false;
$roleIssue = false;

//placeholders for values
$userUpdated = false;
$username = "";
$email = "";
$password = "";
$passwordConfirm = "";
$currentPassword = "";

//if the action is edit, get the user id from the url
if ($action == 'edit') {
    $userId = intval($_GET['id']);
}

/*confirm user has a role with update user permissions*/
//get the id of the update user permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('UPDATE USER');

//boolean to track if the user has the update user permission
$hasPermission = $auth->checkUserPermission(intval($session->get('user_id')), $relevantPermissionID);

//get the is admin permission id
$isAdminPermissionID = $permissionsObject->getPermissionIdByName('IS ADMIN');

//boolean to check if the user has the is admin permission
$hasIsAdminPermission = $auth->checkUserPermission(intval($session->get('user_id')), $isAdminPermissionID);

//get the is super admin permission id
$isSuperAdminPermissionID = $permissionsObject->getPermissionIdByName('IS SUPERADMIN');

//boolean to check if the user has the is super admin permission
$hasIsSuperAdminPermission = $auth->checkUserPermission(intval($session->get('user_id')), $isSuperAdminPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../../includes/errors/errorMessage.inc.php');
} else {

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        //get the username from the form
        if (isset($_POST["user_name"])) {
            $username = trim($_POST["user_name"]);
            //prepare the username
            $username = prepareData($username);

            //check if the username is already taken, if it is validate it against the current user
            if ($action == 'edit') {
                $usernameTaken = $auth->validateUserByUsername($username);
                //if the username is taken, check if it is the current user
                if ($usernameTaken) {
                    //get the current user's username
                    $currentUsername = $user->getUserUsername($userId);
                    //if the username is the same as the current user's username, set the usernameTaken to false
                    if ($username == $currentUsername) {
                        $usernameTaken = false;
                        $username = $currentUsername;
                    }
                }
            } else {
                $usernameTaken = $auth->validateUserByUsername($username);
            }
        }

        //get the email from the form
        if (isset($_POST["user_email"])) {
            $email = trim($_POST["user_email"]);
            //prepare the email
            $email = prepareData($email);

            //check if the email is already taken, if it is validate it against the current user
            if ($action == 'edit') {
                $emailTaken = $auth->validateUserByEmail($email);
                //if the email is taken, check if it is the current user
                if ($emailTaken) {
                    //get the current user's email
                    $currentEmail = $user->getUserEmail($userId);
                    //if the email is the same as the current user's email, set the emailTaken to false
                    if ($email == $currentEmail) {
                        $emailTaken = false;
                    }
                }
            } else {
                $emailTaken = $auth->validateUserByEmail($email);
            }
        }

        //get the password from the form
        if (isset($_POST["user_password"])) {
            $password = trim($_POST["user_password"]);
            //prepare the password
            $password = prepareData($password);
        }

        //get the password confirmation from the form
        if (isset($_POST["confirm_password"])) {
            $passwordConfirm = trim($_POST["confirm_password"]);
            //prepare the password confirmation
            $passwordConfirm = prepareData($passwordConfirm);
        }

        //get the current password from the form for validation of changes
        if (isset($_POST["current_password"])) {
            $currentPassword = trim($_POST["current_password"]);
            //prepare the current password
            $currentPassword = prepareData($currentPassword);
        }

        //if the password and password confirmation are not empty, check if the current password is correct
        if ($password != "" && $passwordConfirm != "" && $currentPassword != "") {
            //get the current user's password
            $currentPasswordHash = $user->getUserPassword($userId);
            //check if the current password is correct
            if (!password_verify($currentPassword, $currentPasswordHash)) {
                $passwordError = "Current password is incorrect.";
            }
        } else if ($password != "" && $passwordConfirm != "" && $currentPassword == "") {
            $passwordError = "Current password is required to update the password.";
        } else if ($password == "" && $passwordConfirm == "" && $currentPassword != "") {
            $passwordError = "New password is required to update the password. If you do not want to update the password, leave the password fields blank.";
        } else {
            //check if the password and password confirmation match
            if ($password != $passwordConfirm) {
                $passwordError = "Passwords do not match.";
            }
        }

        //get all users
        $usersArray = $users;

        //count the number of users
        $userCount = count($usersArray);

        //for each user, check if they are a super admin and increment the count
        $superAdminCount = 0;
        foreach ($usersArray as $userData) {
            //check if the user is a super admin
            $userIsSuperAdmin = $auth->checkUserPermission(intval($userData['id']), $isSuperAdminPermissionID);
            //if the user is a super admin, increment the count
            if ($userIsSuperAdmin) {
                $superAdminCount++;
            }
        }

        //placeholder for role data
        $rolesArray = array();

        //get the roles from the form
        if (isset($_POST["user_roles"])) {
            $rolesArray = $_POST["user_roles"];
        }

        $roleIsSuperAdmin = false;

        //if the user is the only super admin, check if the user is trying to remove the super admin role
        if ($superAdminCount == 1) {
            //check if the user is trying to remove the super admin role
            $userIsSuperAdmin = $auth->checkUserPermission($userId, $isSuperAdminPermissionID);

            if ($userIsSuperAdmin) {
                //loop through the roles array, if none of the roles are the super admin role, set the error
                foreach ($rolesArray as $roleObject) {
                    //see if the role has the is super admin permission
                    $rolePermissions = $role->getRolePermissions($roleObject);
                    foreach ($rolePermissions as $permissionsKey) {
                        foreach ($permissionsKey as $permission) {
                            //check if the role has the is super admin permission
                            if (intval($permission) == $isSuperAdminPermissionID) {
                                $roleIsSuperAdmin = true;
                            }
                        }
                    }
                }
            }

            if ($roleIsSuperAdmin) {
                $roleIssue = true;
                $roleError = "User: " . $username . " is the only super admin. Please assign another user the super admin role before removing the super admin role from this user.";
            }
        }

        //check if the username is taken
        if ($usernameTaken) {
            $usernameError = "Username is already taken.";
        }

        //check if the email is taken
        if ($emailTaken) {
            $emailError = "Email is already taken.";
        }

        //if no errors, update the user
        if (!$usernameTaken && !$emailTaken && !$passwordError && !$roleIssue) {
            //if the action is edit, update the user
            if ($action == 'edit') {
                //if the password is empty, update the user without updating the password
                if ($password == "" && $passwordConfirm == "") {
                    $userUpdated = $user->modifyUser($userId, $email, $username, null, intval($session->get('user_id')), $rolesArray);
                } else {
                    $userUpdated = $user->modifyUser($userId, $email, $username, $password, intval($session->get('user_id')), $rolesArray);
                }
            }
        } else if ($usernameTaken || $emailTaken || $passwordError || $roleIssue) {
            $userUpdated = false;
        } else if (!$usernameTaken && !$emailTaken && !$passwordError && $roleIssue) {
            $arrayHasSuperAdmin = false;
            //if the action is edit, see if the super admin role is being removed
            if ($action == 'edit') {
                //check the roles array, check if the super admin role is missing
                foreach ($rolesArray as $roleObject) {
                    //see if the role has the is super admin permission
                    $rolePermissions = $role->getRolePermissions($roleObject);
                    foreach ($rolePermissions as $permissionsKey) {
                        foreach ($permissionsKey as $permission) {
                            //check if the role has the is super admin permission
                            if (intval($permission) == $isSuperAdminPermissionID) {
                                $arrayHasSuperAdmin = true;
                            }
                        }
                    }
                }
                //if the array still has the super admin role, update the user
                if ($arrayHasSuperAdmin) {
                    $roleIssue = false;
                    $userUpdated = $user->modifyUser($userId, $email, $username, $password, intval($session->get('user_id')), $rolesArray);
                } else {
                    $userUpdated = false;
                }
            }
        }
    }
?>
    <!-- Completion page content -->
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?php echo $username; ?></h1>
        <div class="row">
            <div class="card mb-4">
                <!-- show completion message -->
                <div class="card-header">
                    <div class="card-title">
                        <div>
                            <?php
                            if ($action == 'create') {
                                if ($userUpdated) {
                                    echo '<i class="fa-solid fa-check"></i>';
                                    echo 'User Updated';
                                } else {
                                    echo '<i class="fa-solid fa-x"></i>';
                                    echo 'Error: User Not Updated';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- show completion message -->
                        <div class="col-md-12">
                            <?php
                            if ($action == 'edit') {
                                if ($userUpdated) {
                                    echo '<p>The user: ' . $username . ' has been updated.</p>';
                                } else {
                                    echo '<i class="fa-solid fa-circle-exclamation"></i>';
                                    echo '<p>The user: ' . $username . ' could not be updated.</p>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <!-- show error messages -->
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($action == 'edit') {
                                if (!$userUpdated) {
                                    if ($usernameTaken) {
                                        echo '<br>' . $usernameError;
                                    }
                                    //if the email is taken, display the error
                                    if ($emailTaken) {
                                        echo '<br>' . $emailError;
                                    }
                                    //if the passwords do not match, display the error
                                    if ($passwordError) {
                                        echo '<br>' . $passwordError;
                                    }
                                    //if there is a role issue, display the error
                                    if ($roleIssue) {
                                        echo '<br>' . $roleError;
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <!-- show back buttons -->
                        <div class="col-md-12">
                            <div class="card-buttons">
                                <?php
                                if ($action == 'edit') {
                                    if ($userUpdated) {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=users&user=list" class="btn btn-primary">Return to User List</a></span>';
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=users&user=single&id=' . $user_id . '" class="btn btn-secondary">Go to User</a></span>';
                                    } else {
                                        echo '<span><a href="' . APP_URL . '/admin/dashboard.php?view=users&user=list" class="btn btn-primary">Return to User List</a></span>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
