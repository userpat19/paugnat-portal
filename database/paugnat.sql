-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 25, 2026 at 03:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `paugnatDb`
--

-- --------------------------------------------------------


--
-- Table structure for table `adminLogs`
--




CREATE TABLE `adminLogs` (
  `logId` int(11) NOT NULL,
  `adminId` int(11) DEFAULT NULL,
  `actionType` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `affectedTable` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminLogs`
--

INSERT INTO `adminLogs` (`logId`, `adminId`, `actionType`, `affectedTable`, `description`, `createdAt`) VALUES
(1, 2, 'INSERT', 'admins', 'New admin created: admin2', '2026-04-17 03:19:19'),
(2, 2, 'UPDATE', 'admins', 'Username changed', '2026-04-17 03:21:13'),
(3, 1, 'DELETE', 'admins', 'Admin deleted: admin', '2026-04-17 03:22:16'),
(4, 3, 'INSERT', 'admins', 'New admin created: Patrick', '2026-04-18 03:44:08'),
(5, 3, 'UPDATE', 'admins', 'Password changed', '2026-04-18 03:44:34'),
(6, 2, 'DELETE', 'admins', 'Admin deleted: Justine', '2026-04-18 03:44:50'),
(7, 3, 'UPDATE', 'admins', 'Username changed', '2026-04-18 03:47:59'),
(8, 3, 'UPDATE', 'admins', 'Password changed', '2026-04-18 03:47:59');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(3, 'PatrickLouise', 'mypassword');

--
-- Triggers `admins`
--
DELIMITER $$
CREATE TRIGGER `after_admin_delete` AFTER DELETE ON `admins` FOR EACH ROW BEGIN
    INSERT INTO adminLogs (adminId, actionType, affectedTable, description)
    VALUES (
        OLD.id,
        'DELETE',
        'admins',
        CONCAT('Admin deleted: ', OLD.username)
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_admin_insert` AFTER INSERT ON `admins` FOR EACH ROW BEGIN
    INSERT INTO adminLogs (adminId, actionType, affectedTable, description)
    VALUES (
        NEW.id,
        'INSERT',
        'admins',
        CONCAT('New admin created: ', NEW.username)
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_admin_update` AFTER UPDATE ON `admins` FOR EACH ROW BEGIN
    IF OLD.username <> NEW.username THEN
        INSERT INTO adminLogs (adminId, actionType, affectedTable, description)
        VALUES (NEW.id, 'UPDATE', 'admins', 'Username changed');
    END IF;

    IF OLD.password <> NEW.password THEN
        INSERT INTO adminLogs (adminId, actionType, affectedTable, description)
        VALUES (NEW.id, 'UPDATE', 'admins', 'Password changed');
    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `colleges`
--

CREATE TABLE `colleges` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `dean_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `building` varchar(100) DEFAULT NULL,
  `established_year` year(4) DEFAULT NULL,
  `points` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `colleges`
--
DELIMITER $$
CREATE TRIGGER `colleges_after_delete` AFTER DELETE ON `colleges` FOR EACH ROW BEGIN
    INSERT INTO college_logs (college_id, action_type, description)
    VALUES (
        OLD.id,
        'DELETE',
        CONCAT('Deleted college: ', OLD.name)
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `colleges_after_insert` AFTER INSERT ON `colleges` FOR EACH ROW BEGIN
    INSERT INTO college_logs (college_id, action_type, description)
    VALUES (
        NEW.id,
        'INSERT',
        CONCAT('College created: ', NEW.name)
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `colleges_after_update` AFTER UPDATE ON `colleges` FOR EACH ROW BEGIN
    DECLARE changes TEXT DEFAULT '';

    IF OLD.name <> NEW.name THEN
        SET changes = CONCAT(changes, 'name: ', OLD.name, ' → ', NEW.name, '; ');
    END IF;

    IF OLD.code <> NEW.code THEN
        SET changes = CONCAT(changes, 'code: ', OLD.code, ' → ', NEW.code, '; ');
    END IF;

    IF OLD.description <> NEW.description THEN
        SET changes = CONCAT(changes, 'description changed; ');
    END IF;

    IF OLD.dean_name <> NEW.dean_name THEN
        SET changes = CONCAT(changes, 'dean: ', OLD.dean_name, ' → ', NEW.dean_name, '; ');
    END IF;

    IF OLD.email <> NEW.email THEN
        SET changes = CONCAT(changes, 'email changed; ');
    END IF;

    IF OLD.phone <> NEW.phone THEN
        SET changes = CONCAT(changes, 'phone changed; ');
    END IF;

    IF OLD.building <> NEW.building THEN
        SET changes = CONCAT(changes, 'building: ', OLD.building, ' → ', NEW.building, '; ');
    END IF;

    IF OLD.established_year <> NEW.established_year THEN
        SET changes = CONCAT(changes, 'year: ', OLD.established_year, ' → ', NEW.established_year, '; ');
    END IF;

    IF OLD.points <> NEW.points THEN
        SET changes = CONCAT(changes, 'points: ', OLD.points, ' → ', NEW.points, '; ');
    END IF;

    IF OLD.status <> NEW.status THEN
        SET changes = CONCAT(changes, 'status: ', OLD.status, ' → ', NEW.status, '; ');
    END IF;

    INSERT INTO college_logs (college_id, action_type, description)
    VALUES (NEW.id, 'UPDATE', changes);

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `college_logs`
--

CREATE TABLE `college_logs` (
  `log_id` int(11) NOT NULL,
  `college_id` int(11) DEFAULT NULL,
  `action_type` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `college_logs`
--

INSERT INTO `college_logs` (`log_id`, `college_id`, `action_type`, `description`, `created_at`) VALUES
(1, 2, 'INSERT', 'College created: College of Engineering', '2026-04-18 03:36:14'),
(3, 2, 'UPDATE', 'dean: Dr. Juan → Dr. Santos; ', '2026-04-18 03:40:27'),
(4, 2, 'UPDATE', 'year: 1995 → 2000; points: 150 → 200; ', '2026-04-18 03:46:51'),
(5, 2, 'UPDATE', 'code: COE → COS; ', '2026-04-21 10:54:57'),
(6, 2, 'DELETE', 'Deleted college: College of Engineering', '2026-04-21 10:55:17');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `eventName` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `eventType` enum('sports','academic','cultural') DEFAULT 'sports',
  `eventDate` date NOT NULL,
  `startTime` time DEFAULT NULL,
  `endTime` time DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `status` enum('upcoming','ongoing','finished','cancelled') DEFAULT 'upcoming',
  `maxParticipants` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','read') NOT NULL DEFAULT 'new',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminLogs`
--
ALTER TABLE `adminLogs`
  ADD PRIMARY KEY (`logId`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `colleges`
--
ALTER TABLE `colleges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `code` (`code`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `college_logs`
--
ALTER TABLE `college_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminLogs`
--
ALTER TABLE `adminLogs`
  MODIFY `logId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `colleges`
--
ALTER TABLE `colleges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `college_logs`
--
ALTER TABLE `college_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
