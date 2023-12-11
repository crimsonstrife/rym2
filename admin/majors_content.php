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
        if (isset($_GET['major'])) {
            switch ($_GET['major']) {
                case 'list':
                    include_once('view/major_list.php');
                    break;
                case 'add':
                    if (isset($_GET['action'])) {
                        switch ($_GET['action']) {
                            case 'create':
                                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                    include_once('editor/actions/major/create.php');
                                } else {
                                    include_once('editor/major_edit.php');
                                }
                                break;
                            default:
                                include_once('view/major_list.php');
                                break;
                        }
                    } else {
                        include_once('view/major_list.php');
                    }
                    break;
                case 'edit':
                    if (isset($_GET['action'])) {
                        switch ($_GET['action']) {
                            case 'edit':
                                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                    include_once('editor/actions/major/modify.php');
                                } else {
                                    include_once('editor/major_edit.php');
                                }
                                break;
                            default:
                                include_once('view/major_list.php');
                                break;
                        }
                    } else {
                        include_once('view/major_list.php');
                    }
                    break;
                case 'single':
                    include_once('view/major_list.php');
                    break;
                default:
                    include_once('view/major_list.php');
                    break;
            }
        } else {
            include_once('view/major_list.php');
        }
        ?>
    </main>
</div>