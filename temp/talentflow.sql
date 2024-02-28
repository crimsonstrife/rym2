-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Feb 28, 2024 at 12:39 PM
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
-- Database: `talentflow`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `action` enum('CREATE','MODIFY','DELETE','LOGIN','LOGOUT','LOGIN FAILED','OTHER','RESET','UPLOAD','DOWNLOAD','ERROR','EMAIL') COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `performed_on` varchar(535) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aoi`
--

CREATE TABLE `aoi` (
  `id` bigint(20) NOT NULL,
  `name` varchar(55) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

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

CREATE TABLE `contact_log` (
  `id` bigint(20) NOT NULL,
  `student` bigint(20) DEFAULT NULL,
  `auto` tinyint(1) NOT NULL DEFAULT '1',
  `sender` bigint(20) DEFAULT NULL,
  `send_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subject` varchar(150) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `message` varchar(500) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `contact_log`
--

INSERT INTO `contact_log` (`id`, `student`, `auto`, `sender`, `send_date`, `subject`, `message`) VALUES
(5, 6, 1, NULL, '2024-01-16 20:34:21', 'Thank you Emily, for registering!', 'Thank you for registering for the College Recruitment Program. We will be in touch with you soon.'),
(6, 7, 1, NULL, '2024-01-16 20:39:12', 'Thank you Alex, for registering!', 'Thank you for registering for the College Recruitment Program. We will be in touch with you soon.'),
(7, 8, 1, NULL, '2024-01-16 20:42:21', 'Thank you Olivia, for registering!', 'Thank you for registering for the College Recruitment Program. We will be in touch with you soon.'),
(8, 9, 1, NULL, '2024-01-16 20:47:01', 'Thank you Sophia, for registering!', 'Thank you for registering for the College Recruitment Program. We will be in touch with you soon.'),
(9, 10, 1, NULL, '2024-01-16 20:48:33', 'Thank you Michael, for registering!', 'Thank you for registering for the College Recruitment Program. We will be in touch with you soon.'),
(10, NULL, 1, NULL, '2024-02-28 21:19:17', 'Thank you Patrick, for registering!', 'Thank you for registering for the College Recruitment Program. We will be in touch with you soon.');

-- --------------------------------------------------------

--
-- Table structure for table `degree_lvl`
--

CREATE TABLE `degree_lvl` (
  `id` bigint(20) NOT NULL,
  `name` varchar(80) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `degree_lvl`
--

INSERT INTO `degree_lvl` (`id`, `name`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'AA - Associate of Arts', '2024-02-27 22:10:27', '2024-02-27 22:10:27', NULL, NULL),
(2, 'AAA - Associate of Applied Arts', '2024-02-27 22:10:27', '2024-02-27 22:10:27', NULL, NULL),
(3, 'AS - Associate of Science', '2024-02-27 22:10:27', '2024-02-27 22:10:27', NULL, NULL),
(4, 'AAS - Associate of Applied Science', '2024-02-27 22:10:27', '2024-02-27 22:10:27', NULL, NULL),
(5, 'BA - Bachelor of Arts', '2024-02-27 22:10:27', '2024-02-27 22:10:27', NULL, NULL),
(6, 'BAA - Bachelor of Applied Arts', '2024-02-27 22:10:27', '2024-02-27 22:10:27', NULL, NULL),
(7, 'BScIT - Bachelor of Science in Information Technology', '2024-02-27 22:10:27', '2024-02-27 22:10:27', NULL, NULL),
(8, 'MA - Master of Arts', '2024-02-27 22:10:27', '2024-02-27 22:10:27', NULL, NULL),
(9, 'MS - Master of Science', '2024-02-27 22:10:27', '2024-02-27 22:10:27', NULL, NULL),
(11, 'BS - Bachelor of Science', '2024-02-27 22:10:27', '2024-02-27 22:10:27', NULL, NULL),
(12, 'BBA - Bachelor of Business Administration', '2024-02-27 22:10:27', '2024-02-27 22:10:27', NULL, NULL),
(14, 'DBA - Doctor of Business Administration', '2024-02-27 22:10:27', '2024-02-27 22:10:27', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `event_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `location` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `name`, `event_date`, `created_at`, `updated_at`, `updated_by`, `created_by`, `location`) VALUES
(1, 'WGU Job Faire', '2024-03-15', '2023-10-19 18:04:31', '2024-01-15 20:39:02', 1, 1, 1),
(2, 'UNCC Charlotte', '2024-02-29', '2023-10-24 17:49:33', '2024-01-05 15:21:13', 1, 1, 2),
(3, 'Stanly Community Outreach', '2024-02-15', '2023-11-02 17:36:01', '2024-01-05 15:20:47', 1, 1, 3),
(4, 'NC State Recruitement', '2024-06-16', '2024-01-16 20:19:38', '2024-01-16 20:19:38', 1, 1, 15),
(5, 'Wake Forest Trip', '2024-01-31', '2024-01-16 20:21:09', '2024-01-16 20:21:09', 1, 1, 16),
(6, 'Berkeley Recruitment', '2024-02-28', '2024-01-16 20:22:08', '2024-01-16 20:22:08', 1, 1, 9),
(7, 'Winston Salem Trip', '2024-03-13', '2024-01-16 20:23:14', '2024-01-16 20:23:14', 1, 1, 16),
(8, 'Duke Recruitment', '2024-04-18', '2024-01-16 20:23:53', '2024-01-16 20:23:53', 1, 1, 13),
(9, 'UNC Recruitment', '2024-05-15', '2024-01-16 20:24:20', '2024-01-16 20:24:20', 1, 1, 14),
(10, 'RCCC - Community Recruitment Event', '2024-08-21', '2024-01-16 20:25:21', '2024-01-16 15:30:18', 1, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `event_branding`
--

CREATE TABLE `event_branding` (
  `id` bigint(20) NOT NULL,
  `event_id` bigint(20) NOT NULL,
  `event_logo` bigint(20) DEFAULT NULL,
  `event_banner` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `event_branding`
--

INSERT INTO `event_branding` (`id`, `event_id`, `event_logo`, `event_banner`) VALUES
(2, 1, 20, 19),
(3, 2, 20, 19),
(4, 5, 20, 19);

-- --------------------------------------------------------

--
-- Table structure for table `event_slugs`
--

CREATE TABLE `event_slugs` (
  `id` bigint(20) NOT NULL,
  `event_id` bigint(20) NOT NULL,
  `slug` varchar(30) COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `event_slugs`
--

INSERT INTO `event_slugs` (`id`, `event_id`, `slug`) VALUES
(1, 1, 'wgu-job-faire'),
(2, 2, 'uncc-charlotte'),
(6, 3, 'stanly-community-outreach'),
(7, 4, 'nc-state-recruitement'),
(8, 5, 'wake-forest-trip'),
(9, 6, 'berkeley-recruitment'),
(10, 7, 'winston-salem-trip'),
(11, 8, 'duke-recruitment'),
(12, 9, 'unc-recruitment'),
(13, 10, 'rccc---community-recruitm');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_520_ci,
  `summary` text COLLATE utf8mb4_unicode_520_ci,
  `type` enum('FULL','PART','INTERN') COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'INTERN',
  `field` bigint(20) DEFAULT NULL,
  `education` bigint(20) DEFAULT NULL,
  `skills` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `name`, `description`, `summary`, `type`, `field`, `education`, `skills`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(4, 'Intern', '<p><strong>[Intro paragraph] </strong>The best job descriptions provide 2-3 sentences that will introduce the prospective interns to your company culture and working environment. This is where you can sell your opening to job seekers and set yourself apart from competing job listings.</p><p><strong>Intern Job Responsibilities:</strong></p><ul><li>Understands the overall concept of the company, including the brand, customer, product goals, and all other aspects of service.</li><li>Rotates through our divisions of responsibility and provides ideas to grow and improve the business.</li><li>Accepts designated, business-focus projects to research, propose ideas and solutions, and present final project during the internship.</li><li>Engages with customers or clients and provides service and/or sales.</li><li>Provide suggestions to management for improving customer service and internal processes.</li><li>Learns and becomes proficient on internal software systems.</li><li>Assists in creating performance reports.</li></ul><p><strong>[Work Hours &amp; Benefits]</strong> It’s always a good idea to highlight the <strong>working hours and benefits </strong>specific to your business. Potential interns want to learn things like team size, mentor-to-intern ratios, and weekly hour requirements. You can also highlight any of the benefits that set you apart, like contact with upper management or school credits.</p>', 'The best job descriptions provide 2-3 sentences that will introduce the prospective interns to your company culture and working environment. This is where you can sell your opening to job seekers.', 'INTERN', 2, 4, '[\"Communication\", \"Problem solving skills\", \"Analysis\", \"Adaptability\", \"Computer skills\", \"Initiative\", \"Critical thinking\", \"Time management\", \"Collaboration\", \"Planning and prioritizing\", \"Teamwork\", \"Work ethic\", \"Customer service\", \"Interpersonal\", \"Networking\", \"Passionate\", \"Punctuality\", \"Scripting\"]', '2024-01-29 14:38:56', '2024-01-29 19:38:56', 1, 1),
(5, 'Intern', '<p><strong>[Intro paragraph] </strong>The best job descriptions provide 2-3 sentences that will introduce the prospective interns to your company culture and working environment. This is where you can sell your opening to job seekers and set yourself apart from competing job listings.</p><p><strong>Intern Job Responsibilities:</strong></p><ul><li>Understands the overall concept of the company, including the brand, customer, product goals, and all other aspects of service.</li><li>Rotates through our divisions of responsibility and provides ideas to grow and improve the business.</li><li>Accepts designated, business-focus projects to research, propose ideas and solutions, and present final project during the internship.</li><li>Engages with customers or clients and provides service and/or sales.</li><li>Provide suggestions to management for improving customer service and internal processes.</li><li>Learns and becomes proficient on internal software systems.</li><li>Assists in creating performance reports.</li></ul><p><strong>[Work Hours &amp; Benefits]</strong> It’s always a good idea to highlight the <strong>working hours and benefits </strong>specific to your business. Potential interns want to learn things like team size, mentor-to-intern ratios, and weekly hour requirements. You can also highlight any of the benefits that set you apart, like contact with upper management or school credits.</p>', 'The best job descriptions provide 2-3 sentences that will introduce the prospective interns to your company culture and working environment. This is where you can sell your opening to job seekers.', 'INTERN', 4, 11, '[\"Communication\", \"Problem solving skills\", \"Adaptability\", \"Computer skills\", \"Initiative\", \"Critical thinking\", \"Time management\", \"Collaboration\", \"Planning and prioritizing\", \"Teamwork\", \"Work ethic\", \"Customer service\", \"Passionate\", \"Punctuality\"]', '2024-01-29 14:38:56', '2024-01-29 19:38:56', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `major`
--

CREATE TABLE `major` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `major`
--

INSERT INTO `major` (`id`, `name`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Software Development', '2024-02-27 22:09:49', '2024-02-27 22:09:49', NULL, NULL),
(2, 'Business Administration', '2024-02-27 22:09:49', '2024-02-27 22:09:49', NULL, NULL),
(3, 'Accounting', '2024-02-27 22:09:49', '2024-02-27 22:09:49', NULL, NULL),
(6, 'Computer Science', '2024-02-27 22:09:49', '2024-02-27 22:09:49', NULL, NULL),
(7, 'Marketing', '2023-12-13 00:57:25', '2023-12-13 00:57:25', NULL, NULL),
(8, 'Electrical Engineering', '2024-01-16 20:39:12', '2024-01-16 20:39:12', NULL, NULL),
(9, 'Psychology', '2024-01-16 20:42:21', '2024-01-16 20:42:21', NULL, NULL),
(10, 'Economics', '2024-01-16 20:47:01', '2024-01-16 20:47:01', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint(20) NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `filetype` varchar(5) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `filesize` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `filename`, `filetype`, `filesize`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(19, 'placeholderbanner_1920x400.png', 'png', 29410, '2024-01-24 23:20:09', 1, '2024-01-24 18:50:58', 1),
(20, 'placeholderlogo_200x200.png', 'png', 2835, '2024-01-25 01:31:24', 1, '2024-01-24 20:31:41', 1),
(21, 'TalentFlow-Logo.png', 'png', 19205, '2024-02-09 01:32:52', 1, '2024-02-09 01:32:52', 1),
(22, 'company-logo.png', 'png', 13833, '2024-02-09 19:57:25', 1, '2024-02-09 19:57:25', 1),
(23, 'company-logo.svg', 'svg', 3769, '2024-02-09 19:57:49', 1, '2024-02-09 19:57:49', 1),
(24, 'TalentFlow-Logo.svg', 'svg', 2230, '2024-02-09 20:00:28', 1, '2024-02-09 20:00:28', 1);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`) VALUES
(40, 'CAN EXPORT'),
(54, 'CONTACT STUDENT'),
(29, 'CREATE DEGREE'),
(9, 'CREATE EVENT'),
(5, 'CREATE JOB'),
(25, 'CREATE MAJOR'),
(60, 'CREATE MEDIA'),
(33, 'CREATE REPORT'),
(17, 'CREATE ROLE'),
(13, 'CREATE SCHOOL'),
(63, 'CREATE STUDENT'),
(21, 'CREATE SUBJECT'),
(1, 'CREATE USER'),
(30, 'DELETE DEGREE'),
(10, 'DELETE EVENT'),
(6, 'DELETE JOB'),
(26, 'DELETE MAJOR'),
(62, 'DELETE MEDIA'),
(34, 'DELETE REPORT'),
(18, 'DELETE ROLE'),
(14, 'DELETE SCHOOL'),
(50, 'DELETE STUDENT'),
(22, 'DELETE SUBJECT'),
(3, 'DELETE USER'),
(51, 'EXPORT ACTIVITY'),
(52, 'EXPORT CONTACT'),
(45, 'EXPORT DEGREE'),
(47, 'EXPORT EVENT'),
(43, 'EXPORT JOB'),
(46, 'EXPORT MAJOR'),
(53, 'EXPORT REPORT'),
(42, 'EXPORT SCHOOL'),
(41, 'EXPORT STUDENT'),
(44, 'EXPORT SUBJECT'),
(48, 'EXPORT USER'),
(55, 'IS ADMIN'),
(56, 'IS SUPERADMIN'),
(57, 'READ ACTIVITY'),
(58, 'READ CONTACT'),
(31, 'READ DEGREE'),
(11, 'READ EVENT'),
(7, 'READ JOB'),
(27, 'READ MAJOR'),
(59, 'READ MEDIA'),
(35, 'READ REPORT'),
(19, 'READ ROLE'),
(15, 'READ SCHOOL'),
(38, 'READ SETTINGS'),
(49, 'READ STUDENT'),
(23, 'READ SUBJECT'),
(4, 'READ USER'),
(32, 'UPDATE DEGREE'),
(12, 'UPDATE EVENT'),
(8, 'UPDATE JOB'),
(28, 'UPDATE MAJOR'),
(61, 'UPDATE MEDIA'),
(36, 'UPDATE REPORT'),
(20, 'UPDATE ROLE'),
(16, 'UPDATE SCHOOL'),
(39, 'UPDATE SETTINGS'),
(64, 'UPDATE STUDENT'),
(24, 'UPDATE SUBJECT'),
(2, 'UPDATE USER'),
(37, 'VIEW DASHBOARD');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) NOT NULL,
  `report_type` varchar(500) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `data` json NOT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `report_type`, `data`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Top Degree by School', '[{\"major\": \"Accounting\", \"degree\": \"AA - Associate of Arts\", \"school\": \"Rowan-Cabarrus Community College - RCCC\", \"student_count\": 2}, {\"major\": \"Software Development\", \"degree\": \"BScIT - Bachelor of Science in Information Technology\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Business Administration\", \"degree\": \"BA - Bachelor of Arts\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"AAS - Associate of Applied Science\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}]', 1, '2023-12-20 00:36:04', 1, '2023-12-20 00:36:04'),
(2, 'Top Degree by School', '[{\"major\": \"Accounting\", \"degree\": \"AA - Associate of Arts\", \"school\": \"Rowan-Cabarrus Community College - RCCC\", \"student_count\": 2}, {\"major\": \"Software Development\", \"degree\": \"BScIT - Bachelor of Science in Information Technology\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Business Administration\", \"degree\": \"BA - Bachelor of Arts\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"AAS - Associate of Applied Science\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}]', 1, '2023-12-20 00:41:49', 1, '2023-12-20 00:41:49'),
(5, 'Top Degree by School', '[{\"major\": \"Accounting\", \"degree\": \"AA - Associate of Arts\", \"school\": \"Rowan-Cabarrus Community College - RCCC\", \"student_count\": 2}, {\"major\": \"Software Development\", \"degree\": \"BScIT - Bachelor of Science in Information Technology\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Business Administration\", \"degree\": \"BA - Bachelor of Arts\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"AAS - Associate of Applied Science\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}]', 1, '2023-12-20 20:27:37', 1, '2023-12-20 20:27:37'),
(6, 'Major to Field Ratio by School', '[{\"ratio\": 1, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"ratio\": 1, \"school\": \"Rowan-Cabarrus Community College - RCCC\"}]', 1, '2023-12-20 21:50:45', 1, '2023-12-20 21:50:45'),
(7, 'Major to Field Ratio by School', '[{\"ratio\": 1, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"ratio\": 1, \"school\": \"Rowan-Cabarrus Community College - RCCC\"}]', 1, '2023-12-20 21:52:58', 1, '2023-12-20 21:52:58'),
(8, 'Major to Field Ratio by School', '[{\"ratio\": 1, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"ratio\": 1, \"school\": \"Rowan-Cabarrus Community College - RCCC\"}]', 1, '2023-12-20 21:53:39', 1, '2023-12-20 21:53:39'),
(9, 'Major to Field Ratio by School', '[{\"ratio\": 1, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"ratio\": 1, \"school\": \"Rowan-Cabarrus Community College - RCCC\"}]', 1, '2023-12-20 21:55:45', 1, '2023-12-20 21:55:45'),
(11, 'Major to Field Ratio by School', '[{\"ratio\": 1, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"ratio\": 1, \"school\": \"Rowan-Cabarrus Community College - RCCC\"}]', 1, '2023-12-20 22:00:32', 1, '2023-12-20 22:00:32'),
(13, 'Major to Field Ratio by School', '[{\"field\": \"Programming\", \"major\": \"Software Development\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"Supply Chain\", \"major\": \"Business Administration\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"field\": \"Finance\", \"major\": \"Accounting\", \"ratio\": 1, \"school\": \"Rowan-Cabarrus Community College - RCCC\"}]', 1, '2023-12-20 23:28:25', 1, '2023-12-20 23:28:25'),
(14, 'Jobs by Field', '[{\"jobs\": [\"Lorem Ipsum 2\", \"Job\"], \"job_count\": 2, \"job_field_id\": 4, \"job_field_name\": \"Cybersecurity\"}, {\"jobs\": [\"Packaging Operator\", \"lorem\"], \"job_count\": 2, \"job_field_id\": 6, \"job_field_name\": \"Supply Chain\"}, {\"jobs\": [\"Helpdesk\"], \"job_count\": 1, \"job_field_id\": 1, \"job_field_name\": \"IT\"}, {\"jobs\": [\"Business Analyst\"], \"job_count\": 1, \"job_field_id\": 3, \"job_field_name\": \"Analytics\"}, {\"jobs\": [\"Lorem Ipsum\"], \"job_count\": 1, \"job_field_id\": 5, \"job_field_name\": \"Finance\"}]', 1, '2023-12-21 19:39:02', 1, '2023-12-21 19:39:02'),
(15, 'Jobs by Field', '[{\"jobs\": [\"Lorem Ipsum 2\", \"Job\"], \"job_count\": 2, \"job_field_name\": \"Cybersecurity\"}, {\"jobs\": [\"Packaging Operator\", \"lorem\"], \"job_count\": 2, \"job_field_name\": \"Supply Chain\"}, {\"jobs\": [\"Helpdesk\"], \"job_count\": 1, \"job_field_name\": \"IT\"}, {\"jobs\": [\"Business Analyst\"], \"job_count\": 1, \"job_field_name\": \"Analytics\"}, {\"jobs\": [\"Lorem Ipsum\"], \"job_count\": 1, \"job_field_name\": \"Finance\"}]', 1, '2023-12-21 19:40:04', 1, '2023-12-21 19:40:04'),
(16, 'Jobs by Field', '[{\"jobs\": \"[\\\"Lorem Ipsum 2\\\",\\\"Job\\\"]\", \"job_count\": 2, \"job_field_name\": \"Cybersecurity\"}, {\"jobs\": \"[\\\"Packaging Operator\\\",\\\"lorem\\\"]\", \"job_count\": 2, \"job_field_name\": \"Supply Chain\"}, {\"jobs\": \"[\\\"Helpdesk\\\"]\", \"job_count\": 1, \"job_field_name\": \"IT\"}, {\"jobs\": \"[\\\"Business Analyst\\\"]\", \"job_count\": 1, \"job_field_name\": \"Analytics\"}, {\"jobs\": \"[\\\"Lorem Ipsum\\\"]\", \"job_count\": 1, \"job_field_name\": \"Finance\"}]', 1, '2023-12-21 19:40:46', 1, '2023-12-21 19:40:46'),
(17, 'Jobs by Field', '{\"IT\": {\"jobs\": \"[\\\"Helpdesk\\\"]\", \"job_count\": 1, \"job_field_name\": \"IT\"}, \"Finance\": {\"jobs\": \"[\\\"Lorem Ipsum\\\"]\", \"job_count\": 1, \"job_field_name\": \"Finance\"}, \"Analytics\": {\"jobs\": \"[\\\"Business Analyst\\\"]\", \"job_count\": 1, \"job_field_name\": \"Analytics\"}, \"Supply Chain\": {\"jobs\": \"[\\\"Packaging Operator\\\",\\\"lorem\\\"]\", \"job_count\": 2, \"job_field_name\": \"Supply Chain\"}, \"Cybersecurity\": {\"jobs\": \"[\\\"Lorem Ipsum 2\\\",\\\"Job\\\"]\", \"job_count\": 2, \"job_field_name\": \"Cybersecurity\"}}', 1, '2023-12-21 19:42:15', 1, '2023-12-21 19:42:15'),
(19, 'Jobs by Field', '[{\"jobs\": \"[\\\"Helpdesk\\\"]\", \"job_count\": 1, \"job_field_name\": \"IT\"}, {\"jobs\": \"[\\\"Business Analyst\\\"]\", \"job_count\": 1, \"job_field_name\": \"Analytics\"}, {\"jobs\": \"[\\\"Lorem Ipsum 2\\\",\\\"Job\\\"]\", \"job_count\": 2, \"job_field_name\": \"Cybersecurity\"}, {\"jobs\": \"[\\\"Lorem Ipsum\\\"]\", \"job_count\": 1, \"job_field_name\": \"Finance\"}, {\"jobs\": \"[\\\"Packaging Operator\\\",\\\"lorem\\\"]\", \"job_count\": 2, \"job_field_name\": \"Supply Chain\"}]', 1, '2023-12-21 19:42:48', 1, '2023-12-21 19:42:48'),
(26, 'Major to Field Ratio by School', '[{\"field\": \"Programming\", \"major\": \"Software Development\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"Supply Chain\", \"major\": \"Business Administration\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"field\": \"Finance\", \"major\": \"Accounting\", \"ratio\": 1, \"school\": \"Rowan-Cabarrus Community College - RCCC\"}]', 1, '2023-12-22 01:13:09', 1, '2023-12-22 01:13:09'),
(27, 'Top Degree by School', '[{\"major\": \"Accounting\", \"degree\": \"AA - Associate of Arts\", \"school\": \"Rowan-Cabarrus Community College - RCCC\", \"student_count\": 2}, {\"major\": \"Software Development\", \"degree\": \"BScIT - Bachelor of Science in Information Technology\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Business Administration\", \"degree\": \"BA - Bachelor of Arts\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"AAS - Associate of Applied Science\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}]', 1, '2023-12-29 00:04:42', 1, '2023-12-29 00:04:42'),
(28, 'Jobs by Field', '[{\"jobs\": \"Helpdesk\", \"job_count\": 1, \"job_field_name\": \"IT\"}, {\"jobs\": \"Business Analyst\", \"job_count\": 1, \"job_field_name\": \"Analytics\"}, {\"jobs\": \"Job\", \"job_count\": 1, \"job_field_name\": \"Cybersecurity\"}, {\"jobs\": \"Packaging Operator\", \"job_count\": 1, \"job_field_name\": \"Supply Chain\"}]', 1, '2024-01-09 00:57:49', 1, '2024-01-09 00:57:49'),
(29, 'Top Field by School', '[{\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Programming\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Supply Chain\"}, {\"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1, \"field_of_study\": \"IT\"}]', 1, '2024-01-16 20:07:19', 1, '2024-01-16 20:07:19'),
(30, 'Top Degree by School', '[{\"major\": \"Software Development\", \"degree\": \"BScIT - Bachelor of Science in Information Technology\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Business Administration\", \"degree\": \"BA - Bachelor of Arts\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Duke University\", \"student_count\": 1}]', 1, '2024-01-16 20:34:49', 1, '2024-01-16 20:34:49'),
(31, 'Top Field by School', '[{\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Programming\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Supply Chain\"}, {\"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1, \"field_of_study\": \"IT\"}, {\"school\": \"Duke University\", \"student_count\": 1, \"field_of_study\": \"IT\"}]', 1, '2024-01-16 20:35:03', 1, '2024-01-16 20:35:03'),
(33, 'Top Degree by School', '[{\"major\": \"Software Development\", \"degree\": \"BScIT - Bachelor of Science in Information Technology\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Business Administration\", \"degree\": \"BA - Bachelor of Arts\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Duke University\", \"student_count\": 1}, {\"major\": \"Electrical Engineering\", \"degree\": \"MS - Master of Science\", \"school\": \"Stanford University\", \"student_count\": 1}]', 1, '2024-01-16 20:39:43', 1, '2024-01-16 20:39:43'),
(34, 'Top Field by School', '[{\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Programming\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Supply Chain\"}, {\"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1, \"field_of_study\": \"IT\"}, {\"school\": \"Duke University\", \"student_count\": 1, \"field_of_study\": \"IT\"}, {\"school\": \"Stanford University\", \"student_count\": 1, \"field_of_study\": \"Cybersecurity\"}]', 1, '2024-01-16 20:40:00', 1, '2024-01-16 20:40:00'),
(35, 'Contact Follow-Up Percentage', '[{\"total\": 5, \"percentage\": \"40.00000\", \"top_sending_user\": \"admin\"}]', 1, '2024-01-16 20:40:08', 1, '2024-01-16 20:40:08'),
(36, 'Contact Follow-Up Percentage', '[{\"total\": 6, \"percentage\": \"33.33333\", \"top_sending_user\": \"admin\"}]', 1, '2024-01-16 20:43:11', 1, '2024-01-16 20:43:11'),
(37, 'Jobs by Field', '[{\"jobs\": \"Helpdesk\", \"job_count\": 1, \"job_field_name\": \"IT\"}, {\"jobs\": \"Business Analyst\", \"job_count\": 1, \"job_field_name\": \"Analytics\"}, {\"jobs\": \"Job\", \"job_count\": 1, \"job_field_name\": \"Cybersecurity\"}, {\"jobs\": \"Packaging Operator\", \"job_count\": 1, \"job_field_name\": \"Supply Chain\"}]', 1, '2024-01-16 20:43:21', 1, '2024-01-16 20:43:21'),
(38, 'Major to Field Ratio by School', '[{\"field\": \"Programming\", \"major\": \"Software Development\", \"ratio\": 0.5, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"Supply Chain\", \"major\": \"Business Administration\", \"ratio\": 0.5, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"field\": \"Cybersecurity\", \"major\": \"Electrical Engineering\", \"ratio\": 1, \"school\": \"Stanford University\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 1, \"school\": \"Duke University\"}, {\"field\": \"Sales\", \"major\": \"Psychology\", \"ratio\": 1, \"school\": \"Wake Forest University\"}]', 1, '2024-01-16 20:43:32', 1, '2024-01-16 20:43:32'),
(39, 'Top Field by School', '[{\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Programming\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Supply Chain\"}, {\"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1, \"field_of_study\": \"IT\"}, {\"school\": \"Duke University\", \"student_count\": 1, \"field_of_study\": \"IT\"}, {\"school\": \"Stanford University\", \"student_count\": 1, \"field_of_study\": \"Cybersecurity\"}, {\"school\": \"Wake Forest University\", \"student_count\": 1, \"field_of_study\": \"Sales\"}]', 1, '2024-01-16 20:43:44', 1, '2024-01-16 20:43:44'),
(40, 'Top Degree by School', '[{\"major\": \"Software Development\", \"degree\": \"BScIT - Bachelor of Science in Information Technology\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Business Administration\", \"degree\": \"BA - Bachelor of Arts\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Duke University\", \"student_count\": 1}, {\"major\": \"Electrical Engineering\", \"degree\": \"MS - Master of Science\", \"school\": \"Stanford University\", \"student_count\": 1}, {\"major\": \"Psychology\", \"degree\": \"MA - Master of Arts\", \"school\": \"Wake Forest University\", \"student_count\": 1}]', 1, '2024-01-16 20:44:13', 1, '2024-01-16 20:44:13'),
(41, 'Contact Follow-Up Percentage', '[{\"total\": 8, \"percentage\": \"25.00000\", \"top_sending_user\": \"admin\"}]', 1, '2024-01-16 20:48:56', 1, '2024-01-16 20:48:56'),
(42, 'Jobs by Field', '[{\"jobs\": \"Helpdesk\", \"job_count\": 1, \"job_field_name\": \"IT\"}, {\"jobs\": \"Business Analyst\", \"job_count\": 1, \"job_field_name\": \"Analytics\"}, {\"jobs\": \"Job\", \"job_count\": 1, \"job_field_name\": \"Cybersecurity\"}, {\"jobs\": \"Packaging Operator\", \"job_count\": 1, \"job_field_name\": \"Supply Chain\"}]', 1, '2024-01-16 20:49:02', 1, '2024-01-16 20:49:02'),
(43, 'Major to Field Ratio by School', '[{\"field\": \"Programming\", \"major\": \"Software Development\", \"ratio\": 0.5, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"Supply Chain\", \"major\": \"Business Administration\", \"ratio\": 0.5, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"field\": \"Cybersecurity\", \"major\": \"Electrical Engineering\", \"ratio\": 1, \"school\": \"Stanford University\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 0.67, \"school\": \"Duke University\"}, {\"field\": \"Finance\", \"major\": \"Economics\", \"ratio\": 0.33, \"school\": \"Duke University\"}, {\"field\": \"Sales\", \"major\": \"Psychology\", \"ratio\": 1, \"school\": \"Wake Forest University\"}]', 1, '2024-01-16 20:49:08', 1, '2024-01-16 20:49:08'),
(44, 'Top Degree by School', '[{\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Duke University\", \"student_count\": 2}, {\"major\": \"Economics\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Duke University\", \"student_count\": 1}, {\"major\": \"Software Development\", \"degree\": \"BScIT - Bachelor of Science in Information Technology\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Business Administration\", \"degree\": \"BA - Bachelor of Arts\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}, {\"major\": \"Electrical Engineering\", \"degree\": \"MS - Master of Science\", \"school\": \"Stanford University\", \"student_count\": 1}, {\"major\": \"Psychology\", \"degree\": \"MA - Master of Arts\", \"school\": \"Wake Forest University\", \"student_count\": 1}]', 1, '2024-01-16 20:49:22', 1, '2024-01-16 20:49:22'),
(45, 'Top Field by School', '[{\"school\": \"Duke University\", \"student_count\": 2, \"field_of_study\": \"IT\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Programming\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Supply Chain\"}, {\"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1, \"field_of_study\": \"IT\"}, {\"school\": \"Stanford University\", \"student_count\": 1, \"field_of_study\": \"Cybersecurity\"}, {\"school\": \"Wake Forest University\", \"student_count\": 1, \"field_of_study\": \"Sales\"}, {\"school\": \"Duke University\", \"student_count\": 1, \"field_of_study\": \"Finance\"}]', 1, '2024-01-16 20:49:34', 1, '2024-01-16 20:49:34'),
(46, 'Top Degree by School', '[{\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Duke University\", \"student_count\": 2}, {\"major\": \"Economics\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Duke University\", \"student_count\": 1}, {\"major\": \"Software Development\", \"degree\": \"BScIT - Bachelor of Science in Information Technology\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Software Development\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Business Administration\", \"degree\": \"BA - Bachelor of Arts\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}, {\"major\": \"Electrical Engineering\", \"degree\": \"MS - Master of Science\", \"school\": \"Stanford University\", \"student_count\": 1}, {\"major\": \"Psychology\", \"degree\": \"MA - Master of Arts\", \"school\": \"Wake Forest University\", \"student_count\": 1}]', 1, '2024-01-16 20:52:11', 1, '2024-01-16 20:52:11'),
(47, 'Top Field by School', '[{\"school\": \"Duke University\", \"student_count\": 2, \"field_of_study\": \"IT\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"IT\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Programming\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Supply Chain\"}, {\"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1, \"field_of_study\": \"IT\"}, {\"school\": \"Stanford University\", \"student_count\": 1, \"field_of_study\": \"Cybersecurity\"}, {\"school\": \"Wake Forest University\", \"student_count\": 1, \"field_of_study\": \"Sales\"}, {\"school\": \"Duke University\", \"student_count\": 1, \"field_of_study\": \"Finance\"}]', 1, '2024-01-16 20:52:23', 1, '2024-01-16 20:52:23'),
(48, 'Top Field by School', '[{\"school\": \"Duke University\", \"student_count\": 2, \"field_of_study\": \"IT\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"IT\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Programming\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Supply Chain\"}, {\"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1, \"field_of_study\": \"IT\"}, {\"school\": \"Stanford University\", \"student_count\": 1, \"field_of_study\": \"Cybersecurity\"}, {\"school\": \"Wake Forest University\", \"student_count\": 1, \"field_of_study\": \"Sales\"}, {\"school\": \"Duke University\", \"student_count\": 1, \"field_of_study\": \"Finance\"}]', 1, '2024-01-16 20:52:40', 1, '2024-01-16 20:52:40'),
(49, 'Major to Field Ratio by School', '[{\"field\": \"IT\", \"major\": \"Software Development\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"Programming\", \"major\": \"Software Development\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"Supply Chain\", \"major\": \"Business Administration\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"field\": \"Cybersecurity\", \"major\": \"Electrical Engineering\", \"ratio\": 1, \"school\": \"Stanford University\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 0.67, \"school\": \"Duke University\"}, {\"field\": \"Finance\", \"major\": \"Economics\", \"ratio\": 0.33, \"school\": \"Duke University\"}, {\"field\": \"Sales\", \"major\": \"Psychology\", \"ratio\": 1, \"school\": \"Wake Forest University\"}]', 1, '2024-01-16 20:52:49', 1, '2024-01-16 20:52:49'),
(50, 'Contact Follow-Up Percentage', '[{\"total\": 9, \"percentage\": \"22.22222\", \"top_sending_user\": \"admin\"}]', 1, '2024-01-16 20:52:56', 1, '2024-01-16 20:52:56'),
(51, 'Top Degree by School', '[{\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Duke University\", \"student_count\": 2}, {\"major\": \"Economics\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Duke University\", \"student_count\": 1}, {\"major\": \"Software Development\", \"degree\": \"BScIT - Bachelor of Science in Information Technology\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Software Development\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Business Administration\", \"degree\": \"BA - Bachelor of Arts\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}, {\"major\": \"Electrical Engineering\", \"degree\": \"MS - Master of Science\", \"school\": \"Stanford University\", \"student_count\": 1}, {\"major\": \"Psychology\", \"degree\": \"MA - Master of Arts\", \"school\": \"Wake Forest University\", \"student_count\": 1}]', 1, '2024-01-17 00:32:22', 1, '2024-01-17 00:32:22'),
(52, 'Contact Follow-Up Percentage', '[{\"total\": 20, \"percentage\": \"65.00000\", \"top_sending_user\": \"admin\"}]', 1, '2024-01-17 23:17:38', 1, '2024-01-17 23:17:38'),
(53, 'Top Degree by School', '[{\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Duke University\", \"student_count\": 2}, {\"major\": \"Economics\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Duke University\", \"student_count\": 1}, {\"major\": \"Software Development\", \"degree\": \"BScIT - Bachelor of Science in Information Technology\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Software Development\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Business Administration\", \"degree\": \"BA - Bachelor of Arts\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}, {\"major\": \"Electrical Engineering\", \"degree\": \"MS - Master of Science\", \"school\": \"Stanford University\", \"student_count\": 1}, {\"major\": \"Psychology\", \"degree\": \"MA - Master of Arts\", \"school\": \"Wake Forest University\", \"student_count\": 1}]', 1, '2024-01-17 23:48:31', 1, '2024-01-17 23:48:31'),
(54, 'Top Field by School', '[{\"school\": \"Duke University\", \"student_count\": 2, \"field_of_study\": \"IT\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"IT\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Programming\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Supply Chain\"}, {\"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1, \"field_of_study\": \"IT\"}, {\"school\": \"Stanford University\", \"student_count\": 1, \"field_of_study\": \"Cybersecurity\"}, {\"school\": \"Wake Forest University\", \"student_count\": 1, \"field_of_study\": \"Sales\"}, {\"school\": \"Duke University\", \"student_count\": 1, \"field_of_study\": \"Finance\"}]', 1, '2024-01-17 23:48:33', 1, '2024-01-17 23:48:33'),
(55, 'Major to Field Ratio by School', '[{\"field\": \"IT\", \"major\": \"Software Development\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"Programming\", \"major\": \"Software Development\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"Supply Chain\", \"major\": \"Business Administration\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"field\": \"Cybersecurity\", \"major\": \"Electrical Engineering\", \"ratio\": 1, \"school\": \"Stanford University\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 0.67, \"school\": \"Duke University\"}, {\"field\": \"Finance\", \"major\": \"Economics\", \"ratio\": 0.33, \"school\": \"Duke University\"}, {\"field\": \"Sales\", \"major\": \"Psychology\", \"ratio\": 1, \"school\": \"Wake Forest University\"}]', 1, '2024-01-17 23:48:35', 1, '2024-01-17 23:48:35'),
(56, 'Jobs by Field', '[{\"jobs\": \"Helpdesk\", \"job_count\": 1, \"job_field_name\": \"IT\"}, {\"jobs\": \"Business Analyst\", \"job_count\": 1, \"job_field_name\": \"Analytics\"}, {\"jobs\": \"Job\", \"job_count\": 1, \"job_field_name\": \"Cybersecurity\"}, {\"jobs\": \"Packaging Operator\", \"job_count\": 1, \"job_field_name\": \"Supply Chain\"}]', 1, '2024-01-17 23:48:37', 1, '2024-01-17 23:48:37'),
(57, 'Contact Follow-Up Percentage', '[{\"total\": 21, \"percentage\": \"66.66667\", \"top_sending_user\": \"admin\"}]', 1, '2024-01-17 23:48:39', 1, '2024-01-17 23:48:39'),
(58, 'Jobs by Field', '[{\"jobs\": \"Intern\", \"job_count\": 1, \"job_field_name\": \"Programming\"}]', 1, '2024-01-26 22:17:18', 1, '2024-01-26 22:17:18'),
(59, 'Top Degree by School', '[{\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Duke University\", \"student_count\": 2}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}, {\"major\": \"Electrical Engineering\", \"degree\": \"MS - Master of Science\", \"school\": \"Stanford University\", \"student_count\": 1}, {\"major\": \"Psychology\", \"degree\": \"MA - Master of Arts\", \"school\": \"Wake Forest University\", \"student_count\": 1}, {\"major\": \"Economics\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Duke University\", \"student_count\": 1}]', 1, '2024-01-30 01:29:39', 1, '2024-01-30 01:29:39'),
(60, 'Top Degree by School', '[{\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Duke University\", \"student_count\": 2}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}, {\"major\": \"Electrical Engineering\", \"degree\": \"MS - Master of Science\", \"school\": \"Stanford University\", \"student_count\": 1}, {\"major\": \"Psychology\", \"degree\": \"MA - Master of Arts\", \"school\": \"Wake Forest University\", \"student_count\": 1}, {\"major\": \"Economics\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Duke University\", \"student_count\": 1}]', 1, '2024-02-01 00:55:48', 1, '2024-02-01 00:55:48'),
(61, 'Jobs by Field', '[{\"jobs\": \"Intern\", \"job_count\": 1, \"job_field_name\": \"Programming\"}]', 1, '2024-02-01 01:16:29', 1, '2024-02-01 01:16:29'),
(62, 'Jobs by Field', '[{\"jobs\": \"Intern\", \"job_count\": 1, \"job_field_name\": \"Programming\"}, {\"jobs\": \"Intern\", \"job_count\": 1, \"job_field_name\": \"Cybersecurity\"}]', 1, '2024-02-01 01:18:31', 1, '2024-02-01 01:18:31'),
(63, 'Jobs by Field', '[{\"jobs\": \"Intern\", \"job_count\": 1, \"job_field_name\": \"Programming\"}, {\"jobs\": \"Intern\", \"job_count\": 1, \"job_field_name\": \"Cybersecurity\"}]', 1, '2024-02-01 01:28:31', 1, '2024-02-01 01:28:31'),
(64, 'Major to Field Ratio by School', '[{\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"field\": \"Cybersecurity\", \"major\": \"Electrical Engineering\", \"ratio\": 1, \"school\": \"Stanford University\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 0.67, \"school\": \"Duke University\"}, {\"field\": \"Finance\", \"major\": \"Economics\", \"ratio\": 0.33, \"school\": \"Duke University\"}, {\"field\": \"Sales\", \"major\": \"Psychology\", \"ratio\": 1, \"school\": \"Wake Forest University\"}]', 1, '2024-02-01 01:31:12', 1, '2024-02-01 01:31:12'),
(65, 'Jobs by Field', '[{\"jobs\": \"Intern\", \"job_count\": 1, \"job_field_name\": \"Programming\"}, {\"jobs\": \"Intern\", \"job_count\": 1, \"job_field_name\": \"Cybersecurity\"}]', 1, '2024-02-01 22:03:39', 1, '2024-02-01 22:03:39'),
(66, 'Contact Follow-Up Percentage', '[{\"total\": 17, \"percentage\": \"64.70588\", \"top_sending_user\": \"admin\"}]', 1, '2024-02-02 20:43:58', 1, '2024-02-02 20:43:58'),
(67, 'Contact Follow-Up Percentage', '[{\"total\": 18, \"percentage\": \"61.11111\", \"top_sending_user\": \"admin\"}]', 1, '2024-02-05 10:02:59', 1, '2024-02-05 10:02:59'),
(68, 'Top Degree by School', '[{\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Duke University\", \"student_count\": 2}, {\"major\": \"Electrical Engineering\", \"degree\": \"MS - Master of Science\", \"school\": \"Stanford University\", \"student_count\": 1}, {\"major\": \"Psychology\", \"degree\": \"MA - Master of Arts\", \"school\": \"Wake Forest University\", \"student_count\": 1}, {\"major\": \"Economics\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Duke University\", \"student_count\": 1}, {\"major\": \"Accounting\", \"degree\": \"AA - Associate of Arts\", \"school\": \"Columbia University\", \"student_count\": 1}]', 1, '2024-02-23 21:24:39', 1, '2024-02-23 21:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

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

CREATE TABLE `role_has_permission` (
  `id` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `permission_id` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

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
(49, 3, 36, '2023-12-22 23:59:56', 1, '2023-12-22 23:59:56', 1),
(51, 4, 2, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(52, 4, 4, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(53, 4, 6, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(54, 4, 7, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(55, 4, 8, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(56, 4, 10, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(57, 4, 11, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(58, 4, 12, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(59, 4, 14, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(60, 4, 15, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(61, 4, 16, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(62, 4, 18, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(63, 4, 19, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(64, 4, 20, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(65, 4, 22, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(66, 4, 23, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(67, 4, 24, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(68, 4, 26, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(69, 4, 27, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(70, 4, 28, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(71, 4, 30, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(72, 4, 31, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(73, 4, 32, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(74, 4, 34, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(75, 4, 35, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(76, 4, 36, '2023-12-27 19:35:01', 1, '2023-12-27 19:35:01', 1),
(77, 3, 37, '2024-01-04 21:43:03', 1, '2024-01-04 21:43:03', 1),
(78, 3, 38, '2024-01-04 21:43:03', 1, '2024-01-04 21:43:03', 1),
(79, 3, 39, '2024-01-04 21:43:03', 1, '2024-01-04 21:43:03', 1),
(80, 1, 37, '2024-01-04 21:43:17', 1, '2024-01-04 21:43:17', 1),
(81, 1, 38, '2024-01-04 21:43:17', 1, '2024-01-04 21:43:17', 1),
(82, 1, 15, '2024-01-04 23:41:47', 1, '2024-01-04 23:41:47', 1),
(83, 1, 16, '2024-01-04 23:46:27', 1, '2024-01-04 23:46:27', 1),
(84, 1, 49, '2024-01-05 00:11:01', 1, '2024-01-05 00:11:01', 1),
(85, 1, 41, '2024-01-05 00:20:29', 1, '2024-01-05 00:20:29', 1),
(86, 1, 42, '2024-01-05 00:21:07', 1, '2024-01-05 00:21:07', 1),
(87, 1, 11, '2024-01-05 01:03:57', 1, '2024-01-05 01:03:57', 1),
(88, 1, 12, '2024-01-05 01:48:13', 1, '2024-01-05 01:48:13', 1),
(89, 1, 13, '2024-01-05 20:02:17', 1, '2024-01-05 20:02:17', 1),
(90, 1, 14, '2024-01-05 20:02:17', 1, '2024-01-05 20:02:17', 1),
(91, 1, 9, '2024-01-05 21:25:13', 1, '2024-01-05 21:25:13', 1),
(92, 1, 10, '2024-01-05 21:25:13', 1, '2024-01-05 21:25:13', 1),
(93, 1, 7, '2024-01-08 23:40:11', 1, '2024-01-08 23:40:11', 1),
(94, 1, 5, '2024-01-08 23:51:04', 1, '2024-01-08 23:51:04', 1),
(95, 1, 6, '2024-01-08 23:51:04', 1, '2024-01-08 23:51:04', 1),
(96, 1, 8, '2024-01-08 23:51:04', 1, '2024-01-08 23:51:04', 1),
(97, 1, 21, '2024-01-09 01:29:06', 1, '2024-01-09 01:29:06', 1),
(98, 1, 22, '2024-01-09 01:29:06', 1, '2024-01-09 01:29:06', 1),
(99, 1, 23, '2024-01-09 01:29:06', 1, '2024-01-09 01:29:06', 1),
(100, 1, 24, '2024-01-09 01:29:06', 1, '2024-01-09 01:29:06', 1),
(101, 1, 43, '2024-01-09 01:29:06', 1, '2024-01-09 01:29:06', 1),
(102, 1, 44, '2024-01-09 01:29:06', 1, '2024-01-09 01:29:06', 1),
(103, 1, 47, '2024-01-09 01:29:06', 1, '2024-01-09 01:29:06', 1),
(104, 1, 25, '2024-01-10 20:22:30', 1, '2024-01-10 20:22:30', 1),
(105, 1, 26, '2024-01-10 20:22:30', 1, '2024-01-10 20:22:30', 1),
(106, 1, 27, '2024-01-10 20:22:30', 1, '2024-01-10 20:22:30', 1),
(107, 1, 28, '2024-01-10 20:22:30', 1, '2024-01-10 20:22:30', 1),
(108, 1, 50, '2024-01-10 21:55:48', 1, '2024-01-10 21:55:48', 1),
(109, 1, 54, '2024-01-10 22:55:57', 1, '2024-01-10 22:55:57', 1),
(110, 1, 29, '2024-01-11 00:38:50', 1, '2024-01-11 00:38:50', 1),
(111, 1, 30, '2024-01-11 00:38:50', 1, '2024-01-11 00:38:50', 1),
(112, 1, 31, '2024-01-11 00:38:50', 1, '2024-01-11 00:38:50', 1),
(113, 1, 32, '2024-01-11 00:38:50', 1, '2024-01-11 00:38:50', 1),
(114, 3, 40, '2024-01-11 01:15:09', 1, '2024-01-11 01:15:09', 1),
(115, 3, 41, '2024-01-11 01:15:09', 1, '2024-01-11 01:15:09', 1),
(116, 3, 42, '2024-01-11 01:15:09', 1, '2024-01-11 01:15:09', 1),
(117, 3, 43, '2024-01-11 01:15:09', 1, '2024-01-11 01:15:09', 1),
(118, 3, 44, '2024-01-11 01:15:09', 1, '2024-01-11 01:15:09', 1),
(119, 3, 45, '2024-01-11 01:15:09', 1, '2024-01-11 01:15:09', 1),
(120, 3, 46, '2024-01-11 01:15:09', 1, '2024-01-11 01:15:09', 1),
(121, 3, 47, '2024-01-11 01:15:09', 1, '2024-01-11 01:15:09', 1),
(122, 3, 48, '2024-01-11 01:15:09', 1, '2024-01-11 01:15:09', 1),
(123, 3, 49, '2024-01-11 01:15:09', 1, '2024-01-11 01:15:09', 1),
(124, 3, 50, '2024-01-11 01:15:09', 1, '2024-01-11 01:15:09', 1),
(125, 3, 51, '2024-01-11 01:15:09', 1, '2024-01-11 01:15:09', 1),
(126, 3, 52, '2024-01-11 01:15:09', 1, '2024-01-11 01:15:09', 1),
(127, 3, 53, '2024-01-11 01:15:09', 1, '2024-01-11 01:15:09', 1),
(128, 3, 54, '2024-01-11 01:15:09', 1, '2024-01-11 01:15:09', 1),
(129, 3, 55, '2024-01-11 01:15:09', 1, '2024-01-11 01:15:09', 1),
(130, 3, 56, '2024-01-11 01:15:09', 1, '2024-01-11 01:15:09', 1),
(131, 1, 17, '2024-01-11 01:16:03', 1, '2024-01-11 01:16:03', 1),
(132, 1, 18, '2024-01-11 01:16:03', 1, '2024-01-11 01:16:03', 1),
(133, 1, 19, '2024-01-11 01:16:03', 1, '2024-01-11 01:16:03', 1),
(134, 1, 20, '2024-01-11 01:16:03', 1, '2024-01-11 01:16:03', 1),
(135, 1, 33, '2024-01-11 01:16:03', 1, '2024-01-11 01:16:03', 1),
(136, 1, 34, '2024-01-11 01:16:03', 1, '2024-01-11 01:16:03', 1),
(137, 1, 35, '2024-01-11 01:16:03', 1, '2024-01-11 01:16:03', 1),
(138, 1, 36, '2024-01-11 01:16:03', 1, '2024-01-11 01:16:03', 1),
(139, 1, 40, '2024-01-11 01:16:03', 1, '2024-01-11 01:16:03', 1),
(140, 1, 45, '2024-01-11 01:16:03', 1, '2024-01-11 01:16:03', 1),
(141, 1, 46, '2024-01-11 01:16:03', 1, '2024-01-11 01:16:03', 1),
(142, 1, 48, '2024-01-11 01:16:03', 1, '2024-01-11 01:16:03', 1),
(143, 1, 51, '2024-01-11 01:16:03', 1, '2024-01-11 01:16:03', 1),
(144, 1, 52, '2024-01-11 01:16:03', 1, '2024-01-11 01:16:03', 1),
(145, 1, 53, '2024-01-11 01:16:03', 1, '2024-01-11 01:16:03', 1),
(147, 1, 39, '2024-01-13 00:19:30', 1, '2024-01-13 00:19:30', 1),
(148, 1, 57, '2024-01-15 19:59:22', 1, '2024-01-15 19:59:22', 1),
(151, 1, 55, '2024-01-15 21:27:49', 1, '2024-01-15 21:27:49', 1),
(152, 3, 57, '2024-01-15 23:14:50', 1, '2024-01-15 23:14:50', 1),
(153, 3, 58, '2024-01-15 23:14:50', 1, '2024-01-15 23:14:50', 1),
(154, 11, 37, '2024-01-17 02:57:50', 1, '2024-01-17 02:57:50', 1),
(155, 3, 59, '2024-01-23 19:05:31', 1, '2024-01-23 19:05:31', 1),
(156, 3, 60, '2024-01-23 19:05:31', 1, '2024-01-23 19:05:31', 1),
(157, 3, 61, '2024-01-23 19:05:31', 1, '2024-01-23 19:05:31', 1),
(158, 3, 62, '2024-01-23 19:05:31', 1, '2024-01-23 19:05:31', 1),
(159, 1, 58, '2024-02-21 02:20:45', 1, '2024-02-21 02:20:45', 1),
(160, 1, 59, '2024-02-21 02:20:45', 1, '2024-02-21 02:20:45', 1),
(161, 1, 60, '2024-02-21 02:20:45', 1, '2024-02-21 02:20:45', 1),
(162, 1, 61, '2024-02-21 02:20:45', 1, '2024-02-21 02:20:45', 1),
(163, 1, 62, '2024-02-21 02:20:45', 1, '2024-02-21 02:20:45', 1),
(164, 1, 63, '2024-02-26 22:29:21', 1, '2024-02-26 22:29:21', 1),
(165, 1, 64, '2024-02-26 22:29:21', 1, '2024-02-26 22:29:21', 1),
(166, 3, 63, '2024-02-26 22:29:32', 1, '2024-02-26 22:29:32', 1),
(167, 3, 64, '2024-02-26 22:29:32', 1, '2024-02-26 22:29:32', 1);

-- --------------------------------------------------------

--
-- Table structure for table `school`
--

CREATE TABLE `school` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `address` varchar(80) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `city` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `state` char(2) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `zipcode` varchar(15) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `school`
--

INSERT INTO `school` (`id`, `name`, `address`, `city`, `state`, `zipcode`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Western Governor&#039;s University - WGU', '4001 700 East', 'Millcreek', 'UT', '84107', 1, 1, '2023-11-09 20:28:23', '2024-01-25 01:32:11'),
(2, 'University of North Carolina at Charlotte - UNCC', '9201 University City Blvd', 'Charlotte', 'NC', '28223', 1, 1, '2023-10-24 17:47:54', '2024-01-25 01:32:38'),
(3, 'Stanly Community College - SCC', '141 College Drive', 'Albemarle', 'NC', '28001', 1, 1, '2023-11-02 17:35:22', '2024-01-25 01:32:50'),
(4, 'Rowan-Cabarrus Community College - RCCC', '1333 Jake Alexander Blvd., S.', 'Salisbury', 'NC', '28146', 1, 1, '2023-12-07 16:05:07', '2024-01-25 01:38:01'),
(5, 'University of North Carolina at Greensboro - UNCG', '1400 Spring Garden Street', 'Greensboro', 'NC', '27412', 1, 1, '2023-12-08 00:17:09', '2024-01-25 01:33:30'),
(6, 'Harvard University', '86 Brattle Street', 'Cambridge', 'MA', '02138', 1, 1, '2024-01-16 19:47:09', '2024-01-25 01:33:44'),
(7, 'Stanford University', '450 Serra Mall', 'Stanford', 'CA', '94305', 1, 1, '2024-01-16 19:49:48', '2024-01-25 01:33:58'),
(8, 'Massachusetts Institute of Technology - MIT', '77 Massachusetts Ave', 'Cambridge', 'MA', '02139', 1, 1, '2024-01-16 19:52:29', '2024-01-25 01:34:12'),
(9, 'University of California, Berkeley', '110 Sproul Hall #4206', 'Berkeley', 'CA', '94720', 1, 1, '2024-01-16 19:53:40', '2024-01-25 01:34:29'),
(10, 'Yale University', '63 High Street', 'New Haven', 'CT', '06511', 1, 1, '2024-01-16 19:56:36', '2024-01-25 01:34:46'),
(11, 'University of Chicago', '5801 S Ellis Ave', 'Chicago', 'IL', '60637', 1, 1, '2024-01-16 19:58:05', '2024-01-25 01:35:03'),
(12, 'Columbia University', '116th St &amp; Broadway', 'New York', 'NY', '10027', 1, 1, '2024-01-16 19:59:50', '2024-01-25 01:35:18'),
(13, 'Duke University', '103 Allen Building', 'Durham', 'NC', '27708', 1, 1, '2024-01-16 20:01:09', '2024-01-25 01:35:32'),
(14, 'University of North Carolina at Chapel Hill - UNC Chapel Hill', '103 South Bldg Cb 9100', 'Chapel Hill', 'NC', '27599', 1, 1, '2024-01-16 20:02:59', '2024-01-25 01:35:55'),
(15, 'North Carolina State University', '2101 Hillsborough Street', 'Raleigh', 'NC', '27695', 1, 1, '2024-01-16 20:04:51', '2024-01-25 01:36:10'),
(16, 'Wake Forest University', '1834 Wake Forest Road', 'Winston-Salem', 'NC', '27109', 1, 1, '2024-01-16 20:06:30', '2024-01-25 01:36:27');

-- --------------------------------------------------------

--
-- Table structure for table `school_branding`
--

CREATE TABLE `school_branding` (
  `id` bigint(20) NOT NULL,
  `school_id` bigint(20) DEFAULT NULL,
  `school_logo` bigint(20) DEFAULT NULL,
  `school_color` varchar(8) COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `school_branding`
--

INSERT INTO `school_branding` (`id`, `school_id`, `school_logo`, `school_color`) VALUES
(1, 1, 20, '#003057'),
(2, 3, 20, '#ffd200'),
(3, 2, 20, '#005035'),
(4, 4, 20, '#002f6d'),
(5, 5, 20, '#0f2044'),
(6, 6, 20, '#a51c30'),
(7, 7, 20, '#8c1515'),
(8, 8, 20, '#750014'),
(9, 9, 20, '#003262'),
(10, 10, 20, '#00356b'),
(11, 11, 20, '#800000'),
(12, 12, 20, '#0849a3'),
(13, 13, 20, '#012169'),
(14, 14, 20, '#4b9cd3'),
(15, 15, 20, '#cc0000'),
(16, 16, 20, '#8c6d2c');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `isSet` enum('SET') COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `app_name` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `app_url` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `company_name` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `company_url` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `company_logo` bigint(20) DEFAULT NULL,
  `company_address` varchar(80) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `company_city` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `company_state` char(2) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `company_zip` varchar(15) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `company_phone` varchar(15) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `app_logo` bigint(20) DEFAULT NULL,
  `contact_email` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mail_from_address` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mail_from_name` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mail_mailer` enum('smtp') COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mail_host` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mail_port` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mail_auth_req` tinyint(1) DEFAULT NULL,
  `mail_username` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mail_password` varchar(500) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mail_encryption` enum('ssl','tls') COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `privacy_policy` longtext COLLATE utf8mb4_unicode_520_ci,
  `terms_conditions` longtext COLLATE utf8mb4_unicode_520_ci,
  `hotjar_enable` tinyint(1) DEFAULT NULL,
  `hotjar_id` varchar(20) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `hotjar_version` int(11) DEFAULT NULL,
  `ga_enable` tinyint(1) DEFAULT NULL,
  `ga_id` varchar(20) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`isSet`, `app_name`, `app_url`, `company_name`, `company_url`, `company_logo`, `company_address`, `company_city`, `company_state`, `company_zip`, `company_phone`, `app_logo`, `contact_email`, `mail_from_address`, `mail_from_name`, `mail_mailer`, `mail_host`, `mail_port`, `mail_auth_req`, `mail_username`, `mail_password`, `mail_encryption`, `privacy_policy`, `terms_conditions`, `hotjar_enable`, `hotjar_id`, `hotjar_version`, `ga_enable`, `ga_id`) VALUES
('SET', 'TalentFlow', NULL, 'Pipe and Foundry Company', 'https://www.pipecompany.example', 23, '1234 Main St', 'Anyplace', 'NC', '123456', '123-456-1234', 24, 'contact@talentflow.email', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<h2>Test Header</h2><p>test paragraph</p><blockquote><p>test quote</p></blockquote><ul><li>test list item 1</li><li>test list item 2</li></ul>', '<h2>Test Header</h2><p>test paragraph</p><blockquote><p>test quote</p></blockquote><ul><li>test list item 1</li><li>test list item 2</li></ul>', 0, NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` bigint(20) NOT NULL,
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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `first_name`, `last_name`, `email`, `phone`, `address`, `city`, `state`, `zipcode`, `interest`, `degree`, `major`, `school`, `position`, `graduation`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(6, 'Emily', 'Johnson', 'emily.johnson@email.com', '(555) 123-4567', '123 College Avenue', 'Durham', 'NC', '27708', 1, 11, 6, 13, 'FULL', '2025-05-31', '2024-01-16 20:34:21', '2024-01-16 20:34:21', NULL, NULL),
(7, 'Alex', 'Davis', 'alex.davis@email.com', '(555) 234-5678', '456 University Street', 'Stanford', 'CA', '94305', 4, 9, 8, 7, 'FULL', '2024-05-30', '2024-01-16 20:39:12', '2024-01-16 20:39:12', NULL, NULL),
(8, 'Olivia', 'Thompson', 'olivia.thompson@email.com', '(555) 567-8901', '234 Elm Street', 'Winston-Salem', 'NC', '27109', 7, 8, 9, 16, 'FULL', '2024-12-31', '2024-01-16 20:42:21', '2024-01-16 20:42:21', NULL, NULL),
(9, 'Sophia', 'Brown', 'sophia.brown@email.com', '(555) 567-8901', '234 Duke Circle', 'Durham', 'NC', '27708', 5, 11, 10, 13, 'INTERN', '2026-05-31', '2024-01-16 20:47:01', '2024-01-16 20:47:01', NULL, NULL),
(10, 'Michael', 'Miller', 'michael.miller@email.com', '(555) 456-7890', '101 Blue Ridge Road', 'Durham', 'NC', '27708', 1, 11, 6, 13, 'FULL', '2024-05-31', '2024-01-16 20:48:33', '2024-01-16 20:48:33', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_at_event`
--

CREATE TABLE `student_at_event` (
  `id` bigint(20) NOT NULL,
  `student_id` bigint(20) NOT NULL,
  `event_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `student_at_event`
--

INSERT INTO `student_at_event` (`id`, `student_id`, `event_id`) VALUES
(3, 6, 8),
(4, 8, 5),
(5, 9, 8),
(6, 10, 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `username` varchar(55) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'admin', 'admin@capstone.hostedprojects.net', '$2y$10$rVqFTvBSATwuM4klnmBdMeRadHJJLQqxxHVPpkzD4WwOWVxnHL/2e', '2023-10-12 18:34:47', NULL, '2024-02-16 00:09:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_has_role`
--

CREATE TABLE `user_has_role` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `user_has_role`
--

INSERT INTO `user_has_role` (`id`, `user_id`, `role_id`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(7, 1, 3, '2024-01-11 01:14:19', NULL, '2024-01-11 01:14:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_token_auth`
--

CREATE TABLE `user_token_auth` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `selector_hash` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `is_expired` int(11) NOT NULL DEFAULT '0',
  `expiry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aoi`
--
ALTER TABLE `aoi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `created` (`created_by`),
  ADD KEY `updated` (`updated_by`) USING BTREE;

--
-- Indexes for table `contact_log`
--
ALTER TABLE `contact_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student` (`student`),
  ADD KEY `sender` (`sender`);

--
-- Indexes for table `degree_lvl`
--
ALTER TABLE `degree_lvl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `eventCreatedBy` (`created_by`),
  ADD KEY `eventUpdatedBy` (`updated_by`),
  ADD KEY `eventAtSchool` (`location`);

--
-- Indexes for table `event_branding`
--
ALTER TABLE `event_branding`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `event_id` (`event_id`) USING BTREE,
  ADD KEY `eventLogoFile` (`event_logo`),
  ADD KEY `eventBannerFile` (`event_banner`);

--
-- Indexes for table `event_slugs`
--
ALTER TABLE `event_slugs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `eventHasSlug` (`event_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobField` (`field`),
  ADD KEY `jobCreatedBy` (`created_by`),
  ADD KEY `jobUpdatedBy` (`updated_by`),
  ADD KEY `jobEducation` (`education`);

--
-- Indexes for table `major`
--
ALTER TABLE `major`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `filename` (`filename`),
  ADD KEY `fileCreatedBy` (`created_by`),
  ADD KEY `fileUpdatedBy` (`updated_by`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `updated_at` (`updated_at`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `roleUpdatedBy` (`updated_by`);

--
-- Indexes for table `role_has_permission`
--
ALTER TABLE `role_has_permission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roleHasPermission` (`role_id`,`permission_id`),
  ADD KEY `permissionID` (`permission_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `school`
--
ALTER TABLE `school`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `school_branding`
--
ALTER TABLE `school_branding`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schoolHasBranding` (`school_id`),
  ADD KEY `schoolLogoFile` (`school_logo`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD UNIQUE KEY `IsSet` (`isSet`),
  ADD KEY `companyLogo` (`company_logo`),
  ADD KEY `appLogo` (`app_logo`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `areaOfInterest` (`interest`),
  ADD KEY `degree` (`degree`),
  ADD KEY `major` (`major`),
  ADD KEY `school` (`school`),
  ADD KEY `studentAddedBy` (`created_by`),
  ADD KEY `studentUpdatedBy` (`updated_by`);

--
-- Indexes for table `student_at_event`
--
ALTER TABLE `student_at_event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `studentAtEvent` (`student_id`),
  ADD KEY `eventHadStudent` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_has_role`
--
ALTER TABLE `user_has_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userHasRole` (`user_id`,`role_id`),
  ADD KEY `roleID` (`role_id`),
  ADD KEY `userGivenRole` (`created_by`),
  ADD KEY `userRoleModified` (`updated_by`);

--
-- Indexes for table `user_token_auth`
--
ALTER TABLE `user_token_auth`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userHasToken` (`user_id`),
  ADD KEY `usersName` (`user_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=490;

--
-- AUTO_INCREMENT for table `aoi`
--
ALTER TABLE `aoi`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contact_log`
--
ALTER TABLE `contact_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `degree_lvl`
--
ALTER TABLE `degree_lvl`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `event_branding`
--
ALTER TABLE `event_branding`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `event_slugs`
--
ALTER TABLE `event_slugs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `major`
--
ALTER TABLE `major`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `role_has_permission`
--
ALTER TABLE `role_has_permission`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `school`
--
ALTER TABLE `school`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `school_branding`
--
ALTER TABLE `school_branding`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `student_at_event`
--
ALTER TABLE `student_at_event`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_has_role`
--
ALTER TABLE `user_has_role`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_token_auth`
--
ALTER TABLE `user_token_auth`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `eventBannerFile` FOREIGN KEY (`event_banner`) REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `eventHasBranding` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `eventLogoFile` FOREIGN KEY (`event_logo`) REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

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
  ADD CONSTRAINT `jobEducation` FOREIGN KEY (`education`) REFERENCES `degree_lvl` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `jobField` FOREIGN KEY (`field`) REFERENCES `aoi` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `jobUpdatedBy` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `major`
--
ALTER TABLE `major`
  ADD CONSTRAINT `majorCreatedBy` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `majorUpdatedBy` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `fileCreatedBy` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fileUpdatedBy` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

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
-- Constraints for table `school_branding`
--
ALTER TABLE `school_branding`
  ADD CONSTRAINT `schoolHasBranding` FOREIGN KEY (`school_id`) REFERENCES `school` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `schoolLogoFile` FOREIGN KEY (`school_logo`) REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `appLogo` FOREIGN KEY (`app_logo`) REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `companyLogo` FOREIGN KEY (`company_logo`) REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `areaOfInterest` FOREIGN KEY (`interest`) REFERENCES `aoi` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `areaOfStudy` FOREIGN KEY (`major`) REFERENCES `major` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `degreeLevel` FOREIGN KEY (`degree`) REFERENCES `degree_lvl` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `schoolAttended` FOREIGN KEY (`school`) REFERENCES `school` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `studentAddedBy` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `studentUpdatedBy` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

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
  ADD CONSTRAINT `roleID` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
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
