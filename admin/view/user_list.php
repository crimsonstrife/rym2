<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
} ?>
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
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=add&action=create' ?>"
                        class="btn btn-primary">Add User</a>
                </div>
            </div>
            <div class="card-body">
                <table id="dataTable">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Date Created</th>
                            <th>Created By</th>
                            <th>Date Updated</th>
                            <th>Updated By</th>
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
                        $userArray = $usersData->getAllUsers();
                        //for each user, display it
                        foreach ($userArray as $user) {
                        ?>
                        <tr>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <?php
                                //get the roles of the user as a list
                                $roles = $usersData->getUserRoles($user['id']);

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
                            <td><?php echo $user['created_at']; ?></td>
                            <td><?php echo $usersData->getUserUsername(intval($user['created_by'])); ?></td>
                            <td><?php echo $user['updated_at']; ?></td>
                            <td><?php echo $usersData->getUserUsername(intval($user['updated_by'])); ?></td>
                            <td>
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=single&id=' . $user['id']; ?>"
                                    class="btn btn-success">View</a>
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=edit&action=edit&id=' . $user['id']; ?>"
                                    class="btn btn-primary">Edit</a>
                                <a href="/delete/delete_user.php?id=<?php echo $user['id']; ?>"
                                    class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="card-footer">
                    <!-- Download CSV -->
                    <?php
                    //prepare the user array for download
                    $csvArray = $userArray;
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
                    <form target="_blank"
                        action="<?php echo APP_URL . '/admin/download.php?type=subjects&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>"
                        method="post" enctype="multipart/form-data">
                        <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ?>
