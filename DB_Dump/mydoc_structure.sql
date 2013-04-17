# ************************************************************
# Sequel Pro SQL dump
# Version 4004
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 54.251.36.197 (MySQL 5.5.29)
# Database: mydoc
# Generation Time: 2013-03-10 11:45:29 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table a_role_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `a_role_permissions`;

CREATE TABLE `a_role_permissions` (
  `role_code` varchar(5) NOT NULL,
  `permission_code` varchar(40) NOT NULL,
  KEY `Index_1` (`role_code`),
  KEY `Index_2` (`permission_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table a_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `a_roles`;

CREATE TABLE `a_roles` (
  `code` varchar(5) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table accounts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `accounts`;

CREATE TABLE `accounts` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table admin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;

INSERT INTO `admin` (`id`, `username`, `password`, `reset_password_key`, `first_name`, `last_name`, `email`, `contact_no`, `last_ip`, `created`, `updated`, `active`, `role_code`)
VALUES
	(1,'admin','200820e3227815ed1756a6b531e7e0d2',NULL,'Sudhan','Raj','sudhan03raj@gmail.com','93855270','202.156.169.24','2011-11-30 08:40:33','2013-03-10 10:52:03',1,'super'),
	(2,'vas','d8578edf8458ce06fbc5bb76a58c5ca4',NULL,'Vasanth','Metupalle','metupalle@gmail.com','81232719','58.182.245.185','2013-01-12 20:38:20','2013-01-12 20:38:30',1,'super'),
	(3,'testadmin','0192023a7bbd73250516f069df18b500',NULL,'test_admin','don','test@admin.com','786780600','110.37.19.85','2013-03-06 22:56:05','2013-03-10 15:17:04',1,'super');

/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table diary_entry
# ------------------------------------------------------------

DROP TABLE IF EXISTS `diary_entry`;

CREATE TABLE `diary_entry` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `episode_id` int(11) NOT NULL,
  `acc_id` int(11) NOT NULL,
  `type` enum('patient','doctor','consult','request_transcript','transcript','admin') NOT NULL DEFAULT 'patient',
  `content` text NOT NULL,
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_active` int(1) NOT NULL DEFAULT '1',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table diary_entry_obj
# ------------------------------------------------------------

DROP TABLE IF EXISTS `diary_entry_obj`;

CREATE TABLE `diary_entry_obj` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL,
  `type` enum('audio','video','photo','text','transcript') NOT NULL DEFAULT 'video',
  `uri_prefix` varchar(255) NOT NULL DEFAULT '',
  `item` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `transcript` text NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table diary_episode
# ------------------------------------------------------------

DROP TABLE IF EXISTS `diary_episode`;

CREATE TABLE `diary_episode` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `acc_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table doctor_favourite
# ------------------------------------------------------------

DROP TABLE IF EXISTS `doctor_favourite`;

CREATE TABLE `doctor_favourite` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table doctor_message
# ------------------------------------------------------------

DROP TABLE IF EXISTS `doctor_message`;

CREATE TABLE `doctor_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `from_doc_id` int(11) NOT NULL,
  `to_doc_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `reply_for` int(11) NOT NULL,
  `created_time` int(11) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table doctor_peer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `doctor_peer`;

CREATE TABLE `doctor_peer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `doc_id` int(11) NOT NULL,
  `peer_doc_id` int(11) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table doctor_profile
# ------------------------------------------------------------

DROP TABLE IF EXISTS `doctor_profile`;

CREATE TABLE `doctor_profile` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table doctor_profile_extra
# ------------------------------------------------------------

DROP TABLE IF EXISTS `doctor_profile_extra`;

CREATE TABLE `doctor_profile_extra` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `doc_id` int(11) NOT NULL,
  `type` enum('statement','affiliation','award','certification','education','language','membership','speciality') NOT NULL DEFAULT 'statement',
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table doctor_review
# ------------------------------------------------------------

DROP TABLE IF EXISTS `doctor_review`;

CREATE TABLE `doctor_review` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `doc_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `rating` float(10,1) NOT NULL,
  `review` text NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT '1',
  `created_time` int(32) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table episode_slot_map
# ------------------------------------------------------------

DROP TABLE IF EXISTS `episode_slot_map`;

CREATE TABLE `episode_slot_map` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `episode_id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table message_entry
# ------------------------------------------------------------

DROP TABLE IF EXISTS `message_entry`;

CREATE TABLE `message_entry` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table message_entry_obj
# ------------------------------------------------------------

DROP TABLE IF EXISTS `message_entry_obj`;

CREATE TABLE `message_entry_obj` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL,
  `type` enum('audio','video','photo','text','transcript') NOT NULL DEFAULT 'video',
  `uri_prefix` varchar(255) NOT NULL DEFAULT '',
  `item` varchar(255) NOT NULL,
  `transcript` text NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table news
# ------------------------------------------------------------

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table patient_profile
# ------------------------------------------------------------

DROP TABLE IF EXISTS `patient_profile`;

CREATE TABLE `patient_profile` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table schedule
# ------------------------------------------------------------

DROP TABLE IF EXISTS `schedule`;

CREATE TABLE `schedule` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table schedule_slot
# ------------------------------------------------------------

DROP TABLE IF EXISTS `schedule_slot`;

CREATE TABLE `schedule_slot` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table transaction_history
# ------------------------------------------------------------

DROP TABLE IF EXISTS `transaction_history`;

CREATE TABLE `transaction_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `acc_id` int(11) NOT NULL,
  `amount` float(16,2) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT '',
  `from_acc_id` int(11) NOT NULL,
  `txn_time` int(32) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

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

# Dump of table devices
# ------------------------------------------------------------

DROP TABLE IF EXISTS `devices`;

CREATE TABLE `devices` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Dump of table device_patient
# ------------------------------------------------------------

DROP TABLE IF EXISTS `device_patient`;

CREATE TABLE `device_patient` (
  `device_ID` int(11) unsigned NOT NULL ,
  `patient_ID` int(11) unsigned NOT NULL ,
  PRIMARY KEY (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Dump of table device_patient
# ------------------------------------------------------------

DROP TABLE IF EXISTS `device_data`;

CREATE TABLE `device_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `patient_ID` int(11) unsigned NOT NULL ,
  `name` varchar(255) NOT NULL DEFAULT '',
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `value` float(10) NOT NULL, 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
