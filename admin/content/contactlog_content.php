<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
} // idea from https://stackoverflow.com/a/409515 (user UnkwnTech)

//include the permissions class
$permissionsObject = new Permission();

//auth class
$auth = new Authenticator();

//check that the view dashboard permission is set
if (!isset($hasViewDashboardPermission)) {
    //set the error type
    $thisError = 'CONFIGURATION_ERROR';

    //include the error message file
    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
} else {
    //check that the user has the view dashboard permission
    if (!$hasViewDashboardPermission) {
        //set the error type
        $thisError = 'DASHBOARD_PERMISSION_ERROR';

        //include the error message file
        include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
    } else {

        /*confirm user has a role with read contact permissions*/
        //get the id of the read contact permission
        $relevantPermissionID = $permissionsObject->getPermissionIdByName('READ CONTACT');

        //boolean to track if the user has the read contact permission
        $hasPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $relevantPermissionID);

        //prevent the user from accessing the page if they do not have the relevant permission
        if (!$hasPermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else {
?>
<!-- main content -->
<div id="layout_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Contact Log</h1>
            <div class="row">
                <!-- Contact Log -->
                <div class="row">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fa-solid fa-table"></i>
                            Student Outreach Contact Log
                        </div>
                        <div class="card-body">
                            <div>
                                <table id="dataTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Student Email</th>
                                            <th>School</th>
                                            <th>Degree</th>
                                            <th>Automatic Email?</th>
                                            <th>Send Date</th>
                                            <th>Sending User</th>
                                            <th>Subject</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                                    /* Setup datatable of students */
                                                    //include the student class
                                                    $studentsData = new Student();
                                                    //include the school class
                                                    $schoolsData = new School();
                                                    //include the degree class
                                                    $degreesData = new Degree();
                                                    //include the contact class
                                                    $contactsData = new Contact();
                                                    //include the user class
                                                    $userData = new User();
                                                    //get the contact log
                                                    $contactLogArray = $contactsData->getContactLog();
                                                    //sort the contact log by date
                                                    usort($contactLogArray, function ($a, $b) {
                                                        return $b['send_date'] <=> $a['send_date'];
                                                    });
                                                    foreach ($contactLogArray as $entry) {
                                                        //get the student id
                                                        $studentId = intval($entry['student']);
                                                        //get the student email
                                                        $studentEmail = $studentsData->getStudentEmail($studentId);
                                                        //get the student name
                                                        $studentName = $studentsData->getStudentFullName($studentId);
                                                        //get the school id from the student id
                                                        $schoolId = $studentsData->getStudentSchool($studentId);
                                                        //get the school name
                                                        $schoolName = $schoolsData->getSchoolName($schoolId);
                                                        //get the degree id from the student id
                                                        $degreeId = $studentsData->getStudentDegree($studentId);
                                                        //get the degree name
                                                        $degreeName = $studentsData->getStudentDegree($studentId);
                                                        //get the sending user id
                                                        $sendingUserId = $entry['sender'];
                                                        //get the sending user name, if the sending user is not null
                                                        if ($sendingUserId != NULL) {
                                                            $sendingUserName = $userData->getUserUsername(intval($sendingUserId));
                                                        } else {
                                                            $sendingUserName = "SYSTEM";
                                                        }
                                                        //email subject
                                                        $subject = $entry['subject'];
                                                        //email body
                                                        $body = $entry['message'];
                                                        //email send date
                                                        $sendDate = formatDate($entry['send_date']);
                                                        //automatic email int to boolean
                                                        $automaticEmail = intval($entry['auto']);
                                                        if ($automaticEmail == 1) {
                                                            $automaticEmail = "Automatic";
                                                        } else {
                                                            $automaticEmail = "Manual";
                                                        }
                                                    ?>
                                        <tr>
                                            <td><?php echo $studentName; ?></td>
                                            <td><?php echo $studentEmail; ?></td>
                                            <td><?php echo $schoolName; ?></td>
                                            <td><?php echo $degreeName; ?></td>
                                            <td><?php echo $automaticEmail; ?></td>
                                            <td><?php echo $sendDate; ?></td>
                                            <td><?php echo $sendingUserName; ?></td>
                                            <td><?php echo $subject; ?></td>
                                        </tr>
                                        <?php
                                                        //setup a download array
                                                        $studentContactArray[] = array(
                                                            'Student Name' => $studentName,
                                                            'Student Email' => $studentEmail,
                                                            'School' => $schoolName,
                                                            'Degree' => $degreeName,
                                                            'Automatic Email' => $automaticEmail,
                                                            'Sent Date' => $sendDate,
                                                            'Sending User' => $sendingUserName,
                                                            'subject' => $subject,
                                                            'Message' => $body
                                                        );
                                                    }
                                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <!-- Download CSV -->
                            <?php
                                        //prepare the user array for download
                                        $csvArray = $studentContactArray; ?>
                            <form target="_blank"
                                action="<?php echo APP_URL . '/admin/download.php?type=contact_log&payload=' . base64_encode(urlencode(json_encode($csvArray))); ?>"
                                method="post" enctype="multipart/form-data">
                                <input type="submit" name="export" value="Export to CSV" class="btn btn-success" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script type="module">
import {
    DataTable
} from "<?php echo getLibraryPath() . 'simple-datatables/module.js' ?>"
const dt = new DataTable("table", {
    scrollY: "100vh",
    rowNavigation: true,
    perPageSelect: [5, 10, 15, 20, 25, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100, ["All", -1]],
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
            type: "date",
            format: "MM/DD/YYYY",
            sortSequence: ["desc", "asc"]
        },
        {
            select: 6,
            sortSequence: ["desc", "asc"]
        },
        {
            select: 7,
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
dt.columns.order([0, 1, 2, 3, 4, 5, 6, 7])
window.dt = dt
</script>
<?php }
    }
} ?>
