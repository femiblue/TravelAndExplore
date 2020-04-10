-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2018 at 01:22 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `meirunas_travel`
--

-- --------------------------------------------------------

--
-- Table structure for table `dialogue_knowledge`
--

CREATE TABLE `dialogue_knowledge` (
  `Id` int(10) UNSIGNED NOT NULL,
  `DkFrom` varchar(255) NOT NULL DEFAULT '',
  `DkTo` varchar(255) NOT NULL DEFAULT '',
  `DkMessage` text CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `DkSent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DkRecd` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dialogue_knowledge`
--

INSERT INTO `dialogue_knowledge` (`Id`, `DkFrom`, `DkTo`, `DkMessage`, `DkSent`, `DkRecd`) VALUES
(1, '::1', 'TAEN-Agent', 'holiday', '2018-04-03 16:53:27', 0),
(2, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-03 16:53:27', 0),
(3, '::1', 'TAEN-Agent', 'flights to Amsterdam on 11/03/2018', '2018-04-03 16:55:07', 0),
(4, 'TAEN-Agent', '::1', 'I could not find any match for your search. Try stuffs like &quot;Flight from London to Amsterdam 11/03/2018&quot; or Hotels In Amsterdam', '2018-04-03 16:55:07', 0),
(5, '::1', 'TAEN-Agent', 'flights from London to Amsterdam 11/03/2018', '2018-04-03 16:58:53', 0),
(6, 'TAEN-Agent', '::1', 'Hmm, I can not understand that one. Start all over by clicking Wanted Holiday or Help button', '2018-04-03 16:58:53', 0),
(7, '::1', 'TAEN-Agent', 'holiday', '2018-04-03 16:59:26', 0),
(8, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-03 16:59:26', 0),
(9, '::1', 'TAEN-Agent', 'flights from London to Amsterdam on 11/03/2018', '2018-04-03 17:03:47', 0),
(10, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-03 17:03:47', 0),
(11, '::1', 'TAEN-Agent', 'result', '2018-04-03 17:04:24', 0),
(12, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-03 17:04:24', 0),
(13, '::1', 'TAEN-Agent', 'help', '2018-04-03 17:06:31', 0),
(14, 'TAEN-Agent', '::1', 'In which continent do you like to travel', '2018-04-03 17:06:31', 0),
(15, '::1', 'TAEN-Agent', 'europe', '2018-04-03 17:07:05', 0),
(16, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-03 17:07:05', 0),
(17, '::1', 'TAEN-Agent', 'help', '2018-04-03 17:07:17', 0),
(18, 'TAEN-Agent', '::1', 'Which environment do you prefer?', '2018-04-03 17:07:17', 0),
(19, '::1', 'TAEN-Agent', 'environment', '2018-04-03 17:08:16', 0),
(20, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-03 17:08:16', 0),
(21, '::1', 'TAEN-Agent', 'holiday', '2018-04-04 10:56:21', 0),
(22, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-04 10:56:21', 0),
(23, '::1', 'TAEN-Agent', 'xzczc', '2018-04-04 10:56:23', 0),
(24, 'TAEN-Agent', '::1', 'I could not find any match for your search. Try stuffs like &quot;Flight from London to Amsterdam 11/03/2018&quot; or Hotels In Amsterdam', '2018-04-04 10:56:23', 0),
(25, '::1', 'TAEN-Agent', 'sacas', '2018-04-04 10:56:25', 0),
(26, 'TAEN-Agent', '::1', 'Hmm, I can not understand that one. Start all over by clicking Wanted Holiday or Help button. Then try stuffs like \'hotel room offers in London\' or \'flying from New York to London on 29/03/2018\' ', '2018-04-04 10:56:25', 0),
(27, '::1', 'TAEN-Agent', 'holiday', '2018-04-04 11:26:03', 0),
(28, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-04 11:26:03', 0),
(29, '::1', 'TAEN-Agent', 'flight from london to amsterdam on 11/03/2018', '2018-04-04 11:26:39', 0),
(30, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-04 11:26:39', 0),
(31, '::1', 'TAEN-Agent', 'result', '2018-04-04 11:26:49', 0),
(32, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-04 11:26:49', 0),
(33, '::1', 'TAEN-Agent', 'holiday', '2018-04-04 11:37:28', 0),
(34, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-04 11:37:28', 0),
(35, '::1', 'TAEN-Agent', 'hello', '2018-04-04 11:37:33', 0),
(36, 'TAEN-Agent', '::1', 'I could not find any match for your search. Try stuffs like &quot;Flight from London to Amsterdam 11/03/2018&quot; or Hotels In Amsterdam', '2018-04-04 11:37:33', 0),
(37, '::1', 'TAEN-Agent', 'hello', '2018-04-04 11:37:39', 0),
(38, 'TAEN-Agent', '::1', 'Hmm, I can not understand that one. Start all over by clicking Wanted Holiday or Help button. Then try stuffs like \'hotel room offers in London\' or \'flying from New York to London on 29/03/2018\' ', '2018-04-04 11:37:40', 0),
(39, '::1', 'TAEN-Agent', 'holiday', '2018-04-04 12:01:35', 0),
(40, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-04 12:01:35', 0),
(41, '::1', 'TAEN-Agent', 'csacasd', '2018-04-04 12:01:37', 0),
(42, 'TAEN-Agent', '::1', 'I could not find any match for your search. Try stuffs like &quot;Flight from London to Amsterdam 11/03/2018&quot; or Hotels In Amsterdam', '2018-04-04 12:01:37', 0),
(43, '::1', 'TAEN-Agent', 'dhdhd', '2018-04-04 12:01:41', 0),
(44, 'TAEN-Agent', '::1', 'Hmm, I can not understand that one. Start all over by clicking Wanted Holiday or Help button. Then try stuffs like \'hotel room offers in London\' or \'flying from New York to London on 29/03/2018\' ', '2018-04-04 12:01:41', 0),
(45, '::1', 'TAEN-Agent', 'holiday', '2018-04-04 12:07:39', 0),
(46, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-04 12:07:39', 0),
(47, '::1', 'TAEN-Agent', 'csdcfs', '2018-04-04 12:07:41', 0),
(48, 'TAEN-Agent', '::1', 'I could not find any match for your search. Try stuffs like &quot;Flight from London to Amsterdam 11/03/2018&quot; or Hotels In Amsterdam', '2018-04-04 12:07:42', 0),
(49, '::1', 'TAEN-Agent', 'zcdd', '2018-04-04 12:07:44', 0),
(50, 'TAEN-Agent', '::1', 'I could not find any match for your search. Try stuffs like &quot;Flight from London to Amsterdam 11/03/2018&quot; or Hotels In Amsterdam', '2018-04-04 12:07:44', 0),
(51, '::1', 'TAEN-Agent', 'dcdsd', '2018-04-04 12:07:47', 0),
(52, 'TAEN-Agent', '::1', 'Hmm, I can not understand that one. Start all over by clicking Wanted Holiday or Help button. Then try stuffs like \'hotel room offers in London\' or \'flying from New York to London on 29/03/2018\' ', '2018-04-04 12:07:47', 0),
(53, '::1', 'TAEN-Agent', 'holiday', '2018-04-04 12:08:13', 0),
(54, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-04 12:08:13', 0),
(55, '::1', 'TAEN-Agent', 'dsnjdjd', '2018-04-04 12:08:16', 0),
(56, 'TAEN-Agent', '::1', 'I could not find any match for your search. Try stuffs like &quot;Flight from London to Amsterdam 11/03/2018&quot; or Hotels In Amsterdam', '2018-04-04 12:08:16', 0),
(57, '::1', 'TAEN-Agent', 'mnjkmoo', '2018-04-04 12:08:24', 0),
(58, 'TAEN-Agent', '::1', 'I could not find any match for your search. Try stuffs like &quot;Flight from London to Amsterdam 11/03/2018&quot; or Hotels In Amsterdam', '2018-04-04 12:08:24', 0),
(59, '::1', 'TAEN-Agent', 'b jhb', '2018-04-04 12:08:28', 0),
(60, 'TAEN-Agent', '::1', 'I could not find any match for your search. Try stuffs like &quot;Flight from London to Amsterdam 11/03/2018&quot; or Hotels In Amsterdam', '2018-04-04 12:08:28', 0),
(61, '::1', 'TAEN-Agent', 'dADW', '2018-04-04 12:08:44', 0),
(62, 'TAEN-Agent', '::1', 'Hmm, I can not understand that one. Start all over by clicking Wanted Holiday or Help button. Then try stuffs like \'hotel room offers in London\' or \'flying from New York to London on 29/03/2018\' ', '2018-04-04 12:08:44', 0),
(63, '::1', 'TAEN-Agent', 'help', '2018-04-04 12:09:04', 0),
(64, 'TAEN-Agent', '::1', 'In which continent do you like to travel', '2018-04-04 12:09:04', 0),
(65, '::1', 'TAEN-Agent', 'Europe', '2018-04-04 12:09:09', 0),
(66, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-04 12:09:09', 0),
(67, '::1', 'TAEN-Agent', 'help', '2018-04-04 12:09:12', 0),
(68, 'TAEN-Agent', '::1', 'Which environment do you prefer?', '2018-04-04 12:09:12', 0),
(69, '::1', 'TAEN-Agent', 'environment', '2018-04-04 12:09:24', 0),
(70, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-04 12:09:24', 0),
(71, '::1', 'TAEN-Agent', 'help', '2018-04-04 12:09:27', 0),
(72, 'TAEN-Agent', '::1', 'Which season do you like to have holidays in?', '2018-04-04 12:09:27', 0),
(73, '::1', 'TAEN-Agent', 'spring', '2018-04-04 12:09:38', 0),
(74, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-04 12:09:38', 0),
(75, '::1', 'TAEN-Agent', 'help', '2018-04-04 12:09:40', 0),
(76, 'TAEN-Agent', '::1', 'Which weather do you prefer?', '2018-04-04 12:09:40', 0),
(77, '::1', 'TAEN-Agent', 'winter', '2018-04-04 12:10:14', 0),
(78, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-04 12:10:14', 0),
(79, '::1', 'TAEN-Agent', 'help', '2018-04-04 12:10:17', 0),
(80, 'TAEN-Agent', '::1', 'Which date range?', '2018-04-04 12:10:17', 0),
(81, '::1', 'TAEN-Agent', '10/03/2018 to 11/03/2018', '2018-04-04 12:10:47', 0),
(82, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-04 12:10:47', 0),
(83, '::1', 'TAEN-Agent', 'help', '2018-04-04 12:10:51', 0),
(84, 'TAEN-Agent', '::1', 'Which price range for activities?', '2018-04-04 12:10:51', 0),
(85, '::1', 'TAEN-Agent', '10 - 50', '2018-04-04 12:11:13', 0),
(86, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-04 12:11:13', 0),
(87, '::1', 'TAEN-Agent', 'help', '2018-04-04 12:11:15', 0),
(88, 'TAEN-Agent', '::1', 'Which is your age group?', '2018-04-04 12:11:15', 0),
(89, '::1', 'TAEN-Agent', 'Young-adult', '2018-04-04 12:11:29', 0),
(90, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-04 12:11:29', 0),
(91, '::1', 'TAEN-Agent', 'help', '2018-04-04 12:11:31', 0),
(92, 'TAEN-Agent', '::1', 'Which activities are you expecting to have?', '2018-04-04 12:11:31', 0),
(93, '::1', 'TAEN-Agent', 'Bruges Day', '2018-04-04 12:11:43', 0),
(94, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-04 12:11:43', 0),
(95, '::1', 'TAEN-Agent', 'result', '2018-04-04 12:11:48', 0),
(96, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-04 12:11:48', 0),
(97, '::1', 'TAEN-Agent', 'result', '2018-04-04 12:13:09', 0),
(98, 'TAEN-Agent', '::1', 'No result to display. Click the Holiday or Help Button to start.', '2018-04-04 12:13:09', 0),
(99, '::1', 'TAEN-Agent', 'restart', '2018-04-04 12:13:18', 0),
(100, 'TAEN-Agent', '::1', 'Hmm, I can not understand that one. Start all over by clicking Wanted Holiday or Help button. Then try stuffs like \'hotel room offers in London\' or \'flying from New York to London on 29/03/2018\' ', '2018-04-04 12:13:18', 0),
(101, '::1', 'TAEN-Agent', 'restart', '2018-04-04 12:13:22', 0),
(102, 'TAEN-Agent', '::1', 'Hmm, I can not understand that one. Start all over by clicking Wanted Holiday or Help button. Then try stuffs like \'hotel room offers in London\' or \'flying from New York to London on 29/03/2018\' ', '2018-04-04 12:13:22', 0),
(103, '::1', 'TAEN-Agent', 'holiday', '2018-04-04 12:13:38', 0),
(104, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-04 12:13:38', 0),
(105, '::1', 'TAEN-Agent', 'flights from london to amsterdam 11/03/2018', '2018-04-04 12:14:00', 0),
(106, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-04 12:14:00', 0),
(107, '::1', 'TAEN-Agent', 'result', '2018-04-04 12:14:05', 0),
(108, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-04 12:14:05', 0),
(109, '::1', 'TAEN-Agent', 'holiday', '2018-04-04 12:55:43', 0),
(110, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-04 12:55:43', 0),
(111, '::1', 'TAEN-Agent', 'saSF', '2018-04-04 12:55:46', 0),
(112, 'TAEN-Agent', '::1', 'I could not find any match for your search. Try stuffs like &quot;Flight from London to Amsterdam 11/03/2018&quot; or Hotels In Amsterdam', '2018-04-04 12:55:46', 0),
(113, '::1', 'TAEN-Agent', 'DSCFDS', '2018-04-04 12:55:47', 0),
(114, 'TAEN-Agent', '::1', 'I could not find any match for your search. Try stuffs like &quot;Flight from London to Amsterdam 11/03/2018&quot; or Hotels In Amsterdam', '2018-04-04 12:55:47', 0),
(115, '::1', 'TAEN-Agent', 'SDSsd', '2018-04-04 12:55:48', 0),
(116, 'TAEN-Agent', '::1', 'I could not find any match for your search. Try stuffs like &quot;Flight from London to Amsterdam 11/03/2018&quot; or Hotels In Amsterdam', '2018-04-04 12:55:48', 0),
(117, '::1', 'TAEN-Agent', 'dfds', '2018-04-04 12:55:50', 0),
(118, 'TAEN-Agent', '::1', 'Hmm, I can not understand that one. Start all over by clicking Wanted Holiday or Help button. Then try stuffs like \'hotel room offers in London\' or \'flying from New York to London on 29/03/2018\' ', '2018-04-04 12:55:50', 0),
(119, '::1', 'TAEN-Agent', 'holiday', '2018-04-05 12:20:11', 0),
(120, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-05 12:20:11', 0),
(121, '::1', 'TAEN-Agent', 'flights from london to amsterdam 11/03/2018', '2018-04-05 12:20:32', 0),
(122, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-05 12:20:32', 0),
(123, '::1', 'TAEN-Agent', 'result', '2018-04-05 12:20:35', 0),
(124, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-05 12:20:35', 0),
(125, '::1', 'TAEN-Agent', 'holiday', '2018-04-05 12:25:17', 0),
(126, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-05 12:25:17', 0),
(127, '::1', 'TAEN-Agent', 'flights from london to amsterdam 11/03/2018', '2018-04-05 12:25:44', 0),
(128, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-05 12:25:44', 0),
(129, '::1', 'TAEN-Agent', 'result', '2018-04-05 12:25:48', 0),
(130, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-05 12:25:48', 0),
(131, '::1', 'TAEN-Agent', 'holiday', '2018-04-05 12:26:42', 0),
(132, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-05 12:26:42', 0),
(133, '::1', 'TAEN-Agent', 'flights from london to amsterdam 11/03/2018', '2018-04-05 12:27:01', 0),
(134, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-05 12:27:01', 0),
(135, '::1', 'TAEN-Agent', 'result', '2018-04-05 12:27:05', 0),
(136, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-05 12:27:05', 0),
(137, '::1', 'TAEN-Agent', 'holiday', '2018-04-05 12:27:49', 0),
(138, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-05 12:27:49', 0),
(139, '::1', 'TAEN-Agent', 'flights from london to amsterdam 11/03/2018 and hotel rooms in amsterdam', '2018-04-05 12:28:07', 0),
(140, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-05 12:28:07', 0),
(141, '::1', 'TAEN-Agent', 'result', '2018-04-05 12:28:10', 0),
(142, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-05 12:28:10', 0),
(143, '::1', 'TAEN-Agent', 'holiday', '2018-04-05 12:28:23', 0),
(144, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-05 12:28:24', 0),
(145, '::1', 'TAEN-Agent', 'flights from london to amsterdam 11/03/2018 and hotel rooms in amsterdam and Bruges Day activity in europe', '2018-04-05 12:29:10', 0),
(146, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-05 12:29:10', 0),
(147, '::1', 'TAEN-Agent', 'result', '2018-04-05 12:29:15', 0),
(148, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-05 12:29:15', 0),
(149, '::1', 'TAEN-Agent', 'holiday', '2018-04-05 12:31:09', 0),
(150, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-05 12:31:09', 0),
(151, '::1', 'TAEN-Agent', 'flights from london to amsterdam 11/03/2018 and hotel rooms in amsterdam and Bruges Day activity in europe', '2018-04-05 12:31:11', 0),
(152, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-05 12:31:11', 0),
(153, '::1', 'TAEN-Agent', 'result', '2018-04-05 12:31:13', 0),
(154, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-05 12:31:13', 0),
(155, '::1', 'TAEN-Agent', 'holiday', '2018-04-05 12:32:20', 0),
(156, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-05 12:32:20', 0),
(157, '::1', 'TAEN-Agent', 'flights from london to amsterdam 11/03/2018 and hotel rooms in amsterdam and Bruges Day activity in europe', '2018-04-05 12:32:22', 0),
(158, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-05 12:32:22', 0),
(159, '::1', 'TAEN-Agent', 'holiday', '2018-04-05 12:42:33', 0),
(160, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-05 12:42:33', 0),
(161, '::1', 'TAEN-Agent', 'flights from london to amsterdam 11/03/2018 and hotel rooms in amsterdam and Bruges Day activity in europe', '2018-04-05 12:42:35', 0),
(162, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-05 12:42:35', 0),
(163, '::1', 'TAEN-Agent', 'holiday', '2018-04-05 12:44:35', 0),
(164, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-05 12:44:35', 0),
(165, '::1', 'TAEN-Agent', 'flights from london to amsterdam 11/03/2018 and hotel rooms in amsterdam and Bruges Day activity in europe', '2018-04-05 12:46:00', 0),
(166, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-05 12:46:00', 0),
(167, '::1', 'TAEN-Agent', 'holiday', '2018-04-05 12:46:54', 0),
(168, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-05 12:46:54', 0),
(169, '::1', 'TAEN-Agent', 'flights from london to amsterdam 11/03/2018 and hotel rooms in amsterdam and Bruges Day activity in europe', '2018-04-05 12:46:56', 0),
(170, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-05 12:46:56', 0),
(171, '::1', 'TAEN-Agent', 'holiday', '2018-04-05 12:56:08', 0),
(172, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-05 12:56:08', 0),
(173, '::1', 'TAEN-Agent', 'flights from london to amsterdam 11/03/2018 and hotel rooms in amsterdam and Bruges Day activity in europe', '2018-04-05 12:56:10', 0),
(174, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-05 12:56:10', 0),
(175, '::1', 'TAEN-Agent', 'result', '2018-04-05 12:56:24', 0),
(176, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-05 12:56:24', 0),
(177, '::1', 'TAEN-Agent', 'Bruges Day activity in europe', '2018-04-05 12:58:12', 0),
(178, 'TAEN-Agent', '::1', 'Hmm, I can not understand that one. Start all over by clicking Wanted Holiday or Help button. Then try stuffs like \'hotel room offers in London\' or \'flying from New York to London on 29/03/2018\' ', '2018-04-05 12:58:12', 0),
(179, '::1', 'TAEN-Agent', 'holiday', '2018-04-05 12:58:22', 0),
(180, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-05 12:58:22', 0),
(181, '::1', 'TAEN-Agent', 'Bruges Day activity in europe', '2018-04-05 12:58:31', 0),
(182, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-05 12:58:31', 0),
(183, '::1', 'TAEN-Agent', 'result', '2018-04-05 12:58:35', 0),
(184, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-05 12:58:35', 0),
(185, '::1', 'TAEN-Agent', 'holiday', '2018-04-05 12:58:42', 0),
(186, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-05 12:58:42', 0),
(187, '::1', 'TAEN-Agent', 'hotels in amsterdam', '2018-04-05 12:58:51', 0),
(188, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-05 12:58:51', 0),
(189, '::1', 'TAEN-Agent', 'result', '2018-04-05 12:58:55', 0),
(190, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-05 12:58:55', 0),
(191, '::1', 'TAEN-Agent', 'holiday', '2018-04-05 13:35:53', 0),
(192, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-05 13:35:53', 0),
(193, '::1', 'TAEN-Agent', 'flight from london to amsterdam 11/03/2018 and Bruges day activities in europe', '2018-04-05 13:36:31', 0),
(194, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-05 13:36:31', 0),
(195, '::1', 'TAEN-Agent', 'holiday', '2018-04-05 13:37:08', 0),
(196, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-05 13:37:08', 0),
(197, '::1', 'TAEN-Agent', 'flight from london to amsterdam 11/03/2018 and Bruges day activities in europe', '2018-04-05 13:37:10', 0),
(198, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-05 13:37:10', 0),
(199, '::1', 'TAEN-Agent', 'result', '2018-04-05 13:37:14', 0),
(200, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-05 13:37:14', 0),
(201, '::1', 'TAEN-Agent', 'help', '2018-04-05 14:10:43', 0),
(202, 'TAEN-Agent', '::1', 'In which continent do you like to travel', '2018-04-05 14:10:43', 0),
(203, '::1', 'TAEN-Agent', 'Europe', '2018-04-05 14:10:45', 0),
(204, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-05 14:10:45', 0),
(205, '::1', 'TAEN-Agent', 'holiday', '2018-04-05 15:48:58', 0),
(206, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-05 15:48:58', 0),
(207, '::1', 'TAEN-Agent', 'holiday', '2018-04-05 15:49:09', 0),
(208, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-05 15:49:09', 0),
(209, '::1', 'TAEN-Agent', 'Bruges activity in Amsterdam', '2018-04-05 15:49:12', 0),
(210, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-05 15:49:12', 0),
(211, '::1', 'TAEN-Agent', 'result', '2018-04-05 15:49:15', 0),
(212, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-05 15:49:15', 0),
(213, '::1', 'TAEN-Agent', 'holiday', '2018-04-06 18:17:34', 0),
(214, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-06 18:17:34', 0),
(215, '::1', 'TAEN-Agent', 'flights from london to Amsterdam on 11/03/2018 and 13/03/2018', '2018-04-06 18:18:05', 0),
(216, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-06 18:18:05', 0),
(217, '::1', 'TAEN-Agent', 'result', '2018-04-06 18:18:11', 0),
(218, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-06 18:18:11', 0),
(219, '::1', 'TAEN-Agent', 'help', '2018-04-06 22:43:55', 0),
(220, 'TAEN-Agent', '::1', 'In which continent do you like to travel', '2018-04-06 22:43:55', 0),
(221, '::1', 'TAEN-Agent', 'any', '2018-04-06 22:43:59', 0),
(222, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-06 22:43:59', 0),
(223, '::1', 'TAEN-Agent', 'holiday', '2018-04-08 23:19:34', 0),
(224, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-08 23:19:34', 0),
(225, '::1', 'TAEN-Agent', 'flights from london to amsterdam on 11/03/2018', '2018-04-08 23:19:59', 0),
(226, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-08 23:20:00', 0),
(227, '::1', 'TAEN-Agent', 'result', '2018-04-08 23:20:03', 0),
(228, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-08 23:20:03', 0),
(229, '::1', 'TAEN-Agent', 'holiday', '2018-04-08 23:23:17', 0),
(230, 'TAEN-Agent', '::1', 'What do you want for holiday?', '2018-04-08 23:23:17', 0),
(231, '::1', 'TAEN-Agent', 'flight from london to amsterdam on 11/03/2018', '2018-04-08 23:23:38', 0),
(232, 'TAEN-Agent', '::1', 'I have got some results for you. Type &quot;result&quot; to view result on main page.', '2018-04-08 23:23:38', 0),
(233, '::1', 'TAEN-Agent', 'result', '2018-04-08 23:23:42', 0),
(234, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-08 23:23:42', 0),
(235, '::1', 'TAEN-Agent', 'help', '2018-04-08 23:40:55', 0),
(236, 'TAEN-Agent', '::1', 'In which continent do you like to travel', '2018-04-08 23:40:55', 0),
(237, '::1', 'TAEN-Agent', 'rufus', '2018-04-08 23:41:01', 0),
(238, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-08 23:41:01', 0),
(239, '::1', 'TAEN-Agent', 'help', '2018-04-08 23:41:11', 0),
(240, 'TAEN-Agent', '::1', 'Which environment do you prefer?', '2018-04-08 23:41:11', 0),
(241, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:09:01', 0),
(242, 'TAEN-Agent', '::1', 'In which continent do you like to travel', '2018-04-09 00:09:01', 0),
(243, '::1', 'TAEN-Agent', 'burundi', '2018-04-09 00:09:07', 0),
(244, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid continent like &quot;Europe&quot;, &quot;Asia&quot;, &quot;North America&quot; or &quot;help&quot; to start over.', '2018-04-09 00:09:07', 0),
(245, '::1', 'TAEN-Agent', 'Asia', '2018-04-09 00:09:20', 0),
(246, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:09:20', 0),
(247, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:09:26', 0),
(248, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid continent like &quot;Europe&quot;, &quot;Asia&quot;, &quot;North America&quot; or &quot;help&quot; to start over.', '2018-04-09 00:09:26', 0),
(249, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:11:27', 0),
(250, 'TAEN-Agent', '::1', 'In which continent do you like to travel', '2018-04-09 00:11:27', 0),
(251, '::1', 'TAEN-Agent', 'Asia', '2018-04-09 00:11:31', 0),
(252, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:11:31', 0),
(253, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:11:33', 0),
(254, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid continent like &quot;Europe&quot;, &quot;Asia&quot;, &quot;North America&quot; or &quot;help&quot; to start over.', '2018-04-09 00:11:33', 0),
(255, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:12:05', 0),
(256, 'TAEN-Agent', '::1', 'Which season do you like to have holidays in?', '2018-04-09 00:12:05', 0),
(257, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:12:12', 0),
(258, 'TAEN-Agent', '::1', 'In which continent do you like to travel', '2018-04-09 00:12:12', 0),
(259, '::1', 'TAEN-Agent', 'genuine', '2018-04-09 00:12:34', 0),
(260, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid continent like &quot;Europe&quot;, &quot;Asia&quot;, &quot;North America&quot; or &quot;help&quot; to start over.', '2018-04-09 00:12:34', 0),
(261, '::1', 'TAEN-Agent', 'Jugunu', '2018-04-09 00:12:54', 0),
(262, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid continent like &quot;Europe&quot;, &quot;Asia&quot;, &quot;North America&quot; or &quot;help&quot; to start over.', '2018-04-09 00:12:54', 0),
(263, '::1', 'TAEN-Agent', 'Europe', '2018-04-09 00:13:00', 0),
(264, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:13:00', 0),
(265, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:13:03', 0),
(266, 'TAEN-Agent', '::1', 'Which environment do you prefer?', '2018-04-09 00:13:03', 0),
(267, '::1', 'TAEN-Agent', 'rainy', '2018-04-09 00:17:08', 0),
(268, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:17:08', 0),
(269, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:17:11', 0),
(270, 'TAEN-Agent', '::1', 'Which season do you like to have holidays in?', '2018-04-09 00:17:11', 0),
(271, '::1', 'TAEN-Agent', 'Alimonjiri', '2018-04-09 00:17:18', 0),
(272, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid season like &quot;Winter&quot;, &quot;Summer&quot; or &quot;help&quot; to start over.', '2018-04-09 00:17:18', 0),
(273, '::1', 'TAEN-Agent', 'Jolade', '2018-04-09 00:17:27', 0),
(274, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid season like &quot;Winter&quot;, &quot;Summer&quot; or &quot;help&quot; to start over.', '2018-04-09 00:17:27', 0),
(275, '::1', 'TAEN-Agent', 'winter', '2018-04-09 00:17:32', 0),
(276, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:17:32', 0),
(277, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:28:57', 0),
(278, 'TAEN-Agent', '::1', 'Which weather do you prefer?', '2018-04-09 00:28:57', 0),
(279, '::1', 'TAEN-Agent', 'stormy', '2018-04-09 00:29:07', 0);
INSERT INTO `dialogue_knowledge` (`Id`, `DkFrom`, `DkTo`, `DkMessage`, `DkSent`, `DkRecd`) VALUES
(280, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:29:07', 0),
(281, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:29:09', 0),
(282, 'TAEN-Agent', '::1', 'Which date range?', '2018-04-09 00:29:09', 0),
(283, '::1', 'TAEN-Agent', 'dade -dada', '2018-04-09 00:29:15', 0),
(284, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:29:15', 0),
(285, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:30:27', 0),
(286, 'TAEN-Agent', '::1', 'Which price range for activities?', '2018-04-09 00:30:28', 0),
(287, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:30:36', 0),
(288, 'TAEN-Agent', '::1', 'In which continent do you like to travel', '2018-04-09 00:30:36', 0),
(289, '::1', 'TAEN-Agent', 'Europe', '2018-04-09 00:30:41', 0),
(290, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:30:41', 0),
(291, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:30:47', 0),
(292, 'TAEN-Agent', '::1', 'Which environment do you prefer?', '2018-04-09 00:30:47', 0),
(293, '::1', 'TAEN-Agent', 'Plain', '2018-04-09 00:30:54', 0),
(294, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:30:54', 0),
(295, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:30:57', 0),
(296, 'TAEN-Agent', '::1', 'Which season do you like to have holidays in?', '2018-04-09 00:30:57', 0),
(297, '::1', 'TAEN-Agent', 'Winterrrrr', '2018-04-09 00:31:03', 0),
(298, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid season like &quot;Winter&quot;, &quot;Summer&quot; or &quot;help&quot; to start over.', '2018-04-09 00:31:03', 0),
(299, '::1', 'TAEN-Agent', 'Summer', '2018-04-09 00:31:06', 0),
(300, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:31:06', 0),
(301, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:31:08', 0),
(302, 'TAEN-Agent', '::1', 'Which weather do you prefer?', '2018-04-09 00:31:09', 0),
(303, '::1', 'TAEN-Agent', 'sunny', '2018-04-09 00:31:14', 0),
(304, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:31:14', 0),
(305, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:31:16', 0),
(306, 'TAEN-Agent', '::1', 'Which date range?', '2018-04-09 00:31:16', 0),
(307, '::1', 'TAEN-Agent', 'dade -- dade', '2018-04-09 00:31:24', 0),
(308, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:31:24', 0),
(309, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:35:44', 0),
(310, 'TAEN-Agent', '::1', 'In which continent do you like to travel', '2018-04-09 00:35:45', 0),
(311, '::1', 'TAEN-Agent', 'Europe', '2018-04-09 00:35:50', 0),
(312, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:35:50', 0),
(313, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:35:52', 0),
(314, 'TAEN-Agent', '::1', 'Which environment do you prefer?', '2018-04-09 00:35:52', 0),
(315, '::1', 'TAEN-Agent', 'plain', '2018-04-09 00:35:56', 0),
(316, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:35:56', 0),
(317, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:35:59', 0),
(318, 'TAEN-Agent', '::1', 'Which season do you like to have holidays in?', '2018-04-09 00:35:59', 0),
(319, '::1', 'TAEN-Agent', 'summer', '2018-04-09 00:36:08', 0),
(320, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:36:08', 0),
(321, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:36:10', 0),
(322, 'TAEN-Agent', '::1', 'Which weather do you prefer?', '2018-04-09 00:36:10', 0),
(323, '::1', 'TAEN-Agent', 'sunny', '2018-04-09 00:36:16', 0),
(324, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:36:16', 0),
(325, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:36:18', 0),
(326, 'TAEN-Agent', '::1', 'Which date range?', '2018-04-09 00:36:18', 0),
(327, '::1', 'TAEN-Agent', 'dade- dae', '2018-04-09 00:36:24', 0),
(328, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:36:24', 0),
(329, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:38:23', 0),
(330, 'TAEN-Agent', '::1', 'In which continent do you like to travel', '2018-04-09 00:38:23', 0),
(331, '::1', 'TAEN-Agent', 'Europe', '2018-04-09 00:38:29', 0),
(332, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:38:29', 0),
(333, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:38:33', 0),
(334, 'TAEN-Agent', '::1', 'Which environment do you prefer?', '2018-04-09 00:38:33', 0),
(335, '::1', 'TAEN-Agent', 'plain', '2018-04-09 00:38:37', 0),
(336, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:38:37', 0),
(337, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:38:39', 0),
(338, 'TAEN-Agent', '::1', 'Which season do you like to have holidays in?', '2018-04-09 00:38:39', 0),
(339, '::1', 'TAEN-Agent', 'Summer', '2018-04-09 00:38:43', 0),
(340, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:38:43', 0),
(341, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:38:45', 0),
(342, 'TAEN-Agent', '::1', 'Which weather do you prefer?', '2018-04-09 00:38:45', 0),
(343, '::1', 'TAEN-Agent', 'sunny', '2018-04-09 00:38:54', 0),
(344, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:38:54', 0),
(345, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:38:56', 0),
(346, 'TAEN-Agent', '::1', 'Which date range?', '2018-04-09 00:38:56', 0),
(347, '::1', 'TAEN-Agent', 'sdsd- sdsds', '2018-04-09 00:39:04', 0),
(348, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:39:04', 0),
(349, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:41:58', 0),
(350, 'TAEN-Agent', '::1', 'In which continent do you like to travel', '2018-04-09 00:41:58', 0),
(351, '::1', 'TAEN-Agent', 'Europe', '2018-04-09 00:42:02', 0),
(352, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:42:02', 0),
(353, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:42:06', 0),
(354, 'TAEN-Agent', '::1', 'Which environment do you prefer?', '2018-04-09 00:42:06', 0),
(355, '::1', 'TAEN-Agent', 'plain', '2018-04-09 00:42:11', 0),
(356, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:42:11', 0),
(357, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:42:15', 0),
(358, 'TAEN-Agent', '::1', 'Which season do you like to have holidays in?', '2018-04-09 00:42:15', 0),
(359, '::1', 'TAEN-Agent', 'summer', '2018-04-09 00:42:19', 0),
(360, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:42:20', 0),
(361, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:42:21', 0),
(362, 'TAEN-Agent', '::1', 'Which weather do you prefer?', '2018-04-09 00:42:22', 0),
(363, '::1', 'TAEN-Agent', 'sunny', '2018-04-09 00:42:27', 0),
(364, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:42:27', 0),
(365, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:42:30', 0),
(366, 'TAEN-Agent', '::1', 'Which date range?', '2018-04-09 00:42:30', 0),
(367, '::1', 'TAEN-Agent', 'sdas-dwd', '2018-04-09 00:42:34', 0),
(368, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:42:34', 0),
(369, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:46:06', 0),
(370, 'TAEN-Agent', '::1', 'In which continent do you like to travel', '2018-04-09 00:46:06', 0),
(371, '::1', 'TAEN-Agent', 'Europe', '2018-04-09 00:46:10', 0),
(372, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:46:10', 0),
(373, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:46:13', 0),
(374, 'TAEN-Agent', '::1', 'Which environment do you prefer?', '2018-04-09 00:46:13', 0),
(375, '::1', 'TAEN-Agent', 'plain', '2018-04-09 00:46:17', 0),
(376, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:46:17', 0),
(377, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:46:20', 0),
(378, 'TAEN-Agent', '::1', 'Which season do you like to have holidays in?', '2018-04-09 00:46:20', 0),
(379, '::1', 'TAEN-Agent', 'summer', '2018-04-09 00:46:23', 0),
(380, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:46:23', 0),
(381, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:46:26', 0),
(382, 'TAEN-Agent', '::1', 'Which weather do you prefer?', '2018-04-09 00:46:26', 0),
(383, '::1', 'TAEN-Agent', 'rainy', '2018-04-09 00:46:33', 0),
(384, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:46:33', 0),
(385, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:46:35', 0),
(386, 'TAEN-Agent', '::1', 'Which date range?', '2018-04-09 00:46:35', 0),
(387, '::1', 'TAEN-Agent', 'dede-dcd', '2018-04-09 00:46:39', 0),
(388, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid date range like &quot;11/03/2018 - 15/03/2018&quot; or &quot;restart&quot; to start over.', '2018-04-09 00:46:39', 0),
(389, '::1', 'TAEN-Agent', '11/03/2018 - 15/03/2018', '2018-04-09 00:47:04', 0),
(390, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:47:04', 0),
(391, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:47:16', 0),
(392, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid date range like &quot;11/03/2018 - 15/03/2018&quot; or &quot;restart&quot; to start over.', '2018-04-09 00:47:17', 0),
(393, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:47:50', 0),
(394, 'TAEN-Agent', '::1', 'Which is your age group?', '2018-04-09 00:47:50', 0),
(395, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:48:34', 0),
(396, 'TAEN-Agent', '::1', 'In which continent do you like to travel', '2018-04-09 00:48:34', 0),
(397, '::1', 'TAEN-Agent', 'Europe', '2018-04-09 00:48:38', 0),
(398, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:48:38', 0),
(399, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:48:40', 0),
(400, 'TAEN-Agent', '::1', 'Which environment do you prefer?', '2018-04-09 00:48:40', 0),
(401, '::1', 'TAEN-Agent', 'plain', '2018-04-09 00:48:46', 0),
(402, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:48:46', 0),
(403, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:48:48', 0),
(404, 'TAEN-Agent', '::1', 'Which season do you like to have holidays in?', '2018-04-09 00:48:48', 0),
(405, '::1', 'TAEN-Agent', 'summer', '2018-04-09 00:48:51', 0),
(406, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:48:51', 0),
(407, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:48:54', 0),
(408, 'TAEN-Agent', '::1', 'Which weather do you prefer?', '2018-04-09 00:48:54', 0),
(409, '::1', 'TAEN-Agent', 'rainy', '2018-04-09 00:48:58', 0);
INSERT INTO `dialogue_knowledge` (`Id`, `DkFrom`, `DkTo`, `DkMessage`, `DkSent`, `DkRecd`) VALUES
(410, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:48:58', 0),
(411, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:49:00', 0),
(412, 'TAEN-Agent', '::1', 'Which date range?', '2018-04-09 00:49:00', 0),
(413, '::1', 'TAEN-Agent', 'ddd-dfdf', '2018-04-09 00:49:04', 0),
(414, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid date range like &quot;11/03/2018 - 15/03/2018&quot; or &quot;restart&quot; to start over.', '2018-04-09 00:49:04', 0),
(415, '::1', 'TAEN-Agent', '11/03/2018 - 15/03/2018', '2018-04-09 00:49:24', 0),
(416, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:49:24', 0),
(417, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:49:27', 0),
(418, 'TAEN-Agent', '::1', 'Which price range for activities?', '2018-04-09 00:49:27', 0),
(419, '::1', 'TAEN-Agent', 'sdsds-sdsd', '2018-04-09 00:56:03', 0),
(420, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid price range like 23 - 40 or &quot;restart&quot; to start over.', '2018-04-09 00:56:03', 0),
(421, '::1', 'TAEN-Agent', '20-100', '2018-04-09 00:56:11', 0),
(422, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid price range like 23 - 40 or &quot;restart&quot; to start over.', '2018-04-09 00:56:11', 0),
(423, '::1', 'TAEN-Agent', '20 - 100', '2018-04-09 00:57:06', 0),
(424, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 00:57:06', 0),
(425, '::1', 'TAEN-Agent', 'help', '2018-04-09 00:57:14', 0),
(426, 'TAEN-Agent', '::1', 'Which is your age group?', '2018-04-09 00:57:15', 0),
(427, '::1', 'TAEN-Agent', 'ssaa', '2018-04-09 01:06:22', 0),
(428, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid Age Group like  &quot;Adolescence&quot;, &quot;Young-adult&quot; or &quot;restart&quot; to start over.', '2018-04-09 01:06:22', 0),
(429, '::1', 'TAEN-Agent', 'Adolescence', '2018-04-09 01:06:32', 0),
(430, 'TAEN-Agent', '::1', 'Which activities are you expecting to have?', '2018-04-09 01:06:32', 0),
(431, '::1', 'TAEN-Agent', 'Bruges day', '2018-04-09 01:06:40', 0),
(432, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid Age Group like  &quot;Adolescence&quot;, &quot;Young-adult&quot; or &quot;restart&quot; to start over.', '2018-04-09 01:06:40', 0),
(433, '::1', 'TAEN-Agent', 'Adolescence', '2018-04-09 01:07:19', 0),
(434, 'TAEN-Agent', '::1', 'Which activities are you expecting to have?', '2018-04-09 01:07:19', 0),
(435, '::1', 'TAEN-Agent', 'Bruges day', '2018-04-09 01:07:31', 0),
(436, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid Age Group like  &quot;Adolescence&quot;, &quot;Young-adult&quot; or &quot;restart&quot; to start over.', '2018-04-09 01:07:31', 0),
(437, '::1', 'TAEN-Agent', 'Adolescence', '2018-04-09 01:08:01', 0),
(438, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 01:08:01', 0),
(439, '::1', 'TAEN-Agent', 'help', '2018-04-09 01:08:04', 0),
(440, 'TAEN-Agent', '::1', 'Which activities are you expecting to have?', '2018-04-09 01:08:05', 0),
(441, '::1', 'TAEN-Agent', 'Bruges day', '2018-04-09 01:08:11', 0),
(442, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 01:08:11', 0),
(443, '::1', 'TAEN-Agent', 'result', '2018-04-09 01:08:16', 0),
(444, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-09 01:08:16', 0),
(445, '::1', 'TAEN-Agent', 'result', '2018-04-09 01:08:27', 0),
(446, 'TAEN-Agent', '::1', 'No result to display. Click the Holiday or Help Button to start.', '2018-04-09 01:08:27', 0),
(447, '::1', 'TAEN-Agent', 'restart', '2018-04-09 01:08:38', 0),
(448, 'TAEN-Agent', '::1', 'Hmm, I can not understand that one. Start all over by clicking Wanted Holiday or Help button. Then try stuffs like \'hotel room offers in London\' or \'flying from New York to London on 29/03/2018\' ', '2018-04-09 01:08:38', 0),
(449, '::1', 'TAEN-Agent', 'restart', '2018-04-09 01:08:44', 0),
(450, 'TAEN-Agent', '::1', 'Hmm, I can not understand that one. Start all over by clicking Wanted Holiday or Help button. Then try stuffs like \'hotel room offers in London\' or \'flying from New York to London on 29/03/2018\' ', '2018-04-09 01:08:44', 0),
(451, '::1', 'TAEN-Agent', 'help', '2018-04-09 01:08:49', 0),
(452, 'TAEN-Agent', '::1', 'In which continent do you like to travel', '2018-04-09 01:08:49', 0),
(453, '::1', 'TAEN-Agent', 'Europe', '2018-04-09 01:08:53', 0),
(454, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 01:08:53', 0),
(455, '::1', 'TAEN-Agent', 'help', '2018-04-09 01:08:56', 0),
(456, 'TAEN-Agent', '::1', 'Which environment do you prefer?', '2018-04-09 01:08:56', 0),
(457, '::1', 'TAEN-Agent', 'plain', '2018-04-09 01:09:00', 0),
(458, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 01:09:00', 0),
(459, '::1', 'TAEN-Agent', 'help', '2018-04-09 01:09:02', 0),
(460, 'TAEN-Agent', '::1', 'Which season do you like to have holidays in?', '2018-04-09 01:09:02', 0),
(461, '::1', 'TAEN-Agent', 'summer', '2018-04-09 01:09:05', 0),
(462, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 01:09:05', 0),
(463, '::1', 'TAEN-Agent', 'help', '2018-04-09 01:09:07', 0),
(464, 'TAEN-Agent', '::1', 'Which weather do you prefer?', '2018-04-09 01:09:07', 0),
(465, '::1', 'TAEN-Agent', 'rainy', '2018-04-09 01:09:15', 0),
(466, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 01:09:15', 0),
(467, '::1', 'TAEN-Agent', 'help', '2018-04-09 01:09:17', 0),
(468, 'TAEN-Agent', '::1', 'Which date range?', '2018-04-09 01:09:17', 0),
(469, '::1', 'TAEN-Agent', '11/03/2018 - 15/03/2018', '2018-04-09 01:09:34', 0),
(470, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 01:09:34', 0),
(471, '::1', 'TAEN-Agent', 'help', '2018-04-09 01:09:36', 0),
(472, 'TAEN-Agent', '::1', 'Which price range for activities?', '2018-04-09 01:09:36', 0),
(473, '::1', 'TAEN-Agent', 'sddf', '2018-04-09 01:09:39', 0),
(474, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid price range like 23 - 40 or &quot;restart&quot; to start over.', '2018-04-09 01:09:39', 0),
(475, '::1', 'TAEN-Agent', '20-100', '2018-04-09 01:09:44', 0),
(476, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid price range like 23 - 40 or &quot;restart&quot; to start over.', '2018-04-09 01:09:44', 0),
(477, '::1', 'TAEN-Agent', '20 - 100', '2018-04-09 01:09:53', 0),
(478, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 01:09:53', 0),
(479, '::1', 'TAEN-Agent', 'help', '2018-04-09 01:09:54', 0),
(480, 'TAEN-Agent', '::1', 'Which is your age group?', '2018-04-09 01:09:54', 0),
(481, '::1', 'TAEN-Agent', 'Adolescencescdscsd', '2018-04-09 01:10:02', 0),
(482, 'TAEN-Agent', '::1', 'I could not understand that. Enter a valid Age Group like  &quot;Adolescence&quot;, &quot;Young-adult&quot; or &quot;restart&quot; to start over.', '2018-04-09 01:10:02', 0),
(483, '::1', 'TAEN-Agent', 'Adolescence', '2018-04-09 01:10:05', 0),
(484, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-anchor\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'help\') style=\'text-decoration:none;\'&gt;HELP&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 01:10:05', 0),
(485, '::1', 'TAEN-Agent', 'help', '2018-04-09 01:10:07', 0),
(486, 'TAEN-Agent', '::1', 'Which activities are you expecting to have?', '2018-04-09 01:10:07', 0),
(487, '::1', 'TAEN-Agent', 'Bruges', '2018-04-09 01:10:13', 0),
(488, 'TAEN-Agent', '::1', '&lt;div style=\'margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-spinner\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'restart\') style=\'text-decoration:none;\'&gt;RESTART&lt;/a&gt;&lt;/div&gt;\r\n                                                                          &lt;div style=\'margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;\'&gt;&lt;span class=\'fa fa-info\' style=\'color:#009900;\'&gt;&lt;/span&gt;&amp;nbsp;&amp;nbsp;&lt;a href=\'javascript:void(0)\' onclick=javascript:chatWith(\'TAEN-Agent\',\'result\') style=\'text-decoration:none;\'&gt;RESULTS&lt;/a&gt;&lt;/div&gt;', '2018-04-09 01:10:13', 0),
(489, '::1', 'TAEN-Agent', 'result', '2018-04-09 01:10:15', 0),
(490, 'TAEN-Agent', '::1', 'Coming your way', '2018-04-09 01:10:16', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dialogue_rules`
--

CREATE TABLE `dialogue_rules` (
  `DrTrigger` longtext COLLATE latin1_general_ci,
  `DrResponse` longtext COLLATE latin1_general_ci NOT NULL,
  `DrType` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT 'holiday',
  `DrValidationResponse` longtext COLLATE latin1_general_ci,
  `DrStage` int(10) NOT NULL,
  `DrID` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `dialogue_rules`
--

INSERT INTO `dialogue_rules` (`DrTrigger`, `DrResponse`, `DrType`, `DrValidationResponse`, `DrStage`, `DrID`) VALUES
('Holiday in, Adventure, City Tour, Activities, Activity, Flight from, flight to, travel', 'What do you want for holiday?', 'holiday', 'I could not understand that. Try stuffs like \"Flight from London to Amsterdam 11/03/2018\" or Hotels In Amsterdam. Click WANTED HOLIDAY or HELP button to start again ', 1, 1),
(NULL, 'I have got some results for you. Type \"result\" to view result on main page.', 'holiday', 'I could not find any match for your search. Try stuffs like \"Flight from London to Amsterdam 11/03/2018\" or Hotels In Amsterdam', 2, 2),
(NULL, 'Coming your way', 'result', 'No result to display. Click the Holiday or Help Button to start.', 1, 3),
('Help', 'In which continent do you like to travel', 'help', 'I could not understand that. Enter \"restart\" to start over.', 1, 4),
(NULL, 'Which environment do you prefer?', 'help', 'I could not understand that. Enter a valid continent like \"Europe\", \"Asia\", \"North America\" or \"help\" to start over.', 2, 5),
(NULL, 'Which season do you like to have holidays in?', 'help', 'I could not understand that. Enter your preferred season or \"help\" to start over.', 3, 6),
(NULL, 'Which weather do you prefer?', 'help', 'I could not understand that. Enter a valid season like \"Winter\", \"Summer\" or \"help\" to start over.', 4, 7),
(NULL, 'Which date range?', 'help', 'I could not understand that. Enter your date range like “23-10-2018” and “01-11-2018” or \"restart\" to start over.', 5, 8),
(NULL, 'Which price range for activities?', 'help', 'I could not understand that. Enter a valid date range like \"11/03/2018 - 15/03/2018\" or \"restart\" to start over.', 6, 9),
(NULL, 'Which is your age group?', 'help', 'I could not understand that. Enter a valid price range like 23 - 40 or \"restart\" to start over.', 7, 10),
(NULL, 'Which activities are you expecting to have?', 'help', 'I could not understand that. Enter a valid Age Group like  \"Adolescence\", \"Young-adult\" or \"restart\" to start over.', 8, 11),
(NULL, 'I have got some results for you. Type \"result\" to view result on main page.', 'help', 'No result to display. Click the Holiday or Help Button to start.I could not find any match for your search. Try stuffs like \"Flight from London to Amsterdam 11/03/2018\" or Hotels In Amsterdam', 9, 12);

-- --------------------------------------------------------

--
-- Table structure for table `filter`
--

CREATE TABLE `filter` (
  `FilterID` int(14) NOT NULL,
  `Date1` datetime NOT NULL,
  `Date2` datetime NOT NULL,
  `Continent` varchar(255) NOT NULL,
  `Environment` longtext NOT NULL,
  `Season` varchar(255) NOT NULL,
  `Weather` longtext NOT NULL,
  `Age` varchar(255) NOT NULL,
  `Activities` longtext NOT NULL,
  `ActivityPrice` double(14,2) NOT NULL,
  `Country` varchar(255) NOT NULL,
  `City` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `filter`
--

INSERT INTO `filter` (`FilterID`, `Date1`, `Date2`, `Continent`, `Environment`, `Season`, `Weather`, `Age`, `Activities`, `ActivityPrice`, `Country`, `City`) VALUES
(1, '2018-03-10 00:00:00', '2018-03-11 00:00:00', 'Europe', 'Bruges Day Trip from Amsterdam.\r\nDiscover Bruges, an immaculately preserved medieval old town, just over the border into Belgium. Team up with an experienced guide to hear stories about this picture-perfect town while you explore charming streets, quaint canals, and exquisite shops selling everything from monk-brewed beer to handmade chocolates.', 'Spring', 'This year weather came as a suprise. Winter has entended well into Spring. Amsterdam is really cold right now with temperature as low as -20c. Please come with you over coats and pullovers with long stocking and boot.', 'Young-adult', 'Bruges Day Trip from Amsterdam. Discover Bruges, an immaculately preserved medieval old town, just over the border into Belgium. Team up with an experienced guide to hear stories about this picture-perfect town while you explore charming streets, quaint canals, and exquisite shops selling everything from monk-brewed beer to handmade chocolates.', 20.00, 'The Netherlands', 'Amsterdam'),
(2, '2018-03-11 08:00:00', '2018-03-11 17:00:00', 'Europe', 'Amsterdam Hop-On Hop-Off Bus Tour\r\nExplore Amsterdam on a red double-decker bus that gives you panoramic views of the city. Unlimited rides let you hop on and off the bus at your leisure, so you can visit all the attractions on your sightseeing list. Or, just sit back and ride the complete loop for an overview of the Dutch capital.\r\n\r\nWith the freedom to move at your own pace, you can stroll through stone-paved alleyways and stop at the city\'s world-famous attractions, tourist areas, and inviting neighborhoods. Listen to anecdotes about Amsterdam\'s past, present, and future, plus get insider tips about where to shop, dine, or dance the night away.', 'Spring', 'This year weather came as a suprise. Winter has entended well into Spring. Amsterdam is really cold right now with temperature as low as -20c. Please come with you over coats and pullovers with long stocking and boot.', 'Young-adult', 'Amsterdam Hop-On Hop-Off Bus Tour Explore Amsterdam on a red double-decker bus that gives you panoramic views of the city. Unlimited rides let you hop on and off the bus at your leisure, so you can visit all the attractions on your sightseeing list. Or, just sit back and ride the complete loop for an overview of the Dutch capital.  With the freedom to move at your own pace, you can stroll through stone-paved alleyways and stop at the city\'s world-famous attractions, tourist areas, and inviting neighborhoods. Listen to anecdotes about Amsterdam\'s past, present, and future, plus get insider tips about where to shop, dine, or dance the night away.', 50.00, 'The Netherlands', 'Amsterdam'),
(3, '2018-03-12 09:11:00', '2018-03-12 15:00:00', 'Europe', 'Van Gogh & Rijksmuseum Guided Tour with Lunch Cruise\r\nAfter skipping the long queues, let an art expert walk you through collections of Van Gogh, Rembrandt, and Vermeer masterpieces at 2 world-class museums. And while traveling between them, enjoy a cruise with lunch on Amsterdam’s enchanting canals.\r\n', 'Spring', 'This year weather came as a suprise. Winter has entended well into Spring. Amsterdam is really cold right now with temperature as low as -20c. Please come with you over coats and pullovers with long stocking and boot.', 'Adolescence', 'Amsterdam Hop-On Hop-Off Bus Tour Explore Amsterdam on a red double-decker bus that gives you panoramic views of the city. Unlimited rides let you hop on and off the bus at your leisure, so you can visit all the attractions on your sightseeing list. Or, just sit back and ride the complete loop for an overview of the Dutch capital.  With the freedom to move at your own pace, you can stroll through stone-paved alleyways and stop at the city\'s world-famous attractions, tourist areas, and inviting neighborhoods. Listen to anecdotes about Amsterdam\'s past, present, and future, plus get insider tips about where to shop, dine, or dance the night away.', 20.00, 'The Netherlands', 'Amsterdam');

-- --------------------------------------------------------

--
-- Table structure for table `flight`
--

CREATE TABLE `flight` (
  `FlightID` int(14) NOT NULL,
  `DepartureAirport` varchar(255) NOT NULL,
  `ArrivalLocation` varchar(255) NOT NULL,
  `DepartureTime` time NOT NULL,
  `ArrivalTime` time NOT NULL,
  `DepartureDate` date NOT NULL,
  `ArrivalDate` date NOT NULL,
  `Price` double(14,2) NOT NULL,
  `Company` varchar(255) NOT NULL,
  `Capacity` int(14) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `flight`
--

INSERT INTO `flight` (`FlightID`, `DepartureAirport`, `ArrivalLocation`, `DepartureTime`, `ArrivalTime`, `DepartureDate`, `ArrivalDate`, `Price`, `Company`, `Capacity`) VALUES
(1, 'London/Heathrow (LCY )', 'Amsterdam', '06:30:00', '09:00:00', '2018-03-11', '2018-03-11', 146.00, 'British Airways', 70),
(2, 'Amsterdam/Schiphol', 'London', '07:50:00', '09:50:00', '2018-03-13', '2018-03-13', 125.00, 'KLM Royal Dutch Airlines', 100),
(3, 'Amsterdam/Schiphol', 'London', '08:40:00', '10:50:00', '2018-03-12', '2018-03-12', 140.00, 'British Airways', 70),
(4, 'London/Heathrow (LCY )', 'Amsterdam', '06:45:00', '09:00:00', '2018-03-11', '2018-03-11', 120.00, 'KLM Royal Dutch Airlines', 98);

-- --------------------------------------------------------

--
-- Table structure for table `hotel`
--

CREATE TABLE `hotel` (
  `HotelID` int(14) NOT NULL,
  `Country` varchar(255) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `HotelCity` varchar(255) NOT NULL,
  `Address` longtext NOT NULL,
  `NumberOfRooms` int(14) NOT NULL,
  `Ranking` int(14) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hotel`
--

INSERT INTO `hotel` (`HotelID`, `Country`, `Title`, `HotelCity`, `Address`, `NumberOfRooms`, `Ranking`) VALUES
(1, 'The Netherlands', 'Nova Hotel Amsterdam', 'Amsterdam', 'Nieuwezijds Voorburgwal 276, 1012 RS Amsterdam, Netherlands', 199, 1),
(2, 'The Netherlands', 'NH Amsterdam Noord', 'Amsterdam', 'Distelkade 21, 1031 XP Amsterdam, Netherlands', 48, 2);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `RoomId` int(14) NOT NULL,
  `RoomNumber` int(14) NOT NULL,
  `Price` float(14,2) NOT NULL,
  `Type` varchar(255) NOT NULL,
  `Hotel` int(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`RoomId`, `RoomNumber`, `Price`, `Type`, `Hotel`) VALUES
(1, 27, 40.00, 'Single Bed Suite', 1),
(2, 45, 30.00, 'Single Bed Suite', 1),
(3, 112, 90.00, 'Double Bed Suite', 2),
(4, 6, 90.00, 'Double Bed Suite', 1),
(5, 15, 150.00, 'Business Suite', 1),
(6, 40, 150.00, 'Business Suite', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dialogue_knowledge`
--
ALTER TABLE `dialogue_knowledge`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `dialogue_rules`
--
ALTER TABLE `dialogue_rules`
  ADD PRIMARY KEY (`DrID`);
ALTER TABLE `dialogue_rules` ADD FULLTEXT KEY `trigger` (`DrTrigger`);

--
-- Indexes for table `filter`
--
ALTER TABLE `filter`
  ADD PRIMARY KEY (`FilterID`);
ALTER TABLE `filter` ADD FULLTEXT KEY `continent` (`Continent`);
ALTER TABLE `filter` ADD FULLTEXT KEY `environment` (`Environment`);
ALTER TABLE `filter` ADD FULLTEXT KEY `season` (`Season`);
ALTER TABLE `filter` ADD FULLTEXT KEY `weather` (`Weather`);
ALTER TABLE `filter` ADD FULLTEXT KEY `activities` (`Activities`);

--
-- Indexes for table `flight`
--
ALTER TABLE `flight`
  ADD PRIMARY KEY (`FlightID`);
ALTER TABLE `flight` ADD FULLTEXT KEY `departureAirport` (`DepartureAirport`);
ALTER TABLE `flight` ADD FULLTEXT KEY `arrivalLocation` (`ArrivalLocation`);
ALTER TABLE `flight` ADD FULLTEXT KEY `company` (`Company`);

--
-- Indexes for table `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`HotelID`);
ALTER TABLE `hotel` ADD FULLTEXT KEY `address` (`Address`);
ALTER TABLE `hotel` ADD FULLTEXT KEY `title` (`Title`);
ALTER TABLE `hotel` ADD FULLTEXT KEY `city` (`HotelCity`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`RoomId`),
  ADD KEY `hotel` (`Hotel`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dialogue_knowledge`
--
ALTER TABLE `dialogue_knowledge`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=491;

--
-- AUTO_INCREMENT for table `dialogue_rules`
--
ALTER TABLE `dialogue_rules`
  MODIFY `DrID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `filter`
--
ALTER TABLE `filter`
  MODIFY `FilterID` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `flight`
--
ALTER TABLE `flight`
  MODIFY `FlightID` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `HotelID` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `RoomId` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
