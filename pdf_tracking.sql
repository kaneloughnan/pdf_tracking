-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2016 at 07:42 AM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pdf_tracking`
--

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE `module` (
  `moduleId` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `text` text NOT NULL,
  `pdf` int(11) NOT NULL,
  `quiz` int(11) NOT NULL,
  `deleted` int(11) NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `moduleassign`
--

CREATE TABLE `moduleassign` (
  `moduleAssignId` int(11) NOT NULL,
  `moduleId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `viewed` int(11) NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `moduleview`
--

CREATE TABLE `moduleview` (
  `moduleViewId` int(11) NOT NULL,
  `moduleId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `sessionId` varchar(64) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `quizId` int(11) NOT NULL,
  `moduleId` int(11) NOT NULL,
  `question` varchar(512) NOT NULL,
  `answers` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quizattempt`
--

CREATE TABLE `quizattempt` (
  `quizAttemptId` int(11) NOT NULL,
  `quizId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `attempts` int(11) NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userId` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `fname` varchar(32) NOT NULL,
  `lname` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  `admin` int(11) NOT NULL,
  `deleted` int(11) NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `username`, `password`, `fname`, `lname`, `email`, `admin`, `deleted`, `timestamp`) VALUES
(1, 'admin', '$2y$10$xwSMFm0eb2u78zaiNiZRZ.rwQvOto5vUbCwvPotUNNzptsqDHRX1C', 'Admin', '', 'admin@placeholder.test', 1, 0, '2018-04-29 15:29:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`moduleId`),
  ADD KEY `deleted` (`deleted`);

--
-- Indexes for table `moduleassign`
--
ALTER TABLE `moduleassign`
  ADD PRIMARY KEY (`moduleAssignId`),
  ADD KEY `moduleId` (`moduleId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `viewed` (`viewed`);

--
-- Indexes for table `moduleview`
--
ALTER TABLE `moduleview`
  ADD PRIMARY KEY (`moduleViewId`),
  ADD KEY `moduleId` (`moduleId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `sessionId` (`sessionId`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`quizId`),
  ADD KEY `moduleId` (`moduleId`);

--
-- Indexes for table `quizattempt`
--
ALTER TABLE `quizattempt`
  ADD PRIMARY KEY (`quizAttemptId`),
  ADD KEY `quizId` (`quizId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`),
  ADD KEY `username` (`username`),
  ADD KEY `password` (`password`),
  ADD KEY `deleted` (`deleted`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `module`
--
ALTER TABLE `module`
  MODIFY `moduleId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `moduleassign`
--
ALTER TABLE `moduleassign`
  MODIFY `moduleAssignId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `moduleview`
--
ALTER TABLE `moduleview`
  MODIFY `moduleViewId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `quizId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `quizattempt`
--
ALTER TABLE `quizattempt`
  MODIFY `quizAttemptId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `moduleassign`
--
ALTER TABLE `moduleassign`
  ADD CONSTRAINT `moduleassign_ibfk_1` FOREIGN KEY (`moduleId`) REFERENCES `module` (`moduleId`),
  ADD CONSTRAINT `moduleassign_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`);

--
-- Constraints for table `moduleview`
--
ALTER TABLE `moduleview`
  ADD CONSTRAINT `moduleview_ibfk_1` FOREIGN KEY (`moduleId`) REFERENCES `module` (`moduleId`),
  ADD CONSTRAINT `moduleview_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`);

--
-- Constraints for table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `quiz_ibfk_1` FOREIGN KEY (`moduleId`) REFERENCES `module` (`moduleId`);

--
-- Constraints for table `quizattempt`
--
ALTER TABLE `quizattempt`
  ADD CONSTRAINT `quizattempt_ibfk_1` FOREIGN KEY (`quizId`) REFERENCES `quiz` (`quizId`),
  ADD CONSTRAINT `quizattempt_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
