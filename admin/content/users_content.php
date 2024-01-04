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
        if (isset($_GET['user'])) {
            switch ($_GET['user']) {
                case 'list':
                    include_once('./view/user_list.php');
                    break;
                case 'add':
                    if (isset($_GET['action'])) {
                        switch ($_GET['action']) {
                            case 'create':
                                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                    include_once('./editor/actions/user/create.php');
                                } else {
                                    include_once('./editor/user_edit.php');
                                }
                                break;
                            default:
                                include_once('./view/user_list.php');
                                break;
                        }
                    } else {
                        include_once('./view/user_list.php');
                    }
                    break;
                case 'edit':
                    if (isset($_GET['action'])) {
                        switch ($_GET['action']) {
                            case 'edit':
                                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                    include_once('./editor/actions/user/modify.php');
                                } else {
                                    include_once('./editor/user_edit.php');
                                }
                                break;
                            default:
                                include_once('./view/user_list.php');
                                break;
                        }
                    } else {
                        include_once('./view/user_list.php');
                    }
                    break;
                case 'single':
                    include_once('./view/user_single.php');
                    break;
                default:
                    include_once('./view/user_list.php');
                    break;
            }
        } else {
            include_once('./view/user_list.php');
        }
        ?>
    </main>
</div>