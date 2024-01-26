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

//include the users class
$usersData = new User();

/* confirm user has a role with read media permissions */
//get the read media permission id
$readPermissionID = $permissionsObject->getPermissionIdByName('READ MEDIA');

//boolean to check if the user has the read media permission
$hasReadPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readPermissionID);

//if the user does not have the read media permission, display an error message and do not display the page
if (!$hasReadPermission) {
    //set the error type
    $thisError = 'PERMISSION_ERROR_ACCESS';

    //include the error message file
    include_once(__DIR__ . '/../../../includes/errors/errorMessage.inc.php');
} else {
?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Media</h1>
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-table"></i>
                    Media List
                </div>
                <div class="card-tools">
                    <?php
                        /*confirm user has a role with create media permissions*/
                        //get the id of the create media permission
                        $createMediaPermissionID = $permissionsObject->getPermissionIdByName('CREATE MEDIA');

                        //boolean to check if the user has the create media permission
                        $hasCreatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createMediaPermissionID);

                        //if the user has the create media permission, display the add media button
                        if ($hasCreatePermission) {
                        ?>
                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=media&media=add&action=create' ?>"
                        class="btn btn-primary">Add Media</a>
                    <?php } ?>
                    <!-- Media View Toggle -->
                    <div class="btn-group btn-group-toggle">
                        <button type="button" class="btn btn-secondary active" autocomplete="off" id="listView"
                            onclick="toggleMediaView('list')">List View</button>
                        <button type="button" class="btn btn-secondary" id="galleryView" autocomplete="off"
                            onclick="toggleMediaView('gallery')">Gallery View</button>
                    </div>
                </div>
            </div>
            <?php /* Setup datatable of media */
                //include the media class
                $mediaData = new Media();
                //include the school class
                $schoolsData = new School();
                //include the users class
                $usersData = new User();
                //get all media
                $mediaArray = $mediaData->getMedia();
                ?>
            <!-- Media List View -->
            <div id="list-view" class="card-body">
                <div>
                    <table id="dataTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>File Type</th>
                                <th>File Size</th>
                                <th>Resolution</th>
                                <th>Date Created</th>
                                <th>Created By</th>
                                <th>Date Updated</th>
                                <th>Updated By</th>
                                <th data-sortable="false">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                //for each media, display it
                                foreach ($mediaArray as $media) {
                                ?>
                            <tr>
                                <td><?php echo $media['filename']; ?></td>
                                <td><?php echo "." . strtoupper($media['filetype']); ?></td>
                                <td>
                                    <?php if ($media['filesize'] != NULL) {
                                                echo formatFilesize($media['filesize']);
                                            } else {
                                                echo "N/A";
                                            } ?>
                                </td>
                                <?php
                                        $imagePath = __DIR__ . '/../../../public/content/uploads/' . $media['filename'];
                                        $imageDimensions = file_exists($imagePath) ? getImageDimensions($imagePath) : null;
                                        ?>
                                <td>
                                    <?php if ($imageDimensions != NULL) {
                                                echo strval($imageDimensions[0]) . "x" . strval($imageDimensions[1]);
                                            } else {
                                                echo "N/A";
                                            } ?>
                                </td>
                                <td><?php echo $media['created_at']; ?></td>
                                <td><?php echo $usersData->getUserUsername($media['created_by']); ?>
                                </td>
                                <td><?php echo $media['updated_at']; ?></td>
                                <td><?php echo $usersData->getUserUsername($media['updated_by']); ?>
                                </td>
                                <td>
                                    <span class="td-actions">
                                        <?php /*confirm user has a role with read media permissions*/
                                                //get the read media permission id
                                                $updatePermissionID = $permissionsObject->getPermissionIdByName('READ MEDIA');

                                                //boolean to check if the user has the read media permission
                                                $hasReadPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                                                //only show the edit button if the user has the read media permission
                                                if ($hasReadPermission) { ?>
                                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=media&media=single' ?>&id=<?php echo $media['id']; ?>"
                                            class="btn btn-success">View Media</a>
                                        <?php } ?>
                                        <?php /*confirm user has a role with update media permissions*/
                                                //get the update media permission id
                                                $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE MEDIA');

                                                //boolean to check if the user has the update media permission
                                                $hasUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                                                //only show the edit button if the user has the update media permission
                                                if ($hasUpdatePermission) { ?>
                                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=media&media=edit&action=edit&id=' . $media['id']; ?>"
                                            class="btn btn-primary">Edit Media</a>
                                        <?php } ?>
                                        <?php /*confirm user has a role with delete media permissions*/
                                                //get the delete media permission id
                                                $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE MEDIA');

                                                //boolean to check if the user has the delete media permission
                                                $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                                                //only show the delete button if the user has the delete media permission
                                                if ($hasDeletePermission) { ?>
                                        <button type="button" id="openDeleteModal" class="btn btn-danger"
                                            data-bs-toggle="modal" data-bs-target="#deleteMediaModal"
                                            onclick="setDeleteID(<?php echo $media['id']; ?>)">
                                            Delete Media
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
            <!-- End Media List View -->
            <!-- Media Gallery View -->
            <div id="gallery-view" class="card-body hidden">
                <div class="overflow-auto">
                    <div class="container">
                        <?php
                            // calculate the number of columns and rows to display, based on the number of media in the array
                            $numMedia = count($mediaArray); //get the number of media
                            $numColumns = 4; //set the number of columns (4 columns per row)
                            $numRows = ceil($numMedia / $numColumns); //calculate the number of rows needed

                            //for each row, display the media
                            for ($i = 0; $i < $numRows; $i++) {
                            ?>
                        <div class="row gallery-row">
                            <?php
                                    //for each column, display the media
                                    for ($n = 0; $n < $numColumns; $n++) {
                                        $mediaIndex = ($i * $numColumns) + $n; //calculate the index of the media in the array
                                        if ($mediaIndex < $numMedia) { //if the index is less than the number of media, display the media
                                    ?>
                            <div class="col-md-3">
                                <div class="thumbnail">
                                    <?php
                                                    //get the media id
                                                    $mediaID = $mediaArray[$mediaIndex]['id'];

                                                    //get the media filename
                                                    $mediaFilename = $mediaArray[$mediaIndex]['filename'];

                                                    //get the created by user id
                                                    $createdBy = $mediaArray[$mediaIndex]['created_by'];

                                                    //get the created by username
                                                    $createdByUsername = $usersData->getUserUsername($createdBy);

                                                    //get the updated by user id
                                                    $updatedBy = $mediaArray[$mediaIndex]['updated_by'];

                                                    //get the updated by username
                                                    $updatedByUsername = $usersData->getUserUsername($updatedBy);

                                                    //swap the ids for the usernames in the media array
                                                    $mediaArray[$mediaIndex]['created_by'] = $createdByUsername;
                                                    $mediaArray[$mediaIndex]['updated_by'] = $updatedByUsername;
                                                    ?>
                                    <div class="thumbnail-container"
                                        style="background-image: url('<?php echo getAssetPath() . 'img/transparency.svg' ?>'); background-size:cover;">
                                        <img id="thumbnail" class="img-thumbnail"
                                            src="<?php echo getUploadPath() . $mediaData->getMediaThumbnail(intval($mediaID)); ?>"
                                            alt="<?php echo $mediaFilename; ?>">
                                    </div>
                                    <div class="caption">
                                        <span class="td-actions">
                                            <?php /*confirm user has a role with read media permissions*/
                                                            //get the read media permission id
                                                            $updatePermissionID = $permissionsObject->getPermissionIdByName('READ MEDIA');

                                                            //boolean to check if the user has the read media permission
                                                            $hasReadPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                                                            //only show the edit button if the user has the read media permission
                                                            if ($hasReadPermission) { ?>
                                            <button type="button" id="openReadModal" class="btn btn-primary"
                                                data-bs-toggle="modal" data-bs-target="#readMediaModal"
                                                onclick="setReadID(<?php echo $mediaID; ?>)">
                                                View Media
                                            </button>
                                            <?php } ?>
                                        </span>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- End Media Gallery View -->
            <div class="card-footer">
            </div>
            <?php if ($hasDeletePermission) { ?>
            <div id="info" class="">
                <!-- Delete Media Modal-->
                <!-- Modal -->
                <div id="deleteMediaModal" class="modal fade delete" tabindex="-1" role="dialog"
                    aria-labelledby="#mediaDeleteModal" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="mediaDeleteModal">Delete Media - <span
                                        id="mediaName-Title">Media Name</span></h3>
                                <button type="button" class="btn-close close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this media?</p>
                                <p>This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                                <script>
                                var deleteBaseURL =
                                    "<?php echo APP_URL . '/admin/dashboard.php?view=media&media=single&action=delete&id='; ?>";
                                </script>
                                <form id="deleteMediaForm" action="" method="post">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                        onclick="clearDeleteID()">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete Media</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php if ($hasReadPermission) { ?>
            <div id="info" class="">
                <!-- Read Media Modal-->
                <!-- Modal -->
                <div id="readMediaModal" class="modal fade read" tabindex="-1" role="dialog"
                    aria-labelledby="#mediaReadModal" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="mediaReadModal">Read Media - <span
                                        id="mediaName-Title">Media Name</span></h3>
                                <button type="button" class="btn-close close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <div class="row">
                                        <!-- Media Image -->
                                        <div class="col-md-6">
                                            <img id="thumbnail" class="img-thumbnail modal-thumb" src="#" alt="#">
                                        </div>
                                        <!-- Media Information -->
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>File Name:</strong></p>
                                                    <p><strong>File Type:</strong></p>
                                                    <p><strong>File Size:</strong></p>
                                                    <p><strong>Date Created:</strong></p>
                                                    <p><strong>Created By:</strong></p>
                                                    <p><strong>Date Updated:</strong></p>
                                                    <p><strong>Updated By:</strong></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p id="filename"></p>
                                                    <p id="filetype"></p>
                                                    <p id="filesize"></p>
                                                    <p id="created_at"></p>
                                                    <p id="created_by"></p>
                                                    <p id="updated_at"></p>
                                                    <p id="updated_by"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <script>
                                    var deleteBaseURL =
                                        "<?php echo APP_URL . '/admin/dashboard.php?view=media&media=single&action=delete&id='; ?>";
                                    </script>
                                    <span class="td-actions">
                                        <form id="readMediaForm" action="" method="post">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                                onclick="clearReadID()">Cancel</button>
                                            <?php /*confirm user has a role with read media permissions*/
                                                    //get the read media permission id
                                                    $updatePermissionID = $permissionsObject->getPermissionIdByName('READ MEDIA');

                                                    //boolean to check if the user has the read media permission
                                                    $hasReadPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                                                    //only show the edit button if the user has the read media permission
                                                    if ($hasReadPermission) { ?>
                                            <a id="view-media-btn"
                                                href="<?php echo APP_URL . '/admin/dashboard.php?view=media&media=single' ?>&id=<?php /** Need to find a way to load this on demand */ ?>"
                                                class="btn btn-success">View Media</a>
                                            <?php } ?>
                                            <?php /*confirm user has a role with update media permissions*/
                                                    //get the update media permission id
                                                    $updatePermissionID = $permissionsObject->getPermissionIdByName('UPDATE MEDIA');

                                                    //boolean to check if the user has the update media permission
                                                    $hasUpdatePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermissionID);

                                                    //only show the edit button if the user has the update media permission
                                                    if ($hasUpdatePermission) { ?>
                                            <a id="update-media-btn"
                                                href="<?php echo APP_URL . '/admin/dashboard.php?view=media&media=edit&action=edit&id=';
                                                                                        /** Need to find a way to load this on demand */ ?>"
                                                class="btn btn-primary">Edit Media</a>
                                            <?php } ?>
                                            <?php /*confirm user has a role with delete media permissions*/
                                                    //get the delete media permission id
                                                    $deletePermissionID = $permissionsObject->getPermissionIdByName('DELETE MEDIA');

                                                    //boolean to check if the user has the delete media permission
                                                    $hasDeletePermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $deletePermissionID);

                                                    //only show the delete button if the user has the delete media permission
                                                    if ($hasDeletePermission) { ?>
                                            <button type="button" id="openDeleteModal" class="btn btn-danger"
                                                data-bs-toggle="modal" data-bs-target="#deleteMediaModal"
                                                onclick="setDeleteID(/** Need to find a way to load this on demand */)">
                                                Delete Media
                                            </button>
                                            <?php } ?>
                                        </form>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <script>
        //set the media array to a javascript variable
        var mediaArray = <?php echo json_encode($mediaArray); ?>;
        </script>
        <?php if ($hasDeletePermission) { ?>
        <script>
        //function to set the delete id on the action url of the delete modal based on which media is selected
        function setDeleteID(id) {
            //get the media name
            var mediaName = mediaArray.find(media => media.id == id).filename;

            //set the modal to a variable
            var deleteModal = document.getElementById("deleteMediaModal");

            //get the title element
            var title = deleteModal.querySelector("#mediaName-Title");

            //get the form
            var form = deleteModal.querySelector("#deleteMediaForm");

            //set the media name in the modal title
            title.innerHTML = mediaName;

            //set the action url of the delete modal
            form.action = deleteBaseURL + id;
        }

        function clearDeleteID() {
            //set the action url of the delete modal
            document.getElementById("deleteMediaForm").action = "";
        }
        </script>
        <?php } ?>
        <?php if ($hasReadPermission) { ?>
        <script>
        //function to format the filesize, since we can't use the php function in javascript
        function formatFilesize(filesize) {
            if (filesize == null) {
                return "N/A";
            } else if (filesize < 1024) {
                return filesize + " B";
            } else if (filesize < 1048576) {
                return (filesize / 1024).toFixed(2) + " KB";
            } else if (filesize < 1073741824) {
                return (filesize / 1048576).toFixed(2) + " MB";
            } else {
                return (filesize / 1073741824).toFixed(2) + " GB";
            }
        }

        //function to set the read id on the action url of the read modal based on which media is selected
        function setReadID(id) {
            //get the media name
            var mediaName = mediaArray.find(media => media.id == id).filename;

            //set the modal to a variable
            var readModal = document.getElementById("readMediaModal");

            //get the image element
            var img = readModal.querySelector("#thumbnail");

            //get the title element
            var title = readModal.querySelector("#mediaName-Title");

            //get the view media button
            var viewMediaBtn = readModal.querySelector("#view-media-btn");

            //get the update media button
            var updateMediaBtn = readModal.querySelector("#update-media-btn");

            //get the delete media button
            var deleteMediaBtn = readModal.querySelector("#openDeleteModal");

            //set the media name in the modal title
            title.innerHTML = mediaName;

            //set the media information in the modal
            readModal.querySelector("#filename").innerHTML = mediaName;
            readModal.querySelector("#filetype").innerHTML = "." + mediaName.split('.').pop().toUpperCase();
            readModal.querySelector("#filesize").innerHTML = formatFilesize(mediaArray.find(media => media.id == id)
                .filesize);
            readModal.querySelector("#created_at").innerHTML = mediaArray.find(media => media.id == id).created_at;
            readModal.querySelector("#created_by").innerHTML = mediaArray.find(media => media.id == id).created_by;
            readModal.querySelector("#updated_at").innerHTML = mediaArray.find(media => media.id == id).updated_at;
            readModal.querySelector("#updated_by").innerHTML = mediaArray.find(media => media.id == id).updated_by;

            //check if the thumbnail file exists in the uploads folder
            var thumbnailExists = fileExists("<?php echo getUploadPath() ?>" + "thumb_600_" + mediaName);

            //set the image source
            if (thumbnailExists) {
                img.src = "<?php echo getUploadPath() ?>" + "thumb_600_" + mediaName;
            } else {
                img.src = "<?php echo getUploadPath() ?>" + mediaName;
            }

            //set the image alt
            img.alt = mediaName;

            //set the url of the view media button
            viewMediaBtn.href = "<?php echo APP_URL . '/admin/dashboard.php?view=media&media=single' ?>&id=" + id;

            //set the url of the update media button
            updateMediaBtn.href =
                "<?php echo APP_URL . '/admin/dashboard.php?view=media&media=edit&action=edit&id=' ?>" + id;

            //set the onclick function of the delete media button
            deleteMediaBtn.setAttribute("onclick", "setDeleteID(" + id + ")");
        }

        function clearReadID() {
            //set the action url of the read modal
            document.getElementById("readMediaForm").action = "";
        }

        function fileExists(url) {
            var http = new XMLHttpRequest();
            http.open('HEAD', url, false);
            http.send();
            return http.status != 404;
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
                type: "date",
                format: "YYYY-MM-DD HH:mm:ss",
                sortSequence: ["desc", "asc"]
            },
            {
                select: 5,
                sortSequence: ["desc", "asc"]
            },
            {
                select: 6,
                type: "date",
                format: "YYYY-MM-DD HH:mm:ss",
                sortSequence: ["desc", "asc"]
            },
            {
                select: 7,
                sortSequence: ["desc", "asc"]
            },
            {
                select: 8,
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
    dt.columns.order([0, 1, 2, 3, 4, 5, 6, 7, 8])
    window.dt = dt
    </script>
    <script>
    //function to toggle the media view between list and gallery
    function toggleMediaView(view) {
        if (view == "list") {
            document.getElementById("list-view").classList.remove("hidden");
            document.getElementById("gallery-view").classList.add("hidden");
            document.getElementById("listView").classList.add("active");
            document.getElementById("galleryView").classList.remove("active");
        } else if (view == "gallery") {
            document.getElementById("list-view").classList.add("hidden");
            document.getElementById("gallery-view").classList.remove("hidden");
            document.getElementById("listView").classList.remove("active");
            document.getElementById("galleryView").classList.add("active");
        }
    }
    </script>
    <?php } ?>
