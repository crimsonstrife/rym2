<?php
/* include autoloader */
require_once(__DIR__ . '../../vendor/autoload.php');

/* include config */
require_once(__DIR__ . '../../config/app.php');

/* Setup HTML for page header */
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard</title>
        <?php echo includeHeader(); ?>
    </head>

    <body class="nav-fixed">
        <nav class="top-nav navbar navbar-expand navbar-dark bg-dark">
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
                <i class="icon toggleIcon"></i>
            </button>
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="<?php echo APP_URL ?>"><?php echo htmlspecialchars(APP_NAME) ?></a>
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
                                href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=single&id=' . $_SESSION["user_id"]; ?>">Profile</a>
                        </li>
                        <li><a class="dropdown-item"
                                href="<?php echo APP_URL . '/admin/dashboard.php?view=settings' ?>">Settings</a></li>
                        <li><a class="dropdown-item"
                                href="<?php echo APP_URL . '/admin/dashboard.php?view=users&user=single&id=' . $_SESSION["user_id"] . '#activity_log'; ?>">Activity
                                Log</a></li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <!-- Reset password link -->
                            <a class="dropdown-item" href="<?php echo APP_URL; ?>/reset-password.php">Reset Password
                            </a>
                        </li>
                        <li>
                            <!-- Logout link -->
                            <a class="dropdown-item" href="<?php echo APP_URL; ?>/logout.php">Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
