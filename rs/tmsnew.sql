-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 22, 2015 at 01:00 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tmsnew`
--

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE IF NOT EXISTS `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_no` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `company_date` date NOT NULL,
  `company_description` varchar(250) NOT NULL,
  `company_status` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `company_no`, `company_name`, `company_date`, `company_description`, `company_status`, `created`, `created_by`, `updated`, `updated_by`) VALUES
(1, 1, 'Company A', '2014-07-21', 'Company Description goes  here', 1, '2015-05-01 16:29:29', 1, '2015-05-19 16:08:35', 1),
(3, 3, 'Company C', '2014-07-21', 'Company c description', 1, '2015-05-05 15:26:01', 1, '2015-05-19 16:08:50', 1),
(4, 4, 'Company D', '0000-00-00', 'Company D Description here', 1, '2015-05-01 16:29:29', 1, '2015-05-05 16:30:44', 1),
(6, 6, 'Company E', '0000-00-00', 'Company E description goes here. Company E description goes here. Company E description goes here. Company E description goes here. Company E description goes here. Company E description goes here. Company E description goes here. Company E descripti', 1, '2015-05-05 22:50:13', 1, '2015-05-05 16:50:13', 0),
(11, 7, 'Company E', '0000-00-00', 'Company E Description goes here.', 2, '2015-05-06 16:53:45', 1, '2015-05-20 11:40:52', 0),
(12, 8, 'Scubaland  Inc', '0000-00-00', 'Description of Scubaland', 1, '2015-05-07 22:49:02', 1, '2015-05-07 16:49:02', 0),
(13, 9, 'William business solutions', '2015-05-19', 'William business solutions Company description goes here. ', 1, '2015-05-19 22:19:22', 1, '2015-05-19 16:19:22', 0);

-- --------------------------------------------------------

--
-- Table structure for table `company_notes`
--

CREATE TABLE IF NOT EXISTS `company_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `notes_body` text NOT NULL,
  `notes_image_id` int(11) DEFAULT NULL,
  `notes_by` int(11) NOT NULL,
  `notify_user_id` varchar(100) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `company_notes`
--

INSERT INTO `company_notes` (`id`, `company_id`, `notes_body`, `notes_image_id`, `notes_by`, `notify_user_id`, `created`) VALUES
(29, 1, 'Company A Developments Notes', 0, 1, 'null', '2015-05-08 16:44:44'),
(30, 1, ' Company A another Notes', 0, 1, 'null', '2015-05-08 17:05:04'),
(31, 1, ' Notes from Mamun', 0, 7, 'null', '2015-05-08 17:05:56'),
(32, 1, ' test notes', 0, 1, 'null', '2015-05-08 21:52:08'),
(33, 1, 'Another Company A notes', 0, 1, 'null', '2015-05-12 21:43:32'),
(34, 3, ' company C Notes', 0, 1, 'null', '2015-05-15 18:07:18'),
(35, 1, ' This is a test notes for company A . this company note s is added by mamun. This company have some important projects. Every project will complete within 20 years. Within this time no note will be published. so this notes is published. this is an option to send notes to the company owner.', 0, 1, 'null', '2015-05-18 22:56:43'),
(36, 1, ' This is a test notes for company A . this company note s is added by mamun. This company have some important projects. Every project will complete within 20 years. Within this time no note will be published. so this notes is published. this is an option to send notes to the company owner.', 0, 7, 'null', '2015-05-18 22:58:52'),
(37, 1, 'File/image', 206, 1, '', '2015-05-20 22:23:03'),
(38, 1, 'File/image', 207, 1, '', '2015-05-20 22:23:12');

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `fid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) DEFAULT NULL COMMENT 'original image/filename',
  `filetype` varchar(20) DEFAULT NULL,
  `filesize` double DEFAULT NULL,
  `filepath` tinytext,
  `filename_custom` tinytext COMMENT 'document/filename by user',
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`fid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=213 ;

--
-- Dumping data for table `file`
--

INSERT INTO `file` (`fid`, `filename`, `filetype`, `filesize`, `filepath`, `filename_custom`, `created`, `updated`, `uid`) VALUES
(50, 'agensi.jpg', 'image/jpeg', 82.88, 'D:/wamp/www/wbs/uploads/request/document/agensi.jpg', NULL, 1407824520, 1407840705, 1),
(51, 'bukhari_n.jpg', 'image/jpeg', 85.18, 'D:/wamp/www/wbs/uploads/request/document/bukhari_n.jpg', NULL, 1407828337, NULL, 1),
(52, 'customer.png', 'image/png', 12.01, 'D:/wamp/www/wbs/uploads/request/document/customer.png', NULL, 1407828337, NULL, 1),
(53, 'basa.png', 'image/png', 6.54, 'D:/wamp/www/wbs/uploads/request/document/basa.png', NULL, NULL, 1407837106, 1),
(54, 'bcs-prep.png', 'image/png', 50.23, 'D:/wamp/www/wbs/uploads/request/document/bcs-prep.png', NULL, NULL, 1407837106, 1),
(55, 'amarsoft.jpg', 'image/jpeg', 180.02, 'D:/wamp/www/wbs/uploads/request/document/amarsoft.jpg', NULL, NULL, 1407840705, 1),
(56, 'capitalmarket3.jpg', 'image/jpeg', 13.91, 'D:/wamp/www/wbs/uploads/request/document/capitalmarket3.jpg', NULL, 1408696306, NULL, 1),
(57, 'bd-it2.png', 'image/png', 1000.32, 'D:/wamp/www/wbs/uploads/request/document/bd-it2.png', NULL, 1408696383, NULL, 1),
(152, 'basa4.png', 'image/png', 6.54, 'D:/wamp/www/wbs/uploads/notes/images/basa4.png', NULL, 1409134832, NULL, 1),
(153, 'bcs-prep4.png', 'image/png', 50.23, 'D:/wamp/www/wbs/uploads/notes/images/bcs-prep4.png', NULL, 1409134841, NULL, 1),
(154, 'bukhari_n3.jpg', 'image/jpeg', 85.18, 'D:/wamp/www/wbs/uploads/notes/images/bukhari_n3.jpg', NULL, 1409136116, NULL, 1),
(155, '3towers3.jpg', 'image/jpeg', 68.91, 'D:/wamp/www/wbs/uploads/notes/images/3towers3.jpg', NULL, 1409136774, NULL, 1),
(156, 'bmw-s1000-rr-hp4-02-1408121.jpg', 'image/jpeg', 185.38, 'D:/wamp/www/wbs/uploads/notes/images/bmw-s1000-rr-hp4-02-1408121.jpg', NULL, 1409137926, NULL, 1),
(157, 'bukhari_n4.jpg', 'image/jpeg', 85.18, 'D:/wamp/www/wbs/uploads/notes/images/bukhari_n4.jpg', NULL, 1409138531, NULL, 1),
(158, 'cc2.jpg', 'image/jpeg', 2441.1, 'D:/wamp/www/wbs/uploads/notes/images/cc2.jpg', NULL, 1409138569, NULL, 1),
(159, 'Boston+Marathon.jpg', 'image/jpeg', 45.37, 'D:/wamp/www/wbs/uploads/notes/images/Boston+Marathon.jpg', NULL, 1409138607, NULL, 1),
(160, 'bd.png', 'image/png', 354.22, 'D:/wamp/www/wbs/uploads/notes/images/bd.png', NULL, 1409138619, NULL, 1),
(161, 'bukhari_n5.jpg', 'image/jpeg', 85.18, 'D:/wamp/www/wbs/uploads/notes/images/bukhari_n5.jpg', NULL, 1409138670, NULL, 1),
(162, '3towers4.jpg', 'image/jpeg', 68.91, 'D:/wamp/www/wbs/uploads/notes/images/3towers4.jpg', NULL, 1409138700, NULL, 1),
(163, 'bd-it2.png', 'image/png', 1000.32, 'D:/wamp/www/wbs/uploads/notes/images/bd-it2.png', NULL, 1409138774, NULL, 1),
(164, 'basa5.png', 'image/png', 6.54, 'D:/wamp/www/wbs/uploads/notes/images/basa5.png', NULL, 1409138816, NULL, 1),
(165, 'bukhari_n6.jpg', 'image/jpeg', 85.18, 'D:/wamp/www/wbs/uploads/notes/images/bukhari_n6.jpg', NULL, 1409138841, NULL, 1),
(166, 'bukhari_n7.jpg', 'image/jpeg', 85.18, 'D:/wamp/www/wbs/uploads/notes/images/bukhari_n7.jpg', NULL, 1409140408, NULL, 1),
(167, 'Boston+Marathon1.jpg', 'image/jpeg', 45.37, 'D:/wamp/www/wbs/uploads/notes/images/Boston+Marathon1.jpg', NULL, 1409140550, NULL, 1),
(168, 'bmw-s1000-rr-hp4-02-1408122.jpg', 'image/jpeg', 185.38, 'D:/wamp/www/wbs/uploads/notes/images/bmw-s1000-rr-hp4-02-1408122.jpg', NULL, 1409140583, NULL, 1),
(169, 'bukhari_n8.jpg', 'image/jpeg', 85.18, 'D:/wamp/www/wbs/uploads/notes/images/bukhari_n8.jpg', NULL, 1409140748, NULL, 1),
(170, 'barchart2.gif', 'image/gif', 3.49, 'D:/wamp/www/wbs/uploads/notes/images/barchart2.gif', NULL, 1409646555, NULL, 1),
(171, 'home15.png', 'image/png', 24.8, 'D:/wamp/www/wbs/uploads/notes/images/home15.png', NULL, 1409649555, NULL, NULL),
(172, 'srshome1.png', 'image/png', 30.76, 'D:/wamp/www/wbs/uploads/notes/images/srshome1.png', NULL, 1409649733, NULL, NULL),
(173, 'home16.png', 'image/png', 24.8, 'D:/wamp/www/wbs/uploads/notes/images/home16.png', NULL, 1409650122, NULL, 7),
(174, 'bmw-s1000-rr-hp4-02-1408123.jpg', 'image/jpeg', 185.38, 'D:/wamp/www/wbs/uploads/request/document/bmw-s1000-rr-hp4-02-1408123.jpg', NULL, NULL, 2014, NULL),
(175, 'bukhari_n9.jpg', 'image/jpeg', 85.18, 'D:/wamp/www/wbs/uploads/notes/images/bukhari_n9.jpg', NULL, 2014, NULL, 1),
(176, 'bcs-prep4.png', 'image/png', 50.23, 'D:/wamp/www/wbs/uploads/request/document/bcs-prep4.png', NULL, NULL, 2014, 7),
(177, 'barchart4.gif', 'image/gif', 3.49, 'D:/wamp/www/wbs/uploads/request/document/barchart4.gif', NULL, NULL, 2014, 7),
(178, 'bcs-prep5.png', 'image/png', 50.23, 'D:/wamp/www/wbs/uploads/notes/images/bcs-prep5.png', NULL, 2014, NULL, 1),
(179, 'bcs-prep6.png', 'image/png', 50.23, 'D:/wamp/www/wbs/uploads/notes/images/bcs-prep6.png', NULL, 2014, NULL, 1),
(180, 'bcs-prep7.png', 'image/png', 50.23, 'D:/wamp/www/wbs/uploads/notes/images/bcs-prep7.png', NULL, 2014, NULL, 1),
(181, 'basa6.png', 'image/png', 6.54, 'D:/wamp/www/wbs/uploads/notes/images/basa6.png', NULL, 2014, NULL, 7),
(182, 'horncastle-arena.png', 'image/png', 104.62, 'D:/wamp/www/wbs/uploads/request/document/horncastle-arena.png', NULL, NULL, 2014, 7),
(183, 'phase.PNG', 'image/png', 191.64, 'D:/wamp/www/wbs/uploads/request/document/phase.PNG', NULL, 2014, NULL, 1),
(184, 'ProposedManagementSystemScope.pdf', 'application/pdf', 13015.4, 'D:/wamp/www/wbs/uploads/request/document/ProposedManagementSystemScope.pdf', NULL, 2014, NULL, 1),
(185, 'html_icon.jpg', 'image/jpeg', 3.48, 'D:/wamp/www/srs/uploads/request/document/html_icon.jpg', NULL, 2014, 2014, 1),
(186, 'db_inventory_info.txt', 'text/plain', 0.11, 'D:/wamp/www/srs/uploads/notes/images/db_inventory_info.txt', NULL, 2015, NULL, 7),
(187, 'mychair.jpg', 'image/jpeg', 34.55, 'D:/wamp/www/srs/uploads/notes/images/mychair.jpg', NULL, 2015, NULL, 7),
(188, 'mychair1.jpg', 'image/jpeg', 34.55, 'D:/wamp/www/srs/uploads/notes/images/mychair1.jpg', NULL, 2015, NULL, 7),
(189, 'sultan_user_pass.txt', 'text/plain', 0.07, 'D:/wamp/www/srs/uploads/notes/images/sultan_user_pass.txt', NULL, 2015, NULL, 7),
(190, 'sultan_user_pass1.txt', 'text/plain', 0.07, 'D:/wamp/www/srs/uploads/notes/images/sultan_user_pass1.txt', NULL, 2015, NULL, 7),
(191, 'note.txt', 'text/plain', 2.17, 'D:/wamp/www/srs/uploads/request/document/note.txt', NULL, NULL, 2015, 1),
(192, 'razor.png', 'image/png', 31.35, 'D:/wamp/www/tmsnew/uploads/notes/images/razor.png', NULL, 2015, NULL, 1),
(193, 'razor1.png', 'image/png', 31.35, 'D:/wamp/www/tmsnew/uploads/notes/images/razor1.png', NULL, 2015, NULL, 1),
(194, 'request.php', 'application/octet-st', 33.04, 'D:/wamp/www/tmsnew/uploads/notes/images/request.php', NULL, 2015, NULL, 1),
(195, 'imgo-date.jpg', 'image/jpeg', 85.68, 'D:/wamp/www/tmsnew/uploads/notes/images/imgo-date.jpg', NULL, 2015, NULL, 1),
(196, 'imgo-date1.jpg', 'image/jpeg', 85.68, 'D:/wamp/www/tmsnew/uploads/notes/images/imgo-date1.jpg', NULL, 2015, NULL, 1),
(197, 'Maintenance_pages.pdf', 'application/pdf', 2608.8, 'D:/wamp/www/tmsnew/uploads/notes/images/Maintenance_pages.pdf', NULL, 2015, NULL, 1),
(198, 'razor2.png', 'image/png', 31.35, 'D:/wamp/www/tmsnew/uploads/notes/images/razor2.png', NULL, 2015, NULL, 1),
(199, 'Maintenance_pages1.pdf', 'application/pdf', 2608.8, 'D:/wamp/www/tmsnew/uploads/notes/images/Maintenance_pages1.pdf', NULL, 2015, NULL, 1),
(200, 'imgo-date2.jpg', 'image/jpeg', 85.68, 'D:/wamp/www/tmsnew/uploads/notes/images/imgo-date2.jpg', NULL, 2015, NULL, 1),
(201, 'Maintenance_pages2.pdf', 'application/pdf', 2608.8, 'D:/wamp/www/tmsnew/uploads/notes/images/Maintenance_pages2.pdf', NULL, 2015, NULL, 1),
(202, 'imgo-date3.jpg', 'image/jpeg', 85.68, 'D:/wamp/www/tmsnew/uploads/notes/images/imgo-date3.jpg', NULL, 2015, NULL, 1),
(203, 'imgo-date4.jpg', 'image/jpeg', 85.68, 'D:/wamp/www/tmsnew/uploads/notes/images/imgo-date4.jpg', NULL, 2015, NULL, 1),
(204, 'Maintenance_pages3.pdf', 'application/pdf', 2608.8, 'D:/wamp/www/tmsnew/uploads/notes/images/Maintenance_pages3.pdf', NULL, 2015, NULL, 1),
(205, 'imgo-date5.jpg', 'image/jpeg', 85.68, 'D:/wamp/www/tmsnew/uploads/notes/images/imgo-date5.jpg', NULL, 2015, NULL, 1),
(206, 'imgo-date6.jpg', 'image/jpeg', 85.68, 'D:/wamp/www/tmsnew/uploads/notes/images/imgo-date6.jpg', NULL, 2015, NULL, 1),
(207, 'Maintenance_Schedule_Scope.pdf', 'application/pdf', 333.82, 'D:/wamp/www/tmsnew/uploads/notes/images/Maintenance_Schedule_Scope.pdf', NULL, 2015, NULL, 1),
(208, 'imgo-date7.jpg', 'image/jpeg', 85.68, 'D:/wamp/www/tmsnew/uploads/notes/images/imgo-date7.jpg', NULL, 2015, NULL, 1),
(209, 'Maintenance_pages4.pdf', 'application/pdf', 2608.8, 'D:/wamp/www/tmsnew/uploads/notes/images/Maintenance_pages4.pdf', NULL, 2015, NULL, 1),
(210, 'imgo-date.jpg', 'image/jpeg', 85.68, 'D:/wamp/www/tmsnew/uploads/request/document/imgo-date.jpg', NULL, NULL, 2015, 1),
(211, 'RazorFlow_Customization_Project_v1-2_2.pdf', 'application/pdf', 1028.22, 'D:/wamp/www/tmsnew/uploads/request/document/RazorFlow_Customization_Project_v1-2_2.pdf', NULL, NULL, 2015, 1),
(212, 'RazorFlow_Customization_Project_v1-2_21.pdf', 'application/pdf', 1028.22, 'D:/wamp/www/tmsnew/uploads/request/document/RazorFlow_Customization_Project_v1-2_21.pdf', NULL, NULL, 2015, 1);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE IF NOT EXISTS `notes` (
  `nid` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` int(11) NOT NULL,
  `notes_body` text NOT NULL,
  `notes_image_id` int(11) DEFAULT NULL,
  `notes_by` int(11) NOT NULL,
  `notify_user_id` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`nid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=349 ;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`nid`, `request_id`, `notes_body`, `notes_image_id`, `notes_by`, `notify_user_id`, `created`) VALUES
(332, 61, 'abc', 0, 7, '7,10', '2015-01-26 23:05:15'),
(334, 61, 'the quick', 0, 7, 'null', '2015-01-26 23:14:29'),
(335, 61, ' brown fox', 0, 1, 'null', '2015-01-26 23:51:46'),
(336, 61, 'jomps over', 0, 1, '9,4,7,10', '2015-01-26 23:52:14'),
(337, 60, ' aa', 0, 1, 'null', '2015-02-10 05:31:09'),
(338, 60, ' abc', 0, 1, 'null', '2015-02-10 19:14:54'),
(339, 60, ' A?B?C', 0, 1, 'null', '2015-02-12 00:35:04'),
(340, 60, ' A+B+C', 0, 1, 'null', '2015-02-12 00:35:49'),
(341, 60, ' C+D+', 0, 1, 'null', '2015-02-12 00:36:50'),
(342, 60, ' bb', 0, 1, 'null', '2015-02-12 01:26:54'),
(343, 66, ' abc', 0, 1, 'null', '2015-05-18 18:17:07'),
(344, 66, ' def', 0, 7, 'null', '2015-05-18 19:03:51'),
(347, 66, '', 198, 1, '', '2015-05-20 21:56:21'),
(348, 66, '', 199, 1, '', '2015-05-20 22:08:25');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `pid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL COMMENT 'role id',
  `perm` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'permission name: load add, loan delete',
  `perm_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'permission url:loan/add or loan/update etc',
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=290 ;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`pid`, `rid`, `perm`, `perm_url`) VALUES
(231, 1, 'user user_detail', 'user/user_detail'),
(230, 1, 'user user_list', 'user/user_list'),
(229, 1, 'user user_delete', 'user/user_delete'),
(228, 1, 'user user_update', 'user/user_update'),
(227, 1, 'user user_add', 'user/user_add');

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_no` int(11) NOT NULL,
  `project_date` date NOT NULL,
  `company_id` int(11) NOT NULL,
  `project_name` varchar(100) NOT NULL,
  `project_description` varchar(250) NOT NULL,
  `project_status` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `project_no`, `project_date`, `company_id`, `project_name`, `project_description`, `project_status`, `created`, `created_by`, `updated`, `updated_by`) VALUES
(1, 1, '0000-00-00', 3, 'Horncastle Development', 'Horncastle Development is the new management System for the fencing.', 1, '2014-07-21 12:12:02', 1, '2014-12-17 20:56:35', 1),
(2, 2, '2015-05-19', 4, 'William business web', 'William business website is under construnction but coming soon', 1, '2014-07-21 12:21:33', 1, '2015-05-19 23:29:11', 1),
(3, 3, '0000-00-00', 3, 'Super Channel', 'Super channel High Definition project', 2, '0000-00-00 00:00:00', 1, '2014-12-17 20:54:48', 1),
(6, 6, '0000-00-00', 1, 'abc', 'abc bvc', 1, '2015-03-01 17:21:00', 1, '2015-05-18 18:10:02', 1),
(7, 4, '0000-00-00', 1, 'another test project', 'another test  project info description', 2, '2014-12-01 20:49:55', 7, '2015-05-11 17:20:15', 1),
(9, 6, '0000-00-00', 1, 'circket board', 'cricket score  board ', 1, '2014-12-16 00:53:00', 1, '2014-12-15 17:53:00', 0),
(11, 7, '0000-00-00', 1, 'cricked field', 'cricked field', 1, '2014-12-16 00:59:19', 1, '2014-12-15 17:59:19', 0),
(15, 8, '2015-05-19', 13, 'Test Project', 'Test Project Description', 1, '2015-05-19 23:37:35', 1, '2015-05-19 17:37:35', 0);

-- --------------------------------------------------------

--
-- Table structure for table `project_notes`
--

CREATE TABLE IF NOT EXISTS `project_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `notes_body` text NOT NULL,
  `notes_image_id` int(11) DEFAULT NULL,
  `notes_by` int(11) NOT NULL,
  `notify_user_id` varchar(100) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

--
-- Dumping data for table `project_notes`
--

INSERT INTO `project_notes` (`id`, `project_id`, `notes_body`, `notes_image_id`, `notes_by`, `notify_user_id`, `created`) VALUES
(22, 9, 'abc', 0, 7, '9,7', '2015-02-02 19:16:51'),
(23, 9, 'abc', 0, 7, '9,7', '2015-02-02 19:19:03'),
(24, 9, 'abc', 0, 7, '9,7', '2015-02-02 19:19:53'),
(25, 9, ' new project notes', 0, 7, '1,9,8', '2015-02-02 19:32:28'),
(26, 9, ' asssss', 0, 1, '10', '2015-02-02 20:07:15'),
(27, 9, 'jjh jgjkgj ', 0, 1, '10', '2015-02-02 20:07:24'),
(28, 9, 'gfdgd fhfhf hfh ', 0, 1, 'null', '2015-02-02 20:07:40'),
(29, 1, ' Test Project Developments Notes', 0, 1, 'null', '2015-05-08 16:44:44'),
(30, 1, ' Another Notes', 0, 1, 'null', '2015-05-08 17:05:04'),
(31, 1, ' Notes from Mamun', 0, 7, 'null', '2015-05-08 17:05:56'),
(32, 1, ' test notes', 0, 1, 'null', '2015-05-08 21:52:08'),
(33, 6, '', 192, 1, '', '2015-05-15 12:32:30'),
(34, 3, '', 193, 1, '', '2015-05-15 12:33:35'),
(35, 7, ' This is a text notes', 0, 1, 'null', '2015-05-20 21:34:31'),
(44, 1, '', 208, 1, '', '2015-05-20 22:23:51'),
(45, 1, '', 209, 1, '', '2015-05-20 22:23:56');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE IF NOT EXISTS `request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_no` int(11) NOT NULL,
  `request_date` date NOT NULL,
  `request_title` varchar(100) NOT NULL,
  `request_description` varchar(250) NOT NULL,
  `document_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `assign_manager_id` varchar(110) NOT NULL,
  `assign_developer_id` varchar(110) NOT NULL,
  `priority` int(1) NOT NULL,
  `estimated_completion` date NOT NULL,
  `request_status` tinyint(1) NOT NULL,
  `request_viewed_byuser` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=67 ;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`id`, `request_no`, `request_date`, `request_title`, `request_description`, `document_id`, `image_id`, `project_id`, `company_id`, `assign_manager_id`, `assign_developer_id`, `priority`, `estimated_completion`, `request_status`, `request_viewed_byuser`, `created`, `created_by`, `updated`, `updated_by`) VALUES
(1, 1, '2014-07-21', 'Bug', 'bug in development system', 0, 0, 1, 3, '9', '10', 2, '2014-07-31', 2, '1, 4, 7', '2014-07-21 14:20:31', 1, '2015-01-19 16:44:05', 1),
(2, 4, '2014-07-23', 'Logo change', 'Logo change of support system', 0, 0, 1, 3, '0', '0', 2, '2014-07-25', 2, '', '2014-07-23 15:28:08', 1, '2014-12-17 14:17:59', 1),
(7, 5, '2014-08-11', 'New bug', 'New bug description', 0, 0, 3, 3, '7', '4', 1, '2014-08-13', 2, '', '0000-00-00 00:00:00', 1, '2014-12-17 14:17:59', 1),
(10, 6, '2014-08-12', 'docum test', 'cd df  ', 50, 55, 3, 3, '7', '8', 2, '2014-08-13', 2, '', '0000-00-00 00:00:00', 1, '2014-12-17 14:17:59', 7),
(11, 7, '2014-08-12', 'file and image', 'the project page meeds to have the following filters. open/closed most open requests because if we have lost of projects they need to be able to be sorted', 53, 54, 4, 1, '7', '4', 1, '2014-08-20', 1, '7,1', '0000-00-00 00:00:00', 1, '2015-04-23 19:13:37', 1),
(12, 8, '2014-08-13', 'Launch project', 'horncastle project development', 0, 0, 1, 3, '7', '4', 2, '2014-08-20', 2, '', '0000-00-00 00:00:00', 1, '2014-12-17 14:17:59', 0),
(14, 10, '2014-09-02', 'On the overview page i do not think the update your details should take up so much space, it should ', 'On the overview page i do not think the update your details should take up so much space, it should be a button next to log out on the top right corner. This is my opinion. Thanks', 174, 0, 3, 3, '9', '10', 2, '2014-09-09', 1, '', '2014-09-02 10:25:18', 7, '2014-12-17 14:17:59', 0),
(15, 11, '2014-09-15', 'aaa', 'aaaaaa', 176, 177, 1, 3, '9', '10', 2, '2014-09-25', 1, '1', '2014-09-15 10:19:10', 7, '2015-04-23 20:44:45', 1),
(16, 12, '2014-09-16', 'bbb', 'bbbb', 0, 0, 1, 3, '7', '10', 1, '2015-09-10', 1, '7', '2014-09-16 10:55:57', 7, '2015-01-20 01:41:32', 7),
(17, 13, '2014-09-17', 'request to test', 'request to test from project details', 0, 0, 2, 4, '9', '10', 2, '2014-09-25', 2, '', '2014-09-17 11:21:00', 1, '2014-12-17 14:17:59', 1),
(18, 14, '2014-09-19', 'from project', 'Request add from Project', 0, 0, 2, 4, '7', '8', 2, '2014-09-09', 1, '7', '2014-09-19 23:21:01', 7, '2015-01-20 01:39:30', 7),
(19, 15, '2014-09-19', 'aa', 'aaa', 0, 0, 3, 3, '7', '10', 2, '2014-09-10', 1, '7', '2014-09-19 23:23:10', 7, '2015-01-20 01:39:48', 7),
(20, 16, '2014-09-19', 'bb', 'bbb', 0, 0, 6, 1, '9', '10', 2, '2014-09-25', 1, '1', '2014-09-19 23:27:44', 7, '2015-04-23 18:30:04', 1),
(21, 17, '2014-09-19', 'bb', 'bbb', 0, 0, 6, 1, '9', '10', 2, '2014-09-25', 1, '1', '2014-09-19 23:29:20', 7, '2015-04-23 18:51:16', 1),
(22, 18, '2014-09-19', 'cc', 'cc', 0, 0, 6, 1, '9', '10', 2, '2014-09-10', 1, '1', '2014-09-19 23:29:47', 7, '2015-04-23 19:34:08', 1),
(23, 19, '2014-09-19', 'ccvb', 'cc', 0, 0, 6, 1, '9', '10', 2, '2014-09-10', 1, '1', '2014-11-18 18:43:07', 7, '2015-04-23 19:29:02', 1),
(24, 20, '2014-09-19', 'ccvb bbc', 'cc', 0, 0, 6, 1, '9', '10', 2, '2014-09-10', 1, '1', '2014-11-18 18:43:32', 7, '2015-04-23 19:33:00', 1),
(25, 21, '2014-09-02', 'On the overview page', 'On the overview page i do not think the update your details should take up so much space, it should be a button next to log out on the top right corner. This is my opinion. Thanks', 0, 182, 3, 3, '9', '10', 2, '2014-09-09', 1, '', '2014-11-18 19:00:54', 7, '2014-12-17 14:17:59', 7),
(26, 22, '2014-12-01', 'request 100', 'Request 100 description ', 0, 0, 2, 4, '7', '10', 2, '2014-12-24', 1, '7', '2014-12-01 20:14:11', 7, '2015-01-20 01:39:54', 7),
(27, 23, '2014-12-01', 'request 110', 'Description of request 110', 0, 0, 2, 4, '9', '8', 2, '2014-12-18', 1, '', '2014-12-01 20:43:12', 7, '2014-12-17 14:17:59', 0),
(28, 24, '2014-12-01', 'sdfs', 'fdsfdsaf', 0, 0, 7, 1, '9', '8', 2, '2014-12-26', 1, '8', '2014-12-01 20:51:20', 7, '2015-03-12 23:15:33', 8),
(29, 25, '2014-12-09', 'fancyfox', 'test fancy box', 0, 183, 1, 3, '9', '10', 2, '2014-12-18', 1, '', '2014-12-09 19:06:26', 1, '2014-12-17 14:17:59', 0),
(30, 26, '2014-12-11', 'test request ', 'test request ', 0, 0, 1, 3, '7', '10', 2, '2014-10-11', 1, '7', '2014-10-11 01:19:27', 10, '2015-02-03 15:58:06', 7),
(31, 27, '2014-12-11', 'sdfsdf', 'sdfdsf', 0, 0, 1, 3, '9', '10', 2, '2014-12-11', 1, '', '2014-12-11 01:22:23', 10, '2014-12-17 14:17:59', 0),
(32, 28, '2014-12-11', 'new requ', 'new requ', 0, 0, 1, 3, '9', '10', 1, '2014-12-18', 1, '', '2014-12-11 01:26:48', 10, '2014-12-17 14:17:59', 0),
(33, 29, '2014-12-11', 'another requ', 'anther requ dewc', 0, 0, 1, 3, '9', '10', 2, '2014-12-18', 1, '', '2014-12-11 01:28:00', 10, '2014-12-17 14:17:59', 1),
(34, 30, '2014-12-11', 'upload large file', 'upload large file', 184, 0, 1, 3, '9', '10', 2, '2014-12-11', 1, '1', '2014-12-11 02:15:53', 1, '2015-04-23 19:17:03', 1),
(35, 31, '2014-12-18', 'crik ok', 'crik', 0, 0, 11, 1, '9', '10', 2, '2014-12-18', 1, '1', '2014-12-18 02:04:06', 1, '2015-04-23 19:16:18', 1),
(36, 32, '2014-12-18', 'only company', 'only company', 0, 0, 0, 1, '9', '10', 2, '2014-12-26', 2, '1,8,10', '2014-12-18 22:54:44', 1, '2015-03-12 22:56:01', 10),
(37, 33, '2014-12-19', 'fnc box', 'fnc box', 0, 185, 11, 1, '9', '10', 2, '2014-12-20', 1, '1', '2014-12-19 22:47:59', 1, '2015-05-22 22:14:23', 1),
(39, 35, '2015-01-09', 'edit task test company', 'edit task test company', 0, 0, 9, 1, '9', '10', 2, '2015-01-16', 1, '1', '2015-01-09 19:17:47', 1, '2015-04-23 19:40:47', 1),
(40, 36, '2015-01-12', 'multiple manager', 'multiple manager', 0, 0, 9, 1, '9,7', '10,8', 2, '2015-01-22', 2, '7,1', '2015-01-12 20:24:31', 1, '2015-02-13 12:23:51', 1),
(41, 37, '2015-01-12', 'aaaa', 'adsfdsfsfd dsfsdf', 0, 0, 11, 1, '9,7', '10,8', 2, '2015-01-15', 2, '7,1', '2015-01-12 23:48:28', 1, '2015-02-13 12:23:09', 1),
(42, 38, '2015-01-12', 'aaaa', 'adsfdsfsfd dsfsdf', 0, 0, 11, 1, '9,7', '10,8', 2, '2015-01-15', 1, '7,10,8,1', '2015-01-12 23:51:27', 1, '2015-04-23 19:17:34', 1),
(45, 41, '2015-01-12', 'cccc', 'dfdsfsdf', 0, 0, 1, 3, '9,7', '4', 2, '2015-01-20', 1, '7', '2015-01-12 23:55:27', 1, '2015-01-20 01:40:59', 7),
(46, 42, '2015-01-12', 'cccc', 'dfdsfsdf', 0, 0, 1, 3, '9,7', '4', 2, '2015-01-20', 1, '7', '2015-01-12 23:58:34', 1, '2015-01-20 01:40:55', 7),
(47, 43, '2015-01-12', 'ccccc', 'ddssd dfdfd', 0, 0, 11, 1, '9,7', '0,10', 2, '2015-01-29', 1, '7', '2015-01-13 00:00:20', 1, '2015-01-20 01:40:52', 7),
(48, 44, '2015-01-13', 'asdsad', 'xczxczxc', 0, 0, 9, 1, '9,7', '0', 2, '2015-01-27', 2, '7,1', '2015-01-13 00:06:09', 1, '2015-02-13 12:24:20', 1),
(49, 45, '2015-01-13', 'gfdgfd', 'gfdg dfbgfdg', 0, 0, 9, 1, '9,7', '0', 2, '2015-01-20', 1, '7', '2015-01-13 00:09:06', 1, '2015-01-20 01:40:45', 7),
(50, 46, '2015-01-13', 'dsfdsf', 'dsfdsfdsf', 0, 0, 9, 1, '9', '4,10,8', 2, '2015-01-20', 1, '7,1', '2015-01-13 00:23:09', 1, '2015-04-23 19:28:07', 1),
(51, 47, '2015-01-13', 'dsfdsf', 'dsfdsfdsf', 0, 0, 9, 1, '9', '4,10,8', 2, '2015-01-20', 2, '7,1', '2015-01-13 00:24:56', 1, '2015-02-13 12:24:36', 1),
(52, 48, '2015-01-13', 'my final task', 'my final task', 0, 0, 9, 1, '9,7', '4,10,8', 2, '2015-01-27', 1, '7,1', '2015-01-13 00:27:20', 1, '2015-04-23 18:27:30', 1),
(53, 49, '2015-01-13', 'new task assign multiple user', 'new task assign multiple user', 0, 0, 2, 4, '9,7', '10,8', 2, '2015-01-21', 1, '7,1', '2015-01-13 18:47:02', 7, '2015-01-29 21:51:13', 1),
(54, 50, '2015-01-15', 'mulselect check all', 'mulselect check all select box', 0, 0, 1, 3, '7', '10,8', 2, '2015-01-21', 1, '7', '2015-01-15 23:07:58', 1, '2015-01-20 01:10:41', 7),
(56, 52, '2015-01-16', 'sdsd', 'sadsad', 0, 0, 9, 1, '9', '0', 2, '2015-01-13', 1, '9,1,7', '2015-01-16 23:51:12', 1, '2015-01-22 18:50:03', 7),
(58, 54, '2015-01-17', 'mmm', 'adfdsf', 212, 0, 9, 1, '9,7', '', 2, '2015-01-20', 1, '1,4,7', '2015-01-17 00:00:38', 1, '2015-05-22 22:04:42', 1),
(59, 55, '2015-01-20', 'new task', 'new', 0, 0, 2, 4, '7', '4', 2, '2015-01-29', 1, '1,7', '2015-01-20 02:17:07', 7, '2015-02-18 12:19:56', 7),
(61, 57, '2015-01-22', 'a new task', 'asdfsfdsf', 0, 0, 2, 4, '9,7', '10,8', 2, '2015-01-29', 2, '10,7,1', '2015-01-22 19:10:24', 7, '2015-02-04 10:59:17', 1),
(62, 58, '2015-01-23', 'Latest new task', 'Latest new task description', 0, 0, 9, 1, '7', '10', 2, '2015-01-30', 2, '7,1', '2015-01-23 21:46:31', 7, '2015-02-04 10:50:15', 1),
(63, 59, '2015-01-23', 'another new task', 'another new task', 0, 0, 2, 4, '7', '10', 2, '2015-01-30', 2, '7,1', '2015-01-23 22:13:36', 7, '2015-02-04 10:50:00', 1),
(65, 61, '2015-03-13', 'test date', 'test date', 191, 0, 9, 1, '7', '8', 2, '2015-03-12', 1, '8,1', '2015-03-13 00:42:18', 8, '2015-04-09 22:19:25', 1),
(66, 62, '2015-04-29', 'Tms New ', 'Tms New  Description', 211, 210, 9, 1, '9', '8', 2, '2015-04-30', 1, '1,7,', '2015-04-29 20:26:17', 1, '2015-05-22 23:19:56', 0);

-- --------------------------------------------------------

--
-- Table structure for table `task_hours`
--

CREATE TABLE IF NOT EXISTS `task_hours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `contractor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hour` int(3) NOT NULL,
  `minute` int(3) NOT NULL,
  `week_start_date` date NOT NULL,
  `note` varchar(200) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `task_hours`
--

INSERT INTO `task_hours` (`id`, `task_id`, `contractor_id`, `user_id`, `hour`, `minute`, `week_start_date`, `note`, `created`, `created_by`, `updated`, `updated_by`) VALUES
(1, 20, 1, 1, 1, 20, '2015-05-13', 'task notes', '2015-05-13 11:14:07', 1, '2015-05-13 11:14:45', 0),
(2, 21, 1, 1, 2, 40, '2015-05-07', 'task notes', '2015-05-13 11:14:20', 1, '2015-05-13 11:14:45', 0),
(3, 66, 7, 1, 3, 40, '2015-05-20', 'task finish', '2015-05-20 18:14:42', 1, '2015-05-20 12:14:42', 0),
(4, 66, 8, 1, 2, 50, '2015-05-20', 'task updated', '2015-05-20 18:15:35', 1, '2015-05-20 12:15:35', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `rid` tinyint(4) NOT NULL,
  `name` varchar(55) NOT NULL,
  `pass` varchar(120) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  `access` int(11) NOT NULL,
  `login` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `rid`, `name`, `pass`, `email`, `created`, `updated`, `access`, `login`, `status`) VALUES
(1, 1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin@gmail.com', 0, 1422264182, 0, 0, 1),
(4, 3, 'developer1', 'c71c7b77c1429b7a4d6824f04f258062', 'developer1@gmail.com', 1392354385, 1409646751, 0, 0, 1),
(7, 2, 'mamun', 'e10adc3949ba59abbe56e057f20f883e', 'mamun@gmail.com', 1392627368, 1422264136, 0, 0, 1),
(8, 3, 'zia', 'e10adc3949ba59abbe56e057f20f883e', 'ziaursami@gmail.com', 1393223217, 1407920444, 0, 0, 1),
(9, 2, 'alimul', 'e10adc3949ba59abbe56e057f20f883e', 'alimul@gmail.com', 1407920420, 1429774416, 0, 0, 1),
(10, 3, 'nurul', 'e10adc3949ba59abbe56e057f20f883e', 'nurulku02@gmail.com', 1407920479, 1422257654, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_roles`
--

CREATE TABLE IF NOT EXISTS `users_roles` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `rname` varchar(30) NOT NULL,
  `rdesc` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users_roles`
--

INSERT INTO `users_roles` (`rid`, `rname`, `rdesc`, `uid`) VALUES
(1, 'Admin', 'Managing the users', 0),
(2, 'Staff', 'Full admin rights over the system', 0),
(3, 'Developer', 'Limited access but can respond to support requests with their correspondence.', 0);
