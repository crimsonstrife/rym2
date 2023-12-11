<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
}

//degree class
$degree = new Degree();

//user class
$user = new User();

//get the action from the url parameter
$action = $_GET['action'];

//if the action is edit, get the degree id from the url parameter
if ($action == 'edit') {
    $degree_id = $_GET['id'];
}

//if the action is edit, show the degree edit form
if ($action == 'edit') { ?>
    <div class="container-fluid px-4">
        <h1 class="mt-4"><?php echo $degree->getGradeNameById($degree_id); ?></h1>
        <div class="row">
            <div class="card mb-4">
                <!-- Edit Form -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&degree=' . $_GET['degree'] . '&action=' . $_GET['action'] . '&id=' . $_GET['id']; ?>" method="post" enctype="multipart/form-data">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa-solid fa-calendar-day"></i>
                            Edit Degree
                        </div>
                        <div class="card-buttons">
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=degrees&degree=list'; ?>" class="btn btn-primary btn-sm">Back to Degrees</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><label for="degreeName">Degree/Grade Name:</label></strong></p>
                                <p><input type="text" id="degreeName" name="degree_name" class="form-control" value="<?php echo $degree->getGradeNameById($degree_id); ?>" placeholder="<?php echo $degree->getGradeNameById($degree_id); ?>" required></p>
                            </div>
                        </div>
                    </div>
                    <div class=" card-footer">
                        <button name="create_Button" type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=degrees&degree=list'; ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } else if ($action == 'create') { //else if the action is create, show the degree creation form
?>
    <div class="container-fluid px-4">
        <h1 class="mt-4">New Degree</h1>
        <div class="row">
            <div class="card mb-4">
                <!-- Create Form -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&degree=' . $_GET['degree'] . '&action=' . $_GET['action']; ?>" method="post" enctype="multipart/form-data">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa-solid fa-calendar-day"></i>
                            Create Degree
                        </div>
                        <div class="card-buttons">
                            <a href="<?php echo APP_URL . '/admin/dashboard.php?view=degrees&degree=list'; ?>" class="btn btn-primary btn-sm">Back to Degrees</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><label for="degreeName">Degree/Grade Name:</label></strong></p>
                                <p><input type="text" id="degreeName" name="degree_name" class="form-control" placeholder="example: AA - Associates of Arts" required></p>
                            </div>
                        </div>
                    </div>
                    <div class=" card-footer">
                        <button name="create_Button" type="submit" class="btn btn-primary">Save</button>
                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=degrees&degree=list'; ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>
