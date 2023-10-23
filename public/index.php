<?php
//check the url for an event slug
if (isset($_GET['event'])) {
    $event_slug = $_GET['event'];
    $event = $db->getEventBySlug($event_slug);
    if ($event) {
        //if the event is found in the database, show the event page
        include_once(__DIR__ . '/pages/event_content.php');
    } else {
        //if the event is not found in the database, show the 404 page
        include_once(__DIR__ . '/pages/404.php');
    }
    return;
} else {
    //if no event slug is set, show the generic landing page
    include_once(__DIR__ . '/pages/landing_content.php');
}
