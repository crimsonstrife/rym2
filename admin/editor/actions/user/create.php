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

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //get the username from the form
    if (isset($_POST["user_name"])) {
        $username = trim($_POST["user_name"]);
        //prepare the username
        $username = prepareData($username);

        //check if the username is already taken
        $usernameTaken = $user->validateUserByUsername($username);
    }

    //get the email from the form
    if (isset($_POST["user_email"])) {
        $email = trim($_POST["user_email"]);
        //prepare the email
        $email = prepareData($email);

        //check if the email is already taken
        $emailTaken = $user->validateUserByEmail($email);
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

    //check if the passwords match
    if ($password != $passwordConfirm) {
        $passwordError = "Passwords do not match.";
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

    //if no errors, create the user
    if (!$usernameTaken && !$emailTaken && !$passwordError) {
        //create the user
        $userCreated = $user->createUser($email, $username, $password, intval($_SESSION['user_id']), $rolesArray);
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
                    if ($action == 'create') {
                        if ($userCreated) {
                            echo 'User Created';
                        } else {
                            echo 'Error: User Not Created';
                            //if the username is taken, display the error
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
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>