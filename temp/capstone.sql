-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 12, 2023 at 02:29 AM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

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

CREATE TABLE `aoi` (
  `id` bigint(20) NOT NULL,
  `name` varchar(55) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `updated_by` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_log`
--

CREATE TABLE `contact_log` (
  `id` bigint(20) NOT NULL,
  `student` bigint(20) NOT NULL,
  `auto` tinyint(1) NOT NULL DEFAULT '1',
  `sender` bigint(20) DEFAULT NULL,
  `send_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subject` varchar(150) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `message` varchar(500) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `degree_lvl`
--

CREATE TABLE `degree_lvl` (
  `id` int(20) NOT NULL,
  `name` varchar(80) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) NOT NULL,
  `updated_by` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `major`
--

CREATE TABLE `major` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint(20) NOT NULL,
  `updated_by` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_topMajorBySchool`
--

CREATE TABLE `report_topMajorBySchool` (
  `major_id` bigint(20) NOT NULL,
  `major_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `school_id` bigint(20) NOT NULL,
  `school_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `student_count` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_topAOIBySchool`
--
CREATE TABLE 'report_topAOIBySchool' (
  `aoi_id` bigint(20) NOT NULL,
  `aoi_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `school_id` bigint(20) NOT NULL,
  `school_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `student_count` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_majorToAOIRatioBySchool`
--
CREATE TABLE 'report_majorToAOIRatioBySchool' (
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
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permission`
--

CREATE TABLE `role_has_permission` (
  `id` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `permission_id` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

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
  `created_by` bigint(20) NOT NULL,
  `updated_by` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` bigint(20) NOT NULL,
  `first_name` varchar(55) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `last_name` varchar(80) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `interest` bigint(20) NOT NULL,
  `grade` tinyint(2) NOT NULL,
  `major` bigint(20) NOT NULL,
  `school` bigint(20) NOT NULL,
  `position` enum('FULL','PART','INTERN') COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'INTERN',
  `graduation` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `username` varchar(55) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_has_role`
--

CREATE TABLE `user_has_role` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Indexes for dumped tables
--

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
-- Indexes for table `major`
--
ALTER TABLE `major`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `role_has_permission`
--
ALTER TABLE `role_has_permission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roleHasPermission` (`role_id`,`permission_id`),
  ADD KEY `permissionID` (`permission_id`);

--
-- Indexes for table `school`
--
ALTER TABLE `school`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `studentInterest` (`email`,`interest`),
  ADD KEY `areaOfInterest` (`interest`),
  ADD KEY `major` (`major`),
  ADD KEY `school` (`school`);

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
  ADD KEY `roleID` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aoi`
--
ALTER TABLE `aoi`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_log`
--
ALTER TABLE `contact_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `degree_lvl`
--
ALTER TABLE `degree_lvl`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `major`
--
ALTER TABLE `major`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_has_permission`
--
ALTER TABLE `role_has_permission`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school`
--
ALTER TABLE `school`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_has_role`
--
ALTER TABLE `user_has_role`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aoi`
--
ALTER TABLE `aoi`
  ADD CONSTRAINT `createdBy` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `updatedBy` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `contact_log`
--
ALTER TABLE `contact_log`
  ADD CONSTRAINT `senderOfEmail` FOREIGN KEY (`sender`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `studentContacted` FOREIGN KEY (`student`) REFERENCES `student` (`id`);

--
-- Constraints for table `degree_lvl`
--
ALTER TABLE `degree_lvl`
  ADD CONSTRAINT `degreeCreatedBy` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `degreeUpdatedBy` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `major`
--
ALTER TABLE `major`
  ADD CONSTRAINT `majorCreatedBy` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `majorUpdatedBy` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `role_has_permission`
--
ALTER TABLE `role_has_permission`
  ADD CONSTRAINT `permissionID` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`),
  ADD CONSTRAINT `roleForPermission` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `school`
--
ALTER TABLE `school`
  ADD CONSTRAINT `schoolAddedBy` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `schoolUpdatedBy` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `areaOfInterest` FOREIGN KEY (`interest`) REFERENCES `aoi` (`id`),
  ADD CONSTRAINT `areaOfStudy` FOREIGN KEY (`major`) REFERENCES `major` (`id`),
  ADD CONSTRAINT `schoolAttended` FOREIGN KEY (`school`) REFERENCES `school` (`id`);

--
-- Constraints for table `user_has_role`
--
ALTER TABLE `user_has_role`
  ADD CONSTRAINT `roleID` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `userID` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

--
-- setup joins summary report of top majors by school on table report_topMajorBySchool
--
SELECT major.id AS major_id, major.name AS major_name, school.id AS school_id, school.name AS school_name, COUNT(student.id) AS student_count
FROM student
INNER JOIN major ON student.major = major.id
INNER JOIN school ON student.school = school.id
GROUP BY major.id, school.id
ORDER BY school.id, student_count DESC;

--
-- setup joins summary report of top areas of interest by school on table report_topAOIBySchool
--
SELECT aoi.id AS aoi_id, aoi.name AS aoi_name, school.id AS school_id, school.name AS school_name, COUNT(student.id) AS student_count
FROM student
INNER JOIN aoi ON student.interest = aoi.id
INNER JOIN school ON student.school = school.id
GROUP BY aoi.id, school.id
ORDER BY school.id, student_count DESC;

--
-- setup joins summary report comparison of top majors to areas of interest by school on table report_majorToAOIRatioBySchool
--
SELECT major.id AS major_id, major.name AS major_name, aoi.id AS aoi_id, aoi.name AS aoi_name, school.id AS school_id, school.name AS school_name, COUNT(student.id) AS student_count
FROM student
INNER JOIN major ON student.major = major.id
INNER JOIN aoi ON student.interest = aoi.id
INNER JOIN school ON student.school = school.id
GROUP BY major.id, aoi.id, school.id
ORDER BY school.id, student_count DESC;

--
-- setup triggers to update the report_topMajorBySchool table
--
CREATE TRIGGER `updateOnInsert_topMajorBySchool` AFTER INSERT ON `student` FOR EACH ROW
BEGIN
    INSERT INTO report_topMajorBySchool (major_id, major_name, school_id, school_name, student_count)
    SELECT major.id AS major_id, major.name AS major_name, school.id AS school_id, school.name AS school_name, COUNT(student.id) AS student_count
    FROM student
    INNER JOIN major ON student.major = major.id
    INNER JOIN school ON student.school = school.id
    WHERE major.id = NEW.major
    GROUP BY major.id, school.id
    ORDER BY school.id, student_count DESC
    ON DUPLICATE KEY UPDATE student_count = student_count + 1;
END;

CREATE TRIGGER `updateOnUpdate_topMajorBySchool` AFTER UPDATE ON `student` FOR EACH ROW
BEGIN
    INSERT INTO report_topMajorBySchool (major_id, major_name, school_id, school_name, student_count)
    SELECT major.id AS major_id, major.name AS major_name, school.id AS school_id, school.name AS school_name, COUNT(student.id) AS student_count
    FROM student
    INNER JOIN major ON student.major = major.id
    INNER JOIN school ON student.school = school.id
    WHERE major.id = NEW.major
    GROUP BY major.id, school.id
    ORDER BY school.id, student_count DESC
    ON DUPLICATE KEY UPDATE student_count = student_count + 1;
END;

CREATE TRIGGER `updateOnDelete_topMajorBySchool` AFTER DELETE ON `student` FOR EACH ROW
BEGIN
    INSERT INTO report_topMajorBySchool (major_id, major_name, school_id, school_name, student_count)
    SELECT major.id AS major_id, major.name AS major_name, school.id AS school_id, school.name AS school_name, COUNT(student.id) AS student_count
    FROM student
    INNER JOIN major ON student.major = major.id
    INNER JOIN school ON student.school = school.id
    WHERE major.id = OLD.major
    GROUP BY major.id, school.id
    ORDER BY school.id, student_count DESC
    ON DUPLICATE KEY UPDATE student_count = student_count - 1;
END;

--
-- setup triggers to update the report_topAOIBySchool table
--
CREATE TRIGGER `updateOnInsert_topAOIBySchool` AFTER INSERT ON `student` FOR EACH ROW
BEGIN
    INSERT INTO report_topAOIBySchool (aoi_id, aoi_name, school_id, school_name, student_count)
    SELECT aoi.id AS aoi_id, aoi.name AS aoi_name, school.id AS school_id, school.name AS school_name, COUNT(student.id) AS student_count
    FROM student
    INNER JOIN aoi ON student.interest = aoi.id
    INNER JOIN school ON student.school = school.id
    WHERE aoi.id = NEW.interest
    GROUP BY aoi.id, school.id
    ORDER BY school.id, student_count DESC
    ON DUPLICATE KEY UPDATE student_count = student_count + 1;
END;

CREATE TRIGGER `updateOnUpdate_topAOIBySchool` AFTER UPDATE ON `student` FOR EACH ROW
BEGIN
    INSERT INTO report_topAOIBySchool (aoi_id, aoi_name, school_id, school_name, student_count)
    SELECT aoi.id AS aoi_id, aoi.name AS aoi_name, school.id AS school_id, school.name AS school_name, COUNT(student.id) AS student_count
    FROM student
    INNER JOIN aoi ON student.interest = aoi.id
    INNER JOIN school ON student.school = school.id
    WHERE aoi.id = NEW.interest
    GROUP BY aoi.id, school.id
    ORDER BY school.id, student_count DESC
    ON DUPLICATE KEY UPDATE student_count = student_count + 1;
END;

CREATE TRIGGER `updateOnDelete_topAOIBySchool` AFTER DELETE ON `student` FOR EACH ROW
BEGIN
    INSERT INTO report_topAOIBySchool (aoi_id, aoi_name, school_id, school_name, student_count)
    SELECT aoi.id AS aoi_id, aoi.name AS aoi_name, school.id AS school_id, school.name AS school_name, COUNT(student.id) AS student_count
    FROM student
    INNER JOIN aoi ON student.interest = aoi.id
    INNER JOIN school ON student.school = school.id
    WHERE aoi.id = OLD.interest
    GROUP BY aoi.id, school.id
    ORDER BY school.id, student_count DESC
    ON DUPLICATE KEY UPDATE student_count = student_count - 1;
END;

--
-- setup triggers to update the report_majorToAOIRatioBySchool table
--
CREATE TRIGGER `updateOnInsert_majorToAOIRatioBySchool` AFTER INSERT ON `student` FOR EACH ROW
BEGIN
    INSERT INTO report_majorToAOIRatioBySchool (major_id, major_name, aoi_id, aoi_name, school_id, school_name, student_count)
    SELECT major.id AS major_id, major.name AS major_name, aoi.id AS aoi_id, aoi.name AS aoi_name, school.id AS school_id, school.name AS school_name, COUNT(student.id) AS student_count
    FROM student
    INNER JOIN major ON student.major = major.id
    INNER JOIN aoi ON student.interest = aoi.id
    INNER JOIN school ON student.school = school.id
    WHERE major.id = NEW.major AND aoi.id = NEW.interest
    GROUP BY major.id, aoi.id, school.id
    ORDER BY school.id, student_count DESC
    ON DUPLICATE KEY UPDATE student_count = student_count + 1;
END;

CREATE TRIGGER `updateOnUpdate_majorToAOIRatioBySchool` AFTER UPDATE ON `student` FOR EACH ROW
BEGIN
    INSERT INTO report_majorToAOIRatioBySchool (major_id, major_name, aoi_id, aoi_name, school_id, school_name, student_count)
    SELECT major.id AS major_id, major.name AS major_name, aoi.id AS aoi_id, aoi.name AS aoi_name, school.id AS school_id, school.name AS school_name, COUNT(student.id) AS student_count
    FROM student
    INNER JOIN major ON student.major = major.id
    INNER JOIN aoi ON student.interest = aoi.id
    INNER JOIN school ON student.school = school.id
    WHERE major.id = NEW.major AND aoi.id = NEW.interest
    GROUP BY major.id, aoi.id, school.id
    ORDER BY school.id, student_count DESC
    ON DUPLICATE KEY UPDATE student_count = student_count + 1;
END;

CREATE TRIGGER `updateOnDelete_majorToAOIRatioBySchool` AFTER DELETE ON `student` FOR EACH ROW
BEGIN
    INSERT INTO report_majorToAOIRatioBySchool (major_id, major_name, aoi_id, aoi_name, school_id, school_name, student_count)
    SELECT major.id AS major_id, major.name AS major_name, aoi.id AS aoi_id, aoi.name AS aoi_name, school.id AS school_id, school.name AS school_name, COUNT(student.id) AS student_count
    FROM student
    INNER JOIN major ON student.major = major.id
    INNER JOIN aoi ON student.interest = aoi.id
    INNER JOIN school ON student.school = school.id
    WHERE major.id = OLD.major AND aoi.id = OLD.interest
    GROUP BY major.id, aoi.id, school.id
    ORDER BY school.id, student_count DESC
    ON DUPLICATE KEY UPDATE student_count = student_count - 1;
END;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
