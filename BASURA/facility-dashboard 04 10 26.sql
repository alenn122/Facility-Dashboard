-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2026 at 08:49 AM
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
  `Status` enum('granted','denied') DEFAULT 'denied'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `access_log`
--

INSERT INTO `access_log` (`Log_id`, `User_id`, `Rfid_tag`, `Room_id`, `Schedule_id`, `Access_time`, `Access_type`, `Status`) VALUES
(1, NULL, '1,82 04 10 01', 1, NULL, '2025-10-09 15:38:44', 'Entry', 'denied'),
(2, NULL, '2,82 04 10 01', 2, NULL, '2025-10-09 15:38:56', 'Entry', 'denied'),
(3, NULL, '2,82 04 10 01', 2, NULL, '2025-10-09 15:38:59', 'Entry', 'denied'),
(4, NULL, '1,82 04 10 01', 1, NULL, '2025-10-09 15:40:14', 'Entry', 'denied'),
(5, NULL, '2,82 04 10 01', 2, NULL, '2025-10-09 15:40:19', 'Entry', 'denied'),
(6, NULL, '2,82 04 10 01', 2, NULL, '2025-10-09 15:40:27', 'Entry', 'denied'),
(7, NULL, '2,82 04 10 01', 2, NULL, '2025-10-09 15:45:18', 'Entry', 'denied'),
(8, NULL, '82 04 10 01', 1, NULL, '2025-10-09 15:48:46', 'Entry', 'granted'),
(9, NULL, 'D3 CB B1 38', 1, NULL, '2025-10-09 15:49:41', 'Entry', 'denied'),
(10, NULL, '2,D3 CB B1 38', 2, NULL, '2025-10-09 15:49:47', 'Entry', 'denied'),
(11, NULL, '2,D3 CB B1 38', 2, NULL, '2025-10-09 15:49:51', 'Entry', 'denied'),
(12, NULL, '82 04 10 01', 1, NULL, '2025-10-09 15:49:54', 'Entry', 'granted'),
(13, NULL, '82 04 10 01', 1, NULL, '2025-10-09 15:50:13', 'Entry', 'granted'),
(14, NULL, '82 04 10 01', 1, NULL, '2025-10-09 15:50:43', 'Entry', 'granted'),
(15, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-09 15:51:02', 'Entry', 'denied'),
(16, 8, 'A4 12 3D 05', 1, NULL, '2025-10-09 15:51:26', 'Exit', 'granted'),
(17, NULL, '82 04 10 01', 1, NULL, '2025-10-09 15:51:33', 'Entry', 'granted'),
(18, 8, 'A4 12 3D 05', 1, NULL, '2025-10-09 15:51:40', 'Exit', 'granted'),
(19, NULL, '82 04 10 01', 1, NULL, '2025-10-09 15:52:05', 'Entry', 'denied'),
(20, NULL, '82 04 10 01', 1, NULL, '2025-10-09 15:52:16', 'Entry', 'denied'),
(21, NULL, '82 04 10 01', 1, NULL, '2025-10-09 15:52:21', 'Entry', 'denied'),
(22, NULL, '2,82 04 10 01', 2, NULL, '2025-10-09 15:53:03', 'Entry', 'denied'),
(23, NULL, '2,82 04 10 01', 2, NULL, '2025-10-09 15:53:08', 'Entry', 'denied'),
(24, NULL, '82 04 10 01', 1, NULL, '2025-10-09 15:53:13', 'Entry', 'granted'),
(25, 8, 'A4 12 3D 05', 1, NULL, '2025-10-09 15:53:35', 'Exit', 'granted'),
(26, 8, 'A4 12 3D 05', 1, NULL, '2025-10-09 15:53:37', 'Entry', 'granted'),
(27, NULL, '2,A4 12 3D 05', 2, NULL, '2025-10-09 15:53:46', 'Entry', 'denied'),
(28, NULL, '2,82 04 10 01', 2, NULL, '2025-10-09 16:13:01', 'Entry', 'denied'),
(29, NULL, '82 04 10 01', 1, NULL, '2025-10-09 16:13:22', 'Entry', 'granted'),
(30, NULL, '82 04 10 01', 2, NULL, '2025-10-09 16:14:30', 'Entry', 'granted'),
(31, NULL, '82 04 10 01', 1, NULL, '2025-10-09 16:14:34', 'Entry', 'granted'),
(32, NULL, '82 04 10 01', 2, NULL, '2025-10-09 16:15:53', 'Entry', 'granted'),
(33, 8, 'A4 12 3D 05', 2, NULL, '2025-10-09 16:15:59', 'Exit', 'granted'),
(34, NULL, 'D3 CB B1 38', 2, NULL, '2025-10-09 16:16:03', 'Entry', 'denied'),
(35, NULL, 'D3 CB B1 38', 2, 1, '2025-10-09 16:19:29', 'Entry', 'granted'),
(36, NULL, 'D3 CB B1 38', 1, 1, '2025-10-09 16:19:32', 'Entry', 'granted'),
(37, 8, 'A4 12 3D 05', 1, NULL, '2025-10-09 16:22:40', 'Exit', 'granted'),
(38, 8, 'A4 12 3D 05', 1, NULL, '2025-10-09 16:22:43', 'Entry', 'granted'),
(39, 8, 'A4 12 3D 05', 1, NULL, '2025-10-09 16:22:51', 'Exit', 'granted'),
(40, NULL, 'D3 CB B1 38', 1, NULL, '2025-10-09 16:22:55', 'Entry', 'denied'),
(41, NULL, 'D3 CB B1 38', 2, 1, '2025-10-09 16:23:00', 'Entry', 'granted'),
(42, NULL, '82 04 10 01', 2, NULL, '2025-10-09 16:23:09', 'Entry', 'denied'),
(43, NULL, '82 04 10 01', 1, NULL, '2025-10-09 16:23:12', 'Entry', 'denied'),
(44, 8, 'A4 12 3D 05', 1, NULL, '2025-10-09 16:23:46', 'Entry', 'granted'),
(45, 8, 'A4 12 3D 05', 1, NULL, '2025-10-09 16:23:53', 'Exit', 'granted'),
(46, NULL, 'D3 CB B1 38', 1, NULL, '2025-10-09 16:23:57', 'Entry', 'denied'),
(47, NULL, 'D3 CB B1 38', 2, 1, '2025-10-09 16:24:04', 'Entry', 'granted'),
(48, NULL, 'D3 CB B1 38', 2, 1, '2025-10-09 16:24:20', 'Entry', 'granted'),
(49, NULL, 'D3 CB B1 38', 2, 1, '2025-10-09 16:24:28', 'Entry', 'granted'),
(50, NULL, '82 04 10 01', 2, NULL, '2025-10-09 16:24:50', 'Entry', 'denied'),
(51, NULL, '82 04 10 01', 1, NULL, '2025-10-09 16:25:01', 'Entry', 'granted'),
(52, NULL, '82 04 10 01', 1, NULL, '2025-10-09 16:26:30', 'Entry', 'granted'),
(53, NULL, '82 04 10 01', 2, NULL, '2025-10-09 16:26:34', 'Entry', 'denied'),
(54, NULL, '82 04 10 01', 2, NULL, '2025-10-09 16:28:41', 'Entry', 'denied'),
(55, NULL, '82 04 10 01', 2, NULL, '2025-10-09 16:28:43', 'Entry', 'denied'),
(56, NULL, '82 04 10 01', 1, NULL, '2025-10-09 16:28:45', 'Entry', 'granted'),
(57, NULL, 'D3 CB B1 38', 2, 1, '2025-10-09 16:28:52', 'Entry', 'granted'),
(58, NULL, 'D3 CB B1 38', 1, NULL, '2025-10-09 16:28:55', 'Entry', 'denied'),
(59, NULL, 'D3 CB B1 38', 2, NULL, '2025-10-10 10:28:40', 'Entry', 'denied'),
(60, NULL, 'D3 CB B1 38', 2, NULL, '2025-10-10 10:30:37', 'Entry', 'denied'),
(61, NULL, '82 04 10 01', 2, NULL, '2025-10-10 10:30:41', 'Entry', 'denied'),
(62, NULL, '82 04 10 01', 1, NULL, '2025-10-10 10:39:08', 'Entry', 'denied'),
(63, NULL, '82 04 10 01', 1, NULL, '2025-10-10 10:39:11', 'Entry', 'denied'),
(64, NULL, '82 04 10 01', 1, NULL, '2025-10-10 10:39:14', 'Entry', 'denied'),
(65, NULL, '82 04 10 01', 1, NULL, '2025-10-10 10:39:20', 'Entry', 'denied'),
(66, NULL, '82 04 10 01', 1, NULL, '2025-10-10 10:39:23', 'Entry', 'denied'),
(67, NULL, 'D3 CB B1 38', 1, NULL, '2025-10-10 10:40:18', 'Entry', 'denied'),
(68, NULL, 'D3 CB B1 38', 1, 1, '2025-10-10 10:41:06', 'Entry', 'granted'),
(69, NULL, 'D3 CB B1 38', 2, 1, '2025-10-10 10:41:11', 'Entry', 'granted'),
(70, NULL, 'D3 CB B1 38', 2, 1, '2025-10-10 10:41:19', 'Entry', 'granted'),
(71, NULL, '82 04 10 01', 2, NULL, '2025-10-10 10:41:27', 'Entry', 'granted'),
(72, NULL, '82 04 10 01', 1, NULL, '2025-10-10 10:41:31', 'Entry', 'granted'),
(73, NULL, 'D3 CB B1 38', 2, 1, '2025-10-10 10:44:08', 'Entry', 'granted'),
(74, NULL, 'D3 CB B1 38', 2, 1, '2025-10-10 10:45:11', 'Entry', 'granted'),
(75, NULL, '82 04 10 01', 2, NULL, '2025-10-10 10:45:47', 'Entry', 'denied'),
(76, NULL, '82 04 10 01', 2, NULL, '2025-10-10 10:45:52', 'Entry', 'denied'),
(77, NULL, 'D3 CB B1 38', 2, 1, '2025-10-10 10:45:56', 'Entry', 'granted'),
(78, NULL, 'D3 CB B1 38', 1, NULL, '2025-10-10 10:46:13', 'Entry', 'denied'),
(79, NULL, 'D3 CB B1 38', 2, 1, '2025-10-10 10:46:17', 'Entry', 'granted'),
(80, NULL, '82 04 10 01', 1, NULL, '2025-10-10 10:46:23', 'Entry', 'granted'),
(81, NULL, '82 04 10 01', 2, NULL, '2025-10-10 10:46:28', 'Entry', 'denied'),
(82, NULL, 'D3 CB B1 38', 2, 1, '2025-10-10 10:48:03', 'Entry', 'granted'),
(83, NULL, 'D3 CB B1 38', 2, 1, '2025-10-10 10:48:26', 'Entry', 'granted'),
(84, NULL, 'D3 CB B1 38', 2, 1, '2025-10-10 10:48:33', 'Entry', 'granted'),
(85, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 10:48:41', 'Exit', 'granted'),
(86, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 10:48:48', 'Entry', 'granted'),
(87, NULL, '82 04 10 01', 2, NULL, '2025-10-10 10:48:56', 'Entry', 'denied'),
(88, NULL, '82 04 10 01', 2, NULL, '2025-10-10 10:48:58', 'Entry', 'denied'),
(89, NULL, '82 04 10 01', 2, NULL, '2025-10-10 10:49:01', 'Entry', 'denied'),
(90, NULL, '82 04 10 01', 2, NULL, '2025-10-10 10:49:04', 'Entry', 'denied'),
(91, NULL, '82 04 10 01', 1, NULL, '2025-10-10 10:49:06', 'Entry', 'granted'),
(92, NULL, 'D3 CB B1 38', 2, 1, '2025-10-10 10:49:10', 'Entry', 'granted'),
(93, NULL, 'D3 CB B1 38', 1, NULL, '2025-10-10 10:49:16', 'Entry', 'denied'),
(94, NULL, 'D3 CB B1 38', 1, NULL, '2025-10-10 10:49:18', 'Entry', 'denied'),
(95, NULL, 'D3 CB B1 38', 2, 1, '2025-10-10 11:02:20', 'Entry', 'granted'),
(96, NULL, 'D3 CB B1 38', 2, 1, '2025-10-10 11:02:31', 'Entry', 'granted'),
(97, NULL, 'D3 CB B1 38', 2, 1, '2025-10-10 11:02:45', 'Entry', 'granted'),
(98, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:05:31', 'Exit', 'granted'),
(99, NULL, '82 04 10 01', 2, NULL, '2025-10-10 11:05:35', 'Entry', 'denied'),
(100, NULL, '82 04 10 01', 2, NULL, '2025-10-10 11:05:38', 'Entry', 'denied'),
(101, NULL, '82 04 10 01', 2, NULL, '2025-10-10 11:05:47', 'Entry', 'denied'),
(102, NULL, '82 04 10 01', 2, NULL, '2025-10-10 11:05:49', 'Entry', 'denied'),
(103, NULL, 'D3 CB B1 38', 2, 1, '2025-10-10 11:05:55', 'Entry', 'granted'),
(104, NULL, 'D3 CB B1 38', 2, 1, '2025-10-10 11:06:02', 'Entry', 'granted'),
(105, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:06:11', 'Exit', 'granted'),
(106, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:06:17', 'Entry', 'granted'),
(107, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:06:24', 'Exit', 'granted'),
(108, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:06:31', 'Entry', 'granted'),
(109, NULL, '82 04 10 01', 2, NULL, '2025-10-10 11:07:24', 'Entry', 'denied'),
(110, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:07:28', 'Exit', 'granted'),
(111, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:07:32', 'Entry', 'granted'),
(112, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:07:40', 'Exit', 'granted'),
(113, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:07:43', 'Entry', 'granted'),
(114, NULL, 'D3 CB B1 38', 2, 1, '2025-10-10 11:09:03', 'Entry', 'granted'),
(115, NULL, 'D3 CB B1 38', 2, 1, '2025-10-10 11:09:23', 'Entry', 'granted'),
(116, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:09:30', 'Exit', 'granted'),
(117, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:09:33', 'Entry', 'granted'),
(118, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:09:41', 'Exit', 'granted'),
(119, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:10:28', 'Entry', 'granted'),
(120, 8, 'A4 12 3D 05', 1, NULL, '2025-10-10 11:10:36', 'Exit', 'granted'),
(121, 8, 'A4 12 3D 05', 1, NULL, '2025-10-10 11:10:40', 'Entry', 'granted'),
(122, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:13:13', 'Exit', 'granted'),
(123, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:13:15', 'Entry', 'granted'),
(124, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:13:51', 'Exit', 'granted'),
(125, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:13:55', 'Entry', 'granted'),
(126, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:14:04', 'Exit', 'granted'),
(127, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:14:09', 'Entry', 'granted'),
(128, 8, 'A4 12 3D 05', 2, NULL, '2025-10-10 11:14:18', 'Exit', 'granted'),
(129, NULL, '61 DE 6A 05', 1, NULL, '2025-10-13 08:09:23', 'Entry', 'denied'),
(130, NULL, '61 DE 6A 05', 1, NULL, '2025-10-13 08:09:26', 'Entry', 'denied'),
(131, NULL, '61 DE 6A 05', 1, NULL, '2025-10-13 08:09:29', 'Entry', 'denied'),
(132, NULL, '44 22 95 04', 2, NULL, '2025-10-13 08:11:25', 'Entry', 'denied'),
(133, NULL, '44 22 95 04', 2, NULL, '2025-10-13 08:11:28', 'Entry', 'denied'),
(134, NULL, '44 22 95 04', 2, NULL, '2025-10-13 08:11:30', 'Entry', 'denied'),
(135, NULL, '44 22 95 04', 1, NULL, '2025-10-13 08:13:49', 'Entry', 'denied'),
(136, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:13:54', 'Exit', 'granted'),
(137, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:14:01', 'Entry', 'granted'),
(138, 8, '61 DE 6A 05', 2, NULL, '2025-10-13 08:14:34', 'Entry', 'granted'),
(139, NULL, '44 22 95 04', 1, NULL, '2025-10-13 08:14:40', 'Entry', 'denied'),
(140, NULL, '44 22 95 04', 2, NULL, '2025-10-13 08:14:43', 'Entry', 'denied'),
(141, 8, '61 DE 6A 05', 2, NULL, '2025-10-13 08:14:46', 'Exit', 'granted'),
(142, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:14:56', 'Exit', 'granted'),
(143, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:15:24', 'Entry', 'granted'),
(144, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:15:33', 'Exit', 'granted'),
(145, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:16:24', 'Entry', 'granted'),
(146, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:16:32', 'Exit', 'granted'),
(147, 8, '61 DE 6A 05', 2, NULL, '2025-10-13 08:16:48', 'Entry', 'granted'),
(148, 8, '61 DE 6A 05', 2, NULL, '2025-10-13 08:16:55', 'Exit', 'granted'),
(149, 8, '61 DE 6A 05', 2, NULL, '2025-10-13 08:16:58', 'Entry', 'granted'),
(150, 8, '61 DE 6A 05', 2, NULL, '2025-10-13 08:17:05', 'Exit', 'granted'),
(151, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:18:26', 'Entry', 'granted'),
(152, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:18:35', 'Exit', 'granted'),
(153, 8, '61 DE 6A 05', 2, NULL, '2025-10-13 08:18:42', 'Entry', 'granted'),
(154, 8, '61 DE 6A 05', 2, NULL, '2025-10-13 08:18:57', 'Exit', 'granted'),
(155, 8, '61 DE 6A 05', 1, NULL, '2025-10-13 08:19:03', 'Entry', 'granted'),
(156, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 01:55:37', 'Exit', 'granted'),
(157, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 01:55:40', 'Entry', 'granted'),
(158, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 01:56:24', 'Exit', 'granted'),
(159, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 01:56:27', 'Entry', 'granted'),
(160, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 01:56:35', 'Exit', 'granted'),
(161, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 01:56:38', 'Entry', 'granted'),
(162, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 01:56:48', 'Exit', 'granted'),
(163, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 01:58:10', 'Entry', 'granted'),
(164, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 01:58:25', 'Exit', 'granted'),
(165, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:00:24', 'Entry', 'granted'),
(166, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:00:31', 'Exit', 'granted'),
(167, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:00:36', 'Entry', 'granted'),
(168, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:01:12', 'Exit', 'granted'),
(169, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:01:16', 'Entry', 'granted'),
(170, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:01:23', 'Exit', 'granted'),
(171, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:01:25', 'Entry', 'granted'),
(172, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:01:33', 'Exit', 'granted'),
(173, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:01:38', 'Entry', 'granted'),
(174, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:01:45', 'Exit', 'granted'),
(175, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:01:50', 'Entry', 'granted'),
(176, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:01:57', 'Exit', 'granted'),
(177, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:04:05', 'Entry', 'granted'),
(178, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:04:16', 'Exit', 'granted'),
(179, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:04:22', 'Entry', 'granted'),
(180, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:06:12', 'Exit', 'granted'),
(181, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:06:20', 'Entry', 'granted'),
(182, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:06:29', 'Exit', 'granted'),
(183, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:07:09', 'Entry', 'granted'),
(184, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:07:15', 'Exit', 'granted'),
(185, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:07:22', 'Entry', 'granted'),
(186, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:07:58', 'Exit', 'granted'),
(187, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:08:04', 'Entry', 'granted'),
(188, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:08:11', 'Exit', 'granted'),
(189, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:08:43', 'Entry', 'granted'),
(190, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:09:59', 'Exit', 'granted'),
(191, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:10:07', 'Entry', 'granted'),
(192, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:10:59', 'Exit', 'granted'),
(193, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:11:01', 'Entry', 'granted'),
(194, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:11:37', 'Exit', 'granted'),
(195, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:11:58', 'Entry', 'granted'),
(196, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:12:20', 'Exit', 'granted'),
(197, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:12:22', 'Entry', 'granted'),
(198, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:13:20', 'Exit', 'granted'),
(199, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:13:23', 'Entry', 'granted'),
(200, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:13:46', 'Exit', 'granted'),
(201, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:13:48', 'Entry', 'granted'),
(202, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:14:29', 'Exit', 'granted'),
(203, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:14:31', 'Entry', 'granted'),
(204, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:14:38', 'Exit', 'granted'),
(205, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:15:13', 'Entry', 'granted'),
(206, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:15:19', 'Exit', 'granted'),
(207, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:15:25', 'Entry', 'granted'),
(208, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:17:13', 'Exit', 'granted'),
(209, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:17:20', 'Entry', 'granted'),
(210, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:17:27', 'Exit', 'granted'),
(211, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:17:33', 'Entry', 'granted'),
(212, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:17:40', 'Exit', 'granted'),
(213, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:17:56', 'Entry', 'granted'),
(214, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:18:04', 'Exit', 'granted'),
(215, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:18:07', 'Entry', 'granted'),
(216, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:18:13', 'Exit', 'granted'),
(217, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:18:18', 'Entry', 'granted'),
(218, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:18:25', 'Exit', 'granted'),
(219, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:18:28', 'Entry', 'granted'),
(220, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:19:17', 'Exit', 'granted'),
(221, 8, '61 DE 6A 05', 1, NULL, '2025-10-14 02:19:22', 'Entry', 'granted'),
(222, NULL, '\0', 1, NULL, '2025-10-14 02:19:52', 'Entry', 'denied'),
(223, NULL, '82 04 10 01', 1, NULL, '2025-10-16 22:41:25', 'Entry', 'denied'),
(224, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:04:01', 'Exit', 'granted'),
(225, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:04:06', 'Entry', 'granted'),
(226, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:05:15', 'Exit', 'granted'),
(227, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:05:18', 'Entry', 'granted'),
(228, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 12:05:27', 'Entry', 'denied'),
(229, NULL, 'D3 CB B1 38', 1, NULL, '2025-10-17 12:05:34', 'Entry', 'denied'),
(230, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:05:39', 'Exit', 'granted'),
(231, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:06:05', 'Entry', 'granted'),
(232, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:06:11', 'Exit', 'granted'),
(233, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:06:18', 'Entry', 'granted'),
(234, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:06:27', 'Exit', 'granted'),
(235, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 12:07:17', 'Entry', 'denied'),
(236, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 12:07:19', 'Entry', 'denied'),
(237, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:07:22', 'Entry', 'granted'),
(238, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 12:08:17', 'Entry', 'denied'),
(239, NULL, 'D3 CB B1 38', 1, NULL, '2025-10-17 12:08:22', 'Entry', 'denied'),
(240, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:08:25', 'Exit', 'granted'),
(241, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:18:36', 'Entry', 'granted'),
(242, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:38:27', 'Exit', 'granted'),
(243, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:38:32', 'Entry', 'granted'),
(244, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:38:39', 'Exit', 'granted'),
(245, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:38:52', 'Entry', 'granted'),
(246, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:39:03', 'Exit', 'granted'),
(247, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:41:24', 'Entry', 'granted'),
(248, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:43:04', 'Exit', 'granted'),
(249, NULL, '82 04 10 01', 1, NULL, '2025-10-17 12:45:15', 'Entry', 'denied'),
(250, NULL, '82 04 10 01', 1, NULL, '2025-10-17 12:45:20', 'Entry', 'denied'),
(251, NULL, '82 04 10 01', 1, NULL, '2025-10-17 12:45:24', 'Entry', 'denied'),
(252, NULL, 'D3 CB B1 38', 1, NULL, '2025-10-17 12:45:39', 'Entry', 'denied'),
(253, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 12:45:43', 'Entry', 'denied'),
(254, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:45:59', 'Entry', 'granted'),
(255, NULL, 'D3 CB B1 38', 1, NULL, '2025-10-17 12:47:24', 'Entry', 'denied'),
(256, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 12:47:43', 'Entry', 'denied'),
(257, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:47:46', 'Exit', 'granted'),
(258, NULL, '82 04 10 01', 1, NULL, '2025-10-17 12:47:52', 'Entry', 'denied'),
(259, NULL, 'D3 CB B1 38', 1, NULL, '2025-10-17 12:47:55', 'Entry', 'denied'),
(260, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:47:57', 'Entry', 'granted'),
(261, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:49:37', 'Exit', 'granted'),
(262, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:49:41', 'Entry', 'granted'),
(263, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:50:41', 'Exit', 'granted'),
(264, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 12:50:46', 'Entry', 'granted'),
(265, NULL, 'D3 CB B1 38', 1, NULL, '2025-10-17 13:10:46', 'Entry', 'denied'),
(266, NULL, '82 04 10 01', 1, NULL, '2025-10-17 13:10:50', 'Entry', 'denied'),
(267, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 13:10:53', 'Entry', 'denied'),
(268, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:10:58', 'Exit', 'granted'),
(269, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:11:19', 'Entry', 'granted'),
(270, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:11:34', 'Exit', 'granted'),
(271, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:11:38', 'Entry', 'granted'),
(272, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:11:44', 'Exit', 'granted'),
(273, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:11:58', 'Entry', 'granted'),
(274, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:14:33', 'Exit', 'granted'),
(275, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:14:41', 'Entry', 'granted'),
(276, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:15:32', 'Exit', 'granted'),
(277, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:15:36', 'Entry', 'granted'),
(278, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:15:47', 'Exit', 'granted'),
(279, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:15:52', 'Entry', 'granted'),
(280, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:20:01', 'Exit', 'granted'),
(281, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:20:09', 'Entry', 'granted'),
(282, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:23:11', 'Exit', 'granted'),
(283, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:23:16', 'Entry', 'granted'),
(284, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:24:02', 'Exit', 'granted'),
(285, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:24:10', 'Entry', 'granted'),
(286, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:24:39', 'Exit', 'granted'),
(287, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:24:43', 'Entry', 'granted'),
(288, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:27:47', 'Exit', 'granted'),
(289, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:27:52', 'Entry', 'granted'),
(290, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:31:42', 'Exit', 'granted'),
(291, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:31:48', 'Entry', 'granted'),
(292, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:34:46', 'Exit', 'granted'),
(293, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:34:51', 'Entry', 'granted'),
(294, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:37:05', 'Exit', 'granted'),
(295, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:37:11', 'Entry', 'granted'),
(296, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:37:28', 'Exit', 'granted'),
(297, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:37:32', 'Entry', 'granted'),
(298, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:38:12', 'Exit', 'granted'),
(299, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:38:15', 'Entry', 'granted'),
(300, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:57:10', 'Exit', 'granted'),
(301, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 13:57:14', 'Entry', 'granted'),
(302, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 14:00:33', 'Exit', 'granted'),
(303, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 14:00:41', 'Entry', 'granted'),
(304, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 14:01:59', 'Exit', 'granted'),
(305, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 14:02:25', 'Entry', 'granted'),
(306, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 14:02:44', 'Exit', 'granted'),
(307, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 16:46:24', 'Entry', 'denied'),
(308, NULL, '82 04 10 01', 1, NULL, '2025-10-17 16:46:31', 'Entry', 'denied'),
(309, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 16:46:35', 'Entry', 'denied'),
(310, NULL, 'D3 CB B1 38', 1, NULL, '2025-10-17 16:46:39', 'Entry', 'denied'),
(311, NULL, 'D3 CB B1 38', 1, NULL, '2025-10-17 16:46:41', 'Entry', 'denied'),
(312, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-17 16:46:46', 'Entry', 'denied'),
(313, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:46:49', 'Entry', 'granted'),
(314, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:48:46', 'Exit', 'granted'),
(315, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:48:50', 'Entry', 'granted'),
(316, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:51:16', 'Exit', 'granted'),
(317, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:51:20', 'Entry', 'granted'),
(318, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:52:10', 'Exit', 'granted'),
(319, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:52:18', 'Entry', 'granted'),
(320, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:52:26', 'Exit', 'granted'),
(321, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:52:56', 'Entry', 'granted'),
(322, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:53:19', 'Exit', 'granted'),
(323, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:53:58', 'Entry', 'granted'),
(324, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:54:15', 'Exit', 'granted'),
(325, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 16:54:18', 'Entry', 'granted'),
(326, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 17:00:10', 'Exit', 'granted'),
(327, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 17:00:34', 'Entry', 'granted'),
(328, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 17:01:14', 'Exit', 'granted'),
(329, 8, '61 DE 6A 05', 1, NULL, '2025-10-17 17:01:16', 'Entry', 'granted'),
(330, NULL, 'RFID Ready - Waiting for Card...', 1, NULL, '2025-10-21 16:20:08', 'Entry', 'denied'),
(331, NULL, 'D3CBB138', 1, NULL, '2025-10-21 16:20:13', 'Entry', 'denied'),
(332, NULL, 'ACCESS DENIED', 1, NULL, '2025-10-21 16:20:13', 'Entry', 'denied'),
(333, NULL, '82041001', 1, NULL, '2025-10-21 16:20:20', 'Entry', 'denied'),
(334, NULL, 'ACCESS DENIED', 1, NULL, '2025-10-21 16:20:20', 'Entry', 'denied'),
(335, NULL, 'A4123D05', 1, NULL, '2025-10-21 16:20:25', 'Entry', 'denied'),
(336, NULL, 'ACCESS DENIED', 1, NULL, '2025-10-21 16:20:25', 'Entry', 'denied'),
(337, NULL, 'RFID Ready - Waiting for Card...', 1, NULL, '2025-10-21 16:20:45', 'Entry', 'denied'),
(338, NULL, 'A4123D05', 1, NULL, '2025-10-21 16:20:48', 'Entry', 'denied'),
(339, NULL, 'ACCESS DENIED', 1, NULL, '2025-10-21 16:20:48', 'Entry', 'denied'),
(340, NULL, 'D3 CB B1 38', 1, NULL, '2025-10-21 16:26:45', 'Entry', 'denied'),
(341, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-21 16:26:52', 'Entry', 'denied'),
(342, NULL, '82 04 10 01', 1, NULL, '2025-10-21 16:26:55', 'Entry', 'denied'),
(343, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 16:26:58', 'Exit', 'granted'),
(344, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 16:27:01', 'Entry', 'granted'),
(345, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 16:27:39', 'Exit', 'granted'),
(346, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 16:27:44', 'Entry', 'granted'),
(347, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 16:27:56', 'Exit', 'granted'),
(348, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 16:27:58', 'Entry', 'granted'),
(349, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 16:28:27', 'Exit', 'granted'),
(350, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 16:28:31', 'Entry', 'granted'),
(351, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:09:44', 'Exit', 'granted'),
(352, NULL, '82 04 10 01', 1, NULL, '2025-10-21 19:09:48', 'Entry', 'denied'),
(353, NULL, 'D3 CB B1 38', 1, NULL, '2025-10-21 19:09:52', 'Entry', 'denied'),
(354, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-21 19:09:55', 'Entry', 'denied'),
(355, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-21 19:10:03', 'Entry', 'denied'),
(356, NULL, 'A4 12 3D 05', 1, NULL, '2025-10-21 19:10:06', 'Entry', 'denied'),
(357, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:10:08', 'Entry', 'granted'),
(358, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:10:15', 'Exit', 'granted'),
(359, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:10:19', 'Entry', 'granted'),
(360, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:13:51', 'Exit', 'granted'),
(361, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:13:54', 'Entry', 'granted'),
(362, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:14:23', 'Exit', 'granted'),
(363, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:14:30', 'Entry', 'granted'),
(364, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:14:37', 'Exit', 'granted'),
(365, NULL, '61DE6A05', 1, NULL, '2025-10-21 19:25:57', 'Entry', 'denied'),
(366, NULL, '❌ Access DENIED', 1, NULL, '2025-10-21 19:25:59', 'Entry', 'denied'),
(367, NULL, '❌ Access DENIED', 1, NULL, '2025-10-21 19:26:02', 'Entry', 'denied'),
(368, NULL, '❌ Access DENIED', 1, NULL, '2025-10-21 19:26:04', 'Entry', 'denied'),
(369, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:30:17', 'Entry', 'granted'),
(370, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:31:00', 'Exit', 'granted'),
(371, NULL, '❌ Access DENIED', 1, NULL, '2025-10-21 19:31:06', 'Entry', 'denied'),
(372, NULL, '❌ Access DENIED', 1, NULL, '2025-10-21 19:31:08', 'Entry', 'denied'),
(373, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:33:52', 'Entry', 'granted'),
(374, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:35:00', 'Exit', 'granted'),
(375, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:36:19', 'Entry', 'granted'),
(376, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:38:14', 'Exit', 'granted'),
(377, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:40:04', 'Entry', 'granted'),
(378, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:40:12', 'Exit', 'granted'),
(379, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:40:19', 'Entry', 'granted'),
(380, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:41:31', 'Exit', 'granted'),
(381, 8, '61 DE 6A 05', 1, NULL, '2025-10-21 19:41:37', 'Entry', 'granted'),
(382, 8, '61 DE 6A 05', 1, NULL, '2025-10-22 23:09:39', 'Exit', 'granted'),
(383, 8, '61 DE 6A 05', 1, NULL, '2025-10-22 23:09:42', 'Entry', 'granted'),
(384, 8, '61 DE 6A 05', 1, NULL, '2025-10-22 23:10:10', 'Exit', 'granted'),
(385, 8, '61 DE 6A 05', 1, NULL, '2025-10-22 23:10:12', 'Entry', 'granted'),
(386, NULL, '82 04 10 01', 1, NULL, '2025-10-22 23:10:55', 'Entry', 'denied'),
(387, 8, '61 DE 6A 05', 1, NULL, '2025-10-22 23:11:13', 'Exit', 'granted'),
(388, 8, '61 DE 6A 05', 1, NULL, '2025-10-22 23:11:19', 'Entry', 'granted'),
(389, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 13:58:27', 'Exit', 'granted'),
(390, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 13:58:35', 'Entry', 'granted'),
(391, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 13:59:25', 'Exit', 'granted'),
(392, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 13:59:27', 'Entry', 'granted'),
(393, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 13:59:45', 'Exit', 'granted'),
(394, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 13:59:47', 'Entry', 'granted'),
(395, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 13:59:53', 'Exit', 'granted'),
(396, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:00:31', 'Entry', 'granted'),
(397, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:03:41', 'Exit', 'granted'),
(398, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:03:43', 'Entry', 'granted'),
(399, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:04:56', 'Exit', 'granted'),
(400, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:04:58', 'Entry', 'granted'),
(401, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:05:16', 'Exit', 'granted'),
(402, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:05:18', 'Entry', 'granted'),
(403, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:05:34', 'Exit', 'granted'),
(404, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:06:42', 'Entry', 'granted'),
(405, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:06:49', 'Exit', 'granted'),
(406, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:11:13', 'Entry', 'granted'),
(407, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:11:31', 'Exit', 'granted'),
(408, 8, '61 DE 6A 05', 1, NULL, '2025-10-23 14:11:33', 'Entry', 'granted'),
(409, NULL, 'D3CBB138', 2, NULL, '2026-02-05 14:15:29', 'Entry', 'denied'),
(410, NULL, 'D3CBB138', 2, NULL, '2026-02-05 14:16:00', 'Entry', 'denied'),
(411, 10, '5C0C3439', 2, NULL, '2026-02-05 14:16:26', 'Entry', 'granted'),
(412, 10, '5C0C3439', 2, NULL, '2026-02-05 14:16:34', 'Exit', 'granted'),
(413, 10, '5C0C3439', 2, NULL, '2026-02-05 14:16:51', 'Entry', 'granted'),
(414, 10, '5C0C3439', 2, NULL, '2026-02-05 14:31:20', 'Exit', 'granted'),
(415, NULL, '61DE6A05', 2, NULL, '2026-02-25 19:42:57', 'Entry', 'denied'),
(416, NULL, '61DE6A05', 2, NULL, '2026-02-25 19:43:00', 'Entry', 'denied'),
(417, NULL, 'A4123D05', 2, NULL, '2026-02-25 23:36:25', 'Entry', 'denied'),
(418, NULL, 'D3CBB138', 2, NULL, '2026-02-25 23:36:28', 'Entry', 'denied'),
(419, NULL, 'D3CBB138', 2, NULL, '2026-02-25 23:36:32', 'Entry', 'denied'),
(420, NULL, '82041001', 2, NULL, '2026-02-25 23:36:34', 'Entry', 'denied'),
(421, NULL, '61DE6A05', 2, NULL, '2026-02-25 23:36:41', 'Entry', 'denied'),
(422, 7, '61DE6A05', 2, NULL, '2026-02-25 23:38:02', 'Entry', 'granted'),
(423, 7, '61DE6A05', 2, NULL, '2026-02-25 23:38:18', 'Exit', 'granted'),
(424, 7, '61DE6A05', 2, NULL, '2026-02-25 23:38:27', 'Entry', 'granted'),
(425, 7, '61DE6A05', 2, NULL, '2026-02-25 23:43:06', 'Exit', 'granted'),
(426, 7, '61DE6A05', 2, NULL, '2026-02-25 23:43:18', 'Entry', 'granted'),
(427, NULL, 'D3CBB138', 2, NULL, '2026-02-25 23:43:29', 'Entry', 'denied'),
(428, NULL, 'A4123D05', 2, NULL, '2026-02-25 23:43:35', 'Entry', 'denied'),
(429, 7, '61DE6A05', 2, NULL, '2026-02-25 23:43:38', 'Exit', 'granted'),
(430, 7, '61DE6A05', 2, NULL, '2026-02-25 23:43:51', 'Entry', 'granted'),
(431, 7, '61DE6A05', 2, NULL, '2026-02-25 23:44:01', 'Exit', 'granted'),
(432, 7, '61DE6A05', 2, NULL, '2026-02-25 23:44:09', 'Entry', 'granted'),
(433, 7, '61DE6A05', 2, NULL, '2026-02-25 23:44:21', 'Exit', 'granted'),
(434, 7, '61DE6A05', 2, NULL, '2026-02-25 23:44:29', 'Entry', 'granted'),
(435, 7, '61DE6A05', 2, NULL, '2026-02-25 23:44:36', 'Exit', 'granted'),
(436, NULL, '82041001', 2, NULL, '2026-02-25 23:50:26', 'Entry', 'denied'),
(437, 7, '61DE6A05', 2, NULL, '2026-02-25 23:50:39', 'Entry', 'granted'),
(438, 7, '61DE6A05', 2, NULL, '2026-02-25 23:50:48', 'Exit', 'granted'),
(439, 7, '61DE6A05', 2, NULL, '2026-02-26 00:07:00', 'Entry', 'granted'),
(440, 7, '61DE6A05', 2, NULL, '2026-02-26 00:07:11', 'Exit', 'granted'),
(441, 7, '61DE6A05', 2, NULL, '2026-02-26 00:07:18', 'Entry', 'granted'),
(442, 7, '61DE6A05', 2, NULL, '2026-02-26 00:07:26', 'Exit', 'granted'),
(443, 7, '61DE6A05', 2, NULL, '2026-02-26 00:07:43', 'Entry', 'granted'),
(444, 7, '61DE6A05', 2, NULL, '2026-02-26 00:07:53', 'Exit', 'granted'),
(445, 7, '61DE6A05', 2, NULL, '2026-02-26 00:08:03', 'Entry', 'granted'),
(446, 7, '61DE6A05', 2, NULL, '2026-02-26 00:08:13', 'Exit', 'granted'),
(447, 7, '61DE6A05', 2, NULL, '2026-02-26 01:08:22', 'Entry', 'granted'),
(448, 7, '61DE6A05', 2, NULL, '2026-02-26 01:08:31', 'Exit', 'granted'),
(449, 7, '61DE6A05', 2, NULL, '2026-02-26 01:08:39', 'Entry', 'granted'),
(450, 7, '61DE6A05', 2, NULL, '2026-02-26 01:08:46', 'Exit', 'granted'),
(451, 7, '61DE6A05', 2, NULL, '2026-02-26 01:12:15', 'Entry', 'granted'),
(452, 7, '61DE6A05', 2, NULL, '2026-02-26 01:12:21', 'Exit', 'granted'),
(453, 7, '61DE6A05', 2, NULL, '2026-02-26 01:12:36', 'Entry', 'granted'),
(454, 7, '61DE6A05', 2, NULL, '2026-02-26 01:12:48', 'Exit', 'granted'),
(455, 7, '61DE6A05', 2, NULL, '2026-02-26 01:15:02', 'Entry', 'granted'),
(456, 7, '61DE6A05', 2, NULL, '2026-02-26 01:16:20', 'Exit', 'granted'),
(457, 7, '61DE6A05', 2, NULL, '2026-02-26 01:16:50', 'Entry', 'granted'),
(458, 7, '61DE6A05', 2, NULL, '2026-02-26 01:17:26', 'Exit', 'granted'),
(459, 7, '61DE6A05', 2, NULL, '2026-02-26 01:17:53', 'Entry', 'granted'),
(460, 7, '61DE6A05', 2, NULL, '2026-02-26 01:19:42', 'Exit', 'granted'),
(461, 7, '61DE6A05', 2, NULL, '2026-02-26 01:19:48', 'Entry', 'granted'),
(462, 7, '61DE6A05', 2, NULL, '2026-02-26 01:22:30', 'Exit', 'granted'),
(463, 7, '61DE6A05', 2, NULL, '2026-02-26 01:22:39', 'Entry', 'granted'),
(464, 7, '61DE6A05', 2, NULL, '2026-02-26 01:22:50', 'Exit', 'granted'),
(465, 7, '61DE6A05', 2, NULL, '2026-02-26 01:22:58', 'Entry', 'granted'),
(466, 7, '61DE6A05', 2, NULL, '2026-02-26 01:23:05', 'Exit', 'granted'),
(467, 7, '61DE6A05', 2, NULL, '2026-02-26 01:23:13', 'Entry', 'granted'),
(468, 7, '61DE6A05', 2, NULL, '2026-02-26 01:23:27', 'Exit', 'granted'),
(469, 7, '61DE6A05', 2, NULL, '2026-02-26 01:23:46', 'Entry', 'granted'),
(470, 7, '61DE6A05', 2, NULL, '2026-02-26 01:24:57', 'Exit', 'granted'),
(471, 7, '61DE6A05', 2, NULL, '2026-02-26 01:25:07', 'Entry', 'granted'),
(472, NULL, '51EACD17', 1, NULL, '2026-02-27 20:34:52', 'Entry', 'denied'),
(473, NULL, '51EACD17', 1, NULL, '2026-02-27 20:34:59', 'Entry', 'denied'),
(474, NULL, '51EACD17', 1, NULL, '2026-02-27 20:36:18', 'Entry', 'denied'),
(475, NULL, '1FB9BCDE', 1, NULL, '2026-02-27 20:36:28', 'Entry', 'denied'),
(476, NULL, '1FB9BCDE', 1, NULL, '2026-02-27 20:36:47', 'Entry', 'denied'),
(477, NULL, '51EACD17', 1, NULL, '2026-02-27 20:37:02', 'Entry', 'denied'),
(478, NULL, '51EACD17', 1, NULL, '2026-02-27 20:37:05', 'Entry', 'denied'),
(479, NULL, '51EACD17', 1, NULL, '2026-02-27 20:37:10', 'Entry', 'denied'),
(480, NULL, '51EACD17', 1, NULL, '2026-02-27 20:37:18', 'Entry', 'denied'),
(481, NULL, '51EACD17', 1, NULL, '2026-02-27 20:37:41', 'Entry', 'denied'),
(482, NULL, '51EACD17', 1, NULL, '2026-02-27 20:37:47', 'Entry', 'denied'),
(483, NULL, '51EACD17', 1, NULL, '2026-02-27 20:37:50', 'Entry', 'denied'),
(484, NULL, '51EACD17', 1, NULL, '2026-02-27 20:38:00', 'Entry', 'denied'),
(485, NULL, '51EACD17', 1, NULL, '2026-02-27 20:38:05', 'Entry', 'denied'),
(486, NULL, '51EACD17', 1, NULL, '2026-02-27 20:38:11', 'Entry', 'denied'),
(487, NULL, '51EACD17', 1, NULL, '2026-02-27 20:38:15', 'Entry', 'denied'),
(488, NULL, '51EACD17', 1, NULL, '2026-02-27 20:38:22', 'Entry', 'denied'),
(489, NULL, '51EACD17', 1, NULL, '2026-02-27 20:38:25', 'Entry', 'denied'),
(490, NULL, '51EACD17', 1, NULL, '2026-02-27 20:38:28', 'Entry', 'denied'),
(491, NULL, '51EACD17', 1, NULL, '2026-02-27 20:38:38', 'Entry', 'denied'),
(492, NULL, '51EACD17', 1, NULL, '2026-02-27 20:39:17', 'Entry', 'denied'),
(493, NULL, '51EACD17', 1, NULL, '2026-02-27 20:39:22', 'Entry', 'denied'),
(494, 7, '61DE6A05', 1, NULL, '2026-02-27 20:39:38', 'Entry', 'granted'),
(495, 7, '61DE6A05', 1, NULL, '2026-02-27 20:39:47', 'Exit', 'granted'),
(496, 7, '61DE6A05', 1, NULL, '2026-02-27 20:39:57', 'Entry', 'granted'),
(497, 7, '61DE6A05', 1, NULL, '2026-02-27 20:40:06', 'Exit', 'granted'),
(498, NULL, '51EACD17', 1, NULL, '2026-02-27 20:42:41', 'Entry', 'denied'),
(499, NULL, '51EACD17', 1, NULL, '2026-02-27 21:15:24', 'Entry', 'denied'),
(500, 7, '61DE6A05', 1, NULL, '2026-02-27 21:15:31', 'Entry', 'granted'),
(501, 7, '61DE6A05', 1, NULL, '2026-02-27 21:15:38', 'Exit', 'granted'),
(502, 7, '61DE6A05', 1, NULL, '2026-02-27 21:15:50', 'Entry', 'granted'),
(503, 7, '61DE6A05', 1, NULL, '2026-02-27 21:15:56', 'Exit', 'granted'),
(504, 7, '61DE6A05', 1, NULL, '2026-02-27 21:18:16', 'Entry', 'granted'),
(505, 7, '61DE6A05', 1, NULL, '2026-02-27 21:19:14', 'Exit', 'granted'),
(506, 7, '61DE6A05', 1, NULL, '2026-02-27 21:20:30', 'Entry', 'granted'),
(507, NULL, '51EACD17', 1, NULL, '2026-02-27 21:20:38', 'Entry', 'denied'),
(508, 7, '61DE6A05', 1, NULL, '2026-02-27 21:20:47', 'Exit', 'granted'),
(509, NULL, '51EACD17', 1, NULL, '2026-02-27 21:20:54', 'Entry', 'denied'),
(510, 7, '61DE6A05', 1, NULL, '2026-02-27 22:08:49', 'Entry', 'granted'),
(511, 7, '61DE6A05', 1, NULL, '2026-02-27 22:16:05', 'Exit', 'granted'),
(512, 7, '61DE6A05', 1, NULL, '2026-02-27 22:20:03', 'Entry', 'granted'),
(513, 7, '61DE6A05', 1, NULL, '2026-02-27 22:20:11', 'Exit', 'granted'),
(514, 7, '61DE6A05', 1, NULL, '2026-02-27 22:20:18', 'Entry', 'granted'),
(515, 7, '61DE6A05', 1, NULL, '2026-02-27 22:20:26', 'Exit', 'granted'),
(516, NULL, '51EACD17', 1, NULL, '2026-02-27 22:20:34', 'Entry', 'denied'),
(517, NULL, '51EACD17', 1, NULL, '2026-02-27 22:20:37', 'Entry', 'denied'),
(518, NULL, '51EACD17', 1, NULL, '2026-02-27 22:20:42', 'Entry', 'denied'),
(519, 7, '61DE6A05', 1, NULL, '2026-02-27 22:20:48', 'Entry', 'granted'),
(520, 7, '61DE6A05', 2, NULL, '2026-02-27 22:54:11', 'Exit', 'granted'),
(521, 7, '61DE6A05', 1, NULL, '2026-02-27 22:54:41', 'Exit', 'granted'),
(522, 7, '61DE6A05', 2, NULL, '2026-02-27 22:54:55', 'Entry', 'granted'),
(523, 7, '61DE6A05', 1, NULL, '2026-02-27 22:54:56', 'Entry', 'granted'),
(524, 7, '61DE6A05', 2, NULL, '2026-02-27 22:55:04', 'Exit', 'granted'),
(525, 7, '61DE6A05', 1, NULL, '2026-02-27 22:55:05', 'Exit', 'granted'),
(526, 7, '61DE6A05', 1, NULL, '2026-02-27 22:56:01', 'Entry', 'granted'),
(527, 7, '61DE6A05', 1, NULL, '2026-02-27 22:56:12', 'Exit', 'granted'),
(528, NULL, '51EACD17', 1, NULL, '2026-02-27 22:56:44', 'Entry', 'denied'),
(529, NULL, '51EACD17', 2, NULL, '2026-02-27 22:56:49', 'Entry', 'denied'),
(530, 7, '61DE6A05', 2, NULL, '2026-02-27 22:56:54', 'Entry', 'granted'),
(531, 7, '61DE6A05', 1, NULL, '2026-02-27 22:56:55', 'Entry', 'granted'),
(532, 7, '61DE6A05', 2, NULL, '2026-02-27 22:57:03', 'Exit', 'granted'),
(533, NULL, '51EACD17', 1, NULL, '2026-02-27 22:57:04', 'Entry', 'denied'),
(534, NULL, '51EACD17', 1, NULL, '2026-02-27 22:57:14', 'Entry', 'denied'),
(535, 7, '61DE6A05', 1, NULL, '2026-02-27 22:57:22', 'Exit', 'granted'),
(536, NULL, '51EACD17', 1, NULL, '2026-02-27 22:57:30', 'Entry', 'denied'),
(537, 7, '61DE6A05', 2, NULL, '2026-02-27 22:57:30', 'Entry', 'granted'),
(538, NULL, '51EACD17', 2, NULL, '2026-02-27 22:57:37', 'Entry', 'denied'),
(539, 7, '61DE6A05', 1, NULL, '2026-02-27 22:57:40', 'Entry', 'granted'),
(540, 7, '61DE6A05', 2, NULL, '2026-02-27 22:57:54', 'Exit', 'granted'),
(541, 7, '61DE6A05', 1, NULL, '2026-02-27 22:57:57', 'Exit', 'granted'),
(542, NULL, '51EACD17', 1, NULL, '2026-02-27 22:58:28', 'Entry', 'denied'),
(543, NULL, '51EACD17', 2, NULL, '2026-02-27 22:58:31', 'Entry', 'denied'),
(544, NULL, '51EACD17', 1, NULL, '2026-02-27 22:58:35', 'Entry', 'denied'),
(545, NULL, '51EACD17', 2, NULL, '2026-02-27 22:58:39', 'Entry', 'denied'),
(546, 7, '61DE6A05', 1, NULL, '2026-02-27 22:58:44', 'Entry', 'granted'),
(547, 7, '61DE6A05', 2, NULL, '2026-02-27 22:58:59', 'Entry', 'granted'),
(548, 7, '61DE6A05', 2, NULL, '2026-02-27 22:59:19', 'Exit', 'granted'),
(549, 7, '61DE6A05', 1, NULL, '2026-02-27 22:59:23', 'Exit', 'granted'),
(550, 7, '61DE6A05', 2, NULL, '2026-02-27 23:00:02', 'Entry', 'granted'),
(551, 7, '61DE6A05', 1, NULL, '2026-02-27 23:00:13', 'Entry', 'granted'),
(552, 7, '61DE6A05', 2, NULL, '2026-02-27 23:00:25', 'Exit', 'granted'),
(553, 7, '61DE6A05', 1, NULL, '2026-02-27 23:00:29', 'Exit', 'granted'),
(554, 7, '61DE6A05', 2, NULL, '2026-02-27 23:02:07', 'Entry', 'granted'),
(555, 7, '61DE6A05', 1, NULL, '2026-02-27 23:02:11', 'Entry', 'granted'),
(556, 7, '61DE6A05', 1, NULL, '2026-02-27 23:02:25', 'Exit', 'granted'),
(557, 7, '61DE6A05', 2, NULL, '2026-02-27 23:02:28', 'Exit', 'granted'),
(558, NULL, '51EACD17', 1, NULL, '2026-02-27 23:08:03', 'Entry', 'denied'),
(559, 7, '61DE6A05', 1, NULL, '2026-02-27 23:08:09', 'Entry', 'granted'),
(560, 7, '61DE6A05', 1, NULL, '2026-02-27 23:08:57', 'Exit', 'granted'),
(561, 7, '61DE6A05', 2, NULL, '2026-02-27 23:09:02', 'Entry', 'granted'),
(562, 7, '61DE6A05', 1, NULL, '2026-02-27 23:09:50', 'Entry', 'granted'),
(563, NULL, '51EACD17', 1, NULL, '2026-02-27 23:10:00', 'Entry', 'denied'),
(564, 7, '61DE6A05', 1, NULL, '2026-02-27 23:11:02', 'Exit', 'granted'),
(565, NULL, '51EACD17', 1, NULL, '2026-02-27 23:11:10', 'Entry', 'denied'),
(566, 7, '61DE6A05', 1, NULL, '2026-02-27 23:11:14', 'Entry', 'granted'),
(567, 7, '61DE6A05', 2, NULL, '2026-02-27 23:12:38', 'Exit', 'granted'),
(568, NULL, '51EACD17', 1, NULL, '2026-02-27 23:12:40', 'Entry', 'denied'),
(569, 7, '61DE6A05', 1, NULL, '2026-02-27 23:12:48', 'Exit', 'granted'),
(570, 7, '61DE6A05', 2, NULL, '2026-02-27 23:12:53', 'Entry', 'granted'),
(571, 7, '61DE6A05', 2, NULL, '2026-02-27 23:13:02', 'Exit', 'granted'),
(572, 7, '61DE6A05', 1, NULL, '2026-02-27 23:13:03', 'Entry', 'granted'),
(573, 7, '61DE6A05', 1, NULL, '2026-02-27 23:15:25', 'Exit', 'granted'),
(574, 7, '61DE6A05', 2, NULL, '2026-02-27 23:15:25', 'Entry', 'granted'),
(575, 7, '61DE6A05', 1, NULL, '2026-02-27 23:18:40', 'Entry', 'granted'),
(576, 7, '61DE6A05', 1, NULL, '2026-02-27 23:20:00', 'Exit', 'granted'),
(577, 7, '61DE6A05', 1, NULL, '2026-02-27 23:20:26', 'Entry', 'granted'),
(578, 7, '61DE6A05', 1, NULL, '2026-02-27 23:20:35', 'Exit', 'granted'),
(579, NULL, '51EACD17', 1, NULL, '2026-02-27 23:25:56', 'Entry', 'denied'),
(580, NULL, '51EACD17', 1, NULL, '2026-02-27 23:26:02', 'Entry', 'denied'),
(581, NULL, '058E807FFD6200', 1, NULL, '2026-02-28 14:26:37', 'Entry', 'denied'),
(582, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:27:21', 'Entry', 'granted'),
(583, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:27:31', 'Exit', 'granted'),
(584, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:27:42', 'Entry', 'granted'),
(585, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:27:51', 'Exit', 'granted'),
(586, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:28:02', 'Entry', 'granted'),
(587, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:28:31', 'Exit', 'granted'),
(588, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:28:50', 'Entry', 'granted'),
(589, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:29:13', 'Exit', 'granted'),
(590, NULL, '02038BBA062020', 1, NULL, '2026-02-28 14:29:46', 'Entry', 'denied'),
(591, 10, '5C0C3439', 1, NULL, '2026-02-28 14:29:52', 'Entry', 'granted'),
(592, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:30:06', 'Entry', 'granted'),
(593, 10, '5C0C3439', 1, NULL, '2026-02-28 14:30:15', 'Exit', 'granted'),
(594, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:30:25', 'Exit', 'granted'),
(595, 10, '5C0C3439', 2, NULL, '2026-02-28 14:31:12', 'Entry', 'granted'),
(596, 10, '5C0C3439', 2, NULL, '2026-02-28 14:31:28', 'Exit', 'granted'),
(597, 10, '5C0C3439', 2, NULL, '2026-02-28 14:31:38', 'Entry', 'granted'),
(598, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:31:38', 'Entry', 'granted'),
(599, 10, '5C0C3439', 2, NULL, '2026-02-28 14:31:59', 'Exit', 'granted'),
(600, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:32:05', 'Exit', 'granted'),
(601, 6, '058E807FFD6200', 2, NULL, '2026-02-28 14:35:12', 'Entry', 'granted'),
(602, NULL, '5C0368AA', 2, NULL, '2026-02-28 14:35:35', 'Entry', 'denied'),
(603, 11, '5C0368AA', 2, NULL, '2026-02-28 14:36:36', 'Entry', 'granted'),
(604, 11, '5C0368AA', 2, NULL, '2026-02-28 14:36:53', 'Exit', 'granted'),
(605, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:37:03', 'Entry', 'granted'),
(606, 11, '5C0368AA', 1, NULL, '2026-02-28 14:37:41', 'Entry', 'granted'),
(607, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:38:03', 'Exit', 'granted'),
(608, 11, '5C0368AA', 1, NULL, '2026-02-28 14:38:24', 'Exit', 'granted'),
(609, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:39:19', 'Entry', 'granted'),
(610, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:39:32', 'Exit', 'granted'),
(611, NULL, '058E807FFD6200', 1, NULL, '2026-02-28 14:39:52', 'Entry', 'denied'),
(612, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:42:35', 'Entry', 'denied'),
(613, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:42:50', 'Entry', 'denied'),
(614, 6, '058E807FFD6200', 2, NULL, '2026-02-28 14:43:06', 'Exit', 'granted'),
(615, 6, '058E807FFD6200', 2, NULL, '2026-02-28 14:43:15', 'Entry', 'denied'),
(616, 6, '058E807FFD6200', 2, NULL, '2026-02-28 14:43:23', 'Entry', 'denied'),
(617, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:43:31', 'Entry', 'denied'),
(618, 6, '058E807FFD6200', 2, NULL, '2026-02-28 14:44:08', 'Entry', 'denied'),
(619, 6, '058E807FFD6200', 1, NULL, '2026-02-28 14:44:16', 'Entry', 'denied'),
(620, 6, '058E807FFD6200', 2, NULL, '2026-02-28 14:44:17', 'Entry', 'denied'),
(621, NULL, 'A4123D05', 2, NULL, '2026-03-06 00:36:31', 'Entry', 'denied'),
(622, NULL, 'A4123D05', 2, NULL, '2026-03-06 00:36:38', 'Entry', 'denied'),
(623, NULL, 'A4123D05', 2, NULL, '2026-03-06 00:48:00', 'Entry', 'denied'),
(624, NULL, 'A4123D05', 2, NULL, '2026-03-06 00:48:09', 'Entry', 'denied'),
(625, NULL, 'A4123D05', 2, NULL, '2026-03-06 00:48:15', 'Entry', 'denied'),
(626, NULL, 'A4123D05', 2, NULL, '2026-03-06 01:00:17', 'Entry', 'denied'),
(627, NULL, 'A4123D05', 2, NULL, '2026-03-06 01:00:40', 'Entry', 'denied'),
(628, NULL, 'A4123D05', 2, NULL, '2026-03-06 01:09:08', 'Entry', 'denied'),
(629, 7, 'A4123D05', 2, NULL, '2026-03-06 01:09:40', 'Exit', 'granted'),
(630, 7, 'A4123D05', 2, NULL, '2026-03-06 01:09:44', 'Entry', 'granted'),
(631, 7, 'A4123D05', 2, NULL, '2026-03-06 01:09:56', 'Exit', 'granted'),
(632, 7, 'A4123D05', 2, NULL, '2026-03-06 01:10:05', 'Entry', 'granted'),
(633, 7, 'A4123D05', 2, NULL, '2026-03-06 01:11:52', 'Exit', 'granted'),
(634, 7, 'A4123D05', 2, NULL, '2026-03-06 01:12:00', 'Entry', 'granted'),
(635, 7, 'A4123D05', 2, NULL, '2026-03-06 01:12:08', 'Exit', 'granted'),
(636, 7, 'A4123D05', 2, NULL, '2026-03-06 01:27:12', 'Entry', 'granted'),
(637, 7, 'A4123D05', 2, NULL, '2026-03-06 01:42:51', 'Exit', 'granted'),
(638, 7, 'A4123D05', 2, NULL, '2026-03-06 01:42:59', 'Entry', 'granted'),
(639, 7, 'A4123D05', 2, NULL, '2026-03-06 01:43:16', 'Exit', 'granted'),
(640, 7, 'A4123D05', 2, NULL, '2026-03-06 01:43:28', 'Entry', 'granted'),
(641, 7, 'A4123D05', 2, NULL, '2026-03-06 01:43:36', 'Exit', 'granted'),
(642, 7, 'A4123D05', 2, NULL, '2026-03-06 01:43:49', 'Entry', 'granted'),
(643, 7, 'A4123D05', 2, NULL, '2026-03-06 01:44:22', 'Exit', 'granted'),
(644, 7, 'A4123D05', 2, NULL, '2026-03-06 01:44:29', 'Entry', 'granted'),
(645, 7, 'A4123D05', 2, NULL, '2026-03-06 01:44:38', 'Exit', 'granted'),
(646, 7, 'A4123D05', 2, NULL, '2026-03-06 01:53:26', 'Entry', 'granted'),
(647, 7, 'A4123D05', 2, NULL, '2026-03-06 01:53:39', 'Exit', 'granted'),
(648, 7, 'A4123D05', 2, NULL, '2026-03-06 01:53:43', 'Entry', 'granted'),
(649, 7, 'A4123D05', 2, NULL, '2026-03-06 01:54:01', 'Exit', 'granted'),
(650, 7, 'A4123D05', 2, NULL, '2026-03-06 01:54:05', 'Entry', 'granted'),
(651, 7, 'A4123D05', 2, NULL, '2026-03-06 01:55:51', 'Exit', 'granted'),
(652, 7, 'A4123D05', 2, NULL, '2026-03-06 01:55:57', 'Entry', 'granted'),
(653, 7, 'A4123D05', 2, NULL, '2026-03-06 01:56:33', 'Exit', 'granted'),
(654, 7, 'A4123D05', 2, NULL, '2026-03-06 01:56:37', 'Entry', 'granted'),
(655, 7, 'A4123D05', 2, NULL, '2026-03-06 01:57:29', 'Exit', 'granted'),
(656, 7, 'A4123D05', 2, NULL, '2026-03-06 01:57:37', 'Entry', 'granted'),
(657, 7, 'A4123D05', 2, NULL, '2026-03-06 02:01:17', 'Exit', 'granted'),
(658, 7, 'A4123D05', 2, NULL, '2026-03-06 02:01:22', 'Entry', 'granted'),
(659, 7, 'A4123D05', 2, NULL, '2026-03-06 02:01:30', 'Exit', 'granted'),
(660, 7, 'A4123D05', 2, NULL, '2026-03-06 02:01:35', 'Entry', 'granted'),
(661, 7, 'A4123D05', 2, NULL, '2026-03-06 02:01:43', 'Exit', 'granted'),
(662, 7, 'A4123D05', 2, NULL, '2026-03-06 02:01:46', 'Entry', 'granted'),
(663, 7, 'A4123D05', 2, NULL, '2026-03-06 02:03:56', 'Exit', 'granted'),
(664, 7, 'A4123D05', 2, NULL, '2026-03-06 02:04:03', 'Entry', 'granted'),
(665, 7, 'A4123D05', 2, NULL, '2026-03-06 02:04:13', 'Exit', 'granted'),
(666, 7, 'A4123D05', 2, NULL, '2026-03-06 02:04:17', 'Entry', 'granted'),
(667, 7, 'A4123D05', 2, NULL, '2026-03-06 02:04:26', 'Exit', 'granted'),
(668, 7, 'A4123D05', 2, NULL, '2026-03-06 02:04:30', 'Entry', 'granted'),
(669, 7, 'A4123D05', 2, NULL, '2026-03-06 02:05:20', 'Exit', 'granted'),
(670, 7, 'A4123D05', 2, NULL, '2026-03-06 02:05:24', 'Entry', 'granted');
INSERT INTO `access_log` (`Log_id`, `User_id`, `Rfid_tag`, `Room_id`, `Schedule_id`, `Access_time`, `Access_type`, `Status`) VALUES
(671, 7, 'A4123D05', 2, NULL, '2026-03-06 02:05:30', 'Exit', 'granted'),
(672, 7, 'A4123D05', 2, NULL, '2026-03-06 02:07:20', 'Entry', 'granted'),
(673, 7, 'A4123D05', 2, NULL, '2026-03-06 02:07:29', 'Exit', 'granted'),
(674, 7, 'A4123D05', 2, NULL, '2026-03-06 02:07:35', 'Entry', 'granted'),
(675, 7, 'A4123D05', 2, NULL, '2026-03-06 02:07:43', 'Exit', 'granted'),
(676, 7, 'A4123D05', 2, NULL, '2026-03-06 02:07:48', 'Entry', 'granted'),
(677, 7, 'A4123D05', 2, NULL, '2026-03-06 02:07:57', 'Exit', 'granted'),
(678, 7, 'A4123D05', 2, NULL, '2026-03-06 02:08:01', 'Entry', 'granted'),
(679, 7, 'A4123D05', 2, NULL, '2026-03-06 02:10:20', 'Exit', 'granted'),
(680, 7, 'A4123D05', 2, NULL, '2026-03-06 02:11:15', 'Entry', 'granted'),
(681, 7, 'A4123D05', 2, NULL, '2026-03-06 02:11:23', 'Exit', 'granted'),
(682, 7, 'A4123D05', 2, NULL, '2026-03-06 02:14:25', 'Entry', 'granted'),
(683, 7, 'A4123D05', 2, NULL, '2026-03-06 02:14:52', 'Exit', 'granted'),
(684, 7, 'A4123D05', 2, NULL, '2026-03-06 02:14:56', 'Entry', 'granted'),
(685, 7, 'A4123D05', 2, NULL, '2026-03-06 02:15:24', 'Exit', 'granted'),
(686, 7, 'A4123D05', 2, NULL, '2026-03-06 02:15:29', 'Entry', 'granted'),
(687, 7, 'A4123D05', 2, NULL, '2026-03-06 02:15:38', 'Exit', 'granted'),
(688, 7, 'A4123D05', 2, NULL, '2026-03-06 02:15:42', 'Entry', 'granted'),
(689, 7, 'A4123D05', 2, NULL, '2026-03-06 02:15:52', 'Exit', 'granted'),
(690, 7, 'A4123D05', 2, NULL, '2026-03-06 02:15:58', 'Entry', 'granted'),
(691, 7, 'A4123D05', 2, NULL, '2026-03-06 02:16:06', 'Exit', 'granted'),
(692, 7, 'A4123D05', 2, NULL, '2026-03-06 02:21:02', 'Entry', 'granted'),
(693, 7, 'A4123D05', 2, NULL, '2026-03-06 02:21:30', 'Exit', 'granted'),
(694, 7, 'A4123D05', 2, NULL, '2026-03-06 02:21:39', 'Entry', 'granted'),
(695, 7, 'A4123D05', 2, NULL, '2026-03-06 02:21:47', 'Exit', 'granted'),
(696, 7, 'A4123D05', 2, NULL, '2026-03-06 02:21:59', 'Entry', 'granted'),
(697, 7, 'A4123D05', 2, NULL, '2026-03-06 02:22:08', 'Exit', 'granted'),
(698, 7, 'A4123D05', 2, NULL, '2026-03-06 02:22:20', 'Entry', 'granted'),
(699, 7, 'A4123D05', 2, NULL, '2026-03-06 02:22:51', 'Exit', 'granted'),
(700, 7, 'A4123D05', 2, NULL, '2026-03-06 02:22:59', 'Entry', 'granted'),
(701, 7, 'A4123D05', 2, NULL, '2026-03-06 02:23:07', 'Exit', 'granted'),
(702, 7, 'A4123D05', 2, NULL, '2026-03-06 02:23:13', 'Entry', 'granted'),
(703, 7, 'A4123D05', 2, NULL, '2026-03-06 02:23:36', 'Exit', 'granted'),
(704, 7, 'A4123D05', 2, NULL, '2026-03-06 02:23:41', 'Entry', 'granted'),
(705, 7, 'A4123D05', 2, NULL, '2026-03-06 02:24:01', 'Exit', 'granted'),
(706, 7, 'A4123D05', 2, NULL, '2026-03-06 02:24:05', 'Entry', 'granted'),
(707, 7, 'A4123D05', 2, NULL, '2026-03-06 02:24:31', 'Exit', 'granted'),
(708, 7, 'A4123D05', 2, NULL, '2026-03-06 02:24:38', 'Entry', 'granted'),
(709, 7, 'A4123D05', 2, NULL, '2026-03-06 02:24:51', 'Exit', 'granted'),
(710, 7, 'A4123D05', 2, NULL, '2026-03-06 02:25:06', 'Entry', 'granted'),
(711, 7, 'A4123D05', 2, NULL, '2026-03-06 02:25:26', 'Exit', 'granted'),
(712, 7, 'A4123D05', 2, NULL, '2026-03-06 02:25:31', 'Entry', 'granted'),
(713, 7, 'A4123D05', 1, NULL, '2026-03-06 03:38:09', 'Entry', 'granted'),
(714, 7, 'A4123D05', 1, NULL, '2026-03-06 03:38:20', 'Exit', 'granted'),
(715, 7, 'A4123D05', 1, NULL, '2026-03-06 03:38:54', 'Entry', 'granted'),
(716, 7, 'A4123D05', 1, NULL, '2026-03-06 03:39:15', 'Exit', 'granted'),
(717, 7, 'A4123D05', 1, NULL, '2026-03-06 03:40:35', 'Entry', 'granted'),
(718, 7, 'A4123D05', 1, NULL, '2026-03-06 03:40:55', 'Exit', 'granted'),
(719, 7, 'A4123D05', 1, NULL, '2026-03-06 03:41:02', 'Entry', 'granted'),
(720, 7, 'A4123D05', 1, NULL, '2026-03-06 03:41:18', 'Exit', 'granted'),
(721, 7, 'A4123D05', 1, NULL, '2026-03-06 03:41:25', 'Entry', 'granted'),
(722, 7, 'A4123D05', 1, NULL, '2026-03-06 03:41:33', 'Exit', 'granted'),
(723, 7, 'A4123D05', 1, NULL, '2026-03-06 03:50:01', 'Entry', 'granted'),
(724, 7, 'A4123D05', 1, NULL, '2026-03-06 03:50:13', 'Exit', 'granted'),
(725, 7, 'A4123D05', 1, NULL, '2026-03-06 03:51:14', 'Entry', 'granted'),
(726, 7, 'A4123D05', 1, NULL, '2026-03-06 03:51:59', 'Exit', 'granted'),
(727, 7, 'A4123D05', 1, NULL, '2026-03-06 03:52:03', 'Entry', 'granted'),
(728, 7, 'A4123D05', 1, NULL, '2026-03-06 03:52:23', 'Exit', 'granted'),
(729, 7, 'A4123D05', 1, NULL, '2026-03-06 03:53:37', 'Entry', 'granted'),
(730, 7, 'A4123D05', 1, NULL, '2026-03-06 03:53:44', 'Exit', 'granted'),
(731, 7, 'A4123D05', 1, NULL, '2026-03-06 04:04:22', 'Entry', 'granted'),
(732, 7, 'A4123D05', 2, NULL, '2026-03-06 04:04:35', 'Exit', 'granted'),
(733, 7, 'A4123D05', 1, NULL, '2026-03-06 04:04:46', 'Exit', 'granted'),
(734, 7, 'A4123D05', 2, NULL, '2026-03-06 04:04:49', 'Entry', 'granted'),
(735, 7, 'A4123D05', 2, NULL, '2026-03-06 04:04:58', 'Exit', 'granted'),
(736, 7, 'A4123D05', 1, NULL, '2026-03-06 04:05:37', 'Entry', 'granted'),
(737, 7, 'A4123D05', 1, NULL, '2026-03-06 04:05:54', 'Exit', 'granted'),
(738, 7, 'A4123D05', 1, NULL, '2026-03-06 04:06:03', 'Entry', 'granted'),
(739, 7, 'A4123D05', 1, NULL, '2026-03-06 04:06:18', 'Exit', 'granted'),
(740, 7, 'A4123D05', 1, NULL, '2026-03-06 04:06:23', 'Entry', 'granted'),
(741, 7, 'A4123D05', 1, NULL, '2026-03-06 04:06:33', 'Exit', 'granted'),
(742, 7, 'A4123D05', 1, NULL, '2026-03-06 04:06:44', 'Entry', 'granted'),
(743, 7, 'A4123D05', 1, NULL, '2026-03-06 04:06:53', 'Exit', 'granted'),
(744, NULL, '255AD206', 1, NULL, '2026-03-06 08:11:27', 'Entry', 'denied'),
(745, NULL, '255AD206', 1, NULL, '2026-03-06 08:11:33', 'Entry', 'denied'),
(746, NULL, '9DEDD106', 1, NULL, '2026-03-06 08:12:21', 'Entry', 'denied'),
(747, 12, '255AD206', 1, NULL, '2026-03-06 08:14:26', 'Entry', 'denied'),
(748, 12, '255AD206', 1, NULL, '2026-03-06 08:14:36', 'Entry', 'denied'),
(749, 12, '255AD206', 1, NULL, '2026-03-06 08:15:26', 'Entry', 'denied'),
(750, 12, '255AD206', 1, NULL, '2026-03-06 08:15:42', 'Entry', 'denied'),
(751, 12, '255AD206', 1, NULL, '2026-03-06 08:15:49', 'Entry', 'denied'),
(752, 12, '255AD206', 1, NULL, '2026-03-06 08:17:11', 'Entry', 'granted'),
(753, 12, '255AD206', 1, NULL, '2026-03-06 08:17:19', 'Exit', 'granted'),
(754, 12, '255AD206', 1, NULL, '2026-03-06 08:18:46', 'Entry', 'granted'),
(755, 12, '255AD206', 1, NULL, '2026-03-06 08:19:09', 'Exit', 'granted'),
(756, 12, '255AD206', 1, NULL, '2026-03-06 08:30:23', 'Entry', 'denied'),
(757, 12, '255AD206', 1, NULL, '2026-03-06 08:30:25', 'Entry', 'granted'),
(758, 12, '255AD206', 1, NULL, '2026-03-06 08:30:29', 'Exit', 'granted'),
(759, 12, '255AD206', 1, NULL, '2026-03-06 08:30:34', 'Entry', 'granted'),
(760, 12, '255AD206', 1, NULL, '2026-03-06 08:31:21', 'Exit', 'granted'),
(761, 12, '255AD206', 1, NULL, '2026-03-06 08:31:28', 'Entry', 'denied'),
(762, 13, '9DEDD106', 1, NULL, '2026-03-06 08:32:08', 'Entry', 'denied'),
(763, 11, 'A4123D05', 1, NULL, '2026-03-06 08:32:23', 'Entry', 'granted'),
(764, 11, 'A4123D05', 1, NULL, '2026-03-06 08:32:33', 'Exit', 'granted'),
(765, 12, '255AD206', 1, NULL, '2026-03-06 08:32:54', 'Entry', 'denied'),
(766, 11, 'A4123D05', 1, NULL, '2026-03-06 08:32:59', 'Entry', 'granted'),
(767, 12, '255AD206', 1, NULL, '2026-03-06 08:33:35', 'Entry', 'denied'),
(768, 12, '255AD206', 1, NULL, '2026-03-06 08:33:38', 'Entry', 'granted'),
(769, 11, 'A4123D05', 1, NULL, '2026-03-06 08:33:45', 'Exit', 'granted'),
(770, 11, 'A4123D05', 1, NULL, '2026-03-06 08:33:53', 'Entry', 'granted'),
(771, 11, 'A4123D05', 1, NULL, '2026-03-06 08:34:10', 'Exit', 'granted'),
(772, 11, 'A4123D05', 1, NULL, '2026-03-06 08:35:18', 'Entry', 'granted'),
(773, 13, '9DEDD106', 1, NULL, '2026-03-06 08:35:28', 'Entry', 'granted'),
(774, 11, 'A4123D05', 1, NULL, '2026-03-06 08:35:32', 'Exit', 'granted'),
(775, 12, '255AD206', 1, NULL, '2026-03-06 08:35:51', 'Exit', 'denied'),
(776, 11, 'A4123D05', 1, NULL, '2026-03-06 08:36:01', 'Entry', 'granted'),
(777, 11, 'A4123D05', 1, NULL, '2026-03-06 08:36:12', 'Exit', 'granted'),
(778, 11, 'A4123D05', 1, NULL, '2026-03-06 08:36:22', 'Entry', 'granted'),
(779, 11, 'A4123D05', 1, NULL, '2026-03-06 08:36:25', 'Exit', 'granted'),
(780, 11, 'A4123D05', 1, NULL, '2026-03-06 08:36:39', 'Entry', 'granted'),
(781, 11, 'A4123D05', 1, NULL, '2026-03-06 08:36:46', 'Exit', 'granted'),
(782, 11, 'A4123D05', 1, NULL, '2026-03-06 08:39:59', 'Entry', 'granted'),
(783, 11, 'A4123D05', 1, NULL, '2026-03-06 08:40:07', 'Exit', 'granted'),
(784, 11, 'A4123D05', 1, NULL, '2026-03-06 08:40:38', 'Entry', 'granted'),
(785, 11, 'A4123D05', 1, NULL, '2026-03-06 08:40:46', 'Exit', 'granted'),
(786, 13, '9DEDD106', 1, NULL, '2026-03-06 08:41:00', 'Exit', 'denied'),
(787, 11, 'A4123D05', 1, NULL, '2026-03-06 08:41:05', 'Entry', 'granted'),
(788, 13, '9DEDD106', 1, NULL, '2026-03-06 08:41:14', 'Exit', 'denied'),
(789, 13, '9DEDD106', 1, NULL, '2026-03-06 08:41:15', 'Exit', 'denied'),
(790, 11, 'A4123D05', 1, NULL, '2026-03-06 08:41:21', 'Exit', 'granted'),
(791, 11, 'A4123D05', 1, NULL, '2026-03-06 08:41:28', 'Entry', 'granted'),
(792, 11, 'A4123D05', 1, NULL, '2026-03-06 08:41:37', 'Exit', 'granted'),
(793, 12, '255AD206', 1, NULL, '2026-03-06 08:41:40', 'Exit', 'denied'),
(794, 12, '255AD206', 1, NULL, '2026-03-06 11:16:04', 'Exit', 'denied'),
(795, NULL, '5C0C3439', 1, NULL, '2026-03-06 11:19:14', 'Entry', 'denied'),
(796, NULL, '5C0C3439', 1, NULL, '2026-03-06 11:19:27', 'Entry', 'denied'),
(797, NULL, '5C0C3439', 1, NULL, '2026-03-06 11:19:33', 'Entry', 'denied'),
(798, NULL, '5C0C3439', 1, NULL, '2026-03-06 11:19:37', 'Entry', 'denied'),
(799, 10, '5C0C3439', 1, NULL, '2026-03-06 11:19:53', 'Entry', 'granted'),
(800, 10, '5C0C3439', 1, NULL, '2026-03-06 11:20:18', 'Exit', 'granted'),
(801, 11, 'A4123D05', 1, NULL, '2026-03-06 11:20:23', 'Entry', 'granted'),
(802, 10, '5C0C3439', 1, NULL, '2026-03-06 11:20:25', 'Entry', 'granted'),
(803, 11, 'A4123D05', 1, NULL, '2026-03-06 11:20:31', 'Exit', 'granted'),
(804, 11, 'A4123D05', 1, NULL, '2026-03-06 11:20:36', 'Entry', 'granted'),
(805, 11, 'A4123D05', 1, NULL, '2026-03-06 11:20:44', 'Exit', 'granted'),
(806, 13, '9DEDD106', 1, NULL, '2026-03-06 11:20:53', 'Exit', 'denied'),
(807, 13, '9DEDD106', 1, NULL, '2026-03-06 11:21:17', 'Exit', 'denied'),
(808, 11, 'A4123D05', 1, NULL, '2026-03-06 11:21:22', 'Entry', 'granted'),
(809, 10, '5C0C3439', 1, NULL, '2026-03-06 11:21:28', 'Exit', 'granted'),
(810, 10, '5C0C3439', 1, NULL, '2026-03-06 11:21:49', 'Entry', 'granted'),
(811, 10, '5C0C3439', 1, NULL, '2026-03-06 11:21:57', 'Exit', 'granted'),
(812, 10, '5C0C3439', 1, NULL, '2026-03-06 11:22:08', 'Entry', 'granted'),
(813, 11, 'A4123D05', 2, NULL, '2026-03-06 11:22:13', 'Entry', 'granted'),
(814, 11, 'A4123D05', 2, NULL, '2026-03-06 11:22:23', 'Exit', 'granted'),
(815, 10, '5C0C3439', 1, NULL, '2026-03-06 11:22:27', 'Exit', 'granted'),
(816, 11, 'A4123D05', 1, NULL, '2026-03-06 11:22:35', 'Exit', 'granted'),
(817, 11, 'A4123D05', 2, NULL, '2026-03-06 11:22:37', 'Entry', 'granted'),
(818, 13, '9DEDD106', 1, NULL, '2026-03-06 11:23:06', 'Exit', 'denied'),
(819, 11, 'A4123D05', 2, NULL, '2026-03-06 11:23:18', 'Exit', 'granted'),
(820, 11, 'A4123D05', 1, NULL, '2026-03-06 11:23:40', 'Entry', 'granted'),
(821, 11, 'A4123D05', 1, NULL, '2026-03-06 11:23:50', 'Exit', 'granted'),
(822, 11, 'A4123D05', 2, NULL, '2026-03-06 11:24:35', 'Entry', 'granted'),
(823, 11, 'A4123D05', 2, NULL, '2026-03-06 11:24:44', 'Exit', 'granted'),
(824, 11, 'A4123D05', 1, NULL, '2026-03-06 11:24:50', 'Entry', 'granted'),
(825, 11, 'A4123D05', 1, NULL, '2026-03-06 11:24:57', 'Exit', 'granted'),
(826, 12, '255AD206', 1, NULL, '2026-03-06 11:34:58', 'Exit', 'denied'),
(827, 11, 'A4123D05', 1, NULL, '2026-03-06 11:35:09', 'Entry', 'granted'),
(828, 11, 'A4123D05', 1, NULL, '2026-03-06 11:35:39', 'Exit', 'granted'),
(829, 11, 'A4123D05', 1, NULL, '2026-03-06 11:35:48', 'Entry', 'granted'),
(830, 11, 'A4123D05', 1, NULL, '2026-03-06 11:35:57', 'Exit', 'granted'),
(831, 12, '255AD206', 2, NULL, '2026-03-06 11:36:30', 'Entry', 'denied'),
(832, 11, 'A4123D05', 2, NULL, '2026-03-06 11:36:35', 'Entry', 'granted'),
(833, 11, 'A4123D05', 2, NULL, '2026-03-06 11:37:02', 'Exit', 'granted'),
(834, 11, 'A4123D05', 1, NULL, '2026-03-06 11:40:55', 'Entry', 'granted'),
(835, 12, '255AD206', 1, NULL, '2026-03-06 13:31:49', 'Exit', 'denied'),
(836, 12, '255AD206', 1, NULL, '2026-03-06 13:31:57', 'Exit', 'denied'),
(837, 12, '255AD206', 1, NULL, '2026-03-06 13:32:02', 'Exit', 'denied'),
(838, 12, '255AD206', 1, NULL, '2026-03-06 13:32:06', 'Exit', 'denied'),
(839, 12, '255AD206', 1, NULL, '2026-03-06 13:32:12', 'Exit', 'denied'),
(840, 11, 'A4123D05', 1, NULL, '2026-03-06 13:32:38', 'Exit', 'granted'),
(841, 11, 'A4123D05', 1, NULL, '2026-03-06 13:33:16', 'Entry', 'granted'),
(842, 13, '9DEDD106', 1, NULL, '2026-03-06 13:35:16', 'Exit', 'granted'),
(843, 13, '9DEDD106', 1, NULL, '2026-03-06 13:35:28', 'Entry', 'granted'),
(844, 13, '9DEDD106', 1, NULL, '2026-03-06 13:37:14', 'Exit', 'denied'),
(845, 11, 'A4123D05', 1, NULL, '2026-03-06 13:37:23', 'Exit', 'granted'),
(846, 11, 'A4123D05', 1, NULL, '2026-03-06 13:37:37', 'Entry', 'granted'),
(847, 13, '9DEDD106', 1, NULL, '2026-03-06 13:37:45', 'Exit', 'denied'),
(848, 11, 'A4123D05', 1, NULL, '2026-03-06 13:37:50', 'Exit', 'granted'),
(849, 13, '9DEDD106', 1, NULL, '2026-03-06 13:45:44', 'Exit', 'denied'),
(850, NULL, 'B7556506', 1, NULL, '2026-03-06 13:47:31', 'Entry', 'denied'),
(851, NULL, 'B7556506', 1, NULL, '2026-03-06 13:47:36', 'Entry', 'denied'),
(852, NULL, 'B7556506', 1, NULL, '2026-03-06 13:47:43', 'Entry', 'denied'),
(853, 12, '255AD206', 1, NULL, '2026-03-06 13:47:47', 'Exit', 'denied'),
(854, NULL, 'B7556506', 1, NULL, '2026-03-06 13:47:53', 'Entry', 'denied'),
(855, NULL, 'B7556506', 1, NULL, '2026-03-06 13:47:59', 'Entry', 'denied'),
(856, NULL, 'B7556506', 1, NULL, '2026-03-06 13:48:06', 'Entry', 'denied'),
(857, NULL, 'B7556506', 1, NULL, '2026-03-06 13:48:12', 'Entry', 'denied'),
(858, 14, 'B7556506', 1, NULL, '2026-03-06 13:48:59', 'Entry', 'denied'),
(859, NULL, '82041001', 1, NULL, '2026-03-07 13:18:16', 'Entry', 'denied'),
(860, NULL, 'D3CBB138', 1, NULL, '2026-03-07 13:18:21', 'Entry', 'denied'),
(861, NULL, 'D3CBB138', 1, NULL, '2026-03-07 13:19:37', 'Entry', 'denied'),
(862, NULL, 'D3CBB138', 1, NULL, '2026-03-07 13:20:04', 'Entry', 'denied'),
(863, NULL, '82041001', 1, NULL, '2026-03-07 13:20:09', 'Entry', 'denied'),
(864, NULL, '82041001', 1, NULL, '2026-03-07 13:20:14', 'Entry', 'denied'),
(865, NULL, 'D3CBB138', 1, NULL, '2026-03-07 13:20:48', 'Entry', 'denied'),
(866, NULL, 'D3CBB138', 1, NULL, '2026-03-07 13:20:53', 'Entry', 'denied'),
(867, 14, '82041001', 1, NULL, '2026-03-07 13:20:58', 'Entry', 'granted'),
(868, 14, '82041001', 1, NULL, '2026-03-07 13:21:16', 'Exit', 'granted'),
(869, 14, '82041001', 1, NULL, '2026-03-07 13:21:52', 'Entry', 'granted'),
(870, 14, '82041001', 1, NULL, '2026-03-07 13:22:00', 'Exit', 'granted'),
(871, 14, '82041001', 1, NULL, '2026-03-07 13:22:20', 'Entry', 'granted'),
(872, 14, '82041001', 1, NULL, '2026-03-07 13:22:27', 'Exit', 'granted'),
(873, 14, '82041001', 1, NULL, '2026-03-07 13:24:04', 'Entry', 'granted'),
(874, 14, '82041001', 1, NULL, '2026-03-07 13:24:12', 'Exit', 'granted'),
(875, 14, '82041001', 1, NULL, '2026-03-07 13:24:18', 'Entry', 'granted'),
(876, 14, '82041001', 1, NULL, '2026-03-07 13:24:27', 'Exit', 'granted'),
(877, 14, '82041001', 1, NULL, '2026-03-07 13:24:57', 'Entry', 'granted'),
(878, 14, '82041001', 1, NULL, '2026-03-07 13:25:27', 'Exit', 'granted'),
(879, 14, '82041001', 1, NULL, '2026-03-07 13:25:40', 'Entry', 'granted'),
(880, 14, '82041001', 1, NULL, '2026-03-07 13:25:53', 'Exit', 'granted'),
(881, 14, '82041001', 1, NULL, '2026-03-07 13:26:10', 'Entry', 'granted'),
(882, 14, '82041001', 1, NULL, '2026-03-07 13:26:27', 'Exit', 'granted'),
(883, 14, '82041001', 1, NULL, '2026-03-07 13:26:43', 'Entry', 'granted'),
(884, 14, '82041001', 1, NULL, '2026-03-07 13:26:51', 'Exit', 'granted'),
(885, 14, '82041001', 1, NULL, '2026-03-07 13:26:56', 'Entry', 'granted'),
(886, 14, '82041001', 1, NULL, '2026-03-07 13:27:22', 'Exit', 'granted'),
(887, 14, '82041001', 1, NULL, '2026-03-07 13:28:02', 'Entry', 'granted'),
(888, 14, '82041001', 1, NULL, '2026-03-07 13:28:43', 'Exit', 'granted'),
(889, 14, '82041001', 1, NULL, '2026-03-07 13:28:48', 'Entry', 'granted'),
(890, 14, '82041001', 1, NULL, '2026-03-07 13:28:57', 'Exit', 'granted'),
(891, 14, '82041001', 1, NULL, '2026-03-07 13:29:21', 'Entry', 'granted'),
(892, 14, '82041001', 1, NULL, '2026-03-07 13:29:31', 'Exit', 'granted'),
(893, 14, '82041001', 1, NULL, '2026-03-07 13:29:39', 'Entry', 'granted'),
(894, 14, '82041001', 1, NULL, '2026-03-07 13:29:46', 'Exit', 'granted'),
(895, 14, '82041001', 1, NULL, '2026-03-07 13:31:19', 'Entry', 'granted'),
(896, NULL, 'D3CBB138', 1, NULL, '2026-03-07 13:33:04', 'Entry', 'denied'),
(897, NULL, 'D3CBB138', 1, NULL, '2026-03-07 13:35:34', 'Entry', 'denied'),
(898, NULL, 'D3CBB138', 1, NULL, '2026-03-07 13:35:52', 'Entry', 'denied'),
(899, 14, '82041001', 1, NULL, '2026-03-07 13:36:39', 'Exit', 'granted'),
(900, NULL, 'D3CBB138', 1, NULL, '2026-03-07 13:36:51', 'Entry', 'denied'),
(901, 14, '82041001', 1, NULL, '2026-03-07 13:37:07', 'Entry', 'granted'),
(902, 14, '82041001', 1, NULL, '2026-03-07 13:37:17', 'Exit', 'granted'),
(903, 14, '82041001', 1, NULL, '2026-03-07 13:37:29', 'Entry', 'granted'),
(904, 14, '82041001', 1, NULL, '2026-03-07 13:37:42', 'Exit', 'granted'),
(905, 14, '82041001', 1, NULL, '2026-03-07 13:37:51', 'Entry', 'granted'),
(906, 14, '82041001', 1, NULL, '2026-03-07 13:38:18', 'Exit', 'granted'),
(907, 14, '82041001', 1, NULL, '2026-03-07 13:38:25', 'Entry', 'granted'),
(908, 14, '82041001', 1, NULL, '2026-03-07 13:38:35', 'Exit', 'granted'),
(909, 14, '82041001', 1, NULL, '2026-03-07 13:43:00', 'Entry', 'granted'),
(910, 14, '82041001', 1, NULL, '2026-03-07 13:43:10', 'Exit', 'granted'),
(911, 14, '82041001', 1, NULL, '2026-03-07 13:44:12', 'Entry', 'granted'),
(912, 14, '82041001', 1, NULL, '2026-03-07 13:44:37', 'Exit', 'granted'),
(913, NULL, 'D3CBB138', 1, NULL, '2026-03-07 14:30:37', 'Entry', 'denied'),
(914, 14, '82041001', 1, NULL, '2026-03-07 14:30:45', 'Entry', 'granted'),
(915, 14, '82041001', 1, NULL, '2026-03-07 14:32:14', 'Exit', 'granted'),
(916, 14, '82041001', 1, NULL, '2026-03-07 14:32:21', 'Entry', 'granted'),
(917, 14, '82041001', 1, NULL, '2026-03-07 14:32:48', 'Exit', 'granted'),
(918, 14, '82041001', 1, NULL, '2026-03-07 14:32:52', 'Entry', 'granted'),
(919, 14, '82041001', 1, NULL, '2026-03-07 14:33:13', 'Exit', 'granted'),
(920, 14, '82041001', 1, NULL, '2026-03-07 14:33:17', 'Entry', 'granted'),
(921, NULL, 'D3CBB138', 1, NULL, '2026-03-07 14:34:12', 'Entry', 'denied'),
(922, NULL, 'D3CBB138', 1, NULL, '2026-03-07 14:34:16', 'Entry', 'denied'),
(923, NULL, 'D3CBB138', 1, NULL, '2026-03-07 14:34:22', 'Entry', 'denied'),
(924, 14, '82041001', 1, NULL, '2026-03-07 14:34:27', 'Exit', 'granted'),
(925, 14, '82041001', 1, NULL, '2026-03-07 14:34:31', 'Entry', 'granted'),
(926, 14, '82041001', 1, NULL, '2026-03-07 14:38:30', 'Exit', 'granted'),
(927, 14, '82041001', 1, NULL, '2026-03-07 14:38:35', 'Entry', 'granted'),
(928, 14, '82041001', 1, NULL, '2026-03-07 14:38:42', 'Exit', 'granted'),
(929, 14, '82041001', 1, NULL, '2026-03-07 14:38:46', 'Entry', 'granted'),
(930, 14, '82041001', 1, NULL, '2026-03-07 14:42:23', 'Exit', 'granted'),
(931, 14, '82041001', 1, NULL, '2026-03-07 14:42:27', 'Entry', 'granted'),
(932, 14, '82041001', 1, NULL, '2026-03-07 14:42:52', 'Exit', 'granted'),
(933, 14, '82041001', 1, NULL, '2026-03-07 14:42:56', 'Entry', 'granted'),
(934, 14, '82041001', 1, NULL, '2026-03-07 14:43:04', 'Exit', 'granted'),
(935, 14, '82041001', 1, NULL, '2026-03-07 14:43:08', 'Entry', 'granted'),
(936, 14, '82041001', 1, NULL, '2026-03-07 14:43:17', 'Exit', 'granted'),
(937, 14, '82041001', 1, NULL, '2026-03-07 14:45:59', 'Entry', 'granted'),
(938, 14, '82041001', 1, NULL, '2026-03-07 14:47:14', 'Exit', 'granted'),
(939, 14, '82041001', 1, NULL, '2026-03-07 14:47:24', 'Entry', 'granted'),
(940, 14, '82041001', 1, NULL, '2026-03-07 14:47:33', 'Exit', 'granted'),
(941, 14, '82041001', 1, NULL, '2026-03-07 14:47:40', 'Entry', 'granted'),
(942, 14, '82041001', 1, NULL, '2026-03-07 14:49:36', 'Exit', 'granted'),
(943, 14, '82041001', 1, NULL, '2026-03-07 14:49:55', 'Entry', 'granted'),
(944, 14, '82041001', 1, NULL, '2026-03-07 15:04:20', 'Exit', 'granted'),
(945, 14, '82041001', 1, NULL, '2026-03-07 15:06:32', 'Entry', 'granted'),
(946, 14, '82041001', 1, NULL, '2026-03-07 15:06:57', 'Exit', 'granted');

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
  `FLOOR` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classrooms`
--

INSERT INTO `classrooms` (`Room_id`, `Room_code`, `Status`, `Classroom_type`, `Capacity`, `FLOOR`) VALUES
(1, 'ROOM101', 'Unoccupied', 'CLASSROOM', 50, '2ND Floor'),
(2, 'ROOM102', 'Unoccupied', 'CLASSROOM', 30, '1st Floor'),
(13, '454', 'Unoccupied', 'CLASSROOM', 79, '3rd Floor');

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
(1, 'BSIT 1-11'),
(3, 'BSIT 2-11'),
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
(1, 'D4:E9:F4:65:F5:1C', 1, 'POWER', '2026-03-07 13:33:25', 'Offline'),
(2, 'D4:E9:F4:65:76:D8', 1, 'POWER', '2026-03-07 15:48:15', 'Online');

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
  `End_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`Schedule_id`, `Subject_id`, `Room_id`, `Faculty_id`, `Day`, `Start_time`, `End_time`) VALUES
(1, 1, 2, 6, 'Fri', '10:00:00', '18:00:00'),
(2, 1, 1, 6, 'Mon', '10:00:00', '12:00:00'),
(4, 3, 2, 6, 'Wed', '16:00:00', '19:00:00'),
(6, 5, 1, 6, 'Fri', '18:00:00', '21:00:00'),
(7, 6, 1, 6, 'Fri', '15:00:00', '18:00:00'),
(8, 7, 2, 7, 'Mon', '07:00:00', '10:00:00'),
(9, 8, 2, 7, 'Mon', '13:00:00', '16:00:00'),
(10, 3, 2, 6, 'Mon', '18:00:00', '22:00:00'),
(11, 3, 1, 8, 'Sun', '00:34:00', '12:34:00'),
(12, 3, 1, 14, 'Sat', '14:30:00', '16:00:00');

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
(1, 1, 2),
(2, 2, 2),
(4, 4, 2),
(6, 6, 131),
(7, 7, 131),
(8, 8, 131),
(9, 9, 131),
(10, 10, 141),
(11, 10, 141),
(14, 11, 3),
(15, 11, 3),
(42, 12, 1),
(43, 12, 1);

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
(4, 'asdawdasdawd ajshdgawj ', 'awdasd'),
(5, 'OAC310', 'Business Law'),
(6, 'OAE301', 'Human Anatomy and Physiology'),
(7, 'GEE303', 'GE Elective 3- Business Logic'),
(8, 'OAC309', 'Customer Relations');

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
(6, '058E807FFD6200', 'Anna', 'Cruz', 1, 'Student', 'Active'),
(7, 'A4123D050', 'Paul', 'Santos', NULL, 'Admin', 'Active'),
(8, '61 DE 6A 05', 'Michael', 'Tan', NULL, 'Admin', 'Inactive'),
(10, '5C0C3439', 'boss', 'erwin', NULL, 'Admin', 'Active'),
(11, 'A4123D05', 'Gerarld', 'Jamindang', NULL, 'Admin', 'Active'),
(12, '255AD206', 'JOhn', 'Wick', 1, 'Student', 'Active'),
(13, '9DEDD106', 'Stephen', 'James', 3, 'Student', 'Active'),
(14, '82041001', 'Jino', 'Barrantes', NULL, 'Admin', 'Active');

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
  ADD KEY `Schedule_id` (`Schedule_id`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_id`),
  ADD UNIQUE KEY `Rfid_tag` (`Rfid_tag`),
  ADD KEY `CourseSection_id` (`CourseSection_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_log`
--
ALTER TABLE `access_log`
  MODIFY `Log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=947;

--
-- AUTO_INCREMENT for table `classrooms`
--
ALTER TABLE `classrooms`
  MODIFY `Room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `course_section`
--
ALTER TABLE `course_section`
  MODIFY `CourseSection_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `rfid_reader`
--
ALTER TABLE `rfid_reader`
  MODIFY `Reader_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `Schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `schedule_access`
--
ALTER TABLE `schedule_access`
  MODIFY `Rule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `Subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
