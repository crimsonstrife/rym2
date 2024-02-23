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

//include the auth class
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
$emailTaken = false;
$usernameTaken = false;

/*confirm user has a role with create user permissions*/
//get the id of the create user permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('CREATE USER');

//boolean to track if the user has the create user permission
$hasPermission = $auth->checkUserPermission(intval($session->get('user_id')), $relevantPermissionID);

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

            //check if the username is already taken
            $usernameTaken = $auth->validateUserByUsername($username);
        }

        //get the email from the form
        if (isset($_POST["user_email"])) {
            $email = trim($_POST["user_email"]);
            //prepare the email
            $email = prepareData($email);

            //check if the email is already taken
            $emailTaken = $auth->validateUserByEmail($email);
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
            $userCreated = $user->createUser($email, $username, $password, intval($session->get('user_id')), $rolesArray);
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
<?php } ?>
