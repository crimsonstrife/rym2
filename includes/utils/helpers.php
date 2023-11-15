<?php
require_once(__DIR__ . '/../constants.php');

function formatDate(string $date, string $format = DATE_FORMAT): string
{
    $date = new DateTime($date);
    return $date->format($format);
};

function clearCookies()
{
    //clear the cookies
    setcookie("user_id", "");
    setcookie("user_name", "");
    setcookie("user_password", "");
    setcookie("user_password_selector", "");
};

function setCookies(int $user_id, string $username, string $password_hash, string $selector_hash, string $expiry_date)
{
    //convert the expiry date to a unix timestamp
    $expiry_date = strtotime($expiry_date);
    $expiry_date = mktime(date("H", $expiry_date), date("i", $expiry_date), date("s", $expiry_date), date("m", $expiry_date), date("d", $expiry_date), date("Y", $expiry_date));

    //set the cookies
    setcookie("user_id", $user_id, $expiry_date, "/");
    setcookie("user_name", $username, $expiry_date, "/");
    setcookie("user_password", $password_hash, $expiry_date, "/");
    setcookie("user_password_selector", $selector_hash, $expiry_date, "/");
};

function randomizeEncryption($minimum, $maximum)
{
    //number range
    $random_range = $maximum - $minimum;

    //ensure the range is greater than 1
    if ($random_range < 1) {
        return $minimum;
    }

    //determine the log base 2 of the range
    $log = ceil(log($random_range, 2));

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
    } while ($random >= $random_range);
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
    exit;
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

    //move back to beginning of file
    fseek($output, 0);

    //set the headers to download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $fileName);

    //output remaining data to the file
    fpassthru($output);

    //close the output stream
    fclose($output);
}
