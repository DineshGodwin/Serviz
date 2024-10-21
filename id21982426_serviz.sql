-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 07, 2024 at 05:42 AM
-- Server version: 10.5.20-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id21982426_serviz`
--

-- --------------------------------------------------------

--
-- Table structure for table `acceptanceletter`
--

CREATE TABLE `acceptanceletter` (
  `Fid` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `folderpath` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `gid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `acceptanceletter`
--

INSERT INTO `acceptanceletter` (`Fid`, `filename`, `folderpath`, `timestamp`, `gid`) VALUES
(2, 'visualization.pdf', 'uploads/AcceptanceLetter/visualization.pdf', '2024-03-07 10:25:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `accepts_table`
--

CREATE TABLE `accepts_table` (
  `aid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `accepts_table`
--

INSERT INTO `accepts_table` (`aid`, `user_id`, `gid`, `timestamp`) VALUES
(1, 2160418, 7, '2024-03-20 09:51:40'),
(2, 2160418, 7, '2024-03-28 11:18:19'),
(3, 2160418, 7, '2024-03-28 11:18:19'),
(4, 2160334, 8, '2024-05-10 10:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `verify_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `email`, `verify_token`) VALUES
(1, 'admin', '1234', 'aleeza.alex@btech.christuniversity.in', '493568a8642379f27b43652f0a46e506');

-- --------------------------------------------------------

--
-- Table structure for table `completionletter`
--

CREATE TABLE `completionletter` (
  `Fid` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `folderpath` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `gid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `completionletter`
--

INSERT INTO `completionletter` (`Fid`, `filename`, `folderpath`, `timestamp`, `gid`) VALUES
(2, 'serviz rport.pdf', 'reports/CompletionLetter/serviz rport.pdf', '2024-03-06 18:28:12', NULL),
(3, 'serviz rport.pdf', 'uploads/CompletionLetter/serviz rport.pdf', '2024-03-07 10:23:45', NULL),
(4, 'visualization.pdf', 'reports/CompletionLetter/visualization.pdf', '2024-03-14 20:27:14', NULL),
(5, 'ANTI-SLEEPING ALARM SYSTEM.pptx', 'reports/CompletionLetter/ANTI-SLEEPING ALARM SYSTEM.pptx', '2024-03-14 20:40:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `classassg` text DEFAULT NULL,
  `verify_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `name`, `email`, `password`, `classassg`, `verify_token`) VALUES
(1021, 'aa', 'abeera.a@btech.christuniversity.in', '$2y$10$851fngK/9oc/1QYqn6JxTOJXJh33DWSVYa1pv23.PnhXHmWcgLKFG', 'CSE A', 'd8e7cb047c1f8fd98510e6989e5f4262'),
(1022, 'anu', 'ananya.johny@btech.christuniversity.in', '$2y$10$2By3SwBhU5giDnqpKDAIweddtwr3Y8fU6nW59Rolg/nZGMsEYz1xC', 'CSE A', '4a8b939791ccfa104b50c123b4ba9f80'),
(1033, 'aa', 'aa@gmail.com', '$2y$10$rJ1AXM4oncTxz6FkRkUaCO3G4jTS7q821zVGLj1aDZnB3BT8ZQxNu', 'CSE A', NULL),
(1034, 'bb', 'bb@gmail.com', '$2y$10$sBwDK6efzjZT4RynW0Fq/OKL9QGuHfMICFshQW.ElYbnGxHlvy68i', 'CSE B', NULL),
(1037, 'tt', 'tt@gmail.com', '$2y$10$Z.fkPO0dWq6l4pNxhBcus.3A1LhyJX3uFO9FO5FksKOTH9wOhMZC2', 'CSE B', NULL),
(1038, 'hh', 'hh@gmail.com', '$2y$10$P74LNtUCxG1qNKNX596kFuJNMH.fJJuzw6OESShBWc82YkmaWo3nG', 'CSE C', NULL),
(1039, 'll', 'll@gmail.com', '78542', 'AIML', NULL),
(1040, 'mm', 'mm@gmail.com', '84553', 'DS', NULL),
(1041, 'nn', 'nn@gmail.com', '*956', 'IOT', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `finalreport`
--

CREATE TABLE `finalreport` (
  `Fid` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `folderpath` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `gid` int(11) DEFAULT NULL,
  `sno` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `finalreport`
--

INSERT INTO `finalreport` (`Fid`, `filename`, `folderpath`, `timestamp`, `gid`, `sno`) VALUES
(14, 'serviz rport.pdf', 'reports/FinalReport/serviz rport.pdf', '2024-03-20 15:20:42', NULL, 17),
(15, 'visualization.pdf', 'reports/FinalReport/visualization.pdf', '2024-03-20 15:20:48', 8, 19),
(17, 'bio abeera.pdf', 'reports/FinalReport/bio abeera.pdf', '2024-03-20 15:55:22', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `idea`
--

CREATE TABLE `idea` (
  `projtitle` text NOT NULL,
  `gid` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL,
  `projdesc` varchar(200) NOT NULL,
  `community` varchar(30) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `year` int(11) NOT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `idea`
--

INSERT INTO `idea` (`projtitle`, `gid`, `id`, `projdesc`, `community`, `status`, `year`, `comment`) VALUES
('attendance', NULL, 17, 'feff', 'RR', 1, 2023, 'gd'),
('attendance ', 8, 19, 'dtgf', 'kengeri', 1, 2024, 'Very good');

-- --------------------------------------------------------

--
-- Table structure for table `markstable`
--

CREATE TABLE `markstable` (
  `Mid` int(11) NOT NULL,
  `Gid` int(11) DEFAULT NULL,
  `regno` int(11) NOT NULL,
  `identification` int(11) NOT NULL,
  `survey` int(11) NOT NULL,
  `proof` int(11) NOT NULL,
  `presentation` int(11) NOT NULL,
  `evaluation` int(11) NOT NULL,
  `deployement` int(11) NOT NULL,
  `engagement` int(11) NOT NULL,
  `ese` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `markstable`
--

INSERT INTO `markstable` (`Mid`, `Gid`, `regno`, `identification`, `survey`, `proof`, `presentation`, `evaluation`, `deployement`, `engagement`, `ese`) VALUES
(20, 8, 2160000, 33, 33, 33, 333, 33, 3, 33, 111),
(21, 8, 2160001, 33, 34, 43, 34, 34, 43, 34, 34);

-- --------------------------------------------------------

--
-- Table structure for table `requests_table`
--

CREATE TABLE `requests_table` (
  `request_id` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests_table`
--

INSERT INTO `requests_table` (`request_id`, `gid`, `user_id`, `timestamp`) VALUES
(16, 7, 2160417, '2024-03-20 10:15:59');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `name` varchar(50) NOT NULL,
  `regno` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text DEFAULT NULL,
  `class` text NOT NULL,
  `verify_token` varchar(255) DEFAULT NULL,
  `slt` int(11) DEFAULT NULL,
  `year` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`name`, `regno`, `email`, `password`, `class`, `verify_token`, `slt`, `year`) VALUES
('ry', 2160000, 'ryanvp2003@gmail.com', '1234', 'CSE A', NULL, 8, 21),
('yan', 2160001, 'ryanvp33@gmail.com', '$2y$10$knINxili2MU/oYmjQpIFhe25JDk.LN9V.J/4yD1HfP3SrQkuq4jWW', 'CSE A', NULL, 8, 21),
('dinesh', 2160334, 'dinesh.godwin@btech.christuniversity.in', '1234', 'CSE A', NULL, 8, 21);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `gid` int(11) NOT NULL,
  `teamleader` varchar(256) NOT NULL,
  `no` int(11) NOT NULL,
  `class` varchar(50) NOT NULL,
  `tlreg` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`gid`, `teamleader`, `no`, `class`, `tlreg`) VALUES
(8, 'ry', 3, 'CSE A', 2160000);

-- --------------------------------------------------------

--
-- Table structure for table `weeklyreport`
--

CREATE TABLE `weeklyreport` (
  `Fid` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `folderpath` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `gid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `weeklyreport`
--

INSERT INTO `weeklyreport` (`Fid`, `filename`, `folderpath`, `timestamp`, `gid`) VALUES
(2, 'SELF ASSISMENT.pdf', 'uploads/WeeklyReport/SELF ASSISMENT.pdf', '2024-03-07 10:26:05', NULL),
(3, 'serviz rport.pdf', 'uploads/WeeklyReport/serviz rport.pdf', '2024-03-07 10:29:17', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acceptanceletter`
--
ALTER TABLE `acceptanceletter`
  ADD PRIMARY KEY (`Fid`),
  ADD KEY `acceptanceletter_ibfk_1` (`gid`);

--
-- Indexes for table `accepts_table`
--
ALTER TABLE `accepts_table`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `completionletter`
--
ALTER TABLE `completionletter`
  ADD PRIMARY KEY (`Fid`),
  ADD KEY `completionletter_ibfk_1` (`gid`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `finalreport`
--
ALTER TABLE `finalreport`
  ADD PRIMARY KEY (`Fid`),
  ADD KEY `gid` (`gid`),
  ADD KEY `sno` (`sno`);

--
-- Indexes for table `idea`
--
ALTER TABLE `idea`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Gpid` (`gid`);

--
-- Indexes for table `markstable`
--
ALTER TABLE `markstable`
  ADD PRIMARY KEY (`Mid`),
  ADD UNIQUE KEY `regno` (`regno`),
  ADD KEY `Gid` (`Gid`);

--
-- Indexes for table `requests_table`
--
ALTER TABLE `requests_table`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`regno`),
  ADD KEY `student_ibfk_1` (`slt`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`gid`);

--
-- Indexes for table `weeklyreport`
--
ALTER TABLE `weeklyreport`
  ADD PRIMARY KEY (`Fid`),
  ADD KEY `weeklyreport_ibfk_1` (`gid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acceptanceletter`
--
ALTER TABLE `acceptanceletter`
  MODIFY `Fid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `accepts_table`
--
ALTER TABLE `accepts_table`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `completionletter`
--
ALTER TABLE `completionletter`
  MODIFY `Fid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1044;

--
-- AUTO_INCREMENT for table `finalreport`
--
ALTER TABLE `finalreport`
  MODIFY `Fid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `idea`
--
ALTER TABLE `idea`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `markstable`
--
ALTER TABLE `markstable`
  MODIFY `Mid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `requests_table`
--
ALTER TABLE `requests_table`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `gid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `weeklyreport`
--
ALTER TABLE `weeklyreport`
  MODIFY `Fid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `acceptanceletter`
--
ALTER TABLE `acceptanceletter`
  ADD CONSTRAINT `acceptanceletter_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `team` (`gid`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `completionletter`
--
ALTER TABLE `completionletter`
  ADD CONSTRAINT `completionletter_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `team` (`gid`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `finalreport`
--
ALTER TABLE `finalreport`
  ADD CONSTRAINT `finalreport_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `team` (`gid`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `finalreport_ibfk_2` FOREIGN KEY (`sno`) REFERENCES `idea` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `idea`
--
ALTER TABLE `idea`
  ADD CONSTRAINT `idea_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `team` (`gid`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `markstable`
--
ALTER TABLE `markstable`
  ADD CONSTRAINT `markstable_ibfk_1` FOREIGN KEY (`Gid`) REFERENCES `team` (`gid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`slt`) REFERENCES `team` (`gid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `weeklyreport`
--
ALTER TABLE `weeklyreport`
  ADD CONSTRAINT `weeklyreport_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `team` (`gid`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
