<?php

/**
 * This is the content for the job page of the website
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   12/28/2023
 *
 * @package RYM2
 * Filename: job_content.php
 * @version 1.0.0
 * @requires PHP 8.1.2+
 * @requires Bootstrap 5.3.2+
 * @requires Font Awesome 6.4.2+
 * @requires jQuery 3.7.1+
 */

//include the header
include_once('header.php');

//include the application class
$APP = new Application();

//include the settings class
$settings_class = new Settings();

//include the job class
$job = new Job();

//include the degree class
$degree = new Degree();

//include the job field class
$jobField = new JobField();

//get the id from the url
isset($_GET['id']) ? $id = $_GET['id'] : $id = '';

//get the job content
$job_content = $job->getJob(intval($id)); ?>
<div id="layout">
    <!-- main content -->
    <main>
        <div id="layout_content" class="nav-less">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Page Content -->
                        <div class="container">
                            <div class="row">
                                <h1 class="mt-5"><?php echo $settings_class->getAppName(); ?></h1>
                                <p class="lead">Job/Internship Listing</p>
                                <ul class="list-unstyled">
                                    <li>Last Updated: <?php echo formatDate($job_content['updated_at']); ?></li>
                                </ul>
                            </div>
                            <div class="row">
                                <div class="card">
                                    <div class="card-header">
                                        <h2><?php echo $job_content['name']; ?></h2>
                                    </div>
                                    <div class="card-body">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h3>Job Summary:</h3>
                                                    <p><?php echo $job_content['summary']; ?></p>
                                                    <div class="col-md-6">
                                                        <h4>Job Field: </h4>
                                                        <p><?php echo $jobField->getSubjectName(intval($job_content['field'])); ?>
                                                        <h4>Job Type: </h4>
                                                        <p><?php echo $job->getJobType(intval($id)); ?>
                                                    </div>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h3>Job Description:</h3>
                                                    <p><?php echo $job_content['description']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h3>Job Requirements:</h3>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4>Education Requirement:</h4>
                                                    <p><?php echo $degree->getGradeNameById(intval($job_content['education'])); ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4>Skill Requirements:</h4>
                                                </div>
                                                <div class="row">
                                                    <?php
                                                    //get the job skills
                                                    $job_skills = $job->getJobSkills(intval($id));
                                                    ?>
                                                    <div class="col-md-12">
                                                        <p><strong>Job Skills:</strong></p>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <ul id="jobSkillsList" name="job_skills_list"
                                                            class="list-group job-skill-list">
                                                            <?php
                                                            //if the job skills are not empty, loop through the array and display the skills
                                                            if (!empty($job_skills)) {
                                                                foreach ($job_skills as $skill) { ?>
                                                            <li class="list-group-item job-skill-item"
                                                                style="border-top-width: 1px;">
                                                                <?php echo $skill; ?></li>
                                                            <?php }
                                                            } else { ?>
                                                            <li style="list-style: none;">No skills listed</li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>
</div>

<?php include_once('footer.php'); ?>
