<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
}

//autoload composer dependencies
require_once __DIR__ . '/../../../vendor/autoload.php';

//include the permissions class
$permissionsObject = new Permission();

//auth class
$auth = new Authenticator();

//include the roles class
$role = new Roles();

//user class
$user = new User();

/*confirm user has a role with read role permissions*/
//get the id of the read role permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('READ ROLE');

//boolean to track if the user has the read role permission
$hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

//get the is admin permission id
$isAdminPermissionID = $permissionsObject->getPermissionIdByName('IS ADMIN');

//boolean to check if the user has the is admin permission
$hasIsAdminPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $isAdminPermissionID);

//get the is super admin permission id
$isSuperAdminPermissionID = $permissionsObject->getPermissionIdByName('IS SUPERADMIN');

//boolean to check if the user has the is super admin permission
$hasIsSuperAdminPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $isSuperAdminPermissionID);

//prevent the user from accessing the page if they do not have the relevant permission
if (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
} else {
    if (isset($_GET['id'])) {
        //get the role id from the url parameter
        $roleId = $_GET['id'];
    } else {
        //set the role id to null
        $roleId = null;
    }

    //confirm the id exists
    if (empty($roleId) || $roleId == null) {
        //set the error type
        $thisError = 'INVALID_REQUEST_ERROR';

        //include the error message file
        include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
    } else {
        //get the role data by id
        $roleData = $role->getRoleById(intval($roleId));

        //check if the role is empty
        if (empty($roleData)) {
            //set the error type
            $thisError = 'NOT_FOUND';

            //include the error message file
            include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
        }
    }

    //if not empty, display the event information
    if (!empty($roleData)) {

        //role array to count
        $rolesArray = $role->getAllRoles();

        //count of how many roles have the super admin permission
        $superAdminCount = 0;

        //boolean to check for the super admin role
        $roleHasSuperPermission = false;

        //loop through the roles array, and count how many roles have the super admin permission
        foreach ($rolesArray as $roleArray) {
            foreach ($roleArray as $roleObject) {
                //get the role permissions
                $rolePermissions = $role->getRolePermissions(intval($roleId));

                //loop through the role permissions
                foreach ($rolePermissions as $permissionArray) {
                    foreach ($permissionArray as $permission) {
                        //if the role has the super admin permission
                        if ($permission['id'] == $isSuperAdminPermissionID) {
                            //increment the super admin count
                            $superAdminCount++;
                        }
                    }
                }
            }
        }
?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $role->getRoleNameById(intval($roleId)); ?></h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-scale-balanced"></i>
                    Role Details
                </div>
                <div class="card-buttons">
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=list'; ?>"
                        class="btn btn-secondary">Back to Roles</a>
                    <?php /*confirm user has a role with update role permissions*/
                            //get the update role permission id
                            $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE ROLE');

                            //boolean to check if the user has the update role permission
                            $hasUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                            //only show the edit button if the user has the update role permission
                            if ($hasUpdatePermission) { ?>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=edit&action=edit&id=' . $roleId; ?>"
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
                    <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteRoleModal">
                        Delete Role
                    </button>
                    <?php }
                                }
                            } ?>
                </div>
            </div>
            <div class="card-body">
                <!-- Single Role Information -->
                <div class="row">
                    <!-- Role Details -->
                    <div class="col-md-6" style="height: 100%;">
                        <h3>Role Details</h3>
                        <div id="info" class="">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Role Name:</strong></p>
                                    <p><strong>Role Created:</strong></p>
                                    <p><strong>Role Created By:</strong></p>
                                    <p><strong>Role Updated:</strong></p>
                                    <p><strong>Role Updated By:</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <p><?php echo $roleData['name']; ?></p>
                                    <p><?php echo $roleData['created_at']; ?></p>
                                    <p><?php echo $user->getUserUsername(intval($roleData['created_by'])); ?></p>
                                    <p><?php echo $roleData['updated_at']; ?></p>
                                    <p><?php echo $user->getUserUsername(intval($roleData['updated_by'])); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" style="height: 100%;">
                        <h3>Role Permissions</h3>
                        <div id="info" class="">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php $rolePermissions = $role->getRolePermissions(intval($roleId)); ?>
                                    <p><strong>Permissions:</strong></p>
                                    <!-- Permissions Table -->
                                    <div class="card-body">
                                        <div>
                                            <table id="dataTable" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Permission</th>
                                                        <th>Date Granted</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($rolePermissions as $permissionArray) {
                                                                foreach ($permissionArray as $permission) { ?>
                                                    <tr>
                                                        <td><?php echo $permission['name']; ?></td>
                                                        <td><?php echo $role->getPermissionGrantDate(intval($roleId), intval($permission['id'])); ?>
                                                        </td>
                                                    </tr>
                                                    <?php }
                                                            } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- Users with this role -->
                        <h3>Users with this role</h3>
                        <div id="info" class="">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php $usersWithRole = $role->getUsersWithRole(intval($roleId));
                                            if (!empty($usersWithRole)) { ?>
                                    <p><strong>Users:</strong></p>
                                    <div class="card-body">
                                        <div>
                                            <table id="dataTable" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Username</th>
                                                        <th>Email</th>
                                                        <th>Created</th>
                                                        <th>Created By</th>
                                                        <th>Updated</th>
                                                        <th>Updated By</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($usersWithRole as $userHasRole) {
                                                                    $userDetails = $user->getUserById(intval($userHasRole['user_id'])); ?>
                                                    <tr>
                                                        <td><?php echo $userDetails['username']; ?></td>
                                                        <td><?php echo $userDetails['email']; ?></td>
                                                        <td><?php echo $role->getUserRoleGivenDate(intval($userDetails['id']), intval($roleId)); ?>
                                                        </td>
                                                        <td><?php echo $user->getUserUsername(intval($userHasRole['created_by'])); ?>
                                                        </td>
                                                        <td><?php echo $role->getUserRoleModifiedDate(intval($userDetails['id']), intval($roleId)); ?>
                                                        </td>
                                                        <td><?php echo $user->getUserUsername(intval($userHasRole['updated_by'])); ?>
                                                        </td>
                                                    </tr>
                                                    <?php }
                                                            } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                <h3 class="modal-title" id="roleDeleteModal">Delete Role -
                                    <?php echo $role->getRoleNameById(intval($roleId)); ?></h3>
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
                                <form
                                    action="<?php echo APP_URL . '/admin/dashboard.php?view=roles&role=single&action=delete&id=' . $roleId; ?>"
                                    method="post">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
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
</div>
<script type="module">
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
dt.columns.order([0, 1])
window.dt = dt
</script>
<?php }
} ?>
