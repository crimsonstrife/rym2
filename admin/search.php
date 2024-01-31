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
                <div id="layout_content" class="w-95 mx-auto">
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
                                                <table id="dataTable" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="5">Result</th>
                                                            <th> </th>
                                                            <th> </th>
                                                            <th> </th>
                                                            <th> </th>
                                                            <th data-sortable="false">Link</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Students -->
                                                        <?php
                                                        //check if the students key contains any data
                                                        if (isset($searchResults['students']) && !empty($searchResults['students'])) {
                                                            // confirm user has a role with read student permissions
                                                            //get the read student permission id
                                                            $readStudentPermissionID = $permissionsObject->getPermissionIdByName('READ STUDENT');

                                                            //boolean to check if the user has the read student permission
                                                            $hasReadStudentPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readStudentPermissionID);

                                                            //prevent the user from seeing the results of the student search if they do not have the read student permission
                                                            if ($hasReadStudentPermission) { ?>
                                                                <?php
                                                                /* Setup datatable of Student results */
                                                                foreach ($searchResults['students'] as $student) { ?>
                                                                    <tr>
                                                                        <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                                                                        </td>
                                                                        <td><?php echo $degreeData->getGradeNameById(intval(htmlspecialchars($student['degree']))); ?>
                                                                        </td>
                                                                        <td><?php echo $degreeData->getMajorNameById(intval(htmlspecialchars($student['major']))); ?>
                                                                        </td>
                                                                        <td><?php echo formatDate(htmlspecialchars($student['graduation'])); ?>
                                                                        </td>
                                                                        <td><?php echo $schoolData->getSchoolName(intval(htmlspecialchars($student['school']))); ?>
                                                                        </td>
                                                                        <td>
                                                                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=students&student=single&id=' . $student['id']; ?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-user"></i> View
                                                                                Student</a>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                        <?php }
                                                        } ?>
                                                        <!-- Schools -->
                                                        <?php
                                                        //check if the schools key contains any data
                                                        if (isset($searchResults['schools']) && !empty($searchResults['schools'])) {
                                                            // confirm user has a role with read school permissions
                                                            //get the read school permission id
                                                            $readSchoolPermissionID = $permissionsObject->getPermissionIdByName('READ SCHOOL');

                                                            //boolean to check if the user has the read school permission
                                                            $hasReadSchoolPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readSchoolPermissionID);

                                                            //prevent the user from seeing the results of the school search if they do not have the read school permission
                                                            if ($hasReadSchoolPermission) { ?>
                                                                <?php
                                                                /* Setup datatable of School results */
                                                                foreach ($searchResults['schools'] as $school) { ?>
                                                                    <tr>
                                                                        <td><?php echo htmlspecialchars($school['name']); ?></td>
                                                                        <td><?php echo htmlspecialchars($school['address']); ?></td>
                                                                        <td><?php echo htmlspecialchars($school['city']); ?></td>
                                                                        <td><?php echo htmlspecialchars($school['state']); ?></td>
                                                                        <td><?php echo htmlspecialchars($school['zipcode']); ?></td>
                                                                        <td>
                                                                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=single&id=' . $school['id']; ?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-school"></i> View
                                                                                School</a>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                        <?php }
                                                        } ?>
                                                        <!-- Events -->
                                                        <?php
                                                        //check if the events key contains any data
                                                        if (isset($searchResults['events']) && !empty($searchResults['events'])) {
                                                            // confirm user has a role with read event permissions
                                                            //get the read event permission id
                                                            $readEventPermissionID = $permissionsObject->getPermissionIdByName('READ EVENT');

                                                            //boolean to check if the user has the read event permission
                                                            $hasReadEventPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readEventPermissionID);

                                                            //prevent the user from seeing the results of the event search if they do not have the read event permission
                                                            if ($hasReadEventPermission) { ?>
                                                                <?php
                                                                /* Setup datatable of Event results */
                                                                foreach ($searchResults['events'] as $event) { ?>
                                                                    <tr>
                                                                        <td><?php echo htmlspecialchars($event['name']); ?></td>
                                                                        <td><?php echo htmlspecialchars($event['location']); ?></td>
                                                                        <td><?php echo $schoolData->getSchoolCity($schoolData->getSchoolIdByName($event['location'])) ?>
                                                                        </td>
                                                                        <td><?php echo $schoolData->getSchoolState($schoolData->getSchoolIdByName($event['location'])) ?>
                                                                        </td>
                                                                        <td><?php echo formatDate(htmlspecialchars($event['event_date'])); ?>
                                                                        </td>
                                                                        <td>
                                                                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=events&event=single&id=' . $event['id']; ?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-calendar"></i> View
                                                                                Event</a>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                        <?php }
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="card-footer"></div>
                                </div>
                            </div>
                        </div>
                </div>
                </main>
                <script type="module">
                    /** import the simple-datatables module, implementation based on the demos/documentation from @fiduswriter/simple-datatables
                     * from https://fiduswriter.github.io/simple-datatables/documentation/
                     **/
                    import {
                        DataTable
                    } from "<?php echo getLibraryPath() . 'simple-datatables/module.js' ?>"
                    const dt = new DataTable("table", {
                        scrollY: "50%",
                        rowNavigation: true,
                        perPageSelect: [5, 10, 15, 20, 25, 50, ["All", -1]],
                        classes: {
                            active: "active",
                            disabled: "disabled",
                            selector: "form-select",
                            paginationList: "pagination",
                            paginationListItem: "page-item",
                            paginationListItemLink: "page-link"
                        },
                        columns: [{
                                select: 0,
                                sortSequence: ["desc", "asc"]
                            },
                            {
                                select: 1,
                                sortable: false,
                                sortSequence: ["desc", "asc"]
                            },
                            {
                                select: 2,
                                sortable: false,
                                sortSequence: ["desc", "asc"]
                            },
                            {
                                select: 3,
                                sortable: false,
                                sortSequence: ["desc", "asc"]
                            },
                            {
                                select: 4,
                                sortable: false,
                                sortSequence: ["desc", "asc"]
                            },
                            {
                                select: 5,
                                sortable: false,
                                searchable: false
                            }
                        ],
                        template: options => `<div class='${options.classes.top} fixed-table-toolbar'>
    ${
    options.paging && options.perPageSelect ?
        `<div class='${options.classes.dropdown} bs-bars float-left'>
            <label>
                <select class='${options.classes.selector}'></select>
            </label>
        </div>` :
        ""
}
    ${
    options.searchable ?
        `<div class='${options.classes.search} float-right search btn-group'>
            <input class='${options.classes.input} form-control search-input' placeholder='Search' type='search' title='Search within table'>
        </div>` :
        ""
}
</div>
<div class='${options.classes.container}'${options.scrollY.length ? ` style='height: ${options.scrollY}; overflow-Y: auto;'` : ""}></div>
<div class='${options.classes.bottom} fixed-table-toolbar'>
    ${
    options.paging ?
        `<div class='${options.classes.info}'></div>` :
        ""
}
    <nav class='${options.classes.pagination}'></nav>
</div>`,
                        tableRender: (_data, table, _type) => {
                            const thead = table.childNodes[0]
                            thead.childNodes[0].childNodes.forEach(th => {
                                //if the th is not sortable, don't add the sortable class
                                if (th.options?.sortable === false) {
                                    return
                                } else {
                                    if (!th.attributes) {
                                        th.attributes = {}
                                    }
                                    th.attributes.scope = "col"
                                    const innerHeader = th.childNodes[0]
                                    if (!innerHeader.attributes) {
                                        innerHeader.attributes = {}
                                    }
                                    let innerHeaderClass = innerHeader.attributes.class ?
                                        `${innerHeader.attributes.class} th-inner` : "th-inner"

                                    if (innerHeader.nodeName === "a") {
                                        innerHeaderClass += " sortable sortable-center both"
                                        if (th.attributes.class?.includes("desc")) {
                                            innerHeaderClass += " desc"
                                        } else if (th.attributes.class?.includes("asc")) {
                                            innerHeaderClass += " asc"
                                        }
                                    }
                                    innerHeader.attributes.class = innerHeaderClass
                                }
                            })

                            return table
                        }
                    })
                    dt.columns.add({
                        data: dt.data.data.map((_row, index) => index),
                        heading: "#",
                        render: (_data, td, _index, _cIndex) => {
                            if (!td.attributes) {
                                td.attributes = {}
                            }
                            td.attributes.scope = "row"
                            td.nodeName = "TH"
                            return td
                        }
                    })
                    dt.columns.order([0, 1, 2, 3, 4, 5])
                    window.dt = dt
                </script>
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
