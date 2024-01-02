<?php
//get the path parameter from the url, if it's not set, set it to empty
isset($_GET['path']) ? $page = $_GET['path'] : $page = '';

//if the page is empty, set it to the landing page
if ($page == '') {
    include_once(__DIR__ . '/pages/landing_content.php');
} else {
    //if it's in the admin path, do nothing
    if (strpos($page, 'admin') !== false) {
        //do nothing, send to the landing page
        include_once(__DIR__ . '/pages/landing_content.php');
    } else {
        //if the page is not empty, check if it is a valid page
        if (file_exists(__DIR__ . '/pages/' . $page . '_content.php')) {
            //if the page is valid, include it
            include_once(__DIR__ . '/pages/' . $page . '_content.php');
        } else {
            //if the page is not valid, include the 404 page
            include_once(__DIR__ . '/pages/error_content.php?error=404');
        }
    }
}
