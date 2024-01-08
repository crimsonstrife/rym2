<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
} // idea from https://stackoverflow.com/a/409515 (user UnkwnTech)

//check that the view dashboard permission is set
if (!isset($hasViewDashboardPermission)) {
    die('Error: You do not have permission to access this content or there is a configuration error, contact the Administrator.');
} else {
    //check that the user has the view dashboard permission
    if (!$hasViewDashboardPermission) {
        die('Error: You do not have permission to access this content, contact the Administrator.');
    } else {
?>
        <!-- main content -->
        <div id="layout_content">
            <main>
                <?php
                if (isset($_GET['student'])) {
                    switch ($_GET['student']) {
                        case 'list':
                            include_once('./view/list/student_list.php');
                            break;
                        case 'add':
                            if (isset($_GET['action'])) {
                                switch ($_GET['action']) {
                                    default:
                                        include_once('./view/list/student_list.php');
                                        break;
                                }
                            } else {
                                include_once('./view/list/student_list.php');
                            }
                            break;
                        case 'edit':
                            if (isset($_GET['action'])) {
                                switch ($_GET['action']) {
                                    default:
                                        include_once('./view/list/student_list.php');
                                        break;
                                }
                            } else {
                                include_once('./view/list/student_list.php');
                            }
                            break;
                        case 'single':
                            include_once('./view/single/student_single.php');
                            break;
                        default:
                            include_once('./view/list/student_list.php');
                            break;
                    }
                } else {
                    include_once('./view/list/student_list.php');
                }
                ?>
            </main>
        </div>
<?php }
} ?>
