<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
} // idea from https://stackoverflow.com/a/409515 (user UnkwnTech)
?>
<!-- main content -->
<div id="layout_content">
    <main>
        <?php
        if (isset($_GET['report'])) {
            switch ($_GET['report']) {
                case 'list':
                    include_once('view/report_list.php');
                    break;
                case 'single':
                    include_once('view/report_single.php');
                    break;
                default:
                    include_once('view/report_list.php');
                    break;
            }
        } else {
            include_once('view/report_list.php');
        }
        ?>
    </main>
</div>
