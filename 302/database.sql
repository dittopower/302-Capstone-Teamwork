-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 27, 2015 at 09:19 AM
-- Server version: 5.5.42-37.1
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `deamon_INB302`
--

-- --------------------------------------------------------

--
-- Table structure for table `Chat`
--

CREATE TABLE IF NOT EXISTS `Chat` (
  `ChatID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `UserReceive` int(11) DEFAULT NULL,
  `GroupID` int(11) DEFAULT NULL,
  `Message` text COLLATE utf8_unicode_ci,
  `TimeSent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `D_Accounts`
--

CREATE TABLE IF NOT EXISTS `D_Accounts` (
  `UserId` int(11) NOT NULL,
  `PassPhrase` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Length` int(11) NOT NULL,
  `salt` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `Username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `FirstName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `LastName` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `Email` varchar(60) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=92213415 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `D_Articles`
--

CREATE TABLE IF NOT EXISTS `D_Articles` (
  `art_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mod_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tags` text COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `contents` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `D_Media`
--

CREATE TABLE IF NOT EXISTS `D_Media` (
  `media_id` int(11) NOT NULL,
  `location` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `owner` int(11) NOT NULL,
  `share` smallint(6) NOT NULL,
  `people` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `D_Perms`
--

CREATE TABLE IF NOT EXISTS `D_Perms` (
  `Perm_No` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `what` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `level` int(11) NOT NULL,
  `other` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Groups`
--

CREATE TABLE IF NOT EXISTS `Groups` (
  `GroupId` int(11) NOT NULL,
  `GroupName` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `GroupProject` int(11) NOT NULL,
  `UnitCode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `Supervisor` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Group_Members`
--

CREATE TABLE IF NOT EXISTS `Group_Members` (
  `GroupId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `Role` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Group_Mod`
--

CREATE TABLE IF NOT EXISTS `Group_Mod` (
  `User_Id` int(11) NOT NULL,
  `Group_Id` int(11) NOT NULL,
  `Action` enum('Add','Remove','Cancel','Deny') COLLATE utf8_unicode_ci NOT NULL,
  `Who` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Group_Requests`
--

CREATE TABLE IF NOT EXISTS `Group_Requests` (
  `UserId` int(11) NOT NULL,
  `UnitCode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `Similar` int(1) NOT NULL,
  `PreferenceType1` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `PreferenceType2` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `PreferenceType3` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Milestones`
--

CREATE TABLE IF NOT EXISTS `Milestones` (
  `MID` int(11) NOT NULL,
  `GroupId` int(11) DEFAULT NULL,
  `Content` text COLLATE utf8_unicode_ci,
  `Checked` int(1) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Projects`
--

CREATE TABLE IF NOT EXISTS `Projects` (
  `P_Id` int(11) NOT NULL,
  `Name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ProjectType1` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `ProjectType2` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ProjectType3` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `skill` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `requirements` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `UnitCode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `Supervisor` int(11) NOT NULL,
  `Start` date NOT NULL,
  `End` date NOT NULL,
  `Dueby` date NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Project_Applications`
--

CREATE TABLE IF NOT EXISTS `Project_Applications` (
  `ApplicationID` int(11) NOT NULL,
  `P_Id` int(11) DEFAULT NULL,
  `GroupId` int(11) DEFAULT NULL,
  `CoverLetter` text COLLATE utf8_unicode_ci,
  `Status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `TimeSubmitted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Project_Types`
--

CREATE TABLE IF NOT EXISTS `Project_Types` (
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Supervisor`
--

CREATE TABLE IF NOT EXISTS `Supervisor` (
  `SupervisorID` int(11) NOT NULL,
  `Username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FirstName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Surname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Unit`
--

CREATE TABLE IF NOT EXISTS `Unit` (
  `UnitCode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `Unit` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `User_Details`
--

CREATE TABLE IF NOT EXISTS `User_Details` (
  `UserId` int(11) NOT NULL,
  `GPA` decimal(7,1) DEFAULT NULL,
  `Skills` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `Blurb` text COLLATE utf8_unicode_ci NOT NULL,
  `LinkedIn` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Facebook` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Skype` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Phone` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Chat`
--
ALTER TABLE `Chat`
  ADD PRIMARY KEY (`ChatID`);

--
-- Indexes for table `D_Accounts`
--
ALTER TABLE `D_Accounts`
  ADD PRIMARY KEY (`UserId`), ADD UNIQUE KEY `usernames` (`Username`), ADD KEY `Username` (`Username`);

--
-- Indexes for table `D_Articles`
--
ALTER TABLE `D_Articles`
  ADD PRIMARY KEY (`art_id`), ADD UNIQUE KEY `art_id` (`art_id`);

--
-- Indexes for table `D_Media`
--
ALTER TABLE `D_Media`
  ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `D_Perms`
--
ALTER TABLE `D_Perms`
  ADD PRIMARY KEY (`Perm_No`), ADD KEY `UserId` (`UserId`);

--
-- Indexes for table `Groups`
--
ALTER TABLE `Groups`
  ADD PRIMARY KEY (`GroupId`);

--
-- Indexes for table `Group_Members`
--
ALTER TABLE `Group_Members`
  ADD PRIMARY KEY (`GroupId`,`UserId`);

--
-- Indexes for table `Group_Mod`
--
ALTER TABLE `Group_Mod`
  ADD PRIMARY KEY (`User_Id`,`Group_Id`,`Who`);

--
-- Indexes for table `Group_Requests`
--
ALTER TABLE `Group_Requests`
  ADD PRIMARY KEY (`UserId`,`UnitCode`);

--
-- Indexes for table `Milestones`
--
ALTER TABLE `Milestones`
  ADD PRIMARY KEY (`MID`);

--
-- Indexes for table `Projects`
--
ALTER TABLE `Projects`
  ADD PRIMARY KEY (`P_Id`);

--
-- Indexes for table `Project_Applications`
--
ALTER TABLE `Project_Applications`
  ADD PRIMARY KEY (`ApplicationID`);

--
-- Indexes for table `Project_Types`
--
ALTER TABLE `Project_Types`
  ADD PRIMARY KEY (`type`);

--
-- Indexes for table `Supervisor`
--
ALTER TABLE `Supervisor`
  ADD PRIMARY KEY (`SupervisorID`);

--
-- Indexes for table `Unit`
--
ALTER TABLE `Unit`
  ADD PRIMARY KEY (`UnitCode`);

--
-- Indexes for table `User_Details`
--
ALTER TABLE `User_Details`
  ADD PRIMARY KEY (`UserId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Chat`
--
ALTER TABLE `Chat`
  MODIFY `ChatID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT for table `D_Accounts`
--
ALTER TABLE `D_Accounts`
  MODIFY `UserId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=92213415;
--
-- AUTO_INCREMENT for table `D_Articles`
--
ALTER TABLE `D_Articles`
  MODIFY `art_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=106;
--
-- AUTO_INCREMENT for table `D_Media`
--
ALTER TABLE `D_Media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `D_Perms`
--
ALTER TABLE `D_Perms`
  MODIFY `Perm_No` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `Groups`
--
ALTER TABLE `Groups`
  MODIFY `GroupId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `Milestones`
--
ALTER TABLE `Milestones`
  MODIFY `MID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `Projects`
--
ALTER TABLE `Projects`
  MODIFY `P_Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `Project_Applications`
--
ALTER TABLE `Project_Applications`
  MODIFY `ApplicationID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `Supervisor`
--
ALTER TABLE `Supervisor`
  MODIFY `SupervisorID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
