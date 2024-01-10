<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//include the permissions class
$permissionsObject = new Permission();

//include the auth class
$auth = new Authenticator();

/* confirm user has a role with read subject permissions */
//get the read subject permission id
$readPermissionID = $permissionsObject->getPermissionIdByName('READ SUBJECT');

//boolean to check if the user has the read subject permission
$hasReadPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readPermissionID);

//if the user does not have the read subject permission, display an error message and do not display the page
if (!$hasReadPermission) {
    die('Error: You do not have permission to perform this request.');
} else { ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Subjects/Fields</h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-table"></i>
                    Subjects List
                </div>
                <div class="card-tools">
                    <?php
                        /*confirm user has a role with create subject permissions*/
                        //get the id of the create subject permission
                        $createSubjectPermissionID = $permissionsObject->getPermissionIdByName('CREATE SUBJECT');

                        //boolean to check if the user has the create subject permission
                        $hasCreatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createSubjectPermissionID);

                        //if the user has the create subject permission, display the add subject button
                        if ($hasCreatePermission) {
                        ?>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=subjects&subject=add&action=create' ?>"
                        class="btn btn-primary">Add Subject</a>
                    <?php } ?>
                </div>
            </div>
            <div class="card-body table-scroll">
                <table id="dataTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Subject/Field Name</th>
                            <th>Date Created</th>
                            <th>Created By</th>
                            <th>Date Updated</th>
                            <th>Updated By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            /* Setup datatable of Subjects */
                            //include the Area of Interest class
                            $subjectsData = new AreaOfInterest();
                            //include the users class
                            $usersData = new User();
                            //get all subjects
                            $subjectsArray = $subjectsData->getAllSubjects();
                            //for each event, display it
                            foreach ($subjectsArray as $subject) {
                            ?>
                        <tr>
                            <td><?php echo $subject['name']; ?></td>
                            <td><?php echo $subject['created_at']; ?></td>
                            <td><?php echo $usersData->getUserUsername($subject['created_by']); ?></td>
                            <td><?php echo $subject['updated_at']; ?></td>
                            <td><?php echo $usersData->getUserUsername($subject['updated_by']); ?></td>
                            <td>
                                <?php /*confirm user has a role with update subject permissions*/
                                        //get the update subject permission id
                                        $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE SUBJECT');

                                        //boolean to check if the user has the update subject permission
                                        $hasUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                                        //only show the edit button if the user has the update subject permission
                                        if ($hasUpdatePermission) { ?>
                                <a href="<?php echo APP_URL . '/admin/dashboard.php?view=subjects&subject=edit&action=edit&id=' . $subject['id']; ?>"
                                    class="btn btn-primary">Edit Subject</a>
                                <?php } ?>
                                <?php /*confirm user has a role with delete subject permissions*/
                                        //get the delete subject permission id
                                        $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE SUBJECT');

                                        //boolean to check if the user has the delete subject permission
                                        $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                                        //only show the delete button if the user has the delete subject permission
                                        if ($hasDeletePermission) { ?>
                                <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteSubjectModal"
                                    onclick="setDeleteID(<?php echo $subject['id']; ?>)">
                                    Delete Subject
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
                <?php /*confirm user has a role with export subjects permissions*/
                    //get the id of the export subjects permission
                    $exportSubjectsPermissionID = $permissionsObject->getPermissionIdByName('EXPORT SUBJECT');

                    //boolean to check if the user has the export subjects permission
                    $hasExportSubjectsPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $exportSubjectsPermissionID);

                    if ($hasExportSubjectsPermission) {
                        //prepare the degree array for download
                        $csvArray = $subjectsArray;
                        //set the created by and updated by to the username
                        foreach ($csvArray as $key => $row) {
                            $csvArray[$key]['created_by'] = $usersData->getUserUsername(intval($row['created_by'])); //get the username of the user who created the subject, and swap out the user id
                            $csvArray[$key]['updated_by'] = $usersData->getUserUsername(intval($row['updated_by'])); //get the username of the user who updated the subject, and swap out the user id
                        }
                        //clean up the column headers to be more readable, i.e. remove underscores and capitalize
                        foreach ($csvArray as $key => $row) {
                            $csvArray[$key] = array(
                                'Subject/Field Name' => $row['name'],
                                'Date Created' => $row['created_at'],
                                'Created By' => $row['created_by'],
                                'Date Updated' => $row['updated_at'],
                                'Updated By' => $row['updated_by']
                            );
                        }
                    ?>
                <form target="_blank"
                    action="<?php echo APP_URL . '/admin/download.php?type=subjects&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>"
                    method="post" enctype="multipart/form-data">
                    <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                </form>
                <?php } else { ?>
                <p class="text-danger">You do not have permission to download the CSV of subjects.</p>
                <button class="btn btn-success" disabled>Export to CSV</button>
                <?php } ?>
            </div>
            <?php if ($hasDeletePermission) { ?>
            <div id="info" class="">
                <!-- Delete Subject Modal-->
                <!-- Modal -->
                <div id="deleteSubjectModal" class="modal fade delete" tabindex="-1" role="dialog"
                    aria-labelledby="#subjectDeleteModal" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="subjectDeleteModal">Delete Subject - <span
                                        id="subjectName-Title">Subject Name</span></h3>
                                <button type="button" class="btn-close close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this subject?</p>
                                <p>This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                                <script>
                                var deleteBaseURL =
                                    "<?php echo APP_URL . '/admin/dashboard.php?view=subjects&subject=single&action=delete&id='; ?>";
                                </script>
                                <form id="deleteSubjectForm" action="" method="post">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                        onclick="clearDeleteID()">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete Subject</button>
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
    //set the subjects array to a javascript variable
    var subjectsArray = <?php echo json_encode($subjectsArray); ?>;

    //function to set the delete id on the action url of the delete modal based on which subject is selected
    function setDeleteID(id) {
        //get the subject name
        var subjectName = subjectsArray.find(subject => subject.id == id).name;
        //set the subject name in the modal title
        document.getElementById("subjectName-Title").innerHTML = subjectName;
        //set the action url of the delete modal
        document.getElementById("deleteSubjectForm").action = deleteBaseURL + id;
    }

    function clearDeleteID() {
        //set the action url of the delete modal
        document.getElementById("deleteSubjectForm").action = "";
    }
    </script>
    <?php } ?>
</div>
<?php ?>
<?php } ?>
