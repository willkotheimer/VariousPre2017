-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 05, 2017 at 05:52 AM
-- Server version: 5.5.38
-- PHP Version: 5.6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `Property`
--

CREATE TABLE `Property` (
`Id` int(11) NOT NULL,
  `Name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `brand` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `URL` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `isFullService` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Property`
--

INSERT INTO `Property` (`Id`, `Name`, `brand`, `phone`, `URL`, `isFullService`) VALUES
(1, 'South Hampton', 'Marriot', '216-555-8888', 'www.southhamptonmarriot.com', '1'),
(7, 'Nashville', 'Hilton', 'phone', 'www.hilton.com', '0'),
(9, 'Memphis', 'Hilton', '901-827-2621', 'www.hilton.com', '1'),
(14, 'Memphis', 'Peabody', '901-888-2134', 'www.duckgalore.com', '1'),
(15, 'Louisville', 'Holiday Inn', 'phone', 'http://holiday.com', '0');

-- --------------------------------------------------------

--
-- Table structure for table `Region`
--

CREATE TABLE `Region` (
`Id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Region`
--

INSERT INTO `Region` (`Id`, `name`) VALUES
(1, 'Northlands'),
(2, 'South'),
(3, 'Southern Kentucky');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
`id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `role` int(3) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
(1, 'wkotheimer', 'dbadmin', 'wkotheimer@gmail.com', 1),
(2, 'testuser', 'password', 'wkotheimer@gmail.com', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Property`
--
ALTER TABLE `Property`
 ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Region`
--
ALTER TABLE `Region`
 ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Property`
--
ALTER TABLE `Property`
MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `Region`
--
ALTER TABLE `Region`
MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;