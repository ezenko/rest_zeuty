-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 22, 2010 at 03:41 PM
-- Server version: 5.1.40
-- PHP Version: 5.2.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `realestate`
--

-- --------------------------------------------------------

--
-- Table structure for table `re_account_deactivated`
--

DROP TABLE IF EXISTS `re_account_deactivated`;
CREATE TABLE IF NOT EXISTS `re_account_deactivated` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_deactive` int(11) NOT NULL DEFAULT '0',
  `comments` text,
  `deactivated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_account_deactivated`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_active_sessions`
--

DROP TABLE IF EXISTS `re_active_sessions`;
CREATE TABLE IF NOT EXISTS `re_active_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session` varchar(32) NOT NULL DEFAULT '0',
  `ip_address` varchar(15) NOT NULL DEFAULT '0',
  `id_user` int(11) DEFAULT '0',
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `session` (`session`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101 ;

--
-- Dumping data for table `re_active_sessions`
--

INSERT INTO `re_active_sessions` (`id`, `session`, `ip_address`, `id_user`, `update_date`, `file`) VALUES
(100, '522b07b68aac57821498fc960e5916fe', '127.0.0.1', 2, '2010-12-22 15:33:56', '/index.php');

-- --------------------------------------------------------

--
-- Table structure for table `re_agent_of_company`
--

DROP TABLE IF EXISTS `re_agent_of_company`;
CREATE TABLE IF NOT EXISTS `re_agent_of_company` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_agent` int(11) unsigned NOT NULL DEFAULT '0',
  `id_company` int(11) unsigned NOT NULL DEFAULT '0',
  `approve` enum('0','1') NOT NULL DEFAULT '0',
  `inviter` enum('company','agent') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_agent_of_company`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_billing_adding_by_admin`
--

DROP TABLE IF EXISTS `re_billing_adding_by_admin`;
CREATE TABLE IF NOT EXISTS `re_billing_adding_by_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `count_curr` double DEFAULT NULL,
  `currency` varchar(5) NOT NULL DEFAULT '',
  `date_send` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_billing_adding_by_admin`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_billing_send_requests`
--

DROP TABLE IF EXISTS `re_billing_send_requests`;
CREATE TABLE IF NOT EXISTS `re_billing_send_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `count_units` double DEFAULT NULL,
  `count_curr` double DEFAULT NULL,
  `currency` varchar(5) NOT NULL DEFAULT '',
  `id_group` int(3) DEFAULT NULL,
  `id_product` int(3) DEFAULT '0',
  `id_service` int(3) DEFAULT '0',
  `date_send` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('send','approve','fail') DEFAULT 'send',
  `paysystem` varchar(255) NOT NULL DEFAULT '',
  `user_info` text,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_group` (`id_group`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `re_billing_send_requests`
--

INSERT INTO `re_billing_send_requests` (`id`, `id_user`, `count_units`, `count_curr`, `currency`, `id_group`, `id_product`, `id_service`, `date_send`, `status`, `paysystem`, `user_info`) VALUES
(1, 86, NULL, 1000, 'USD', NULL, 0, 0, '2008-05-18 13:13:50', 'approve', 'paypal', NULL),
(3, 101, NULL, 800, 'USD', NULL, 0, 0, '2008-05-18 14:35:19', 'approve', 'authorizenet', NULL),
(5, 105, NULL, 330, 'USD', NULL, 0, 0, '2008-05-18 15:27:22', 'send', 'chronopay', NULL),
(6, 104, NULL, 270, 'USD', NULL, 0, 0, '2008-05-18 15:28:14', 'send', 'paysat', NULL),
(7, 103, NULL, 650, 'USD', NULL, 0, 0, '2008-05-18 15:29:27', 'send', 'egold', NULL),
(8, 86, NULL, 5, 'USD', NULL, 0, 0, '2008-05-19 12:56:03', 'send', 'paypal', NULL),
(11, 86, NULL, 5, 'USD', NULL, 0, 0, '2008-05-29 16:40:37', 'send', '2checkout', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_spended`
--

DROP TABLE IF EXISTS `re_billing_spended`;
CREATE TABLE IF NOT EXISTS `re_billing_spended` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `count_curr` double DEFAULT NULL,
  `currency` varchar(5) NOT NULL DEFAULT '',
  `id_service` int(3) DEFAULT '0',
  `date_send` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `re_billing_spended`
--

INSERT INTO `re_billing_spended` (`id`, `id_user`, `count_curr`, `currency`, `id_service`, `date_send`) VALUES
(1, 86, 14, 'USD', 4, '2008-05-18 14:00:50'),
(2, 86, 1, 'USD', 1, '2008-05-18 14:01:03'),
(3, 86, 1, 'USD', 2, '2008-05-18 14:01:14'),
(4, 86, 10, 'USD', 3, '2008-05-18 14:01:43'),
(5, 101, 14, 'USD', 4, '2008-05-18 17:06:19'),
(6, 101, 1, 'USD', 1, '2008-05-18 17:06:30'),
(7, 86, 1, 'USD', 2, '2008-05-21 15:54:06'),
(8, 86, 14, 'USD', 4, '2008-05-28 09:26:11'),
(9, 101, 14, 'USD', 4, '2008-05-29 10:43:58'),
(10, 86, 8, 'USD', 4, '2008-05-29 16:39:50'),
(11, 86, 1, 'USD', 1, '2008-06-01 16:35:36'),
(12, 86, 1, 'USD', 1, '2008-06-01 16:35:39'),
(13, 86, 1, 'USD', 1, '2008-06-01 16:35:43'),
(14, 86, 1, 'USD', 1, '2008-06-04 17:14:19'),
(15, 86, 1, 'USD', 1, '2008-06-05 09:46:28'),
(16, 86, 1, 'USD', 1, '2008-06-05 09:46:42'),
(17, 86, 1, 'USD', 4, '2008-06-05 09:48:28'),
(18, 86, 8, 'USD', 4, '2008-06-05 09:48:32');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_systems`
--

DROP TABLE IF EXISTS `re_billing_systems`;
CREATE TABLE IF NOT EXISTS `re_billing_systems` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `used` enum('0','1') NOT NULL DEFAULT '1',
  `template_name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `re_billing_systems`
--

INSERT INTO `re_billing_systems` (`id`, `name`, `used`, `template_name`) VALUES
(1, '2checkout', '0', '2CheckOut'),
(2, 'paypal', '1', 'PayPal'),
(3, 'ccbill', '0', 'CCBill'),
(4, 'chronopay', '1', 'ChronoPay'),
(5, 'authorizenet', '0', 'authorize.net'),
(6, 'egold', '1', 'E-gold'),
(7, 'usaepay', '0', 'USAepay'),
(8, 'worldpay', '0', 'WorldPay'),
(9, 'paysat', '1', 'PaySat'),
(10, 'esec', '0', 'E-sec'),
(11, 'ipos', '0', 'iPos'),
(12, 'webmoney', '0', 'webmoney'),
(13, 'smscoin', '0', 'smscoin'),
(14, 'manual', '0', 'Manual payment');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_sys_2checkout`
--

DROP TABLE IF EXISTS `re_billing_sys_2checkout`;
CREATE TABLE IF NOT EXISTS `re_billing_sys_2checkout` (
  `name` varchar(255) DEFAULT NULL,
  `seller_id` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_billing_sys_2checkout`
--

INSERT INTO `re_billing_sys_2checkout` (`name`, `seller_id`) VALUES
('2checkout', '2checkout@mail.com');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_sys_authorizenet`
--

DROP TABLE IF EXISTS `re_billing_sys_authorizenet`;
CREATE TABLE IF NOT EXISTS `re_billing_sys_authorizenet` (
  `name` varchar(255) DEFAULT NULL,
  `seller_id` varchar(255) NOT NULL DEFAULT '',
  `trans_key` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_billing_sys_authorizenet`
--

INSERT INTO `re_billing_sys_authorizenet` (`name`, `seller_id`, `trans_key`) VALUES
('authorizenet', 'asd', 'asd');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_sys_ccbill`
--

DROP TABLE IF EXISTS `re_billing_sys_ccbill`;
CREATE TABLE IF NOT EXISTS `re_billing_sys_ccbill` (
  `name` varchar(255) DEFAULT NULL,
  `seller_id` varchar(255) DEFAULT NULL,
  `seller_sub_id` varchar(255) DEFAULT NULL,
  `form_name` varchar(255) DEFAULT NULL,
  `lang` varchar(255) DEFAULT 'English',
  `allowed_types` varchar(255) DEFAULT NULL,
  `subscription_type_id` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_billing_sys_ccbill`
--

INSERT INTO `re_billing_sys_ccbill` (`name`, `seller_id`, `seller_sub_id`, `form_name`, `lang`, `allowed_types`, `subscription_type_id`) VALUES
('ccbill', '', '', '', 'English', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_sys_chronopay`
--

DROP TABLE IF EXISTS `re_billing_sys_chronopay`;
CREATE TABLE IF NOT EXISTS `re_billing_sys_chronopay` (
  `name` varchar(255) DEFAULT NULL,
  `seller_id` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_billing_sys_chronopay`
--

INSERT INTO `re_billing_sys_chronopay` (`name`, `seller_id`) VALUES
('chronopay', '111111-1111-2222');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_sys_egold`
--

DROP TABLE IF EXISTS `re_billing_sys_egold`;
CREATE TABLE IF NOT EXISTS `re_billing_sys_egold` (
  `name` varchar(255) DEFAULT NULL,
  `seller_id` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_billing_sys_egold`
--

INSERT INTO `re_billing_sys_egold` (`name`, `seller_id`) VALUES
('egold', '1111111111111');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_sys_esec`
--

DROP TABLE IF EXISTS `re_billing_sys_esec`;
CREATE TABLE IF NOT EXISTS `re_billing_sys_esec` (
  `name` varchar(255) DEFAULT NULL,
  `seller_id` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_billing_sys_esec`
--

INSERT INTO `re_billing_sys_esec` (`name`, `seller_id`) VALUES
('esec', '');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_sys_ipos`
--

DROP TABLE IF EXISTS `re_billing_sys_ipos`;
CREATE TABLE IF NOT EXISTS `re_billing_sys_ipos` (
  `name` varchar(255) DEFAULT NULL,
  `seller_id` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_billing_sys_ipos`
--

INSERT INTO `re_billing_sys_ipos` (`name`, `seller_id`) VALUES
('ipos', '');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_sys_manual`
--

DROP TABLE IF EXISTS `re_billing_sys_manual`;
CREATE TABLE IF NOT EXISTS `re_billing_sys_manual` (
  `name` varchar(255) DEFAULT NULL,
  `information` blob NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_billing_sys_manual`
--

INSERT INTO `re_billing_sys_manual` (`name`, `information`) VALUES
('manual', '');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_sys_paypal`
--

DROP TABLE IF EXISTS `re_billing_sys_paypal`;
CREATE TABLE IF NOT EXISTS `re_billing_sys_paypal` (
  `name` varchar(255) DEFAULT NULL,
  `seller_id` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_billing_sys_paypal`
--

INSERT INTO `re_billing_sys_paypal` (`name`, `seller_id`) VALUES
('paypal', '1337@DecodeThe.Net');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_sys_paysat`
--

DROP TABLE IF EXISTS `re_billing_sys_paysat`;
CREATE TABLE IF NOT EXISTS `re_billing_sys_paysat` (
  `name` varchar(255) DEFAULT NULL,
  `seller_id` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_billing_sys_paysat`
--

INSERT INTO `re_billing_sys_paysat` (`name`, `seller_id`) VALUES
('paysat', '1111_1111_paysat');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_sys_smscoin`
--

DROP TABLE IF EXISTS `re_billing_sys_smscoin`;
CREATE TABLE IF NOT EXISTS `re_billing_sys_smscoin` (
  `name` varchar(255) DEFAULT NULL,
  `purse_id` varchar(255) NOT NULL DEFAULT '',
  `secret_code` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_billing_sys_smscoin`
--

INSERT INTO `re_billing_sys_smscoin` (`name`, `purse_id`, `secret_code`) VALUES
('smscoin', '2121', '101184');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_sys_smscoin_operator`
--

DROP TABLE IF EXISTS `re_billing_sys_smscoin_operator`;
CREATE TABLE IF NOT EXISTS `re_billing_sys_smscoin_operator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `re_billing_sys_smscoin_operator`
--

INSERT INTO `re_billing_sys_smscoin_operator` (`id`, `name`) VALUES
(1, 'russia');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_sys_smscoin_tarif`
--

DROP TABLE IF EXISTS `re_billing_sys_smscoin_tarif`;
CREATE TABLE IF NOT EXISTS `re_billing_sys_smscoin_tarif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operator_id` int(11) NOT NULL DEFAULT '0',
  `amount` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `re_billing_sys_smscoin_tarif`
--

INSERT INTO `re_billing_sys_smscoin_tarif` (`id`, `operator_id`, `amount`) VALUES
(1, 1, 0.9),
(2, 1, 1.3),
(3, 1, 2),
(4, 1, 3),
(5, 1, 4.75);

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_sys_usaepay`
--

DROP TABLE IF EXISTS `re_billing_sys_usaepay`;
CREATE TABLE IF NOT EXISTS `re_billing_sys_usaepay` (
  `name` varchar(255) DEFAULT NULL,
  `seller_id` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_billing_sys_usaepay`
--

INSERT INTO `re_billing_sys_usaepay` (`name`, `seller_id`) VALUES
('usaepay', '11111111111111');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_sys_webmoney`
--

DROP TABLE IF EXISTS `re_billing_sys_webmoney`;
CREATE TABLE IF NOT EXISTS `re_billing_sys_webmoney` (
  `name` varchar(255) DEFAULT NULL,
  `seller_id` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_billing_sys_webmoney`
--

INSERT INTO `re_billing_sys_webmoney` (`name`, `seller_id`) VALUES
('webmoney', '');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_sys_worldpay`
--

DROP TABLE IF EXISTS `re_billing_sys_worldpay`;
CREATE TABLE IF NOT EXISTS `re_billing_sys_worldpay` (
  `name` varchar(255) DEFAULT NULL,
  `seller_id` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_billing_sys_worldpay`
--

INSERT INTO `re_billing_sys_worldpay` (`name`, `seller_id`) VALUES
('worldpay', '1111111111111111111');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_unit`
--

DROP TABLE IF EXISTS `re_billing_unit`;
CREATE TABLE IF NOT EXISTS `re_billing_unit` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `abbr` varchar(10) NOT NULL DEFAULT '',
  `name` varchar(100) DEFAULT NULL,
  `fractional_unit` tinyint(4) DEFAULT NULL,
  `symbol` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `re_billing_unit`
--

INSERT INTO `re_billing_unit` (`id`, `abbr`, `name`, `fractional_unit`, `symbol`) VALUES
(1, 'USD', 'American Dollar', 2, '$'),
(2, 'EUR', 'Euro', 2, '&euro;'),
(3, 'GBP', 'Pound Sterling', 2, '&pound;'),
(4, 'CAD', 'Canadian Dollar', 2, 'cad'),
(5, 'JPY', 'Yen', 0, '&yen;'),
(6, 'AUD', 'Australian Dollar', 2, 'aud'),
(7, 'RUR', 'Russian Rouble', 0, 'rur');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_user_account`
--

DROP TABLE IF EXISTS `re_billing_user_account`;
CREATE TABLE IF NOT EXISTS `re_billing_user_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `account` double NOT NULL DEFAULT '0',
  `account_curr` double NOT NULL DEFAULT '0',
  `date_refresh` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_send` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `re_billing_user_account`
--

INSERT INTO `re_billing_user_account` (`id`, `id_user`, `account`, `account_curr`, `date_refresh`, `is_send`) VALUES
(1, 86, 936, 0, '2008-06-06 13:43:30', '0'),
(2, 101, 771, 0, '2008-06-05 11:14:09', '0');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_user_period`
--

DROP TABLE IF EXISTS `re_billing_user_period`;
CREATE TABLE IF NOT EXISTS `re_billing_user_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_group_period` int(3) NOT NULL DEFAULT '0',
  `date_begin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_group_period` (`id_group_period`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `re_billing_user_period`
--

INSERT INTO `re_billing_user_period` (`id`, `id_user`, `id_group_period`, `date_begin`, `date_end`) VALUES
(3, 86, 0, '2008-05-28 09:26:11', '2008-06-13 09:26:11');

-- --------------------------------------------------------

--
-- Table structure for table `re_billing_user_recipients`
--

DROP TABLE IF EXISTS `re_billing_user_recipients`;
CREATE TABLE IF NOT EXISTS `re_billing_user_recipients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_from_user` int(11) NOT NULL DEFAULT '0',
  `count_curr` double DEFAULT NULL,
  `commission` double DEFAULT NULL,
  `currency` varchar(5) NOT NULL DEFAULT '',
  `date_send` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_billing_user_recipients`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_blacklist`
--

DROP TABLE IF EXISTS `re_blacklist`;
CREATE TABLE IF NOT EXISTS `re_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_enemy` int(11) NOT NULL DEFAULT '0',
  `datenow` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_enemy` (`id_enemy`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_blacklist`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_bonus_settings`
--

DROP TABLE IF EXISTS `re_bonus_settings`;
CREATE TABLE IF NOT EXISTS `re_bonus_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `percent` int(11) NOT NULL DEFAULT '0',
  `amount` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `re_bonus_settings`
--

INSERT INTO `re_bonus_settings` (`id`, `percent`, `amount`) VALUES
(3, 90, 5),
(4, 60, 2.5),
(5, 30, 1);

-- --------------------------------------------------------

--
-- Table structure for table `re_calendar_events`
--

DROP TABLE IF EXISTS `re_calendar_events`;
CREATE TABLE IF NOT EXISTS `re_calendar_events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_ad` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `start_time` time NOT NULL DEFAULT '00:00:00',
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  `end_time` time NOT NULL DEFAULT '00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_calendar_events`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_city_spr`
--

DROP TABLE IF EXISTS `re_city_spr`;
CREATE TABLE IF NOT EXISTS `re_city_spr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `zip_code` text,
  `region_code` int(11) NOT NULL DEFAULT '0',
  `id_region` int(11) NOT NULL DEFAULT '0',
  `lat` decimal(11,7) NOT NULL DEFAULT '0.0000000',
  `lon` decimal(11,7) NOT NULL DEFAULT '0.0000000',
  PRIMARY KEY (`id`),
  KEY `id_country` (`id_country`),
  KEY `id_region` (`id_region`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_city_spr`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_comparison_list`
--

DROP TABLE IF EXISTS `re_comparison_list`;
CREATE TABLE IF NOT EXISTS `re_comparison_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `session` varchar(32) NOT NULL DEFAULT '0',
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_ad` (`id_ad`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_comparison_list`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_country_spr`
--

DROP TABLE IF EXISTS `re_country_spr`;
CREATE TABLE IF NOT EXISTS `re_country_spr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `id_country` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `re_country_spr`
--

INSERT INTO `re_country_spr` (`id`, `name`, `id_country`) VALUES
(1, 'Чехия', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `re_export_fields`
--

DROP TABLE IF EXISTS `re_export_fields`;
CREATE TABLE IF NOT EXISTS `re_export_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_type` int(2) NOT NULL,
  `feed_str` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=68 ;

--
-- Dumping data for table `re_export_fields`
--

INSERT INTO `re_export_fields` (`id`, `feed_type`, `feed_str`) VALUES
(1, 0, 'SingleFamily'),
(2, 0, 'Condo'),
(3, 0, 'Townhouse'),
(4, 0, 'Coop'),
(5, 0, 'MultiFamily'),
(6, 0, 'Manufactured'),
(7, 0, 'VacantLand'),
(8, 0, 'Other'),
(9, 0, '1'),
(10, 0, '2'),
(11, 0, '3'),
(12, 0, '4'),
(13, 0, '5'),
(14, 0, '6'),
(15, 0, '7'),
(16, 0, '8'),
(17, 0, '9'),
(18, 0, '10'),
(19, 0, '10+'),
(20, 0, 'Dishwasher'),
(21, 0, 'Dryer'),
(22, 0, 'Freezer'),
(23, 0, 'Microwave'),
(24, 0, 'Refrigerator'),
(25, 0, 'Washer'),
(26, 0, 'Deck'),
(27, 0, 'Elevator'),
(28, 0, 'Fireplace'),
(29, 0, 'Garden'),
(30, 0, 'HottubSpa'),
(31, 0, 'Patio'),
(32, 0, 'Pool'),
(33, 0, 'Sauna'),
(34, 1, 'Apartament/Condo/Townhouse'),
(35, 1, 'Condo'),
(36, 1, 'Townhouse'),
(37, 1, 'Coop'),
(38, 1, 'Apartament'),
(39, 1, 'Loft'),
(40, 1, 'TIC'),
(41, 1, 'Mobile/Manufactured'),
(42, 1, 'Farm/Ranch'),
(43, 1, 'Multi-Family'),
(44, 1, 'Income/Investment'),
(45, 1, 'Houseboat'),
(46, 1, 'Lot/Land'),
(47, 1, 'Single-Family Home'),
(48, 2, 'CONDO'),
(49, 2, 'HOUSE'),
(50, 2, 'TOWNHOUSE'),
(51, 2, 'DIVIDED'),
(52, 2, 'LARGE'),
(53, 2, 'COMMUNITY'),
(54, 2, 'MEDIUM'),
(55, 2, 'LAND'),
(56, 3, 'apartment'),
(57, 3, 'commercial'),
(58, 3, 'condo'),
(59, 3, 'coop'),
(60, 3, 'farm'),
(61, 3, 'land'),
(62, 3, 'manufactured'),
(63, 3, 'multifamily'),
(64, 3, 'ranch'),
(65, 3, 'single family'),
(66, 3, 'tic'),
(67, 3, 'townhouse');

-- --------------------------------------------------------

--
-- Table structure for table `re_export_spr`
--

DROP TABLE IF EXISTS `re_export_spr`;
CREATE TABLE IF NOT EXISTS `re_export_spr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_type` int(2) NOT NULL,
  `reference` enum('info','description','realty_type','period','gender','language') NOT NULL,
  `subreference` int(11) NOT NULL,
  `feed_str_id` int(11) NOT NULL,
  `re_spr_value` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=79 ;

--
-- Dumping data for table `re_export_spr`
--

INSERT INTO `re_export_spr` (`id`, `feed_type`, `reference`, `subreference`, `feed_str_id`, `re_spr_value`) VALUES
(1, 0, 'realty_type', 2, 1, '8,12,13,16,19,23'),
(2, 0, 'realty_type', 2, 2, '25,26'),
(3, 0, 'realty_type', 2, 3, '9,10,15'),
(4, 0, 'realty_type', 2, 4, ''),
(5, 0, 'realty_type', 2, 5, '11'),
(6, 0, 'realty_type', 2, 6, '41'),
(7, 0, 'realty_type', 2, 7, '31'),
(8, 0, 'realty_type', 2, 8, '14,17,18,20,21,22,24,27,28,29,30,32,33,34,35,36,37,38,39,40,42,43,44,45,46,47,48,49'),
(9, 0, 'description', 1, 9, '1'),
(10, 0, 'description', 1, 10, '2'),
(11, 0, 'description', 1, 11, '3'),
(12, 0, 'description', 1, 12, '4'),
(13, 0, 'description', 1, 13, '5'),
(14, 0, 'description', 1, 14, '6'),
(15, 0, 'description', 1, 15, '7'),
(16, 0, 'description', 1, 16, '8'),
(17, 0, 'description', 1, 17, '9'),
(18, 0, 'description', 1, 18, '10'),
(19, 0, 'description', 1, 19, '11'),
(20, 0, 'description', 2, 9, '12'),
(21, 0, 'description', 2, 10, '13'),
(22, 0, 'description', 2, 11, '14'),
(23, 0, 'description', 2, 12, '15'),
(24, 0, 'description', 2, 13, '16'),
(25, 0, 'description', 2, 14, '17'),
(26, 0, 'description', 2, 15, '18'),
(27, 0, 'description', 2, 16, '19'),
(28, 0, 'description', 2, 17, '20'),
(29, 0, 'description', 2, 18, '21'),
(30, 0, 'description', 2, 19, '22'),
(31, 0, 'info', 16, 20, '194'),
(32, 0, 'info', 16, 21, '233'),
(33, 0, 'info', 16, 22, '196'),
(34, 0, 'info', 16, 23, '198'),
(35, 0, 'info', 16, 24, '202'),
(36, 0, 'info', 16, 25, '208'),
(37, 0, 'info', 15, 26, '231'),
(38, 0, 'info', 15, 27, '166'),
(39, 0, 'info', 15, 28, '167'),
(40, 0, 'info', 15, 29, '171'),
(41, 0, 'info', 15, 30, '186'),
(42, 0, 'info', 15, 31, '181'),
(43, 0, 'info', 15, 32, '182'),
(44, 0, 'info', 15, 33, '184'),
(45, 1, 'realty_type', 2, 34, '14,17,18,21,22,27,28,29,30,32,33,34,35,36,37,38,39,40,42,43,44,45,46,47,48,49'),
(46, 1, 'realty_type', 2, 35, '25,26'),
(47, 1, 'realty_type', 2, 36, '9,15'),
(48, 1, 'realty_type', 2, 37, ''),
(49, 1, 'realty_type', 2, 38, '10'),
(50, 1, 'realty_type', 2, 39, '20'),
(51, 1, 'realty_type', 2, 40, ''),
(52, 1, 'realty_type', 2, 41, '8,41'),
(53, 1, 'realty_type', 2, 42, '24'),
(54, 1, 'realty_type', 2, 43, '11'),
(55, 1, 'realty_type', 2, 44, ''),
(56, 1, 'realty_type', 2, 45, ''),
(57, 1, 'realty_type', 2, 46, '31'),
(58, 1, 'realty_type', 2, 47, '8,12,13,16,19,23'),
(59, 2, 'realty_type', 2, 48, '25,26'),
(60, 2, 'realty_type', 2, 49, '12,13,16,19'),
(61, 2, 'realty_type', 2, 50, '15'),
(62, 2, 'realty_type', 2, 51, ''),
(63, 2, 'realty_type', 2, 52, '11'),
(64, 2, 'realty_type', 2, 53, ''),
(65, 2, 'realty_type', 2, 54, '10'),
(66, 2, 'realty_type', 2, 55, '24,31'),
(67, 3, 'realty_type', 2, 56, '10'),
(68, 3, 'realty_type', 2, 57, ''),
(69, 3, 'realty_type', 2, 58, '25,26'),
(70, 3, 'realty_type', 2, 59, ''),
(71, 3, 'realty_type', 2, 60, '24'),
(72, 3, 'realty_type', 2, 61, '31'),
(73, 3, 'realty_type', 2, 62, '8,41'),
(74, 3, 'realty_type', 2, 63, '11'),
(75, 3, 'realty_type', 2, 64, ''),
(76, 3, 'realty_type', 2, 65, '8,12,13,16,19,23'),
(77, 3, 'realty_type', 2, 66, ''),
(78, 3, 'realty_type', 2, 67, '9,15');

-- --------------------------------------------------------

--
-- Table structure for table `re_featured`
--

DROP TABLE IF EXISTS `re_featured`;
CREATE TABLE IF NOT EXISTS `re_featured` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `type` enum('1','2') NOT NULL DEFAULT '1',
  `headline` varchar(255) NOT NULL DEFAULT '',
  `date_featured` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `curr_count` double DEFAULT NULL,
  `id_region` varchar(30) NOT NULL DEFAULT '0',
  `datenow` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `upload_path` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `id_ad` (`id_ad`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `re_featured`
--

INSERT INTO `re_featured` (`id`, `id_user`, `id_ad`, `type`, `headline`, `date_featured`, `curr_count`, `id_region`, `datenow`, `upload_path`) VALUES
(1, 86, 79, '1', 'The Best Real Estate!', '2008-05-18 14:01:43', 1, '4143', '2008-05-18 14:27:24', '86_b55fd9c6.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `re_groups`
--

DROP TABLE IF EXISTS `re_groups`;
CREATE TABLE IF NOT EXISTS `re_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('f','r','g','d','t') NOT NULL DEFAULT 'f',
  `speed` int(11) DEFAULT '0',
  `addable` enum('0','1') NOT NULL DEFAULT '0',
  `allow_trial` enum('0','1') NOT NULL DEFAULT '0',
  `trial_period` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `re_groups`
--

INSERT INTO `re_groups` (`id`, `type`, `speed`, `addable`, `allow_trial`, `trial_period`) VALUES
(1, 'r', 0, '0', '0', 0),
(2, 'g', 0, '0', '0', 0),
(3, 'd', 0, '0', '0', 0),
(4, 'f', 1, '1', '1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `re_group_module`
--

DROP TABLE IF EXISTS `re_group_module`;
CREATE TABLE IF NOT EXISTS `re_group_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_group` int(11) unsigned NOT NULL DEFAULT '0',
  `id_module` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_group` (`id_group`),
  KEY `id_module` (`id_module`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=321 ;

--
-- Dumping data for table `re_group_module`
--

INSERT INTO `re_group_module` (`id`, `id_group`, `id_module`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(5, 1, 38),
(6, 1, 10),
(7, 1, 7),
(8, 3, 10),
(9, 1, 9),
(10, 4, 10),
(11, 1, 22),
(12, 1, 16),
(101, 4, 15),
(16, 1, 23),
(17, 1, 17),
(19, 1, 19),
(21, 1, 21),
(28, 1, 28),
(30, 1, 30),
(33, 1, 33),
(34, 1, 34),
(37, 1, 37),
(38, 1, 48),
(49, 2, 3),
(51, 2, 9),
(53, 2, 17),
(58, 2, 24),
(310, 3, 24),
(309, 3, 21),
(308, 3, 19),
(306, 3, 17),
(305, 3, 15),
(304, 3, 9),
(32, 1, 32),
(303, 3, 7),
(87, 4, 1),
(88, 4, 2),
(89, 4, 3),
(93, 4, 7),
(95, 4, 9),
(15, 1, 15),
(31, 1, 31),
(103, 4, 17),
(105, 4, 19),
(107, 4, 21),
(110, 4, 24),
(115, 1, 50),
(116, 2, 50),
(302, 3, 3),
(118, 4, 50),
(119, 1, 51),
(121, 1, 52),
(301, 3, 2),
(123, 4, 52),
(153, 1, 54),
(300, 3, 1),
(299, 1, 55),
(315, 1, 56),
(316, 3, 57),
(317, 1, 57),
(318, 4, 57),
(319, 1, 58),
(320, 1, 59);

-- --------------------------------------------------------

--
-- Table structure for table `re_group_period`
--

DROP TABLE IF EXISTS `re_group_period`;
CREATE TABLE IF NOT EXISTS `re_group_period` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `id_group` int(3) NOT NULL DEFAULT '0',
  `amount` int(3) NOT NULL DEFAULT '0',
  `period` enum('day','week','month','year') NOT NULL DEFAULT 'day',
  `cost` double NOT NULL DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_group` (`id_group`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `re_group_period`
--

INSERT INTO `re_group_period` (`id`, `id_group`, `amount`, `period`, `cost`, `status`) VALUES
(1, 4, 4, 'day', 10, '1'),
(2, 4, 1, 'week', 15, '1'),
(3, 4, 2, 'week', 25, '1'),
(4, 4, 1, 'month', 40, '1'),
(5, 4, 6, 'month', 200, '1'),
(6, 4, 1, 'year', 330, '1');

-- --------------------------------------------------------

--
-- Table structure for table `re_group_trial_user_period`
--

DROP TABLE IF EXISTS `re_group_trial_user_period`;
CREATE TABLE IF NOT EXISTS `re_group_trial_user_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_group` int(3) NOT NULL DEFAULT '0',
  `date_begin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_group` (`id_group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_group_trial_user_period`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_hotlist`
--

DROP TABLE IF EXISTS `re_hotlist`;
CREATE TABLE IF NOT EXISTS `re_hotlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_friend` int(11) NOT NULL DEFAULT '0',
  `datenow` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_friend` (`id_friend`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_hotlist`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_info_section`
--

DROP TABLE IF EXISTS `re_info_section`;
CREATE TABLE IF NOT EXISTS `re_info_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL DEFAULT '0',
  `sequence` int(11) NOT NULL DEFAULT '0',
  `caption` tinyblob,
  `content` blob,
  `description` tinyblob,
  `keywords` blob,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `menu_position` enum('bottom','top') NOT NULL DEFAULT 'bottom',
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `re_info_section`
--

INSERT INTO `re_info_section` (`id`, `language_id`, `sequence`, `caption`, `content`, `description`, `keywords`, `status`, `menu_position`, `update_date`) VALUES
(1, 2, 1, 0xd094d0bed0b3d0bed0b2d0bed180d0b0, 0x3c703e3c6120687265663d22687474703a2f2f7777772e70696c6f7467726f75702e6e65742f22207461726765743d225f626c616e6b22206d63655f687265663d22687474703a2f2f7777772e70696c6f7467726f75702e6e65742f223e3c696d67207372633d22687474703a2f2f726573742f61646d696e2f75706c6f616465732f656469746f722f796f75725f6c6f676f2e67696622207374796c653d22626f726465723a206d656469756d206e6f6e653b223e3c2f613e3c2f703ed094d0bed0b3d0bed0b2d0bed180d0b03c62723e0d0a3c703e596f757220706f7374616c20616464726573733c62723e436974792c2053746174652c20506f7374616c20696e6465783c62723e3c62723e57652068617665206275696c742061207374726f6e67200d0a72657075746174696f6e20617320616e206f75747374616e64696e67206d6f7274676167652062726f6b6572616765206669726d2073657276696e6720746865206c656e64696e67206e65656473200d0a6f66207265616c206573746174652070726f66657373696f6e616c732c206275696c6465727320616e6420696e646976696475616c20686f6d65627579657273207468726f7567686f7574204e6577200d0a4a657273657920616e64204d61696e652e3c62723e3c62723e576527726520612066756c6c2073657276696365206d6f7274676167652062726f6b6572207769746820616e200d0a657870657269656e636564207374616666206f66666572696e672065787065727469736520696e2065766572792061726561206f66206d6f727467616765206c656e64696e672e2e2e66726f6d200d0a707572636861736520746f20726566696e616e636520746f20636f6e737472756374696f6e206c656e64696e672c20616e6420616c736f20636f6d6d65726369616c206c656e64696e672e205765200d0a686176652061636365737320746f20612066756c6c2072616e6765206f66206d6f72746761676520736f757263657320616e6420616c6c206f66206f7572206c656e64696e67200d0a7370656369616c69737473206172652064656469636174656420746f2066696e64696e6720746865207269676874206c6f616e202d20776974682074686520626573742072617465732c207465726d73200d0a616e6420636f737473202d20746f206d656574206f757220636c69656e74732720756e69717565206e656564732e2042757420746861742773206a7573742074686520626567696e6e696e67206f66200d0a6f757220736572766963652e205468726f7567686f757420746865206c656e64696e672070726f636573732077652070726f7669646520726567756c6172206c6f616e207570646174657320616e64200d0a70726f6772657373207265706f72747320736f20636c69656e747320616c77617973206b6e6f772074686520737461747573206f66207468656972206c6f616e2e20576520616c736f206f66666572200d0a61207370656369616c204d6f727467616765204d616e61676572207365727669636520666f722074686f736520636f6e7369646572696e6720726566696e616e63696e67207468656972200d0a6d6f7274676167652e3c62723e3c62723e416e642c206e6f772069742773206f757220706c65617375726520746f206f6666657220616c6c206f66206f757220657863657074696f6e616c200d0a6d6f727467616765207365727669636573206f6e6c696e652e205468726f756768206f757220636f6d70616e7920796f75206e6f74206f6e6c7920686176652061636365737320746f20746865200d0a62657374206c6f616e7320617661696c61626c6520696e20746865206d61726b6574706c6163652c2062757420796f752063616e20726576696577206c6f616e20616c7465726e6174697665732c200d0a616e64206576656e206170706c7920666f7220796f7572206c6f616e2c20617420796f757220636f6e76656e69656e63652c206f6e6c696e65202d20323420686f7572732061200d0a6461792e3c62723e3c62723e5468616e6b20796f7520666f72207669736974696e67206f75722077656220736974652e205765206c6f6f6b20666f727761726420746f2070757474696e67206f7572200d0a6d6f727467616765207365727669636520746f20776f726b20666f7220796f75213c666f6e7420636f6c6f723d22233830383038302220666163653d225461686f6d61222073697a653d2231223e203c2f666f6e743e3c2f703e, 0xd09e20d0bdd0b0d181, 0xd0bfd180d0bed0b4d0b0d182d18c20d0b4d0bed0bc2c20d0bfd180d0bed0b4d0b0d0b6d0b020d0bad0b2d0b0d180d182d0b8d1802c20d0bfd180d0bed0b4d0b0d182d18c20d0bdd0b5d0b4d0b2d0b8d0b6d0b8d0bcd0bed181d182d18c2c20d0bad183d0bfd0b8d182d18c20d0bdd0b5d0b4d0b2d0b8d0b6d0b8d0bcd0bed181d182d18c2c20d0bad183d0bfd0b8d182d18c20d0bad0b2d0b0d180d182d0b8d180d1832c20d0bfd180d0bed0b4d0b0d182d18c20d0bad0b2d0b0d180d182d0b8d180d1832c20d0b0d180d0b5d0bdd0b4d0b020d0bad0b2d0b0d180d182d0b8d1802c20d0b0d180d0b5d0bdd0b4d0b020d0bad0bed0bcd0bdd0b0d1822c20d181d0bdd18fd182d18c20d0bad0b2d0b0d180d182d0b8d180d1832c20d181d0bdd18fd182d18c20d0bad0bed0bcd0bdd0b0d182d1832c20d181d0b4d0b0d0bc20d0bad0b2d0b0d180d182d0b8d180d1832c20d181d0b4d0b0d0bc20d0bad0bed0bcd0bdd0b0d182d1832c20d0bfd0bed181d183d182d0bed187d0bdd0b0d18f20d0b0d180d0b5d0bdd0b4d0b020d0bad0b2d0b0d180d182d0b8d1802c20d0bad0b2d0b0d180d182d0b8d180d18b20d0b220d0b0d180d0b5d0bdd0b4d1832c20d0b0d180d0b5d0bdd0b4d0b020d0bad0b2d0b0d180d182d0b8d18020d0b1d0b5d0b720d0bfd0bed181d180d0b5d0b4d0bdd0b8d0bad0bed0b22c20d0b0d180d0b5d0bdd0b4d0b020d0b1d0b5d0b720d0bfd0bed181d180d0b5d0b4d0bdd0b8d0bad0bed0b2, '1', 'bottom', '2010-12-21 15:11:17'),
(2, 2, 1, 0xd092d0bed0bfd180d0bed181d18b20d0b820d0bed182d0b2d0b5d182d18b, 0xd0add182d0bed18220d180d0b0d0b7d0b4d0b5d0bb20d0bfd0bed0bcd0bed0b6d0b5d18220d092d0b0d0bc20d0bdd0b0d0b9d182d0b820d0bed182d0b2d0b5d182d18b20d0bdd0b020d0bcd0bdd0bed0b6d0b5d181d182d0b2d0be20d0b2d0bed0bfd180d0bed181d0bed0b22e3c62723e, 0xd092d0bed0bfd180d0bed181d18b20d0b820d0bed182d0b2d0b5d182d18b, 0xd0bfd180d0bed0b4d0b0d182d18c20d0b4d0bed0bc2c20d0bfd180d0bed0b4d0b0d0b6d0b020d0bad0b2d0b0d180d182d0b8d1802c20d0bfd180d0bed0b4d0b0d182d18c20d0bdd0b5d0b4d0b2d0b8d0b6d0b8d0bcd0bed181d182d18c2c20d0bad183d0bfd0b8d182d18c20d0bdd0b5d0b4d0b2d0b8d0b6d0b8d0bcd0bed181d182d18c2c20d0bad183d0bfd0b8d182d18c20d0bad0b2d0b0d180d182d0b8d180d1832c20d0bfd180d0bed0b4d0b0d182d18c20d0bad0b2d0b0d180d182d0b8d180d1832c20d0b0d180d0b5d0bdd0b4d0b020d0bad0b2d0b0d180d182d0b8d1802c20d0b0d180d0b5d0bdd0b4d0b020d0bad0bed0bcd0bdd0b0d1822c20d181d0bdd18fd182d18c20d0bad0b2d0b0d180d182d0b8d180d1832c20d181d0bdd18fd182d18c20d0bad0bed0bcd0bdd0b0d182d1832c20d181d0b4d0b0d0bc20d0bad0b2d0b0d180d182d0b8d180d1832c20d181d0b4d0b0d0bc20d0bad0bed0bcd0bdd0b0d182d1832c20d0bfd0bed181d183d182d0bed187d0bdd0b0d18f20d0b0d180d0b5d0bdd0b4d0b020d0bad0b2d0b0d180d182d0b8d1802c20d0bad0b2d0b0d180d182d0b8d180d18b20d0b220d0b0d180d0b5d0bdd0b4d1832c20d0b0d180d0b5d0bdd0b4d0b020d0bad0b2d0b0d180d182d0b8d18020d0b1d0b5d0b720d0bfd0bed181d180d0b5d0b4d0bdd0b8d0bad0bed0b22c20d0b0d180d0b5d0bdd0b4d0b020d0b1d0b5d0b720d0bfd0bed181d180d0b5d0b4d0bdd0b8d0bad0bed0b2, '0', 'top', '2010-12-21 13:29:56'),
(3, 2, 2, 0xd0a1d182d0b0d182d0b8d181d182d0b8d0bad0b0, 0x3c703ed091d0bbd0b0d0b3d0bed0b4d0b0d180d18e20d0b2d0b0d18820d181d0b5d180d0b2d0b8d18120d0b7d0b020d0b1d0b5d0b7d183d0bfd180d0b5d187d0bdd183d18e20d0b820d0bad0b2d0b0d0bbd0b8d184d0b8d186d0b8d180d0bed0b2d0b0d0bdd0bdd183d18e20d180d0b0d0b1d0bed182d18320d0b820d0bed0bad0b0d0b7d0b0d0bdd0bdd183d18e20d0bfd0bed0bcd0bed189d18c20d0bfd180d0b820d0bfd180d0b8d0bed0b1d180d0b5d182d0b5d0bdd0b8d0b820d0bcd0bdd0bed18e20d0bad0b2d0b0d180d182d0b8d180d18b2e20d0a0d0b0d0b420d0b1d18bd0bb20d181d0bed182d180d183d0b4d0bdd0b8d187d0b0d182d18c20d181d0be20d181d0bfd0b5d186d0b8d0b0d0bbd0b8d181d182d0bed0bc20d0b2d18bd181d0bed0bad0bed0b3d0be20d0bad0bbd0b0d181d181d0b020d0b820d0bfd180d0b8d18fd182d0bdd18bd0bc20d187d0b5d0bbd0bed0b2d0b5d0bad0bed0bc2120d0a1d09fd090d0a1d098d091d09e21266e6273703b3c62723e3c656d3ed090d0bbd0b5d0bad181d0b0d0bdd0b4d18020d093d0bed0bbd183d0b1d0b5d0b23c2f656d3e3c2f703e3c62723e0d0a3c703ed0a1d187d0b8d182d0b0d18e20d0b2d0b0d18820d181d0b0d0b9d18220d0bed0b4d0bdd0b8d0bc20d0b8d0b720d181d0b0d0bcd18bd18520d0b8d0bdd184d0bed180d0bcd0b0d182d0b8d0b2d0bdd18bd18520d0b220d181d184d0b5d180d0b520d0bdd0b5d0b4d0b2d0b8d0b6d0b8d0bcd0bed181d182d0b82e20d09fd180d0b8d0b2d0bbd0b5d0bad0b0d0b5d18220d0bfd180d0bed184d0b5d181d181d0b8d0bed0bdd0b0d0bbd18cd0bdd0b0d18f20d0bfd0bed0b4d0b0d187d0b020d0b4d0b0d0bdd0bdd18bd1852c20d183d0b4d0bed0b1d0bdd0b0d18f20d0bdd0b0d0b2d0b8d0b3d0b0d186d0b8d18f20d0b820d0bfd0bed181d182d0bed18fd0bdd0bdd0bed0b520d0bed0b1d0bdd0bed0b2d0bbd0b5d0bdd0b8d0b520d181d0b5d180d0b2d0b8d181d0bed0b220d0b820d180d0b0d0b7d0b4d0b5d0bbd0bed0b22e20d0a3d0b4d0b0d187d0b82e203c62723e3c656d3ed090d0bbd0b5d0bad181d0b5d0b920d09cd0b5d0b4d0b2d0b5d0b4d0b5d0b23c2f656d3e3c2f703e3c62723e0d0a3c703ed094d0bed0b1d180d18bd0b920d0b4d0b5d0bdd18c2e20d09dd0b5d0b4d0b0d0b2d0bdd0be20d0bfd180d0b820d0bfd0bed0bcd0bed189d0b820d0b820d0bfd0bed0b4d0b4d0b5d180d0b6d0bad0b520d092d0b0d188d0b5d0b920d0bad0bed0bcd0bfd0b0d0bdd0b8d0b820d0bfd180d0b8d0bed0b1d180d0b5d0bb20d0bad0b2d0b0d180d182d0b8d180d1832e20d09ed187d0b5d0bdd18c20d0bfd0bed0bdd180d0b0d0b2d0b8d0bbd0bed181d18c20d0bed0b1d181d0bbd183d0b6d0b8d0b2d0b0d0bdd0b8d0b520d0b820d0b2d18bd181d0bed0bad0b0d18f20d0bad0b2d0b0d0bbd0b8d184d0b8d0bad0b0d186d0b8d18f20d181d0bed182d180d183d0b4d0bdd0b8d0bad0bed0b22c20d0b020d182d0b0d0bad0b6d0b520d0b4d180d183d0b6d0b5d181d0bad0bed0b520d0bed182d0bdd0bed188d0b5d0bdd0b8d0b520d0ba20d0bad0bbd0b8d0b5d0bdd182d0b0d0bc2c20d1822ed0b52e20d0ba20d0bdd0b0d0bc2e20d096d0b5d0bbd0b0d0b5d0bc20d092d0b0d0bc20d0bfd180d0bed184d0b5d181d181d0b8d0bed0bdd0b0d0bbd18cd0bdd18bd18520d183d181d0bfd0b5d185d0bed0b22e3c62723e3c656d3ed09cd0b0d180d0b8d18f20d098d0b2d0b0d0bdd0bed0b2d0b03c2f656d3e3c2f703e, 0xd09ed182d0b7d18bd0b2d18b, 0xd0bfd180d0bed0b4d0b0d182d18c20d0b4d0bed0bc2c20d0bfd180d0bed0b4d0b0d0b6d0b020d0bad0b2d0b0d180d182d0b8d1802c20d0bfd180d0bed0b4d0b0d182d18c20d0bdd0b5d0b4d0b2d0b8d0b6d0b8d0bcd0bed181d182d18c2c20d0bad183d0bfd0b8d182d18c20d0bdd0b5d0b4d0b2d0b8d0b6d0b8d0bcd0bed181d182d18c2c20d0bad183d0bfd0b8d182d18c20d0bad0b2d0b0d180d182d0b8d180d1832c20d0bfd180d0bed0b4d0b0d182d18c20d0bad0b2d0b0d180d182d0b8d180d1832c20d0b0d180d0b5d0bdd0b4d0b020d0bad0b2d0b0d180d182d0b8d1802c20d0b0d180d0b5d0bdd0b4d0b020d0bad0bed0bcd0bdd0b0d1822c20d181d0bdd18fd182d18c20d0bad0b2d0b0d180d182d0b8d180d1832c20d181d0bdd18fd182d18c20d0bad0bed0bcd0bdd0b0d182d1832c20d181d0b4d0b0d0bc20d0bad0b2d0b0d180d182d0b8d180d1832c20d181d0b4d0b0d0bc20d0bad0bed0bcd0bdd0b0d182d1832c20d0bfd0bed181d183d182d0bed187d0bdd0b0d18f20d0b0d180d0b5d0bdd0b4d0b020d0bad0b2d0b0d180d182d0b8d1802c20d0bad0b2d0b0d180d182d0b8d180d18b20d0b220d0b0d180d0b5d0bdd0b4d1832c20d0b0d180d0b5d0bdd0b4d0b020d0bad0b2d0b0d180d182d0b8d18020d0b1d0b5d0b720d0bfd0bed181d180d0b5d0b4d0bdd0b8d0bad0bed0b22c20d0b0d180d0b5d0bdd0b4d0b020d0b1d0b5d0b720d0bfd0bed181d180d0b5d0b4d0bdd0b8d0bad0bed0b2, '1', 'bottom', '2010-12-21 15:11:45'),
(4, 2, 4, 0xd09dd0b0d188d0b820d180d0b5d181d183d180d181d18b, 0x3c703e3c623e5072697661637920506f6c6963793c2f623e3c2f703e3c756c3e3c6c693e3c62723e3c2f6c693e3c6c693e3c613ed09dd0b0d188d0b820d180d0b5d181d183d180d181d18b3c2f613e3c2f6c693e3c2f756c3e0d0a3c703e5765207265737065637420616e642061726520636f6d6d697474656420746f2070726f74656374696e6720796f757220707269766163792e20546861742069732077687920776520686176652061646f707465642074686973205072697661637920506f6c6963792e2054686973205072697661637920506f6c696379206c65747320796f75206b6e6f7720686f7720796f757220706572736f6e616c20696e666f726d6174696f6e2069732070726f63657373656420616e64206578706c61696e73206461746120636f6c6c656374696f6e20616e64207573652070726163746963657320666f7220596f757220436f6d70616e7920736974652f6e6574776f726b2e205468652022596f757220436f6d70616e7920736974652f6e6574776f726b2220696e636c7564657320616c6c205765622070616765732c206e6577736c6574746572732c2064697363757373696f6e20666f72756d7320616e64206c6973747320616e64206f70742d696e20616e6e6f756e63656d656e74206c69737473206f776e656420616e64206f7065726174656420627920596f757220436f6d70616e7920616e642065616368206f66206974732077686f6c6c792d6f776e6564207375627369646961726965732e20427920616363657373696e6720596f757220436f6d70616e7920736974652f6e6574776f726b2c20796f752061726520636f6e73656e74696e6720746f2074686520696e666f726d6174696f6e20636f6c6c656374696f6e20616e6420757365207072616374696365732064657363726962656420696e2074686973205072697661637920506f6c6963792e3c2f703e0d0a3c62723e0d0a3c703e57652070726f6d69736520746861742077652077696c6c2075736520796f757220706572736f6e616c20696e666f726d6174696f6e206f6e6c7920696e207761797320746861742061726520636f6d70617469626c6520776974682074686973205072697661637920506f6c69637920617320666f6c6c6f77733a3c2f703e0d0a3c62723e0d0a3c703e3c623e5768617420696e666f726d6174696f6e2061726520796f7520636f6c6c656374696e6720616e6420686f772061726520796f7520636f6c6c656374696e672069743f203c2f623e3c2f703e0d0a3c62723e0d0a3c703e3c623e446f20796f7520636f6c6c656374204950206164647265737365733f3c2f623e3c2f703e0d0a3c62723e0d0a3c703e457665727920636f6d707574657220636f6e6e656374656420746f2074686520496e7465726e657420697320676976656e206120646f6d61696e206e616d6520616e64206120736574206f66206e756d626572732c2074686174207365727665206173207468617420636f6d707574657227732022496e7465726e65742050726f746f636f6c2220495020616464726573732e205768656e20612076697369746f72207265717565737473206120706167652066726f6d20616e792057656220736974652077697468696e2074686520594f555220434f4d50414e5920534954452f4e4554574f524b2c206f7572205765622073657276657273206175746f6d61746963616c6c79207265636f676e697a6520746861742076697369746f72277320646f6d61696e206e616d6520616e6420495020616464726573732e2054686520646f6d61696e206e616d6520616e6420495020616464726573732072657665616c206e6f7468696e6720706572736f6e616c2061626f757420796f75206f74686572207468616e2074686520495020616464726573732066726f6d20776869636820796f752068617665206163636573736564206f75722053697465732e20576520757365207468697320696e666f726d6174696f6e20746f206578616d696e65206f7572207472616666696320696e206167677265676174652c20616e6420746f20696e766573746967617465206d6973757365206f662074686520594f555220434f4d50414e5920534954452f4e4554574f524b2c206974732075736572732c206f7220746f20636f6f7065726174652077697468206c617720656e666f7263656d656e742e20576520646f206e6f7420636f6c6c65637420616e64206576616c75617465207468697320696e666f726d6174696f6e20666f7220737065636966696320696e646976696475616c732e204f757220576562207365727665727320646f206e6f74206175746f6d61746963616c6c79207265636f726420652d6d61696c20616464726573736573206f66207468652076697369746f72732e3c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e576861742061726520636f6f6b6965733f3c2f623e3c2f703e0d0a3c62723e0d0a3c703e46726f6d2074696d6520746f2074696d652c20594f555220434f4d50414e5920534954452f4e4554574f524b206f7220697473206164766572746973657273206d61792073656e6420612022636f6f6b69652220746f20796f757220636f6d70757465722e204120636f6f6b6965206973206120736d616c6c207069656365206f66206461746120746861742069732073656e7420746f20796f757220496e7465726e65742062726f777365722066726f6d2061205765622073657276657220616e642073746f726564206f6e20796f757220636f6d7075746572277320686172642064726976652e204120636f6f6b69652063616e277420726561642064617461206f6666206f6620796f757220636f6d70757465722068617264206469736b206f72207265616420636f6f6b69652066696c65732063726561746564206279206f74686572205765622073697465732e20436f6f6b69657320646f206e6f742064616d61676520796f75722073797374656d2e2057652075736520636f6f6b69657320746f206964656e74696679207768696368206172656173206f6620594f555220434f4d50414e5920534954452f20796f7520686176652076697369746564206f7220637573746f6d697a65642c20736f20746865206e6578742074696d6520796f752076697369742c2074686f7365207061676573206d61792062652072656164696c792061636365737369626c652e204f7572206164766572746973657273206d617920616c736f2075736520636f6f6b69657320746f2061736365727461696e20686f77206d616e792074696d657320796f75277665207365656e20616e206164766572746973656d656e742e204f757220616476657274697365727320616e64207765206d617920757365207468697320696e666f726d6174696f6e20746f2062657474657220706572736f6e616c697a652074686520636f6e74656e742c2062616e6e6572732c20616e642070726f6d6f74696f6e73207468617420796f7520736565206f6e206f757220536974652e20596f752063616e2063686f6f7365207768657468657220746f2061636365707420636f6f6b696573206279206368616e67696e67207468652073657474696e6773206f6620796f757220496e7465726e65742062726f777365722e20596f752063616e20726573657420796f75722062726f7773657220746f2072656675736520616c6c20636f6f6b6965732c206f7220616c6c6f7720796f75722062726f7773657220746f2073686f7720796f75207768656e206120636f6f6b6965206973206265696e672073656e742e20496620796f752063686f6f7365206e6f7420746f2061636365707420746865736520636f6f6b6965732c20796f757220657870657269656e6365206174206f7572205369746520616e64206f7468657220576562207369746573206d61792062652064696d696e697368656420616e6420736f6d65206665617475726573206d6179206e6f7420776f726b20617320696e74656e6465642e203c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e486f7720646f20796f75207573652073696e676c652d706978656c20676966733f203c2f623e3c2f703e0d0a3c62723e0d0a3c703e594f555220434f4d50414e5920534954452f4e4554574f524b20616e6420697473206164766572746973657273206d6179207573652073696e676c652d706978656c2067696620696d616765732c20736f6d6574696d657320726566657272656420746f206173207765622062756773206f722077656220626561636f6e732c20746f20636f756e7420576562207061676520616363657373657320616e642067617468657220736f6d652067656e6572616c20737461746973746963616c20696e666f726d6174696f6e2e20594f555220434f4d50414e5920534954452f4e4554574f524b20646f6573206e6f742067617468657220706572736f6e616c20696e666f726d6174696f6e207468726f7567682074686520757365206f6620746865736520696d616765732e204f7572206164766572746973657273206d6179207573652073696e676c652d706978656c206769667320746861742063616e20747261636b20706572736f6e616c20696e666f726d6174696f6e2066726f6d20636f6f6b6965732074686174206d61792068617665206265656e2070726576696f75736c7920706c61636564206f6e20612075736572277320636f6d707574657220627920616e20616476657274697365722e203c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e5768617420706572736f6e616c20696e666f726d6174696f6e20646f20796f7520636f6c6c6563743f3c2f623e3c2f703e0d0a3c62723e0d0a3c703e576865726576657220594f555220434f4d50414e5920534954452f4e4554574f524b20636f6c6c6563747320706572736f6e616c20696e666f726d6174696f6e207765206d616b6520616e206566666f727420746f20696e636c7564652061206c696e6b20746f2074686973205072697661637920506f6c6963792e2057652077696c6c2061736b20796f75207768656e207765206e65656420696e666f726d6174696f6e207468617420706572736f6e616c6c79206964656e74696669657320796f752028706572736f6e616c20696e666f726d6174696f6e29206f7220616c6c6f777320757320746f20636f6e7461637420796f752e2054686520706572736f6e616c20696e666f726d6174696f6e20776520636f6c6c656374206d617920696e636c75646520796f7572206e616d652c207469746c652c20636f6d70616e79206f72206f7267616e697a6174696f6e206e616d652c20652d6d61696c20616464726573732c2070686f6e65206e756d6265722c20776f726b206f7220686f6d6520616464726573732c20696e666f726d6174696f6e2061626f757420796f7572206a6f622066756e6374696f6e2c20696e666f726d6174696f6e2061626f757420796f757220636f6d70616e792c20616e6420637265646974206361726420696e666f726d6174696f6e2e203c2f703e0d0a3c703e5765206d6179207265717565737420796f757220652d6d61696c2061646472657373206f72206d61696c696e67206164647265737320666f722074686520707572706f736573206f6620636f6e64756374696e67206120636f6e74657374206f7220746f2070726f76696465206164646974696f6e616c2073657276696365732028666f72206578616d706c652c20737562736372697074696f6e7320746f20616e6e6f756e63656d656e74206c69737473206f7220696e666f726d6174696f6e2061626f757420636f6e666572656e63657320616e642074726164652073686f7773292e2050617274696369706174696f6e20696e20636f6e7465737473206f72206f746865722070726f6d6f74696f6e73206f6e2074686520594f555220434f4d50414e5920534954452f4e4554574f524b204e6574776f726b20697320636f6d706c6574656c7920766f6c756e7461727920616e6420796f75206861766520612063686f6963652077686574686572206f72206e6f7420746f20646973636c6f736520706572736f6e616c20696e666f726d6174696f6e2e20496e666f726d6174696f6e20726571756573746564206d617920696e636c75646520636f6e7461637420696e666f726d6174696f6e202873756368206173206e616d6520616e64207368697070696e672061646472657373292c20616e642064656d6f6772617068696320696e666f726d6174696f6e202873756368206173207a697020636f6465292e20436f6e7461637420696e666f726d6174696f6e2077696c6c206265207573656420746f206e6f74696679207468652077696e6e65727320616e64206177617264207072697a65732e203c2f703e0d0a3c703e496620796f75206172652070757263686173696e6720736f6d657468696e67206f6e2074686520594f555220434f4d50414e5920534954452f4e4554574f524b2c2077652c20616e6420616e792074686972642070617274792070726f76696465722c206e65656420746f206b6e6f7720796f7572206e616d652c20652d6d61696c20616464726573732c206d61696c696e6720616464726573732c206372656469742063617264206e756d62657220616e642065787069726174696f6e20646174652e205468697320616c6c6f77732075732c206f722074686972642070617274792070726f7669646572732c20746f2070726f6365737320616e642066756c66696c6c20796f7572206f7264657220616e6420746f206e6f7469667920796f75206f6620796f7572206f72646572207374617475732e20437265646974206361726420616e6420652d6d61696c20696e666f726d6174696f6e2077696c6c206e6f7420626520736861726564206f7220736f6c6420746f207468697264207061727469657320666f7220616e7920707572706f7365206578636570742061732070726f766964656420696e2074686973205072697661637920506f6c69637920776974686f757420796f75722065787072657373207065726d697373696f6e2e203c2f703e0d0a3c703e594f555220434f4d50414e5920534954452f4e4554574f524b20616c736f206f66666572732061636365737320746f20736f6d652073657276696365732074686174207265717569726520726567697374726174696f6e2e205765206d61792075736520796f757220726567697374726174696f6e20636f6e7461637420696e666f726d6174696f6e20746f2073656e64206e6f74696669636174696f6e20746f20796f75206f662070726f647563747320616e6420736572766963657320627920652d6d61696c20616e642f6f7220706f7374616c206d61696c2e205765206d6179207368617265207468617420696e666f726d6174696f6e2077697468206f757220636c69656e747320616e642074686579206d617920616c736f2073656e6420796f752070726f6d6f74696f6e616c206d6174657269616c732e205765206d617920616c736f2075736520796f7572204950206164647265737320696e7465726e616c6c7920616e64207368617265206974207769746820746869726420706172746965732e203c2f703e0d0a3c703e5768656e20796f75207369676e20757020666f72206120594f555220434f4d50414e5920534954452f4e4554574f524b20652d6d61696c206e6577736c65747465722c20796f752061726520676976656e20746865206f70706f7274756e69747920746f207369676e20757020666f722074617267657465642074686972642d7061727479206d61696c696e67732e2057686574686572206f72206e6f7420796f752077616e7420746f207265636569766520746865736520616e6e6f756e63656d656e747320697320796f75722063686f6963652e20496620796f752063686f6f736520746f207265636569766520616e20616e6e6f756e63656d656e742066726f6d206120737065636966696320646973747269627574696f6e206c6973742c20796f752077696c6c207265636569766520636f6d6d65726369616c206d657373616765732066726f6d20696e646570656e64656e7420746869726420706172746965732074686174206861766520636f6e74726163746564207769746820594f555220434f4d50414e5920534954452f4e4554574f524b20746f2073656e64206d6573736167657320746f20746865206c6973742e20596f75206d61792073746f7020746865206172726976616c206f6620746865736520616e6e6f756e63656d656e74206c6973747320617420616e792074696d652062792072656d6f76696e6720796f757220652d6d61696c20616464726573732066726f6d20746865206c69737420627920666f7277617264696e672074686520616e6e6f756e63656d656e74206c69737420746f20616e6e6f756e63656d656e746c69737440796f7572636f6d70616e792e636f6d206f7220627920666f6c6c6f77696e672074686520696e737472756374696f6e73206f6e2074686520652d6d61696c7320796f7520726563656976652e203c2f703e0d0a3c703e5768656e20796f75207369676e20757020666f72206120594f555220434f4d50414e5920534954452f4e4554574f524b2064697363757373696f6e20666f72756d2c20796f7572206e616d65206f7220616c696173206973207265636f7264656420736f6c656c7920666f7220707572706f736573206f66206d61696e7461696e696e6720796f7572206f776e206163636f756e742077697468696e2074686520666f72756d732e205468697320696e666f726d6174696f6e206973206e6f74207573656420746f206d6f6e69746f7220796f75722061637469766974792077697468696e206120666f72756d2c206e6f72206973206974207573656420746f206964656e7469667920796f75206f7574736964652074686520594f555220434f4d50414e5920534954452f4e4554574f524b2e203c2f703e0d0a3c703e5768656e20796f752075736520636f2d6272616e6465642073657276696365732c206f722074686f73652070726f766964656420627920612074686972642070617274792c2077697468696e2074686520594f555220434f4d50414e5920534954452f4e4554574f524b2c20796f757220696e666f726d6174696f6e206d61792062652070617373656420746f2074686520636f2d6272616e64206f722074686972642070617274792070726f766964657220616e6420746865697220757365206f6620796f757220696e666f726d6174696f6e206973207375626a65637420746f207468656972206170706c696361626c65207072697661637920706f6c69636965732e203c2f703e0d0a3c703e5765206d61792066726f6d2074696d6520746f2074696d652073656e6420652d6d61696c20616e6e6f756e63696e67206e65772070726f647563747320616e642073657276696365732e20496620796f752063686f6f736520746f20737570706c7920796f757220706f7374616c206164647265737320696e20616e206f6e6c696e6520666f726d2c20796f75206d6179207265636569766520706f7374616c206d61696c696e67732066726f6d20594f555220434f4d50414e5920534954452f4e4554574f524b206f7220746869726420706172747920636f6d70616e6965732e203c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e57696c6c20796f7520646973636c6f73652074686520696e666f726d6174696f6e20796f7520636f6c6c65637420746f206f75747369646520746869726420706172746965733f3c2f623e3c2f703e0d0a3c62723e0d0a3c703e594f555220434f4d50414e5920534954452f4e4554574f524b2077696c6c20646973636c6f736520706572736f6e616c20696e666f726d6174696f6e20616e642f6f7220616e20495020616464726573733a203c2f703e0d0a3c6f6c3e0d0a3c6c693e546f20636f6e7461637420796f7520726567617264696e67206f746865722070726f647563747320616e64207365727669636573207768696368206d6179206265206f6620696e74657265737420746f20796f752028696e636c7564696e672074686f7365207765206d6179206f66666572206a6f696e746c792077697468206f7468657220636f6d70616e696573292e20417420616e792074696d652c20686f77657665722c20796f75206d6179206f7074206f7574206f66207375636820636f6e746163743b203c2f6c693e0d0a3c6c693e5768656e207265717569726564206279206c6177206f7220696e2074686520676f6f642d66616974682062656c6965662074686174207375636820616374696f6e206973206e656365737361727920746f3a203c2f6c693e0d0a3c756c3e0d0a3c6c693e436f6f70657261746520776974682074686520696e7665737469676174696f6e73206f6620707572706f7274656420756e6c617766756c206163746976697469657320616e6420636f6e666f726d20746f2074686520656469637473206f6620746865206c6177206f7220636f6d706c792077697468206c6567616c2070726f6365737320736572766564206f6e20594f555220434f4d50414e5920534954452f4e4554574f524b3b203c2f6c693e0d0a3c6c693e50726f7465637420616e6420646566656e642074686520726967687473206f722070726f7065727479206f662074686520594f555220434f4d50414e5920534954452f4e4554574f524b206f6620736974657320616e642072656c617465642070726f706572746965732c206f722076697369746f727320746f2074686520594f555220434f4d50414e5920534954452f4e4554574f524b206f6620736974657320616e642072656c617465642070726f706572746965733b203c2f6c693e0d0a3c6c693e4964656e7469667920706572736f6e732077686f206d61792062652076696c696174696e6720746865206c61772c2074686520594f555220434f4d50414e5920534954452f4e4554574f524b206c6567616c206e6f7469636520616e6420576562207369746520557365722041677265656d656e742c2074686520726967687473206f6620746869726420706172746965732c206f72206f7468657277697365206d69737573696e672074686520594f555220434f4d50414e5920534954452f4e4554574f524b206f72206974732072656c617465642070726f706572746965733b203c2f6c693e0d0a3c6c693e46756c66696c6c206120757365722773206f72646572206f7220726571756573743b203c2f6c693e3c2f756c3e0d0a3c6c693e546f2070726f6365737320616e642066756c66696c6c20796f7572206f72646572206f72206e6f7469667920796f75206f66206f72646572207374617475733b203c2f6c693e0d0a3c6c693e5768656e207765206861766520796f757220636f6e73656e7420746f2073686172652074686520696e666f726d6174696f6e3b203c2f6c693e0d0a3c6c693e5768656e20796f752075736520636f2d6272616e6465642073657276696365732077697468696e206f7572206e6574776f726b206f662073697465732c20796f75206772616e74207573207065726d697373696f6e20746f207061737320796f757220726567697374726174696f6e20696e666f726d6174696f6e206261636b20746f2074686174207365727669636520706172746e65722e20546865697220757365206f6620796f757220696e666f726d6174696f6e206973207375626a65637420746f207468656972206170706c696361626c65207072697661637920706f6c69636965732e203c2f6c693e3c2f6f6c3e0d0a3c703e5765206d61696e7461696e20612073747269637420224e6f2d5370616d2220706f6c6963792074686174206d65616e73207468617420776520646f206e6f742073656c6c2c2072656e742c206f72206f7468657277697365206769766520796f757220652d6d61696c206164647265737320746f20612074686972642d70617274792c20776974686f757420796f757220636f6e73656e74206f72206173207065726d69747465642062792074686973205072697661637920506f6c6963792e203c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e5768617420656c73652073686f756c642049206b6e6f772061626f7574206d792070726976616379207768656e206f6e6c696e653f3c2f623e3c2f703e0d0a3c62723e0d0a3c703e54686520594f555220434f4d50414e5920534954452f4e4554574f524b20636f6e7461696e73206d616e792068797065726c696e6b7320746f207468697264207061727479205765622073697465732e2054686520594f555220434f4d50414e5920534954452f4e4554574f524b20616c736f20636f6e7461696e73206164766572746973656d656e7473206f6620746869726420706172746965732e20594f555220434f4d50414e5920534954452f4e4554574f524b206973206e6f7420726573706f6e7369626c6520666f7220746865207072697661637920707261637469636573206f722074686520636f6e74656e74206f66207375636820746869726420706172747920576562207369746573206f722061647665727469736572732e20594f555220434f4d50414e5920534954452f4e4554574f524b20646f6573206e6f7420736861726520616e79206f662074686520696e646976696475616c20706572736f6e616c20696e666f726d6174696f6e20796f752070726f7669646520594f555220434f4d50414e5920534954452f4e4554574f524b207769746820746865207468697264207061727469657320746f20776869636820594f555220434f4d50414e5920534954452f4e4554574f524b206c696e6b732c206578636570742061732073746174656420656c736577686572652077697468696e2074686973205072697661637920506f6c6963792c20616c74686f75676820594f555220434f4d50414e5920534954452f4e4554574f524b206d61792073686172652067656e6572616c2c20616767726567617465206461746120776974682073756368207468697264207061727469657320287375636820617320686f77206d616e792070656f706c6520757365206f75722053697465292e20506c6561736520636865636b20776974682074686f73652057656220736974657320746f2064657465726d696e65207468656972207072697661637920706f6c6963792e203c2f703e0d0a3c703e496620796f752075736520594f555220434f4d50414e5920534954452f4e4554574f524b27732064697363757373696f6e20746872656164732c20796f752073686f756c64206265206177617265207468617420616e7920706572736f6e616c6c79206964656e7469666961626c6520696e666f726d6174696f6e20796f75207375626d69742074686572652063616e20626520726561642c20636f6c6c65637465642c206f722075736564206279206f74686572207573657273206f6620746865736520666f72756d732c20616e6420636f756c64206265207573656420746f2073656e6420796f7520756e736f6c696369746564206d657373616765732e20576520617265206e6f7420726573706f6e7369626c6520666f722074686520706572736f6e616c6c79206964656e7469666961626c6520696e666f726d6174696f6e20796f752063686f6f736520746f207375626d697420696e2074686573652064697363757373696f6e20746872656164732e20496e206f7264657220746f2061766f696420796f757220652d6d61696c2061646472657373206f72206f7468657220706572736f6e616c20696e666f726d6174696f6e2066726f6d206265696e6720676174686572656420616e642075736564206279206f746865727320666f7220696e617070726f707269617465206f72206861726d66756c20707572706f7365732c20594f555220434f4d50414e5920534954452f4e4554574f524b2061647669736573207468617420796f752073686f756c642062652063617574696f75732061626f757420706f7374696e672061207265616c20652d6d61696c2061646472657373206f72206f7468657220706572736f6e616c20696e666f726d6174696f6e20746f206e65777367726f7570732c2063686174732c206f72206f74686572207075626c696320666f72756d732e203c2f703e0d0a3c703e506c65617365206b65657020696e206d696e642074686174207768656e6576657220796f7520766f6c756e746172696c7920646973636c6f736520706572736f6e616c20696e666f726d6174696f6e206f6e6c696e65202d20666f72206578616d706c65207468726f75676820652d6d61696c2c2064697363757373696f6e206c697374732c206f7220656c73657768657265202d207468617420696e666f726d6174696f6e2063616e20626520636f6c6c656374656420616e642075736564206279206f74686572732e20496e2073686f72742c20696620796f7520706f737420706572736f6e616c20696e666f726d6174696f6e206f6e6c696e6520746861742069732061636365737369626c6520746f20746865207075626c69632c20796f75206d6179207265636569766520756e736f6c696369746564206d657373616765732066726f6d206f74686572207061727469657320696e2072657475726e2e203c2f703e0d0a3c703e4365727461696e20594f555220434f4d50414e5920534954452f4e4554574f524b206d656469612070726f706572746965732075736520612073686f7070696e672063617274206665617475726520746861742073616665677561726473207468697320696e666f726d6174696f6e206279207573696e6720696e647573747279207374616e646172642053534c202853656375726520536f636b6574204c617965722920656e6372797074656420736572766572732e2053534c20636f6465732074686520696e666f726d6174696f6e207472616e73666572726564206265747765656e20796f7520616e642074686520732065727665722c2072656e646572696e6720697420756e7265616461626c6520746f20616e796f6e6520747279696e6720746f20696e746572636570742074686520696e666f726d6174696f6e2e204f7468657220594f555220434f4d50414e5920534954452f4e4554574f524b206d656469612070726f7065727469657320646f206e6f74207573652053534c20616e64207468657265627920646f206e6f74206f6666657220612073656375726520636f6465642077617920746f207472616e7366657220696e666f726d6174696f6e2e203c2f703e0d0a3c703e556c74696d6174656c792c20796f752061726520736f6c656c7920726573706f6e7369626c6520666f72206d61696e7461696e696e67207468652073656372656379206f6620796f757220706572736f6e616c20696e666f726d6174696f6e2e20506c65617365206265206361726566756c20616e6420726573706f6e7369626c65207768656e6576657220796f75277265206f6e6c696e652e203c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e427573696e657373207472616e73666572733c2f623e3c2f703e0d0a3c62723e0d0a3c703e57652061726520616c77617973207365656b696e6720746f20696d70726f7665206f7572206e6574776f726b206f662057656220736974657320616e64206f757220627573696e6573732e20496e206163636f6d706c697368696e67207468657365206f626a656374697665732c207765206d61792073656c6c206120636f6d70616e792c20617373657473206f72207765627369746573206f72206f6e65206f72206d6f7265206f662074686520636f6d70616e69657320696e206f757220636f72706f726174652066616d696c79206d6179206d6572676520776974682c206f722062652061637175697265642062792c20616e6f7468657220636f6d70616e792e20496e20636f6e6e656374696f6e20776974682074686520666f7265676f696e672c207765206d6179207472616e7366657220736f6d65206f7220616c6c206f6620796f757220696e666f726d6174696f6e20696e206f726465722074686174207468652073657276696365206265696e672070726f766964656420746f20796f75206d617920636f6e74696e7565206f7220666f72206f7468657220627573696e65737320707572706f7365732e203c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e596f757220436f6e73656e7420546f20546869732041677265656d656e743c2f623e3c2f703e0d0a3c62723e0d0a3c703e4279207573696e672074686520594f555220434f4d50414e5920534954452f4e4554574f524b204e6574776f726b2c20796f7520636f6e73656e7420746f2074686520636f6c6c656374696f6e20616e6420757365206f6620696e666f726d6174696f6e20627920594f555220434f4d50414e5920534954452f4e4554574f524b206173207370656369666965642061626f76652e20576520726573657276652074686520726967687420746f206d6f64696679207468697320506f6c6963792e2049662077652064656369646520746f206368616e6765206f7572205072697661637920506f6c6963792c2077652077696c6c20706f73742074686f7365206368616e676573206f6e2074686973207061676520736f207468617420796f752061726520616c77617973206177617265206f66207768617420696e666f726d6174696f6e20776520636f6c6c6563742c20686f77207765207573652069742c20616e6420756e64657220776861742063697263756d7374616e63657320776520646973636c6f73652069742e203c2f703e0d0a3c703e506c656173652073656e6420616e79207175657374696f6e732061626f757420594f555220434f4d50414e5920534954452f4e4554574f524b2773205072697661637920506f6c69637920746f3a203c6120687265663d226d61696c746f3a7072697661637940796f7572636f6d70616e792e636f6d22206d63655f687265663d226d61696c746f3a7072697661637940796f7572636f6d70616e792e636f6d223e7072697661637940796f7572636f6d70616e792e636f6d3c2f613e203c2f703e0d0a3c703e506c65617365206e6f7465207468617420796f757220757365206f662074686520594f555220434f4d50414e5920534954452f4e4554574f524b206f662057656220736974657320697320676f7665726e6564206279206f7572204c6567616c204e6f746963652e203c2f703e, 0xd09fd0bed0bbd0b8d182d0b8d0bad0b020d0bad0bed0bdd184d0b8d0b4d0b5d0bdd186d0b8d0b0d0bbd18cd0bdd0bed181d182d0b8, 0xd0bfd180d0bed0b4d0b0d182d18c20d0b4d0bed0bc2c20d0bfd180d0bed0b4d0b0d0b6d0b020d0bad0b2d0b0d180d182d0b8d1802c20d0bfd180d0bed0b4d0b0d182d18c20d0bdd0b5d0b4d0b2d0b8d0b6d0b8d0bcd0bed181d182d18c2c20d0bad183d0bfd0b8d182d18c20d0bdd0b5d0b4d0b2d0b8d0b6d0b8d0bcd0bed181d182d18c2c20d0bad183d0bfd0b8d182d18c20d0bad0b2d0b0d180d182d0b8d180d1832c20d0bfd180d0bed0b4d0b0d182d18c20d0bad0b2d0b0d180d182d0b8d180d1832c20d0b0d180d0b5d0bdd0b4d0b020d0bad0b2d0b0d180d182d0b8d1802c20d0b0d180d0b5d0bdd0b4d0b020d0bad0bed0bcd0bdd0b0d1822c20d181d0bdd18fd182d18c20d0bad0b2d0b0d180d182d0b8d180d1832c20d181d0bdd18fd182d18c20d0bad0bed0bcd0bdd0b0d182d1832c20d181d0b4d0b0d0bc20d0bad0b2d0b0d180d182d0b8d180d1832c20d181d0b4d0b0d0bc20d0bad0bed0bcd0bdd0b0d182d1832c20d0bfd0bed181d183d182d0bed187d0bdd0b0d18f20d0b0d180d0b5d0bdd0b4d0b020d0bad0b2d0b0d180d182d0b8d1802c20d0bad0b2d0b0d180d182d0b8d180d18b20d0b220d0b0d180d0b5d0bdd0b4d1832c20d0b0d180d0b5d0bdd0b4d0b020d0bad0b2d0b0d180d182d0b8d18020d0b1d0b5d0b720d0bfd0bed181d180d0b5d0b4d0bdd0b8d0bad0bed0b22c20d0b0d180d0b5d0bdd0b4d0b020d0b1d0b5d0b720d0bfd0bed181d180d0b5d0b4d0bdd0b8d0bad0bed0b2, '1', 'bottom', '2010-12-21 15:32:02'),
(5, 2, 3, 0xd0a0d0b5d0bad0bbd0b0d0bcd0b020d0bdd0b020d181d0b0d0b9d182d0b5, 0x3c703e266e6273703b3c613ed0a0d0b5d0bad0bbd0b0d0bcd0b020d0bdd0b020d181d0b0d0b9d182d0b53c2f613e3c2f703e3c703e3c623e416363657074616e6365206f66205465726d73206f66205573652e203c2f623e3c2f703e0d0a3c62723e0d0a3c703e495420495320494d504f5254414e54205448415420594f55205245414420414c4c20544845205445524d5320414e4420434f4e444954494f4e53204341524546554c4c592e205265616c74792050726f20285265616c74792050726f29206f776e7320616e64206f706572617465732074686973205765622c206c6f636174656420617420687474703a2f2f7265616c7479736f66742e70726f2028746865205765622073697465292e2054686973205465726d73206f66205573652041677265656d656e742028746869732041677265656d656e74292073746174657320746865207465726d7320616e6420636f6e646974696f6e7320756e64657220776869636820796f75206d61792061636365737320616e6420757365207468652057656220736974652e20427920616363657373696e6720616e64207573696e672074686520576562207369746520796f752061726520696e6469636174696e6720796f757220616363657074616e636520746f20626520626f756e6420627920746865207465726d7320616e6420636f6e646974696f6e73206f6620746869732041677265656d656e742e20496620796f7520646f206e6f7420616363657074207468657365207465726d7320616e6420636f6e646974696f6e732c20796f75206d757374206e6f7420616363657373206f7220757365207468652057656220736974652e20494620594f552041524520444953534154495346494544205749544820544845205445524d532c20434f4e444954494f4e532c2052554c45532c20504f4c49434945532c2047554944454c494e4553204f5220505241435449434553204f4620544845205265616c74792050726f204f5045524154494e472057454220534954452c20594f555220534f4c4520414e44204558434c55534956452052454d45445920495320544f20444953434f4e54494e5545205553494e472049542e205265616c74792050726f206d61792072657669736520746869732041677265656d656e7420617420616e792074696d65206279207570646174696e67207468697320706f7374696e672e20557365206f66207468652057656220736974652061667465722073756368206368616e6765732061726520706f737465642077696c6c207369676e69667920796f75722061677265656d656e7420746f2074686573652072657669736564207465726d732e20596f752073686f756c642076697369742074686973207061676520706572696f646963616c6c7920746f2072657669657720746869732041677265656d656e742e3c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e4f776e6572736869702e3c2f623e3c2f703e0d0a3c62723e0d0a3c703e416c6c206d6174657269616c7320646973706c61796564206f72206f74686572776973652061636365737369626c65207468726f756768207468652057656220736974652c20696e636c7564696e672c20776974686f7574206c696d69746174696f6e2c206e6577732061727469636c65732c20746578742c2070686f746f6772617068732c20696d616765732c20696c6c757374726174696f6e732c20617564696f20636c6970732c20766964656f20636c6970732c20636f6d707574657220736f66747761726520616e6420636f64652028636f6c6c6563746976656c792c2074686520436f6e74656e7429206172652070726f74656374656420756e646572206c6f63616c20616e6420666f726569676e20636f70797269676874206f72206f74686572206c6177732c20616e6420617265206f776e6564206279205265616c74792050726f2c20697473206c6963656e736f7273206f72207468652070617274792061636372656469746564206173207468652070726f7669646572206f662074686520436f6e74656e742e20496e206164646974696f6e2c207468652057656220736974652069732070726f74656374656420756e64657220636f70797269676874206c6177206173206120636f6c6c65637469766520776f726b20616e642f6f7220636f6d70696c6174696f6e207075727375616e7420746f204c6f63616c20616e6420666f726569676e206c6177732e20596f75207368616c6c20616269646520627920616c6c206164646974696f6e616c20636f70797269676874206e6f74696365732c20696e666f726d6174696f6e20616e64207265737472696374696f6e73206f6e206f7220636f6e7461696e656420696e20616e79206f662074686520436f6e74656e74206163636573736564207468726f756768207468652057656220736974652e20414e59205553452c20524550524f44554354494f4e2c20414c5445524154494f4e2c204d4f44494649434154494f4e2c205055424c494320504552464f524d414e4345204f5220444953504c41592c2055504c4f4144494e47204f5220504f5354494e47204f4e544f2054484520494e5445524e45542c205452414e534d495353494f4e2c205245444953545249425554494f4e204f52204f54484552204558504c4f49544154494f4e204f46205448452057454253495445204f52204f4620414e5920434f4e54454e542c205748455448455220494e2057484f4c45204f5220494e20504152542c204f54484552205448414e20455850524553534c5920534554204f55542048455245494e2c2049532050524f4849424954454420574954484f5554205448452045585052455353205752495454454e205045524d495353494f4e204f46205265616c74792050726f2e203c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e54726164656d61726b732e203c2f623e3c2f703e0d0a3c62723e0d0a3c703e4c4f43414c2042524f414443415354494e4720434f52504f524154494f4e2c2044455349474e20617265206f6666696369616c206d61726b73206f722074726164656d61726b73206f6620746865204c6f63616c2042726f616463617374696e6720436f72706f726174696f6e2e204f74686572206e616d65732c20776f7264732c207469746c65732c20706872617365732c206c6f676f732c2064657369676e732c2067726170686963732c2069636f6e7320616e642074726164656d61726b7320646973706c61796564206f6e20746865205765622073697465206d617920636f6e737469747574652072656769737465726564206f7220756e726567697374657265642074726164656d61726b73206f66205265616c74792050726f206f7220746869726420706172746965732e205768696c65206365727461696e2074726164656d61726b73206f662074686972642070617274696573206d61792062652075736564206279205265616c74792050726f20756e646572206c6963656e73652c2074686520646973706c6179206f662074686972642d70617274792074726164656d61726b73206f6e207468652057656220736974652073686f756c64206e6f742062652074616b656e20746f20696d706c7920616e792072656c6174696f6e73686970206f72206c6963656e7365206265747765656e205265616c74792050726f20616e6420746865206f776e6572206f6620736169642074726164656d61726b206f7220746f20696d706c792074686174205265616c74792050726f20656e646f72736573207468652077617265732c207365727669636573206f7220627573696e657373206f6620746865206f776e6572206f6620736169642074726164656d61726b2e204e6f7468696e6720636f6e7461696e6564206f6e207468652057656220736974652073686f756c6420626520636f6e737472756564206173206772616e74696e6720796f7520616e79206c6963656e7365206f7220726967687420746f2075736520616e792074726164656d61726b206c6f676f206f722064657369676e206f66205265616c74792050726f206f7220616e792074686972642070617274792c20776974686f757420746865207772697474656e207065726d697373696f6e206f66205265616c74792050726f206f72207468652072657370656374697665206f776e6572206f6620616e792074686972642d70617274792074726164656d61726b2e3c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e444953434c41494d4552204f462057415252414e544945532e3c2f623e3c2f703e0d0a3c62723e0d0a3c703e54484520574542205349544520414e4420414c4c20434f4e54454e542049532050524f56494445442041532049532e20425920414343455353494e4720414e44205553494e472054484520574542205349544520594f552041434b4e4f574c4544474520414e44204147524545205448415420555345204f462054484520574542205349544520414e442054484520434f4e54454e5420495320454e544952454c5920415420594f5552204f574e205249534b2e205265616c74792050726f204d414b4553204e4f20524550524553454e544154494f4e53204f522057415252414e5449455320524547415244494e472054484520574542205349544520414e442054484520434f4e54454e542c20494e434c5544494e472c20574954484f5554204c494d49544154494f4e2c204e4f20524550524553454e544154494f4e204f522057415252414e54592028492920544841542054484520574542205349544520414e442f4f5220434f4e54454e542057494c4c2042452041434355524154452c20434f4d504c4554452c2052454c4941424c452c205355495441424c45204f522054494d454c593b2028494929205448415420414e5920434f4e54454e542c20494e434c5544494e472c20574954484f5554204c494d49544154494f4e2c20414e5920494e464f524d4154494f4e2c20444154412c20534f4654574152452c2050524f44554354204f52205345525649434520434f4e5441494e454420494e204f52204d41444520415641494c41424c45205448524f554748205448452057454220534954452057494c4c204245204f46204d45524348414e5441424c45205155414c495459204f522046495420464f52204120504152544943554c415220505552504f53453b2049494929205448415420544845204f5045524154494f4e204f46205448452057454220534954452057494c4c20424520554e494e544552525550544544204f52204552524f5220465245453b202849562920544841542044454645435453204f52204552524f525320494e205448452057454220534954452057494c4c20424520434f525245435445443b202856292054484154205448452057454220534954452057494c4c20424520465245452046524f4d2056495255534553204f52204841524d46554c20434f4d504f4e454e54533b20414e442028564929205448415420434f4d4d554e49434154494f4e5320544f204f522046524f4d205448452057454220534954452057494c4c20424520534543555245204f52204e4f5420494e5445524345505445442e3c2f703e, 0xd09fd180d0b0d0b2d0b8d0bbd0b020d0bfd0bed0bbd18cd0b7d0bed0b2d0b0d0bdd0b8d18f, 0xd0bfd180d0bed0b4d0b0d182d18c20d0b4d0bed0bc2c20d0bfd180d0bed0b4d0b0d0b6d0b020d0bad0b2d0b0d180d182d0b8d1802c20d0bfd180d0bed0b4d0b0d182d18c20d0bdd0b5d0b4d0b2d0b8d0b6d0b8d0bcd0bed181d182d18c2c20d0bad183d0bfd0b8d182d18c20d0bdd0b5d0b4d0b2d0b8d0b6d0b8d0bcd0bed181d182d18c2c20d0bad183d0bfd0b8d182d18c20d0bad0b2d0b0d180d182d0b8d180d1832c20d0bfd180d0bed0b4d0b0d182d18c20d0bad0b2d0b0d180d182d0b8d180d1832c20d0b0d180d0b5d0bdd0b4d0b020d0bad0b2d0b0d180d182d0b8d1802c20d0b0d180d0b5d0bdd0b4d0b020d0bad0bed0bcd0bdd0b0d1822c20d181d0bdd18fd182d18c20d0bad0b2d0b0d180d182d0b8d180d1832c20d181d0bdd18fd182d18c20d0bad0bed0bcd0bdd0b0d182d1832c20d181d0b4d0b0d0bc20d0bad0b2d0b0d180d182d0b8d180d1832c20d181d0b4d0b0d0bc20d0bad0bed0bcd0bdd0b0d182d1832c20d0bfd0bed181d183d182d0bed187d0bdd0b0d18f20d0b0d180d0b5d0bdd0b4d0b020d0bad0b2d0b0d180d182d0b8d1802c20d0bad0b2d0b0d180d182d0b8d180d18b20d0b220d0b0d180d0b5d0bdd0b4d1832c20d0b0d180d0b5d0bdd0b4d0b020d0bad0b2d0b0d180d182d0b8d18020d0b1d0b5d0b720d0bfd0bed181d180d0b5d0b4d0bdd0b8d0bad0bed0b22c20d0b0d180d0b5d0bdd0b4d0b020d0b1d0b5d0b720d0bfd0bed181d180d0b5d0b4d0bdd0b8d0bad0bed0b2, '1', 'bottom', '2010-12-21 15:31:35'),
(7, 1, 1, 0x41626f7574205573, 0x3c703e3c6120687265663d22687474703a2f2f7777772e70696c6f7467726f75702e6e65742f22207461726765743d225f626c616e6b22206d63655f687265663d22687474703a2f2f7777772e70696c6f7467726f75702e6e65742f223e3c696d67207372633d222e2f75706c6f616465732f656469746f722f796f75725f6c6f676f2e67696622207374796c653d22626f726465723a206d656469756d206e6f6e65203b223e3c2f613e3c2f703e3c62723e0d0a3c703e596f757220706f7374616c20616464726573733c62723e436974792c2053746174652c20506f7374616c20696e6465783c62723e3c62723e57652068617665206275696c742061207374726f6e672072657075746174696f6e20617320616e206f75747374616e64696e67206d6f7274676167652062726f6b6572616765206669726d2073657276696e6720746865206c656e64696e67206e65656473206f66207265616c206573746174652070726f66657373696f6e616c732c206275696c6465727320616e6420696e646976696475616c20686f6d65627579657273207468726f7567686f7574204e6577204a657273657920616e64204d61696e652e3c62723e3c62723e576527726520612066756c6c2073657276696365206d6f7274676167652062726f6b6572207769746820616e20657870657269656e636564207374616666206f66666572696e672065787065727469736520696e2065766572792061726561206f66206d6f727467616765206c656e64696e672e2e2e66726f6d20707572636861736520746f20726566696e616e636520746f20636f6e737472756374696f6e206c656e64696e672c20616e6420616c736f20636f6d6d65726369616c206c656e64696e672e20576520686176652061636365737320746f20612066756c6c2072616e6765206f66206d6f72746761676520736f757263657320616e6420616c6c206f66206f7572206c656e64696e67207370656369616c69737473206172652064656469636174656420746f2066696e64696e6720746865207269676874206c6f616e202d20776974682074686520626573742072617465732c207465726d7320616e6420636f737473202d20746f206d656574206f757220636c69656e74732720756e69717565206e656564732e2042757420746861742773206a7573742074686520626567696e6e696e67206f66206f757220736572766963652e205468726f7567686f757420746865206c656e64696e672070726f636573732077652070726f7669646520726567756c6172206c6f616e207570646174657320616e642070726f6772657373207265706f72747320736f20636c69656e747320616c77617973206b6e6f772074686520737461747573206f66207468656972206c6f616e2e20576520616c736f206f666665722061207370656369616c204d6f727467616765204d616e61676572207365727669636520666f722074686f736520636f6e7369646572696e6720726566696e616e63696e67207468656972206d6f7274676167652e3c62723e3c62723e416e642c206e6f772069742773206f757220706c65617375726520746f206f6666657220616c6c206f66206f757220657863657074696f6e616c206d6f727467616765207365727669636573206f6e6c696e652e205468726f756768206f757220636f6d70616e7920796f75206e6f74206f6e6c7920686176652061636365737320746f207468652062657374206c6f616e7320617661696c61626c6520696e20746865206d61726b6574706c6163652c2062757420796f752063616e20726576696577206c6f616e20616c7465726e6174697665732c20616e64206576656e206170706c7920666f7220796f7572206c6f616e2c20617420796f757220636f6e76656e69656e63652c206f6e6c696e65202d20323420686f7572732061206461792e3c62723e3c62723e5468616e6b20796f7520666f72207669736974696e67206f75722077656220736974652e205765206c6f6f6b20666f727761726420746f2070757474696e67206f7572206d6f727467616765207365727669636520746f20776f726b20666f7220796f75213c666f6e7420636f6c6f723d2223383038303830222073697a653d22312220666163653d225461686f6d61223e203c2f666f6e743e3c2f703e, 0x41626f7574205573, 0x7265616c206573746174652c207265616c746f722c20686f6d657320666f722073616c652c20686f75736520666f722073616c652c2066696e64206120686f6d652c207265616c20657374617465206c697374696e67732c20636f6e646f732c20746f776e686f6d65732c207265616c6573746174652c2070726f70657274792c206d6c732c2073656172636820686f6d65732c206c656173652c2072656e742c2072656e74616c206c697374696e67, '1', 'bottom', '2008-07-11 18:03:16');
INSERT INTO `re_info_section` (`id`, `language_id`, `sequence`, `caption`, `content`, `description`, `keywords`, `status`, `menu_position`, `update_date`) VALUES
(8, 1, 2, 0x54657374696d6f6e69616c73, 0x3c703e496620796f7520617265206c6f6f6b696e6720746f2074616b652074686520737472657373206f7574206f662073656c6c696e67206f7220627579696e6720796f752063616e2072656c79206f6e207468697320636f6d70616e792e204f757220726563656e7420657870657269656e6365207468726f756768207468656d2077617320736f20656173792c2074686579206c697374656e2c2061637420616e6420776f726b207769746820746865697220636c69656e7473206173206f6e65207465616d2e3c62723e3c7370616e207374796c653d22666f6e742d73697a653a20382e3570743b20666f6e742d66616d696c793a20275461686f6d61272c2773616e732d7365726966273b223e3c656d3e57696c6c6977204c2e2c20466c6f726964613c2f656d3e3c2f7370616e3e203c2f703e3c62723e0d0a3c703e492063616d65206163726f7373207468697320736572766963652071756974652072616e646f6d6c79206f6e2074686520696e7465726e657420616e6420636f6e746163746564207468656d20726567617264696e6720746865207075726368617365206f662061207665727920737065636966696320636f6e646f20646f776e746f776e2e2054686520636f6d70616e792069732065787472656d656c792070726f66657373696f6e616c2c20696e666f726d617469766520616e64206769766520746865697220686f6e657374206f70696e696f6e73207768656e2061736b65642e204920776f756c64207265636f6d6d656e64207468656d20746f20616e796f6e652e203c62723e3c7370616e207374796c653d22666f6e742d73697a653a20382e3570743b20666f6e742d66616d696c793a20275461686f6d61272c2773616e732d7365726966273b223e3c656d3e546f7279204d2e2c204d6973736f7572693c2f656d3e3c2f7370616e3e203c2f703e3c62723e0d0a3c703e576520666f756e6420796f7572207365727669636520746f206265207665727920686f6e65737420616e642072656c6961626c652e20596f75207765726520616c736f207665727920617070726f61636861626c652c206d616b696e6720796f757273656c6620617661696c61626c652065697468657220696e20706572736f6e206f722062792070686f6e6520746f20616e7377657220616e79206f66206f757220646f75627473206f72207175657269657320647572696e67207468652073616c652070726f636573732e204e6f7468696e67207365656d656420746f20626520746f6f206d7563682074726f75626c652e205468616e6b20796f7520666f722064656c69766572696e672073756368206120667269656e646c7920616e642070726f66657373696f6e616c207365727669636520746861742061742074696d65732077656e742061626f766520616e64206265796f6e64206f7572206578706563746174696f6e732e203c62723e3c7370616e207374796c653d22666f6e742d73697a653a20382e3570743b20666f6e742d66616d696c793a20275461686f6d61272c2773616e732d7365726966273b223e3c656d3e54686f6d6173204a2e2c2043616c69666f726e69613c2f656d3e3c2f7370616e3e3c2f703e, 0x54657374696d6f6e69616c73, 0x7265616c206573746174652c207265616c746f722c20686f6d657320666f722073616c652c20686f75736520666f722073616c652c2066696e64206120686f6d652c207265616c20657374617465206c697374696e67732c20636f6e646f732c20746f776e686f6d65732c207265616c6573746174652c2070726f70657274792c206d6c732c2073656172636820686f6d65732c206c656173652c2072656e742c2072656e74616c206c697374696e67, '1', 'bottom', '2008-07-11 17:39:40'),
(9, 1, 3, 0x5072697661637920506f6c696379, 0x3c703e3c623e5072697661637920506f6c6963793c2f623e3c2f703e3c62723e0d0a3c703e5765207265737065637420616e642061726520636f6d6d697474656420746f2070726f74656374696e6720796f757220707269766163792e20546861742069732077687920776520686176652061646f707465642074686973205072697661637920506f6c6963792e2054686973205072697661637920506f6c696379206c65747320796f75206b6e6f7720686f7720796f757220706572736f6e616c20696e666f726d6174696f6e2069732070726f63657373656420616e64206578706c61696e73206461746120636f6c6c656374696f6e20616e64207573652070726163746963657320666f7220596f757220436f6d70616e7920736974652f6e6574776f726b2e205468652022596f757220436f6d70616e7920736974652f6e6574776f726b2220696e636c7564657320616c6c205765622070616765732c206e6577736c6574746572732c2064697363757373696f6e20666f72756d7320616e64206c6973747320616e64206f70742d696e20616e6e6f756e63656d656e74206c69737473206f776e656420616e64206f7065726174656420627920596f757220436f6d70616e7920616e642065616368206f66206974732077686f6c6c792d6f776e6564207375627369646961726965732e20427920616363657373696e6720596f757220436f6d70616e7920736974652f6e6574776f726b2c20796f752061726520636f6e73656e74696e6720746f2074686520696e666f726d6174696f6e20636f6c6c656374696f6e20616e6420757365207072616374696365732064657363726962656420696e2074686973205072697661637920506f6c6963792e3c2f703e0d0a3c62723e0d0a3c703e57652070726f6d69736520746861742077652077696c6c2075736520796f757220706572736f6e616c20696e666f726d6174696f6e206f6e6c7920696e207761797320746861742061726520636f6d70617469626c6520776974682074686973205072697661637920506f6c69637920617320666f6c6c6f77733a3c2f703e0d0a3c62723e0d0a3c703e3c623e5768617420696e666f726d6174696f6e2061726520796f7520636f6c6c656374696e6720616e6420686f772061726520796f7520636f6c6c656374696e672069743f203c2f623e3c2f703e0d0a3c62723e0d0a3c703e3c623e446f20796f7520636f6c6c656374204950206164647265737365733f3c2f623e3c2f703e0d0a3c62723e0d0a3c703e457665727920636f6d707574657220636f6e6e656374656420746f2074686520496e7465726e657420697320676976656e206120646f6d61696e206e616d6520616e64206120736574206f66206e756d626572732c2074686174207365727665206173207468617420636f6d707574657227732022496e7465726e65742050726f746f636f6c2220495020616464726573732e205768656e20612076697369746f72207265717565737473206120706167652066726f6d20616e792057656220736974652077697468696e2074686520594f555220434f4d50414e5920534954452f4e4554574f524b2c206f7572205765622073657276657273206175746f6d61746963616c6c79207265636f676e697a6520746861742076697369746f72277320646f6d61696e206e616d6520616e6420495020616464726573732e2054686520646f6d61696e206e616d6520616e6420495020616464726573732072657665616c206e6f7468696e6720706572736f6e616c2061626f757420796f75206f74686572207468616e2074686520495020616464726573732066726f6d20776869636820796f752068617665206163636573736564206f75722053697465732e20576520757365207468697320696e666f726d6174696f6e20746f206578616d696e65206f7572207472616666696320696e206167677265676174652c20616e6420746f20696e766573746967617465206d6973757365206f662074686520594f555220434f4d50414e5920534954452f4e4554574f524b2c206974732075736572732c206f7220746f20636f6f7065726174652077697468206c617720656e666f7263656d656e742e20576520646f206e6f7420636f6c6c65637420616e64206576616c75617465207468697320696e666f726d6174696f6e20666f7220737065636966696320696e646976696475616c732e204f757220576562207365727665727320646f206e6f74206175746f6d61746963616c6c79207265636f726420652d6d61696c20616464726573736573206f66207468652076697369746f72732e3c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e576861742061726520636f6f6b6965733f3c2f623e3c2f703e0d0a3c62723e0d0a3c703e46726f6d2074696d6520746f2074696d652c20594f555220434f4d50414e5920534954452f4e4554574f524b206f7220697473206164766572746973657273206d61792073656e6420612022636f6f6b69652220746f20796f757220636f6d70757465722e204120636f6f6b6965206973206120736d616c6c207069656365206f66206461746120746861742069732073656e7420746f20796f757220496e7465726e65742062726f777365722066726f6d2061205765622073657276657220616e642073746f726564206f6e20796f757220636f6d7075746572277320686172642064726976652e204120636f6f6b69652063616e277420726561642064617461206f6666206f6620796f757220636f6d70757465722068617264206469736b206f72207265616420636f6f6b69652066696c65732063726561746564206279206f74686572205765622073697465732e20436f6f6b69657320646f206e6f742064616d61676520796f75722073797374656d2e2057652075736520636f6f6b69657320746f206964656e74696679207768696368206172656173206f6620594f555220434f4d50414e5920534954452f20796f7520686176652076697369746564206f7220637573746f6d697a65642c20736f20746865206e6578742074696d6520796f752076697369742c2074686f7365207061676573206d61792062652072656164696c792061636365737369626c652e204f7572206164766572746973657273206d617920616c736f2075736520636f6f6b69657320746f2061736365727461696e20686f77206d616e792074696d657320796f75277665207365656e20616e206164766572746973656d656e742e204f757220616476657274697365727320616e64207765206d617920757365207468697320696e666f726d6174696f6e20746f2062657474657220706572736f6e616c697a652074686520636f6e74656e742c2062616e6e6572732c20616e642070726f6d6f74696f6e73207468617420796f7520736565206f6e206f757220536974652e20596f752063616e2063686f6f7365207768657468657220746f2061636365707420636f6f6b696573206279206368616e67696e67207468652073657474696e6773206f6620796f757220496e7465726e65742062726f777365722e20596f752063616e20726573657420796f75722062726f7773657220746f2072656675736520616c6c20636f6f6b6965732c206f7220616c6c6f7720796f75722062726f7773657220746f2073686f7720796f75207768656e206120636f6f6b6965206973206265696e672073656e742e20496620796f752063686f6f7365206e6f7420746f2061636365707420746865736520636f6f6b6965732c20796f757220657870657269656e6365206174206f7572205369746520616e64206f7468657220576562207369746573206d61792062652064696d696e697368656420616e6420736f6d65206665617475726573206d6179206e6f7420776f726b20617320696e74656e6465642e203c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e486f7720646f20796f75207573652073696e676c652d706978656c20676966733f203c2f623e3c2f703e0d0a3c62723e0d0a3c703e594f555220434f4d50414e5920534954452f4e4554574f524b20616e6420697473206164766572746973657273206d6179207573652073696e676c652d706978656c2067696620696d616765732c20736f6d6574696d657320726566657272656420746f206173207765622062756773206f722077656220626561636f6e732c20746f20636f756e7420576562207061676520616363657373657320616e642067617468657220736f6d652067656e6572616c20737461746973746963616c20696e666f726d6174696f6e2e20594f555220434f4d50414e5920534954452f4e4554574f524b20646f6573206e6f742067617468657220706572736f6e616c20696e666f726d6174696f6e207468726f7567682074686520757365206f6620746865736520696d616765732e204f7572206164766572746973657273206d6179207573652073696e676c652d706978656c206769667320746861742063616e20747261636b20706572736f6e616c20696e666f726d6174696f6e2066726f6d20636f6f6b6965732074686174206d61792068617665206265656e2070726576696f75736c7920706c61636564206f6e20612075736572277320636f6d707574657220627920616e20616476657274697365722e203c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e5768617420706572736f6e616c20696e666f726d6174696f6e20646f20796f7520636f6c6c6563743f3c2f623e3c2f703e0d0a3c62723e0d0a3c703e576865726576657220594f555220434f4d50414e5920534954452f4e4554574f524b20636f6c6c6563747320706572736f6e616c20696e666f726d6174696f6e207765206d616b6520616e206566666f727420746f20696e636c7564652061206c696e6b20746f2074686973205072697661637920506f6c6963792e2057652077696c6c2061736b20796f75207768656e207765206e65656420696e666f726d6174696f6e207468617420706572736f6e616c6c79206964656e74696669657320796f752028706572736f6e616c20696e666f726d6174696f6e29206f7220616c6c6f777320757320746f20636f6e7461637420796f752e2054686520706572736f6e616c20696e666f726d6174696f6e20776520636f6c6c656374206d617920696e636c75646520796f7572206e616d652c207469746c652c20636f6d70616e79206f72206f7267616e697a6174696f6e206e616d652c20652d6d61696c20616464726573732c2070686f6e65206e756d6265722c20776f726b206f7220686f6d6520616464726573732c20696e666f726d6174696f6e2061626f757420796f7572206a6f622066756e6374696f6e2c20696e666f726d6174696f6e2061626f757420796f757220636f6d70616e792c20616e6420637265646974206361726420696e666f726d6174696f6e2e203c2f703e0d0a3c703e5765206d6179207265717565737420796f757220652d6d61696c2061646472657373206f72206d61696c696e67206164647265737320666f722074686520707572706f736573206f6620636f6e64756374696e67206120636f6e74657374206f7220746f2070726f76696465206164646974696f6e616c2073657276696365732028666f72206578616d706c652c20737562736372697074696f6e7320746f20616e6e6f756e63656d656e74206c69737473206f7220696e666f726d6174696f6e2061626f757420636f6e666572656e63657320616e642074726164652073686f7773292e2050617274696369706174696f6e20696e20636f6e7465737473206f72206f746865722070726f6d6f74696f6e73206f6e2074686520594f555220434f4d50414e5920534954452f4e4554574f524b204e6574776f726b20697320636f6d706c6574656c7920766f6c756e7461727920616e6420796f75206861766520612063686f6963652077686574686572206f72206e6f7420746f20646973636c6f736520706572736f6e616c20696e666f726d6174696f6e2e20496e666f726d6174696f6e20726571756573746564206d617920696e636c75646520636f6e7461637420696e666f726d6174696f6e202873756368206173206e616d6520616e64207368697070696e672061646472657373292c20616e642064656d6f6772617068696320696e666f726d6174696f6e202873756368206173207a697020636f6465292e20436f6e7461637420696e666f726d6174696f6e2077696c6c206265207573656420746f206e6f74696679207468652077696e6e65727320616e64206177617264207072697a65732e203c2f703e0d0a3c703e496620796f75206172652070757263686173696e6720736f6d657468696e67206f6e2074686520594f555220434f4d50414e5920534954452f4e4554574f524b2c2077652c20616e6420616e792074686972642070617274792070726f76696465722c206e65656420746f206b6e6f7720796f7572206e616d652c20652d6d61696c20616464726573732c206d61696c696e6720616464726573732c206372656469742063617264206e756d62657220616e642065787069726174696f6e20646174652e205468697320616c6c6f77732075732c206f722074686972642070617274792070726f7669646572732c20746f2070726f6365737320616e642066756c66696c6c20796f7572206f7264657220616e6420746f206e6f7469667920796f75206f6620796f7572206f72646572207374617475732e20437265646974206361726420616e6420652d6d61696c20696e666f726d6174696f6e2077696c6c206e6f7420626520736861726564206f7220736f6c6420746f207468697264207061727469657320666f7220616e7920707572706f7365206578636570742061732070726f766964656420696e2074686973205072697661637920506f6c69637920776974686f757420796f75722065787072657373207065726d697373696f6e2e203c2f703e0d0a3c703e594f555220434f4d50414e5920534954452f4e4554574f524b20616c736f206f66666572732061636365737320746f20736f6d652073657276696365732074686174207265717569726520726567697374726174696f6e2e205765206d61792075736520796f757220726567697374726174696f6e20636f6e7461637420696e666f726d6174696f6e20746f2073656e64206e6f74696669636174696f6e20746f20796f75206f662070726f647563747320616e6420736572766963657320627920652d6d61696c20616e642f6f7220706f7374616c206d61696c2e205765206d6179207368617265207468617420696e666f726d6174696f6e2077697468206f757220636c69656e747320616e642074686579206d617920616c736f2073656e6420796f752070726f6d6f74696f6e616c206d6174657269616c732e205765206d617920616c736f2075736520796f7572204950206164647265737320696e7465726e616c6c7920616e64207368617265206974207769746820746869726420706172746965732e203c2f703e0d0a3c703e5768656e20796f75207369676e20757020666f72206120594f555220434f4d50414e5920534954452f4e4554574f524b20652d6d61696c206e6577736c65747465722c20796f752061726520676976656e20746865206f70706f7274756e69747920746f207369676e20757020666f722074617267657465642074686972642d7061727479206d61696c696e67732e2057686574686572206f72206e6f7420796f752077616e7420746f207265636569766520746865736520616e6e6f756e63656d656e747320697320796f75722063686f6963652e20496620796f752063686f6f736520746f207265636569766520616e20616e6e6f756e63656d656e742066726f6d206120737065636966696320646973747269627574696f6e206c6973742c20796f752077696c6c207265636569766520636f6d6d65726369616c206d657373616765732066726f6d20696e646570656e64656e7420746869726420706172746965732074686174206861766520636f6e74726163746564207769746820594f555220434f4d50414e5920534954452f4e4554574f524b20746f2073656e64206d6573736167657320746f20746865206c6973742e20596f75206d61792073746f7020746865206172726976616c206f6620746865736520616e6e6f756e63656d656e74206c6973747320617420616e792074696d652062792072656d6f76696e6720796f757220652d6d61696c20616464726573732066726f6d20746865206c69737420627920666f7277617264696e672074686520616e6e6f756e63656d656e74206c69737420746f20616e6e6f756e63656d656e746c69737440796f7572636f6d70616e792e636f6d206f7220627920666f6c6c6f77696e672074686520696e737472756374696f6e73206f6e2074686520652d6d61696c7320796f7520726563656976652e203c2f703e0d0a3c703e5768656e20796f75207369676e20757020666f72206120594f555220434f4d50414e5920534954452f4e4554574f524b2064697363757373696f6e20666f72756d2c20796f7572206e616d65206f7220616c696173206973207265636f7264656420736f6c656c7920666f7220707572706f736573206f66206d61696e7461696e696e6720796f7572206f776e206163636f756e742077697468696e2074686520666f72756d732e205468697320696e666f726d6174696f6e206973206e6f74207573656420746f206d6f6e69746f7220796f75722061637469766974792077697468696e206120666f72756d2c206e6f72206973206974207573656420746f206964656e7469667920796f75206f7574736964652074686520594f555220434f4d50414e5920534954452f4e4554574f524b2e203c2f703e0d0a3c703e5768656e20796f752075736520636f2d6272616e6465642073657276696365732c206f722074686f73652070726f766964656420627920612074686972642070617274792c2077697468696e2074686520594f555220434f4d50414e5920534954452f4e4554574f524b2c20796f757220696e666f726d6174696f6e206d61792062652070617373656420746f2074686520636f2d6272616e64206f722074686972642070617274792070726f766964657220616e6420746865697220757365206f6620796f757220696e666f726d6174696f6e206973207375626a65637420746f207468656972206170706c696361626c65207072697661637920706f6c69636965732e203c2f703e0d0a3c703e5765206d61792066726f6d2074696d6520746f2074696d652073656e6420652d6d61696c20616e6e6f756e63696e67206e65772070726f647563747320616e642073657276696365732e20496620796f752063686f6f736520746f20737570706c7920796f757220706f7374616c206164647265737320696e20616e206f6e6c696e6520666f726d2c20796f75206d6179207265636569766520706f7374616c206d61696c696e67732066726f6d20594f555220434f4d50414e5920534954452f4e4554574f524b206f7220746869726420706172747920636f6d70616e6965732e203c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e57696c6c20796f7520646973636c6f73652074686520696e666f726d6174696f6e20796f7520636f6c6c65637420746f206f75747369646520746869726420706172746965733f3c2f623e3c2f703e0d0a3c62723e0d0a3c703e594f555220434f4d50414e5920534954452f4e4554574f524b2077696c6c20646973636c6f736520706572736f6e616c20696e666f726d6174696f6e20616e642f6f7220616e20495020616464726573733a203c2f703e0d0a3c6f6c3e0d0a3c6c693e546f20636f6e7461637420796f7520726567617264696e67206f746865722070726f647563747320616e64207365727669636573207768696368206d6179206265206f6620696e74657265737420746f20796f752028696e636c7564696e672074686f7365207765206d6179206f66666572206a6f696e746c792077697468206f7468657220636f6d70616e696573292e20417420616e792074696d652c20686f77657665722c20796f75206d6179206f7074206f7574206f66207375636820636f6e746163743b203c2f6c693e0d0a3c6c693e5768656e207265717569726564206279206c6177206f7220696e2074686520676f6f642d66616974682062656c6965662074686174207375636820616374696f6e206973206e656365737361727920746f3a203c2f6c693e0d0a3c756c3e0d0a3c6c693e436f6f70657261746520776974682074686520696e7665737469676174696f6e73206f6620707572706f7274656420756e6c617766756c206163746976697469657320616e6420636f6e666f726d20746f2074686520656469637473206f6620746865206c6177206f7220636f6d706c792077697468206c6567616c2070726f6365737320736572766564206f6e20594f555220434f4d50414e5920534954452f4e4554574f524b3b203c2f6c693e0d0a3c6c693e50726f7465637420616e6420646566656e642074686520726967687473206f722070726f7065727479206f662074686520594f555220434f4d50414e5920534954452f4e4554574f524b206f6620736974657320616e642072656c617465642070726f706572746965732c206f722076697369746f727320746f2074686520594f555220434f4d50414e5920534954452f4e4554574f524b206f6620736974657320616e642072656c617465642070726f706572746965733b203c2f6c693e0d0a3c6c693e4964656e7469667920706572736f6e732077686f206d61792062652076696c696174696e6720746865206c61772c2074686520594f555220434f4d50414e5920534954452f4e4554574f524b206c6567616c206e6f7469636520616e6420576562207369746520557365722041677265656d656e742c2074686520726967687473206f6620746869726420706172746965732c206f72206f7468657277697365206d69737573696e672074686520594f555220434f4d50414e5920534954452f4e4554574f524b206f72206974732072656c617465642070726f706572746965733b203c2f6c693e0d0a3c6c693e46756c66696c6c206120757365722773206f72646572206f7220726571756573743b203c2f6c693e3c2f756c3e0d0a3c6c693e546f2070726f6365737320616e642066756c66696c6c20796f7572206f72646572206f72206e6f7469667920796f75206f66206f72646572207374617475733b203c2f6c693e0d0a3c6c693e5768656e207765206861766520796f757220636f6e73656e7420746f2073686172652074686520696e666f726d6174696f6e3b203c2f6c693e0d0a3c6c693e5768656e20796f752075736520636f2d6272616e6465642073657276696365732077697468696e206f7572206e6574776f726b206f662073697465732c20796f75206772616e74207573207065726d697373696f6e20746f207061737320796f757220726567697374726174696f6e20696e666f726d6174696f6e206261636b20746f2074686174207365727669636520706172746e65722e20546865697220757365206f6620796f757220696e666f726d6174696f6e206973207375626a65637420746f207468656972206170706c696361626c65207072697661637920706f6c69636965732e203c2f6c693e3c2f6f6c3e0d0a3c703e5765206d61696e7461696e20612073747269637420224e6f2d5370616d2220706f6c6963792074686174206d65616e73207468617420776520646f206e6f742073656c6c2c2072656e742c206f72206f7468657277697365206769766520796f757220652d6d61696c206164647265737320746f20612074686972642d70617274792c20776974686f757420796f757220636f6e73656e74206f72206173207065726d69747465642062792074686973205072697661637920506f6c6963792e203c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e5768617420656c73652073686f756c642049206b6e6f772061626f7574206d792070726976616379207768656e206f6e6c696e653f3c2f623e3c2f703e0d0a3c62723e0d0a3c703e54686520594f555220434f4d50414e5920534954452f4e4554574f524b20636f6e7461696e73206d616e792068797065726c696e6b7320746f207468697264207061727479205765622073697465732e2054686520594f555220434f4d50414e5920534954452f4e4554574f524b20616c736f20636f6e7461696e73206164766572746973656d656e7473206f6620746869726420706172746965732e20594f555220434f4d50414e5920534954452f4e4554574f524b206973206e6f7420726573706f6e7369626c6520666f7220746865207072697661637920707261637469636573206f722074686520636f6e74656e74206f66207375636820746869726420706172747920576562207369746573206f722061647665727469736572732e20594f555220434f4d50414e5920534954452f4e4554574f524b20646f6573206e6f7420736861726520616e79206f662074686520696e646976696475616c20706572736f6e616c20696e666f726d6174696f6e20796f752070726f7669646520594f555220434f4d50414e5920534954452f4e4554574f524b207769746820746865207468697264207061727469657320746f20776869636820594f555220434f4d50414e5920534954452f4e4554574f524b206c696e6b732c206578636570742061732073746174656420656c736577686572652077697468696e2074686973205072697661637920506f6c6963792c20616c74686f75676820594f555220434f4d50414e5920534954452f4e4554574f524b206d61792073686172652067656e6572616c2c20616767726567617465206461746120776974682073756368207468697264207061727469657320287375636820617320686f77206d616e792070656f706c6520757365206f75722053697465292e20506c6561736520636865636b20776974682074686f73652057656220736974657320746f2064657465726d696e65207468656972207072697661637920706f6c6963792e203c2f703e0d0a3c703e496620796f752075736520594f555220434f4d50414e5920534954452f4e4554574f524b27732064697363757373696f6e20746872656164732c20796f752073686f756c64206265206177617265207468617420616e7920706572736f6e616c6c79206964656e7469666961626c6520696e666f726d6174696f6e20796f75207375626d69742074686572652063616e20626520726561642c20636f6c6c65637465642c206f722075736564206279206f74686572207573657273206f6620746865736520666f72756d732c20616e6420636f756c64206265207573656420746f2073656e6420796f7520756e736f6c696369746564206d657373616765732e20576520617265206e6f7420726573706f6e7369626c6520666f722074686520706572736f6e616c6c79206964656e7469666961626c6520696e666f726d6174696f6e20796f752063686f6f736520746f207375626d697420696e2074686573652064697363757373696f6e20746872656164732e20496e206f7264657220746f2061766f696420796f757220652d6d61696c2061646472657373206f72206f7468657220706572736f6e616c20696e666f726d6174696f6e2066726f6d206265696e6720676174686572656420616e642075736564206279206f746865727320666f7220696e617070726f707269617465206f72206861726d66756c20707572706f7365732c20594f555220434f4d50414e5920534954452f4e4554574f524b2061647669736573207468617420796f752073686f756c642062652063617574696f75732061626f757420706f7374696e672061207265616c20652d6d61696c2061646472657373206f72206f7468657220706572736f6e616c20696e666f726d6174696f6e20746f206e65777367726f7570732c2063686174732c206f72206f74686572207075626c696320666f72756d732e203c2f703e0d0a3c703e506c65617365206b65657020696e206d696e642074686174207768656e6576657220796f7520766f6c756e746172696c7920646973636c6f736520706572736f6e616c20696e666f726d6174696f6e206f6e6c696e65202d20666f72206578616d706c65207468726f75676820652d6d61696c2c2064697363757373696f6e206c697374732c206f7220656c73657768657265202d207468617420696e666f726d6174696f6e2063616e20626520636f6c6c656374656420616e642075736564206279206f74686572732e20496e2073686f72742c20696620796f7520706f737420706572736f6e616c20696e666f726d6174696f6e206f6e6c696e6520746861742069732061636365737369626c6520746f20746865207075626c69632c20796f75206d6179207265636569766520756e736f6c696369746564206d657373616765732066726f6d206f74686572207061727469657320696e2072657475726e2e203c2f703e0d0a3c703e4365727461696e20594f555220434f4d50414e5920534954452f4e4554574f524b206d656469612070726f706572746965732075736520612073686f7070696e672063617274206665617475726520746861742073616665677561726473207468697320696e666f726d6174696f6e206279207573696e6720696e647573747279207374616e646172642053534c202853656375726520536f636b6574204c617965722920656e6372797074656420736572766572732e2053534c20636f6465732074686520696e666f726d6174696f6e207472616e73666572726564206265747765656e20796f7520616e642074686520732065727665722c2072656e646572696e6720697420756e7265616461626c6520746f20616e796f6e6520747279696e6720746f20696e746572636570742074686520696e666f726d6174696f6e2e204f7468657220594f555220434f4d50414e5920534954452f4e4554574f524b206d656469612070726f7065727469657320646f206e6f74207573652053534c20616e64207468657265627920646f206e6f74206f6666657220612073656375726520636f6465642077617920746f207472616e7366657220696e666f726d6174696f6e2e203c2f703e0d0a3c703e556c74696d6174656c792c20796f752061726520736f6c656c7920726573706f6e7369626c6520666f72206d61696e7461696e696e67207468652073656372656379206f6620796f757220706572736f6e616c20696e666f726d6174696f6e2e20506c65617365206265206361726566756c20616e6420726573706f6e7369626c65207768656e6576657220796f75277265206f6e6c696e652e203c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e427573696e657373207472616e73666572733c2f623e3c2f703e0d0a3c62723e0d0a3c703e57652061726520616c77617973207365656b696e6720746f20696d70726f7665206f7572206e6574776f726b206f662057656220736974657320616e64206f757220627573696e6573732e20496e206163636f6d706c697368696e67207468657365206f626a656374697665732c207765206d61792073656c6c206120636f6d70616e792c20617373657473206f72207765627369746573206f72206f6e65206f72206d6f7265206f662074686520636f6d70616e69657320696e206f757220636f72706f726174652066616d696c79206d6179206d6572676520776974682c206f722062652061637175697265642062792c20616e6f7468657220636f6d70616e792e20496e20636f6e6e656374696f6e20776974682074686520666f7265676f696e672c207765206d6179207472616e7366657220736f6d65206f7220616c6c206f6620796f757220696e666f726d6174696f6e20696e206f726465722074686174207468652073657276696365206265696e672070726f766964656420746f20796f75206d617920636f6e74696e7565206f7220666f72206f7468657220627573696e65737320707572706f7365732e203c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e596f757220436f6e73656e7420546f20546869732041677265656d656e743c2f623e3c2f703e0d0a3c62723e0d0a3c703e4279207573696e672074686520594f555220434f4d50414e5920534954452f4e4554574f524b204e6574776f726b2c20796f7520636f6e73656e7420746f2074686520636f6c6c656374696f6e20616e6420757365206f6620696e666f726d6174696f6e20627920594f555220434f4d50414e5920534954452f4e4554574f524b206173207370656369666965642061626f76652e20576520726573657276652074686520726967687420746f206d6f64696679207468697320506f6c6963792e2049662077652064656369646520746f206368616e6765206f7572205072697661637920506f6c6963792c2077652077696c6c20706f73742074686f7365206368616e676573206f6e2074686973207061676520736f207468617420796f752061726520616c77617973206177617265206f66207768617420696e666f726d6174696f6e20776520636f6c6c6563742c20686f77207765207573652069742c20616e6420756e64657220776861742063697263756d7374616e63657320776520646973636c6f73652069742e203c2f703e0d0a3c703e506c656173652073656e6420616e79207175657374696f6e732061626f757420594f555220434f4d50414e5920534954452f4e4554574f524b2773205072697661637920506f6c69637920746f3a203c6120687265663d226d61696c746f3a7072697661637940796f7572636f6d70616e792e636f6d22206d63655f687265663d226d61696c746f3a7072697661637940796f7572636f6d70616e792e636f6d223e7072697661637940796f7572636f6d70616e792e636f6d3c2f613e203c2f703e0d0a3c703e506c65617365206e6f7465207468617420796f757220757365206f662074686520594f555220434f4d50414e5920534954452f4e4554574f524b206f662057656220736974657320697320676f7665726e6564206279206f7572204c6567616c204e6f746963652e203c2f703e, 0x5072697661637920506f6c696379, 0x7265616c206573746174652c207265616c746f722c20686f6d657320666f722073616c652c20686f75736520666f722073616c652c2066696e64206120686f6d652c207265616c20657374617465206c697374696e67732c20636f6e646f732c20746f776e686f6d65732c207265616c6573746174652c2070726f70657274792c206d6c732c2073656172636820686f6d65732c206c656173652c2072656e742c2072656e74616c206c697374696e67, '1', 'bottom', '2008-07-11 17:39:49'),
(10, 1, 4, 0x5465726d73206f6620757365, 0x3c703e3c623e416363657074616e6365206f66205465726d73206f66205573652e203c2f623e3c2f703e0d0a3c62723e0d0a3c703e495420495320494d504f5254414e54205448415420594f55205245414420414c4c20544845205445524d5320414e4420434f4e444954494f4e53204341524546554c4c592e205265616c74792050726f20285265616c74792050726f29206f776e7320616e64206f706572617465732074686973205765622c206c6f636174656420617420687474703a2f2f7265616c7479736f66742e70726f2028746865205765622073697465292e2054686973205465726d73206f66205573652041677265656d656e742028746869732041677265656d656e74292073746174657320746865207465726d7320616e6420636f6e646974696f6e7320756e64657220776869636820796f75206d61792061636365737320616e6420757365207468652057656220736974652e20427920616363657373696e6720616e64207573696e672074686520576562207369746520796f752061726520696e6469636174696e6720796f757220616363657074616e636520746f20626520626f756e6420627920746865207465726d7320616e6420636f6e646974696f6e73206f6620746869732041677265656d656e742e20496620796f7520646f206e6f7420616363657074207468657365207465726d7320616e6420636f6e646974696f6e732c20796f75206d757374206e6f7420616363657373206f7220757365207468652057656220736974652e20494620594f552041524520444953534154495346494544205749544820544845205445524d532c20434f4e444954494f4e532c2052554c45532c20504f4c49434945532c2047554944454c494e4553204f5220505241435449434553204f4620544845205265616c74792050726f204f5045524154494e472057454220534954452c20594f555220534f4c4520414e44204558434c55534956452052454d45445920495320544f20444953434f4e54494e5545205553494e472049542e205265616c74792050726f206d61792072657669736520746869732041677265656d656e7420617420616e792074696d65206279207570646174696e67207468697320706f7374696e672e20557365206f66207468652057656220736974652061667465722073756368206368616e6765732061726520706f737465642077696c6c207369676e69667920796f75722061677265656d656e7420746f2074686573652072657669736564207465726d732e20596f752073686f756c642076697369742074686973207061676520706572696f646963616c6c7920746f2072657669657720746869732041677265656d656e742e3c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e4f776e6572736869702e3c2f623e3c2f703e0d0a3c62723e0d0a3c703e416c6c206d6174657269616c7320646973706c61796564206f72206f74686572776973652061636365737369626c65207468726f756768207468652057656220736974652c20696e636c7564696e672c20776974686f7574206c696d69746174696f6e2c206e6577732061727469636c65732c20746578742c2070686f746f6772617068732c20696d616765732c20696c6c757374726174696f6e732c20617564696f20636c6970732c20766964656f20636c6970732c20636f6d707574657220736f66747761726520616e6420636f64652028636f6c6c6563746976656c792c2074686520436f6e74656e7429206172652070726f74656374656420756e646572206c6f63616c20616e6420666f726569676e20636f70797269676874206f72206f74686572206c6177732c20616e6420617265206f776e6564206279205265616c74792050726f2c20697473206c6963656e736f7273206f72207468652070617274792061636372656469746564206173207468652070726f7669646572206f662074686520436f6e74656e742e20496e206164646974696f6e2c207468652057656220736974652069732070726f74656374656420756e64657220636f70797269676874206c6177206173206120636f6c6c65637469766520776f726b20616e642f6f7220636f6d70696c6174696f6e207075727375616e7420746f204c6f63616c20616e6420666f726569676e206c6177732e20596f75207368616c6c20616269646520627920616c6c206164646974696f6e616c20636f70797269676874206e6f74696365732c20696e666f726d6174696f6e20616e64207265737472696374696f6e73206f6e206f7220636f6e7461696e656420696e20616e79206f662074686520436f6e74656e74206163636573736564207468726f756768207468652057656220736974652e20414e59205553452c20524550524f44554354494f4e2c20414c5445524154494f4e2c204d4f44494649434154494f4e2c205055424c494320504552464f524d414e4345204f5220444953504c41592c2055504c4f4144494e47204f5220504f5354494e47204f4e544f2054484520494e5445524e45542c205452414e534d495353494f4e2c205245444953545249425554494f4e204f52204f54484552204558504c4f49544154494f4e204f46205448452057454253495445204f52204f4620414e5920434f4e54454e542c205748455448455220494e2057484f4c45204f5220494e20504152542c204f54484552205448414e20455850524553534c5920534554204f55542048455245494e2c2049532050524f4849424954454420574954484f5554205448452045585052455353205752495454454e205045524d495353494f4e204f46205265616c74792050726f2e203c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e54726164656d61726b732e203c2f623e3c2f703e0d0a3c62723e0d0a3c703e4c4f43414c2042524f414443415354494e4720434f52504f524154494f4e2c2044455349474e20617265206f6666696369616c206d61726b73206f722074726164656d61726b73206f6620746865204c6f63616c2042726f616463617374696e6720436f72706f726174696f6e2e204f74686572206e616d65732c20776f7264732c207469746c65732c20706872617365732c206c6f676f732c2064657369676e732c2067726170686963732c2069636f6e7320616e642074726164656d61726b7320646973706c61796564206f6e20746865205765622073697465206d617920636f6e737469747574652072656769737465726564206f7220756e726567697374657265642074726164656d61726b73206f66205265616c74792050726f206f7220746869726420706172746965732e205768696c65206365727461696e2074726164656d61726b73206f662074686972642070617274696573206d61792062652075736564206279205265616c74792050726f20756e646572206c6963656e73652c2074686520646973706c6179206f662074686972642d70617274792074726164656d61726b73206f6e207468652057656220736974652073686f756c64206e6f742062652074616b656e20746f20696d706c7920616e792072656c6174696f6e73686970206f72206c6963656e7365206265747765656e205265616c74792050726f20616e6420746865206f776e6572206f6620736169642074726164656d61726b206f7220746f20696d706c792074686174205265616c74792050726f20656e646f72736573207468652077617265732c207365727669636573206f7220627573696e657373206f6620746865206f776e6572206f6620736169642074726164656d61726b2e204e6f7468696e6720636f6e7461696e6564206f6e207468652057656220736974652073686f756c6420626520636f6e737472756564206173206772616e74696e6720796f7520616e79206c6963656e7365206f7220726967687420746f2075736520616e792074726164656d61726b206c6f676f206f722064657369676e206f66205265616c74792050726f206f7220616e792074686972642070617274792c20776974686f757420746865207772697474656e207065726d697373696f6e206f66205265616c74792050726f206f72207468652072657370656374697665206f776e6572206f6620616e792074686972642d70617274792074726164656d61726b2e3c2f703e0d0a3c62723e3c62723e0d0a3c703e3c623e444953434c41494d4552204f462057415252414e544945532e3c2f623e3c2f703e0d0a3c62723e0d0a3c703e54484520574542205349544520414e4420414c4c20434f4e54454e542049532050524f56494445442041532049532e20425920414343455353494e4720414e44205553494e472054484520574542205349544520594f552041434b4e4f574c4544474520414e44204147524545205448415420555345204f462054484520574542205349544520414e442054484520434f4e54454e5420495320454e544952454c5920415420594f5552204f574e205249534b2e205265616c74792050726f204d414b4553204e4f20524550524553454e544154494f4e53204f522057415252414e5449455320524547415244494e472054484520574542205349544520414e442054484520434f4e54454e542c20494e434c5544494e472c20574954484f5554204c494d49544154494f4e2c204e4f20524550524553454e544154494f4e204f522057415252414e54592028492920544841542054484520574542205349544520414e442f4f5220434f4e54454e542057494c4c2042452041434355524154452c20434f4d504c4554452c2052454c4941424c452c205355495441424c45204f522054494d454c593b2028494929205448415420414e5920434f4e54454e542c20494e434c5544494e472c20574954484f5554204c494d49544154494f4e2c20414e5920494e464f524d4154494f4e2c20444154412c20534f4654574152452c2050524f44554354204f52205345525649434520434f4e5441494e454420494e204f52204d41444520415641494c41424c45205448524f554748205448452057454220534954452057494c4c204245204f46204d45524348414e5441424c45205155414c495459204f522046495420464f52204120504152544943554c415220505552504f53453b2049494929205448415420544845204f5045524154494f4e204f46205448452057454220534954452057494c4c20424520554e494e544552525550544544204f52204552524f5220465245453b202849562920544841542044454645435453204f52204552524f525320494e205448452057454220534954452057494c4c20424520434f525245435445443b202856292054484154205448452057454220534954452057494c4c20424520465245452046524f4d2056495255534553204f52204841524d46554c20434f4d504f4e454e54533b20414e442028564929205448415420434f4d4d554e49434154494f4e5320544f204f522046524f4d205448452057454220534954452057494c4c20424520534543555245204f52204e4f5420494e5445524345505445442e3c2f703e, 0x5465726d73206f6620757365, 0x7265616c206573746174652c207265616c746f722c20686f6d657320666f722073616c652c20686f75736520666f722073616c652c2066696e64206120686f6d652c207265616c20657374617465206c697374696e67732c20636f6e646f732c20746f776e686f6d65732c207265616c6573746174652c2070726f70657274792c206d6c732c2073656172636820686f6d65732c206c656173652c2072656e742c2072656e74616c206c697374696e67, '1', 'bottom', '2008-07-11 17:39:56'),
(11, 1, 1, 0x48656c70, 0x546869732073656374696f6e2077696c6c2068656c7020796f752066696e6420616e737765727320746f2061206772656174206e756d626572206f66207175657374696f6e73, 0x48656c70, 0x7265616c206573746174652c207265616c746f722c20686f6d657320666f722073616c652c20686f75736520666f722073616c652c2066696e64206120686f6d652c207265616c20657374617465206c697374696e67732c20636f6e646f732c20746f776e686f6d65732c207265616c6573746174652c2070726f70657274792c206d6c732c2073656172636820686f6d65732c206c656173652c2072656e742c2072656e74616c206c697374696e67, '1', 'top', '2008-07-16 15:51:51');

-- --------------------------------------------------------

--
-- Table structure for table `re_info_subsection`
--

DROP TABLE IF EXISTS `re_info_subsection`;
CREATE TABLE IF NOT EXISTS `re_info_subsection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL DEFAULT '0',
  `sequence` int(11) NOT NULL DEFAULT '0',
  `caption` tinyblob,
  `content` blob,
  `description` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `re_info_subsection`
--

INSERT INTO `re_info_subsection` (`id`, `section_id`, `sequence`, `caption`, `content`, `description`, `keywords`, `status`, `update_date`) VALUES
(1, 2, 1, 0xd0a1d0bfd180d0b0d0b2d0bed187d0bdd0b0d18f20d0b8d0bdd184d0bed180d0bcd0b0d186d0b8d18f20d0b4d0bbd18f20d180d0b8d18dd0bbd182d0bed180d0bed0b2, 0x3c503e3c493e3c423ed097d0b0d187d0b5d0bc20d0b4d0b5d0bbd0b8d182d18c20d0bad0b2d0b0d180d182d0b8d180d18320d18120d0bad0b5d0bc2dd182d0be20d0b5d189d1913f3c2f423e3c2f493e3c2f503e3c42523e0d0a3c503ed0add182d0bed18220d180d0b0d0b7d0b4d0b5d0bb2c20d181d0bad0bed180d0b5d0b52c20d0b1d183d0b4d0b5d18220d0b8d0bdd182d0b5d180d0b5d181d0b5d0bd20d182d0b5d0bc2c20d18320d0bad0bed0b3d0be20d183d0b6d0b520d0b5d181d182d18c20d0bad0b2d0b0d180d182d0b8d180d0b020d0b820d182d0b5d0bc2c20d0bad182d0be20d185d0bed187d0b5d18220d183d0b7d0bdd0b0d182d18c20d0bfd0bed187d0b5d0bcd18320d0b8d0bc20d0bcd0bed0b6d0b5d18220d0b1d18bd182d18c20d0b2d18bd0b3d0bed0b4d0bdd0be20d0bfd0bed181d0b5d0bbd0b8d182d18c20d18320d181d0b5d0b1d18f20d0bdd0b020d0b2d180d0b5d0bcd18f20d0bad0bed0b3d0be2dd182d0be20d0b5d189d0b52e3c42523ed09cd0bed0b6d0bdd0be20d0bed181d0bed0b1d0be20d0bfd0bed0b4d187d0b5d180d0bad0bdd183d182d18c20d181d0bbd0b5d0b4d183d18ed189d0b8d0b520d0bcd0bed0bcd0b5d0bdd182d18b3a3c2f503e0d0a3c503e3c5354524f4e473ed092d18b20d0bfd0bed0bbd183d187d0b0d0b5d182d0b520d0b4d0bed0bfd0bed0bbd0bdd0b8d182d0b5d0bbd18cd0bdd18bd0b920d0b8d181d182d0bed187d0bdd0b8d0ba20d0b4d0bed185d0bed0b4d0b02e3c2f5354524f4e473e3c42523ed09dd0b0d0b2d0b5d180d0bdd18fd0bad0b020d0bdd0b8d0bad182d0be20d0bdd0b520d0bed182d0bad0b0d0b6d0b5d182d181d18f20d0bed18220d0b2d0bed0b7d0bcd0bed0b6d0bdd0bed181d182d0b820d181d0b4d0b0d0b2d0b0d182d18c20d0bad0b2d0b0d180d182d0b8d180d18320d0b820d0bfd0bed0bbd183d187d0b0d182d18c20d0bfd0bed181d182d0bed18fd0bdd0bdd18bd0b920d0b3d0b0d180d0b0d0bdd182d0b8d180d0bed0b2d0b0d0bdd0bdd0bed0b920d0b4d0bed185d0bed0b420d0bdd0b520d182d180d0b0d182d18f20d0bdd0b020d18dd182d0be2c20d0bfd0be20d181d183d182d0b82c20d0bcd0bdd0bed0b3d0be20d0b2d180d0b5d0bcd0b5d0bdd0b82e20d094d0b0d0b6d0b520d0b5d181d0bbd0b820d18320d092d0b0d18120d0bdd0b5d18220d0bbd0b8d188d0bdd0b5d0b920d0bad0b2d0b0d180d182d0b8d180d18b2c20d0b2d18b20d0bcd0bed0b6d0b5d182d0b520d0bfd0bed0bbd183d187d0b0d182d18c20d0b0d180d0b5d0bdd0b4d0bdd18bd0b920d0b4d0bed185d0bed0b420d18120d182d0bed0b3d0be2c20d187d182d0be20d18320d0b2d0b0d18120d183d0b6d0b520d0b5d181d182d18c2e20d09dd0b0d0bfd180d0b8d0bcd0b5d1802c20d092d18b20d0bcd0bed0b6d0b5d182d0b520d0bed181d0b2d0bed0b1d0bed0b4d0b8d182d18c20d0bed0b4d0bdd18320d0bad0bed0bcd0bdd0b0d182d18320d0b820d181d0b4d0b0d0b2d0b0d182d18c20d0b5d0b520d0bbd18ed0b4d18fd0bc2c20d18120d0bad0bed182d0bed180d18bd0bcd0b820d092d0b0d0bc20d0b1d183d0b4d0b5d18220d0b8d0bdd182d0b5d180d0b5d181d0bdd0be20d0b6d0b8d182d18c2028d0b4d0bbd18f20d182d0bed0b3d0be2c20d187d182d0bed0b1d18b20d0bdd0b0d0b9d182d0b820d0b8d0bcd0b5d0bdd0bdd0be20d182d0b0d0bad0b8d18520d0bbd18ed0b4d0b5d0b920d0b820d181d0bed0b7d0b4d0b0d0bd20d0bdd0b0d18820d181d0b0d0b9d182292e20d0bfd0bbd0b82c20d0b4d0b0d0b6d0b520d0b5d181d0bbd0b820d18320d092d0b0d18120d0bdd0b5d18220d0bbd0b8d188d0bdd0b5d0b3d0be20d0bfd180d0bed181d182d180d0b0d0bdd181d182d0b2d0b02c20d0bdd0b0d0b2d0b5d180d0bdd18fd0bad0b020d0b8d0bdd0bed0b3d0b4d0b020d18320d092d0b0d18120d0b5d181d182d18c20d0b2d0bed0b7d0bcd0bed0b6d0bdd0bed181d182d18c20d185d0bed182d18f20d0b1d18b20d0bdd0b020d0bdd0b5d181d0bad0bed0bbd18cd0bad0be20d0b4d0bdd0b5d0b920d0bad0bed0b3d0be2dd0bdd0b8d0b1d183d0b4d18c20d18320d181d0b5d0b1d18f20d180d0b0d0b7d0bcd0b5d181d182d0b8d182d18c2c20d0b5d181d0bbd0b820d18320d092d0b0d18120d0b5d181d182d18c20d0bdd0b0d181d182d180d0bed0b5d0bdd0b8d0b52e20d0a2d0bed0b3d0b4d0b02c20d092d18b20d0bcd0bed0b6d0b5d182d0b520d0bdd0b0d0b9d182d0b820d0bdd0b020d0bdd0b0d188d0b5d0bc20d181d0b0d0b9d182d0b520d187d0b5d0bbd0bed0b2d0b5d0bad0b020d0b8d0b720d0b4d180d183d0b3d0bed0b920d181d182d180d0b0d0bdd18b20d0b820d0bfd180d0b5d0b4d0bbd0bed0b6d0b8d182d18c20d0b5d0bcd18320d0b8d0bbd0b820d0b5d0b920d0bfd0bed0b6d0b8d182d18c20d18320d092d0b0d18120d0bdd0b5d0bad0bed182d0bed180d0bed0b520d0b2d180d0b5d0bcd18f2e3c2f503e0d0a3c503e3c5354524f4e473ed092d0bed0b7d0bcd0bed0b6d0bdd0bed181d182d18c20d183d0bbd183d187d188d0b8d182d18c20d181d0b2d0bed0b820d0b6d0b8d0bbd0b8d189d0bdd18bd0b520d183d181d0bbd0bed0b2d0b8d18f2e3c2f5354524f4e473e3c42523ed095d181d182d18c20d18320d092d0b0d18120d0b6d0b5d0bbd0b0d0bdd0b8d0b520d181d0b4d0b5d0bbd0b0d182d18c20d180d0b5d0bcd0bed0bdd18220d18320d181d0b5d0b1d18f20d0b220d0bad0b2d0b0d180d182d0b8d180d0b520d0b8d0bbd0b820d0bad183d0bfd0b8d182d18c20d187d182d0be2dd0bdd0b8d0b1d183d0b4d18c2c20d0bdd0b020d187d182d0be20d18320d092d0b0d18120d181d0b5d0b9d187d0b0d18120d0bdd0b5d0b4d0bed181d182d0b0d182d0bed187d0bdd0be20d181d180d0b5d0b4d181d182d0b23f20d095d181d0bbd0b820d0b4d0b02c20d182d0be2c20d181d0b4d0b0d0b220d0bad0bed0bcd0bdd0b0d182d18320d0b220d092d0b0d188d0b5d0b920d0bad0b2d0b0d180d182d0b8d180d0b52c20d092d18b20d0bcd0bed0b6d0b5d182d0b520d0b4d0bed0b3d0bed0b2d0bed180d0b8d182d18cd181d18f20d18120d0b2d0b0d188d0b8d0bcd0b820d0bdd0bed0b2d18bd0bcd0b820d181d0bed181d0b5d0b4d18fd0bcd0b820d0b82c20d0bdd0b0d0bfd180d0b8d0bcd0b5d1802c20d181d0bad0b8d0bdd183d182d18cd181d18f20d0bdd0b020d180d0b5d0bcd0bed0bdd18220d0bad183d185d0bdd0b820d0b8d0bbd0b820d0bfd0bed0bad183d0bfd0bad18320d181d182d0b8d180d0b0d0bbd18cd0bdd0bed0b920d0bcd0b0d188d0b8d0bdd18b2c20d0bad0bed182d0bed180d0bed0b920d092d18b20d0b2d0bcd0b5d181d182d0b520d0b1d183d0b4d0b5d182d0b520d0bfd0bed0bbd18cd0b7d0bed0b2d0b0d182d18cd181d18f2e3c2f503e0d0a3c503e3c5354524f4e473ed09ed0b1d189d0b5d0bdd0b8d0b52c20d0bdd0bed0b2d18bd0b520d0b7d0bdd0b0d0bad0bed0bcd181d182d0b2d0b02e3c2f5354524f4e473e3c42523ed094d180d183d0b3d0bed0b920d187d0b5d0bbd0bed0b2d0b5d0ba20d181d0bcd0bed0b6d0b5d18220d0bfd180d0b8d0b2d0bdd0b5d181d182d0b820d0b1d0bed0bbd18cd188d0b5d0b520d180d0b0d0b7d0bdd0bed0bed0b1d180d0b0d0b7d0b8d0b520d0b220d092d0b0d188d18320d0b6d0b8d0b7d0bdd18c2c20d0b020d182d0b0d0bad0b6d0b520e2809420d0b8d0bdd182d0b5d180d0b5d181d0bdd0bed0b520d0bed0b1d189d0b5d0bdd0b8d0b52e20d09fd180d0bed181d182d0be20d092d0b0d0bc20d0bdd0b0d0b4d0be20d0bdd0b0d0b9d182d0b820d0bfd0bed0b4d185d0bed0b4d18fd189d0b5d0b3d0be20d181d0bed181d0b5d0b4d0b020d0b8d0bbd0b820d181d0bed181d0b5d0b4d0bad18320d0b820d18120d0bfd0bed0bcd0bed189d18cd18e20d0bfd0bed0b8d181d0bad0bed0b2d0bed0b920d181d0b8d181d182d0b5d0bcd18b20d0bdd0b020d18dd182d0bed0bc20d181d0b0d0b9d182d0b52c20d092d18b20d0bcd0bed0b6d0b5d182d0b520d0bbd0b5d0b3d0bad0be20d18dd182d0be20d181d0b4d0b5d0bbd0b0d182d18c2e2e3c2f503e0d0a3c424c4f434b51554f54453e0d0a3c50206d63655f6b6565703d2274727565223e266e6273703b3c2f503e3c2f424c4f434b51554f54453e0d0a3c503e3c493e3c423ed0a7d182d0be20d0bdd0b5d0bed0b1d185d0bed0b4d0b8d0bcd0be20d0bfd180d0bed0b2d0b5d180d0b8d182d18c20d0bfd0b5d180d0b5d0b420d0b7d0b0d181d0b5d0bbd0b5d0bdd0b8d0b5d0bc20d0b220d0bad0b2d0b0d180d182d0b8d180d1833f3c2f423e3c2f493e266e6273703b3c2f503e3c42523e0d0a3c503ed09dd0b5d0bed0b1d185d0bed0b4d0b8d0bcd0be20d181d180d0b0d0b7d18320d0bed0b3d0bed0b2d0bed180d0b8d182d18cd181d18f2c20d187d182d0be20d0bcd18b20d0bdd0b520d18ed180d0b8d181d182d18b2c20d0bfd0bed18dd182d0bed0bcd1832c20d0bfd0bed0b6d0b0d0bbd183d0b9d181d182d0b02c20d0b2d0bed181d0bfd180d0b8d0bdd0b8d0bcd0b0d0b9d182d0b520d0b8d0bdd184d0bed180d0bcd0b0d186d0b8d18e2c20d0b8d0b7d0bbd0bed0b6d0b5d0bdd0bdd183d18e20d0b7d0b4d0b5d181d18c20d0bad0b0d0ba20d0bfd0bed0bbd0b5d0b7d0bdd18bd0b520d181d0bed0b2d0b5d182d18b2c20d0bad0bed182d0bed180d18bd0b520d0bdd0b520d181d0bcd0bed0b3d183d18220d0b7d0b0d0bcd0b5d0bdd0b8d182d18c20d0bad0bed0bdd181d183d0bbd18cd182d0b0d186d0b8d18e20d0bfd180d0bed184d0b5d181d181d0b8d0bed0bdd0b0d0bbd18cd0bdd0bed0b3d0be20d18ed180d0b8d181d182d0b02e3c2f503e0d0a3c503e3c5354524f4e473ed0bfd182d0b0d0ba2c20d0b220d0bfd180d0bed186d0b5d181d181d0b520d0bfd0bed0b8d181d0bad0b020d0b6d0b8d0bbd18cd18f20d092d0b0d0bc20d0bdd0b5d0bed0b1d185d0bed0b4d0b8d0bcd0be20d0b1d183d0b4d0b5d1823a3c2f5354524f4e473e3c42523e312920d09fd180d0bed0b2d0b5d180d0b8d182d18c20d0b8d0bcd0b5d0b5d18220d0bbd0b820d187d0b5d0bbd0bed0b2d0b5d0ba2c20d181d0b4d0b0d18ed189d0b8d0b920d092d0b0d0bc20d0bad0b2d0b0d180d182d0b8d180d1832c20d0bdd0b020d18dd182d0be20d0bfd180d0b0d0b2d0be2028d182d0be20d0b5d181d182d18c2c20d0bdd0b020d181d0b4d0b0d187d18320d18dd182d0bed0b920d0bad0b2d0b0d180d182d0b8d180d18b20d0b220d0b0d180d0b5d0bdd0b4d18320d0b8d0bbd0b820d0b5d0b520d183d0bfd180d0b0d0b2d0bbd0b5d0bdd0b8d0b5d0bc292e20d09fd0be20d0b4d180d183d0b3d0bed0bcd18320d18dd182d0be20d0bdd0b0d0b7d18bd0b2d0b0d0b5d182d181d18f20d0bfd180d0bed0b2d0b5d180d0bad0b02022d18ed180d0b8d0b4d0b8d187d0b5d181d0bad0bed0b920d187d0b8d181d182d0bed182d18b2220d181d0b4d0b5d0bbd0bad0b82e3c42523e322920d0a2d0b0d0bad0b6d0b52c20d180d0b5d0bad0bed0bcd0b5d0bdd0b4d183d0b5d182d181d18f20d181d0bed181d182d0b0d0b2d0b8d182d18c20d0bfd180d0bed181d182d0bed0b920d0b4d0bed0b3d0bed0b2d0bed18020d18120d0b2d0bbd0b0d0b4d0b5d0bbd18cd186d0b5d0bc20d0bad0b2d0b0d180d182d0b8d180d18b20d18120d182d0b5d0bc2c20d187d182d0bed0b1d18b20d0b1d18bd0bbd0b820d187d0b5d182d0bad0be20d0bfd180d0bed0bfd0b8d181d0b0d0bdd18b20d092d0b0d188d0b820d0bfd180d0b0d0b2d0b020d0b820d0bed0b1d18fd0b7d0b0d0bdd0bdd0bed181d182d0b82c20d181d183d0bcd0bcd0b020d0b820d0bfd0b5d180d0b8d0bed0b4d0b8d187d0bdd0bed181d182d18c20d0bed0bfd0bbd0b0d182d18b2c20d0b820d0bfd180d0bed0b8d0b7d0b2d0b5d0b4d0b5d0bdd0b020d0bed0bfd0b8d181d18c20d0b8d0bcd183d189d0b5d181d182d0b2d0b02e20d0a1d182d0bed0b8d182d18c20d0bed182d0bcd0b5d182d0b8d182d18c2c20d187d182d0be20d0bed0b1d18bd187d0bdd0be20d0b2d0bbd0b0d0b4d0b5d0bbd18cd186d18b20d0bad0b2d0b0d180d182d0b8d18020d0bfd180d0b5d0b4d0bfd0bed187d0b8d182d0b0d18ed18220d0b7d0b0d0bdd0b8d0b7d0b8d182d18c20d181d182d0bed0b8d0bcd0bed181d182d18c20d0bad0bed0bdd182d180d0b0d0bad182d0b020d18120d182d0b5d0bc2c20d187d182d0bed0b1d18b20d0bfd0bbd0b0d182d0b8d182d18c20d0bcd0b5d0bdd18cd188d0b520d0bdd0b0d0bbd0bed0b3d0bed0b22e3c2f503e0d0a3c503e3c5354524f4e473ed0a7d182d0bed0b1d18b20d183d0b4d0bed181d182d0bed0b2d0b5d180d0b8d182d18cd181d18f20d0b220d182d0bed0bc2c20d187d182d0be20d0bad0b2d0b0d180d182d0b8d180d0b020c2abd18ed180d0b8d0b4d0b8d187d0b5d181d0bad0b820d187d0b8d181d182d0b0c2bb3c2f5354524f4e473e2c20d0b020d181d0b4d0b5d0bbd0bad0b020d0bfd180d0b0d0b2d0bed0bcd0b5d180d0bdd0b02c20d0bdd0b5d0bed0b1d185d0bed0b4d0b8d0bcd0be20d183d0b1d0b5d0b4d0b8d182d18cd181d18f20d0b220d181d0bbd0b5d0b4d183d18ed189d0b5d0bc205bd0b8d181d0bfd0bed0bbd18cd0b7d0bed0b2d0b0d0bdd0b020d0b8d0bdd184d0bed180d0bcd0b0d186d0b8d18f20d0b0d0b3d0b5d0bdd182d181d182d0b2d0b020d0bdd0b5d0b4d0b2d0b8d0b6d0b8d0bcd0bed181d182d0b820c2abd091d0b5d181d182c2bb2c20323030325d3a3c2f503e0d0a3c503e3c5354524f4e473ed09fd180d0b0d0b2d0bed183d181d182d0b0d0bdd0b0d0b2d0bbd0b8d0b2d0b0d18ed189d0b8d0b520d0b4d0bed0bad183d0bcd0b5d0bdd182d18b3c2f5354524f4e473e2028d0b4d0bed0bad183d0bcd0b5d0bdd182d18b2c20d0bfd0bed0b4d182d0b2d0b5d180d0b6d0b4d0b0d18ed189d0b8d0b520d0bfd180d0b0d0b2d0be20d181d0bed0b1d181d182d0b2d0b5d0bdd0bdd0bed181d182d0b82920d0bdd0b020d0bad0b2d0b0d180d182d0b8d180d18320d0b4d0b5d0b9d181d182d0b2d0b8d182d0b5d0bbd18cd0bdd18b2c20d182d0be20d0b5d181d182d18c20d0bad0b2d0b0d180d182d0b8d180d0b020d0bfd180d0b8d0bdd0b0d0b4d0bbd0b5d0b6d0b8d18220d0b8d0bcd0b5d0bdd0bdd0be20d182d0b5d0bc20d0bbd0b8d186d0b0d0bc2c20d0bad0bed182d0bed180d18bd0bc20d0bed0bdd0b020d0b4d0bed0bbd0b6d0bdd0b020d0bfd180d0b8d0bdd0b0d0b4d0bbd0b5d0b6d0b0d182d18c2e20d0add182d0be20d0bed0b7d0bdd0b0d187d0b0d0b5d1822c20d187d182d0be20d0bfd180d0b820d0bfd180d0bed0b2d0b5d0b4d0b5d0bdd0b8d0b820d0bad0b0d0b6d0b4d0bed0b920d181d0b4d0b5d0bbd0bad0b820d18120d0bdd0b5d0b92c20d0bdd0b0d187d0b8d0bdd0b0d18f20d18120d0bfd180d0b8d0b2d0b0d182d0b8d0b7d0b0d186d0b8d0b82c20d0bdd0b520d0b1d18bd0bb20d0bed0b1d0b8d0b6d0b5d0bd20d0bdd0b8d0bad182d0be20d0b8d0b720d182d0b5d1852c20d0bad182d0be20d0b8d0bcd0b5d0bb20d0bdd0b020d18dd182d18320d0bad0b2d0b0d180d182d0b8d180d18320d0bad0b0d0bad0b8d0b52dd0bbd0b8d0b1d0be20d0bfd180d0b0d0b2d0b02c20d0b820d0bad0b0d0b6d0b4d0b0d18f20d181d0b4d0b5d0bbd0bad0b020d0b1d18bd0bbd0b020d0bfd180d0bed0b2d0b5d0b4d0b5d0bdd0b020d18120d181d0bed0b1d0bbd18ed0b4d0b5d0bdd0b8d0b5d0bc20d182d180d0b5d0b1d0bed0b2d0b0d0bdd0b8d0b920d0b7d0b0d0bad0bed0bdd0bed0b4d0b0d182d0b5d0bbd18cd181d182d0b2d0b020d0b820d0bdd0bed180d0bcd0b0d182d0b8d0b2d0bdd18bd18520d0b0d0bad182d0bed0b22e20d09dd0b0d0b8d0b1d0bed0bbd0b5d0b520d187d0b0d181d182d0be20d0bdd0b0d180d183d188d0b0d18ed182d181d18f20d0bfd180d0b0d0b2d0b020d0bbd18ed0b4d0b5d0b92c20d0bad0bed182d0bed180d18bd0b520d0b2d180d0b5d0bcd0b5d0bdd0bdd0be20d0bed182d181d183d182d181d182d0b2d183d18ed1822028d0b2d0bed0b5d0bdd0bed181d0bbd183d0b6d0b0d189d0b8d0b52c20d0bed182d0b1d18bd0b2d0b0d18ed189d0b8d0b520d181d180d0bed0ba20d0b220d0bcd0b5d181d182d0b0d18520d0bbd0b8d188d0b5d0bdd0b8d18f20d181d0b2d0bed0b1d0bed0b4d18b2c20d180d0b0d0b1d0bed182d0b0d18ed189d0b8d0b520d0b7d0b020d180d183d0b1d0b5d0b6d0bed0bc20d0b820d1822ed0b42e293c42523ed0a7d182d0be20d0bdd183d0b6d0bdd0be20d0bfd180d0bed0b2d0b5d180d0b8d182d18c3a2028312920d09fd180d0b0d0b2d0bed183d181d182d0b0d0bdd0b0d0b2d0bbd0b8d0b2d0b02ed189d0b8d0b520d0b4d0bed0bad183d0bcd0b5d0bdd182d18b20d0b0d180d0b5d0bdd0b4d0b0d182d0bed180d0b02028d0bfd180d0bed0b4d0b0d0b2d186d0b0293b2028322920d095d181d182d18c20d0bbd0b820d0b5d189d19120d181d0bed0b1d181d182d0b2d0b5d0bdd0bdd0b8d0bad0b820d0b8d0bbd0b820d0bbd18ed0b4d0b82c20d0bfd180d0b5d182d0b5d0bdd0b4d183d18ed189d0b8d0b520d0bdd0b020d0b6d0b8d0bbd18cd1913b202833292e20d094d0bed0bcd0bed0b2d0b0d18f20d0bad0bdd0b8d0b3d0b02028d0bad182d0be20d0b1d18bd0bb20d0bfd180d0bed0bfd0b8d181d0b0d0bd3f293b2028342920d094d0b0d0bdd0bdd18bd0b520d094d0b5d0bfd0b0d180d182d0b0d0bcd0b5d0bdd182d0b020d0bcd183d0bdd0b8d186d0b8d0bfd0b0d0bbd18cd0bdd0bed0b3d0be20d0b6d0b8d0bbd18cd18f2028d0bad0b5d0bc20d0b1d18bd0bbd0b820d181d0bed0b1d181d182d0b2d0b5d0bdd0bdd0b8d0bad0b83f20d091d18bd0bbd0b820d0bbd0b820d0b2d181d0b520d0bfd180d0b5d0b4d18bd0b4d183d189d0b8d0b520d181d0b4d0b5d0bbd0bad0b820d18120d0bad0b2d0b0d180d182d0b8d180d0bed0b920d0bfd180d0b0d0b2d0b8d0bbd18cd0bdd0be20d0b7d0b0d180d0b5d0b3d0b8d181d182d180d0b8d180d0bed0b2d0b0d0bdd18b3f293b2028352920d094d0b0d0bdd0bdd18bd0b520d0bdd0bed182d0b0d180d0b8d0b0d0bbd18cd0bdd18bd18520d0bad0bed0bdd182d0bed1802028d094d0b5d0b9d181d182d0b2d0b8d182d0b5d0bbd18cd0bdd0be20d0bbd0b820d0b2d181d0b520d181d0b4d0b5d0bbd0bad0b820d18120d18dd182d0bed0b920d0bad0b2d0b0d180d182d0b8d180d0bed0b920d0b1d18bd0bbd0b820d0bdd0b0d0b4d0bbd0b5d0b6d0b0d189d0b8d0bc20d0bed0b1d180d0b0d0b7d0bed0bc20d183d0b4d0bed181d182d0bed0b2d0b5d180d0b5d0bdd18b20d18320d0bdd0bed182d0b0d180d0b8d183d181d0b03f292e2028362920d092d0b0d0b6d0bdd0be2c20d187d182d0bed0b1d18b20d0bfd0bed0b420d0b4d0bed0b3d0bed0b2d0bed180d0bed0bc20d0b0d180d0b5d0bdd0b4d0b020d18bd0bbd0b820d0bfd0bed181d182d0b0d0b2d0bbd0b5d0bdd18b20d0bfd0bed0b4d0bfd0b8d181d0b820d0b2d181d0b5d18520d181d0bed0b1d181d182d0b2d0b5d0bdd0bdd0b8d0bad0bed0b2202f20d0bfd180d0b5d0b4d0b5d0bdd0b4d183d18ed189d0b8d18520d0bdd0b020d0b6d0b8d0bbd18cd1912e3c2f503e0d0a3c503e3c5354524f4e473ed094d0b5d0b5d181d0bfd0bed181d0bed0b1d0bdd0bed181d182d18c2e3c2f5354524f4e473e20d0a1d0bed0b1d181d182d0b2d0b5d0bdd0bdd0b8d0bad0b82028d0bfd180d0bed0b4d0b0d0b2d186d18b2920d0bfd180d0bed0b2d0b5d180d18fd0b5d0bcd0bed0b920d0bad0b2d0b0d180d182d0b8d180d18b20d0b4d0bed0bbd0b6d0bdd18b20d0bed0b1d0bbd0b0d0b4d0b0d182d18c20d0bfd0bed0bbd0bdd0bed0b920d0b4d0b5d0b5d181d0bfd0bed181d0bed0b1d0bdd0bed181d182d18cd18e2c20d182d0be20d0b5d181d182d18c20d0bdd0b520d0b8d0bcd0b5d182d18c20d0bed0b3d180d0b0d0bdd0b8d187d0b5d0bdd0b8d0b92c20d0bdd0b0d0bbd0bed0b6d0b5d0bdd0bdd18bd18520d0b7d0b0d0bad0bed0bdd0bed0bc20d0b8d0bbd0b820d181d183d0b4d0b5d0b1d0bdd18bd0bcd0b820d0bed180d0b3d0b0d0bdd0b0d0bcd0b820d0bdd0b020d0b2d0bed0b7d0bcd0bed0b6d0bdd0bed181d182d18c20d181d0bed0b2d0b5d180d188d0b5d0bdd0b8d18f20d0b8d0bcd0b820d181d0b4d0b5d0bbd0bed0ba20d0bfd0be20d0bed182d187d183d0b6d0b4d0b5d0bdd0b8d18e2028d0bfd183d182d0b5d0bc20d0bad183d0bfd0bbd0b82dd0bfd180d0bed0b4d0b0d0b6d0b82c20d0bcd0b5d0bdd18b2c20d0b4d0b0d180d0b5d0bdd0b8d18f20d0b8d0bbd0b820d0bed0b1d0bcd0b5d0bdd0b02920d0b8d0bcd0b820d0b8d0bcd183d189d0b5d181d182d0b2d0b02e3c42523ed0a1d0bbd0b5d0b4d183d0b5d18220d0b7d0b0d0bcd0b5d182d0b8d182d18c2c20d187d182d0be20d0b7d0b4d0b5d181d18c20d181d183d189d0b5d181d182d0b2d183d18ed18220d181d0b2d0bed0b820d182d0bed0bdd0bad0bed181d182d0b8202d2d20d0bfd180d0b0d0b2d0be20d181d0bed0b1d181d182d0b2d0b5d0bdd0bdd0bed181d182d0b820d0bfd0bed0b4d182d0b2d0b5d180d0b6d0b4d0b0d18ed18220d181d0bbd0b5d0b4d183d18ed189d0b8d0b520d0b4d0bed0bad183d0bcd0b5d0bdd182d18b3a20d0bed180d0b4d0b5d1802c20d181d0b2d0b8d0b4d0b5d182d0b5d0bbd18cd181d182d0b2d0be20d0be20d181d0bed0b1d181d182d0b2d0b5d0bdd0bdd0bed181d182d0b82c20d0b4d0bed0b3d0bed0b2d0bed18020d0bad183d0bfd0bbd0b82dd0bfd180d0bed0b4d0b0d0b6d0b82c20d0b4d0b0d180d0b5d0bdd0b8d18f2e20d0a2d0b0d0bad0b6d0b52c20d0b2d0b0d0b6d0bdd0be2c20d187d182d0be20d0bfd0be20d0bdd0b0d181d0bbd0b5d0b4d181d182d0b2d18320d0bad0b2d0b0d180d182d0b8d180d18b20d0bed182d185d0bed0b4d0b8d18220d0bdd0bed0b2d0bed0bcd18320d181d0bed0b1d181d182d0b2d0b5d0bdd0bdd0b8d0bad18320d182d0bed0bbd18cd0bad0be20d187d0b5d180d0b5d0b7203620d0bcd0b5d181d18fd186d0b5d0b220d0bfd0bed181d0bbd0b520d181d0bcd0b5d180d182d0b820d0b1d18bd0b2d188d0b5d0b3d0be20d0b2d0bbd0b0d0b4d0b5d0bbd18cd186d0b02e3c42523ed0a7d182d0be20d0bdd183d0b6d0bdd0be20d0bfd180d0bed0b2d0b5d180d0b8d182d18c3a2028312920d0a3d0bfd0bed0bbd0bdd0bed0bcd0bed187d0b5d0bdd0bdd0bed181d182d18c20d181d0b4d0b0d0b2d0b0d182d18c20d0b6d0b8d0bbd18cd191202f20d0b2d0b5d181d182d0b820d0bfd0b5d180d0b5d0b3d0bed0b2d0bed180d18b3b2028322920d09ed0b3d180d0b0d0bdd0b8d187d0b5d0bdd0b8d18f2c20d0bdd0b0d0bbd0bed0b6d0b5d0bdd0bdd18bd0b520d0b7d0b0d0bad0bed0bdd0bed0bc20d0b8d0bbd0b820d181d183d0b4d0b5d0b1d0bdd18bd0bcd0b820d0bed180d0b3d0b0d0bdd0b0d0bcd0b820d0bdd0b020d0b2d0bed0b7d0bcd0bed0b6d0bdd0bed181d182d18c20d181d0bed0b2d0b5d180d188d0b5d0bdd0b8d18f20d181d0b4d0b5d0bbd0bad0b83b2028332920d09fd181d0b8d185d0b8d187d0b5d181d0bad0bed0b520d0b7d0b4d0bed180d0bed0b2d18cd0b53b2028342920d09dd0b0d180d0bad0bed0bbd0bed0b3d0b8d187d0b5d181d0bad0b0d18f20d0b7d0b0d0b2d0b8d181d0b8d0bcd0bed181d182d18c2028d181d182d0bed18fd18220d0bbd0b820d0bdd0b020d183d187d191d182d0b520d0b220d0b4d0b8d181d0bfd0b0d0bdd181d0b5d180d0b5293b2028352920d09fd0bed0bbd0bdd0bed0bcd0bed187d0b8d18f20d0bed0bfd0b5d0bad183d0bdd0bed0b220d0b820d0bfd0bed0bfd0b5d187d0b8d182d0b5d0bbd0b5d0b92028d0bed0bdd0b820d0bcd0bed0b3d183d18220d0bdd0b020d0bed181d0bdd0bed0b2d0b0d0bdd0b8d0b820d0bfd180d0b5d0b4d0bed181d182d0b0d0b2d0bbd0b5d0bdd0bdd18bd18520d0b8d0bc20d0b7d0b0d0bad0bed0bdd0bed0bc2c20d181d183d0b4d0bed0bc20d0b8d0bbd0b820d0bed180d0b3d0b0d0bdd0b0d0bcd0b820d0bed0bfd0b5d0bad0b820d0b820d0bfd0bed0bfd0b5d187d0b8d182d0b5d0bbd18cd181d182d0b2d0b020d0bfd0bed0bbd0bdd0bed0bcd0bed187d0b8d0b920d0b8d0bcd0b5d182d18c20d0bfd180d0b0d0b2d0be20d0bdd0b020d181d0bed0b2d0b5d180d188d0b5d0bdd0b8d0b520d181d0b4d0b5d0bbd0bad0b820d0bed18220d0b8d0bcd0b5d0bdd0b820d181d0b2d0bed0b8d18520d0bfd0bed0b4d0bed0bfd0b5d187d0bdd18bd185292e3c2f503e0d0a3c503e3c5354524f4e473ed09ed182d181d183d182d181d182d0b2d0b8d0b520d0bfd180d0b5d182d0b5d0bdd0b7d0b8d0b9203c2f5354524f4e473ed0bdd0b020d0bad0b2d0b0d180d182d0b8d180d18320d181d0be20d181d182d0bed180d0bed0bdd18b20d182d180d0b5d182d0b8d18520d0bbd0b8d1862e20d09dd0b5d18220d181d0b2d0b5d0b4d0b5d0bdd0b8d0b920d0be20d0bbd0b8d186d0b0d1852c20d181d187d0b8d182d0b0d18ed189d0b8d18520d181d0b5d0b1d18f20d183d189d0b5d0bcd0bbd0b5d0bdd0bdd18bd0bcd0b820d0b220d0bfd180d0b0d0b2d0b0d18520d0b820d0bfd18bd182d0b0d18ed189d0b8d185d181d18f20d180d0b5d188d0b0d182d18c20d18dd182d0bed18220d0b2d0bed0bfd180d0bed18120d0b220d181d183d0b4d0b5d0b1d0bdd18bd18520d0bed180d0b3d0b0d0bdd0b0d1852e3c2f503e0d0a3c503e3c5354524f4e473ed09ed182d181d183d182d181d182d0b2d183d18ed18220d0bbd0b8d186d0b02c20d181d0bed185d180d0b0d0bdd0b8d0b2d188d0b8d0b520d0bfd180d0b0d0b2d0be20d0bfd0bed0bbd18cd0b7d0bed0b2d0b0d0bdd0b8d18f20d0bad0b2d0b0d180d182d0b8d180d0bed0b9203c2f5354524f4e473ed0bfd0bed181d0bbd0b520d0b5d19120d0bfd180d0bed0b4d0b0d0b6d0b82e20d0a1d18ed0b4d0b020d0b2d185d0bed0b4d0b8d18220d0bdd0b5d181d0bad0bed0bbd18cd0bad0be20d0bad0b0d182d0b5d0b3d0bed180d0b8d0b92e20d0add182d0be20d0bcd0bed0b3d183d18220d0b1d18bd182d18c20d0bbd0b8d186d0b02c20d0b2d180d0b5d0bcd0b5d0bdd0bdd0be20d0b2d18bd0b1d18bd0b2d188d0b8d0b520d0b220d0bcd0b5d181d182d0b020d0bbd0b8d188d0b5d0bdd0b8d18f20d181d0b2d0bed0b1d0bed0b4d18b2c20d0b8d0bbd0b820d0b4d0bbd18f20d0bfd180d0bed185d0bed0b6d0b4d0b5d0bdd0b8d18f20d181d0bbd183d0b6d0b1d18b20d0b220d092d0bed0bed180d183d0b6d0b5d0bdd0bdd18bd18520d181d0b8d0bbd0b0d1852c20d0b8d0bbd0b820d0bdd0b020d180d0b0d0b1d0bed182d18320d0b7d0b020d180d183d0b1d0b5d0b6d0bed0bc20d0bfd0be20d0bad0bed0bdd182d180d0b0d0bad182d1832c20d0b8d0bbd0b820d0bfd0be20d0bad0b0d0bad0b8d0bc2dd0bbd0b8d0b1d0be20d0b4d180d183d0b3d0b8d0bc20d0bed181d0bdd0bed0b2d0b0d0bdd0b8d18fd0bc2e20d092d181d0b520d0bed0bdd0b820d0bfd180d0b820d18dd182d0bed0bc20d0bed181d182d0b0d18ed182d181d18f20d0b7d0b0d180d0b5d0b3d0b8d181d182d180d0b8d180d0bed0b2d0b0d0bdd0bdd18bd0bcd0b820d0bfd0be20d0bcd0b5d181d182d18320d0b6d0b8d182d0b5d0bbd18cd181d182d0b2d0b02028d0bfd0be2dd181d182d0b0d180d0bed0bcd18320e2809320d0bfd0bed181d182d0bed18fd0bdd0bdd0be20d0bfd180d0bed0bfd0b8d181d0b0d0bdd0bdd18bd0bcd0b82920d0b220d0bad0b2d0b0d180d182d0b8d180d0b52c20d0b820d0b8d0bcd0b5d18ed18220d181d0bed0bed182d0b2d0b5d182d181d182d0b2d183d18ed189d0b8d0b520d0bfd180d0b0d0b2d0b02e3c42523ed095d181d0bbd0b820d0bad0b2d0b0d180d182d0b8d180d0b020d181d0b4d0b0d0b5d182d181d18f20d0b220d0b0d180d0b5d0bdd0b4d1832c20d0bdd0b5d0bed0b1d185d0bed0b4d0b8d0bcd0be2c20d187d182d0bed0b1d18b20d0bfd0bed0b420d0b4d0bed0b3d0bed0b2d0bed180d0bed0bc20d181d182d0bed18fd0bbd0b020d0bfd0bed0b4d0bfd0b8d181d18c20d0b2d181d0b5d18520d0bbd18ed0b4d0b5d0b92c20d0bfd180d0bed0bfd0b8d181d0b0d0bdd0bdd18bd18520d0b220d0bad0b2d0b0d180d182d0b8d180d0b52e3c2f503e0d0a3c503e3c5354524f4e473ed09ad0b2d0b0d180d182d0b8d180d0b020d0bdd0b520d18fd0b2d0bbd18fd0b5d182d181d18f20d0bfd180d0b5d0b4d0bcd0b5d182d0bed0bc20d181d0bfd0bed180d0b03c2f5354524f4e473e2c20d0bdd0b020d0bdd0b5d0b520d0bdd0b520d0bdd0b0d0bbd0bed0b6d0b5d0bd20d0b0d180d0b5d181d18220d0b820d0bed182d181d183d182d181d182d0b2d183d18ed18220d181d0b2d0b5d0b4d0b5d0bdd0b8d18f20d0be20d0b7d0b0d0bfd180d0b5d182d0b0d18520d0bdd0b020d0bfd180d0bed0b2d0b5d0b4d0b5d0bdd0b8d0b520d181d0b4d0b5d0bbd0bed0ba20d18120d0bdd0b5d0b92e20d09fd180d0b820d0bed181d0bfd0b0d180d0b8d0b2d0b0d0bdd0b8d0b820d0bad0b5d0bc2dd0bbd0b8d0b1d0be20d0bfd180d0b0d0b220d0bdd0b020d0b2d18bd0b1d180d0b0d0bdd0bdd183d18e20d0b2d0b0d0bcd0b820d0bad0b2d0b0d180d182d0b8d180d18320d181d183d0b420d0bcd0bed0b6d0b5d18220d0bdd0b0d0bbd0bed0b6d0b8d182d18c20d0bdd0b020d0bdd0b5d0b520d0b0d180d0b5d181d18220d0b820d181d0bed0bed0b1d189d0b8d182d18c20d0bed0b120d18dd182d0bed0bc20d0b220d0bed180d0b3d0b0d0bdd18b20d0b3d0bed181d183d0b4d0b0d180d181d182d0b2d0b5d0bdd0bdd0bed0b920d180d0b5d0b3d0b8d181d182d180d0b0d186d0b8d0b820d181d0b4d0b5d0bbd0bed0ba2e3c42523ed0a7d182d0be20d0bdd183d0b6d0bdd0be20d0bfd180d0bed0b2d0b5d180d0b8d182d18c3a20d0bdd0b0d0bfd180d0b0d0b2d0b8d182d18c20d0b7d0b0d0bfd180d0bed181d18b20d0b220d181d0bed0bed182d0b2d0b5d182d181d182d0b2d183d18ed189d0b8d0b520d0b8d0bdd181d182d0b0d0bdd186d0b8d0b820d0b820d183d0b1d0b5d0b4d0b8d182d18cd181d18f20d0b220d0bed182d181d183d182d181d182d0b2d0b8d0b820d0bfd0bed0b4d0bed0b1d0bdd18bd18520d0b7d0b0d0bfd180d0b5d182d0bed0b22e3c2f503e0d0a3c503e3c5354524f4e473ed09bd0b8d187d0bdd0bed0b520d0bed0b1d189d0b5d0bdd0b8d0b520d18120d185d0bed0b7d18fd0b5d0b2d0b0d0bcd0b8203c2f5354524f4e473ed0b820d0b4d180d183d0b3d0b8d0bcd0b820d0bbd0b8d186d0b0d0bcd0b82c20d0bfd180d0bed0b6d0b8d0b2d0b0d18ed189d0b8d18520d0b220d0bad0b2d0b0d180d182d0b8d180d0b5202f20d0bfd180d0b5d182d0b5d0bdd0b4d183d18ed189d0b8d18520d0bdd0b020d0bdd0b5d1912e3c2f503e0d0a3c503e3c5354524f4e473ed09fd180d0b820d0b7d0b0d0bad0bbd18ed187d0b5d0bdd0b8d0b820d0b4d0bed0b3d0bed0b2d0bed180d0b020d0b0d180d0b5d0bdd0b4d0bed0b4d0b0d182d0b5d0bbd18e20d181d0bbd0b5d0b4d183d0b5d18220d0bed0b1d180d0b0d182d0b8d182d18c20d0b2d0bdd0b8d0bcd0b0d0bdd0b8d0b520d0bdd0b020d181d0bbd0b5d0b4d183d18ed189d0b8d0b520d0bcd0bed0bcd0b5d0bdd182d18b3a3c2f5354524f4e473e3c42523ed09fd0bed0b8d0bcd0b5d0bdd0bdd0be20d0bed0b1d0bed0b7d0bdd0b0d187d0b5d0bdd18b20d0bbd0b8d186d0b02c20d0bad0bed182d0bed180d18bd0b520d0b1d183d0b4d183d18220d0bfd180d0bed0b6d0b8d0b2d0b0d182d18c20d0b220d0b4d0b0d0bdd0bdd0bed0b920d0bad0b2d0b0d180d182d0b8d180d0b520d18120d0b8d18520d0bfd0b0d181d0bfd0bed180d182d0bdd18bd0bcd0b820d0b4d0b0d0bdd0bdd18bd0bcd0b82e3c42523ed09fd0b5d180d0b8d0bed0b4d0b8d187d0b5d181d0bad0b8d0b920d0bad0bed0bdd182d180d0bed0bbd18c20d0bad0b2d0b0d180d182d0b8d180d18b2e203c42523ed09ed0bfd0b8d181d18c20d0b8d0bcd183d189d0b5d181d182d0b2d0b020d18120d0bed186d0b5d0bdd0bad0bed0b920d0bad0b0d0b6d0b4d0bed0b3d0be20d0bfd180d0b5d0b4d0bcd0b5d182d0b020d0b820d0bed182d0b2d0b5d182d181d182d0b2d0b5d0bdd0bdd0bed181d182d18c20d0b0d180d0b5d0bdd0b4d0b0d182d0bed180d0b020d0b7d0b020d181d0bed185d180d0b0d0bdd0bdd0bed181d182d18c2e3c42523ed0a1d0bfd0bed181d0bed0b120d0bad0bed0bdd182d180d0bed0bbd18f20d0b7d0b020d0bed0bfd0bbd0b0d182d0bed0b920d182d0b5d0bbd0b5d184d0bed0bdd0bdd18bd18520d180d0b0d0b7d0b3d0bed0b2d0bed180d0bed0b22e3c42523ed0a3d181d0bbd0bed0b2d0b8d18f20d0b4d0bed181d180d0bed187d0bdd0bed0b3d0be20d180d0b0d181d182d0bed180d0b6d0b5d0bdd0b8d18f20d0b4d0bed0b3d0bed0b2d0bed180d0b020d0bfd0be20d0b8d0bdd0b8d186d0b8d0b0d182d0b8d0b2d0b520d0bed0b4d0bdd0bed0b920d0b8d0b720d181d182d0bed180d0bed0bd2e3c42523ed0a3d181d0bbd0bed0b2d0b8d18f20d181d0b4d0b0d187d0b820d0b0d180d0b5d0bdd0b4d0b0d182d0bed180d0bed0bc20d0bad0b2d0b0d180d182d0b8d180d18b20d0bfd180d0b820d0bfd180d0b5d0bad180d0b0d189d0b5d0bdd0b8d0b820d0b4d0bed0b3d0bed0b2d0bed180d0b02e3c2f503e0d0a3c503e3c5354524f4e473ed090d180d0b5d0bdd0b4d0b0d182d0bed180d0b0d0bc20d181d0bbd0b5d0b4d183d0b5d18220d0bed181d0bed0b1d0be20d0bed182d0bcd0b5d182d0b8d182d18c20d181d0bbd0b5d0b4d183d18ed189d0b8d0b520d0bcd0bed0bcd0b5d0bdd182d18b20d0b220d0b4d0bed0b3d0bed0b2d0bed180d0b53a3c2f5354524f4e473e3c42523ed0a1d183d0bcd0bcd0b020d0b820d0bfd0b5d180d0b8d0bed0b4d0b8d187d0bdd0bed181d182d18c20d0bed0bfd0bbd0b0d182d18b3c42523ed09ed0bfd0b8d181d18c20d0b8d0bcd183d189d0b5d181d182d0b2d0b02c20d0bdd0b0d185d0bed0b4d18fd189d0b5d0b3d0bed181d18f20d0b220d0bad0b2d0b0d180d182d0b8d180d0b520d0b820d0b5d0b3d0be20d181d0bed181d182d0bed18fd0bdd0b8d0b53c42523ed09fd0bed180d18fd0b4d0bed0ba20d0b2d0bed0b7d0bcd0b5d189d0b5d0bdd0b8d18f20d183d189d0b5d180d0b1d0b020d0b820d0bed181d182d0b0d0bdd0bed0b2d0bad0b820d0bad0bed0bdd182d180d0b0d0bad182d0b03c42523ed0a3d187d0b5d181d182d18c20d0b5d181d182d0b5d181d182d0b2d0b5d0bdd0bdd18bd0b920d0b8d0b7d0bdd0bed18120d0b8d0bdd182d0b5d180d18cd0b5d180d0b020d0b820d0bfd180d0b5d0b4d0bcd0b5d182d0bed0b22c20d0bdd0b0d185d0bed0b4d18fd189d0b8d185d181d18f20d0b220d0bad0b2d0b0d180d182d0b8d180d0b53c42523e3c2f503e, '', '', '1', '2008-05-06 11:09:55'),
(2, 2, 3, 0xd09ad0b0d0ba20d0bdd0b0d0b9d182d0b820d181d0bed181d0b5d0b4d0b020d0b8d0bbd0b820d181d0bed181d0b5d0b4d0bad18320d0bfd0be20d0bad0b2d0b0d180d182d0b8d180d0b52c20d0bfd0bed0b8d181d0ba20d0bad0bed0bcd0bfd0b0d0bdd18cd0bed0bdd0bed0b220d0b4d0bbd18f20d0b0d180d0b5d0bdd0b4d18b20d0bad0b2d0b0d180d182d0b8d180d18b, 0x3c703ed09fd180d0bed189d0b520d0b2d181d0b5d0b3d0be20d0bdd0b0d187d0b0d182d18c20d0bfd0bed0b8d181d0ba20d181d180d0b5d0b4d0b820d0b7d0bdd0b0d0bad0bed0bcd18bd185e2809420d0bdd0b0d0b2d0b5d180d0bdd18fd0bad0b020d181d180d0b5d0b4d0b820d0bdd0b8d18520d0b5d181d182d18c20d0bad182d0be2dd0bdd0b8d0b1d183d0b4d18c2c20d0bad182d0be20d182d0bed0b6d0b520d185d0bed187d0b5d18220d181d0bdd18fd182d18c20d0bad0bed0bcd0bdd0b0d182d18320d0b8d0bbd0b820d0bad0b2d0b0d182d0b8d180d1832e20d09fd180d0b820d0bfd0bed0b8d181d0bad0b520d0b2d0b0d0b6d0bdd0be20d0b8d0bcd0b5d182d18c20d183d0b2d0b5d180d0b5d0bdd0bdd0bed181d182d18c20d0b220d182d0bed0bc2c20d187d182d0be20d092d0b0d0bc20d0b1d183d0b4d0b5d18220d0bfd180d0b8d18fd182d0bdd0be20d0b820d0b8d0bdd182d0b5d180d0b5d181d0bdd0be20d0b6d0b8d182d18c20d18120d18dd182d0b8d0bc20d187d0b5d0bbd0bed0b2d0b5d0bad0bed0bc2c20d187d182d0be20d092d18b20d181d0bcd0bed0b6d0b5d182d0b520d0b4d0bed0b3d0bed0b2d0bed180d0b8d182d18cd181d18f20d0be20d182d0bed0bc2c20d187d182d0bed0b1d18b2c20d0bdd0b0d0bfd180d0b8d0bcd0b5d1802c20d0b5d181d0bbd0b820d092d18b20d0bdd0b520d0bad183d180d0b8d182d0b52c20d0b4d180d183d0b3d0bed0b920d187d0b5d0bbd0bed0b2d0b5d0ba20d0bad183d180d0b8d0bb20d182d0bed0bbd18cd0bad0be20d0bdd0b020d0b1d0b0d0bbd0bad0bed0bdd0b520d0b8d0bbd0b82c20d181d0bad0b0d0b6d0b5d0bc2c20d0bad0b0d0b6d0b4d18bd0b920d183d0b1d0b8d180d0b0d0bb20d0b7d0b020d181d0bed0b1d0bed0b92e3c62723ed0a2d0b0d0bad0b6d0b520e2809420d181d0bed0b2d0bcd0b5d181d182d0bdd0b0d18f20d0b0d180d0b5d0bdd0b4d0b020d0bcd0bed0b6d0b5d18220d0b1d18bd182d18c20d185d0bed180d0bed188d0b8d0bc20d181d0bfd0bed181d0bed0b1d0bed0bc20d0bfd0bed0b7d0bdd0b0d0bad0bed0bcd0b8d182d18cd181d18f20d0bfd0bed0b1d0bbd0b8d0b6d0b520d18120d0bfd180d0bed182d0b8d0b2d0bed0bfd0bed0bbd0bed0b6d0bdd18bd0bc20d0bfd0bed0bbd0bed0bc2e20d0a2d0be20d0b5d181d182d18c2c20d092d18b20d0bcd0bed0b6d0b5d182d0b520d0bdd0b0d187d0b0d182d18c20d0bfd0bed0b8d181d0ba20d181d0be20d181d0b2d0bed0b5d0b3d0be20d0bfd0b0d180d182d0bdd0b5d180d0b020d0b8d0bbd0b820d182d0b5d1852c20d18120d0bad0b5d0bc20d092d18b20d0b1d18b20d185d0bed182d0b5d0bbd0b820d0bfd0bed0bbd183d187d188d0b520d0bfd0bed0b7d0bdd0b0d0bad0bed0bcd0b8d182d18cd181d18f2e3c62723ed092d0bed18220d0bdd0b5d181d0bad0bed0bbd18cd0bad0be20d180d0b5d0bad0bed0bcd0b5d0bdd0b4d0b0d186d0b8d0b920d0b8d0b720d0bbd0b8d187d0bdd0bed0b3d0be20d0bed0bfd18bd182d0b02c20d0bad0bed182d0bed180d18bd0b520d0bfd0bed0b7d0b2d0bed0bbd18fd18220d092d0b0d0bc20d0bed0bfd180d0b5d0b4d0b5d0bbd0b8d182d18c20d0bdd0b020d187d182d0be20d0bbd183d187d188d0b520d0bed180d0b8d0b5d0bdd182d0b8d180d0bed0b2d0b0d182d18cd181d18f20d0bfd180d0b820d0bfd0bed0b8d181d0bad0b520d0bfd0bed182d0b5d0bdd186d0b8d0b0d0bbd18cd0bdd0bed0b3d0be20d181d0bed181d0b5d0b4d0b020d0b8d0bbd0b820d181d0bed181d0b5d0b4d0bad0b820d0b8d0bbd0b820d0bad0bed0bcd0bfd0b0d0bdd18cd0bed0bdd0b020d0bfd0be20d0b0d180d0b5d0bdd0b4d0b520d0bad0b2d0b0d180d182d0b8d180d18b3a3c2f703e3c703e3c7374726f6e673ed09fd0bed181d182d0b0d180d0b0d0b9d182d0b5d181d18c2c20d187d182d0bed0b1d18b20d18320d0b2d0b0d18120d0b1d18bd0bbd0b820d0bfd0bed185d0bed0b6d0b8d0b520d0b8d0bdd182d0b5d180d0b5d181d18b2e3c2f7374726f6e673e3c62723ed0add182d0be20d0bfd0bed0b7d0b2d0bed0bbd0b8d18220d0bdd0b520d182d0bed0bbd18cd0bad0be20d0bbd0b5d0b3d187d0b520d0bdd0b0d185d0bed0b4d0b8d182d18c20d0bed0b1d189d0b8d0b920d18fd0b7d18bd0ba2c20d0bdd0be20d0b820d0b2d0bcd0b5d181d182d0b520d0b7d0b0d0bdd0b8d0bcd0b0d182d18cd181d18f20d0b2d0b5d189d0b0d0bcd0b82c20d0b8d0bdd182d0b5d180d0b5d181d0bdd18bd0bcd0b820d0b2d0b0d0bc20d0bed0b1d0bed0b8d0bc20d0b820d180d0b0d0b7d0b2d0b8d0b2d0b0d182d18cd181d18f20d0b220d18dd182d0bed0bc20d0bdd0b0d0bfd180d0b0d0b2d0bbd0b5d0bdd0b8d0b82e3c2f703e3c703e3c7374726f6e673ed09ed0b4d0bdd0b0d0bad0be2c20d0bdd0b520d181d182d0bed0b8d18220d0bed0b3d180d0b0d0bdd0b8d187d0b8d0b2d0b0d182d18c20d0bfd0bed0b8d181d0ba20d182d0bed0bbd18cd0bad0be20d0bbd18ed0b4d18cd0bcd0b82c20d0bfd0bed185d0bed0b6d0b8d0bcd0b820d0bdd0b020d092d0b0d1812e3c2f7374726f6e673e3c62723ed09fd180d0b5d0b4d181d182d0b0d0b2d18cd182d0b52c20d0bad0b0d0bad0bed0b520d0b8d0bdd182d0b5d180d0b5d181d0bdd0bed0b520d0bcd0bed0b6d0b5d18220d0bfd0bed0bbd183d187d0b8d182d18cd181d18f20d0bed0b1d189d0b5d0bdd0b8d0b52c20d0b5d181d0bbd0b820d092d18b2c20d181d0bad0b0d0b6d0b5d0bc2c20d0b7d0b0d0bdd0b8d0bcd0b0d0b5d182d0b5d181d18c20d0bfd180d0b8d0bad0bbd0b0d0b4d0bdd0bed0b920d0bcd0b0d182d0b5d0bcd0b0d182d0b8d0bad0bed0b92c20d0b020d092d0b0d188d0b020d181d0bed181d0b5d0b4d0bad0b0202d2d20d181d0bed0b2d180d0b5d0bcd0b5d0bdd0bdd18bd0bc20d182d0b5d0b0d182d180d0bed0bc2c20d0b8d0bbd0b82c20d0bdd0b0d0bfd180d0b8d0bcd0b5d1802c20d092d18b20d0bbd18ed0b1d0b8d182d0b520d0b2d0bad183d181d0bdd0be20d0bfd0bed0b5d181d182d18c2c20d0b020d092d0b0d18820d181d0bed181d0b5d0b420d0bad0b0d0ba20d180d0b0d0b720d181d0bfd0b5d186d0b8d0b0d0bbd0b8d181d18220d0bfd0be20d184d180d0b0d0bdd186d183d0b7d181d0bad0bed0b920d0bad183d185d0bdd0b52e3c2f703e3c703e3c7374726f6e673ed09fd0bed181d182d0b0d180d0b0d0b9d182d0b5d181d18c20d0b7d0b0d180d0b0d0bdd0b5d0b520d0b4d0bed0b3d0bed0b2d0bed180d0b8d182d18cd181d18f20d0be20d0b2d0b0d0b6d0bdd18bd18520d0b4d0bbd18f20d092d0b0d18120d0b2d0b5d189d0b0d1853c2f7374726f6e673e3c62723ed09dd0b0d0bfd180d0b8d0bcd0b5d1802c20d0b5d181d0bbd0b820d092d18b20d0bdd0b520d0b2d18bd0bdd0bed181d0b8d182d0b520d0bad183d180d0b5d0bdd0b8d18f20d0b220d0bad0b2d0b0d180d182d0b8d180d0b52c20d0b8d0bbd0b820d0bbd18ed0b1d0b8d182d0b520d0bfd0bed0b1d18bd182d18c20d0b220d0bed0b4d0b8d0bdd0bed187d0b5d181d182d0b2d0b52c20d182d0be20d0bbd183d187d188d0b520d181d180d0b0d0b7d18320d0b8d0b7d0bbd0bed0b6d0b8d182d18c20d0b4d180d183d0b320d0b4d180d183d0b3d18320d181d0b2d0bed0b820d182d180d0b5d0b1d0bed0b2d0b0d0bdd0b8d18f20d18120d182d0b5d0bc2c20d187d182d0bed0b1d18b20d0bfd0bed182d0bed0bc20d0bdd0b520d0b1d18bd0bbd0be20d0bfd180d0bed0b1d0bbd0b5d0bc2e3c2f703e3c703e3c7374726f6e673ed09bd183d187d188d0b520d0bdd0b0d0b9d182d0b820d187d0b5d0bbd0bed0b2d0b5d0bad0b02c20d0bad0bed182d0bed180d0bed0bcd18320d0bdd183d0b6d0bdd18b20d0bfd180d0b8d0bcd0b5d180d0bdd0be20d182d0b0d0bad0b8d0b520d0b6d0b520d0b6d0b8d0b7d0bdd0b5d0bdd0bdd18bd0b520d183d181d0bbd0bed0b2d0b8d18f20d0b820d0bad0bed182d0bed180d18bd0b920d0b3d0bed182d0bed0b220d0b7d0b020d18dd182d0be20d0bfd0bbd0b0d182d0b8d182d18c2e3c2f7374726f6e673e3c62723ed0a2d0bed0b3d0b4d0b020d092d18b20d181d0bcd0bed0b6d0b5d182d0b520d0b6d0b8d182d18c20d0b8d0bcd0b5d0bdd0bdd0be20d182d0b0d0bc2c20d0b3d0b4d0b520d092d0b0d0bc20d185d0bed187d0b5d182d181d18f20d0b820d0bdd0b520d0b1d183d0b4d0b5d18220d0b2d0bed0b7d0bdd0b8d0bad0b0d182d18c20d0bfd180d0bed0b1d0bbd0b5d0bc20d181d0be20d181d0b2d0bed0b5d0b2d180d0b5d0bcd0b5d0bdd0bdd0bed0b920d0bed0bfd0bbd0b0d182d0bed0b920d0b6d0b8d0bbd18cd18f2e3c2f703e3c703e3c7374726f6e673ed095d181d0bbd0b820d092d18b20d0b8d189d0b5d182d0b520d187d0b5d0bbd0bed0b2d0b5d0bad0b020d187d0b5d180d0b5d0b720d0bed0b1d18ad18fd0b2d0bbd0b5d0bdd0b8d18f20d0bdd0b520d181d182d0bed0b8d18220d181d180d0b0d0b7d18320d0b4d0b0d0b2d0b0d182d18c20d0be20d181d0b5d0b1d0b520d0b2d181d18e20d0b8d0bdd184d0bed180d0bcd0b0d186d0b8d18e2e203c2f7374726f6e673e3c62723ed09dd0b020d181d0b0d0bcd0bed0bc20d0b4d0b5d0bbd0b52c20d0bdd0b0d0b9d182d0b820d0bfd0bed0b4d185d0bed0b4d18fd189d0b5d0b3d0be20d181d0bed181d0b5d0b4d0b020d0b8d0bbd0b820d181d0bed181d0b5d0b4d0bad18320d0bcd0bed0b6d0b5d18220d0b1d18bd182d18c20d0b4d0bed181d182d0b0d182d0bed187d0bdd0be20d181d0bbd0bed0b6d0bdd0be2c20d0bed0b4d0bdd0b0d0bad0be20d0bdd0b020d182d0be20d0b820d181d183d189d0b5d181d182d0b2d183d0b5d18220d0bdd0b0d18820d181d0b0d0b9d1823a20d187d182d0bed0b1d18b20d0bed0b1d0bbd0b5d0b3d187d0b8d182d18c20d18dd182d0bed18220d0bfd0bed0b8d181d0ba2c20d0bfd180d0b5d0b4d0bed181d182d0b0d0b2d0b8d182d18c20d092d0b0d0bc20d0b2d0bed0b7d0bcd0bed0b6d0bdd0bed181d182d18c20d180d0b0d181d188d0b8d180d0b8d182d18c20d0bad180d183d0b320d0bfd0bed0b8d181d0bad0b02c20d0b020d182d0b0d0bad0b6d0b5202d2d20d0b2d0bed0b7d0bcd0bed0b6d0bdd0bed181d182d18c20d0bed181d182d0b0d0b2d0b8d182d18c20d0b8d0bdd184d0bed180d0bcd0b0d186d0b8d18e20d0be20d181d0b5d0b1d0b52c20d187d182d0bed0b1d18b20d0bad182d0be2dd0bdd0b8d0b1d183d0b4d18c20d0b4d180d183d0b3d0bed0b92028d182d0bed0b6d0b520d0b8d189d183d189d0b8d0b920d181d0bed181d0b5d0b4d0b020d0b8d0bbd0b820d181d0bed181d0b5d0b4d0bad1832c20d0b8d0bbd0b820d18320d0bad0bed0b3d0be20d183d0b6d0b520d0b5d181d182d18c20d0bad0b2d0b0d180d182d0b8d180d0b02920d0bcd0bed0b320d0bdd0b0d0b9d182d0b820d092d0b0d1812e3c62723ed09ad180d0bed0bcd0b520d182d0bed0b3d0be2c203c7374726f6e673ed0b2d0b0d0b6d0bdd0bed0b520d0bfd180d0b5d0b8d0bcd183d189d0b5d181d182d0b2d0be20d0bdd0b0d188d0b5d0b3d0be20d181d0b0d0b9d182d0b03c2f7374726f6e673e20d0bfd0b5d180d0b5d0b420d182d180d0b0d0b4d0b8d186d0b8d0bed0bdd0bdd18bd0bcd0b820d181d0bfd0bed181d0bed0b1d0b0d0bcd0b820d0bfd0bed0b8d181d0bad0b020d0b220d182d0bed0bc2c20d187d182d0be20d0bfd180d0b820d0bed0b1d189d0b5d0bdd0b8d0b820d18120d0bfd0bed182d0b5d0bdd186d0b8d0b0d0bbd18cd0bdd18bd0bcd0b82022d181d0bed181d0b5d0b4d18fd0bcd0b82220d092d18b20d0bcd0bed0b6d0b5d182d0b520d0bdd0b520d181d0bed0bed0b1d189d0b0d182d18c20d0be20d181d0b5d0b1d0b520d0bdd0b8d0bad0b0d0bad0bed0b920d0b8d0bdd184d0bed180d0bcd0b0d186d0b8d0b82c20d182d0b0d0bad0bed0b920d0bad0b0d0ba20d0bbd0b8d187d0bdd18bd0b920d182d0b5d0bbd0b5d184d0bed0bd20d0b8d0bbd0b820652d6d61696c2c20d0bfd0bed0bad0b020d092d18b20d0bdd0b520d183d0b2d0b5d180d0b5d0bdd18b20d0b220d182d0bed0bc2c20d185d0bed182d0b8d182d0b520d0bbd0b820d092d18b20d0bbd0b8d187d0bdd0be20d0bed0b1d181d183d0b4d0b8d182d18c20d0b2d0bed0b7d0bcd0bed0b6d0bdd0bed181d182d18c20d181d0bed0b2d0bcd0b5d181d182d0bdd0bed0b3d0be20d0bfd180d0bed0b6d0b8d0b2d0b0d0bdd0b8d18f2e3c62723ed0a2d0b0d0bad0b8d0bc20d0bed0b1d180d0b0d0b7d0bed0bc2c20d0bcd0bed0b6d0b5d182d0b520d0bdd0b520d0b1d0b5d181d0bfd0bed0bad0bed0b8d182d18cd181d18f20d0be20d182d0bed0bc2c20d187d182d0be20d0bad182d0be2dd0bbd0b8d0b1d0be20d0bdd0b0d187d0bdd0b5d1822022d0b1d0bed0bcd0b1d0b0d180d0b4d0b8d180d0bed0b2d0b0d182d18c2220d0b2d0b0d18820d0bfd0bed187d182d0bed0b2d18bd0b920d18fd189d0b8d0ba20d181d0bed0bed0b1d189d0b5d0bdd0b8d18fd0bcd0b820d0b820d184d0bed182d0bed0b3d180d0b0d184d0b8d18fd0bcd0b820d0bed0b3d180d0bed0bcd0bdd0bed0b3d0be20d180d0b0d0b7d0bcd0b5d180d0b02c20d0b8d0bbd0b820d0bdd0b0d0b7d0b2d0b0d0bdd0b8d0b2d0b0d182d18c20d092d0b0d0bc20d0b4d0bed0bcd0bed0b920d0b8d0bbd0b820d0bdd0b020d181d0bed182d0bed0b2d18bd0b92e2e2e3c62723e3c2f703e, '', '', '1', '2008-05-05 16:05:24'),
(3, 2, 4, 0xd097d0b0d187d0b5d0bc20d0b6d0b8d182d18c20d0b2d0bcd0b5d181d182d0b53f, 0x3c503ed0a320d181d0bed0b2d0bcd0b5d181d182d0bdd0bed0b3d0be20d0bfd180d0bed0b6d0b8d0b2d0b0d0bdd0b8d18f20d0b5d181d182d18c20d180d18fd0b420d0bdd0b5d0bed181d0bfd0bed180d0b8d0bcd18bd18520d0bfd180d0b5d0b8d0bcd183d189d0b5d181d182d0b23a3c42523e3c5354524f4e473ed0add0bad0bed0bdd0bed0bcd0b8d18f20d181d180d0b5d0b4d181d182d0b23c2f5354524f4e473e3c42523ed097d0b020d181d187d0b5d18220d182d0bed0b3d0be2c20d187d182d0be20d092d18b20d0b1d183d0b4d0b5d182d0b520d0b4d0b5d0bbd0b8d182d18c20d180d0b0d181d185d0bed0b4d18b20d0bdd0b020d0bad0b2d0b0d180d182d0b8d180d18320d18120d0b4d180d183d0b3d0b8d0bcd0b820d0bbd18ed0b4d18cd0bcd0b82c20d092d0b0d0bc20d0bdd0b0d0b4d0be20d0b1d183d0b4d0b5d18220d0bcd0b5d0bdd18cd188d0b520d0bfd0bbd0b0d182d0b8d182d18c20d0b7d0b020d0b0d180d0b5d0bdd0b4d1832e3c2f503e0d0a3c503e3c5354524f4e473ed092d18bd0b1d0bed18020d0bbd18ed0b4d0b5d0b92c20d18120d0bad0bed182d0bed180d18bd0bcd0b820d092d18b20d0b1d183d0b4d0b5d182d0b520d0b6d0b8d182d18c3c42523e3c2f5354524f4e473ed094d0bed0bfd183d181d182d0b8d0bc2c20d092d18b20d0b6d0b8d0b2d0b5d182d0b520d18120d180d0bed0b4d0b8d182d0b5d0bbd18fd0bcd0b820d0b8d0bbd0b820d180d0bed0b4d181d182d0b2d0b5d0bdd0bdd0b8d0bad0b0d0bcd0b820d0b820d092d0b0d18120d18dd182d0be20d0bdd0b520d0bed187d0b5d0bdd18c20d183d181d182d180d0b0d0b8d0b2d0b0d0b5d1822e20d0a2d0bed0b3d0b4d0b020d0bfd180d0bed181d182d0be20d0bdd0b0d0b9d0b4d0b8d182d0b520d187d0b5d0bbd0bed0b2d0b5d0bad0b02c20d0b8d0bdd182d0b5d180d0b5d181d0bdd0bed0b3d0be20d092d0b0d0bc20d0b820d181d0bdd0b8d0bcd0b0d0b9d182d0b520d0bad0b2d0b0d180d182d0b8d180d18320d0b2d0bcd0b5d181d182d0b5213c2f503e0d0a3c503e3c5354524f4e473ed09ed0b1d189d0b5d0bdd0b8d0b52c20d0b2d181d182d180d0b5d187d0b020d18120d0b8d0bdd182d0b5d180d0b5d181d0bdd18bd0bcd0b820d0bbd18ed0b4d18cd0bcd0b83c2f5354524f4e473e3c42523ed09a20d181d0bed0b6d0b0d0bbd0b5d0bdd0b8d18e2c20d187d0b0d181d182d0be20d0b6d0b8d0b7d0bdd18c20d0b220d0b1d0bed0bbd18cd188d0bed0bc20d0b3d0bed180d0bed0b4d0b520d181d0bed0bfd180d0bed0b2d0bed0b6d0b4d0b0d0b5d182d181d18f20d0bdd0b5d185d0b2d0b0d182d0bad0bed0b920d0bed0b1d189d0b5d0bdd0b8d18f2c20d0b7d0b0d0bad180d18bd182d0bed181d182d18cd18e20d0b2d0be20d0b2d0b7d0b0d0b8d0bcd0bed182d0bdd0bed188d0b5d0bdd0b8d18fd18520d0bcd0b5d0b6d0b4d18320d0bbd18ed0b4d18cd0bcd0b82c20d095d181d0bbd0b820d092d18b20d0b1d183d0b4d0b5d182d0b520d0b6d0b8d182d18c20d18120d0bad0b5d0bc2dd182d0be20d0b5d189d0b52c20d182d0be20d18320d092d0b0d18120d0b2d181d0b5d0b3d0b4d0b020d0b1d183d0b4d0b5d18220d180d18fd0b4d0bed0bc20d187d0b5d0bbd0bed0b2d0b5d0ba2c20d18120d0bad0bed182d0bed180d18bd0bc20d0bcd0bed0b6d0bdd0be20d0bfd180d0b8d18fd182d0bdd0be20d0bfd180d0bed0b2d0b5d181d182d0b820d0b2d180d0b5d0bcd18f20d0b820d0bfd180d0bed181d182d0be20d0bfd0bed0bed0b1d189d0b0d182d18cd181d18f2e3c2f503e0d0a3c503e3c5354524f4e473ed092d0bed0b7d0bcd0bed0b6d0bdd0bed181d182d18c20d0b1d18bd182d18c20d0bdd0b0d0b5d0b4d0b8d0bdd0b520d18120d181d0bed0b1d0bed0b93c2f5354524f4e473e3c42523ed09dd0b020d0bfd0b5d180d0b2d18bd0b920d0b2d0b7d0b3d0bbd18fd0b42c20d18dd182d0be20d0bdd0b520d182d0b0d0ba20d0bed187d0b5d0b2d0b8d0b4d0bdd0be2c20d0bdd0be20d0b5d181d0bbd0b820d092d18b20d0bfd180d0b5d0b4d0bfd0bed187d0b8d182d0b0d0b5d182d0b520d0bfd180d0bed0b2d0bed0b4d0b8d182d18c20d0bad0b0d0bad0bed0b52dd182d0be20d0b2d180d0b5d0bcd18f20d0bdd0b0d0b5d0b4d0b8d0bdd0b520d18120d181d0bed0b1d0bed0b92c20d182d0be20d092d18b20d0b2d181d0b5d0b3d0b4d0b020d0bcd0bed0b6d0b5d182d0b520d0b4d0bed0b3d0bed0b2d0bed180d0b8d182d18cd181d18f20d18120d0b2d0b0d188d0b8d0bc20d181d0bed181d0b5d0b4d0bed0bc2028d0b8d0bbd0b820d181d0bed181d0b5d0b4d0bad0bed0b9292c20d0b820d092d0b0d18120d0bdd0b8d0bad182d0be20d0bdd0b520d0b1d183d0b4d0b5d18220d0b1d0b5d181d0bfd0bed0bad0bed0b8d182d18c2e20d09cd0bed0b6d0bdd0be20d0b4d0b0d0b6d0b520d0bfd0bed0bfd180d0bed181d0b8d182d18c20d0b5d0b3d0be20d0b8d0bbd0b820d0b5d0b520d0bed182d0b2d0b5d187d0b0d182d18c20d0bdd0b020d182d0b5d0bbd0b5d184d0bed0bdd0bdd18bd0b520d0b7d0b2d0bed0bdd0bad0b820d0b820d0b3d0bed0b2d0bed180d0b8d182d18c2c20d187d182d0be20d092d0b0d18120d0bdd0b5d18220d0b4d0bed0bcd0b02c20d0b5d181d0bbd0b820d18320d092d0b0d18120d0b2d0b4d180d183d0b320d0bfd0bed18fd0b2d0b8d0bbd0bed181d18c20d0b6d0b5d0bbd0b0d0bdd0b8d0b52022d0b8d181d187d0b5d0b7d0bdd183d182d18c2220d0bdd0b020d0bdd0b5d0bad0bed182d0bed180d0bed0b520d0b2d180d0b5d0bcd18f2e3c2f503e0d0a3c503e3c5354524f4e473ed09dd0bed0b2d18bd0b520d0b4d180d183d0b7d18cd18f3c2f5354524f4e473e3c42523eefbfbd3f20d18320d0b2d0b0d18120d0b820d18320d0bdd0b5d0b3d0be2028d0b8d0bbd0b820d0bdd0b5d1912920d0b5d181d182d18c20d0b4d180d183d0b7d18cd18f2c20d0bfd0bed18dd182d0bed0bcd18320d0b2d18b20d0b2d181d0b5d0b3d0b4d0b020d181d0bcd0bed0b6d0b5d182d0b520d0bfd0bed0b7d0bdd0b0d0bad0bed0bcd0b8d182d18cd181d18f20d18120d0bdd0b8d0bcd0b820d0b820d0bdd0b0d0b2d0b5d180d0bdd18fd0bad0b020d18dd182d0be20d0b4d0b0d181d18220d092d0b0d0bc20d0bcd0bdd0bed0b6d0b5d181d182d0b2d0be20d0bdd0bed0b2d18bd18520d0b8d0b4d0b5d0b92c20d0b2d0b7d0b3d0bbd18fd0b4d0bed0b22c20d0b8d0bdd182d0b5d180d0b5d181d0bed0b220d0b820d0b2d0bed0b7d0bcd0bed0b6d0bdd0bed181d182d0b5d0b920d0b220d0b6d0b8d0b7d0bdd0b82e3c2f503e0d0a3c503e3c5354524f4e473ed09dd0b5d0b7d0b0d0b2d0b8d181d0b8d0bcd0bed181d182d18c2c20d181d0b0d0bcd0bed181d182d0bed18fd182d0b5d0bbd18cd0bdd0b0d18f20d0b6d0b8d0b7d0bdd18c3c2f5354524f4e473e3c42523ed0a1d0bdd0b8d0bcd0b0d18f20d0bad0bed0bcd0bdd0b0d182d18320d0b8d0bbd0b820d0bad0b2d0b0d180d182d0b8d180d18320d0b2d18b20d0bfd0bed0bbd183d187d0b0d0b5d182d0b520d0b2d0bed0b7d0bcd0bed0b6d0bdd0bed181d182d18c20d0b6d0b8d182d18c20d0b0d0b1d181d0bed0bbd18ed182d0bdd0be20d181d0b0d0bcd0bed181d182d0bed18fd182d0b5d0bbd18cd0bdd0be2c20d0bfd0bed0bbd0bdd0bed181d182d18cd18e20d0bed182d0b2d0b5d187d0b0d18f20d0b7d0b020d182d0be2c20d0bad0b0d0ba20d092d18b20d0b6d0b8d0b2d0b5d182d0b52e20d09ad0bed0bdd0b5d187d0bdd0be2c20d0bdd183d0b6d0bdd0be20d0b1d183d0b4d0b5d18220d0b4d0bed0b3d0bed0b2d0bed180d0b8d182d18cd181d18f20d18120d092d0b0d188d0b8d0bcd0b820d181d0bed181d0b5d0b4d18fd0bcd0b820d0bfd0be20d0bad0b2d0b0d180d182d0b8d180d0b52c20d187d182d0bed0b1d18b20d092d18b20d0b4d180d183d0b320d0b4d180d183d0b3d0b020d0bdd0b520d0b1d0b5d181d0bfd0bed0bad0bed0b8d0bbd0b82c20d0b820d0b220d18dd182d0bed0bc20d181d0bcd18bd181d0bbd0b520d181d0bed0b2d0bcd0b5d181d182d0bdd0bed0b520d0bfd180d0bed0b6d0b8d0b2d0b0d0bdd0b8d0b5202d2d20d0b7d0b0d0bcd0b5d187d0b0d182d0b5d0bbd18cd0bdd18bd0b920d0bed0bfd18bd18220d0b2d0b7d0b0d0b8d0bcd0bed0b4d0b5d0b9d181d182d0b2d0b8d18f20d18120d0bbd18ed0b4d18cd0bcd0b820d0b820d181d0b0d0bcd0bed181d182d0bed18fd182d0b5d0bbd18cd0bdd0bed0b920d0b6d0b8d0b7d0bdd0b82e3c2f503e0d0a3c503e3c5354524f4e473ed092d0bed0b7d0bcd0bed0b6d0bdd0bed181d182d18c20d183d0bbd183d187d188d0b8d182d18c20d181d0b2d0bed0b820d0b6d0b8d0bbd0b8d189d0bdd18bd0b520d183d181d0bbd0bed0b2d0b8d18f3c2f5354524f4e473e2c20d181d0bdd0b8d0bcd0b0d18f20d0bad0b2d0b0d180d182d0b8d180d18320d0b2d0bcd0b5d181d182d0b520d18120d0bad0b5d0bc2dd0bbd0b8d0b1d0be3c42523ed09fd180d0b5d0b4d0bfd0bed0bbd0bed0b6d0b8d0bc2c20d092d18b20d185d0bed182d0b8d182d0b520d0b6d0b8d182d18c20d0b220d0bad0b2d0b0d180d182d0b8d180d0b520d0b220d185d0bed180d0bed188d0b5d0bc20d180d0b0d0b9d0bed0bdd0b52c20d18120d0b5d0b2d180d0be2dd180d0b5d0bcd0bed0bdd182d0bed0bc2c20d181d182d0b8d180d0b0d0bbd18cd0bdd0bed0b920d0bcd0b0d188d0b8d0bdd0bed0b92c20d185d0bed180d0bed188d0b5d0b920d0b0d183d0b4d0b8d0be2dd181d0b8d181d182d0b5d0bcd0bed0b92c20d0b2d18bd0b4d0b5d0bbd0b5d0bdd0bdd18bd0bc20d0b4d0bed181d182d183d0bfd0bed0bc20d0b220d0b8d0bdd182d0b5d180d0bdd0b5d1822e20efbfbd3f2c20d0b2d0bfd0bed0bbd0bdd0b520d0b2d0b5d180d0bed18fd182d0bdd0be2c20d187d182d0be20d0bdd0b020d0b2d181d0b520d18dd182d0be20d181d180d0b5d0b4d181d182d0b220d18320d092d0b0d18120d0bdd0b520d185d0b2d0b0d182d0b8d1822e20d09ed0b4d0bdd0b0d0bad0be2c20d182d0b0d0bad0b6d0b520d0b2d0bfd0bed0bbd0bdd0b520d0b2d0b5d180d0bed18fd182d0bdd0be2c20d187d182d0be20d18320d092d0b0d18120d183d0b6d0b520d0b5d181d182d18c20d187d182d0be2dd0bbd0b8d0b1d0be20d0b8d0b720d0b2d18bd188d0b5d0bfd0b5d180d0b5d187d0b8d181d0bbd0b5d0bdd0bdd0bed0b3d0be2c20d0b020d18320d092d0b0d188d0b5d0b3d0be20d0b1d183d0b4d183d189d0b5d0b3d0be20d181d0bed181d0b5d0b4d0b020d0b8d0bbd0b820d181d0bed181d0b5d0b4d0bad0b820d0bfd0be20d0bad0b2d0b0d180d182d0b8d180d0b520d0b5d181d182d18c20d187d182d0be2dd182d0be20d182d0b0d0bad0bed0b52c20d187d0b5d0b3d0be20d0bdd0b5d18220d18320d092d0b0d1812e20d09ed0b1d18ad0b5d0b4d0b8d0bdd0b8d0b2d188d0b8d181d18c2c20d092d18b20d0bed0b1d0b020d181d0bcd0bed0b6d0b5d182d0b520d183d0bbd183d187d188d0b8d182d18c20d181d0b2d0bed0b820d0b6d0b8d0b7d0bdd0b5d0bdd0bdd18bd0b520d183d181d0bbd0bed0b2d0b8d18f2c20d0b020d182d0b0d0bad0b6d0b520e280942022d181d0bad0b8d0bdd183d182d18cd181d18f2220d0bdd0b020d187d182d0be2dd0bdd0b8d0b1d183d0b4d18c20d0b5d189d0b53a20d0bdd0b0d0bfd180d0b8d0bcd0b5d1802c20d0bdd0b020d182d0b0d0bad183d18e20d0bfd0bed0bbd0b5d0b7d0bdd183d18e20d0bcd0b5d0bbd0bed187d18c2c20d0bad0b0d0ba20d0bcd0b8d0bad180d0bed0b2d0bed0bbd0bdd0bed0b2d0bad0b02c20d0b8d0bbd0b820d0bfd0bed0bfd0bed0bbd0b0d0bc20d0bfd0bbd0b0d182d0b8d182d18c20d0b0d0b1d0bed0bdd0b5d0bdd182d181d0bad183d18e20d0bfd0bbd0b0d182d18320d0b7d0b020d0bad0b0d0b1d0b5d0bbd18cd0bdd0bed0b520d182d0b5d0bbd0b5d0b2d0b8d0b4d0b5d0bdd0b8d0b520d0b8d0bbd0b820d0bfd180d18fd0bcd0bed0b920d0b4d0bed181d182d183d0bf20d0b220d0b8d0bdd182d0b5d180d0bdd0b5d1822e20d09020d182d0b0d0bad0b6d0b52c20d0bdd0b0d0bfd180d0b8d0bcd0b5d1802c20d181d0bad0b8d0bdd183d182d18cd181d18f20d0bdd0b020d180d0b5d0bcd0bed0bdd18220d0bad183d185d0bdd0b820d0b820d0b2d0b0d0bdd0bdd0bed0b920d0bad0bed0bcd0bdd0b0d182d18b2c20d0b8d0bbd0b820d181d0bdd0b8d0bcd0b0d182d18c20d0b2d0bcd0b5d181d182d0b520d0bad0b2d0b0d180d182d0b8d180d18320d0b220d186d0b5d0bdd182d180d0b52c20d181d18dd0bad0bed0bdd0bed0bcd0b8d0b220d0bfd0bed0bbd0bed0b2d0b8d0bdd18320d181d180d0b5d0b4d181d182d0b22e3c2f503e0d0a3c503e3c5354524f4e473ed0a1d0bfd0b5d186d0b8d0b0d0bbd18cd0bdd0be20d0b4d0bbd18f20d0bfd183d182d0b5d188d0b5d181d182d0b2d0b5d0bdd0bdd0b8d0bad0bed0b23a3c2f5354524f4e473e20d0b2d0bed0b7d0bcd0bed0b6d0bdd0bed181d182d18c20d0bfd0bed187d183d181d182d0b2d0bed0b2d0b0d182d18c20d181d0b5d0b1d18f20d0bdd0b020d0b2d180d0b5d0bcd18f2022d0bcd0b5d181d182d0bdd18bd0bc20d0b6d0b8d182d0b5d0bbd0b5d0bc223c42523ed095d181d0bbd0b820d092d18b20d0bbd18ed0b1d0b8d182d0b520d0bfd183d182d0b5d188d0b5d181d182d0b2d0bed0b2d0b0d182d18c2c20d182d0be20d0bdd0b0d0b2d0b5d180d0bdd18fd0bad0b020d092d0b0d0bc20d0bfd0bed0bdd180d0b0d0b2d0b8d182d181d18f20d0b2d0bed0b7d0bcd0bed0b6d0bdd0bed181d182d18c2c20d0bfd180d0b8d0b5d185d0b0d182d18c20d0b220d0b4d180d183d0b3d0bed0b920d0b3d0bed180d0bed0b420d0b8d0bbd0b820d181d182d180d0b0d0bdd1832c20d0bed181d182d0b0d0bdd0bed0b2d0b8d182d18cd181d18f20d0bdd0b520d0b220d0bed0b1d18bd187d0bdd0bed0b920d0b3d0bed181d182d0b8d0bdd0b8d186d0b52c20d0b020d0b4d0bed0bcd0b020d18320d0bcd0b5d181d182d0bdd18bd18520d0b6d0b8d182d0b5d0bbd0b5d0b92e20d092d18b20d0bdd0b520d182d0bed0bbd18cd0bad0be20d181d0bcd0bed0b6d0b5d182d0b520d181d18dd0bad0bed0bdd0bed0bcd0b8d182d18c20d0b7d0bdd0b0d187d0b8d182d0b5d0bbd18cd0bdd183d18e20d181d183d0bcd0bcd18320d0b4d0b5d0bdd0b5d0b32c20d0bdd0be20d0b820d0bfd0bed0b1d0bbd0b8d0b6d0b520d183d0b7d0bdd0b0d182d18c20d185d0b0d180d0b0d0bad182d0b5d18020d0b82022d0b1d18bd1822220d0bcd0b5d181d182d0bdd18bd18520d0b6d0b8d182d0b5d0bbd0b5d0b92c20d0b020d182d0b0d0bad0b6d0b5202d2d20d0bdd0b020d0b2d180d0b5d0bcd18f20d0bfd0bed187d183d0b2d181d182d0b2d0bed0b2d0b0d182d18c20d181d0b5d0b1d18f2022d181d0b2d0bed0b8d0bc222e3c2f503e0d0a3c503e3c5354524f4e473ed092d0bed0b7d0bcd0bed0b6d0bdd0bed181d182d18c20d0bed0b1d189d0b5d0bdd0b8d18f20d18120d0bbd18ed0b4d18cd0bcd0b820d0b8d0b720d0b4d180d183d0b3d0b8d18520d0b3d0bed180d0bed0b4d0bed0b220d0b820d181d182d180d0b0d0bd3c2f5354524f4e473e3c42523ed092d18b20d0b7d0b0d0bfd180d0bed181d182d0be20d0bcd0bed0b6d0b5d182d0b520d180d0b0d0b7d0bcd0b5d181d182d0b8d182d18c20d18320d181d0b5d0b1d18f20d0b220d0bad0b2d0b0d180d182d0b8d180d0b520d0b8d0bbd0b820d0bad0bed0bcd0bdd0b0d182d0b520d0bbd18ed0b4d0b5d0b920d0b8d0b720d0b4d180d183d0b3d0bed0b3d0be20d0b3d0bed180d0bed0b4d0b020d0b8d0bbd0b820d181d182d180d0b0d0bdd18b20d0bdd0b020d0bdd0b5d181d0bad0bed0bbd18cd0bad0be20d0b4d0bdd0b5d0b92e20d0add182d0be20d0bfd0bed0b7d0b2d0bed0bbd0b8d18220d092d0b0d0bc20d183d0b7d0bdd0b0d182d18c2c20d0bad0b0d0ba20d0bed0bdd0b820d0b6d0b8d0b2d183d1822c20d0bfd0bed0bfd180d0b0d0bad182d0b8d0b2d0b0d182d18cd181d18f20d0b220d0b8d0bdd0bed181d182d180d0b0d0bdd0bdd0bed0bc20d18fd0b7d18bd0bad0b52028d0b5d181d0bbd0b820d092d0b0d18820d0b3d0bed181d182d18c202d2d20d0b8d0b720d0b4d180d183d0b3d0bed0b920d181d182d180d0b0d0bdd18b292c20d0b020d182d0b0d0bad0b6d0b5202d2d20d0b2d181d0b5d0b3d0b4d0b020d0bcd0bed0b6d0bdd0be20d0b4d0bed0b3d0bed0b2d0bed180d0b8d182d18cd181d18f2c20d187d182d0be20d092d0b0d18820d0b3d0bed181d182d18c20d0bfd180d0b8d0bcd0b5d18220d092d0b0d18120d18320d181d0b5d0b1d18f20d0b4d0bed0bcd0b02c20d0bad0bed0b3d0b4d0b020d092d18b20d181d0bed0b1d0b5d180d0b5d182d0b5d181d18c20d0bfd0bed181d0b5d182d0b8d182d18c20d0b5d0b3d0be20d0b8d0bbd0b820d0b5d19120d0b3d0bed180d0bed0b420d0b8d0bbd0b820d181d182d180d0b0d0bdd1832e3c42523e3c2f503e, '', '', '1', '2008-05-06 10:28:48');
INSERT INTO `re_info_subsection` (`id`, `section_id`, `sequence`, `caption`, `content`, `description`, `keywords`, `status`, `update_date`) VALUES
(4, 2, 5, 0xd09ad0b0d0ba20d0bdd0b0d0b9d182d0b820d0bad0b2d0b0d180d182d0b8d180d18320d0b8d0bbd0b820d0bad0bed0bcd0bdd0b0d182d1832c20d187d182d0bed0b1d18b20d181d0bdd0b8d0bcd0b0d182d18c20d0b5d19120d0b2d0bcd0b5d181d182d0b53f, 0x3c503ed092d0bfd0bed0bbd0bdd0b520d0b2d0b5d180d0bed18fd182d0bdd0be2c20d187d182d0be20d18320d0bed0b4d0bdd0bed0b3d0be20d0b8d0b720d092d0b0d188d0b8d18520d0b1d183d0b4d183d189d0b8d18520d181d0bed181d0b5d0b4d0b5d0b920d0bfd0be20d0bad0b2d0b0d180d182d0b8d180d0b520d0b1d183d0b4d0b5d18220d181d0b2d0bed0b1d0bed0b4d0bdd0b0d18f20d0bad0b2d0b0d180d182d0b8d180d0b020d0b8d0bbd0b820d0bad0bed0bcd0bdd0b0d182d0b02c20d0b3d0b4d0b520d0bcd0bed0b6d0bdd0be20d0b6d0b8d182d18c2e20d09ed0b4d0bdd0b0d0bad0be2c20d0b5d181d0bbd0b820d18dd182d0be20d0bdd0b520d182d0b0d0ba2c20d0b8d0bbd0b820d0b5d181d0bbd0b820d182d0be2c20d187d182d0be20d183d0b6d0b520d0b5d181d182d18c20d092d0b0d0bc20d0bdd0b520d0bfd0bed0b4d185d0bed0b4d0b8d1822c20d0bdd0b0d0b4d0be20d0b1d183d0b4d0b5d18220d0bfd0bed182d180d0b0d182d0b8d182d18c20d0b2d180d0b5d0bcd18f2c20d187d182d0bed0b1d18b20d0bdd0b0d0b9d182d0b820d0bfd0bed0b4d185d0bed0b4d18fd189d0b5d0b520d0b6d0b8d0bbd18cd0b52e20d097d0b4d0b5d181d18c20d0bed182d0bad180d18bd0b2d0b0d0b5d182d181d18f20d0bcd0bdd0bed0b6d0b5d181d182d0b2d0be20d0b2d0bed0b7d0bcd0bed0b6d0bdd0bed181d182d0b5d0b93a20d0bfd0bed181d0bad0bed0bbd18cd0bad18320d182d0b5d0bfd0b5d180d18c20d092d18b20e2809420d0b3d180d183d0bfd0bfd0b020d0bbd18ed0b4d0b5d0b92c20d182d0be20d0bcd0bed0b6d0b5d182d0b520d181d0b5d0b1d0b520d0bfd0bed0b7d0b2d0bed0bbd0b8d182d18c20d0b1d0bed0bbd18cd188d0b520d187d0b5d0bc20d180d0b0d0bdd18cd188d0b52e20d09dd0b0d0bfd180d0b8d0bcd0b5d1802c20d0bdd0b5d0bed0b1d18fd0b7d0b0d182d0b5d0bbd18cd0bdd0be20d0b8d181d0bad0bbd18ed187d0b0d182d18c20d0b8d0b720d0bfd0bed0b8d181d0bad0b020d182d180d0b5d185d0bad0bed0bdd0bcd0b0d182d0bdd18bd0b520d0bad0b2d0b0d180d182d0b8d180d18b2c20d0b4d0b0d0b6d0b520d0b5d181d0bbd0b820d0b2d0b0d181202d2d20d0b4d0b2d0bed0b52e20d092d18b20d0b2d181d0b5d0b3d0b4d0b020d0bcd0bed0b6d0b5d182d0b520d0bdd0b0d0b9d182d0b820d0b5d189d0b520d0bed0b4d0bdd0bed0b3d0be20d187d0b5d0bbd0bed0b2d0b5d0bad0b02c20d187d182d0bed0b1d18b20d0bed0bd20d181d0bdd0b8d0bcd0b0d0bb20d182d180d0b5d182d18cd18e20d0bad0bed0bcd0bdd0b0d182d1832c20d0b2d0b5d0b4d18c20d187d0b0d189d0b520d0b2d181d0b5d0b3d0be20d186d0b5d0bdd0b020d0bdd0b020d0b4d0b2d183d18520d0b820d182d180d0b5d1852dd0bad0bed0bcd0bdd0b0d182d0bdd18bd0b520d0bad0b2d0b0d180d182d0b8d180d18b20d0bdd0b520d181d0b8d0bbd18cd0bdd0be20d180d0b0d0b7d0bbd0b8d187d0b0d0b5d182d181d18f2e3c42523ed095d181d0bbd0b820d092d18b20d0b1d183d0b4d0b5d182d0b520d0b8d181d0bad0b0d182d18c20d0bad0b2d0b0d180d182d0b8d180d18320d187d0b5d180d0b5d0b720d0b0d0b3d0b5d0bdd182d181d182d0b2d0b020d0bdd0b5d0b4d0b2d0b8d0b6d0b8d0bcd0bed181d182d0b820d18120d185d0bed180d0bed188d0b5d0b920d180d0b5d0bfd183d182d0b0d186d0b8d0b5d0b92c20d182d0be20d181d0bcd0bed0b6d0b5d182d0b520d0b1d18bd182d18c20d183d0b2d0b5d180d0b5d0bdd0bdd18bd0bcd0b820d0b220d182d0bed0bc2c20d187d182d0be20d181d0b4d0b5d0bbd0bad0b020d0bfd180d0bed0b9d0b4d0b5d18220d183d181d0bfd0b5d188d0bdd0be20d0b820d0bbd0b5d0b3d0b0d0bbd18cd0bdd0be2c20d0bed0b4d0bdd0b0d0bad0be20d092d0b0d0bc20d0bfd180d0b8d0b4d0b5d182d181d18f20d0b7d0b0d0bfd0bbd0b0d182d0b8d182d18c20d0bfd180d0b8d0bcd0b5d180d0bdd0be203130302520d0bed18220d0bcd0b5d181d18fd187d0bdd0bed0b920d0b0d180d0b5d0bdd0b4d0bdd0bed0b920d0bfd0bbd0b0d182d18b20d0bad0bed0bcd0b8d181d181d0b8d0bed0bdd0bdd18bd0b920d0b0d0b3d0b5d0bdd182d181d182d0b2d1832028d0b7d0b020d181d0bfd0bed0bad0bed0b9d181d182d0b2d0b8d0b5292e3c42523ed0a2d0b0d0bad0b6d0b52c20d0bcd0bed0b6d0bdd0be20d0bfd0bed0bfd180d0bed0b1d0bed0b2d0b0d182d18c20d0bdd0b0d0b9d182d0b820d0bdd0b0d0bfd180d18fd0bcd183d18e20d185d0bed0b7d18fd0b8d0bdd0b02c20d0bad0bed182d0bed180d18bd0b920d181d0b4d0b0d0b5d18220d0bad0b2d0b0d180d182d0b8d180d1832e20d09220d18dd182d0bed0bc20d181d0bbd183d187d0b0d0b520d092d18b20d181d18dd0bad0bed0bdd0bed0bcd0b8d182d0b520d0bdd0b020d0bad0bed0bcd0b8d181d181d0b8d0bed0bdd0bdd18bd1852c20d0bed0b4d0bdd0b0d0bad0be20d092d0b0d0bc20d181d0b0d0bcd0b8d0bc20d0bdd0b5d0bed0b1d185d0bed0b4d0b8d0bcd0be20d0b1d183d0b4d0b5d18220d0bfd180d0bed0b2d0b5d180d18fd182d18c2c20d0b5d181d182d18c20d0bbd0b820d18320d18dd182d0bed0b3d0be20d187d0b5d0bbd0bed0b2d0b5d0bad0b020d0bfd180d0b0d0b2d0be20d181d0b4d0b0d0b2d0b0d182d18c20d0bad0b2d0b0d180d182d0b8d180d18320d0b820d0bfd180d0b8d181d183d182d181d182d0b2d183d18ed18220d0bbd0b820d0b2d181d0b520d0bdd0b5d0bed0b1d185d0bed0b4d0b8d0bcd18bd0b520d0b4d0bbd18f20d18dd182d0bed0b3d0be20d0b4d0bed0bad183d0bcd0b5d0bdd182d18b2e200d0a3c503ed092d0bed182203c5354524f4e473ed0bdd0b5d181d0bad0bed0bbd18cd0bad0be20d0bad0b0d187d0b5d181d182d0b220d0bad0b2d0b0d180d182d0b8d180d18b2c20d0bdd0b020d0bad0bed182d0bed180d18bd0b520d181d182d0bed0b8d18220d0bed0b1d180d0b0d182d0b8d182d18c20d0b2d0bdd0b8d0bcd0b0d0bdd0b8d0b53a3c2f5354524f4e473e200d0a3c503e3c5354524f4e473ed09cd0b5d181d182d0bed180d0b0d181d0bfd0bed0bbd0bed0b6d0b5d0bdd0b8d0b52e3c2f5354524f4e473e3c42523ed09bd183d187d188d0b520d0b2d181d0b5d0b3d0be20d0bdd0b0d0b9d182d0b820d0bad0b2d0b0d180d182d0b8d180d18320d0b220d182d0b0d0bad0bed0bc20d0bcd0b5d181d182d0b52c20d187d182d0bed0b1d18b20d092d0b0d0bc20d0bad0b0d0ba20d0bcd0bed0b6d0bdd0be20d0b2d180d0b5d0bcd0b5d0bdd0b820d0bfd180d0b8d185d0bed0b4d0b8d0bbd0bed181d18c20d182d180d0b0d182d0b8d182d18c20d0bdd0b020d182d180d0b0d0bdd181d0bfd0bed180d1822e20d0a2d0b0d0bad0b6d0b52c20d0b6d0b5d0bbd0b0d182d0b5d0bbd18cd0bdd0be2c20d0b5d181d0bbd0b820d180d18fd0b4d0bed0bc20d0b5d181d182d18c20d0bfd0b0d180d0ba2c20d187d182d0bed0b1d18b20d0b2d181d0b5d0b3d0b4d0b020d0bcd0bed0b6d0bdd0be20d0b1d18bd0bbd0be20d0b1d18b20d0bed182d0b4d0bed185d0bdd183d182d18c20d0bdd0b020d0bfd180d0b8d180d0bed0b4d0b52e20d0a2d0be20d0b5d181d182d18c2c20d0b2d181d0b5d0b3d0b4d0b020d0bbd183d187d188d0b520d181d182d0b0d180d0b0d182d18cd181d18f20d0bdd0b0d0b9d182d0b820d0bad0b2d0b0d180d182d0b8d180d18320d0b220d182d0b0d0bad0bed0bc20d180d0b0d0b9d0bed0bdd0b52c20d0b3d0b4d0b520d0b5d181d182d18c20d0b2d181d0b520d0bdd0b5d0bed0b1d185d0bed0b4d0b8d0bcd0bed0b520d092d0b0d0bc20d0b4d0bbd18f20d0bad0bed0bcd184d0bed180d182d0bdd0bed0b920d0b6d0b8d0b7d0bdd0b82e3c42523ed09ed0b4d0bdd0b0d0bad0be2c20d0b220d182d0be20d0b6d0b520d0b2d180d0b5d0bcd18f20d0bdd0b5d0bed0b1d185d0bed0b4d0b8d0bcd0be20d183d187d0b8d182d18bd0b2d0b0d182d18c2c20d187d182d0be20d0bdd0b5d0bad0bed182d0bed180d18bd0b520d180d0b0d0b9d0bed0bdd18b20d0b4d0bed180d0bed0b6d0b520d0b4d180d183d0b3d0b8d18520d0b820d181d0bed0bed182d0b2d0b5d182d181d0b2d183d18ed189d0b8d0bc20d0bed0b1d180d0b0d0b7d0bed0bc20d0b8d181d0bad0b0d182d18c20d0bad0b2d0b0d180d182d0b8d180d1832e3c2f503e0d0a3c503e3c5354524f4e473ed0a1d0bed181d182d0bed18fd0bdd0b8d0b520d0b4d0bed0bcd0b02e3c2f5354524f4e473e3c42523ed09ad0bed0bdd0b5d187d0bdd0be2c20d0bbd183d187d188d0b52c20d0b5d181d0bbd0b820d0b4d0bed0bc20d0b220d185d0bed180d0bed188d0b5d0bc20d181d0bed181d182d0bed18fd0bdd0b8d0b820d0b820d0bad0b2d0b0d180d182d0b8d180d0b020d0bed182d180d0b5d0bcd0bed0bdd182d0b8d180d0bed0b2d0b0d0bdd0b02e20d09ed0b4d0bdd0b0d0bad0be2c20d0b5d181d0bbd0b820d18dd182d0be20d0bdd0b520d182d0b0d0ba2c20d182d0be20d092d18b20d0bcd0bed0b6d0b5d182d0b520d0bfd0bed0bfd180d0bed0b1d0bed0b2d0b0d182d18c20d0b4d0bed0b3d0bed0b2d0bed180d0b8d182d18cd181d18f20d18120d185d0bed0b7d18fd0b8d0bdd0bed0bc20d182d0b0d0bad0b8d0bc20d0bed0b1d180d0b0d0b7d0bed0bc2c20d187d182d0be20d092d18b20d0bed0bfd0bbd0b0d187d0b8d0b2d0b0d0b5d182d0b520d180d0b5d0bcd0bed0bdd1822028d187d0b0d181d182d0b8d187d0bdd0be20d0b8d0bbd0b820d0bfd0bed0bbd0bdd0bed181d182d18cd18e292c20d0b020d0bed0bd20d092d0b0d0bc20d181d0bdd0b8d0b6d0b0d0b5d18220d0b0d180d0b5d0bdd0b4d0bdd183d18e20d0bfd0bbd0b0d182d1832e3c2f503e0d0a3c503e3c5354524f4e473ed09ed0bfd0bbd0b0d182d0b02c20d181d187d0b5d182d0b02e3c2f5354524f4e473e3c42523ed09dd0b5d0bed0b1d185d0bed0b4d0b8d0bcd0be20d0b7d0b0d180d0b0d0bdd0b5d0b520d183d0b7d0bdd0b0d182d18c20d0bed0b1d189d183d18e20d181d183d0bcd0bcd18320d0bed0bfd0bbd0b0d182d18b2c20d0b020d182d0b0d0bad0b6d0b5202d2d20d182d0be2c20d0bad182d0be20d0b1d183d0b4d0b5d18220d0bed0bfd0bbd0b0d187d0b8d0b2d0b0d182d18c20d181d187d0b5d182d0b020d0bfd0be20d0bad0b2d0b0d180d182d0b8d180d0b52e3c2f503e0d0a3c503e3c5354524f4e473ed092d0b0d188d0b820d0b1d183d0b4d183d189d0b8d0b520d181d0bed181d0b5d0b4d0b82e3c2f5354524f4e473e3c42523ed095d181d0bbd0b820d092d18b20d183d0b6d0b520d0bdd0b0d188d0bbd0b820d181d0bed181d0b5d0b4d0b5d0b920d0bfd0be20d0bad0b2d0b0d180d182d0b8d180d0b52c20d0bfd0bed0bdd180d0b0d0b2d0b8d0bbd0b8d181d18c20d0b4d180d183d0b320d0b4d180d183d0b3d18320d0b820d180d0b5d188d0b8d0bbd0b820d0b6d0b8d182d18c20d0b2d0bcd0b5d181d182d0b52c20d182d0be20d0bfd180d0bed0b1d0bbd0b5d0bc20d0b1d18bd182d18c20d0bdd0b520d0b4d0bed0bbd0b6d0bdd0be2e20d09ed0b4d0bdd0b0d0bad0be2c20d0b5d181d0bbd0b820d092d18b20d181d0bdd0b8d0bcd0b0d0b5d182d0b520d0bad0bed0bcd0bdd0b0d182d1832c20d0bdd0b5d0bed0b1d185d0bed0b4d0b8d0bcd0be20d0b7d0b0d180d0b0d0bdd0b5d0b520d0b2d18bd18fd181d0bdd0b8d182d18c20d187d182d0be20d0b7d0b020d187d0b5d0bbd0bed0b2d0b5d0ba20d0b1d183d0b4d0b5d18220d0b6d0b8d182d18c20d18120d092d0b0d0bcd0b82e20d09dd0b0d0b2d0b5d180d0bdd0bed0b52c20d18dd182d0be20d0bed0b4d0b8d0bd20d0b8d0b720d181d0b0d0bcd18bd18520d0b2d0b0d0b6d0bdd18bd18520d0bad180d0b8d182d0b5d180d0b8d0b5d0b220d0b2d18bd0b1d0bed180d0b02c20d0bfd0bed18dd182d0bed0bcd18320d0bfd0bed0b4d0bed0b9d182d0b820d0ba20d0bdd0b5d0bcd18320d181d182d0bed0b8d18220d18120d0bed181d0bed0b1d18bd0bc20d0b2d0bdd0b8d0bcd0b0d0bdd0b8d0b5d0bc2e3c42523ed09fd0bed181d182d0b0d180d0b0d0b9d182d0b5d181d18c20d0b7d0b0d180d0b0d0bdd0b5d0b520d0bfd0bed0b1d0bed0bbd18cd188d0b520d183d0b7d0bdd0b0d182d18c20d0be20d185d0bed0b7d18fd0b8d0bdd0b520d0bad0b2d0b0d180d182d0b8d180d18b2c20d0bfd0bed0b3d0bed0b2d0bed180d0b8d182d18c20d18120d0bdd0b8d0bc20d0b8d0bbd0b820d18120d0bdd0b5d0b920d0bfd0be20d182d0b5d0bbd0b5d184d0bed0bdd1832028d0b8d0bbd0b820d0bfd0be20d0b8d0bdd182d0b5d180d0bdd0b5d182d18329202c20d183d0b7d0bdd0b0d182d18c2c20d187d0b5d0bc20d0bed0bd20d0b7d0b0d0bdd0b8d0bcd0b0d0b5d182d181d18f2c20d0b3d0b4d0b520d180d0b0d0b1d0bed182d0b0d0b5d1822c20d0bad183d180d0b8d18220d0b8d0bbd0b820d0bdd0b520d0bad183d180d0b8d1822c20d0b5d181d182d18c20d0bbd0b820d0b4d0bed0bcd0b0d188d0bdd0b8d0b520d0b6d0b8d0b2d0bed182d0bdd18bd0b520d0b820d0bad0b0d0bad0bed0b2d0b020d0b2d0bed0bed0b1d189d0b520d0b0d182d0bcd0bed181d184d0b5d180d0b020d0b220d0bad0b2d0b0d180d182d0b8d180d0b52e3c42523ed09dd0b0d18820d181d0b0d0b9d18220d0bfd0bed0b7d0b2d0bed0bbd18fd0b5d18220d092d0b0d0bc20d181d0b4d0b5d0bbd0b0d182d18c20d18dd182d0be2c20d0bfd0bed181d180d0b5d0b4d181d182d0b2d0bed0bc20d0bfd180d0b5d0b4d181d182d0b0d0b2d0bbd0b5d0bdd0b8d18f20d0b2d181d0b5d0b920d18dd182d0bed0b920d0b8d0bdd184d0bed180d0bcd0b0d186d0b8d0b820d0be20d185d0bed0b7d18fd0b8d0bdd0b520d0b220d0b5d0b3d0be20d0bfd180d0bed184d0b0d0b9d0bbd0b52e3c42523e3c2f503e, '', '', '1', '2008-05-06 10:28:48'),
(5, 2, 2, 0xd0a1d0bfd180d0b0d0b2d0bed187d0bdd0b0d18f20d0b8d0bdd184d0bed180d0bcd0b0d186d0b8d18f20d0b4d0bbd18f20d0bfd0bed0bbd18cd0b7d0bed0b2d0b0d182d0b5d0bbd0b5d0b9, 0x3c503e3c5354524f4e473ed096d0b8d0b7d0bdd18c20d0b2d0bcd0b5d181d182d0b52e2e2e3c2f5354524f4e473e3c2f503e0d0a3c503ed09dd0b0d0b4d0b5d0b5d0bcd181d18f2c20d187d182d0be20d092d0b0d18820d0bfd0bed0b8d181d0ba20d0b7d0b0d0b2d0b5d180d188d0b8d0bbd181d18f20d183d181d0bfd0b5d188d0bdd0be20d0b820d092d18b20d0bdd0b0d188d0bbd0b820d187d0b5d0bbd0bed0b2d0b5d0bad0b02c20d18120d0bad0bed182d0bed180d18bd0bc20d092d0b0d0bc20d0b8d0bdd182d0b5d180d0b5d181d0bdd0be20d0b6d0b8d182d18c20d0b2d0bcd0b5d181d182d0b520d0b820d0bad0b2d0b0d180d182d0b8d180d0b02c20d0b3d0b4d0b520d092d18b20d0b1d183d0b4d0b5d182d0b520d0b6d0b8d182d18c20d092d0b0d0bc20d0bdd180d0b0d0b2d0b8d182d181d18f2e3c42523ed0a2d0b5d0bfd0b5d180d18c20d0bdd0b0d187d0b8d0bdd0b0d0b5d182d181d18f20d181d0b0d0bcd18bd0b920d0b8d0bdd182d0b5d180d0b5d181d0bdd18bd0b920d18dd182d0b0d0bf202d2d20d181d0bed0b2d0bcd0b5d181d182d0bdd0b0d18f20d0b6d0b8d0b7d0bdd18c3a20d0bed0b1d189d0b5d0bdd0b8d0b52c20d0bfd180d0b8d18fd182d0bdd18bd0b520d0b2d0b5d187d0b5d180d0bdd0b8d0b520d180d0b0d0b7d0b3d0bed0b2d0bed180d18b20d0b7d0b020d187d0b0d188d0bad0bed0b920d187d0b0d18f2c20d181d0bed0b2d0bcd0b5d181d182d0bdd0bed0b520d180d0b5d188d0b5d0bdd0b8d0b520d0bdd0b0d181d183d189d0bdd18bd18520d0bfd180d0bed0b1d0bbd0b5d0bc2c20d0bdd0bed0b2d18bd0b520d0b7d0bdd0b0d0bad0bed0bcd18bd0b52c20d0bed0b1d0bcd0b5d0bd20d0bed0bfd18bd182d0bed0bc20d0b820d182d0b0d0ba20d0b4d0b0d0bbd0b5d0b52e2e2e3c42523ed09ad0bed0bdd0b5d187d0bdd0be2c20d0b8d0bdd0bed0b3d0b4d0b020d18320d0b2d0b0d18120d0bcd0bed0b3d183d18220d0b2d0bed0b7d0bdd0b8d0bad0bdd183d182d18c20d0bcd0b0d0bbd0b5d0bdd18cd0bad0b8d0b520d181d0bfd0bed180d18b20d0bdd0b020d0b1d18bd182d0bed0b2d0bed0b920d0bfd0bed187d0b2d0b52c20d0bad0bed182d0bed180d18bd0b520d0bbd0b5d0b3d0bad0be20d0bcd0bed0b6d0bdd0be20d0b8d0b7d0b1d0b5d0b6d0b0d182d18c2c20d0bfd180d0b8d0b4d0b5d180d0b6d0b8d0b2d0b0d18fd181d18c20d181d0bbd0b5d0b4d183d18ed189d0b8d18520d0bfd180d0bed181d182d18bd18520d0bfd180d0b0d0b2d0b8d0bb3a3c42523ed09ed0b1d18ad18fd181d0bdd0b8d182d0b520d0b7d0b0d180d0b0d0bdd0b5d0b520d0b4d180d183d0b320d0b4d180d183d0b3d18320d187d182d0be20d092d0b0d0bc20d0bdd180d0b0d0b2d0b8d182d181d18f2c20d0b020d187d182d0be20d0bdd0b5d1822e3c42523ed095d181d0bbd0b820d092d18b20d183d0b6d0b520d0b4d0b0d0b2d0bdd0be20d0b7d0bdd0b0d0b5d182d0b520d0b4d180d183d0b320d0b4d180d183d0b3d0b020d0b2d180d18fd0b420d0bbd0b820d0bfd180d0b8d0b4d0b5d182d181d18f20d18dd182d0be20d0b4d0b5d0bbd0b0d182d18c2c20d0bed0b4d0bdd0b0d0bad0be2c20d0b2d181d0b520d180d0b0d0b2d0bdd0be20d181d182d0bed0b8d18220d0b4d0b0d182d18c20d0bfd0bed0bdd18fd182d18c20d092d0b0d188d0b5d0bcd18320d181d0bed181d0b5d0b4d1832c20d187d182d0be2c20d092d0b0d0bc2c20d0bdd0b0d0bfd180d0b8d0bcd0b5d1802c20d0bdd0b520d0bdd180d0b0d0b2d0b8d182d181d18f20d0b2d0b8d0b4d0b5d182d18c20d183d182d180d0bed0bc20d0bdd0b020d0bad183d185d0bdd0b520d187d183d0b6d183d18e20d0b3d180d18fd0b7d0bdd183d18e20d0bfd0bed181d183d0b4d1832c20d0b820d0b220d182d0be20d0b6d0b520d0b2d180d0b5d0bcd18f20d18120d0bfd0bed0bdd0b8d0bcd0b0d0b5d0bc20d0bed182d0bdd0b5d181d182d0b8d181d18c20d0ba20d182d0bed0bcd1832c20d187d182d0be20d0b5d0bcd1832c20d0bdd0b0d0bfd180d0b8d0bcd0b5d1802c20d0bdd0b520d0bfd0bed0bdd180d0b0d0b2d0b8d182d181d18f2c20d0b5d181d0bbd0b820d0bad182d0be2dd0bdd0b8d0b1d183d0b4d18c20d0b1d183d0b4d0b5d18220d0b8d181d0bfd0bed0bbd18cd0b7d0bed0b2d0b0d182d18c20d0b5d0b3d0be20d0bbd18ed0b1d0b8d0bcd183d18e20d187d0b0d188d0bad1832e3c42523ed0a1d180d0b0d0b7d18320d0b4d0bed0b3d0bed0b2d0bed180d0b8d182d0b5d181d18c20d0be20d182d0bed0bc2c20d0bad0b0d0ba20d0b2d18b20d0b1d183d0b4d0b5d182d0b520d182d180d0b0d182d0b8d182d18c20d0b4d0b5d0bdd18cd0b3d0b82e3c42523ed09dd0b0d0bfd180d0b8d0bcd0b5d1802c20d0b1d183d0b4d0b5d182d0b520d0bbd0b820d0b2d18b20d0bfd0bed0bad183d0bfd0b0d182d18c20d0bfd180d0bed0b4d183d0bad182d18b20d0bfd0be20d0bed187d0b5d180d0b5d0b4d0b820d0b8d0bbd0b820d0bad0b0d0b6d0b4d18bd0b920d0b1d183d0b4d0b5d18220d0bfd0bbd0b0d182d0b8d182d18c20d0b7d0b020d181d0b5d0b1d18f2e20d098d0bbd0b820d0bad0b0d0ba20d092d18b20d0b1d183d0b4d0b5d182d0b520d0bed0bfd0bbd0b0d187d0b8d0b2d0b0d182d18c20d182d0b5d0bbd0b5d184d0bed0bdd0bdd18bd0b520d0b820d0b8d0bdd182d0b5d180d0bdd0b5d18220d181d187d0b5d182d0b02e3c42523ed0a3d0b2d0b0d0b6d0b0d0b9d182d0b520d0b4d180d183d0b320d0b4d180d183d0b3d0b02e3c42523ed09dd0b0d0bfd180d0b8d0bcd0b5d1802c20d0b4d0bed0b3d0bed0b2d0bed180d0b8d182d0b5d181d18c20d0be20d182d0bed0bc2c20d187d182d0be20d092d18b20d0b1d183d0b4d0b5d182d0b520d0bad183d180d0b8d182d18c20d182d0bed0bbd18cd0bad0be20d0bdd0b020d0b1d0b0d0bbd0bad0bed0bdd0b52c20d0b5d181d0bbd0b820d092d0b0d188d0b5d0b920d181d0bed181d0b5d0b4d0bad0b520d18dd182d0be20d0bdd0b520d0bdd180d0b0d0b2d0b8d182d181d18f2c20d0b8d0bbd0b820d0be20d182d0bed0bc2c20d187d182d0be20d0b5d181d0bbd0b820d0bcd183d0b7d18bd0bad0b020d0b8d0bbd0b820d182d0b5d0bbd0b5d0b2d0b8d0b7d0bed18020d0b220d092d0b0d188d0b5d0b920d0bad0bed0bcd0bdd0b0d182d0b520d0b1d183d0b4d0b5d18220d180d0b0d0b1d0bed182d0b0d182d18c20d181d0bbd0b8d188d0bad0bed0bc20d0b3d180d0bed0bcd0bad0be2c20d182d0be20d0b4d180d183d0b3d0bed0b920d0b2d181d0b5d0b3d0b4d0b020d181d0bcd0bed0b6d0b5d18220d0bdd0b520d181d182d0b5d181d0bdd18fd18fd181d18c20d0bfd0bed0bfd180d0bed181d0b8d182d18c20d181d0b4d0b5d0bbd0b0d182d18c20d0b7d0b2d183d0ba20d0bfd0bed182d0b8d188d0b52e3c42523ed09dd0b020d181d0b0d0bcd0bed0bc20d0b4d0b5d0bbd0b52c20d18dd182d0be20e2809420d0bcd0b5d0bbd0bed187d0b82c20d0b820d0b2d0be20d0b2d181d0b5d0bc20d18dd182d0bed0bc20d0b5d181d182d18c20d0bed0b4d0b8d0bd20d0bed187d0b5d0bdd18c20d185d0bed180d0bed188d0b8d0b920d0bcd0bed0bcd0b5d0bdd18220e2809420d092d18b20d0bdd0b0d183d187d0b8d182d0b5d181d18c20d0b1d0bed0bbd0b5d0b520d0bbd0b5d0b3d0bad0be20d0b0d0b4d0b0d0bfd182d0b8d180d0bed0b2d0b0d182d18cd181d18f20d0ba20d0bbd18ed0b4d18fd0bc20d0b82c20d0b220d182d0be20d0b6d0b520d0b2d180d0b5d0bcd18f2c20d183d0bcd0b5d182d18c20d184d0bed180d0bcd183d0bbd0b8d180d0bed0b2d0b0d182d18c20d181d0b2d0bed0b820d0b8d0bdd182d0b5d180d0b5d181d18b202d2d20d0bed0b1d0b020d18dd182d0b820d0bad0b0d187d0b5d181d182d0b2d0b020d0b2d181d0b5d0b3d0b4d0b020d0bed187d0b5d0bdd18c20d0bfd0bed0bcd0bed0b3d0b0d18ed18220d0b220d0b6d0b8d0b7d0bdd0b82e3c42523e3c2f503e, '', '', '1', '2008-05-05 16:05:24');
INSERT INTO `re_info_subsection` (`id`, `section_id`, `sequence`, `caption`, `content`, `description`, `keywords`, `status`, `update_date`) VALUES
(6, 11, 1, 0x5469707320666f72205265616c2045737461746520427579657273, 0x3c503e3c5354524f4e473e5469707320746f20466f6c6c6f77205768656e20427579696e67204e657720436f6e737472756374696f6e205265616c204573746174653c2f5354524f4e473e3c2f503e0d0a3c503e4e657720686f6d6520636f6d6d756e6974696573206f666665722062656175746966756c20686f6d65732c206f70656e2d666c6f6f7220706c616e732c206e6577206170706c69616e6365732c20616e64206d756368206d6f72652e20506c75732c206e657720686f6d6573206f6674656e206f6666657220656173792070757263686173696e67207468726f75676820616e206f6e2d736974652073616c6573206167656e742e205468652070726f626c656d206973207468617420746865792063616e20616c736f2074616c6c7920757020746f207369676e69666963616e74206c6f737365732e20546f2062757920796f7572206e657720636f6e737472756374696f6e20686f6d652074686520736d617274207761792c20666f6c6c6f7720746865736520746970733a3c42523e3c42523e3129205573652061205265616c746f722057686f20486173204e657720486f6d652053616c657320457870657269656e63653c42523e3c42523e4e657720686f6d656275696c646572732077696c6c20736f6d6574696d657320707574207072657373757265206f6e20796f7520746f2075736520616e206f6e2d73697465206167656e7420706c75732061207072652d617070726f766564206c656e6465722c20696e737572657220616e64207469746c6520636f6d70616e792e204974e28099732061206d697374616b65206e6f7420746f2067657420796f7572206f776e207265616c746f722e2041207265616c746f722063616e2070726f7465637420796f757220696e7465726573747320616e642063616e20656e73757265207468617420616c6c20636f73747320616e6420696e746572657374207261746573206172652077697468696e20696e647573747279207374616e64617264732e205265616c746f72732077697468206e657720686f6d6520657870657269656e6365206b6e6f772074686520686f6d656275696c64657220636f6d6d756e69747920616e6420746869732063616e20656e73757265207468617420686f6d656275696c6465727320617265207665727920636f6f706572617469766520e2809320616674657220616c6c2c207468657920646f6ee28099742077616e7420746f207461726e6973682074686569722072657075746174696f6e2e3c42523e3c42523e322920446f6ee2809974205369676e20414e595448494e4720556e74696c20596f75e280997665204e65676f7469617465642045766572792044657461696c203c42523e3c42523e416c7761797320617373756d652074686174206e6f7468696e67206973206167726565642075706f6e20756e74696c20697420697320696e2077726974696e672e204f6e6365206974e280997320696e2077726974696e672c20646f6ee280997420617373756d6520746861742069742063616e206265206368616e676564206f72206e65676f7469617465642e20446f6ee28099742066616c6c20666f722074686520e2809c77726974652075702074686520636f6e747261637420736f2074686174206e6f206f6e6520656c73652063616e2067657420796f757220686f757365e2809d20706c6f792e20496e73746561642c206d616b65207375726520746861742074686520636f6e747261637420796f75207369676e206861732065766572797468696e6720796f75206e65676f74696174656420696e2077726974696e67206265666f726520796f75207369676e2e3c42523e3c42523e332920474554204120484f4d4520494e5350454354494f4e2121213c42523e3c42523e4d616e792070656f706c6520617373756d65207468617420686f6d6520696e7370656374696f6e732061726520666f72206f6c64657220686f6d65732074686174206d61792068617665206173626573746f732c207374727563747572616c2070726f626c656d732c20616e64206f74686572206c696162696c69746965732e2054686973206973206e6f74207472756521205768696c65206d616e79206e657720636f6e737472756374696f6e7320636f6d6520776974682066756c6c2077617272616e746965732c2074686f73652077617272616e7469657320757375616c6c79206f6e6c79206c617374203132206d6f6e74687320616e64206d616e792070726f626c656d732073757266616365206f6e6c79206166746572207468617420666972737420796561722e20416e20696e646570656e64656e742c2070726f66657373696f6e616c20696e73706563746f722063616e2068656c7020796f752061766f6964207665727920636f73746c79207265706169727320612066657720796561727320646f776e20746865206c696e652e3c42523e3c42523e342920446f6ee280997420557365205468656972204c656e6465723c42523e3c42523e4d616e79206275696c646572732077686f206275696c6420656e7469726520636f6d6d756e697469657320617265206e6f77207075626c69636c792074726164656420636f72706f726174696f6e732e20546865736520636f6d70616e696573206d616b652061206c6f74206f66206d6f6e65792062792066696e616e63696e6720e28093206e6f74206a757374206275696c64696e6720616e642073656c6c696e6720e2809320686f6d65732e204173206120726573756c742c206d616e79206275696c646572732077696c6c206f6666657220796f7520656e6f726d6f757320696e63656e7469766573206f7220707265737375726520796f7520746f20757365207468656972206c656e646572732e205468652070726f626c656d206973207468617420746865206275696c646572e2809973206c656e6465722077696c6c20757375616c6c7920686176652068696768657220696e74657265737420726174657320616e642068696768657220636c6f73696e6720636f737473207468616e206120747261646974696f6e616c206c656e6465722e20496e206d6f73742063617365732c20796f752063616e2068617665207468652073746970756c6174696f6e732072656d6f76656420736f207468617420796f752063616e2063686f6f736520796f7572206f776e206c656e64657220616e6420656e6a6f7920736f6d6520696e63656e74697665732e20416674657220616c6c2c20746865206275696c6465722077696c6c206e6f74206d616b6520616e79206d6f6e657920696620796f752072656675736520746f20627579206120686f6d652e2049662061206275696c64657220696e7369737473207468617420796f7520757365207468656972206c656e6465722c2077616c6b206177617920616e642066696e6420616e6f74686572206275696c6465722e204974206d616b6573206e6f2073656e736520746f20706179206d616e792074686f7573616e6473206f6620646f6c6c617273203c42523e65787472612e3c42523e3c42523e352920526573656172636820746865204275696c6465723c42523e3c42523e4d6f7374206275696c646572732061726520726573706f6e7369626c6520616e642074616b65206361726520746f2070726f74656374207468656972206275696c74206e65696768626f72686f6f64732e205374696c6c2c206d616b652073757265207468617420796f7520726573656172636820796f7572206275696c6465722e205370656369666963616c6c792c206d616b652073757265207468617420796f7572206275696c6465722068617320612072657075746174696f6e20666f7220676f6f64207175616c69747920686f6d65732e204d616b65207375726520746861742074686520636f6d70616e79206c696d69747320696e766573746f722070757263686173657320e280932074686573652063616e20726573756c7420696e2072656e74616c2070726f7065727469657320746861742064657072656369617465206e65696768626f72686f6f642076616c75652e20416c736f2c2064657465726d696e65207768657468657220746865206275696c6465722077696c6c206275696c6420657175616c206f7220677265617465722076616c756520686f6d657320696e2074686520737572726f756e64696e6720617265612e204966207468657920646f206e6f742c20746865206e657720686f6d65732077696c6c20696e7374616e746c7920646576616c75652e3c42523e3c42523e36292043686f6f736520416e204170707261697365723c42523e3c42523e4c656e64657273207265717569726520796f75206861766520616e2061707072616973616c20616e797761792c20736f20796f75206d61792061732077656c6c207265736561726368206120676f6f642061707072616973657220796f757273656c662e2041736b20666f72206120636f7079206f662074686520617070726169736572e28099732066696e64696e67732061732077656c6c20e280932069742063616e20636f6e7461696e20696e666f726d6174696f6e20746861742077696c6c206769766520796f752062657474657220696e736967687420696e746f207768617420796f752061726520627579696e672e3c42523e3c42523e3729205265736561726368204369747920506c616e733c42523e3c42523e4e6577206e65696768626f72686f6f647320617265206f6674656e206275696c74206f6e20746865206f7574736b69727473206f6620746f776e2c207768657265206c616e6420697320617661696c61626c652061742061206c6f77657220636f73742e204265207375726520746f2061736b20796f7572207265616c746f72206f7220646f20796f7572206f776e20726573656172636820696e746f20776861742074686520636974792068617320696e206d696e6420666f722074686520617265612e20526573656172636820726f6164732c207a6f6e696e672c207075626c6963207472616e73706f72746174696f6e2c207061726b732c20616e64207363686f6f6c7320e2809320616c6c2077696c6c2064657465726d696e6520746865206675747572652076616c7565206f6620796f7572206e657720686f6d652e3c42523e3c42523e4e657720686f6d65732061726520766572792061707065616c696e6720746f206275796572732e2049662074686579e2809972652061707065616c696e6720746f20796f752c206265207375726520746f20686972652070726f66657373696f6e616c7320616e6420646f20796f757220726573656172636820736f207468617420796f7572206e657720686f6d652072656d61696e73206120706f73697469766520657870657269656e636520666f7220796561727320746f20636f6d65213c2f503e0d0a3c503e3c5354524f4e473e3c2f5354524f4e473e266e6273703b3c2f503e0d0a3c503e3c5354524f4e473e5768617420746f204578616d696e65204265666f726520427579696e67205265616c2045737461746520466f7265636c6f737572652050726f706572746965733c2f5354524f4e473e3c2f503e0d0a3c503e41726520796f7520696e746572657374656420696e20627579696e67207265616c2065737461746520666f7265636c6f737572652070726f7065727469657320776974682074686520686f706573206f66207475726e696e67207468656d20696e746f20696e766573746d656e742070726f7065727469657320616e64206d616b696e67206d6f6e65792077697468207468656d3f20496620796f75206172652c20796f75206e65656420746f2062652066616d696c6961722077697468207265616c2065737461746520666f7265636c6f737572652070726f706572746965732e204e6f74206f6e6c7920646f20796f75206e65656420746f206b6e6f7720776861742074686579206172652c2062757420796f7520616c736f206e65656420746f206b6e6f77207468652062657374207761797320746f20676f2061626f75742066696e64696e6720616e6420627579696e67207468656d2e203c42523e3c42523e5768656e20697420636f6d657320746f2066696e64696e67207265616c2065737461746520666f7265636c6f737572652070726f706572746965732c2074686572652061206e756d626572206f6620646966666572656e7420617070726f6163686573207468617420796f752063616e2074616b652e20466f7220696e7374616e63652c20796f752063616e207573652074686520696e7465726e65742e205468657265206172652061206e756d626572206f66206f6e6c696e65207265616c2065737461746520666f7265636c6f73757265206c697374696e67207365727669636573207468617420796f752063616e2075736520746f2062726f777365207468726f756768206f722073656172636820666f7220666f7265636c6f73757265732e20596f752063616e20616c736f2066696e64207265616c2065737461746520666f7265636c6f737572652070726f70657274696573206279206b656570696e6720616e20657965206f6e20796f7572206c6f63616c206e657773706170657273206f72206279206578616d696e696e6720746865207075626c6963207265636f726473206174206c6f63616c20636f756e747920636c65726b206f6666696365732e203c42523e3c42523e4e6f77207468617420796f752065786163746c7920686f7720796f752063616e20676f2061626f75742066696e64696e67207265616c2065737461746520666f7265636c6f737572652070726f706572746965732c20796f757220666f6375732073686f756c64207468656e2073776974636820746f20627579696e67207468652070726f706572746965732e204265666f726520627579696e6720616e79207265616c2065737461746520666f7265636c6f737572652070726f706572746965732c20796f7520617265206164766973656420746f206578616d696e65207468652070726f7065727469657320696e207175657374696f6e2c206173206d75636820617320706f737369626c652e2054686572652061726520736f6d6520696e7374616e63657320776865726520796f75206d617920626520726571756972656420746f206d616b652061207075726368617365206465636973696f6e20776974686f75742061637475616c6c7920736565696e67207468652070726f706572747920696e207175657374696f6e2c206275742c207769746820616e20616464726573732c20796f752073686f756c64206174206c656173742062652061626c6520746f206765742061206c6f6f6b206174207468652070726f706572747920696e207175657374696f6e2e204c6f6f6b20666f7220616e79207369676e732074686174206d617920696e64696361746520746861742072657061697273206f722075706461746573206d6179206e65656420746f206265206d6164652e20416e79206164646974696f6e616c206d6f6e6579207468617420796f752077696c6c206861766520746f20696e7665737420696e2061207265616c2065737461746520666f7265636c6f737572652070726f706572747920697320696d706f7274616e742c2061732069742073686f756c6420696d7061637420686f77206d75636820796f75206172652077696c6c696e6720746f2070617920666f72207468652070726f70657274792e203c42523e3c42523e496e206164646974696f6e20746f20746865207265616c2065737461746520666f7265636c6f737572652070726f706572747920696e207175657374696f6e2c20796f752061726520616c736f206164766973656420746f206578616d696e652069747320737572726f756e64696e67732e20466f7220696e7374616e63652c20697320746865207265616c2065737461746520666f7265636c6f737572652070726f7065727479206c6f636174656420696e206120676f6f64206e65696768626f72686f6f643f20417265207468657265206d616e792066756e2c206275742073616665206163746976697469657320616e642061747472616374696f6e73206e65617262793f2049662074686572652069732c20796f752068617665206120626574746572206368616e6365206f66207475726e696e6720612070726f6669742e205265616c2065737461746520696e766573746d656e742070726f70657274696573206172652074686f7365207468617420617265206c6174657220736f6c6420666f7220612070726f666974206f722072656e746564206f75742e20596f75206e65656420746f206e6f74206f6e6c79206d616b652073757265207468617420746865207265616c2065737461746520666f7265636c6f7375726520796f752061726520696e746572657374656420696e206973206d61726b657461626c652c2062757420796f7520616c736f206e65656420746f206d616b652073757265207468617420746865206172656120696e2077686963682074686520666f7265636c6f737572652070726f70657274792069732061732077656c6c2e203c42523e3c42523e4f6620636f757273652c20796f752077696c6c20616c736f2077616e7420746f206c6f6f6b20666f72207265616c2065737461746520666f7265636c6f737572652070726f70657274696573207468617420617265206265696e6720736f6c64206174206772656174207072696365732e204d616e79207265616c2065737461746520666f7265636c6f737572652070726f706572746965732061726520736f6c642061742070726963657320776869636820617265206c657373207468616e207468652066616972206d61726b65742076616c75652e20546869732069732077686174206d616b6573207265616c2065737461746520666f7265636c6f737572652070726f707269657469657320686967686c7920736f756768742061667465722c20706172746963756c61726c792077697468207265616c2065737461746520696e766573746f72732e204173207374617465642061626f76652c207768656e206578616d696e696e672074686520636f7374206f662061207265616c2065737461746520666f7265636c6f73757265206f72207468652062696464696e67207072696365206966206974206973206265696e672061756374696f6e6564206f66662c20796f75206e65656420746f2074616b6520616e7920706f737369626c652075706461746573206f72207265706169727320696e746f20636f6e73696465726174696f6e2e205468697320697320696d706f7274616e74206265636175736520796f752077696c6c2077616e7420746f20696e7665737420696e20676f6f64207265616c2065737461746520666f7265636c6f737572652070726f706572746965732c2062757420796f7520616c736f2077616e7420746f2074727920616e64206c696d697420796f757220696e766573746d656e74732c20696620796f752063616e20646f20736f2e20546865206c65737320796f7520696e766573742c207468652065617369657220697420697320666f7220796f7520746f206d616b6520612070726f6669742e203c42523e3c42523e5468652061626f7665206d656e74696f6e656420706f696e747320617265206a757374206120666577206f6620746865206d616e79207468617420796f752077696c6c2077616e7420746f206b65657020696e206d696e642c207768656e206c6f6f6b696e6720746f2066696e6420616e6420627579207265616c2065737461746520666f7265636c6f737572652070726f706572746965732e20466f72206164646974696f6e616c20696e666f726d6174696f6e2c20796f75206d61792077616e7420746f207468696e6b2061626f75742074616b696e672061207265616c2065737461746520696e76657374696e6720636f757273652c20706172746963756c61726c79206f6e65207468617420706c616365732061206c6172676520666f637573206f6e207265616c2065737461746520666f7265636c6f737572652070726f706572746965733c2f503e0d0a3c503e3c5354524f4e473e3c2f5354524f4e473e266e6273703b3c2f503e0d0a3c503e3c5354524f4e473e5468652052656369706520666f72205265616c2045737461746520537563636573732e2e2e2046696e64696e67204d6f746976617465642053656c6c657273266e6273703b3c2f5354524f4e473e3c2f503e0d0a3c503e496e207265616c20657374617465207468657265206973206120736179696e67207468617420796f7520646f6e5c2774206d616b6520796f7572206d6f6e6579207768656e20796f752073656c6c2c20796f75206d616b6520796f7572206d6f6e6579207768656e20796f75206275792e20546865206e616d65206f66207468652067616d652069732066696e64696e6720616d617a696e67206465616c7320616e64207468656e206b656570696e67207468656d20666f7220746865206c6f6e67207465726d206f72207475726e696e672061726f756e6420616e6420666c697070696e6720666f7220612068616e64736f6d652070726f6669742e204f6620636f757273652c206966206772656174206465616c7320776572652074686174206561737920746f2066696e642c206576657279626f647920776f756c6420626520646f696e672069742e2054686520666f72636573206f6620737570706c7920616e642064656d616e6420776f756c6420696e666c61746520746865207072696365206f662070726f7065727469657320746f2074686520706f696e74207468617420746865726520776f756c64206265206e6f206465616c73206c65667421204e617973617965727320636c61696d207468617420746869732069732074727565206f6620746f6461795c277320686f7573696e67206d61726b65742c2062757420696e207265616c6974792c2074686572652061726520656e646c657373206465616c7320746f20626520666f756e6420616c6d6f737420616e79776865726520617420616c6d6f737420616e7974696d652e2046696e64696e67207468657365206465616c732074616b657320657870657269656e636520616e642074616c656e742c2062757420746869732061727469636c65207365727665732061732061206865616420737461727420666f72206e6f7669636520696e766573746f72732c206f7220612072656672657368657220636f7572736520666f72206f6c642070726f732e3c42523e3c42523e44697374726573736564204f776e657273204d616b6520666f7220446973747265737365642050726f706572746965732028416e642056696365205665727361293c42523e3c42523e576861742069732061206772656174206465616c3f2051756974652073696d706c792c2069745c2773207768656e20796f752062757920612070726f706572747920666f722077656c6c2062656c6f77206974732061637475616c2076616c756520616e642f6f722077697468206661766f7261626c65207465726d732e20546865206f6e6c792077617920746869732063616e2068617070656e20697320666f72207468652073656c6c657220746f2062652069676e6f72616e74206f6620746865206d61726b65742c20636f6d706c6574656c7920756e696e746572657374656420696e2070726f666974206d6f74697665732c206f722065787472656d656c79206d6f7469766174656420746f2073656c6c2e20596f7572206368616e636573206f66206d616b696e67206120636172656572206f7574206f662066696e64696e6720686f6d6573206f776e65642062792070656f706c652077686f20646f6e5c2774206b6e6f7720616e7920626574746572206f722077686f20646f6e5c277420636172652061726520736c696d2c20736f2069745c2773206265737420746f20636f6e63656e7472617465206f6e206964656e74696679696e67206d6f74697661746564206f72205c22646973747265737365645c222073656c6c6572732e20416674657220616c6c2c206f6e6c7920736f6d656f6e652077686f206162736f6c7574656c79206e6565647320746f2073656c6c20697320676f696e6720746f20707269636520686973206f722068657220686f6d652077656c6c2062656c6f77206d61726b65742076616c756520616e642f6f722061636365707420756e757375616c2066696e616e63696e6720617272616e67656d656e74732e205468657365206172652074686520696e6772656469656e7473206f662061206772656174206465616c213c42523e3c42523e536f2077686174206d616b6573206120706572736f6e2061206d6f746976617465642073656c6c65723f204469766f7263652c206465617468206f6620612072656c61746976652c206a6f62207472616e736665722c20616e6420736572696f75732066696e616e6369616c2064697374726573732061726520746865206974656d73207468617420746f7020746865206c6973742e205768696c6520796f75206d69676874206665656c206775696c747920666f72205c2274616b696e6720616476616e746167655c22206f662070656f706c6520696e2073756368206120736974756174696f6e2c20796f752073686f756c646e5c27742e20416674657220616c6c2c2074686579206e65656420746f2073656c6c202d20796f75206172652068656c70696e67207468656d2120596f7520616e64207468652073656c6c6572206172652066696e64696e672061206d757475616c6c7920616772656561626c6520707269636520706f696e7420616e64207465726d732e20596f75206172652067657474696e672061206772656174206465616c20616e6420746865792061726520756e6c6f6164696e6720612068656164616368652e2049745c277320612077696e2d77696e20736974756174696f6e2e3c42523e3c42523e486f7720746f2046696e6420446973747265737365642053656c6c6572733c42523e3c42523e54686520666972737420706c61636520746f206c6f6f6b20697320746865206e65777370617065722e20446f6e5c277420626f7468657220736561726368696e67207468726f756768207468652066616e637920616473207769746820706963747572657320706c61636564206279207265616c20657374617465206167656e74733b20676f20726967687420746f2074686520636c61737369666965647320696e73746561642e204c6f6f6b20666f72206c697374696e67732077697468205c22666f722073616c65206279206f776e65725c2220696e2074686520746578742c206f722074686174206170706561722061732074686f756768207468657920617265206265696e6720736f6c6420776974686f757420616e206167656e742e20546563686e6963616c6c792c207265616c20657374617465206167656e7473206d7573742073746174652074686174207468657920617265206167656e747320696e20616c6c206164766572746973696e67206d6174657269616c732c2062757420746865206c657373207363727570756c6f7573206f6e6573206672657175656e746c79206469736f62657920746869732072756c652e20416c736f206c6f6f6b20666f72206b657920706872617365732073756368206173205c226d7573742073656c6c2c206669782d75702c206e6565647320776f726b2c20766163616e742c5c2220616e64206f6620636f757273652c205c226d6f746976617465642073656c6c6572735c222028616c74686f756768206167656e7473206f6674656e20616476657274697365205c226d6f746976617465642073656c6c65725c22207768656e20696e206661637420746865697220636c69656e742069736e5c277420616c6c2074686174206d6f7469766174656421292e20426520707265706172656420746f206d616b652061206c6f74206f662063616c6c7320616e64206e6f7420746f207370656e64206d7563682074696d65207769746820656163682073656c6c6572202d2066696e64696e67206465616c732069732061206e756d626572732067616d652c20616e6420796f75206861766520746f206d616b652061206c6f74206f662063616c6c7320746f2066696e642074686174206f6e65207370656369616c206465616c2e3c42523e3c42523e42757420796f752073686f756c646e5c2774206c696d697420796f757273656c6620746f204653424f732028686f6d6573207468617420617265205c22666f722073616c65206279206f776e65725c22292e20496e73746561642c2064726166742061206c6574746572206f6e2070726f66657373696f6e616c206c65747465726865616420616e642066617820697420746f20616c6c206f6620746865207265616c20657374617465206f66666963657320696e20796f757220617265612e204578706c61696e207468617420796f75206172652061207265616c2065737461746520696e766573746f72206c6f6f6b696e6720666f7220646973747265737365642070726f7065727469657320616e64207468617420796f752063616e20636c6f736520717569636b6c79206966207468652070726963652069732072696768742e2054686973207761792c20796f752063616e206861766520616e20656e746972652061726d79206f66207265616c20657374617465206167656e747320776f726b696e6720666f7220796f752c2066726565206f66206368617267652e204966206f6e65206f66207468656d2066696e647320612070726f706572747920666f7220796f752c207468652073656c6c6572206f662074686520686f6d652077696c6c2070617920746865206167656e745c277320636f6d6d697373696f6e2e20596f75206f7765207468656d206e6f7468696e672c20697420636f6d6573206f6666207468652073656c6c65725c277320736964652e3c42523e3c42523e416e6f74686572206964656120697320746f2063616c6c20746865206f776e657273206f662072656e74616c2070726f7065727469657320616e64206f6666657220746f206275792e204d616e7920696e636f6d652070726f7065727479206f776e657273206172652072656c756374616e74206c616e646c6f72647320616e642077696c6c206365727461696e6c7920656e7465727461696e20746865206f666665722e204966207468657920736179206e6f2c206c65617665207468656d20796f7572206e616d6520616e642070686f6e65206e756d62657220616e642074656c6c207468656d20746f2063616c6c20796f7520696620746865795c277265206576657220696e746572657374656420696e2073656c6c696e672e3c42523e3c42523e46696e616c6c792c20796f752063616e20706c61636520796f7572206f776e20636c61737369666965642061642e20412073696d706c6520686561646c696e65206c696b65205c2257652042757920486f7573657320666f7220436173685c2220776f726b7320626573742e20446f6e5c277420776f7272792074686174206f7468657220696e766573746f727320757365207468652073616d65206164732c2069745c27732061206e756d626572732067616d652e20536f6d6574696d65732070656f706c652077696c6c2073656c6c20746f20796f7520626563617573652074686579206c696b65207468652077617920796f7520736f756e64206f72207468657920747275737420796f75206f76657220796f757220636f6d70657469746f722e20486f77206d616e79206164766572746973656d656e7420646f20796f752073656520696e2074686520706170657220666f72206d6f72746761676520636f6d70616e6965732c20636172206465616c65727320616e642072657461696c2073746f7265732073656c6c696e67207468652073616d652070726f647563743f2054686572655c277320656e6f75676820627573696e65737320746f20676f2061726f756e642c20616e6420736f206c6f6e6720617320796f7520676574207468652070686f6e652072696e67696e672c20796f755c276c6c206c6561726e20746f2067657420676f6f6420617420636f6e76657274696e67207468656d20696e746f206465616c732e203c42523e3c42523e4279206b6e6f77696e67207768617420796f755c277265206c6f6f6b696e6720666f72202d2064697374726573736564206f776e657273202d20616e6420666f6c6c6f77696e6720746865736520737472617465676965732c20796f752077696c6c20616c726561647920626520776179206168656164206f66206d6f737420626567696e6e696e67207265616c2065737461746520696e766573746f72732e2049742074616b657320776f726b2c20616e64206c6f7473206f662069742c206275742074686520726577617264732061726520776f7274682069742e266e6273703b3c42523e3c5354524f4e473e266e6273703b3c2f5354524f4e473e3c2f503e0d0a3c503e3c5354524f4e473e266e6273703b3c2f5354524f4e473e3c5354524f4e473e5468696e677320746f20436865636b204f7574204265666f726520427579696e67204120486f7573653c2f5354524f4e473e3c2f503e3c5354524f4e473e3c2f5354524f4e473e0d0a3c503e496620796f75e280997265207468696e6b696e672061626f757420627579696e67206120686f7573652c20796f75e280996c6c20686176652061206e756d626572206f66207468696e6773207468617420796f75e280996c6c2077616e7420746f207370656369666963616c6c79206c6f6f6b20696e746f206265666f726520796f7520646f2e20546869732061727469636c652077696c6c206769766520796f752061206e756d626572206f662073756767657374696f6e732061626f75742065786163746c79207768617420666163746f727320746f206c6f6f6b20696e746f206265666f726520796f75206d616b65207468652062696720706c756e676520696e746f206265696e67206120686f6d656f776e65722e3c42523e3c42523e46697273742c20616c776179732061736b2061726f756e6420616d6f6e6720746865206e65696768626f7273206265666f726520796f75206275792e20596f75e280996c6c206265207375727072697365642061626f75742077686174206d69676874207475726e2075702e204966207468657265e2809973206265656e2062616420626c6f6f642c2061206e65696768626f72206d696768742062652077696c6c696e6720746f2072657665616c2065766572792070726f626c656d2074686579206b6e6f772061626f757420776974682074686520686f7573652e2054686579e280996c6c20616c736f2062652061626c6520746f20636c756520796f7520696e20746f207468696e67732074686174206d6179206e6f7420626520612070726f626c656d20776974682074686520686f75736520696e20706172746963756c617220627574206d6179206265207769746820746865206e65696768626f72686f6f6420696e2067656e6572616c2e20546869732063616e20696e636c7564652061206e756d626572206f66207468696e67732e2052656d656d62657220746f2061736b2061626f75743a20776865746865722074686520686f757365206f7220746865206e65696768626f72686f6f6420697320696e206120666c6f6f64207a6f6e652c20776865746865722074686572652061726520616e792070726f626c656d206e65696768626f7273206e65617262792c20776865746865722074686579206b6e6f77206f6620616e792070726576696f75732064616d6167652c20616e6420776865746865722074686572652069732061206372696d652070726f626c656d2e20596f752063616e2070726f6261626c79207468696e6b206f662061626f7574206120646f7a656e206f74686572207468696e677320746f2061736b202d2074616c6b2077697468207365766572616c206e65696768626f72732c20616e6420696620796f752066696e6420746865206c6f63616c20676f737369702c20796f75e280996c6c20626520696e206f6e2065766572797468696e6720796f75206e65656420746f206b6e6f772e20416c77617973206d616b652073757265207468617420796f7520646f63756d656e74207768617420726570726573656e746174696f6e7320746865206f776e6572206d616b657320746f20796f752061626f75742074686520686f757365202d20697420636f756c6420636f6d6520696e2068616e6479206c617465722c20657370656369616c6c7920696620746865726520617265206d616a6f7220756e646973636c6f7365642070726f626c656d7320776974682069742e20446f2061206c6974746c6520736561726368696e67206f6e2074686520696e7465726e6574202d20796f752063616e20616c7761797320646f20612073656172636820666f7220746865206e616d65206f662074686520686f6d656f776e657220616e642073656520696620616e797468696e6720696e746572657374696e6720636f6d65732075702e204966207468657265e280997320736f6d657468696e67207368616479206f722074686579e28099726520756e7472757374776f727468792c20796f752077616e7420746f206b6e6f772061626f75742069742e204279207468652073616d6520746f6b656e2c20796f752063616e206f6674656e20656173696c7920736565206966207468657920617265206c65676974696d6174652074686174207761792e204d616b652073757265207468617420796f75e280997665206861642061207469746c652073656172636820646f6e65202d20796f7572207265616c20657374617465206167656e742077696c6c2070726f6261626c792074616b652063617265206f662069742c20627574206974e28099732061206d7573742d686176652e204869726520612068616e64796d616e20746f20696e73706563742074686520706c61636520696620796f75206172656ee280997420676f6f642077697468207468617420736f7274206f66207468696e67202d206f72206a7573742067657420736f6d656f6e6520796f7520747275737420746f206c6f6f6b2061726f756e642e20497420646f65736ee28099742074616b65206d75636820746f206d616b652073757265207468617420796f757220686f7573652077696c6c206265206120676f6f6420696e766573746d656e742e3c42523e3c5354524f4e473e266e6273703b3c2f5354524f4e473e3c2f503e0d0a3c503e3c5354524f4e473e3130205468696e677320596f75204d75737420446f204265666f726520427579696e67206120486f6d653c2f5354524f4e473e3c2f503e0d0a3c503e427579696e67206120686f6d65206973206f6674656e20746865206c61726765737420706572736f6e616c2066696e616e6365207472616e73616374696f6e206120706572736f6e206d616b657320696e20686973206f7220686572206c6966652e20536f2069745c277320637269746963616c207468617420796f75206d616b6520746865207269676874207072657061726174696f6e7320616e6420646f207468652070726f7065722072657365617263682e205265676172646c657373206f6620756e6971756520736974756174696f6e7320616e64207370656369616c2063697263756d7374616e6365732c207468657265206172652074656e207468696e677320796f75206d75737420646f206265666f726520627579696e67206120686f6d652e3c42523e3c42523e312e2053747564792074686520686f6d6520627579696e672070726f636573732e3c42523e3c42523e546869732077696c6c20616c6c6f7720796f7520746f206d616b6520626574746572206465636973696f6e7320616e642061637420636f6e666964656e746c792e20486f6d6520627579696e67206c696e676f2069732061206269672070617274206f6620746869732c20736f206265207375726520746f2072656164207468726f75676820612066657720686f6d652d627579696e6720676c6f73736172696573206265666f726520796f752067657420696e746f2074686520746869636b206f66207468696e67732e3c42523e3c42523e322e204f627461696e20796f757220637265646974207265706f72742e3c42523e3c42523e476574206120636f7079206f6620796f757220637265646974207265706f727420616e642072657669657720697420666f72206572726f72732e20596f752063616e2067657420636f706965732066726f6d20616c6c207468726565206372656469742062757265617573206174206f6e6365206279207669736974696e67207777772e416e6e75616c4372656469745265706f72742e636f6d2e204d6f727467616765206c656e646572732077696c6c2072657669657720796f757220637265646974207769746820612066696e652d746f6f7468656420636f6d622c20736f20796f752073686f756c6420646f207468652073616d65202e2e2e206265666f72652074686579207265766965772069742e3c42523e3c42523e332e2046697820637265646974206572726f727320717569636b6c792e3c42523e3c42523e496620796f752066696e6420616e206572726f72206f6e20796f757220637265646974207265706f72742c20676f20746f2074686520636f6d70616e795c2773207765627369746520776865726520746865207265706f72742063616d652066726f6d20285472616e73556e696f6e2c2045717569666178206f7220457870657269616e2920746f20636f6e746573742069742e2049742063616e2074616b652074696d6520746f20636c65616e20757020616e206572726f6e656f757320637265646974207265706f72742c20736f20676574207374617274656420617320736f6f6e20617320796f752073706f7420746865206572726f722e3c42523e3c42523e342e20436865636b20796f757220646562742d746f2d696e636f6d6520726174696f2e3c42523e3c42523e4d6f727467616765206c656e64657273206c696b6520746f20736565206120626f72726f7765725c2773206465627420617420286f722062656c6f772920323025206f66206e6574206d6f6e74686c7920696e636f6d652e20496620796f75722064656274206578636565647320323025206f6620796f7572206e6574206d6f6e74686c7920696e636f6d652c2074727920746f2070617920697420646f776e20666f72206170706c79696e6720666f722061206d6f727467616765206c6f616e2e20596f755c276c6c206861766520616e20656173696572207175616c696669636174696f6e2070726f6365737320616e642077696c6c206c696b656c79207175616c69667920666f7220612062657474657220726174652e3c42523e3c42523e352e2044657465726d696e6520796f7572206275646765742e3c42523e3c42523e55736520616e206f6e6c696e65206d6f7274676167652063616c63756c61746f7220746f2067657420616e2069646561206f6620686f77206d75636820796f752063616e206166666f726420746f207061792065616368206d6f6e74682c20616e6420776861742074686174206571756174657320746f20696e207465726d73206f66206120686f6d652070726963652e20546869732077696c6c206769766520796f7520612062756467657420746f20776f726b2066726f6d2c2077686963682077696c6c2068656c7020796f752077656564206f75742074686520686f6d6573207468617420617265206265796f6e6420796f757220636f6d666f7274207a6f6e652e3c42523e3c42523e362e20537461727420736176696e6720796f757220636173682e3c42523e3c42523e54686973206973206f6e65206f66207468652062657374207468696e677320796f752063616e20646f206265666f7265207374617274696e672074686520686f6d6520627579696e672070726f636573732c20666f72206120636f75706c65206f6620726561736f6e732e204669727374206f6620616c6c2c206d6f727467616765206c656e64657273206c696b6520746f20736565207468617420796f75206861766520736f6d652063617368207265736572766573206f6e2068616e642e205365636f6e646c792c20796f755c276c6c206e656564206361736820726573657276657320666f7220616e7920756e65787065637465642066656573206f7220636f7374732074686174206d696768742061726973652028776869636820697320636f6d6d6f6e292e3c42523e3c42523e372e20476574207072652d617070726f76656420666f722061206c6f616e2e3c42523e3c42523e447572696e67207072652d617070726f76616c2c2061206d6f727467616765206c656e6465722077696c6c2072657669657720796f7572206372656469742c2066696e616e6365732c20646562742c206574632e20616e6420636f6e646974696f6e616c6c79207175616c69667920796f7520666f722061206365727461696e20616d6f756e74206f66206d6f7274676167652e2053656c6c6572732077696c6c2074616b6520796f75206d6f726520736572696f75736c7920696620796f7520686176652061207072652d617070726f76616c206c65747465722c20616e64207468652070726f6365737320616c736f2068656c7073206964656e7469667920616e792070726f626c656d73207769746820796f757220637265646974206f72206f74686572207175616c696679696e6720666163746f72732e3c42523e3c42523e382e2041766f6964206e6577206c696e6573206f66206372656469742e3c42523e3c42523e54727920746f206b65657020796f75722066696e616e6369616c20736974756174696f6e206173205c22737461626c655c2220616e64206661766f7261626c6520617320706f737369626c652e2049745c2773206120676f6f64206964656120746f2070617920646f776e20736f6d6520646562742028736565206974656d2023342061626f76652920616e6420746f207361766520757020736f6d6520636173682e204275742074686520776f727374207468696e6720796f752063616e20646f2069732074616b65206f75742061206e6577206c6f616e202f206c696e65206f66206372656469742e20417420626573742c207468697320636f756c64206d616b6520746865207175616c696669636174696f6e2070726f636573732074616b65206c6f6e6765722e20417420776f7273742c20697420636f756c6420746970207468652064656274207363616c657320696e746f20746865205c2267726561746572207468616e203230255c22207a6f6e652c2077686963682077696c6c206d616b652069742068617264657220746f206765742061206c6f616e2e3c42523e3c42523e392e2056616c6964617465207468652061736b696e672070726963652e3c42523e3c42523e49745c27732063616c6c656420616e205c2261736b696e672070726963655c2220666f72206120676f6f6420726561736f6e2e204e6f2061736b696e672070726963652069732073657420696e2073746f6e652c20616e642065766572797468696e6720696e207265616c20657374617465206e65676f746961626c652e20536f20646f6e5c27742061636365707420616e2061736b696e67207072696365206173206265696e6720726561736f6e61626c6520756e74696c20796f752076616c6964617465206974207468726f756768206361726566756c2072657365617263682e20436f6d706172652074686520686f6d65202f20707269636520746f20726563656e742073616c657320696e2074686520617265612e20596f7572207265616c20657374617465206167656e742063616e2070726f76696465206120636f6d7061726174697665206d61726b657420616e616c797369732028434d412920746f2068656c7020796f752077697468207468697320737465702e3c42523e3c42523e31302e20476574206120686f6d6520696e7370656374696f6e2e3c42523e3c42523e4974206973206e65766572202d2d2049207265706561742c206e65766572202d2d207769736520746f20736b69702074686520686f6d6520696e7370656374696f6e2e204120686f75736520697320612073697a61626c6520696e766573746d656e742c20616e6420746865206c617374207468696e6720796f752077616e7420697320746f2066696e6420612062756e6368206f66207468696e67732077726f6e67207769746820697420616674657220796f755c2776652074616b656e206f776e6572736869702e20486f6d6520696e7370656374696f6e73206172652076657279206166666f726461626c652c20616e6420796f752063616e6e6f74207075742061207072696365206f6e20746865207065616365206f66206d696e6420796f755c276c6c2068617665206173206120726573756c74206f6620796f757220696e7370656374696f6e2e3c42523e266e6273703b3c2f503e, '', '', '1', '2008-07-16 15:40:11');
INSERT INTO `re_info_subsection` (`id`, `section_id`, `sequence`, `caption`, `content`, `description`, `keywords`, `status`, `update_date`) VALUES
(7, 11, 2, 0x53656c6c6572733a20507265706172696e6720796f757220486f6d6520666f722053616c65, 0x3c503e3c5354524f4e473e507265706172696e6720596f757220486f6d6520666f722053616c653c2f5354524f4e473e3c2f503e0d0a3c503e507265706172696e6720746f2073656c6c20796f757220686f6d652063616e2062652073747265737366756c20616e642074696d6520636f6e73756d696e672e204b6e6f77696e672077686174206b696e64206f66207468696e677320746f20666f637573206f6e2063616e2067726561746c792072656475636520796f75722073747265737320616e64206b65657020796f7520666f6375736564206f6e207468652070726f6a6563747320796f75206e65656420746f20756e64657274616b6520696e20707265706172696e6720796f757220686f6d6520666f722073616c652e20486176696e67206120686f6d65207468617420697320636c65616e20616e6420696e20676f6f6420636f6e646974696f6e2077696c6c207479706963616c6c792073656c6c20666f7220612068696768657220707269636520616e64206d6f726520717569636b6c79207468616e20686f6d6573207468617420617265206e6f742077656c6c206d61696e7461696e65642e3c42523e3c42523e54686520646966666572656e6365206265747765656e206120686f6d6520696e20676f6f6420636f6e646974696f6e20616e64206f6e652074686174206973206e6f74206d616e792074696d65732069732061732073696d706c652061732061206e6577207061696e74206a6f62206f72207265706c6163696e672064616d61676564206669787475726573207468726f7567686f75742074686520686f6d652e2050757420796f757273656c6620696e2074686520706f74656e7469616c20627579657273e280992073686f657320616e642074616b65206120676f6f642068617264206c6f6f6b20617420796f757220686f6d652e205768617420776f756c6420796f752077616e7420746f206861766520666978656420696620796f75207765726520746f20627579207468697320686f7573653f3c42523e3c42523e5768656e20707265706172696e6720746f2073656c6c20796f757220686f6d6520646f6ee2809974206e65676c6563742074686520796172642e20596f7572207961726420697320746865206669727374207468696e6720686f6d65206275796572732077696c6c207365652e204b65657020796f75206c61776e206d6f77656420616e6420686564676573207472696d6d65642e20496620697420697320737072696e672074696d6520706c616e7420736f6d6520666c6f7765727320696e207468652066726f6e7420746f2061646420736f6d6520636f6c6f722e205069636b20757020616e7920636c7574746572207468616e20697320696e20796f757220796172642c2066726f6e7420616e64206261636b2e2049e280997665207365656e20686f6d657320776865726520616c6c20697320676f696e672077656c6c20616e642049206c696b652077686174204920616d20736565696e6720756e74696c20492067657420746f20746865206261636b7961726420616e64206974206c6f6f6b73206c696b652061206a756e6b20796172642e205479706963616c6c792c207468652073656c6c6572206f662074686520686f6d652077696c6c20636c65616e2069742075702062757420736f6d6574696d6573207468657920646f6ee280997420616e6420746865206275796572206973206c656674207769746820746865206d6573732e20506c75732075706f6e20736565696e6720746865206d65737320746865206275796572206d617920666f726765742061626f75742074686520676f6f64207468696e677320616e642072656d656d62657220796f757220686f6d652062792c20e2809c746865206f6e65207769746820616c6c20746865206372617020696e2074686520796172642ee2809d3c42523e3c42523e436f6e7369646572207061696e74696e6720796f757220686f6d652c20657370656369616c6c792074686520696e736964652e204e65776c79207061696e7465642077616c6c732061646420746f20746865206f766572616c6c20617070656172616e6365206f6620796f757220686f6d6520616e64206d61792068656c702069742073656c6c2e2052657061697220646f6f72732c2077696e646f77732c2077616c6c732c20616e6420616e797468696e6720656c73652074686174206e6565647320746f2072657061697265642e20496620746865726520617265207468696e6773207468617420796f752063616ee2809974206166666f726420746f20726570616972206174207468652074696d65206f7220696620796f7520617265206e6f7420696e746572657374656420696e20726570616972696e67206974206b6e6f7720746861742069742077696c6c207265666c65637420696e207468652070726963652070656f706c65206172652077696c6c696e6720746f2070617920666f7220796f757220686f6d652e3c42523e3c5354524f4e473e266e6273703b3c2f5354524f4e473e3c2f503e0d0a3c503e3c5354524f4e473e496e657870656e73697665205469707320746f2053656c6c20486f757365733c2f5354524f4e473e3c2f503e0d0a3c503e496e2074686520706173742079656172732c2073656c6c696e6720686f7573657320666f637573206f6e6c79206f6e2072656372656174696e6720746865206578746572696f7220736f20617320746f20696e76697465206275796572732e20486f77657665722c206120736869667420686173206e6f77206f63637572726564207768657265696e2074686520656d70686173697320697320676976656e20746f77617264732074686520696e6e65722061707065616c2061732077656c6c2e20416674657220616c6c2c207468657265206973206e6f20706f696e7420696e2073656c6c696e6720286f7220627579696e672920612062656175746966756c20686f757365206a756467696e67206279207468652065787465726e616c206f66666572732069742068617320696620796f752061726520676f696e6720746f20736163726966696365207468652064657369676e206f662074686520696e746572696f722e3c42523e3c42523e4c6f6f6b20617420796f7572206f776e20686f7573652077697468206120637269746963616c206579652c20617320696620796f752061726520736f6d65626f6479206c6f6f6b696e6720617420616e6f7468657220706572736f6e5c277320686f7573652e20446f6ee2809974206469736d69737320616e7920666c617773207468617420796f75207468696e6b206172652061636365707461626c6520656e6f756768206f6e20796f7572206f776e207065727370656374697665732e205468696e6b20617320696620796f75722070726f737065637420627579657273206172652074686520776f727365206372697469637320796f752077696c6c206861766520616e6420796f757220667574757265206469676e69747920776f756c64206c6965206f6e207468656d2e20537472616e67652069736ee28099742069743f204275742077652074656c6c20796f752c207468697320746563686e697175652069732065666665637469766520656e6f75676820746f2077617272616e742065617379206d61726b657420666f7220796f752e2054686973206973206265636175736520796f75206861766520616c7265616479206c696d697465642074686520706f73736962696c6974696573206f662072656a656374696f6e20616e64207363727574696e792e3c42523e3c42523e417320796f75206d6179206861766520616c7265616479206e6f74696365642c2074686572652061726520766172696f75732074656c65766973696f6e2070726f6772616d732074686174206c65742070656f706c6520736565207768617420746865792063616e20646f20696e207468656972206f776e20686f75736573207468617420636f756c64206d616b65207468656d2061707065616c696e6720666f72206275796572732e20496e206d6f73742063617365732c2074686520686f7573657320617265207265636f6e737472756374656420696e20696e657870656e73697665207761797320796574206361757365206472616d61746963206368616e6765732e2057656c6c2c20697420776f756c642068656c7020696620796f755c276c6c20666f6c6c6f77207468652073756767657374696f6e73207375636820686f6d6520696d70726f76656d656e742073686f77732070726f766964652e3c42523e3c42523e42757420796f75206d75737420616c77617973206b65657020696e206d696e64207468617420796f75206861766520746f206d6178696d697a65207468652065666665637473206f662073756368206368616e676573207468726f75676820696e63757272696e67206c657373657220657870656e7365732e20546875732c20796f75206d757374207365656b2074686520626573742070726f737065637473207468617420776f756c64206272696e67206f757420746865206265737420696e20796f757220686f757365207768696c65206e6f7420746178696e6720796f75722077616c6c65742e3c42523e3c42523e466f72206f6e6520726561736f6e206f7220616e6f746865722c2074686520666c6f6f72207365656d7320746f206d6174746572206d75636820616e642062757965727320746f6f6b206e6f74696365206f6e207468652074797065206f6620666c6f6f72696e6720796f752068617665207573656420696e20796f757220686f7573652e20416c736f2c20666f637573206f6e2074686520636f6e646974696f6e206f6620796f757220666c6f6f72696e672e2049662074686973206e65656473207265636f6e737472756374696f6e207468656e2073656520746f2069742074686174206974206973207265636f6e737472756374656420696e20746865206c6561737420706f737369626c6520657870656e73657320796f752063616e20686176652e3c42523e3c42523e446f6ee28099742068617374656e20746865206368616e6765732074686f7567682e20596f752077696c6c206861766520746f20706c616e207468652064657369676e20616e6420746865206f7574636f6d65206f6620746865206368616e67657320696e20796f757220666c6f6f72696e672e20476f20666f722065636f6e6f6d6963616c207965742061707065616c696e6720666c6f6f722074696c657320616e64206f74686572207479706573206f6620666c6f6f72696e672e20546865726520617265206c6f7473206f66206f7074696f6e7320666f7220796f752e20496e20666163742c20746865726520697320616c6d6f737420616e20656e646c657373206c697374206f66206d6174657269616c7320796f752063616e2075736520746f206675726e69736820796f757220666c6f6f7220776974682e20416d6f6e6720746865206d6f737420636f6d6d6f6e20617265206c616d696e617465642074696c65732c20636572616d69632074696c65732c20636f726b2074696c65732c20776f6f6420616e64206f74686572732e204f6620636f757273652c207468652074797065206f66206d6174657269616c20796f752077696c6c2063686f6f73652077696c6c206265206c617267656c7920646570656e64656e74206f6e2074686520656e746972652073657474696e67206f6620796f757220696e746572696f722e3c42523e3c42523e536f6d6520686f75736573207365656d20746f2068617665206974732064697374696e6374206f646f7220746861742061637420617320726570656c6c616e7420616761696e73742070726f6d6973696e67206275796572732e20596f75206d6179206e6f74206b6e6f7720746869732062757420796f75206d75737420756e6465727374616e64207468617420616e7920706f737369626c65207475726e206f6666732073686f756c642062652072656d6f7665642e3c42523e3c42523e54686973206973207479706963616c6c79206861726420746f206a756467652e2053696e636520796f75206861766520616c7265616479206c6976656420696e20796f757220686f75736520616e642068617320616c7265616479206265636f6d65206163637573746f6d656420746f2069747320736d656c6c2c20697420776f756c64206265206861726420666f7220796f7520746f2064657465637420616e7920756e77616e746564206f646f722e20496e207468697320636173652c20796f75206d7573742061736b2074686520617373697374616e6365206f6620616e6f7468657220706572736f6e2077686f206861736ee2809974206265656e20696e20796f757220686f75736520666f72206c6f6e672e20576861746576657220686973206f7220686572206f70696e696f6e2069732c20796f75206861766520746f20616e616c797a6520616e6420636f6e7369646572207468656d20696e2067726561742064657461696c2e3c42523e3c42523e416c736f2c2074616b652061206c6f6f6b206f6e20796f75722077616c6c732e204d6179626520746865726520617265206d61726b7320746861742073686f756c64206e6f742062652074686572652e204f72207065726861707320637261636b732061726520616c726561647920617070656172696e67206f6e20736f6d65206f662069747320706f7274696f6e732e2057616c6c20626c656d697368657320636f756c642061666665637420626f74682074686520707269636520616e642074686520696e746572657374206f6620746865206275796572206f6620796f757220686f7573652e2042652073757265207468617420796f75206861766520636f6d706c6574656c7920666978656420796f75722077616c6c73207468617420657870616e73696f6e20637261636b7320616e64206e61696c20706f707320626172656c792068617665207468656972207472616365732e3c42523e3c42523e546865736520617265206a75737420736f6d65206d6561737572657320796f7520636f756c642075736520746f2068656c702067657420746865206869676865722070726963657320666f7220796f757220686f75736573207768696c6520696e63757272696e67206d696e696d756d20657870656e736573206f6e2074686520726570616972732e3c42523e3c42523e3c5354524f4e473e53656c6c20596f757220486f6d6520417420546f7020446f6c6c6172205769746820416e20496e657870656e736976652046616365204c6966743c2f5354524f4e473e3c2f503e0d0a3c503e4861766520796f7520657665722077687920736f6d6520686f6d65732073656c6c20666173746572207468616e206f74686572733f20497420636f756c642062652074686520766572792073616d65206d6f64656c20696e207468652073616d65206e65696768626f72686f6f642c20616e64206f6e6520686f6d65206d6f76657320717569636b6c79207768657265617320736f6d65206a7573742072656d61696e206f6e20746865206d61726b6574207769746820646973696e7465726573746564206275796572732e20486572652061726520736f6d65207469707320746f20737461676520796f757220686f6d6520746f206e6f74206f6e6c792073656c6c2069742c2062757420746f2067657420746f7020646f6c6c61722e3c42523e3c42523e46697273742c206578616d696e6520796f757220686f6d652066726f6d207468652063757262736964652061732074686f75676820796f7520686164206e65766572207365656e206974206265666f72652e204974206d617920626520646966666963756c7420746f207265616c6c7920707574206f6e20796f7572206f626a65637469766520676c61737365732c2062757420696620796f752063616e2c20796f752077696c6c20626520737572707269736564206f6620686f77206163637573746f6d6564207765206265636f6d6520746f20746861742062726f6b656e20676174652c207468652064616d616765642067617261676520646f6f722c206f72207468652067617264656e20686f736520746861742069736e3f7420726f6c6c65642075702e205468657365206d617920736f756e64206c696b65206d696e6f72206973737565732c2062757420746f2074686520686f6d652062757965722c20746865793f726520746f7572696e6720796f757220686f6d6520776974682061206d61676e696679696e6720676c61737320616e64206120776869746520676c6f76652e20426567696e20796f7572206576616c756174696f6e2062792077726974696e6720646f776e2065766572797468696e6720796f75206e6f74696365207468617420636f756c6420696d70726f766520796f75722070726f706572747920696e2074686520736c69676874657374207761792e20486572652061726520736f6d6520696e657870656e736976652c2065617379207469707320746f20656e68616e636520796f757220686f6d6520616e642067657420746f7020646f6c6c6172213c42523e3c42523e4265207375726520746f207265706c616365206f72207472696d20747265657320616e6420706c616e747320616e6420747279206c6976656e696e6720757020796f75722067617264656e207769746820736f6d6520636f6c6f7266756c2c206672616772616e7420666c6f77657273206e6561722074686520656e7472616e63652e20496e20736f6d652063617365732c20796f752063616e206576656e207573652062656175746966756c2073696c6b20666c6f72616c2070696563657320696e206c6172676520706f7473206173206c6f6e672061732074686579206c6f6f6b207265616c213c42523e3c42523e5661726e697368206f72207061696e742074686520656e7472616e636520646f6f72207769746820737562746c6520636f6c6f7273207375636820617320626c61636b2c206275726e742062726f776e2c20677261792c20666f7265737420677265656e2c206f722077686974652e20496620796f753f726520686f6d65206973206f6c6465722c207265706c61636520746865206f6c6420646f6f722077697468206120676c61737320656e7472616e636520646f6f722e20496620796f75206861766520612073637265656e20646f6f722c20636f6e73696465722070757263686173696e67206f6e65206f6620746865206e65776572206d6f64656c7320616e64206d616b65207375726520746865206c6174636820776f726b7320666f72206561737920656e7472792e20466972737420696d7072657373696f6e7320616e6420616e74696369706174696f6e206f662077616c6b696e6720696e746f20796f7572203f647265616d20686f7573653f20626567696e7320686572652e3c42523e3c42523e546865206669727374207468696e672070656f706c65206e6f746963652069732069662074686520686f6d652069732062726967687420616e64206368656572792e20546865206c617374207468696e6720686f6d656275796572732077616e7420746f20646f2069732077616c6b20696e746f2061206461726b20686f6c6520616e6420646570656e64206f6e2061207265616c746f7220746f207475726e206f6e2028616e64206f66662920746865206c696768747320616e64206f70656e2028616e6420636c6f73652920616c6c2074686520626c696e6473206f72207368616465732e2054686520636865657279206772656574696e6720796f757220686f6d6520676976657320746f2070656f706c652077696c6c2067697665207468656d20616e20696d6d65646961746520696d7072657373696f6e20617320746f20686f772074686579204645454c2061626f757420796f757220686f6d652e20496620796f75206861766520706574732c206265207375726520746f207061636b206177617920616c6c20746865697220746f797320616e6420646f6720666f6f74206f72206b69747479206c6974746572732e204b65657020796f757220686f6d6520736d656c6c696e6720667265736820616e6420636c65616e207468617420646f65736e5c277420726570656c207468652070726f737065637469766520686f6d6562757965722e3c42523e466c6f6f72696e67206973206f6e65206f6620746865206d6f737420696d706f7274616e7420666561747572657320686f6d656275796572732077696c6c206a756467652e20436c65616e207468652063617270657420616e6420646f6e3f7420726571756573742074686174206275796572732074616b65206f66662074686569722073686f65732e20446f2065766572797468696e6720696e20796f757220706f77657220746f2077656c636f6d652076697369746f727320616e64206b656570207468656d2074686572652e20416e792065786375736520796f752067697665207468656d204e4f5420746f207669657720796f757220686f6d652077696c6c206c6f77657220796f7572206e756d626572732e2052656d656d62657220746865206d6f72652070656f706c652077686f207669657720796f757220686f6d652c2074686520626574746572206368616e636520796f75206861766520696e2073656c6c696e672069742e3c42523e3c42523e5265706c6163652062617468726f6f6d206669787475726573207769746820746865206e65776572206f6e6573203f486f6c6c79776f6f64206c696768747320617265206f75742e204d616b65207375726520746865206261746874756220697320736372756262656420636c65616e2061732077656c6c2061732073686f776572207374616c6c732e204275792061206e65772073686f776572206375727461696e2c206966206e656365737361727920616e64206361756c6b2061726f756e642074696c657320696e207468652073686f77657220616e64207475622e20426520617761726520746861742062726967687420636f6c6f7273206f72206368696c6472656e5c277320636f6c6f7273206d61792062652061207475726e2d6f666620746f206e6577206275796572732e205061696e7420796f75722077616c6c732077697468206e65757472616c20636f6c6f72732073756368206173206265696765206f72206f66662077686974652e3c42523e3c42523e54616b65206f75742074686520636c75747465722c20676172626167652c207374696e6b792073686f65732c20616e642070757420796f757220646972747920636c6f7468657320696e207468652068616d7065722e2049745c2773207665727920696d706f7274616e7420746f20686176652074686f7365206265647320666978656420696e20746865206d6f726e696e6720746f6f2120436c65616e2074686520736c6964696e6720676c61737320646f6f7220746f2074686520706174696f2061732077656c6c2061732074686520706174696f206675726e69747572652e2043757420616e64207472696d20746865206772617373206f6674656e20666f7220612066726573682c20636c65616e20736d656c6c2e3c42523e3c42523e50656f706c652077616e7420746f20696d6167696e652074686569722066616d696c696573206c6976696e6720696e2074686569722066757475726520686f6d6520616e6420796f75206861766520746865206162696c69747920746f20736574207468652073746167652e2054686520686f6d652073686f756c6420626520636f6d706c6574652077697468206261722073746f6f6c732061742074686520627265616b66617374206e6f6f6b2c20736f6674206c69676874696e672c20616e64206e696365206675726e69747572652e2052656d656d6265722c20746865206261636b7961726420686173206265636f6d6520616e20657874656e73696f6e206f662074686520686f6d652e2053686f77206f666620796f7572204242512c20706174696f206675726e6974757265206f72206368696c6472656e5c277320706c6179206172656120696e20612077617920746861742074686579206665656c207468656972206f776e2066616d696c7920776f756c6420656e6a6f792e3c42523e3c42523e496620796f752068617665206120706f6f6c2c20686176652074686520706f6f6c2067757920636f6d65206f76657220736f20796f757220706f6f6c20697320737061726b6c696e6720636c65616e2e2042757920736f6d65205461686974692063616e646c6520746f726368657320666f7220612074726f706963616c206c6f6f6b2e20496620796f7520686176652061206261722c20706c61636520706c617374696320676c617373657320616e642074726179732061732074686f75676820796f75207765726520726561647920746f206861766520612070617274792e20496620796f7520646f6e3f742068617665206120636f766572656420706174696f2c20796f75206d61792077616e7420746f206d616b65206120736d616c6c20696e766573746d656e7420627920627579696e6720612063616e76617320636162616e612074686174207761726d732074686520686561727420616e642073657473206120726f6d616e746963206d6f6f642e2050757420736f6d652063616e646c6573206f6e20796f757220706174696f207461626c6520616e6420616363656e74756174652077697468206c6f7473206f6620706c616e747320737572726f756e64696e672074686520706174696f2e3c42523e3c42523e426c696e64732c20706c616e746174696f6e2073687574746572732c20616e6420526f6d616e207368616465732061726520696e2e20546865206c6561737420657870656e736976652c20627574206d6f737420706f70756c61722063686f6963652c20697320746f207265706c61636520796f757220766572746963616c73206f72206f75746461746564206d696e692d626c696e6473207769746820776f6f64656e20626c696e64732e20546865793f7265206561737920746f20696e7374616c6c20796f757273656c6620616e642077696c6c206d616b6520612062696720646966666572656e636520696e20746865206f766572616c6c206c6f6f6b206f662074686520686f6d652e20596f75206d61792066696e6420736f6d652076657279206e69636520526f6d616e2073686164657320746861742061726520726561736f6e61626c6520616e642063616e206265207573656420696e206c696575206f66206375727461696e732061732061207472696d2e204375727461696e2070616e656c732066726f6d207468652068696768206365696c696e67732077696c6c20736572766520617320612077696e646f772074726561746d656e7420616e642061646420656c6567616e6365206f72206d61676e697475646520746f20796f757220726f6f6d20616c736f2e3c42523e3c42523e4d616b65207375726520796f7572206b69746368656e20616e642062617468726f6f6d732061726520616c7761797320636c65616e20616e642072656d656d626572207468617420686f6d656275796572732077696c6c206c6f6f6b20696e20796f75722070616e74727920616e6420636c6f736574732e20596f752063616e20616c776179732066616b6520697420627920636c65616e696e67207468652073696e6b73206576657279206d6f726e696e67206265666f7265206c656176696e6720666f7220776f726b20616e6420776970696e6720646f776e20746865206d6972726f72732069662074686572652061726520616e79206e6f7469636561626c652073706f74732e204e65766572206c6561766520646972747920646973686573206f7220706f747320616e642070616e7320696e207468652073696e6b2e204a7573742068696465207468656d20696e2074686520646973687761736865722e3c42523e3c42523e4461726520746f206265206f70656e20656e6f75676820746f20676574206f70696e696f6e732066726f6d20796f757220667269656e64732c206e65696768626f72732c20616e64207265616c746f722e2041736b2069662074686579206861766520616e792073756767657374696f6e7320617320746f20686f7720746f20617272616e676520796f7572206675726e697475726520616e642062652077696c6c696e6720746f206769766520697420612074727920746f2073656520686f77206974206c6f6f6b732e205468656972207065727370656374697665206d6179206d616b6520612062696720646966666572656e636520696e20796f757220656e7469726520666c6f6f7220706c616e2e2050656f706c6520617265206c6f6f6b696e6720666f7220737061636520736f20796f752077616e7420746f20646f2077686174657665722069742074616b657320746f206d616b6520796f757220686f6d65206c6f6f6b206f70656e20616e642073706163696f75732e3c42523e3c42523e496620796f75206665656c207468617420796f757220626f74746f6d206c696e652063616e2068616e646c65206d6f726520657870656e7369766520757067726164657320746f20636f6d7065746520616761696e7374206f74686572206e6577657220686f6d65732c20796f75206d61792077616e7420746f207265706c616365206f6c6420636172706574696e6720776974682031385c222074696c6520616e64206e657720636172706574696e6720696e2074686520626564726f6f6d732077697468206120746869636b207061642e204920776f756c646ee28099742073756767657374206368616e67696e6720796f757220636f756e746572746f707320746f206772616e697465206f72206368616e67696e6720746865206b69746368656e20636162696e657473206173207468617420776f756c64206265207665727920657870656e7369766520616e64206d6179206e6f7420626520636f6d70656e736174656420756e6c65737320796f752061726520646f696e67206120746f74616c2072656e6f766174696f6e2e3c42523e3c42523e3c5354524f4e473e53656c6c696e67206120486f6d65202d20546865205072657061726174696f6e2053746167653c2f5354524f4e473e3c2f503e0d0a3c503e546865726520617265206365727461696e2070726f63657373657320746861742061726520766974616c20696e20616e7920656e646561766f722e20416e642073656c6c696e67206120686f7573652c206265696e6720736f6d657468696e672074686174207472756c7920706f7373657320697473206f776e20646966666963756c746965732c20616c736f207265717569726520736f6d652061637469766974696573207468617420776f756c64207072657061726520697420666f72207468652073756273657175656e742070726f6365737365732e20486572652061726520736f6d65206f662074686520696e697469616c207374616765732074686174206d61726b20746865207072657061726174696f6e206f6620796f757220686f75736520666f722073616c652e3c42523e3c42523e47657474696e6720737461727465643c42523e3c42523e596f752077616e7420796f757220686f75736520746f20626520696d707265737369766520616e6420667269656e646c7920656e6f756768207769746820667574757265206275796572733f205468656e20636c6561722074686520636c75747465722e3c42523e54686973206d6967687420626520616d6f6e6720746865206d6f737420646966666963756c74207468696e6720796f7520776f756c6420646f207769746820796f757220686f7573652e20447572696e6720796f7572207965617273206f66206f776e6572736869702c20796f75206d69676874206861766520617474616368656420656d6f74696f6e7320696e746f20796f757220686f757365207468617420697420776f756c64206265206861726420746f2064657461636820796f757273656c662066726f6d2069742e204275742074686973206973206d6f7265207468616e20746861742e3c42523e3c42523e5965617273206f6620656d6f74696f6e616c206174746163686d656e7420636f756c64206d65616e207965617273206f6620636c757474657220636f6c6c656374696f6e2e20576520636f6c6c65637420616c6c20736f727473206f66206d6174657269616c73207468617420636f756c6420626520646966666963756c7420746f207365706172617465206f757273656c7665732066726f6d2e205468697320636c757474657220776f756c642062652065766964656e74206f6e2074686520746f70207368656c7665732c20647261776572732c20636f756e746572746f70732c20636c6f736574732c206174746963732c206761726167657320616e6420626173656d656e74732e3c42523e3c42523e57656c6c2c20696620796f752077616e7420746f2073656c6c20796f757220686f75736520696d6d6564696174656c792c207468656e20796f75206d757374206c65742074686520627579657220736565206d6f7265206f70656e207370616365732e3c42523e4c6f6f6b207468656e20617420796f757220686f7573652066726f6d20616e20616e6f6e796d6f757320706f696e74206f6620766965772e2054727920646973636f6e6e656374696e6720796f757220656d6f74696f6e7320666f7220617768696c6520616e64207365652069742066726f6d20616e6f7468657220706572736f6e5c277320657965732e20496620796f752063616e5c277420646f2074686973207468656e2066696e6420736f6d656f6e652077686f2077696c6c20676c61646c7920646f20697420666f7220796f752e3c42523e596f75206d69676874206e6f74207265616c697a652074686520657874656e74206f662068656c70207468697320616374697669747920636f756c64206272696e672062757420796f7520776f756c6420736f6f6e2066696e642074686174207468652070726563696f7573206d6174657269616c7320696e20796f7572206579657320617265206e6f74206173206d7563682070726563696f75732066726f6d207468652076696577206f6620616e6f746865722e3c42523e3c42523e546869732063616e5c277420626520646f6e6520756e6c65737320796f75207374617274207468696e6b696e6720796f757220686f757365206173206120636f6d6d6f646974792e20496620796f7520776f756c642068617665206e6f74696365642c207768656e20796f7520627579206120686f75736520746865207265616c20657374617465206167656e747320776f756c6420726566657220746f20697420617320796f7572205c22686f6d655c222062757420696620796f75206172652073656c6c696e67206f6e652c20686520776f756c64206d616b65206974206120706f696e7420746f2063616c6c206974206173205c2270726f70657274795c222e20546869732068656c7073207468652070726f63657373206f66207265616c697a6174696f6e206f63637572206661737465722073696e636520776f72647320636f6e6e6f7465206576656e2074686520656d6f74696f6e7320746861742061726520656d626f6469656420696e20656163682061727469636c652e3c42523e3c42523e596f7572207265616c697a6174696f6e206d75737420636f6d652066726f6d2074686520706f696e742074686174206974206973206e6f206c6f6e67657220796f757220686f6d6520627574207468656972732e20427920646f696e6720746869732c20796f752077696c6c2072652d6368616e6e656c20706f73736962696c6974696573207468617420636f756c6420696e616476657274656e746c79206d616b65207468652073656c6c696e672061206c6f6e6765722070726f636573732e3c42523e3c42523e53657061726174652074686520706572736f6e2066726f6d2074686520706572736f6e616c6974793c42523e3c42523e5768656e2073656c6c696e67206120686f7573652c20626520707265706172656420746f207365706172617465206576656e20746865206d6f73742076616c7561626c65207468696e67732066726f6d2069742e20497420776f756c6420646f20796f75206e6f20676f6f6420696620796f7520776f756c64206c656176652070696374757265206672616d6573207769746820796f7572206f776e2070686f746f73206f6e2069742e20496e73746561642c206c6561766520616e6f6e796d6f7573206974656d73207468617420776f756c64206d616b6520746865206275796572206665656c20746861742074686973206973206120706f74656e7469616c20686f6d6520666f722068696d20616e64206e6f742061207472616365206f6620796f7572206f776e20706572736f6e616c69747920636f756c6420626520666f756e642e3c42523e3c42523e496620746865206275796572206f6620796f75722070726f7065727479207365657320616e797468696e67207468617420776f756c642072656d696e642068696d20746861742074686973206973206e6f742068697320686f6d65207965742c207375636820617320612066616d696c7920706963747572652068616e67696e67206f6e207468652077616c6c2c20796f7520636f756c64207368617474657220746865697220686f706573207468617420746869732069732074686520706f74656e7469616c20686f6d6520666f72207468656d2e20546865736520776f756c64206c6561766520796f7572206d61726b7320696e2074686520686f75736520776869636820696e207475726e2c20636f756c6420726570656c207468652070726f7370656374206f662072656372656174696e672074686520706572736f6e616c697479206f662074686520686f6d652066726f6d207468656972206f776e20706f696e74206f6620766965772e3c42523e3c42523e52656d6f766520616c6c207468696e6773207468617420776f756c642072656d696e642074686520627579657273206f6620746865206d656d6f72696573206f66207468652070726576696f7573206f776e6572732e20507574207468656d20616c6c20696e2073746f7261676520736f6d6577686572652061706172742066726f6d207468652061747469632c2067617261676520616e6420626173656d656e742e3c42523e3c42523e5468652074776f2073756767657374696f6e73207765206861766520676976656e206865726520617265206e6f74206f6e6c792065666665637469766520666f72206d616b696e6720796f757220686f75736520612062697420667269656e646c69657220666f722073616c652e205468697320616c736f2065617365732074686520686172642070726f63657373206f66206c657474696e6720676f206f6620736f6d657468696e6720796f752068617665206f776e656420796f757273656c6620666f72206c6f6e672e20556e64656e6961626c792c207468697320776f756c642074616b65206d756368206566666f7274206f6e20796f757220736964652073696e6365206974206973206e6f742074686174206561737920746f206d616b6520796f7520666f72676574206f6620736f6d657468696e6720796f752068617665206163637573746f6d656420796f757273656c6620746f206c6f76652e3c42523e3c2f503e, '', '', '1', '2008-07-16 15:35:58'),
(8, 11, 3, 0x332054697073205768656e2050726963696e6720596f757220486f6d65, 0x5768656e20627579696e67206120686f6d6520666f72207468652066697273742074696d65206974206973206d6f7374206c696b656c792074686520626967676573742066696e616e6369616c206465636973696f6e20796f752068617665206d6164652074687573206661722e204e6f7720796f7520617265206174206120706f696e74207768656e2073656c6c696e6720796f757220686f6d652c20666f722077686174206576657220726561736f6e2c206973206a75737420617320626967206f6620612066696e616e6369616c206465636973696f6e20617320627579696e672e204e6f206d617474657220776861742074686520726561736f6e20666f722073656c6c696e6720796f757220686f6d6520796f75207374696c6c2077616e7420746f20676574206173206d756368206f6620796f757220696e766573746d656e74206261636b20617320706f737369626c652e205468657265206172652033206b657920706f696e747320746f20636f6e7369646572207768656e2070726963696e6720796f757220686f6d652c206d61726b657420636f6e646974696f6e732c20746172676574696e672c20616e642070726963652e3c42523e3c42523e486176696e67206120676f6f6420756e6465727374616e64696e67206f66207768617420746865206c6f63616c207265616c20657374617465206d61726b657420697320646f696e6720697320696d706f7274616e74207768656e2064657465726d696e696e6720746f2070757420796f757220686f6d6520757020666f722073616c652e20446570656e64696e67206f6e20796f75722063697263756d7374616e636573206974206d6179206265207769736520746f20686f6c64206f666620756e74696c20746865207265616c20657374617465206d61726b657420636f6e646974696f6e7320696d70726f76652e20486f77657665722c207468657265206172652074696d6573207768656e20796f75206e65656420746f2073656c6c20796f757220686f6d6520617320717569636b20617320706f737369626c652e205768656e20746865207265616c20657374617465206d61726b65742069732068756d6d696e67207769746820616374697669747920616e6420746865726520617265206d6f726520627579657273207468616e2073656c6c657273206f66207175616c69747920686f6d657320796f752077696c6c206c696b656c7920676574206d6f72652072657475726e206f6e20796f757220696e766573746d656e74207768656e2073656c6c696e6720796f757220686f6d652e204a75737420746865206f70706f73697465206d6179206f63637572207768656e20746865726520617265206d6f72652073656c6c657273207468616e206275796572732e20446966666572656e742074696d6573206f662074686520796561722063616e2061666665637420626f74682062757965727320616e642073656c6c6572732e204279206b6e6f77696e67207468652065666665637473206f662074686520736561736f6e73206f6e20746865207265616c20657374617465206d61726b657420796f75206d61792066696e6420796f757220686f6d652077696c6c2073656c6c2061742061206869676865722072617465206f662072657475726e20647572696e6720746861742074696d65206672616d652e20466f72206578616d706c652c20647572696e672074686520737072696e6720616e642073756d6d6572206d6f6e7468732074686572652074656e6420746f206265206d6f72652073656c6c6572732077686963682c206d616b657320746865206d61726b657420686967686c7920636f6d70657469746976652e20486f77657665722c20696620796f75206c69737420796f757220686f6d6520696e207468652066616c6c20616e642077696e746572206d6f6e746873207468657265206d6179206265206c65737320636f6d7065746974696f6e20657370656369616c6c792069662074686520636c696d6174652069732068617273682e3c42523e3c42523e496e206f7264657220746f2073656c6c20796f757220686f6d6520796f75206861766520746f206861766520746865207265736f757263657320746f2074617267657420706f74656e7469616c2062757965727320666f7220796f75722070726f70657274792e205265616c20657374617465206167656e7473206861766520746865206162696c69747920616e64206b6e6f7720686f7720746f20646f206a75737420746861742e20576974682074686520757365206f66206d656469612c20746563686e6f6c6f67792c20616e64206e6574776f726b696e67207265616c20657374617465206167656e74732063616e206769766520796f757220686f6d6520746865206578706f73757265206e656564656420746f2073656c6c20796f757220686f6d652e2054686520496e7465726e657420686173206265636f6d65206120706f77657266756c20746f6f6c20696e20746f646179e2809973207265616c20657374617465206d61726b657420666f722067657474696e6720796f757220686f6d6520696e2066726f6e74206f6620706f74656e7469616c206275796572732e20496620796f7520686f6d65206973206e6f74206c697374656420696e207769746820746865206c6f63616c207265616c20657374617465206c697374696e67207365727669636520796f7520636f756c6420706f74656e7469616c6c792062652063757474696e67206f757420373525206f72206d6f7265206f6620696e746572657374656420686f6d65206275796572732e3c42523e3c42523e46696e616c6c792c20746865206d6f737420696d706f7274616e74207468696e6720746f20636f6e7369646572207768656e2073656c6c696e6720796f757220686f6d652069732070726963652e205479706963616c6c792c207768656e2074686520686f6d6520796f75206172652073656c6c696e67206f6e20746865207265616c20657374617465206d61726b65742069732077656c6c207072696365642069742077696c6c2073656c6c20717569636b6c792e204275796572732074686573652064617973206172652077656c6c20656475636174656420616e642074686579207479706963616c6c7920757365207265616c20657374617465206167656e747320746f2066696e6420686f6d6573207468617420666974207468656972206e656564732e20496620796f757220686f6d65206973206f7665722d707269636564206974206d617920676574206f7665722d6c6f6f6b656420666f72207468652073696d706c6520726561736f6e2074686174206974206973206f7665722d7072696365642e3c42523e, '', '', '1', '2008-07-16 15:42:14');

-- --------------------------------------------------------

--
-- Table structure for table `re_interests`
--

DROP TABLE IF EXISTS `re_interests`;
CREATE TABLE IF NOT EXISTS `re_interests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_interest_user` int(11) NOT NULL DEFAULT '0',
  `id_interest_ad` int(11) NOT NULL DEFAULT '0',
  `interest_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_interest_user` (`id_interest_user`),
  KEY `id_interest_ad` (`id_interest_ad`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_interests`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_language`
--

DROP TABLE IF EXISTS `re_language`;
CREATE TABLE IF NOT EXISTS `re_language` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT NULL,
  `charset` varchar(100) DEFAULT NULL,
  `lang_path` varchar(100) DEFAULT '0',
  `name` varchar(25) DEFAULT NULL,
  `visible` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `re_language`
--

INSERT INTO `re_language` (`id`, `code`, `charset`, `lang_path`, `name`, `visible`) VALUES
(1, 'en-us', 'UTF-8', '/lang/english/', 'english', '0'),
(2, 'ru', 'UTF-8', '/lang/russian/', 'russian', '1'),
(3, 'ger', 'UTF-8', '/lang/german/', 'german', '0');

-- --------------------------------------------------------

--
-- Table structure for table `re_logo_settings`
--

DROP TABLE IF EXISTS `re_logo_settings`;
CREATE TABLE IF NOT EXISTS `re_logo_settings` (
  `id` int(3) NOT NULL,
  `type` varchar(255) NOT NULL,
  `width` int(4) NOT NULL,
  `height` int(4) NOT NULL,
  `pic_1` varchar(255) NOT NULL,
  `alt_1` tinyblob NOT NULL,
  `pic_2` varchar(255) NOT NULL,
  `alt_2` tinyblob NOT NULL,
  `pic_3` varchar(255) NOT NULL,
  `alt_3` tinyblob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_logo_settings`
--

INSERT INTO `re_logo_settings` (`id`, `type`, `width`, `height`, `pic_1`, `alt_1`, `pic_2`, `alt_2`, `pic_3`, `alt_3`) VALUES
(1, 'logotype', 205, 40, 'logotype.png', 0x5265616c457374617465202d2073656c6c696e672c20627579696e672c206c656173696e6720616e642072656e74696e67205265616c20457374617465, 'logotype.png', 0x5265616c457374617465202d20d0bfd180d0bed0b4d0b0d0b6d0b02c20d0bfd0bed0bad183d0bfd0bad0b02c20d0b0d180d0b5d0bdd0b4d0b02c20d181d0b4d0b0d187d0b020d0b220d0b0d180d0b5d0bdd0b4d18320d0bdd0b5d0b4d0b2d0b8d0b6d0b8d0bcd0bed181d182d0b8, 'logotype.png', 0x5265616c20457374617465202d205665726b6175662c204b6175662c204d6965746520756e64205061636874),
(2, 'homelogo', 74, 70, 'home_logo.png', 0x5265616c457374617465202d2073656c6c696e672c20627579696e672c206c656173696e6720616e642072656e74696e67205265616c20457374617465, 'home_logo.png', 0x5265616c457374617465202d20d0bfd180d0bed0b4d0b0d0b6d0b02c20d0bfd0bed0bad183d0bfd0bad0b02c20d0b0d180d0b5d0bdd0b4d0b02c20d181d0b4d0b0d187d0b020d0b220d0b0d180d0b5d0bdd0b4d18320d0bdd0b5d0b4d0b2d0b8d0b6d0b8d0bcd0bed181d182d0b8, 'home_logo.png', 0x5265616c20457374617465202d205665726b6175662c204b6175662c204d6965746520756e64205061636874),
(3, 'slogan', 600, 17, 'slogan.gif', 0x5265616c457374617465202d2073656c6c696e672c20627579696e672c206c656173696e6720616e642072656e74696e67205265616c20457374617465, 'slogan.gif', 0x5265616c457374617465202d20d0bfd180d0bed0b4d0b0d0b6d0b02c20d0bfd0bed0bad183d0bfd0bad0b02c20d0b0d180d0b5d0bdd0b4d0b02c20d181d0b4d0b0d187d0b020d0b220d0b0d180d0b5d0bdd0b4d18320d0bdd0b5d0b4d0b2d0b8d0b6d0b8d0bcd0bed181d182d0b8, 'slogan.gif', 0x5265616c20457374617465202d205665726b6175662c204b6175662c204d6965746520756e64205061636874);

-- --------------------------------------------------------

--
-- Table structure for table `re_mailbox_messages`
--

DROP TABLE IF EXISTS `re_mailbox_messages`;
CREATE TABLE IF NOT EXISTS `re_mailbox_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_user_id` int(11) NOT NULL DEFAULT '0',
  `to_user_id` int(11) NOT NULL DEFAULT '0',
  `timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `body` text NOT NULL,
  `seen` enum('1','0') NOT NULL DEFAULT '0',
  `clear_user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `from_user_id` (`from_user_id`),
  KEY `to_user_id` (`to_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_mailbox_messages`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_mailbox_user_lists`
--

DROP TABLE IF EXISTS `re_mailbox_user_lists`;
CREATE TABLE IF NOT EXISTS `re_mailbox_user_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `list_user_id` int(11) NOT NULL DEFAULT '0',
  `is_ignored` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `list_user_id` (`list_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_mailbox_user_lists`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_maps`
--

DROP TABLE IF EXISTS `re_maps`;
CREATE TABLE IF NOT EXISTS `re_maps` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `app_id` varchar(255) NOT NULL DEFAULT '',
  `used` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `re_maps`
--

INSERT INTO `re_maps` (`id`, `name`, `app_id`, `used`) VALUES
(1, 'google', 'google_map_id', '0'),
(2, 'yahoo', 'yahoo_map_id', '0'),
(3, 'microsoft', '', '1');

-- --------------------------------------------------------

--
-- Table structure for table `re_modes`
--

DROP TABLE IF EXISTS `re_modes`;
CREATE TABLE IF NOT EXISTS `re_modes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descr` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `re_modes`
--

INSERT INTO `re_modes` (`id`, `descr`) VALUES
(1, 'service'),
(2, 'separate_reagent'),
(3, 'admin_defined');

-- --------------------------------------------------------

--
-- Table structure for table `re_mode_hide_ids`
--

DROP TABLE IF EXISTS `re_mode_hide_ids`;
CREATE TABLE IF NOT EXISTS `re_mode_hide_ids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_mode` enum('1','2','3') NOT NULL DEFAULT '1',
  `id_elem` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_mode` (`id_mode`),
  KEY `id_elem` (`id_elem`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `re_mode_hide_ids`
--

INSERT INTO `re_mode_hide_ids` (`id`, `id_mode`, `id_elem`) VALUES
(1, '2', 1),
(2, '2', 2),
(3, '2', 3),
(4, '2', 6),
(5, '2', 7),
(6, '2', 8),
(7, '2', 9),
(8, '2', 10),
(9, '2', 11),
(10, '2', 12),
(11, '2', 13),
(12, '2', 15),
(13, '2', 16),
(14, '2', 17);

-- --------------------------------------------------------

--
-- Table structure for table `re_mode_ids`
--

DROP TABLE IF EXISTS `re_mode_ids`;
CREATE TABLE IF NOT EXISTS `re_mode_ids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_elem` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `re_mode_ids`
--

INSERT INTO `re_mode_ids` (`id`, `id_elem`) VALUES
(1, 'mhi_registration'),
(2, 'mhi_ad_buy'),
(3, 'mhi_ad_rent'),
(4, 'mhi_ad_sell'),
(5, 'mhi_ad_lease'),
(6, 'mhi_addfriend_link'),
(7, 'mhi_blacklist_link'),
(8, 'mhi_interest_link'),
(9, 'mhi_my_messages'),
(10, 'mhi_services'),
(11, 'mhi_contact_agency'),
(12, 'mhi_complain_link'),
(13, 'mhi_match_me'),
(15, 'mhi_online'),
(16, 'mhi_new_members'),
(17, 'mhi_most_active');

-- --------------------------------------------------------

--
-- Table structure for table `re_module_file`
--

DROP TABLE IF EXISTS `re_module_file`;
CREATE TABLE IF NOT EXISTS `re_module_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=60 ;

--
-- Dumping data for table `re_module_file`
--

INSERT INTO `re_module_file` (`id`, `file`) VALUES
(1, 'account'),
(2, 'blacklist'),
(3, 'contact'),
(7, 'hotlist'),
(10, 'search_preferences'),
(15, 'power_searchr'),
(16, 'admin_rentals'),
(17, 'quick_search'),
(19, 'rentals'),
(21, 'services'),
(22, 'admin_show_ads_area'),
(23, 'admin_pay_services'),
(28, 'admin_editfile'),
(30, 'admin_groups'),
(31, 'admin_homepage'),
(32, 'admin_payment'),
(33, 'admin_references'),
(34, 'admin_settings'),
(37, 'admin_users'),
(38, 'admin_upload'),
(48, 'admin_info_manager'),
(51, 'admin_badwords'),
(52, 'mailbox'),
(54, 'admin_news'),
(55, 'admin_mails'),
(56, 'admin_sponsors'),
(57, 'agents'),
(58, 'admin_listings_export'),
(59, 'admin_countries');

-- --------------------------------------------------------

--
-- Table structure for table `re_news`
--

DROP TABLE IF EXISTS `re_news`;
CREATE TABLE IF NOT EXISTS `re_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `news_text` text,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `image_path` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `date_ts` varchar(255) DEFAULT NULL,
  `channel_name` varchar(255) NOT NULL DEFAULT '',
  `channel_link` varchar(255) NOT NULL DEFAULT '',
  `news_link` varchar(255) NOT NULL DEFAULT '',
  `id_channel` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_channel` (`id_channel`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_news`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_news_feeds`
--

DROP TABLE IF EXISTS `re_news_feeds`;
CREATE TABLE IF NOT EXISTS `re_news_feeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL DEFAULT '0',
  `link` text,
  `max_news` tinyint(3) NOT NULL DEFAULT '5',
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `date_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_news_feeds`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_profile_visit`
--

DROP TABLE IF EXISTS `re_profile_visit`;
CREATE TABLE IF NOT EXISTS `re_profile_visit` (
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_visiter` int(11) NOT NULL DEFAULT '0',
  `last_visit_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `visits_count` int(11) NOT NULL DEFAULT '0',
  KEY `id_user` (`id_user`),
  KEY `id_visiter` (`id_visiter`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_profile_visit`
--

INSERT INTO `re_profile_visit` (`id_user`, `id_visiter`, `last_visit_date`, `visits_count`) VALUES
(101, 2, '2010-12-21 16:37:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `re_reference_lang_spr`
--

DROP TABLE IF EXISTS `re_reference_lang_spr`;
CREATE TABLE IF NOT EXISTS `re_reference_lang_spr` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `table_key` int(3) NOT NULL DEFAULT '0',
  `id_reference` int(11) NOT NULL DEFAULT '0',
  `lang_1_1` varchar(255) NOT NULL DEFAULT '',
  `lang_2_1` varchar(255) NOT NULL DEFAULT '',
  `lang_1_2` varchar(255) NOT NULL DEFAULT '',
  `lang_2_2` varchar(255) NOT NULL DEFAULT '',
  `lang_3_1` varchar(255) NOT NULL DEFAULT '',
  `lang_3_2` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `id_reference` (`id_reference`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=641 ;

--
-- Dumping data for table `re_reference_lang_spr`
--

INSERT INTO `re_reference_lang_spr` (`id`, `table_key`, `id_reference`, `lang_1_1`, `lang_2_1`, `lang_1_2`, `lang_2_2`, `lang_3_1`, `lang_3_2`) VALUES
(1, 1, 1, 'Gender', 'Пол', 'Gender', 'Пол', 'Geschlecht', 'Geschlecht'),
(4, 3, 3, 'Furniture', 'Мебель', 'Furniture', 'Наличие мебели', 'Möbel', 'Möbel'),
(8, 5, 1, 'Do you smoke?', 'Вы курите?', 'Does he/she smoke?', 'Он/она курит?', 'Rauchen Sie?', 'Rauchen Sie?'),
(11, 5, 4, 'Pets', 'Домашние животные', 'Pets', 'Его/Её Домашние животные', 'Haustiere', 'Haustiere'),
(14, 5, 7, 'Orientation', 'Ориентация', 'Orientation', 'Его/Её Ориентация', 'Orientierung', 'Orientierung'),
(17, 5, 10, 'Material state', 'Материальное положение', 'Material state', 'Его/Её Материальное положение', 'Finanzieller Stand', 'Finanzieller Stand'),
(18, 5, 11, 'Drinking', 'Отношение к алкоголю', 'Drinking', 'Его/Её Отношение к алкоголю', 'Trinken', 'Trinken'),
(19, 5, 12, 'Drugs', 'Отношение к наркотикам', 'Drugs', 'Его/Её Отношение к наркотикам', 'Drogen', 'Drogen'),
(20, 2, 1, 'Male', 'Мужчина', 'Male', 'Мужчину', 'Männlich', 'Männlich'),
(21, 2, 2, 'Female', 'Женщина', 'Female', 'Женщину', 'Weiblich', 'Weiblich'),
(22, 4, 1, 'Room', 'Комната', 'Room', 'Комната', 'Room', 'Room'),
(23, 4, 2, 'Apartment', 'Квартира', 'Apartment', 'Квартира', 'Apartment', 'Apartment'),
(24, 4, 3, 'House', 'Дом', 'House', 'Дом', 'House', 'House'),
(51, 4, 30, 'Furnitured', 'Мебель есть', 'Furnitured', 'Обязательно', 'Mit Möbel', 'Mit Möbel'),
(52, 4, 31, 'None', 'Без мебели', 'None', 'Не нужно', 'Nichts', 'Nichts'),
(73, 4, 52, 'month to month', 'За каждый месяц', 'month to month', 'За каждый месяц', 'month to month', 'month to month'),
(78, 6, 1, 'Non smoking', 'Не курю', 'Non smoking', 'Не курю', 'Non smoking', 'Non smoking'),
(79, 6, 2, 'Outdoor smoking', 'Не курю в помещении', 'Outdoor smoking', 'Не курю в помещении', 'Outdoor smoking', 'Outdoor smoking'),
(80, 6, 3, 'Indoor smoking', 'Курю в помещении', 'Indoor smoking', 'Курю в помещении', 'Indoor smoking', 'Indoor smoking'),
(86, 6, 9, 'Dog(s)', 'Собака', 'Dog(s)', 'Собака', 'Hund (e)', 'Hund (e)'),
(87, 6, 10, 'Cat(s)', 'Кошка', 'Cat(s)', 'Кошка', 'Katz (Kätze)', 'Katz (Kätze)'),
(88, 6, 11, 'Caged Pet(s)', 'Животные в клетке', 'Caged Pet(s)', 'Животные в клетке', 'eingesperrte Haustiere', 'eingesperrte Haustiere'),
(93, 6, 16, 'Hetero sexual', 'Гетеро', 'Hetero sexual', 'Гетеро', 'Heterosexual', 'Heterosexual'),
(94, 6, 17, 'Bi sexual', 'Би', 'Bi sexual', 'Би', 'Bi-sexual', 'Bi-sexual'),
(95, 6, 18, 'Gay', 'Гей', 'Gay', 'Гей', 'Schwul', 'Schwul'),
(96, 6, 19, 'Lesbian', 'Лесби', 'Lesbian', 'Лесби', 'Lesbisch', 'Lesbisch'),
(106, 6, 29, 'Casual earnings ', 'Непостоянные заработки', 'Casual earnings ', 'Непостоянные заработки', 'Nebeneinkünfte', 'Nebeneinkünfte'),
(107, 6, 30, 'Stable lower-income', 'Постоянный небольшой доход', 'Stable lower-income', 'Постоянный небольшой доход', 'Stabiles Kleineinkommen', 'Stabiles Kleineinkommen'),
(108, 6, 31, 'Stable average income', 'Стабильный средний доход', 'Stable average income', 'Стабильный средний доход', 'Stabiles Durchschnittseinkommen', 'Stabiles Durchschnittseinkommen'),
(109, 6, 32, 'Comfortably situated', 'Хорошо обеспечен, не жалуюсь', 'Comfortably situated', 'Хорошо обеспечен, не жалуется', 'Gutes Einkommen', 'Gutes Einkommen'),
(110, 6, 33, 'Never', 'Не пью вообще', 'Never', 'Не пьёт вообще', 'Nie', 'Nie'),
(111, 6, 34, 'Sometimes in company', 'Пью в компаниях изредка', 'Sometimes in company', 'Пьёт в компаниях изредка', 'Manchmal gesellschaftlich', 'Manchmal gesellschaftlich'),
(112, 6, 35, 'Like it', 'Люблю выпить', 'Like it', 'Любит выпить', 'Trinke gern', 'Trinke gern'),
(113, 6, 36, 'Never tried', 'Никогда не принимал', 'Never tried', 'Никогда не принимал', 'Nie probiert', 'Nie probiert'),
(114, 6, 37, 'Reject', 'Категорически не приемлю', 'Reject', 'Категорически не приемлет', 'Abgelehnt', 'Abgelehnt'),
(115, 6, 38, 'Smoke &quot;Indian grass&quot;', 'Покуриваю &quot;травку&quot;', 'Smoke &quot;Indian grass&quot;', 'Покуривает &quot;травку&quot;', 'Rauche &quot;Indianisches Gras&quot;', 'Rauche &quot;Indianisches Gras&quot;'),
(116, 6, 39, 'Take lights', 'Принимаю легкие', 'Take lights', 'Принимает легкие', 'Nehm leichte', 'Nehm leichte'),
(117, 6, 40, 'Take strong ones', 'Принимаю тяжелые', 'Take strong ones', 'Принимает тяжелые', 'Nehm harte', 'Nehm harte'),
(118, 6, 41, 'Trying to stop', 'Бросаю', 'Trying to stop', 'Бросает', 'Möchte verzichten', 'Möchte verzichten'),
(119, 6, 42, 'Getting cured at NA', 'Выздоравливаю по NA', 'Getting cured at NA', 'Выздоравливает по NA', 'Werde in der Klinik geheilt', 'Werde in der Klinik geheilt'),
(120, 6, 43, 'Discontinued', 'Бросил', 'Discontinued', 'Бросил', 'Abgebrochen', 'Abgebrochen'),
(121, 7, 1, 'Interests', '', 'Interests', '', 'Interests', 'Interests'),
(122, 7, 2, 'Sports', 'Занятия спортом', 'Sports', 'Занятия спортом', 'Sports', 'Sports'),
(123, 7, 3, 'Favourite music style', 'Любимые музыкальные направления', 'Favourite music style', 'Любимые музыкальные направления', 'Favourite music style', 'Favourite music style'),
(124, 8, 1, 'Automobiles', 'Автомобили', 'Automobiles', 'Автомобили', 'Automobiles', 'Automobiles'),
(125, 8, 2, 'Business and finance', 'Бизнес и финансы', 'Business and finance', 'Бизнес и финансы', 'Business and finance', 'Business and finance'),
(126, 8, 3, 'Pets', 'Домашние животные', 'Pets', 'Домашние животные', 'Pets', 'Pets'),
(127, 8, 4, 'Arts', '', 'Arts', '', 'Arts', 'Arts'),
(128, 8, 5, 'Movies', 'Кино', 'Movies', 'Кино', 'Movies', 'Movies'),
(129, 8, 6, 'Computers and Internet', 'Компьютеры и интернет', 'Computers and Internet', 'Компьютеры и интернет', 'Computers and Internet', 'Computers and Internet'),
(130, 8, 7, 'Cooking', 'Кулинария, готовка', 'Cooking', 'Кулинария, готовка', 'Cooking', 'Cooking'),
(131, 8, 8, 'Literature', 'Литература', 'Literature', 'Литература', 'Literature', 'Literature'),
(132, 8, 9, 'Fashion, style', 'Мода, стиль', 'Fashion, style', 'Мода, стиль', 'Fashion, style', 'Fashion, style'),
(133, 8, 10, 'Music', 'Музыка', 'Music', 'Музыка', 'Music', 'Music'),
(134, 8, 11, 'Science', 'Наука', 'Science', 'Наука', 'Science', 'Science'),
(135, 8, 12, 'Politics, law', 'Политика и право', 'Politics, law', 'Политика и право', 'Politics, law', 'Politics, law'),
(136, 8, 13, 'Psychology', 'Психология', 'Psychology', 'Психология', 'Psychology', 'Psychology'),
(137, 8, 14, 'Travelling', 'Путешествия', 'Travelling', 'Путешествия', 'Travelling', 'Travelling'),
(138, 8, 15, 'Work and career', 'Работа и карьера', 'Work and career', 'Работа и карьера', 'Work and career', 'Work and career'),
(139, 8, 16, 'Religion, occultism', 'Религия, оккультизм', 'Religion, occultism', 'Религия, оккультизм', 'Religion, occultism', 'Religion, occultism'),
(140, 8, 17, 'Sport', 'Спорт', 'Sport', 'Спорт', 'Sport', 'Sport'),
(141, 8, 18, 'Theater', 'Театр', 'Theater', 'Театр', 'Theater', 'Theater'),
(142, 8, 19, 'Photo', 'Фотография', 'Photo', 'Фотография', 'Photo', 'Photo'),
(143, 8, 20, 'Jogging', 'Бег', 'Jogging', 'Бег', 'Jogging', 'Jogging'),
(144, 8, 21, 'Walking', 'Прогулки', 'Walking', 'Прогулки', 'Walking', 'Walking'),
(145, 8, 22, 'Fitness', 'Фитнес', 'Fitness', 'Фитнес', 'Fitness', 'Fitness'),
(146, 8, 23, 'Swimming', 'Плавание', 'Swimming', 'Плавание', 'Swimming', 'Swimming'),
(147, 8, 24, 'Bicycle', 'Велосипед', 'Bicycle', 'Велосипед', 'Bicycle', 'Bicycle'),
(148, 8, 25, 'Skateboarding', 'Скейтборд', 'Skateboarding', 'Скейтборд', 'Skateboarding', 'Skateboarding'),
(149, 8, 26, 'Roller skating', 'Ролики', 'Roller skating', 'Ролики', 'Roller skating', 'Roller skating'),
(150, 8, 27, 'Skies, snowboarding', 'Лыжи, сноуборд', 'Skies, snowboarding', 'Лыжи, сноуборд', 'Skies, snowboarding', 'Skies, snowboarding'),
(151, 8, 28, 'Snorkelling, diving', 'Подводное плавание', 'Snorkelling, diving', 'Подводное плавание', 'Snorkelling, diving', 'Snorkelling, diving'),
(152, 8, 29, 'Serfing', 'Серфинг', 'Serfing', 'Серфинг', 'Serfing', 'Serfing'),
(153, 8, 30, 'Martial art', 'Восточные единоборства', 'Martial art', 'Восточные единоборства', 'Martial art', 'Martial art'),
(154, 8, 31, 'Joga, meditation', 'Медитация, йога', 'Joga, meditation', 'Медитация, йога', 'Joga, meditation', 'Joga, meditation'),
(155, 8, 32, 'Pop music', 'Поп-музыка', 'Pop music', 'Поп-музыка', 'Pop music', 'Pop music'),
(156, 8, 33, 'Bards, genuin songs', 'Барды, авторская песня', 'Bards, genuin songs', 'Барды, авторская песня', 'Bards, genuin songs', 'Bards, genuin songs'),
(157, 8, 34, 'Rock, metall', 'Рок, металл', 'Rock, metall', 'Рок, металл', 'Rock, metall', 'Rock, metall'),
(158, 8, 35, 'Blues, jazz', 'Блюз, джаз', 'Blues, jazz', 'Блюз, джаз', 'Blues, jazz', 'Blues, jazz'),
(159, 8, 36, 'Ethnic, New Edge', 'Этническая, нью-эйдж', 'Ethnic, New Edge', 'Этническая, нью-эйдж', 'Ethnic, New Edge', 'Ethnic, New Edge'),
(160, 8, 37, 'Rap', 'Рэп', 'Rap', 'Рэп', 'Rap', 'Rap'),
(161, 8, 38, 'Classic', 'Классическая', 'Classic', 'Классическая', 'Classic', 'Classic'),
(162, 8, 39, 'Dance music', 'Танцевальная музыка', 'Dance music', 'Танцевальная музыка', 'Dance music', 'Dance music'),
(163, 8, 40, 'Electronic', 'Электронная', 'Electronic', 'Электронная', 'Electronic', 'Electronic'),
(164, 9, 1, 'Eyes', 'Цвет глаз', 'Eyes', 'Цвет глаз', 'Eyes', 'Eyes'),
(165, 9, 2, 'Complexion', 'Телосложение', 'Complexion', 'Телосложение', 'Complexion', 'Complexion'),
(166, 9, 3, 'In my body I have', 'На теле есть', 'In my body I have', 'На теле есть', 'In my body I have', 'In my body I have'),
(167, 9, 4, 'Hair (head)', 'Волосы на голове', 'Hair (head)', 'Волосы на голове', 'Hair (head)', 'Hair (head)'),
(168, 9, 5, 'Hair (face and body)', 'Волосы на лице и теле', 'Hair (face and body)', 'Волосы на лице и теле', 'Hair (face and body)', 'Hair (face and body)'),
(169, 10, 1, 'Green', 'Зеленый', 'Green', 'Зеленый', 'Green', 'Green'),
(170, 10, 2, 'Grey', 'Серый', 'Grey', 'Серый', 'Grey', 'Grey'),
(171, 10, 3, 'Walnut', 'Ореховые', 'Walnut', 'Ореховые', 'Walnut', 'Walnut'),
(172, 10, 4, 'Chestnut', 'Каштановые', 'Chestnut', 'Каштановые', 'Chestnut', 'Chestnut'),
(173, 10, 5, 'Other', 'Другие', 'Other', 'Другие', 'Other', 'Other'),
(174, 10, 6, 'Blue', 'Голубые', 'Blue', 'Голубые', 'Blue', 'Blue'),
(175, 10, 7, 'Black', 'Черные', 'Black', 'Черные', 'Black', 'Black'),
(176, 10, 8, 'Slim', 'Худощавое', 'Slim', 'Худощавое', 'Slim', 'Slim'),
(177, 10, 9, 'Common', 'Обычное', 'Common', 'Обычное', 'Common', 'Common'),
(178, 10, 10, 'Athletic', 'Спортивное', 'Athletic', 'Спортивное', 'Athletic', 'Athletic'),
(179, 10, 11, 'Beefy', 'Мускулистое', 'Beefy', 'Мускулистое', 'Beefy', 'Beefy'),
(180, 10, 12, 'Heavily-built', 'Плотное', 'Heavily-built', 'Плотное', 'Heavily-built', 'Heavily-built'),
(181, 10, 13, 'Full-bodied', 'Полное', 'Full-bodied', 'Полное', 'Full-bodied', 'Full-bodied'),
(182, 10, 14, 'Piercing', 'Пирсинг', 'Piercing', 'Пирсинг', 'Piercing', 'Piercing'),
(183, 10, 15, 'Tattoo', 'Татуировки', 'Tattoo', 'Татуировки', 'Tattoo', 'Tattoo'),
(184, 10, 16, 'Light', 'Светлые', 'Light', 'Светлые', 'Light', 'Light'),
(185, 10, 17, 'Dark', 'Темные', 'Dark', 'Темные', 'Dark', 'Dark'),
(186, 10, 18, 'Red', 'Рыжие', 'Red', 'Рыжие', 'Red', 'Red'),
(187, 10, 19, 'Dyed', 'Мелированные', 'Dyed', 'Мелированные', 'Dyed', 'Dyed'),
(188, 10, 20, 'Bright', 'Яркие', 'Bright', 'Яркие', 'Bright', 'Bright'),
(189, 10, 21, 'Salt and pepper', 'Седые', 'Salt and pepper', 'Седые', 'Salt and pepper', 'Salt and pepper'),
(190, 10, 22, 'Bold', 'Голова бритая наголо', 'Bold', 'Голова бритая наголо', 'Bold', 'Bold'),
(191, 10, 23, 'Moustache', 'Усы', 'Moustache', 'Усы', 'Moustache', 'Moustache'),
(192, 10, 24, 'Beard', 'Борода', 'Beard', 'Борода', 'Beard', 'Beard'),
(193, 10, 25, 'Bristle', 'Щетина', 'Bristle', 'Щетина', 'Bristle', 'Bristle'),
(194, 10, 26, 'Chest', 'Грудь', 'Chest', 'Грудь', 'Chest', 'Chest'),
(195, 10, 27, 'Back', 'Спина', 'Back', 'Спина', 'Back', 'Back'),
(196, 10, 28, 'Hands', 'Руки', 'Hands', 'Руки', 'Hands', 'Hands'),
(197, 10, 29, 'Legs', 'Ноги', 'Legs', 'Ноги', 'Legs', 'Legs'),
(198, 10, 30, 'Almost none by nature', 'Почти нет от природы', 'Almost none by nature', 'Почти нет от природы', 'Almost none by nature', 'Almost none by nature'),
(199, 11, 1, 'Occupation', 'Занятие', 'Occupation', 'Занятие', 'Occupation', 'Occupation'),
(200, 12, 1, 'Professional(s)', 'Специалист', 'Professional(s)', 'Специалист', 'Professional(s)', 'Professional(s)'),
(201, 12, 2, 'Student(s)', 'Студент', 'Student(s)', 'Студент', 'Student(s)', 'Student(s)'),
(202, 12, 3, 'Military', 'Военный', 'Military', 'Военный', 'Military', 'Military'),
(203, 12, 4, 'Unemployed', 'Неработающий', 'Unemployed', 'Неработающий', 'Unemployed', 'Unemployed'),
(204, 13, 1, 'Please select', 'Пожалуйста выберите', 'Please select', 'Пожалуйста выберите', 'Bitte wählen', 'Bitte wählen'),
(205, 14, 1, 'I found roommate', 'Я нашел соседа', 'I found roommate', 'Я нашел соседа', 'I found roommate', 'I found roommate'),
(206, 14, 2, 'I found apartment', 'Я нашел жилье', 'I found apartment', 'Я нашел жилье', 'Ich hab schon Wohnung gefunden', 'Ich hab schon Wohnung gefunden'),
(207, 14, 3, 'Other', 'Другое', 'Other', 'Другое', 'Anderes', 'Anderes'),
(208, 15, 1, 'Languages', 'Знание языков', 'Languages', 'Знание языков', 'Sprachen', 'Sprachen'),
(209, 16, 1, 'English', 'English', 'English', 'English', 'English', 'English'),
(210, 16, 2, 'Deutsch', 'Deutsch', 'Deutsch', 'Deutsch', 'Deutsch', 'Deutsch'),
(211, 16, 3, 'Fran&ccedil;ais', 'Fran&ccedil;ais', 'Fran&ccedil;ais', 'Fran&ccedil;ais', 'Fran&ccedil;ais', 'Fran&ccedil;ais'),
(212, 16, 4, 'Espa&ntilde;ol', 'Espa&ntilde;ol', 'Espa&ntilde;ol', 'Espa&ntilde;ol', 'Espa&ntilde;ol', 'Espa&ntilde;ol'),
(213, 16, 5, 'Italiano', 'Italiano', 'Italiano', 'Italiano', 'Italiano', 'Italiano'),
(214, 16, 6, 'Русский', 'Русский', 'Русский', 'Русский', 'Русский', 'Русский'),
(215, 16, 7, 'Hebrew', 'Hebrew', 'Hebrew', 'Hebrew', 'Hebrew', 'Hebrew'),
(216, 16, 8, 'Chinese', 'Chinese', 'Chinese', 'Chinese', 'Chinese', 'Chinese'),
(217, 16, 9, 'Qafar', 'Qafar', 'Qafar', 'Qafar', 'Qafar', 'Qafar'),
(218, 16, 10, 'Afrikaans', 'Afrikaans', 'Afrikaans', 'Afrikaans', 'Afrikaans', 'Afrikaans'),
(219, 16, 11, '&#1575;&#1585;&#1583;&#1608;', '&#1575;&#1585;&#1583;&#1608;', '&#1575;&#1585;&#1583;&#1608;', '&#1575;&#1585;&#1583;&#1608;', '&#1575;&#1585;&#1583;&#1608;', '&#1575;&#1585;&#1583;&#1608;'),
(220, 16, 12, '&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;', '&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;', '&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;', '&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;', '&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;', '&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;'),
(221, 16, 13, 'Аз&#1241;рба?&#1209;ан', 'Аз&#1241;рба?&#1209;ан', 'Аз&#1241;рба?&#1209;ан', 'Аз&#1241;рба?&#1209;ан', 'Аз&#1241;рба?&#1209;ан', 'Аз&#1241;рба?&#1209;ан'),
(222, 16, 14, 'Беларускі', 'Беларускі', 'Беларускі', 'Беларускі', 'Беларускі', 'Беларускі'),
(223, 16, 15, 'Български', 'Български', 'Български', 'Български', 'Български', 'Български'),
(224, 16, 16, 'Catal&agrave;', 'Catal&agrave;', 'Catal&agrave;', 'Catal&agrave;', 'Catal&agrave;', 'Catal&agrave;'),
(225, 16, 17, '&#268;e&#353;tina', '&#268;e&#353;tina', '&#268;e&#353;tina', '&#268;e&#353;tina', '&#268;e&#353;tina', '&#268;e&#353;tina'),
(226, 16, 18, 'Dansk', 'Dansk', 'Dansk', 'Dansk', 'Dansk', 'Dansk'),
(227, 16, 19, '&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940;', '&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940;', '&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940;', '&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940;', '&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940;', '&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940;'),
(228, 16, 20, 'Eesti', 'Eesti', 'Eesti', 'Eesti', 'Eesti', 'Eesti'),
(229, 16, 21, '&#1601;&#1575;&#1585;&#1587;&#1740;', '&#1601;&#1575;&#1585;&#1587;&#1740;', '&#1601;&#1575;&#1585;&#1587;&#1740;', '&#1601;&#1575;&#1585;&#1587;&#1740;', '&#1601;&#1575;&#1585;&#1587;&#1740;', '&#1601;&#1575;&#1585;&#1587;&#1740;'),
(230, 16, 22, 'Suomi', 'Suomi', 'Suomi', 'Suomi', 'Suomi', 'Suomi'),
(231, 16, 23, 'Gaeilge', 'Gaeilge', 'Gaeilge', 'Gaeilge', 'Gaeilge', 'Gaeilge'),
(232, 16, 24, '&#2361;&#2367;&#2306;&#2342;&#2368;', '&#2361;&#2367;&#2306;&#2342;&#2368;', '&#2361;&#2367;&#2306;&#2342;&#2368;', '&#2361;&#2367;&#2306;&#2342;&#2368;', '&#2361;&#2367;&#2306;&#2342;&#2368;', '&#2361;&#2367;&#2306;&#2342;&#2368;'),
(233, 16, 25, 'Hrvatski', 'Hrvatski', 'Hrvatski', 'Hrvatski', 'Hrvatski', 'Hrvatski'),
(234, 16, 26, 'Magyar', 'Magyar', 'Magyar', 'Magyar', 'Magyar', 'Magyar'),
(235, 16, 27, '&#1344;&#1377;&#1397;&#1381;&#1408;&#1383;&#1398;', '&#1344;&#1377;&#1397;&#1381;&#1408;&#1383;&#1398;', '&#1344;&#1377;&#1397;&#1381;&#1408;&#1383;&#1398;', '&#1344;&#1377;&#1397;&#1381;&#1408;&#1383;&#1398;', '&#1344;&#1377;&#1397;&#1381;&#1408;&#1383;&#1398;', '&#1344;&#1377;&#1397;&#1381;&#1408;&#1383;&#1398;'),
(236, 16, 28, '&#26085;&#26412;&#35486;', '&#26085;&#26412;&#35486;', '&#26085;&#26412;&#35486;', '&#26085;&#26412;&#35486;', '&#26085;&#26412;&#35486;', '&#26085;&#26412;&#35486;'),
(237, 16, 29, '&#4325;&#4304;&#4320;&#4311;&#4323;&#4314;&#4312;', '&#4325;&#4304;&#4320;&#4311;&#4323;&#4314;&#4312;', '&#4325;&#4304;&#4320;&#4311;&#4323;&#4314;&#4312;', '&#4325;&#4304;&#4320;&#4311;&#4323;&#4314;&#4312;', '&#4325;&#4304;&#4320;&#4311;&#4323;&#4314;&#4312;', '&#4325;&#4304;&#4320;&#4311;&#4323;&#4314;&#4312;'),
(238, 16, 30, '&#1178;аза&#1179;', '&#1178;аза&#1179;', '&#1178;аза&#1179;', '&#1178;аза&#1179;', '&#1178;аза&#1179;', '&#1178;аза&#1179;'),
(239, 16, 31, 'Lietuvi&#371;', 'Lietuvi&#371;', 'Lietuvi&#371;', 'Lietuvi&#371;', 'Lietuvi&#371;', 'Lietuvi&#371;'),
(240, 16, 32, 'latvie&#353;u', 'latvie&#353;u', 'latvie&#353;u', 'latvie&#353;u', 'latvie&#353;u', 'latvie&#353;u'),
(241, 16, 33, 'Nederlands', 'Nederlands', 'Nederlands', 'Nederlands', 'Nederlands', 'Nederlands'),
(242, 16, 34, 'Polski', 'Polski', 'Polski', 'Polski', 'Polski', 'Polski'),
(243, 16, 35, 'Portugu&ecirc;s', 'Portugu&ecirc;s', 'Portugu&ecirc;s', 'Portugu&ecirc;s', 'Portugu&ecirc;s', 'Portugu&ecirc;s'),
(244, 16, 36, 'Rom&acirc;n&#259;', 'Rom&acirc;n&#259;', 'Rom&acirc;n&#259;', 'Rom&acirc;n&#259;', 'Rom&acirc;n&#259;', 'Rom&acirc;n&#259;'),
(245, 16, 37, 'Slovensk&yacute;', 'Slovensk&yacute;', 'Slovensk&yacute;', 'Slovensk&yacute;', 'Slovensk&yacute;', 'Slovensk&yacute;'),
(246, 16, 38, 'Sloven&#353;&#269;ina', 'Sloven&#353;&#269;ina', 'Sloven&#353;&#269;ina', 'Sloven&#353;&#269;ina', 'Sloven&#353;&#269;ina', 'Sloven&#353;&#269;ina'),
(247, 16, 39, 'Shqipe', 'Shqipe', 'Shqipe', 'Shqipe', 'Shqipe', 'Shqipe'),
(248, 16, 40, 'Српски', 'Српски', 'Српски', 'Српски', 'Српски', 'Српски'),
(249, 16, 41, 'Svenska', 'Svenska', 'Svenska', 'Svenska', 'Svenska', 'Svenska'),
(250, 16, 42, 'T&uuml;rk&ccedil;e', 'T&uuml;rk&ccedil;e', 'T&uuml;rk&ccedil;e', 'T&uuml;rk&ccedil;e', 'T&uuml;rk&ccedil;e', 'T&uuml;rk&ccedil;e'),
(251, 16, 43, 'Татар', 'Татар', 'Татар', 'Татар', 'Татар', 'Татар'),
(252, 16, 44, 'Українська', 'Українська', 'Українська', 'Українська', 'Українська', 'Українська'),
(253, 16, 45, 'Ўзбек', 'Ўзбек', 'Ўзбек', 'Ўзбек', 'Ўзбек', 'Ўзбек'),
(254, 16, 46, '&#2437;&#2488;&#2478;&#2496;&#2527;&#2494;', '&#2437;&#2488;&#2478;&#2496;&#2527;&#2494;', '&#2437;&#2488;&#2478;&#2496;&#2527;&#2494;', '&#2437;&#2488;&#2478;&#2496;&#2527;&#2494;', '&#2437;&#2488;&#2478;&#2496;&#2527;&#2494;', '&#2437;&#2488;&#2478;&#2496;&#2527;&#2494;'),
(255, 16, 47, '&#2476;&#2494;&#2434;&#2482;&#2494;', '&#2476;&#2494;&#2434;&#2482;&#2494;', '&#2476;&#2494;&#2434;&#2482;&#2494;', '&#2476;&#2494;&#2434;&#2482;&#2494;', '&#2476;&#2494;&#2434;&#2482;&#2494;', '&#2476;&#2494;&#2434;&#2482;&#2494;'),
(256, 16, 48, 'Cymraeg', 'Cymraeg', 'Cymraeg', 'Cymraeg', 'Cymraeg', 'Cymraeg'),
(257, 16, 49, '&#1931;&#1960;&#1928;&#1964;&#1920;&#1960;&#1924;&#1958;&#1936;&#1968;', '&#1931;&#1960;&#1928;&#1964;&#1920;&#1960;&#1924;&#1958;&#1936;&#1968;', '&#1931;&#1960;&#1928;&#1964;&#1920;&#1960;&#1924;&#1958;&#1936;&#1968;', '&#1931;&#1960;&#1928;&#1964;&#1920;&#1960;&#1924;&#1958;&#1936;&#1968;', '&#1931;&#1960;&#1928;&#1964;&#1920;&#1960;&#1924;&#1958;&#1936;&#1968;', '&#1931;&#1960;&#1928;&#1964;&#1920;&#1960;&#1924;&#1958;&#1936;&#1968;'),
(258, 16, 50, '&#3938;&#4011;&#3964;&#3908;&#3851;&#3905;', '&#3938;&#4011;&#3964;&#3908;&#3851;&#3905;', '&#3938;&#4011;&#3964;&#3908;&#3851;&#3905;', '&#3938;&#4011;&#3964;&#3908;&#3851;&#3905;', '&#3938;&#4011;&#3964;&#3908;&#3851;&#3905;', '&#3938;&#4011;&#3964;&#3908;&#3851;&#3905;'),
(259, 16, 51, 'Esperanto', 'Esperanto', 'Esperanto', 'Esperanto', 'Esperanto', 'Esperanto'),
(260, 16, 52, 'Euskara', 'Euskara', 'Euskara', 'Euskara', 'Euskara', 'Euskara'),
(261, 16, 53, 'F&oslash;royskt', 'F&oslash;royskt', 'F&oslash;royskt', 'F&oslash;royskt', 'F&oslash;royskt', 'F&oslash;royskt'),
(262, 16, 54, 'Galego', 'Galego', 'Galego', 'Galego', 'Galego', 'Galego'),
(263, 16, 55, '&#2711;&#2753;&#2716;&#2736;&#2750;&#2724;&#2752;', '&#2711;&#2753;&#2716;&#2736;&#2750;&#2724;&#2752;', '&#2711;&#2753;&#2716;&#2736;&#2750;&#2724;&#2752;', '&#2711;&#2753;&#2716;&#2736;&#2750;&#2724;&#2752;', '&#2711;&#2753;&#2716;&#2736;&#2750;&#2724;&#2752;', '&#2711;&#2753;&#2716;&#2736;&#2750;&#2724;&#2752;'),
(264, 16, 56, 'Gaelg', 'Gaelg', 'Gaelg', 'Gaelg', 'Gaelg', 'Gaelg'),
(265, 16, 57, 'Hawai', 'Hawai', 'Hawai', 'Hawai', 'Hawai', 'Hawai'),
(266, 16, 58, 'Bahasa Indonesia', 'Bahasa Indonesia', 'Bahasa Indonesia', 'Bahasa Indonesia', 'Bahasa Indonesia', 'Bahasa Indonesia'),
(267, 16, 59, '&Iacute;slenska', '&Iacute;slenska', '&Iacute;slenska', '&Iacute;slenska', '&Iacute;slenska', '&Iacute;slenska'),
(268, 16, 60, 'Kalaallisut', 'Kalaallisut', 'Kalaallisut', 'Kalaallisut', 'Kalaallisut', 'Kalaallisut'),
(269, 16, 61, '&#54620;&#44397;&#50612;', '&#54620;&#44397;&#50612;', '&#54620;&#44397;&#50612;', '&#54620;&#44397;&#50612;', '&#54620;&#44397;&#50612;', '&#54620;&#44397;&#50612;'),
(270, 16, 62, '&#2325;&#2379;&#2306;&#2325;&#2339;&#2368;', '&#2325;&#2379;&#2306;&#2325;&#2339;&#2368;', '&#2325;&#2379;&#2306;&#2325;&#2339;&#2368;', '&#2325;&#2379;&#2306;&#2325;&#2339;&#2368;', '&#2325;&#2379;&#2306;&#2325;&#2339;&#2368;', '&#2325;&#2379;&#2306;&#2325;&#2339;&#2368;'),
(271, 16, 63, 'Кыргыз', 'Кыргыз', 'Кыргыз', 'Кыргыз', 'Кыргыз', 'Кыргыз'),
(272, 16, 64, 'Kernewek', 'Kernewek', 'Kernewek', 'Kernewek', 'Kernewek', 'Kernewek'),
(273, 16, 65, '&#3749;&#3762;&#3751;', '&#3749;&#3762;&#3751;', '&#3749;&#3762;&#3751;', '&#3749;&#3762;&#3751;', '&#3749;&#3762;&#3751;', '&#3749;&#3762;&#3751;'),
(274, 16, 66, 'Mакедонски', 'Mакедонски', 'Mакедонски', 'Mакедонски', 'Mакедонски', 'Mакедонски'),
(275, 16, 67, '&#3374;&#3378;&#3375;&#3390;&#3379;&#3330;', '&#3374;&#3378;&#3375;&#3390;&#3379;&#3330;', '&#3374;&#3378;&#3375;&#3390;&#3379;&#3330;', '&#3374;&#3378;&#3375;&#3390;&#3379;&#3330;', '&#3374;&#3378;&#3375;&#3390;&#3379;&#3330;', '&#3374;&#3378;&#3375;&#3390;&#3379;&#3330;'),
(276, 16, 68, 'Монгол хэл', 'Монгол хэл', 'Монгол хэл', 'Монгол хэл', 'Монгол хэл', 'Монгол хэл'),
(277, 16, 69, '&#2350;&#2352;&#2366;&#2336;&#2368;', '&#2350;&#2352;&#2366;&#2336;&#2368;', '&#2350;&#2352;&#2366;&#2336;&#2368;', '&#2350;&#2352;&#2366;&#2336;&#2368;', '&#2350;&#2352;&#2366;&#2336;&#2368;', '&#2350;&#2352;&#2366;&#2336;&#2368;'),
(278, 16, 70, 'Bahasa Melayu', 'Bahasa Melayu', 'Bahasa Melayu', 'Bahasa Melayu', 'Bahasa Melayu', 'Bahasa Melayu'),
(279, 16, 71, 'Malti', 'Malti', 'Malti', 'Malti', 'Malti', 'Malti'),
(280, 16, 72, 'Norsk bokm&aring;l', 'Norsk bokm&aring;l', 'Norsk bokm&aring;l', 'Norsk bokm&aring;l', 'Norsk bokm&aring;l', 'Norsk bokm&aring;l'),
(281, 16, 73, 'Norsk nynorsk', 'Norsk nynorsk', 'Norsk nynorsk', 'Norsk nynorsk', 'Norsk nynorsk', 'Norsk nynorsk'),
(282, 16, 74, 'Oromoo', 'Oromoo', 'Oromoo', 'Oromoo', 'Oromoo', 'Oromoo'),
(283, 16, 75, '&#2835;&#2908;&#2879;&#2822;', '&#2835;&#2908;&#2879;&#2822;', '&#2835;&#2908;&#2879;&#2822;', '&#2835;&#2908;&#2879;&#2822;', '&#2835;&#2908;&#2879;&#2822;', '&#2835;&#2908;&#2879;&#2822;'),
(284, 16, 76, '&#2602;&#2672;&#2588;&#2622;&#2604;&#2624;', '&#2602;&#2672;&#2588;&#2622;&#2604;&#2624;', '&#2602;&#2672;&#2588;&#2622;&#2604;&#2624;', '&#2602;&#2672;&#2588;&#2622;&#2604;&#2624;', '&#2602;&#2672;&#2588;&#2622;&#2604;&#2624;', '&#2602;&#2672;&#2588;&#2622;&#2604;&#2624;'),
(285, 16, 77, '&#1662;&#1690;&#1578;&#1608;', '&#1662;&#1690;&#1578;&#1608;', '&#1662;&#1690;&#1578;&#1608;', '&#1662;&#1690;&#1578;&#1608;', '&#1662;&#1690;&#1578;&#1608;', '&#1662;&#1690;&#1578;&#1608;'),
(286, 16, 78, '&#2360;&#2306;&#2360;&#2381;&#2325;&#2371;&#2340;', '&#2360;&#2306;&#2360;&#2381;&#2325;&#2371;&#2340;', '&#2360;&#2306;&#2360;&#2381;&#2325;&#2371;&#2340;', '&#2360;&#2306;&#2360;&#2381;&#2325;&#2371;&#2340;', '&#2360;&#2306;&#2360;&#2381;&#2325;&#2371;&#2340;', '&#2360;&#2306;&#2360;&#2381;&#2325;&#2371;&#2340;'),
(287, 16, 79, 'Sidaamu Afo', 'Sidaamu Afo', 'Sidaamu Afo', 'Sidaamu Afo', 'Sidaamu Afo', 'Sidaamu Afo'),
(288, 16, 80, 'Soomaali', 'Soomaali', 'Soomaali', 'Soomaali', 'Soomaali', 'Soomaali'),
(289, 16, 81, 'Kiswahili', 'Kiswahili', 'Kiswahili', 'Kiswahili', 'Kiswahili', 'Kiswahili'),
(290, 16, 82, '&#1827;&#1816;&#1834;&#1821;&#1821;&#1808;', '&#1827;&#1816;&#1834;&#1821;&#1821;&#1808;', '&#1827;&#1816;&#1834;&#1821;&#1821;&#1808;', '&#1827;&#1816;&#1834;&#1821;&#1821;&#1808;', '&#1827;&#1816;&#1834;&#1821;&#1821;&#1808;', '&#1827;&#1816;&#1834;&#1821;&#1821;&#1808;'),
(291, 16, 83, '&#2980;&#2990;&#3007;&#2996;&#3021;', '&#2980;&#2990;&#3007;&#2996;&#3021;', '&#2980;&#2990;&#3007;&#2996;&#3021;', '&#2980;&#2990;&#3007;&#2996;&#3021;', '&#2980;&#2990;&#3007;&#2996;&#3021;', '&#2980;&#2990;&#3007;&#2996;&#3021;'),
(292, 16, 84, '&#3108;&#3142;&#3122;&#3137;&#3095;&#3137;', '&#3108;&#3142;&#3122;&#3137;&#3095;&#3137;', '&#3108;&#3142;&#3122;&#3137;&#3095;&#3137;', '&#3108;&#3142;&#3122;&#3137;&#3095;&#3137;', '&#3108;&#3142;&#3122;&#3137;&#3095;&#3137;', '&#3108;&#3142;&#3122;&#3137;&#3095;&#3137;'),
(293, 16, 85, '&#3652;&#3607;&#3618;', '&#3652;&#3607;&#3618;', '&#3652;&#3607;&#3618;', '&#3652;&#3607;&#3618;', '&#3652;&#3607;&#3618;', '&#3652;&#3607;&#3618;'),
(294, 16, 86, 'Ti&#7871;ng Vi&#7879;t', 'Ti&#7871;ng Vi&#7879;t', 'Ti&#7871;ng Vi&#7879;t', 'Ti&#7871;ng Vi&#7879;t', 'Ti&#7871;ng Vi&#7879;t', 'Ti&#7871;ng Vi&#7879;t'),
(315, 4, 76, 'Investment/Development', 'Investment/Development', 'Investment/Development', 'Investment/Development', 'Investment/Development', 'Investment/Development'),
(350, 4, 110, 'studio', 'Студия, мастерская', 'studio', 'Студия, мастерская', 'studio', 'studio'),
(351, 4, 111, 'alcove', 'Садовая беседка', 'alcove', 'Садовая беседка', 'alcove', 'alcove'),
(364, 3, 13, 'Environment, surroundings', 'Рядом находятся', 'Environment, surroundings', 'Рядом находятся', 'Ausstattung, Umgebung', 'Ausstattung, Umgebung'),
(365, 4, 122, 'Beach Service (available) ', 'Пляж доступен', 'Beach Service (available) ', 'Пляж доступен', 'Strand-Service (inklusiv)', 'Strand-Service (inklusiv)'),
(366, 4, 123, 'Beach Front', 'Beach Front', 'Beach Front', 'Beach Front', 'Beach Front', 'Beach Front'),
(367, 4, 124, 'Golf nearby', 'Неподалёку поле для гольфа', 'Golf nearby', 'Неподалёку поле для гольфа', 'Golf in der Nähe', 'Golf in der Nähe'),
(368, 4, 125, 'Golf on site', 'Golf on site', 'Golf on site', 'Golf on site', 'Golf on site', 'Golf on site'),
(369, 4, 126, 'Lake Front', 'Lake Front', 'Lake Front', 'Lake Front', 'Lake Front', 'Lake Front'),
(370, 4, 127, 'Lake nearby', 'Рядом с озером', 'Lake nearby', 'Рядом с озером', 'Teich in der Nähe', 'Teich in der Nähe'),
(371, 4, 128, 'Lake View', 'С видом на озеро', 'Lake View', 'С видом на озеро', 'Teichansicht', 'Teichansicht'),
(372, 4, 129, 'Ocean Front', 'Ocean Front', 'Ocean Front', 'Ocean Front', 'Ocean Front', 'Ocean Front'),
(373, 4, 130, 'Ocean nearby', 'Недалеко от океана', 'Ocean nearby', 'Недалеко от океана', 'Ozean in der Nähe', 'Ozean in der Nähe'),
(374, 4, 131, 'Ocean View', 'С видом на океан', 'Ocean View', 'С видом на океан', 'Ozeanansicht', 'Ozeanansicht'),
(375, 4, 132, 'Ski-in/Ski-out ', 'Ski-in/Ski-out ', 'Ski-in/Ski-out ', 'Ski-in/Ski-out ', 'Ski-in/Ski-out ', 'Ski-in/Ski-out '),
(376, 4, 133, 'Skiing nearby', 'Skiing nearby', 'Skiing nearby', 'Skiing nearby', 'Skiing nearby', 'Skiing nearby'),
(377, 4, 134, 'Walk to Slopes', 'Walk to Slopes', 'Walk to Slopes', 'Walk to Slopes', 'Walk to Slopes', 'Walk to Slopes'),
(378, 3, 14, 'The following is available in the neighbourhood', 'В округе доступны', 'The following is available in the neighbourhood', 'В округе доступны', 'Folgendes ist in der Umgebung verfügbar', 'Folgendes ist in der Umgebung verfügbar'),
(379, 4, 135, 'Boating', 'Лодочный спорт, гребля', 'Boating', 'Лодочный спорт, гребля', 'Bootfahren', 'Bootfahren'),
(380, 4, 136, 'Cycling ', 'Катание на велосипеде', 'Cycling ', 'Катание на велосипеде', 'Fahrrad', 'Fahrrad'),
(381, 4, 137, 'Deep Sea Fishing', 'Рыбалка на глубине моря', 'Deep Sea Fishing', 'Рыбалка на глубине моря', 'Deep Sea Fishing', 'Deep Sea Fishing'),
(382, 4, 138, 'Fishing', 'Рыбалка', 'Fishing', 'Рыбалка', 'Fischen', 'Fischen'),
(383, 4, 139, 'Golf', 'Гольф', 'Golf', 'Гольф', 'Golf', 'Golf'),
(384, 4, 140, 'Hiking', 'Прогулки пешком', 'Hiking', 'Прогулки пешком', 'Bergwandern', 'Bergwandern'),
(385, 4, 141, 'Horseback Riding', 'Езда верхом на лошади', 'Horseback Riding', 'Езда верхом на лошади', 'Reiten', 'Reiten'),
(386, 4, 142, 'Hunting', 'Охота', 'Hunting', 'Охота', 'Jagen', 'Jagen'),
(387, 4, 143, 'Live Entertainment', 'Live Entertainment', 'Live Entertainment', 'Live Entertainment', 'Live Entertainment', 'Live Entertainment'),
(388, 4, 144, 'Mountain Biking', 'Катание на горном велосипеде', 'Mountain Biking', 'Катание на горном велосипеде', 'Mountain-Biking', 'Mountain-Biking'),
(389, 4, 145, 'Paragliding', 'Параглайдинг', 'Paragliding', 'Параглайдинг', 'Gleitschirmfliegen', 'Gleitschirmfliegen'),
(390, 4, 146, 'River', 'Река', 'River', 'Река', 'River', 'River'),
(391, 4, 147, 'Rollerblading', 'Катание на роликах', 'Rollerblading', 'Катание на роликах', 'Rollerblading', 'Rollerblading'),
(392, 4, 148, 'Scuba/Snorkel', 'Подводное плавание', 'Scuba/Snorkel', 'Подводное плавание', 'Tauchen', 'Tauchen'),
(393, 4, 149, 'Shopping/Restaurants', 'Покупки/рестораны', 'Shopping/Restaurants', 'Покупки/рестораны', 'Shopping/Restaurants', 'Shopping/Restaurants'),
(394, 4, 150, 'Skiing', 'Катание на лыжах', 'Skiing', 'Катание на лыжах', 'Schifahren', 'Schifahren'),
(395, 4, 151, 'Surfing', 'Сёрфинг', 'Surfing', 'Сёрфинг', 'Surfing', 'Surfing'),
(396, 4, 152, 'Swimming', 'Плавание', 'Swimming', 'Плавание', 'Schwimmen', 'Schwimmen'),
(397, 4, 153, 'Tennis', 'Теннис', 'Tennis', 'Теннис', 'Tennis', 'Tennis'),
(398, 4, 154, 'Theme Park', 'Парк с аттракционами', 'Theme Park', 'Парк с аттракционами', 'Freizeitpark', 'Freizeitpark'),
(399, 4, 155, 'Walking', 'Прогулки', 'Walking', 'Прогулки', 'Walking', 'Walking'),
(400, 4, 156, 'Water Skiing', 'Катание на водных лыжах', 'Water Skiing', 'Катание на водных лыжах', 'Wasserschifahren', 'Wasserschifahren'),
(401, 3, 15, 'Accomodations', 'Удобства', 'Accomodations', 'Необходимы удобства', 'Bequemlichkeiten', 'Bequemlichkeiten'),
(402, 4, 157, 'Air Conditioning', 'Кондиционер', 'Air Conditioning', 'Кондиционер', 'Air Conditioning', 'Air Conditioning'),
(403, 4, 158, 'Beach Service (available)', 'Пляж (доступен)', 'Beach Service (available)', 'Пляж (доступен)', 'Beach Service (available)', 'Beach Service (available)'),
(404, 4, 159, 'Beach Service (included)', 'Пляж (входит в собственность)', 'Beach Service (included)', 'Пляж (входит в собственность)', 'Strand-Service (inclusiv)', 'Strand-Service (inclusiv)'),
(405, 4, 160, 'Charcoal Grill', 'Гриль на углях', 'Charcoal Grill', 'Гриль на углях', 'Kohle Grill', 'Kohle Grill'),
(406, 4, 161, 'Continental Breakfast', 'Continental Breakfast', 'Continental Breakfast', 'Continental Breakfast', 'Continental Breakfast', 'Continental Breakfast'),
(407, 4, 162, 'Cooking Utensils Provided', 'Домашняя утварь', 'Cooking Utensils Provided', 'Домашняя утварь', 'Cooking Utensils Provided', 'Cooking Utensils Provided'),
(408, 4, 163, 'Covered Parking', 'Парковка', 'Covered Parking', 'Парковка', 'Bedeckte Parkung', 'Bedeckte Parkung'),
(409, 4, 164, 'Crib', 'Crib', 'Crib', 'Crib', 'Crib', 'Crib'),
(410, 4, 165, 'Deck', 'Deck', 'Deck', 'Deck', 'Deck', 'Deck'),
(411, 4, 166, 'Elevator', 'Лифт', 'Elevator', 'Лифт', 'Fahrstuhl', 'Fahrstuhl'),
(412, 4, 167, 'Fireplace', 'Камин', 'Fireplace', 'Камин', 'Feuerstelle', 'Feuerstelle'),
(413, 4, 168, 'Fitness Center', 'Фитнес Центр', 'Fitness Center', 'Фитнес Центр', 'Fitnesscenter', 'Fitnesscenter'),
(414, 4, 169, 'Full Kitchen ', 'Полный кухонный гарнитур', 'Full Kitchen ', 'Полный кухонный гарнитур', 'Full Kitchen ', 'Full Kitchen '),
(415, 4, 170, 'Games', 'Games', 'Games', 'Games', 'Games', 'Games'),
(416, 4, 171, 'Garden', 'Сад', 'Garden', 'Сад', 'Garten', 'Garten'),
(417, 4, 172, 'Gas Grill', 'Гриль на газу', 'Gas Grill', 'Гриль на газу', 'Gas-Grill', 'Gas-Grill'),
(418, 4, 173, 'Gated Complex ', 'Огороженный комплекс', 'Gated Complex ', 'Огороженный комплекс', 'Gated Complex ', 'Gated Complex '),
(419, 4, 174, 'High Chair', 'High Chair', 'High Chair', 'High Chair', 'High Chair', 'High Chair'),
(420, 4, 175, 'High-Speed Internet', 'Высокоскоростной интернет', 'High-Speed Internet', 'Высокоскоростной интернет', 'Hochgeschwindigkeitsinternet', 'Hochgeschwindigkeitsinternet'),
(421, 4, 176, 'Jacuzzi', 'Джакуззи', 'Jacuzzi', 'Джакуззи', 'Sprudelbad', 'Sprudelbad'),
(422, 4, 177, 'Lanai', 'Веранда', 'Lanai', 'Веранда', 'Veranda', 'Veranda'),
(423, 4, 178, 'Linens Provided ', 'Linens Provided ', 'Linens Provided ', 'Linens Provided ', 'Linens Provided ', 'Linens Provided '),
(424, 4, 179, 'Lounge/Common Area', 'Гостиная', 'Lounge/Common Area', 'Гостиная', 'Lounge/Common Area', 'Lounge/Common Area'),
(425, 4, 180, 'Maid Service Available', '?меется прислуга', 'Maid Service Available', '?меется прислуга', 'Maid Service Available', 'Maid Service Available'),
(426, 4, 181, 'Patio', 'Внутренний дворик/патио', 'Patio', 'Внутренний дворик/патио', 'Patio', 'Patio'),
(427, 4, 182, 'Pool', 'Бассейн', 'Pool', 'Бассейн', 'Bassin', 'Bassin'),
(428, 4, 183, 'Pool (private)', 'Pool (private)', 'Pool (private)', 'Pool (private)', 'Pool (private)', 'Pool (private)'),
(429, 4, 184, 'Sauna', 'Сауна', 'Sauna', 'Сауна', 'Sauna', 'Sauna'),
(430, 4, 185, 'Sauna (private)', 'Sauna (private)', 'Sauna (private)', 'Sauna (private)', 'Sauna (private)', 'Sauna (private)'),
(431, 4, 186, 'Spa/Hot Tub', 'Спа/горячая ванна', 'Spa/Hot Tub', 'Спа/горячая ванна', 'Spa/Hot Tub', 'Spa/Hot Tub'),
(432, 4, 187, 'Spa/Hot Tub (private)', 'Spa/Hot Tub (private)', 'Spa/Hot Tub (private)', 'Spa/Hot Tub (private)', 'Spa/Hot Tub (private)', 'Spa/Hot Tub (private)'),
(433, 4, 188, 'Spices', 'Spices', 'Spices', 'Spices', 'Spices', 'Spices'),
(434, 4, 189, 'Terrace', 'Терраса', 'Terrace', 'Терраса', 'Terrasse', 'Terrasse'),
(435, 3, 16, 'Appliances', 'Наличие приспособлений', 'Appliances', 'Необходимы приспособления', 'Geräte', 'Geräte'),
(436, 4, 190, 'Alarm Clock', 'Будильник', 'Alarm Clock', 'Будильник', 'Wecker', 'Wecker'),
(437, 4, 191, 'Blender', 'Блендер', 'Blender', 'Блендер', 'Blender', 'Blender'),
(438, 4, 192, 'CD Player', 'CD плеер', 'CD Player', 'CD плеер', 'CD Player', 'CD Player'),
(439, 4, 193, 'Coffee Maker', 'Кофеварка', 'Coffee Maker', 'Кофеварка', 'Kaffee-Maschine', 'Kaffee-Maschine'),
(440, 4, 194, 'Dishwasher', 'Посудомоечная машина', 'Dishwasher', 'Посудомоечная машина', 'Geschirrspüler', 'Geschirrspüler'),
(441, 4, 195, 'DVD Player', 'DVD плеер', 'DVD Player', 'DVD плеер', 'DVD Player', 'DVD Player'),
(442, 4, 196, 'Freezer', 'Морозильник', 'Freezer', 'Морозильник', 'Gefrierschrank', 'Gefrierschrank'),
(443, 4, 197, 'Iron and Board', 'Утюг и гладильная доска', 'Iron and Board', 'Утюг и гладильная доска', 'Bügeleisen', 'Bügeleisen'),
(444, 4, 198, 'Microwave', 'Микроволновая печь', 'Microwave', 'Микроволновая печь', 'Mikrowelle', 'Mikrowelle'),
(445, 4, 199, 'Oven', 'Oven', 'Oven', 'Oven', 'Oven', 'Oven'),
(446, 4, 200, 'Phone', 'Телефон', 'Phone', 'Телефон', 'Telefon', 'Telefon'),
(447, 4, 201, 'Radio', 'Радио', 'Radio', 'Радио', 'Radio', 'Radio'),
(448, 4, 202, 'Refrigerator', 'Холодильник', 'Refrigerator', 'Холодильник', 'Kühlschrank', 'Kühlschrank'),
(449, 4, 203, 'Air conditioning', 'Кондиционер', 'Air conditioning', 'Кондиционер', 'Klimaanlage', 'Klimaanlage'),
(450, 4, 204, 'Stereo', 'Стерео система', 'Stereo', 'Стерео система', 'Stereo', 'Stereo'),
(451, 4, 205, 'Toaster', 'Тостер', 'Toaster', 'Тостер', 'Toaster', 'Toaster'),
(452, 4, 206, 'TV(s)', 'Телевизор(ы)', 'TV(s)', 'Телевизор(ы)', 'TV(s)', 'TV(s)'),
(453, 4, 207, 'VCR Player', 'Видеомагнитофон', 'VCR Player', 'Видеомагнитофон', 'VCR Player', 'VCR Player'),
(454, 4, 208, 'Washing machine', 'Стиральная машина', 'Washing machine', 'Стиральная машина', 'Waschmaschine', 'Waschmaschine'),
(455, 4, 209, 'Washer/Dryer (private)', 'Washer/Dryer (private)', 'Washer/Dryer (private)', 'Washer/Dryer (private)', 'Washer/Dryer (private)', 'Washer/Dryer (private)'),
(467, 4, 221, 'Water supply', 'Водопровод', 'Water supply', 'Водопровод', 'Wasseranschluss', 'Wasseranschluss'),
(468, 4, 222, 'Hot water and heating', 'Горячая вода и отопление', 'Hot water and heating', 'Горячая вода и отопление', 'Heißwasser und Aufheizung', 'Heißwasser und Aufheizung'),
(469, 4, 223, 'Canalization ', 'Канализация', 'Canalization ', 'Канализация', 'Kanalization ', 'Kanalization '),
(470, 4, 224, 'Electricity', 'Электричество', 'Electricity', 'Электричество', 'Elektrizität', 'Elektrizität'),
(471, 4, 225, 'Telephone line', 'Телефонная линия', 'Telephone line', 'Телефонная линия', 'Telefonanschluss', 'Telefonanschluss'),
(472, 4, 226, 'Satellite TV or Cable TV ', 'Спутниковое или кабельное телевидение', 'Satellite TV or Cable TV ', 'Спутниковое или кабельное телевидение', 'Satellit-TV oder Kabel-TV', 'Satellit-TV oder Kabel-TV'),
(473, 4, 227, 'Electronic Security ', 'Электронная охрана', 'Electronic Security ', 'Электронная охрана', 'Electronische Geborgenheit', 'Electronische Geborgenheit'),
(474, 4, 228, 'Security ', 'Охрана', 'Security ', 'Охрана', 'Geborgenheit', 'Geborgenheit'),
(475, 4, 229, 'Billiards room', 'Бильярдная', 'Billiards room', 'Бильярдная', 'Billard-Zimmer', 'Billard-Zimmer'),
(476, 4, 230, 'Gym', 'Тренажерный зал', 'Gym', 'Тренажерный зал', 'Turnhalle', 'Turnhalle'),
(477, 4, 231, 'Balcony/Deck', 'Балкон', 'Balcony/Deck', 'Балкон', 'Balkon', 'Balkon'),
(478, 4, 232, 'Bathhouse', 'Баня', 'Bathhouse', 'Баня', 'Badehaus', 'Badehaus'),
(479, 4, 233, 'Drier', 'Сушильная машина', 'Drier', 'Сушильная машина', 'Trockenmaschine', 'Trockenmaschine'),
(480, 4, 234, 'High-rise buildings', 'Дома высотки', 'High-rise buildings', 'Дома высотки', 'Hochhäuser', 'Hochhäuser'),
(481, 4, 235, 'Low houses ', 'Дома невысокие ', 'Low houses ', 'Дома невысокие ', 'Kleinhaus', 'Kleinhaus'),
(482, 4, 236, 'Near Bus Stop ', 'Автобусная остановка', 'Near Bus Stop ', 'Автобусная остановка', 'Busstation in der Nähe', 'Busstation in der Nähe'),
(483, 4, 237, 'Near Subway ', 'Метро', 'Near Subway ', 'Метро', 'U-Bahn in der Nähe', 'U-Bahn in der Nähe'),
(484, 4, 238, 'Parking Lot ', 'Стоянка автомобилей', 'Parking Lot ', 'Стоянка автомобилей', 'Parkung', 'Parkung'),
(485, 4, 239, 'Disability Access ', 'Вход для инвалидов', 'Disability Access ', 'Вход для инвалидов', 'Behinderungszutritt', 'Behinderungszutritt'),
(486, 4, 240, 'Laundry ', 'Прачечная', 'Laundry ', 'Прачечная', 'Waschanstalt', 'Waschanstalt'),
(487, 4, 241, 'Swimming Pool ', 'Бассейн', 'Swimming Pool ', 'Бассейн', 'Schwimmbad', 'Schwimmbad'),
(488, 4, 242, 'Skating ring', 'Каток', 'Skating ring', 'Каток', 'Skating ring', 'Skating ring'),
(489, 4, 243, 'Fitness Center ', 'Фитнес Центр', 'Fitness Center ', 'Фитнес Центр', 'Fitnesscenter ', 'Fitnesscenter '),
(490, 4, 244, 'Walkup ', 'Прогулочная зона ', 'Walkup ', 'Прогулочная зона ', 'Walkup ', 'Walkup '),
(491, 4, 245, 'Near the river', 'Рядом с рекой', 'Near the river', 'Рядом с рекой', 'Fluss in der Nähe', 'Fluss in der Nähe'),
(495, 2, 3, 'A family with children', 'Семья с детьми', 'A family with children', 'Семью с детьми', 'Familie mit Kindern', 'Familie mit Kindern'),
(496, 2, 4, 'A family without children', 'Семья без детей', 'A family without children', 'Семью без детей', 'Familie ohne Kinder', 'Familie ohne Kinder'),
(497, 6, 44, 'Ambitious of wealth', 'Стремлюсь к большему ', 'Ambitious of wealth', 'Стремится к большему ', 'Strebsam nach Reichtum', 'Strebsam nach Reichtum'),
(498, 6, 45, 'No, I don''t and I can''t stand smoke', 'Нет и не переношу запах', 'No, he/she doesn''t and can''t stand smoke', 'Нет и не переносит запах  ', 'Nein, rauch nicht und kann nicht es ausstehen', 'Nein, rauch nicht und kann nicht es ausstehen'),
(499, 6, 46, 'No I don''t but I am loyal to those smoking', 'Нет, но лояльно отношусь к курильщикам', 'No he/she doesn''t but he/she is loyal to those smoking', 'Нет, но лояльно относится к курильщикам  ', 'Nein, rauch nicht, aber bin loyal dazu', 'Nein, rauch nicht, aber bin loyal dazu'),
(500, 6, 47, 'I smoke occasionally ', 'Да, но редко', 'He/she smokes occasionally ', 'Да, но редко', 'Ich rauche gelegentlich', 'Ich rauche gelegentlich'),
(501, 6, 48, 'I smoke', 'Да', 'He/she smokes', 'Да', 'Ich rauche', 'Ich rauche'),
(502, 6, 49, 'I smoke but trying to give it up', 'Да, но бросаю', 'He/she smokes but tries to give it up', 'Да, но бросает', 'Ich rauche, aber möchte darauf verzichten', 'Ich rauche, aber möchte darauf verzichten'),
(503, 5, 13, 'I am', 'Я по режиму сна', 'I am', 'Он(а) по режиму сна', 'Ich bin', 'Ich bin'),
(504, 6, 50, 'Night owl (go to bed late, get up late)', 'Сова (поздно ложусь, поздно встаю)', 'Night owl (go to bed late, get up late)', 'Сова (поздно ложится, поздно встаёт)', 'Nachteule (gehe spät zu Bett, steh spät auf)', 'Nachteule (gehe spät zu Bett, steh spät auf)'),
(505, 6, 51, 'Lark (rise with the lark)', 'Жаворонок (рано просыпаюсь) ', 'Lark (rise with the lark)', 'Жаворонок (рано просыпается) ', 'Lerche (steh mit Lerchen auf)', 'Lerche (steh mit Lerchen auf)'),
(506, 6, 52, 'Other', 'Ни то, ни другое ', 'Other', 'Ни то, ни другое ', 'Anderes', 'Anderes'),
(507, 6, 54, 'asd', 'asd', 'asd', 'asd', 'asd', 'asd'),
(515, 17, 2, 'Property type', 'Вид недвижимости', 'Property type', 'Вид недвижимости', 'Immobilientyp', 'Immobilientyp'),
(522, 18, 7, 'Передвижной дом-фургон', 'Передвижной дом-фургон', 'Передвижной дом-фургон', 'Передвижной дом-фургон', 'Передвижной дом-фургон', 'Передвижной дом-фургон'),
(523, 18, 8, 'Mobile Home', 'Передвижной дом-фургон', 'Mobile Home', 'Передвижной дом-фургон', 'Mobilheim', 'Mobile Home'),
(524, 18, 9, 'Room', 'Комната', 'Room', 'Комнату', 'Zimmer', 'Zimmer'),
(525, 18, 10, 'Apartment', 'Квартира', 'Apartment', 'Квартиру', 'Wohnung', 'Wohnung'),
(526, 18, 11, 'Apartment Complex', 'Квартирный комплекс', 'Apartment Complex', 'Квартирный комплекс', 'Wohnkomplex', 'Wohnkomplex'),
(527, 18, 12, 'Duplex', 'Квартира в двух уровнях', 'Duplex', 'Квартиру в двух уровнях', 'Zweifamilienhaus', 'Zweifamilienhaus'),
(528, 18, 13, 'Triplex', 'Квартира в трёх уровнях', 'Triplex', 'Квартиру в трёх уровнях', 'Triplex', 'Triplex'),
(529, 21, 1, 'Term of lease', 'Срок аренды', 'Term of lease', 'Срок аренды', 'Pachtdauer', 'Pachtdauer'),
(530, 22, 1, '1 month', '1 месяц', '1 month', '1 месяц', '1 Monat', '1 Monat'),
(531, 22, 2, '3 months', '3 месяца', '3 months', '3 месяца', '3 Monate', '3 Monate'),
(532, 22, 3, 'more than 6 months', 'более 6 месяцев', 'more than 6 months', 'более 6 месяцев', 'mehr, als 6 Monate', 'mehr, als 6 Monate'),
(533, 22, 4, 'unlimited', 'неограничен', 'unlimited', 'неограничен', 'unbegrenzt', 'unbegrenzt'),
(534, 19, 1, 'Number of bedrooms', 'Количество спален', 'Number of bedrooms', 'Количество спален', 'Anzahl der Zimmer', 'Anzahl der Zimmer'),
(535, 20, 1, '1', '1', '1', '1', '1', '1'),
(536, 20, 2, '2', '2', '2', '2', '2', '2'),
(537, 20, 3, '3', '3', '3', '3', '3', '3'),
(538, 20, 4, '4', '4', '4', '4', '4', '4');
INSERT INTO `re_reference_lang_spr` (`id`, `table_key`, `id_reference`, `lang_1_1`, `lang_2_1`, `lang_1_2`, `lang_2_2`, `lang_3_1`, `lang_3_2`) VALUES
(539, 20, 5, '5', '5', '5', '5', '5', '5'),
(540, 20, 6, '6', '6', '6', '6', '6', '6'),
(541, 20, 7, '7', '7', '7', '7', '7', '7'),
(542, 20, 8, '8', '8', '8', '8', '8', '8'),
(543, 20, 9, '9', '9', '9', '9', '9', '9'),
(544, 20, 10, '10', '10', '10', '10', '10', '10'),
(545, 20, 11, '10+', '10+', '10+', '10+', '10+', '10+'),
(546, 19, 2, 'Number of bathrooms', 'Количество ванных комнат', 'Number of bathrooms', 'Количество ванных комнат', 'Anzahl der Badezimmer', 'Anzahl der Badezimmer'),
(547, 20, 12, '1', '1', '1', '1', '1', '1'),
(548, 20, 13, '2', '2', '2', '2', '2', '2'),
(549, 20, 14, '3', '3', '3', '3', '3', '3'),
(550, 20, 15, '4', '4', '4', '4', '4', '4'),
(551, 20, 16, '5', '5', '5', '5', '5', '5'),
(552, 20, 17, '6', '6', '6', '6', '6', '6'),
(553, 20, 18, '7', '7', '7', '7', '7', '7'),
(554, 20, 19, '8', '8', '8', '8', '8', '8'),
(555, 20, 20, '9', '9', '9', '9', '9', '9'),
(556, 20, 21, '10', '10', '10', '10', '10', '10'),
(557, 20, 22, '10+', '10+', '10+', '10+', '10+', '10+'),
(558, 19, 3, 'Garage', 'Гараж', 'Garage', 'Гараж', 'Garage', 'Garage'),
(559, 20, 23, '1 car', 'на 1 машину', '1 car', 'на 1 машину', '1 car', '1 car'),
(560, 20, 24, '2 cars', 'на 2 машины', '2 cars', 'на 2 машины', '2 cars', '2 cars'),
(561, 20, 25, '3 cars', 'на 3 машины ', '3 cars', 'на 3 машины ', '3 cars', '3 cars'),
(562, 20, 26, '4 cars', 'на 4 машины', '4 cars', 'на 4 машины', '4 cars', '4 cars'),
(563, 20, 27, '5 cars', 'на 5 машин', '5 cars', 'на 5 машин', '5 cars', '5 cars'),
(564, 20, 28, '5+ cars', 'на 5 + машин', '5+ cars', 'на 5 + машин', '5+ cars', '5+ cars'),
(565, 18, 14, 'Bungalow', 'Бунгало', 'Bungalow', 'Бунгало', 'Bungalow', 'Bungalow'),
(566, 18, 15, 'Townhouse', 'Городской одноквартирный дом', 'Townhouse', 'Городской одноквартирный дом', 'Stadthaus', 'Stadthaus'),
(567, 18, 16, 'Single Family Home', 'Дом для одной семьи', 'Single Family Home', 'Дом для одной семьи', 'Einfamilienhaus', 'Einfamilienhaus'),
(568, 18, 17, 'Guest House', 'Дом для гостей', 'Guest House', 'Дом для гостей', 'Gasthaus', 'Gasthaus'),
(569, 18, 18, 'Cabin/Cottage', 'Небольшой дом/коттедж', 'Cabin/Cottage', 'Небольшой дом/коттедж', 'Wochenendhaus/Landhaus', 'Wochenendhaus/Landhaus'),
(570, 18, 19, 'House', 'Дом', 'House', 'Дом', 'Haus', 'Haus'),
(571, 18, 20, 'Mansion', 'Особняк', 'Mansion', 'Особняк', 'Wohnhaus', 'Wohnhaus'),
(572, 18, 21, 'Castle', 'Замок', 'Castle', 'Замок', 'Kastell', 'Kastell'),
(573, 18, 22, 'Chateau', 'Дворец', 'Chateau', 'Дворец', 'Schloss', 'Schloss'),
(574, 18, 23, 'Villa', 'Вилла', 'Villa', 'Виллу', 'Villa', 'Villa'),
(575, 18, 24, 'Farm/Ranch', 'Ферма/ранчо', 'Farm/Ranch', 'Ферму/ранчо', 'Farm/Ranch', 'Farm/Ranch'),
(576, 18, 25, 'Condo', 'Кондоминиум', 'Condo', 'Кондоминиум', 'Eigentumswohnung', 'Eigentumswohnung'),
(577, 18, 26, 'Condo Hotel', 'Кондо-отель', 'Condo Hotel', 'Кондо-отель', 'Gasthof', 'Gasthof'),
(578, 18, 27, 'Inn/Lodge', 'Гостиница (сельская)', 'Inn/Lodge', 'Гостиницу (сельскую)', 'Wirtshaus/Hütte', 'Wirtshaus/Hütte'),
(579, 18, 28, 'Motel', 'Мотель', 'Motel', 'Мотель', 'Motel', 'Motel'),
(580, 18, 29, 'Bed and Breakfast', 'Частная гостиница', 'Bed and Breakfast', 'Частную гостиницу', 'Zimmer mit Frühstück', 'Zimmer mit Frühstück'),
(581, 18, 30, 'Hotel', 'Отель', 'Hotel', 'Отель', 'Hotel', 'Hotel'),
(582, 18, 31, 'Lots/Land', 'Земельный участок', 'Lots/Land', 'Земельный участок', 'Bodengrundstück', 'Bodengrundstück'),
(583, 18, 32, 'Plantation', 'Зона зелёных насаждений', 'Plantation', 'Зону зелёных насаждений', 'Plantage', 'Plantage'),
(584, 18, 33, 'Beach Property', 'Пляжная территория', 'Beach Property', 'Пляжную территорию', 'Strandhaus', 'Strandhaus'),
(585, 18, 34, 'Coastal Property', 'Прибрежная собственность', 'Coastal Property', 'Прибрежную собственность', 'Küstenhaus', 'Küstenhaus'),
(586, 18, 35, 'Marina', 'Пристань', 'Marina', 'Пристань', 'Yachthafen', 'Yachthafen'),
(587, 18, 36, 'Loft', 'Нежилое чердачное помещение', 'Loft', 'Нежилое чердачное помещение', 'Dachboden', 'Dachboden'),
(588, 18, 37, 'Commercial Property', 'Коммерческая недвижимость', 'Commercial Property', 'Коммерческую недвижимость', 'Gewerbeimmobilie', 'Gewerbeimmobilie'),
(589, 18, 38, 'Office Space', 'Офисная площадь', 'Office Space', 'Офисную площадь', 'Büroräume', 'Büroräume'),
(590, 18, 39, 'Business', 'Помещение для деловых операций', 'Business', 'Помещение для деловых операций', 'Business', 'Business'),
(591, 18, 40, 'Store Front', 'Помещение на первом этаже с выходом на улицу', 'Store Front', 'Помещение на первом этаже с выходом на улицу', 'Store Front', 'Store Front'),
(592, 18, 41, 'Manufactured Home', 'Производственная площадь', 'Manufactured Home', 'Производственную площадь', 'Fertighaus', 'Fertighaus'),
(593, 18, 42, 'Warehouse', 'Складское помещение', 'Warehouse', 'Складское помещение', 'Lagerhaus', 'Lagerhaus'),
(594, 18, 43, 'Restaurant', 'Ресторан', 'Restaurant', 'Ресторан', 'Restaurant', 'Restaurant'),
(595, 18, 44, 'Winery', 'Винодельня', 'Winery', 'Винодельню', 'Weinkellerei', 'Weinkellerei'),
(596, 18, 45, 'Vineyard', 'Виноградник', 'Vineyard', 'Виноградник', 'Weingarten', 'Weingarten'),
(597, 18, 46, 'Resort', 'Место отдыха', 'Resort', 'Место отдыха', 'Ferienort', 'Ferienort'),
(598, 18, 47, 'Very Exotic Property', 'Экзотическая собственность', 'Very Exotic Property', 'Экзотическую собственность', 'Exotische Immobilie', 'Exotische Immobilie'),
(599, 18, 48, 'Island', 'Остров', 'Island', 'Остров', 'Insel', 'Insel'),
(600, 18, 49, 'Other', 'Другое', 'Other', 'Другое', 'Anderes', 'Anderes'),
(601, 23, 1, '', 'Тематический отдых', '', 'Тематический отдых', '', ''),
(602, 23, 2, '', 'Активный отдых', '', 'Активный отдых', '', ''),
(603, 24, 1, 'Отдых дикарем', 'Отдых дикарем', 'Отдых дикарем', 'Отдых дикарем', 'Отдых дикарем', 'Отдых дикарем'),
(604, 24, 2, 'Пляжные туры', 'Пляжные туры', 'Пляжные туры', 'Пляжные туры', 'Пляжные туры', 'Пляжные туры'),
(605, 24, 3, 'Праздничные туры', 'Праздничные туры', 'Праздничные туры', 'Праздничные туры', 'Праздничные туры', 'Праздничные туры'),
(606, 24, 4, 'Автобусные туры', 'Автобусные туры', 'Автобусные туры', 'Автобусные туры', 'Автобусные туры', 'Автобусные туры'),
(607, 24, 5, 'Бизнес туры', 'Бизнес туры', 'Бизнес туры', 'Бизнес туры', 'Бизнес туры', 'Бизнес туры'),
(608, 24, 6, 'Паломнические туры', 'Паломнические туры', 'Паломнические туры', 'Паломнические туры', 'Паломнические туры', 'Паломнические туры'),
(609, 24, 7, 'Горнолыжные туры', 'Горнолыжные туры', 'Горнолыжные туры', 'Горнолыжные туры', 'Горнолыжные туры', 'Горнолыжные туры'),
(610, 24, 8, 'Детский отдых', 'Детский отдых', 'Детский отдых', 'Детский отдых', 'Детский отдых', 'Детский отдых'),
(611, 24, 9, 'Романтические туры', 'Романтические туры', 'Романтические туры', 'Романтические туры', 'Романтические туры', 'Романтические туры'),
(612, 24, 10, 'Серфинг', 'Серфинг', 'Серфинг', 'Серфинг', 'Серфинг', 'Серфинг'),
(613, 24, 11, 'Дайвинг', 'Дайвинг', 'Дайвинг', 'Дайвинг', 'Дайвинг', 'Дайвинг'),
(614, 24, 12, 'Горнолыжный отдых', 'Горнолыжный отдых', 'Горнолыжный отдых', 'Горнолыжный отдых', 'Горнолыжный отдых', 'Горнолыжный отдых'),
(615, 24, 13, 'Эскурсионные туры', 'Эскурсионные туры', 'Эскурсионные туры', 'Эскурсионные туры', 'Эскурсионные туры', 'Эскурсионные туры'),
(616, 24, 14, 'Круизные туры', 'Круизные туры', 'Круизные туры', 'Круизные туры', 'Круизные туры', 'Круизные туры'),
(617, 24, 15, 'Лечебные туры', 'Лечебные туры', 'Лечебные туры', 'Лечебные туры', 'Лечебные туры', 'Лечебные туры'),
(618, 24, 16, 'Активный отдых', 'Активный отдых', 'Активный отдых', 'Активный отдых', 'Активный отдых', 'Активный отдых'),
(619, 24, 17, 'Семейный отдых', 'Семейный отдых', 'Семейный отдых', 'Семейный отдых', 'Семейный отдых', 'Семейный отдых'),
(620, 24, 18, 'Ночные клубы', 'Ночные клубы', 'Ночные клубы', 'Ночные клубы', 'Ночные клубы', 'Ночные клубы'),
(621, 24, 19, 'Аквапарки', 'Аквапарки', 'Аквапарки', 'Аквапарки', 'Аквапарки', 'Аквапарки'),
(622, 24, 20, 'Морские прогулки', 'Морские прогулки', 'Морские прогулки', 'Морские прогулки', 'Морские прогулки', 'Морские прогулки'),
(623, 24, 21, 'Эскурсии', 'Эскурсии', 'Эскурсии', 'Эскурсии', 'Эскурсии', 'Эскурсии'),
(624, 24, 22, 'Советы', 'Советы', 'Советы', 'Советы', 'Советы', 'Советы'),
(625, 24, 23, 'Фотогалерея', 'Фотогалерея', 'Фотогалерея', 'Фотогалерея', 'Фотогалерея', 'Фотогалерея'),
(626, 24, 24, 'Достопримечательности', 'Достопримечательности', 'Достопримечательности', 'Достопримечательности', 'Достопримечательности', 'Достопримечательности'),
(627, 24, 25, 'Лыжные походы', 'Лыжные походы', 'Лыжные походы', 'Лыжные походы', 'Лыжные походы', 'Лыжные походы'),
(628, 24, 26, 'Рыбалка', 'Рыбалка', 'Рыбалка', 'Рыбалка', 'Рыбалка', 'Рыбалка'),
(629, 24, 27, 'Фотоохота', 'Фотоохота', 'Фотоохота', 'Фотоохота', 'Фотоохота', 'Фотоохота'),
(630, 24, 28, 'Прыжки с парашутом', 'Прыжки с парашутом', 'Прыжки с парашутом', 'Прыжки с парашутом', 'Прыжки с парашутом', 'Прыжки с парашутом'),
(631, 24, 29, 'Горный туризм', 'Горный туризм', 'Горный туризм', 'Горный туризм', 'Горный туризм', 'Горный туризм'),
(632, 24, 30, 'Водный туризм', 'Водный туризм', 'Водный туризм', 'Водный туризм', 'Водный туризм', 'Водный туризм'),
(633, 24, 31, 'Эскурсионные туры', 'Эскурсионные туры', 'Эскурсионные туры', 'Эскурсионные туры', 'Эскурсионные туры', 'Эскурсионные туры'),
(634, 24, 32, 'Конный туризм', 'Конный туризм', 'Конный туризм', 'Конный туризм', 'Конный туризм', 'Конный туризм'),
(635, 24, 33, 'Вело туризм', 'Вело туризм', 'Вело туризм', 'Вело туризм', 'Вело туризм', 'Вело туризм'),
(636, 24, 34, 'Джипинг', 'Джипинг', 'Джипинг', 'Джипинг', 'Джипинг', 'Джипинг'),
(637, 24, 35, 'Спелео туризм', 'Спелео туризм', 'Спелео туризм', 'Спелео туризм', 'Спелео туризм', 'Спелео туризм'),
(638, 24, 36, 'Скалолазание', 'Скалолазание', 'Скалолазание', 'Скалолазание', 'Скалолазание', 'Скалолазание'),
(639, 24, 37, 'Туристические походы', 'Туристические походы', 'Туристические походы', 'Туристические походы', 'Туристические походы', 'Туристические походы'),
(640, 24, 38, 'Места боевой славы', 'Места боевой славы', 'Места боевой славы', 'Места боевой славы', 'Места боевой славы', 'Места боевой славы');

-- --------------------------------------------------------

--
-- Table structure for table `re_region_abb_spr`
--

DROP TABLE IF EXISTS `re_region_abb_spr`;
CREATE TABLE IF NOT EXISTS `re_region_abb_spr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_region` int(11) NOT NULL DEFAULT '0',
  `region_abb` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

--
-- Dumping data for table `re_region_abb_spr`
--

INSERT INTO `re_region_abb_spr` (`id`, `id_region`, `region_abb`) VALUES
(1, 4198, 'AL'),
(2, 4199, 'AK'),
(3, 4200, 'AZ'),
(4, 4201, 'AR'),
(5, 4202, 'CA'),
(6, 4203, 'CO'),
(7, 4204, 'CT'),
(8, 4205, 'DE'),
(9, 4206, 'DC'),
(10, 4207, 'FL'),
(11, 4208, 'GA'),
(12, 4209, 'HI'),
(13, 4210, 'ID'),
(14, 4211, 'IL'),
(15, 4212, 'IN'),
(16, 4213, 'IA'),
(17, 4214, 'KS'),
(18, 4215, 'KY'),
(19, 4216, 'LA'),
(20, 4217, 'ME'),
(21, 4218, 'MD'),
(22, 4219, 'MA'),
(23, 4220, 'MI'),
(24, 4221, 'MN'),
(25, 4222, 'MS'),
(26, 4223, 'MO'),
(27, 4224, 'MT'),
(28, 4225, 'NE'),
(29, 4226, 'NV'),
(30, 4227, 'NH'),
(31, 4228, 'NJ'),
(32, 4229, 'NM'),
(33, 4230, 'NY'),
(34, 4231, 'NC'),
(35, 4232, 'ND'),
(36, 4233, 'OH'),
(37, 4234, 'OK'),
(38, 4235, 'OR'),
(39, 4236, 'PA'),
(40, 4237, 'RI'),
(41, 4238, 'SC'),
(42, 4239, 'SD'),
(43, 4240, 'TN'),
(44, 4241, 'TH'),
(45, 4242, 'UT'),
(46, 4243, 'VT'),
(47, 4244, 'VA'),
(48, 4245, 'WA'),
(49, 4246, 'WV'),
(50, 4247, 'WI'),
(51, 4248, 'WY');

-- --------------------------------------------------------

--
-- Table structure for table `re_region_spr`
--

DROP TABLE IF EXISTS `re_region_spr`;
CREATE TABLE IF NOT EXISTS `re_region_spr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `id_country` (`id_country`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_region_spr`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_rent_ads`
--

DROP TABLE IF EXISTS `re_rent_ads`;
CREATE TABLE IF NOT EXISTS `re_rent_ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `type` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `movedate` date NOT NULL DEFAULT '0000-00-00',
  `people_count` int(3) NOT NULL DEFAULT '0',
  `comment` text,
  `headline` varchar(255) NOT NULL DEFAULT '',
  `datenow` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `sold_leased_status` enum('0','1') NOT NULL DEFAULT '0',
  `upload_path` varchar(255) NOT NULL DEFAULT '',
  `date_slided` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `room_type` enum('0','1') NOT NULL DEFAULT '0',
  `with_photo` enum('1','0') NOT NULL DEFAULT '0',
  `with_video` enum('1','0') NOT NULL DEFAULT '0',
  `date_unactive` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=84 ;

--
-- Dumping data for table `re_rent_ads`
--

INSERT INTO `re_rent_ads` (`id`, `id_user`, `type`, `movedate`, `people_count`, `comment`, `headline`, `datenow`, `status`, `sold_leased_status`, `upload_path`, `date_slided`, `room_type`, `with_photo`, `with_video`, `date_unactive`) VALUES
(59, 101, '4', '2008-07-01', 0, '3 family Brownstone. Lots of original details. Needs to be seen. View of the Empire State Building from the top floor. 2bd/2bd/4bd duplex/semi-finished basement. Will be delivered  vacant.', '', '2008-05-24 17:01:22', '1', '0', '', '0000-00-00 00:00:00', '0', '0', '0', '0000-00-00 00:00:00'),
(60, 102, '4', '2008-07-01', 0, ' HOUSE MUST BE MOVED!! House is in good condition with all appliances.', '', '2008-04-25 17:36:23', '1', '0', '', '0000-00-00 00:00:00', '0', '0', '0', '0000-00-00 00:00:00'),
(62, 103, '4', '2008-07-26', 0, '****AFFORTABLE HOME WITH A GREAT VIEW OF MT.HOOD* **** ***WASHOUGAL WASHINTION*** NEW HOME, NEVER LIVED IN --- BUILD IN 2007 --- 3504 Sq Ft, 3 Bedroom 2.5 Bathrooms & Office --- Hardwood & Title Floors --- Vaulted Ceiling in All Bedrooms --- Formal Dining Room w/ Built- in Sideboard --- Big Room with Fireplace, Sound System --- Gourmet Kitchen w/ SlabGranite Countertops, Island, Eat Bar, Stainless Steel Appliances, Pantry --- Nook with Build- in Desk & Cabinets, Access to Covered Desk & Big Patio --- Master Suite w/ Jet- Tub Shower, Walk-in Closet, Dual Vanities, Linen Closet --- Laundry Room w/ Cabinets, Sink & Ironing Board --- Landscape w/ Sprinklers --- 3 Car Tandem Garage --- Central Heating & A/C, Central Vacuum System --- Excellent Camas Schools, Great Location --- VIEW TO INJOY!!! --- $665,000 ', '', '2008-04-26 11:03:13', '1', '0', '', '0000-00-00 00:00:00', '0', '0', '0', '0000-00-00 00:00:00'),
(63, 104, '4', '2008-07-14', 0, 'Great location in Salmon creek built in 2000 on cul-de-sac in the Vancouver school district. This is a 4 bedroom 2 1/2 bath, 1980 square feet, great room concept, with formal dining and a large bonus room. The multi level deck leads out to a semi private backyard that is a tropical oasis in the summertime. It includes a nice size tool shed on the side of the house. This home has a large storage area underneath house and RV Parking on side. It has an over size 2 car garage with work bench. All appliances will stay, including Fridge ,washer and dryer. The house is built with plenty of space on each side of the house, 10 feet from fence where other houses in the neighborhood only have five', '', '2008-04-26 11:16:58', '1', '0', '', '0000-00-00 00:00:00', '0', '0', '0', '0000-00-00 00:00:00'),
(64, 105, '4', '2008-07-17', 0, '25 beautiful acres with views like these can be yours for only $5000.00 per acre. 45 min. from Missoula, MT; 3 miles east of St. Ignatius, Mt in the beautiful and historic Mission Valley. Paved road, great wells, fenced, irrigated, great neighbors. Only 2 miles from Mission Mountain Wilderness. All sorts of both summer and winter outdoor recreational activities are available. Short distances to the National Bison Range, Glacier Park, the Bob Marshall Wilderness and Flathead Lake. No covenants.', '', '2008-04-26 14:20:42', '1', '0', '', '0000-00-00 00:00:00', '0', '0', '0', '0000-00-00 00:00:00'),
(65, 106, '4', '2008-07-06', 0, NULL, '', '2008-04-26 14:32:22', '1', '0', '', '0000-00-00 00:00:00', '0', '0', '0', '0000-00-00 00:00:00'),
(66, 107, '2', '2008-04-27', 0, 'Be a Part of a Landmark. Class A office/retail space is available in the most famous building in the world, in the greatest city in the world. In the heart of Manhattan, The Empire State Building, located at 350 Fifth Avenue, offers classic space which is modern, convenient and affordable.\r\n\r\nWith office suites to suit almost any firm or organization, the building''s modernization program has made it a rare find indeed for those lucky enough to occupy it.\r\n\r\nInvestigating your options for prime office space in New York is essential to your success. To that end, please feel free to get to know the management of the building, the atmosphere of the neighborhood - and even a complete list of your future neighbors!\r\n\r\nIf you have any questions regarding leasing space in the building and the advantages of having a business address at 350 Fifth Avenue, please email us.\r\n\r\nThe average monthly rental is $37.00+ PSF (per square foot) per month.', '', '2008-04-26 17:56:32', '1', '0', '', '0000-00-00 00:00:00', '0', '0', '0', '0000-00-00 00:00:00'),
(67, 108, '4', '2008-07-16', 0, 'Masterpiece under construction with sweeping bay views! Beautiful 4 bed, 3.5 bath waterfront home under construction in the upscale gated community of Symphony Isles. 3833 square feet with water views from most rooms. Gourmet kitchen: 42'' custom cabinets, granite counters, designer appliances including a 66" built-in fridge, tile floors with granite detail, and cooking island. Formal dining room with wood floors, bonus room over the 3-car garage, master bedroom downstairs, ceiling fans, security system, large walk-in closets, and custom wrought iron railing. Large screened-in lanai with pavers and custom heated swimming pool and spa. Tile roof, pavers, and lush landscaping with irrigation system. Seawall and dock in place. Community has gated security, community pool, playground, boat ramp, and private beach. Deep water canal - just seconds from Tampa Bay for those incredible sunsets, and access to the Gulf of Mexico!', '', '2008-04-28 12:42:43', '1', '0', '', '0000-00-00 00:00:00', '0', '0', '0', '0000-00-00 00:00:00'),
(68, 109, '4', '2008-07-28', 0, 'THIS ONE HAS IT ALL!!! Sitting high above the Town of Burnsville at approx. 3200 feet elevation this quality home offers convenient living + PANORAMIC MOUNTAIN VIEW! Kitchen with cabinets, cabinets, cabinets and walk-in pantry. Den features a stone veneer fireplace with gas logs. Formal entry hall and living room. Master Bedroom Suite with view and private access to porch/deck. Full basement has 3rd bedroom and bath. Laundry facility on each level. Paved circular drive & parking. Beautifully landscaped with small babbling brooke along property boundary. MOVE-IN READY!', '', '2008-04-28 13:15:01', '1', '0', '', '0000-00-00 00:00:00', '0', '0', '0', '0000-00-00 00:00:00'),
(81, 116, '2', '2008-06-21', 0, ' 1800 Sq. feet Two Story Home w/ Professionally Selected Colors for Interior and Exterior \r\n- Master Bedroom with Bridge to Private Veranda/Balcony Overlooking Park, Walk-in Closet, and Master Bath w/ Jacuzzi Bathtub and Walk-in Shower \r\n- Living Rm, Dining Rm, and Family Rm w/ Fireplace \r\n- Kitchen w/ Breakfast Nook \r\n- All Kitchen and Laundry Appliances Included w/ Laundry Upstairs \r\n- Vaulted Ceilings and Hardwood Floors in Entire First Floor \r\n- Great Park Across the Street, Close to Downtown HB and Freeways \r\n\r\n', '', '2008-05-21 11:50:38', '1', '0', '', '0000-00-00 00:00:00', '0', '0', '0', '0000-00-00 00:00:00'),
(78, 86, '4', '2008-10-17', 0, NULL, '', '2008-05-24 16:56:15', '1', '0', '', '0000-00-00 00:00:00', '0', '0', '0', '0000-00-00 00:00:00'),
(76, 86, '4', '2008-07-17', 0, NULL, '', '2008-05-24 16:55:37', '1', '0', '', '0000-00-00 00:00:00', '0', '0', '0', '0000-00-00 00:00:00'),
(79, 86, '2', '2008-07-17', 0, NULL, '', '2008-05-22 15:57:42', '1', '0', '', '0000-00-00 00:00:00', '0', '0', '0', '0000-00-00 00:00:00'),
(80, 115, '2', '2008-06-19', 0, 'house very bright with lots of windows. Private entrance. Wall to wall carpet. High hats, and ceiling fans. Electric (range and dryer). Curtains and Valances by windows. Access to the attic with lots of storage. Public transportation. Base board heating. Tenant is responsible for Electric bill, Phone, and Cable. Credit & Reference Checks Required.\r\n', '', '2008-05-19 16:08:54', '1', '0', '', '0000-00-00 00:00:00', '0', '0', '0', '0000-00-00 00:00:00'),
(82, 119, '2', '2008-06-22', 0, 'Walk to downtown Palatine and train station, great location. large unit with 2 bedroom 2 full and 2 1/2 baths. Open and airy floor plan. This home has a double master suite with 2nd floor laundry, washer/dryer included. First floor boost hardwood floors, kitchen island, maple cabinets and all appliances are included. Lower level basement is finished with den and 1/2 bath. This unit has never been lived in. \r\n\r\n', '', '2008-05-22 11:50:38', '1', '0', '', '0000-00-00 00:00:00', '0', '0', '0', '0000-00-00 00:00:00'),
(83, 120, '1', '2008-06-22', 0, NULL, '', '2008-05-22 12:17:26', '1', '0', '', '0000-00-00 00:00:00', '0', '0', '0', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `re_rent_ad_visit`
--

DROP TABLE IF EXISTS `re_rent_ad_visit`;
CREATE TABLE IF NOT EXISTS `re_rent_ad_visit` (
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `id_visiter` int(11) NOT NULL DEFAULT '0',
  `last_visit_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `visits_count` int(11) NOT NULL DEFAULT '0',
  KEY `id_ad` (`id_ad`),
  KEY `id_visiter` (`id_visiter`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_rent_ad_visit`
--

INSERT INTO `re_rent_ad_visit` (`id_ad`, `id_visiter`, `last_visit_date`, `visits_count`) VALUES
(59, 2, '2010-12-21 16:37:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `re_save_powersearch`
--

DROP TABLE IF EXISTS `re_save_powersearch`;
CREATE TABLE IF NOT EXISTS `re_save_powersearch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `id_user` int(11) DEFAULT NULL,
  `choise` int(3) NOT NULL DEFAULT '1',
  `country` varchar(30) NOT NULL DEFAULT '0',
  `region` varchar(30) NOT NULL DEFAULT '0',
  `city` varchar(30) NOT NULL DEFAULT '0',
  `min_payment` int(11) NOT NULL DEFAULT '0',
  `max_payment` int(11) NOT NULL DEFAULT '0',
  `auction` enum('1','2') NOT NULL DEFAULT '1',
  `min_deposit` int(11) NOT NULL DEFAULT '0',
  `max_deposit` int(11) NOT NULL DEFAULT '0',
  `movedate` date NOT NULL DEFAULT '0000-00-00',
  `use_movedate` enum('1','0') NOT NULL DEFAULT '0',
  `min_year_build` int(4) NOT NULL DEFAULT '0',
  `max_year_build` int(4) NOT NULL DEFAULT '0',
  `min_live_square` int(11) NOT NULL DEFAULT '0',
  `max_live_square` int(11) NOT NULL DEFAULT '0',
  `min_total_square` int(11) NOT NULL DEFAULT '0',
  `max_total_square` int(11) NOT NULL DEFAULT '0',
  `min_land_square` int(11) NOT NULL DEFAULT '0',
  `max_land_square` int(11) NOT NULL DEFAULT '0',
  `min_floor` int(3) NOT NULL DEFAULT '0',
  `max_floor` int(3) NOT NULL DEFAULT '0',
  `floor_num` int(3) NOT NULL DEFAULT '0',
  `subway_min` int(3) NOT NULL DEFAULT '0',
  `with_photo` enum('1','0') NOT NULL DEFAULT '0',
  `with_video` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_save_powersearch`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_save_powersearch_descr`
--

DROP TABLE IF EXISTS `re_save_powersearch_descr`;
CREATE TABLE IF NOT EXISTS `re_save_powersearch_descr` (
  `id_save` int(11) NOT NULL DEFAULT '0',
  `id_spr` int(11) NOT NULL DEFAULT '0',
  `id_info` int(11) NOT NULL DEFAULT '0',
  KEY `id_save` (`id_save`),
  KEY `id_spr` (`id_spr`),
  KEY `id_info` (`id_info`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_save_powersearch_descr`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_search_location`
--

DROP TABLE IF EXISTS `re_search_location`;
CREATE TABLE IF NOT EXISTS `re_search_location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_country` int(11) NOT NULL DEFAULT '0',
  `id_region` int(11) NOT NULL DEFAULT '0',
  `id_city` int(11) NOT NULL DEFAULT '0',
  `is_preferred` enum('0','1') NOT NULL DEFAULT '0',
  `is_primary` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_search_location`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_search_preferences`
--

DROP TABLE IF EXISTS `re_search_preferences`;
CREATE TABLE IF NOT EXISTS `re_search_preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `type` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `use_movedate` enum('0','1') NOT NULL DEFAULT '0',
  `realty_type` int(11) NOT NULL DEFAULT '0',
  `beds_number` int(11) NOT NULL DEFAULT '0',
  `min_payment` int(11) NOT NULL DEFAULT '0',
  `max_payment` int(11) NOT NULL DEFAULT '0',
  `move_day` int(2) NOT NULL DEFAULT '0',
  `move_month` int(2) NOT NULL DEFAULT '0',
  `with_photo` enum('1','0') NOT NULL DEFAULT '0',
  `bath_number` int(11) NOT NULL DEFAULT '0',
  `garage_number` int(11) NOT NULL DEFAULT '0',
  `with_video` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_search_preferences`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_sell_lease_payment_settings`
--

DROP TABLE IF EXISTS `re_sell_lease_payment_settings`;
CREATE TABLE IF NOT EXISTS `re_sell_lease_payment_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ads_number` int(11) NOT NULL DEFAULT '0',
  `amount` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `re_sell_lease_payment_settings`
--

INSERT INTO `re_sell_lease_payment_settings` (`id`, `ads_number`, `amount`) VALUES
(3, 1, 2),
(4, 2, 3.5),
(5, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `re_server_errors`
--

DROP TABLE IF EXISTS `re_server_errors`;
CREATE TABLE IF NOT EXISTS `re_server_errors` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `code` int(5) NOT NULL,
  `description` tinyblob NOT NULL,
  `message_1` blob NOT NULL,
  `message_2` blob NOT NULL,
  `message_3` blob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `re_server_errors`
--

INSERT INTO `re_server_errors` (`id`, `code`, `description`, `message_1`, `message_2`, `message_3`) VALUES
(1, 400, 0x4261642052657175657374, 0x4261642052657175657374, 0x4261642052657175657374, 0x4261642052657175657374),
(2, 403, 0x466f7262696464656e, 0x466f7262696464656e, 0x466f7262696464656e, 0x466f7262696464656e),
(3, 404, 0x4e6f7420466f756e64, 0x4e6f7420466f756e64, 0x4e6f7420466f756e64, 0x4e6f7420466f756e64),
(4, 401, 0x556e617574686f72697a6564, 0x556e617574686f72697a6564, 0x556e617574686f72697a6564, 0x556e617574686f72697a6564),
(5, 405, 0x4d6574686f64204e6f7420416c6c6f776564, 0x4d6574686f64204e6f7420416c6c6f776564, 0x4d6574686f64204e6f7420416c6c6f776564, 0x4d6574686f64204e6f7420416c6c6f776564),
(6, 406, 0x4e6f742041636365707461626c65, 0x4e6f742041636365707461626c65, 0x4e6f742041636365707461626c65, 0x4e6f742041636365707461626c65),
(7, 407, 0x50726f78792041757468656e7469636174696f6e205265717569726564, 0x50726f78792041757468656e7469636174696f6e205265717569726564, 0x50726f78792041757468656e7469636174696f6e205265717569726564, 0x50726f78792041757468656e7469636174696f6e205265717569726564),
(8, 408, 0x526571756573742054696d656f7574, 0x526571756573742054696d656f7574, 0x526571756573742054696d656f7574, 0x526571756573742054696d656f7574),
(9, 409, 0x436f6e666c696374, 0x436f6e666c696374, 0x436f6e666c696374, 0x436f6e666c696374),
(10, 410, 0x476f6e65, 0x476f6e65, 0x476f6e65, 0x476f6e65),
(11, 411, 0x4c656e677468205265717569726564, 0x4c656e677468205265717569726564, 0x4c656e677468205265717569726564, 0x4c656e677468205265717569726564),
(12, 412, 0x507265636f6e646974696f6e204661696c6564, 0x507265636f6e646974696f6e204661696c6564, 0x507265636f6e646974696f6e204661696c6564, 0x507265636f6e646974696f6e204661696c6564),
(13, 413, 0x5265717565737420456e7469747920546f6f204c61726765, 0x5265717565737420456e7469747920546f6f204c61726765, 0x5265717565737420456e7469747920546f6f204c61726765, 0x5265717565737420456e7469747920546f6f204c61726765),
(14, 414, 0x526571756573742d55524920546f6f204c6f6e67, 0x526571756573742d55524920546f6f204c6f6e67, 0x526571756573742d55524920546f6f204c6f6e67, 0x526571756573742d55524920546f6f204c6f6e67),
(15, 415, 0x556e737570706f72746564204d656469612054797065, 0x556e737570706f72746564204d656469612054797065, 0x556e737570706f72746564204d656469612054797065, 0x556e737570706f72746564204d656469612054797065),
(16, 416, 0x5265717565737465642052616e6765204e6f74205361746973666961626c65, 0x5265717565737465642052616e6765204e6f74205361746973666961626c65, 0x5265717565737465642052616e6765204e6f74205361746973666961626c65, 0x5265717565737465642052616e6765204e6f74205361746973666961626c65),
(17, 417, 0x4578706563746174696f6e204661696c6564, 0x4578706563746174696f6e204661696c6564, 0x4578706563746174696f6e204661696c6564, 0x4578706563746174696f6e204661696c6564);

-- --------------------------------------------------------

--
-- Table structure for table `re_settings`
--

DROP TABLE IF EXISTS `re_settings`;
CREATE TABLE IF NOT EXISTS `re_settings` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` varchar(255) NOT NULL DEFAULT '',
  `comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=128 ;

--
-- Dumping data for table `re_settings`
--

INSERT INTO `re_settings` (`id`, `name`, `value`, `comment`) VALUES
(1, 'index_theme_path', '/templates/default_theme', 'site templates'),
(2, 'admin_theme_path', '/templates/admin', 'admin templates'),
(3, 'index_theme_css_path', '/css', 'css site folder'),
(4, 'index_theme_images_path', '/images', 'images site folder'),
(5, 'menu_path', 'menu/', 'menu folder'),
(6, 'default_lang', '2', 'default language'),
(7, 'use_registration_confirmation', '0', 'use_registration_confirmation'),
(8, 'site_email', 'hudzenlg@gmail.com', 'site_email'),
(10, 'plan_photo_max_user_count', '5', 'max number of photos for lising plan section'),
(11, 'admin_rows_per_page', '15', 'number of rows on page in admin section'),
(12, 'admin_upload_numpage', '10', 'number of upload files on page in admin approvement section'),
(13, 'date_format', '%m.%d.%Y', 'site date format'),
(14, 'users_num_page', '5', 'users per page'),
(15, 'use_maps_in_search_results', '1', 'use maps in searchresults'),
(16, 'use_maps_in_viewprofile', '1', 'use maps in viewprofile'),
(20, 'default_video_icon', 'default_video_icon.gif', NULL),
(21, 'use_video_approve', '1', NULL),
(22, 'use_image_resize', '1', 'use image resize'),
(23, 'thumb_max_width', '150', 'thumb_max_width'),
(24, 'thumb_max_height', '100', 'thumb_max_height'),
(25, 'reference_numpage', '10', NULL),
(26, 'max_age_limit', '99', NULL),
(27, 'min_age_limit', '18', NULL),
(28, 'use_photo_approve', '1', NULL),
(29, 'photo_folder', '/uploades/photo', 'photo folder'),
(30, 'photo_max_user_count', '15', NULL),
(31, 'photo_max_size', '512000', NULL),
(32, 'photo_max_width', '1000', NULL),
(33, 'photo_max_height', '1000', NULL),
(34, 'default_photo', 'default_photo.gif', NULL),
(35, 'ads_num_page', '5', NULL),
(36, 'icons_folder', '/uploades/icons', 'icons folder'),
(39, 'site_unit_costunit', 'USD', NULL),
(41, 'slideshow_folder', '/uploades/slideshow', 'slideshow folder'),
(43, 'slideshow_max_size', '1024000', NULL),
(47, 'admin_user_ads_numpage', '1', NULL),
(48, 'one_country', '1', NULL),
(49, 'id_country', '226', NULL),
(50, 'video_folder', '/uploades/video', 'video upload path'),
(51, 'video_max_count', '2', ''),
(52, 'video_max_size', '1024000', ''),
(54, 'news_short_length', '255', NULL),
(53, 'hot_offers_num_at_search', '4', NULL),
(55, 'last_ads_num_at_page', '5', NULL),
(56, 'min_feeds_news_cnt', '10', NULL),
(57, 'default_photo_group', 'group.gif', NULL),
(58, 'default_photo_male', 'male.gif', NULL),
(59, 'default_photo_female', 'female.gif', NULL),
(60, 'default_photo_fwithc', 'family_with_children.gif', NULL),
(61, 'default_photo_fwithoutc', 'family_without_children.gif', NULL),
(62, 'default_photo_agency', 'f_67d9929b.gif', NULL),
(63, 'default_photo_man', 'man.gif', NULL),
(64, 'site_mode', '1', 'site configuration'),
(65, 'contact_for_free', '0', 'show contact info in listings for free users'),
(66, 'pilot_product_version', 'FEB_2009_01', 'current PilotGroup product version'),
(67, 'use_pilot_module_banners', '0', 'flag for checking if banners module is already installed'),
(68, 'top_search_cost', '1', 'Lift up listing in the search service cost'),
(69, 'slideshow_cost', '1', 'Slideshow service cost'),
(70, 'slideshow_period', '1', 'Period in days for slideshow service'),
(71, 'featured_in_region_cost', '1', 'Notice listing in region service cost'),
(72, 'use_listing_completion_bonus', '1', 'Use listing completion bonus service'),
(73, 'listing_completion_bonus_number', '1', 'Number of listings, for wich completion user could get bonus'),
(74, 'featured_in_region_period', '1', 'Notice listing in region service write-offs period in minutes'),
(75, 'free_user_group_default_module', '1,2,3,7,10,15,17,19,21,50,57', 'module file ids for default free users group'),
(76, 'use_sell_lease_payment', '0', 'Use payment for publication ads on sell or lease'),
(77, 'max_ads_number', '20', 'Max listings number for change displaying listigs in user mode (show ads areas)'),
(78, 'use_private_person_ads_limit', '0', 'Use limit on listings publication for private persons'),
(79, 'private_person_ads_limit', '3', 'Limit on listings publication for private persons value'),
(80, 'max_ads_admin', '6', 'Number of ads on pages admin mode'),
(81, 'default_use_watermark', '0', 'Use watermark'),
(82, 'default_watermark_width', '300', NULL),
(83, 'default_watermark_height', '85', NULL),
(84, 'default_watermark_type', 'text', NULL),
(85, 'default_watermark_text', 'RealEstate', NULL),
(86, 'default_watermark_image', 'watermark_default.png', NULL),
(87, 'default_watermark_blank', 'watermark_blank.gif', NULL),
(88, 'use_watermark', '0', 'Use watermark'),
(89, 'watermark_width', '300', NULL),
(90, 'watermark_height', '85', NULL),
(91, 'watermark_type', 'text', NULL),
(92, 'watermark_text', 'RealEstate', NULL),
(93, 'watermark_image', 'watermark_image.png', NULL),
(94, 'watermark_blank', 'watermark_blank.gif', NULL),
(95, 'default_logotype', 'def_logotype.png', NULL),
(96, 'default_homelogo', 'def_home_logo.png', NULL),
(97, 'default_slogan', 'def_slogan.gif', NULL),
(98, 'thousands_separator', 'nbsp', NULL),
(99, 'decimal_point', '.', NULL),
(100, 'decimals_after_point', '0', NULL),
(101, 'commission_percent', '3', NULL),
(102, 'account_threshold', '1', 'Threshold value of funds on users account, if users account is >= than this value, send mail to him'),
(103, 'use_sold_leased_status', '1', NULL),
(104, 'minimal_transfer_value', '1', '1.00'),
(105, 'headline_preview_size', '100', NULL),
(106, 'use_ads_activity_period', '0', NULL),
(107, 'ads_activity_period', '34', NULL),
(108, 'use_ffmpeg', '0', NULL),
(109, 'path_to_ffmpeg', '/usr/bin/ffmpeg/', NULL),
(110, 'flv_output_dimension', '320x240', NULL),
(111, 'flv_output_audio_sampling_rate', '44100', NULL),
(112, 'flv_output_audio_bit_rate', '12', NULL),
(113, 'flv_output_foto_dimension', '120x72', NULL),
(114, 'news_per_page', '5', 'News per page in user mode'),
(115, 'sq_meters', 'sq.<sup>f.</sup>', NULL),
(116, 'use_maps_in_account', '1', 'use maps in account'),
(117, 'thumb_big_max_width', '238', 'thumb_max_width'),
(118, 'thumb_big_max_height', '162', 'thumb_max_height'),
(119, 'rss_title_for_export', 'Title of your RSS data feed', NULL),
(120, 'rss_description_for_export', 'Description of your RSS data feed', NULL),
(121, 'rss_title_ads_for_export', 'Title of item', NULL),
(122, 'rss_description_ads_for_export', 'Description of item', NULL),
(123, 'rss_site_name', '', NULL),
(124, 'contact_for_unreg', '0', 'show contact info in listings for unregister users'),
(125, 'cur_position', 'end', NULL),
(126, 'cur_format', 'abbr', 'abbr or symbol'),
(127, 'use_agent_user_type', '0', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `re_show_ads_area`
--

DROP TABLE IF EXISTS `re_show_ads_area`;
CREATE TABLE IF NOT EXISTS `re_show_ads_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area` varchar(25) NOT NULL DEFAULT '',
  `for_registered` enum('1','0') DEFAULT '0',
  `show_type` enum('max_views','admin_choose','last_added','off') DEFAULT 'last_added',
  `ads_number` int(2) NOT NULL DEFAULT '4',
  `view_type` enum('list','row') DEFAULT 'list',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `re_show_ads_area`
--

INSERT INTO `re_show_ads_area` (`id`, `area`, `for_registered`, `show_type`, `ads_number`, `view_type`) VALUES
(1, 'index', '0', 'last_added', 4, 'row'),
(2, 'quick_search', '0', 'max_views', 4, 'list'),
(3, 'quick_search', '1', 'last_added', 4, 'list'),
(4, 'homepage', '1', 'off', 4, 'list');

-- --------------------------------------------------------

--
-- Table structure for table `re_sponsors_ads`
--

DROP TABLE IF EXISTS `re_sponsors_ads`;
CREATE TABLE IF NOT EXISTS `re_sponsors_ads` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_ad` (`id_ad`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_sponsors_ads`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_spr_apartment`
--

DROP TABLE IF EXISTS `re_spr_apartment`;
CREATE TABLE IF NOT EXISTS `re_spr_apartment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `sorter` int(3) DEFAULT '0',
  `type` enum('1','2') NOT NULL DEFAULT '1',
  `des_type` enum('1','2') NOT NULL DEFAULT '1',
  `visible_in` enum('1','2','3') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `re_spr_apartment`
--

INSERT INTO `re_spr_apartment` (`id`, `name`, `sorter`, `type`, `des_type`, `visible_in`) VALUES
(3, 'Мебель', 1, '1', '2', '1'),
(13, 'Рядом находятся', 5, '2', '1', '1'),
(14, 'В округе доступны', 4, '2', '1', '1'),
(15, 'Удобства', 2, '2', '1', '1'),
(16, 'Наличие приспособлений', 3, '2', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `re_spr_deactivate`
--

DROP TABLE IF EXISTS `re_spr_deactivate`;
CREATE TABLE IF NOT EXISTS `re_spr_deactivate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `sorter` int(3) DEFAULT '0',
  `type` enum('1','2') NOT NULL DEFAULT '1',
  `des_type` enum('1','2') NOT NULL DEFAULT '1',
  `visible_in` enum('1','2','3') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `re_spr_deactivate`
--

INSERT INTO `re_spr_deactivate` (`id`, `name`, `sorter`, `type`, `des_type`, `visible_in`) VALUES
(1, 'Deactivate reason', 1, '2', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `re_spr_description`
--

DROP TABLE IF EXISTS `re_spr_description`;
CREATE TABLE IF NOT EXISTS `re_spr_description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `sorter` int(3) DEFAULT '0',
  `type` enum('1','2') NOT NULL DEFAULT '1',
  `des_type` enum('1','2') NOT NULL DEFAULT '1',
  `visible_in` enum('1','2','3') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `re_spr_description`
--

INSERT INTO `re_spr_description` (`id`, `name`, `sorter`, `type`, `des_type`, `visible_in`) VALUES
(1, 'Number of bedrooms', 1, '1', '2', '1'),
(2, 'Number of bathrooms', 2, '1', '2', '1'),
(3, 'Гараж', 3, '1', '2', '1');

-- --------------------------------------------------------

--
-- Table structure for table `re_spr_gender`
--

DROP TABLE IF EXISTS `re_spr_gender`;
CREATE TABLE IF NOT EXISTS `re_spr_gender` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `sorter` int(3) DEFAULT '0',
  `type` enum('1','2') NOT NULL DEFAULT '1',
  `des_type` enum('1','2') NOT NULL DEFAULT '1',
  `visible_in` enum('1','2','3') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `re_spr_gender`
--

INSERT INTO `re_spr_gender` (`id`, `name`, `sorter`, `type`, `des_type`, `visible_in`) VALUES
(1, 'Пол', 1, '1', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `re_spr_language`
--

DROP TABLE IF EXISTS `re_spr_language`;
CREATE TABLE IF NOT EXISTS `re_spr_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `sorter` int(3) DEFAULT '0',
  `type` enum('1','2') NOT NULL DEFAULT '1',
  `des_type` enum('1','2') NOT NULL DEFAULT '1',
  `visible_in` enum('1','2','3') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `re_spr_language`
--

INSERT INTO `re_spr_language` (`id`, `name`, `sorter`, `type`, `des_type`, `visible_in`) VALUES
(1, 'Знание языков', 1, '2', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `re_spr_people`
--

DROP TABLE IF EXISTS `re_spr_people`;
CREATE TABLE IF NOT EXISTS `re_spr_people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `sorter` int(3) DEFAULT '0',
  `type` enum('1','2') NOT NULL DEFAULT '1',
  `des_type` enum('1','2') NOT NULL DEFAULT '1',
  `visible_in` enum('1','2','3') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `re_spr_people`
--

INSERT INTO `re_spr_people` (`id`, `name`, `sorter`, `type`, `des_type`, `visible_in`) VALUES
(1, 'Вы курите?', 5, '1', '2', '1'),
(4, 'Домашние животные', 2, '2', '1', '1'),
(13, 'I am', 3, '1', '1', '1'),
(7, 'Ориентация', 1, '1', '1', '1'),
(10, 'Материальное положение', 4, '1', '2', '1'),
(11, 'Отношение к алкоголю', 6, '1', '2', '1'),
(12, 'Отношение к наркотикам', 7, '1', '2', '1');

-- --------------------------------------------------------

--
-- Table structure for table `re_spr_period`
--

DROP TABLE IF EXISTS `re_spr_period`;
CREATE TABLE IF NOT EXISTS `re_spr_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `sorter` int(3) DEFAULT '0',
  `type` enum('1','2') NOT NULL DEFAULT '1',
  `des_type` enum('1','2') NOT NULL DEFAULT '1',
  `visible_in` enum('1','2','3') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `re_spr_period`
--

INSERT INTO `re_spr_period` (`id`, `name`, `sorter`, `type`, `des_type`, `visible_in`) VALUES
(1, 'Term of lease', 1, '1', '2', '1');

-- --------------------------------------------------------

--
-- Table structure for table `re_spr_rent_apartment_user`
--

DROP TABLE IF EXISTS `re_spr_rent_apartment_user`;
CREATE TABLE IF NOT EXISTS `re_spr_rent_apartment_user` (
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_spr` int(11) NOT NULL DEFAULT '0',
  `id_value` int(11) NOT NULL DEFAULT '0',
  KEY `id_ad` (`id_ad`),
  KEY `id_user` (`id_user`),
  KEY `id_spr` (`id_spr`),
  KEY `id_value` (`id_value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_spr_rent_apartment_user`
--

INSERT INTO `re_spr_rent_apartment_user` (`id_ad`, `id_user`, `id_spr`, `id_value`) VALUES
(68, 109, 14, 150),
(68, 109, 14, 138),
(68, 109, 16, 233),
(68, 109, 16, 208),
(68, 109, 16, 202),
(68, 109, 16, 196),
(41, 86, 13, 243),
(41, 86, 13, 238),
(41, 86, 13, 131),
(41, 86, 13, 128),
(41, 86, 13, 245),
(41, 86, 13, 127),
(41, 86, 13, 244),
(41, 86, 13, 240),
(41, 86, 13, 122),
(41, 86, 13, 124),
(41, 86, 13, 130),
(41, 86, 13, 237),
(41, 86, 13, 242),
(41, 86, 13, 235),
(41, 86, 13, 234),
(41, 86, 13, 239),
(41, 86, 13, 241),
(41, 86, 13, 236),
(41, 86, 14, 153),
(41, 86, 14, 151),
(43, 86, 14, 151),
(43, 86, 14, 154),
(43, 86, 14, 142),
(43, 86, 14, 139),
(43, 86, 16, 205),
(43, 86, 16, 200),
(43, 86, 16, 206),
(43, 86, 16, 196),
(43, 86, 16, 203),
(43, 86, 16, 192),
(43, 86, 15, 230),
(43, 86, 15, 163),
(43, 86, 15, 166),
(43, 86, 15, 172),
(43, 86, 15, 229),
(43, 86, 15, 232),
(43, 86, 15, 231),
(43, 86, 3, 30),
(40, 86, 13, 243),
(40, 86, 13, 238),
(41, 86, 14, 138),
(41, 86, 14, 140),
(41, 86, 14, 149),
(41, 86, 14, 148),
(41, 86, 14, 152),
(41, 86, 14, 154),
(41, 86, 14, 145),
(41, 86, 14, 142),
(41, 86, 14, 135),
(41, 86, 14, 147),
(41, 86, 14, 150),
(41, 86, 14, 144),
(41, 86, 14, 156),
(41, 86, 14, 136),
(41, 86, 14, 141),
(41, 86, 14, 139),
(41, 86, 16, 202),
(41, 86, 16, 197),
(41, 86, 16, 205),
(41, 86, 16, 200),
(41, 86, 16, 206),
(41, 86, 16, 233),
(41, 86, 16, 208),
(41, 86, 16, 204),
(41, 86, 16, 201),
(41, 86, 16, 194),
(41, 86, 16, 196),
(41, 86, 16, 198),
(41, 86, 16, 193),
(41, 86, 16, 203),
(41, 86, 16, 207),
(41, 86, 16, 190),
(41, 86, 16, 191),
(41, 86, 16, 195),
(41, 86, 16, 192),
(41, 86, 15, 227),
(41, 86, 15, 224),
(41, 86, 15, 168),
(41, 86, 15, 230),
(41, 86, 15, 189),
(41, 86, 15, 225),
(41, 86, 15, 226),
(41, 86, 15, 186),
(41, 86, 15, 184),
(41, 86, 15, 171),
(41, 86, 15, 159),
(68, 109, 16, 194),
(41, 86, 15, 163),
(41, 86, 15, 228),
(68, 109, 16, 203),
(68, 109, 15, 222),
(67, 108, 14, 151),
(67, 108, 14, 150),
(67, 108, 14, 148),
(67, 108, 14, 139),
(67, 108, 14, 138),
(67, 108, 16, 202),
(67, 108, 16, 196),
(67, 108, 16, 194),
(67, 108, 3, 30),
(65, 106, 13, 130),
(65, 106, 14, 151),
(65, 106, 14, 148),
(65, 106, 14, 141),
(65, 106, 14, 140),
(65, 106, 14, 139),
(65, 106, 14, 138),
(65, 106, 16, 233),
(65, 106, 16, 208),
(65, 106, 16, 202),
(65, 106, 16, 198),
(65, 106, 16, 196),
(65, 106, 16, 194),
(65, 106, 16, 203),
(41, 86, 15, 166),
(41, 86, 15, 223),
(37, 86, 14, 156),
(37, 86, 14, 136),
(37, 86, 14, 141),
(37, 86, 14, 139),
(37, 86, 13, 237),
(37, 86, 13, 242),
(37, 86, 13, 235),
(37, 86, 13, 234),
(37, 86, 13, 239),
(37, 86, 13, 241),
(37, 86, 13, 236),
(37, 86, 14, 153),
(37, 86, 14, 151),
(37, 86, 14, 140),
(37, 86, 14, 149),
(37, 86, 14, 148),
(37, 86, 14, 152),
(37, 86, 14, 154),
(37, 86, 14, 145),
(37, 86, 14, 142),
(37, 86, 14, 135),
(37, 86, 14, 147),
(37, 86, 14, 150),
(37, 86, 14, 144),
(37, 86, 16, 202),
(37, 86, 16, 197),
(37, 86, 16, 205),
(37, 86, 16, 200),
(37, 86, 16, 206),
(37, 86, 16, 233),
(37, 86, 16, 208),
(37, 86, 16, 204),
(37, 86, 16, 201),
(37, 86, 16, 194),
(37, 86, 16, 196),
(37, 86, 16, 198),
(37, 86, 16, 193),
(37, 86, 16, 203),
(37, 86, 16, 207),
(37, 86, 16, 191),
(37, 86, 16, 195),
(37, 86, 16, 192),
(37, 86, 15, 224),
(37, 86, 15, 168),
(37, 86, 15, 230),
(37, 86, 15, 189),
(37, 86, 15, 225),
(37, 86, 15, 226),
(37, 86, 15, 186),
(37, 86, 15, 184),
(37, 86, 15, 171),
(37, 86, 15, 159),
(37, 86, 15, 163),
(37, 86, 15, 228),
(37, 86, 15, 166),
(37, 86, 15, 223),
(37, 86, 15, 167),
(37, 86, 15, 176),
(37, 86, 15, 160),
(37, 86, 15, 172),
(37, 86, 15, 222),
(37, 86, 15, 175),
(37, 86, 15, 221),
(37, 86, 15, 181),
(37, 86, 15, 177),
(37, 86, 15, 229),
(37, 86, 15, 182),
(37, 86, 15, 232),
(37, 86, 15, 231),
(37, 86, 3, 30),
(40, 86, 13, 131),
(40, 86, 13, 128),
(40, 86, 13, 245),
(40, 86, 13, 127),
(40, 86, 13, 244),
(40, 86, 13, 240),
(40, 86, 13, 122),
(40, 86, 13, 124),
(40, 86, 13, 130),
(40, 86, 13, 237),
(40, 86, 13, 242),
(40, 86, 13, 235),
(40, 86, 13, 234),
(40, 86, 13, 239),
(40, 86, 13, 241),
(40, 86, 14, 153),
(40, 86, 14, 151),
(40, 86, 14, 138),
(40, 86, 14, 140),
(40, 86, 14, 149),
(40, 86, 14, 148),
(40, 86, 14, 152),
(40, 86, 14, 154),
(40, 86, 14, 145),
(40, 86, 14, 142),
(40, 86, 14, 135),
(40, 86, 14, 147),
(40, 86, 14, 150),
(65, 106, 15, 189),
(65, 106, 15, 182),
(65, 106, 15, 222),
(65, 106, 15, 171),
(63, 104, 16, 233),
(63, 104, 16, 208),
(63, 104, 16, 202),
(63, 104, 16, 198),
(63, 104, 16, 194),
(63, 104, 3, 30),
(37, 86, 13, 243),
(37, 86, 13, 238),
(37, 86, 13, 128),
(37, 86, 13, 245),
(37, 86, 13, 127),
(37, 86, 13, 244),
(37, 86, 13, 240),
(37, 86, 13, 122),
(37, 86, 13, 124),
(37, 86, 13, 130),
(40, 86, 14, 144),
(40, 86, 14, 156),
(40, 86, 14, 136),
(40, 86, 14, 141),
(40, 86, 16, 202),
(40, 86, 16, 197),
(40, 86, 16, 205),
(40, 86, 16, 200),
(40, 86, 16, 206),
(40, 86, 16, 233),
(40, 86, 16, 208),
(40, 86, 16, 204),
(40, 86, 16, 201),
(40, 86, 16, 194),
(40, 86, 16, 196),
(40, 86, 16, 198),
(40, 86, 16, 193),
(40, 86, 16, 203),
(40, 86, 16, 207),
(40, 86, 16, 190),
(40, 86, 16, 191),
(40, 86, 16, 195),
(40, 86, 15, 227),
(40, 86, 15, 224),
(40, 86, 15, 168),
(40, 86, 15, 230),
(40, 86, 15, 189),
(40, 86, 15, 225),
(40, 86, 15, 226),
(40, 86, 15, 186),
(40, 86, 15, 184),
(40, 86, 15, 171),
(40, 86, 15, 159),
(40, 86, 15, 163),
(40, 86, 15, 228),
(40, 86, 15, 166),
(40, 86, 15, 223),
(40, 86, 15, 167),
(40, 86, 15, 176),
(40, 86, 15, 160),
(40, 86, 15, 172),
(40, 86, 15, 222),
(40, 86, 15, 175),
(40, 86, 15, 221),
(40, 86, 15, 181),
(40, 86, 15, 177),
(40, 86, 15, 229),
(40, 86, 15, 182),
(40, 86, 15, 232),
(40, 86, 3, 30),
(43, 86, 13, 238),
(43, 86, 13, 240),
(43, 86, 13, 124),
(43, 86, 13, 236),
(43, 86, 14, 153),
(41, 86, 15, 167),
(41, 86, 15, 176),
(41, 86, 15, 160),
(41, 86, 15, 172),
(41, 86, 15, 222),
(41, 86, 15, 175),
(41, 86, 15, 221),
(41, 86, 15, 181),
(41, 86, 15, 177),
(41, 86, 15, 229),
(41, 86, 15, 182),
(41, 86, 15, 232),
(41, 86, 15, 231),
(41, 86, 3, 31),
(62, 103, 16, 197),
(62, 103, 15, 227),
(62, 103, 15, 224),
(62, 103, 15, 223),
(62, 103, 15, 222),
(62, 103, 15, 221),
(62, 103, 15, 181),
(62, 103, 3, 30),
(60, 102, 16, 202),
(60, 102, 16, 203),
(60, 102, 15, 224),
(59, 101, 3, 31),
(78, 86, 15, 181),
(76, 86, 13, 244),
(76, 86, 13, 238),
(76, 86, 13, 237),
(76, 86, 13, 236),
(76, 86, 13, 240),
(76, 86, 13, 234),
(76, 86, 14, 149),
(76, 86, 14, 140),
(76, 86, 15, 225),
(76, 86, 15, 222),
(76, 86, 15, 175),
(76, 86, 15, 230),
(76, 86, 15, 224),
(78, 86, 15, 175),
(78, 86, 15, 222),
(78, 86, 15, 176),
(78, 86, 15, 223),
(78, 86, 15, 163),
(78, 86, 15, 186),
(78, 86, 15, 226),
(78, 86, 15, 225),
(78, 86, 15, 224),
(78, 86, 15, 227),
(78, 86, 13, 241),
(78, 86, 13, 234),
(78, 86, 13, 237),
(78, 86, 13, 240),
(78, 86, 13, 238),
(78, 86, 13, 243),
(79, 86, 3, 31),
(79, 86, 15, 231),
(79, 86, 15, 175),
(79, 86, 15, 222),
(79, 86, 15, 176),
(79, 86, 15, 167),
(79, 86, 15, 223),
(79, 86, 15, 228),
(79, 86, 15, 186),
(79, 86, 15, 226),
(79, 86, 15, 225),
(79, 86, 15, 224),
(79, 86, 15, 227),
(80, 115, 3, 30),
(80, 115, 16, 193),
(80, 115, 16, 194),
(80, 115, 16, 198),
(80, 115, 16, 202),
(80, 115, 16, 205),
(80, 115, 16, 208),
(80, 115, 16, 233),
(81, 116, 15, 231),
(81, 116, 16, 194),
(81, 116, 16, 198),
(81, 116, 16, 202),
(82, 119, 15, 231),
(82, 119, 16, 194),
(82, 119, 16, 198),
(82, 119, 16, 202),
(83, 120, 15, 231),
(83, 120, 15, 229),
(83, 120, 15, 223),
(83, 120, 15, 224),
(83, 120, 15, 226),
(83, 120, 16, 203),
(83, 120, 16, 194),
(83, 120, 16, 196),
(83, 120, 16, 198),
(83, 120, 16, 204),
(83, 120, 16, 206),
(83, 120, 16, 233),
(83, 120, 13, 243),
(83, 120, 13, 244);

-- --------------------------------------------------------

--
-- Table structure for table `re_spr_rent_description_user`
--

DROP TABLE IF EXISTS `re_spr_rent_description_user`;
CREATE TABLE IF NOT EXISTS `re_spr_rent_description_user` (
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_spr` int(11) NOT NULL DEFAULT '0',
  `id_value` int(11) NOT NULL DEFAULT '0',
  KEY `id_ad` (`id_ad`),
  KEY `id_user` (`id_user`),
  KEY `id_spr` (`id_spr`),
  KEY `id_value` (`id_value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_spr_rent_description_user`
--

INSERT INTO `re_spr_rent_description_user` (`id_ad`, `id_user`, `id_spr`, `id_value`) VALUES
(41, 86, 1, 11),
(41, 86, 2, 22),
(41, 86, 3, 28),
(43, 86, 1, 2),
(43, 86, 2, 14),
(43, 86, 3, 27),
(40, 86, 1, 11),
(40, 86, 2, 14),
(40, 86, 3, 24),
(37, 86, 1, 11),
(37, 86, 2, 13),
(37, 86, 3, 24),
(59, 101, 1, 8),
(59, 101, 2, 14),
(60, 102, 1, 3),
(60, 102, 2, 13),
(62, 103, 1, 3),
(62, 103, 2, 14),
(62, 103, 3, 25),
(63, 104, 1, 4),
(63, 104, 2, 14),
(63, 104, 3, 24),
(65, 106, 1, 3),
(65, 106, 2, 13),
(65, 106, 3, 23),
(67, 108, 1, 4),
(67, 108, 2, 14),
(67, 108, 3, 23),
(68, 109, 1, 2),
(68, 109, 2, 13),
(68, 109, 3, 23),
(78, 86, 1, 1),
(78, 86, 2, 12),
(79, 86, 1, 3),
(79, 86, 2, 13),
(80, 115, 1, 3),
(80, 115, 2, 13),
(81, 116, 1, 3),
(81, 116, 2, 13),
(81, 116, 3, 24),
(82, 119, 1, 2),
(82, 119, 2, 13),
(82, 119, 3, 24),
(83, 120, 1, 2),
(83, 120, 2, 13),
(76, 86, 1, 1),
(76, 86, 2, 12);

-- --------------------------------------------------------

--
-- Table structure for table `re_spr_rent_gender_match`
--

DROP TABLE IF EXISTS `re_spr_rent_gender_match`;
CREATE TABLE IF NOT EXISTS `re_spr_rent_gender_match` (
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_spr` int(11) NOT NULL DEFAULT '0',
  `id_value` int(11) NOT NULL DEFAULT '0',
  KEY `id_ad` (`id_ad`),
  KEY `id_user` (`id_user`),
  KEY `id_spr` (`id_spr`),
  KEY `id_value` (`id_value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_spr_rent_gender_match`
--

INSERT INTO `re_spr_rent_gender_match` (`id_ad`, `id_user`, `id_spr`, `id_value`) VALUES
(76, 86, 1, 3),
(76, 86, 1, 2),
(76, 86, 1, 4),
(76, 86, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `re_spr_rent_gender_user`
--

DROP TABLE IF EXISTS `re_spr_rent_gender_user`;
CREATE TABLE IF NOT EXISTS `re_spr_rent_gender_user` (
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_spr` int(11) NOT NULL DEFAULT '0',
  `id_value` int(11) NOT NULL DEFAULT '0',
  KEY `id_ad` (`id_ad`),
  KEY `id_user` (`id_user`),
  KEY `id_spr` (`id_spr`),
  KEY `id_value` (`id_value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_spr_rent_gender_user`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_spr_rent_language_match`
--

DROP TABLE IF EXISTS `re_spr_rent_language_match`;
CREATE TABLE IF NOT EXISTS `re_spr_rent_language_match` (
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_spr` int(11) NOT NULL DEFAULT '0',
  `id_value` int(11) NOT NULL DEFAULT '0',
  KEY `id_ad` (`id_ad`),
  KEY `id_user` (`id_user`),
  KEY `id_spr` (`id_spr`),
  KEY `id_value` (`id_value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_spr_rent_language_match`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_spr_rent_language_user`
--

DROP TABLE IF EXISTS `re_spr_rent_language_user`;
CREATE TABLE IF NOT EXISTS `re_spr_rent_language_user` (
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_spr` int(11) NOT NULL DEFAULT '0',
  `id_value` int(11) NOT NULL DEFAULT '0',
  KEY `id_ad` (`id_ad`),
  KEY `id_user` (`id_user`),
  KEY `id_spr` (`id_spr`),
  KEY `id_value` (`id_value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_spr_rent_language_user`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_spr_rent_people_match`
--

DROP TABLE IF EXISTS `re_spr_rent_people_match`;
CREATE TABLE IF NOT EXISTS `re_spr_rent_people_match` (
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_spr` int(11) NOT NULL DEFAULT '0',
  `id_value` int(11) NOT NULL DEFAULT '0',
  KEY `id_ad` (`id_ad`),
  KEY `id_user` (`id_user`),
  KEY `id_spr` (`id_spr`),
  KEY `id_value` (`id_value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_spr_rent_people_match`
--

INSERT INTO `re_spr_rent_people_match` (`id_ad`, `id_user`, `id_spr`, `id_value`) VALUES
(80, 115, 1, 45),
(76, 86, 7, 19),
(76, 86, 7, 16),
(76, 86, 7, 18),
(76, 86, 7, 17);

-- --------------------------------------------------------

--
-- Table structure for table `re_spr_rent_people_user`
--

DROP TABLE IF EXISTS `re_spr_rent_people_user`;
CREATE TABLE IF NOT EXISTS `re_spr_rent_people_user` (
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_spr` int(11) NOT NULL DEFAULT '0',
  `id_value` int(11) NOT NULL DEFAULT '0',
  KEY `id_ad` (`id_ad`),
  KEY `id_user` (`id_user`),
  KEY `id_spr` (`id_spr`),
  KEY `id_value` (`id_value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_spr_rent_people_user`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_spr_rent_period_user`
--

DROP TABLE IF EXISTS `re_spr_rent_period_user`;
CREATE TABLE IF NOT EXISTS `re_spr_rent_period_user` (
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_spr` int(11) NOT NULL DEFAULT '0',
  `id_value` int(11) NOT NULL DEFAULT '0',
  KEY `id_ad` (`id_ad`),
  KEY `id_user` (`id_user`),
  KEY `id_spr` (`id_spr`),
  KEY `id_value` (`id_value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_spr_rent_period_user`
--

INSERT INTO `re_spr_rent_period_user` (`id_ad`, `id_user`, `id_spr`, `id_value`) VALUES
(43, 86, 1, 3),
(40, 86, 1, 3),
(79, 86, 1, 4),
(80, 115, 1, 3),
(81, 116, 1, 3),
(82, 119, 1, 3),
(83, 120, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `re_spr_rent_type_user`
--

DROP TABLE IF EXISTS `re_spr_rent_type_user`;
CREATE TABLE IF NOT EXISTS `re_spr_rent_type_user` (
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_spr` int(11) NOT NULL DEFAULT '0',
  `id_value` int(11) NOT NULL DEFAULT '0',
  KEY `id_ad` (`id_ad`),
  KEY `id_user` (`id_user`),
  KEY `id_spr` (`id_spr`),
  KEY `id_value` (`id_value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_spr_rent_type_user`
--

INSERT INTO `re_spr_rent_type_user` (`id_ad`, `id_user`, `id_spr`, `id_value`) VALUES
(41, 86, 2, 10),
(43, 86, 2, 13),
(40, 86, 2, 12),
(37, 86, 2, 12),
(59, 101, 2, 11),
(60, 102, 2, 8),
(62, 103, 2, 10),
(63, 104, 2, 10),
(64, 105, 2, 31),
(65, 106, 2, 23),
(66, 107, 2, 38),
(67, 108, 2, 23),
(68, 109, 2, 19),
(78, 86, 2, 10),
(79, 86, 2, 12),
(80, 115, 2, 10),
(81, 116, 2, 19),
(82, 119, 2, 15),
(83, 120, 2, 10),
(76, 86, 2, 12);

-- --------------------------------------------------------

--
-- Table structure for table `re_spr_theme_rest`
--

DROP TABLE IF EXISTS `re_spr_theme_rest`;
CREATE TABLE IF NOT EXISTS `re_spr_theme_rest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `sorter` int(3) DEFAULT NULL,
  `type` enum('1','2') NOT NULL,
  `des_type` enum('1','2') NOT NULL,
  `visible_in` enum('1','2','3') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `re_spr_theme_rest`
--

INSERT INTO `re_spr_theme_rest` (`id`, `name`, `sorter`, `type`, `des_type`, `visible_in`) VALUES
(1, 'Тематический отдых', 0, '2', '2', '1'),
(2, 'Активный отдых', 2, '2', '2', '1');

-- --------------------------------------------------------

--
-- Table structure for table `re_spr_type`
--

DROP TABLE IF EXISTS `re_spr_type`;
CREATE TABLE IF NOT EXISTS `re_spr_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `sorter` int(3) DEFAULT '0',
  `type` enum('1','2') NOT NULL DEFAULT '1',
  `des_type` enum('1','2') NOT NULL DEFAULT '1',
  `visible_in` enum('1','2','3') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `re_spr_type`
--

INSERT INTO `re_spr_type` (`id`, `name`, `sorter`, `type`, `des_type`, `visible_in`) VALUES
(2, 'Property type', 1, '1', '2', '1');

-- --------------------------------------------------------

--
-- Table structure for table `re_subscribe_system`
--

DROP TABLE IF EXISTS `re_subscribe_system`;
CREATE TABLE IF NOT EXISTS `re_subscribe_system` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL DEFAULT '',
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `type` enum('email','sms') NOT NULL DEFAULT 'email',
  `date_last_send` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `re_subscribe_system`
--

INSERT INTO `re_subscribe_system` (`id`, `description`, `status`, `type`, `date_last_send`) VALUES
(1, 'Match listing was founded', '1', 'email', '2006-02-05 12:19:02'),
(2, 'New message in my mailbox', '1', 'email', '0000-00-00 00:00:00'),
(3, 'Someone visited My Profile', '1', 'email', '0000-00-00 00:00:00'),
(4, 'If user interested me', '1', 'email', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `re_subscribe_user`
--

DROP TABLE IF EXISTS `re_subscribe_user`;
CREATE TABLE IF NOT EXISTS `re_subscribe_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_subscribe` int(3) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `type` enum('s','a') NOT NULL DEFAULT 's',
  PRIMARY KEY (`id`),
  KEY `id_subscribe` (`id_subscribe`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_subscribe_user`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_system_letters`
--

DROP TABLE IF EXISTS `re_system_letters`;
CREATE TABLE IF NOT EXISTS `re_system_letters` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `xml` varchar(45) DEFAULT NULL,
  `tpl` varchar(45) DEFAULT NULL,
  `unsubscribe_possible` enum('1','0') NOT NULL DEFAULT '1',
  `id_subscribe` int(2) unsigned NOT NULL DEFAULT '0',
  `is_for_admin` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `re_system_letters`
--

INSERT INTO `re_system_letters` (`id`, `xml`, `tpl`, `unsubscribe_possible`, `id_subscribe`, `is_for_admin`) VALUES
(1, 'mail_content_deactivate', 'mail_contact_deactivate', '0', 0, '1'),
(2, 'mail_content_contact_agency', 'mail_contact_agency', '0', 0, '1'),
(3, 'mail_content_contact_complain', 'mail_contact_complain', '0', 0, '1'),
(4, 'mail_content_contact_form', 'mail_contact_form', '0', 0, '1'),
(5, 'mail_content_money_end', 'mail_money_end_table', '0', 0, '0'),
(6, 'mail_content_match', 'mail_mailbox_match', '1', 1, '0'),
(7, 'mail_content_money_add', 'mail_money_add_table', '0', 0, '0'),
(8, 'mail_content_registration_by_admin', 'mail_registration_for_user', '0', 0, '0'),
(9, 'mail_content_profile_change_by_admin', 'mail_registration_for_user', '0', 0, '0'),
(10, 'mail_content_lost_pass', 'mail_lost_pass', '0', 0, '0'),
(11, 'mail_content_newmailbox', 'mail_mailbox_subscr_table', '1', 2, '0'),
(12, 'mail_content_registration', 'mail_registration_for_user', '0', 0, '0'),
(13, 'mail_content_viewme', 'mail_viewme_subscr_table', '1', 3, '0'),
(14, 'mail_content_interme', 'mail_interme_subscr_table', '1', 4, '0'),
(15, 'mail_content_unactivate_ad', 'mail_unactivate_ad', '0', 0, '0'),
(16, 'mail_content_unactivate_sold_leased', 'mail_unactivate_ad', '0', 0, '0'),
(17, 'mail_content_contact_user', 'mail_contact_user', '0', 0, '0'),
(18, 'mail_content_approve_by_agent', 'mail_update_by_agent_table', '0', 0, '0'),
(19, 'mail_content_approve_by_realtor', 'mail_approve_by_realtor_table', '0', 0, '0'),
(20, 'mail_content_decline_by_agent', 'mail_update_by_agent_table', '0', 0, '0'),
(21, 'mail_content_decline_by_realtor', 'mail_delete_by_realtor_table', '0', 0, '0'),
(22, 'mail_content_delete_by_agent', 'mail_delete_by_agent_table', '0', 0, '0'),
(23, 'mail_content_delete_by_agent_1', 'mail_delete_by_agent_table_2', '0', 0, '0'),
(24, 'mail_content_delete_by_agent_2', 'mail_delete_by_agent_table_2', '0', 0, '0'),
(25, 'mail_content_delete_by_realtor', 'mail_delete_by_realtor_table', '0', 0, '0'),
(26, 'mail_content_delete_offer_by_realtor', 'mail_delete_by_realtor_table', '0', 0, '0'),
(27, 'mail_content_invite_to_realtor', 'mail_invite_to_realtor_table', '0', 0, '0'),
(28, 'mail_content_new_agent', 'mail_new_agent_table', '0', 0, '0'),
(29, 'mail_content_birthday', 'mail_birthday_table', '0', 0, '0');

-- --------------------------------------------------------

--
-- Table structure for table `re_top_search_ads`
--

DROP TABLE IF EXISTS `re_top_search_ads`;
CREATE TABLE IF NOT EXISTS `re_top_search_ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `type` enum('1','2') NOT NULL DEFAULT '1',
  `date_begin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_ad` (`id_ad`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_top_search_ads`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_user`
--

DROP TABLE IF EXISTS `re_user`;
CREATE TABLE IF NOT EXISTS `re_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(255) NOT NULL DEFAULT '',
  `sname` varchar(255) NOT NULL DEFAULT '',
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `confirm` enum('0','1') NOT NULL DEFAULT '0',
  `login` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `lang_id` int(3) DEFAULT '1',
  `email` varchar(255) NOT NULL DEFAULT '',
  `date_birthday` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_last_seen` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_registration` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `root_user` enum('0','1') NOT NULL DEFAULT '0',
  `guest_user` enum('0','1') NOT NULL DEFAULT '0',
  `login_count` int(11) NOT NULL DEFAULT '0',
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `show_info` enum('0','1') NOT NULL DEFAULT '1',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `user_type` enum('1','2','3') NOT NULL DEFAULT '1',
  `access` enum('1','0') DEFAULT '1',
  `version_message_count` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `lang_id` (`lang_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=121 ;

--
-- Dumping data for table `re_user`
--

INSERT INTO `re_user` (`id`, `fname`, `sname`, `status`, `confirm`, `login`, `password`, `lang_id`, `email`, `date_birthday`, `date_last_seen`, `date_registration`, `root_user`, `guest_user`, `login_count`, `active`, `show_info`, `phone`, `user_type`, `access`, `version_message_count`) VALUES
(1, 'admin', '', '1', '1', 'admin', 'b1b743523a624f62c77e1e6a76deb2f2', 1, 'hudzenlg@gmail.com', '1984-05-05 00:00:00', '2010-12-22 15:32:38', '2008-03-15 03:29:00', '1', '0', 169, '1', '1', '', '2', '1', 0),
(2, 'Your Name', 'Your Surname', '1', '1', 'guest', '', 1, 'yourmail@yourdomain.com', '1984-05-05 00:00:00', '2010-12-22 15:33:56', '2008-03-15 03:30:00', '0', '1', 0, '1', '1', '', '1', '1', 0),
(86, 'PG RealEstate', 'Broker', '1', '1', 'example1@example.tld', 'e10adc3949ba59abbe56e057f20f883e', 1, 'example1@example.tld', '1984-11-10 00:00:00', '2008-06-06 13:43:50', '2008-03-15 03:31:07', '0', '0', 249, '1', '1', '1-866-282-1029', '2', '1', 0),
(101, 'Lisa', 'Murphy', '1', '1', 'example2@example.tld', 'e10adc3949ba59abbe56e057f20f883e', 1, 'example2@example.tld', '0000-00-00 00:00:00', '2008-06-05 11:34:26', '2008-04-25 04:53:13', '0', '0', 26, '1', '1', '123 45 67', '1', '1', 0),
(102, 'Michael', 'Kreick', '1', '1', 'example3@example.tld', 'e10adc3949ba59abbe56e057f20f883e', 1, 'example3@example.tld', '0000-00-00 00:00:00', '2008-04-25 17:37:08', '2008-04-25 05:32:02', '0', '0', 0, '1', '0', '123 45 67', '1', '1', 0),
(103, 'Forsaleby2007', '.', '1', '1', 'example4@example.tld', 'e10adc3949ba59abbe56e057f20f883e', 1, 'example4@example.tld', '0000-00-00 00:00:00', '2008-05-18 17:05:50', '2008-04-26 10:57:05', '0', '0', 1, '1', '0', '360-891-1710', '1', '1', 0),
(104, 'Heid', 'O&#039;Connor', '1', '1', 'example5@example.tld', 'e10adc3949ba59abbe56e057f20f883e', 1, 'example5@example.tld', '0000-00-00 00:00:00', '2008-05-18 15:28:58', '2008-04-26 11:07:35', '0', '0', 1, '1', '0', '360-433-9114', '1', '1', 0),
(105, 'Randy', 'Ingraham', '1', '1', 'example6@example.tld', 'e10adc3949ba59abbe56e057f20f883e', 1, 'example6@example.tld', '0000-00-00 00:00:00', '2008-05-18 15:27:38', '2008-04-26 02:10:02', '0', '0', 3, '1', '0', '(406) 745-5123', '1', '1', 0),
(106, 'John', 'Crider', '1', '1', 'example7@example.tld', 'e10adc3949ba59abbe56e057f20f883e', 1, 'example7@example.tld', '1910-01-01 00:00:00', '2008-04-26 14:38:18', '2008-04-26 02:25:09', '0', '0', 0, '1', '0', '2392802259', '2', '1', 0),
(107, 'John', 'Wallace', '1', '1', 'example8@example.tld', 'e10adc3949ba59abbe56e057f20f883e', 1, 'example8@example.tld', '1910-01-01 00:00:00', '2008-04-26 17:58:15', '2008-04-26 05:07:02', '0', '0', 1, '1', '1', '518-392-7062', '2', '1', 0),
(108, 'Rhonda', 'Lindsay', '1', '1', 'example9@example.tld', 'e10adc3949ba59abbe56e057f20f883e', 1, 'example9@example.tld', '1910-01-01 00:00:00', '2008-04-28 12:42:51', '2008-04-28 12:35:19', '0', '0', 0, '1', '0', '8664042311', '2', '1', 0),
(109, 'Deborah', 'Wilson', '1', '1', 'example10@example.tld', 'e10adc3949ba59abbe56e057f20f883e', 1, 'example10@example.tld', '1910-01-01 00:00:00', '2008-04-28 13:15:04', '2008-04-28 12:48:06', '0', '0', 0, '1', '0', '8772412450', '2', '1', 0),
(115, 'Frankie', 'Firouznia', '1', '1', 'example11@example.tld', 'e10adc3949ba59abbe56e057f20f883e', 1, 'example11@example.tld', '0000-00-00 00:00:00', '2008-05-22 13:06:48', '2008-05-19 03:57:35', '0', '0', 2, '1', '0', '(631) 455-8452', '2', '1', 0),
(116, 'Scott', 'Kien', '1', '1', 'example12@example.tld', 'e10adc3949ba59abbe56e057f20f883e', 1, 'example12@example.tld', '1910-01-01 00:00:00', '2008-05-23 12:22:35', '2008-05-21 11:41:39', '0', '0', 2, '1', '0', '(714) 478-0533', '2', '1', 0),
(119, 'Jerry', 'Messner', '1', '1', 'example13@example.tld', 'e10adc3949ba59abbe56e057f20f883e', 1, 'example13@example.tld', '1910-01-01 00:00:00', '2008-05-22 12:03:26', '2008-05-22 11:46:18', '0', '0', 0, '1', '0', '(847) 202-9084', '2', '1', 0),
(120, 'Mark', 'Louis', '1', '1', 'example14@example.tld', 'e10adc3949ba59abbe56e057f20f883e', 1, 'example14@example.tld', '0000-00-00 00:00:00', '2008-06-05 13:15:59', '2008-05-22 12:13:36', '0', '0', 2, '1', '0', '', '1', '1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `re_user_group`
--

DROP TABLE IF EXISTS `re_user_group`;
CREATE TABLE IF NOT EXISTS `re_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_group` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_group` (`id_group`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `re_user_group`
--

INSERT INTO `re_user_group` (`id`, `id_user`, `id_group`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 17, 3),
(4, 39, 3),
(5, 40, 3),
(6, 86, 4),
(7, 39, 3),
(8, 40, 3),
(9, 101, 3),
(10, 102, 3),
(11, 103, 3),
(12, 104, 3),
(13, 105, 3),
(14, 106, 3),
(15, 107, 3),
(16, 108, 3),
(17, 109, 3),
(18, 115, 3),
(19, 116, 3),
(20, 119, 3),
(21, 120, 3);

-- --------------------------------------------------------

--
-- Table structure for table `re_user_photos`
--

DROP TABLE IF EXISTS `re_user_photos`;
CREATE TABLE IF NOT EXISTS `re_user_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `photo_path` varchar(255) NOT NULL,
  `approve` enum('0','1','2') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_user_photos`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_user_reg_data`
--

DROP TABLE IF EXISTS `re_user_reg_data`;
CREATE TABLE IF NOT EXISTS `re_user_reg_data` (
  `id_user` int(11) NOT NULL DEFAULT '0',
  `company_name` varchar(255) NOT NULL DEFAULT '',
  `company_url` varchar(50) NOT NULL DEFAULT '',
  `company_rent_count` varchar(50) NOT NULL DEFAULT '',
  `company_how_know` varchar(255) NOT NULL DEFAULT '',
  `company_quests_comments` varchar(255) NOT NULL DEFAULT '',
  `weekday_str` varchar(100) NOT NULL DEFAULT '',
  `work_time_begin` int(3) DEFAULT '0',
  `work_time_end` int(3) DEFAULT '0',
  `lunch_time_begin` int(3) DEFAULT '0',
  `lunch_time_end` int(3) DEFAULT '0',
  `logo_path` varchar(255) NOT NULL DEFAULT '',
  `admin_approve` enum('0','1','2') DEFAULT '1',
  `id_country` int(11) DEFAULT '0',
  `id_region` int(11) DEFAULT '0',
  `id_city` int(11) DEFAULT '0',
  `address` varchar(255) NOT NULL DEFAULT '',
  `postal_code` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_user_reg_data`
--

INSERT INTO `re_user_reg_data` (`id_user`, `company_name`, `company_url`, `company_rent_count`, `company_how_know`, `company_quests_comments`, `weekday_str`, `work_time_begin`, `work_time_end`, `lunch_time_begin`, `lunch_time_end`, `logo_path`, `admin_approve`, `id_country`, `id_region`, `id_city`, `address`, `postal_code`) VALUES
(86, 'Pilot Group', 'www.website.tld', '', '', '', '1,2,3,4,5', 8, 4, 12, 13, '86_5d499a0c.gif', '1', 226, 4100, 2756562, '', ''),
(106, 'John R. Wood, Realtors', '', '', '', '', '', 0, 0, 0, 0, '106_f0c43793.jpg', '1', 226, 4100, 2756562, '', ''),
(107, 'The Grey Rider', '', '', '', '', '', 0, 0, 0, 0, '107_b1519ff0.gif', '1', 226, 4100, 2756562, '', ''),
(108, 'Coldwell Banker Dolphin Realty', '', '', '', '', '', 0, 0, 0, 0, '', '1', 226, 4100, 2756562, '', ''),
(109, 'Carolina Mountain Realty, Inc.', '', '', '', '', '', 0, 0, 0, 0, '', '1', 226, 4100, 2756562, '', ''),
(110, 'John R. Wood, Realtors', '', '', '', '', '', 0, 0, 0, 0, '', '1', 226, 4100, 2756562, '', ''),
(114, 'Yoly''s catering & travel', '', '', '', '', '', 0, 0, 0, 0, '', '1', 226, 4100, 2756562, '', ''),
(116, 'AOL', '', '', '', '', '', 0, 0, 0, 0, '', '1', 226, 4100, 2756562, '', ''),
(119, 'Cornell Commons', '', '', '', '', '', 0, 0, 0, 0, '', '1', 226, 4100, 2756562, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `re_user_rent_ages`
--

DROP TABLE IF EXISTS `re_user_rent_ages`;
CREATE TABLE IF NOT EXISTS `re_user_rent_ages` (
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `my_age_1` int(11) NOT NULL DEFAULT '0',
  `my_age_2` int(11) NOT NULL DEFAULT '0',
  `his_age_1` int(11) NOT NULL DEFAULT '0',
  `his_age_2` int(11) NOT NULL DEFAULT '0',
  KEY `id_ad` (`id_ad`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_user_rent_ages`
--

INSERT INTO `re_user_rent_ages` (`id_ad`, `id_user`, `my_age_1`, `my_age_2`, `his_age_1`, `his_age_2`) VALUES
(82, 119, 0, 0, 0, 0),
(76, 86, 0, 0, 18, 99),
(79, 86, 0, 0, 0, 0),
(26, 87, 0, 0, 0, 0),
(81, 116, 0, 0, 18, 99),
(83, 120, 0, 0, 0, 0),
(59, 101, 0, 0, 0, 0),
(60, 102, 0, 0, 0, 0),
(78, 86, 0, 0, 0, 0),
(62, 103, 0, 0, 0, 0),
(63, 104, 0, 0, 0, 0),
(64, 105, 0, 0, 0, 0),
(65, 106, 0, 0, 0, 0),
(66, 107, 0, 0, 0, 0),
(67, 108, 0, 0, 0, 0),
(68, 109, 0, 0, 0, 0),
(80, 115, 0, 0, 18, 99);

-- --------------------------------------------------------

--
-- Table structure for table `re_user_rent_location`
--

DROP TABLE IF EXISTS `re_user_rent_location`;
CREATE TABLE IF NOT EXISTS `re_user_rent_location` (
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `id_country` varchar(30) NOT NULL DEFAULT '0',
  `id_region` varchar(30) NOT NULL DEFAULT '0',
  `id_city` varchar(30) NOT NULL DEFAULT '0',
  `zip_code` varchar(255) NOT NULL DEFAULT '',
  `street_1` varchar(255) NOT NULL DEFAULT '',
  `street_2` varchar(255) NOT NULL DEFAULT '',
  `adress` varchar(255) NOT NULL DEFAULT '',
  KEY `id_user` (`id_user`),
  KEY `id_ad` (`id_ad`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_user_rent_location`
--

INSERT INTO `re_user_rent_location` (`id_user`, `id_ad`, `id_country`, `id_region`, `id_city`, `zip_code`, `street_1`, `street_2`, `adress`) VALUES
(116, 81, '251', '4202', '1657806', '', '', '', ''),
(119, 82, '251', '4211', '1677817', '', '', '', '416 N Eric Dr'),
(120, 83, '251', '4230', '1734912', '', '', '', ''),
(86, 76, '251', '4225', '1723966', '123123', '', '', ''),
(86, 79, '251', '4245', '1792956', '', '', '', ''),
(101, 59, '251', '4230', '1730202', '11206', 'Pulaski St', '', '163 Pulaski St'),
(102, 60, '251', '4245', '1791441', '98155', '56TH AVE', '', ' 19344 56TH AVE'),
(86, 78, '251', '4230', '1730082', '', '', '', ''),
(103, 62, '251', '4245', '1790756', '98671', 'W S ST', '', '797 W S ST'),
(104, 63, '251', '4245', '1790752', '98686', 'ne 37th ct', '', '10905 ne 37th ct'),
(105, 64, '251', '4224', '1722050', '11206', 'S. 300 E.', '', '2240 S. 300 E.'),
(106, 65, '251', '4207', '1666692', '', '', '', ''),
(107, 66, '251', '4230', '1730082', '', 'Fifth Avenue', '', '350 Fifth Avenue'),
(108, 67, '251', '4207', '1667295', '', '', '', ''),
(109, 68, '251', '4231', '1736210', '', '', '', ''),
(115, 80, '251', '4230', '1734912', '11743', '', '', '9 Rockne St');

-- --------------------------------------------------------

--
-- Table structure for table `re_user_rent_pays`
--

DROP TABLE IF EXISTS `re_user_rent_pays`;
CREATE TABLE IF NOT EXISTS `re_user_rent_pays` (
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `min_payment` int(11) NOT NULL DEFAULT '0',
  `max_payment` int(11) NOT NULL DEFAULT '0',
  `auction` enum('1','2') NOT NULL DEFAULT '1',
  `min_deposit` int(11) NOT NULL DEFAULT '0',
  `max_deposit` int(11) NOT NULL DEFAULT '0',
  `min_live_square` int(11) NOT NULL DEFAULT '0',
  `max_live_square` int(11) NOT NULL DEFAULT '0',
  `min_total_square` int(11) NOT NULL DEFAULT '0',
  `max_total_square` int(11) NOT NULL DEFAULT '0',
  `min_land_square` int(11) NOT NULL DEFAULT '0',
  `max_land_square` int(11) NOT NULL DEFAULT '0',
  `min_floor` int(3) NOT NULL DEFAULT '0',
  `max_floor` int(3) NOT NULL DEFAULT '0',
  `floor_num` int(3) NOT NULL DEFAULT '0',
  `subway_min` int(3) NOT NULL DEFAULT '0',
  `min_year_build` int(4) NOT NULL DEFAULT '0',
  `max_year_build` int(4) NOT NULL DEFAULT '0',
  KEY `id_ad` (`id_ad`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `re_user_rent_pays`
--

INSERT INTO `re_user_rent_pays` (`id_ad`, `id_user`, `min_payment`, `max_payment`, `auction`, `min_deposit`, `max_deposit`, `min_live_square`, `max_live_square`, `min_total_square`, `max_total_square`, `min_land_square`, `max_land_square`, `min_floor`, `max_floor`, `floor_num`, `subway_min`, `min_year_build`, `max_year_build`) VALUES
(59, 101, 769000, 0, '1', 0, 0, 1900, 0, 3420, 0, 0, 0, 0, 0, 1, 0, 1910, 0),
(60, 102, 21500, 0, '1', 0, 0, 0, 0, 1456, 0, 0, 0, 0, 0, 1, 0, 1996, 0),
(62, 103, 665000, 0, '1', 0, 0, 0, 0, 3504, 0, 0, 0, 0, 0, 2, 0, 2007, 0),
(63, 104, 295000, 0, '1', 0, 0, 0, 0, 1980, 0, 0, 0, 0, 0, 1, 0, 0, 0),
(64, 105, 125000, 0, '1', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0),
(65, 106, 529900, 0, '1', 0, 0, 4073, 0, 0, 0, 8450, 0, 0, 0, 1, 0, 1999, 0),
(66, 107, 62000, 0, '2', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 77, 0, 0, 0),
(67, 108, 1050000, 0, '2', 0, 0, 3833, 0, 0, 0, 0, 0, 0, 0, 2, 0, 2006, 0),
(68, 109, 350000, 0, '1', 0, 0, 0, 0, 1740, 0, 0, 0, 0, 0, 1, 0, 1993, 0),
(76, 86, 1200000, 0, '1', 0, 0, 100, 0, 120, 0, 0, 0, 21, 0, 22, 3, 2008, 0),
(78, 86, 1000000, 0, '1', 0, 0, 80, 0, 100, 0, 0, 0, 0, 0, 1, 0, 0, 0),
(79, 86, 17000, 0, '1', 0, 0, 170, 0, 200, 0, 0, 0, 5, 0, 7, 7, 1970, 0),
(80, 115, 1700, 0, '2', 3400, 0, 1150, 0, 0, 0, 0, 0, 2, 0, 1, 0, 0, 0),
(81, 116, 3300, 0, '2', 3300, 0, 1800, 0, 0, 0, 0, 0, 0, 0, 2, 0, 0, 0),
(82, 119, 1800, 0, '2', 3600, 0, 2010, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0),
(83, 120, 800, 1000, '1', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `re_user_rent_plan`
--

DROP TABLE IF EXISTS `re_user_rent_plan`;
CREATE TABLE IF NOT EXISTS `re_user_rent_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `upload_path` varchar(255) NOT NULL DEFAULT '',
  `file_type` varchar(20) DEFAULT NULL,
  `status` enum('0','1') DEFAULT '1',
  `admin_approve` enum('0','1','2') DEFAULT '1',
  `user_comment` varchar(255) NOT NULL DEFAULT '',
  `sequence` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_ad` (`id_ad`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `re_user_rent_plan`
--

INSERT INTO `re_user_rent_plan` (`id`, `id_user`, `id_ad`, `upload_path`, `file_type`, `status`, `admin_approve`, `user_comment`, `sequence`) VALUES
(28, 86, 76, '86_c872bb84.jpg', NULL, '1', '1', '', 1),
(26, 107, 66, '107_2198d95c.jpg', NULL, '1', '1', '', 1),
(27, 107, 66, '107_fa41f7e0.jpg', NULL, '1', '1', '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `re_user_rent_upload`
--

DROP TABLE IF EXISTS `re_user_rent_upload`;
CREATE TABLE IF NOT EXISTS `re_user_rent_upload` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_ad` int(11) NOT NULL DEFAULT '0',
  `upload_path` varchar(255) NOT NULL DEFAULT '',
  `upload_type` enum('f','a','v') NOT NULL DEFAULT 'f',
  `file_type` varchar(20) DEFAULT NULL,
  `status` enum('0','1') DEFAULT '1',
  `admin_approve` enum('0','1','2') DEFAULT '1',
  `user_comment` varchar(255) NOT NULL DEFAULT '',
  `sequence` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_ad` (`id_ad`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=176 ;

--
-- Dumping data for table `re_user_rent_upload`
--

INSERT INTO `re_user_rent_upload` (`id`, `id_user`, `id_ad`, `upload_path`, `upload_type`, `file_type`, `status`, `admin_approve`, `user_comment`, `sequence`) VALUES
(87, 101, 59, '101_354f3171.jpg', 'f', 'image/jpeg', '1', '1', '', 1),
(77, 101, 59, '101_b9aaace8.jpg', 'f', 'image/jpeg', '1', '1', '', 2),
(78, 101, 59, '101_b7f6547f.jpg', 'f', 'image/jpeg', '1', '1', '', 3),
(79, 101, 59, '101_f3c4ef3a.jpg', 'f', 'image/jpeg', '1', '1', '', 4),
(80, 102, 60, '102_eab8a1ac.jpg', 'f', 'image/jpeg', '1', '1', '', 1),
(81, 103, 62, '103_cca83cca.jpg', 'f', 'image/jpeg', '1', '1', '', 1),
(82, 103, 62, '103_b539272b.jpg', 'f', 'image/jpeg', '1', '1', '', 2),
(83, 103, 62, '103_02715d29.jpg', 'f', 'image/jpeg', '1', '1', '', 3),
(84, 104, 63, '104_9aef57ed.jpg', 'f', 'image/jpeg', '1', '1', '', 1),
(85, 104, 63, '104_d435c668.jpg', 'f', 'image/jpeg', '1', '1', '', 2),
(86, 104, 63, '104_90ad668f.jpg', 'f', 'image/jpeg', '1', '1', '', 3),
(88, 105, 64, '105_5e29822b.jpg', 'f', 'image/jpeg', '1', '1', '', 1),
(89, 106, 65, '106_b1fc0410.jpg', 'f', 'image/jpeg', '1', '1', '', 1),
(90, 106, 65, '106_5dc10261.jpg', 'f', 'image/jpeg', '1', '1', '', 2),
(91, 106, 65, '106_47c0ecc0.jpg', 'f', 'image/jpeg', '1', '1', '', 3),
(92, 106, 65, '106_a1f15657.jpg', 'f', 'image/jpeg', '1', '1', '', 4),
(93, 106, 65, '106_9301d734.jpg', 'f', 'image/jpeg', '1', '1', '', 5),
(94, 106, 65, '106_1d4af32c.jpg', 'f', 'image/jpeg', '1', '1', '', 6),
(95, 106, 65, '106_e019267e.jpg', 'f', 'image/jpeg', '1', '1', '', 7),
(96, 107, 66, '107_57f981ce.jpg', 'f', 'image/jpeg', '1', '1', '', 1),
(97, 107, 66, '107_e79a3d02.jpg', 'f', 'image/jpeg', '1', '1', '', 2),
(98, 108, 67, '108_1169909e.jpg', 'f', 'image/jpeg', '1', '1', '', 1),
(99, 108, 67, '108_3bda71a6.jpg', 'f', 'image/jpeg', '1', '1', '', 2),
(100, 108, 67, '108_2d0e3542.jpg', 'f', 'image/jpeg', '1', '1', '', 3),
(101, 108, 67, '108_4bd5f2fe.jpg', 'f', 'image/jpeg', '1', '1', '', 4),
(102, 108, 67, '108_b305323c.jpg', 'f', 'image/jpeg', '1', '1', '', 5),
(103, 108, 67, '108_95b3c08b.jpg', 'f', 'image/jpeg', '1', '1', '', 6),
(104, 108, 67, '108_1ae8a18a.jpg', 'f', 'image/jpeg', '1', '1', '', 7),
(105, 108, 67, '108_9feae7cc.jpg', 'f', 'image/jpeg', '1', '1', '', 8),
(106, 109, 68, '109_24929290.jpg', 'f', 'image/jpeg', '1', '1', '', 1),
(107, 109, 68, '109_45c6583f.jpg', 'f', 'image/jpeg', '1', '1', '', 2),
(108, 109, 68, '109_c7530482.jpg', 'f', 'image/jpeg', '1', '1', '', 3),
(109, 109, 68, '109_2d7e37c4.jpg', 'f', 'image/jpeg', '1', '1', '', 4),
(113, 109, 68, '109_6322d4cc.jpg', 'f', 'image/jpeg', '1', '1', '', 5),
(111, 109, 68, '109_c5af68c0.jpg', 'f', 'image/jpeg', '1', '1', '', 6),
(114, 109, 68, '109_6c48b67a.jpg', 'f', 'image/jpeg', '1', '1', '', 7),
(115, 109, 68, '109_ccf2e2e0.jpg', 'f', 'image/jpeg', '1', '1', '', 8),
(125, 86, 76, '86_ef1be2db.jpg', 'f', 'image/pjpeg', '1', '1', '', 1),
(126, 86, 76, '86_0cacee99.jpg', 'f', 'image/pjpeg', '1', '1', '', 2),
(127, 86, 76, '86_51351986.jpg', 'f', 'image/pjpeg', '1', '1', '', 3),
(140, 86, 78, '86_ea4d0a0c.jpg', 'f', 'image/pjpeg', '1', '1', '', 1),
(139, 86, 78, '86_b7a013de.jpg', 'f', 'image/pjpeg', '1', '1', '', 2),
(138, 86, 78, '86_03ad4f89.jpg', 'f', 'image/pjpeg', '1', '1', '', 3),
(137, 86, 78, '86_4d8cb23d.jpg', 'f', 'image/pjpeg', '1', '1', '', 4),
(135, 86, 78, '86_7b812787.jpg', 'f', 'image/pjpeg', '1', '1', '', 5),
(136, 86, 78, '86_08f95813.jpg', 'f', 'image/pjpeg', '1', '1', '', 6),
(146, 86, 79, '86_b55fd9c6.jpg', 'f', 'image/pjpeg', '1', '1', '', 1),
(144, 86, 79, '86_6826d7c4.jpg', 'f', 'image/pjpeg', '1', '1', '', 2),
(145, 86, 79, '86_07f9f382.jpg', 'f', 'image/pjpeg', '1', '1', '', 3),
(143, 86, 79, '86_851b4afd.jpg', 'f', 'image/pjpeg', '1', '1', '', 4),
(141, 86, 79, '86_a50362c9.jpg', 'f', 'image/pjpeg', '1', '1', '', 5),
(142, 86, 79, '86_4622dbd6.jpg', 'f', 'image/pjpeg', '1', '1', '', 6),
(147, 86, 79, '86_3214417a.jpg', 'f', 'image/pjpeg', '1', '1', '', 7),
(162, 115, 80, '115_5aa15375.jpg', 'f', 'image/pjpeg', '1', '1', '', 1),
(161, 115, 80, '115_e4f80719.jpg', 'f', 'image/pjpeg', '1', '1', '', 2),
(159, 115, 80, '115_3638ee43.jpg', 'f', 'image/pjpeg', '1', '1', '', 3),
(160, 115, 80, '115_6315478a.jpg', 'f', 'image/pjpeg', '1', '1', '', 4),
(157, 115, 80, '115_ab679a09.jpg', 'f', 'image/pjpeg', '1', '1', '', 5),
(158, 115, 80, '115_a9cfaf7d.jpg', 'f', 'image/pjpeg', '1', '1', '', 6),
(163, 115, 80, '115_2fc624bb.jpg', 'f', 'image/pjpeg', '1', '1', '', 7),
(164, 115, 80, '115_dd09eba5.jpg', 'f', 'image/pjpeg', '1', '1', '', 8),
(165, 116, 81, '116_6931d373.jpg', 'f', 'image/pjpeg', '1', '1', '', 1),
(166, 116, 81, '116_15844975.jpg', 'f', 'image/pjpeg', '1', '1', '', 2),
(167, 116, 81, '116_705410d8.jpg', 'f', 'image/pjpeg', '1', '1', '', 3),
(168, 116, 81, '116_c03eb652.jpg', 'f', 'image/pjpeg', '1', '1', '', 4),
(169, 116, 81, '116_ae0ee91e.jpg', 'f', 'image/pjpeg', '1', '1', '', 5),
(170, 116, 81, '116_5eab0155.jpg', 'f', 'image/pjpeg', '1', '1', '', 6),
(171, 116, 81, '116_905a3252.jpg', 'f', 'image/pjpeg', '1', '1', '', 7),
(172, 116, 81, '116_fcff12eb.jpg', 'f', 'image/pjpeg', '1', '1', '', 8),
(173, 116, 81, '116_e1d8494f.jpg', 'f', 'image/pjpeg', '1', '1', '', 9),
(174, 116, 81, '116_4d6a42dd.jpg', 'f', 'image/pjpeg', '1', '1', '', 10),
(175, 119, 82, '119_d3bfb13d.jpg', 'f', 'image/pjpeg', '1', '1', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `re_user_sell_lease_payment`
--

DROP TABLE IF EXISTS `re_user_sell_lease_payment`;
CREATE TABLE IF NOT EXISTS `re_user_sell_lease_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `ads_number` int(11) NOT NULL DEFAULT '0',
  `amount` double NOT NULL DEFAULT '0',
  `used_ads_number` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `re_user_sell_lease_payment`
--


-- --------------------------------------------------------

--
-- Table structure for table `re_values_apartment`
--

DROP TABLE IF EXISTS `re_values_apartment`;
CREATE TABLE IF NOT EXISTS `re_values_apartment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_spr` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_spr` (`id_spr`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=246 ;

--
-- Dumping data for table `re_values_apartment`
--

INSERT INTO `re_values_apartment` (`id`, `id_spr`, `name`) VALUES
(245, 13, 'Рядом с рекой'),
(244, 13, 'Прогулочная зона'),
(243, 13, 'Фитнес Центр'),
(242, 13, 'Каток'),
(241, 13, 'Бассейн'),
(240, 13, 'Прачечная'),
(239, 13, 'Вход для инвалидов'),
(238, 13, 'Стоянка автомобилей'),
(237, 13, 'Метро'),
(232, 15, 'Баня'),
(30, 3, 'Furnitured'),
(31, 3, 'None'),
(231, 15, 'Балкон'),
(230, 15, 'Тренажерный зал'),
(229, 15, 'Бильярдная'),
(228, 15, 'Охрана'),
(122, 13, 'Beach Access'),
(124, 13, 'Golf nearby'),
(227, 15, 'Электронная охрана'),
(127, 13, 'Lake nearby'),
(128, 13, 'Lake View'),
(130, 13, 'Ocean nearby'),
(131, 13, 'Ocean View'),
(226, 15, 'Спутниковое или кабельное телевидение'),
(135, 14, 'Boating'),
(136, 14, 'Cycling'),
(138, 14, 'Fishing'),
(139, 14, 'Golf'),
(140, 14, 'Hiking'),
(141, 14, 'Horseback Riding'),
(142, 14, 'Hunting'),
(225, 15, 'Телефон'),
(144, 14, 'Mountain Biking'),
(145, 14, 'Paragliding'),
(147, 14, 'Rollerblading'),
(148, 14, 'Scuba/Snorkel'),
(149, 14, 'Shopping/Restaurants'),
(150, 14, 'Skiing'),
(151, 14, 'Surfing'),
(152, 14, 'Swimming'),
(153, 14, 'Tennis'),
(154, 14, 'Theme Park'),
(156, 14, 'Water Skiing'),
(233, 16, 'Сушильная машина'),
(159, 15, 'Beach Service (included)'),
(160, 15, 'Charcoal Grill'),
(236, 13, 'Автобусная остановка'),
(163, 15, 'Covered Parking'),
(166, 15, 'Elevator'),
(167, 15, 'Fireplace'),
(168, 15, 'Fitness Center'),
(224, 15, 'Электричество'),
(171, 15, 'Garden'),
(172, 15, 'Gas Grill'),
(235, 13, 'Дома невысокие'),
(175, 15, 'High-Speed Internet'),
(176, 15, 'Jacuzzi'),
(177, 15, 'Lanai'),
(223, 15, 'Канализация'),
(234, 13, 'Дома высотки'),
(181, 15, 'Patio'),
(182, 15, 'Pool (access)'),
(184, 15, 'Sauna (access)'),
(186, 15, 'Spa/Hot Tub (access)'),
(222, 15, 'Горячая вода и отопление'),
(189, 15, 'Terrace'),
(190, 16, 'Alarm Clock'),
(191, 16, 'Blender'),
(192, 16, 'CD Player'),
(193, 16, 'Coffee Maker'),
(194, 16, 'Dishwasher'),
(195, 16, 'DVD Player'),
(196, 16, 'Freezer'),
(197, 16, 'Iron and Board'),
(198, 16, 'Microwave'),
(221, 15, 'Водопровод'),
(200, 16, 'Phone'),
(201, 16, 'Radio'),
(202, 16, 'Refrigerator'),
(203, 16, 'Satellite TV or Cable TV'),
(204, 16, 'Stereo'),
(205, 16, 'Toaster'),
(206, 16, 'TV(s)'),
(207, 16, 'VCR Player'),
(208, 16, 'Washer/Dryer (access)');

-- --------------------------------------------------------

--
-- Table structure for table `re_values_deactivate`
--

DROP TABLE IF EXISTS `re_values_deactivate`;
CREATE TABLE IF NOT EXISTS `re_values_deactivate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_spr` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_spr` (`id_spr`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `re_values_deactivate`
--

INSERT INTO `re_values_deactivate` (`id`, `id_spr`, `name`) VALUES
(2, 1, 'I found apartment'),
(3, 1, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `re_values_description`
--

DROP TABLE IF EXISTS `re_values_description`;
CREATE TABLE IF NOT EXISTS `re_values_description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_spr` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_spr` (`id_spr`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `re_values_description`
--

INSERT INTO `re_values_description` (`id`, `id_spr`, `name`) VALUES
(1, 1, '1'),
(2, 1, '2'),
(3, 1, '3'),
(4, 1, '4'),
(5, 1, '5'),
(6, 1, '6'),
(7, 1, '7'),
(8, 1, '8'),
(9, 1, '9'),
(10, 1, '10'),
(11, 1, '10+'),
(12, 2, '1'),
(13, 2, '2'),
(14, 2, '3'),
(15, 2, '4'),
(16, 2, '5'),
(17, 2, '6'),
(18, 2, '7'),
(19, 2, '8'),
(20, 2, '9'),
(21, 2, '10'),
(22, 2, '10+'),
(23, 3, 'на 1 машину'),
(24, 3, 'на 2 машины'),
(25, 3, 'на 3 машины '),
(26, 3, 'на 4 машины'),
(27, 3, 'на 5 машин'),
(28, 3, 'на 5 + машин');

-- --------------------------------------------------------

--
-- Table structure for table `re_values_gender`
--

DROP TABLE IF EXISTS `re_values_gender`;
CREATE TABLE IF NOT EXISTS `re_values_gender` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_spr` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_spr` (`id_spr`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `re_values_gender`
--

INSERT INTO `re_values_gender` (`id`, `id_spr`, `name`) VALUES
(1, 1, 'Male'),
(2, 1, 'Female'),
(3, 1, 'Семья с детьми'),
(4, 1, 'Семья без детей');

-- --------------------------------------------------------

--
-- Table structure for table `re_values_language`
--

DROP TABLE IF EXISTS `re_values_language`;
CREATE TABLE IF NOT EXISTS `re_values_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_spr` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_spr` (`id_spr`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=87 ;

--
-- Dumping data for table `re_values_language`
--

INSERT INTO `re_values_language` (`id`, `id_spr`, `name`) VALUES
(1, 1, 'English'),
(2, 1, 'Deutsch'),
(3, 1, 'Fran&ccedil;ais'),
(4, 1, 'Espa&ntilde;ol'),
(5, 1, 'Italiano'),
(6, 1, 'Русский'),
(7, 1, 'Hebrew'),
(8, 1, 'Chinese'),
(9, 1, 'Qafar'),
(10, 1, 'Afrikaans'),
(11, 1, '&#1575;&#1585;&#1583;&#1608;'),
(12, 1, '&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;'),
(13, 1, 'Аз&#1241;рба?&#1209;ан'),
(14, 1, 'Беларускі'),
(15, 1, 'Български'),
(16, 1, 'Catal&agrave;'),
(17, 1, '&#268;e&#353;tina'),
(18, 1, 'Dansk'),
(19, 1, '&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940;'),
(20, 1, 'Eesti'),
(21, 1, '&#1601;&#1575;&#1585;&#1587;&#1740;'),
(22, 1, 'Suomi'),
(23, 1, 'Gaeilge'),
(24, 1, '&#2361;&#2367;&#2306;&#2342;&#2368;'),
(25, 1, 'Hrvatski'),
(26, 1, 'Magyar'),
(27, 1, '&#1344;&#1377;&#1397;&#1381;&#1408;&#1383;&#1398;'),
(28, 1, '&#26085;&#26412;&#35486;'),
(29, 1, '&#4325;&#4304;&#4320;&#4311;&#4323;&#4314;&#4312;'),
(30, 1, '&#1178;аза&#1179;'),
(31, 1, 'Lietuvi&#371;'),
(32, 1, 'latvie&#353;u'),
(33, 1, 'Nederlands'),
(34, 1, 'Polski'),
(35, 1, 'Portugu&ecirc;s'),
(36, 1, 'Rom&acirc;n&#259;'),
(37, 1, 'Slovensk&yacute;'),
(38, 1, 'Sloven&#353;&#269;ina'),
(39, 1, 'Shqipe'),
(40, 1, 'Српски'),
(41, 1, 'Svenska'),
(42, 1, 'T&uuml;rk&ccedil;e'),
(43, 1, 'Татар'),
(44, 1, 'Українська'),
(45, 1, 'Ўзбек'),
(46, 1, '&#2437;&#2488;&#2478;&#2496;&#2527;&#2494;'),
(47, 1, '&#2476;&#2494;&#2434;&#2482;&#2494;'),
(48, 1, 'Cymraeg'),
(49, 1, '&#1931;&#1960;&#1928;&#1964;&#1920;&#1960;&#1924;&#1958;&#1936;&#1968;'),
(50, 1, '&#3938;&#4011;&#3964;&#3908;&#3851;&#3905;'),
(51, 1, 'Esperanto'),
(52, 1, 'Euskara'),
(53, 1, 'F&oslash;royskt'),
(54, 1, 'Galego'),
(55, 1, '&#2711;&#2753;&#2716;&#2736;&#2750;&#2724;&#2752;'),
(56, 1, 'Gaelg'),
(57, 1, 'Hawai'),
(58, 1, 'Bahasa Indonesia'),
(59, 1, '&Iacute;slenska'),
(60, 1, 'Kalaallisut'),
(61, 1, '&#54620;&#44397;&#50612;'),
(62, 1, '&#2325;&#2379;&#2306;&#2325;&#2339;&#2368;'),
(63, 1, 'Кыргыз'),
(64, 1, 'Kernewek'),
(65, 1, '&#3749;&#3762;&#3751;'),
(66, 1, 'Mакедонски'),
(67, 1, '&#3374;&#3378;&#3375;&#3390;&#3379;&#3330;'),
(68, 1, 'Монгол хэл'),
(69, 1, '&#2350;&#2352;&#2366;&#2336;&#2368;'),
(70, 1, 'Bahasa Melayu'),
(71, 1, 'Malti'),
(72, 1, 'Norsk bokm&aring;l'),
(73, 1, 'Norsk nynorsk'),
(74, 1, 'Oromoo'),
(75, 1, '&#2835;&#2908;&#2879;&#2822;'),
(76, 1, '&#2602;&#2672;&#2588;&#2622;&#2604;&#2624;'),
(77, 1, '&#1662;&#1690;&#1578;&#1608;'),
(78, 1, '&#2360;&#2306;&#2360;&#2381;&#2325;&#2371;&#2340;'),
(79, 1, 'Sidaamu Afo'),
(80, 1, 'Soomaali'),
(81, 1, 'Kiswahili'),
(82, 1, '&#1827;&#1816;&#1834;&#1821;&#1821;&#1808;'),
(83, 1, '&#2980;&#2990;&#3007;&#2996;&#3021;'),
(84, 1, '&#3108;&#3142;&#3122;&#3137;&#3095;&#3137;'),
(85, 1, '&#3652;&#3607;&#3618;'),
(86, 1, 'Ti&#7871;ng Vi&#7879;t');

-- --------------------------------------------------------

--
-- Table structure for table `re_values_people`
--

DROP TABLE IF EXISTS `re_values_people`;
CREATE TABLE IF NOT EXISTS `re_values_people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_spr` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_spr` (`id_spr`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

--
-- Dumping data for table `re_values_people`
--

INSERT INTO `re_values_people` (`id`, `id_spr`, `name`) VALUES
(45, 1, 'Нет и не переношу запах'),
(9, 4, 'Dog(s)'),
(10, 4, 'Cat(s)'),
(11, 4, 'Caged Pet(s)'),
(44, 10, 'Стремлюсь к большему'),
(50, 13, 'Сова (поздно ложусь, поздно встаю)'),
(16, 7, 'Hetero sexual'),
(17, 7, 'Bi sexual'),
(18, 7, 'Gay'),
(19, 7, 'Lesbian'),
(52, 13, 'Ни то, ни другое'),
(51, 13, 'Жаворонок (рано просыпаюсь)'),
(29, 10, 'Accidental earnings'),
(30, 10, 'Stable little income'),
(31, 10, 'Stable average income'),
(32, 10, 'Well-heeled'),
(33, 11, 'never'),
(34, 11, 'sometimes in company'),
(35, 11, 'like it'),
(36, 12, 'never tried'),
(37, 12, 'Reject'),
(38, 12, 'Smoke &quot;Indian grass&quot;'),
(39, 12, 'Take lights'),
(40, 12, 'Take strong ones'),
(41, 12, 'Trying to stop'),
(42, 12, 'Getting cured at NA'),
(43, 12, 'discontinued'),
(46, 1, 'Нет, но лояльно отношусь к курильщикам'),
(47, 1, 'Да, но редко'),
(48, 1, 'Да'),
(49, 1, 'Да, но бросаю');

-- --------------------------------------------------------

--
-- Table structure for table `re_values_period`
--

DROP TABLE IF EXISTS `re_values_period`;
CREATE TABLE IF NOT EXISTS `re_values_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_spr` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_spr` (`id_spr`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `re_values_period`
--

INSERT INTO `re_values_period` (`id`, `id_spr`, `name`) VALUES
(1, 1, '1 месяц'),
(2, 1, '3 месяца'),
(3, 1, 'более 6 месяцев'),
(4, 1, 'неограничен');

-- --------------------------------------------------------

--
-- Table structure for table `re_values_theme_rest`
--

DROP TABLE IF EXISTS `re_values_theme_rest`;
CREATE TABLE IF NOT EXISTS `re_values_theme_rest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_spr` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `re_values_theme_rest`
--

INSERT INTO `re_values_theme_rest` (`id`, `id_spr`, `name`) VALUES
(1, 1, 'Отдых дикарем'),
(2, 1, 'Пляжные туры'),
(3, 1, 'Праздничные туры'),
(4, 1, 'Автобусные туры'),
(5, 1, 'Бизнес туры'),
(6, 1, 'Паломнические туры'),
(7, 1, 'Горнолыжные туры'),
(8, 1, 'Детский отдых'),
(9, 1, 'Романтические туры'),
(10, 2, 'Серфинг'),
(11, 2, 'Дайвинг'),
(12, 2, 'Горнолыжный отдых'),
(13, 1, 'Эскурсионные туры'),
(14, 1, 'Круизные туры'),
(15, 1, 'Лечебные туры'),
(16, 1, 'Активный отдых'),
(17, 1, 'Семейный отдых'),
(18, 1, 'Ночные клубы'),
(19, 1, 'Аквапарки'),
(20, 1, 'Морские прогулки'),
(21, 1, 'Эскурсии'),
(22, 1, 'Советы'),
(23, 1, 'Фотогалерея'),
(24, 1, 'Достопримечательности'),
(25, 2, 'Лыжные походы'),
(26, 2, 'Рыбалка'),
(27, 2, 'Фотоохота'),
(28, 2, 'Прыжки с парашутом'),
(29, 2, 'Горный туризм'),
(30, 2, 'Водный туризм'),
(31, 2, 'Эскурсионные туры'),
(32, 2, 'Конный туризм'),
(33, 2, 'Вело туризм'),
(34, 2, 'Джипинг'),
(35, 2, 'Спелео туризм'),
(36, 2, 'Скалолазание'),
(37, 2, 'Туристические походы'),
(38, 2, 'Места боевой славы');

-- --------------------------------------------------------

--
-- Table structure for table `re_values_type`
--

DROP TABLE IF EXISTS `re_values_type`;
CREATE TABLE IF NOT EXISTS `re_values_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_spr` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_spr` (`id_spr`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

--
-- Dumping data for table `re_values_type`
--

INSERT INTO `re_values_type` (`id`, `id_spr`, `name`) VALUES
(8, 2, 'Передвижной дом-фургон'),
(9, 2, 'Комната'),
(10, 2, 'Квартира'),
(11, 2, 'Квартирный комплекс'),
(12, 2, 'Кваритира в двух уровнях'),
(13, 2, 'Квартира в трёх уровнях'),
(14, 2, 'Бунгало'),
(15, 2, 'Городской одноквартирный дом'),
(16, 2, 'Дом для одной семьи'),
(17, 2, 'Дом для гостей'),
(18, 2, 'Небольшой дом/коттедж'),
(19, 2, 'Дом'),
(20, 2, 'Особняк'),
(21, 2, 'Замок'),
(22, 2, 'Дворец'),
(23, 2, 'Вилла'),
(24, 2, 'Ферма/ранчо'),
(25, 2, 'Кондоминиум'),
(26, 2, 'Кондо-отель'),
(27, 2, 'Гостиница (сельская)'),
(28, 2, 'Мотель'),
(29, 2, 'Частная гостиница'),
(30, 2, 'Отель'),
(31, 2, 'Земельный участок'),
(32, 2, 'Зона зелёных насаждений'),
(33, 2, 'Пляжная территория'),
(34, 2, 'Прибрежная собственность'),
(35, 2, 'Пристань'),
(36, 2, 'Нежилое чердачное помещение'),
(37, 2, 'Коммерческая недвижимость'),
(38, 2, 'Офисная площадь'),
(39, 2, 'Помещение для деловых операций'),
(40, 2, 'Помещение на первом этаже с выходом на улицу'),
(41, 2, 'Производственная площадь'),
(42, 2, 'Складское помещение'),
(43, 2, 'Ресторан'),
(44, 2, 'Винодельня'),
(45, 2, 'Виноградник'),
(46, 2, 'Место отдыха'),
(47, 2, 'Экзотическая собственность'),
(48, 2, 'Остров'),
(49, 2, 'Другое');
