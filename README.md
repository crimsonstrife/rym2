
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
  - [Environment Variables](#environment-variables)
  - [Usage](#usage)
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
- <b>Libraries: </b> .ENV ^5.5, PHPMailer ^6.8, PHP-QRCode ^5.0, PHP Markdown ^2.0

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

-- TODO -- Add features here / had to remove what was here from a previous unrelated project when I reused my template.

## Installation

```bash

```

## Environment Variables

To run this project, you will need to add the following environment variables to your .env file, or edit the .env.example file included and rename it to ".env".

`APP_NAME`

`APP_ENV`

`APP_DEBUG`

`APP_URL`

`LOG_LEVEL`

`DB_CONNECTION`

`DB_HOST`

`DB_PORT`

`DB_DATABASE`

`DB_USERNAME`

`DB_PASSWORD`

To use the mail functions of the project, you will also need the following environment variables in the .env file.

`MAIL_MAILER`

`MAIL_HOST`

`MAIL_PORT`

`MAIL_AUTH_REQ`

`MAIL_USERNAME`

`MAIL_PASSWORD`

`MAIL_ENCRYPTION`

`MAIL_FROM_ADDRESS`

`MAIL_FROM_NAME`

`MAILER_PASSWORD_ENCRYPTION_KEY`

## Usage

TODO: Write usage instructions/examples

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
- [PHP Markdown](https://github.com/michelf/php-markdown)
- [PHPMailer](https://github.com/PHPMailer/PHPMailer)
- [PHP-QRCode](https://github.com/chillerlan/php-qrcode)
- [Simple-DataTables](https://github.com/fiduswriter/simple-datatables)
- [Popper.js](https://popper.js.org/)
- [iro.js](https://iro.js.org/)
- [Select2](https://select2.org/)

</details>

## References

- Method for preventing direct access to specific PHP files, but still allow the files to be included [(UnkwnTech 2009, Amal Murali 2013)](https://stackoverflow.com/questions/409496/prevent-direct-access-to-a-php-include-file/409515#409515)
- PHP Markdown Documentation [(Michel Fortin 2019)](https://michelf.ca/projects/php-markdown/)
- PHPMailer Documentation [(Marcus Bointon 2021)](https://phpmailer.github.io/PHPMailer/)
- PHP-QRCode Manual [(@chillerlan 2024)](https://php-qrcode.readthedocs.io/en/main/)
- Leaflet Documentation [(Volodymyr Agafonkin 2010-2023)](https://leafletjs.com/reference.html)
- Simple-DataTables Documentation [(Fidus Writer 2023)](https://fiduswriter.github.io/simple-datatables/documentation/)
- Simple-DataTables Demos [(Fidus Writer 2023)](https://fiduswriter.github.io/simple-datatables/demos/)

## Appendix

Any additional information goes here

## License

Distributed under the MIT License. See `LICENSE` for more information.
