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
     * Get All Media
     * Returns all media objects
     *
     * @return array The media objects
     */
    public function getMedia(): array
    {
        //SQL statement to get all media
        $sql = "SELECT * FROM media";
        //Query the database
        $result = $this->mysqli->query($sql);
        //If the query returns a result
        if ($result) {
            //Loop through the result and add each row to the media array
            while ($row = $result->fetch_assoc()) {
                $media[] = $row;
            }
            //Return the media array
            return $media;
        } else {
            //If the query fails, return an empty array
            return array();
        }
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

        //local path to the upload directory
        $upload_path = dirname(__DIR__, 2) . '/public/content/uploads/';

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
            $filesize = filesize($upload_path . $filename);

            if ($filesize > 0) {
                $updatedResult = $this->updateMediaFileSize($media_id, $filesize);
            }
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
        } else {
            //log the activity
            $activity = new Activity();
            $activity->logActivity($user_id, "Upload Success", "Media Uploaded: " . $filename);
        }

        //generate thumbnails for the new file
        $this->generateMediaThumbs($media_id);

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

    /**
     * Get media usage by media id
     * Returns the ids of the school or event that is using the media
     *
     * @param int $media_id The media id
     *
     * @return array The ids of the school or event that is using the media
     */
    public function getMediaUsage(int $media_id): array
    {
        //placeholder array for the media usage
        $mediaUsage = array(
            "schools" => array(),
            "events" => array()
        );

        //include the school class
        $school = new School();

        //include the event class
        $event = new Event();

        //get the ids of the schools that are using the media
        $schoolsArray = $school->getSchoolsByMediaID($media_id);

        //get the ids of the events that are using the media
        $eventsArray = $event->getEventsByMediaID($media_id);

        //if there are schools using the media, add the school ids to the media usage array
        if (count($schoolsArray) > 0) {
            foreach ($schoolsArray as $schoolObject) {
                //add the school id to the media usage array
                $mediaUsage["schools"][] = $schoolObject;
            }
        }

        //if there are events using the media, add the event ids to the media usage array
        if (count($eventsArray) > 0) {
            foreach ($eventsArray as $event) {
                $mediaUsage["events"][] = $event;
            }
        }

        //return the media usage array
        return $mediaUsage;
    }

    /**
     * Rename Media File
     * Renames the media file on the server and updates the media object in the database
     *
     * @param int $media_id The media id
     * @param string $new_filename The new file name
     * @param int $user_id The user id for the user updating the media
     *
     * @return bool True if the media file was renamed, false if it was not
     */
    public function renameMedia(int $media_id, string $new_filename, int $user_id = NULL): bool
    {
        //placeholder for the result
        $result = false;

        //local path to the upload directory
        $upload_path = dirname(__DIR__, 2) . '/public/content/uploads/';

        //placeholder boolean for if there is an error
        $error = false;

        //placeholder for the upload error
        $upload_error = '';

        //get the current file name
        $current_filename = $this->getMediaFileName($media_id);

        //if the file name is different, rename the file
        if ($new_filename !== $current_filename) {
            //if the file exists, rename the file
            if (file_exists($upload_path . $current_filename)) {
                //create a backup of the current file
                if (copy($upload_path . $current_filename, $upload_path . 'backup_' . $current_filename)) {
                    //rename the new file
                    if (rename($upload_path . $current_filename, $upload_path . $new_filename)) {
                        //update the file name in the database
                        $this->updateMediaFileName($media_id, $new_filename);
                    } else {
                        //set the upload error
                        $upload_error = 'Error renaming file';
                        //set the error boolean to true
                        $error = true;
                    }
                } else {
                    //set the upload error
                    $upload_error = 'Error creating backup file';
                    //set the error boolean to true
                    $error = true;
                }
            } else {
                //set the upload error
                $upload_error = 'File does not exist';
                //set the error boolean to true
                $error = true;
            }

            //if there is an error, log the activity
            if ($error === true) {
                $activity = new Activity();
                $activity->logActivity($user_id, "Upload Error", "Error Renaming Media File: " . $upload_error . " - " . $current_filename . " to " . $new_filename);

                //set the result to false
                $result = false;
            } else {
                //delete the backup file
                unlink($upload_path . 'backup_' . $current_filename);

                //set the result to true
                $result = true;
            }
        } else {
            //do nothing, the file name is the same
            //set the result to true to prevent errors
            $result = true;
        }

        //return the result
        return $result;
    }

    /**
     * Update Media File
     * Replaces the media file on the server (if the name of the new file is different, renames it) and updates the media object in the database
     *
     * @param int $media_id The media id
     * @param array $file The file array from the $_FILES superglobal
     * @param int $user_id The user id for the user updating the media
     *
     * @return bool True if the media file was updated, false if it was not
     */
    public function updateMediaFile(int $media_id, array $file, int $user_id = NULL): bool
    {
        //placeholder for the result
        $result = false;

        //local path to the upload directory
        $upload_path = dirname(__DIR__, 2) . '/public/content/uploads/';

        //placeholder boolean for if there is an error
        $error = false;

        //placeholder for the upload error
        $upload_error = '';

        //if the file array is empty, return false
        if (empty($file)) {
            return $result;
        }

        //get the file name
        $filename = $file['name'];

        //get the file size
        $filesize = $file['size'];

        //get the current file name
        $current_filename = $this->getMediaFileName($media_id);

        //if the file name is different, rename the file
        if ($filename !== $current_filename) {
            //if the file exists, rename the file
            if (file_exists($upload_path . $filename)) {
                //create a backup of the current file
                if (copy($upload_path . $current_filename, $upload_path . 'backup_' . $current_filename)) {
                    //rename the new file
                    if (rename($upload_path . $filename, $upload_path . $current_filename)) {
                        //update the file name in the database
                        $this->updateMediaFileName($media_id, $current_filename);
                        //update the file size in the database
                        $this->updateMediaFileSize($media_id, $filesize);
                    } else {
                        //set the upload error
                        $upload_error = 'Error renaming file';
                        //set the error boolean to true
                        $error = true;
                    }
                } else {
                    //set the upload error
                    $upload_error = 'Error creating backup file';
                    //set the error boolean to true
                    $error = true;
                }
            } else {
                //set the upload error
                $upload_error = 'File does not exist';
                //set the error boolean to true
                $error = true;
            }

            //if there is an error, log the activity
            if ($error === true) {
                $activity = new Activity();
                $activity->logActivity($user_id, "Upload Error", "Error Updating Media File: " . $upload_error . " - " . $current_filename . " from " . $filename);

                //set the result to false
                $result = false;
            } else {
                //delete the backup file
                unlink($upload_path . 'backup_' . $current_filename);

                //set the result to true
                $result = true;
            }
        }

        //generate thumbnails for the new file
        $this->generateMediaThumbs($media_id);

        //return the result
        return $result;
    }

    /**
     * Delete Media
     * Deletes the media file from the server and removes the media object from the database
     *
     * @param int $media_id The media id
     * @param int $user_id The user id for the user deleting the media
     *
     * @return bool True if the media was deleted, false if it was not
     */
    public function deleteMedia(int $media_id, int $user_id = NULL): bool
    {
        //placeholder for the result
        $result = false;

        //local path to the upload directory
        $upload_path = dirname(__DIR__, 2) . '/public/content/uploads/';

        //placeholder boolean for if there is an error
        $error = false;

        //placeholder for the delete status
        $deleted = false;

        //placeholder for the error message
        $error_message = '';

        //get the file name
        $filename = $this->getMediaFileName($media_id);

        //if the file exists, delete the file
        if (file_exists($upload_path . $filename)) {
            //delete the file
            if (unlink($upload_path . $filename)) {
                $deleted = true;
            } else {
                //set the error boolean to true
                $error = true;
                //set the error message
                $error_message = 'Error deleting file';
            }
        } else {
            //set the error boolean to true
            $error = true;
            //set the error message
            $error_message = 'File does not exist';
        }

        //delete thumbnails if they exist
        if (file_exists($upload_path . 'thumb_600_' . $filename)) {
            unlink($upload_path . 'thumb_600_' . $filename);
        }
        if (file_exists($upload_path . 'thumb_200_' . $filename)) {
            unlink($upload_path . 'thumb_200_' . $filename);
        }

        //if the file was deleted, delete the media object from the database
        if ($deleted === true) {
            //query to delete the media from the database
            $query = "DELETE FROM media WHERE id = ?";

            //prepare the query
            $stmt = $this->mysqli->prepare($query);

            //bind the media id to the query
            $stmt->bind_param('i', $media_id);

            //execute the query
            $stmt->execute();

            //if the query was successful, set the result to true
            if ($stmt->affected_rows > 0) {
                $result = true;
            } else {
                //set the error boolean to true
                $error = true;
                //set the error message
                $error_message = 'Error deleting media from database';
                //set the result to false
                $result = false;
            }
        }

        //if there is an error, log the activity
        if ($error === true) {
            $activity = new Activity();
            $activity->logActivity($user_id, "Delete Error", "Error Deleting Media: " . $error_message . " - " . $filename);
        }

        //return the result
        return $result;
    }

    /**
     * Generate Thumbnails
     * Generates thumbnails for the given media id, one for the list page and one for the modal/detail page.
     * Requires Imagick to be installed on the server.
     *
     * @param int $media_id The media id
     *
     * @return void
     */
    public function generateMediaThumbs(int $media_id)
    {
        //max width for the thumbnail
        $modalMaxWidth = 600;
        $modalMaxHeight = 300;
        $listMaxWidth = 200;
        $listMaxHeight = 200;

        //get the file name
        $filename = $this->getMediaFileName($media_id);

        //get the file type
        $filetype = $this->getMediaFileType($media_id);

        //base path to the upload directory
        $upload_path = dirname(__DIR__, 2) . '/public/content/uploads/';

        //create an Imagick object
        $image = new Imagick($upload_path . $filename);

        //if the file type is SVG, do nothing
        if ($filetype != 'svg') {
            //If the image fits within the max width and height, do nothing
            if (
                $image->getImageWidth() <= $modalMaxWidth &&
                $image->getImageHeight() <= $modalMaxHeight
            ) {
                //do nothing
            } else {
                //Resize to whatever size is larger, width or height
                if ($image->getImageWidth() >= $image->getImageHeight()) {
                    //resize the image to the max width
                    $image->resizeImage($modalMaxWidth, $modalMaxHeight, Imagick::FILTER_LANCZOS, 1, true);
                } else {
                    //resize the image to the max height
                    $image->resizeImage($modalMaxHeight, $modalMaxWidth, Imagick::FILTER_LANCZOS, 1, true);
                }

                //set the compression based on the file type
                if ($filetype === 'jpg' || $filetype === 'jpeg') {
                    //set the compression type
                    $image->setImageCompression(Imagick::COMPRESSION_JPEG); //JPEG is lossy

                    //set the compression quality (1 = lowest, 100 = highest)
                    $image->setImageCompressionQuality(80);
                } elseif ($filetype === 'png') {
                    //set the compression type
                    $image->setImageCompression(Imagick::COMPRESSION_ZIP); //Zip is lossless

                    //set the compression quality (1 = lowest, 100 = highest)
                    $image->setImageCompressionQuality(80);
                } elseif ($filetype === 'gif') {
                    //set the compression type
                    $image->setImageCompression(Imagick::COMPRESSION_LZW); //LZW is lossless

                    //set the compression quality (1 = lowest, 100 = highest)
                    $image->setImageCompressionQuality(80);
                } elseif ($filetype === 'svg') {
                    //do nothing, SVG is vector
                } else {
                    //set the compression type
                    $image->setImageCompression(Imagick::COMPRESSION_JPEG); //JPEG is lossy

                    //set the compression quality (1 = lowest, 100 = highest)
                    $image->setImageCompressionQuality(80);
                }

                //strip the metadata from the image
                $image->stripImage();

                //set the new file name
                $new_filename = 'thumb_600_' . $filename;

                //write the image to the server
                $image->writeImage($upload_path . $new_filename);

                //destroy the image object
                $image->destroy();
            }
        }

        /*repeat the process for the list thumbnail*/

        //create an Imagick object
        $image = new Imagick($upload_path . $filename);

        //if the file type is SVG, do nothing
        if ($filetype != 'svg') {
            //If the image fits within the max width and height, do nothing
            if (
                $image->getImageWidth() <= $listMaxWidth &&
                $image->getImageHeight() <= $listMaxHeight
            ) {
                //do nothing
            } else {
                //Resize to whatever size is larger, width or height
                if ($image->getImageWidth() >= $image->getImageHeight()) {
                    //resize the image to the max width
                    $image->resizeImage($listMaxWidth, $listMaxHeight, Imagick::FILTER_LANCZOS, 1, true);
                } else {
                    //resize the image to the max height
                    $image->resizeImage($listMaxHeight, $listMaxWidth, Imagick::FILTER_LANCZOS, 1, true);
                }

                //set the compression based on the file type
                if (
                    $filetype === 'jpg' || $filetype === 'jpeg'
                ) {
                    //set the compression type
                    $image->setImageCompression(Imagick::COMPRESSION_JPEG); //JPEG is lossy

                    //set the compression quality (1 = lowest, 100 = highest)
                    $image->setImageCompressionQuality(80);
                } elseif ($filetype === 'png') {
                    //set the compression type
                    $image->setImageCompression(Imagick::COMPRESSION_ZIP); //Zip is lossless

                    //set the compression quality (1 = lowest, 100 = highest)
                    $image->setImageCompressionQuality(80);
                } elseif ($filetype === 'gif') {
                    //set the compression type
                    $image->setImageCompression(Imagick::COMPRESSION_LZW); //LZW is lossless

                    //set the compression quality (1 = lowest, 100 = highest)
                    $image->setImageCompressionQuality(80);
                } elseif ($filetype === 'svg') {
                    //do nothing, SVG is vector
                } else {
                    //set the compression type
                    $image->setImageCompression(Imagick::COMPRESSION_JPEG); //JPEG is lossy

                    //set the compression quality (1 = lowest, 100 = highest)
                    $image->setImageCompressionQuality(80);
                }

                //strip the metadata from the image
                $image->stripImage();

                //set the new file name
                $new_filename = 'thumb_200_' . $filename;

                //write the image to the server
                $image->writeImage($upload_path . $new_filename);

                //destroy the image object
                $image->destroy();
            }
        }
    }

    /**
     * Get Media Thumbnail
     *
     * @param int $media_id The media id
     * @param string $size The size of the thumbnail (list or modal)
     *
     * @return string The filename for the thumbnail, or the original file if the thumbnail does not exist
     */
    public function getMediaThumbnail(int $media_id, string $size = 'list'): string
    {
        //placeholder for the filename
        $filename = '';

        //the upload path
        $upload_path = dirname(__DIR__, 2) . '/public/content/uploads/';

        //get the file name
        $original_filename = $this->getMediaFileName($media_id);

        //if the size is modal, get the modal thumbnail
        switch ($size) {
            case 'modal':
                //if the modal thumbnail exists, get the modal thumbnail
                if (file_exists($upload_path . 'thumb_600_' . $original_filename)) {
                    $filename = 'thumb_600_' . $original_filename;
                } else {
                    //if the modal thumbnail does not exist, get the original file
                    $filename = $original_filename;
                }
                //return the filename
                return $filename;
            case 'list':
                //if the list thumbnail exists, get the list thumbnail
                if (file_exists($upload_path . 'thumb_200_' . $original_filename)) {
                    $filename = 'thumb_200_' . $original_filename;
                } else {
                    //if the list thumbnail does not exist, get the original file
                    $filename = $original_filename;
                }
                //return the filename
                return $filename;
            default:
                //if the size is not modal or list, get the original file
                $filename = $original_filename;

                //return the filename
                return $filename;
        }
    }
}
