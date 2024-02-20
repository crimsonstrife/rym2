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

/* confirm user has a role with read user permissions */
//get the read user permission id
$readPermissionID = $permissionsObject->getPermissionIdByName('READ USER');

//boolean to check if the user has the read user permission
$hasReadPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readPermissionID);

//get the edit user permission id
$editPermissionID = $permissionsObject->getPermissionIdByName('UPDATE USER');

//boolean to check if the user has the edit user permission
$hasEditPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $editPermissionID);

//get the delete user permission id
$deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE USER');

//boolean to check if the user has the delete user permission
$hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

//get the is admin permission id
$isAdminPermissionID = $permissionsObject->getPermissionIdByName('IS ADMIN');

//boolean to check if the user has the is admin permission
$hasIsAdminPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $isAdminPermissionID);

//get the is super admin permission id
$isSuperAdminPermissionID = $permissionsObject->getPermissionIdByName('IS SUPERADMIN');

//boolean to check if the user has the is super admin permission
$hasIsSuperAdminPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $isSuperAdminPermissionID);

//if the user does not have the read user permission, display an error message and do not display the page
if (!$hasReadPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
} else {
?>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Users</h1>
        <div class="row">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-table"></i>
                        User List
                    </div>
                    <div class="card-tools">
                        <?php
                        /*confirm user has a role with create user permissions*/
                        //get the id of the create user permission
                        $createUserPermissionID = $permissionsObject->getPermissionIdByName('CREATE USER');

                        //boolean to check if the user has the create user permission
                        $hasCreatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createUserPermissionID);

                        //if the user has the create user permission, display the add user button
                        if ($hasCreatePermission) {
                        ?>
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=add&action=create' ?>" class="btn btn-primary">Add User</a>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <table id="dataTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Date Created</th>
                                    <th>Created By</th>
                                    <th>Date Updated</th>
                                    <th>Updated By</th>
                                    <th data-sortable="false">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                /* Setup datatable of Users */
                                //include the User class
                                $usersData = new User();
                                //include the Roles class
                                $rolesData = new Roles();
                                //get all users
                                $usersArray = $usersData->getAllUsers();

                                //count the number of users
                                $userCount = count($usersArray);

                                //for each user, check if they are a super admin and increment the count
                                $superAdminCount = 0;
                                foreach ($usersArray as $singleUser) {
                                    //check if the user is a super admin
                                    $userIsSuperAdmin = $auth->checkUserPermission(intval($singleUser['id']), $isSuperAdminPermissionID);
                                    //if the user is a super admin, increment the count
                                    if ($userIsSuperAdmin) {
                                        $superAdminCount++;
                                    }
                                    //for each user, display it
                                ?>
                                    <tr>
                                        <td><?php echo $singleUser['username']; ?></td>
                                        <td><?php echo $singleUser['email']; ?></td>
                                        <?php
                                        //get the roles of the user as a list
                                        $roles = $usersData->getUserRoles($singleUser['id']);

                                        //check if the current and selected users match, if they do, set to true, if not, set to false
                                        $currentAndSelectedUsersMatch = false;
                                        if (intval($_SESSION['user_id']) == intval($singleUser['id'])) {
                                            $currentAndSelectedUsersMatch = true;
                                        } else {
                                            $currentAndSelectedUsersMatch = false;
                                        }

                                        //check if the selected user is a super admin
                                        $selectedUserIsSuperAdmin = false;
                                        if ($auth->checkUserPermission(intval($singleUser['id']), $isSuperAdminPermissionID) == 1) {
                                            $selectedUserIsSuperAdmin = true;
                                        } else {
                                            $selectedUserIsSuperAdmin = false;
                                        }

                                        //check if the selected user is an admin
                                        $selectedUserIsAdmin = false;
                                        if ($auth->checkUserPermission(intval($singleUser['id']), $isAdminPermissionID) == 1) {
                                            $selectedUserIsAdmin = true;
                                        } else {
                                            $selectedUserIsAdmin = false;
                                        }

                                        //check if the current user is a super admin
                                        $currentUserIsSuperAdmin = false;
                                        if ($auth->checkUserPermission(intval($_SESSION['user_id']), $isSuperAdminPermissionID) == 1) {
                                            $currentUserIsSuperAdmin = true;
                                        } else {
                                            $currentUserIsSuperAdmin = false;
                                        }

                                        //check if the current user is an admin
                                        $currentUserIsAdmin = false;
                                        if ($auth->checkUserPermission(intval($_SESSION['user_id']), $isAdminPermissionID) == 1) {
                                            $currentUserIsAdmin = true;
                                        } else {
                                            $currentUserIsAdmin = false;
                                        }

                                        //create a string to hold the roles
                                        $rolesString = "";
                                        //loop through the roles and add them to the string
                                        foreach ($roles as $role) {
                                            //if the string is empty, add the role name
                                            if ($rolesString == "") {
                                                $rolesString = $role['name'];
                                            } else {
                                                //if the string is not empty, add a comma and the role name
                                                $rolesString = $rolesString . ", " . $role['name'];
                                            }
                                        }

                                        /* Display the roles */
                                        //first, check if the roles string is empty, if it is, display a message
                                        if ($rolesString == "") {
                                            echo "<td>No Roles</td>";
                                        } else {
                                            //if the roles string is not empty, break the string by the comma and display each role on a new line
                                            $rolesArray = explode(", ", $rolesString);
                                            echo "<td>";
                                            foreach ($rolesArray as $role) {
                                                echo $role . "<br>";
                                            }
                                            echo "</td>";
                                        }
                                        ?>
                                        <td><?php echo $singleUser['created_at']; ?></td>
                                        <td><?php echo $usersData->getUserUsername(intval($singleUser['created_by'])); ?></td>
                                        <td><?php echo $singleUser['updated_at']; ?></td>
                                        <td><?php echo $usersData->getUserUsername(intval($singleUser['updated_by'])); ?></td>
                                        <td>
                                            <span class="td-actions">
                                                <?php
                                                //if the user does not have the edit user, delete user, or read user permission, do not display the controls
                                                if ($hasEditPermission || $hasDeletePermission || $hasReadPermission) { ?>
                                                    <!-- View User -->
                                                    <?php if ($hasReadPermission) { ?>
                                                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=single&id=' . $singleUser['id']; ?>" class="btn btn-success">View User</a>
                                                    <?php } ?>
                                                    <!-- Edit User -->
                                                    <?php if ($hasEditPermission) {
                                                        /*if the selected user is a super admin, do not allow editing unless the current user is a super admin or matches the selected user id*/
                                                        //if the selected user is a super admin and the current user is not a super admin and the current and selected users do not match, do not allow editing
                                                        if (!$currentAndSelectedUsersMatch && (!$currentUserIsSuperAdmin && $selectedUserIsSuperAdmin)) {
                                                            //disable the edit button
                                                            echo '<a href="#" class="btn btn-primary disabled">Edit User</a>';
                                                        } else if (!$currentAndSelectedUsersMatch && ($currentUserIsSuperAdmin && $selectedUserIsSuperAdmin)) {
                                                            //if the selected user is a super admin and the current user is a super admin and the current and selected users do not match, allow editing
                                                            echo '<a href="' . APP_URL . '/admin/dashboard.php?view=users&user=edit&action=edit&id=' . $singleUser['id'] . '" class="btn btn-primary">Edit User</a>';
                                                        } else if (!$currentAndSelectedUsersMatch && ($currentUserIsAdmin && !$selectedUserIsSuperAdmin)) {
                                                            //if the selected user is not a super admin and the current user is a regular admin and the current and selected users do not match, allow editing
                                                            echo '<a href="' . APP_URL . '/admin/dashboard.php?view=users&user=edit&action=edit&id=' . $singleUser['id'] . '" class="btn btn-primary">Edit User</a>';
                                                        } else if (!$currentAndSelectedUsersMatch && (!$currentUserIsSuperAdmin && !$selectedUserIsSuperAdmin) && (!$currentUserIsAdmin && $selectedUserIsAdmin)) {
                                                            //if the users do not match, neither is a super admin, the current user is not an admin and the selected user is an admin, prohibit editing
                                                            echo '<a href="#" class="btn btn-primary disabled">Edit User</a>';
                                                        } else if (!$currentAndSelectedUsersMatch && (!$currentUserIsSuperAdmin && !$selectedUserIsSuperAdmin) && (!$currentUserIsAdmin && !$selectedUserIsAdmin)) {
                                                            //if the users do not match, neither is a super admin, and neither is an admin, allow editing
                                                            echo '<a href="' . APP_URL . '/admin/dashboard.php?view=users&user=edit&action=edit&id=' . $singleUser['id'] . '" class="btn btn-primary">Edit User</a>';
                                                        } else {
                                                            //always allow the current user to edit their own account
                                                            if ($currentAndSelectedUsersMatch) {
                                                                echo '<a href="' . APP_URL . '/admin/dashboard.php?view=users&user=edit&action=edit&id=' . $singleUser['id'] . '" class="btn btn-primary">Edit User</a>';
                                                            }
                                                        }
                                                    } ?>
                                                    <!-- Delete User -->
                                                    <?php if ($hasDeletePermission) {
                                                        /*only allow super admins to delete other super admins, if the current user is neither a super admin nor an admin, they can only delete other non-admin users*/
                                                        if ($currentAndSelectedUsersMatch == true) { //do not allow the current user to delete their own account
                                                    ?>
                                                            <button type="button" id="openDeleteModal" class="btn btn-danger" disabled>
                                                                Delete User
                                                            </button>
                                                        <?php } else if ($currentAndSelectedUsersMatch == false && (!$currentUserIsSuperAdmin && $selectedUserIsSuperAdmin)) {
                                                            //if the selected user is a super admin and the current user is not a super admin, do not allow deleting
                                                        ?>
                                                            <button type="button" id="openDeleteModal" class="btn btn-danger" disabled>
                                                                Delete User
                                                            </button>
                                                        <?php } else if ($currentAndSelectedUsersMatch == false && ($currentUserIsSuperAdmin && $selectedUserIsSuperAdmin) && ($userCount > 1 && $superAdminCount > 1)) {
                                                            //if the selected user is a super admin and the current user is a super admin, the current and selected users do not match, and they are not the last user or the last super admin allow deleting
                                                        ?>
                                                            <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal" onclick="setDeleteID(<?php echo $singleUser['id']; ?>)">
                                                                Delete User
                                                            </button>
                                                        <?php } else if ($currentAndSelectedUsersMatch == false && ($currentUserIsSuperAdmin && !$selectedUserIsSuperAdmin) && ($userCount > 1 && $superAdminCount > 1)) {
                                                            //if the selected user is not a super admin and the current user is a regular admin, the current and selected users do not match, and they are not the last user, allow deleting
                                                        ?>
                                                            <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal" onclick="setDeleteID(<?php echo $singleUser['id']; ?>)">
                                                                Delete User
                                                            </button>
                                                        <?php } else if ($currentAndSelectedUsersMatch == false && (!$currentUserIsSuperAdmin && !$selectedUserIsSuperAdmin && $currentUserIsAdmin) && ($userCount > 1)) {
                                                            //if the users do not match, neither is a super admin, the current user is not an admin and the selected user is an admin, prohibit deleting
                                                        ?>
                                                            <button type="button" id="openDeleteModal" class="btn btn-danger" disabled>
                                                                Delete User
                                                            </button>
                                                        <?php } else if ($currentAndSelectedUsersMatch == false && (!$currentUserIsSuperAdmin && !$selectedUserIsSuperAdmin && !$currentUserIsAdmin && !$selectedUserIsAdmin)) { //if the users do not match, neither is a super admin, and neither is an admin, and they are not the last user, allow deleting
                                                        ?>
                                                            <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal" onclick="setDeleteID(<?php echo $singleUser['id']; ?>)">
                                                                Delete User
                                                            </button>
                                                        <?php } else if ($currentAndSelectedUsersMatch = false && (!$currentUserIsAdmin && !$currentUserIsSuperAdmin && !$selectedUserIsAdmin && !$selectedUserIsSuperAdmin) && ($userCount > 1)) { ?>
                                                            <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal" onclick="setDeleteID(<?php echo $singleUser['id']; ?>)">
                                                                Delete User
                                                            </button>
                                                        <?php } else if ($currentAndSelectedUsersMatch == false && (!$selectedUserIsSuperAdmin && !$selectedUserIsAdmin) && ($currentUserIsAdmin && $currentUserIsSuperAdmin) && ($userCount > 1)) { ?>
                                                            <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal" onclick="setDeleteID(<?php echo $singleUser['id']; ?>)">
                                                                Delete User
                                                            </button>
                                                        <?php } else if ($currentAndSelectedUsersMatch == false && (($currentUserIsSuperAdmin && $selectedUserIsAdmin) || (($selectedUserIsAdmin && $selectedUserIsAdmin) && ($userCount > 1)))) { ?>
                                                            <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal" onclick="setDeleteID(<?php echo $singleUser['id']; ?>)">
                                                                Delete User
                                                            </button>
                                                        <?php } else if ($currentAndSelectedUsersMatch == true) { ?>
                                                            <button type="button" id="openDeleteModal" class="btn btn-danger" disabled>
                                                                Delete User
                                                            </button>
                                                        <?php } ?>
                                                <?php }
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
                    <!-- Download CSV -->
                    <?php
                    /*confirm user has a role with export users permissions*/
                    //get the id of the export users permission
                    $exportUsersPermissionID = $permissionsObject->getPermissionIdByName('EXPORT USER');

                    //boolean to check if the user has the export users permission
                    $hasExportUsersPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $exportUsersPermissionID);

                    if ($hasExportUsersPermission) {
                        //prepare the user array for download
                        $csvArray = $usersArray;
                        //set the created by and updated by to the username
                        foreach ($csvArray as $key => $row) {
                            //$csvArray[$key]['created_by'] = $usersData->getUserUsername(intval($row['created_by'])); //get the username of the user who created the user, and swap out the user id
                            //$csvArray[$key]['updated_by'] = $usersData->getUserUsername(intval($row['updated_by'])); //get the username of the user who updated the user, and swap out the user id
                        }
                        //clean up the column headers to be more readable, i.e. remove underscores and capitalize
                        foreach ($csvArray as $key => $row) {
                            $csvArray[$key] = array(
                                'Username' => $row['username'],
                                'Email' => $row['email'],
                                'Date Created' => $row['created_at'],
                                'Created By' => $row['created_by'],
                                'Date Updated' => $row['updated_at'],
                                'Updated By' => $row['updated_by']
                            );
                        } ?>
                        <form target="_blank" action="<?php echo APP_URL . '/admin/download.php?type=subjects&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>" method="post" enctype="multipart/form-data">
                            <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                        </form>
                    <?php } else { ?>
                        <p class="text-danger">You do not have permission to download the CSV of users.</p>
                        <button class="btn btn-success" disabled>Export to CSV</button>
                    <?php } ?>
                </div>
                <?php if ($hasDeletePermission) { ?>
                    <div id="info" class="">
                        <!-- Delete User Modal-->
                        <!-- Modal -->
                        <div id="deleteUserModal" class="modal fade delete" tabindex="-1" role="dialog" aria-labelledby="#userDeleteModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="userDeleteModal">Delete User - <span id="userName-Title">User Name</span></h3>
                                        <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this user?</p>
                                        <p>This action cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <script>
                                            var deleteBaseURL =
                                                "<?php echo APP_URL . '/admin/dashboard.php?view=users&user=single&action=delete&id='; ?>";
                                        </script>
                                        <form id="deleteUserForm" action="" method="post">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="clearDeleteID()">Cancel</button>
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
        <?php if ($hasDeletePermission) { ?>
            <script>
                //set the users array to a javascript variable
                var usersArray = <?php echo json_encode($usersArray); ?>;

                //function to set the delete id on the action url of the delete modal based on which user is selected
                function setDeleteID(id) {
                    //get the user name
                    var userName = usersArray.find(user => user.id == id).username;
                    //set the user name in the modal title
                    document.getElementById("userName-Title").innerHTML = userName;
                    //set the action url of the delete modal
                    document.getElementById("deleteUserForm").action = deleteBaseURL + id;
                }

                function clearDeleteID() {
                    //set the action url of the delete modal
                    document.getElementById("deleteUserForm").action = "";
                }
            </script>
        <?php } ?>
        <script type="text/javascript">
            //variables for the datatable
            var tableHeight = "50vh";
            var rowNav = true;
            var pageSelect = [5, 10, 15, 20, 25, 50, ["All", -1]];
            var columnArray = [{
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
                    sortSequence: ["desc", "asc"]
                },
                {
                    select: 7,
                    sortable: false,
                    searchable: false
                }
            ];
            var columnOrder = [0, 1, 2, 3, 4, 5, 6, 7];
        </script>
    </div>
<?php } ?>
