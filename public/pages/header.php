<?php

/**
 * This is the default header for pages of the College Recruitment Application
 * Students will be able to enter their information and have it sent to the database.
 * Uses the student class to create a new student object
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/18/2023
 *
 * @package RYM2
 * Filename: header.php
 * @version 1.0.0
 * @requires PHP 7.2.5+
 * @requires Bootstrap 5.3.2+
 * @requires Font Awesome 6.4.2+
 * @requires jQuery 3.7.1+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* include the base application config file */
require_once(__DIR__ . '/../../config/app.php');

/* include autoloader */
require_once(__DIR__ . '/../../vendor/autoload.php');

/* include the application class */
$app_class = new Application();

/* include the settings class */
$settings_class = new Settings();

/* include the company settings class */
$companySettings_class = new CompanySettings();


/* include the media class */
$media_class = new Media();


//placeholder variables
$author = 'Patrick Barnhardt';

// get the company name from the application settings
$companyName = $companySettings_class->getCompanyName();

//if the company name is not set, use the default of the developer name
if (!empty($companyName) && $companyName != null && $companyName != '') {
    $author = $companyName;
}

/* Setup HTML for page header */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="<?php echo $author; ?>" />
    <meta name="robots" content="noindex, nofollow">
    <title>
        <?php
        /* Get the APP_NAME from the constants, if not set use default */
        echo (defined('APP_NAME') ? APP_NAME : 'TalentFlow');
        ?> | Student Registration
    </title>
    <!-- Favicons/Icons and Manifest -->
    <link rel="icon" href="/favicon.ico" sizes="32x32">
    <link rel="icon" href="/icon.svg" type="image/svg+xml">
    <link rel="icon" href="/favicon-16x16.png" sizes="16x16">
    <link rel="icon" href="/favicon-32x32.png" sizes="32x32">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="android-chrome" href="/android-chrome-192x192.png" sizes="192x192">
    <link rel="android-chrome" href="/android-chrome-512x512.png" sizes="512x512">
    <link rel="manifest" href="/site.webmanifest">
    <?php echo includeHeader(); ?>
</head>

<body class="nav-fixed">
    <nav class="top-nav navbar navbar-expand-lg navbar-dark bg-dark schoolBrandedNav">
        <!-- Navbar Brand-->
        <div class="navbar-brand brand-container">
            <div class="brand-logo">
                <?php
                // Get the app logo from the application settings, if not set use default
                $appLogo = $settings_class->getAppLogo();
                if (!empty($appLogo) && $appLogo != null && $appLogo != '') {
                    echo '<img src="' . htmlspecialchars(getUploadPath()) . htmlspecialchars($media_class->getMediaFileName(intval($appLogo))) . '" alt="' . APP_NAME . '" class="brand-logo" />';
                }
                ?>
            </div>
            <span class="brand-text"><a class="navbar-brand ps-3" href="<?php echo APP_URL ?>"><?php echo htmlspecialchars(APP_NAME) ?></a></span>
        </div>
    </nav>
    <?php
    /* End of file header.php */
    /* Location: public/pages/header.php */
    /* See public/pages/landing_content.php for implementation of this file */
    ?>
