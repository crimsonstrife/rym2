<?php
//instance the event class
$eventObject = new Event();
//check the event slug
if (isset($event_slug)) {
    $eventForPage = $eventObject->getEventBySlug($event_slug);
    if ($eventForPage) {
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
