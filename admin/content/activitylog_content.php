<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
} // idea from https://stackoverflow.com/a/409515 (user UnkwnTech)

//include the permissions class
$permissionsObject = new Permission();

//auth class
$auth = new Authenticator();

//check that the view dashboard permission is set
if (!isset($hasViewDashboardPermission)) {
    //set the error type
    $thisError = 'CONFIGURATION_ERROR';

    //include the error message file
    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
} else {
    //check that the user has the view dashboard permission
    if (!$hasViewDashboardPermission) {
        //set the error type
        $thisError = 'DASHBOARD_PERMISSION_ERROR';

        //include the error message file
        include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
    } else {
        /*confirm user has a role with read contact permissions*/
        //get the id of the read contact permission
        $relevantPermissionID = $permissionsObject->getPermissionIdByName('READ CONTACT');

        //boolean to track if the user has the read contact permission
        $hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

        //prevent the user from accessing the page if they do not have the relevant permission
        if (!$hasPermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else {
?>
<!-- main content -->
<div id="layout_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Activity Log</h1>
            <div class="row">
                <!-- Site Activity Log -->
                <div class="row">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fa-solid fa-table"></i>
                            Activity Log
                        </div>
                        <div class="card-body">
                            <div>
                                <table id="dataTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Action</th>
                                            <th>Details</th>
                                            <th>Action Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                                    /* Setup datatable of activity */
                                                    //include the activity class
                                                    $activity = new Activity();
                                                    //include the user class
                                                    $user = new User();
                                                    //include the school class
                                                    $school = new School();
                                                    //include the degree class
                                                    $degree = new Degree();
                                                    //include the student class
                                                    $student = new Student();
                                                    //include the event class
                                                    $event = new Event();
                                                    //include the role class
                                                    $role = new Roles();
                                                    //get the activity log
                                                    $activityArray = $activity->getAllActivity();
                                                    foreach ($activityArray as $entry) {
                                                        //get the user id
                                                        $userId = intval($entry['user_id']);
                                                        //get the user name
                                                        $userName = $user->getUserUsername($userId);
                                                        //get the action
                                                        $action = $entry['action'];
                                                        //get the details
                                                        $details = $entry['performed_on'];

                                                        //get the action date
                                                        $actionDate = $entry['action_date'];
                                                        //display the activity log
                                                        echo '<tr>';
                                                        echo '<td>' . $userName . '</td>';
                                                        echo '<td>' . $action . '</td>';
                                                        echo '<td>' . $details . '</td>';
                                                        echo '<td>' . $actionDate . '</td>';
                                                        echo '</tr>';
                                                    }
                                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <!-- Download CSV -->
                            <?php
                                        //prepare the user array for download
                                        //swap the user id for the username
                                        foreach ($activityArray as $key => $entry) {
                                            //get the user id
                                            $userId = intval($entry['user_id']);
                                            //get the user name
                                            $userName = $user->getUserUsername($userId);
                                            //swap the user id for the username
                                            $activityArray[$key]['user_id'] = $userName;
                                        }
                                        $csvArray = $activityArray; ?>
                            <form target="_blank"
                                action="<?php echo APP_URL . '/admin/download.php?type=activity_log&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>"
                                method="post" enctype="multipart/form-data">
                                <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script type="module">
/** import the simple-datatables module, implementation based on the demos/documentation from @fiduswriter/simple-datatables
 * from https://fiduswriter.github.io/simple-datatables/documentation/
 **/
import {
    DataTable
} from "<?php echo getLibraryPath() . 'simple-datatables/module.js' ?>"
const dt = new DataTable("table", {
    scrollY: "100vh",
    rowNavigation: true,
    perPageSelect: [5, 10, 15, 20, 25, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100, ["All", -1]],
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
            sortSequence: ["desc", "asc"]
        },
        {
            select: 2,
            sortSequence: ["desc", "asc"]
        },
        {
            select: 3,
            type: "date",
            format: "YYYY-MM-DD HH:mm:ss",
            sortSequence: ["desc", "asc"]
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
dt.columns.order([0, 1, 2, 3])
window.dt = dt
</script>
<?php }
    }
} ?>
