<?php
//instance of the session class
$session = new Session();
//prevent direct access to this file, if there is no user id set in the session - i.e. the user is not logged in
if ($session->check('user_id') === false){
    //set the error type
    $thisError = 'AUTHENTICATION_ERROR';
?>
<div id="layout">
    <?php
        //include the sidebar
        include_once('./sidebar.php');

        //include the error message file
        include_once(__DIR__ . '../../includes/errors/errorMessage.inc.php');

        //include the footer
        include_once('./footer.php'); ?>
</div>
<?php } else {
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

    if (isset($_GET['type'])) {
        //get the type from the request
        $type = $_GET['type'];
    } else {
        //set the type to generic if it is not set
        $type = 'file';
    }

    //if the payload is not null, download the file and close the tab, otherwise just close the tab
    if (!empty($payload)) {
        //json decode the payload
        $payload = json_decode($payload, true);
        //export the array to a csv file
        if (isset($_POST['export'])) {
            exportData($payload, $type);
        } else {
        }
        exit;
    } else {
        //set the error type
        $thisError = 'PAYLOAD_ERROR';

        //include the sidebar
        include_once('./sidebar.php');

        //include the error message file
        include_once(__DIR__ . '../../includes/errors/errorMessage.inc.php');

        //include the footer
        include_once('./footer.php');

        //close the tab after 5 seconds
        echo "<script>setTimeout(function(){window.close();}, 5000);</script>";
    }
    //close the tab
    echo "<script>window.close();</script>";
}
