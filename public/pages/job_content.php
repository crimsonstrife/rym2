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
 * @requires PHP 7.2.5+
 * @requires Bootstrap 5.3.2+
 * @requires Font Awesome 6.4.2+
 * @requires jQuery 3.7.1+
 */

//include the header
include_once('header.php');

//include the application class
$APP = new Application();

//include the job class
$job = new Job();

//get the id from the url
isset($_GET['id']) ? $id = $_GET['id'] : $id = '';

//get the job content
$job_content = $job->getJob(intval($id)); ?>
<div id="layout">
    <!-- main content -->
    <main>
        <div id="layout_content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Page Content -->
                        <div class="container">
                            <div class="row">
                                <h1 class="mt-5"><?php echo $APP->getAppName(); ?></h1>
                                <p class="lead">Job/Internship Listing</p>
                                <ul class="list-unstyled">
                                    <li>Last Updated: </li>
                                </ul>
                            </div>
                            <div class="row">
                                <div class="card">
                                    <div class="card-body">
                                        <?php print_r($job_content); ?>
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
