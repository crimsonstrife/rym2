<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

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

//placeholders for errors
$usernameError = "";
$emailError = "";
$passwordError = "";
$emailTaken = false;
$usernameTaken = false;

//placeholders for values
$username = "";
$email = "";
$password = "";
$passwordConfirm = "";
$currentPassword = "";

//if the action is edit, get the user id from the url
if ($action == 'edit') {
    $userId = intval($_GET['id']);
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //get the username from the form
    if (isset($_POST["user_name"])) {
        $username = trim($_POST["user_name"]);
        //prepare the username
        $username = prepareData($username);

        //check if the username is already taken, if it is validate it against the current user
        if ($action == 'edit') {
            $usernameTaken = $user->validateUserByUsername($username);
            //if the username is taken, check if it is the current user
            if ($usernameTaken) {
                //get the current user's username
                $currentUsername = $user->getUserUsername($userId);
                //if the username is the same as the current user's username, set the usernameTaken to false
                if ($username == $currentUsername) {
                    $usernameTaken = false;
                }
            }
        } else {
            $usernameTaken = $user->validateUserByUsername($username);
        }
    }

    //get the email from the form
    if (isset($_POST["user_email"])) {
        $email = trim($_POST["user_email"]);
        //prepare the email
        $email = prepareData($email);

        //check if the email is already taken, if it is validate it against the current user
        if ($action == 'edit') {
            $emailTaken = $user->validateUserByEmail($email);
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
            $emailTaken = $user->validateUserByEmail($email);
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

    //placeholder for role data
    $rolesArray = array();

    //get the roles from the form
    if (isset($_POST["user_roles"])) {
        $rolesArray = $_POST["user_roles"];
        //prepare the roles
        //$rolesArray = prepareData($rolesArray);
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
    if (!$usernameTaken && !$emailTaken && !$passwordError) {
        //if the action is edit, update the user
        if ($action == 'edit') {
            //if the password is empty, update the user without updating the password
            if ($password == "" && $passwordConfirm == "") {
                $userUpdated = $user->modifyUser($userId, $email, $username, $password, intval($_SESSION['user_id']), $rolesArray);
            }
        }
    }
}
?>
<!-- Completion page content -->
<div class="container-fluid px-4">
    <div class="row">
        <div class="card mb-4">
            <!-- show completion message -->
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-check"></i>
                    <?php
                    if ($action == 'edit') {
                        if ($userUpdated) {
                            echo 'User Updated';
                        } else {
                            echo 'Error: User Not Updated';
                            //if the username is taken, display the error
                            if ($usernameTaken) {
                                echo '<br>' . $usernameError;
                            }
                            //if the email is taken, display the error
                            if ($emailTaken) {
                                echo '<br>' . $emailError;
                            }
                            //if the passwords has an error, display the error
                            if ($passwordError) {
                                echo '<br>' . $passwordError;
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
