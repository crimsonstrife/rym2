-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Feb 08, 2024 at 04:15 PM
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

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `user_id`, `action`, `performed_on`, `action_date`) VALUES
(1, NULL, 'OTHER', 'User 1', '2023-12-28 23:20:28'),
(2, NULL, 'OTHER', 'User 4', '2023-12-28 23:22:41'),
(3, 1, 'OTHER', 'User test', '2023-12-28 23:22:41'),
(4, 1, 'OTHER', 'User test', '2023-12-28 23:24:22'),
(5, NULL, 'MODIFY', 'Role 11 added to user 4', '2023-12-28 23:31:01'),
(6, 1, 'MODIFY', 'User test', '2023-12-28 23:31:01'),
(7, 1, 'OTHER', 'Report 27', '2023-12-29 00:04:42'),
(8, 1, 'CREATE', 'School Test', '2024-01-03 00:10:57'),
(9, 1, 'CREATE', 'School Test', '2024-01-04 01:08:39'),
(10, 1, 'CREATE', 'School Test', '2024-01-04 01:16:18'),
(11, 1, 'DELETE', 'School ID: 7', '2024-01-04 01:16:39'),
(12, NULL, 'LOGIN', 'User 1', '2024-01-04 21:03:49'),
(13, NULL, 'LOGIN', 'User 1', '2024-01-04 21:42:32'),
(14, NULL, 'MODIFY', 'Role 3 added to user 1', '2024-01-04 21:42:49'),
(15, 1, 'MODIFY', 'User admin', '2024-01-04 21:42:49'),
(16, 1, 'MODIFY', 'User admin', '2024-01-04 23:31:57'),
(17, 3, 'MODIFY', 'Event', '2024-01-05 20:20:47'),
(18, 3, 'MODIFY', 'Event', '2024-01-05 20:20:47'),
(19, 3, 'MODIFY', 'Event', '2024-01-05 20:20:47'),
(20, 2, 'MODIFY', 'Event', '2024-01-05 20:21:13'),
(21, 2, 'MODIFY', 'Event', '2024-01-05 20:21:13'),
(22, 2, 'MODIFY', 'Event', '2024-01-05 20:21:13'),
(23, 1, 'OTHER', 'Event Slug for Event:TrailheadDX does not exist, creating slug', '2024-01-05 21:42:25'),
(24, 1, 'CREATE', 'Event ID: 14 Event Name: TrailheadDX was sluggified with Slug: trailheaddx', '2024-01-05 21:42:25'),
(25, 1, 'CREATE', 'Event ID: 14 Event Name: TrailheadDX', '2024-01-05 21:42:25'),
(26, NULL, 'LOGIN', 'User 1', '2024-01-08 21:25:40'),
(27, 1, 'DELETE', 'Event ID: 14 Event Name: TrailheadDX', '2024-01-08 22:33:42'),
(28, 1, 'DELETE', 'Job ID: 6 Job Name: lorem', '2024-01-09 00:44:14'),
(29, 1, 'DELETE', 'Job ID: 3 Job Name: Lorem Ipsum', '2024-01-09 00:44:29'),
(30, 1, 'DELETE', 'Job ID: 4 Job Name: Lorem Ipsum 2', '2024-01-09 00:44:34'),
(31, 1, 'OTHER', 'Report 28', '2024-01-09 00:57:49'),
(32, 1, 'OTHER', 'Subject', '2024-01-09 01:57:59'),
(33, 1, 'OTHER', 'Subject', '2024-01-09 02:01:07'),
(34, 1, 'DELETE', 'Subject ID: 9 Subject Name: Test', '2024-01-09 02:01:13'),
(35, 1, 'OTHER', 'major', '2024-01-10 20:31:32'),
(36, 1, 'DELETE', 'Student ID: 4 Student Name: Jane Doe', '2024-01-10 23:26:54'),
(37, 1, 'DELETE', 'Student ID: 3 Student Name: John Doe', '2024-01-10 23:39:20'),
(38, 1, 'DELETE', 'Student ID: 7 Student Name: Mark Iplier', '2024-01-10 23:57:04'),
(39, 1, 'DELETE', 'Major ID: 5 Major Name: Test', '2024-01-11 00:19:19'),
(40, 1, 'DELETE', 'Degree Level ID: 10 Degree Level Name: BBA - Bachelor of Business Administration', '2024-01-11 01:10:44'),
(41, NULL, 'MODIFY', 'Role 3 added to user 1', '2024-01-11 01:14:19'),
(42, 1, 'MODIFY', 'User admin', '2024-01-11 01:14:19'),
(43, NULL, 'MODIFY', 'Role 3 added to user 4', '2024-01-11 23:50:13'),
(44, 1, 'MODIFY', 'User: test updated by User: 1', '2024-01-11 23:50:13'),
(45, 1, 'MODIFY', 'User: test updated by User: 1', '2024-01-11 23:50:21'),
(46, NULL, 'MODIFY', 'Role ID: 12Role Name: Test added to User ID: 4User Name: test', '2024-01-11 23:54:44'),
(47, 1, 'MODIFY', 'User: test updated by User: 1', '2024-01-11 23:54:44'),
(48, NULL, 'LOGIN', 'User 1', '2024-01-12 19:47:06'),
(49, 1, 'DELETE', 'User ID: 4 User Name: test', '2024-01-12 19:56:37'),
(50, 1, 'MODIFY', 'Role 1 was removed permission 55', '2024-01-15 21:27:42'),
(51, 1, 'MODIFY', 'Role 1 was given permission 55', '2024-01-15 21:27:49'),
(52, 1, 'MODIFY', 'Role 12 was removed permission 4', '2024-01-15 23:04:46'),
(53, 1, 'DELETE', 'Role 12 was deleted', '2024-01-15 23:04:46'),
(54, 1, 'MODIFY', 'Role 3 was given permission 57', '2024-01-15 23:14:50'),
(55, 1, 'MODIFY', 'Role 3 was given permission 58', '2024-01-15 23:14:50'),
(56, 1, 'DELETE', 'Report 12', '2024-01-16 00:42:09'),
(57, 1, 'DELETE', 'Report 18', '2024-01-16 01:08:19'),
(58, 1, 'DELETE', 'Report 20', '2024-01-16 01:10:09'),
(59, 1, 'DELETE', 'Report 23', '2024-01-16 01:11:56'),
(60, NULL, 'LOGIN', 'User 1', '2024-01-16 01:37:19'),
(61, 1, 'DELETE', 'Event ID: 13 Event Name: new event with branding', '2024-01-16 01:38:23'),
(62, 1, 'DELETE', 'Event ID: 11 Event Name: New Test Event', '2024-01-16 01:38:32'),
(63, 1, 'OTHER', 'Event Slug for Event:WGU Job Faire already exists, incrementing slug', '2024-01-16 01:39:02'),
(64, 1, 'MODIFY', 'Event ID: 1 Event Name: WGU Job Faire was sluggified with Slug: wgu-job-faire', '2024-01-16 01:39:02'),
(65, 1, 'MODIFY', 'Event ID: 1 Event Name: WGU Job Faire', '2024-01-16 01:39:02'),
(66, 1, 'CREATE', 'School Harvard University', '2024-01-16 19:47:09'),
(67, 1, 'MODIFY', 'School Harvard University', '2024-01-16 19:47:55'),
(68, 1, 'CREATE', 'School Stanford University', '2024-01-16 19:49:48'),
(69, 1, 'CREATE', 'School Massachusetts Institute of Technology - MIT', '2024-01-16 19:52:29'),
(70, 1, 'CREATE', 'School University of California, Berkeley', '2024-01-16 19:53:40'),
(71, 1, 'MODIFY', 'School University of California, Berkeley', '2024-01-16 19:54:56'),
(72, 1, 'CREATE', 'School Yale University', '2024-01-16 19:56:36'),
(73, 1, 'CREATE', 'School University of Chicago', '2024-01-16 19:58:05'),
(74, 1, 'CREATE', 'School Columbia University', '2024-01-16 19:59:50'),
(75, 1, 'CREATE', 'School Duke University', '2024-01-16 20:01:09'),
(76, 1, 'CREATE', 'School University of North Carolina at Chapel Hill - UNC Chapel Hill', '2024-01-16 20:02:59'),
(77, 1, 'CREATE', 'School North Carolina State University', '2024-01-16 20:04:51'),
(78, 1, 'CREATE', 'School Wake Forest University', '2024-01-16 20:06:30'),
(79, 1, 'OTHER', 'Report 29', '2024-01-16 20:07:19'),
(80, 1, 'OTHER', 'Event Slug for Event:NC State Recruitement does not exist, creating slug', '2024-01-16 20:19:38'),
(81, 1, 'CREATE', 'Event ID: 4 Event Name: NC State Recruitement was sluggified with Slug: nc-state-recruitement', '2024-01-16 20:19:38'),
(82, 1, 'CREATE', 'Event ID: 4 Event Name: NC State Recruitement', '2024-01-16 20:19:38'),
(83, 1, 'OTHER', 'Event Slug for Event:Wake Forest Trip does not exist, creating slug', '2024-01-16 20:21:09'),
(84, 1, 'CREATE', 'Event ID: 5 Event Name: Wake Forest Trip was sluggified with Slug: wake-forest-trip', '2024-01-16 20:21:09'),
(85, 1, 'CREATE', 'Event ID: 5 Event Name: Wake Forest Trip', '2024-01-16 20:21:09'),
(86, 1, 'OTHER', 'Event Slug for Event:Berkeley Recruitment does not exist, creating slug', '2024-01-16 20:22:08'),
(87, 1, 'CREATE', 'Event ID: 6 Event Name: Berkeley Recruitment was sluggified with Slug: berkeley-recruitment', '2024-01-16 20:22:08'),
(88, 1, 'CREATE', 'Event ID: 6 Event Name: Berkeley Recruitment', '2024-01-16 20:22:08'),
(89, 1, 'OTHER', 'Event Slug for Event:Winston Salem Trip does not exist, creating slug', '2024-01-16 20:23:14'),
(90, 1, 'CREATE', 'Event ID: 7 Event Name: Winston Salem Trip was sluggified with Slug: winston-salem-trip', '2024-01-16 20:23:14'),
(91, 1, 'CREATE', 'Event ID: 7 Event Name: Winston Salem Trip', '2024-01-16 20:23:14'),
(92, 1, 'OTHER', 'Event Slug for Event:Duke Recruitment does not exist, creating slug', '2024-01-16 20:23:53'),
(93, 1, 'CREATE', 'Event ID: 8 Event Name: Duke Recruitment was sluggified with Slug: duke-recruitment', '2024-01-16 20:23:53'),
(94, 1, 'CREATE', 'Event ID: 8 Event Name: Duke Recruitment', '2024-01-16 20:23:53'),
(95, 1, 'OTHER', 'Event Slug for Event:UNC Recruitment does not exist, creating slug', '2024-01-16 20:24:20'),
(96, 1, 'CREATE', 'Event ID: 9 Event Name: UNC Recruitment was sluggified with Slug: unc-recruitment', '2024-01-16 20:24:20'),
(97, 1, 'CREATE', 'Event ID: 9 Event Name: UNC Recruitment', '2024-01-16 20:24:20'),
(98, 1, 'OTHER', 'Event Slug for Event:RCCC - Community Recruitment does not exist, creating slug', '2024-01-16 20:25:21'),
(99, 1, 'OTHER', 'Event Slug for Event:RCCC - Community Recruitment Event does not exist, creating slug', '2024-01-16 20:30:18'),
(100, 1, 'CREATE', 'Event ID: 10 Event Name: RCCC - Community Recruitment Event was sluggified with Slug: rccc---community-recruitm', '2024-01-16 20:30:18'),
(101, 1, 'MODIFY', 'Event ID: 10 Event Name: RCCC - Community Recruitment Event', '2024-01-16 20:30:18'),
(102, NULL, 'OTHER', 'Emily Johnson Added', '2024-01-16 20:34:21'),
(103, NULL, 'MODIFY', 'Emily Johnson Added to Event Duke Recruitment', '2024-01-16 20:34:21'),
(104, NULL, 'OTHER', 'Emily Johnson', '2024-01-16 20:34:21'),
(105, 1, 'OTHER', 'Report 30', '2024-01-16 20:34:49'),
(106, 1, 'OTHER', 'Report 31', '2024-01-16 20:35:03'),
(107, 1, 'OTHER', 'Report 32', '2024-01-16 20:35:13'),
(108, NULL, 'OTHER', 'major', '2024-01-16 20:39:12'),
(109, NULL, 'OTHER', 'Alex Davis Added', '2024-01-16 20:39:12'),
(110, NULL, 'OTHER', 'Alex Davis', '2024-01-16 20:39:12'),
(111, 1, 'OTHER', 'Report 33', '2024-01-16 20:39:43'),
(112, 1, 'OTHER', 'Report 34', '2024-01-16 20:40:00'),
(113, 1, 'OTHER', 'Report 35', '2024-01-16 20:40:08'),
(114, NULL, 'OTHER', 'major', '2024-01-16 20:42:21'),
(115, NULL, 'OTHER', 'Olivia Thompson Added', '2024-01-16 20:42:21'),
(116, NULL, 'MODIFY', 'Olivia Thompson Added to Event Wake Forest Trip', '2024-01-16 20:42:21'),
(117, NULL, 'OTHER', 'Olivia Thompson', '2024-01-16 20:42:21'),
(118, 1, 'OTHER', 'Report 36', '2024-01-16 20:43:11'),
(119, 1, 'OTHER', 'Report 37', '2024-01-16 20:43:21'),
(120, 1, 'OTHER', 'Report 38', '2024-01-16 20:43:32'),
(121, 1, 'OTHER', 'Report 39', '2024-01-16 20:43:44'),
(122, 1, 'OTHER', 'Report 40', '2024-01-16 20:44:13'),
(123, NULL, 'OTHER', 'major', '2024-01-16 20:47:01'),
(124, NULL, 'OTHER', 'Sophia Brown Added', '2024-01-16 20:47:01'),
(125, NULL, 'MODIFY', 'Sophia Brown Added to Event Duke Recruitment', '2024-01-16 20:47:01'),
(126, NULL, 'OTHER', 'Sophia Brown', '2024-01-16 20:47:01'),
(127, NULL, 'OTHER', 'Michael Miller Added', '2024-01-16 20:48:33'),
(128, NULL, 'MODIFY', 'Michael Miller Added to Event Duke Recruitment', '2024-01-16 20:48:33'),
(129, NULL, 'OTHER', 'Michael Miller', '2024-01-16 20:48:33'),
(130, 1, 'OTHER', 'Report 41', '2024-01-16 20:48:56'),
(131, 1, 'OTHER', 'Report 42', '2024-01-16 20:49:02'),
(132, 1, 'OTHER', 'Report 43', '2024-01-16 20:49:08'),
(133, 1, 'OTHER', 'Report 44', '2024-01-16 20:49:22'),
(134, 1, 'OTHER', 'Report 45', '2024-01-16 20:49:34'),
(135, NULL, 'OTHER', 'Patrick Barnhardt Added', '2024-01-16 20:51:49'),
(136, NULL, 'MODIFY', 'Patrick Barnhardt Added to Event WGU Job Faire', '2024-01-16 20:51:49'),
(137, NULL, 'OTHER', 'Patrick Barnhardt', '2024-01-16 20:51:49'),
(138, 1, 'OTHER', 'Report 46', '2024-01-16 20:52:11'),
(139, 1, 'OTHER', 'Report 47', '2024-01-16 20:52:23'),
(140, 1, 'OTHER', 'Report 48', '2024-01-16 20:52:40'),
(141, 1, 'OTHER', 'Report 49', '2024-01-16 20:52:49'),
(142, 1, 'OTHER', 'Report 50', '2024-01-16 20:52:56'),
(143, NULL, 'LOGIN', 'User 1', '2024-01-16 20:57:58'),
(144, 1, 'OTHER', 'Report 51', '2024-01-17 00:32:22'),
(145, NULL, 'MODIFY', 'Role ID: 11 Role Name: standard user added to User ID: 2 User Name: test', '2024-01-17 02:29:03'),
(146, NULL, 'OTHER', 'test@email.com', '2024-01-17 02:29:03'),
(147, 1, 'CREATE', 'User test', '2024-01-17 02:29:03'),
(148, NULL, 'LOGIN', 'User 2', '2024-01-17 02:39:36'),
(149, 2, 'ERROR', 'CODE: [MYSTIC]- An error has occurred, contact the Administrator. FOUND AT: /admin/dashboard.php?login=success&u=Mg==', '2024-01-17 02:42:41'),
(150, 2, 'ERROR', 'CODE: [TELESCOPE]- You do not have permission to access the dashboard, contact the Administrator. FOUND AT: /admin/dashboard.php?login=success&u=Mg==', '2024-01-17 02:45:52'),
(151, 2, 'ERROR', 'CODE: [TELESCOPE]- You do not have permission to access the dashboard, contact the Administrator. FOUND AT: /admin/dashboard.php', '2024-01-17 02:46:05'),
(152, 2, 'ERROR', 'CODE: [TELESCOPE]- You do not have permission to access this content, contact the Administrator. FOUND AT: /admin/dashboard.php', '2024-01-17 02:47:03'),
(153, 2, 'ERROR', 'CODE: [TELESCOPE]- You do not have permission to access this content, contact the Administrator. FOUND AT: /admin/dashboard.php', '2024-01-17 02:53:06'),
(154, 2, 'ERROR', 'CODE: [TELESCOPE]- You do not have permission to access this content, contact the Administrator. FOUND AT: /admin/dashboard.php', '2024-01-17 02:53:17'),
(155, 2, 'ERROR', 'CODE: [TELESCOPE]- You do not have permission to access this content, contact the Administrator. FOUND AT: /admin/dashboard.php', '2024-01-17 02:55:49'),
(156, 1, 'MODIFY', 'Role 11 was given permission 37', '2024-01-17 02:57:50'),
(157, 2, 'ERROR', 'CODE: [LYNX]- AT: /admin/dashboard.php?view=activity-log', '2024-01-17 03:01:37'),
(158, 2, 'ERROR', 'CODE: [LYNX]- AT: /admin/dashboard.php?view=activity-log', '2024-01-17 03:01:42'),
(159, 2, 'ERROR', 'CODE: [LYNX]- AT: /admin/dashboard.php?view=contact-log', '2024-01-17 03:03:28'),
(160, 2, 'ERROR', 'CODE: [LYNX]- AT: /admin/dashboard.php?view=activity-log', '2024-01-17 03:03:29'),
(161, NULL, 'LOGIN', 'User 2', '2024-01-17 19:30:41'),
(162, 2, 'ERROR', 'CODE: [LYNX]- AT: /admin/dashboard.php?view=students&student=list', '2024-01-17 19:34:13'),
(163, 2, 'ERROR', 'CODE: [LYNX]- AT: /admin/dashboard.php?view=students&student=list', '2024-01-17 19:43:22'),
(164, 2, 'ERROR', 'CODE: [LYNX]- AT: /admin/dashboard.php?view=activity-log', '2024-01-17 19:43:47'),
(165, 2, 'ERROR', 'CODE: [SASQUATCH]- AT: /admin/dashboard.php?view=events&event=single&id=100', '2024-01-17 19:56:44'),
(166, 2, 'ERROR', 'CODE: [CIPHER]- AT: /admin/dashboard.php?view=events&event=single&id=0', '2024-01-17 20:18:54'),
(167, 2, 'ERROR', 'CODE: [SASQUATCH]- AT: /admin/dashboard.php?view=events&event=single&id=100', '2024-01-17 20:19:02'),
(168, 2, 'ERROR', 'CODE: [LYNX]- AT: /admin/dashboard.php?view=activity-log', '2024-01-17 20:38:52'),
(169, 2, 'ERROR', 'CODE: [CIPHER]- AT: /admin/dashboard.php?view=reports&report=single&type=Top+Degree+by+School&id=0', '2024-01-17 20:39:07'),
(170, 2, 'ERROR', 'CODE: [MONOLITH]- AT: /admin/dashboard.php?view=reports&report=single&type=fae&id=0', '2024-01-17 20:39:25'),
(171, 2, 'ERROR', 'CODE: [LYNX]- AT: /admin/dashboard.php?view=activity-log', '2024-01-17 20:39:31'),
(172, 2, 'ERROR', 'CODE: [LYNX]- AT: /admin/dashboard.php?view=activity-log', '2024-01-17 20:41:53'),
(173, 2, 'ERROR', 'CODE: [SASQUATCH]- AT: /admin/dashboard.php?view=reports&report=single&type=Top+Degree+by+School&id=100', '2024-01-17 20:42:31'),
(174, 2, 'ERROR', 'CODE: [SASQUATCH]- AT: /admin/dashboard.php?view=roles&role=single&id=15', '2024-01-17 20:49:32'),
(175, 2, 'ERROR', 'CODE: [SASQUATCH]- AT: /admin/dashboard.php?view=roles&role=single&id=15', '2024-01-17 20:51:47'),
(176, 1, 'ERROR', 'CODE: [PELICAN]- AT: /admin/dashboard.php?view=students&student=single&id=5', '2024-01-17 21:28:33'),
(177, 1, 'OTHER', 'Sam Smith', '2024-01-17 21:28:43'),
(178, 1, 'ERROR', 'CODE: [PELICAN]- AT: /admin/dashboard.php?view=students&student=single&id=10', '2024-01-17 21:30:07'),
(179, 1, 'OTHER', 'Michael Miller', '2024-01-17 21:30:15'),
(180, 1, 'ERROR', 'CODE: [PELICAN]- AT: /admin/dashboard.php?view=students&student=single&id=5', '2024-01-17 21:35:01'),
(181, 1, 'OTHER', 'Sam Smith', '2024-01-17 21:35:09'),
(182, 1, 'ERROR', 'CODE: [PELICAN]- AT: /admin/dashboard.php?view=students&student=single&id=6', '2024-01-17 21:36:06'),
(183, 1, 'OTHER', 'Emily Johnson', '2024-01-17 21:36:13'),
(184, 1, 'ERROR', 'CODE: [PELICAN]- AT: /admin/dashboard.php?view=students&student=single&id=11', '2024-01-17 21:43:23'),
(185, 1, 'ERROR', 'CODE: [PELICAN]- AT: /admin/dashboard.php?view=students&student=single&id=11', '2024-01-17 21:43:42'),
(186, 1, 'ERROR', 'CODE: [PELICAN]- AT: /admin/dashboard.php?view=students&student=single&id=9', '2024-01-17 21:45:06'),
(187, 1, 'ERROR', 'CODE: [PELICAN]- AT: /admin/dashboard.php?view=students&student=single&id=5', '2024-01-17 21:45:13'),
(188, 1, 'EMAIL', 'Sent Sam Smith @ ssmith@example.com - Subject: tests by admin', '2024-01-17 21:45:19'),
(189, 1, 'EMAIL', 'Sent Sam Smith @ ssmith@example.com - Subject: tests by admin', '2024-01-17 21:47:28'),
(190, 1, 'EMAIL', 'Sent Sam Smith @ ssmith@example.com - Subject: test by admin', '2024-01-17 21:47:37'),
(191, 1, 'EMAIL', 'Sent Sam Smith @ ssmith@example.com - Subject: test by admin', '2024-01-17 21:52:02'),
(192, 1, 'ERROR', 'CODE: [PELICAN]- AT: /admin/dashboard.php?view=students&student=single&id=5', '2024-01-17 21:52:42'),
(193, 1, 'EMAIL', 'Sent Sam Smith @ ssmith@example.com - Subject: test by admin', '2024-01-17 21:52:50'),
(194, 1, 'ERROR', 'CODE: [PELICAN]- AT: /admin/dashboard.php?view=students&student=single&id=10', '2024-01-17 21:53:29'),
(195, 1, 'EMAIL', 'Sent Michael Miller @ michael.miller@email.com - Subject: any of the R710 left? by admin', '2024-01-17 21:53:35'),
(196, 1, 'ERROR', 'CODE: [PELICAN]- AT: /admin/dashboard.php?view=students&student=single&id=11', '2024-01-17 23:13:20'),
(197, 1, 'EMAIL', 'Sent Patrick Barnhardt @ pbarnh1@wgu.edu - Subject: test by admin', '2024-01-17 23:13:26'),
(198, 1, 'OTHER', 'Report 52', '2024-01-17 23:17:38'),
(199, 1, 'EMAIL', 'Sent Michael Miller @ michael.miller@email.com - Subject: any of the R710 left? by admin', '2024-01-17 23:17:55'),
(200, 2, 'ERROR', 'CODE: [LYNX]- AT: /admin/dashboard.php?view=users&user=single&id=1', '2024-01-17 23:36:44'),
(201, 2, 'ERROR', 'CODE: [LYNX]- AT: /admin/dashboard.php?view=users&user=single&id=10', '2024-01-17 23:36:53'),
(202, 1, 'CREATE', 'Report 53', '2024-01-17 23:48:31'),
(203, 1, 'CREATE', 'Report:  Top Field by School - ID: 54 Date: 01/17/2024', '2024-01-17 23:48:33'),
(204, 1, 'CREATE', 'Report:  Major to Field Ratio by School - ID: 55 Date: 01/17/2024', '2024-01-17 23:48:35'),
(205, 1, 'CREATE', 'Report:  Jobs by Field - ID: 56 Date: 01/17/2024', '2024-01-17 23:48:37'),
(206, 1, 'CREATE', 'Report:  Contact Follow-Up Percentage - ID: 57 Date: 01/17/2024', '2024-01-17 23:48:39'),
(207, 2, 'ERROR', 'CODE: [LYNX]- AT: /admin/dashboard.php?view=students&student=list', '2024-01-18 01:50:59'),
(208, 2, 'ERROR', 'CODE: [LYNX]- AT: /admin/dashboard.php?view=settings', '2024-01-18 01:54:11'),
(209, 2, 'ERROR', 'CODE: [LYNX]- AT: /admin/dashboard.php?view=events&event=edit&action=edit&id=1', '2024-01-18 01:54:35'),
(210, 2, 'ERROR', 'CODE: [LYNX]- AT: /admin/dashboard.php?view=users&user=single&id=1', '2024-01-18 01:55:40'),
(211, 1, 'ERROR', 'CODE: [LYNX]- AT: /admin/dashboard.php?view=users&user=edit&action=edit&id=2', '2024-01-18 22:43:57'),
(212, NULL, 'LOGIN', 'User 1', '2024-01-22 19:05:05'),
(213, 1, 'MODIFY', 'School Harvard University', '2024-01-22 20:08:56'),
(214, 1, 'OTHER', 'Logo Image File:/Users/pbarnhardt/Sites/capstone/rym2/admin/editor/actions/school/../../../../public/content/uploads/Harvard_University_shield.png uploaded successfully', '2024-01-22 20:26:31'),
(215, 1, 'MODIFY', 'School Harvard University', '2024-01-22 20:26:31'),
(216, 1, 'ERROR', 'Error Uploading Media: Error moving file to upload directory - 98cf7d6d899badc5d9752a12d6e97209.png', '2024-01-23 00:11:03'),
(217, 1, 'MODIFY', 'School Stanford University', '2024-01-23 00:11:03'),
(218, 1, 'ERROR', 'Error Uploading Media: Error moving file to upload directory - 98cf7d6d899badc5d9752a12d6e97209.png', '2024-01-23 00:15:17'),
(219, 1, 'MODIFY', 'School Stanford University', '2024-01-23 00:15:17'),
(220, 1, 'ERROR', 'Error Uploading Media: Error moving file to upload directory - 98cf7d6d899badc5d9752a12d6e97209.png', '2024-01-23 00:16:14'),
(221, 1, 'MODIFY', 'School Stanford University', '2024-01-23 00:16:14'),
(222, 1, 'ERROR', 'Error Uploading Media: Error moving file to upload directory - 98cf7d6d899badc5d9752a12d6e97209.png', '2024-01-23 00:17:21'),
(223, 1, 'MODIFY', 'School Stanford University', '2024-01-23 00:17:21'),
(224, 1, 'ERROR', 'Error Uploading Media: File already exists - 98cf7d6d899badc5d9752a12d6e97209.png', '2024-01-23 00:19:29'),
(225, 1, 'MODIFY', 'School Stanford University', '2024-01-23 00:19:29'),
(226, 1, 'MODIFY', 'School Stanford University', '2024-01-23 00:21:20'),
(227, 1, 'OTHER', 'Event Slug for Event:Wake Forest Trip already exists, incrementing slug', '2024-01-23 00:53:34'),
(228, 1, 'MODIFY', 'Event ID: 5 Event Name: Wake Forest Trip was sluggified with Slug: wake-forest-trip', '2024-01-23 00:53:35'),
(229, 1, 'MODIFY', 'Event ID: 5 Event Name: Wake Forest Trip', '2024-01-23 00:53:35'),
(230, 1, 'ERROR', 'Error Uploading Media: Invalid file type - ', '2024-01-23 00:57:39'),
(231, 1, 'OTHER', 'Event Slug for Event:Wake Forest Trip already exists, incrementing slug', '2024-01-23 00:57:39'),
(232, 1, 'MODIFY', 'Event ID: 5 Event Name: Wake Forest Trip was sluggified with Slug: wake-forest-trip', '2024-01-23 00:57:39'),
(233, 1, 'MODIFY', 'Event ID: 5 Event Name: Wake Forest Trip', '2024-01-23 00:57:39'),
(234, 1, 'ERROR', 'Error Uploading Media: Invalid file type - ', '2024-01-23 01:04:42'),
(235, 1, 'OTHER', 'Event Slug for Event:Wake Forest Trip already exists, incrementing slug', '2024-01-23 01:04:42'),
(236, 1, 'MODIFY', 'Event ID: 5 Event Name: Wake Forest Trip was sluggified with Slug: wake-forest-trip', '2024-01-23 01:04:42'),
(237, 1, 'MODIFY', 'Event ID: 5 Event Name: Wake Forest Trip', '2024-01-23 01:04:42'),
(238, 1, 'ERROR', 'Error Uploading Media: File already exists - image-placeholder-500x500.jpg', '2024-01-23 01:05:24'),
(239, 1, 'OTHER', 'Event Slug for Event:Wake Forest Trip already exists, incrementing slug', '2024-01-23 01:05:25'),
(240, 1, 'MODIFY', 'Event ID: 5 Event Name: Wake Forest Trip was sluggified with Slug: wake-forest-trip', '2024-01-23 01:05:25'),
(241, 1, 'MODIFY', 'Event ID: 5 Event Name: Wake Forest Trip', '2024-01-23 01:05:25'),
(242, 1, 'OTHER', 'Event Slug for Event:Wake Forest Trip already exists, incrementing slug', '2024-01-23 01:17:09'),
(243, 1, 'MODIFY', 'Event ID: 5 Event Name: Wake Forest Trip was sluggified with Slug: wake-forest-trip', '2024-01-23 01:17:09'),
(244, 1, 'MODIFY', 'Event ID: 5 Event Name: Wake Forest Trip', '2024-01-23 01:17:09'),
(245, 1, 'ERROR', 'Error Uploading Media: Invalid file type - ', '2024-01-23 01:26:23'),
(246, 1, 'OTHER', 'Event Slug for Event:Berkeley Recruitment already exists, incrementing slug', '2024-01-23 01:26:23'),
(247, 1, 'MODIFY', 'Event ID: 6 Event Name: Berkeley Recruitment was sluggified with Slug: berkeley-recruitment', '2024-01-23 01:26:23'),
(248, 1, 'MODIFY', 'Event ID: 6 Event Name: Berkeley Recruitment', '2024-01-23 01:26:23'),
(249, 1, 'ERROR', 'Error Uploading Media: Invalid file type - ', '2024-01-23 01:33:03'),
(250, 1, 'OTHER', 'Event Slug for Event:Berkeley Recruitment already exists, incrementing slug', '2024-01-23 01:33:03'),
(251, 1, 'MODIFY', 'Event ID: 6 Event Name: Berkeley Recruitment was sluggified with Slug: berkeley-recruitment', '2024-01-23 01:33:03'),
(252, 1, 'MODIFY', 'Event ID: 6 Event Name: Berkeley Recruitment', '2024-01-23 01:33:03'),
(253, 1, 'MODIFY', 'Role 3 was given permission 59', '2024-01-23 19:05:31'),
(254, 1, 'MODIFY', 'Role 3 was given permission 60', '2024-01-23 19:05:31'),
(255, 1, 'MODIFY', 'Role 3 was given permission 61', '2024-01-23 19:05:31'),
(256, 1, 'MODIFY', 'Role 3 was given permission 62', '2024-01-23 19:05:31'),
(257, NULL, 'LOGIN', 'User admin', '2024-01-24 01:42:04'),
(258, 1, 'ERROR', 'CODE: [CIPHER]- AT: /admin/dashboard.php?view=media&media=add&action=create', '2024-01-24 23:16:36'),
(259, 1, 'ERROR', 'CODE: [CIPHER]- AT: /admin/dashboard.php?view=media&media=add&action=create', '2024-01-24 23:18:15'),
(260, 1, 'OTHER', 'Media Uploaded: placeholderbanner.png', '2024-01-24 23:20:09'),
(261, 1, 'ERROR', 'Error Uploading Media: Invalid file type - ', '2024-01-24 23:49:59'),
(262, 1, 'ERROR', 'Error Uploading Media: Invalid file type - ', '2024-01-24 23:50:58'),
(263, 1, 'OTHER', 'Media Uploaded: placeholderlogopng.png', '2024-01-25 01:31:24'),
(264, 1, 'ERROR', 'Error Uploading Media: Invalid file type - ', '2024-01-25 01:31:41'),
(265, 1, 'MODIFY', 'School Western Governor&#039;s University - WGU', '2024-01-25 01:32:11'),
(266, 1, 'MODIFY', 'School University of North Carolina at Charlotte - UNCC', '2024-01-25 01:32:38'),
(267, 1, 'MODIFY', 'School Stanly Community College - SCC', '2024-01-25 01:32:50'),
(268, 1, 'MODIFY', 'School University of North Carolina at Greensboro - UNCG', '2024-01-25 01:33:30'),
(269, 1, 'MODIFY', 'School Harvard University', '2024-01-25 01:33:44'),
(270, 1, 'MODIFY', 'School Stanford University', '2024-01-25 01:33:58'),
(271, 1, 'MODIFY', 'School Massachusetts Institute of Technology - MIT', '2024-01-25 01:34:12'),
(272, 1, 'MODIFY', 'School University of California, Berkeley', '2024-01-25 01:34:29'),
(273, 1, 'MODIFY', 'School Yale University', '2024-01-25 01:34:46'),
(274, 1, 'MODIFY', 'School University of Chicago', '2024-01-25 01:35:03'),
(275, 1, 'MODIFY', 'School Columbia University', '2024-01-25 01:35:18'),
(276, 1, 'MODIFY', 'School Duke University', '2024-01-25 01:35:32'),
(277, 1, 'MODIFY', 'School University of North Carolina at Chapel Hill - UNC Chapel Hill', '2024-01-25 01:35:55'),
(278, 1, 'MODIFY', 'School North Carolina State University', '2024-01-25 01:36:10'),
(279, 1, 'MODIFY', 'School Wake Forest University', '2024-01-25 01:36:27'),
(280, 1, 'MODIFY', 'School Rowan-Cabarrus Community College - RCCC', '2024-01-25 01:38:01'),
(281, 1, 'DELETE', 'Student ID: 2 Student Name: Kelsey Berkman from Event ID: 1 Event Name: WGU Job Faire', '2024-01-25 19:02:28'),
(282, 1, 'DELETE', 'Student ID: 2 Student Name: Kelsey Berkman', '2024-01-25 19:02:28'),
(283, 1, 'DELETE', 'Student ID: 1 Student Name: Patrick Barnhardt from Event ID: 1 Event Name: WGU Job Faire', '2024-01-25 19:02:34'),
(284, 1, 'DELETE', 'Student ID: 1 Student Name: Patrick Barnhardt', '2024-01-25 19:02:34'),
(285, 1, 'DELETE', 'Student ID: 11 Student Name: Patrick Barnhardt from Event ID: 1 Event Name: WGU Job Faire', '2024-01-25 19:02:43'),
(286, 1, 'DELETE', 'Student ID: 11 Student Name: Patrick Barnhardt', '2024-01-25 19:02:43'),
(287, 1, 'OTHER', 'Media Uploaded: 312920701_622333729345028_2932566406123148558_n.jpg', '2024-01-25 19:44:01'),
(288, 1, 'OTHER', 'Media Uploaded: 2023.09.07_FinancialServices-CareerFair_600x400.png', '2024-01-25 19:44:01'),
(289, 1, 'OTHER', 'Event Slug for Event:UNCC Charlotte already exists, incrementing slug', '2024-01-25 19:44:01'),
(290, 1, 'MODIFY', 'Event ID: 2 Event Name: UNCC Charlotte was sluggified with Slug: uncc-charlotte', '2024-01-25 19:44:01'),
(291, 1, 'MODIFY', 'Event ID: 2 Event Name: UNCC Charlotte', '2024-01-25 19:44:01'),
(292, 1, 'OTHER', 'Event Slug for Event:UNCC Charlotte already exists, incrementing slug', '2024-01-25 19:44:17'),
(293, 1, 'MODIFY', 'Event ID: 2 Event Name: UNCC Charlotte was sluggified with Slug: uncc-charlotte', '2024-01-25 19:44:17'),
(294, 1, 'MODIFY', 'Event ID: 2 Event Name: UNCC Charlotte', '2024-01-25 19:44:17'),
(295, 1, 'OTHER', 'Event Slug for Event:UNCC Charlotte already exists, incrementing slug', '2024-01-25 19:44:30'),
(296, 1, 'MODIFY', 'Event ID: 2 Event Name: UNCC Charlotte was sluggified with Slug: uncc-charlotte', '2024-01-25 19:44:30'),
(297, 1, 'MODIFY', 'Event ID: 2 Event Name: UNCC Charlotte', '2024-01-25 19:44:30'),
(298, 1, 'DELETE', 'Job ID: 1 Job Name: Packaging Operator', '2024-01-25 19:51:48'),
(299, 1, 'DELETE', 'Job ID: 2 Job Name: Business Analyst', '2024-01-25 19:51:54'),
(300, 1, 'DELETE', 'Job ID: 5 Job Name: Job', '2024-01-25 19:51:58'),
(301, 1, 'DELETE', 'Job ID: 7 Job Name: Helpdesk', '2024-01-25 19:52:02'),
(302, 1, 'MODIFY', 'The privacy policy was changed.', '2024-01-26 01:41:47'),
(303, 1, 'MODIFY', 'The terms and conditions were changed.', '2024-01-26 01:41:47'),
(304, 1, 'MODIFY', 'The privacy policy was changed.', '2024-01-26 01:44:09'),
(305, 1, 'MODIFY', 'The terms and conditions were changed.', '2024-01-26 01:44:09'),
(306, 1, 'OTHER', 'Media Uploaded: gc4pJHd.jpg', '2024-01-26 01:21:33'),
(307, 1, 'OTHER', 'Media Uploaded: gc4pJHd.jpg', '2024-01-26 01:28:03'),
(308, 1, 'ERROR', 'CODE: [HARMONY]- AT: /admin/dashboard.php?view=media&media=edit&action=edit&id=19', '2024-01-26 01:30:24'),
(309, NULL, 'LOGIN', 'User admin', '2024-01-26 13:13:33'),
(310, 1, 'OTHER', 'CREATEDIntern', '2024-01-26 17:35:06'),
(311, 1, 'OTHER', 'CREATEDIntern', '2024-01-26 17:39:44'),
(312, 1, 'DELETE', 'Job ID: 2 Job Name: Intern', '2024-01-26 18:16:43'),
(313, 1, 'OTHER', 'CREATEDIntern', '2024-01-26 18:18:09'),
(314, 1, 'DELETE', 'Job ID: 3 Job Name: Intern', '2024-01-26 18:22:20'),
(315, 1, 'OTHER', 'CREATEDIntern', '2024-01-26 18:30:03'),
(316, 1, 'OTHER', 'UPDATEDIntern', '2024-01-26 21:57:51'),
(317, 1, 'CREATE', 'Report:  Jobs by Field - ID: 58 Date: 01/26/2024', '2024-01-26 22:17:18'),
(318, 1, 'OTHER', 'UPDATEDIntern', '2024-01-29 19:38:56'),
(319, 1, 'CREATE', 'Report:  Top Degree by School - ID: 59 Date: 01/29/2024', '2024-01-30 01:29:39'),
(320, NULL, 'LOGIN', 'User admin', '2024-02-01 00:50:50'),
(321, 1, 'CREATE', 'Report:  Top Degree by School - ID: 60 Date: 01/31/2024', '2024-02-01 00:55:48'),
(322, 1, 'CREATE', 'Report:  Jobs by Field - ID: 61 Date: 01/31/2024', '2024-02-01 01:16:29'),
(323, 1, 'CREATE', 'Report:  Jobs by Field - ID: 62 Date: 01/31/2024', '2024-02-01 01:18:31'),
(324, 1, 'CREATE', 'Report:  Jobs by Field - ID: 63 Date: 01/31/2024', '2024-02-01 01:28:31'),
(325, 1, 'CREATE', 'Report:  Major to Field Ratio by School - ID: 64 Date: 01/31/2024', '2024-02-01 01:31:12'),
(326, 1, 'CREATE', 'Report:  Jobs by Field - ID: 65 Date: 02/01/2024', '2024-02-01 22:03:39'),
(327, 1, 'MODIFY', 'The application name was changed to TalentFlow.', '2024-02-02 20:33:46'),
(328, 1, 'CREATE', 'Report:  Contact Follow-Up Percentage - ID: 66 Date: 02/02/2024', '2024-02-02 20:43:58'),
(329, NULL, 'OTHER', 'Lunar Gobson Added', '2024-02-05 09:55:42'),
(330, NULL, 'EMAIL', 'Sent Lunar Gobson @ LunaGob92@gmail.com - Subject: Thank you Lunar, for registering! by SYSTEM', '2024-02-05 09:55:42'),
(331, 1, 'CREATE', 'Report:  Contact Follow-Up Percentage - ID: 67 Date: 02/05/2024', '2024-02-05 10:02:59'),
(332, 1, 'DELETE', 'Student ID: 11 Student Name: Lunar Gobson', '2024-02-05 10:09:55'),
(333, NULL, 'OTHER', 'Patrick Barnhardt Added', '2024-02-05 23:34:43'),
(334, NULL, 'EMAIL', 'Sent Patrick Barnhardt @ contact@patrickbarnhardt.info - Subject: Thank you Patrick, for registering! by SYSTEM', '2024-02-05 23:34:43'),
(335, NULL, 'OTHER', 'Patrick Barnhardt Added', '2024-02-05 23:36:03'),
(336, NULL, 'EMAIL', 'Sent Patrick Barnhardt @ thecrimsonstrife@gmail.com - Subject: Thank you Patrick, for registering! by SYSTEM', '2024-02-05 23:36:03'),
(337, NULL, 'OTHER', 'Patrick Barnhardt Added', '2024-02-06 00:23:26'),
(338, NULL, 'EMAIL', 'Sent Patrick Barnhardt @ thecrimsonstrife@gmail.com - Subject: Thank you Patrick, for registering! by SYSTEM', '2024-02-06 00:23:26'),
(339, NULL, 'LOGIN', 'User admin', '2024-02-06 21:13:35'),
(340, 1, 'DELETE', 'Student ID: 11 Student Name: Patrick Barnhardt', '2024-02-06 21:13:52'),
(341, NULL, 'LOGIN', 'User admin', '2024-02-07 23:14:27'),
(342, 1, 'MODIFY', 'The application name was changed to .', '2024-02-09 01:21:45'),
(343, 1, 'OTHER', 'Media Uploaded: TalentFlow-Logo.png', '2024-02-09 01:32:52'),
(344, 1, 'MODIFY', 'The application name was changed to TalentFlow.', '2024-02-09 01:50:40'),
(345, 1, 'MODIFY', 'The contact email was changed to contact@talentflow.email.', '2024-02-09 01:50:40');

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
(2, 5, 1, NULL, '2023-12-16 01:02:37', 'Thank you Sam, for registering!', 'Thank you for registering for the College Recruitment Program. We will be in touch with you soon.'),
(5, 6, 1, NULL, '2024-01-16 20:34:21', 'Thank you Emily, for registering!', 'Thank you for registering for the College Recruitment Program. We will be in touch with you soon.'),
(6, 7, 1, NULL, '2024-01-16 20:39:12', 'Thank you Alex, for registering!', 'Thank you for registering for the College Recruitment Program. We will be in touch with you soon.'),
(7, 8, 1, NULL, '2024-01-16 20:42:21', 'Thank you Olivia, for registering!', 'Thank you for registering for the College Recruitment Program. We will be in touch with you soon.'),
(8, 9, 1, NULL, '2024-01-16 20:47:01', 'Thank you Sophia, for registering!', 'Thank you for registering for the College Recruitment Program. We will be in touch with you soon.'),
(9, 10, 1, NULL, '2024-01-16 20:48:33', 'Thank you Michael, for registering!', 'Thank you for registering for the College Recruitment Program. We will be in touch with you soon.'),
(11, 5, 0, 1, '2024-01-17 21:28:43', 'test', 'this is a test'),
(12, 10, 0, 1, '2024-01-17 21:30:15', 'test', 'test message'),
(13, 5, 0, 1, '2024-01-17 21:35:09', 'test1', 'tgeaeta'),
(14, 6, 0, 1, '2024-01-17 21:36:13', 'gaelhJf', 'gaefafe'),
(15, 5, 0, 1, '2024-01-17 21:45:19', 'tests', 'testeste'),
(16, 5, 0, 1, '2024-01-17 21:47:28', 'tests', 'testeste'),
(17, 5, 0, 1, '2024-01-17 21:47:37', 'test', 'test'),
(18, 5, 0, 1, '2024-01-17 21:52:02', 'test', 'test'),
(19, 5, 0, 1, '2024-01-17 21:52:50', 'test', 'fawdadwa'),
(20, 10, 0, 1, '2024-01-17 21:53:35', 'any of the R710 left?', 'test'),
(22, 10, 0, 1, '2024-01-17 23:17:55', 'any of the R710 left?', 'test'),
(23, NULL, 1, NULL, '2024-02-05 23:34:43', 'Thank you Patrick, for registering!', 'Thank you for registering for the College Recruitment Program. We will be in touch with you soon.'),
(24, NULL, 1, NULL, '2024-02-05 23:36:03', 'Thank you Patrick, for registering!', 'Thank you for registering for the College Recruitment Program. We will be in touch with you soon.');

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
(1, 'AA - Associate of Arts', '2023-10-18 17:01:38', '2023-10-18 17:01:38', 1, 1),
(2, 'AAA - Associate of Applied Arts', '2023-10-18 17:01:38', '2023-10-18 17:01:38', 1, 1),
(3, 'AS - Associate of Science', '2023-10-18 17:02:06', '2023-10-18 17:02:06', 1, 1),
(4, 'AAS - Associate of Applied Science', '2023-10-18 17:02:28', '2023-10-18 17:02:28', 1, 1),
(5, 'BA - Bachelor of Arts', '2023-10-18 17:03:22', '2023-10-18 17:03:22', 1, 1),
(6, 'BAA - Bachelor of Applied Arts', '2023-10-18 17:03:54', '2023-10-18 17:03:54', 1, 1),
(7, 'BScIT - Bachelor of Science in Information Technology', '2023-10-18 17:05:14', '2023-12-12 19:40:49', 1, 1),
(8, 'MA - Master of Arts', '2023-10-18 17:06:00', '2023-10-18 17:06:00', 1, 1),
(9, 'MS - Master of Science', '2023-10-18 17:06:40', '2023-10-18 17:06:40', 1, 1),
(11, 'BS - Bachelor of Science', '2023-12-15 21:14:29', '2023-12-15 21:14:29', 1, 1);

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
(1, 'Software Development', '2023-10-18 17:07:54', '2023-10-18 17:07:54', 1, 1),
(2, 'Business Administration', '2023-10-18 17:08:11', '2023-10-18 17:08:11', 1, 1),
(3, 'Accounting', '2023-10-18 17:08:27', '2023-10-18 17:08:27', 1, 1),
(6, 'Computer Science', '2023-12-12 22:39:15', '2023-12-12 22:39:15', 1, 1),
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
(21, 'TalentFlow-Logo.png', 'png', 19205, '2024-02-09 01:32:52', 1, '2024-02-09 01:32:52', 1);

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
(4, 'Top Field by School', '[{\"school\": \"Rowan-Cabarrus Community College - RCCC\", \"student_count\": 2, \"field_of_study\": \"Finance\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Programming\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Supply Chain\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"IT\"}, {\"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1, \"field_of_study\": \"IT\"}]', 1, '2023-12-20 20:27:05', 1, '2023-12-20 20:27:05'),
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
(22, 'Jobs by Field', '[{\"jobs\": \"Lorem Ipsum 2, Job\", \"job_count\": 2, \"job_field_name\": \"Cybersecurity\"}, {\"jobs\": \"Packaging Operator, lorem\", \"job_count\": 2, \"job_field_name\": \"Supply Chain\"}, {\"jobs\": \"Helpdesk\", \"job_count\": 1, \"job_field_name\": \"IT\"}, {\"jobs\": \"Business Analyst\", \"job_count\": 1, \"job_field_name\": \"Analytics\"}, {\"jobs\": \"Lorem Ipsum\", \"job_count\": 1, \"job_field_name\": \"Finance\"}]', 1, '2023-12-21 19:50:29', 1, '2023-12-21 19:50:29'),
(24, 'Contact Follow-Up Percentage', '[{\"total\": 3, \"percentage\": \"33.33333\", \"top_sending_user\": \"admin\"}]', 1, '2023-12-21 21:12:35', 1, '2023-12-21 21:12:35'),
(25, 'Contact Follow-Up Percentage', '[{\"total\": 4, \"percentage\": \"50.00000\", \"top_sending_user\": \"admin\"}]', 1, '2023-12-21 21:58:51', 1, '2023-12-21 21:58:51'),
(26, 'Major to Field Ratio by School', '[{\"field\": \"Programming\", \"major\": \"Software Development\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"Supply Chain\", \"major\": \"Business Administration\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 0.33, \"school\": \"Western Governor&#039;s University - WGU\"}, {\"field\": \"IT\", \"major\": \"Computer Science\", \"ratio\": 1, \"school\": \"University of North Carolina at Charlotte - UNCC\"}, {\"field\": \"Finance\", \"major\": \"Accounting\", \"ratio\": 1, \"school\": \"Rowan-Cabarrus Community College - RCCC\"}]', 1, '2023-12-22 01:13:09', 1, '2023-12-22 01:13:09'),
(27, 'Top Degree by School', '[{\"major\": \"Accounting\", \"degree\": \"AA - Associate of Arts\", \"school\": \"Rowan-Cabarrus Community College - RCCC\", \"student_count\": 2}, {\"major\": \"Software Development\", \"degree\": \"BScIT - Bachelor of Science in Information Technology\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Business Administration\", \"degree\": \"BA - Bachelor of Arts\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"AAS - Associate of Applied Science\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}]', 1, '2023-12-29 00:04:42', 1, '2023-12-29 00:04:42'),
(28, 'Jobs by Field', '[{\"jobs\": \"Helpdesk\", \"job_count\": 1, \"job_field_name\": \"IT\"}, {\"jobs\": \"Business Analyst\", \"job_count\": 1, \"job_field_name\": \"Analytics\"}, {\"jobs\": \"Job\", \"job_count\": 1, \"job_field_name\": \"Cybersecurity\"}, {\"jobs\": \"Packaging Operator\", \"job_count\": 1, \"job_field_name\": \"Supply Chain\"}]', 1, '2024-01-09 00:57:49', 1, '2024-01-09 00:57:49'),
(29, 'Top Field by School', '[{\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Programming\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Supply Chain\"}, {\"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1, \"field_of_study\": \"IT\"}]', 1, '2024-01-16 20:07:19', 1, '2024-01-16 20:07:19'),
(30, 'Top Degree by School', '[{\"major\": \"Software Development\", \"degree\": \"BScIT - Bachelor of Science in Information Technology\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Business Administration\", \"degree\": \"BA - Bachelor of Arts\", \"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1}, {\"major\": \"Computer Science\", \"degree\": \"BS - Bachelor of Science\", \"school\": \"Duke University\", \"student_count\": 1}]', 1, '2024-01-16 20:34:49', 1, '2024-01-16 20:34:49'),
(31, 'Top Field by School', '[{\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Programming\"}, {\"school\": \"Western Governor&#039;s University - WGU\", \"student_count\": 1, \"field_of_study\": \"Supply Chain\"}, {\"school\": \"University of North Carolina at Charlotte - UNCC\", \"student_count\": 1, \"field_of_study\": \"IT\"}, {\"school\": \"Duke University\", \"student_count\": 1, \"field_of_study\": \"IT\"}]', 1, '2024-01-16 20:35:03', 1, '2024-01-16 20:35:03'),
(32, 'Contact Follow-Up Percentage', '[{\"total\": 4, \"percentage\": \"50.00000\", \"top_sending_user\": \"admin\"}]', 1, '2024-01-16 20:35:13', 1, '2024-01-16 20:35:13'),
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
(67, 'Contact Follow-Up Percentage', '[{\"total\": 18, \"percentage\": \"61.11111\", \"top_sending_user\": \"admin\"}]', 1, '2024-02-05 10:02:59', 1, '2024-02-05 10:02:59');

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
(158, 3, 62, '2024-01-23 19:05:31', 1, '2024-01-23 19:05:31', 1);

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
  `terms_conditions` longtext COLLATE utf8mb4_unicode_520_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`isSet`, `app_name`, `app_url`, `company_name`, `company_url`, `company_logo`, `company_address`, `company_city`, `company_state`, `company_zip`, `company_phone`, `app_logo`, `contact_email`, `mail_from_address`, `mail_from_name`, `mail_mailer`, `mail_host`, `mail_port`, `mail_auth_req`, `mail_username`, `mail_password`, `mail_encryption`, `privacy_policy`, `terms_conditions`) VALUES
('SET', 'TalentFlow', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'contact@talentflow.email', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<h2>Test Header</h2><p>test paragraph</p><blockquote><p>test quote</p></blockquote><ul><li>test list item 1</li><li>test list item 2</li></ul>', '<h2>Test Header</h2><p>test paragraph</p><blockquote><p>test quote</p></blockquote><ul><li>test list item 1</li><li>test list item 2</li></ul>');

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
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `first_name`, `last_name`, `email`, `phone`, `address`, `city`, `state`, `zipcode`, `interest`, `degree`, `major`, `school`, `position`, `graduation`, `created_at`, `updated_at`) VALUES
(5, 'Sam', 'Smith', 'ssmith@example.com', '223-432-5454', '2221 Devon Dr', 'Dallas', 'NC', '28034', 1, 11, 6, 2, 'FULL', '2023-12-31', '2023-12-16 01:02:37', '2023-12-16 01:02:37'),
(6, 'Emily', 'Johnson', 'emily.johnson@email.com', '(555) 123-4567', '123 College Avenue', 'Durham', 'NC', '27708', 1, 11, 6, 13, 'FULL', '2025-05-31', '2024-01-16 20:34:21', '2024-01-16 20:34:21'),
(7, 'Alex', 'Davis', 'alex.davis@email.com', '(555) 234-5678', '456 University Street', 'Stanford', 'CA', '94305', 4, 9, 8, 7, 'FULL', '2024-05-30', '2024-01-16 20:39:12', '2024-01-16 20:39:12'),
(8, 'Olivia', 'Thompson', 'olivia.thompson@email.com', '(555) 567-8901', '234 Elm Street', 'Winston-Salem', 'NC', '27109', 7, 8, 9, 16, 'FULL', '2024-12-31', '2024-01-16 20:42:21', '2024-01-16 20:42:21'),
(9, 'Sophia', 'Brown', 'sophia.brown@email.com', '(555) 567-8901', '234 Duke Circle', 'Durham', 'NC', '27708', 5, 11, 10, 13, 'INTERN', '2026-05-31', '2024-01-16 20:47:01', '2024-01-16 20:47:01'),
(10, 'Michael', 'Miller', 'michael.miller@email.com', '(555) 456-7890', '101 Blue Ridge Road', 'Durham', 'NC', '27708', 1, 11, 6, 13, 'FULL', '2024-05-31', '2024-01-16 20:48:33', '2024-01-16 20:48:33');

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
(1, 'admin', 'thecrimsonstrife@gmail.com', '$2y$10$0qU/dXyrENwWzGpwkmliM.Uw.ncYWlWQMGPp.q81mrbHWVC/wEwX2', '2023-10-12 18:34:47', NULL, '2024-01-11 01:14:19', 1),
(2, 'test', 'test@email.com', '$2y$10$aTrqh2Bjt0Zj0b3L706NpuMCBnnhALLrdy8Ba1XCCWaNvyhPJIfQy', '2024-01-17 02:29:03', 1, '2024-01-17 02:29:03', 1);

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
(7, 1, 3, '2024-01-11 01:14:19', NULL, '2024-01-11 01:14:19', NULL),
(8, 2, 11, '2024-01-17 02:29:03', NULL, '2024-01-17 02:29:03', NULL);

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
-- Dumping data for table `user_token_auth`
--

INSERT INTO `user_token_auth` (`id`, `user_id`, `user_name`, `password_hash`, `selector_hash`, `is_expired`, `expiry_date`) VALUES
(1, 1, 'admin', '$2y$10$mTEfMXuOKoix.YGz2uVPSuIxXJ/jyCpdM3tifpycUy9sSi8suld76', '$2y$10$TzwL8obqyt3uhOBBNE6PBerRpDA1m7sa0Wq56w6ks.5DwHfiKigZm', 1, '2023-11-22 17:41:56'),
(2, 1, 'admin', '$2y$10$MsbF1YERJOqcJtYab.z.yOX4sbw7g4fPiZxSgwpQkB6kYbjNB8El2', '$2y$10$UpEOAReFyZ4/Ap3hKUnQS.sOzvrbcWhKwbPwKGjyyWBbJjn6E70FW', 1, '2023-12-15 16:12:06'),
(3, 1, 'admin', '$2y$10$tkuu26p67cmtu.b1sPLuIOTkB4S8ImXpt/w2r.9iRwmof90yweLou', '$2y$10$bSCA5g6dEO31Te/4HJWI9ueX2lNM9S1P7LtsmQVn50BTmcCTlEv4C', 1, '2023-12-19 16:42:56'),
(4, 1, 'admin', '$2y$10$vtA0bcR9Y0XqGFl8FIgxzO0.IbIBh9qt.7UDvHmwCkxV1ODGNZ6va', '$2y$10$LECAISZra5Vbb68P0WXN.Oa2ZTH.ixsTWW3FHRE2Or6vPYjhi8nlO', 1, '2023-12-20 18:26:34'),
(5, 1, 'admin', '$2y$10$aPxsdxoE7K7goLSn8qQXveV8kvlpcQCCx5kjpFmzyYy.v7wvvQlOe', '$2y$10$JJwRijaUU0mOrtHPch3l6ed422jZ9P8Ajnvd8M1QXmhTbM/xDHA4u', 1, '2023-12-22 14:55:46'),
(6, 1, 'admin', '$2y$10$jr.RWYdJidisIVwSM7JVOeqXXxkc1y4Z2kMM2jrJQ.GsxkSs2YNS2', '$2y$10$IRZV.robFoGB/0F9H/w3HuD69jgcAwCVi915obWM246nTjrcqbdEG', 1, '2023-12-28 18:20:28'),
(7, 1, 'admin', '$2y$10$TouCpqkP2F8PntjUW.VGBeH.8mKgJsz7Ngxw8TWRxUCldIYcW9uCG', '$2y$10$4pY.lGSMaU3xnXpOeC2.auqQwLmBp3Hyj8e1iQ2ekWCq6D30jg3mG', 1, '2024-01-04 16:03:49'),
(8, 1, 'admin', '$2y$10$mvI/6VXDNHqQ3gQ/8llZXuJZUJBA1zoMyQgf8K2fDtg7FskRuI.qS', '$2y$10$cWM8kj.m4erzBDENe1UWSOHn9RrtIEKdpBECdXhVC6nllwtY7smWy', 1, '2024-01-04 16:42:32'),
(9, 1, 'admin', '$2y$10$ANzgGffe8kpUSxz74xobaOfHl96cK4ylRtreV5i8ehudAHH2ZARti', '$2y$10$aJNLOp2WAQhwsilKH.WuCuK7..iqRF0G9Fq2vD2sWRKpI7CYKUc0y', 1, '2024-01-08 16:25:40'),
(10, 1, 'admin', '$2y$10$M0HPJcV79C1.2koi2fTw4OGDN9M17HR/x3FUehTupdrwMs/Z2E.Ya', '$2y$10$SsvZmduDnQ22r5x/HLUY4uDibkGUAe/11IwZisuAUPSgbSu5/GL86', 1, '2024-01-12 14:44:47'),
(11, 1, 'admin', '$2y$10$k73UYboDHcKBeUF2DR556Oteivl4IhdW3tSax0R3z2lEiFeKTXzUS', '$2y$10$0RGknWVv80mvF13A8J/at.ZfqK38GSBU5Cqxu911vkW0tqhGVujIy', 1, '2024-01-15 20:37:18'),
(12, 1, 'admin', '$2y$10$LvJsCTVOYqUYO3HCNjJpDut.5rPnM7mIkO/wi59uUtEI9ttEuQVqK', '$2y$10$US85Jb1kbg5/PddUPsC4xOy2eVuDg5UeqE5JTXeCWxbBbEnYG5d7G', 1, '2024-01-16 15:57:58'),
(13, 1, 'admin', '$2y$10$5zg9mZqQGbOIt7dmo.lmeOZuZNrwMGMW1VZEv2FrsFvXG9oLkCHBG', '$2y$10$SjvtW3coyIHg1lOW0ZConeCt/YrlCxakPt84l2zgSKpNZiynyqDx2', 1, '2024-01-17 14:30:40'),
(14, 2, 'test', '$2y$10$VK1qmYCv5Ry1tJg6xgAZIeMBmi3qfIh2FMZWHRP1Oc7gmr3sPGC5e', '$2y$10$fHYlrz.l8.cE4xbb074REuP.arA/W.AZ7qRn8nMnSnxym1AxkB29y', 1, '2024-01-31 19:50:50');

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
  ADD KEY `school` (`school`);

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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=346;

--
-- AUTO_INCREMENT for table `aoi`
--
ALTER TABLE `aoi`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contact_log`
--
ALTER TABLE `contact_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `degree_lvl`
--
ALTER TABLE `degree_lvl`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `role_has_permission`
--
ALTER TABLE `role_has_permission`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `student_at_event`
--
ALTER TABLE `student_at_event`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_has_role`
--
ALTER TABLE `user_has_role`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_token_auth`
--
ALTER TABLE `user_token_auth`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
