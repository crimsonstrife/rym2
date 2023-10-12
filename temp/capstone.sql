-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 12, 2023 at 10:55 AM
-- Server version: 5.7.39
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `capstone`
--
CREATE DATABASE IF NOT EXISTS `capstone` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
USE `capstone`;

-- --------------------------------------------------------

--
-- Table structure for table `aoi`
--

DROP TABLE IF EXISTS `aoi`;
CREATE TABLE IF NOT EXISTS `aoi` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `created` (`created_by`),
  KEY `updated` (`updated_by`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_log`
--

DROP TABLE IF EXISTS `contact_log`;
CREATE TABLE IF NOT EXISTS `contact_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `student` bigint(20) DEFAULT NULL,
  `auto` tinyint(1) NOT NULL DEFAULT '1',
  `sender` bigint(20) DEFAULT NULL,
  `send_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subject` varchar(150) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `message` varchar(500) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student` (`student`),
  KEY `sender` (`sender`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `degree_lvl`
--

DROP TABLE IF EXISTS `degree_lvl`;
CREATE TABLE IF NOT EXISTS `degree_lvl` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
CREATE TABLE IF NOT EXISTS `event` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `event_date` date NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `location` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `eventCreatedBy` (`created_by`),
  KEY `eventUpdatedBy` (`updated_by`),
  KEY `eventAtSchool` (`location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `type` enum('FULL','PART','INTERN') COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'INTERN',
  `field` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jobField` (`field`),
  KEY `jobCreatedBy` (`created_by`),
  KEY `jobUpdatedBy` (`updated_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `major`
--

DROP TABLE IF EXISTS `major`;
CREATE TABLE IF NOT EXISTS `major` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_majorToAOIRatioBySchool`
--

DROP TABLE IF EXISTS `report_majorToAOIRatioBySchool`;
CREATE TABLE IF NOT EXISTS `report_majorToAOIRatioBySchool` (
  `major_id` bigint(20) NOT NULL,
  `major_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `aoi_id` bigint(20) NOT NULL,
  `aoi_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `school_id` bigint(20) NOT NULL,
  `school_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `student_count` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_topAOIBySchool`
--

DROP TABLE IF EXISTS `report_topAOIBySchool`;
CREATE TABLE IF NOT EXISTS `report_topAOIBySchool` (
  `aoi_id` bigint(20) NOT NULL,
  `aoi_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `school_id` bigint(20) NOT NULL,
  `school_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `student_count` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_topMajorBySchool`
--

DROP TABLE IF EXISTS `report_topMajorBySchool`;
CREATE TABLE IF NOT EXISTS `report_topMajorBySchool` (
  `major_id` bigint(20) NOT NULL,
  `major_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `school_id` bigint(20) NOT NULL,
  `school_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `student_count` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_jobsByAOI`
--

DROP TABLE IF EXISTS `report_jobsByAOI`;
CREATE TABLE IF NOT EXISTS `report_jobsByAOI` (
  `aoi_id` bigint(20) NOT NULL,
  `aoi_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `job_id` bigint(20) NOT NULL,
  `job_name` varchar(150) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `job_type` enum('FULL','PART','INTERN') COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'INTERN',
  `job_field` bigint(20) NOT NULL,
  `job_field_name` varchar(55) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `job_count` bigint(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permission`
--

DROP TABLE IF EXISTS `role_has_permission`;
CREATE TABLE IF NOT EXISTS `role_has_permission` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) NOT NULL,
  `permission_id` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roleHasPermission` (`role_id`,`permission_id`),
  KEY `permissionID` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `school`
--

DROP TABLE IF EXISTS `school`;
CREATE TABLE IF NOT EXISTS `school` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `address` varchar(80) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `city` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `state` char(2) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `zipcode` varchar(15) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(55) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `last_name` varchar(80) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `event` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `interest` bigint(20) NOT NULL,
  `grade` tinyint(2) NOT NULL,
  `degree` bigint(20) NOT NULL,
  `major` bigint(20) NOT NULL,
  `school` bigint(20) NOT NULL,
  `position` enum('FULL','PART','INTERN') COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'INTERN',
  `graduation` year(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `studentInterest` (`email`,`interest`),
  KEY `areaOfInterest` (`interest`),
  KEY `degree` (`degree`),
  KEY `major` (`major`),
  KEY `school` (`school`),
  KEY `eventAttended` (`event`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Triggers `student`
--
DROP TRIGGER IF EXISTS `updateOnDelete_majorToAOIRatioBySchool`;
DELIMITER $$
CREATE TRIGGER `updateOnDelete_majorToAOIRatioBySchool` AFTER DELETE ON `student` FOR EACH ROW INSERT INTO report_majorToAOIRatioBySchool (major_id, major_name, aoi_id, aoi_name, school_id, school_name, student_count)
    SELECT major.id AS major_id, major.name AS major_name, aoi.id AS aoi_id, aoi.name AS aoi_name, school.id AS school_id, school.name AS school_name, COUNT(student.id) AS student_count
    FROM student
    INNER JOIN major ON student.major = major.id
    INNER JOIN aoi ON student.interest = aoi.id
    INNER JOIN school ON student.school = school.id
    WHERE major.id = OLD.major AND aoi.id = OLD.interest
    GROUP BY major.id, aoi.id, school.id
    ORDER BY school.id, student_count DESC
    ON DUPLICATE KEY UPDATE student_count = student_count - 1
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `updateOnDelete_topAOIBySchool`;
DELIMITER $$
CREATE TRIGGER `updateOnDelete_topAOIBySchool` AFTER DELETE ON `student` FOR EACH ROW INSERT INTO report_topAOIBySchool (aoi_id, aoi_name, school_id, school_name, student_count)
    SELECT aoi.id AS aoi_id, aoi.name AS aoi_name, school.id AS school_id, school.name AS school_name, COUNT(student.id) AS student_count
    FROM student
    INNER JOIN aoi ON student.interest = aoi.id
    INNER JOIN school ON student.school = school.id
    WHERE aoi.id = OLD.interest
    GROUP BY aoi.id, school.id
    ORDER BY school.id, student_count DESC
    ON DUPLICATE KEY UPDATE student_count = student_count - 1
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `updateOnDelete_topMajorBySchool`;
DELIMITER $$
CREATE TRIGGER `updateOnDelete_topMajorBySchool` AFTER DELETE ON `student` FOR EACH ROW INSERT INTO report_topMajorBySchool (major_id, major_name, school_id, school_name, student_count)
    SELECT major.id AS major_id, major.name AS major_name, school.id AS school_id, school.name AS school_name, COUNT(student.id) AS student_count
    FROM student
    INNER JOIN major ON student.major = major.id
    INNER JOIN school ON student.school = school.id
    WHERE major.id = OLD.major
    GROUP BY major.id, school.id
    ORDER BY school.id, student_count DESC
    ON DUPLICATE KEY UPDATE student_count = student_count - 1
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `updateOnDelete_jobsByAOI`;
DELIMITER $$
CREATE TRIGGER `updateOnDelete_jobsByAOI` AFTER DELETE ON `jobs` FOR EACH ROW INSERT INTO report_jobsByAOI (aoi_id, aoi_name, job_id, job_name, job_type, job_field, job_field_name, job_count)
    SELECT aoi.id AS aoi_id, aoi.name AS aoi_name, jobs.id AS job_id, jobs.name AS job_name, jobs.type AS job_type, aoi.id AS job_field, aoi.name AS job_field_name, COUNT(jobs.id) AS job_count
    FROM jobs
    INNER JOIN aoi ON jobs.field = aoi.id
    WHERE aoi.id = OLD.field
    GROUP BY aoi.id, jobs.id
    ORDER BY aoi.id, job_count DESC
    ON DUPLICATE KEY UPDATE job_count = job_count - 1
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `updateOnInsert_majorToAOIRatioBySchool`;
DELIMITER $$
CREATE TRIGGER `updateOnInsert_majorToAOIRatioBySchool` AFTER INSERT ON `student` FOR EACH ROW INSERT INTO report_majorToAOIRatioBySchool (major_id, major_name, aoi_id, aoi_name, school_id, school_name, student_count)
    SELECT major.id AS major_id, major.name AS major_name, aoi.id AS aoi_id, aoi.name AS aoi_name, school.id AS school_id, school.name AS school_name, COUNT(student.id) AS student_count
    FROM student
    INNER JOIN major ON student.major = major.id
    INNER JOIN aoi ON student.interest = aoi.id
    INNER JOIN school ON student.school = school.id
    WHERE major.id = NEW.major AND aoi.id = NEW.interest
    GROUP BY major.id, aoi.id, school.id
    ORDER BY school.id, student_count DESC
    ON DUPLICATE KEY UPDATE student_count = student_count + 1
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `updateOnInsert_topAOIBySchool`;
DELIMITER $$
CREATE TRIGGER `updateOnInsert_topAOIBySchool` AFTER INSERT ON `student` FOR EACH ROW INSERT INTO report_topAOIBySchool (aoi_id, aoi_name, school_id, school_name, student_count)
    SELECT aoi.id AS aoi_id, aoi.name AS aoi_name, school.id AS school_id, school.name AS school_name, COUNT(student.id) AS student_count
    FROM student
    INNER JOIN aoi ON student.interest = aoi.id
    INNER JOIN school ON student.school = school.id
    WHERE aoi.id = NEW.interest
    GROUP BY aoi.id, school.id
    ORDER BY school.id, student_count DESC
    ON DUPLICATE KEY UPDATE student_count = student_count + 1
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `updateOnInsert_topMajorBySchool`;
DELIMITER $$
CREATE TRIGGER `updateOnInsert_topMajorBySchool` AFTER INSERT ON `student` FOR EACH ROW INSERT INTO report_topMajorBySchool (major_id, major_name, school_id, school_name, student_count)
    SELECT major.id AS major_id, major.name AS major_name, school.id AS school_id, school.name AS school_name, COUNT(student.id) AS student_count
    FROM student
    INNER JOIN major ON student.major = major.id
    INNER JOIN school ON student.school = school.id
    WHERE major.id = NEW.major
    GROUP BY major.id, school.id
    ORDER BY school.id, student_count DESC
    ON DUPLICATE KEY UPDATE student_count = student_count + 1
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `updateOnInsert_jobsByAOI`;
DELIMITER $$
CREATE TRIGGER `updateOnInsert_jobsByAOI` AFTER INSERT ON `jobs` FOR EACH ROW INSERT INTO report_jobsByAOI (aoi_id, aoi_name, job_id, job_name, job_type, job_field, job_field_name, job_count)
    SELECT aoi.id AS aoi_id, aoi.name AS aoi_name, jobs.id AS job_id, jobs.name AS job_name, jobs.type AS job_type, aoi.id AS job_field, aoi.name AS job_field_name, COUNT(jobs.id) AS job_count
    FROM jobs
    INNER JOIN aoi ON jobs.field = aoi.id
    WHERE aoi.id = NEW.field
    GROUP BY aoi.id, jobs.id
    ORDER BY aoi.id, job_count DESC
    ON DUPLICATE KEY UPDATE job_count = job_count + 1
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(55) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_has_role`
--

DROP TABLE IF EXISTS `user_has_role`;
CREATE TABLE IF NOT EXISTS `user_has_role` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userHasRole` (`user_id`,`role_id`),
  KEY `roleID` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aoi`
--
ALTER TABLE `aoi`
  ADD CONSTRAINT `createdBy` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `updatedBy` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `contact_log`
--
ALTER TABLE `contact_log`
  ADD CONSTRAINT `senderOfEmail` FOREIGN KEY (`sender`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `studentContacted` FOREIGN KEY (`student`) REFERENCES `student` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `degree_lvl`
--
ALTER TABLE `degree_lvl`
  ADD CONSTRAINT `degreeCreatedBy` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `degreeUpdatedBy` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `eventAtSchool` FOREIGN KEY (`location`) REFERENCES `school` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `eventCreatedBy` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `eventUpdatedBy` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobCreatedBy` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `jobField` FOREIGN KEY (`field`) REFERENCES `aoi` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `jobUpdatedBy` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `major`
--
ALTER TABLE `major`
  ADD CONSTRAINT `majorCreatedBy` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `majorUpdatedBy` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `role_has_permission`
--
ALTER TABLE `role_has_permission`
  ADD CONSTRAINT `permissionID` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `roleForPermission` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `school`
--
ALTER TABLE `school`
  ADD CONSTRAINT `schoolAddedBy` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `schoolUpdatedBy` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `areaOfInterest` FOREIGN KEY (`interest`) REFERENCES `aoi` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `areaOfStudy` FOREIGN KEY (`major`) REFERENCES `major` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `degreeLevel` FOREIGN KEY (`degree`) REFERENCES `degree_lvl` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `eventAttended` FOREIGN KEY (`event`) REFERENCES `event` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `schoolAttended` FOREIGN KEY (`school`) REFERENCES `school` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `user_has_role`
--
ALTER TABLE `user_has_role`
  ADD CONSTRAINT `roleID` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `userID` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
