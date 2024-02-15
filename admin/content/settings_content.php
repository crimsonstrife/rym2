<?php
//Prevent direct access to this file by checking if the constant ISVALIDUSER is defined.
if (!defined('ISVALIDUSER')) {
    //set the error type
    $thisError = 'INVALID_USER_REQUEST';

    //include the error message file
    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
} // idea from https://stackoverflow.com/a/409515 (user UnkwnTech)

//check that the view dashboard permission is set
if (!isset($hasViewDashboardPermission)) {
    //set the error type
    $thisError = 'CONFIGURATION_ERROR';

    //include the error message file
    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
} else {
    //check that the user has the view dashboard permission
    if (!$hasViewDashboardPermission) {
        //set the error type
        $thisError = 'DASHBOARD_PERMISSION_ERROR';

        //include the error message file
        include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
    } else {

        //include the application class
        $APP = new Application();

        //include the authenticator class
        $auth = new Authenticator();

        //include the permissions class
        $permissions = new Permission();

        //include the media class
        $media = new Media();

        //create an array of available media
        $mediaArray = $media->getMedia();

        //create an array of states
        $stateArray = STATES;

        //placeholder variables for the settings
        $entry_error = false; //boolean value for if there is an error with the form entry for server-side validation.
        $app_name = $app_url = $app_logo = $contact_email = $company_name = $company_logo = $company_address = $company_city = $company_state = $company_zip = $company_url = $company_phone = $mail_mailer = $mail_host = $mail_port = $mail_auth_req = $mail_username = $mail_password = $mail_encryption = $mail_from_address = $mail_from_name = $privacy_policy = $terms_conditions = $enableHotjar = $hotjarSiteId = $hotjarVersion = null;
        //other variables
        $target_file_appLogo = null;
        $target_file_companyLogo = null;
        $imageFileType_appLogo = null;
        $imageFileType_companyLogo = null;
        $appLogo_media_id = null;
        $companyLogo_media_id = null;
        $app_logoSelection = null;
        $company_logoSelection = null;
        $uploaded_appLogoFile = null;
        $uploaded_companyLogoFile = null;

        /*check if the user has the read settings permission */
        //get the permission id
        $readPermission_id = $permissions->getPermissionIdByName('READ SETTINGS');

        //boolean value for if the user has the read settings permission
        $hasReadSettingsPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $readPermission_id);

        //only show the settings if the user has the read settings permission
        if (!$hasReadSettingsPermission) {
            //set the error type
            $thisError = 'PERMISSION_ERROR_ACCESS';

            //include the error message file
            include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
        } else {
            /*check if the user has the update settings permission */
            //get the permission id
            $updatePermission_id = $permissions->getPermissionIdByName('UPDATE SETTINGS');

            //boolean value for if the user has the update settings permission
            $hasUpdateSettingsPermission = $auth->checkUserPermission(intval($_SESSION['user_id']), $updatePermission_id);

            //check if the form has been submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                //check if the user has the update settings permission
                if (!$hasUpdateSettingsPermission) {
                    //set the error type
                    $thisError = 'AUTHORIZATION_ERROR';

                    //include the error message file
                    include_once(__DIR__ . '/../../includes/errors/errorMessage.inc.php');
                }
                //check if the submit button was clicked
                if (isset($_POST['btnSubmit'])) {

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
                    } else {
                        //set the app url to null
                        $APP->setAppURL(null);
                    }

                    //get the app logo from the form
                    if (isset($_POST["app_logoSelect"])) {
                        $app_logoSelection = $_POST["app_logoSelect"];

                        //if the logo selection is empty, blank, or zero
                        if (
                            empty($app_logoSelection) || $app_logoSelection == '' || $app_logoSelection == 0
                        ) {
                            //try to get the file from the file input
                            if (isset($_FILES["app_logoUpload"])) {
                                $uploaded_appLogoFile = $_FILES["app_logoUpload"];

                                //if the file is empty, set the uploaded file to null
                                if (
                                    empty($uploaded_appLogoFile) || $uploaded_appLogoFile == '' || $uploaded_appLogoFile == null
                                ) {
                                    $uploaded_appLogoFile = null;
                                } else {
                                    //set the app logo to the uploaded file
                                    $app_logo = $uploaded_appLogoFile;
                                }
                            }
                        } else {
                            //set the uploaded file to null
                            $uploaded_appLogoFile = null;
                            //set the app logo to the selection
                            $app_logo = $app_logoSelection;
                        }
                    }

                    //if the app logo is empty, set the app logo to null
                    if (empty($app_logo)) {
                        $app_logo = null;
                    }

                    //check if the contact email is set
                    if (isset($_POST['contact_email']) && $_POST['contact_email'] != '') {
                        //prevent XSS attacks by removing html tags
                        $_POST['contact_email'] = strip_tags($_POST['contact_email']);
                        //trim whitespace from the contact email
                        $_POST['contact_email'] = trim($_POST['contact_email']);
                        //set the contact email
                        $APP->setContactEmail($_POST['contact_email']);
                    } else {
                        //set the contact email to null
                        $APP->setContactEmail(null);
                    }

                    //check if the company name is set
                    if (isset($_POST['company_name']) && $_POST['company_name'] != '') {
                        //prevent XSS attacks by removing html tags
                        $_POST['company_name'] = strip_tags($_POST['company_name']);
                        //prepare the company name
                        $_POST['company_name'] = htmlspecialchars($_POST['company_name']);
                        //trim whitespace from the company name
                        $_POST['company_name'] = trim($_POST['company_name']);
                        //set the company name
                        $APP->setCompanyName($_POST['company_name']);
                    } else {
                        //set the company name to null
                        $APP->setCompanyName(null);
                    }

                    //get the company logo from the form
                    if (isset($_POST["company_logoSelect"])) {
                        $company_logoSelection = $_POST["company_logoSelect"];

                        //if the logo selection is empty, blank, or zero
                        if (
                            empty($company_logoSelection) || $company_logoSelection == '' || $company_logoSelection == 0
                        ) {
                            //try to get the file from the file input
                            if (isset($_FILES["company_logoUpload"])) {
                                $uploaded_companyLogoFile = $_FILES["company_logoUpload"];

                                //if the file is empty, set the uploaded file to null
                                if (
                                    empty($uploaded_companyLogoFile) || $uploaded_companyLogoFile == '' || $uploaded_companyLogoFile == null
                                ) {
                                    $uploaded_companyLogoFile = null;
                                } else {
                                    //set the company logo to the uploaded file
                                    $company_logo = $uploaded_companyLogoFile;
                                }
                            }
                        } else {
                            //set the uploaded file to null
                            $uploaded_companyLogoFile = null;
                            //set the company logo to the selection
                            $company_logo = intval($company_logoSelection);
                        }
                    }

                    //if the company logo is empty, set the company logo to null
                    if (empty($company_logo)) {
                        $company_logo = null;
                    }

                    //check if the company address is set
                    if (isset($_POST['company_address']) && $_POST['company_address'] != '') {
                        //prevent XSS attacks by removing html tags
                        $_POST['company_address'] = strip_tags($_POST['company_address']);
                        //prepare the company address
                        $_POST['company_address'] = htmlspecialchars($_POST['company_address']);
                        //trim whitespace from the company address
                        $_POST['company_address'] = trim($_POST['company_address']);
                        //set the company address
                        $APP->setCompanyAddress($_POST['company_address']);
                    } else {
                        //set the company address to null
                        $APP->setCompanyAddress(null);
                    }

                    //check if the company city is set
                    if (isset($_POST['company_city']) && $_POST['company_city'] != '') {
                        //prevent XSS attacks by removing html tags
                        $_POST['company_city'] = strip_tags($_POST['company_city']);
                        //prepare the company city
                        $_POST['company_city'] = htmlspecialchars($_POST['company_city']);
                        //trim whitespace from the company city
                        $_POST['company_city'] = trim($_POST['company_city']);
                        //set the company city
                        $APP->setCompanyCity($_POST['company_city']);
                    } else {
                        //set the company city to null
                        $APP->setCompanyCity(null);
                    }

                    //check if the company state is set
                    if (isset($_POST['company_state']) && $_POST['company_state'] != '') {
                        //prevent XSS attacks by removing html tags
                        $_POST['company_state'] = strip_tags($_POST['company_state']);
                        //prepare the company state
                        $_POST['company_state'] = htmlspecialchars($_POST['company_state']);
                        //trim whitespace from the company state
                        $_POST['company_state'] = trim($_POST['company_state']);
                        //set the company state
                        $APP->setCompanyState($_POST['company_state']);
                    } else {
                        //set the company state to null
                        $APP->setCompanyState(null);
                    }

                    //check if the company zip is set
                    if (isset($_POST['company_zip']) && $_POST['company_zip'] != '') {
                        //prevent XSS attacks by removing html tags
                        $_POST['company_zip'] = strip_tags($_POST['company_zip']);
                        //prepare the company zip
                        $_POST['company_zip'] = htmlspecialchars($_POST['company_zip']);
                        //trim whitespace from the company zip
                        $_POST['company_zip'] = trim($_POST['company_zip']);
                        //set the company zip
                        $APP->setCompanyZip($_POST['company_zip']);
                    } else {
                        //set the company zip to null
                        $APP->setCompanyZip(null);
                    }

                    //check if the company url is set
                    if (isset($_POST['company_url']) && $_POST['company_url'] != '') {
                        //prevent XSS attacks by removing html tags
                        $_POST['company_url'] = strip_tags($_POST['company_url']);
                        //prepare the company url
                        $_POST['company_url'] = htmlspecialchars($_POST['company_url']);
                        //trim whitespace from the company url
                        $_POST['company_url'] = trim($_POST['company_url']);
                        //set the company url
                        $APP->setCompanyURL($_POST['company_url']);
                    } else {
                        //set the company url to null
                        $APP->setCompanyURL(null);
                    }

                    //check if the company phone is set
                    if (isset($_POST['company_phone']) && $_POST['company_phone'] != '') {
                        //prevent XSS attacks by removing html tags
                        $_POST['company_phone'] = strip_tags($_POST['company_phone']);
                        //prepare the company phone
                        $_POST['company_phone'] = htmlspecialchars($_POST['company_phone']);
                        //trim whitespace from the company phone
                        $_POST['company_phone'] = trim($_POST['company_phone']);
                        //set the company phone
                        $APP->setCompanyPhone($_POST['company_phone']);
                    } else {
                        //set the company phone to null
                        $APP->setCompanyPhone(null);
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
                        //$_POST['privacy_policy'] = strip_tags($_POST['privacy_policy']);
                        //trim whitespace from the privacy policy
                        //$_POST['privacy_policy'] = trim($_POST['privacy_policy']);
                        //set the privacy policy
                        $APP->setPrivacyPolicy($_POST['privacy_policy']);
                    }

                    //check if the terms and conditions is set
                    if (isset($_POST['terms_conditions']) && $_POST['terms_conditions'] != '') {
                        //prevent XSS attacks by removing html tags
                        //$_POST['terms_conditions'] = strip_tags($_POST['terms_conditions']);
                        //trim whitespace from the terms and conditions
                        //$_POST['terms_conditions'] = trim($_POST['terms_conditions']);
                        //set the terms and conditions
                        $APP->setTerms($_POST['terms_conditions']);
                    }

                    //if there are files to upload for the app, upload them
                    if (!empty($app_logo) && ($uploaded_appLogoFile != null || $uploaded_appLogoFile != '')) {
                        //check if the app logo is an array
                        if (is_array($app_logo)) {
                            //debugging
                            error_log('app_logo is array: ' . $app_logo);

                            //upload the app logo, and get the media id
                            $appLogo_media_id = $media->uploadMedia($app_logo, intval($_SESSION['user_id']));

                            //get the file name
                            $target_file_appLogo = $media->getMediaFileName($appLogo_media_id);

                            //get the file type
                            $imageFileType_logo = strtolower(pathinfo($target_file_appLogo, PATHINFO_EXTENSION));
                        } else if (!empty($app_logo) && $uploaded_appLogoFile = null) {
                            //debugging
                            error_log('app_logo: ' . $app_logo);

                            //assume the app logo is an integer
                            $appLogo_media_id = $app_logo;

                            //debugging
                            error_log('appLogo_media_id: ' . $appLogo_media_id);

                            //get the file name
                            $target_file_appLogo = $media->getMediaFileName($appLogo_media_id);

                            //get the file type
                            $imageFileType_logo = strtolower(pathinfo($target_file_appLogo, PATHINFO_EXTENSION));
                        }
                    } else if (!empty($app_logo) && $uploaded_appLogoFile = null) {
                        $appLogo_media_id = $app_logo;
                    } else if (empty($app_logo) || $app_logo == null || !isset($app_logo) || $app_logo == '') {
                        //set the app logo to null
                        $appLogo_media_id = null;
                    } else if (!empty($app_logo)) {
                        $appLogo_media_id = $app_logo;
                    }

                    //if there are files to upload for the company, upload them
                    if (!empty($company_logo) && $uploaded_companyLogoFile != null) {
                        //check if the company logo is an array
                        if (is_array($company_logo)) {
                            //upload the company logo, and get the media id
                            $companyLogo_media_id = $media->uploadMedia($company_logo, intval($_SESSION['user_id']));

                            //get the file name
                            $target_file_companyLogo = $media->getMediaFileName($companyLogo_media_id);

                            //get the file type
                            $imageFileType_logo = strtolower(pathinfo($target_file_companyLogo, PATHINFO_EXTENSION));
                        } else if (!empty($company_logo) && $uploaded_companyLogoFile = null) {
                            //assume the company logo is an integer
                            $companyLogo_media_id = $company_logo;

                            //get the file name
                            $target_file_companyLogo = $media->getMediaFileName($companyLogo_media_id);

                            //get the file type
                            $imageFileType_logo = strtolower(pathinfo($target_file_companyLogo, PATHINFO_EXTENSION));
                        }
                    } else if (!empty($company_logo) && $uploaded_companyLogoFile = null) {
                        $companyLogo_media_id = $company_logo;
                    } else if (empty($company_logo) || $company_logo == null || !isset($company_logo) || $company_logo == '') {
                        //set the company logo to null
                        $companyLogo_media_id = null;
                    } else if (!empty($company_logo)) {
                        $companyLogo_media_id = $company_logo;
                    }

                    if (!empty($app_logo) || $app_logo != null || isset($app_logo)) {
                        //set the app logo
                        $appLogoSet = $APP->setAppLogo($appLogo_media_id);
                    } else {
                        $appLogoSet = false;
                    }

                    if (!empty($company_logo) || $company_logo != null || isset($company_logo)) {
                        //set the company logo
                        $companyLogoSet = $APP->setCompanyLogo($companyLogo_media_id);
                    } else {
                        $companyLogoSet = false;
                    }

                    //refresh the page
                    header('Location: ' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view']);
                } else if (isset($_POST['btnReset'])) {
                    //reset the settings
                    $APP->resetSettings();

                    //refresh the page
                    header('Location: ' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view']);
                }
            }
?>
<script src="<?php echo getLibraryPath() . 'ckeditor/ckeditor.js'; ?>"></script>
<!-- main content -->
<div id="layout_content" class="w-95 mx-auto">
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
                    <form class="form-inline" <?php if ($hasUpdateSettingsPermission) {
                                                                echo 'method="post"' . ' action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?view=' . $_GET['view'] . '"';
                                                            } ?> class="needs-validation <?php if ($entry_error) {
                                                                                                echo 'was-validated';
                                                                                            } ?>">
                        <div class="form-group">
                            <div class="form-row">
                                <label for="main-app-settings">
                                    <h3>Application</h3>
                                </label>
                                <div id="main-app-settings">
                                    <?php
                                                //get the general application settings from the database
                                                $app_name = $APP->getAppName();
                                                $app_url = $APP->getAppURL();
                                                $app_logo = $APP->getAppLogo();
                                                $contact_email = $APP->getContactEmail();
                                                ?>
                                    <div class="form-group">
                                        <div class="form-row">
                                            <label for="app-name">Application Name</label>
                                            <input type="text" class="form-control" id="app-name" name="app_name"
                                                placeholder="<?php
                                                                                                                                            //if app_name is set and not blank
                                                                                                                                            if (isset($app_name) && $app_name != '') {
                                                                                                                                                echo $app_name;
                                                                                                                                            } else {
                                                                                                                                                echo 'Application Name';
                                                                                                                                            } ?>"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                        echo 'disabled';
                                                                                                                                                    } ?>
                                                value="<?php
                                                                                                                                                                //if app_name is set and not blank
                                                                                                                                                                if (isset($app_name) && $app_name != '') {
                                                                                                                                                                    echo $app_name;
                                                                                                                                                                } else {
                                                                                                                                                                    echo '';
                                                                                                                                                                } ?>">
                                        </div>
                                        <div class="form-row">
                                            <label for="app-url">Application URL</label>
                                            <input type="text" class="form-control" id="app-url" name="app_url"
                                                placeholder="<?php
                                                                                                                                            //if app_url is set and not blank
                                                                                                                                            if (isset($app_url) && $app_url != '') {
                                                                                                                                                echo $app_url;
                                                                                                                                            } else {
                                                                                                                                                echo 'Application URL';
                                                                                                                                            } ?>"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                        echo 'disabled';
                                                                                                                                                    } ?>
                                                value="<?php
                                                                                                                                                                //if app_url is set and not blank
                                                                                                                                                                if (isset($app_url) && $app_url != '') {
                                                                                                                                                                    echo $app_url;
                                                                                                                                                                } else {
                                                                                                                                                                    echo '';
                                                                                                                                                                } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label for="app-logo">Application Logo</label>
                                                <br />
                                                <!-- if there is an existing logo, show the file -->
                                                <?php
                                                            if (!empty($APP->getAppLogo())) {
                                                                //render the file as an image
                                                                echo '<div><img src="' . getUploadPath() . $media->getMediaFileName(intval($app_logo)) . '" alt="Application Logo" style="max-width: 200px; max-height: auto;"></div>';
                                                                //show the file name
                                                                echo '<div> ' . $media->getMediaFileName(intval($app_logo)) . '</div>';
                                                            }
                                                            ?>
                                                <?php //allow the user to either select a file from the mediaArray or upload a new file if they have upload permissions
                                                            if (!empty($mediaArray)) { ?>
                                                <br />
                                                <label for="appLogoSelect">Select a New Logo:</label>
                                                <select id="appLogoSelect" name="app_logoSelect" class="form-control"
                                                    <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                            echo 'disabled';
                                                                                                                                        } ?>>
                                                    <option value="">Select a Logo</option>
                                                    <?php /* check if the user has permission to upload media */
                                                                    if ($auth->checkUserPermission(intval($_SESSION['user_id']), $permissions->getPermissionIdByName('CREATE MEDIA'))) { ?>
                                                    <option value="0">Upload a New Logo</option>
                                                    <?php } ?>
                                                    <?php foreach ($mediaArray as $key => $value) { ?>
                                                    <option value="<?php echo $value['id'] ?>">
                                                        <?php echo $value['filename']; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <br />
                                                <!-- if the user selects to upload a new file, show the file upload input -->
                                                <input type="file" id="appLogoUpload" name="app_logoUpload"
                                                    class="form-control" disabled hidden>
                                                <small id="appLogoHelp" class="form-text text-muted">To upload a new
                                                    file, select
                                                    "Upload
                                                    a New Logo" from the dropdown. To use an existing file, select the
                                                    filename.</small>
                                                <?php } else if (empty($mediaArray)) { //if there are no media files, show the file upload input if the user has upload permissions
                                                                if ($auth->checkUserPermission(intval($_SESSION['user_id']), $permissions->getPermissionIdByName('CREATE MEDIA'))) { ?>
                                                <label for="app-logo-upload">Upload a New Logo:</label>
                                                <input type="file" id="app-logo-upload" name="app_logoUpload"
                                                    class="form-control"
                                                    <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                            echo 'disabled';
                                                                                                                                                        } ?>>
                                                <small id="appLogoHelp" class="form-text text-muted">To upload a new
                                                    file, select
                                                    "Upload
                                                    a New Logo" from the dropdown. To use an existing file, select the
                                                    filename.</small>
                                                <div class="alert alert-warning" role="alert"><span class="note-icon"><i
                                                            class="fa-solid fa-circle-exclamation"></i></span>
                                                    <span class="note-text">If the upload option is not available,
                                                        contact the
                                                        administrator
                                                        for assistance.</span>
                                                </div>
                                                <?php } else { ?>
                                                <p><strong><label for="appLogo">No Media Available</label></strong></p>
                                                <p>You lack permissions to upload new media files and none currently
                                                    exist, contact the
                                                    administrator.</p>
                                                <?php }
                                                            } ?>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label for="contact-email">Contact Email</label>
                                            <input type="email" class="form-control" id="contact-email"
                                                name="contact_email"
                                                placeholder="<?php
                                                                                                                                                        //if contact_email is set and not blank
                                                                                                                                                        if (isset($contact_email) && $contact_email != '') {
                                                                                                                                                            echo $contact_email;
                                                                                                                                                        } else {
                                                                                                                                                            echo 'Contact Email';
                                                                                                                                                        } ?>"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                    echo 'disabled';
                                                                                                                                                                } ?>
                                                value="<?php
                                                                                                                                                                            //if contact_email is set and not blank
                                                                                                                                                                            if (isset($contact_email) && $contact_email != '') {
                                                                                                                                                                                echo $contact_email;
                                                                                                                                                                            } else {
                                                                                                                                                                                echo '';
                                                                                                                                                                            } ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <label for="company-settings">
                                    <h3>Company</h3>
                                </label>
                                <div id="company-settings">
                                    <?php
                                                //get the company settings from the database
                                                $company_name = $APP->getCompanyName();
                                                $company_logo = $APP->getCompanyLogo();
                                                $company_address = $APP->getCompanyAddress();
                                                $company_city = $APP->getCompanyCity();
                                                $company_state = $APP->getCompanyState();
                                                $company_zip = $APP->getCompanyZip();
                                                $company_formattedAddress = $APP->getFormattedCompanyAddress();
                                                $company_url = $APP->getCompanyURL();
                                                $company_phone = $APP->getCompanyPhone();
                                                ?>
                                    <div class="form-group">
                                        <div class="form-row">
                                            <label for="company-name">Company Name</label>
                                            <input type="text" class="form-control" id="company-name"
                                                name="company_name"
                                                placeholder="<?php
                                                                                                                                                    //if company_name is set and not blank
                                                                                                                                                    if (isset($company_name) && $company_name != '') {
                                                                                                                                                        echo $company_name;
                                                                                                                                                    } else {
                                                                                                                                                        echo 'Company Name';
                                                                                                                                                    } ?>"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                echo 'disabled';
                                                                                                                                                            } ?>
                                                value="<?php
                                                                                                                                                                        //if company_name is set and not blank
                                                                                                                                                                        if (isset($company_name) && $company_name != '') {
                                                                                                                                                                            echo $company_name;
                                                                                                                                                                        } else {
                                                                                                                                                                            echo '';
                                                                                                                                                                        } ?>">
                                        </div>
                                        <div class=" form-row">
                                            <div class="form-group">
                                                <label for="company-logo">Company Logo</label>
                                                <br />
                                                <!-- if there is an existing logo, show the file -->
                                                <?php
                                                            if (!empty($APP->getCompanyLogo())) {
                                                                //render the file as an image
                                                                echo '<div><img src="' . getUploadPath() . $media->getMediaFileName(intval($company_logo)) . '" alt="Company Logo" style="max-width: 200px; max-height: auto;"></div>';
                                                                //show the file name
                                                                echo '<div> ' . $media->getMediaFileName(intval($company_logo)) . '</div>';
                                                            }
                                                            ?>
                                                <?php //allow the user to either select a file from the mediaArray or upload a new file if they have upload permissions
                                                            if (!empty($mediaArray)) { ?>
                                                <br />
                                                <label for="companyLogoSelect">Select a New Logo:</label>
                                                <select id="companyLogoSelect" name="company_logoSelect"
                                                    class="form-control"
                                                    <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                    echo 'disabled';
                                                                                                                                                } ?>>
                                                    <option value="">Select a Logo</option>
                                                    <?php /* check if the user has permission to upload media */
                                                                    if ($auth->checkUserPermission(intval($_SESSION['user_id']), $permissions->getPermissionIdByName('CREATE MEDIA'))) { ?>
                                                    <option value="0">Upload a New Logo</option>
                                                    <?php } ?>
                                                    <?php foreach ($mediaArray as $key => $value) { ?>
                                                    <option value="<?php echo $value['id'] ?>">
                                                        <?php echo $value['filename']; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <br />
                                                <!-- if the user selects to upload a new file, show the file upload input -->
                                                <input type="file" id="companyLogoUpload" name="company_logoUpload"
                                                    class="form-control" disabled hidden>
                                                <small id="companyLogoHelp" class="form-text text-muted">To upload a
                                                    new
                                                    file, select
                                                    "Upload
                                                    a New Logo" from the dropdown. To use an existing file, select
                                                    the
                                                    filename.</small>
                                                <?php } else if (empty($mediaArray)) { //if there are no media files, show the file upload input if the user has upload permissions
                                                                if ($auth->checkUserPermission(intval($_SESSION['user_id']), $permissions->getPermissionIdByName('CREATE MEDIA'))) { ?>
                                                <label for="company-logo-upload">Upload a New Logo:</label>
                                                <input type="file" id="company-logo-upload" name="company_logoUpload"
                                                    class="form-control"
                                                    <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                    echo 'disabled';
                                                                                                                                                                } ?>>
                                                <small id="companyLogoHelp" class="form-text text-muted">To upload a
                                                    new
                                                    file,
                                                    select
                                                    "Upload
                                                    a New Logo" from the dropdown. To use an existing file, select
                                                    the
                                                    filename.</small>
                                                <div class="alert alert-warning" role="alert"><span class="note-icon"><i
                                                            class="fa-solid fa-circle-exclamation"></i></span>
                                                    <span class="note-text">If the upload option is not available,
                                                        contact the
                                                        administrator
                                                        for assistance.</span>
                                                </div>
                                                <?php } else { ?>
                                                <p><strong><label for="companyLogo">No Media
                                                            Available</label></strong>
                                                </p>
                                                <p>You lack permissions to upload new media files and none currently
                                                    exist, contact the
                                                    administrator.</p>
                                                <?php }
                                                            } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-row">
                                            <label for="company-address">Company Address</label>
                                            <input type="text" class="form-control" id="company-address"
                                                name="company_address"
                                                placeholder="<?php
                                                                                                                                                            //if company_address is set and not blank
                                                                                                                                                            if (isset($company_address) && $company_address != '') {
                                                                                                                                                                echo $company_address;
                                                                                                                                                            } else {
                                                                                                                                                                echo 'Street Address';
                                                                                                                                                            } ?>"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                        echo 'disabled';
                                                                                                                                                                    } ?>
                                                value="<?php
                                                                                                                                                                                //if company_address is set and not blank
                                                                                                                                                                                if (isset($company_address) && $company_address != '') {
                                                                                                                                                                                    echo $company_address;
                                                                                                                                                                                } else {
                                                                                                                                                                                    echo '';
                                                                                                                                                                                } ?>">
                                        </div>
                                        <div class=" form-row">
                                            <label for="company-city">City</label>
                                            <input type="text" class="form-control" id="company-city"
                                                name="company_city"
                                                placeholder="<?php
                                                                                                                                                    //if company_city is set and not blank
                                                                                                                                                    if (isset($company_city) && $company_city != '') {
                                                                                                                                                        echo $company_city;
                                                                                                                                                    } else {
                                                                                                                                                        echo 'City';
                                                                                                                                                    } ?>"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                echo 'disabled';
                                                                                                                                                            } ?>
                                                value="<?php
                                                                                                                                                                        //if company_city is set and not blank
                                                                                                                                                                        if (isset($company_city) && $company_city != '') {
                                                                                                                                                                            echo $company_city;
                                                                                                                                                                        } else {
                                                                                                                                                                            echo '';
                                                                                                                                                                        } ?>">
                                        </div>
                                        <div class="form-row">
                                            <label for="company-state">State</label>
                                            <select class="form-control" id="company-state" name="company_state"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                    echo 'disabled';
                                                                                                                                } ?>>

                                                <?php
                                                            //blank first option to default to
                                                            echo '<option value="">Select a State</option>';
                                                            //loop through the state array
                                                            foreach ($stateArray as $key => $value) {
                                                                //if the state matches the company state, set the selected attribute
                                                                if ($value['value'] == $company_state) {
                                                                    echo '<option value="' . $value['value'] . '" selected>' . $value['label'] . '</option>';
                                                                } else {
                                                                    echo '<option value="' . $value['value'] . '">' . $value['label'] . '</option>';
                                                                }
                                                            }
                                                            ?>
                                            </select>
                                        </div>
                                        <div class="form-row">
                                            <label for="company-zip">Zip</label>
                                            <input type="text" class="form-control" id="company-zip" name="company_zip"
                                                placeholder="<?php
                                                                                                                                                    //if company_zip is set and not blank
                                                                                                                                                    if (isset($company_zip) && $company_zip != '') {
                                                                                                                                                        echo $company_zip;
                                                                                                                                                    } else {
                                                                                                                                                        echo 'Zip';
                                                                                                                                                    } ?>"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                echo 'disabled';
                                                                                                                                                            } ?>
                                                value="<?php
                                                                                                                                                                        //if company_zip is set and not blank
                                                                                                                                                                        if (isset($company_zip) && $company_zip != '') {
                                                                                                                                                                            echo $company_zip;
                                                                                                                                                                        } else {
                                                                                                                                                                            echo '';
                                                                                                                                                                        } ?>">
                                        </div>
                                        <div class="form-row">
                                            <label for="company-url">Company URL</label>
                                            <input type="text" class="form-control" id="company-url" name="company_url"
                                                placeholder="<?php
                                                                                                                                                    //if company_url is set and not blank
                                                                                                                                                    if (isset($company_url) && $company_url != '') {
                                                                                                                                                        echo $company_url;
                                                                                                                                                    } else {
                                                                                                                                                        echo 'Company URL';
                                                                                                                                                    } ?>"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                echo 'disabled';
                                                                                                                                                            } ?>
                                                value="<?php
                                                                                                                                                                        //if company_url is set and not blank
                                                                                                                                                                        if (isset($company_url) && $company_url != '') {
                                                                                                                                                                            echo $company_url;
                                                                                                                                                                        } else {
                                                                                                                                                                            echo '';
                                                                                                                                                                        } ?>">
                                        </div>
                                        <div class="form-row">
                                            <label for="company-phone">Company Phone</label>
                                            <input type="tel" class="form-control" id="company-phone"
                                                name="company_phone"
                                                placeholder="<?php
                                                                                                                                                    //if company_phone is set and not blank
                                                                                                                                                    if (isset($company_phone) && $company_phone != '') {
                                                                                                                                                        echo $company_phone;
                                                                                                                                                    } else {
                                                                                                                                                        echo 'Company Phone Number';
                                                                                                                                                    } ?>"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                echo 'disabled';
                                                                                                                                                            } ?>
                                                value="<?php
                                                                                                                                                                        //if company_phone is set and not blank
                                                                                                                                                                        if (isset($company_phone) && $company_phone != '') {
                                                                                                                                                                            echo $company_phone;
                                                                                                                                                                        } else {
                                                                                                                                                                            echo '';
                                                                                                                                                                        } ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <label for="mailer-settings">
                                    <h3>Mailer</h3>
                                </label>
                                <div id="mailer-settings">
                                    <?php
                                                //get the mailer settings from the database
                                                $mail_host = $APP->getMailerHost();
                                                $mail_port = $APP->getMailerPort();
                                                $mail_username = $APP->getMailerUsername();
                                                $mail_password = $APP->getMailerPassword();
                                                $mail_encryption = $APP->getMailerEncryption();
                                                $mail_from_address = $APP->getMailerFromAddress();
                                                $mail_from_name = $APP->getMailerFromName();
                                                $mail_auth_req = $APP->getMailerAuthRequired();
                                                $mail_mailer = $APP->getMailerType();
                                                //the mailer options are defined in includes/constants.php
                                                //setup the mailer options array
                                                $mailer_options = MAILER;
                                                ?>
                                    <div class="form-group">
                                        <div id="mail-mailer-row" class="form-row">
                                            <label for="mail-mailer">Mailer</label>
                                            <select class="form-control" id="mail-mailer" name="mail_mailer"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                echo 'disabled';
                                                                                                                            } ?>>
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
                                        <div id="mail-host-row" class="form-row">
                                            <label for="mail-host">Host</label>
                                            <input type="text" class="form-control" id="mail-host" name="mail_host"
                                                autocomplete="smtp host"
                                                placeholder="<?php
                                                                                                                                                                        //if mail_host is set and not blank
                                                                                                                                                                        if (isset($mail_host) && $mail_host != '') {
                                                                                                                                                                            echo $mail_host;
                                                                                                                                                                        } else {
                                                                                                                                                                            echo '127.0.0.1';
                                                                                                                                                                        } ?>"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                                    echo 'disabled';
                                                                                                                                                                                } ?>
                                                value="<?php
                                                                                                                                                                                            //if mail_host is set and not blank
                                                                                                                                                                                            if (isset($mail_host) && $mail_host != '') {
                                                                                                                                                                                                echo $mail_host;
                                                                                                                                                                                            } else {
                                                                                                                                                                                                echo '';
                                                                                                                                                                                            } ?>">
                                        </div>
                                        <div id="mail-port-row" class="form-row">
                                            <label for="mail-port">Port</label>
                                            <input type="text" class="form-control" id="mail-port" name="mail_port"
                                                autocomplete="smtp port"
                                                placeholder="<?php
                                                                                                                                                                        //if mail_port is set and not blank
                                                                                                                                                                        if (isset($mail_port) && $mail_port != '') {
                                                                                                                                                                            echo $mail_port;
                                                                                                                                                                        } else {
                                                                                                                                                                            echo '25';
                                                                                                                                                                        } ?>"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                                    echo 'disabled';
                                                                                                                                                                                } ?>
                                                value="<?php
                                                                                                                                                                                            //if mail_port is set and not blank
                                                                                                                                                                                            if (isset($mail_port) && $mail_port != '') {
                                                                                                                                                                                                echo $mail_port;
                                                                                                                                                                                            } else {
                                                                                                                                                                                                echo '';
                                                                                                                                                                                            } ?>">
                                        </div>
                                        <div id="mail-auth-req-row" class="form-row">
                                            <label for="mail-auth-req">Authentication Required</label>
                                            <div class="form-check" id="mail-auth-req-container">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="mail-auth-req" name="mail_auth_req"
                                                    <?php if (isset($mail_auth_req) && $mail_auth_req != '') {
                                                                                                                                                                    if ($mail_auth_req = 'true') {
                                                                                                                                                                        echo 'checked';
                                                                                                                                                                    }
                                                                                                                                                                } ?>
                                                    <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                            echo ' disabled';
                                                                                                                                                                        } ?>>
                                            </div>
                                        </div>
                                        <div id="mail-username-row" class="form-row">
                                            <label for="mail-username">Username</label>
                                            <input type="text" class="form-control" id="mail-username"
                                                name="mail_username" autocomplete="username"
                                                placeholder="<?php
                                                                                                                                                                                //if mail_username is set and not blank
                                                                                                                                                                                if (isset($mail_username) && $mail_username != '') {
                                                                                                                                                                                    echo $mail_username;
                                                                                                                                                                                } else {
                                                                                                                                                                                    echo 'username';
                                                                                                                                                                                } ?>"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                                            echo 'disabled';
                                                                                                                                                                                        } ?>
                                                value="<?php
                                                                                                                                                                                                    //if mail_username is set and not blank
                                                                                                                                                                                                    if (isset($mail_username) && $mail_username != '') {
                                                                                                                                                                                                        echo $mail_username;
                                                                                                                                                                                                    } else {
                                                                                                                                                                                                        echo '';
                                                                                                                                                                                                    } ?>">
                                        </div>
                                        <div id="mail-password-row" class="form-row">
                                            <label for="mail-password">Password</label>
                                            <input type="password" class="form-control" id="mail-password"
                                                name="mail_password" autocomplete="password"
                                                placeholder="<?php
                                                                                                                                                                                    //if mail_password is set and not blank
                                                                                                                                                                                    if (isset($mail_password) && $mail_password != '') {
                                                                                                                                                                                        //mask the password with asterisks
                                                                                                                                                                                        echo str_repeat('*', strlen($APP->getMailerPassword()));
                                                                                                                                                                                    } else {
                                                                                                                                                                                        echo 'password';
                                                                                                                                                                                    } ?>"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                                                echo 'disabled';
                                                                                                                                                                                            } ?>
                                                value="<?php
                                                                                                                                                                                                        //if mail_password is set and not blank
                                                                                                                                                                                                        if (isset($mail_password) && $mail_password != '') {
                                                                                                                                                                                                            //mask the password with asterisks
                                                                                                                                                                                                            echo str_repeat('*', strlen($APP->getMailerPassword()));
                                                                                                                                                                                                        } else {
                                                                                                                                                                                                            echo '';
                                                                                                                                                                                                        } ?>">
                                        </div>
                                        <div id="mail-encryption-row" class="form-row">
                                            <label for="mail-encryption">Encryption</label>
                                            <select class="form-control" id="mail-encryption" name="mail_encryption"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                        echo 'disabled';
                                                                                                                                    } ?>>
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
                                        <div id="mail-from-address-row" class="form-row">
                                            <label for="mail-from-address">From Address</label>
                                            <input type="text" class="form-control" id="mail-from-address"
                                                name="mail_from_address" autocomplete="email"
                                                placeholder="<?php
                                                                                                                                                                                    //if mail_from_address is set and not blank
                                                                                                                                                                                    if (isset($mail_from_address) && $mail_from_address != '') {
                                                                                                                                                                                        echo $mail_from_address;
                                                                                                                                                                                    } else {
                                                                                                                                                                                        echo 'user@example.com';
                                                                                                                                                                                    } ?>"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                                                echo 'disabled';
                                                                                                                                                                                            } ?>
                                                value="<?php
                                                                                                                                                                                                        //if mail_from_address is set and not blank
                                                                                                                                                                                                        if (isset($mail_from_address) && $mail_from_address != '') {
                                                                                                                                                                                                            echo $mail_from_address;
                                                                                                                                                                                                        } else {
                                                                                                                                                                                                            echo '';
                                                                                                                                                                                                        } ?>">
                                        </div>
                                        <div id="mail-from-name-row" class="form-row">
                                            <label for="mail-from-name">From Name</label>
                                            <input type="text" class="form-control" id="mail-from-name"
                                                name="mail_from_name" autocomplete="name"
                                                placeholder="<?php
                                                                                                                                                                            //if mail_from_name is set and not blank
                                                                                                                                                                            if (isset($mail_from_name) && $mail_from_name != '') {
                                                                                                                                                                                echo $mail_from_name;
                                                                                                                                                                            } else {
                                                                                                                                                                                echo 'Example User';
                                                                                                                                                                            } ?>"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                                        echo 'disabled';
                                                                                                                                                                                    } ?>
                                                value="<?php
                                                                                                                                                                                                //if mail_from_name is set and not blank
                                                                                                                                                                                                if (isset($mail_from_name) && $mail_from_name != '') {
                                                                                                                                                                                                    echo $mail_from_name;
                                                                                                                                                                                                } else {
                                                                                                                                                                                                    echo '';
                                                                                                                                                                                                } ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <label for="privacy_policy">
                                    <h3>Privacy Policy</h3>
                                </label>
                                <div id="privacy_policy">
                                    <?php //get the privacy policy from the database
                                                $privacy_policy = $APP->getSetting('privacy_policy');
                                                ?>
                                    <div class="form-group">
                                        <div class="form-row">
                                            <label for="privacy-policy">Privacy Policy Page Content</label>
                                            <textarea class="form-control wysiwyg-editor" id="privacy-policy"
                                                name="privacy_policy" rows="15"
                                                placeholder='<?php
                                                                                                                                                                        //if privacy_policy is set and not blank
                                                                                                                                                                        if (isset($privacy_policy) && $privacy_policy != '') {
                                                                                                                                                                            echo $privacy_policy;
                                                                                                                                                                        } else {
                                                                                                                                                                            echo strval(PRIVACY_POLICY);
                                                                                                                                                                        } ?>'
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                                    echo 'disabled';
                                                                                                                                                                                } ?>><?php
                                                                                                                                                                                        //if privacy_policy is set and not blank
                                                                                                                                                                                        if (isset($privacy_policy) && $privacy_policy != '') {
                                                                                                                                                                                            echo $privacy_policy;
                                                                                                                                                                                        } ?></textarea>
                                            <small id="privacy-policy-help" class="form-text text-muted">Create the
                                                Privacy
                                                Policy style, using the WYSIWYG Editor</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <label for="terms_conditions">
                                    <h3>Terms and Conditions</h3>
                                </label>
                                <div id="terms_conditions">
                                    <?php //get the terms and conditions from the database
                                                $terms_conditions = $APP->getSetting('terms_conditions');
                                                ?>
                                    <div class="form-group">
                                        <div class="form-row">
                                            <label for="terms-conditions">Terms and Conditions Page Content</label>
                                            <textarea class="form-control wysiwyg-editor" id="terms-conditions"
                                                name="terms_conditions" rows="15"
                                                placeholder='<?php
                                                                                                                                                                            //if terms_conditions is set and not blank
                                                                                                                                                                            if (isset($terms_conditions) && $terms_conditions != '') {
                                                                                                                                                                                echo $terms_conditions;
                                                                                                                                                                            } else {
                                                                                                                                                                                echo strval(TERMS_CONDITIONS);
                                                                                                                                                                            } ?>'
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                                        echo 'disabled';
                                                                                                                                                                                    } ?>><?php
                                                                                                                                                                                            //if terms_conditions is set and not blank
                                                                                                                                                                                            if (isset($terms_conditions) && $terms_conditions != '') {
                                                                                                                                                                                                echo $terms_conditions;
                                                                                                                                                                                            } ?></textarea>
                                            <small id="terms-conditions-help" class="form-text text-muted">Create
                                                the
                                                Terms
                                                and Conditions style, using the WYSIWYG Editor</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <label for="hotjar_settings">
                                    <h3>Hotjar Tracking</h3>
                                </label>
                                <div id="hotjar_settings">
                                    <?php //get the hotjar tracking settings
                                                $enableHotjar = $APP->getSetting('hotjar_enable');
                                                $hotjarSiteId = $APP->getSetting('hotjar_siteid');
                                                $hotjarVersion = $APP->getSetting('hotjar_version');
                                                ?>
                                    <div class="form-group">
                                        <div class="form-row">
                                            <label for="hotjar-enable">Enable Hotjar Tracking</label>
                                            <div class="form-check" id="hotjar-enable-container">
                                                <input class="form-check" type="checkbox" value="" id="hotjar-enable"
                                                    name="hotjar_enable"
                                                    <?php if (isset($enableHotjar) && $enableHotjar != '') {
                                                                                                                                                                                    if ($enableHotjar = 'true') {
                                                                                                                                                                                        echo 'checked';
                                                                                                                                                                                    }
                                                                                                                                                                                } ?>
                                                    <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                                            echo ' disabled';
                                                                                                                                                                                        } ?>>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label for="hotjar-siteid">Hotjar Site ID</label>
                                            <input type="text" class="form-control" id="hotjar-siteid" name="hotjar_siteid"
                                                placeholder="<?php
                                                                                                                                                                                    //if hotjarSiteId is set and not blank
                                                                                                                                                                                    if (isset($hotjarSiteId) && $hotjarSiteId != '') {
                                                                                                                                                                                        echo $hotjarSiteId;
                                                                                                                                                                                    } else {
                                                                                                                                                                                        echo 'Hotjar Site ID';
                                                                                                                                                                                    } ?>"
                                                <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                                                                            echo 'disabled';
                                                                                                                                                                                        } ?>
                                                value="<?php
                                                                                                                                                                                        //if hotjarSiteId is set and not blank
                                                                                                                                                                                        if (isset($hotjarSiteId) && $hotjarSiteId != '') {
                                                                                                                                                                                            echo $hotjarSiteId;
                                                                                                                                                                                        } else {
                                                                                                                                                                                            echo '';
                                                                                                                                                                                        } ?>">
                                                                                                                                                                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <button id="submit-btn" name="btnSubmit" type="submit" class="btn btn-primary"
                                        <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                echo 'disabled';
                                                                                                                            } ?>>Save</button>
                                    <button id="reset-btn" name="btnReset" type="reset" class="btn btn-secondary"
                                        <?php if (!$hasUpdateSettingsPermission) {
                                                                                                                                echo 'disabled';
                                                                                                                            } ?>>Reset</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<script>
//hide or show the file upload input based on the user selection
$(document).ready(function() {
    $('#appLogoSelect').change(function() {
        if ($(this).val() == '0') {
            $('#appLogoUpload').prop('disabled', false).show();
            $('#appLogoUpload').prop('hidden', false).show();
        } else {
            $('#appLogoUpload').prop('disabled', true).hide();
            $('#appLogoUpload').prop('hidden', true).hide();
        }
    });
    $('#companyLogoSelect').change(function() {
        if ($(this).val() == '0') {
            $('#companyLogoUpload').prop('disabled', false).show();
            $('#companyLogoUpload').prop('hidden', false).show();
        } else {
            $('#companyLogoUpload').prop('disabled', true).hide();
            $('#companyLogoUpload').prop('hidden', true).hide();
        }
    });
});

//add an event listener to show/hide the smtp settings
document.addEventListener("DOMContentLoaded", function() {
    //get the mailer type
    var mailerType = document.getElementById('mail-mailer').value;

    //if the mailer type is not smtp
    if (mailerType != 'smtp') {
        //hide the smtp settings
        document.getElementById('mail-host-row').style.display = 'none';
        document.getElementById('mail-port-row').style.display = 'none';
        document.getElementById('mail-auth-req-row').style.display = 'none';
        document.getElementById('mail-from-address-row').style.display = 'none';
        document.getElementById('mail-from-name-row').style.display = 'none';
    }
    //add an event listener to the mailer type select
    document.getElementById('mail-mailer').addEventListener('change', function() {
        //get the mailer type
        var mailerType = document.getElementById('mail-mailer').value;
        //if the mailer type is not smtp
        if (mailerType != 'smtp') {
            //hide the smtp settings
            document.getElementById('mail-host-row').style.display = 'none';
            document.getElementById('mail-port-row').style.display = 'none';
            document.getElementById('mail-auth-req-row').style.display = 'none';
            document.getElementById('mail-from-address-row').style.display = 'none';
            document.getElementById('mail-from-name-row').style.display = 'none';
        } else {
            //show the smtp settings
            document.getElementById('mail-host-row').style.display = 'block';
            document.getElementById('mail-port-row').style.display = 'block';
            document.getElementById('mail-auth-req-row').style.display = 'block';
            document.getElementById('mail-from-address-row').style.display = 'block';
            document.getElementById('mail-from-name-row').style.display = 'block';
        }
    });

    //get the mailer auth req
    var mailerAuthReq = document.getElementById('mail-auth-req').checked;

    //if the mailer auth req is not checked
    if (!mailerAuthReq) {
        //hide the mailer username and password
        document.getElementById('mail-username-row').style.display = 'none';
        document.getElementById('mail-password-row').style.display = 'none';
        document.getElementById('mail-encryption-row').style.display = 'none';
    }

    //add an event listener to the mailer auth req checkbox
    document.getElementById('mail-auth-req').addEventListener('change', function() {
        //get the mailer auth req
        var mailerAuthReq = document.getElementById('mail-auth-req').checked;
        //if the mailer auth req is not checked
        if (!mailerAuthReq) {
            //hide the mailer username and password
            document.getElementById('mail-username-row').style.display = 'none';
            document.getElementById('mail-password-row').style.display = 'none';
            document.getElementById('mail-encryption-row').style.display = 'none';
        } else {
            //show the mailer username and password
            document.getElementById('mail-username-row').style.display = 'block';
            document.getElementById('mail-password-row').style.display = 'block';
            document.getElementById('mail-encryption-row').style.display = 'block';
        }
    });
});
</script>
<?php }
    }
} ?>
