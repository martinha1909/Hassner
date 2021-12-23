-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2021 at 09:01 PM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `log_artist_pps` ()  BEGIN
  DECLARE done INT DEFAULT FALSE;
  DECLARE x VARCHAR(20);
  DECLARE cur1 CURSOR FOR SELECT username FROM account WHERE account_type = 'artist';
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

  OPEN cur1;

  read_loop: LOOP
    FETCH cur1 INTO x;
    IF done THEN
      LEAVE read_loop;
    END IF;
      INSERT INTO artist_stock_change(artist_username, price_per_share, date_recorded) 
      VALUES (x, (SELECT price_per_share FROM account WHERE username = x), NOW());
  END LOOP;

  CLOSE cur1;
END$$

DELIMITER ;

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
('88Glam', 'artist', 'artist', 2, 1000, 10570, 0, 3268, '12@gmail.com', '1234', '88 Camino', 'Toronto', 'Ontario', '123456', '1111-2222-3333-4444', '12345', '123', '12345678', 'AAAABBCC', 10, 0, 0, 10570, 21),
('daniel', 'user', 'user', 8, 0, 100000, 0, 0, 'iosrghn@gmail.com', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0),
('Drake', 'artist', 'artist', 11, 0, 0, 0, 0, 'qwerty@gmail.com', 'Drake', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0),
('kai', 'user', 'user', 4, 304, 96960, 0, 0, '123@gmail.com', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0),
('martin', 'user', 'user', 1, 753, 2470, 0, 0, 'martinvuha1909@gmail.com', '2240', 'Vu Ha (Martin)', 'Calgary', 'AB', 'T2N', '1111-2222-3333-4444', '12345', '123', '12345678', 'AAAABBCC', 0, 0, 0, 0, 0),
('NAV', 'artist', 'artist', 3, 0, 0, 0, 20000, '4321@gmail.com', '', '', '', '', '', '', '', '', '', '', 10, 0, 0, 0, 0),
('riley', 'user', 'user', 7, 0, 100000, 0, 0, 'efin@gmail.com', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0),
('vitor', 'user', 'user', 5, 0, 100000, 0, 0, '1234@gmail.com', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0);

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
('kai', '88Glam', 304),
('martin', '88Glam', 753);

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
(5, 'martin', '88Glam', '88Glam', 1, 10, '2021-11-29 18:11:57'),
(6, 'martin', '88Glam', '88Glam', 1, 10, '2021-11-29 18:12:25'),
(7, 'martin', '88Glam', '88Glam', 1, 10, '2021-11-29 18:14:27'),
(8, 'martin', '88Glam', '88Glam', 1, 10, '2021-11-29 18:16:36'),
(9, 'martin', '88Glam', '88Glam', 1, 10, '2021-11-29 18:18:08'),
(10, 'martin', '88Glam', '88Glam', 1, 10, '2021-11-29 18:18:14'),
(11, 'martin', '88Glam', '88Glam', 1, 10, '2021-11-29 18:18:31'),
(12, 'martin', '88Glam', '88Glam', 1, 10, '2021-11-29 18:18:58'),
(13, 'martin', '88Glam', '88Glam', 1, 10, '2021-11-29 18:19:12'),
(14, 'martin', '88Glam', '88Glam', 1, 10, '2021-11-29 18:19:16'),
(15, 'martin', '88Glam', '88Glam', 1, 10, '2021-11-29 18:19:32'),
(16, 'martin', '88Glam', '88Glam', 1, 10, '2021-11-29 18:20:01'),
(17, 'martin', '88Glam', '88Glam', 1, 10, '2021-11-29 18:23:23'),
(18, 'martin', '88Glam', '88Glam', 1, 10, '2021-11-29 18:25:18'),
(19, 'martin', '88Glam', '88Glam', 1, 10, '2021-11-29 18:25:51'),
(20, 'martin', '88Glam', '88Glam', 1, 10, '2021-11-29 18:26:09'),
(21, 'martin', '88Glam', '88Glam', 6, 10, '2021-11-29 18:27:16'),
(22, 'martin', '88Glam', '88Glam', 1, 10, '2021-11-29 18:27:16'),
(23, 'martin', '88Glam', '88Glam', 9, 10, '2021-11-29 18:27:37'),
(24, 'kai', 'martin', '88Glam', 1, 10, '2021-11-30 19:04:31'),
(25, 'kai', 'martin', '88Glam', 1, 10, '2021-11-30 19:04:31'),
(26, 'kai', 'martin', '88Glam', 22, 10, '2021-11-30 19:04:31'),
(29, 'martin', '88Glam', '88Glam', 47, 10, '2021-11-30 19:36:07'),
(30, 'martin', '88Glam', '88Glam', 10, 10, '2021-11-30 19:36:07'),
(31, 'kai', '88Glam', '88Glam', 91, 10, '2021-11-30 19:36:07'),
(32, 'martin', '88Glam', '88Glam', 300, 10, '2021-11-30 19:40:00'),
(33, 'martin', '88Glam', '88Glam', 300, 10, '2021-11-30 19:40:03'),
(34, 'martin', '88Glam', '88Glam', 252, 10, '2021-11-30 19:40:04'),
(35, 'kai', 'martin', '88Glam', 551, 10, '2021-11-30 19:41:37'),
(36, 'martin', 'kai', '88Glam', 48, 10, '2021-11-30 19:42:44'),
(37, 'martin', 'kai', '88Glam', 200, 10, '2021-11-30 19:42:44'),
(38, 'martin', 'kai', '88Glam', 100, 10, '2021-11-30 19:43:04'),
(39, 'martin', 'kai', '88Glam', 14, 10, '2021-11-30 19:43:04'),
(40, 'kai', 'martin', '88Glam', 1, 10, '2021-11-30 20:34:07'),
(41, 'kai', 'martin', '88Glam', 1, 10, '2021-11-30 20:34:23');

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
  `date_posted` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `buy_order`
--

INSERT INTO `buy_order` (`id`, `user_username`, `artist_username`, `quantity`, `siliqas_requested`, `date_posted`) VALUES
(15, 'martin', '88Glam', 286, 10, '2021-11-30 19:40:04'),
(16, 'martin', '88Glam', 300, 10, '2021-11-30 19:40:04'),
(17, 'martin', '88Glam', 300, 10, '2021-11-30 19:40:04'),
(18, 'martin', '88Glam', 300, 10, '2021-11-30 19:40:04'),
(19, 'kai', '88Glam', 255, 10, '2021-11-30 19:41:37');

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
(1, '88Glam', 'backstage', '2021-11-15 16:45:07', '0000-00-00 00:00:00', 'benchmark', 1, 0, NULL),
(3, '88Glam', 'merchandise', '2021-11-15 17:10:41', '0000-00-00 00:00:00', 'raffle', 1, 0, NULL);

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

--
-- Dumping data for table `debug_log`
--

INSERT INTO `debug_log` (`id`, `log_type`, `message`, `log_file`, `log_line`, `date_logged`) VALUES
(8, 'helper', 'searchAccount returned 1 rows', 'Helper.php', 50, '2021-12-20 15:46:50'),
(9, 'helper', 'searchAccount returned 1 rows', 'Helper.php', 71, '2021-12-20 15:49:23'),
(10, 'helper', 'searchAccount returned 1 rows', 'Helper.php', 71, '2021-12-20 15:49:24'),
(11, 'helper', 'searchAccount returned 1 rows', 'Helper.php', 50, '2021-12-20 15:49:47'),
(12, 'helper', 'searchAccount returned 1 rows', 'Helper.php', 50, '2021-12-20 15:49:58'),
(13, 'helper', 'searchAccount returned 1 rows', 'Helper.php', 50, '2021-12-20 15:50:01');

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

--
-- Dumping data for table `info_log`
--

INSERT INTO `info_log` (`id`, `log_type`, `message`, `log_file`, `log_line`, `date_logged`) VALUES
(2, 'login', 'User martin just logged in', 'LoginBackend.php', 25, '2021-12-20 15:46:50'),
(3, 'login', 'User martin just logged in', 'LoginBackend.php', 25, '2021-12-20 15:49:58');

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
(1, '88Glam', 2000, 'IPO', '2021-11-12 16:54:18'),
(2, 'NAV', 20000, 'IPO', '2021-11-12 17:04:14'),
(3, '88Glam', 10, 'added 10 more shares', '2021-11-15 15:40:39'),
(9, '88Glam', 22, 'This is a test', '2021-11-28 18:08:53'),
(10, '88Glam', 21, 'This is a test', '2021-11-28 18:15:27'),
(11, '88Glam', 21, 'This is a test', '2021-11-28 18:16:29'),
(12, '88Glam', 21, 'This is a test', '2021-11-28 18:16:59'),
(13, '88Glam', 21, 'This is a test', '2021-11-28 18:17:37'),
(14, '88Glam', 21, 'This is a test', '2021-11-28 18:18:14'),
(15, '88Glam', 21, 'This is a test', '2021-11-28 18:18:42'),
(16, '88Glam', 21, 'This is a test', '2021-11-28 18:20:47'),
(17, '88Glam', 1, 'added 1', '2021-11-28 18:23:47'),
(18, '88Glam', 10, 'added 10 more share', '2021-11-28 18:24:47'),
(19, '88Glam', 1, 'This is a test', '2021-11-28 18:34:20'),
(20, '88Glam', 1, 'added 1 more share', '2021-11-28 18:35:25'),
(21, '88Glam', 1, 'added 1 more share', '2021-11-28 18:36:11'),
(22, '88Glam', 1, 'added 1 more share', '2021-11-28 18:36:33'),
(23, '88Glam', 1, 'added 1 more share', '2021-11-28 18:42:03'),
(24, '88Glam', 1, 'added 1', '2021-11-28 18:44:23'),
(25, '88Glam', 1, 'added 1', '2021-11-28 18:53:36'),
(26, '88Glam', 21, 'This is a test', '2021-11-28 19:01:28'),
(27, '88Glam', 1000, 'Injected 1000 shares', '2021-11-30 19:36:07');

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
  `date_posted` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `buy_history`
--
ALTER TABLE `buy_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `buy_order`
--
ALTER TABLE `buy_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `campaign`
--
ALTER TABLE `campaign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `debug_log`
--
ALTER TABLE `debug_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `error_log`
--
ALTER TABLE `error_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `info_log`
--
ALTER TABLE `info_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inject_history`
--
ALTER TABLE `inject_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `sell_order`
--
ALTER TABLE `sell_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `log_artist_pps` ON SCHEDULE EVERY 15 MINUTE STARTS '2021-11-05 09:00:00' ON COMPLETION NOT PRESERVE DISABLE DO CALL log_artist_pps$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
