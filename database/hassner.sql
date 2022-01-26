-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 26, 2022 at 03:57 AM
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
  `balance` float NOT NULL,
  `rate` float NOT NULL,
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
  `price_per_share` float NOT NULL,
  `Monthly_shareholder` int(11) NOT NULL,
  `Income` float NOT NULL,
  `Market_cap` float NOT NULL,
  `shares_repurchase` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`username`, `password`, `account_type`, `id`, `Shares`, `balance`, `rate`, `Share_Distributed`, `email`, `billing_address`, `Full_name`, `City`, `State`, `ZIP`, `Card_number`, `Transit_no`, `Inst_no`, `Account_no`, `Swift`, `price_per_share`, `Monthly_shareholder`, `Income`, `Market_cap`, `shares_repurchase`) VALUES
('21 Savage', 'artist', 'artist', 6, 0, 0, 0, 0, '21savage@gmail.com', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0),
('88Glam', 'artist', 'artist', 2, 1000000, 1000000, 0, 1000000, '12@gmail.com', '1234', '88 Camino', 'Toronto', 'Ontario', '123456', '1111-2222-3333-4444', '12345', '123', '12345678', 'AAAABBCC', 1, 0, 0, 446909, 0),
('daniel', 'user', 'user', 8, 8330, 91670, 0, 0, 'iosrghn@gmail.com', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0),
('Drake', 'artist', 'artist', 11, 0, 0, 0, 0, 'qwerty@gmail.com', 'Drake', '', '', '', '', '', '', '', '', '', 10, 0, 0, 0, 0),
('kai', 'user', 'user', 4, 0, 100000, 0, 0, '123@gmail.com', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0),
('martin', 'user', 'user', 1, 786651, 9213360, 0, 0, 'martinvuha1909@gmail.com', '2240', 'Vu Ha (Martin)', 'Calgary', 'AB', 'T2N', '1111-2222-3333-4444', '12345', '123', '12345678', 'AAAABBCC', 0, 0, 0, 0, 0),
('NAV', 'artist', 'artist', 3, 0, 0, 0, 0, '4321@gmail.com', '', '', '', '', '', '', '', '', '', '', 2, 0, 0, 36922, 0),
('riley', 'user', 'user', 7, 70909, 29091, 0, 0, 'efin@gmail.com', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0),
('testuser', 'user', 'user', 30, 0, 0, 0, 0, 'testuser@gmail.com', 'testuser', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0),
('vitor', 'user', 'user', 5, 134110, 65990, 0, 0, '1234@gmail.com', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0);

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
('daniel', '88Glam', 8330),
('kai', '88Glam', 0),
('martin', '88Glam', 786651),
('riley', '88Glam', 70909),
('vitor', '88Glam', 134110);

-- --------------------------------------------------------

--
-- Table structure for table `artist_stock_change`
--

CREATE TABLE `artist_stock_change` (
  `artist_username` varchar(20) NOT NULL,
  `price_per_share` float NOT NULL,
  `date_recorded` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `artist_stock_change`
--

INSERT INTO `artist_stock_change` (`artist_username`, `price_per_share`, `date_recorded`) VALUES
('21 Savage', 1.33333, '2021-11-06 19:30:00'),
('21 Savage', 1.33333, '2021-11-06 19:45:00'),
('21 Savage', 1.33333, '2021-11-06 20:00:00'),
('21 Savage', 1.33333, '2021-11-07 14:52:30'),
('21 Savage', 1.33333, '2021-11-07 15:00:00'),
('21 Savage', 1.33333, '2021-11-07 15:15:00'),
('21 Savage', 1.33333, '2021-11-07 15:30:00'),
('21 Savage', 1.33333, '2021-11-07 21:30:00'),
('21 Savage', 1.33333, '2021-11-07 21:45:00'),
('21 Savage', 1.33333, '2021-11-08 11:45:00'),
('21 Savage', 1.33333, '2021-11-08 12:00:00'),
('21 Savage', 1.33333, '2021-11-08 12:15:00'),
('21 Savage', 1.33333, '2021-11-08 12:30:00'),
('21 Savage', 2, '2021-11-10 11:45:00'),
('21 Savage', 2, '2021-11-10 12:00:00'),
('21 Savage', 2, '2021-11-10 12:15:00'),
('21 Savage', 2, '2021-11-10 12:30:00'),
('21 Savage', 2, '2021-11-10 12:45:00'),
('21 Savage', 2, '2021-11-10 13:00:00'),
('21 Savage', 2, '2021-11-10 13:15:00'),
('21 Savage', 2, '2021-11-10 13:30:00'),
('21 Savage', 2, '2021-11-10 13:45:00'),
('21 Savage', 2, '2021-11-10 14:00:00'),
('21 Savage', 2, '2021-11-10 14:15:00'),
('21 Savage', 2, '2021-11-10 14:30:00'),
('21 Savage', 2, '2021-11-10 14:45:00'),
('21 Savage', 2, '2021-11-10 15:00:00'),
('21 Savage', 2, '2021-11-10 15:15:00'),
('21 Savage', 2, '2021-11-10 15:30:00'),
('21 Savage', 2, '2021-11-10 15:45:00'),
('21 Savage', 2, '2021-11-10 16:00:00'),
('21 Savage', 2, '2021-11-10 16:15:00'),
('21 Savage', 2, '2021-11-10 16:30:00'),
('21 Savage', 2, '2021-11-10 16:45:00'),
('21 Savage', 2, '2021-11-10 17:00:00'),
('21 Savage', 2, '2021-11-10 17:15:00'),
('21 Savage', 2, '2021-11-12 12:30:00'),
('21 Savage', 2, '2021-11-12 12:45:00'),
('21 Savage', 2, '2021-11-12 13:00:00'),
('21 Savage', 2, '2021-11-12 13:15:00'),
('21 Savage', 2, '2021-11-12 13:30:00'),
('21 Savage', 2, '2021-11-12 13:45:00'),
('21 Savage', 2, '2021-11-12 14:00:00'),
('88Glam', 2.5, '2021-10-26 19:30:00'),
('88Glam', 2.2, '2021-10-27 19:30:00'),
('88Glam', 3.2, '2021-10-28 19:30:00'),
('88Glam', 2.6, '2021-10-29 19:30:00'),
('88Glam', 2.9, '2021-10-30 19:30:00'),
('88Glam', 2.9, '2021-10-31 19:30:00'),
('88Glam', 4.9, '2021-11-01 19:30:00'),
('88Glam', 4.5, '2021-11-02 19:30:00'),
('88Glam', 2, '2021-11-03 19:30:00'),
('88Glam', 2, '2021-11-04 19:30:00'),
('88Glam', 4, '2021-11-05 19:30:00'),
('88Glam', 3, '2021-11-05 19:45:00'),
('88Glam', 5, '2021-11-05 20:00:00'),
('88Glam', 6, '2021-11-06 19:30:00'),
('88Glam', 6, '2021-11-06 19:45:00'),
('88Glam', 6, '2021-11-06 20:00:00'),
('88Glam', 6, '2021-11-07 14:52:30'),
('88Glam', 6, '2021-11-07 15:00:00'),
('88Glam', 3.6, '2021-11-07 15:15:00'),
('88Glam', 3.6, '2021-11-07 15:30:00'),
('88Glam', 6, '2021-11-07 21:30:00'),
('88Glam', 6, '2021-11-07 21:45:00'),
('88Glam', 3.6, '2021-11-08 11:45:00'),
('88Glam', 3.6, '2021-11-08 12:00:00'),
('88Glam', 3.6, '2021-11-08 12:15:00'),
('88Glam', 3.6, '2021-11-08 12:30:00'),
('88Glam', 4.7, '2021-11-10 11:45:00'),
('88Glam', 4.7, '2021-11-10 12:00:00'),
('88Glam', 4.7, '2021-11-10 12:15:00'),
('88Glam', 4.7, '2021-11-10 12:30:00'),
('88Glam', 4.7, '2021-11-10 12:45:00'),
('88Glam', 4.7, '2021-11-10 13:00:00'),
('88Glam', 4.7, '2021-11-10 13:15:00'),
('88Glam', 4.7, '2021-11-10 13:30:00'),
('88Glam', 4.7, '2021-11-10 13:45:00'),
('88Glam', 4.7, '2021-11-10 14:00:00'),
('88Glam', 4.7, '2021-11-10 14:15:00'),
('88Glam', 4.7, '2021-11-10 14:30:00'),
('88Glam', 4.7, '2021-11-10 14:45:00'),
('88Glam', 4.7, '2021-11-10 15:00:00'),
('88Glam', 4.7, '2021-11-10 15:15:00'),
('88Glam', 4.7, '2021-11-10 15:30:00'),
('88Glam', 4.7, '2021-11-10 15:45:00'),
('88Glam', 4.7, '2021-11-10 16:00:00'),
('88Glam', 4.7, '2021-11-10 16:15:00'),
('88Glam', 4.7, '2021-11-10 16:30:00'),
('88Glam', 4.7, '2021-11-10 16:45:00'),
('88Glam', 4.7, '2021-11-10 17:00:00'),
('88Glam', 4.7, '2021-11-10 17:15:00'),
('88Glam', 3.3, '2021-11-12 12:30:00'),
('88Glam', 3.3, '2021-11-12 12:45:00'),
('88Glam', 3.3, '2021-11-12 13:00:00'),
('88Glam', 3.3, '2021-11-12 13:15:00'),
('88Glam', 3.3, '2021-11-12 13:30:00'),
('88Glam', 3.3, '2021-11-12 13:45:00'),
('88Glam', 3.3, '2021-11-12 14:00:00'),
('Drake', 1, '2021-11-06 19:30:00'),
('Drake', 1, '2021-11-06 19:45:00'),
('Drake', 1, '2021-11-06 20:00:00'),
('Drake', 1, '2021-11-07 14:52:30'),
('Drake', 1, '2021-11-07 15:00:00'),
('Drake', 1, '2021-11-07 15:15:00'),
('Drake', 1, '2021-11-07 15:30:00'),
('Drake', 1, '2021-11-07 21:30:00'),
('Drake', 1, '2021-11-07 21:45:00'),
('Drake', 1, '2021-11-08 11:45:00'),
('Drake', 1, '2021-11-08 12:00:00'),
('Drake', 1, '2021-11-08 12:15:00'),
('Drake', 1, '2021-11-08 12:30:00'),
('Drake', 2.2, '2021-11-10 11:45:00'),
('Drake', 2.2, '2021-11-10 12:00:00'),
('Drake', 2.2, '2021-11-10 12:15:00'),
('Drake', 2.2, '2021-11-10 12:30:00'),
('Drake', 2.2, '2021-11-10 12:45:00'),
('Drake', 2.2, '2021-11-10 13:00:00'),
('Drake', 2.2, '2021-11-10 13:15:00'),
('Drake', 2.2, '2021-11-10 13:30:00'),
('Drake', 2.2, '2021-11-10 13:45:00'),
('Drake', 2.2, '2021-11-10 14:00:00'),
('Drake', 2.2, '2021-11-10 14:15:00'),
('Drake', 2.2, '2021-11-10 14:30:00'),
('Drake', 2.2, '2021-11-10 14:45:00'),
('Drake', 2.2, '2021-11-10 15:00:00'),
('Drake', 2.2, '2021-11-10 15:15:00'),
('Drake', 2.2, '2021-11-10 15:30:00'),
('Drake', 2.2, '2021-11-10 15:45:00'),
('Drake', 2.2, '2021-11-10 16:00:00'),
('Drake', 2.2, '2021-11-10 16:15:00'),
('Drake', 2.2, '2021-11-10 16:30:00'),
('Drake', 2.2, '2021-11-10 16:45:00'),
('Drake', 2.2, '2021-11-10 17:00:00'),
('Drake', 2.2, '2021-11-10 17:15:00'),
('Drake', 2.2, '2021-11-12 12:30:00'),
('Drake', 2.2, '2021-11-12 12:45:00'),
('Drake', 2.2, '2021-11-12 13:00:00'),
('Drake', 2.2, '2021-11-12 13:15:00'),
('Drake', 2.2, '2021-11-12 13:30:00'),
('Drake', 2.2, '2021-11-12 13:45:00'),
('Drake', 2.2, '2021-11-12 14:00:00'),
('NAV', 3, '2021-11-06 19:30:00'),
('NAV', 3, '2021-11-06 19:45:00'),
('NAV', 3, '2021-11-06 20:00:00'),
('NAV', 3, '2021-11-07 14:52:30'),
('NAV', 3, '2021-11-07 15:00:00'),
('NAV', 3, '2021-11-07 15:15:00'),
('NAV', 3, '2021-11-07 15:30:00'),
('NAV', 3, '2021-11-07 21:30:00'),
('NAV', 3, '2021-11-07 21:45:00'),
('NAV', 3, '2021-11-08 11:45:00'),
('NAV', 3, '2021-11-08 12:00:00'),
('NAV', 3, '2021-11-08 12:15:00'),
('NAV', 3, '2021-11-08 12:30:00'),
('NAV', 1.5, '2021-11-10 11:45:00'),
('NAV', 1.5, '2021-11-10 12:00:00'),
('NAV', 1.5, '2021-11-10 12:15:00'),
('NAV', 1.5, '2021-11-10 12:30:00'),
('NAV', 1.5, '2021-11-10 12:45:00'),
('NAV', 1.5, '2021-11-10 13:00:00'),
('NAV', 1.5, '2021-11-10 13:15:00'),
('NAV', 1.5, '2021-11-10 13:30:00'),
('NAV', 1.5, '2021-11-10 13:45:00'),
('NAV', 1.5, '2021-11-10 14:00:00'),
('NAV', 1.5, '2021-11-10 14:15:00'),
('NAV', 1.5, '2021-11-10 14:30:00'),
('NAV', 1.5, '2021-11-10 14:45:00'),
('NAV', 1.5, '2021-11-10 15:00:00'),
('NAV', 1.5, '2021-11-10 15:15:00'),
('NAV', 1.5, '2021-11-10 15:30:00'),
('NAV', 1.5, '2021-11-10 15:45:00'),
('NAV', 1.5, '2021-11-10 16:00:00'),
('NAV', 1.5, '2021-11-10 16:15:00'),
('NAV', 1.5, '2021-11-10 16:30:00'),
('NAV', 1.5, '2021-11-10 16:45:00'),
('NAV', 1.5, '2021-11-10 17:00:00'),
('NAV', 1.5, '2021-11-10 17:15:00'),
('NAV', 1.5, '2021-11-12 12:30:00'),
('NAV', 1.5, '2021-11-12 12:45:00'),
('NAV', 1.5, '2021-11-12 13:00:00'),
('NAV', 1.5, '2021-11-12 13:15:00'),
('NAV', 1.5, '2021-11-12 13:30:00'),
('NAV', 1.5, '2021-11-12 13:45:00'),
('NAV', 1.5, '2021-11-12 14:00:00');

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
  `price_per_share_when_bought` float NOT NULL,
  `date_purchased` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `buy_history`
--

INSERT INTO `buy_history` (`id`, `user_username`, `seller_username`, `artist_username`, `no_of_share_bought`, `price_per_share_when_bought`, `date_purchased`) VALUES
(82, 'riley', '88Glam', '88Glam', 91148, 1, '2022-01-25 18:07:00'),
(83, 'vitor', '88Glam', '88Glam', 200000, 1, '2022-01-25 18:08:44'),
(84, 'kai', '88Glam', '88Glam', 85380, 1, '2022-01-25 18:09:59'),
(85, 'daniel', '88Glam', '88Glam', 70381, 1, '2022-01-25 18:11:06'),
(86, 'martin', '88Glam', '88Glam', 53091, 1, '2022-01-25 19:48:25'),
(87, 'martin', 'riley', '88Glam', 1000, 1, '2022-01-25 19:48:25'),
(88, 'martin', 'riley', '88Glam', 3034, 1, '2022-01-25 19:48:25'),
(89, 'martin', 'riley', '88Glam', 16205, 1, '2022-01-25 19:48:25'),
(90, 'martin', 'vitor', '88Glam', 65890, 1, '2022-01-25 19:48:25'),
(91, 'martin', 'kai', '88Glam', 65733, 1, '2022-01-25 19:48:25'),
(92, 'martin', 'kai', '88Glam', 19647, 1, '2022-01-25 19:48:25'),
(93, 'martin', 'daniel', '88Glam', 62051, 1, '2022-01-25 19:48:25'),
(94, 'martin', '88Glam', '88Glam', 500000, 1, '2022-01-25 19:48:25');

-- --------------------------------------------------------

--
-- Table structure for table `buy_order`
--

CREATE TABLE `buy_order` (
  `id` int(11) NOT NULL,
  `user_username` varchar(50) NOT NULL,
  `artist_username` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `siliqas_requested` float NOT NULL,
  `buy_limit` float NOT NULL,
  `buy_stop` float NOT NULL,
  `date_posted` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `buy_order`
--

INSERT INTO `buy_order` (`id`, `user_username`, `artist_username`, `quantity`, `siliqas_requested`, `buy_limit`, `buy_stop`, `date_posted`) VALUES
(27, 'martin', '88Glam', 213349, 1, -1, -1, '2022-01-25 19:48:25');

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
  `winner` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `campaign`
--

INSERT INTO `campaign` (`id`, `artist_username`, `offering`, `date_posted`, `date_expires`, `type`, `minimum_ethos`, `eligible_participants`, `winner`) VALUES
(4, '88Glam', 'tickets', '2022-01-19 17:08:03', '2022-01-29 17:18:00', 'raffle', 10, 4, NULL),
(5, '88Glam', 'merchandise', '2022-01-19 17:08:23', '2022-01-29 17:14:00', 'benchmark', 10, 4, NULL),
(6, '88Glam', 'merchandise', '2022-01-19 17:58:15', '2022-01-31 17:04:00', 'raffle', 23, 4, NULL),
(7, '88Glam', 'tickets', '2022-01-19 18:09:13', '0000-00-00 00:00:00', 'raffle', 1, 4, 'vitor'),
(8, '88Glam', 'instrument', '2022-01-20 15:46:03', '2022-01-31 15:51:00', 'benchmark', 16, 4, NULL),
(9, 'NAV', 'backstage', '2022-01-20 16:04:23', '2022-03-02 16:09:00', 'raffle', 27000, 0, NULL),
(10, 'NAV', 'merchandise', '2022-01-20 16:04:36', '2022-01-31 16:04:00', 'benchmark', 22000, 0, NULL),
(11, 'NAV', 'merchandise', '2022-01-20 16:04:50', '2022-01-29 16:09:00', 'benchmark', 30000, 0, NULL),
(12, 'NAV', 'backstage', '2022-01-20 16:05:44', '2022-02-03 16:10:00', 'benchmark', 17000, 1, NULL),
(13, 'Drake', 'merchandise', '2022-01-21 15:39:02', '2022-04-07 15:42:00', 'raffle', 50, 0, NULL);

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
(47, '88Glam', 500000, 'IPO', '2022-01-25 17:34:21'),
(48, '88Glam', 500000, '', '2022-01-25 18:11:27');

-- --------------------------------------------------------

--
-- Table structure for table `sell_order`
--

CREATE TABLE `sell_order` (
  `id` int(11) NOT NULL,
  `user_username` varchar(50) NOT NULL,
  `artist_username` varchar(50) NOT NULL,
  `selling_price` float NOT NULL,
  `no_of_share` int(11) NOT NULL,
  `sell_limit` float NOT NULL,
  `sell_stop` float NOT NULL,
  `is_from_injection` tinyint(1) NOT NULL,
  `date_posted` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sell_order`
--

INSERT INTO `sell_order` (`id`, `user_username`, `artist_username`, `selling_price`, `no_of_share`, `sell_limit`, `sell_stop`, `is_from_injection`, `date_posted`) VALUES
(67, 'riley', '88Glam', -1, 32824, -1, 0.75, 0, '2022-01-25 18:07:06'),
(68, 'riley', '88Glam', -1, 32897, 1.7, -1, 0, '2022-01-25 18:07:13'),
(72, 'vitor', '88Glam', -1, 134110, -1, 0.75, 0, '2022-01-25 18:09:03');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `buy_history`
--
ALTER TABLE `buy_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `buy_order`
--
ALTER TABLE `buy_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `campaign`
--
ALTER TABLE `campaign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `sell_order`
--
ALTER TABLE `sell_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

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
-- Constraints for table `sell_order`
--
ALTER TABLE `sell_order`
  ADD CONSTRAINT `sell_order_artist` FOREIGN KEY (`artist_username`) REFERENCES `account` (`username`),
  ADD CONSTRAINT `sell_order_user` FOREIGN KEY (`user_username`) REFERENCES `account` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
