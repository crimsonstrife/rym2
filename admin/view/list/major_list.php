<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//include the permissions class
$permissionsObject = new Permission();

//include the auth class
$auth = new Authenticator();

/* confirm user has a role with read major permissions */
//get the read major permission id
$readPermissionID = $permissionsObject->getPermissionIdByName('READ MAJOR');

//boolean to check if the user has the read major permission
$hasReadPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readPermissionID);

//if the user does not have the read major permission, display an error message and do not display the page
if (!$hasReadPermission) {
    die('Error: You do not have permission to perform this request.');
} else { ?>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Major/Field of Study</h1>
        <div class="row">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-table"></i>
                        Majors List
                    </div>
                    <div class="card-tools">
                        <?php
                        /*confirm user has a role with create major permissions*/
                        //get the id of the create major permission
                        $createMajorPermissionID = $permissionsObject->getPermissionIdByName('CREATE MAJOR');

                        //boolean to check if the user has the create major permission
                        $hasCreatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createMajorPermissionID);

                        //if the user has the create major permission, display the add major button
                        if ($hasCreatePermission) {
                        ?>
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=majors&major=add&action=create' ?>" class="btn btn-primary">Add Major</a>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-body table-scroll">
                    <table id="dataTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Major Name/Title</th>
                                <th>Date Created</th>
                                <th>Created By</th>
                                <th>Date Updated</th>
                                <th>Updated By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            /* Setup datatable of Majors */
                            //include the degree class
                            $degreesData = new Degree();
                            //include the users class
                            $usersData = new User();
                            //get all degree levels
                            $majorsArray = $degreesData->getAllMajors();
                            //for each event, display it
                            foreach ($majorsArray as $major) {
                            ?>
                                <tr>
                                    <td><?php echo $major['name']; ?></td>
                                    <td><?php echo $major['created_at']; ?></td>
                                    <?php
                                    //if the created by id is not null, get the username
                                    if ($major['created_by'] != null) { ?>
                                        <td><?php echo $usersData->getUserUsername($major['created_by']); ?></td>
                                    <?php } else { ?>
                                        <td>Student Submission</td>
                                    <?php } ?>
                                    <td><?php echo $major['updated_at']; ?></td>
                                    <?php
                                    //if the updated by id is not null, get the username
                                    if ($major['updated_by'] != null) { ?>
                                        <td><?php echo $usersData->getUserUsername($major['updated_by']); ?></td>
                                    <?php } else { ?>
                                        <td>Student Submission</td>
                                    <?php } ?>
                                    <td>
                                        <?php /*confirm user has a role with update major permissions*/
                                        //get the update major permission id
                                        $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE MAJOR');

                                        //boolean to check if the user has the update major permission
                                        $hasUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                                        //only show the edit button if the user has the update major permission
                                        if ($hasUpdatePermission) { ?>
                                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=majors&major=edit&action=edit&id=' . $major['id']; ?>" class="btn btn-primary">Edit Major</a>
                                        <?php } ?>
                                        <?php /*confirm user has a role with delete major permissions*/
                                        //get the delete major permission id
                                        $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE MAJOR');

                                        //boolean to check if the user has the delete major permission
                                        $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                                        //only show the delete button if the user has the delete major permission
                                        if ($hasDeletePermission) { ?>
                                            <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteMajorModal" onclick="setDeleteID(<?php echo $major['id']; ?>)">
                                                Delete Major
                                            </button>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <!-- Download CSV -->
                    <?php /*confirm user has a role with export majors permissions*/
                    //get the id of the export majors permission
                    $exportMajorsPermissionID = $permissionsObject->getPermissionIdByName('EXPORT MAJOR');

                    //boolean to check if the user has the export majors permission
                    $hasExportMajorsPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $exportMajorsPermissionID);

                    if ($hasExportMajorsPermission) {
                        //prepare the Major array for download
                        $csvArray = $majorsArray;
                        //set the created by and updated by to the username
                        foreach ($csvArray as $key => $row) {
                            $csvArray[$key]['created_by'] = $usersData->getUserUsername(intval($row['created_by'])); //get the username of the user who created the degree, and swap out the user id
                            $csvArray[$key]['updated_by'] = $usersData->getUserUsername(intval($row['updated_by'])); //get the username of the user who updated the degree, and swap out the user id
                        }
                        //clean up the column headers to be more readable, i.e. remove underscores and capitalize
                        foreach ($csvArray as $key => $row) {
                            $csvArray[$key] = array(
                                'Major Name' => $row['name'],
                                'Date Created' => $row['created_at'],
                                'Created By' => $row['created_by'],
                                'Date Updated' => $row['updated_at'],
                                'Updated By' => $row['updated_by']
                            );
                        }
                    ?>
                        <form target="_blank" action="<?php echo APP_URL . '/admin/download.php?type=majors&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>" method="post" enctype="multipart/form-data">
                            <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                        </form>
                    <?php } else { ?>
                        <p class="text-danger">You do not have permission to download the CSV of majors.</p>
                        <button class="btn btn-success" disabled>Export to CSV</button>
                    <?php } ?>
                </div>
                <?php if ($hasDeletePermission) { ?>
                    <div id="info" class="">
                        <!-- Delete Major Modal-->
                        <!-- Modal -->
                        <div id="deleteMajorModal" class="modal fade delete" tabindex="-1" role="dialog" aria-labelledby="#majorDeleteModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="majorDeleteModal">Delete Major - <span id="majorName-Title">Major Name</span></h3>
                                        <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this major?</p>
                                        <p>This action cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <script>
                                            var deleteBaseURL =
                                                "<?php echo APP_URL . '/admin/dashboard.php?view=majors&major=single&action=delete&id='; ?>";
                                        </script>
                                        <form id="deleteMajorForm" action="" method="post">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="clearDeleteID()">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Delete Major</button>
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
                //set the majors array to a javascript variable
                var majorsArray = <?php echo json_encode($majorsArray); ?>;

                //function to set the delete id on the action url of the delete modal based on which major is selected
                function setDeleteID(id) {
                    //get the major name
                    var majorName = majorsArray.find(major => major.id == id).name;
                    //set the major name in the modal title
                    document.getElementById("majorName-Title").innerHTML = majorName;
                    //set the action url of the delete modal
                    document.getElementById("deleteMajorForm").action = deleteBaseURL + id;
                }

                function clearDeleteID() {
                    //set the action url of the delete modal
                    document.getElementById("deleteMajorForm").action = "";
                }
            </script>
        <?php } ?>
    </div>
<?php } ?>
