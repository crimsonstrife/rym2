<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
}

//include the permissions class
$permissionsObject = new Permission();

//include the auth class
$auth = new Authenticator();

/* confirm user has a role with read school permissions */
//get the read school permission id
$readPermissionID = $permissionsObject->getPermissionIdByName('READ SCHOOL');

//boolean to check if the user has the read school permission
$hasReadPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readPermissionID);

//if the user does not have the read school permission, display an error message and do not display the page
if (!$hasReadPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
} else {
?>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Schools</h1>
        <div class="row">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-table"></i>
                        School List
                    </div>
                    <div class="card-tools">
                        <?php
                        /*confirm user has a role with create school permissions*/
                        //get the id of the create school permission
                        $createSchoolPermissionID = $permissionsObject->getPermissionIdByName('CREATE SCHOOL');

                        //boolean to check if the user has the create school permission
                        $hasCreatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createSchoolPermissionID);

                        //if the user has the create school permission, display the add school button
                        if ($hasCreatePermission) {
                        ?>
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=add&action=create' ?>" class="btn btn-primary">Add School</a>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <table id="dataTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>School Name</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Zip Code</th>
                                    <th>Events Held</th>
                                    <th>Date Created</th>
                                    <th>Created By</th>
                                    <th>Date Updated</th>
                                    <th>Updated By</th>
                                    <th data-sortable="false">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                /* Setup datatable of Schools */
                                //include the event class
                                $eventsData = new Event();
                                //include the school class
                                $schoolsData = new School();
                                //include the users class
                                $usersData = new User();
                                //get all schools
                                $schoolsArray = $schoolsData->getSchools();
                                //for each event, display it
                                foreach ($schoolsArray as $school) {
                                ?>
                                    <tr>
                                        <td><?php echo $school['name']; ?></td>
                                        <td><?php echo $school['address']; ?></td>
                                        <td><?php echo $school['city']; ?></td>
                                        <td><?php echo $school['state']; ?></td>
                                        <td><?php echo $school['zipcode']; ?></td>
                                        <td><?php echo $eventsData->getHeldEvents(intval($school['id'])); ?></td>
                                        <td><?php echo $school['created_at']; ?></td>
                                        <td><?php echo $usersData->getUserUsername($school['created_by']); ?>
                                        </td>
                                        <td><?php echo $school['updated_at']; ?></td>
                                        <td><?php echo $usersData->getUserUsername($school['updated_by']); ?>
                                        </td>
                                        <td>
                                            <span class="td-actions">
                                                <?php /*confirm user has a role with read school permissions*/
                                                //only show the view button if the user has the read school permission
                                                if ($hasReadPermission) { ?>
                                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=single' ?>&id=<?php echo $school['id']; ?>" class="btn btn-success">View School</a>
                                                <?php } ?>
                                                <?php /*confirm user has a role with update school permissions*/
                                                //get the update school permission id
                                                $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE SCHOOL');

                                                //boolean to check if the user has the update school permission
                                                $hasUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                                                //only show the edit button if the user has the update school permission
                                                if ($hasUpdatePermission) { ?>
                                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=edit&action=edit&id=' . $school['id']; ?>" class="btn btn-primary">Edit School</a>
                                                <?php } ?>
                                                <?php /*confirm user has a role with delete school permissions*/
                                                //get the delete school permission id
                                                $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE SCHOOL');

                                                //boolean to check if the user has the delete school permission
                                                $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                                                //only show the delete button if the user has the delete school permission
                                                if ($hasDeletePermission) { ?>
                                                    <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSchoolModal" onclick="setDeleteID(<?php echo $school['id']; ?>)">
                                                        Delete School
                                                    </button>
                                                <?php } ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <!-- Download CSV -->
                    <?php /*confirm user has a role with export schools permissions*/
                    //get the id of the export schools permission
                    $exportSchoolsPermissionID = $permissionsObject->getPermissionIdByName('EXPORT SCHOOL');

                    //boolean to check if the user has the export schools permission
                    $hasExportSchoolsPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $exportSchoolsPermissionID);

                    if ($hasExportSchoolsPermission) {
                        //prepare the events array for download
                        $csvArray = $schoolsArray;
                        //set the created by and updated by to the username
                        foreach ($csvArray as $key => $row) {
                            $csvArray[$key]['created_by'] = $usersData->getUserUsername(intval($row['created_by'])); //get the username of the user who created the event, and swap out the user id
                            $csvArray[$key]['updated_by'] = $usersData->getUserUsername(intval($row['updated_by'])); //get the username of the user who updated the event, and swap out the user id
                        }
                        //clean up the column headers to be more readable, i.e. remove underscores and capitalize
                        foreach ($csvArray as $key => $row) {
                            $csvArray[$key] = array(
                                'School Name' => $row['name'],
                                'Address' => $row['address'],
                                'City' => $row['city'],
                                'State' => $row['state'],
                                'Zip Code' => $row['zipcode'],
                                'Events Held' => $eventsData->getHeldEvents(intval($row['id'])),
                                'Date Created' => $row['created_at'],
                                'Created By' => $row['created_by'],
                                'Date Updated' => $row['updated_at'],
                                'Updated By' => $row['updated_by']
                            );
                        }
                    ?>
                        <form target="_blank" action="<?php echo APP_URL . '/admin/download.php?type=schools&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>" method="post" enctype="multipart/form-data">
                            <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                        </form>
                    <?php } else { ?>
                        <p class="text-danger">You do not have permission to download the CSV of schools.</p>
                        <button class="btn btn-success" disabled>Export to CSV</button>
                    <?php } ?>
                </div>
                <?php if ($hasDeletePermission) { ?>
                    <div id="info" class="">
                        <!-- Delete School Modal-->
                        <!-- Modal -->
                        <div id="deleteSchoolModal" class="modal fade delete" tabindex="-1" role="dialog" aria-labelledby="#schoolDeleteModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="schoolDeleteModal">Delete School - <span id="schoolName-Title">School Name</span></h3>
                                        <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this school?</p>
                                        <p>This action cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <script>
                                            var deleteBaseURL =
                                                "<?php echo APP_URL . '/admin/dashboard.php?view=schools&school=single&action=delete&id='; ?>";
                                        </script>
                                        <form id="deleteSchoolForm" action="" method="post">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="clearDeleteID()">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Delete School</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php if ($hasDeletePermission) { ?>
            <script>
                //set the schools array to a javascript variable
                var schoolsArray = <?php echo json_encode($schoolsArray); ?>;

                //function to set the delete id on the action url of the delete modal based on which school is selected
                function setDeleteID(id) {
                    //get the school name
                    var schoolName = schoolsArray.find(school => school.id == id).name;
                    //set the school name in the modal title
                    document.getElementById("schoolName-Title").innerHTML = schoolName;
                    //set the action url of the delete modal
                    document.getElementById("deleteSchoolForm").action = deleteBaseURL + id;
                }

                function clearDeleteID() {
                    //set the action url of the delete modal
                    document.getElementById("deleteSchoolForm").action = "";
                }
            </script>
        <?php } ?>
    </div>
    <script type="module">
        /** import the simple-datatables module, implementation based on the demos/documentation from @fiduswriter/simple-datatables
         * from https://fiduswriter.github.io/simple-datatables/documentation/
         **/
        import {
            DataTable
        } from "<?php echo getLibraryPath() . 'simple-datatables/module.js' ?>"
        const dt = new DataTable("table", {
            scrollY: "50vh",
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
                    sortSequence: ["desc", "asc"]
                },
                {
                    select: 2,
                    sortSequence: ["desc", "asc"]
                },
                {
                    select: 3,
                    sortSequence: ["desc", "asc"]
                },
                {
                    select: 4,
                    sortSequence: ["desc", "asc"]
                },
                {
                    select: 5,
                    type: "numeric",
                    sortSequence: ["desc", "asc"]
                },
                {
                    select: 6,
                    type: "date",
                    format: "YYYY-MM-DD HH:mm:ss",
                    sortSequence: ["desc", "asc"]
                },
                {
                    select: 7,
                    sortSequence: ["desc", "asc"]
                },
                {
                    select: 8,
                    type: "date",
                    format: "YYYY-MM-DD HH:mm:ss",
                    sortSequence: ["desc", "asc"]
                },
                {
                    select: 9,
                    sortSequence: ["desc", "asc"]
                },
                {
                    select: 10,
                    sortable: false,
                    searchable: false
                }
            ],
            template: options => `<div class='${options.classes.top} '>
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
<div class='${options.classes.bottom} '>
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
        dt.columns.order([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10])
        window.dt = dt
    </script>
<?php } ?>
