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

/* confirm user has a role with read role permissions */
//get the read role permission id
$readPermissionID = $permissionsObject->getPermissionIdByName('READ ROLE');

//boolean to check if the user has the read role permission
$hasReadPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readPermissionID);

//get the edit role permission id
$editPermissionID = $permissionsObject->getPermissionIdByName('UPDATE ROLE');

//boolean to check if the user has the edit role permission
$hasEditPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $editPermissionID);

//get the delete role permission id
$deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE ROLE');

//boolean to check if the user has the delete role permission
$hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

//get the is admin permission id
$isAdminPermissionID = $permissionsObject->getPermissionIdByName('IS ADMIN');

//boolean to check if the user has the is admin permission
$hasIsAdminPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $isAdminPermissionID);

//get the is super admin permission id
$isSuperAdminPermissionID = $permissionsObject->getPermissionIdByName('IS SUPERADMIN');

//boolean to check if the user has the is super admin permission
$hasIsSuperAdminPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $isSuperAdminPermissionID);

//if the user does not have the read role permission, display an error message and do not display the page
if (!$hasReadPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
} else { ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Roles</h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-table"></i>
                    Role List
                </div>
                <div class="card-tools">
                    <?php
                        /*confirm user has a role with create role permissions*/
                        //get the id of the create role permission
                        $createRolePermissionID = $permissionsObject->getPermissionIdByName('CREATE ROLE');

                        //boolean to check if the user has the create role permission
                        $hasCreatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createRolePermissionID);

                        //if the user has the create role permission, display the add role button
                        if ($hasCreatePermission) {
                        ?>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=add&action=create' ?>"
                        class="btn btn-primary">Add Role</a>
                    <?php } ?>
                </div>
            </div>
            <div class="card-body">
                <div>
                    <table id="dataTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Role Name</th>
                                <th data-sortable="false">Permissions</th>
                                <th>Role Created</th>
                                <th>Role Created By</th>
                                <th>Role Updated</th>
                                <th>Role Updated By</th>
                                <th data-sortable="false">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                /* Setup datatable of Roles */
                                //include the Roles class
                                $rolesData = new Roles();
                                //include the Permissions class
                                $permissionsData = new Permission();
                                //include the User class
                                $userData = new User();
                                //get all the roles
                                $rolesArray = $rolesData->getAllRoles();
                                //boolean to check for the super admin role
                                $roleHasSuperPermission = false;
                                //count of how many roles have the super admin permission
                                $superAdminCount = 0;
                                //for each user, display it
                                foreach ($rolesArray as $role) {
                                    //get the permissions for the role
                                    $permissionsArray = $rolesData->getRolePermissions(intval($role['id']));

                                    //create a string of the permissions
                                    $permissionsString = "";

                                    //count variables
                                    $lineCount = 0;
                                    $hiddenLines = 0;

                                    //check if the permissions array is empty
                                    if (!empty($permissionsArray)) {
                                        foreach ($permissionsArray as $permission) {
                                            foreach ($permission as $key => $value) {
                                                //get the permissions
                                                $permissions = $permissionsData->getPermissionById(intval($value['id']));
                                                foreach ($permissions as $permission) {
                                                    //keep track of the number of lines, if there are more than 6 hide the rest
                                                    if ($lineCount <= 6) {
                                                        //add the permission to the string, followed by a new line
                                                        $permissionsString .= $permission['name'] . "<br>";
                                                        //increment the line count
                                                        $lineCount++;
                                                    } else {
                                                        //increment the line count
                                                        $lineCount++;
                                                        $hiddenLines++;
                                                    }

                                                    //if this is the last permission, add an ellipsis with the count of the remaining permissions
                                                    if (($lineCount == count($permissionsArray)) && ($hiddenLines > 0)) {
                                                        $permissionsString .= "... +[" . $hiddenLines . "] more" . "<br>";
                                                    }
                                                }

                                                //check if the permission is the super admin permission
                                                if (intval($value['id']) == $isSuperAdminPermissionID) {
                                                    //if the permission is the super admin permission, set the hasSuperAdminPermission boolean to true
                                                    $roleHasSuperPermission = true;
                                                    //increment the super admin count
                                                    $superAdminCount++;
                                                } else {
                                                    //do nothing
                                                    $roleHasSuperPermission = false;
                                                }
                                            }
                                        }
                                    } else {
                                        //if the permissions array is empty, add a message to the string
                                        $permissionsString = "No permissions assigned";
                                    }
                                ?>
                            <tr>
                                <td><?php echo $role['name']; ?></td>
                                <td><?php echo $permissionsString; ?></td>
                                <td><?php echo $role['created_at']; ?></td>
                                <td><?php echo $userData->getUserUsername(intval($role['created_by'])); ?></td>
                                <td><?php echo $role['updated_at']; ?></td>
                                <td><?php echo $userData->getUserUsername(intval($role['updated_by'])); ?></td>
                                <td>
                                    <span class="td-actions">
                                        <?php /*confirm user has a role with read role permissions*/
                                                //only show the view button if the user has the read role permission
                                                if ($hasReadPermission) { ?>
                                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=single' ?>&id=<?php echo $role['id']; ?>"
                                            class="btn btn-success">View Role</a>
                                        <?php } ?>
                                        <?php /*confirm user has a role with update role permissions*/
                                                //get the update role permission id
                                                $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE ROLE');

                                                //boolean to check if the user has the update role permission
                                                $hasUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                                                //only show the edit button if the user has the update role permission
                                                if ($hasUpdatePermission) { ?>
                                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=edit&action=edit&id=' . $role['id']; ?>"
                                            class="btn btn-primary">Edit Role</a>
                                        <?php } ?>
                                        <?php /*confirm user has a role with delete role permissions*/
                                                //get the delete role permission id
                                                $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE ROLE');

                                                //boolean to check if the user has the delete role permission
                                                $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                                                //only show the delete button if the user has the delete role permission
                                                if ($hasDeletePermission) {

                                                    //if there are 1 or fewer super admins
                                                    if ($superAdminCount <= 1) {
                                                        //if the role is the super admin role, do not show the delete button
                                                        if ($roleHasSuperPermission) {
                                                            //do not show the delete button
                                                        } else { ?>
                                        <button type="button" id="openDeleteModal" class="btn btn-danger"
                                            data-bs-toggle="modal" data-bs-target="#deleteRoleModal"
                                            onclick="setDeleteID(<?php echo $role['id']; ?>)">
                                            Delete Role
                                        </button>
                                        <?php //reset the super admin boolean
                                                            $roleHasSuperPermission = false;
                                                        }
                                                    }
                                                } ?>
                                    </span>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
            </div>
            <?php if ($hasDeletePermission) { ?>
            <div id="info" class="">
                <!-- Delete Role Modal-->
                <!-- Modal -->
                <div id="deleteRoleModal" class="modal fade delete" tabindex="-1" role="dialog"
                    aria-labelledby="#roleDeleteModal" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="roleDeleteModal">Delete Role - <span
                                        id="roleName-Title">Role Name</span></h3>
                                <button type="button" class="btn-close close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this role?</p>
                                <p>This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                                <script>
                                var deleteBaseURL =
                                    "<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=single&action=delete&id='; ?>";
                                </script>
                                <form id="deleteRoleForm" action="" method="post">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                        onclick="clearDeleteID()">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete Role</button>
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
    //set the roles array to a javascript variable
    var rolesArray = <?php echo json_encode($rolesArray); ?>;

    //function to set the delete id on the action url of the delete modal based on which role is selected
    function setDeleteID(id) {
        //get the role name
        var roleName = rolesArray.find(role => role.id == id).name;
        //set the role name in the modal title
        document.getElementById("roleName-Title").innerHTML = roleName;
        //set the action url of the delete modal
        document.getElementById("deleteRoleForm").action = deleteBaseURL + id;
    }

    function clearDeleteID() {
        //set the action url of the delete modal
        document.getElementById("deleteRoleForm").action = "";
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
            sortable: false,
            searchable: false
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
            type: "date",
            format: "YYYY-MM-DD HH:mm:ss",
            sortSequence: ["desc", "asc"]
        },
        {
            select: 6,
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
dt.columns.order([0, 1, 2, 3, 4, 5, 6])
window.dt = dt
</script>
<?php } ?>
