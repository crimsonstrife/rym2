-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 22, 2023 at 02:10 PM
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `aoi`
--

INSERT INTO `aoi` (`id`, `name`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'IT', 1, 1, '2023-10-18 17:11:07', '2023-10-18 17:11:07'),
(2, 'Programming', 1, 1, '2023-10-18 17:11:07', '2023-10-18 17:11:07'),
(3, 'Analytics', 1, 1, '2023-10-18 17:11:07', '2023-10-18 17:11:07'),
(4, 'Cybersecurity', 1, 1, '2023-10-18 17:11:07', '2023-10-18 17:11:07'),
(5, 'Finance', 1, 1, '2023-10-18 17:11:07', '2023-10-18 17:11:07'),
(6, 'Supply Chain', 1, 1, '2023-10-18 17:11:07', '2023-10-18 17:11:07'),
(7, 'Sales', 1, 1, '2023-12-12 23:44:32', '2023-12-12 23:44:32');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `contact_log`
--

INSERT INTO `contact_log` (`id`, `student`, `auto`, `sender`, `send_date`, `subject`, `message`) VALUES
(1, 1, 0, 1, '2023-12-15 19:05:40', 'Test Subject', 'This is a test message'),
(2, 5, 1, NULL, '2023-12-16 01:02:37', 'Thank you Sam, for registering!', 'Thank you for registering for the College Recruitment Program. We will be in touch with you soon.'),
(3, 7, 1, NULL, '2023-12-19 21:42:44', 'Thank you Mark, for registering!', 'Thank you for registering for the College Recruitment Program. We will be in touch with you soon.'),
(4, 2, 0, 1, '2023-12-21 21:58:00', 'test', 'test');

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `degree_lvl`
--

INSERT INTO `degree_lvl` (`id`, `name`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'AA - Associate of Arts', '2023-10-18 17:01:38', '2023-10-18 17:01:38', 1, 1),
(2, 'AAA - Associate of Applied Arts', '2023-10-18 17:01:38', '2023-10-18 17:01:38', 1, 1),
(3, 'AS - Associate of Science', '2023-10-18 17:02:06', '2023-10-18 17:02:06', 1, 1),
(4, 'AAS - Associate of Applied Science', '2023-10-18 17:02:28', '2023-10-18 17:02:28', 1, 1),
(5, 'BA - Bachelor of Arts', '2023-10-18 17:03:22', '2023-10-18 17:03:22', 1, 1),
(6, 'BAA - Bachelor of Applied Arts', '2023-10-18 17:03:54', '2023-10-18 17:03:54', 1, 1),
(7, 'BScIT - Bachelor of Science in Information Technology', '2023-10-18 17:05:14', '2023-12-12 19:40:49', 1, 1),
(8, 'MA - Master of Arts', '2023-10-18 17:06:00', '2023-10-18 17:06:00', 1, 1),
(9, 'MS - Master of Science', '2023-10-18 17:06:40', '2023-10-18 17:06:40', 1, 1),
(10, 'BBA - Bachelor of Business Administration', '2023-12-12 01:29:34', '2023-12-12 01:29:34', 1, 1),
(11, 'BS - Bachelor of Science', '2023-12-15 21:14:29', '2023-12-15 21:14:29', 1, 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `name`, `event_date`, `created_at`, `updated_at`, `updated_by`, `created_by`, `location`) VALUES
(1, 'WGU Job Faire', '2024-01-01', '2023-10-19 18:04:31', '2023-11-10 19:40:51', 1, 1, 1),
(2, 'UNCC Charlotte', '2023-11-30', '2023-10-24 17:49:33', '2023-10-24 17:49:33', 1, 1, 2),
(3, 'Stanly Community Outreach', '2023-12-15', '2023-11-02 17:36:01', '2023-11-02 17:36:01', 1, 1, 3),
(11, 'New Test Event', '2024-03-30', '2023-11-17 00:11:22', '2023-11-17 00:11:22', 1, 1, 3),
(13, 'new event with branding', '2024-04-24', '2023-11-17 00:14:39', '2023-11-17 00:14:39', 1, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `event_branding`
--

DROP TABLE IF EXISTS `event_branding`;
CREATE TABLE IF NOT EXISTS `event_branding` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) NOT NULL,
  `event_logo` varchar(500) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `event_banner` varchar(500) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_id` (`event_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `event_branding`
--

INSERT INTO `event_branding` (`id`, `event_id`, `event_logo`, `event_banner`) VALUES
(2, 1, 'ezgif-1-590ff7284b.jpg', 'ezgif-1-eb6482dde7.jpg'),
(3, 2, '312920701_622333729345028_2932566406123148558_n.jpg', '2023.09.07_FinancialServices-CareerFair_600x400.png'),
(4, 13, 'SCC-Logo (1).png', 'In_The_News_Icon_for_News_Stories.png');

-- --------------------------------------------------------

--
-- Table structure for table `event_slugs`
--

DROP TABLE IF EXISTS `event_slugs`;
CREATE TABLE IF NOT EXISTS `event_slugs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) NOT NULL,
  `slug` varchar(25) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `eventHasSlug` (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `event_slugs`
--

INSERT INTO `event_slugs` (`id`, `event_id`, `slug`) VALUES
(1, 1, 'wgu-job-faire'),
(2, 2, 'uncc-charlotte'),
(3, 11, 'new-test-event'),
(5, 13, 'new-event-with-branding'),
(6, 3, 'stanly-community-outreach');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `name`, `description`, `type`, `field`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Packaging Operator', 'Identify and separate poor quality parts\r\nPack molded parts into boxes\r\nLift and carry full boxes of parts from molding machine to pallet (approx. 2 feet)\r\nLabel boxes\r\nAssemble molded parts\r\nBarcode parts correctly\r\nAdjust case erectors and case sealers as needed', 'FULL', 6, '2023-10-20 13:20:04', '2023-10-20 13:20:04', 1, 1),
(2, 'Business Analyst', 'this is a description.', 'INTERN', 3, '2023-10-20 13:21:03', '2023-10-20 13:21:03', 1, 1),
(3, 'Lorem Ipsum', 'This is a job, lorem ipsum', 'PART', 5, '2023-10-20 18:14:00', '2023-10-20 18:14:00', 1, 1),
(4, 'Lorem Ipsum 2', 'Electric Boogaloo', 'INTERN', 4, '2023-10-20 18:15:05', '2023-10-20 18:15:05', 1, 1),
(5, 'Job', 'I\'m just trying to take up space for testing', 'PART', 4, '2023-10-20 18:15:48', '2023-10-20 18:15:48', 1, 1),
(6, 'lorem', 'ipsum', 'INTERN', 6, '2023-10-20 18:17:38', '2023-12-12 22:14:25', 1, 1),
(7, 'Helpdesk', 'this is an IT helpdesk role', 'FULL', 1, '2023-12-12 21:41:11', '2023-12-12 22:16:42', 1, 1);

--
-- Triggers `jobs`
--
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `major`
--

INSERT INTO `major` (`id`, `name`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Software Development', '2023-10-18 17:07:54', '2023-10-18 17:07:54', 1, 1),
(2, 'Business Administration', '2023-10-18 17:08:11', '2023-10-18 17:08:11', 1, 1),
(3, 'Accounting', '2023-10-18 17:08:27', '2023-10-18 17:08:27', 1, 1),
(5, 'Test', '2023-10-20 14:10:45', '2023-12-08 19:10:48', 1, 1),
(6, 'Computer Science', '2023-12-12 22:39:15', '2023-12-12 22:39:15', 1, 1),
(7, 'Marketing', '2023-12-13 00:57:25', '2023-12-13 00:57:25', NULL, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`) VALUES
(29, 'CREATE DEGREE'),
(9, 'CREATE EVENT'),
(21, 'CREATE FIELD'),
(5, 'CREATE JOB'),
(25, 'CREATE MAJOR'),
(33, 'CREATE REPORT'),
(17, 'CREATE ROLE'),
(13, 'CREATE SCHOOL'),
(1, 'CREATE USER'),
(30, 'DELETE DEGREE'),
(10, 'DELETE EVENT'),
(22, 'DELETE FIELD'),
(6, 'DELETE JOB'),
(26, 'DELETE MAJOR'),
(34, 'DELETE REPORT'),
(18, 'DELETE ROLE'),
(14, 'DELETE SCHOOL'),
(3, 'DELETE USER'),
(31, 'READ DEGREE'),
(11, 'READ EVENT'),
(23, 'READ FIELD'),
(7, 'READ JOB'),
(27, 'READ MAJOR'),
(35, 'READ REPORT'),
(19, 'READ ROLE'),
(15, 'READ SCHOOL'),
(4, 'READ USER'),
(32, 'UPDATE DEGREE'),
(12, 'UPDATE EVENT'),
(24, 'UPDATE FIELD'),
(8, 'UPDATE JOB'),
(28, 'UPDATE MAJOR'),
(36, 'UPDATE REPORT'),
(20, 'UPDATE ROLE'),
(16, 'UPDATE SCHOOL'),
(2, 'UPDATE USER');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `report_type` varchar(500) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `data` json NOT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `report_type`, `data`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Top Degree by School', '[{\"major\": \"Accounting\", \"degree\": \"AA - Associate of Arts\", \"school\": \"Rowan-Cabarrus Community College - RCCC\", \"student_count\": 2}, {\"major\": \"Software Development\", \"degree\": \"BScIT - Bachelor of Science in Information Technology\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Business Administration\", \"degree\": \"BA - Bachelor of Arts\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"AAS - Associate of Applied Science\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}]', 1, '2023-12-20 00:36:04', 1, '2023-12-20 00:36:04'),
(2, 'Top Degree by School', '[{\"major\": \"Accounting\", \"degree\": \"AA - Associate of Arts\", \"school\": \"Rowan-Cabarrus Community College - RCCC\", \"student_count\": 2}, {\"major\": \"Software Development\", \"degree\": \"BScIT - Bachelor of Science in Information Technology\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Business Administration\", \"degree\": \"BA - Bachelor of Arts\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"AAS - Associate of Applied Science\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}]', 1, '2023-12-20 00:41:49', 1, '2023-12-20 00:41:49'),
(3, 'Top Degree by School', '[{\"major\": \"Accounting\", \"degree\": \"AA - Associate of Arts\", \"school\": \"Rowan-Cabarrus Community College - RCCC\", \"student_count\": 2}, {\"major\": \"Software Development\", \"degree\": \"BScIT - Bachelor of Science in Information Technology\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Business Administration\", \"degree\": \"BA - Bachelor of Arts\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"AAS - Associate of Applied Science\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}]', 1, '2023-12-20 01:54:10', 1, '2023-12-20 01:54:10'),
(4, 'Top Field by School', '[{\"school\": \"Rowan-Cabarrus Community College - RCCC\", \"student_count\": 2, \"field_of_study\": \"Finance\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Programming\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Supply Chain\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"IT\"}, {\"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1, \"field_of_study\": \"IT\"}]', 1, '2023-12-20 20:27:05', 1, '2023-12-20 20:27:05'),
(5, 'Top Degree by School', '[{\"major\": \"Accounting\", \"degree\": \"AA - Associate of Arts\", \"school\": \"Rowan-Cabarrus Community College - RCCC\", \"student_count\": 2}, {\"major\": \"Software Development\", \"degree\": \"BScIT - Bachelor of Science in Information Technology\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Business Administration\", \"degree\": \"BA - Bachelor of Arts\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"AAS - Associate of Applied Science\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}]', 1, '2023-12-20 20:27:37', 1, '2023-12-20 20:27:37'),
(6, 'Major to Field Ratio by School', '[{\"ratio\": 1, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"ratio\": 1, \"school\": \"Rowan-Cabarrus Community College - RCCC\"}]', 1, '2023-12-20 21:50:45', 1, '2023-12-20 21:50:45'),
(7, 'Major to Field Ratio by School', '[{\"ratio\": 1, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"ratio\": 1, \"school\": \"Rowan-Cabarrus Community College - RCCC\"}]', 1, '2023-12-20 21:52:58', 1, '2023-12-20 21:52:58'),
(8, 'Major to Field Ratio by School', '[{\"ratio\": 1, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"ratio\": 1, \"school\": \"Rowan-Cabarrus Community College - RCCC\"}]', 1, '2023-12-20 21:53:39', 1, '2023-12-20 21:53:39'),
(9, 'Major to Field Ratio by School', '[{\"ratio\": 1, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"ratio\": 1, \"school\": \"Rowan-Cabarrus Community College - RCCC\"}]', 1, '2023-12-20 21:55:45', 1, '2023-12-20 21:55:45'),
(10, 'Major to Field Ratio by School', '[{\"ratio\": 1, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"ratio\": 1, \"school\": \"Rowan-Cabarrus Community College - RCCC\"}]', 1, '2023-12-20 21:56:43', 1, '2023-12-20 21:56:43'),
(11, 'Major to Field Ratio by School', '[{\"ratio\": 1, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"ratio\": 1, \"school\": \"Rowan-Cabarrus Community College - RCCC\"}]', 1, '2023-12-20 22:00:32', 1, '2023-12-20 22:00:32'),
(12, 'Major to Field Ratio by School', '[{\"field\": \"Programming\", \"major\": \"Software Development\", \"ratio\": 1, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"Supply Chain\", \"major\": \"Business Administration\", \"ratio\": 1, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 1, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"field\": \"Finance\", \"major\": \"Accounting\", \"ratio\": 2, \"school\": \"Rowan-Cabarrus Community College - RCCC\"}]', 1, '2023-12-20 22:29:30', 1, '2023-12-20 22:29:30'),
(13, 'Major to Field Ratio by School', '[{\"field\": \"Programming\", \"major\": \"Software Development\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"Supply Chain\", \"major\": \"Business Administration\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"field\": \"Finance\", \"major\": \"Accounting\", \"ratio\": 1, \"school\": \"Rowan-Cabarrus Community College - RCCC\"}]', 1, '2023-12-20 23:28:25', 1, '2023-12-20 23:28:25'),
(14, 'Jobs by Field', '[{\"jobs\": [\"Lorem Ipsum 2\", \"Job\"], \"job_count\": 2, \"job_field_id\": 4, \"job_field_name\": \"Cybersecurity\"}, {\"jobs\": [\"Packaging Operator\", \"lorem\"], \"job_count\": 2, \"job_field_id\": 6, \"job_field_name\": \"Supply Chain\"}, {\"jobs\": [\"Helpdesk\"], \"job_count\": 1, \"job_field_id\": 1, \"job_field_name\": \"IT\"}, {\"jobs\": [\"Business Analyst\"], \"job_count\": 1, \"job_field_id\": 3, \"job_field_name\": \"Analytics\"}, {\"jobs\": [\"Lorem Ipsum\"], \"job_count\": 1, \"job_field_id\": 5, \"job_field_name\": \"Finance\"}]', 1, '2023-12-21 19:39:02', 1, '2023-12-21 19:39:02'),
(15, 'Jobs by Field', '[{\"jobs\": [\"Lorem Ipsum 2\", \"Job\"], \"job_count\": 2, \"job_field_name\": \"Cybersecurity\"}, {\"jobs\": [\"Packaging Operator\", \"lorem\"], \"job_count\": 2, \"job_field_name\": \"Supply Chain\"}, {\"jobs\": [\"Helpdesk\"], \"job_count\": 1, \"job_field_name\": \"IT\"}, {\"jobs\": [\"Business Analyst\"], \"job_count\": 1, \"job_field_name\": \"Analytics\"}, {\"jobs\": [\"Lorem Ipsum\"], \"job_count\": 1, \"job_field_name\": \"Finance\"}]', 1, '2023-12-21 19:40:04', 1, '2023-12-21 19:40:04'),
(16, 'Jobs by Field', '[{\"jobs\": \"[\\\"Lorem Ipsum 2\\\",\\\"Job\\\"]\", \"job_count\": 2, \"job_field_name\": \"Cybersecurity\"}, {\"jobs\": \"[\\\"Packaging Operator\\\",\\\"lorem\\\"]\", \"job_count\": 2, \"job_field_name\": \"Supply Chain\"}, {\"jobs\": \"[\\\"Helpdesk\\\"]\", \"job_count\": 1, \"job_field_name\": \"IT\"}, {\"jobs\": \"[\\\"Business Analyst\\\"]\", \"job_count\": 1, \"job_field_name\": \"Analytics\"}, {\"jobs\": \"[\\\"Lorem Ipsum\\\"]\", \"job_count\": 1, \"job_field_name\": \"Finance\"}]', 1, '2023-12-21 19:40:46', 1, '2023-12-21 19:40:46'),
(17, 'Jobs by Field', '{\"IT\": {\"jobs\": \"[\\\"Helpdesk\\\"]\", \"job_count\": 1, \"job_field_name\": \"IT\"}, \"Finance\": {\"jobs\": \"[\\\"Lorem Ipsum\\\"]\", \"job_count\": 1, \"job_field_name\": \"Finance\"}, \"Analytics\": {\"jobs\": \"[\\\"Business Analyst\\\"]\", \"job_count\": 1, \"job_field_name\": \"Analytics\"}, \"Supply Chain\": {\"jobs\": \"[\\\"Packaging Operator\\\",\\\"lorem\\\"]\", \"job_count\": 2, \"job_field_name\": \"Supply Chain\"}, \"Cybersecurity\": {\"jobs\": \"[\\\"Lorem Ipsum 2\\\",\\\"Job\\\"]\", \"job_count\": 2, \"job_field_name\": \"Cybersecurity\"}}', 1, '2023-12-21 19:42:15', 1, '2023-12-21 19:42:15'),
(18, 'Jobs by Field', '[{\"jobs\": \"[\\\"Lorem Ipsum 2\\\",\\\"Job\\\"]\", \"job_count\": 2, \"job_field_name\": \"Cybersecurity\"}, {\"jobs\": \"[\\\"Packaging Operator\\\",\\\"lorem\\\"]\", \"job_count\": 2, \"job_field_name\": \"Supply Chain\"}, {\"jobs\": \"[\\\"Helpdesk\\\"]\", \"job_count\": 1, \"job_field_name\": \"IT\"}, {\"jobs\": \"[\\\"Business Analyst\\\"]\", \"job_count\": 1, \"job_field_name\": \"Analytics\"}, {\"jobs\": \"[\\\"Lorem Ipsum\\\"]\", \"job_count\": 1, \"job_field_name\": \"Finance\"}]', 1, '2023-12-21 19:42:33', 1, '2023-12-21 19:42:33'),
(19, 'Jobs by Field', '[{\"jobs\": \"[\\\"Helpdesk\\\"]\", \"job_count\": 1, \"job_field_name\": \"IT\"}, {\"jobs\": \"[\\\"Business Analyst\\\"]\", \"job_count\": 1, \"job_field_name\": \"Analytics\"}, {\"jobs\": \"[\\\"Lorem Ipsum 2\\\",\\\"Job\\\"]\", \"job_count\": 2, \"job_field_name\": \"Cybersecurity\"}, {\"jobs\": \"[\\\"Lorem Ipsum\\\"]\", \"job_count\": 1, \"job_field_name\": \"Finance\"}, {\"jobs\": \"[\\\"Packaging Operator\\\",\\\"lorem\\\"]\", \"job_count\": 2, \"job_field_name\": \"Supply Chain\"}]', 1, '2023-12-21 19:42:48', 1, '2023-12-21 19:42:48'),
(20, 'Jobs by Field', '[{\"jobs\": \"[\\\"Lorem Ipsum\\\"]\", \"job_count\": 1, \"job_field_name\": \"Finance\"}, {\"jobs\": \"[\\\"Business Analyst\\\"]\", \"job_count\": 1, \"job_field_name\": \"Analytics\"}, {\"jobs\": \"[\\\"Helpdesk\\\"]\", \"job_count\": 1, \"job_field_name\": \"IT\"}, {\"jobs\": \"[\\\"Packaging Operator\\\",\\\"lorem\\\"]\", \"job_count\": 2, \"job_field_name\": \"Supply Chain\"}, {\"jobs\": \"[\\\"Lorem Ipsum 2\\\",\\\"Job\\\"]\", \"job_count\": 2, \"job_field_name\": \"Cybersecurity\"}]', 1, '2023-12-21 19:43:32', 1, '2023-12-21 19:43:32'),
(21, 'Jobs by Field', '[{\"jobs\": \"[\\\"Lorem Ipsum 2\\\",\\\"Job\\\"]\", \"job_count\": 2, \"job_field_name\": \"Cybersecurity\"}, {\"jobs\": \"[\\\"Packaging Operator\\\",\\\"lorem\\\"]\", \"job_count\": 2, \"job_field_name\": \"Supply Chain\"}, {\"jobs\": \"[\\\"Helpdesk\\\"]\", \"job_count\": 1, \"job_field_name\": \"IT\"}, {\"jobs\": \"[\\\"Business Analyst\\\"]\", \"job_count\": 1, \"job_field_name\": \"Analytics\"}, {\"jobs\": \"[\\\"Lorem Ipsum\\\"]\", \"job_count\": 1, \"job_field_name\": \"Finance\"}]', 1, '2023-12-21 19:43:52', 1, '2023-12-21 19:43:52'),
(22, 'Jobs by Field', '[{\"jobs\": \"Lorem Ipsum 2, Job\", \"job_count\": 2, \"job_field_name\": \"Cybersecurity\"}, {\"jobs\": \"Packaging Operator, lorem\", \"job_count\": 2, \"job_field_name\": \"Supply Chain\"}, {\"jobs\": \"Helpdesk\", \"job_count\": 1, \"job_field_name\": \"IT\"}, {\"jobs\": \"Business Analyst\", \"job_count\": 1, \"job_field_name\": \"Analytics\"}, {\"jobs\": \"Lorem Ipsum\", \"job_count\": 1, \"job_field_name\": \"Finance\"}]', 1, '2023-12-21 19:50:29', 1, '2023-12-21 19:50:29'),
(23, 'Contact Follow-Up Percentage', '{\"total\": 3, \"percentage\": \"33.33333\", \"top_sending_user\": \"admin\"}', 1, '2023-12-21 21:10:37', 1, '2023-12-21 21:10:37'),
(24, 'Contact Follow-Up Percentage', '[{\"total\": 3, \"percentage\": \"33.33333\", \"top_sending_user\": \"admin\"}]', 1, '2023-12-21 21:12:35', 1, '2023-12-21 21:12:35'),
(25, 'Contact Follow-Up Percentage', '[{\"total\": 4, \"percentage\": \"50.00000\", \"top_sending_user\": \"admin\"}]', 1, '2023-12-21 21:58:51', 1, '2023-12-21 21:58:51'),
(26, 'Major to Field Ratio by School', '[{\"field\": \"Programming\", \"major\": \"Software Development\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"Supply Chain\", \"major\": \"Business Administration\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"field\": \"Finance\", \"major\": \"Accounting\", \"ratio\": 1, \"school\": \"Rowan-Cabarrus Community College - RCCC\"}]', 1, '2023-12-22 01:13:09', 1, '2023-12-22 01:13:09');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `created_by` (`created_by`),
  KEY `roleUpdatedBy` (`updated_by`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Admin', 1, '2023-12-22 14:42:47', 1, '2023-12-22 14:43:10'),
(2, 'Guest', 1, '2023-12-22 14:42:47', 1, '2023-12-22 14:43:15'),
(3, 'SUPERADMIN', 1, '2023-12-22 23:28:29', 1, '2023-12-22 23:28:29'),
(4, 'Moderator', 1, '2023-12-22 23:33:27', 1, '2023-12-22 23:33:27'),
(11, 'standard user', 1, '2023-12-22 23:44:27', 1, '2023-12-22 23:44:27');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permission`
--

DROP TABLE IF EXISTS `role_has_permission`;
CREATE TABLE IF NOT EXISTS `role_has_permission` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) NOT NULL,
  `permission_id` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roleHasPermission` (`role_id`,`permission_id`),
  KEY `permissionID` (`permission_id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `role_has_permission`
--

INSERT INTO `role_has_permission` (`id`, `role_id`, `permission_id`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 1, 1, '2023-10-12 18:08:00', 1, '2023-12-22 14:39:18', 1),
(2, 1, 3, '2023-10-12 18:08:00', 1, '2023-12-22 14:39:24', 1),
(3, 1, 2, '2023-10-12 18:08:00', 1, '2023-12-22 14:39:29', 1),
(4, 1, 4, '2023-10-12 18:08:00', 1, '2023-12-22 14:39:34', 1),
(5, 11, 4, '2023-12-22 23:44:27', 1, '2023-12-22 23:44:27', 1),
(6, 11, 7, '2023-12-22 23:44:27', 1, '2023-12-22 23:44:27', 1),
(7, 11, 11, '2023-12-22 23:44:27', 1, '2023-12-22 23:44:27', 1),
(8, 11, 15, '2023-12-22 23:44:27', 1, '2023-12-22 23:44:27', 1),
(9, 11, 19, '2023-12-22 23:44:27', 1, '2023-12-22 23:44:27', 1),
(10, 11, 23, '2023-12-22 23:44:27', 1, '2023-12-22 23:44:27', 1),
(11, 11, 27, '2023-12-22 23:44:27', 1, '2023-12-22 23:44:27', 1),
(12, 11, 31, '2023-12-22 23:44:27', 1, '2023-12-22 23:44:27', 1),
(13, 11, 35, '2023-12-22 23:44:27', 1, '2023-12-22 23:44:27', 1),
(14, 3, 1, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(15, 3, 2, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(16, 3, 3, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(17, 3, 4, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(18, 3, 5, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(19, 3, 6, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(20, 3, 7, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(21, 3, 8, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(22, 3, 9, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(23, 3, 10, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(24, 3, 11, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(25, 3, 12, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(26, 3, 13, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(27, 3, 14, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(28, 3, 15, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(29, 3, 16, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(30, 3, 17, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(31, 3, 18, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(32, 3, 19, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(33, 3, 20, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(34, 3, 21, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(35, 3, 22, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(36, 3, 23, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(37, 3, 24, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(38, 3, 25, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(39, 3, 26, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(40, 3, 27, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(41, 3, 28, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(42, 3, 29, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(43, 3, 30, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(44, 3, 31, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(45, 3, 32, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(46, 3, 33, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(47, 3, 34, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(48, 3, 35, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(49, 3, 36, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `school`
--

INSERT INTO `school` (`id`, `name`, `address`, `city`, `state`, `zipcode`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Western Governor&#039;s University - WGU', '4001 700 East', 'Millcreek', 'UT', '84107', 1, 1, '2023-11-09 20:28:23', '2023-12-07 23:43:16'),
(2, 'University of North Carolina at Charlotte - UNCC', '9201 University City Blvd', 'Charlotte', 'NC', '28223', 1, 1, '2023-10-24 17:47:54', '2023-12-07 23:41:55'),
(3, 'Stanly Community College - SCC', '141 College Drive', 'Albemarle', 'NC', '28001', 1, 1, '2023-11-02 17:35:22', '2023-12-07 23:36:19'),
(4, 'Rowan-Cabarrus Community College - RCCC', '1333 Jake Alexander Blvd., S.', 'Salisbury', 'NC', '28146', 1, 1, '2023-12-07 16:05:07', '2023-12-07 21:11:56'),
(5, 'University of North Carolina at Greensboro - UNCG', '1400 Spring Garden Street', 'Greensboro', 'NC', '27412', 1, 1, '2023-12-08 00:17:09', '2023-12-08 00:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `school_branding`
--

DROP TABLE IF EXISTS `school_branding`;
CREATE TABLE IF NOT EXISTS `school_branding` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `school_id` bigint(20) NOT NULL,
  `school_logo` varchar(500) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `school_color` varchar(8) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `school_branding`
--

INSERT INTO `school_branding` (`id`, `school_id`, `school_logo`, `school_color`) VALUES
(1, 1, 'WGU-Marketing-logo.png', '#003057'),
(2, 3, 'scc-logo.png', '#ffd200'),
(3, 2, '8716-01-Charlotte-Master-File-v7_1.png', '#005035'),
(4, 4, 'rccc_550_logo-1.png', '#002f6d'),
(5, 5, 'uncg.png', '#0f2044');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `isSet` enum('SET') COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `app_name` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `app_url` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mail_from_address` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mail_from_name` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mail_mailer` enum('smtp') COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mail_host` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mail_port` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mail_auth_req` tinyint(1) DEFAULT NULL,
  `mail_username` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mail_password` varchar(500) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mail_encryption` enum('ssl','tls') COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  UNIQUE KEY `IsSet` (`isSet`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`isSet`, `app_name`, `app_url`, `mail_from_address`, `mail_from_name`, `mail_mailer`, `mail_host`, `mail_port`, `mail_auth_req`, `mail_username`, `mail_password`, `mail_encryption`) VALUES
('SET', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
  `phone` varchar(15) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `zipcode` varchar(15) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `interest` bigint(20) NOT NULL,
  `degree` bigint(20) NOT NULL,
  `major` bigint(20) NOT NULL,
  `school` bigint(20) NOT NULL,
  `position` enum('FULL','PART','INTERN') COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'INTERN',
  `graduation` date NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `studentInterest` (`email`,`interest`),
  KEY `areaOfInterest` (`interest`),
  KEY `degree` (`degree`),
  KEY `major` (`major`),
  KEY `school` (`school`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `first_name`, `last_name`, `email`, `phone`, `address`, `city`, `state`, `zipcode`, `interest`, `degree`, `major`, `school`, `position`, `graduation`, `created_at`, `updated_at`) VALUES
(1, 'Patrick', 'Barnhardt', 'thecrimsonstrife@gmail.com', '7047962590', '2221 Devon Dr', 'Dallas', 'NC', '28034', 2, 7, 1, 1, 'FULL', '2024-03-31', '2023-12-15 21:12:56', '2023-12-15 21:12:56'),
(2, 'Kelsey', 'Berkman', 'kberkm1@example.com', '7048981942', '2221 Devon Dr', 'Dallas', 'NC', '28034', 6, 5, 2, 1, 'INTERN', '2024-11-30', '2023-12-16 00:55:48', '2023-12-16 00:55:48'),
(3, 'John', 'Doe', 'jdoe@wgu.edu', '8982341111', '2860 Mount Pleasant Road South', 'Mount Pleasant', 'NC', '28124', 1, 4, 6, 1, 'INTERN', '2024-01-31', '2023-12-16 00:58:20', '2023-12-16 00:58:20'),
(4, 'Jane', 'Doe', 'jdoe@example.com', '1112223333', '2860 Mount Pleasant Road South', 'Mount Pleasant', 'NC', '28124', 5, 1, 3, 4, 'INTERN', '2024-04-30', '2023-12-16 01:00:12', '2023-12-16 01:00:12'),
(5, 'Sam', 'Smith', 'ssmith@example.com', '223-432-5454', '2221 Devon Dr', 'Dallas', 'NC', '28034', 1, 11, 6, 2, 'FULL', '2023-12-31', '2023-12-16 01:02:37', '2023-12-16 01:02:37'),
(7, 'Mark', 'Iplier', 'mark@example.com', '1112223232', '2221 Devon Dr', 'Dallas', 'NC', '28034', 5, 1, 3, 4, 'FULL', '2024-03-31', '2023-12-19 21:42:44', '2023-12-19 21:42:44');

-- --------------------------------------------------------

--
-- Table structure for table `student_at_event`
--

DROP TABLE IF EXISTS `student_at_event`;
CREATE TABLE IF NOT EXISTS `student_at_event` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) NOT NULL,
  `event_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `studentAtEvent` (`student_id`),
  KEY `eventHadStudent` (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `student_at_event`
--

INSERT INTO `student_at_event` (`id`, `student_id`, `event_id`) VALUES
(1, 1, 1),
(2, 2, 1);

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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'admin', 'thecrimsonstrife@gmail.com', '$2y$10$K3gy8kgHaauBfMawF9/aXuzt6xIbNb2i.WKJ.gESDdh1Eu1hIJs0G', '2023-10-12 18:34:47', NULL, '2023-10-12 18:09:12', NULL);

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
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userHasRole` (`user_id`,`role_id`),
  KEY `roleID` (`role_id`),
  KEY `userGivenRole` (`created_by`),
  KEY `userRoleModified` (`updated_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `user_has_role`
--

INSERT INTO `user_has_role` (`id`, `user_id`, `role_id`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 1, 1, '2023-12-22 14:51:11', 1, '2023-12-22 14:51:11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_token_auth`
--

DROP TABLE IF EXISTS `user_token_auth`;
CREATE TABLE IF NOT EXISTS `user_token_auth` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `selector_hash` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `is_expired` int(11) NOT NULL DEFAULT '0',
  `expiry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `userHasToken` (`user_id`),
  KEY `usersName` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `user_token_auth`
--

INSERT INTO `user_token_auth` (`id`, `user_id`, `user_name`, `password_hash`, `selector_hash`, `is_expired`, `expiry_date`) VALUES
(1, 1, 'admin', '$2y$10$mTEfMXuOKoix.YGz2uVPSuIxXJ/jyCpdM3tifpycUy9sSi8suld76', '$2y$10$TzwL8obqyt3uhOBBNE6PBerRpDA1m7sa0Wq56w6ks.5DwHfiKigZm', 1, '2023-11-22 17:41:56'),
(2, 1, 'admin', '$2y$10$MsbF1YERJOqcJtYab.z.yOX4sbw7g4fPiZxSgwpQkB6kYbjNB8El2', '$2y$10$UpEOAReFyZ4/Ap3hKUnQS.sOzvrbcWhKwbPwKGjyyWBbJjn6E70FW', 1, '2023-12-15 16:12:06'),
(3, 1, 'admin', '$2y$10$tkuu26p67cmtu.b1sPLuIOTkB4S8ImXpt/w2r.9iRwmof90yweLou', '$2y$10$bSCA5g6dEO31Te/4HJWI9ueX2lNM9S1P7LtsmQVn50BTmcCTlEv4C', 1, '2023-12-19 16:42:56'),
(4, 1, 'admin', '$2y$10$vtA0bcR9Y0XqGFl8FIgxzO0.IbIBh9qt.7UDvHmwCkxV1ODGNZ6va', '$2y$10$LECAISZra5Vbb68P0WXN.Oa2ZTH.ixsTWW3FHRE2Or6vPYjhi8nlO', 1, '2023-12-20 18:26:34'),
(5, 1, 'admin', '$2y$10$aPxsdxoE7K7goLSn8qQXveV8kvlpcQCCx5kjpFmzyYy.v7wvvQlOe', '$2y$10$JJwRijaUU0mOrtHPch3l6ed422jZ9P8Ajnvd8M1QXmhTbM/xDHA4u', 1, '2023-12-22 14:55:46'),
(6, 1, 'admin', '$2y$10$jr.RWYdJidisIVwSM7JVOeqXXxkc1y4Z2kMM2jrJQ.GsxkSs2YNS2', '$2y$10$IRZV.robFoGB/0F9H/w3HuD69jgcAwCVi915obWM246nTjrcqbdEG', 0, '2024-01-21 19:55:46');

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
-- Constraints for table `event_branding`
--
ALTER TABLE `event_branding`
  ADD CONSTRAINT `eventHasBranding` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_slugs`
--
ALTER TABLE `event_slugs`
  ADD CONSTRAINT `eventHasSlug` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reportCreatedBy` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `reportUpdatedBy` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roleCreatedBy` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `roleUpdatedBy` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `role_has_permission`
--
ALTER TABLE `role_has_permission`
  ADD CONSTRAINT `permissionID` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `roleForPermission` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `roleGivenPermission` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `rolePermissionModified` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

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
  ADD CONSTRAINT `schoolAttended` FOREIGN KEY (`school`) REFERENCES `school` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `student_at_event`
--
ALTER TABLE `student_at_event`
  ADD CONSTRAINT `eventHadStudent` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `studentAtEvent` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_has_role`
--
ALTER TABLE `user_has_role`
  ADD CONSTRAINT `roleID` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `userGivenRole` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `userID` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userRoleModified` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_token_auth`
--
ALTER TABLE `user_token_auth`
  ADD CONSTRAINT `userHasToken` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usersName` FOREIGN KEY (`user_name`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
