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
    protected $mysqli;

    //Instantiate the database connection
    public function __construct()
    {
        try {
            $this->mysqli = connectToDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
        } catch (Exception $e) {
            //log the error
            error_log('Error: ' . $e->getMessage());
        }
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

        //prepare the statement
        $stmt = $this->mysqli->prepare($sql);

        //execute the statement
        $stmt->execute();

        //get the result
        $result = $stmt->get_result();

        //placeholder for the media array
        $media = array();

        //If the query returns a result
        if ($result) {
            //Loop through the result and add each row to the media array
            while ($row = $result->fetch_assoc()) {
                $media[] = $row;
            }
        }

        //return the media array
        return $media;
    }

    /**
     * Get Media By ID
     * Returns the media object for the given media id
     *
     * @param int $mediaID The media id
     *
     * @return array The media object
     */
    public function getMediaByID(int $mediaID): array
    {
        //placeholder for the media object
        $media = [];

        //query to get the media by id
        $query = "SELECT * FROM media WHERE id = ?";

        //prepare the query
        $stmt = $this->mysqli->prepare($query);

        //bind the media id to the query
        $stmt->bind_param('i', $mediaID);

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
     * Get the file name for the media
     * Returns the file name for the given media id
     *
     * @param int $mediaID The media id
     *
     * @return string The file name
     */
    public function getMediaFileName(int $mediaID): string
    {
        //placeholder for the file name
        $filename = '';

        //query to get the file name for the media
        $query = "SELECT filename FROM media WHERE id = ?";

        //prepare the query
        $stmt = $this->mysqli->prepare($query);

        //bind the media id to the query
        $stmt->bind_param('i', $mediaID);

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
     * @param int $mediaID The media id
     * @param string $fileName The file name
     *
     * @return bool True if the file name was updated, false if it was not
     */
    private function updateMediaFileName(int $mediaID, string $fileName = NULL): bool
    {
        //placeholder for the result
        $result = false;

        //if the new file name is null, do not update the file name
        if ($fileName === NULL) {
            return $result;
        }

        //query to update the file name for the media
        $query = "UPDATE media SET filename = ? WHERE id = ?";

        //prepare the query
        $stmt = $this->mysqli->prepare($query);

        //bind the file name and media id to the query
        $stmt->bind_param('si', $fileName, $mediaID);

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
     * @param int $mediaID The media id
     *
     * @return string The file type
     */
    public function getMediaFileType(int $mediaID): string
    {
        //placeholder for the file type
        $filetype = '';

        //query to get the file type for the media
        $query = "SELECT filetype FROM media WHERE id = ?";

        //prepare the query
        $stmt = $this->mysqli->prepare($query);

        //bind the media id to the query
        $stmt->bind_param('i', $mediaID);

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
     * Update the file size for the media
     * Updates the file size for the given media id
     *
     * @param int $mediaID The media id
     * @param int $fileSize The file size
     *
     * @return bool True if the file size was updated, false if it was not
     */
    private function updateMediaFileSize(int $mediaID, int $fileSize = NULL): bool
    {
        //placeholder for the result
        $result = false;

        //if the new file size is null, do not update the file size
        if ($fileSize === NULL) {
            return $result;
        }

        //query to update the file size for the media
        $query = "UPDATE media SET filesize = ? WHERE id = ?";

        //prepare the query
        $stmt = $this->mysqli->prepare($query);

        //bind the file size and media id to the query
        $stmt->bind_param('ii', $fileSize, $mediaID);

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
     * Get the full file path for the media
     * Returns the file path for the given media id
     *
     * @param int $mediaID The media id
     *
     * @return string The file path
     */
    public function getMediaFilePath(int $mediaID): string
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
        $stmt->bind_param('i', $mediaID);

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
     * @param int $userID The user id for the user uploading the media
     *
     * @return int The media id
     */
    public function addMedia(string $filename, string $filetype, int $filesize, int $userID = NULL): int
    {
        //placeholder for the media id
        $mediaID = 0;

        //get current date and time
        $date = date('Y-m-d H:i:s');

        //query to add the media to the database
        $query = "INSERT INTO media (filename, filetype, filesize, created_at, created_by, updated_at, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?)";

        //prepare the query
        $stmt = $this->mysqli->prepare($query);

        //bind the file name, file type, file size, date, and user id to the query
        $stmt->bind_param('ssisisi', $filename, $filetype, $filesize, $date, $userID, $date, $userID);

        //execute the query
        $stmt->execute();

        //if the query was successful, get the media id
        if ($stmt->affected_rows > 0) {
            $mediaID = intval($stmt->insert_id);
        }

        //return the media id
        return $mediaID;
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
        $mediaID = 0;

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
            $mediaID = intval($result->fetch_assoc()['id']);
        }

        //return the media id
        return $mediaID;
    }

    /**
     * Upload Media
     * Uploads the media file to the server and adds the media object to the database, returns the media id if successful
     *
     * @param array $file The file array from the $_FILES superglobal
     * @param int $userID The user id for the user uploading the media
     */
    public function uploadMedia(array $file, int $userID = null): int
    {
        //include the activity class
        $activity = new Activity();

        // Placeholder for the media id
        $mediaID = 0;

        // Local path to the upload directory
        $uploadPath = dirname(__DIR__, 2) . '/public/content/uploads/';

        // Validate the file
        $uploadError = validateFile($file);
        if ($uploadError !== '') {
            $activity->logActivity($userID, 'Upload Error', 'Error Uploading Media: ' . $uploadError . ' - ' . $file['name']);
            return $mediaID;
        }

        // Move the file to the upload directory
        if (moveFile($file, $uploadPath)) {
            $mediaID = $this->addMediaToDatabase($file, $userID);
            if ($mediaID === 0) {
                $uploadError = 'Error adding media to the database';
                $activity->logActivity($userID, 'Upload Error', 'Error Uploading Media: ' . $uploadError . ' - ' . $file['name']);
            }
            $uploadError = 'Error moving file to upload directory';
            $activity->logActivity($userID, 'Upload Error', 'Error Uploading Media: ' . $uploadError . ' - ' . $file['name']);
        }

        // Log the activity
        $activity->logActivity($userID, 'Upload', 'Media Uploaded: ' . $file['name']);

        // Generate thumbnails for the new file
        $this->generateMediaThumbs($mediaID);

        return $mediaID;
    }

    private function addMediaToDatabase(array $file, int $userID = null): int
    {
        // Get the file name, type, size, and extension
        $filename = $file['name'];
        $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
        $filesize = $file['size'];

        // Add the media object to the database
        return $this->addMedia($filename, $fileExtension, intval($filesize), $userID);
    }

    /**
     * Get media usage by media id
     * Returns the ids of the school or event that is using the media
     *
     * @param int $mediaID The media id
     *
     * @return array The ids of the school or event that is using the media
     */
    public function getMediaUsage(int $mediaID): array
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

        //include the event media class
        $eventMedia = new EventMedia();

        //get the ids of the schools that are using the media
        $schoolsArray = $school->getSchoolsByMediaID($mediaID);

        //get the ids of the events that are using the media
        $eventsArray = $eventMedia->getEventsByMediaID($mediaID);

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
     * @param int $mediaID The media id
     * @param string $newFilename The new file name
     * @param int $userID The user id for the user updating the media
     *
     * @return bool True if the media file was renamed, false if it was not
     */
    public function renameMedia(int $mediaID, string $newFilename, int $userID = NULL): bool
    {
        //placeholder for the result
        $result = false;

        //local path to the upload directory
        $uploadPath = dirname(__DIR__, 2) . '/public/content/uploads/';

        //placeholder boolean for if there is an error
        $error = false;

        //placeholder for the upload error
        $uploadError = '';

        //get the current file name
        $currentFilename= $this->getMediaFileName($mediaID);

        //sanitize the new file name
        $newFilename = htmlspecialchars($newFilename);

        //if the file name is different, rename the file
        if ($newFilename !== $currentFilename) {
            //if the file exists, rename the file
            if (file_exists($uploadPath . $currentFilename)) {
                //create a backup of the current file
                if (copy($uploadPath . $currentFilename, $uploadPath . 'backup_' . $currentFilename)) {
                    //rename the new file
                    if (rename($uploadPath . $currentFilename, $uploadPath . $newFilename)) {
                        //update the file name in the database
                        $this->updateMediaFileName($mediaID, $newFilename);
                    } else {
                        //set the upload error
                        $uploadError = 'Error renaming file';
                        //set the error boolean to true
                        $error = true;
                    }
                } else {
                    //set the upload error
                    $uploadError = 'Error creating backup file';
                    //set the error boolean to true
                    $error = true;
                }
            } else {
                //set the upload error
                $uploadError = 'File does not exist';
                //set the error boolean to true
                $error = true;
            }

            //if there is an error, log the activity
            if ($error === true) {
                $activity = new Activity();
                $activity->logActivity($userID, "Upload Error", "Error Renaming Media File: " . $uploadError . " - " . $currentFilename. " to " . $newFilename);

                //set the result to false
                $result = false;
            } else {
                //delete the backup file
                unlink($uploadPath . 'backup_' . $currentFilename);

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
     * @param int $mediaID The media id
     * @param array $file The file array from the $_FILES superglobal
     * @param int $userID The user id for the user updating the media
     *
     * @return bool True if the media file was updated, false if it was not
     */
    public function updateMediaFile(int $mediaID, array $file, int $userID = NULL): bool
    {
        //placeholder for the result
        $result = false;

        //local path to the upload directory
        $uploadPath = dirname(__DIR__, 2) . '/public/content/uploads/';

        //placeholder boolean for if there is an error
        $error = false;

        //placeholder for the upload error
        $uploadError = '';

        //if the file array is empty, return false
        if (empty($file)) {
            return $result;
        }

        //get the file name
        $filename = $file['name'];

        //get the file size
        $filesize = $file['size'];

        //get the current file name
        $currentFilename= $this->getMediaFileName($mediaID);

        //if the file name is different, rename the file
        if ($filename !== $currentFilename) {
            //if the file exists, rename the file
            if (file_exists($uploadPath . $filename)) {
                //create a backup of the current file
                if (copy($uploadPath . $currentFilename, $uploadPath . 'backup_' . $currentFilename)) {
                    //rename the new file
                    if (rename($uploadPath . $filename, $uploadPath . $currentFilename)) {
                        //update the file name in the database
                        $this->updateMediaFileName($mediaID, $currentFilename);
                        //update the file size in the database
                        $this->updateMediaFileSize($mediaID, $filesize);
                    } else {
                        //set the upload error
                        $uploadError = 'Error renaming file';
                        //set the error boolean to true
                        $error = true;
                    }
                } else {
                    //set the upload error
                    $uploadError = 'Error creating backup file';
                    //set the error boolean to true
                    $error = true;
                }
            } else {
                //set the upload error
                $uploadError = 'File does not exist';
                //set the error boolean to true
                $error = true;
            }

            //if there is an error, log the activity
            if ($error === true) {
                $activity = new Activity();
                $activity->logActivity($userID, "Upload Error", "Error Updating Media File: " . $uploadError . " - " . $currentFilename. " from " . $filename);

                //set the result to false
                $result = false;
            } else {
                //delete the backup file
                unlink($uploadPath . 'backup_' . $currentFilename);

                //set the result to true
                $result = true;
            }
        }

        //generate thumbnails for the new file
        $this->generateMediaThumbs($mediaID);

        //return the result
        return $result;
    }

    /**
     * Delete Media
     * Deletes the media file from the server and removes the media object from the database
     *
     * @param int $mediaID The media id
     * @param int $userID The user id for the user deleting the media
     *
     * @return bool True if the media was deleted, false if it was not
     */
    public function deleteMedia(int $mediaID, int $userID = NULL): bool
    {
        //placeholder for the result
        $result = false;

        //local path to the upload directory
        $uploadPath = dirname(__DIR__, 2) . '/public/content/uploads/';

        //placeholder boolean for if there is an error
        $error = false;

        //placeholder for the delete status
        $deleted = false;

        //placeholder for the error message
        $errorMessage = '';

        //get the file name
        $filename = $this->getMediaFileName($mediaID);

        //if the file exists, delete the file
        if (file_exists($uploadPath . $filename)) {
            //delete the file
            if (unlink($uploadPath . $filename)) {
                $deleted = true;
            } else {
                //set the error boolean to true
                $error = true;
                //set the error message
                $errorMessage = 'Error deleting file';
            }
        } else {
            //set the error boolean to true
            $error = true;
            //set the error message
            $errorMessage = 'File does not exist';
        }

        //delete thumbnails if they exist
        if (file_exists($uploadPath . 'thumb_600_' . $filename)) {
            unlink($uploadPath . 'thumb_600_' . $filename);
        }
        if (file_exists($uploadPath . 'thumb_200_' . $filename)) {
            unlink($uploadPath . 'thumb_200_' . $filename);
        }

        //if the file was deleted, delete the media object from the database
        if ($deleted === true) {
            //query to delete the media from the database
            $query = "DELETE FROM media WHERE id = ?";

            //prepare the query
            $stmt = $this->mysqli->prepare($query);

            //bind the media id to the query
            $stmt->bind_param('i', $mediaID);

            //execute the query
            $stmt->execute();

            //if the query was successful, set the result to true
            if ($stmt->affected_rows > 0) {
                $result = true;
            } else {
                //set the error boolean to true
                $error = true;
                //set the error message
                $errorMessage = 'Error deleting media from database';
                //set the result to false
                $result = false;
            }
        }

        //if there is an error, log the activity
        if ($error === true) {
            $activity = new Activity();
            $activity->logActivity($userID, "Delete Error", "Error Deleting Media: " . $errorMessage . " - " . $filename);
        }

        //return the result
        return $result;
    }

    /**
     * Generate Thumbnails
     * Generates thumbnails for the given media id, one for the list page and one for the modal/detail page.
     * Requires Imagick to be installed on the server.
     *
     * @param int $mediaID The media id
     *
     * @return void
     */
    public function generateMediaThumbs(int $mediaID)
    {
        //max width and height for the thumbnails
        $modalMaxWidth = 600;
        $modalMaxHeight = 300;
        $listMaxWidth = 200;
        $listMaxHeight = 200;

        //get the file name and type
        $filename = $this->getMediaFileName($mediaID);
        $filetype = $this->getMediaFileType($mediaID);

        //base path to the upload directory
        $uploadPath = dirname(__DIR__, 2) . '/public/content/uploads/';

        //file path to the original file
        $originalFile = $uploadPath . $filename;

        //create an Imagick object
        $image = new Imagick();

        //read the original file
        $image->readImage($originalFile);

        //if the file type is SVG, do nothing
        if ($filetype != 'svg') {
            //resize and compress the image for modal thumbnail
            $modalThumbnail = $this->resizeAndCompressImage($image, $modalMaxWidth, $modalMaxHeight, $filetype);
            if ($modalThumbnail !== null) {
                $modalThumbnail->writeImage($uploadPath . 'thumb_600_' . $filename);
                $modalThumbnail->destroy();
            }

            //read the original file, again
            $image->readImage($originalFile);

            //resize and compress the image for list thumbnail
            $listThumbnail = $this->resizeAndCompressImage($image, $listMaxWidth, $listMaxHeight, $filetype);
            if ($listThumbnail !== null) {
                $listThumbnail->writeImage($uploadPath . 'thumb_200_' . $filename);
                $listThumbnail->destroy();
            }
        }

        //destroy the image object
        $image->destroy();
    }

    private function resizeAndCompressImage(Imagick $image, int $maxWidth, int $maxHeight, string $filetype): ?Imagick
    {
        //If the image fits within the max width and height, do nothing
        if ($image->getImageWidth() <= $maxWidth && $image->getImageHeight() <= $maxHeight) {
            return null;
        }

        //Resize to whatever size is larger, width or height
        if ($image->getImageWidth() >= $image->getImageHeight()) {
            //resize the image to the max width
            $image->resizeImage($maxWidth, $maxHeight, Imagick::FILTER_LANCZOS, 1, true);
        } else {
            //resize the image to the max height
            $image->resizeImage($maxHeight, $maxWidth, Imagick::FILTER_LANCZOS, 1, true);
        }

        //set the compression based on the file type
        switch ($filetype) {
            case 'jpg':
            case 'jpeg':
                $compressionType = Imagick::COMPRESSION_JPEG;
                break;
            case 'png':
                $compressionType = Imagick::COMPRESSION_ZIP;
                break;
            case 'gif':
                $compressionType = Imagick::COMPRESSION_LZW;
                break;
            default:
                $compressionType = Imagick::COMPRESSION_JPEG;
                break;
        }

        //set the compression type
        $image->setImageCompression($compressionType);

        //set the compression quality (1 = lowest, 100 = highest)
        $image->setImageCompressionQuality(80);

        //strip the metadata from the image
        $image->stripImage();

        return $image;
    }

    /**
     * Get Media Thumbnail
     *
     * @param int $mediaID The media id
     * @param string $size The size of the thumbnail (list or modal)
     *
     * @return string The filename for the thumbnail, or the original file if the thumbnail does not exist
     */
    public function getMediaThumbnail(int $mediaID, string $size = 'list'): string
    {
        //placeholder for the filename
        $filename = '';

        //the upload path
        $uploadPath = dirname(__DIR__, 2) . '/public/content/uploads/';

        //get the file name
        $originalFilename = $this->getMediaFileName($mediaID);

        //if the size is modal, get the modal thumbnail
        switch ($size) {
            case 'modal':
                //if the modal thumbnail exists, get the modal thumbnail
                if (file_exists($uploadPath . 'thumb_600_' . $originalFilename)) {
                    $filename = 'thumb_600_' . $originalFilename;
                } else {
                    //if the modal thumbnail does not exist, get the original file
                    $filename = $originalFilename;
                }
                //return the filename
                return $filename;
            case 'list':
                //if the list thumbnail exists, get the list thumbnail
                if (file_exists($uploadPath . 'thumb_200_' . $originalFilename)) {
                    $filename = 'thumb_200_' . $originalFilename;
                } else {
                    //if the list thumbnail does not exist, get the original file
                    $filename = $originalFilename;
                }
                //return the filename
                return $filename;
            default:
                //if the size is not modal or list, get the original file
                $filename = $originalFilename;

                //return the filename
                return $filename;
        }
    }
}
