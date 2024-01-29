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

/* confirm user has a role with read job permissions */
//get the read job permission id
$readPermissionID = $permissionsObject->getPermissionIdByName('READ JOB');

//boolean to check if the user has the read job permission
$hasReadPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readPermissionID);

/*confirm user has a role with delete job permissions*/
//get the delete job permission id
$deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE JOB');

//boolean to check if the user has the delete job permission
$hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

//if the user does not have the read job permission, display an error message and do not display the page
if (!$hasReadPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
} else {
?>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Open Jobs/Internships</h1>
        <div class="row">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-table"></i>
                        Jobs List
                    </div>
                    <div class="card-tools">
                        <?php
                        /*confirm user has a role with create job permissions*/
                        //get the id of the create job permission
                        $createJobPermissionID = $permissionsObject->getPermissionIdByName('CREATE JOB');

                        //boolean to check if the user has the create job permission
                        $hasCreatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createJobPermissionID);

                        //if the user has the create job permission, display the add job button
                        if ($hasCreatePermission) {
                        ?>
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=add&action=create' ?>" class="btn btn-primary">Add Job</a>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <table id="dataTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Summary</th>
                                    <th>Position Type</th>
                                    <th>Field</th>
                                    <th>Date Created</th>
                                    <th>Created By</th>
                                    <th>Date Updated</th>
                                    <th>Updated By</th>
                                    <th data-sortable="false">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                /* Setup datatable of Jobs */
                                //include the job class
                                $jobsData = new Job();
                                //include the users class
                                $usersData = new User();
                                //include the areas of interest class
                                $fieldData = new JobField();
                                //get all jobs
                                $jobsArray = $jobsData->getAllJobs();
                                //for each job, display it
                                foreach ($jobsArray as $job) {
                                    //trim the summary to 100 characters, and add an ellipsis
                                    $formattedSummary = strlen($job['summary']) > 100 ? substr($job['summary'], 0, 100) . "[...]" : $job['summary'];
                                ?>
                                    <tr>
                                        <td><?php echo $job['name']; ?></td>
                                        <td><?php echo $formattedSummary; ?></td>
                                        <td><?php echo $jobsData->getJobType($job['id']); ?></td>
                                        <td><?php echo $fieldData->getSubject($job['field'])[0]['name']; ?></td>
                                        <td><?php echo $job['created_at']; ?></td>
                                        <td><?php echo $usersData->getUserUsername($job['created_by']); ?></td>
                                        <td><?php echo $job['updated_at']; ?></td>
                                        <td><?php echo $usersData->getUserUsername($job['updated_by']); ?></td>
                                        <td>
                                            <span class="td-actions">
                                                <?php /*confirm user has a role with read job permissions*/
                                                //get the read job permission id
                                                $updatePermissionID = $permissionsObject->getPermissionIdByName('READ JOB');

                                                //boolean to check if the user has the read job permission
                                                $hasReadPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                                                //only show the edit button if the user has the read job permission
                                                if ($hasReadPermission) { ?>
                                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=single' ?>&id=<?php echo $job['id']; ?>" class="btn btn-success">View Job</a>
                                                <?php } ?>
                                                <?php /*confirm user has a role with update job permissions*/
                                                //get the update job permission id
                                                $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE JOB');

                                                //boolean to check if the user has the update job permission
                                                $hasUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                                                //only show the edit button if the user has the update job permission
                                                if ($hasUpdatePermission) { ?>
                                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=edit&action=edit&id=' . $job['id']; ?>" class="btn btn-primary">Edit Job</a>
                                                <?php } ?>
                                                <?php
                                                //only show the delete button if the user has the delete job permission
                                                if ($hasDeletePermission) { ?>
                                                    <button type="button" id="openDeleteModal" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteJobModal" onclick="setDeleteID(<?php echo $job['id']; ?>)">
                                                        Delete Job
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
                    <?php /*confirm user has a role with export jobs permissions*/
                    //get the id of the export jobs permission
                    $exportJobsPermissionID = $permissionsObject->getPermissionIdByName('EXPORT JOB');

                    //boolean to check if the user has the export jobs permission
                    $hasExportJobsPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $exportJobsPermissionID);

                    if ($hasExportJobsPermission) {
                        //prepare the Major array for download
                        $csvArray = $jobsArray;
                        //set the created by and updated by to the username
                        foreach ($csvArray as $key => $row) {
                            $csvArray[$key]['created_by'] = $usersData->getUserUsername(intval($row['created_by'])); //get the username of the user who created the degree, and swap out the user id
                            $csvArray[$key]['updated_by'] = $usersData->getUserUsername(intval($row['updated_by'])); //get the username of the user who updated the degree, and swap out the user id
                        }
                        //set the field to the name of the area of interest
                        foreach ($csvArray as $key => $row) {
                            $csvArray[$key]['field'] = $fieldData->getSubject(intval($row['field']))[0]['name']; //get the name of the area of interest, and swap out the id
                        }
                        //clean up the column headers to be more readable, i.e. remove underscores and capitalize
                        foreach ($csvArray as $key => $row) {
                            $csvArray[$key] = array(
                                'Job Title' => $row['name'],
                                'Summary' => $row['summary'],
                                'Position Type' => $row['type'],
                                'Field' => $row['field'],
                                'Date Created' => $row['created_at'],
                                'Created By' => $row['created_by'],
                                'Date Updated' => $row['updated_at'],
                                'Updated By' => $row['updated_by']
                            );
                        }
                    ?>
                        <form target="_blank" action="<?php echo APP_URL . '/admin/download.php?type=jobs&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>" method="post" enctype="multipart/form-data">
                            <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                        </form>
                    <?php } else { ?>
                        <p class="text-danger">You do not have permission to download the CSV of jobs.</p>
                        <button class="btn btn-success" disabled>Export to CSV</button>
                    <?php } ?>
                </div>
                <?php if ($hasDeletePermission) { ?>
                    <div id="info" class="">
                        <!-- Delete Job Modal-->
                        <!-- Modal -->
                        <div id="deleteJobModal" class="modal fade delete" tabindex="-1" role="dialog" aria-labelledby="#jobDeleteModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="jobDeleteModal">Delete Job - <span id="jobName-Title">Job
                                                Name</span></h3>
                                        <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this job?</p>
                                        <p>This action cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <script>
                                            var deleteBaseURL =
                                                "<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=single&action=delete&id='; ?>";
                                        </script>
                                        <form id="deleteJobForm" action="" method="post">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="clearDeleteID()">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Delete Job</button>
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
                //set the jobs array to a javascript variable
                var jobsArray = <?php echo json_encode($jobsArray); ?>;

                //function to set the delete id on the action url of the delete modal based on which job is selected
                function setDeleteID(id) {
                    //get the job name
                    var jobName = jobsArray.find(job => job.id == id).name;
                    //set the job name in the modal title
                    document.getElementById("jobName-Title").innerHTML = jobName;
                    //set the action url of the delete modal
                    document.getElementById("deleteJobForm").action = deleteBaseURL + id;
                }

                function clearDeleteID() {
                    //set the action url of the delete modal
                    document.getElementById("deleteJobForm").action = "";
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
            scrollX: "100%",
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
                }, {
                    select: 3,
                    sortSequence: ["desc", "asc"]
                }, {
                    select: 4,
                    type: "date",
                    format: "YYYY-MM-DD HH:mm:ss",
                    sortSequence: ["desc", "asc"]
                }, {
                    select: 5,
                    sortSequence: ["desc", "asc"]
                }, {
                    select: 6,
                    type: "date",
                    format: "YYYY-MM-DD HH:mm:ss",
                    sortSequence: ["desc", "asc"]
                }, {
                    select: 7,
                    sortSequence: ["desc", "asc"]
                }, {
                    select: 8,
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
<div class='${options.classes.container}' style='${options.scrollY.length ? ` height: ${options.scrollY}; overflow-Y: auto;` : ""} ${options.scrollX.length ? ` width: ${options.scrollX}; overflow-X: auto;` : ""}'></div>
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
        dt.columns.order([0, 1, 2, 3, 4, 5, 6, 7, 8])
        window.dt = dt
    </script>
<?php } ?>
