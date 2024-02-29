<?php

/**
 * This is the content for the QRCode page of the website
 * these pages are only used to display a fullscreen QRCode that can be scanned to take the visitor to event-specific content.
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

// instance the event class
$event = new Event();

// instance of the school class
$school = new School();

//check the event slug
if (isset($event_slug)) {
    //get the event by the slug
    $this_event = $event->getEventBySlug($event_slug);
} else {
    $this_event = null;
}

//if the event is set, get the event styling data
if (isset($this_event)) {
    $event_brandingColor = $school->getSchoolColor(intval($this_event['location']));
} else {
    $event_brandingColor = null;
}

//if the event color is set and not null, echo the style tag
if (isset($event_brandingColor) && !empty($event_brandingColor) && !is_null($event_brandingColor)) {
    echo '<style>';
    echo '.schoolBrandedNav {'; //style the nav bar
    echo 'background-color: ' . $event_brandingColor . ' !important;';
    echo '}';
    echo '</style>';
}

//get the event variables if the event is set
if (isset($this_event)) {
    $event_id = $this_event['id'];
} else {
    //if no event is set, set the variables to null
    $event_id = null;
} ?>

<div id="layout">
    <!-- main content -->
    <main>
        <div id="layout_content" class="nav-less">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Page Content -->
                        <div class="container">

                            <?php //confirm the id exists
                            if (empty($event_id) || $event_id == null) { ?>
                            <div class="container-fluid px-4">
                            </div>
                            <?php } else { ?>
                            <div class="container-fluid px-4">
                                <h1 class="mt-4"><?php echo $event->getEventName($event_id); ?></h1>
                                <div class="row">
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <i class="fa-solid fa-qrcode"></i>
                                            QR Code for <?php echo $event->getEventName($event_id); ?>
                                        </div>
                                        <div class="card-body">
                                            <div>
                                                <p><strong>Event QRCode:</strong> (Links to the event page)</p>
                                            </div>
                                            <div class="qr-code-container">
                                                <?php if (isset($event_id)) {
                                                        $qrcode_max_width = '1200px';
                                                        include_once(__DIR__ . '/../../admin/view/qrcode_display.php');
                                                    } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
    </main>
</div>
<?php include_once('footer.php'); ?>
