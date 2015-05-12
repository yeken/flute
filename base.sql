-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 12, 2014 at 05:18 PM
-- Server version: 5.5.22
-- PHP Version: 5.4.31-1+deb.sury.org~precise+1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `catarain`
--

-- --------------------------------------------------------

--
-- Table structure for table `bitauth_groups`
--

CREATE TABLE IF NOT EXISTS `bitauth_groups` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(48) CHARACTER SET latin1 NOT NULL,
  `description` text CHARACTER SET latin1 NOT NULL,
  `roles` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `bitauth_groups`
--

INSERT INTO `bitauth_groups` (`group_id`, `name`, `description`, `roles`) VALUES
(1, 'Administrador', 'Administadores con accesso completo a todo el sitio', '1'),
(2, 'Manager', '', '0'),
(3, 'Jugador', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `bitauth_logins`
--

CREATE TABLE IF NOT EXISTS `bitauth_logins` (
  `login_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` bigint(20) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `time` datetime NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`login_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `bitauth_logins`
--

INSERT INTO `bitauth_logins` (`login_id`, `ip_address`, `user_id`, `time`, `success`) VALUES
(1, 3232235881, 3, '2014-09-09 17:02:08', 1),
(2, 3232235881, 3, '2014-09-10 16:34:09', 1),
(3, 3232235881, 3, '2014-09-11 10:46:51', 1);

-- --------------------------------------------------------

--
-- Table structure for table `bitauth_userdata`
--

CREATE TABLE IF NOT EXISTS `bitauth_userdata` (
  `userdata_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`userdata_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `bitauth_userdata`
--

INSERT INTO `bitauth_userdata` (`userdata_id`, `user_id`) VALUES
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `bitauth_users`
--

CREATE TABLE IF NOT EXISTS `bitauth_users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `displayname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(60) NOT NULL,
  `password_last_set` datetime NOT NULL,
  `password_never_expires` tinyint(1) NOT NULL DEFAULT '0',
  `remember_me` varchar(40) NOT NULL,
  `activation_code` varchar(40) NOT NULL,
  `groups_names` varchar(255) NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `forgot_code` varchar(40) NOT NULL,
  `forgot_generated` datetime NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `file_manager_id` int(11) NOT NULL,
  `last_login` datetime NOT NULL,
  `last_login_ip` int(10) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `group_id` (`group_id`),
  KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21826 ;

--
-- Dumping data for table `bitauth_users`
--

INSERT INTO `bitauth_users` (`user_id`, `username`, `fullname`, `displayname`, `email`, `password`, `password_last_set`, `password_never_expires`, `remember_me`, `activation_code`, `groups_names`, `group_id`, `active`, `forgot_code`, `forgot_generated`, `enabled`, `file_manager_id`, `last_login`, `last_login_ip`, `date_created`) VALUES
(21822, 'bla', 'bla blas', '', 'admin@bla.com', '$2a$08$LfEZbVVeor1fIw79d5GEO.DVNWT6KZUJsYHkDIudtDX0iI0YPs8s2', '2014-08-29 12:34:33', 0, '8812207e38cccfed486024394288d8a22b66a632', '', 'Jugador', 3, 1, '', '0000-00-00 00:00:00', 1, 21, '2014-08-29 12:34:58', 2147483647, '2014-08-20 18:06:35'),
(21823, 'admin3232', '32132132', '', 'j@a.com', '$2a$08$jJelYpqj2/qPWkah4.QFQOWYwNxJrndapu6XrUFkSBYrR9CLDJbk.', '2014-08-20 18:21:46', 0, '', '', 'Jugador', 3, 1, '', '0000-00-00 00:00:00', 1, 22, '0000-00-00 00:00:00', 0, '2014-08-20 18:21:46'),
(3, 'admin', 'Admin tester', '', 'admin@admin.com', '$2a$08$TWJfCg7btiKu4bU4JurF9uJh5Byb7ycRMTZii5vqw93JbJFrT.wEq', '2013-03-15 16:12:39', 0, '0db65fde13455c53a3a5d9a2c779ca2e786a89d3', '', 'Administrador', 1, 1, '', '2013-03-15 16:04:54', 1, 23, '2014-09-11 10:46:51', 2147483647, '2012-09-28 16:27:01'),
(21824, 'bla', 'Blas parera', '', 'bla2@gmail.com', '$2a$08$bDfP.YHM8GiAdb0CsjXwH.Z41rja57p6r/dZyE6mmHT0mwOXXjBse', '2014-08-29 12:40:08', 0, '', '', 'Manager', 2, 1, '', '0000-00-00 00:00:00', 1, 24, '2014-08-29 12:40:28', 2147483647, '2014-08-29 12:40:08'),
(21825, 'bla', 'fdsfd', '', 'bla@bla.com', '$2a$08$MqhbrxvxBsfmga5f//Xt1uZI08GxZ60R4ZrOB/PmR70W0Kum0cKl.', '2014-08-29 13:30:28', 0, '', '', 'Manager', 2, 1, '', '0000-00-00 00:00:00', 1, 25, '0000-00-00 00:00:00', 0, '2014-08-29 13:30:28');

-- --------------------------------------------------------

--
-- Table structure for table `configs_users`
--

CREATE TABLE IF NOT EXISTS `configs_users` (
  `configuration_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `configurations`
--

CREATE TABLE IF NOT EXISTS `configurations` (
  `configuration_id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(250) NOT NULL,
  `telephone` varchar(250) NOT NULL,
  `text_footer` varchar(250) NOT NULL,
  `url_facebook` varchar(250) NOT NULL,
  `url_twitter` varchar(250) NOT NULL,
  `url_googleplus` varchar(250) NOT NULL,
  `url_youtube` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `form_emails` text NOT NULL COMMENT 'separados por coma. (ej: juan@gmail.com, florencia@yahoo.com)',
  `username` varchar(255) NOT NULL,
  PRIMARY KEY (`configuration_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `configurations`
--

INSERT INTO `configurations` (`configuration_id`, `address`, `telephone`, `text_footer`, `url_facebook`, `url_twitter`, `url_googleplus`, `url_youtube`, `email`, `form_emails`, `username`) VALUES
(1, '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `file_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `type` enum('image','video','archive') NOT NULL,
  `code` varchar(255) NOT NULL,
  `ext` varchar(10) NOT NULL,
  `group` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`file_id`, `file`, `file_name`, `type`, `code`, `ext`, `group`, `date_created`) VALUES
(1, '1_1.jpg', '1', 'image', '', 'jpg', '', '2014-09-10 16:34:40'),
(2, '2_4.jpg', '4', 'image', '', 'jpg', '', '2014-09-10 16:34:57'),
(3, '3_4.jpg', '4', 'image', '', 'jpg', '', '2014-09-10 16:35:02'),
(4, '4_7.jpg', '7', 'image', '', 'jpg', '', '2014-09-10 16:35:29'),
(5, '5_2.jpg', '2', 'image', '', 'jpg', '', '2014-09-11 11:18:16'),
(6, '6_4.jpg', '4', 'image', '', 'jpg', '', '2014-09-11 11:18:32'),
(7, '7_2.jpg', '2', 'image', '', 'jpg', '', '2014-09-11 12:37:09'),
(8, '8_4.jpg', '4', 'image', '', 'jpg', '', '2014-09-11 12:37:13'),
(9, '9_7.jpg', '7', 'image', '', 'jpg', '', '2014-09-11 12:37:17');

-- --------------------------------------------------------

--
-- Table structure for table `file_managers`
--

CREATE TABLE IF NOT EXISTS `file_managers` (
  `file_manager_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_manager` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`file_manager_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `file_managers`
--

INSERT INTO `file_managers` (`file_manager_id`, `file_manager`, `date_created`) VALUES
(1, '', '2014-09-10 16:34:40'),
(2, '', '2014-09-10 16:34:57'),
(3, '', '2014-09-10 16:35:02'),
(4, '', '2014-09-10 16:35:29'),
(5, '', '2014-09-11 11:18:16'),
(6, '', '2014-09-11 11:18:32');

-- --------------------------------------------------------

--
-- Table structure for table `file_managers_files`
--

CREATE TABLE IF NOT EXISTS `file_managers_files` (
  `file_manager_id` int(11) unsigned NOT NULL,
  `file_id` int(11) unsigned NOT NULL,
  `tag` varchar(255) NOT NULL,
  `order` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`file_manager_id`,`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `file_managers_files`
--

INSERT INTO `file_managers_files` (`file_manager_id`, `file_id`, `tag`, `order`) VALUES
(1, 1, 'main_image', 0),
(2, 2, 'main_image', 0),
(3, 3, 'main_image', 0),
(4, 4, 'main_image', 0),
(5, 5, 'main_image', 0),
(6, 6, 'main_image', 0),
(1, 7, 'image_gallery', 0),
(1, 8, 'image_gallery', 0),
(1, 9, 'image_gallery', 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `brief` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `file_manager_id` int(11) unsigned NOT NULL,
  `active` tinyint(1) unsigned NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`product_id`),
  KEY `category` (`category`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `brief`, `description`, `category`, `file_manager_id`, `active`, `date_created`) VALUES
(1, 'silvina luna', '', 'Acriz y modelo', 'Actriz', 1, 1, '2014-09-10 16:34:40'),
(2, 'pedro lopez', '', 'actor y conductor', 'Actor', 2, 1, '2014-09-10 16:34:57'),
(3, 'pedro lopez', '', 'actor y conductor', 'Actor', 3, 1, '2014-09-10 16:35:02'),
(4, 'fabian beltran', '', 'actor y conductor', 'Actor', 4, 1, '2014-09-10 16:35:29'),
(5, 'producción 1', '', 'fuimos a filmar a bariloche', 'Produccion', 5, 1, '2014-09-11 11:18:16'),
(6, 'producción 2', '', 'en la montania', 'Produccion', 6, 1, '2014-09-11 11:18:32');

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE IF NOT EXISTS `provinces` (
  `province_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `province_order` tinyint(2) NOT NULL,
  PRIMARY KEY (`province_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`province_id`, `name`, `province_order`) VALUES
(1, 'Buenos Aires', 1),
(2, 'Catamarca', 2),
(3, 'Chaco', 3),
(4, 'Chubut', 4),
(5, 'Cordoba', 5),
(6, 'Corrientes', 6),
(8, 'Entre Rios', 8),
(9, 'Formosa', 9),
(14, 'Jujuy', 14),
(15, 'La Pampa', 15),
(16, 'La Rioja', 16),
(17, 'Mendoza', 17),
(18, 'Misiones', 18),
(19, 'Neuquén', 19),
(20, 'Río Negro', 20),
(21, 'Salta', 21),
(22, 'San Luis', 22),
(23, 'Santa Cruz', 23),
(24, 'Santiago Del Estero', 24),
(25, 'Tierra Del Fuego', 25),
(26, 'Tucumán', 26),
(27, 'San Juan', 27),
(28, 'Santa Fe', 28),
(29, 'Capital Federal', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
