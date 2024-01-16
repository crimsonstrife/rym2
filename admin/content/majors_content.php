<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once('./includes/errors/errorMessage.inc.php');
} // idea from https://stackoverflow.com/a/409515 (user UnkwnTech)

//check that the view dashboard permission is set
if (!isset($hasViewDashboardPermission)) {
    //set the error type
    $thisError = 'CONFIGURATION_ERROR';

    //include the error message file
    include_once('./includes/errors/errorMessage.inc.php');
} else {
    //check that the user has the view dashboard permission
    if (!$hasViewDashboardPermission) {
        //set the error type
        $thisError = 'DASHBOARD_PERMISSION_ERROR';

        //include the error message file
        include_once('./includes/errors/errorMessage.inc.php');
    } else { ?>
<!-- main content -->
<div id="layout_content">
    <main>
        <?php
                if (isset($_GET['major'])) {
                    switch ($_GET['major']) {
                        case 'list':
                            include_once('./view/list/major_list.php');
                            break;
                        case 'add':
                            if (isset($_GET['action'])) {
                                switch ($_GET['action']) {
                                    case 'create':
                                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                            include_once('./editor/actions/major/create.php');
                                        } else {
                                            include_once('./editor/major_edit.php');
                                        }
                                        break;
                                    default:
                                        include_once('./view/list/major_list.php');
                                        break;
                                }
                            } else {
                                include_once('./view/list/major_list.php');
                            }
                            break;
                        case 'edit':
                            if (isset($_GET['action'])) {
                                switch ($_GET['action']) {
                                    case 'edit':
                                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                            include_once('./editor/actions/major/modify.php');
                                        } else {
                                            include_once('./editor/major_edit.php');
                                        }
                                        break;
                                    default:
                                        include_once('./view/list/major_list.php');
                                        break;
                                }
                            } else {
                                include_once('./view/list/major_list.php');
                            }
                            break;
                        case 'single':
                            if (isset($_GET['action'])) {
                                switch ($_GET['action']) {
                                    case 'delete':
                                        include_once('./editor/actions/major/delete.php');
                                        break;
                                    default:
                                        include_once('./view/list/major_list.php');
                                        break;
                                }
                            } else {
                                include_once('./view/list/major_list.php');
                            }
                            break;
                        default:
                            include_once('./view/list/major_list.php');
                            break;
                    }
                } else {
                    include_once('./view/list/major_list.php');
                }
                ?>
    </main>
</div>
<?php
    }
} ?>
