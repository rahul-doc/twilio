-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 10, 2013 at 06:18 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mydoc`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `salt` char(16) NOT NULL DEFAULT '',
  `avatar_url` varchar(255) NOT NULL DEFAULT 'https://s3-ap-southeast-1.amazonaws.com/mydoc-avatar/icon%40512.png',
  `balance` float(16,2) NOT NULL,
  `account_group` enum('doctor','patient','admin','superadmin') NOT NULL DEFAULT 'patient',
  `is_active` int(1) NOT NULL DEFAULT '0',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `email`, `password`, `salt`, `avatar_url`, `balance`, `account_group`, `is_active`, `last_updated`) VALUES
(1, 'sds', 'test@test.com', 'c4134a5fe29f025ad0d5ff28108d57e2', '87c527ca', '', 0.00, 'patient', 1, '2013-04-09 06:30:59'),
(2, 'test', 'test@test.com', '3766810e6efd70fa4570301ebd1c9def', '87a3a183', '', 0.00, 'doctor', 1, '2013-04-09 06:23:38');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `reset_password_key` varchar(32) DEFAULT NULL,
  `first_name` varchar(80) CHARACTER SET latin1 NOT NULL,
  `last_name` varchar(80) CHARACTER SET latin1 NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_no` varchar(60) CHARACTER SET latin1 NOT NULL,
  `last_ip` varchar(100) CHARACTER SET latin1 NOT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` int(1) unsigned NOT NULL DEFAULT '1',
  `role_code` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_username` (`username`),
  UNIQUE KEY `unique_email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `reset_password_key`, `first_name`, `last_name`, `email`, `contact_no`, `last_ip`, `created`, `updated`, `active`, `role_code`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', NULL, 'Sudhan', 'Raj', 'sudhan03raj@gmail.com', '93855270', '::1', '2011-11-30 03:10:57', '2013-04-09 05:33:39', 1, 'super'),
(2, 'vas', 'd8578edf8458ce06fbc5bb76a58c5ca4', NULL, 'Vasanth', 'Metupalle', 'metupalle@gmail.com', '81232719', '58.182.245.185', '2013-01-12 15:08:44', '2013-01-12 15:08:54', 1, 'super'),
(3, 'testadmin', '0192023a7bbd73250516f069df18b500', NULL, 'test_admin', 'don', 'test@admin.com', '786780600', '110.37.19.85', '2013-03-06 17:26:29', '2013-03-10 09:47:28', 1, 'super');

-- --------------------------------------------------------

--
-- Table structure for table `a_roles`
--

CREATE TABLE IF NOT EXISTS `a_roles` (
  `code` varchar(5) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `a_role_permissions`
--

CREATE TABLE IF NOT EXISTS `a_role_permissions` (
  `role_code` varchar(5) NOT NULL,
  `permission_code` varchar(40) NOT NULL,
  KEY `Index_1` (`role_code`),
  KEY `Index_2` (`permission_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE IF NOT EXISTS `devices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `maker` varchar(255) NOT NULL DEFAULT '',
  `distributer_email` varchar(100) NOT NULL DEFAULT '',
  `distributer_name` varchar(255) NOT NULL DEFAULT '',
  `distributer_address` varchar(255) NOT NULL DEFAULT '',
  `distributer_tel` varchar(100) NOT NULL DEFAULT '',
  `avatar_url` varchar(255) NOT NULL DEFAULT 'https://s3-ap-southeast-1.amazonaws.com/mydoc-avatar/icon%40512.png',
  `twonetID` varchar(255) NOT NULL DEFAULT '',
  `is_active` int(1) NOT NULL DEFAULT '0',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `device_data`
--

CREATE TABLE IF NOT EXISTS `device_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `patient_ID` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `value` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `device_patient`
--

CREATE TABLE IF NOT EXISTS `device_patient` (
  `device_ID` int(11) unsigned NOT NULL,
  `patient_ID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`device_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `diary_entry`
--

CREATE TABLE IF NOT EXISTS `diary_entry` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `episode_id` int(11) NOT NULL,
  `acc_id` int(11) NOT NULL,
  `type` enum('patient','doctor','consult','request_transcript','transcript','admin') NOT NULL DEFAULT 'patient',
  `content` text NOT NULL,
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_active` int(1) NOT NULL DEFAULT '1',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `diary_entry_obj`
--

CREATE TABLE IF NOT EXISTS `diary_entry_obj` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL,
  `type` enum('audio','video','photo','text','transcript') NOT NULL DEFAULT 'video',
  `uri_prefix` varchar(255) NOT NULL DEFAULT '',
  `item` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `transcript` text NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `diary_episode`
--

CREATE TABLE IF NOT EXISTS `diary_episode` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `acc_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_favourite`
--

CREATE TABLE IF NOT EXISTS `doctor_favourite` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_message`
--

CREATE TABLE IF NOT EXISTS `doctor_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `from_doc_id` int(11) NOT NULL,
  `to_doc_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `reply_for` int(11) NOT NULL,
  `created_time` int(11) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_peer`
--

CREATE TABLE IF NOT EXISTS `doctor_peer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `doc_id` int(11) NOT NULL,
  `peer_doc_id` int(11) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_profile`
--

CREATE TABLE IF NOT EXISTS `doctor_profile` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `acc_id` int(11) NOT NULL,
  `contact` varchar(100) NOT NULL DEFAULT '',
  `address1` text NOT NULL,
  `address2` text NOT NULL,
  `address3` text NOT NULL,
  `city` varchar(50) NOT NULL DEFAULT '',
  `state` varchar(50) NOT NULL DEFAULT '',
  `lat` varchar(25) NOT NULL,
  `lng` varchar(25) NOT NULL,
  `rating` float(16,2) NOT NULL DEFAULT '0.00',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `doctor_profile`
--

INSERT INTO `doctor_profile` (`id`, `acc_id`, `contact`, `address1`, `address2`, `address3`, `city`, `state`, `lat`, `lng`, `rating`, `last_updated`) VALUES
(1, 2, 'ddhdhg', 'ddd', 'dd', 'dd', 'dd', 'dd', '0', '0', 0.00, '2013-04-09 06:23:38');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_profile_extra`
--

CREATE TABLE IF NOT EXISTS `doctor_profile_extra` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `doc_id` int(11) NOT NULL,
  `type` enum('statement','affiliation','award','certification','education','language','membership','speciality') NOT NULL DEFAULT 'statement',
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_review`
--

CREATE TABLE IF NOT EXISTS `doctor_review` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `doc_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `rating` float(10,1) NOT NULL,
  `review` text NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(32) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `episode_slot_map`
--

CREATE TABLE IF NOT EXISTS `episode_slot_map` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `episode_id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT '1',
  `description` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `admin`, `name`, `status`, `description`, `date_added`) VALUES
(4, 1, 'hhh', '1', 'jj', '2013-04-09 14:30:55');

-- --------------------------------------------------------

--
-- Table structure for table `group_doctors`
--

CREATE TABLE IF NOT EXISTS `group_doctors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `keys`
--

CREATE TABLE IF NOT EXISTS `keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `is_private_key` tinyint(1) NOT NULL DEFAULT '0',
  `ip_addresses` text,
  `date_created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `keys`
--

INSERT INTO `keys` (`id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`) VALUES
(1, 'keyAPI1234%', 1, 0, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `message_entry`
--

CREATE TABLE IF NOT EXISTS `message_entry` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `to_doc_id` int(11) NOT NULL,
  `from_doc_id` int(11) NOT NULL,
  `type` enum('message','referral') NOT NULL DEFAULT 'message',
  `referral_entry_id` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_active` int(1) NOT NULL DEFAULT '1',
  `is_read` int(1) NOT NULL DEFAULT '0',
  `read_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `message_entry_obj`
--

CREATE TABLE IF NOT EXISTS `message_entry_obj` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL,
  `type` enum('audio','video','photo','text','transcript') NOT NULL DEFAULT 'video',
  `uri_prefix` varchar(255) NOT NULL DEFAULT '',
  `item` varchar(255) NOT NULL,
  `transcript` text NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `thumb_url` varchar(255) NOT NULL DEFAULT '',
  `image_url` varchar(255) NOT NULL DEFAULT '',
  `list_start_date` date NOT NULL,
  `list_end_date` date NOT NULL,
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  `is_event` int(1) NOT NULL DEFAULT '0',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `description`, `thumb_url`, `image_url`, `list_start_date`, `list_end_date`, `start_date`, `end_date`, `is_event`, `last_updated`) VALUES
(1, 'Dddd', '<p>jjjj</p>', 'accenture.jpg', '', '2013-04-09', '2013-01-01', '1970-01-01', '1970-01-01', 0, '2013-04-09 05:40:03');

-- --------------------------------------------------------

--
-- Table structure for table `news_email`
--

CREATE TABLE IF NOT EXISTS `news_email` (
  `news_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  PRIMARY KEY (`news_id`,`profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `news_notification_setting`
--

CREATE TABLE IF NOT EXISTS `news_notification_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news_id` int(11) NOT NULL,
  `app` int(1) NOT NULL DEFAULT '0',
  `email` int(1) NOT NULL DEFAULT '0',
  `sms` int(1) NOT NULL DEFAULT '0',
  `phone` int(1) NOT NULL DEFAULT '0',
  `onehour_before` varchar(255) NOT NULL DEFAULT '0',
  `oneday_before` varchar(255) NOT NULL DEFAULT '0',
  `days_before` varchar(255) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `noti_id` int(11) NOT NULL AUTO_INCREMENT,
  `datec` datetime NOT NULL,
  `type` varchar(100) NOT NULL,
  `noti_type` varchar(100) NOT NULL,
  `noti_ref` int(11) NOT NULL,
  `noti_msg` varchar(200) NOT NULL,
  PRIMARY KEY (`noti_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `patient_profile`
--

CREATE TABLE IF NOT EXISTS `patient_profile` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `acc_id` int(11) NOT NULL,
  `contact` varchar(100) NOT NULL DEFAULT '',
  `dob` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gender` varchar(10) NOT NULL DEFAULT '',
  `allergy` text NOT NULL,
  `past_history` text NOT NULL,
  `insurance` varchar(250) NOT NULL,
  `corporate_plans` varchar(500) NOT NULL,
  `discount_schemes` varchar(500) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `patient_profile`
--

INSERT INTO `patient_profile` (`id`, `acc_id`, `contact`, `dob`, `gender`, `allergy`, `past_history`, `insurance`, `corporate_plans`, `discount_schemes`, `last_updated`) VALUES
(1, 1, '+919820397227', '2000-01-01 00:00:00', 'Male', 'xx', 'xxx', '', '', '', '2013-04-09 14:03:38');

-- --------------------------------------------------------

--
-- Table structure for table `price_list`
--

CREATE TABLE IF NOT EXISTS `price_list` (
  `price_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `product_type` enum('consultation','device','medicine','procedure','service','other') COLLATE utf8_unicode_ci NOT NULL,
  `product_type_other` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acc_id` int(11) NOT NULL,
  `provider` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `unit_price` float NOT NULL,
  `currency` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `recurring_monthly` tinyint(1) NOT NULL DEFAULT '0',
  `date_added` date NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`price_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE IF NOT EXISTS `schedule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `doc_id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT '',
  `rate` float(10,2) DEFAULT NULL,
  `rate_clinic` float(10,2) DEFAULT NULL,
  `day` date NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `comment` varchar(500) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT '1',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_slot`
--

CREATE TABLE IF NOT EXISTS `schedule_slot` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sch_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `slot` int(11) NOT NULL,
  `consult_start` time NOT NULL,
  `consult_end` time NOT NULL,
  `type` enum('clinic','non-clinic') NOT NULL DEFAULT 'non-clinic',
  `status` enum('pending','confirmed','rejected') NOT NULL DEFAULT 'pending',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_history`
--

CREATE TABLE IF NOT EXISTS `transaction_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `acc_id` int(11) NOT NULL,
  `amount` float(16,2) NOT NULL,
  `commission` float(16,2) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT '',
  `from_acc_id` int(11) NOT NULL,
  `txn_time` int(32) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('On-Hold','Pending','Completed','Refund') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
