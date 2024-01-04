<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    die('Error: Invalid request');
} // idea from https://stackoverflow.com/a/409515 (user UnkwnTech)

//check that the view dashboard permission is set
if (!isset($hasViewDashboardPermission)) {
    die('Error: You do not have permission to access this content or there is a configuration error, contact the Administrator.');
} else {
    //check that the user has the view dashboard permission
    if (!$hasViewDashboardPermission) {
        die('Error: You do not have permission to access this content, contact the Administrator.');
    } else {

        //TODO: check if the user is an admin or has permission to view this page

        //include the application class
        $APP = new Application();

        //check if the form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //check if the submit button was clicked
            if (isset($_POST['submit'])) {
                //check if the app name is set
                if (isset($_POST['app_name']) && $_POST['app_name'] != '') {
                    //prevent XSS attacks by removing html tags
                    $_POST['app_name'] = strip_tags($_POST['app_name']);
                    //prepare the app name
                    $_POST['app_name'] = htmlspecialchars($_POST['app_name']);
                    //trim whitespace from the app name
                    $_POST['app_name'] = trim($_POST['app_name']);
                    //set the app name
                    $APP->setAppName($_POST['app_name']);
                } else {
                    //set the app name to null
                    $APP->setAppName(null);
                }
                //check if the app url is set
                if (isset($_POST['app_url']) && $_POST['app_url'] != '') {
                    //prevent XSS attacks by removing html tags
                    $_POST['app_url'] = strip_tags($_POST['app_url']);
                    //trim whitespace from the app url
                    $_POST['app_url'] = trim($_POST['app_url']);
                    //set the app url
                    $APP->setAppURL($_POST['app_url']);
                }
                //check if the mailer is set
                if (isset($_POST['mail_mailer'])) {
                    //make sure the mailer is a valid option
                    if (in_array($_POST['mail_mailer'], MAILER)) {
                        //make sure the mailer is not null or none
                        if ($_POST['mail_mailer'] != '' && $_POST['mail_mailer'] != 'none' && $_POST['mail_mailer'] != null && $_POST['mail_mailer'] != 'NONE') {
                            //set the mailer
                            $APP->setMailerType($_POST['mail_mailer']);
                        }
                    }
                }
                //check if the mail host is set
                if (isset($_POST['mail_host']) && $_POST['mail_host'] != '') {
                    //prevent XSS attacks by removing html tags
                    $_POST['mail_host'] = strip_tags($_POST['mail_host']);
                    //trim whitespace from the mail host
                    $_POST['mail_host'] = trim($_POST['mail_host']);
                    //set the mail host
                    $APP->setMailerHost($_POST['mail_host']);
                }
                //check if the mail port is set
                if (isset($_POST['mail_port']) && $_POST['mail_port'] != '') {
                    //prevent XSS attacks by removing html tags
                    $_POST['mail_port'] = strip_tags($_POST['mail_port']);
                    //trim whitespace from the mail port
                    $_POST['mail_port'] = trim($_POST['mail_port']);
                    //set the mail port
                    $APP->setMailerPort($_POST['mail_port']);
                }
                //check if the mail auth req is set
                if (isset($_POST['mail_auth_req'])) {
                    //filter the mail auth req as a boolean
                    $_POST['mail_auth_req'] = filter_var($_POST['mail_auth_req'], FILTER_VALIDATE_BOOLEAN);
                    //set the mail auth req
                    $APP->setMailerAuthRequired($_POST['mail_auth_req']);
                }
                //check if the mail username is set
                if (isset($_POST['mail_username']) && $_POST['mail_username'] != '') {
                    //prevent XSS attacks by removing html tags
                    $_POST['mail_username'] = strip_tags($_POST['mail_username']);
                    //trim whitespace from the mail username
                    $_POST['mail_username'] = trim($_POST['mail_username']);
                    //prepare the mail username
                    $_POST['mail_username'] = htmlspecialchars($_POST['mail_username']);
                    //set the mail username
                    $APP->setMailerUsername($_POST['mail_username']);
                }
                //check if the mail password is set
                if (isset($_POST['mail_password']) && $_POST['mail_password'] != '') {
                    //prevent XSS attacks by removing html tags
                    $_POST['mail_password'] = strip_tags($_POST['mail_password']);
                    //trim whitespace from the mail password
                    $_POST['mail_password'] = trim($_POST['mail_password']);
                    //prepare the mail password
                    $_POST['mail_password'] = htmlspecialchars($_POST['mail_password']);
                    //set the mail password
                    $APP->setMailerPassword($_POST['mail_password']);
                }
                //check if the mail encryption is set
                if (isset($_POST['mail_encryption']) && $_POST['mail_encryption'] != '') {
                    //make sure the mail encryption is a valid option
                    if (in_array($_POST['mail_encryption'], MAILER_ENCRYPTION)) {
                        //make sure the mail encryption is not null or none
                        if ($_POST['mail_encryption'] != '' && $_POST['mail_encryption'] != 'none' && $_POST['mail_encryption'] != null && $_POST['mail_encryption'] != 'NONE') {
                            //set the mail encryption
                            $APP->setMailerEncryption($_POST['mail_encryption']);
                        }
                    }
                }
                //check if the mail from address is set
                if (isset($_POST['mail_from_address']) && $_POST['mail_from_address'] != '') {
                    //prevent XSS attacks by removing html tags
                    $_POST['mail_from_address'] = strip_tags($_POST['mail_from_address']);
                    //trim whitespace from the mail from address
                    $_POST['mail_from_address'] = trim($_POST['mail_from_address']);
                    //set the mail from address
                    $APP->setMailerFromAddress($_POST['mail_from_address']);
                }
                //check if the mail from name is set
                if (isset($_POST['mail_from_name']) && $_POST['mail_from_name'] != '') {
                    //prevent XSS attacks by removing html tags
                    $_POST['mail_from_name'] = strip_tags($_POST['mail_from_name']);
                    //trim whitespace from the mail from name
                    $_POST['mail_from_name'] = trim($_POST['mail_from_name']);
                    //prepare the mail from name
                    $_POST['mail_from_name'] = htmlspecialchars($_POST['mail_from_name']);
                    //set the mail from name
                    $APP->setMailerFromName($_POST['mail_from_name']);
                }

                //check if the privacy policy is set
                if (isset($_POST['privacy_policy']) && $_POST['privacy_policy'] != '') {
                    //prevent XSS attacks by removing html tags
                    $_POST['privacy_policy'] = strip_tags($_POST['privacy_policy']);
                    //trim whitespace from the privacy policy
                    $_POST['privacy_policy'] = trim($_POST['privacy_policy']);
                    //set the privacy policy
                    $APP->setPrivacyPolicy($_POST['privacy_policy']);
                }

                //check if the terms and conditions is set
                if (isset($_POST['terms_conditions']) && $_POST['terms_conditions'] != '') {
                    //prevent XSS attacks by removing html tags
                    $_POST['terms_conditions'] = strip_tags($_POST['terms_conditions']);
                    //trim whitespace from the terms and conditions
                    $_POST['terms_conditions'] = trim($_POST['terms_conditions']);
                    //set the terms and conditions
                    $APP->setTerms($_POST['terms_conditions']);
                }

                //refresh the page
                header('Location: ' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view']);
            } else if (isset($_POST['reset'])) {
                //reset the settings
                $APP->resetSettings();

                //refresh the page
                header('Location: ' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view']);
            }
        }
?>
<!-- main content -->
<div id="layout_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Settings</h1>
            <div class="row">
                <!-- Settings Notice -->
                <div class="col-md-12">
                    <div class="alert alert-warning fade show" role="alert">
                        <strong>Notice:</strong> Settings here may override settings in the config file, preference is
                        given on a case by case basis.
                    </div>
                    <div class="alert alert-warning fade show" role="alert">
                        <strong>Caution:</strong> You should store any passwords, such as the mailer password,
                        in the config .env file, the settings here are for users who do not know how to edit a .env
                        file.
                    </div>
                    <?php
                            //check if openssl is enabled
                            if (OPENSSL_INSTALLED) {
                                echo '<div class="alert alert-success fade show" role="alert">
                        <strong>Success:</strong> OpenSSL is enabled. Passwords should be encrypted. </div>';
                            } else {
                                echo '<div class="alert alert-danger fade show" role="alert">
                        <strong>Warning:</strong> OpenSSL is not enabled. Passwords will not be encrypted and will be stored as plain text. </div>';
                            }
                            ?>
                </div>
                <div class="row">
                    <!-- Main Settings Form -->
                    <form class="form-inline" method="post" enctype="multipart/form-data"
                        action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view']; ?>">
                        <div class="form-row">
                            <label for="main-app-settings">
                                <h3>Application</h3>
                            </label>
                            <div id="main-app-settings">
                                <?php
                                        //get the application name and url from the database
                                        $app_name = $APP->getSetting('app_name');
                                        $app_url = $APP->getSetting('app_url'); ?>
                                <div class="form-group col-md-6">
                                    <div class="form-row">
                                        <label for="app-name">Application Name</label>
                                        <input type="text" class="form-control" id="app-name"
                                            placeholder="<?php
                                                                                                                    //if app_name is set and not blank
                                                                                                                    if (isset($app_name) && $app_name != '') {
                                                                                                                        echo $app_name;
                                                                                                                    } else {
                                                                                                                        echo 'Application Name';
                                                                                                                    } ?>">
                                    </div>
                                    <div class="form-row">
                                        <label for="app-url">Application URL</label>
                                        <input type="text" class="form-control" id="app-url"
                                            placeholder="<?php
                                                                                                                    //if app_url is set and not blank
                                                                                                                    if (isset($app_url) && $app_url != '') {
                                                                                                                        echo $app_url;
                                                                                                                    } else {
                                                                                                                        echo 'Application URL';
                                                                                                                    } ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="mailer-settings">
                                <h3>Mailer</h3>
                            </label>
                            <div id="mailer-settings">
                                <?php
                                        //get the mailer settings from the database
                                        $mail_host = $APP->getSetting('mail_host');
                                        $mail_port = $APP->getSetting('mail_port');
                                        $mail_username = $APP->getSetting('mail_username');
                                        $mail_password = $APP->getSetting('mail_password');
                                        $mail_encryption = $APP->getSetting('mail_encryption');
                                        $mail_from_address = $APP->getSetting('mail_from_address');
                                        $mail_from_name = $APP->getSetting('mail_from_name');
                                        $mail_auth_req = $APP->getSetting('mail_auth_req');
                                        $mail_mailer = $APP->getSetting('mail_mailer');
                                        //the mailer options are defined in includes/constants.php
                                        //setup the mailer options array
                                        $mailer_options = MAILER;
                                        ?>
                                <div class="form-group col-md-6">
                                    <div class="form-row">
                                        <label for="mail-mailer">Mailer</label>
                                        <select class="form-control" id="mail-mailer">
                                            <?php
                                                    //output a blank option for null, this is the default unless the mail_mailer is set
                                                    if (!isset($mail_mailer) || $mail_mailer == '') {
                                                        echo '<option value="" selected>None</option>';
                                                    } else {
                                                        echo '<option value="">None</option>';
                                                    }
                                                    //loop through the mailer options
                                                    foreach ($mailer_options as $mailer_option) {
                                                        //if the mailer option is set and not blank
                                                        if (isset($mail_mailer) && $mail_mailer != '') {
                                                            //if the mailer option is the same as the mailer option in the database
                                                            if ($mail_mailer == $mailer_option['value']) {
                                                                //set the option to selected
                                                                echo '<option value="' . $mailer_option['value'] . '" selected>' . $mailer_option['label'] . '</option>';
                                                            } else {
                                                                //set the option to not selected
                                                                echo '<option value="' . $mailer_option['value'] . '">' . $mailer_option['label'] . '</option>';
                                                            }
                                                        } else {
                                                            //set the option to not selected
                                                            echo '<option value="' . $mailer_option['value'] . '">' . $mailer_option['label'] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                        </select>
                                    </div>
                                    <div class="form-row">
                                        <label for="mail-host">Host</label>
                                        <input type="text" class="form-control" id="mail-host"
                                            placeholder="<?php
                                                                                                                    //if mail_host is set and not blank
                                                                                                                    if (isset($mail_host) && $mail_host != '') {
                                                                                                                        echo $mail_host;
                                                                                                                    } else {
                                                                                                                        echo '127.0.0.1';
                                                                                                                    } ?>">
                                    </div>
                                    <div class="form-row">
                                        <label for="mail-port">Port</label>
                                        <input type="text" class="form-control" id="mail-port"
                                            placeholder="<?php
                                                                                                                    //if mail_port is set and not blank
                                                                                                                    if (isset($mail_port) && $mail_port != '') {
                                                                                                                        echo $mail_port;
                                                                                                                    } else {
                                                                                                                        echo '25';
                                                                                                                    } ?>">
                                    </div>
                                    <div class="form-row">
                                        <label for="mail-auth-req">Authentication Required</label>
                                        <div class="form-check" id="mail-auth-req">
                                            <?php
                                                    //if mail_auth_req is set and not blank
                                                    if (isset($mail_auth_req) && $mail_auth_req != '') {
                                                        //if mail_auth_req is true
                                                        if ($mail_auth_req == 'true') {
                                                            //set the checkbox to checked
                                                            echo '<input class="form-check-input" type="checkbox" value="" id="mail-auth-req" checked>';
                                                        } else {
                                                            //set the checkbox to not checked
                                                            echo '<input class="form-check-input" type="checkbox" value="" id="mail-auth-req">';
                                                        }
                                                    } else {
                                                        //set the checkbox to not checked
                                                        echo '<input class="form-check-input" type="checkbox" value="" id="mail-auth-req">';
                                                    }
                                                    ?>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <label for="mail-username">Username</label>
                                        <input type="text" class="form-control" id="mail-username"
                                            placeholder="<?php
                                                                                                                        //if mail_username is set and not blank
                                                                                                                        if (isset($mail_username) && $mail_username != '') {
                                                                                                                            echo $mail_username;
                                                                                                                        } else {
                                                                                                                            echo 'username';
                                                                                                                        } ?>">
                                    </div>
                                    <div class="form-row">
                                        <label for="mail-password">Password</label>
                                        <input type="password" class="form-control" id="mail-password"
                                            placeholder="<?php
                                                                                                                            //if mail_password is set and not blank
                                                                                                                            if (isset($mail_password) && $mail_password != '') {
                                                                                                                                //mask the password with asterisks
                                                                                                                                echo str_repeat('*', strlen($APP->getMailerPassword()));
                                                                                                                            } else {
                                                                                                                                echo 'password';
                                                                                                                            } ?>">
                                    </div>
                                    <div class="form-row">
                                        <label for="mail-encryption">Encryption</label>
                                        <select class="form-control" id="mail-encryption">
                                            <?php
                                                    //the mailer encryption options are defined in includes/constants.php
                                                    //setup the mailer encryption options array
                                                    $mailer_encryption_options = MAILER_ENCRYPTION;
                                                    //output a blank option for null, this is the default unless the mail_encryption is set
                                                    if (!isset($mail_encryption) || $mail_encryption == '') {
                                                        echo '<option value="" selected>None</option>';
                                                    } else {
                                                        echo '<option value="">None</option>';
                                                    }
                                                    //loop through the mailer encryption options
                                                    foreach ($mailer_encryption_options as $mailer_encryption_option) {
                                                        //if the mailer encryption option is set and not blank
                                                        if (isset($mail_encryption) && $mail_encryption != '') {
                                                            //if the mailer encryption option is the same as the mailer encryption option in the database
                                                            if ($mail_encryption == $mailer_encryption_option['value']) {
                                                                //set the option to selected
                                                                echo '<option value="' . $mailer_encryption_option['value'] . '" selected>' . $mailer_encryption_option['label'] . '</option>';
                                                            } else {
                                                                //set the option to not selected
                                                                echo '<option value="' . $mailer_encryption_option['value'] . '">' . $mailer_encryption_option['label'] . '</option>';
                                                            }
                                                        } else {
                                                            //set the option to not selected
                                                            echo '<option value="' . $mailer_encryption_option['value'] . '">' . $mailer_encryption_option['label'] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                        </select>
                                    </div>
                                    <div class="form-row">
                                        <label for="mail-from-address">From Address</label>
                                        <input type="text" class="form-control" id="mail-from-address"
                                            placeholder="<?php
                                                                                                                            //if mail_from_address is set and not blank
                                                                                                                            if (isset($mail_from_address) && $mail_from_address != '') {
                                                                                                                                echo $mail_from_address;
                                                                                                                            } else {
                                                                                                                                echo 'user@example.com';
                                                                                                                            } ?>">
                                    </div>
                                    <div class="form-row">
                                        <label for="mail-from-name">From Name</label>
                                        <input type="text" class="form-control" id="mail-from-name"
                                            placeholder="<?php
                                                                                                                            //if mail_from_name is set and not blank
                                                                                                                            if (isset($mail_from_name) && $mail_from_name != '') {
                                                                                                                                echo $mail_from_name;
                                                                                                                            } else {
                                                                                                                                echo 'Example User';
                                                                                                                            } ?>">
                                    </div>
                                </div>
                            </div>
                            <div id="privacy_policy">
                                <?php //get the privacy policy from the database
                                        $privacy_policy = $APP->getSetting('privacy_policy');
                                        ?>
                                <div class="form-group col-md-6">
                                    <div class="form-row">
                                        <label for="privacy-policy">Privacy Policy</label>
                                        <textarea class="form-control" id="privacy-policy" rows="15"
                                            placeholder='<?php
                                                                                                                            //if privacy_policy is set and not blank
                                                                                                                            if (isset($privacy_policy) && $privacy_policy != '') {
                                                                                                                                echo $privacy_policy;
                                                                                                                            } else {
                                                                                                                                echo strval(PRIVACY_POLICY);
                                                                                                                            } ?>'><?php
                                                                                                                            //if privacy_policy is set and not blank
                                                                                                                            if (isset($privacy_policy) && $privacy_policy != '') {
                                                                                                                                echo $privacy_policy;
                                                                                                                            } ?></textarea>
                                        <small id="privacy-policy-help" class="form-text text-muted">Create the Privacy
                                            Policy using Markdown.</small>
                                    </div>
                                </div>
                            </div>
                            <div id="terms_conditions">
                                <?php //get the terms and conditions from the database
                                        $terms_conditions = $APP->getSetting('terms_conditions');
                                        ?>
                                <div class="form-group col-md-6">
                                    <div class="form-row">
                                        <label for="terms-conditions">Terms and Conditions</label>
                                        <textarea class="form-control" id="terms-conditions" rows="15"
                                            placeholder='<?php
                                                                                                                            //if terms_conditions is set and not blank
                                                                                                                            if (isset($terms_conditions) && $terms_conditions != '') {
                                                                                                                                echo $terms_conditions;
                                                                                                                            } else {
                                                                                                                                echo strval(TERMS_CONDITIONS);
                                                                                                                            } ?>'><?php
                                                                                                                            //if terms_conditions is set and not blank
                                                                                                                            if (isset($terms_conditions) && $terms_conditions != '') {
                                                                                                                                echo $terms_conditions;
                                                                                                                            } ?></textarea>
                                        <small id="terms-conditions-help" class="form-text text-muted">Create the Terms
                                            and Conditions using Markdown.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                    </form>
                </div>
            </div>
    </main>
</div>
<?php
    }
} ?>
