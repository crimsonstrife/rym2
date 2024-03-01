
# C868: RYM2 - Capstone : College Recruitment Application

A PHP-based web application for handling student information for college recruitment efforts for a business.  Allows students to register interest on a front-end portal. Users for the business can log into an admin dashboard to view or interact with submitted data.

Disclaimer: This project is strictly for educational purposes only.  Any similarities to real-world applications, products, or services are purely coincidental.  The author is not responsible for any misuse of the information provided in this project. The author is not responsible for any damages or losses caused by this project. Any data included in this project is purely fictional and is not intended to represent any real-world data.

## Status

<div align="center">

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](./LICENSE)
![PHP Composer - Build](https://github.com/crimsonstrife/rym2/actions/workflows/php.yml/badge.svg)
![Node.js - Build](https://github.com/crimsonstrife/rym2/actions/workflows/node.js.yml/badge.svg)

</div>

## Table of Contents

<details>

<summary>Table of Contents</summary>

- [C868: RYM2 - Capstone : College Recruitment Application](#c868-rym2---capstone--college-recruitment-application)
  - [Status](#status)
  - [Table of Contents](#table-of-contents)
  - [Authors](#authors)
  - [About This Project](#about-this-project)
  - [Built With](#built-with)
    - [Front-End](#front-end)
    - [Back-End](#back-end)
    - [Development Tools](#development-tools)
  - [Tech Stack](#tech-stack)
    - [Live Environment](#live-environment)
    - [Development Environment](#development-environment)
  - [Features](#features)
  - [Installation](#installation)
    - [Recommended Local Environments](#recommended-local-environments)
  - [Environment Variables](#environment-variables)
  - [Usage](#usage)
    - [Example](#example)
  - [Support](#support)
  - [Acknowledgements](#acknowledgements)
  - [References](#references)
  - [Appendix](#appendix)
  - [License](#license)

</details>

## Authors

- Patrick Barnhardt - [pbarnh1@wgu.edu](mailto:pbarnh1@wgu.edu) [@crimsonstrife](https://www.github.com/crimsonstrife)

## About This Project

This project aims to provide a web-based application for a business to handle student information for college recruitment efforts.  The application stores student information in a database, and allows users to view, edit, or delete student information.  It also allows users from the business to add things like schools, programs of study, and other information to the database that the students can select from when registering for a job or internship.  A base database schema is provided in the project and should be deployed to a MySQL database during installation.

## Built With

<div align="center">

### Front-End

![Bootstrap Badge](https://img.shields.io/badge/Bootstrap-7952B3?logo=bootstrap&logoColor=fff&style=for-the-badge)
![Font Awesome Badge](https://img.shields.io/badge/Font%20Awesome-528DD7?logo=fontawesome&logoColor=fff&style=for-the-badge)
![jQuery Badge](https://img.shields.io/badge/jQuery-0769AD?logo=jquery&logoColor=fff&style=for-the-badge)
![Chart.js Badge](https://img.shields.io/badge/Chart.js-FF6384?logo=chartdotjs&logoColor=fff&style=for-the-badge)
![OpenStreetMap Badge](https://img.shields.io/badge/OpenStreetMap-7EBC6F?logo=openstreetmap&logoColor=fff&style=for-the-badge)
![Leaflet Badge](https://img.shields.io/badge/Leaflet-199900?logo=leaflet&logoColor=fff&style=for-the-badge)
![HTML5 Badge](https://img.shields.io/badge/HTML5-E34F26?logo=html5&logoColor=fff&style=for-the-badge)
![CSS3 Badge](https://img.shields.io/badge/CSS3-1572B6?logo=css3&logoColor=fff&style=for-the-badge)
![JavaScript Badge](https://img.shields.io/badge/JavaScript-F7DF1E?logo=javascript&logoColor=000&style=for-the-badge)

<div align="left">

- <b>Languages: </b> HTML5, CSS3, JavaScript
- <b>Frameworks: </b> Bootstrap ^5.3.2, jQuery ^3.7, Chart.js ^4.4, Leaflet ^1.9.4/Leaflet Geosearch ^3.10.2, Simple-DataTables ^9.0.0, Popper.js ^2.11.8, iro.js ^5.5.2 , Select2 ^4.0
- <b>Libraries: </b> Font Awesome ^6.4

</div>

### Back-End

![PHP Badge](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=fff&style=for-the-badge)
![Composer Badge](https://img.shields.io/badge/Composer-885630?logo=composer&logoColor=fff&style=for-the-badge)
![npm Badge](https://img.shields.io/badge/npm-CB3837?logo=npm&logoColor=fff&style=for-the-badge)
![.ENV Badge](https://img.shields.io/badge/.ENV-ECD53F?logo=dotenv&logoColor=000&style=for-the-badge)

<div align="left">

- <b>Languages: </b> PHP ^8.1.2
- <b>Frameworks: </b> Composer ^2.6.6, npm ^10.3.0
- <b>Libraries: </b> .ENV ^5.5, PHPMailer ^6.8, PHP-QRCode ^5.0

</div>

</div>

<div align="center">

### Development Tools

![Visual Studio Code Badge](https://img.shields.io/badge/Visual%20Studio%20Code-007ACC?logo=visualstudiocode&logoColor=fff&style=for-the-badge)
![Windows Badge](https://img.shields.io/badge/Windows-0078D4?logo=windows&logoColor=fff&style=for-the-badge)
![macOS Badge](https://img.shields.io/badge/macOS-000?logo=macos&logoColor=fff&style=for-the-badge)
![Git Badge](https://img.shields.io/badge/Git-F05032?logo=git&logoColor=fff&style=for-the-badge)
![Composer Badge](https://img.shields.io/badge/Composer-885630?logo=composer&logoColor=fff&style=for-the-badge)
![npm Badge](https://img.shields.io/badge/npm-CB3837?logo=npm&logoColor=fff&style=for-the-badge)

<div align="left">

- <b>IDE: </b> Visual Studio Code ^1.85.1
- <b>Operating Systems: </b> Windows 11 x64, macOS Sonoma 14.2.1 (M1 2020)
- <b>Frameworks: </b> Composer ^2.6.6, npm ^10.3.0
- <b>Tools: </b> Git ^2.43.0

</div>

</div>

## Tech Stack

<div align="center">

### Live Environment

![PHP Badge](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=fff&style=for-the-badge)
![phpMyAdmin Badge](https://img.shields.io/badge/phpMyAdmin-6C78AF?logo=phpmyadmin&logoColor=fff&style=for-the-badge)
![Apache Badge](https://img.shields.io/badge/Apache-D22128?logo=apache&logoColor=fff&style=for-the-badge)
![MySQL Badge](https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=fff&style=for-the-badge)
![Linux Badge](https://img.shields.io/badge/Linux-FCC624?logo=linux&logoColor=000&style=for-the-badge)

</div>

<div align="center">

### Development Environment

![macOS Badge](https://img.shields.io/badge/macOS-000?logo=macos&logoColor=fff&style=for-the-badge)
![MAMP Badge](https://img.shields.io/badge/MAMP-02749C?logo=mamp&logoColor=fff&style=for-the-badge)
![Apache Badge](https://img.shields.io/badge/Apache-D22128?logo=apache&logoColor=fff&style=for-the-badge)
![PHP Badge](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=fff&style=for-the-badge)
![MySQL Badge](https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=fff&style=for-the-badge)
![phpMyAdmin Badge](https://img.shields.io/badge/phpMyAdmin-6C78AF?logo=phpmyadmin&logoColor=fff&style=for-the-badge)

<div align="left">

- <b>Operating Systems: </b> macOS Sonoma 14.2.1 (M1 2020)
- <b>Hypervisor: </b> MAMP PRO ^6.8.1
- <b>Web Server: </b> Apache ^2.4.54
- <b>Database: </b> MySQL ^5.7.39
- <b>Database Management: </b> phpMyAdmin ^5.2.0
- <b>Languages: </b> PHP ^8.1.2

</div>

</div>

## Features

- User Accounts
  - Admins can create, edit, and delete user accounts
  - Users can log in and out
  - Users can reset their password
  - Users can update their profile information
  - Users can view their own data
  - Users can view other user's data if they have the correct permissions
  - Users can view and interact with student data if they have the correct permissions
  - Users can view and interact with school data if they have the correct permissions
  - Users can view and interact with event data if they have the correct permissions
  - Users can view and interact with reports data if they have the correct permissions
  - Users can view and interact with settings data if they have the correct permissions

- Student Data
  - Students can register from the front-end portal, and their data is stored in the database (no login required)
  - Students submit their name, email, phone number, address, city, state, zip code, school, graduation year, degree, job type, subject matter interest, and program of study
  - Students can receive a confirmation email after registering
  - Admins can view, edit, and delete student data
  - Admins can contact students via email from the admin dashboard

- School Data
  - Admins can add, edit, and delete schools
  - Schools have a name, address, city, state, zip code, and branding
  - Schools can be associated with students
  - Schools can be associated with events

- Event Data
  - Admins can add, edit, and delete events
  - Events have a name, date, time, location(school), description, and branding
  - Events can be associated with schools
  - Events can be associated with students
  - Events can be associated with reports

- Reports Data
  - Admins can view, edit, and delete reports
  - Reports store historical data even when relevant objects are deleted
  - Admins can generate new, updated copies of reports

- Education Data
  - Admins can add, edit, and delete programs of study, and degrees
  - Programs of study have a name
  - Programs of study can be associated with students
  - Degrees have a name
  - Degrees can be associated with students
  - Students can select from a list of programs of study and degrees when registering
  - Students can submit their own programs of study when registering

- Subject Matter Data
  - Admins can add, edit, and delete subject matter
  - Subject matter has a name
  - Subject matter can be associated with students

- Settings Data
  - Admins can view, edit, and delete settings

- Mail
  - Admins can send mail to students from the admin dashboard
  - Students receive a confirmation email after registering
  - Users can reset their password via email

## Installation

To install this project, you will need to have an Apache web server with PHP and MySQL installed.  You will also need to have Composer and npm installed on your development machine.  You will also need to have an SMTP mail server set up to send mail from the application, however the rest of the application will function without this.

You will also need to have the following PHP extensions installed and enabled in your PHP configuration:

- openssl
- pdo_mysql
- gd
- mbstring
- mysqli
- imagick

### Recommended Local Environments

- Windows
  - MAMP or MAMP PRO for Windows or XAMPP - Apache, MySQL, PHP, phpMyAdmin comes pre-installed, and you can enable the necessary PHP extensions in the MAMP PRO or XAMPP control panel. You can also use the included phpMyAdmin to create the database for the application.  You may need to adjust the default PHP version.
  - Git for Windows - GitLFS is also recommended for cloning the repository
  - Composer
  - npm

- macOS
  - MAMP or MAMP PRO - Apache, MySQL, PHP, phpMyAdmin comes pre-installed, and you can enable the necessary PHP extensions in the MAMP PRO control panel. You can also use the included phpMyAdmin to create the database for the application.  You may need to adjust the default PHP version.
  - Git for macOS - GitLFS is also recommended for cloning the repository
  - Composer
  - npm

There are two ways to get the project files.  You can either clone the repository from GitHub, or you can download the files as a zip file from GitHub.  If you clone the repository, you will need to have Git installed on your development machine, and GitLFS installed and enabled in your Git configuration.  You'll want to run the following command to clone the repository:

```bash
git clone https://github.com/crimsonstrife/rym2.git rym2 && cd rym2

# If you have GitLFS installed and enabled
git lfs install
git lfs pull
```

If you download the files as a zip file, you will need to extract the files to a directory on your development machine, the LFS files should be included in the zip file.

Once you have the files, you will need to run the following commands to install the project dependencies, these should be run from the root directory of the project, where the composer.json and package.json files are located:

```bash
composer install
composer update

# Composer should install the PHPMailer and PHP-QRCode libraries, and update the composer.lock file. It should also run the npm install command for you as part of the scripts in the composer.json file, but you can run it manually if you need to.
npm install

```

You will also need to create a MySQL database for the application to use.  You can use the provided database schema in the project, or you can create your own.  The database schema is located in the `temp` directory of the project, and is named `talentflow.sql`.  You can import this file into your MySQL database using phpMyAdmin, another editor, or the command line.  If you're running the newer MySQL 8.0, you may need to instead use the `talentflow8.sql` file, which has the correct syntax for MySQL 8.0.  You may also wait and leave the database empty, and the application will create the tables for you when you run the application for the first time, it should be able to determine which file to use on it's own.

You will also need to create a .env file in the root directory of the project, and add the environment variables listed below in the ['Environment Variables' section](#environment-variables) to the file.  You can use the .env.example file included in the project as a template, and rename it to .env.  You will need to add your own values for the environment variables, and you should keep the file secure, as it may contain sensitive information.  The included .htaccess file should prevent direct access to the .env file, but you should still ensure you have proper file permissions set on the file in a production environment.

Open the url for the application, on first run the setup page should appear, and you should see if there are any notable errors with your configuration.  If there are no errors, you can click the "Install" button to install the application.  If there are errors, you will need to correct them before you can install the application.  Once the tables are created, you should be able to log in with the default admin account, and you can then create new users, and change the default admin password.

You may also manually create the tables in the database using the provided schema, and then simply create an empty php file in the root directory of the project named ready.php, and the application should be able to determine that the tables are already created and skip the setup page.

## Environment Variables

To run this project, you will need to add the following environment variables to your .env file, or edit the .env.example file included and rename it to ".env".

`APP_NAME` - The name of the application

`APP_ENV` - The environment the application is running in (local, development, production, etc.)

`APP_DEBUG` - Whether or not the application is in debug mode

`APP_URL` - The URL of the application

`COMPANY_NAME` - The name of the company

`COMPANY_ADDRESS` - The contact address of the company

`COMPANY_CITY` - The contact city of the company

`COMPANY_STATE` - The contact state of the company

`COMPANY_ZIP` - The contact zip code of the company

`COMPANY_PHONE` - The contact phone number of the company

`COMPANY_URL` - The website URL of the company

`CONTACT_EMAIL` - The contact email address for the application administrator

`LOG_LEVEL` - The level of logging for the application (debug, info, notice, warning, error, critical, alert, emergency) //TODO: Currently not implemented

`DB_CONNECTION` - The type of database connection (mysql), currently only mysql is supported

`DB_HOST` - The host of the database

`DB_PORT` - The port of the database

`DB_DATABASE` - The name of the database

`DB_USERNAME` - The username for the database

`DB_PASSWORD` - The password for the database

To use the mail functions of the project, you will also need the following environment variables in the .env file.

`MAIL_MAILER` - The mailer to use (smtp, sendmail, mail, etc.), currently only smtp is supported

`MAIL_HOST` - The host of the mail server

`MAIL_PORT` - The port of the mail server

`MAIL_AUTH_REQ` - Whether or not the mail server requires authentication (true, false)

`MAIL_USERNAME` - The username for the mail server

`MAIL_PASSWORD` - The password for the mail server

`MAIL_ENCRYPTION` - The encryption type for the mail server (tls, ssl), must have openssl installed and enabled in PHP

`MAIL_FROM_ADDRESS` - The email address to send mail from, must be a valid email address for the mail server, and if authentication is required then the username must be able to send mail from this address

`MAIL_FROM_NAME` - The name to send mail as

You will also need to add the following environment variables for the encryption of the mail password, or if you leave them blank, the app should generate them for you.
These can be random strings of any length, but should be kept secret.

`MAILER_PASSWORD_ENCRYPTION_KEY`

`MAILER_PASSWORD_ENCRYPTION_IV`

## Usage

Refer to the [Installation](#installation) section for information on how to install the project.

Once the project is installed, you can use the application by opening the URL for the application in a web browser.  You will be presented with the landing/student registration page, and you can register as a student, or find the Admin Login link in the footer, and log in as an admin.  You can then use the admin dashboard to view, edit, or delete student data, and add, edit, or delete other data in the application.

You can also use the admin dashboard to send mail to students, and generate reports.  You can also view and edit settings for the application, and view the activity log for the application.

### Example

- [TalentFlow Demo](https://capstone.hostedprojects.net/)

## Support

For school-related support, email [pbarnh1@wgu.edu](mailto:pbarnh1@wgu.edu) or [my personal email for professional correspondence](contact@patrickbarnhardt.info).

## Acknowledgements

<details>

<summary>Expand for List</summary>

- [Font Awesome](https://fontawesome.com/)
- [Bootstrap](https://getbootstrap.com/)
- [Chart.js](https://www.chartjs.org/)
- [Leaflet](https://leafletjs.com/)
- [OpenStreetMap](https://www.openstreetmap.org/)
- [jQuery](https://jquery.com/)
- [PHP](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [npm](https://www.npmjs.com/)
- [Visual Studio Code](https://code.visualstudio.com/)
- [GitHub](https://www.github.com/)
- [Git](https://git-scm.com/)
- [MAMP](https://www.mamp.info/)
- [phpMyAdmin](https://www.phpmyadmin.net/)
- [Apache](https://httpd.apache.org/)
- [MySQL](https://www.mysql.com/)
- [PHPMailer](https://github.com/PHPMailer/PHPMailer)
- [PHP-QRCode](https://github.com/chillerlan/php-qrcode)
- [Simple-DataTables](https://github.com/fiduswriter/simple-datatables)
- [Popper.js](https://popper.js.org/)
- [iro.js](https://iro.js.org/)
- [Select2](https://select2.org/)

</details>

## References

- Method for preventing direct access to specific PHP files, but still allow the files to be included [(UnkwnTech 2009, Amal Murali 2013)](https://stackoverflow.com/questions/409496/prevent-direct-access-to-a-php-include-file/409515#409515)
- PHPMailer Documentation [(Marcus Bointon 2021)](https://phpmailer.github.io/PHPMailer/)
- PHP-QRCode Manual [(@chillerlan 2024)](https://php-qrcode.readthedocs.io/en/main/)
- Leaflet Documentation [(Volodymyr Agafonkin 2010-2023)](https://leafletjs.com/reference.html)
- Simple-DataTables Documentation [(Fidus Writer 2023)](https://fiduswriter.github.io/simple-datatables/documentation/)
- Simple-DataTables Demos [(Fidus Writer 2023)](https://fiduswriter.github.io/simple-datatables/demos/)

## Appendix

Any additional information goes here

## License

Distributed under the MIT License. See `LICENSE` for more information.
