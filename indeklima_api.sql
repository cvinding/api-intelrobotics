-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 18, 2019 at 02:01 PM
-- Server version: 10.1.37-MariaDB-0+deb9u1
-- PHP Version: 7.2.15-1+0~20190209065123.16+stretch~1.gbp3ad8c0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `indeklima_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `embedded_controller`
--

CREATE TABLE `embedded_controller` (
  `id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`) VALUES
(27),
(35),
(44),
(58),
(102);

-- --------------------------------------------------------

--
-- Table structure for table `room_embedded_controller`
--

CREATE TABLE `room_embedded_controller` (
  `room_id` int(11) NOT NULL,
  `embedded_controller_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `temperature`
--

CREATE TABLE `temperature` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `temperature` float NOT NULL,
  `humidity` float NOT NULL,
  `temperature_format_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `temperature_default`
--

CREATE TABLE `temperature_default` (
  `id` int(11) NOT NULL,
  `temperature` float NOT NULL,
  `temperature_format_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `temperature_default`
--

INSERT INTO `temperature_default` (`id`, `temperature`, `temperature_format_id`) VALUES
(1, 24, 1);

-- --------------------------------------------------------

--
-- Table structure for table `temperature_format`
--

CREATE TABLE `temperature_format` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `temperature_format`
--

INSERT INTO `temperature_format` (`id`, `name`) VALUES
(1, 'celsius'),
(2, 'kelvin'),
(3, 'fahrenheit');

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE `token` (
  `id` int(11) NOT NULL,
  `token` text NOT NULL,
  `user_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `embedded_controller`
--
ALTER TABLE `embedded_controller`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_embedded_controller`
--
ALTER TABLE `room_embedded_controller`
  ADD KEY `room_id_room_embedded_id` (`room_id`),
  ADD KEY `embedded_id_room_embedded_id` (`embedded_controller_id`);

--
-- Indexes for table `temperature`
--
ALTER TABLE `temperature`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id_temperature_room_id` (`room_id`),
  ADD KEY `temperature_format_id_temperature_format_id` (`temperature_format_id`);

--
-- Indexes for table `temperature_default`
--
ALTER TABLE `temperature_default`
  ADD PRIMARY KEY (`id`),
  ADD KEY `temperature_format_id__temperature_format_id` (`temperature_format_id`);

--
-- Indexes for table `temperature_format`
--
ALTER TABLE `temperature_format`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `temperature`
--
ALTER TABLE `temperature`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `temperature_default`
--
ALTER TABLE `temperature_default`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `temperature_format`
--
ALTER TABLE `temperature_format`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `room_embedded_controller`
--
ALTER TABLE `room_embedded_controller`
  ADD CONSTRAINT `embedded_id_room_embedded_id` FOREIGN KEY (`embedded_controller_id`) REFERENCES `embedded_controller` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `room_id_room_embedded_id` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `temperature`
--
ALTER TABLE `temperature`
  ADD CONSTRAINT `room_id_temperature_room_id` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `temperature_format_id_temperature_format_id` FOREIGN KEY (`temperature_format_id`) REFERENCES `temperature_format` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `temperature_default`
--
ALTER TABLE `temperature_default`
  ADD CONSTRAINT `temperature_format_id__temperature_format_id` FOREIGN KEY (`temperature_format_id`) REFERENCES `temperature_format` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
