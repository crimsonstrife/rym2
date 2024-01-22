<?php

/**
 * Media Class file for the College Recruitment Application
 * Contains all the functions for the Media Class and handles media functions.
 *
 * Author: Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date: 10/16/2023
 *
 * @package RYM2
 * Filename: media.inc.php
 * @version 1.0.0
 * @requires PHP 7.2.5+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

/* Include the base application config file */

require_once(__DIR__ . '/../../config/app.php');
/* Include the database config file */
require_once(__DIR__ . '/../../config/database.php');
// include the database connector file
require_once(BASEPATH . '/includes/connector.inc.php');

/**
 * Media Class
 * Contains all the functions for the Media Class and handles all media functions.
 *
 * @package RYM2
 * @version 1.0.0
 */
class Media
{
    //Reference to the database
    private $mysqli;

    //Instantiate the database connection
    public function __construct()
    {
        $this->mysqli = connectToDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    }

    //Close the database connection when the object is destroyed
    public function __destruct()
    {
        closeDatabaseConnection($this->mysqli);
    }

    /**
     * Get Media By ID
     * Returns the media object for the given media id
     *
     * @param int $media_id The media id
     *
     * @return array The media object
     */
    public function getMediaByID(int $media_id): array
    {
        //placeholder for the media object
        $media = [];

        //query to get the media by id
        $query = "SELECT * FROM media WHERE id = ?";

        //prepare the query
        $stmt = $this->mysqli->prepare($query);

        //bind the media id to the query
        $stmt->bind_param('i', $media_id);

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //if there is a result, get the media object
        if ($result->num_rows > 0) {
            $media = $result->fetch_assoc();
        }

        //return the media object
        return $media;
    }

    /**
     * Get Media By Type
     * Returns the media object for the given media type
     *
     * @param string $media_type The media type
     *
     * @return array The media object
     */
    public function getMediaByType(string $media_type): array
    {
        //placeholder for the media object
        $media = [];

        //query to get the media by type
        $query = "SELECT * FROM media WHERE filetype = ?";

        //prepare the query
        $stmt = $this->mysqli->prepare($query);

        //bind the media type to the query
        $stmt->bind_param('s', $media_type);

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //if there is a result, get the media object
        if ($result->num_rows > 0) {
            $media = $result->fetch_assoc();
        }

        //return the media object(s)
        return $media;
    }

    /**
     * Get the file name for the media
     * Returns the file name for the given media id
     *
     * @param int $media_id The media id
     *
     * @return string The file name
     */
    public function getMediaFileName(int $media_id): string
    {
        //placeholder for the file name
        $filename = '';

        //query to get the file name for the media
        $query = "SELECT filename FROM media WHERE id = ?";

        //prepare the query
        $stmt = $this->mysqli->prepare($query);

        //bind the media id to the query
        $stmt->bind_param('i', $media_id);

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //if there is a result, get the file name
        if ($result->num_rows > 0) {
            $filename = $result->fetch_assoc()['filename'];
        }

        //return the file name
        return $filename;
    }

    /**
     * Update the file name for the media
     * Updates the file name for the given media id
     *
     * @param int $media_id The media id
     * @param string $file_name The file name
     *
     * @return bool True if the file name was updated, false if it was not
     */
    public function updateMediaFileName(int $media_id, string $file_name = NULL): bool
    {
        //placeholder for the result
        $result = false;

        //if the new file name is null, do not update the file name
        if ($file_name === NULL) {
            return $result;
        }

        //query to update the file name for the media
        $query = "UPDATE media SET filename = ? WHERE id = ?";

        //prepare the query
        $stmt = $this->mysqli->prepare($query);

        //bind the file name and media id to the query
        $stmt->bind_param('si', $file_name, $media_id);

        //execute the query
        $stmt->execute();

        //if the query was successful, set the result to true
        if ($stmt->affected_rows > 0) {
            $result = true;
        }

        //return the result
        return $result;
    }

    /**
     * Get the file type for the media
     * Returns the file type for the given media id
     *
     * @param int $media_id The media id
     *
     * @return string The file type
     */
    public function getMediaFileType(int $media_id): string
    {
        //placeholder for the file type
        $filetype = '';

        //query to get the file type for the media
        $query = "SELECT filetype FROM media WHERE id = ?";

        //prepare the query
        $stmt = $this->mysqli->prepare($query);

        //bind the media id to the query
        $stmt->bind_param('i', $media_id);

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //if there is a result, get the file type
        if ($result->num_rows > 0) {
            $filetype = $result->fetch_assoc()['filetype'];
        }

        //return the file type
        return $filetype;
    }

    /**
     * Update the file type for the media
     * Updates the file type for the given media id
     *
     * @param int $media_id The media id
     * @param string $file_type The file type
     *
     * @return bool True if the file type was updated, false if it was not
     */
    public function updateMediaFileType(int $media_id, string $file_type = NULL): bool
    {
        //placeholder for the result
        $result = false;

        //if the new file type is null, do not update the file type
        if ($file_type === NULL) {
            return $result;
        }

        //query to update the file type for the media
        $query = "UPDATE media SET filetype = ? WHERE id = ?";

        //prepare the query
        $stmt = $this->mysqli->prepare($query);

        //bind the file type and media id to the query
        $stmt->bind_param('si', $file_type, $media_id);

        //execute the query
        $stmt->execute();

        //if the query was successful, set the result to true
        if ($stmt->affected_rows > 0) {
            $result = true;
        }

        //return the result
        return $result;
    }

    /**
     * Update the file size for the media
     * Updates the file size for the given media id
     *
     * @param int $media_id The media id
     * @param int $file_size The file size
     *
     * @return bool True if the file size was updated, false if it was not
     */
    public function updateMediaFileSize(int $media_id, int $file_size = NULL): bool
    {
        //placeholder for the result
        $result = false;

        //if the new file size is null, do not update the file size
        if ($file_size === NULL) {
            return $result;
        }

        //query to update the file size for the media
        $query = "UPDATE media SET filesize = ? WHERE id = ?";

        //prepare the query
        $stmt = $this->mysqli->prepare($query);

        //bind the file size and media id to the query
        $stmt->bind_param('ii', $file_size, $media_id);

        //execute the query
        $stmt->execute();

        //if the query was successful, set the result to true
        if ($stmt->affected_rows > 0) {
            $result = true;
        }

        //return the result
        return $result;
    }

    /**
     * Get the file size for the media
     * Returns the file size for the given media id, if the database shows it as null, it checks the file size on the server and updates the database before returning the file size.
     * This should compensate for any files that were uploaded before the file size was being recorded.
     *
     * @param int $media_id The media id
     *
     * @return int The file size
     */
    public function getMediaFileSize(int $media_id): int
    {
        //placeholder for the file size
        $filesize = 0;

        //placeholder for the result if the size needs to be updated
        $updatedResult = false;

        //query to get the file size for the media
        $query = "SELECT filesize FROM media WHERE id = ?";

        //prepare the query
        $stmt = $this->mysqli->prepare($query);

        //bind the media id to the query
        $stmt->bind_param('i', $media_id);

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //if there is a result, get the file size
        if ($result->num_rows > 0) {
            $filesize = $result->fetch_assoc()['filesize'];
        }

        //if the file size is null, get the file size from the server and update the database
        if ($filesize === NULL) {
            $filename = $this->getMediaFileName($media_id);
            $filesize = filesize(getUploadPath() . $filename);
            $updatedResult = $this->updateMediaFileSize($media_id, $filesize);
        }

        //if the file size was updated, return the updated file size
        if ($updatedResult) {
            return $filesize;
        } else {
            //set the file size to 0 if it was not updated
            $filesize = 0;
            //return the file size
            return $filesize;
        }
    }

    /**
     * Get the full file path for the media
     * Returns the file path for the given media id
     *
     * @param int $media_id The media id
     *
     * @return string The file path
     */
    public function getMediaFilePath(int $media_id): string
    {
        //placeholder for the file path
        $filepath = '';

        //placeholder for the file name
        $filename = '';

        //query to get the file name for the media
        $query = "SELECT filename FROM media WHERE id = ?";

        //prepare the query
        $stmt = $this->mysqli->prepare($query);

        //bind the media id to the query
        $stmt->bind_param('i', $media_id);

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //if there is a result, get the file name
        if ($result->num_rows > 0) {
            $filename = $result->fetch_assoc()['filename'];
            // get the file path for the media
            $filepath = getUploadPath() . $filename;
        }

        //return the file path
        return $filepath;
    }

    /**
     * Add media to the database
     * Adds the media object to the database and returns the media id if successful
     *
     * @param string $filename The file name
     * @param string $filetype The file type
     * @param int $filesize The file size
     * @param int $user_id The user id for the user uploading the media
     *
     * @return int The media id
     */
    public function addMedia(string $filename, string $filetype, int $filesize, int $user_id = NULL): int
    {
        //placeholder for the media id
        $media_id = 0;

        //get current date and time
        $date = date('Y-m-d H:i:s');

        //query to add the media to the database
        $query = "INSERT INTO media (filename, filetype, filesize, created_at, created_by, updated_at, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?)";

        //prepare the query
        $stmt = $this->mysqli->prepare($query);

        //bind the file name, file type, file size, date, and user id to the query
        $stmt->bind_param('ssisisi', $filename, $filetype, $filesize, $date, $user_id, $date, $user_id);

        //execute the query
        $stmt->execute();

        //if the query was successful, get the media id
        if ($stmt->affected_rows > 0) {
            $media_id = intval($stmt->insert_id);
        }

        //return the media id
        return $media_id;
    }

    /**
     * Get Media ID By File Name
     * Returns the media id for the given file name
     *
     * @param string $filename The file name
     *
     * @return int The media id
     */
    public function getMediaIDByFileName(string $filename): int
    {
        //placeholder for the media id
        $media_id = 0;

        //query to get the media id by file name
        $query = "SELECT id FROM media WHERE filename = ?";

        //prepare the query
        $stmt = $this->mysqli->prepare($query);

        //bind the file name to the query
        $stmt->bind_param('s', $filename);

        //execute the query
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //if there is a result, get the media id
        if ($result->num_rows > 0) {
            $media_id = intval($result->fetch_assoc()['id']);
        }

        //return the media id
        return $media_id;
    }

    /**
     * Upload Media
     * Uploads the media file to the server and adds the media object to the database, returns the media id if successful
     *
     * @param array $file The file array from the $_FILES superglobal
     * @param int $user_id The user id for the user uploading the media
     */
    public function uploadMedia(array $file, int $user_id = NULL): int
    {
        //placeholder for the media id
        $media_id = 0;

        //local path to the upload directory
        $upload_path = dirname(__DIR__, 2) . '/public/content/uploads/';

        //placeholder boolean for if there is an error
        $error = false;

        //placeholder for the file error
        $file_error = 0;

        //placeholder for the upload error
        $upload_error = '';

        //if the file array is empty, return 0
        if (empty($file)) {
            return $media_id;
        }

        //get the file name
        $filename = $file['name'];

        //get the file type
        $filetype = $file['type'];

        //get the file size
        $filesize = $file['size'];

        //get the file path
        $filepath = $file['tmp_name'];

        //get the error if there is one
        $file_error = intval($file['error']);

        //get the file extension
        $file_extension = pathinfo($filename, PATHINFO_EXTENSION);

        //if there hasn't been an error yet, check the file type
        if ($error === false) {
            //check if the file is an allowed file type, compare file type to the allowed file types
            if (!in_array($filetype, $this->getValidFileTypes())) {
                //set the upload error
                $upload_error = 'Invalid file type';
                //set the error boolean to true
                $error = true;
            }
        }

        //if there hasn't been an error yet, check the file size
        if ($error === false) {
            //check if the file is an allowed file size, compare file size to the allowed file size
            if ($filesize > MAX_FILE_SIZE) {
                //set the upload error
                $upload_error = 'File size is too large';
                //set the error boolean to true
                $error = true;
            }
        }

        //if there hasn't been an error yet, check the file error
        if ($error === false) {
            //check if the file has an error
            if ($file_error > 0) {
                //set the upload error
                $upload_error = 'File upload error';
                //set the error boolean to true
                $error = true;
            }
        }

        //if there hasn't been an error yet, check if the file exists already
        if ($error === false) {
            //check if the file exists
            if (file_exists($upload_path . $filename)) {
                //set the upload error
                $upload_error = 'File already exists';
                //set the error boolean to true
                $error = true;

                //if the file exists, get the id of the media object with the same file name
                $media_id = $this->getMediaIDByFileName($filename);
            }
        }

        //if there hasn't been an error yet, move the file to the upload directory
        if ($error === false) {
            //move the file to the upload directory
            if (move_uploaded_file($filepath, $upload_path . $filename)) {
                //add the media object to the database
                $media_id = $this->addMedia($filename, $file_extension, intval($filesize), $user_id);

                //if the media id is 0, set the upload error
                if ($media_id === 0) {
                    $error = true;
                    $upload_error = 'Error adding media to the database';
                } else {
                    $error = false;
                    //set the upload error to empty
                    $upload_error = '';
                }
            } else {
                //set the upload error
                $upload_error = 'Error moving file to upload directory';
                //set the error boolean to true
                $error = true;
            }
        }

        //if there is an error, log the activity
        if ($error === true) {
            $activity = new Activity();
            $activity->logActivity($user_id, "Upload Error", "Error Uploading Media: " . $upload_error . " - " . $filename);
        }

        //return the media id
        return $media_id;
    }

    /**
     * Get Valid File Types
     * Returns an array of valid file types for media uploads
     *
     * @return array The valid file types
     */
    public function getValidFileTypes(): array
    {
        //get the allowed file types from the contants file
        $valid_file_types = ALLOWED_FILE_TYPES;

        //return the valid file types
        return $valid_file_types;
    }
}
