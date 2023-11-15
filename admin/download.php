<?php
//prevent direct access to this file
if (isset($_SESSION['user_id'])) {
    die('Error: Invalid request');
} else {
    // Define a constant to control the access of the include files
    define('ISVALIDUSER', true); // idea from https://stackoverflow.com/a/409515 (user UnkwnTech)

    //include the app config file
    require_once(__DIR__ . '../../config/app.php');
    //include the helpers file
    require_once(__DIR__ . '../../includes/utils/helpers.php');

    if (isset($_GET['payload'])) {
        //get the payload from the request
        $payload = $_GET['payload'];
        //base64 decode the payload
        $payload = base64_decode($payload);
        //url decode the payload
        $payload = urldecode($payload);
    } else {
        //set the payload to null
        $payload = null;
    }

    //if the payload is not null, download the file, otherwise close the tab
    if (!empty($payload)) {
        //json decode the payload
        $payload = json_decode($payload, true);
        //export the eventsArray to a csv file
        if (isset($_POST['export'])) {
            exportData($payload, "events");
        } else {
        }
        exit;
    } else {
        echo "payload is empty.";
        //close the tab
        echo "<script>window.close();</script>";
    }
    //close the tab
    echo "<script>window.close();</script>";
}
