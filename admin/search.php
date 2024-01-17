<?php
//prevent direct access to this file
if (!isset($_SESSION['user_id'])) {
    //check if the user is logged in
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        //redirect to the login page
        header('Location: ' . APP_URL . '/login.php');
        exit;
    }
} else {
    //include the app config file
    require_once(__DIR__ . '../../config/app.php');
    //include the helpers file
    require_once(__DIR__ . '../../includes/utils/helpers.php');
}

//include the permissions class
$permissionsObject = new Permission();

//include the auth class
$auth = new Authenticator();

//verify the url parameter
if (isset($_GET['view'])) {
    switch ($_GET['view']) {
        case 'search': ?>
<?php //check the post request
            if (isset($_POST['search'])) {
                //get the search term
                $searchTerm = $_POST['searchTerm'];

                //prevent empty search
                if (empty($searchTerm) || $searchTerm == "" || $searchTerm == null || $searchTerm == " ") {
                    $searchTerm = null;
                } else {
                    //prepare the search term
                    $searchTerm = prepareData($searchTerm);
                }

                //include the application class
                $APP = new Application();

                //include the student class
                $studentData = new Student();

                //include the school class
                $schoolData = new School();

                //include the degree class
                $degreeData = new Degree();

                //include the event class
                $eventData = new Event();

                //perform the search
                $searchResults = $APP->search($searchTerm);
            ?>
<!-- main content -->
<div id="layout_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Search</h1>
            <div class="row">
                <div class="card mb-4">
                    <div class="card-header"><i class="fa-solid fa-square-poll-horizontal"></i>Search Results
                    </div>
                    <div class="card-body">
                        <?php if (isset($searchResults) && !empty($searchResults)) { ?>
                        <div class="card-body-section">
                            <!-- Students -->
                            <?php //check if the students key contains any data
                                                if (isset($searchResults['students']) && !empty($searchResults['students'])) {
                                                    // confirm user has a role with read student permissions
                                                    //get the read student permission id
                                                    $readStudentPermissionID = $permissionsObject->getPermissionIdByName('READ STUDENT');

                                                    //boolean to check if the user has the read student permission
                                                    $hasReadStudentPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readStudentPermissionID);

                                                    //prevent the user from seeing the results of the student search if they do not have the read student permission
                                                    if (!$hasReadStudentPermission) {
                                                        //students found, but user does not have read student permission
                                                        echo '<h5>Students</h5>
                                                <div class="table-responsive">';
                                                        echo '<p>Student results may have been found, but you lack permission to view results.</p>';
                                                        echo '</div>';
                                                    } else {
                                                ?>
                            <h5>Students</h5>
                            <div class="table-scroll table-fixedHead table-responsive">
                                <table class="table table-striped table-bordered" id="dataTable" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Major</th>
                                            <th>Degree</th>
                                            <th>School</th>
                                            <th>Graduation Year</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($searchResults['students'] as $student) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                                            <td><?php echo $degreeData->getMajorNameById(intval(htmlspecialchars($student['major']))); ?>
                                            </td>
                                            <td><?php echo $degreeData->getGradeNameById(intval(htmlspecialchars($student['degree']))); ?>
                                            </td>
                                            <td><?php echo $schoolData->getSchoolName(intval(htmlspecialchars($student['school']))); ?>
                                            </td>
                                            <td><?php echo formatDate(htmlspecialchars($student['graduation'])); ?></td>
                                            <td>
                                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=single&id=' . $student['id']; ?>"
                                                    class="btn btn-primary btn-sm"><i class="fa-solid fa-user"></i></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php }
                                                } else {
                                                    //no students found
                                                    echo '<h5>Students</h5>
                                                <div class="table-responsive">';
                                                    echo '<p>No students found.</p>';
                                                    echo '</div>';
                                                } ?>
                            <!-- End Students -->
                            <!-- Schools -->
                            <?php //check if the schools key contains any data
                                                if (isset($searchResults['schools']) && !empty($searchResults['schools'])) {
                                                    // confirm user has a role with read school permissions
                                                    //get the read school permission id
                                                    $readSchoolPermissionID = $permissionsObject->getPermissionIdByName('READ SCHOOL');

                                                    //boolean to check if the user has the read school permission
                                                    $hasReadSchoolPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readSchoolPermissionID);

                                                    //prevent the user from seeing the results of the school search if they do not have the read school permission
                                                    if (!$hasReadSchoolPermission) {
                                                        //schools found, but user does not have read school permission
                                                        echo '<h5>Schools</h5>
                                                <div class="table-responsive">';
                                                        echo '<p>School results may have been found, but you lack permission to view results.</p>';
                                                        echo '</div>';
                                                    } else {
                                                ?>
                            <h5>Schools</h5>
                            <div class="table-scroll table-fixedHead table-responsive">
                                <table class="table table-striped table-bordered" id="dataTable" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>School Name</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Zip</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($searchResults['schools'] as $school) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($school['name']); ?></td>
                                            <td><?php echo htmlspecialchars($school['city']); ?></td>
                                            <td><?php echo htmlspecialchars($school['state']); ?></td>
                                            <td><?php echo htmlspecialchars($school['zipcode']); ?></td>
                                            <td>
                                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=single&id=' . $school['id']; ?>"
                                                    class="btn btn-primary btn-sm"><i
                                                        class="fa-solid fa-school"></i></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php }
                                                } else {
                                                    //no schools found
                                                    echo '<h5>Schools</h5>
                                                <div class="table-responsive">';
                                                    echo '<p>No schools found.</p>';
                                                    echo '</div>';
                                                } ?>
                            <!-- End Schools -->
                            <!-- Events -->
                            <?php //check if the events key contains any data
                                                if (isset($searchResults['events']) && !empty($searchResults['events'])) {
                                                    // confirm user has a role with read event permissions
                                                    //get the read event permission id
                                                    $readEventPermissionID = $permissionsObject->getPermissionIdByName('READ EVENT');

                                                    //boolean to check if the user has the read event permission
                                                    $hasReadEventPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readEventPermissionID);

                                                    //prevent the user from seeing the results of the event search if they do not have the read event permission
                                                    if (!$hasReadEventPermission) {
                                                        //events found, but user does not have read event permission
                                                        echo '<h5>Events</h5>
                                                <div class="table-responsive">';
                                                        echo '<p>Event results may have been found, but you lack permission to view results.</p>';
                                                        echo '</div>';
                                                    } else {
                                                ?>
                            <h5>Events</h5>
                            <div class="table-scroll table-fixedHead table-responsive">
                                <table class="table table-striped table-bordered" id="dataTable" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Location</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($searchResults['events'] as $event) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($event['name']); ?></td>
                                            <td><?php echo htmlspecialchars($event['location']); ?></td>
                                            <td><?php echo formatDate(htmlspecialchars($event['date'])); ?></td>
                                            <td>
                                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=single&id=' . $event['id']; ?>"
                                                    class="btn btn-primary btn-sm"><i
                                                        class="fa-solid fa-calendar"></i></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php }
                                                } else {
                                                    //no events found
                                                    echo '<h5>Events</h5>
                                                <div class="table-responsive">';
                                                    echo '<p>No events found.</p>';
                                                    echo '</div>';
                                                } ?>
                            <!-- End Events -->
                            <?php } ?>
                        </div>
                        <div class="card-body-section">
                            <!-- Wildcard Search Results -->
                            <?php if (isset($searchResults['wildcard']) && !empty($searchResults['wildcard'])) { ?>
                            <h5>Wildcard Search Results</h5>
                            <div class="table-responsive">
                                <?php if (isset($searchResults['wildcard']['students']) && !empty($searchResults['wildcard']['students'])) { ?>
                                <?php
                                                            // confirm user has a role with read student permissions
                                                            //get the read student permission id
                                                            $readStudentPermissionID = $permissionsObject->getPermissionIdByName('READ STUDENT');

                                                            //boolean to check if the user has the read student permission
                                                            $hasReadStudentPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readStudentPermissionID);

                                                            //prevent the user from seeing the results of the student search if they do not have the read student permission
                                                            if (!$hasReadStudentPermission) {
                                                                //students found, but user does not have read student permission
                                                                echo '<h5>Students</h5>
                                                <div class="table-responsive">';
                                                                echo '<p>Student results may have been found, but you lack permission to view results.</p>';
                                                                echo '</div>';
                                                            } else { ?>
                                <h5>Students</h5>
                                <div class="table-scroll table-fixedHead table-responsive">
                                    <table class="table table-striped table-bordered" id="dataTable" width="100%"
                                        cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Major</th>
                                                <th>Degree</th>
                                                <th>School</th>
                                                <th>Graduation Year</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($searchResults['wildcard']['students'] as $student) { ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                                                <td><?php echo $degreeData->getMajorNameById(intval(htmlspecialchars($student['major']))); ?>
                                                </td>
                                                <td><?php echo $degreeData->getGradeNameById(intval(htmlspecialchars($student['degree']))); ?>
                                                </td>
                                                <td><?php echo $schoolData->getSchoolName(intval(htmlspecialchars($student['school']))); ?>
                                                </td>
                                                <td><?php echo formatDate(htmlspecialchars($student['graduation'])); ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=single&id=' . $student['id']; ?>"
                                                        class="btn btn-primary btn-sm"><i
                                                            class="fa-solid fa-user"></i></a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php } ?>
                                <?php } else {
                                                            //no students found
                                                            echo '<h5>Students</h5>
                                                <div class="table-responsive">';
                                                            echo '<p>No students found.</p>';
                                                            echo '</div>';
                                                        }
                                                        //check if the schools key contains any data
                                                        if (isset($searchResults['wildcard']['schools']) && !empty($searchResults['wildcard']['schools'])) { ?>
                                <?php // confirm user has a role with read school permissions
                                                            //get the read school permission id
                                                            $readSchoolPermissionID = $permissionsObject->getPermissionIdByName('READ SCHOOL');

                                                            //boolean to check if the user has the read school permission
                                                            $hasReadSchoolPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readSchoolPermissionID);

                                                            //prevent the user from seeing the results of the school search if they do not have the read school permission
                                                            if (!$hasReadSchoolPermission) {
                                                                //schools found, but user does not have read school permission
                                                                echo '<h5>Schools</h5>
                                                <div class="table-responsive">';
                                                                echo '<p>School results may have been found, but you lack permission to view results.</p>';
                                                                echo '</div>';
                                                            } else {
                                                            ?>
                                <h5>Schools</h5>
                                <div class="table-scroll table-fixedHead table-responsive">
                                    <table class="table table-striped table-bordered" id="dataTable" width="100%"
                                        cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>School Name</th>
                                                <th>City</th>
                                                <th>State</th>
                                                <th>Zip</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($searchResults['wildcard']['schools'] as $school) { ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($school['name']); ?></td>
                                                <td><?php echo htmlspecialchars($school['city']); ?></td>
                                                <td><?php echo htmlspecialchars($school['state']); ?></td>
                                                <td><?php echo htmlspecialchars($school['zipcode']); ?></td>
                                                <td>
                                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=single&id=' . $school['id']; ?>"
                                                        class="btn btn-primary btn-sm"><i
                                                            class="fa-solid fa-school"></i></a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php } ?>
                                <?php } else {
                                                            //no schools found
                                                            echo '<h5>Schools</h5>
                                                <div class="table-responsive">';
                                                            echo '<p>No schools found.</p>';
                                                            echo '</div>';
                                                        }
                                                        //check if the events key contains any data
                                                        if (isset($searchResults['wildcard']['events']) && !empty($searchResults['wildcard']['events'])) { ?>
                                <?php
                                                            // confirm user has a role with read event permissions
                                                            //get the read event permission id
                                                            $readEventPermissionID = $permissionsObject->getPermissionIdByName('READ EVENT');

                                                            //boolean to check if the user has the read event permission
                                                            $hasReadEventPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readEventPermissionID);

                                                            //prevent the user from seeing the results of the event search if they do not have the read event permission
                                                            if (!$hasReadEventPermission) {
                                                                //events found, but user does not have read event permission
                                                                echo '<h5>Events</h5>
                                                <div class="table-responsive">';
                                                                echo '<p>Event results may have been found, but you lack permission to view results.</p>';
                                                                echo '</div>';
                                                            } else {
                                                            ?>
                                <h5>Events</h5>
                                <div class="table-scroll table-fixedHead table-responsive">
                                    <table class="table table-striped table-bordered" id="dataTable" width="100%"
                                        cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Location</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($searchResults['wildcard']['events'] as $event) { ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($event['name']); ?></td>
                                                <td><?php echo htmlspecialchars($event['location']); ?></td>
                                                <td><?php echo formatDate(htmlspecialchars($event['date'])); ?></td>
                                                <td>
                                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=single&id=' . $event['id']; ?>"
                                                        class="btn btn-primary btn-sm"><i
                                                            class="fa-solid fa-calendar"></i></a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php } ?>
                                <?php } else {
                                                            //no events found
                                                            echo '<h5>Events</h5>
                                                <div class="table-responsive">';
                                                            echo '<p>No events found.</p>';
                                                            echo '</div>';
                                                        } ?>
                                <?php
                                                } ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php } else {
                //TODO: redirect to 404 page
                exit;
            }
            break;
        default:
            //TODO: redirect to 404 page
            break;
    }
} else {
    //TODO: redirect to 404 page
    exit;
}
?>
