<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
}

//include the permissions class
$permissionsObject = new Permission();

//include the auth class
$auth = new Authenticator();

//job class
$job = new Job();

//user class
$user = new User();

echo '<script src="' . getLibraryPath() . 'ckeditor/ckeditor.js' . '"></script>';

//check that action is set in the URL parameters
if (isset($_GET['action'])) {
    //get the action from the URL parameters
    $action = $_GET['action'];

    //setup an array of the position types, each item will have a value and a label
    $positionType_list = JOBTYPES;

    //if the action is edit, show the job edit form
    if ($action == 'edit') {
        //get the update job permission id
        $updateJobPermissionID = $permissionsObject->getPermissionIdByName('UPDATE JOB');

        //boolean to check if the user has the update job permission
        $hasUpdateJobPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updateJobPermissionID);

        //if the user does not have the update job permission, prevent access to the editor
        if (!$hasUpdateJobPermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else {

            if (isset($_GET['id'])) {
                //get the job id from the url parameter
                $job_id = $_GET['id'];
            } else {
                //set the job id to null
                $job_id = null;
            }

            //confirm the id exists
            if (empty($job_id) || $job_id == null) {
                //set the error type
                $thisError = 'INVALID_REQUEST_ERROR';

                //include the error message file
                include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
            } else {
                //try to get the job information
                $object = $job->getJob(intval($job_id));

                //check if the job is empty
                if (empty($object)) {
                    //set the error type
                    $thisError = 'NOT_FOUND';

                    //include the error message file
                    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
                }
            }

            //if not empty, display the job information
            if (!empty($object)) {
?>
                <div class="container-fluid px-4">
                    <h1 class="mt-4"><?php echo $job->getJobTitle(intval($job_id)); ?></h1>
                    <div class="row">
                        <div class="card mb-4">
                            <!-- Edit Form -->
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&job=' . $_GET['job'] . '&action=' . $_GET['action'] . '&id=' . $_GET['id']; ?>" method="post" enctype="multipart/form-data">
                                <div class="card-header">
                                    <div class="card-title">
                                        <i class="fa-solid fa-briefcase"></i>
                                        Edit Job
                                    </div>
                                    <div class="card-buttons">
                                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=list'; ?>" class="btn btn-secondary">Back to Jobs</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p>
                                                <strong>
                                                    <label for="jobTitle">Job Title:</label>
                                                </strong>
                                            </p>
                                            <p>
                                                <input type="text" id="jobTitle" name="job_title" class="form-control" value="<?php echo $job->getJobTitle(intval($job_id)); ?>" placeholder="<?php echo $job->getJobTitle(intval($job_id)); ?>" required>
                                            </p>
                                            <p>
                                                <strong>
                                                    <label for="jobSummary">Job Summary:</label>
                                                </strong>
                                            </p>
                                            <p>
                                                <textarea id="jobSummary" name="job_summary" class="form-control" rows="3" maxlength="200" placeholder="<?php echo $job->getJobSummary(intval($job_id)); ?>" required><?php echo $job->getJobSummary(intval($job_id)); ?></textarea>
                                            </p>
                                            <p>
                                                <!-- counter for job summary -->
                                            <div id="counter hint-text"><span id="jobSummaryCounter"><small id="summaryCounterText" class="form-text text-muted">200/200</small><small class="form-text text-muted">
                                                        characters remaining</small></span></div>
                                            </p>
                                            <p>
                                                <strong>
                                                    <label for="jobDescription">Job Description:</label>
                                                </strong>
                                            </p>
                                            <p>
                                                <textarea id="jobDescription" name="job_description" class="form-control wysiwyg-editor" rows="10" placeholder="<?php echo $job->getJobDescription(intval($job_id)); ?>" required><?php echo $job->getJobDescription(intval($job_id)); ?></textarea>
                                            </p>
                                            <p>
                                                <strong>
                                                    <label for="jobType">Job Type:</label>
                                                </strong>
                                            </p>
                                            <p>
                                                <select id="jobType" name="job_type" class="form-control app-forms" required>
                                                    <?php
                                                    //for each item in the position type list, display it as an option
                                                    foreach ($positionType_list as $positionType) {
                                                        //check if the item in the list matches the job type
                                                        if ($positionType['value'] == $job->getJobTypeEnum(intval($job_id))) {
                                                            //if it matches, set the option to selected
                                                            echo '<option value="' . $positionType['value'] . '" selected>' . $positionType['label'] . '</option>';
                                                        } else {
                                                            //if it doesn't match, set the option to not selected
                                                            echo '<option value="' . $positionType['value'] . '">' . $positionType['label'] . '</option>';
                                                        }
                                                    } ?>
                                                </select>
                                            </p>
                                            <p>
                                                <strong>
                                                    <label for="jobField">Job Field:</label>
                                                </strong>
                                            </p>
                                            <p>
                                                <select id="jobField" name="job_field" class="form-control app-forms" required>
                                                    <?php
                                                    //include the job field class
                                                    $jobField = new JobField();
                                                    //get all job fields
                                                    $jobFieldArray = $jobField->getAllSubjects();
                                                    //for each job field, display it as an option
                                                    foreach ($jobFieldArray as $jobField) {
                                                        //check if the item in the list matches the job field
                                                        if (intval($jobField['id']) == $job->getJobField(intval($job_id))) {
                                                            //if it matches, set the option to selected
                                                            echo '<option value="' . $jobField['id'] . '" selected>' . $jobField['name'] . '</option>';
                                                        } else {
                                                            //if it doesn't match, set the option to not selected
                                                            echo '<option value="' . $jobField['id'] . '">' . $jobField['name'] . '</option>';
                                                        }
                                                    } ?>
                                                </select>
                                            </p>
                                            <p>
                                                <strong>
                                                    <label for="jobField">Job Skills:</label>
                                                </strong>
                                            </p>
                                            <?php
                                            //get the job skills
                                            $jobSkills = $job->getJobSkills(intval($job_id));
                                            ?>
                                            <p>
                                            <div class="input-group mb-3">
                                                <input type="text" id="jobSkills" name="job_skills" class="form-control" placeholder="Job Skills">
                                                <!-- button to add a new skill to list -->
                                                <input type="button" id="addSkill" name="add_skill" class="form-control btn btn btn-outline-success btn-skill-btn btn-add" value="Add Skill">
                                            </div>
                                            <div class="input-group mb-3">
                                                <!-- list of skills -->
                                                <ul id="jobSkillsList" name="job_skills_list" class="form-control list-group job-skill-list">
                                                    <?php
                                                    //for each skill in the job skills array, display it as a list item
                                                    foreach ($jobSkills as $skill) {
                                                        //create a unique id for the list item
                                                        $skillID = uniqid();
                                                        echo '<li id="' . $skillID . '" class="list-group-item job-skill-item">' . $skill . '</li>';
                                                    } ?>
                                                </ul>
                                                <!-- button to remove a skill from the list -->
                                                <input type="button" id="removeSkill" name="remove_skill" class="form-control btn btn btn-outline-danger btn-skill-btn btn-remove" value="Remove Skill">
                                                <!-- hidden field to store the list of skills as an array -->
                                                <input type="hidden" id="jobSkillsArray" name="job_skills_array[]" hidden>
                                            </div>
                                            </p>
                                            <p>
                                                <strong>
                                                    <label for="jobField">Education Requirement:</label>
                                                </strong>
                                            </p>
                                            <p>
                                                <select id="jobEducation" name="job_education" class="form-control app-forms" required>
                                                    <option value="0" <?php if ($job->getJobEducation(intval($job_id)) == 0) {
                                                                            echo 'selected';
                                                                        } ?>>None</option>
                                                    <?php
                                                    //include the degree level class
                                                    $degree = new Degree();
                                                    //get all education levels
                                                    $educationArray = $degree->getAllGrades();
                                                    //for each education level, display it as an option
                                                    foreach ($educationArray as $degreeLevel) {
                                                        //check if the item in the list matches the job education
                                                        if (intval($degreeLevel['id']) == $job->getJobEducation(intval($job_id))) {
                                                            //if it matches, set the option to selected
                                                            echo '<option value="' . $degreeLevel['id'] . '" selected>' . $degreeLevel['name'] . '</option>';
                                                        } else {
                                                            //if it doesn't match, set the option to not selected
                                                            echo '<option value="' . $degreeLevel['id'] . '">' . $degreeLevel['name'] . '</option>';
                                                        }
                                                    } ?>
                                                </select>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class=" card-footer">
                                    <button name="create_Button" type="submit" class="btn btn-primary">Save Changes</button>
                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=list'; ?>" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php }
        }
    } else if ($action == 'create') { //else if the action is create, show the job creation form
        //get the create job permission id
        $createJobPermissionID = $permissionsObject->getPermissionIdByName('CREATE JOB');

        //boolean to check if the user has the create job permission
        $hasCreateJobPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $createJobPermissionID);

        //if the user does not have the create job permission, prevent access to the editor
        if (!$hasCreateJobPermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else { //else if the action is create, show the job creation form

            //make sure there is at least one field in the job field table
            $jobField = new JobField();

            //get all job fields
            $jobFieldArray = $jobField->getAllSubjects();

            //if the job field array is empty, display an error message
            if (empty($jobFieldArray)) {
                //set the error type
                $thisError = 'DATA_MISSING';

                //include the error message file
                include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
            } else {
                //display the job creation form
            ?>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">New Job</h1>
                    <div class="row">
                        <div class="card mb-4">
                            <!-- Create Form -->
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '&job=' . $_GET['job'] . '&action=' . $_GET['action']; ?>" method="post" enctype="multipart/form-data">
                                <div class="card-header">
                                    <div class="card-title">
                                        <i class="fa-solid fa-briefcase"></i>
                                        Create Job
                                    </div>
                                    <div class="card-buttons">
                                        <a href="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=list'; ?>" class="btn btn-secondary">Back to Jobs</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p>
                                                <strong>
                                                    <label for="jobTitle">Job Title:</label>
                                                </strong>
                                            </p>
                                            <p>
                                                <input type="text" id="jobTitle" name="job_title" class="form-control" placeholder="Job Title" required>
                                            </p>
                                            <p>
                                                <strong>
                                                    <label for="jobSummary">Job Summary:</label>
                                                </strong>
                                            </p>
                                            <p>
                                                <textarea id="jobSummary" name="job_summary" class="form-control" rows="3" maxlength="200" placeholder="Job Summary" required></textarea>
                                            </p>
                                            <p>
                                                <!-- counter for job summary -->
                                            <div id="counter hint-text"><span id="jobSummaryCounter"><small id="summaryCounterText" class="form-text text-muted">200/200</small><small class="form-text text-muted">
                                                        characters remaining</small></span></div>
                                            </p>
                                            <p>
                                                <strong>
                                                    <label for="jobDescription">Job Description:</label>
                                                </strong>
                                            </p>
                                            <p>
                                                <textarea id="jobDescription" name="job_description" class="form-control wysiwyg-editor" rows="10" placeholder="Job Description" required></textarea>
                                            </p>
                                            <p>
                                                <strong>
                                                    <label for="jobType">Job Type:</label>
                                                </strong>
                                            </p>
                                            <p>
                                                <select id="jobType" name="job_type" class="form-control app-forms" required>
                                                    <?php
                                                    //for each item in the position type list, display it as an option
                                                    foreach ($positionType_list as $positionType) {
                                                        echo '<option value="' . $positionType['value'] . '">' . $positionType['label'] . '</option>';
                                                    } ?>
                                                </select>
                                            </p>
                                            <p>
                                                <strong>
                                                    <label for="jobField">Job Field:</label>
                                                </strong>
                                            </p>
                                            <p>
                                                <select id="jobField" name="job_field" class="form-control app-forms" required>
                                                    <?php
                                                    //for each job field, display it as an option
                                                    foreach ($jobFieldArray as $jobField) {
                                                        echo '<option value="' . $jobField['id'] . '">' . $jobField['name'] . '</option>';
                                                    } ?>
                                                </select>
                                            </p>
                                            <p>
                                                <strong>
                                                    <label for="jobField">Job Skills:</label>
                                                </strong>
                                            </p>
                                            <p>
                                            <div class="input-group mb-3">
                                                <input type="text" id="jobSkills" name="job_skills" class="form-control" placeholder="Job Skills">
                                                <!-- button to add a new skill to list -->
                                                <input type="button" id="addSkill" name="add_skill" class="form-control btn btn btn-outline-success btn-skill-btn btn-add" value="Add Skill">
                                            </div>
                                            <div class="input-group mb-3">
                                                <!-- list of skills -->
                                                <ul id="jobSkillsList" name="job_skills_list" class="form-control list-group job-skill-list">
                                                </ul>
                                                <!-- button to remove a skill from the list -->
                                                <input type="button" id="removeSkill" name="remove_skill" class="form-control btn btn btn-outline-danger btn-skill-btn btn-remove" value="Remove Skill">
                                                <!-- hidden field to store the list of skills as an array -->
                                                <input type="hidden" id="jobSkillsArray" name="job_skills_array[]" hidden>
                                            </div>
                                            </p>
                                            <p>
                                                <strong>
                                                    <label for="jobField">Education Requirement:</label>
                                                </strong>
                                            </p>
                                            <p>
                                                <select id="jobEducation" name="job_education" class="form-control app-forms" required>
                                                    <option value="0">None</option>
                                                    <?php
                                                    //include the degree level class
                                                    $degree = new Degree();
                                                    //get all education levels
                                                    $educationArray = $degree->getAllGrades();
                                                    //for each education level, display it as an option
                                                    foreach ($educationArray as $degreeLevel) {
                                                        echo '<option value="' . $degreeLevel['id'] . '">' . $degreeLevel['name'] . '</option>';
                                                    } ?>
                                                </select>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class=" card-footer">
                                    <button name="create_Button" type="submit" class="btn btn-primary">Save</button>
                                    <a href="<?php echo APP_URL . '/admin/dashboard.php?view=jobs&job=list'; ?>" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
    <?php }
        }
    } ?>
    <script>
        //initialize the wysiwyg editors
        document.querySelectorAll('.wysiwyg-editor').forEach(e => {
            ClassicEditor
                .create(e, {
                    removePlugins: ['Image', 'EasyImage', 'ImageCaption', 'ImageStyle', 'ImageToolbar',
                        'ImageUpload',
                        'MediaEmbed', 'CKFinder', 'CKFinderUploadAdapter'
                    ]
                })
                .then(editor => {
                    console.log('Editor Initialized', editor);
                    editor.model.document.on('change:data', () => {
                        e.value = editor.getData();
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        });

        //function to count the characters in the summary text area, max at the character limit of the field, takes an element ID as an input variable
        function characterCount(fieldID, outputField) {
            //get the element to monitor
            var fieldToWatch = document.getElementById(fieldID);

            //get the charater limit from the field
            var limit = fieldToWatch.maxLength;

            //get the output location
            var counterText = document.getElementById(outputField);

            //calculate if the limit has been reached
            var remaining = limit - fieldToWatch.value.length;

            //if the limit has been reached, set the text to red
            if (remaining <= 0) {
                counterText.style.setProperty("color", "red", "important");
            } else {
                counterText.style.color = "black";
            }

            //display the remaining characters
            counterText.innerHTML = remaining + "/" + limit;
        }

        //add an input event listener to the summary text area
        document.getElementById("jobSummary").addEventListener("input", function() {
            //call the character count function
            characterCount("jobSummary", "summaryCounterText");
        });

        //add an event listener for when the document is loaded
        document.addEventListener("DOMContentLoaded", function() {
            //call the character count function
            characterCount("jobSummary", "summaryCounterText");
        });

        //function to generate a unique id
        function uniqid() {
            var uiid = Date.now().toString(36) + Math.random().toString(36).substring(2, 12).padStart(12, 0);

            return uiid;
        }

        var lastSelectedSkill = null;

        //add an event listener to the items of skills, for click events
        var list = document.querySelectorAll('#jobSkillsList li');
        for (var i = 0; i < list.length; i++) {
            list[i].addEventListener('click', function(e) {
                //if the last selected skill is not null, remove the selected class
                if (lastSelectedSkill != null) {
                    lastSelectedSkill.classList.remove('selected');
                }


                //add the selected class to the selected skill
                e.target.classList.add('selected');

                //if the last selected skill is the same as the selected skill, remove the selected class
                if (lastSelectedSkill == e.target) {
                    e.target.classList.remove('selected');
                    lastSelectedSkill = null;
                }

                //set the last selected skill to the selected skill
                lastSelectedSkill = e.target;
            });
        }

        //add a listener to the list of skills, for when a new child is added
        document.getElementById("jobSkillsList").addEventListener("DOMNodeInserted", function() {
            //add an event listener to the items of skills, for click events
            var list = document.querySelectorAll('#jobSkillsList li');
            for (var i = 0; i < list.length; i++) {
                list[i].addEventListener('click', function(e) {
                    //if the last selected skill is not null, remove the selected class
                    if (lastSelectedSkill != null) {
                        lastSelectedSkill.classList.remove('selected');
                    }


                    //add the selected class to the selected skill
                    e.target.classList.add('selected');

                    //if the last selected skill is the same as the selected skill, remove the selected class
                    if (lastSelectedSkill == e.target) {
                        e.target.classList.remove('selected');
                        lastSelectedSkill = null;
                    }

                    //set the last selected skill to the selected skill
                    lastSelectedSkill = e.target;
                });
            }
        });

        //function to add a skill to the list
        function addSkill() {
            //get the skill from the input field
            var skill = document.getElementById("jobSkills").value;

            //if the skill is empty, do nothing
            if (skill == null) {
                return;
            } else {
                //if the skill is not empty, add it to the list
                //check if there are multiple skills in the input field, delimited by a comma
                if (skill.includes(",")) {
                    //if there are multiple skills, split them into an array
                    var skillArray = skill.split(",");

                    //for each skill in the array, add it to the list
                    for (var i = 0; i < skillArray.length; i++) {
                        //get the list of skills
                        var skillList = document.getElementById("jobSkillsList");

                        //create a new list item element
                        var option = document.createElement("li");

                        //set the inner text of the list item to the skill
                        option.innerText = skillArray[i];

                        //set the class of the list item
                        option.className = "list-group-item job-skill-item";

                        //set a unique id to the list item
                        option.id = uniqid();

                        //add the list item to the list
                        skillList.appendChild(option);

                        //clear the input field
                        document.getElementById("jobSkills").value = "";

                        //update the list of skills as an array in the hidden field
                        updateSkillArray();
                    }
                } else {
                    //if there is only one skill, add it to the list
                    //get the list of skills
                    var skillList = document.getElementById("jobSkillsList");

                    //create a new list item element
                    var option = document.createElement("li");

                    //set the inner text of the list item to the skill
                    option.innerText = skill;

                    //set the class of the list item
                    option.className = "list-group-item job-skill-item";

                    //set a unique id to the list item
                    option.id = uniqid();

                    //add the list item to the list
                    skillList.appendChild(option);

                    //clear the input field
                    document.getElementById("jobSkills").value = "";

                    //update the list of skills as an array in the hidden field
                    updateSkillArray();
                }
            }
        }

        //function to remove a skill from the list
        function removeSkill() {
            //get the list of skills
            var skillList = document.getElementById("jobSkillsList");

            //if the last selected skill is not null, remove it from the list
            if (lastSelectedSkill != null) {
                skillList.removeChild(lastSelectedSkill);

                //set the last selected skill to null
                lastSelectedSkill = null;
            } else {
                //remove the last item from the list
                skillList.removeChild(skillList.lastChild);
            }

            //update the list of skills as an array in the hidden field
            updateSkillArray();
        }

        //function to update the list of skills as an array in the hidden field
        function updateSkillArray() {
            //get the list of skills
            var skillList = document.getElementById("jobSkillsList");

            //create an array to store the skills
            var skillArray = [];

            //for each skill in the list, add it to the array
            for (var i = 0; i < skillList.children.length; i++) {
                skillArray.push(skillList.children[i].innerText);
            }

            //get the hidden field
            var skillArrayField = document.getElementById("jobSkillsArray");

            //set the value of the hidden field to the array of skills
            skillArrayField.value = skillArray;
        }

        //event listener for when the document is loaded to look for skills in the list items
        document.addEventListener("DOMContentLoaded", function() {
            //get the list of skills
            var skillList = document.getElementById("jobSkillsList");

            //create an array to store the skills
            var skillArray = [];

            //for each skill in the list, add it to the array
            for (var i = 0; i < skillList.children.length; i++) {
                skillArray.push(skillList.children[i].innerText);
            }

            //get the hidden field
            var skillArrayField = document.getElementById("jobSkillsArray");

            //set the value of the hidden field to the array of skills
            skillArrayField.value = skillArray;
        });

        //add an input event listener to the skill input field
        document.getElementById("jobSkills").addEventListener("input", function() {
            //get the skill from the input field
            var skill = document.getElementById("jobSkills").value;

            //array to hold the list of pending skills if there are multiple skills in the input field
            var pendingSkills = [];

            //get the hidden field
            var skillArrayField = document.getElementById("jobSkillsArray");

            //get the array of skills from the hidden field
            var skillArray = skillArrayField.value.split(",");

            //if the skill is empty, do nothing
            if (skill == null) {
                return;
            } else {
                if (skill.includes(",")) {
                    //determine if there are multiple skills in the input field, delimited by a comma
                    pendingSkills = skill.split(",");

                    //loop through the array of pending skills, if any match the skills in the skill array, disable the add button
                    for (var i = 0; i < pendingSkills.length; i++) {
                        //loop through the array of skills
                        for (var j = 0; j < skillArray.length; j++) {
                            //if the pending skill matches a skill in the skill array, disable the add button
                            if (pendingSkills[i] == skillArray[j]) {
                                document.getElementById("addSkill").disabled = true;
                                return;
                            } else {
                                document.getElementById("addSkill").disabled = false;
                            }
                        }
                    }
                } else {
                    //if there is only one skill, check if it matches any of the skills in the skill array
                    for (var i = 0; i < skillArray.length; i++) {
                        //if the skill matches a skill in the skill array, disable the add button
                        if (skill == skillArray[i]) {
                            document.getElementById("addSkill").disabled = true;
                            return;
                        } else {
                            document.getElementById("addSkill").disabled = false;
                        }
                    }
                }
            }
        });

        //add an event listener to the add skill button
        document.getElementById("addSkill").addEventListener("click", function() {
            //call the add skill function
            addSkill();
        });

        //add an event listener to the remove skill button
        document.getElementById("removeSkill").addEventListener("click", function() {
            //call the remove skill function
            removeSkill();
        });
    </script>
<?php } else {
    //set the action to null
    $action = null;

    //set the error type
    $thisError = 'ROUTING_ERROR';

    //include the error message file
    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
} ?>
