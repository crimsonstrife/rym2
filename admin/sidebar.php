<?php

//get the view parameter from the URL
isset($_GET['view']) ? $view = $_GET['view'] : $view = 'dashboard';

?>
<!-- sidebar -->
<div id="layout_nav">
    <nav class="side-nav accordion navbar-dark bg-dark side-nav-dark" id="sidenavAccordion">
        <div class="side-nav-menu">
            <div class="nav">
                <a class="nav-link <?php if ($view == 'dashboard') {
                                        echo 'active';
                                    } ?>" href="<?php echo APP_URL . '/admin/dashboard.php' ?>">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-gauge"></i></div>
                    Dashboard
                </a>
                <?php if ($view == 'reports' || $view == 'activity-log' || $view == 'contact-log') { ?>
                    <a class="nav-link active" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutReports" aria-expanded="true" aria-controls="collapseLayoutReports">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-chart-bar"></i></div>
                        Reports
                        <div class="side-nav-collapse-arrow"><i class="fa-solid fa-chevron-down"></i></div>
                    </a>
                <?php } else { ?>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutReports" aria-expanded="false" aria-controls="collapseLayoutReports">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-chart-bar"></i></div>
                        Reports
                        <div class="side-nav-collapse-arrow"><i class="fa-solid fa-chevron-down"></i></div>
                    </a>
                <?php } ?>
                <?php if ($view == 'reports' || $view == 'activity-log' || $view == 'contact-log') { ?>
                    <div class="collapse show" id="collapseLayoutReports" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <?php } else { ?>
                        <div class="collapse" id="collapseLayoutReports" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <?php } ?>
                        <nav class="side-nav-menu-nested nav">
                            <a class="nav-link <?php if ($view == 'activity-log') {
                                                    echo 'active';
                                                } ?>" href="<?php echo APP_URL . '/admin/dashboard.php?view=activity-log'; ?>">Activity
                                Log</a>
                            <a class="nav-link <?php if ($view == 'contact-log') {
                                                    echo 'active';
                                                } ?>" href="<?php echo APP_URL . '/admin/dashboard.php?view=contact-log'; ?>">Contact
                                Log</a>
                            <a class="nav-link <?php if (($view == 'reports' && isset($_GET['type'])) && ($_GET['type'] == 'Top Degree by School')) {
                                                    echo 'active';
                                                } ?>" href="<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=list&type=' . urlencode('Top Degree by School'); ?>">Top
                                Degrees by School</a>
                            <a class="nav-link <?php if (($view == 'reports' && isset($_GET['type'])) && ($_GET['type'] == 'Top Field by School')) {
                                                    echo 'active';
                                                } ?>" href="<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=list&type=' . urlencode('Top Field by School'); ?>">Top
                                Field by School</a>
                            <a class="nav-link <?php if (($view == 'reports' && isset($_GET['type'])) && ($_GET['type'] == 'Jobs by Field')) {
                                                    echo 'active';
                                                } ?>" href="<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=list&type=' . urlencode('Jobs by Field'); ?>">Jobs
                                by
                                Field</a>
                            <a class="nav-link <?php if (($view == 'reports' && isset($_GET['type'])) && ($_GET['type'] == 'Contact Follow-Up Percentage')) {
                                                    echo 'active';
                                                } ?>" href="<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=list&type=' . urlencode('Contact Follow-Up Percentage'); ?>">Contact
                                Follow-Up Percentage</a>
                        </nav>
                        </div>
                        <a class="nav-link <?php if ($view == 'events') {
                                                echo 'active';
                                            } ?>" href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=list' ?>">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-calendar-day"></i></div>
                            Events
                        </a>
                        <a class="nav-link <?php if ($view == 'schools') {
                                                echo 'active';
                                            } ?>" href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=list' ?>">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-school"></i></div>
                            Schools
                        </a>
                        <a class="nav-link <?php if ($view == 'students') {
                                                echo 'active';
                                            } ?>" href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=list' ?>">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-people-group"></i></div>
                            Students
                        </a>
                        <?php if ($view == 'majors' || $view == 'degrees') { ?>
                            <a class="nav-link active" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutDegrees" aria-expanded="true" aria-controls="collapseLayoutDegrees">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                                Degree Programs
                                <div class="side-nav-collapse-arrow"><i class="fa-solid fa-chevron-down"></i></div>
                            </a>
                        <?php } else { ?>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutDegrees" aria-expanded="false" aria-controls="collapseLayoutDegrees">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                                Degree Programs
                                <div class="side-nav-collapse-arrow"><i class="fa-solid fa-chevron-down"></i></div>
                            </a>
                        <?php } ?>
                        <?php if ($view == 'majors' || $view == 'degrees') { ?>
                            <div class="collapse show" id="collapseLayoutDegrees" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <?php } else { ?>
                                <div class="collapse" id="collapseLayoutDegrees" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <?php } ?>
                                <nav class="side-nav-menu-nested nav">
                                    <a class="nav-link <?php if ($view == 'majors') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo APP_URL . '/admin/dashboard.php?view=majors&major=list' ?>">Majors</a>
                                    <a class="nav-link <?php if ($view == 'degrees') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo APP_URL . '/admin/dashboard.php?view=degrees&degree=list' ?>">Degrees</a>
                                </nav>
                                </div>
                                <a class="nav-link <?php if ($view == 'jobs') {
                                                        echo 'active';
                                                    } ?>" href="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=list' ?>">
                                    <div class="sb-nav-link-icon"><i class="fa-solid fa-briefcase"></i></div>
                                    Jobs/Internships
                                </a>
                                <a class="nav-link <?php if ($view == 'subjects') {
                                                        echo 'active';
                                                    } ?>" href="<?php echo APP_URL . '/admin/dashboard.php?view=subjects&subject=list' ?>">
                                    <div class="sb-nav-link-icon"><i class="fa-solid fa-book"></i></div>
                                    Subjects/Fields
                                </a>
                                <a class="nav-link <?php if ($view == 'media') {
                                                        echo 'active';
                                                    } ?>" href="<?php echo APP_URL . '/admin/dashboard.php?view=media&media=list' ?>">
                                    <div class="sb-nav-link-icon"><i class="fa-solid fa-photo-film"></i></div>
                                    Media
                                </a>
                                <?php if ($view == 'users' || $view == 'roles') { ?>
                                    <a class="nav-link active" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutUsers" aria-expanded="true" aria-controls="collapseLayoutUsers">
                                        <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                                        Users/Roles
                                        <div class="side-nav-collapse-arrow"><i class="fa-solid fa-chevron-down"></i></div>
                                    </a>
                                <?php } else { ?>
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutUsers" aria-expanded="false" aria-controls="collapseLayoutUsers">
                                        <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                                        Users/Roles
                                        <div class="side-nav-collapse-arrow"><i class="fa-solid fa-chevron-down"></i></div>
                                    </a>
                                <?php } ?>
                                <?php if ($view == 'users' || $view == 'roles') { ?>
                                    <div class="collapse show" id="collapseLayoutUsers" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                    <?php } else { ?>
                                        <div class="collapse" id="collapseLayoutUsers" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                        <?php } ?>
                                        <nav class="side-nav-menu-nested nav">
                                            <a class="nav-link <?php if ($view == 'users') {
                                                                    echo 'active';
                                                                } ?>" href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=list' ?>">Users</a>
                                            <a class="nav-link <?php if ($view == 'roles') {
                                                                    echo 'active';
                                                                } ?>" href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=list' ?>">Roles</a>
                                        </nav>
                                        </div>
                                        <a class="nav-link <?php if ($view == 'settings') {
                                                                echo 'active';
                                                            } ?>" href="<?php echo APP_URL . '/admin/dashboard.php?view=settings' ?>">
                                            <div class="sb-nav-link-icon"><i class="fa-solid fa-gears"></i></div>
                                            Settings
                                        </a>
                                    </div>
                            </div>
                            <div class="side-nav-footer">
                                <div class="small">Logged in as:</div>
                                <?php
                                //check if the user is logged in, using the session variable we set on login if it exists
                                if (isset($_SESSION['username'])) {
                                    echo htmlspecialchars($_SESSION["username"]);
                                } else {
                                    //check if there is a user id set in the session
                                    if (isset($_SESSION['user_id'])) {
                                        //if there is a user id set, get the username from the database
                                        $username = $user->getUserUsername(intval($_SESSION['user_id']));
                                        echo htmlspecialchars($username);
                                    } else {
                                        //if there is no user id set, display anonymous, followed by an error message
                                        echo 'anonymous';
                                        echo '<br>';
                                        $thisError = constant('USER_NOT_FOUND');

                                        //get the error message and code from the error array
                                        $errorMessage = $thisError['message'];
                                        $errorCode = $thisError['code'];

                                        //format the error message for the activity log
                                        $errorString = 'CODE: [' . $errorCode . ']- AT: ' . $currentURL . '';

                                        //log the error
                                        $activityLog->logActivity(null, 'ERROR', $errorString);

                                        //display the error message and code
                                        echo $errorCode . ': ' . $errorMessage;
                                        echo '<br>';
                                    }
                                }
                                ?>
                            </div>
    </nav>
</div>
