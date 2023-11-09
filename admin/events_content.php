<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
} // idea from https://stackoverflow.com/a/409515 (user UnkwnTech)
//check for the event id in the url parameter
?>
<!-- main content -->
<div id="layout_content">
    <main>
        <?php
        if (isset($_GET['event'])) {
            switch ($_GET['event']) {
                case 'list':
                    include_once('view/event_list.php');
                    break;
                case 'add':
                    include_once('editor/event_add.php');
                    break;
                case 'edit':
                    include_once('editor/event_edit.php');
                    break;
                case 'single':
                    include_once('view/event_single.php');
                    break;
                default:
                    include_once('view/event_list.php');
                    break;
            }
        } else {
            include_once('view/event_list.php');
        }
        ?>
    </main>
</div>
