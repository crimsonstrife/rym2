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

/* confirm user has a role with read degree permissions */
//get the read degree permission id
$readPermissionID = $permissionsObject->getPermissionIdByName('READ DEGREE');

//boolean to check if the user has the read degree permission
$hasReadPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readPermissionID);

//if the user does not have the read degree permission, display an error message and do not display the page
if (!$hasReadPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
} else { ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Degree Levels</h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-table"></i>
                    Degree List
                </div>
                <div class="card-tools">
                    <?php
                        /*confirm user has a role with create degree permissions*/
                        //get the id of the create degree permission
                        $createDegreePermissionID = $permissionsObject->getPermissionIdByName('CREATE DEGREE');

                        //boolean to check if the user has the create degree permission
                        $hasCreatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createDegreePermissionID);

                        //if the user has the create degree permission, display the add degree button
                        if ($hasCreatePermission) {
                        ?>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=degrees&degree=add&action=create' ?>"
                        class="btn btn-primary">Add Degree</a>
                    <?php } ?>
                </div>
            </div>
            <div class="card-body">
                <div>
                    <table id="dataTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Degree Name/Title</th>
                                <th>Date Created</th>
                                <th>Created By</th>
                                <th>Date Updated</th>
                                <th>Updated By</th>
                                <th data-sortable="false">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                /* Setup datatable of Degree Levels */
                                //include the degree class
                                $degreesData = new Degree();
                                //include the users class
                                $usersData = new User();
                                //get all degree levels
                                $degreesArray = $degreesData->getAllGrades();
                                //for each event, display it
                                foreach ($degreesArray as $degree) {
                                ?>
                            <tr>
                                <td><?php echo $degree['name']; ?></td>
                                <td><?php echo $degree['created_at']; ?></td>
                                <td><?php echo $usersData->getUserUsername($degree['created_by']); ?></td>
                                <td><?php echo $degree['updated_at']; ?></td>
                                <td><?php echo $usersData->getUserUsername($degree['updated_by']); ?></td>
                                <td>
                                    <span class="td-actions">
                                        <?php /*confirm user has a role with update degree permissions*/
                                                //get the update degree permission id
                                                $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE DEGREE');

                                                //boolean to check if the user has the update degree permission
                                                $hasUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                                                //only show the edit button if the user has the update degree permission
                                                if ($hasUpdatePermission) { ?>
                                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=degrees&degree=edit&action=edit&id=' . $degree['id']; ?>"
                                            class="btn btn-primary">Edit Degree</a>
                                        <?php } ?>
                                        <?php /*confirm user has a role with delete degree permissions*/
                                                //get the delete degree permission id
                                                $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE DEGREE');

                                                //boolean to check if the user has the delete degree permission
                                                $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                                                //only show the delete button if the user has the delete degree permission
                                                if ($hasDeletePermission) { ?>
                                        <button type="button" id="openDeleteModal" class="btn btn-danger"
                                            data-bs-toggle="modal" data-bs-target="#deleteDegreeModal"
                                            onclick="setDeleteID(<?php echo $degree['id']; ?>)">
                                            Delete Degree
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
                <?php /*confirm user has a role with export degrees permissions*/
                    //get the id of the export degrees permission
                    $exportDegreesPermissionID = $permissionsObject->getPermissionIdByName('EXPORT DEGREE');

                    //boolean to check if the user has the export degrees permission
                    $hasExportDegreesPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $exportDegreesPermissionID);

                    if ($hasExportDegreesPermission) {
                        //prepare the degree array for download
                        $csvArray = $degreesArray;
                        //set the created by and updated by to the username
                        foreach ($csvArray as $key => $row) {
                            $csvArray[$key]['created_by'] = $usersData->getUserUsername(intval($row['created_by'])); //get the username of the user who created the degree, and swap out the user id
                            $csvArray[$key]['updated_by'] = $usersData->getUserUsername(intval($row['updated_by'])); //get the username of the user who updated the degree, and swap out the user id
                        }
                        //clean up the column headers to be more readable, i.e. remove underscores and capitalize
                        foreach ($csvArray as $key => $row) {
                            $csvArray[$key] = array(
                                'Degree Name' => $row['name'],
                                'Date Created' => $row['created_at'],
                                'Created By' => $row['created_by'],
                                'Date Updated' => $row['updated_at'],
                                'Updated By' => $row['updated_by']
                            );
                        }
                    ?>
                <form target="_blank"
                    action="<?php echo APP_URL . '/admin/download.php?type=degrees&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>"
                    method="post" enctype="multipart/form-data">
                    <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                </form>
                <?php } else { ?>
                <p class="text-danger">You do not have permission to download the CSV of degrees.</p>
                <button class="btn btn-success" disabled>Export to CSV</button>
                <?php } ?>
            </div>
            <?php if ($hasDeletePermission) { ?>
            <div id="info" class="">
                <!-- Delete Degree Modal-->
                <!-- Modal -->
                <div id="deleteDegreeModal" class="modal fade delete" tabindex="-1" role="dialog"
                    aria-labelledby="#degreeDeleteModal" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="degreeDeleteModal">Delete Degree - <span
                                        id="degreeName-Title">Degree Name</span></h3>
                                <button type="button" class="btn-close close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this degree?</p>
                                <p>This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                                <script>
                                var deleteBaseURL =
                                    "<?php echo APP_URL . '/admin/dashboard.php?view=degrees&degree=single&action=delete&id='; ?>";
                                </script>
                                <form id="deleteDegreeForm" action="" method="post">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                        onclick="clearDeleteID()">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete Degree</button>
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
    //set the degrees array to a javascript variable
    var degreesArray = <?php echo json_encode($degreesArray); ?>;

    //function to set the delete id on the action url of the delete modal based on which degree is selected
    function setDeleteID(id) {
        //get the degree name
        var degreeName = degreesArray.find(degree => degree.id == id).name;
        //set the degree name in the modal title
        document.getElementById("degreeName-Title").innerHTML = degreeName;
        //set the action url of the delete modal
        document.getElementById("deleteDegreeForm").action = deleteBaseURL + id;
    }

    function clearDeleteID() {
        //set the action url of the delete modal
        document.getElementById("deleteDegreeForm").action = "";
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
    scrollY: "100vh",
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
            type: "date",
            format: "YYYY-MM-DD HH:mm:ss",
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
        },
        {
            select: 4,
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
<?php } ?>
