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

//include the activity class
$activity = new Activity();

//boolean to track if the user is viewing their own profile, or if they have permission to view the profile
$isOwnProfile = false;
$hasPermission = false;

if (isset($_GET['id'])) {
    //get the user id from the url parameter
    $userId = $_GET['id'];
} else {
    //set the user id to null
    $userId = null;
}

if (isset($_SESSION['user_id'])) {
    //get the user id from the session
    $currentUserId = $_SESSION['user_id'];
} else {
    //set the user id to null
    $currentUserId = null;
}

//confirm the ids exists
if (empty($userId) || $userId == null) {
    //set the error type
    $thisError = 'INVALID_REQUEST_ERROR';

    $userData = null;
} else {
    //try to get the user data by id
    $userData = $user->getUserById(intval($userId));
}
if (empty($currentUserData) || $currentUserData == null) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    $currentUserData = null;
} else {
    //try to get the user data by id
    $currentUserData = $user->getUserById(intval($currentUserId));
}

/*confirm user has a role with read user permissions*/
//get the id of the read user permission
$relevantPermissionID = $permissionsObject->getPermissionIdByName('READ USER');

//check if the current user is the same as the user being viewed, always let the user view their own profile
if ((intval($currentUserId) == intval($userId)) && (intval($userId) !== null) && (intval($currentUserId) !== null)) {
    $isOwnProfile = true;
    $hasPermission = $auth->checkUserPermission(intval($currentUserId), $relevantPermissionID);
}

//prevent the user from accessing the page if they do not have the relevant permission
if ((!$hasPermission && !$isOwnProfile)) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
} elseif (!$hasPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
} else {
    //check if the userdata is empty
    if (empty($userData) || $userData == null) {
        //set the error type
        $thisError = 'NOT_FOUND';

        //include the error message file
        include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
    } else {
        //get the roles data by user id
        $rolesData = $user->getUserRoles(intval($userId));
?>
        <div class="container-fluid px-4">
            <h1 class="mt-4"><?php echo $user->getUserUsername($userId); ?></h1>
            <div class="row">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa-solid fa-user"></i>
                            User Details
                        </div>
                        <div class="card-buttons">
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=list'; ?>" class="btn btn-secondary">Back to Users</a>
                            <?php /*confirm user has a role with update user permissions*/
                            //get the update user permission id
                            $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE USER');

                            //boolean to check if the user has the update user permission
                            $hasUpdatePermission = $auth->checkUserPermission(intval($currentUserId), $updatePermissionID);

                            //only show the edit button if the user has the update user permission
                            if ($hasUpdatePermission || $isOwnProfile) { ?>
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=edit&action=edit&id=' . $userId; ?>" class="btn btn-primary">Edit User</a>
                            <?php } ?>
                            <?php /*confirm user has a role with delete user permissions*/
                            //get the delete user permission id
                            $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE USER');

                            //boolean to check if the user has the delete user permission
                            $hasDeletePermission = $auth->checkUserPermission(intval($currentUserId), $deletePermissionID);

                            //only show the delete button if the user has the delete user permission, do not let the user delete their own account
                            if ($hasDeletePermission && !$isOwnProfile) { ?>
                                <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                                    Delete User
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Single User Information -->
                        <div class="row">
                            <!-- User Details -->
                            <div class="col-md-6" style="height: 100%;">
                                <h3>User Details</h3>
                                <div id="info" class="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Username:</strong></p>
                                            <p><strong>Email:</strong></p>
                                            <p><strong>User Created:</strong></p>
                                            <p><strong>User Created By:</strong></p>
                                            <p><strong>User Updated:</strong></p>
                                            <p><strong>User Updated By:</strong></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><?php echo $userData['username']; ?></p>
                                            <p><?php echo $userData['email']; ?></p>
                                            <p><?php echo $userData['created_at']; ?></p>
                                            <p><?php echo $user->getUserUsername(intval($userData['created_by'])); ?></p>
                                            <p><?php echo $userData['updated_at']; ?></p>
                                            <p><?php echo $user->getUserUsername(intval($userData['updated_by'])); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- User Roles -->
                            <div class="col-md-6" style="height: 100%;">
                                <h3>User Roles</h3>
                                <div id="info" class="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Roles:</strong></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p>
                                                <?php
                                                //create a string to hold the roles
                                                $rolesString = "";
                                                //loop through the roles and add them to the string
                                                foreach ($rolesData as $role) {
                                                    //if the string is empty, add the role name
                                                    if ($rolesString == "") {
                                                        $rolesString = $role['name'];
                                                    } else {
                                                        //if the string is not empty, add a comma and the role name
                                                        $rolesString = $rolesString . ", " . $role['name'];
                                                    }
                                                }
                                                echo $rolesString;
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- User Activity -->
                            <div class="col-md-12" style="height: 100%;">
                                <h3 id="activity_log">User Activity</h3>
                                <div id="info" class="">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-scroll">
                                                <table id="dataTable" class="table table-striped table-bordered">
                                                    <?php /*confirm user has a role with read activity permissions*/
                                                    //get the id of the read activity permission
                                                    $readActivityPermissionID = $permissionsObject->getPermissionIdByName('READ ACTIVITY');

                                                    //boolean to track if the user has the read activity permission
                                                    $hasReadActivityPermission = $auth->checkUserPermission(intval($currentUserId), $readActivityPermissionID);

                                                    //only show the activity log if the user has the read activity permission
                                                    if ($hasReadActivityPermission || $isOwnProfile) { ?>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Date</th>
                                                                <th scope="col">Action</th>
                                                                <th scope="col">Performed On</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            //get the activity data by user id
                                                            $activityData = $activity->getAllActivityByUser(intval($userId));
                                                            //loop through the activity data and display it
                                                            foreach ($activityData as $activity) {
                                                                echo "<tr>";
                                                                echo "<td>" . $activity['action_date'] . "</td>";
                                                                echo "<td>" . $activity['action'] . "</td>";
                                                                echo "<td>" . $activity['performed_on'] . "</td>";
                                                                echo "</tr>";
                                                            }
                                                            ?>
                                                        </tbody>
                                                    <?php } else { ?>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Date</th>
                                                                <th scope="col">Action</th>
                                                                <th scope="col">Performed On</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="3">You do not have permission to view this data.</td>
                                                            </tr>
                                                        </tbody>
                                                    <?php } ?>
                                                </table>
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
                            <!-- Delete User Modal-->
                            <!-- Modal -->
                            <div id="deleteUserModal" class="modal fade delete" tabindex="-1" role="dialog" aria-labelledby="#userDeleteModal" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title" id="userDeleteModal">Delete User -
                                                <?php echo $user->getUserUsername($userId); ?></h3>
                                            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this user?</p>
                                            <p>This action cannot be undone.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=single&action=delete&id=' . $userId; ?>" method="post">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete User</button>
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
                        type: "date",
                        format: "YYYY-MM-DD HH:mm:ss",
                        sortSequence: ["desc", "asc"]
                    },
                    {
                        select: 1,
                        sortSequence: ["desc", "asc"]
                    },
                    {
                        select: 2,
                        sortSequence: ["desc", "asc"]
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
            dt.columns.order([0, 1, 2])
            window.dt = dt
        </script>
<?php }
} ?>
