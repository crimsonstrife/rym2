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
    $PORT = strval($_ENV['DB_PORT']);
} else {
    //convert port to a string
    $PORT = strval($_ENV['DB_PORT']);
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

/* Placeholders for error variables */
$errorIsPHPVersion = false;
$errorIsMySQLiExtension = false;
$errorIsDBVarMissing = false;
$errorIsDBConnectionFailed = false;
$errorIsDBNotEmpty = false;
$errorIsTableNotExists = false;
$errorIsMailEnabled = false;
$errorIsMySQLVersion = false;
$errorIsDBConnectionFailed = false;

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
        $testConnection = testDatabaseConnection($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE'], $PORT);
    } catch (Exception $e) {
        // Log the error
        error_log("Failed to connect to the database: " . $e->getMessage());
        //throw an exception if the connection fails
        $errorFound = true;
        $errorIsDBConnectionFailed = true;
        $dbErrorMessage = "Failed to connect to the database: " . $e->getMessage();
        $testConnection = false;
    }
    /* Check if the connection failed */
    if ($testConnection == false) {
        //try to connect to the database if the connection failed, but only if the error is not a failed connection
        if (!$errorIsDBConnectionFailed || !$errorFound) {
            //try to connect to the database
            try {
                //port is a string, need to convert to int
                $PORT = intval($PORT);
                $mysqli = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE'], $PORT);
            } catch (Exception $e) {
                // Log the error
                error_log("Failed to connect to the database: " . $e->getMessage());
                //throw an exception if the connection fails
                $errorFound = true;
                $errorIsDBConnectionFailed = true;
                $dbErrorMessage = "Failed to connect to the database: " . $e->getMessage();
            }
        }

        //check if the error is a failed connection, if not close the connection
        if (!$errorIsDBConnectionFailed) {
            closeDatabaseConnection($mysqli);
        }
    } else {
        /* If the connection was successful, check if the database is empty */
        $mysqli = connectToDatabase($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE'], $PORT);

        /* Query to check if the database is empty */
        $sql = "SELECT * FROM users";

        //try to run the query
        try {
            $result = $mysqli->query($sql);
        } catch (Exception $e) {
            // Log the error
            error_log("Failed to run the query: " . $e->getMessage());
            //throw an exception if the query fails
            $errorFound = true;
            $errorIsDBNotEmpty = true;
            //return an empty result
            $result = null;
        }

        //check if the result is null
        if ($result == null) {
            //if the result is null, the table does not exist
            $errorIsTableNotExists = true;
            //so the database is empty
            $errorIsDBNotEmpty = false;
        } else {
            //if the result is not null, the table exists
            $errorIsTableNotExists = false;
            //so the database is not empty
            $errorIsDBNotEmpty = true;
        }

        /* Check if the MySQL version is greater than or equal to 5.7.0 */
        $mysqlVersion = $mysqli->server_info;
        if (version_compare($mysqlVersion, '5.7.0', '<')) {
            /* If the MySQL version is less than 5.7.0, throw an exception */
            $errorFound = true;
            $errorIsMySQLVersion = true;
            $mysqlErrorMessage = "MySQL version must be greater than or equal to 5.7.0, current version is $mysqlVersion";
        }
        /* Close the connection to the database */
        closeDatabaseConnection($mysqli);
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
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?php echo htmlspecialchars(APP_NAME) ?> | First Time Setup</title>
    <!-- Favicons/Icons and Manifest -->
    <link rel="icon" href="/favicon.ico" sizes="32x32">
    <link rel="icon" href="/icon.svg" type="image/svg+xml">
    <link rel="icon" href="/favicon-16x16.png" sizes="16x16">
    <link rel="icon" href="/favicon-32x32.png" sizes="32x32">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="android-chrome" href="/android-chrome-192x192.png" sizes="192x192">
    <link rel="android-chrome" href="/android-chrome-512x512.png" sizes="512x512">
    <link rel="manifest" href="/site.webmanifest">
    <?php echo includeHeader(); ?>
</head>

<body class="nav-fixed">
    <nav class="top-nav navbar navbar-expand-lg navbar-dark bg-dark schoolBrandedNav">
        <!-- Navbar Brand-->
        <div class="navbar-brand brand-container">
            <span class="brand-text"><a class="navbar-brand ps-3"
                    href="<?php echo APP_URL ?>"><?php echo htmlspecialchars(APP_NAME) ?></a></span>
        </div>
    </nav>
    <main>
        <div id="layout_content" class="w-95 mx-auto nav-less">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="container">
                        <!-- Application Logo -->
                        <div class="text-center">
                            <img src="/icon.svg" alt="Application Logo" class="img-fluid" style="max-width: 200px;">
                            <!-- #region -->
                            <h1 class="mt-3"><?php echo htmlspecialchars(APP_NAME) ?></h1>
                            <p class="lead">First Time Setup</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="container">
                        <?php /* Check if setup has already been run */
                        if (file_exists('ready.php')) { ?>
                        <div class="text-center">
                            <!-- Setup has already been run, notify the user -->
                            <h2>Setup has already been run.</h2>
                            <p>You do not need to load this file directly.</p>
                            <p>If you need to run setup again, please delete the ready.php file and refresh this page.
                            </p>
                            <p>Return to the <a href="index.php">application</a>.</p>
                        </div>
                        <?php } else { ?>
                        <div class="text-center">
                            <!-- Setup has not been run, run the setup process -->
                            <h2><?php echo htmlspecialchars(APP_NAME) ?> Installation</h2>
                            <p>Welcome to <?php echo htmlspecialchars(APP_NAME) ?>.</p>
                            <p>Before you can use the application, you must configure the database and create a user.
                            </p>
                            <h3>Checking the system configuration:</h3>
                        </div>
                        <?php
                            /* Check if there are any errors in the system configuration, show list of pass and fail for each feature*/
                            if ($errorFound) { ?>
                        <p>There were errors found in the system configuration, please fix the following errors
                            before
                            continuing:</p>
                        <?php } ?>
                        <ul>
                            <li>PHP Version: (Must be version 7.2.5 or newer)</li>
                            <li>MySQLi Extension: (Must be enabled)</li>
                            <li>MySQL Version: (Must be version 5.7.0 or newer, only checked if variables are set)
                            </li>
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
                            <li style="color: red;">Database variables are missing from the .env file, will attempt
                                to
                                configure them
                                below.</li>
                            <?php } else {
                                    /* if the database variables are set, was the connection successful? */
                                    if ($errorIsDBConnectionFailed) { ?>
                            <li style="color: red;"><?php echo $dbErrorMessage; ?></li>
                            <?php } else {
                                        /* If the connection was successful, was the database empty? */
                                        if ($errorIsDBNotEmpty) { ?>
                            <li style="color: red;">The database is not empty, please empty the database and try
                                again.
                            </li>
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
                            <input type="text" name="db_host" id="db_host" placeholder="<?php echo $_ENV['DB_HOST']; ?>"
                                required>
                            <br>
                            <label for="db_port">Database Port:</label>
                            <input type="number" name="db_port" id="db_port"
                                placeholder="<?php echo $_ENV['DB_PORT']; ?>" required>
                            <br>
                            <label for="db_database">Database Name:</label>
                            <input type="text" name="db_database" id="db_database"
                                placeholder="<?php echo $_ENV['DB_DATABASE']; ?>" required>
                            <br>
                            <label for="db_username">Database Username:</label>
                            <input type="text" name="db_username" id="db_username"
                                placeholder="<?php echo $_ENV['DB_USERNAME']; ?>" required>
                            <br>
                            <label for="db_password">Database Password:</label>
                            <input type="password" name="db_password" id="db_password"
                                placeholder="<?php echo $_ENV['DB_PASSWORD']; ?>" required>
                            <br>
                            <input type="submit" name="db_submit" value="Submit">
                        </form>
                        <div>
                            <?php
                                /* Check if the form was submitted, if errored notify the user */
                                if (isset($_POST['db_submit'])) {
                                    //try to test the connection
                                    try {
                                        $testConnection = connectToDatabase($_POST['db_host'], $_POST['db_username'], $_POST['db_password'], $_POST['db_database'], $_POST['db_port']);
                                    } catch (Exception $e) {
                                        // Log the error
                                        error_log("Failed to connect to the database: " . $e->getMessage());
                                        //throw an exception if the connection fails
                                        $errorFound = true;
                                        $errorIsDBConnectionFailed = true;
                                        $dbErrorMessage = "Failed to connect to the database: " . $e->getMessage();
                                    }
                                    if ($errorFound) { ?>
                            <p>There were errors found in the system configuration, please fix the following before
                                continuing:</p>
                            <?php if ($errorIsDBConnectionFailed) { ?>
                            <p><?php echo $dbErrorMessage; ?></p>
                            <?php } ?>
                            <?php } ?>
                            <ul>
                                <li>Database Host</li>
                                <li>Database Port</li>
                                <li>Database Name</li>
                                <li>Database Username</li>
                                <li>Database Password</li>
                            </ul>
                            <ul>
                                <?php if (empty($_POST['db_host'])) { ?>
                                <li style="color: red;">Database Host is required</li>
                                <?php } else { ?>
                                <li style="color: green;">Database Host - OK!</li>
                                <?php } ?>
                                <?php if (empty($_POST['db_port'])) { ?>
                                <li style="color: red;">Database Port is required</li>
                                <?php } else { ?>
                                <li style="color: green;">Database Port - OK!</li>
                                <?php } ?>
                                <?php if (empty($_POST['db_database'])) { ?>
                                <li style="color: red;">Database Name is required</li>
                                <?php } else { ?>
                                <li style="color: green;">Database Name - OK!</li>
                                <?php } ?>
                                <?php if (empty($_POST['db_username'])) { ?>
                                <li style="color: red;">Database Username is required</li>
                                <?php } else { ?>
                                <li style="color: green;">Database Username - OK!</li>
                                <?php } ?>
                                <?php if (empty($_POST['db_password'])) { ?>
                                <li style="color: red;">Database Password is required</li>
                                <?php } else { ?>
                                <li style="color: green;">Database Password - OK!</li>
                                <?php } ?>
                            </ul>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } else { ?>
                    <!-- there were no database errors, show the form to have the user approve the table installation -->
                    <h3>Install the database tables:</h3>
                    <div>
                        <form action="setup.php" method="post">
                            <input type="submit" name="install_tables" value="Install Tables">
                        </form>
                        <div>
                            <?php
                                /* Check if the form was submitted, if errored notify the user */
                                if (isset($_POST['install_tables'])) {
                                    //verify the sql file exists in the temp directory
                                    if (file_exists(BASEPATH . '/temp/talentflow.sql')) {
                                        //try to run the sql file
                                        try {
                                            $sql = file_get_contents(BASEPATH . '/temp/talentflow.sql');
                                            $mysqli = connectToDatabase($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE'], $PORT);
                                            $mysqli->multi_query($sql);
                                            //close the connection
                                            closeDatabaseConnection($mysqli);
                                        } catch (Exception $e) {
                                            // Log the error
                                            error_log("Failed to install the database tables: " . $e->getMessage());
                                            //throw an exception if the connection fails
                                            $errorFound = true;
                                            $errorIsDBConnectionFailed = true;
                                            $dbErrorMessage = "Failed to install the database tables: " . $e->getMessage();
                                        }
                                    } else {
                                        //if the file does not exist, throw an exception
                                        $errorFound = true;
                                        $errorIsDBConnectionFailed = true;
                                        $dbErrorMessage = "The database template file does not exist";
                                    }
                                    //try to connect to the database
                                    try {
                                        $mysqli = connectToDatabase($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE'], $PORT);
                                    } catch (Exception $e) {
                                        // Log the error
                                        error_log("Failed to connect to the database: " . $e->getMessage());
                                        //throw an exception if the connection fails
                                        $errorFound = true;
                                        $errorIsDBConnectionFailed = true;
                                        $dbErrorMessage = "Failed to connect to the database: " . $e->getMessage();
                                    }
                                    if ($errorFound) { ?>
                            <p>There were errors found in the system configuration, please fix the following before
                                continuing:</p>
                            <?php if ($errorIsDBConnectionFailed) { ?>
                            <p><?php echo $dbErrorMessage; ?></p>
                            <?php } ?>
                            <?php } ?>
                            <ul>
                                <li>Database Tables</li>
                            </ul>
                            <ul>
                                <?php if ($errorIsDBConnectionFailed) { ?>
                                <li style="color: red;"><?php echo $dbErrorMessage; ?></li>
                                <?php } else { ?>
                                <li style="color: green;">Database Connection - OK!</li>
                                <li style="color: green;">Database Tables - OK!</li>
                                <?php } ?>
                            </ul>

                            <?php if (!$errorIsDBConnectionFailed) { ?>
                            <p>Setup is complete, you can now <a href="index.php">launch the application</a>.</p>
                            <p>The default login is: </p>
                            <p>Username: admin</p>
                            <p>Password: admin</p>
                            <p>It is recommended to change the default password after logging in.</p>
                            <?php } ?>
                            <!-- if the database tables were installed, create the ready file -->
                            <?php if (!$errorIsDBConnectionFailed) {
                                        //create the ready file
                                        $readyFile = fopen(BASEPATH . '/ready.php', 'w');
                                        fclose($readyFile);
                                    } ?>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        </div>
    </main>
</body>
<?php echo includeFooter(); ?>
<?php ?>
