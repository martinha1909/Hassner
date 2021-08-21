-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2021 at 04:27 AM
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
  `lower_bound` float NOT NULL,
  `deposit` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`username`, `password`, `account_type`, `id`, `Shares`, `balance`, `rate`, `Share_Distributed`, `email`, `billing_address`, `Full_name`, `City`, `State`, `ZIP`, `Card_number`, `Transit_no`, `Inst_no`, `Account_no`, `Swift`, `price_per_share`, `Monthly_shareholder`, `Income`, `Market_cap`, `lower_bound`, `deposit`) VALUES
('21 Savage', 'artist', 'artist', 6, 0, 0, 0, 0, '21savage@gmail.com', '', '', '', '', '', '', '', '', '', '', 1, 0, 0, 0, 0, 0),
('88Glam', 'artist', 'artist', 2, 16, 958.967, 0, 50, '12@gmail.com', '1234', '88 Camino', 'Toronto', 'Ontario', '123456', '1111-2222-3333-4444', '12345', '123', '12345678', 'AAAABBCC', 0.43137, 0, 0, 0, 2, 100),
('kai', 'user', 'user', 4, 6, 539.652, 0, 0, '123@gmail.com', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0),
('martin', 'user', 'user', 1, 14, 7968.08, 0, 0, 'martinvuha1909@gmail.com', '2240', 'Vu Ha (Martin)', 'Calgary', 'AB', 'T2N', '1111-2222-3333-4444', '1', '1', '1', '1', 0, 0, 0, 0, 0, 0),
('NAV', 'artist', 'artist', 3, 9, 1203.98, 0.013, 10, '4321@gmail.com', '', '', '', '', '', '', '', '', '', '', 126.784, 0, 0, 0, 0, 0),
('vitor', 'user', 'user', 5, 2, 244.256, 0, 0, '1234@gmail.com', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_artist_sell_share`
--

CREATE TABLE `user_artist_sell_share` (
  `user_username` varchar(50) NOT NULL,
  `artist_username` varchar(50) NOT NULL,
  `selling_price` int(11) NOT NULL,
  `no_of_share` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_artist_sell_share`
--

INSERT INTO `user_artist_sell_share` (`user_username`, `artist_username`, `selling_price`, `no_of_share`) VALUES
('kai', 'NAV', 100, 0),
('martin', '88Glam', 1, 4),
('martin', '88Glam', 4, 2),
('martin', '88Glam', 10, 3),
('vitor', '88Glam', 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_artist_share`
--

CREATE TABLE `user_artist_share` (
  `user_username` varchar(50) NOT NULL,
  `artist_username` varchar(50) NOT NULL,
  `no_of_share_bought` int(255) NOT NULL,
  `price_per_share_when_bought` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_artist_share`
--

INSERT INTO `user_artist_share` (`user_username`, `artist_username`, `no_of_share_bought`, `price_per_share_when_bought`) VALUES
('kai', 'NAV', 6, 70.9525),
('martin', '88Glam', 14, 5),
('martin', 'NAV', 0, 64.356),
('vitor', '88Glam', 2, 7.2954),
('vitor', 'NAV', 3, 109.521);

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
-- Indexes for table `user_artist_sell_share`
--
ALTER TABLE `user_artist_sell_share`
  ADD PRIMARY KEY (`user_username`,`artist_username`,`selling_price`),
  ADD KEY `artist_username_key_sell` (`artist_username`);

--
-- Indexes for table `user_artist_share`
--
ALTER TABLE `user_artist_share`
  ADD PRIMARY KEY (`user_username`,`artist_username`),
  ADD KEY `artist_share_key` (`artist_username`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_artist_sell_share`
--
ALTER TABLE `user_artist_sell_share`
  ADD CONSTRAINT `artist_username_key_sell` FOREIGN KEY (`artist_username`) REFERENCES `account` (`username`),
  ADD CONSTRAINT `user_username_key_sell` FOREIGN KEY (`user_username`) REFERENCES `account` (`username`);

--
-- Constraints for table `user_artist_share`
--
ALTER TABLE `user_artist_share`
  ADD CONSTRAINT `artist_share_key` FOREIGN KEY (`artist_username`) REFERENCES `account` (`username`),
  ADD CONSTRAINT `user_share_key` FOREIGN KEY (`user_username`) REFERENCES `account` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;