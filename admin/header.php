<?php
/* include autoloader */
require_once(__DIR__ . '../../vendor/autoload.php');

/* include config */
require_once(__DIR__ . '../../config/app.php');

/* include the application class */
$app_class = new Application();

/* include the settings class */
$settings = new Settings();

/* include the company settings class */
$companySettings = new CompanySettings();

/* include the media class */
$media_class = new Media();

/* include the session class */
$session = new Session();

//placeholder variables
$author = 'Patrick Barnhardt';

// get the company name from the application settings
$companyName = $companySettings->getCompanyName();

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
        ?> | Dashboard
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
        <nav class="top-nav navbar navbar-expand-lg navbar-dark bg-dark">
            <!-- Sidebar Toggle-->
            <button class="navbar-toggler" type="button" id="sidebarToggle" data-bs-toggle="collapse"
                data-bs-target="#layout_nav" aria-controls="layout_nav" aria-expanded="true"
                aria-label="Toggle Navigation">
                <i class=" icon toggleIcon"></i>
            </button>
            <!-- Navbar Brand-->
            <div class="navbar-brand brand-container">
                <div class="brand-logo">
                    <?php
                // Get the app logo from the application settings, if not set use default
                $appLogo = $settings->getAppLogo();
                if (!empty($appLogo) && $appLogo != null && $appLogo != '') {
                    echo '<img src="' . getUploadPath() . htmlspecialchars($media_class->getMediaFileName(intval($appLogo))) . '" alt="' . htmlspecialchars(APP_NAME) . '" class="brand-logo" />';
                }
                ?>
                </div>
                <span class="brand-text"><a class="navbar-brand ps-3"
                        href="<?php echo APP_URL ?>"><?php echo htmlspecialchars(APP_NAME) ?></a></span>
            </div>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"
                action="<?php echo APP_URL . '/admin/dashboard.php?view=search'; ?>" method="post">
                <div class="input-group">
                    <input class="form-control" type="text" id="searchTerm" name="searchTerm"
                        placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button title="Search" class="btn btn-primary" id="btnNavbarSearch" type="submit" value="submit"
                        name="search"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-circle-user"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item"
                                href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=single&id=' . $session->get('user_id'); ?>">Profile</a>
                        </li>
                        <li><a class="dropdown-item"
                                href="<?php echo APP_URL . '/admin/dashboard.php?view=settings' ?>">Settings</a></li>
                        <li><a class="dropdown-item"
                                href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=single&id=' . $session->get('user_id') . '#activity_log'; ?>">Activity
                                Log</a></li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <!-- Logout link -->
                            <a class="dropdown-item" href="<?php echo APP_URL; ?>/logout.php">Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
