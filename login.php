<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes redirect to the admin dashboard
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    header("location: admin/dashboard.php");
    exit;
}

// Include config file
require_once(__DIR__ . '/config/app.php');
// include the database config file
require_once(BASEPATH . '/config/database.php');

// Setup the connection to MySQL
$mysqli = connectToDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

// Define variables and initialize with empty values
$username = $password = "";
$username_error = $password_error = $login_error = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_error = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_error = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_error) && empty($password_error)) {
        // Prepare a select statement
        $loginSQL = "SELECT id, username, password FROM users WHERE username = ?";

        if ($login_statement = $mysqli->prepare($loginSQL)) {
            // Bind variables to the prepared statement as parameters
            $login_statement->bind_param("s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if ($login_statement->execute()) {
                // Store result
                $login_statement->store_result();

                // Check if username exists, if yes then verify password
                if ($login_statement->num_rows == 1) {
                    // Bind result variables
                    $login_statement->bind_result($id, $username, $hashed_password);
                    if ($login_statement->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["logged_in"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            // Redirect user to admin dashboard
                            header("location: admin/dashboard.php");
                        } else {
                            // Password is not valid, display a generic error message
                            $login_error = "Invalid password.";
                        }
                    }
                } else {
                    // Username doesn't exist, display a generic error message
                    $login_error = "Invalid username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $login_statement->close();
        }
    }

    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>

    <body>
        <div>
            <h2>Login</h2>
            <p>Please fill in your credentials to login.</p>
            <?php
        if (!empty($login_error)) {
            echo '<div>' . $login_error . '</div>';
        }
        ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div>
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo $username; ?>">
                    <span><?php echo $username_error; ?></span>
                </div>
                <div>
                    <label>Password</label>
                    <input type="password" name="password">
                    <span><?php echo $password_error; ?></span>
                </div>
                <div>
                    <input type="submit" value="Login">
                </div>
            </form>
        </div>
    </body>

</html>
