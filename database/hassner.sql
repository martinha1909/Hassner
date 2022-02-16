-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2022 at 08:49 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hassner`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `account_type` varchar(50) NOT NULL,
  `id` int(11) NOT NULL,
  `Shares` int(50) NOT NULL,
  `balance` decimal(10,1) NOT NULL,
  `rate` decimal(10,1) NOT NULL,
  `Share_Distributed` int(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `billing_address` varchar(10) NOT NULL,
  `Full_name` varchar(50) NOT NULL,
  `City` varchar(20) NOT NULL,
  `State` varchar(20) NOT NULL,
  `ZIP` varchar(6) NOT NULL,
  `Card_number` varchar(19) NOT NULL,
  `Transit_no` varchar(5) NOT NULL,
  `Inst_no` varchar(3) NOT NULL,
  `Account_no` varchar(11) NOT NULL,
  `Swift` varchar(8) NOT NULL,
  `price_per_share` decimal(10,1) NOT NULL,
  `Monthly_shareholder` int(11) NOT NULL,
  `Income` decimal(10,1) NOT NULL,
  `Market_cap` decimal(10,1) NOT NULL,
  `shares_repurchase` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`username`, `password`, `account_type`, `id`, `Shares`, `balance`, `rate`, `Share_Distributed`, `email`, `billing_address`, `Full_name`, `City`, `State`, `ZIP`, `Card_number`, `Transit_no`, `Inst_no`, `Account_no`, `Swift`, `price_per_share`, `Monthly_shareholder`, `Income`, `Market_cap`, `shares_repurchase`) VALUES
('21 Savage', 'artist', 'artist', 6, 0, '0.0', '0.0', 0, '21savage@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('88Glam', 'artist', 'artist', 2, 11154, '1218400.0', '0.0', 24017, '12@gmail.com', '1234', '88 Camino', 'Toronto', 'Ontario', '123456', '1111-2222-3333-4444', '12345', '123', '12345678', 'AAAABBCC', '100.0', 0, '0.0', '1115400.0', 395),
('Al Lure', 'artist', 'artist', 47, 10, '150.0', '0.0', 10, '12345@gmail.com', '', '', '', '', '', '', '', '', '', '', '10.0', 0, '0.0', '100.0', 0),
('daniel', 'user', 'user', 8, 0, '10000000.0', '0.0', 0, 'iosrghn@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('Drake', 'artist', 'artist', 11, 0, '0.0', '0.0', 0, 'qwerty@gmail.com', 'Drake', '', '', '', '', '', '', '', '', '', '10.0', 0, '0.0', '0.0', 0),
('kai', 'user', 'user', 4, 8, '7551088.3', '0.0', 0, '123@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('martin', 'user', 'user', 1, -391, '389052.0', '0.0', 0, 'minhvuha1909@gmail.com', '2240', 'Vu Ha (Martin)', 'Calgary', 'AB', 'T2N', '1111-2222-3333-4444', '12345', '123', '12345678', 'AAAABBCC', '0.0', 0, '0.0', '0.0', 0),
('NAV', 'artist', 'artist', 3, 0, '0.0', '0.0', 1, '4321@gmail.com', '', '', '', '', '', '', '', '', '', '', '10.0', 0, '0.0', '0.0', 0),
('riley', 'user', 'user', 7, 0, '7206987.0', '0.0', 0, 'efin@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('testuser', 'user', 'user', 30, 0, '0.0', '0.0', 0, 'testuser@gmail.com', 'testuser', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('vitor', 'user', 'user', 5, 0, '5641130.0', '0.0', 0, '1234@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0);

-- --------------------------------------------------------

--
-- Table structure for table `artist_account_data`
--

CREATE TABLE `artist_account_data` (
  `artist_username` varchar(20) NOT NULL,
  `ticker` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `artist_account_data`
--

INSERT INTO `artist_account_data` (`artist_username`, `ticker`) VALUES
('21 Savage', '21SV'),
('88Glam', '88GM'),
('Al Lure', '18AL'),
('Drake', '00DR'),
('NAV', '11NA');

-- --------------------------------------------------------

--
-- Table structure for table `artist_followers`
--

CREATE TABLE `artist_followers` (
  `artist_username` varchar(20) NOT NULL,
  `user_username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `artist_followers`
--

INSERT INTO `artist_followers` (`artist_username`, `user_username`) VALUES
('21 Savage', 'martin'),
('88Glam', 'martin'),
('Drake', 'martin');

-- --------------------------------------------------------

--
-- Table structure for table `artist_shareholders`
--

CREATE TABLE `artist_shareholders` (
  `user_username` varchar(20) NOT NULL,
  `artist_username` varchar(20) NOT NULL,
  `shares_owned` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `artist_shareholders`
--

INSERT INTO `artist_shareholders` (`user_username`, `artist_username`, `shares_owned`) VALUES
('88Glam', '88Glam', 395),
('kai', '88Glam', 8791),
('kai', 'Al Lure', 8),
('martin', '88Glam', 2363),
('martin', 'Al Lure', 2);

-- --------------------------------------------------------

--
-- Table structure for table `artist_stock_change`
--

CREATE TABLE `artist_stock_change` (
  `artist_username` varchar(20) NOT NULL,
  `price_per_share` decimal(10,1) NOT NULL,
  `date_recorded` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `artist_stock_change`
--

INSERT INTO `artist_stock_change` (`artist_username`, `price_per_share`, `date_recorded`) VALUES
('21 Savage', '1.3', '2021-11-06 19:30:00'),
('21 Savage', '1.3', '2021-11-06 19:45:00'),
('21 Savage', '1.3', '2021-11-06 20:00:00'),
('21 Savage', '1.3', '2021-11-07 14:52:30'),
('21 Savage', '1.3', '2021-11-07 15:00:00'),
('21 Savage', '1.3', '2021-11-07 15:15:00'),
('21 Savage', '1.3', '2021-11-07 15:30:00'),
('21 Savage', '1.3', '2021-11-07 21:30:00'),
('21 Savage', '1.3', '2021-11-07 21:45:00'),
('21 Savage', '1.3', '2021-11-08 11:45:00'),
('21 Savage', '1.3', '2021-11-08 12:00:00'),
('21 Savage', '1.3', '2021-11-08 12:15:00'),
('21 Savage', '1.3', '2021-11-08 12:30:00'),
('21 Savage', '2.0', '2021-11-10 11:45:00'),
('21 Savage', '2.0', '2021-11-10 12:00:00'),
('21 Savage', '2.0', '2021-11-10 12:15:00'),
('21 Savage', '2.0', '2021-11-10 12:30:00'),
('21 Savage', '2.0', '2021-11-10 12:45:00'),
('21 Savage', '2.0', '2021-11-10 13:00:00'),
('21 Savage', '2.0', '2021-11-10 13:15:00'),
('21 Savage', '2.0', '2021-11-10 13:30:00'),
('21 Savage', '2.0', '2021-11-10 13:45:00'),
('21 Savage', '2.0', '2021-11-10 14:00:00'),
('21 Savage', '2.0', '2021-11-10 14:15:00'),
('21 Savage', '2.0', '2021-11-10 14:30:00'),
('21 Savage', '2.0', '2021-11-10 14:45:00'),
('21 Savage', '2.0', '2021-11-10 15:00:00'),
('21 Savage', '2.0', '2021-11-10 15:15:00'),
('21 Savage', '2.0', '2021-11-10 15:30:00'),
('21 Savage', '2.0', '2021-11-10 15:45:00'),
('21 Savage', '2.0', '2021-11-10 16:00:00'),
('21 Savage', '2.0', '2021-11-10 16:15:00'),
('21 Savage', '2.0', '2021-11-10 16:30:00'),
('21 Savage', '2.0', '2021-11-10 16:45:00'),
('21 Savage', '2.0', '2021-11-10 17:00:00'),
('21 Savage', '2.0', '2021-11-10 17:15:00'),
('21 Savage', '2.0', '2021-11-12 12:30:00'),
('21 Savage', '2.0', '2021-11-12 12:45:00'),
('21 Savage', '2.0', '2021-11-12 13:00:00'),
('21 Savage', '2.0', '2021-11-12 13:15:00'),
('21 Savage', '2.0', '2021-11-12 13:30:00'),
('21 Savage', '2.0', '2021-11-12 13:45:00'),
('21 Savage', '2.0', '2021-11-12 14:00:00'),
('88Glam', '2.5', '2021-10-26 19:30:00'),
('88Glam', '2.2', '2021-10-27 19:30:00'),
('88Glam', '3.2', '2021-10-28 19:30:00'),
('88Glam', '2.6', '2021-10-29 19:30:00'),
('88Glam', '2.9', '2021-10-30 19:30:00'),
('88Glam', '2.9', '2021-10-31 19:30:00'),
('88Glam', '4.9', '2021-11-01 19:30:00'),
('88Glam', '4.5', '2021-11-02 19:30:00'),
('88Glam', '2.0', '2021-11-03 19:30:00'),
('88Glam', '2.0', '2021-11-04 19:30:00'),
('88Glam', '4.0', '2021-11-05 19:30:00'),
('88Glam', '3.0', '2021-11-05 19:45:00'),
('88Glam', '5.0', '2021-11-05 20:00:00'),
('88Glam', '6.0', '2021-11-06 19:30:00'),
('88Glam', '6.0', '2021-11-06 19:45:00'),
('88Glam', '6.0', '2021-11-06 20:00:00'),
('88Glam', '6.0', '2021-11-07 14:52:30'),
('88Glam', '6.0', '2021-11-07 15:00:00'),
('88Glam', '3.6', '2021-11-07 15:15:00'),
('88Glam', '3.6', '2021-11-07 15:30:00'),
('88Glam', '6.0', '2021-11-07 21:30:00'),
('88Glam', '6.0', '2021-11-07 21:45:00'),
('88Glam', '3.6', '2021-11-08 11:45:00'),
('88Glam', '3.6', '2021-11-08 12:00:00'),
('88Glam', '3.6', '2021-11-08 12:15:00'),
('88Glam', '3.6', '2021-11-08 12:30:00'),
('88Glam', '4.7', '2021-11-10 11:45:00'),
('88Glam', '4.7', '2021-11-10 12:00:00'),
('88Glam', '4.7', '2021-11-10 12:15:00'),
('88Glam', '4.7', '2021-11-10 12:30:00'),
('88Glam', '4.7', '2021-11-10 12:45:00'),
('88Glam', '4.7', '2021-11-10 13:00:00'),
('88Glam', '4.7', '2021-11-10 13:15:00'),
('88Glam', '4.7', '2021-11-10 13:30:00'),
('88Glam', '4.7', '2021-11-10 13:45:00'),
('88Glam', '4.7', '2021-11-10 14:00:00'),
('88Glam', '4.7', '2021-11-10 14:15:00'),
('88Glam', '4.7', '2021-11-10 14:30:00'),
('88Glam', '4.7', '2021-11-10 14:45:00'),
('88Glam', '4.7', '2021-11-10 15:00:00'),
('88Glam', '4.7', '2021-11-10 15:15:00'),
('88Glam', '4.7', '2021-11-10 15:30:00'),
('88Glam', '4.7', '2021-11-10 15:45:00'),
('88Glam', '4.7', '2021-11-10 16:00:00'),
('88Glam', '4.7', '2021-11-10 16:15:00'),
('88Glam', '4.7', '2021-11-10 16:30:00'),
('88Glam', '4.7', '2021-11-10 16:45:00'),
('88Glam', '4.7', '2021-11-10 17:00:00'),
('88Glam', '4.7', '2021-11-10 17:15:00'),
('88Glam', '3.3', '2021-11-12 12:30:00'),
('88Glam', '3.3', '2021-11-12 12:45:00'),
('88Glam', '3.3', '2021-11-12 13:00:00'),
('88Glam', '3.3', '2021-11-12 13:15:00'),
('88Glam', '3.3', '2021-11-12 13:30:00'),
('88Glam', '3.3', '2021-11-12 13:45:00'),
('88Glam', '3.3', '2021-11-12 14:00:00'),
('Drake', '1.0', '2021-11-06 19:30:00'),
('Drake', '1.0', '2021-11-06 19:45:00'),
('Drake', '1.0', '2021-11-06 20:00:00'),
('Drake', '1.0', '2021-11-07 14:52:30'),
('Drake', '1.0', '2021-11-07 15:00:00'),
('Drake', '1.0', '2021-11-07 15:15:00'),
('Drake', '1.0', '2021-11-07 15:30:00'),
('Drake', '1.0', '2021-11-07 21:30:00'),
('Drake', '1.0', '2021-11-07 21:45:00'),
('Drake', '1.0', '2021-11-08 11:45:00'),
('Drake', '1.0', '2021-11-08 12:00:00'),
('Drake', '1.0', '2021-11-08 12:15:00'),
('Drake', '1.0', '2021-11-08 12:30:00'),
('Drake', '2.2', '2021-11-10 11:45:00'),
('Drake', '2.2', '2021-11-10 12:00:00'),
('Drake', '2.2', '2021-11-10 12:15:00'),
('Drake', '2.2', '2021-11-10 12:30:00'),
('Drake', '2.2', '2021-11-10 12:45:00'),
('Drake', '2.2', '2021-11-10 13:00:00'),
('Drake', '2.2', '2021-11-10 13:15:00'),
('Drake', '2.2', '2021-11-10 13:30:00'),
('Drake', '2.2', '2021-11-10 13:45:00'),
('Drake', '2.2', '2021-11-10 14:00:00'),
('Drake', '2.2', '2021-11-10 14:15:00'),
('Drake', '2.2', '2021-11-10 14:30:00'),
('Drake', '2.2', '2021-11-10 14:45:00'),
('Drake', '2.2', '2021-11-10 15:00:00'),
('Drake', '2.2', '2021-11-10 15:15:00'),
('Drake', '2.2', '2021-11-10 15:30:00'),
('Drake', '2.2', '2021-11-10 15:45:00'),
('Drake', '2.2', '2021-11-10 16:00:00'),
('Drake', '2.2', '2021-11-10 16:15:00'),
('Drake', '2.2', '2021-11-10 16:30:00'),
('Drake', '2.2', '2021-11-10 16:45:00'),
('Drake', '2.2', '2021-11-10 17:00:00'),
('Drake', '2.2', '2021-11-10 17:15:00'),
('Drake', '2.2', '2021-11-12 12:30:00'),
('Drake', '2.2', '2021-11-12 12:45:00'),
('Drake', '2.2', '2021-11-12 13:00:00'),
('Drake', '2.2', '2021-11-12 13:15:00'),
('Drake', '2.2', '2021-11-12 13:30:00'),
('Drake', '2.2', '2021-11-12 13:45:00'),
('Drake', '2.2', '2021-11-12 14:00:00'),
('NAV', '3.0', '2021-11-06 19:30:00'),
('NAV', '3.0', '2021-11-06 19:45:00'),
('NAV', '3.0', '2021-11-06 20:00:00'),
('NAV', '3.0', '2021-11-07 14:52:30'),
('NAV', '3.0', '2021-11-07 15:00:00'),
('NAV', '3.0', '2021-11-07 15:15:00'),
('NAV', '3.0', '2021-11-07 15:30:00'),
('NAV', '3.0', '2021-11-07 21:30:00'),
('NAV', '3.0', '2021-11-07 21:45:00'),
('NAV', '3.0', '2021-11-08 11:45:00'),
('NAV', '3.0', '2021-11-08 12:00:00'),
('NAV', '3.0', '2021-11-08 12:15:00'),
('NAV', '3.0', '2021-11-08 12:30:00'),
('NAV', '1.5', '2021-11-10 11:45:00'),
('NAV', '1.5', '2021-11-10 12:00:00'),
('NAV', '1.5', '2021-11-10 12:15:00'),
('NAV', '1.5', '2021-11-10 12:30:00'),
('NAV', '1.5', '2021-11-10 12:45:00'),
('NAV', '1.5', '2021-11-10 13:00:00'),
('NAV', '1.5', '2021-11-10 13:15:00'),
('NAV', '1.5', '2021-11-10 13:30:00'),
('NAV', '1.5', '2021-11-10 13:45:00'),
('NAV', '1.5', '2021-11-10 14:00:00'),
('NAV', '1.5', '2021-11-10 14:15:00'),
('NAV', '1.5', '2021-11-10 14:30:00'),
('NAV', '1.5', '2021-11-10 14:45:00'),
('NAV', '1.5', '2021-11-10 15:00:00'),
('NAV', '1.5', '2021-11-10 15:15:00'),
('NAV', '1.5', '2021-11-10 15:30:00'),
('NAV', '1.5', '2021-11-10 15:45:00'),
('NAV', '1.5', '2021-11-10 16:00:00'),
('NAV', '1.5', '2021-11-10 16:15:00'),
('NAV', '1.5', '2021-11-10 16:30:00'),
('NAV', '1.5', '2021-11-10 16:45:00'),
('NAV', '1.5', '2021-11-10 17:00:00'),
('NAV', '1.5', '2021-11-10 17:15:00'),
('NAV', '1.5', '2021-11-12 12:30:00'),
('NAV', '1.5', '2021-11-12 12:45:00'),
('NAV', '1.5', '2021-11-12 13:00:00'),
('NAV', '1.5', '2021-11-12 13:15:00'),
('NAV', '1.5', '2021-11-12 13:30:00'),
('NAV', '1.5', '2021-11-12 13:45:00'),
('NAV', '1.5', '2021-11-12 14:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `buy_history`
--

CREATE TABLE `buy_history` (
  `id` int(11) NOT NULL,
  `user_username` varchar(20) NOT NULL,
  `seller_username` varchar(20) NOT NULL,
  `artist_username` varchar(20) NOT NULL,
  `no_of_share_bought` int(11) NOT NULL,
  `price_per_share_when_bought` decimal(10,1) NOT NULL,
  `date_purchased` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `buy_history`
--

INSERT INTO `buy_history` (`id`, `user_username`, `seller_username`, `artist_username`, `no_of_share_bought`, `price_per_share_when_bought`, `date_purchased`) VALUES
(16, 'martin', '88Glam', '88Glam', 3923, '100.0', '2022-02-13 13:10:56'),
(17, 'kai', '88Glam', '88Glam', 6077, '100.0', '2022-02-13 13:11:51'),
(18, 'kai', 'martin', '88Glam', 1579, '100.0', '2022-02-13 13:11:51'),
(19, 'kai', 'martin', '88Glam', 1135, '100.0', '2022-02-13 13:17:24'),
(20, 'martin', '88Glam', '88Glam', 1, '120.0', '2022-02-13 14:18:49'),
(21, 'martin', '88Glam', '88Glam', 1546, '80.0', '2022-02-13 14:19:01'),
(22, 'martin', 'Al Lure', 'Al Lure', 9, '10.0', '2022-02-13 15:49:23'),
(23, 'kai', 'Al Lure', 'Al Lure', 6, '10.0', '2022-02-13 16:18:21'),
(24, '88Glam', 'martin', '88Glam', 63, '100.0', '2022-02-14 18:48:15'),
(25, '88Glam', 'martin', '88Glam', 63, '100.0', '2022-02-14 18:48:17'),
(26, '88Glam', 'martin', '88Glam', 63, '100.0', '2022-02-14 18:48:18'),
(27, '88Glam', 'martin', '88Glam', 63, '100.0', '2022-02-14 18:48:18'),
(28, '88Glam', 'martin', '88Glam', 63, '100.0', '2022-02-14 18:48:19'),
(29, '88Glam', 'martin', '88Glam', 5, '100.0', '2022-02-14 18:48:26'),
(30, '88Glam', 'martin', '88Glam', 5, '100.0', '2022-02-14 18:48:27'),
(31, '88Glam', 'martin', '88Glam', 5, '100.0', '2022-02-14 18:48:28'),
(32, '88Glam', 'martin', '88Glam', 5, '100.0', '2022-02-14 18:48:28'),
(33, '88Glam', 'martin', '88Glam', 5, '100.0', '2022-02-14 18:48:29'),
(34, '88Glam', 'martin', '88Glam', 5, '100.0', '2022-02-14 18:48:38'),
(35, '88Glam', 'martin', '88Glam', 5, '100.0', '2022-02-14 18:48:39'),
(36, '88Glam', 'martin', '88Glam', 5, '100.0', '2022-02-14 18:48:39'),
(37, '88Glam', 'martin', '88Glam', 5, '100.0', '2022-02-14 18:48:40'),
(38, '88Glam', 'martin', '88Glam', 5, '100.0', '2022-02-14 18:48:41'),
(39, '88Glam', 'martin', '88Glam', 5, '100.0', '2022-02-14 18:48:41'),
(40, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-14 18:55:32'),
(41, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-14 19:09:13'),
(42, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-14 19:09:18'),
(43, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-14 19:09:18'),
(44, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-14 19:09:19'),
(45, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-14 19:09:23'),
(46, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-14 19:09:24'),
(47, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-14 19:09:24'),
(48, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-14 19:09:24'),
(49, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-14 19:09:25'),
(50, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-14 19:09:26'),
(51, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-14 19:11:06'),
(52, 'martin', '88Glam', '88Glam', 1, '100.0', '2022-02-14 19:23:27'),
(53, 'martin', '88Glam', '88Glam', 1, '100.0', '2022-02-14 19:23:27'),
(54, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-15 16:52:19'),
(55, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-15 16:52:19'),
(56, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-15 16:52:19'),
(57, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-15 16:52:20'),
(58, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-15 16:52:20'),
(59, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-15 16:52:21'),
(60, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-15 16:52:22'),
(61, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-15 16:53:33'),
(62, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-15 16:53:34'),
(63, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-15 16:53:34'),
(64, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-15 16:53:34'),
(65, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-15 16:53:35'),
(66, '88Glam', 'martin', '88Glam', 1, '100.0', '2022-02-15 16:53:36');

-- --------------------------------------------------------

--
-- Table structure for table `buy_order`
--

CREATE TABLE `buy_order` (
  `id` int(11) NOT NULL,
  `user_username` varchar(50) NOT NULL,
  `artist_username` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `siliqas_requested` decimal(10,1) NOT NULL,
  `buy_limit` decimal(10,1) NOT NULL,
  `buy_stop` decimal(10,1) NOT NULL,
  `date_posted` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `campaign`
--

CREATE TABLE `campaign` (
  `id` int(11) NOT NULL,
  `artist_username` varchar(50) NOT NULL,
  `offering` varchar(20) NOT NULL,
  `date_posted` datetime NOT NULL,
  `date_expires` datetime NOT NULL,
  `type` varchar(10) NOT NULL,
  `minimum_ethos` float NOT NULL,
  `eligible_participants` int(11) NOT NULL,
  `winner` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `campaign`
--

INSERT INTO `campaign` (`id`, `artist_username`, `offering`, `date_posted`, `date_expires`, `type`, `minimum_ethos`, `eligible_participants`, `winner`, `is_active`) VALUES
(47, '88Glam', 'merchandise', '2022-02-15 18:47:02', '2022-02-15 18:48:00', 'raffle', 1, 3, 'martin', 0),
(48, '88Glam', 'tickets', '2022-02-15 18:47:12', '2022-02-15 18:49:00', 'raffle', 1, 3, 'kai', 0),
(50, '88Glam', 'merchandise', '2022-02-15 19:04:38', '2022-02-15 19:06:00', 'raffle', 4000, 1, 'kai', 0),
(51, '88Glam', 'merchandise', '2022-02-16 12:38:17', '2022-02-16 12:41:00', 'benchmark', 1, 3, NULL, 0),
(52, '88Glam', 'backstage', '2022-02-16 12:40:44', '2022-03-07 12:44:00', 'benchmark', 1, 3, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `debug_log`
--

CREATE TABLE `debug_log` (
  `id` int(11) NOT NULL,
  `log_type` varchar(50) NOT NULL,
  `message` varchar(10000) NOT NULL,
  `log_file` varchar(20) NOT NULL,
  `log_line` int(11) NOT NULL,
  `date_logged` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `error_log`
--

CREATE TABLE `error_log` (
  `id` int(11) NOT NULL,
  `log_type` varchar(50) NOT NULL,
  `message` varchar(10000) NOT NULL,
  `log_file` varchar(20) NOT NULL,
  `log_line` int(11) NOT NULL,
  `date_logged` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `info_log`
--

CREATE TABLE `info_log` (
  `id` int(11) NOT NULL,
  `log_type` varchar(50) NOT NULL,
  `message` varchar(10000) NOT NULL,
  `log_file` varchar(20) NOT NULL,
  `log_line` int(11) NOT NULL,
  `date_logged` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `inject_history`
--

CREATE TABLE `inject_history` (
  `id` int(11) NOT NULL,
  `artist_username` varchar(50) NOT NULL,
  `amount` int(11) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `date_injected` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `inject_history`
--

INSERT INTO `inject_history` (`id`, `artist_username`, `amount`, `comment`, `date_injected`) VALUES
(74, '88Glam', 10000, 'IPO', '2022-02-13 13:08:07'),
(75, '88Glam', 10000, '', '2022-02-13 14:18:36'),
(76, 'Al Lure', 10, 'IPO', '2022-02-13 15:05:19'),
(77, '88Glam', 1000, '', '2022-02-15 16:41:05'),
(78, '88Glam', 1000, '', '2022-02-15 16:41:25'),
(79, '88Glam', 1000, '', '2022-02-15 16:48:18'),
(80, '88Glam', 1000, '', '2022-02-15 16:48:26'),
(81, '88Glam', 1, 'hello', '2022-02-15 16:48:38'),
(82, '88Glam', 1, '', '2022-02-15 16:48:52'),
(83, '88Glam', 2, '', '2022-02-15 16:49:05'),
(95, 'NAV', 100, 'IPO', '2022-02-15 17:00:21'),
(96, 'NAV', 100, 'IPO', '2022-02-15 17:00:21'),
(97, 'NAV', 100, 'IPO', '2022-02-15 17:00:21'),
(98, 'NAV', 100, 'IPO', '2022-02-15 17:00:22'),
(99, 'NAV', 100, 'IPO', '2022-02-15 17:00:22'),
(100, 'NAV', 100, 'IPO', '2022-02-15 17:00:23'),
(101, 'NAV', 100, 'IPO', '2022-02-15 17:00:24'),
(102, 'NAV', 100, 'IPO', '2022-02-15 17:00:24'),
(103, 'NAV', 100, 'IPO', '2022-02-15 17:00:24'),
(104, 'NAV', 100, 'IPO', '2022-02-15 17:00:25'),
(105, 'NAV', 100, 'IPO', '2022-02-15 17:00:25'),
(106, 'NAV', 100, 'IPO', '2022-02-15 17:00:25'),
(107, 'NAV', 100, 'IPO', '2022-02-15 17:00:25'),
(108, 'NAV', 100, 'IPO', '2022-02-15 17:00:26'),
(109, 'NAV', 100, 'IPO', '2022-02-15 17:00:26'),
(110, 'NAV', 100, 'IPO', '2022-02-15 17:00:27'),
(111, 'NAV', 100, 'IPO', '2022-02-15 17:00:27'),
(112, 'NAV', 100, 'IPO', '2022-02-15 17:04:45'),
(113, 'NAV', 10, 'IPO', '2022-02-15 17:05:04'),
(114, 'NAV', 1000, 'IPO', '2022-02-15 17:05:42'),
(115, 'NAV', 1000, 'IPO', '2022-02-15 17:05:43'),
(116, 'NAV', 1000, 'IPO', '2022-02-15 17:05:43'),
(117, 'NAV', 1000, 'IPO', '2022-02-15 17:05:44'),
(118, 'NAV', 1000, 'IPO', '2022-02-15 17:05:44'),
(119, 'NAV', 1000, 'IPO', '2022-02-15 17:05:44'),
(120, 'NAV', 1000, 'IPO', '2022-02-15 17:05:45'),
(121, 'NAV', 1000, 'IPO', '2022-02-15 17:05:45'),
(122, 'NAV', 1000, 'IPO', '2022-02-15 17:05:46'),
(123, 'NAV', 100, 'IPO', '2022-02-15 17:07:20'),
(124, 'NAV', 100, 'IPO', '2022-02-15 17:07:20'),
(125, 'NAV', 100, 'IPO', '2022-02-15 17:07:21'),
(126, 'NAV', 100, 'IPO', '2022-02-15 17:07:21'),
(127, 'NAV', 100, 'IPO', '2022-02-15 17:07:22'),
(128, 'NAV', 100, 'IPO', '2022-02-15 17:07:22'),
(129, 'NAV', 100, 'IPO', '2022-02-15 17:07:22'),
(130, 'NAV', 100, 'IPO', '2022-02-15 17:07:23'),
(131, 'NAV', 100, 'IPO', '2022-02-15 17:07:23'),
(132, 'NAV', 100, 'IPO', '2022-02-15 17:07:24'),
(133, 'NAV', 100, 'IPO', '2022-02-15 17:07:24'),
(134, 'NAV', 100, 'IPO', '2022-02-15 17:07:25'),
(135, 'NAV', 1, 'IPO', '2022-02-15 17:07:43'),
(136, 'NAV', 1, 'IPO', '2022-02-15 17:09:04'),
(137, 'NAV', 1, 'IPO', '2022-02-15 17:12:13'),
(138, '88Glam', 1, '', '2022-02-15 17:12:48'),
(139, '88Glam', 1, '', '2022-02-15 17:13:00'),
(140, '88Glam', 1, '', '2022-02-15 17:13:15'),
(141, '88Glam', 1, '', '2022-02-15 17:13:16'),
(142, '88Glam', 1, '', '2022-02-15 17:13:16'),
(143, '88Glam', 1, '', '2022-02-15 17:13:16'),
(144, '88Glam', 1, '', '2022-02-15 17:13:16'),
(145, '88Glam', 1, '', '2022-02-15 17:13:17'),
(146, '88Glam', 1, '', '2022-02-15 17:13:17'),
(147, '88Glam', 1, '', '2022-02-15 17:13:17'),
(148, '88Glam', 1, '', '2022-02-15 17:13:17'),
(149, '88Glam', 1, '', '2022-02-15 17:14:29'),
(150, '88Glam', 1, '', '2022-02-15 17:16:19');

-- --------------------------------------------------------

--
-- Table structure for table `sell_history`
--

CREATE TABLE `sell_history` (
  `id` int(11) NOT NULL,
  `seller_username` varchar(20) NOT NULL,
  `buyer_username` varchar(20) NOT NULL,
  `artist_username` varchar(20) NOT NULL,
  `amount_sold` int(11) NOT NULL,
  `price_sold` decimal(10,1) NOT NULL,
  `date_sold` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sell_order`
--

CREATE TABLE `sell_order` (
  `id` int(11) NOT NULL,
  `user_username` varchar(50) NOT NULL,
  `artist_username` varchar(50) NOT NULL,
  `selling_price` decimal(10,1) NOT NULL,
  `no_of_share` int(11) NOT NULL,
  `sell_limit` decimal(10,1) NOT NULL,
  `sell_stop` decimal(10,1) NOT NULL,
  `is_from_injection` tinyint(1) NOT NULL,
  `date_posted` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sell_order`
--

INSERT INTO `sell_order` (`id`, `user_username`, `artist_username`, `selling_price`, `no_of_share`, `sell_limit`, `sell_stop`, `is_from_injection`, `date_posted`) VALUES
(187, '88Glam', '88Glam', '100.0', 8451, '-1.0', '-1.0', 1, '2022-02-13 14:18:36'),
(199, 'martin', '88Glam', '100.0', 689, '-1.0', '-1.0', 0, '2022-02-14 17:00:21'),
(200, 'martin', '88Glam', '100.0', 195, '-1.0', '-1.0', 0, '2022-02-14 17:00:44'),
(214, '88Glam', '88Glam', '100.0', 1000, '-1.0', '-1.0', 1, '2022-02-15 16:48:18'),
(215, '88Glam', '88Glam', '100.0', 1000, '-1.0', '-1.0', 1, '2022-02-15 16:48:26'),
(217, '88Glam', '88Glam', '100.0', 1, '-1.0', '-1.0', 1, '2022-02-15 16:48:52'),
(218, '88Glam', '88Glam', '100.0', 2, '-1.0', '-1.0', 1, '2022-02-15 16:49:05'),
(272, 'NAV', 'NAV', '10.0', 1, '-1.0', '-1.0', 1, '2022-02-15 17:12:13'),
(274, '88Glam', '88Glam', '100.0', 1, '-1.0', '-1.0', 1, '2022-02-15 17:13:00'),
(285, '88Glam', '88Glam', '100.0', 1, '-1.0', '-1.0', 1, '2022-02-15 17:16:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `artist_account_data`
--
ALTER TABLE `artist_account_data`
  ADD PRIMARY KEY (`artist_username`,`ticker`);

--
-- Indexes for table `artist_followers`
--
ALTER TABLE `artist_followers`
  ADD PRIMARY KEY (`artist_username`,`user_username`),
  ADD KEY `follow_user_key` (`user_username`);

--
-- Indexes for table `artist_shareholders`
--
ALTER TABLE `artist_shareholders`
  ADD PRIMARY KEY (`user_username`,`artist_username`),
  ADD KEY `shareholder_artist_key` (`artist_username`);

--
-- Indexes for table `artist_stock_change`
--
ALTER TABLE `artist_stock_change`
  ADD PRIMARY KEY (`artist_username`,`date_recorded`);

--
-- Indexes for table `buy_history`
--
ALTER TABLE `buy_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buy_order`
--
ALTER TABLE `buy_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buy_order_user_key` (`user_username`),
  ADD KEY `buy_order_artist_key` (`artist_username`);

--
-- Indexes for table `campaign`
--
ALTER TABLE `campaign`
  ADD PRIMARY KEY (`id`),
  ADD KEY `artist_campaign_key` (`artist_username`),
  ADD KEY `winner_key` (`winner`);

--
-- Indexes for table `debug_log`
--
ALTER TABLE `debug_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `error_log`
--
ALTER TABLE `error_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `info_log`
--
ALTER TABLE `info_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inject_history`
--
ALTER TABLE `inject_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `artist_injection_key` (`artist_username`);

--
-- Indexes for table `sell_history`
--
ALTER TABLE `sell_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sell_history_seller` (`seller_username`),
  ADD KEY `sell_history_buyer` (`buyer_username`),
  ADD KEY `sell_history_artist` (`artist_username`);

--
-- Indexes for table `sell_order`
--
ALTER TABLE `sell_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sell_order_user` (`user_username`),
  ADD KEY `sell_order_artist` (`artist_username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `buy_history`
--
ALTER TABLE `buy_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `buy_order`
--
ALTER TABLE `buy_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `campaign`
--
ALTER TABLE `campaign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `debug_log`
--
ALTER TABLE `debug_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `error_log`
--
ALTER TABLE `error_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `info_log`
--
ALTER TABLE `info_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inject_history`
--
ALTER TABLE `inject_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `sell_history`
--
ALTER TABLE `sell_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sell_order`
--
ALTER TABLE `sell_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=286;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `artist_account_data`
--
ALTER TABLE `artist_account_data`
  ADD CONSTRAINT `artist_ticker_key` FOREIGN KEY (`artist_username`) REFERENCES `account` (`username`);

--
-- Constraints for table `artist_followers`
--
ALTER TABLE `artist_followers`
  ADD CONSTRAINT `follow_artist_key` FOREIGN KEY (`artist_username`) REFERENCES `account` (`username`),
  ADD CONSTRAINT `follow_user_key` FOREIGN KEY (`user_username`) REFERENCES `account` (`username`);

--
-- Constraints for table `artist_shareholders`
--
ALTER TABLE `artist_shareholders`
  ADD CONSTRAINT `shareholder_artist_key` FOREIGN KEY (`artist_username`) REFERENCES `account` (`username`),
  ADD CONSTRAINT `shareholder_username_key` FOREIGN KEY (`user_username`) REFERENCES `account` (`username`);

--
-- Constraints for table `artist_stock_change`
--
ALTER TABLE `artist_stock_change`
  ADD CONSTRAINT `artist_stock_key` FOREIGN KEY (`artist_username`) REFERENCES `account` (`username`);

--
-- Constraints for table `buy_order`
--
ALTER TABLE `buy_order`
  ADD CONSTRAINT `buy_order_artist_key` FOREIGN KEY (`artist_username`) REFERENCES `account` (`username`),
  ADD CONSTRAINT `buy_order_user_key` FOREIGN KEY (`user_username`) REFERENCES `account` (`username`);

--
-- Constraints for table `campaign`
--
ALTER TABLE `campaign`
  ADD CONSTRAINT `artist_campaign_key` FOREIGN KEY (`artist_username`) REFERENCES `account` (`username`),
  ADD CONSTRAINT `winner_key` FOREIGN KEY (`winner`) REFERENCES `account` (`username`);

--
-- Constraints for table `inject_history`
--
ALTER TABLE `inject_history`
  ADD CONSTRAINT `artist_injection_key` FOREIGN KEY (`artist_username`) REFERENCES `account` (`username`);

--
-- Constraints for table `sell_history`
--
ALTER TABLE `sell_history`
  ADD CONSTRAINT `sell_history_artist` FOREIGN KEY (`artist_username`) REFERENCES `account` (`username`),
  ADD CONSTRAINT `sell_history_buyer` FOREIGN KEY (`buyer_username`) REFERENCES `account` (`username`),
  ADD CONSTRAINT `sell_history_seller` FOREIGN KEY (`seller_username`) REFERENCES `account` (`username`);

--
-- Constraints for table `sell_order`
--
ALTER TABLE `sell_order`
  ADD CONSTRAINT `sell_order_artist` FOREIGN KEY (`artist_username`) REFERENCES `account` (`username`),
  ADD CONSTRAINT `sell_order_user` FOREIGN KEY (`user_username`) REFERENCES `account` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
