<?php

/**
 * Setup File for the College Recruitment Application
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/10/2023
 *
 * Description: This setup script should help add user credentials and setup the application if it has not been run yet.
 *
 * @package RYM2
 * Filename: setup.php
 * @version 1.0.0
 * @requires PHP 7.2.5+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* Include the base application config file */
require_once(__DIR__ . '/config/app.php');

/* Get the database variables from the .env file, if they don't exist or are empty set placeholders */
if (!isset($_ENV['DB_HOST']) || empty($_ENV['DB_HOST'])) {
    $_ENV['DB_HOST'] = 'localhost';
}
if (!isset($_ENV['DB_PORT']) || empty($_ENV['DB_PORT'])) {
    $_ENV['DB_PORT'] = '3306';
}
if (!isset($_ENV['DB_DATABASE']) || empty($_ENV['DB_DATABASE'])) {
    $_ENV['DB_DATABASE'] = 'capstone_db';
}
if (!isset($_ENV['DB_USERNAME']) || empty($_ENV['DB_USERNAME'])) {
    $_ENV['DB_USERNAME'] = 'capstone_user';
}
if (!isset($_ENV['DB_PASSWORD']) || empty($_ENV['DB_PASSWORD'])) {
    $_ENV['DB_PASSWORD'] = 'password';
}


/* Include the database connector file */
include(BASEPATH . '/includes/connector.inc.php');

/**
 * Check system requirements
 */
$errorFound = false; // Set the errorFound flag to false
/* Check if the PHP version is greater than or equal to 7.2.5 */
$phpVersion = PHP_VERSION;
if (version_compare($phpVersion, '7.2.5', '<')) {
    /* If the PHP version is less than 7.2.5, throw an exception */
    $errorFound = true;
    $errorIsPHPVersion = true;
    $phpErrorMessage = "PHP version must be greater than or equal to 7.2.5, current version is $phpVersion";
}
/* Check if the MySQLi extension is installed */
if (!extension_loaded('mysqli')) {
    /* If the MySQLi extension is not installed, throw an exception */
    $errorFound = true;
    $errorIsMySQLiExtension = true;
    $mysqliErrorMessage = "MySQLi extension is not installed";
}
/* If MySQLi is installed, check if the database variables are set in the .env file */
if (!isset($_ENV['DB_HOST']) || !isset($_ENV['DB_PORT']) || !isset($_ENV['DB_DATABASE']) || !isset($_ENV['DB_USERNAME']) || !isset($_ENV['DB_PASSWORD'])) {
    /* If the database variables are not set, throw an exception */
    $errorFound = true;
    $errorIsDBVarMissing = true;
} else {
    /* If the env vars contain database information, try to connect */
    try {
        $mysqli = connectToDatabase($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE'], $_ENV['DB_PORT']);
    } catch (Exception $e) {
        /* If the connection fails, throw an exception */
        $errorFound = true;
        $errorIsDBConnectionFailed = true;
        $dbErrorMessage = $e->getMessage();
    } finally {
        /* Check the mysql version, get it as a string */
        $mysqlVersion = $mysqli->server_info;
        if ($mysqlVersion != null && version_compare($mysqlVersion, '5.7.0', '<')) {
            /* If the MySQL version is less than 5.7.0, throw an exception */
            $errorFound = true;
            $mysqlErrorMessage = "MySQL version must be greater than or equal to 5.7.0, current version is $mysqlVersion";
        } else {
            /* If the MySQL version is greater than or equal to 5.7.0, check if the database is empty */
            $result = $mysqli->query("SELECT *");
            if ($result->num_rows > 0) {
                /* If the database is not empty, throw an exception */
                $errorFound = true;
                $errorIsDBNotEmpty = true;
            }
        }
        /* Close the connection to the database */
        $mysqli->close();
    }
}
/* Check if mail is enabled */
if (!function_exists('mail')) {
    /* If mail is not enabled, throw an exception */
    $errorFound = true;
    $mailErrorMessage = "PHP Mail is not enabled";
}

?>

<head>
    <meta charset="utf-8">
    <title>College Recruitment Application Setup</title>
</head>

<body>
    <?php /* Check if setup has already been run */
    if (file_exists('ready.php')) { ?>
        <!-- Setup has already been run, notify the user -->
        <h1>Setup has already been run.</h1>
        <p>You do not need to load this file directly.</p>
        <p>If you need to run setup again, please delete the ready.php file and refresh this page.</p>
        <p>Return to the <a href="index.php">application</a>.</p>
    <?php } else { ?>
        <!-- Setup has not been run, run the setup process -->
        <h1>College Recruitment Application Setup</h1>
        <p>Welcome to the College Recruitment Application Setup.</p>
        <p>Before you can use the application, you must configure the database and create a user.</p>
        <h3>Checking the system configuration:</h3>
        <div>
            <?php
            /* Check if there are any errors in the system configuration, show list of pass and fail for each feature*/
            if ($errorFound) { ?>
                <p>There were errors found in the system configuration, please fix the following errors before continuing:</p>
            <?php } ?>
            <ul>
                <li>PHP Version: (Must be version 7.2.5 or newer)</li>
                <li>MySQLi Extension: (Must be enabled)</li>
                <li>MySQL Version: (Must be version 5.7.0 or newer, only checked if variables are set)</li>
                <li>PHP Mail: (Must be enabled)</li>
            </ul>
            <ul>
                <?php if ($errorIsPHPVersion) { ?>
                    <li style="color: red;"><?php echo $phpErrorMessage; ?></li>
                <?php } else { ?>
                    <li style="color: green;">PHP Version: <?php echo $phpVersion; ?> - OK!</li>
                <?php } ?>
                <?php if ($errorIsMySQLiExtension) { ?>
                    <li style="color: red;"><?php echo $mysqliErrorMessage; ?></li>
                <?php } else { ?>
                    <li style="color: green;">MySQLi Extension - OK!</li>
                <?php } ?>
                <?php if ($errorIsDBVarMissing) { ?>
                    <li style="color: red;">Database variables are missing from the .env file, will attempt to configure them
                        below.</li>
                    <?php } else {
                    /* if the database variables are set, was the connection successful? */
                    if ($errorIsDBConnectionFailed) { ?>
                        <li style="color: red;"><?php echo $dbErrorMessage; ?></li>
                        <?php } else {
                        /* If the connection was successful, was the database empty? */
                        if ($errorIsDBNotEmpty) { ?>
                            <li style="color: red;">The database is not empty, please empty the database and try again.</li>
                            <?php } else {
                            /* If the database is empty, was the MySQL version greater than or equal to 5.7.0? */
                            if ($errorIsMySQLVersion) { ?>
                                <li style="color: red;"><?php echo $mysqlErrorMessage; ?></li>
                            <?php } else { ?>
                                <li style="color: green;">MySQL Version: <?php echo $mysqlVersion; ?> - OK!</li>
                <?php }
                        }
                    }
                } ?>
                <?php if ($errorIsMailEnabled) { ?>
                    <li style="color: red;"><?php echo $mailErrorMessage; ?></li>
                <?php } else { ?>
                    <li style="color: green;">PHP Mail - OK!</li>
                <?php } ?>
            </ul>
        </div>
        <!-- there were database errors of any kind, show the form to configure the database -->
        <?php if ($errorFound && $errorIsDBVarMissing || $errorIsMySQLVersion || $errorIsDBConnectionFailed) { ?>
            <h3>Configure the database:</h3>
            <div>
                <form action="setup.php" method="post">
                    <label for="db_host">Database Host:</label>
                    <input type="text" name="db_host" id="db_host" placeholder="<?php echo $_ENV['DB_HOST']; ?>" required>
                    <br>
                    <label for="db_port">Database Port:</label>
                    <input type="number" name="db_port" id="db_port" placeholder="<?php echo $_ENV['DB_PORT']; ?>" required>
                    <br>
                    <label for="db_database">Database Name:</label>
                    <input type="text" name="db_database" id="db_database" placeholder="<?php echo $_ENV['DB_DATABASE']; ?>" required>
                    <br>
                    <label for="db_username">Database Username:</label>
                    <input type="text" name="db_username" id="db_username" placeholder="<?php echo $_ENV['DB_USERNAME']; ?>" required>
                    <br>
                    <label for="db_password">Database Password:</label>
                    <input type="password" name="db_password" id="db_password" placeholder="<?php echo $_ENV['DB_PASSWORD']; ?>" required>
                    <br>
                    <input type="submit" name="submit" value="Submit">
                </form>
                <div>
                    <?php
                    /* Check if the form was submitted, if errored notify the user */
                    $testConnection = connectToDatabase($_POST['db_host'], $_POST['db_username'], $_POST['db_password'], $_POST['db_database'], $_POST['db_port']);
                    if ($testConnection->connect_error) {
                        echo "<p>Connection failed: " . $testConnection->connect_error . "</p>";
                    } else {
                        echo "<p>Connection successful!</p>";
                    }
                    ?>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</body>
<?php ?>
