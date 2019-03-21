-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 21, 2019 at 01:19 PM
-- Server version: 10.3.12-MariaDB
-- PHP Version: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `intelrobotics_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `company_financials`
--

DROP TABLE IF EXISTS `company_financials`;
CREATE TABLE IF NOT EXISTS `company_financials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `upload_path` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `company_groups`
--

DROP TABLE IF EXISTS `company_groups`;
CREATE TABLE IF NOT EXISTS `company_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `company_groups`
--

INSERT INTO `company_groups` (`id`, `name`) VALUES
(1, 'HR'),
(2, 'Webmaster');

-- --------------------------------------------------------

--
-- Table structure for table `company_group_permissions`
--

DROP TABLE IF EXISTS `company_group_permissions`;
CREATE TABLE IF NOT EXISTS `company_group_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `endpoint_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `endpoint_id` (`endpoint_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `company_group_permissions`
--

INSERT INTO `company_group_permissions` (`id`, `group_id`, `endpoint_id`) VALUES
(1, 1, 1),
(2, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `company_web_domains`
--

DROP TABLE IF EXISTS `company_web_domains`;
CREATE TABLE IF NOT EXISTS `company_web_domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `company_web_domains`
--

INSERT INTO `company_web_domains` (`id`, `name`) VALUES
(1, 'DK'),
(2, 'JP'),
(3, 'COM');

-- --------------------------------------------------------

--
-- Table structure for table `restricted_endpoints`
--

DROP TABLE IF EXISTS `restricted_endpoints`;
CREATE TABLE IF NOT EXISTS `restricted_endpoints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `restricted_endpoints`
--

INSERT INTO `restricted_endpoints` (`id`, `name`) VALUES
(1, 'USER'),
(2, 'FINANCIAL'),
(3, 'INFO');

-- --------------------------------------------------------

--
-- Table structure for table `website_about`
--

DROP TABLE IF EXISTS `website_about`;
CREATE TABLE IF NOT EXISTS `website_about` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `web_domain` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `web_domain` (`web_domain`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `website_about`
--

INSERT INTO `website_about` (`id`, `description`, `web_domain`, `author`, `updated`) VALUES
(1, 'Virksomheden startede i 1998, hvor vi solgte rugbrød til private, Kent sagde man burde kunne lave rugbrød automatisk, så teamet gik i gang med at undersøge markedet for automatiske robotter men fandt hurtigt ud af der ikke var særlig mange på markedet, og dem der var solgte robotter til overpris. Christian forslog så om virksomheden ikke skulle begynde at fokusere på robotter i stedet for rugbrød, Kent var lidt uenig eftersom rugbrød var hans store passion. Tobias fik dog Kent overtalt til at ligge sin drøm på hylden.\r\n                <br><br>Teamet gik i gang med de første tegninger til en prototype. De fandt hurtigt ud af det ville være dyrere end forventet, så de begyndte at søge investore.\r\n                Efter den første investore kom på gik det stærkt. Den første udgave blev færdig og der var rigtig mange henvendelser, så mange at virksomheden ikke kunne følge med.\r\n                <br><br>Virksomheden havde svært med at følge med væksten i forhold til antal ansatte. Der blev ansat nye hver uge, for overhovedet at kunne svare på alle henvendelserne.\r\n                Som årene gik forblev dog virksomhedens vækst.\r\n                Virksomheden er nu kommet på verdenskortet med afdelinger i Canada og Japan.\r\n                    Hele IntelRobotics teamet er spændt på fremtiden og glæder sig til alle de nye udfordringer de støder på.', 1, 'TL', '2019-03-21 09:59:56'),
(2, 'みなさん、こんにちは', 2, 'kent', '2019-03-21 10:00:13');

-- --------------------------------------------------------

--
-- Table structure for table `website_news`
--

DROP TABLE IF EXISTS `website_news`;
CREATE TABLE IF NOT EXISTS `website_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `internal` int(11) NOT NULL DEFAULT 0,
  `web_domain` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `web_domain` (`web_domain`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `website_news`
--

INSERT INTO `website_news` (`id`, `title`, `description`, `internal`, `web_domain`, `author`, `updated`) VALUES
(1, 'Test', 'hej med jer', 0, 1, 'chvr', '2019-03-19 09:28:30'),
(2, 'Test2', 'akdokaodkadkk', 0, 1, 'chvr', '2019-03-20 11:26:52'),
(3, 'Mån', 'okokjiuijkoqqdqdqdwqwqetkko', 0, 1, 'chvr', '2019-03-21 09:04:17'),
(4, 'Kage Tirsdag', 'I ledelsen er vi kommet frem til vi skal prioritere medarbejderne mere og har derfor indført kage tirsdag. Håber i kan lide de fremtidige kager.', 0, 1, 'chvr', '2019-03-21 09:04:46'),
(5, 'Ny chef i HR-afdeling', 'Som i nok i allerede har hørt er der blevet ansat en ny chef i HR. Byd velkommmen til Michael, han kommer fordi og hilser på jer.', 0, 1, 'chvr', '2019-03-21 09:04:50');

-- --------------------------------------------------------

--
-- Table structure for table `website_products`
--

DROP TABLE IF EXISTS `website_products`;
CREATE TABLE IF NOT EXISTS `website_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `web_domain` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `web_domain` (`web_domain`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `website_products`
--

INSERT INTO `website_products` (`id`, `title`, `description`, `web_domain`, `author`, `updated`) VALUES
(1, 'Tandpasta robotten 4000', 'Robotten der kan tilføje tandpasta til gulvet', 1, 'chvr', '2019-03-19 09:46:11'),
(2, 'Robotten 1000', 'Robotten der kan tilføje gulerødder til sovsen', 1, 'chvr', '2019-03-21 12:47:46'),
(3, 'Robotten 2000', 'Robotten der kan bage pizzaer', 1, 'chvr', '2019-03-21 12:48:09'),
(4, 'Robotten 3000', 'Robotten der kan bage brød', 1, 'chvr', '2019-03-21 12:48:27'),
(5, 'Robotten 4500', 'Robotten der kan bage brød og pizza', 1, 'chvr', '2019-03-21 12:48:53'),
(6, 'Robotten 5000', 'Robotten der kan åbne et vindue', 1, 'chvr', '2019-03-21 12:49:25'),
(7, 'Robotten 6000', 'Robotten der kan åbne døren', 1, 'chvr', '2019-03-21 12:49:44'),
(8, 'Robotten 7000', 'Robotten der kan gå på toilettet for dig', 1, 'chvr', '2019-03-21 12:50:04'),
(9, 'Robotten 8000', 'Robotten kan tænde for tvet', 1, 'chvr', '2019-03-21 12:50:22'),
(10, 'Robotten 9000', 'Robotten der kan give dig massage', 1, 'chvr', '2019-03-21 12:50:43'),
(11, 'Robotten 10000', 'Robotten der kan gå selv', 1, 'chvr', '2019-03-21 12:51:05'),
(12, 'Robotten 20000', 'Robotten der kan selv', 1, 'chvr', '2019-03-21 12:51:20');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `company_group_permissions`
--
ALTER TABLE `company_group_permissions`
  ADD CONSTRAINT `company_group_id_company_group_permissions_group_id` FOREIGN KEY (`group_id`) REFERENCES `company_groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `restricted_endpoints_id_company_group_permissions_endpoint_id` FOREIGN KEY (`endpoint_id`) REFERENCES `restricted_endpoints` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `website_about`
--
ALTER TABLE `website_about`
  ADD CONSTRAINT `company_web_domains_website_about_web_domain` FOREIGN KEY (`web_domain`) REFERENCES `company_web_domains` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `website_news`
--
ALTER TABLE `website_news`
  ADD CONSTRAINT `company_web_domains_website_news_web_domain` FOREIGN KEY (`web_domain`) REFERENCES `company_web_domains` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `website_products`
--
ALTER TABLE `website_products`
  ADD CONSTRAINT `company_web_domains_website_products_web_domain` FOREIGN KEY (`web_domain`) REFERENCES `company_web_domains` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
