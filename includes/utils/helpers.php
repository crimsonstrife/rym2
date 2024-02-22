<?php
require_once(__DIR__ . '/../constants.php');
function formatDate(string $date, string $format = DATE_FORMAT): string
{
    $date = new DateTime($date);
    return $date->format($format);
};

function formatTime(string $time, string $format = TIME_FORMAT): string
{
    $time = new DateTime($time);
    return $time->format($format);
};

/**
 * Format an email address to a clickable link
 * @param string $email - the email address to be formatted
 * @return string
 */
function formatEmail(string $email): string
{
    //remove any spaces
    $email = str_replace(" ", "", $email);
    //return the formatted email address
    return "<a href='mailto:$email'>$email</a>";
};

/**
 * Format a phone number to a clickable link
 * @param string $phone - the phone number to be formatted
 * @return string
 */
function formatPhone(string $phone): string
{
    //remove any spaces
    $phone = str_replace(" ", "", $phone);
    //remove any dashes
    $phone = str_replace("-", "", $phone);
    //remove any parentheses
    $phone = str_replace("(", "", $phone);
    //remove any periods
    $phone = str_replace(".", "", $phone);

    //if the phone number is 10 digits, format it as (xxx) xxx-xxxx
    if (strlen($phone) == 10) {
        $phoneString = "(" . substr($phone, 0, 3) . ") " . substr($phone, 3, 3) . "-" . substr($phone, 6);
    }
    //otherwise, format it as xxx-xxx-xxxx
    $phoneString = substr($phone, 0, 3) . "-" . substr($phone, 3, 3) . "-" . substr($phone, 6);

    //return the formatted phone number
    return "<a href='tel:$phone'>$phoneString</a>";
};

/**
 * Format a street address
 * @param string $address - the street address to be formatted
 * @param string $city - the city
 * @param string $state - the state
 * @param string $zip - the zip code
 * @return string
 */
function formatAddress(string $address, string $city, string $state, string $zip): string
{
    //format the address
    $formattedAddress = $address . " " . $city . ", " . $state . " " . $zip;
    //return the address
    return $formattedAddress;
};

function clearCookies()
{
    //clear the cookies
    setcookie("user_id", "");
    setcookie("user_name", "");
    setcookie("user_password", "");
    setcookie("user_password_selector", "");
};

function setCookies(int $userID, string $username, string $passwordHash, string $selectorHash, string $expiryDate)
{
    //convert the expiry date to a unix timestamp
    $expiryDate = strtotime($expiryDate);
    $expiryDate = mktime(date("H", $expiryDate), date("i", $expiryDate), date("s", $expiryDate), date("m", $expiryDate), date("d", $expiryDate), date("Y", $expiryDate));

    //set the cookies
    setcookie("user_id", $userID, $expiryDate, "/");
    setcookie("user_name", $username, $expiryDate, "/");
    setcookie("user_password", $passwordHash, $expiryDate, "/");
    setcookie("user_password_selector", $selectorHash, $expiryDate, "/");
};

function randomizeEncryption($minimum, $maximum)
{
    //number range
    $randomRange = $maximum - $minimum;

    //ensure the range is greater than 1
    if ($randomRange < 1) {
        return $minimum;
    }

    //determine the log base 2 of the range
    $log = ceil(log($randomRange, 2));

    //determine the number of bits
    $bits = (int) $log + 1;

    //determine the number of bytes
    $bytes = (int) ($bits / 8) + 1;

    //filter bits
    $filter = (int) (1 << $bits) - 1;

    do {
        //generate random bytes
        $random = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        //filter random bytes
        $random = $random & $filter;
    } while ($random >= $randomRange);
    return $minimum + $random;
};

function createToken($length)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"; //valid characters in the token
    $maximum = strlen($length) - 1;

    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[randomizeEncryption(0, strlen($maximum))];
    }
    return $token;
}

function performRedirect(string $filePath)
{
    //redirect to the specified file
    header("Location: " . APP_URL . $filePath);
}

function flattenArray(array $array): array
{
    $return = array();
    array_walk_recursive($array, function ($keyA) use (&$return) {
        $return[] = $keyA;
    });
    return $return;
}

/**
 * Export the data from a sql query to a csv file
 * @param array $data - the data to be exported
 * @param string $dataType - the type of data to be exported used for naming the file
 *
 * @return void
 */
function exportData(array $data, string $dataType)
{
    //set the file name
    $fileName = $dataType . "_export-" . date("Y-m-d_H-i-s") . ".csv";
    //delimiter
    $delimiter = ",";

    //open the output stream
    $output = fopen('php://output', 'w');

    //set the column headers
    fputcsv($output, array_keys($data[0]), $delimiter);

    //loop through the data and add it to the csv file
    foreach ($data as $row) {
        fputcsv($output, $row, $delimiter);
    }

    //set the headers to download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $fileName);

    //output remaining data to the file
    fpassthru($output);

    //close the output stream
    fclose($output);
}

/**
 * Get a random hex color
 * @return string
 */
function getRandomHexColor(): string
{
    //characters to use in the hex color
    $chars = '0123456789ABCDEF';
    $color = '#';

    //generate a random hex color
    for ($i = 0; $i < 6; $i++) {
        $color .= $chars[randomizeEncryption(0, strlen($chars))];
    }

    //return the hex color
    return $color;
}

/**
 * Format filesize
 * convert bytes to KB, MB, GB, etc.
 *
 * @param int $bytes - the size in bytes
 * @param int $decimals - the number of decimal places to round to
 *
 * @return string
 */
function formatFilesize(int $bytes, int $decimals = 2): string
{
    //placeholder for the formatted size
    $formattedSize = 0;

    //array of size units
    $size = array('B', 'KB', 'MB', 'GB', 'TB'); //realistically, shouldn't need anything larger than MB for web images, but just in case set up to TB

    //determine the size unit
    $factor = floor((strlen($bytes) - 1) / 3);

    //format the size
    $formattedSize = sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . $size[$factor];

    //return the formatted size
    return $formattedSize;
}

/**
 * Get image dimensions
 * @param string $imagePath - the path to the image
 *
 * @return array
 */
function getImageDimensions(string $imagePath): array
{
    //get the image dimensions
    $imageDimensions = getimagesize($imagePath);

    if ($imageDimensions === false) {
        //if the image dimensions could not be determined, set the width and height to 0
        $imageDimensions = array(0, 0);
    }

    //return the image dimensions
    return $imageDimensions;
}

/**
 * Get the degree program for a student
 *
 * @param int $studentDegreeID //id from the students table
 * @param int $studentMajorID //id from the students table
 * @return string
 */
function getDegreeProgram(int $studentDegreeID, int $studentMajorID): string
{
    //instance of the Degree class
    $degreeClass = new Degree();
    //initialize an empty string to store the degree program
    $degreeProgram = "";
    //get the degree level and major
    $major = $degreeClass->getMajorNameById($studentMajorID);
    $degree = $degreeClass->getGradeNameById($studentDegreeID);
    //format the string
    $degreeProgram = $degree . ", " . $major;

    //return the string
    return $degreeProgram;
}

/**
 * Get Valid File Types
 * Returns an array of valid file types for media uploads
 *
 * @return array The valid file types
 */
function getValidFileTypes(): array
{
    //get the allowed file types from the contants file
    $validTypes = ALLOWED_FILE_TYPES;

    //return the valid file types
    return $validTypes;
}

function validateFile(array $file): string
{
    // Placeholder for the upload error
    $uploadError = '';

    // Get the file name, type, size, and path
    $filename = $file['name'];
    $filetype = $file['type'];
    $filesize = $file['size'];

    // Check if the file is an allowed file type
    if (!in_array($filetype, getValidFileTypes())) {
        $uploadError = 'Invalid file type';
    }

    // Check if the file size is too large
    if ($filesize > MAX_FILE_SIZE) {
        $uploadError = 'File size is too large';
    }

    // Check if the file has an error
    if (intval($file['error']) > 0) {
        $uploadError = 'File upload error';
    }

    // Check if the file already exists
    $uploadPath = dirname(__DIR__, 2) . '/public/content/uploads/';
    if (file_exists($uploadPath . $filename)) {
        $uploadError = 'File already exists';
    }

    return $uploadError;
}

function moveFile(array $file, string $uploadPath): bool
{
    // Get the file name and path
    $filename = $file['name'];
    $filepath = $file['tmp_name'];

    // Move the file to the upload directory
    return move_uploaded_file($filepath, $uploadPath . $filename);
}

//redirect user
function redirectUser($location)
{
    header("location: " . $location);
}
