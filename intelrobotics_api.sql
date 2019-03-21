-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 21, 2019 at 08:50 AM
-- Server version: 10.1.37-MariaDB-0+deb9u1
-- PHP Version: 7.2.16-1+0~20190307202415.17+stretch~1.gbpa7be82

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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

CREATE TABLE `company_financials` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `upload_path` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `company_groups`
--

CREATE TABLE `company_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `company_group_permissions` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `endpoint_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

CREATE TABLE `company_web_domains` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

CREATE TABLE `restricted_endpoints` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

CREATE TABLE `website_about` (
  `id` int(11) NOT NULL,
  `description` text NOT NULL,
  `web_domain` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `website_about`
--

INSERT INTO `website_about` (`id`, `description`, `web_domain`, `author`, `updated`) VALUES
(1, 'Vi er et dejligt firma', 1, 'chvr', '2019-03-19 09:39:00'),
(2, 'みなさん、こんにちは', 2, 'ken', '2019-03-19 10:09:03');

-- --------------------------------------------------------

--
-- Table structure for table `website_news`
--

CREATE TABLE `website_news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `internal` int(11) NOT NULL DEFAULT '0',
  `web_domain` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `website_news`
--

INSERT INTO `website_news` (`id`, `title`, `description`, `internal`, `web_domain`, `author`, `updated`) VALUES
(1, 'Test', 'hej med jer', 0, 1, 'chvr', '2019-03-19 09:28:30'),
(2, 'Test2', 'akdokaodkadkk', 0, 1, 'chvr', '2019-03-20 11:26:52'),
(3, 'Test3', 'okokjiuijkoqqdqdqdwqwqetkko', 0, 1, 'chvr', '2019-03-20 11:27:14'),
(4, 'Test4', 'mnnmnmnnmnczmczbbvmbvv', 0, 1, 'chvr', '2019-03-20 11:27:26'),
(5, 'Test5', 'lkiijujujhujujujhujhhuj', 0, 1, 'chvr', '2019-03-20 11:27:50');

-- --------------------------------------------------------

--
-- Table structure for table `website_products`
--

CREATE TABLE `website_products` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `web_domain` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `website_products`
--

INSERT INTO `website_products` (`id`, `title`, `description`, `web_domain`, `author`, `updated`) VALUES
(1, 'Tandpasta robotten 4000', 'Robotten der kan tilføje tandpasta til gulvet', 1, 'chvr', '2019-03-19 09:46:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_financials`
--
ALTER TABLE `company_financials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_groups`
--
ALTER TABLE `company_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_group_permissions`
--
ALTER TABLE `company_group_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `endpoint_id` (`endpoint_id`);

--
-- Indexes for table `company_web_domains`
--
ALTER TABLE `company_web_domains`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restricted_endpoints`
--
ALTER TABLE `restricted_endpoints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `website_about`
--
ALTER TABLE `website_about`
  ADD PRIMARY KEY (`id`),
  ADD KEY `web_domain` (`web_domain`);

--
-- Indexes for table `website_news`
--
ALTER TABLE `website_news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `web_domain` (`web_domain`);

--
-- Indexes for table `website_products`
--
ALTER TABLE `website_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `web_domain` (`web_domain`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_financials`
--
ALTER TABLE `company_financials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `company_groups`
--
ALTER TABLE `company_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `company_group_permissions`
--
ALTER TABLE `company_group_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `company_web_domains`
--
ALTER TABLE `company_web_domains`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `restricted_endpoints`
--
ALTER TABLE `restricted_endpoints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `website_about`
--
ALTER TABLE `website_about`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `website_news`
--
ALTER TABLE `website_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `website_products`
--
ALTER TABLE `website_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
