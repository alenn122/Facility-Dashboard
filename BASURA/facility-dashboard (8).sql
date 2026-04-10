-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2026 at 07:19 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `facility-dashboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_log`
--

CREATE TABLE `access_log` (
  `Log_id` int(11) NOT NULL,
  `User_id` int(11) DEFAULT NULL,
  `Rfid_tag` varchar(50) NOT NULL,
  `Room_id` int(11) NOT NULL,
  `Schedule_id` int(11) DEFAULT NULL,
  `Access_time` datetime DEFAULT current_timestamp(),
  `Access_type` enum('Entry','Exit') NOT NULL,
  `device_type` enum('DOOR','POWER') DEFAULT NULL,
  `Status` enum('granted','denied') DEFAULT 'denied'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `access_log`
--

INSERT INTO `access_log` (`Log_id`, `User_id`, `Rfid_tag`, `Room_id`, `Schedule_id`, `Access_time`, `Access_type`, `device_type`, `Status`) VALUES
(1, NULL, '51EACD17', 1, NULL, '2026-03-22 17:57:31', 'Entry', 'POWER', 'denied'),
(2, NULL, '612A7717', 1, NULL, '2026-03-22 17:57:34', 'Entry', 'POWER', 'denied'),
(3, NULL, '612A7717', 1, NULL, '2026-03-22 17:57:36', 'Entry', 'POWER', 'denied'),
(4, NULL, '51EACD17', 1, NULL, '2026-03-22 17:57:39', 'Entry', 'POWER', 'denied'),
(5, NULL, 'D3CBB138', 1, NULL, '2026-03-22 17:57:45', 'Entry', 'POWER', 'denied'),
(6, NULL, '255AD206', 1, NULL, '2026-03-22 17:57:54', 'Entry', 'POWER', 'denied'),
(7, NULL, '82041001', 1, NULL, '2026-03-22 17:57:57', 'Entry', 'POWER', 'granted'),
(8, NULL, '82041001', 1, NULL, '2026-03-22 17:58:01', 'Exit', 'POWER', 'granted'),
(9, NULL, '82041001', 1, NULL, '2026-03-22 17:58:03', 'Entry', 'POWER', 'granted'),
(10, NULL, '82041001', 1, NULL, '2026-03-22 17:58:07', 'Exit', 'POWER', 'granted'),
(11, NULL, '82041001', 1, NULL, '2026-03-22 17:58:11', 'Entry', 'POWER', 'granted'),
(12, NULL, '82041001', 1, NULL, '2026-03-22 17:58:16', 'Exit', 'POWER', 'granted'),
(13, NULL, '82041001', 1, NULL, '2026-03-22 17:58:20', 'Entry', 'POWER', 'granted'),
(14, NULL, '82041001', 1, NULL, '2026-03-22 17:58:23', 'Exit', 'POWER', 'granted'),
(15, NULL, '82041001', 1, NULL, '2026-03-22 17:58:26', 'Entry', 'POWER', 'granted'),
(16, NULL, '82041001', 1, NULL, '2026-03-22 17:58:33', 'Exit', 'POWER', 'granted'),
(17, NULL, '82041001', 1, NULL, '2026-03-22 17:58:36', 'Entry', 'POWER', 'granted'),
(18, NULL, '82041001', 1, NULL, '2026-03-22 17:58:40', 'Exit', 'POWER', 'granted'),
(19, NULL, '82041001', 1, NULL, '2026-03-22 17:58:43', 'Entry', 'POWER', 'granted'),
(20, NULL, '82041001', 1, NULL, '2026-03-22 17:58:47', 'Exit', 'POWER', 'granted'),
(21, NULL, '82041001', 1, NULL, '2026-03-22 17:58:49', 'Entry', 'POWER', 'granted'),
(22, NULL, '82041001', 1, NULL, '2026-03-22 17:58:53', 'Exit', 'POWER', 'granted'),
(23, NULL, '255AD206', 1, NULL, '2026-03-22 17:58:55', 'Entry', 'POWER', 'denied'),
(24, NULL, 'A4123D05', 1, NULL, '2026-03-22 17:58:58', 'Entry', 'POWER', 'denied'),
(25, NULL, '61DE6A05', 1, NULL, '2026-03-22 17:59:01', 'Entry', 'POWER', 'denied'),
(26, NULL, '61DE6A05', 1, NULL, '2026-03-22 17:59:04', 'Entry', 'POWER', 'denied'),
(27, NULL, 'D3CBB138', 1, NULL, '2026-03-22 17:59:07', 'Entry', 'POWER', 'denied'),
(28, NULL, '51EACD17', 1, NULL, '2026-03-22 17:59:10', 'Entry', 'POWER', 'denied'),
(29, NULL, '612A7717', 1, NULL, '2026-03-22 17:59:12', 'Entry', 'POWER', 'denied'),
(30, NULL, '82041001', 1, NULL, '2026-03-22 17:59:18', 'Entry', 'POWER', 'granted'),
(31, NULL, '255AD206', 1, NULL, '2026-03-22 17:59:24', 'Entry', 'POWER', 'denied'),
(32, NULL, '255AD206', 1, NULL, '2026-03-22 17:59:27', 'Entry', 'POWER', 'denied'),
(33, NULL, '82041001', 1, NULL, '2026-03-22 17:59:30', 'Exit', 'POWER', 'granted'),
(34, NULL, '82041001', 1, NULL, '2026-03-22 17:59:35', 'Entry', 'POWER', 'granted'),
(35, NULL, '82041001', 1, NULL, '2026-03-22 17:59:40', 'Exit', 'POWER', 'granted'),
(36, NULL, '612A7717', 1, NULL, '2026-03-22 20:30:07', 'Entry', 'DOOR', 'denied'),
(37, NULL, '82041001', 1, NULL, '2026-03-22 20:30:10', 'Entry', 'DOOR', 'granted'),
(38, NULL, '82041001', 1, NULL, '2026-03-22 20:30:14', 'Exit', 'DOOR', 'granted'),
(39, NULL, '82041001', 1, NULL, '2026-03-22 20:30:18', 'Entry', 'DOOR', 'granted'),
(40, NULL, '42193D05', 1, NULL, '2026-03-22 20:46:24', 'Entry', 'DOOR', 'granted'),
(41, NULL, '42193D05', 1, NULL, '2026-03-22 20:46:30', 'Exit', 'DOOR', 'granted'),
(42, NULL, '42193D05', 1, NULL, '2026-03-22 20:46:49', 'Entry', 'DOOR', 'granted'),
(43, NULL, '42193D05', 1, NULL, '2026-03-22 20:47:04', 'Exit', 'DOOR', 'granted'),
(44, NULL, '51EACD17', 1, NULL, '2026-03-22 21:02:52', 'Entry', 'DOOR', 'denied'),
(45, NULL, 'D3CBB138', 1, NULL, '2026-03-22 21:02:56', 'Entry', 'DOOR', 'denied'),
(46, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:02:59', 'Entry', 'DOOR', 'denied'),
(47, NULL, 'A4123D05', 1, NULL, '2026-03-22 21:03:02', 'Entry', 'DOOR', 'denied'),
(48, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:03:17', 'Entry', 'DOOR', 'denied'),
(49, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:03:19', 'Entry', 'DOOR', 'denied'),
(50, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:03:22', 'Entry', 'DOOR', 'denied'),
(51, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:03:24', 'Entry', 'DOOR', 'denied'),
(52, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:03:28', 'Entry', 'DOOR', 'denied'),
(53, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:03:34', 'Entry', 'DOOR', 'denied'),
(54, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:03:38', 'Entry', 'DOOR', 'denied'),
(55, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:03:41', 'Entry', 'DOOR', 'denied'),
(56, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:03:44', 'Entry', 'DOOR', 'denied'),
(57, NULL, 'D3CBB138', 1, NULL, '2026-03-22 21:03:46', 'Entry', 'DOOR', 'denied'),
(58, NULL, 'D3CBB138', 1, NULL, '2026-03-22 21:03:49', 'Entry', 'DOOR', 'denied'),
(59, NULL, 'A4123D05', 1, NULL, '2026-03-22 21:03:56', 'Entry', 'DOOR', 'denied'),
(60, NULL, '255AD206', 1, NULL, '2026-03-22 21:03:58', 'Entry', 'DOOR', 'denied'),
(61, NULL, '82041001', 1, NULL, '2026-03-22 21:04:01', 'Exit', 'DOOR', 'granted'),
(62, NULL, '82041001', 1, NULL, '2026-03-22 21:04:05', 'Entry', 'DOOR', 'granted'),
(63, NULL, '82041001', 1, NULL, '2026-03-22 21:04:09', 'Exit', 'DOOR', 'granted'),
(64, NULL, '82041001', 1, NULL, '2026-03-22 21:04:13', 'Entry', 'DOOR', 'granted'),
(65, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:04:18', 'Entry', 'DOOR', 'denied'),
(66, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:04:27', 'Entry', 'DOOR', 'denied'),
(67, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:04:33', 'Entry', 'DOOR', 'denied'),
(68, NULL, '82041001', 1, NULL, '2026-03-22 21:04:36', 'Exit', 'DOOR', 'granted'),
(69, NULL, '612A7717', 1, NULL, '2026-03-22 21:04:50', 'Entry', 'DOOR', 'denied'),
(70, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:04:53', 'Entry', 'DOOR', 'denied'),
(71, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:05:04', 'Entry', 'DOOR', 'denied'),
(72, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:05:07', 'Entry', 'DOOR', 'denied'),
(73, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:05:20', 'Entry', 'DOOR', 'denied'),
(74, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:05:22', 'Entry', 'DOOR', 'denied'),
(75, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:05:29', 'Entry', 'DOOR', 'denied'),
(76, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:05:32', 'Entry', 'DOOR', 'denied'),
(77, NULL, '51EACD17', 1, NULL, '2026-03-22 21:05:34', 'Entry', 'DOOR', 'denied'),
(78, NULL, '255AD206', 1, NULL, '2026-03-22 21:05:37', 'Entry', 'DOOR', 'denied'),
(79, NULL, '82041001', 1, NULL, '2026-03-22 21:05:41', 'Entry', 'DOOR', 'granted'),
(80, NULL, '82041001', 1, NULL, '2026-03-22 21:05:45', 'Exit', 'DOOR', 'granted'),
(81, NULL, '058E807FFD6200', 1, NULL, '2026-03-22 21:06:02', 'Entry', 'DOOR', 'denied'),
(82, NULL, '82041001', 1, NULL, '2026-03-22 21:12:30', 'Entry', 'DOOR', 'granted'),
(83, NULL, '82041001', 1, NULL, '2026-03-22 21:12:33', 'Exit', 'DOOR', 'granted'),
(84, NULL, '82041001', 1, NULL, '2026-03-22 21:12:38', 'Entry', 'DOOR', 'granted'),
(85, NULL, '255AD206', 1, NULL, '2026-03-22 21:12:41', 'Entry', 'DOOR', 'denied'),
(86, NULL, '255AD206', 1, NULL, '2026-03-22 21:13:59', 'Entry', 'DOOR', 'denied'),
(87, NULL, '255AD206', 1, NULL, '2026-03-22 21:14:02', 'Entry', 'DOOR', 'denied'),
(88, NULL, '255AD206', 1, NULL, '2026-03-22 21:14:05', 'Entry', 'DOOR', 'denied'),
(89, NULL, '255AD206', 1, NULL, '2026-03-22 21:14:07', 'Entry', 'DOOR', 'denied'),
(90, NULL, '255AD206', 1, NULL, '2026-03-22 21:14:09', 'Entry', 'DOOR', 'denied'),
(91, NULL, '255AD206', 1, NULL, '2026-03-22 21:14:12', 'Entry', 'DOOR', 'denied'),
(92, NULL, '255AD206', 1, NULL, '2026-03-22 21:14:14', 'Entry', 'DOOR', 'denied'),
(93, NULL, '255AD206', 1, NULL, '2026-03-22 21:14:17', 'Entry', 'DOOR', 'denied'),
(94, NULL, '255AD206', 1, NULL, '2026-03-22 21:14:19', 'Entry', 'DOOR', 'denied'),
(95, NULL, '255AD206', 1, NULL, '2026-03-22 21:14:24', 'Entry', 'DOOR', 'denied'),
(96, NULL, '255AD206', 1, NULL, '2026-03-22 21:14:27', 'Entry', 'DOOR', 'denied'),
(97, NULL, '255AD206', 1, NULL, '2026-03-22 21:14:29', 'Entry', 'DOOR', 'denied'),
(98, NULL, 'D3CBB138', 1, NULL, '2026-03-22 21:15:01', 'Entry', 'DOOR', 'denied'),
(99, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:15:07', 'Entry', 'DOOR', 'denied'),
(100, NULL, '51EACD17', 1, NULL, '2026-03-22 21:15:43', 'Entry', 'DOOR', 'denied'),
(101, NULL, '51EACD17', 1, NULL, '2026-03-22 21:15:48', 'Entry', 'DOOR', 'denied'),
(102, NULL, '255AD206', 1, NULL, '2026-03-22 21:16:18', 'Entry', 'DOOR', 'denied'),
(103, NULL, '255AD206', 1, NULL, '2026-03-22 21:16:34', 'Entry', 'DOOR', 'denied'),
(104, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:16:53', 'Entry', 'DOOR', 'denied'),
(105, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:16:56', 'Entry', 'DOOR', 'denied'),
(106, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:16:59', 'Entry', 'DOOR', 'denied'),
(107, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:17:12', 'Entry', 'DOOR', 'denied'),
(108, NULL, '61DE6A05', 1, NULL, '2026-03-22 21:17:35', 'Entry', 'DOOR', 'denied'),
(109, NULL, 'A4123D05', 1, NULL, '2026-03-22 21:17:38', 'Entry', 'DOOR', 'denied'),
(110, NULL, '255AD206', 1, NULL, '2026-03-22 21:17:42', 'Entry', 'DOOR', 'denied'),
(111, NULL, '51EACD17', 1, NULL, '2026-03-22 21:17:45', 'Entry', 'DOOR', 'denied'),
(112, NULL, '255AD206', 1, NULL, '2026-03-22 21:17:48', 'Entry', 'DOOR', 'denied'),
(113, NULL, '51EACD17', 1, NULL, '2026-03-22 21:19:54', 'Entry', 'DOOR', 'denied'),
(114, NULL, '51EACD17', 1, NULL, '2026-03-22 21:20:09', 'Entry', 'DOOR', 'denied'),
(115, NULL, '255AD206', 1, NULL, '2026-03-22 21:20:11', 'Entry', 'DOOR', 'denied'),
(116, NULL, 'A4123D05', 1, NULL, '2026-03-22 21:20:15', 'Entry', 'DOOR', 'denied'),
(117, NULL, 'D3CBB138', 1, NULL, '2026-03-22 21:20:17', 'Entry', 'DOOR', 'denied'),
(118, NULL, 'D3CBB138', 1, NULL, '2026-03-22 21:20:37', 'Entry', 'DOOR', 'denied'),
(119, NULL, 'D3CBB138', 1, NULL, '2026-03-22 21:20:40', 'Entry', 'DOOR', 'denied'),
(120, NULL, 'D3CBB138', 1, NULL, '2026-03-22 21:20:43', 'Entry', 'DOOR', 'denied'),
(121, NULL, 'D3CBB138', 1, NULL, '2026-03-22 21:24:10', 'Entry', 'DOOR', 'denied'),
(122, NULL, '51EACD17', 1, NULL, '2026-03-22 21:24:13', 'Entry', 'DOOR', 'denied'),
(123, NULL, '82041001', 1, NULL, '2026-03-22 21:24:16', 'Exit', 'DOOR', 'granted'),
(124, NULL, '82041001', 1, NULL, '2026-03-22 21:24:20', 'Entry', 'DOOR', 'granted'),
(125, NULL, '82041001', 1, NULL, '2026-03-22 21:24:23', 'Exit', 'DOOR', 'granted'),
(126, NULL, '82041001', 1, NULL, '2026-03-23 15:26:23', 'Entry', 'POWER', 'granted'),
(127, NULL, '82041001', 1, NULL, '2026-03-23 15:26:27', 'Exit', 'POWER', 'granted'),
(128, NULL, '82041001', 1, NULL, '2026-03-23 15:26:30', 'Entry', 'POWER', 'granted'),
(129, NULL, '82041001', 1, NULL, '2026-03-23 15:26:34', 'Exit', 'POWER', 'granted'),
(130, NULL, '82041001', 1, NULL, '2026-03-23 15:26:37', 'Entry', 'POWER', 'granted'),
(131, NULL, '82041001', 1, NULL, '2026-03-23 15:26:42', 'Exit', 'POWER', 'granted'),
(132, NULL, '82041001', 1, NULL, '2026-03-23 15:26:47', 'Entry', 'POWER', 'granted'),
(133, NULL, '82041001', 1, NULL, '2026-03-23 15:26:51', 'Exit', 'POWER', 'granted'),
(134, NULL, '82041001', 1, NULL, '2026-03-23 15:29:38', 'Entry', 'POWER', 'granted'),
(135, NULL, '82041001', 1, NULL, '2026-03-23 15:29:43', 'Exit', 'POWER', 'granted'),
(136, NULL, '51EACD17', 1, NULL, '2026-03-23 15:43:17', 'Entry', 'POWER', 'denied'),
(137, NULL, '51EACD17', 1, NULL, '2026-03-23 15:43:22', 'Entry', 'POWER', 'denied'),
(138, NULL, '51EACD17', 1, NULL, '2026-03-23 15:43:25', 'Entry', 'POWER', 'denied'),
(139, NULL, '51EACD17', 1, NULL, '2026-03-23 15:43:31', 'Entry', 'POWER', 'denied'),
(140, NULL, '255AD206', 1, NULL, '2026-03-23 15:43:33', 'Entry', 'POWER', 'denied'),
(141, NULL, '255AD206', 1, NULL, '2026-03-23 15:43:37', 'Entry', 'POWER', 'denied'),
(142, NULL, '255AD206', 1, NULL, '2026-03-23 15:43:39', 'Entry', 'POWER', 'denied'),
(143, NULL, '255AD206', 1, NULL, '2026-03-23 15:43:43', 'Entry', 'POWER', 'denied'),
(144, NULL, '255AD206', 1, NULL, '2026-03-23 15:43:46', 'Entry', 'POWER', 'denied'),
(145, NULL, '255AD206', 1, NULL, '2026-03-23 15:43:50', 'Entry', 'POWER', 'denied'),
(146, NULL, '51EACD17', 1, NULL, '2026-03-23 15:45:19', 'Entry', 'POWER', 'denied'),
(147, NULL, '255AD206', 1, NULL, '2026-03-23 15:45:22', 'Entry', 'POWER', 'denied'),
(148, NULL, '61DE6A05', 1, NULL, '2026-03-23 15:55:14', 'Entry', 'DOOR', 'denied'),
(149, NULL, '82041001', 1, NULL, '2026-03-23 15:55:17', 'Entry', 'DOOR', 'granted'),
(150, NULL, '82041001', 1, NULL, '2026-03-23 15:55:19', 'Exit', 'DOOR', 'granted'),
(151, NULL, '82041001', 1, NULL, '2026-03-23 15:55:24', 'Entry', 'DOOR', 'granted'),
(152, NULL, '82041001', 1, NULL, '2026-03-23 15:55:29', 'Exit', 'DOOR', 'granted'),
(153, NULL, '82041001', 1, NULL, '2026-03-23 15:55:34', 'Entry', 'DOOR', 'granted'),
(154, NULL, '82041001', 1, NULL, '2026-03-23 15:55:36', 'Exit', 'DOOR', 'granted'),
(155, NULL, '82041001', 1, NULL, '2026-03-23 15:55:43', 'Entry', 'DOOR', 'granted'),
(156, NULL, '82041001', 1, NULL, '2026-03-23 15:55:49', 'Exit', 'DOOR', 'granted'),
(157, NULL, 'D3CBB138', 1, NULL, '2026-03-23 15:55:51', 'Entry', 'DOOR', 'denied'),
(158, NULL, '82041001', 1, NULL, '2026-03-23 15:55:54', 'Entry', 'DOOR', 'granted'),
(159, NULL, 'D3CBB138', 1, NULL, '2026-03-23 15:55:55', 'Entry', 'DOOR', 'denied'),
(160, NULL, 'D3CBB138', 1, NULL, '2026-03-23 15:55:58', 'Entry', 'DOOR', 'denied'),
(161, NULL, '82041001', 1, NULL, '2026-03-23 15:56:04', 'Exit', 'DOOR', 'granted'),
(162, NULL, 'D3CBB138', 1, NULL, '2026-03-23 15:56:05', 'Entry', 'DOOR', 'denied'),
(163, NULL, 'D3CBB138', 1, NULL, '2026-03-23 15:56:07', 'Entry', 'DOOR', 'denied'),
(164, NULL, '82041001', 1, NULL, '2026-03-23 15:56:08', 'Entry', 'DOOR', 'granted'),
(165, NULL, '82041001', 1, NULL, '2026-03-23 15:56:12', 'Exit', 'DOOR', 'granted'),
(166, NULL, 'D3CBB138', 1, NULL, '2026-03-23 15:56:13', 'Entry', 'DOOR', 'denied'),
(167, NULL, '82041001', 1, NULL, '2026-03-23 15:56:16', 'Entry', 'DOOR', 'granted'),
(168, NULL, 'D3CBB138', 1, NULL, '2026-03-23 15:56:16', 'Entry', 'DOOR', 'denied'),
(169, NULL, 'D3CBB138', 1, NULL, '2026-03-23 15:56:19', 'Entry', 'DOOR', 'denied'),
(170, NULL, '82041001', 1, NULL, '2026-03-23 15:56:22', 'Exit', 'DOOR', 'granted'),
(171, NULL, '82041001', 1, NULL, '2026-03-23 15:56:26', 'Entry', 'DOOR', 'granted'),
(172, NULL, 'D3CBB138', 1, NULL, '2026-03-23 15:56:49', 'Entry', 'DOOR', 'denied'),
(173, NULL, 'D3CBB138', 1, NULL, '2026-03-23 15:57:09', 'Entry', 'DOOR', 'denied'),
(174, NULL, '82041001', 1, NULL, '2026-03-23 15:57:14', 'Exit', 'DOOR', 'granted'),
(175, NULL, '82041001', 1, NULL, '2026-03-23 15:57:18', 'Entry', 'DOOR', 'granted'),
(176, NULL, '82041001', 1, NULL, '2026-03-23 15:57:22', 'Exit', 'DOOR', 'granted'),
(177, NULL, '82041001', 1, NULL, '2026-03-23 15:57:29', 'Entry', 'DOOR', 'granted'),
(178, NULL, '82041001', 1, NULL, '2026-03-23 15:57:33', 'Exit', 'DOOR', 'granted'),
(179, NULL, '82041001', 1, NULL, '2026-03-23 15:57:37', 'Entry', 'DOOR', 'granted'),
(180, NULL, '82041001', 1, NULL, '2026-03-23 15:57:58', 'Exit', 'DOOR', 'granted'),
(181, NULL, 'D3CBB138', 1, NULL, '2026-03-23 15:58:08', 'Entry', 'DOOR', 'denied'),
(182, NULL, '82041001', 1, NULL, '2026-03-23 15:58:33', 'Entry', 'DOOR', 'granted'),
(183, NULL, '82041001', 1, NULL, '2026-03-23 15:58:35', 'Exit', 'DOOR', 'granted'),
(184, NULL, '82041001', 1, NULL, '2026-03-23 15:58:37', 'Entry', 'DOOR', 'granted'),
(185, NULL, '82041001', 1, NULL, '2026-03-23 15:58:39', 'Exit', 'DOOR', 'granted'),
(186, NULL, '82041001', 1, NULL, '2026-03-23 15:58:44', 'Entry', 'DOOR', 'granted'),
(187, NULL, '82041001', 1, NULL, '2026-03-23 15:58:48', 'Exit', 'DOOR', 'granted'),
(188, NULL, '82041001', 1, NULL, '2026-03-23 15:58:49', 'Entry', 'DOOR', 'granted'),
(189, NULL, '82041001', 1, NULL, '2026-03-23 15:58:55', 'Exit', 'DOOR', 'granted'),
(190, NULL, 'D3CBB138', 1, NULL, '2026-03-23 15:58:58', 'Entry', 'DOOR', 'denied'),
(191, NULL, '82041001', 1, NULL, '2026-03-23 15:58:59', 'Entry', 'DOOR', 'granted'),
(192, NULL, '82041001', 1, NULL, '2026-03-23 15:59:03', 'Exit', 'DOOR', 'granted'),
(193, NULL, 'D3CBB138', 1, NULL, '2026-03-23 15:59:04', 'Entry', 'DOOR', 'denied'),
(194, NULL, 'D3CBB138', 1, NULL, '2026-03-23 15:59:06', 'Entry', 'DOOR', 'denied'),
(195, NULL, '82041001', 1, NULL, '2026-03-23 15:59:07', 'Entry', 'DOOR', 'granted'),
(196, NULL, 'D3CBB138', 1, NULL, '2026-03-23 15:59:49', 'Entry', 'DOOR', 'denied'),
(197, NULL, '82041001', 1, NULL, '2026-03-23 15:59:58', 'Exit', 'DOOR', 'granted'),
(198, NULL, '82041001', 1, NULL, '2026-03-23 16:00:03', 'Entry', 'DOOR', 'granted'),
(199, NULL, '82041001', 1, NULL, '2026-03-23 16:00:09', 'Exit', 'DOOR', 'granted'),
(200, NULL, '82041001', 1, NULL, '2026-03-23 16:00:18', 'Entry', 'DOOR', 'granted'),
(201, NULL, '82041001', 1, NULL, '2026-03-23 16:00:33', 'Exit', 'POWER', 'granted'),
(202, NULL, '82041001', 1, NULL, '2026-03-23 16:00:36', 'Entry', 'POWER', 'granted'),
(203, NULL, '82041001', 1, NULL, '2026-03-23 16:00:43', 'Exit', 'POWER', 'granted'),
(204, NULL, '42193D05', 1, NULL, '2026-03-25 00:35:57', 'Entry', 'DOOR', 'granted'),
(205, NULL, '42193D05', 1, NULL, '2026-03-25 00:36:02', 'Exit', 'DOOR', 'granted'),
(206, NULL, '42193D05', 1, NULL, '2026-03-25 00:36:07', 'Entry', 'DOOR', 'granted'),
(207, NULL, '42193D05', 1, NULL, '2026-03-25 00:36:15', 'Exit', 'DOOR', 'granted'),
(208, NULL, '42193D05', 1, NULL, '2026-03-25 00:36:55', 'Entry', 'DOOR', 'granted'),
(209, NULL, '42193D05', 1, NULL, '2026-03-25 00:37:29', 'Exit', 'DOOR', 'granted'),
(210, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:38:52', 'Entry', 'DOOR', 'granted'),
(211, NULL, '42193D05', 1, NULL, '2026-03-25 00:39:04', 'Entry', 'DOOR', 'denied'),
(212, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:39:33', 'Exit', 'DOOR', 'granted'),
(213, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:39:43', 'Entry', 'DOOR', 'granted'),
(214, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:40:48', 'Exit', 'DOOR', 'granted'),
(215, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:40:55', 'Entry', 'DOOR', 'granted'),
(216, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:43:17', 'Exit', 'DOOR', 'granted'),
(217, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:43:22', 'Entry', 'DOOR', 'granted'),
(218, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:44:04', 'Exit', 'POWER', 'denied'),
(219, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:44:06', 'Exit', 'POWER', 'denied'),
(220, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:44:09', 'Exit', 'POWER', 'denied'),
(221, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:44:12', 'Exit', 'POWER', 'denied'),
(222, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:44:14', 'Exit', 'POWER', 'denied'),
(223, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:44:17', 'Exit', 'POWER', 'denied'),
(224, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:44:19', 'Exit', 'POWER', 'denied'),
(225, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:44:21', 'Exit', 'POWER', 'denied'),
(226, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:46:02', 'Exit', 'POWER', 'denied'),
(227, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:46:04', 'Exit', 'POWER', 'denied'),
(228, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:46:50', 'Exit', 'DOOR', 'granted'),
(229, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:46:55', 'Entry', 'DOOR', 'granted'),
(230, NULL, '42193D05', 1, NULL, '2026-03-25 00:47:01', 'Entry', 'DOOR', 'denied'),
(231, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:47:05', 'Exit', 'DOOR', 'granted'),
(232, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:47:12', 'Entry', 'DOOR', 'granted'),
(233, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:47:18', 'Exit', 'DOOR', 'granted'),
(234, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:47:22', 'Entry', 'DOOR', 'granted'),
(235, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:47:26', 'Exit', 'DOOR', 'granted'),
(236, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:47:47', 'Entry', 'DOOR', 'granted'),
(237, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:47:51', 'Exit', 'DOOR', 'granted'),
(238, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:47:55', 'Entry', 'DOOR', 'granted'),
(239, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:47:59', 'Exit', 'DOOR', 'granted'),
(240, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:48:02', 'Entry', 'DOOR', 'granted'),
(241, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:48:06', 'Exit', 'DOOR', 'granted'),
(242, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:48:15', 'Entry', 'DOOR', 'granted'),
(243, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:48:19', 'Exit', 'DOOR', 'granted'),
(244, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:48:23', 'Entry', 'DOOR', 'granted'),
(245, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:48:26', 'Exit', 'DOOR', 'granted'),
(246, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:48:30', 'Entry', 'DOOR', 'granted'),
(247, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:48:34', 'Exit', 'DOOR', 'granted'),
(248, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:48:38', 'Entry', 'DOOR', 'granted'),
(249, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:48:51', 'Exit', 'DOOR', 'granted'),
(250, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:48:55', 'Entry', 'DOOR', 'granted'),
(251, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:48:59', 'Exit', 'DOOR', 'granted'),
(252, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:49:04', 'Entry', 'DOOR', 'granted'),
(253, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:49:08', 'Exit', 'DOOR', 'granted'),
(254, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:49:31', 'Entry', 'DOOR', 'granted'),
(255, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:49:42', 'Exit', 'DOOR', 'granted'),
(256, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:49:48', 'Entry', 'DOOR', 'granted'),
(257, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:49:56', 'Exit', 'DOOR', 'granted'),
(258, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:50:00', 'Entry', 'DOOR', 'granted'),
(259, NULL, '9DEDD106', 1, NULL, '2026-03-25 00:50:04', 'Exit', 'DOOR', 'granted'),
(260, NULL, '9DEDD106', 1, NULL, '2026-03-26 17:14:58', 'Entry', 'POWER', 'denied'),
(261, NULL, '9DEDD106', 1, NULL, '2026-03-26 17:15:04', 'Entry', 'POWER', 'denied'),
(262, NULL, '9DEDD106', 1, NULL, '2026-03-26 17:15:24', 'Entry', 'POWER', 'denied'),
(263, NULL, '9DEDD106', 1, NULL, '2026-03-26 17:15:28', 'Entry', 'POWER', 'denied'),
(264, NULL, '9DEDD106', 1, NULL, '2026-03-26 17:16:18', 'Entry', 'POWER', 'denied'),
(265, NULL, '9DEDD106', 1, NULL, '2026-03-26 17:16:21', 'Entry', 'POWER', 'denied'),
(266, NULL, '9DEDD106', 1, NULL, '2026-03-26 17:16:24', 'Entry', 'POWER', 'denied'),
(267, NULL, '2A914205', 1, NULL, '2026-03-26 17:26:27', 'Entry', 'POWER', 'denied'),
(268, NULL, '2A914205', 1, NULL, '2026-03-26 17:27:45', 'Entry', 'POWER', 'granted'),
(269, NULL, '2A914205', 1, NULL, '2026-03-26 17:33:33', 'Exit', 'POWER', 'granted'),
(270, NULL, '2A914205', 1, NULL, '2026-03-26 17:33:42', 'Entry', 'POWER', 'granted'),
(271, NULL, '2A914205', 1, NULL, '2026-03-26 17:49:22', 'Exit', 'POWER', 'granted'),
(272, NULL, '2A914205', 1, NULL, '2026-03-26 17:49:30', 'Entry', 'POWER', 'granted'),
(273, NULL, '2A914205', 1, NULL, '2026-03-26 17:49:42', 'Exit', 'POWER', 'granted'),
(274, NULL, '2A914205', 1, NULL, '2026-03-26 17:51:09', 'Entry', 'POWER', 'granted'),
(275, NULL, '42193D05', 1, NULL, '2026-03-26 19:34:25', 'Entry', 'POWER', 'denied'),
(276, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:34:28', 'Entry', 'POWER', 'denied'),
(277, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:34:32', 'Entry', 'DOOR', 'denied'),
(278, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:34:35', 'Entry', 'DOOR', 'denied'),
(279, NULL, '42193D05', 1, NULL, '2026-03-26 19:34:39', 'Entry', 'DOOR', 'denied'),
(280, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:35:15', 'Entry', 'POWER', 'denied'),
(281, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:35:17', 'Entry', 'DOOR', 'granted'),
(282, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:35:22', 'Exit', 'DOOR', 'granted'),
(283, NULL, '42193D05', 1, NULL, '2026-03-26 19:35:29', 'Entry', 'DOOR', 'denied'),
(284, NULL, '42193D05', 1, NULL, '2026-03-26 19:35:34', 'Entry', 'DOOR', 'denied'),
(285, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:37:07', 'Entry', 'POWER', 'denied'),
(286, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:37:09', 'Entry', 'DOOR', 'granted'),
(287, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:37:11', 'Exit', 'POWER', 'denied'),
(288, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:37:14', 'Exit', 'DOOR', 'granted'),
(289, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:52:45', 'Entry', 'DOOR', 'granted'),
(290, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:53:04', 'Exit', 'DOOR', 'granted'),
(291, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:53:15', 'Entry', 'DOOR', 'granted'),
(292, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:53:21', 'Exit', 'DOOR', 'granted'),
(293, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:53:29', 'Entry', 'DOOR', 'granted'),
(294, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:53:35', 'Exit', 'POWER', 'denied'),
(295, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:53:38', 'Exit', 'POWER', 'denied'),
(296, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:53:40', 'Exit', 'POWER', 'denied'),
(297, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:53:43', 'Exit', 'POWER', 'denied'),
(298, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:54:10', 'Exit', 'POWER', 'denied'),
(299, NULL, '9DEDD106', 1, NULL, '2026-03-26 19:54:39', 'Exit', 'POWER', 'denied'),
(300, NULL, '51EACD17', 1, NULL, '2026-03-27 21:51:25', 'Entry', 'POWER', 'denied'),
(301, NULL, '51EACD17', 1, NULL, '2026-03-27 21:51:27', 'Entry', 'POWER', 'denied'),
(302, NULL, '51EACD17', 1, NULL, '2026-03-27 21:51:30', 'Entry', 'POWER', 'denied'),
(303, NULL, '51EACD17', 1, NULL, '2026-03-27 21:51:33', 'Entry', 'POWER', 'denied'),
(304, NULL, '51EACD17', 1, NULL, '2026-03-27 21:51:35', 'Entry', 'POWER', 'denied'),
(305, NULL, '51EACD17', 1, NULL, '2026-03-27 21:51:38', 'Entry', 'POWER', 'denied'),
(306, NULL, '51EACD17', 1, NULL, '2026-03-27 21:52:59', 'Entry', 'POWER', 'granted'),
(307, NULL, '51EACD17', 1, NULL, '2026-03-27 21:53:08', 'Exit', 'POWER', 'granted'),
(308, NULL, '51EACD17', 1, NULL, '2026-03-27 21:53:23', 'Entry', 'POWER', 'granted'),
(309, NULL, '51EACD17', 1, NULL, '2026-03-27 21:53:37', 'Exit', 'POWER', 'granted'),
(310, NULL, '51EACD17', 1, NULL, '2026-03-27 21:53:42', 'Entry', 'POWER', 'granted'),
(311, NULL, '51EACD17', 1, NULL, '2026-03-27 21:53:52', 'Exit', 'POWER', 'granted'),
(312, NULL, '51EACD17', 1, NULL, '2026-03-27 21:56:17', 'Entry', 'POWER', 'granted'),
(313, NULL, '51EACD17', 1, NULL, '2026-03-27 21:56:26', 'Exit', 'POWER', 'granted'),
(314, NULL, '51EACD17', 1, NULL, '2026-03-27 21:56:29', 'Entry', 'POWER', 'granted'),
(315, NULL, '51EACD17', 1, NULL, '2026-03-27 21:56:33', 'Exit', 'POWER', 'granted'),
(316, NULL, '51EACD17', 1, NULL, '2026-03-27 21:56:54', 'Entry', 'POWER', 'granted'),
(317, NULL, '51EACD17', 1, NULL, '2026-03-27 21:56:58', 'Exit', 'POWER', 'granted'),
(318, NULL, '51EACD17', 1, NULL, '2026-03-27 21:57:16', 'Entry', 'POWER', 'granted'),
(319, NULL, '51EACD17', 1, NULL, '2026-03-27 21:57:25', 'Exit', 'POWER', 'granted'),
(320, NULL, '51EACD17', 1, NULL, '2026-03-27 21:57:28', 'Entry', 'POWER', 'granted'),
(321, NULL, '51EACD17', 1, NULL, '2026-03-27 21:57:33', 'Exit', 'POWER', 'granted'),
(322, NULL, '51EACD17', 1, NULL, '2026-03-27 21:58:56', 'Entry', 'POWER', 'granted'),
(323, NULL, '51EACD17', 1, NULL, '2026-03-27 21:59:01', 'Exit', 'POWER', 'granted'),
(324, NULL, '51EACD17', 1, NULL, '2026-03-27 22:02:39', 'Entry', 'POWER', 'granted'),
(325, NULL, '51EACD17', 1, NULL, '2026-03-27 22:02:43', 'Exit', 'POWER', 'granted'),
(326, NULL, '51EACD17', 1, NULL, '2026-03-27 22:03:41', 'Entry', 'POWER', 'granted'),
(327, NULL, '51EACD17', 1, NULL, '2026-03-27 22:10:55', 'Exit', 'POWER', 'granted'),
(328, NULL, '51EACD17', 1, NULL, '2026-03-27 22:10:57', 'Entry', 'POWER', 'granted'),
(329, NULL, '51EACD17', 1, NULL, '2026-03-27 22:11:03', 'Exit', 'POWER', 'granted'),
(330, NULL, '51EACD17', 1, NULL, '2026-03-27 22:11:05', 'Entry', 'POWER', 'granted'),
(331, NULL, '51EACD17', 1, NULL, '2026-03-27 22:11:09', 'Exit', 'POWER', 'granted'),
(332, NULL, '51EACD17', 1, NULL, '2026-03-27 22:11:11', 'Entry', 'POWER', 'granted'),
(333, NULL, '51EACD17', 1, NULL, '2026-03-27 22:11:15', 'Exit', 'POWER', 'granted'),
(334, NULL, '51EACD17', 1, NULL, '2026-03-27 22:15:47', 'Entry', 'POWER', 'granted'),
(335, NULL, '51EACD17', 1, NULL, '2026-03-27 22:15:53', 'Exit', 'POWER', 'granted'),
(336, NULL, '51EACD17', 1, NULL, '2026-03-27 22:15:56', 'Entry', 'POWER', 'granted'),
(337, NULL, '51EACD17', 1, NULL, '2026-03-27 22:16:00', 'Exit', 'POWER', 'granted'),
(338, NULL, '51EACD17', 1, NULL, '2026-03-27 22:16:02', 'Entry', 'POWER', 'granted'),
(339, NULL, '51EACD17', 1, NULL, '2026-03-27 22:16:09', 'Exit', 'POWER', 'granted'),
(340, NULL, '51EACD17', 1, NULL, '2026-03-27 22:16:10', 'Entry', 'POWER', 'granted'),
(341, NULL, '51EACD17', 1, NULL, '2026-03-27 22:16:14', 'Exit', 'POWER', 'granted'),
(342, NULL, '51EACD17', 1, NULL, '2026-03-27 23:05:52', 'Entry', 'DOOR', 'granted'),
(343, NULL, '51EACD17', 1, NULL, '2026-03-27 23:05:57', 'Exit', 'DOOR', 'granted'),
(344, NULL, '51EACD17', 1, NULL, '2026-03-27 23:06:03', 'Entry', 'DOOR', 'granted'),
(345, NULL, '51EACD17', 1, NULL, '2026-03-27 23:06:08', 'Exit', 'DOOR', 'granted'),
(346, NULL, '51EACD17', 1, NULL, '2026-03-27 23:06:17', 'Entry', 'DOOR', 'granted'),
(347, NULL, '51EACD17', 1, NULL, '2026-03-27 23:06:22', 'Exit', 'DOOR', 'granted'),
(348, NULL, '51EACD17', 1, NULL, '2026-03-27 23:10:20', 'Entry', 'DOOR', 'granted'),
(349, NULL, '51EACD17', 1, NULL, '2026-03-27 23:10:25', 'Exit', 'DOOR', 'granted'),
(350, NULL, '51EACD17', 1, NULL, '2026-03-27 23:10:32', 'Entry', 'DOOR', 'granted'),
(351, NULL, '51EACD17', 1, NULL, '2026-03-27 23:10:36', 'Exit', 'DOOR', 'granted'),
(352, NULL, '51EACD17', 1, NULL, '2026-03-27 23:11:08', 'Entry', 'DOOR', 'granted'),
(353, NULL, '51EACD17', 1, NULL, '2026-03-27 23:11:12', 'Exit', 'POWER', 'granted'),
(354, NULL, '51EACD17', 1, NULL, '2026-03-27 23:11:15', 'Entry', 'POWER', 'granted'),
(355, NULL, '51EACD17', 1, NULL, '2026-03-27 23:11:17', 'Exit', 'DOOR', 'granted'),
(356, NULL, '51EACD17', 1, NULL, '2026-03-27 23:11:26', 'Entry', 'DOOR', 'granted'),
(357, NULL, '51EACD17', 1, NULL, '2026-03-27 23:11:30', 'Exit', 'POWER', 'granted'),
(358, NULL, '51EACD17', 1, NULL, '2026-03-27 23:11:35', 'Entry', 'DOOR', 'granted'),
(359, NULL, '51EACD17', 1, NULL, '2026-03-27 23:11:41', 'Exit', 'POWER', 'granted'),
(360, NULL, '51EACD17', 1, NULL, '2026-03-27 23:11:43', 'Entry', 'POWER', 'granted'),
(361, NULL, '51EACD17', 1, NULL, '2026-03-27 23:11:45', 'Exit', 'DOOR', 'granted'),
(362, NULL, '51EACD17', 1, NULL, '2026-03-27 23:11:49', 'Entry', 'POWER', 'granted'),
(363, NULL, '51EACD17', 1, NULL, '2026-03-27 23:11:51', 'Exit', 'DOOR', 'granted'),
(364, NULL, '51EACD17', 1, NULL, '2026-03-27 23:11:54', 'Entry', 'POWER', 'granted'),
(365, NULL, '51EACD17', 1, NULL, '2026-03-27 23:11:57', 'Exit', 'DOOR', 'granted'),
(366, NULL, '51EACD17', 1, NULL, '2026-03-27 23:11:59', 'Entry', 'POWER', 'granted'),
(367, NULL, '51EACD17', 1, NULL, '2026-03-27 23:12:03', 'Exit', 'POWER', 'granted'),
(368, NULL, '51EACD17', 1, NULL, '2026-03-27 23:12:06', 'Entry', 'POWER', 'granted'),
(369, NULL, '51EACD17', 1, NULL, '2026-03-27 23:12:11', 'Exit', 'POWER', 'granted'),
(370, NULL, '51EACD17', 1, NULL, '2026-03-27 23:52:46', 'Entry', 'POWER', 'granted'),
(371, NULL, '51EACD17', 1, NULL, '2026-03-27 23:52:53', 'Exit', 'POWER', 'granted'),
(372, NULL, '51EACD17', 1, NULL, '2026-03-27 23:52:57', 'Entry', 'POWER', 'granted'),
(373, NULL, '51EACD17', 1, NULL, '2026-03-27 23:53:05', 'Exit', 'POWER', 'granted'),
(374, NULL, '51EACD17', 1, NULL, '2026-03-27 23:53:08', 'Entry', 'POWER', 'granted'),
(375, NULL, '51EACD17', 1, NULL, '2026-03-27 23:53:12', 'Exit', 'POWER', 'granted'),
(376, NULL, '51EACD17', 1, NULL, '2026-03-27 23:57:05', 'Entry', 'POWER', 'granted'),
(377, NULL, '51EACD17', 1, NULL, '2026-03-27 23:57:12', 'Exit', 'POWER', 'granted'),
(378, NULL, '51EACD17', 1, NULL, '2026-03-27 23:57:20', 'Entry', 'POWER', 'granted'),
(379, NULL, '51EACD17', 1, NULL, '2026-03-27 23:57:30', 'Exit', 'POWER', 'granted'),
(380, NULL, '51EACD17', 1, NULL, '2026-03-28 00:01:41', 'Entry', 'DOOR', 'granted'),
(381, NULL, '51EACD17', 1, NULL, '2026-03-28 00:01:51', 'Exit', 'DOOR', 'granted'),
(382, NULL, '51EACD17', 1, NULL, '2026-03-28 00:01:56', 'Entry', 'DOOR', 'granted'),
(383, NULL, '51EACD17', 1, NULL, '2026-03-28 00:02:03', 'Exit', 'DOOR', 'granted'),
(384, NULL, '51EACD17', 1, NULL, '2026-03-28 02:41:23', 'Entry', 'DOOR', 'granted'),
(385, NULL, '51EACD17', 1, NULL, '2026-03-28 02:41:27', 'Exit', 'DOOR', 'granted'),
(386, NULL, '51EACD17', 1, NULL, '2026-03-28 02:41:28', 'Entry', 'POWER', 'granted'),
(387, NULL, '51EACD17', 1, NULL, '2026-03-28 02:41:32', 'Exit', 'POWER', 'granted'),
(388, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:19:12', 'Exit', 'POWER', 'granted'),
(389, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:19:21', 'Entry', 'POWER', 'granted'),
(390, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:19:33', 'Exit', 'POWER', 'granted'),
(391, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:19:49', 'Entry', 'DOOR', 'granted'),
(392, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:19:56', 'Exit', 'DOOR', 'granted'),
(393, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:20:16', 'Entry', 'POWER', 'granted'),
(394, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:20:28', 'Exit', 'POWER', 'granted'),
(395, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:20:36', 'Entry', 'POWER', 'granted'),
(396, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:20:42', 'Exit', 'POWER', 'granted'),
(397, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:21:03', 'Entry', 'POWER', 'granted'),
(398, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:21:11', 'Exit', 'POWER', 'granted'),
(399, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:21:22', 'Entry', 'POWER', 'granted'),
(400, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:21:28', 'Exit', 'POWER', 'granted'),
(401, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:22:09', 'Entry', 'POWER', 'granted'),
(402, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:22:13', 'Exit', 'POWER', 'granted'),
(403, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:22:16', 'Entry', 'DOOR', 'granted'),
(404, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:22:23', 'Exit', 'DOOR', 'granted'),
(405, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:22:27', 'Entry', 'DOOR', 'granted'),
(406, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:22:31', 'Exit', 'DOOR', 'granted'),
(407, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:22:38', 'Entry', 'DOOR', 'granted'),
(408, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:22:42', 'Exit', 'DOOR', 'granted'),
(409, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:22:50', 'Entry', 'POWER', 'granted'),
(410, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:22:54', 'Exit', 'POWER', 'granted'),
(411, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:23:12', 'Entry', 'DOOR', 'granted'),
(412, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:23:22', 'Exit', 'POWER', 'granted'),
(413, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:23:27', 'Entry', 'POWER', 'granted'),
(414, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:23:32', 'Exit', 'POWER', 'granted'),
(415, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:23:36', 'Entry', 'DOOR', 'granted'),
(416, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:24:19', 'Exit', 'DOOR', 'granted'),
(417, NULL, '61DE6A05', 1, NULL, '2026-03-28 09:24:40', 'Entry', 'DOOR', 'denied'),
(418, NULL, '61DE6A05', 1, NULL, '2026-03-28 09:24:46', 'Entry', 'POWER', 'denied'),
(419, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:24:59', 'Entry', 'DOOR', 'granted'),
(420, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:25:28', 'Exit', 'POWER', 'granted'),
(421, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:25:31', 'Entry', 'POWER', 'granted'),
(422, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:25:35', 'Exit', 'POWER', 'granted'),
(423, NULL, '9DEDD106', 1, NULL, '2026-03-28 09:26:34', 'Entry', 'DOOR', 'granted'),
(424, 28, '61DE6A05', 1, NULL, '2026-03-28 10:44:55', 'Entry', 'DOOR', 'granted'),
(425, 28, '61DE6A05', 1, NULL, '2026-03-28 10:46:33', 'Exit', 'POWER', 'denied'),
(426, 26, '42193D05', 1, NULL, '2026-03-28 10:50:25', 'Entry', 'DOOR', 'granted'),
(427, 26, '42193D05', 1, NULL, '2026-03-28 10:50:37', 'Exit', 'POWER', 'granted'),
(428, 26, '42193D05', 1, NULL, '2026-03-28 10:50:41', 'Entry', 'POWER', 'granted'),
(429, 26, '42193D05', 1, NULL, '2026-03-28 10:53:33', 'Exit', 'POWER', 'denied'),
(430, 24, '9DEDD106', 1, NULL, '2026-03-28 10:54:18', 'Entry', 'POWER', 'granted'),
(431, 24, '9DEDD106', 1, NULL, '2026-03-28 10:54:23', 'Exit', 'POWER', 'granted'),
(432, 24, '9DEDD106', 1, NULL, '2026-03-28 11:05:53', 'Entry', 'DOOR', 'granted'),
(433, 24, '9DEDD106', 1, NULL, '2026-03-28 11:05:59', 'Exit', 'POWER', 'granted'),
(434, 24, '9DEDD106', 1, NULL, '2026-03-28 11:06:02', 'Entry', 'POWER', 'granted'),
(435, 24, '9DEDD106', 1, NULL, '2026-03-28 11:06:11', 'Exit', 'POWER', 'granted'),
(436, 26, '42193D05', 1, NULL, '2026-04-10 12:53:26', 'Exit', 'DOOR', 'denied'),
(437, 26, '42193D05', 1, NULL, '2026-04-10 12:53:29', 'Exit', 'DOOR', 'denied'),
(438, 28, '61DE6A05', 1, NULL, '2026-04-10 12:53:31', 'Exit', 'DOOR', 'denied'),
(439, NULL, 'A4123D05', 1, NULL, '2026-04-10 12:53:54', 'Entry', 'DOOR', 'denied'),
(440, NULL, '9DEDD106', 1, NULL, '2026-04-10 12:53:56', 'Entry', 'DOOR', 'denied'),
(441, 28, '61DE6A05', 1, NULL, '2026-04-10 12:55:17', 'Exit', 'DOOR', 'denied'),
(442, 28, '61DE6A05', 1, NULL, '2026-04-10 12:58:55', 'Exit', 'DOOR', 'denied'),
(443, 28, '61DE6A05', 1, NULL, '2026-04-10 12:59:02', 'Exit', 'DOOR', 'denied'),
(444, 28, '61DE6A05', 1, NULL, '2026-04-10 12:59:05', 'Exit', 'DOOR', 'denied'),
(445, 28, '61DE6A05', 1, NULL, '2026-04-10 12:59:10', 'Exit', 'DOOR', 'denied'),
(446, 28, '61DE6A05', 1, NULL, '2026-04-10 12:59:13', 'Exit', 'DOOR', 'denied'),
(447, 28, '61DE6A05', 1, NULL, '2026-04-10 12:59:16', 'Exit', 'DOOR', 'denied'),
(448, 28, '61DE6A05', 1, NULL, '2026-04-10 12:59:26', 'Exit', 'DOOR', 'denied'),
(449, 28, '61DE6A05', 1, NULL, '2026-04-10 12:59:35', 'Exit', 'DOOR', 'denied'),
(450, 28, '61DE6A05', 1, NULL, '2026-04-10 12:59:42', 'Exit', 'DOOR', 'denied'),
(451, NULL, '9DEDD106', 1, NULL, '2026-04-10 12:59:56', 'Entry', 'POWER', 'denied'),
(452, 28, '61DE6A05', 1, NULL, '2026-04-10 13:00:02', 'Exit', 'DOOR', 'denied'),
(453, 28, '61DE6A05', 1, NULL, '2026-04-10 13:00:23', 'Exit', 'DOOR', 'denied'),
(454, 24, '9DEDD106', 1, NULL, '2026-04-10 13:00:28', 'Entry', 'POWER', 'granted'),
(455, 24, '9DEDD106', 1, NULL, '2026-04-10 13:00:40', 'Exit', 'POWER', 'granted'),
(456, 24, '9DEDD106', 1, NULL, '2026-04-10 13:00:43', 'Entry', 'POWER', 'granted'),
(457, 28, '61DE6A05', 1, NULL, '2026-04-10 13:00:47', 'Exit', 'DOOR', 'denied'),
(458, 24, '9DEDD106', 1, NULL, '2026-04-10 13:00:50', 'Exit', 'DOOR', 'granted'),
(459, 24, '9DEDD106', 1, NULL, '2026-04-10 13:00:55', 'Entry', 'DOOR', 'granted'),
(460, 24, '9DEDD106', 1, NULL, '2026-04-10 13:01:08', 'Exit', 'DOOR', 'granted'),
(461, 28, '61DE6A05', 1, NULL, '2026-04-10 13:01:12', 'Exit', 'DOOR', 'denied'),
(462, 28, '61DE6A05', 1, NULL, '2026-04-10 13:01:16', 'Exit', 'DOOR', 'denied'),
(463, 24, '9DEDD106', 1, NULL, '2026-04-10 13:01:18', 'Entry', 'POWER', 'granted'),
(464, 24, '9DEDD106', 1, NULL, '2026-04-10 13:01:24', 'Exit', 'POWER', 'granted'),
(465, 28, '61DE6A05', 1, NULL, '2026-04-10 13:01:44', 'Exit', 'DOOR', 'denied'),
(466, 28, '61DE6A05', 1, NULL, '2026-04-10 13:01:47', 'Exit', 'DOOR', 'denied'),
(467, 28, '61DE6A05', 1, NULL, '2026-04-10 13:01:49', 'Exit', 'DOOR', 'denied'),
(468, 28, '61DE6A05', 1, NULL, '2026-04-10 13:01:53', 'Exit', 'DOOR', 'denied'),
(469, 28, '61DE6A05', 1, NULL, '2026-04-10 13:02:01', 'Exit', 'DOOR', 'denied'),
(470, 28, '61DE6A05', 1, NULL, '2026-04-10 13:02:15', 'Exit', 'DOOR', 'denied'),
(471, 28, '61DE6A05', 1, NULL, '2026-04-10 13:02:25', 'Exit', 'DOOR', 'denied'),
(472, 28, '61DE6A05', 1, NULL, '2026-04-10 13:03:44', 'Exit', 'DOOR', 'denied'),
(473, 28, '61DE6A05', 1, 34, '2026-04-10 13:04:03', 'Exit', 'DOOR', 'granted'),
(474, 26, '42193D05', 1, NULL, '2026-04-10 13:04:17', 'Exit', 'DOOR', 'denied'),
(475, 26, '42193D05', 1, NULL, '2026-04-10 13:04:20', 'Exit', 'DOOR', 'denied'),
(476, 26, '42193D05', 1, NULL, '2026-04-10 13:04:29', 'Exit', 'DOOR', 'denied'),
(477, 26, '42193D05', 1, NULL, '2026-04-10 13:04:40', 'Exit', 'DOOR', 'granted'),
(478, 26, '42193D05', 1, NULL, '2026-04-10 13:04:44', 'Entry', 'POWER', 'granted'),
(479, 28, '61DE6A05', 1, NULL, '2026-04-10 13:05:47', 'Entry', 'POWER', 'denied'),
(480, 26, '42193D05', 1, NULL, '2026-04-10 13:05:51', 'Exit', 'POWER', 'granted'),
(481, 24, '9DEDD106', 1, NULL, '2026-04-10 13:06:28', 'Entry', 'POWER', 'granted'),
(482, 24, '9DEDD106', 1, NULL, '2026-04-10 13:06:30', 'Exit', 'DOOR', 'granted'),
(483, 24, '9DEDD106', 1, NULL, '2026-04-10 13:06:39', 'Entry', 'DOOR', 'granted'),
(484, 24, '9DEDD106', 1, NULL, '2026-04-10 13:06:41', 'Exit', 'POWER', 'granted'),
(485, 26, '42193D05', 1, 34, '2026-04-10 13:08:42', 'Entry', 'POWER', 'granted'),
(486, 26, '42193D05', 1, NULL, '2026-04-10 13:08:45', 'Exit', 'DOOR', 'denied'),
(487, 26, '42193D05', 1, NULL, '2026-04-10 13:08:48', 'Exit', 'DOOR', 'denied'),
(488, 26, '42193D05', 1, NULL, '2026-04-10 13:08:54', 'Exit', 'DOOR', 'denied'),
(489, 26, '42193D05', 1, 34, '2026-04-10 13:08:54', 'Exit', 'POWER', 'granted'),
(490, 26, '42193D05', 1, NULL, '2026-04-10 13:08:57', 'Entry', 'DOOR', 'denied'),
(491, 26, '42193D05', 1, NULL, '2026-04-10 13:09:00', 'Entry', 'DOOR', 'denied'),
(492, 24, '9DEDD106', 1, NULL, '2026-04-10 13:09:04', 'Entry', 'DOOR', 'granted'),
(493, 24, '9DEDD106', 1, NULL, '2026-04-10 13:09:07', 'Exit', 'POWER', 'granted'),
(494, 24, '9DEDD106', 1, NULL, '2026-04-10 13:09:09', 'Entry', 'POWER', 'granted'),
(495, 24, '9DEDD106', 1, NULL, '2026-04-10 13:09:14', 'Exit', 'POWER', 'granted'),
(496, 24, '9DEDD106', 1, NULL, '2026-04-10 13:09:16', 'Entry', 'POWER', 'granted'),
(497, 24, '9DEDD106', 1, NULL, '2026-04-10 13:09:20', 'Exit', 'POWER', 'granted'),
(498, 24, '9DEDD106', 1, NULL, '2026-04-10 13:09:22', 'Entry', 'POWER', 'granted'),
(499, 24, '9DEDD106', 1, NULL, '2026-04-10 13:09:26', 'Exit', 'POWER', 'granted'),
(500, 24, '9DEDD106', 1, NULL, '2026-04-10 13:09:28', 'Entry', 'POWER', 'granted'),
(501, 24, '9DEDD106', 1, NULL, '2026-04-10 13:09:32', 'Exit', 'POWER', 'granted'),
(502, 24, '9DEDD106', 1, NULL, '2026-04-10 13:09:36', 'Entry', 'DOOR', 'granted'),
(503, 26, '42193D05', 1, NULL, '2026-04-10 13:09:42', 'Entry', 'DOOR', 'denied'),
(504, 26, '42193D05', 1, NULL, '2026-04-10 13:09:45', 'Entry', 'DOOR', 'denied'),
(505, 26, '42193D05', 1, 34, '2026-04-10 13:10:01', 'Entry', 'POWER', 'granted'),
(506, 26, '42193D05', 1, 34, '2026-04-10 13:10:05', 'Exit', 'POWER', 'granted'),
(507, 26, '42193D05', 1, 34, '2026-04-10 13:10:08', 'Entry', 'POWER', 'granted'),
(508, 24, '9DEDD106', 1, NULL, '2026-04-10 13:11:08', 'Exit', 'POWER', 'granted'),
(509, 24, '9DEDD106', 1, NULL, '2026-04-10 13:11:15', 'Entry', 'POWER', 'granted'),
(510, 24, '9DEDD106', 1, NULL, '2026-04-10 13:11:22', 'Exit', 'POWER', 'granted'),
(511, 26, '42193D05', 1, 34, '2026-04-10 13:11:39', 'Exit', 'POWER', 'granted'),
(512, 26, '42193D05', 1, 34, '2026-04-10 13:11:41', 'Entry', 'POWER', 'granted'),
(513, NULL, '42193D05', 1, NULL, '2026-04-10 13:12:05', 'Entry', 'POWER', 'denied'),
(514, 28, '61DE6A05', 1, 34, '2026-04-10 13:14:31', 'Entry', 'DOOR', 'granted'),
(515, 28, '61DE6A05', 1, 34, '2026-04-10 13:14:44', 'Exit', 'DOOR', 'granted'),
(516, NULL, '42193D05', 1, NULL, '2026-04-10 13:15:14', 'Entry', 'DOOR', 'denied'),
(517, NULL, '42193D05', 1, NULL, '2026-04-10 13:15:18', 'Entry', 'DOOR', 'denied'),
(518, NULL, '42193D05', 1, NULL, '2026-04-10 13:15:24', 'Entry', 'DOOR', 'denied'),
(519, 24, '9DEDD106', 1, NULL, '2026-04-10 13:16:05', 'Entry', 'DOOR', 'granted'),
(520, 24, '9DEDD106', 1, NULL, '2026-04-10 13:16:08', 'Exit', 'POWER', 'granted'),
(521, 24, '9DEDD106', 1, NULL, '2026-04-10 13:16:14', 'Entry', 'DOOR', 'granted'),
(522, 24, '9DEDD106', 1, NULL, '2026-04-10 13:16:50', 'Exit', 'POWER', 'granted'),
(523, 24, '9DEDD106', 1, NULL, '2026-04-10 13:16:52', 'Entry', 'POWER', 'granted'),
(524, 24, '9DEDD106', 1, NULL, '2026-04-10 13:16:59', 'Exit', 'POWER', 'granted'),
(525, 24, '9DEDD106', 1, NULL, '2026-04-10 13:17:01', 'Entry', 'POWER', 'granted'),
(526, 24, '9DEDD106', 1, NULL, '2026-04-10 13:17:18', 'Exit', 'POWER', 'granted'),
(527, 24, '9DEDD106', 1, NULL, '2026-04-10 13:17:21', 'Entry', 'POWER', 'granted'),
(528, 24, '9DEDD106', 1, NULL, '2026-04-10 13:17:25', 'Exit', 'DOOR', 'granted'),
(529, 24, '9DEDD106', 1, NULL, '2026-04-10 13:17:43', 'Entry', 'POWER', 'granted'),
(530, 24, '9DEDD106', 1, NULL, '2026-04-10 13:17:47', 'Exit', 'POWER', 'granted'),
(531, 24, '9DEDD106', 1, NULL, '2026-04-10 13:17:51', 'Entry', 'DOOR', 'granted'),
(532, 26, '42193D05', 1, NULL, '2026-04-10 13:18:20', 'Exit', 'DOOR', 'denied'),
(533, 26, '42193D05', 1, 34, '2026-04-10 13:18:23', 'Exit', 'POWER', 'granted'),
(534, 26, '42193D05', 1, 34, '2026-04-10 13:18:26', 'Entry', 'POWER', 'granted'),
(535, 28, '61DE6A05', 1, 34, '2026-04-10 13:18:47', 'Entry', 'DOOR', 'granted'),
(536, NULL, '42193D05', 1, NULL, '2026-04-10 13:18:55', 'Entry', 'DOOR', 'denied'),
(537, NULL, '42193D05', 1, NULL, '2026-04-10 13:18:58', 'Entry', 'DOOR', 'denied'),
(538, NULL, '42193D05', 1, NULL, '2026-04-10 13:19:03', 'Entry', 'DOOR', 'denied'),
(539, NULL, '42193D05', 1, NULL, '2026-04-10 13:19:17', 'Entry', 'DOOR', 'denied');

-- --------------------------------------------------------

--
-- Table structure for table `access_policies`
--

CREATE TABLE `access_policies` (
  `role` varchar(50) DEFAULT NULL,
  `device_type` varchar(20) DEFAULT NULL,
  `requires_schedule` tinyint(4) DEFAULT NULL,
  `can_override_shutdown` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `access_policies`
--

INSERT INTO `access_policies` (`role`, `device_type`, `requires_schedule`, `can_override_shutdown`) VALUES
('Admin', '*', 0, 1),
('Faculty', 'POWER', 1, 1),
('Student', 'DOOR', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Admin_id` int(11) NOT NULL,
  `F_name` varchar(255) DEFAULT NULL,
  `L_name` varchar(255) DEFAULT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Admin_id`, `F_name`, `L_name`, `Username`, `Password`) VALUES
(1, 'Jonathan', 'Mina', 'admin', '$2y$10$.OhR22bXjH76rOtF7RuwguvyTkk7hwzLdoQeRH2cTo2pMPc.R./wi'),
(2, 'John Rey', 'Olivera', 'oliber', '$2y$10$whvEbu732TlhLEynO6RsBetBeefG7E4xS04qkOD.tKtGdtPiYOuLC');

-- --------------------------------------------------------

--
-- Table structure for table `classrooms`
--

CREATE TABLE `classrooms` (
  `Room_id` int(11) NOT NULL,
  `Room_code` varchar(50) NOT NULL,
  `Status` enum('Occupied','Unoccupied') DEFAULT 'Unoccupied',
  `Classroom_type` varchar(255) DEFAULT NULL,
  `Capacity` int(11) DEFAULT NULL,
  `FLOOR` varchar(55) NOT NULL,
  `grace_period` int(11) DEFAULT 15,
  `allow_extension` tinyint(1) DEFAULT 1,
  `double_tap_exit` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classrooms`
--

INSERT INTO `classrooms` (`Room_id`, `Room_code`, `Status`, `Classroom_type`, `Capacity`, `FLOOR`, `grace_period`, `allow_extension`, `double_tap_exit`) VALUES
(1, 'ROOM101', 'Unoccupied', 'CLASSROOM', 50, '2ND Floor', 0, 0, 0),
(2, 'ROOM102', 'Unoccupied', 'CLASSROOM', 30, '1st Floor', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `course_section`
--

CREATE TABLE `course_section` (
  `CourseSection_id` int(11) NOT NULL,
  `CourseSection` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_section`
--

INSERT INTO `course_section` (`CourseSection_id`, `CourseSection`) VALUES
(2, 'BSCS 1-21'),
(158, 'BSCS 2-11'),
(154, 'BSCS-2A'),
(159, 'BSIS 1-31'),
(1, 'BSIT 1-11'),
(144, 'BSIT 1-21'),
(153, 'BSIT 1-31'),
(3, 'BSIT 2-11'),
(156, 'BSIT 3-11'),
(157, 'BSIT 3-21'),
(160, 'BSIT 4-11'),
(155, 'BSIT-1B'),
(111, 'BSOA 1-11'),
(121, 'BSOA 1-21'),
(131, 'BSOA 1-31'),
(141, 'BSOA 1-41');

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `device_id` int(11) NOT NULL,
  `mac_address` varchar(17) NOT NULL,
  `room_id` int(11) NOT NULL,
  `device_type` enum('DOOR','POWER') DEFAULT 'DOOR',
  `last_seen` datetime DEFAULT current_timestamp(),
  `status` enum('Online','Offline') DEFAULT 'Offline'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`device_id`, `mac_address`, `room_id`, `device_type`, `last_seen`, `status`) VALUES
(0, 'AD:AD:AD:AD:AD:AD', 4, 'DOOR', '2026-02-02 14:35:21', 'Offline'),
(1, 'D4:E9:F4:65:F5:1C', 1, 'POWER', '2026-04-10 13:19:14', 'Online'),
(2, '70:B8:F6:28:30:84', 1, 'DOOR', '2026-04-10 13:19:20', 'Online');

-- --------------------------------------------------------

--
-- Table structure for table `individual_permissions`
--

CREATE TABLE `individual_permissions` (
  `Permission_id` int(11) NOT NULL,
  `User_id` int(11) NOT NULL,
  `Schedule_id` int(11) NOT NULL,
  `Reason` varchar(255) DEFAULT 'Irregular/Working Student',
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `last_scan`
--

CREATE TABLE `last_scan` (
  `id` int(11) NOT NULL DEFAULT 1,
  `uid` varchar(50) NOT NULL,
  `scanned_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `last_scan`
--

INSERT INTO `last_scan` (`id`, `uid`, `scanned_at`) VALUES
(1, 'NONE', '2026-03-11 07:20:33');

-- --------------------------------------------------------

--
-- Table structure for table `rfid_buffer`
--

CREATE TABLE `rfid_buffer` (
  `id` int(11) NOT NULL,
  `rfid_tag` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rfid_reader`
--

CREATE TABLE `rfid_reader` (
  `Reader_id` int(11) NOT NULL,
  `Room_id` int(11) NOT NULL,
  `Port_name` varchar(50) NOT NULL,
  `Status` enum('Active','Inactive') DEFAULT 'Active',
  `Last_online` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rfid_reader`
--

INSERT INTO `rfid_reader` (`Reader_id`, `Room_id`, `Port_name`, `Status`, `Last_online`) VALUES
(1, 1, 'COM5', 'Active', NULL),
(2, 2, 'COM10', 'Active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `Schedule_id` int(11) NOT NULL,
  `Subject_id` int(11) NOT NULL,
  `Room_id` int(11) NOT NULL,
  `Faculty_id` int(11) NOT NULL,
  `Day` enum('Mon','Tue','Wed','Thu','Fri','Sat','Sun') NOT NULL,
  `Start_time` time NOT NULL,
  `End_time` time NOT NULL,
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`Schedule_id`, `Subject_id`, `Room_id`, `Faculty_id`, `Day`, `Start_time`, `End_time`, `is_deleted`) VALUES
(34, 14, 1, 26, 'Fri', '10:19:00', '23:19:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `schedule_access`
--

CREATE TABLE `schedule_access` (
  `Rule_id` int(11) NOT NULL,
  `Schedule_id` int(11) NOT NULL,
  `CourseSection_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule_access`
--

INSERT INTO `schedule_access` (`Rule_id`, `Schedule_id`, `CourseSection_id`) VALUES
(197, 34, 158),
(198, 34, 1),
(199, 34, 158),
(200, 34, 1);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `Subject_id` int(11) NOT NULL,
  `Code` varchar(50) NOT NULL,
  `Description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`Subject_id`, `Code`, `Description`) VALUES
(1, 'IT101', 'Introduction to Information Technology'),
(2, 'ITP311', 'Human Computer Interaction'),
(3, 'GE304', 'Science Technology Engineering'),
(5, 'OAC310', 'Business Law'),
(6, 'OAE301', 'Human Anatomy and Physiology'),
(7, 'GEE303', 'GE Elective 3- Business Logic'),
(8, 'OAC309', 'Customer Relations'),
(13, 'IT202 Intergrative Programming', 'IT202 Intergrative Programming'),
(14, 'ENGL101', 'English Composition'),
(15, 'IT311', 'Integrative Programming'),
(16, 'CS202', 'Data Structures'),
(17, 'NET101', 'Networking 1'),
(18, 'IT412', 'Capstone Project 1'),
(19, 'SYS101', 'System Admin'),
(20, 'HUM102', 'Ethics');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`setting_key`, `setting_value`) VALUES
('global_allow_extension', '0'),
('global_double_tap', '0'),
('global_grace_period', '1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_id` int(11) NOT NULL,
  `Rfid_tag` varchar(50) NOT NULL,
  `F_name` varchar(100) NOT NULL,
  `L_name` varchar(100) NOT NULL,
  `CourseSection_id` int(11) DEFAULT NULL,
  `Role` enum('Student','Faculty','Admin') DEFAULT 'Student',
  `Status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_id`, `Rfid_tag`, `F_name`, `L_name`, `CourseSection_id`, `Role`, `Status`) VALUES
(24, '9DEDD106', 'Jonathan', 'Mina', NULL, 'Admin', 'Active'),
(26, '42193D05', 'Rey Vergel', 'Abella', NULL, 'Faculty', 'Inactive'),
(28, '61DE6A05', 'Kristel', 'Ladot', 1, 'Student', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_log`
--
ALTER TABLE `access_log`
  ADD PRIMARY KEY (`Log_id`),
  ADD KEY `User_id` (`User_id`),
  ADD KEY `Room_id` (`Room_id`),
  ADD KEY `Schedule_id` (`Schedule_id`),
  ADD KEY `Rfid_tag` (`Rfid_tag`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Admin_id`);

--
-- Indexes for table `classrooms`
--
ALTER TABLE `classrooms`
  ADD PRIMARY KEY (`Room_id`),
  ADD UNIQUE KEY `Room_code` (`Room_code`);

--
-- Indexes for table `course_section`
--
ALTER TABLE `course_section`
  ADD PRIMARY KEY (`CourseSection_id`),
  ADD UNIQUE KEY `CourseSection` (`CourseSection`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`device_id`),
  ADD UNIQUE KEY `mac_address` (`mac_address`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `individual_permissions`
--
ALTER TABLE `individual_permissions`
  ADD PRIMARY KEY (`Permission_id`),
  ADD KEY `User_id` (`User_id`),
  ADD KEY `Schedule_id` (`Schedule_id`);

--
-- Indexes for table `last_scan`
--
ALTER TABLE `last_scan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rfid_buffer`
--
ALTER TABLE `rfid_buffer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rfid_reader`
--
ALTER TABLE `rfid_reader`
  ADD PRIMARY KEY (`Reader_id`),
  ADD KEY `Room_id` (`Room_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`Schedule_id`),
  ADD KEY `Subject_id` (`Subject_id`),
  ADD KEY `Room_id` (`Room_id`),
  ADD KEY `Faculty_id` (`Faculty_id`);

--
-- Indexes for table `schedule_access`
--
ALTER TABLE `schedule_access`
  ADD PRIMARY KEY (`Rule_id`),
  ADD KEY `Schedule_id` (`Schedule_id`),
  ADD KEY `CourseSection_id` (`CourseSection_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`Subject_id`),
  ADD UNIQUE KEY `Code` (`Code`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_id`),
  ADD UNIQUE KEY `Rfid_tag` (`Rfid_tag`),
  ADD KEY `CourseSection_id` (`CourseSection_id`),
  ADD KEY `Rfid_tag_2` (`Rfid_tag`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_log`
--
ALTER TABLE `access_log`
  MODIFY `Log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=540;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `Admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `classrooms`
--
ALTER TABLE `classrooms`
  MODIFY `Room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `course_section`
--
ALTER TABLE `course_section`
  MODIFY `CourseSection_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `individual_permissions`
--
ALTER TABLE `individual_permissions`
  MODIFY `Permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `rfid_buffer`
--
ALTER TABLE `rfid_buffer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rfid_reader`
--
ALTER TABLE `rfid_reader`
  MODIFY `Reader_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `Schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `schedule_access`
--
ALTER TABLE `schedule_access`
  MODIFY `Rule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `Subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access_log`
--
ALTER TABLE `access_log`
  ADD CONSTRAINT `fk_access_room` FOREIGN KEY (`Room_id`) REFERENCES `classrooms` (`Room_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_access_schedule` FOREIGN KEY (`Schedule_id`) REFERENCES `schedule` (`Schedule_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_access_user` FOREIGN KEY (`User_id`) REFERENCES `users` (`User_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `individual_permissions`
--
ALTER TABLE `individual_permissions`
  ADD CONSTRAINT `individual_permissions_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `users` (`User_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `individual_permissions_ibfk_2` FOREIGN KEY (`Schedule_id`) REFERENCES `schedule` (`Schedule_id`) ON DELETE CASCADE;

--
-- Constraints for table `rfid_reader`
--
ALTER TABLE `rfid_reader`
  ADD CONSTRAINT `rfid_reader_ibfk_1` FOREIGN KEY (`Room_id`) REFERENCES `classrooms` (`Room_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `fk_schedule_faculty` FOREIGN KEY (`Faculty_id`) REFERENCES `users` (`User_id`),
  ADD CONSTRAINT `fk_schedule_room` FOREIGN KEY (`Room_id`) REFERENCES `classrooms` (`Room_id`),
  ADD CONSTRAINT `fk_schedule_subject` FOREIGN KEY (`Subject_id`) REFERENCES `subject` (`Subject_id`);

--
-- Constraints for table `schedule_access`
--
ALTER TABLE `schedule_access`
  ADD CONSTRAINT `fk_schedule_access_course` FOREIGN KEY (`CourseSection_id`) REFERENCES `course_section` (`CourseSection_id`),
  ADD CONSTRAINT `fk_schedule_access_schedule` FOREIGN KEY (`Schedule_id`) REFERENCES `schedule` (`Schedule_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_course` FOREIGN KEY (`CourseSection_id`) REFERENCES `course_section` (`CourseSection_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
