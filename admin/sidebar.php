<?php
?>
<!-- sidebar -->
<div id="layout_nav">
    <nav class="side-nav accordion navbar-dark bg-dark side-nav-dark" id="sidenavAccordion">
        <div class="side-nav-menu">
            <div class="nav">
                <a class="nav-link" href="<?php echo APP_URL . '/admin/dashboard.php' ?>">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-gauge"></i></div>
                    Dashboard
                </a>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutReports"
                    aria-expanded="false" aria-controls="collapseLayoutReports">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-chart-bar"></i></div>
                    Reports
                    <div class="side-nav-collapse-arrow"><i class="fa-solid fa-chevron-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayoutReports" aria-labelledby="headingOne"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="side-nav-menu-nested nav">
                        <a class="nav-link"
                            href="<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=list&type=' . urlencode('Top Degree by School'); ?>">Top
                            Degrees by School</a>
                        <a class="nav-link"
                            href="<?php echo APP_URL . '/admin/dashboard.php?view=reports&report=list&type=' . urlencode('Top Field by School'); ?>">Top
                            Field by School</a>
                        <a class="nav-link" href="<?php echo APP_URL . '/admin/dashboard.php?view=reports' ?>">Major to
                            Field Ratio by School
                        </a>
                        <a class="nav-link" href="<?php echo APP_URL . '/admin/dashboard.php?view=reports' ?>">Jobs by
                            Field</a>
                        <a class="nav-link" href="<?php echo APP_URL . '/admin/dashboard.php?view=reports' ?>">Contact
                            Follow-Up Percentage</a>
                    </nav>
                </div>
                <a class="nav-link" href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=list' ?>">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-calendar-day"></i></div>
                    Events
                </a>
                <a class="nav-link" href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=list' ?>">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-school"></i></div>
                    Schools
                </a>
                <a class="nav-link" href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=list' ?>">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-people-group"></i></div>
                    Students
                </a>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutDegrees"
                    aria-expanded="false" aria-controls="collapseLayoutDegrees">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                    Degree Programs
                    <div class="side-nav-collapse-arrow"><i class="fa-solid fa-chevron-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayoutDegrees" aria-labelledby="headingOne"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="side-nav-menu-nested nav">
                        <a class="nav-link"
                            href="<?php echo APP_URL . '/admin/dashboard.php?view=majors&major=list' ?>">Majors</a>
                        <a class="nav-link"
                            href="<?php echo APP_URL . '/admin/dashboard.php?view=degrees&degree=list' ?>">Degrees</a>
                    </nav>
                </div>
                <a class="nav-link" href="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=list' ?>">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-briefcase"></i></div>
                    Jobs/Internships
                </a>
                <a class="nav-link" href="<?php echo APP_URL . '/admin/dashboard.php?view=subjects&subject=list' ?>">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-book"></i></div>
                    Subjects/Fields
                </a>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutUsers"
                    aria-expanded="false" aria-controls="collapseLayoutUsers">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                    Users/Roles
                    <div class="side-nav-collapse-arrow"><i class="fa-solid fa-chevron-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayoutUsers" aria-labelledby="headingOne"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="side-nav-menu-nested nav">
                        <a class="nav-link"
                            href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=list' ?>">Users</a>
                        <a class="nav-link"
                            href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=list' ?>">Roles</a>
                    </nav>
                </div>
                <a class="nav-link" href="<?php echo APP_URL . '/admin/dashboard.php?view=settings' ?>">
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
                //if the session variable does not exist, use alternative means to ID the user

            }
            ?>
        </div>
    </nav>
</div>
