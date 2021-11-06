-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2021 at 04:37 AM
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
('21 Savage', 'artist', 'artist', 6, 2386, 3181.33, 0, 150000, '21savage@gmail.com', '', '', '', '', '', '', '', '', '', '', 1.33333, 0, 0, 3181.33, 0),
('88Glam', 'artist', 'artist', 2, 4406, 8797.2, 0, 100000, '12@gmail.com', '1234', '88 Camino', 'Toronto', 'Ontario', '123456', '1111-2222-3333-4444', '12345', '123', '12345678', 'AAAABBCC', 6, 0, 0, 26436, 4),
('daniel', 'user', 'user', 8, 0, 100000, 0, 0, 'iosrghn@gmail.com', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0),
('Drake', 'artist', 'artist', 11, 859, 859, 0, 30000, 'qwerty@gmail.com', 'Drake', '', '', '', '', '', '', '', '', '', 1, 0, 0, 859, 0),
('kai', 'user', 'user', 4, 358, 98964.2, 0, 0, '123@gmail.com', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0),
('martin', 'user', 'user', 1, 4041, 88204.3, 0, 0, 'martinvuha1909@gmail.com', '2240', 'Vu Ha (Martin)', 'Calgary', 'AB', 'T2N', '1111-2222-3333-4444', '12345', '123', '12345678', 'AAAABBCC', 0, 0, 0, 0, 0),
('NAV', 'artist', 'artist', 3, 0, 0, 0, 1000, '4321@gmail.com', '', '', '', '', '', '', '', '', '', '', 3, 0, 0, 0, 0),
('riley', 'user', 'user', 7, 0, 100000, 0, 0, 'efin@gmail.com', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0),
('vitor', 'user', 'user', 5, 3, 99994, 0, 0, '1234@gmail.com', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0);

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
('88Glam', '88Gm'),
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
('88Glam', '88Glam', 4),
('kai', '88Glam', 358),
('kai', 'Drake', 859),
('martin', '21 Savage', 2386),
('martin', '88Glam', 4041),
('vitor', '88Glam', 3);

-- --------------------------------------------------------

--
-- Table structure for table `artist_stock_change`
--

CREATE TABLE `artist_stock_change` (
  `artist_username` varchar(20) NOT NULL,
  `price_per_share` float NOT NULL,
  `date_recorded` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `buy_history`
--

CREATE TABLE `buy_history` (
  `user_username` varchar(50) NOT NULL,
  `seller_username` varchar(50) NOT NULL,
  `artist_username` varchar(50) NOT NULL,
  `no_of_share_bought` int(11) NOT NULL,
  `price_per_share_when_bought` float NOT NULL,
  `date_purchased` varchar(10) NOT NULL,
  `time_purchased` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `buy_history`
--

INSERT INTO `buy_history` (`user_username`, `seller_username`, `artist_username`, `no_of_share_bought`, `price_per_share_when_bought`, `date_purchased`, `time_purchased`) VALUES
('88Glam', 'kai', '88Glam', 1, 5, '02-11-2021', '17:38:47'),
('88Glam', 'martin', '88Glam', 2, 6, '02-11-2021', '18:02:25'),
('88Glam', 'martin', '88Glam', 1, 6, '02-11-2021', '18:02:51'),
('kai', '88Glam', '88Glam', 922, 2, '02-11-2021', '16:37:45'),
('kai', '88Glam', '88Glam', 1, 4, '02-11-2021', '17:10:37'),
('kai', 'Drake', 'Drake', 859, 1, '01-11-2021', '18:33:32'),
('kai', 'martin', '88Glam', 1, 3, '02-11-2021', '17:05:05'),
('kai', 'martin', '88Glam', 6, 2, '02-11-2021', '18:01:45'),
('kai', 'martin', '88Glam', 1, 1, '04-11-2021', '21:13:33'),
('kai', 'martin', '88Glam', 1, 5, '04-11-2021', '21:14:30'),
('martin', '21 Savage', '21 Savage', 2386, 1.33333, '02-11-2021', '16:35:27'),
('martin', '88Glam', '88Glam', 1, 2, '02-11-2021', '16:34:02'),
('martin', '88Glam', '88Glam', 1, 2, '02-11-2021', '16:34:59'),
('martin', '88Glam', '88Glam', 1560, 2, '02-11-2021', '16:35:57'),
('martin', '88Glam', '88Glam', 1, 3, '02-11-2021', '17:00:30'),
('martin', '88Glam', '88Glam', 1, 3, '02-11-2021', '17:04:08'),
('martin', '88Glam', '88Glam', 1, 2, '02-11-2021', '17:55:24'),
('martin', '88Glam', '88Glam', 1, 2, '02-11-2021', '17:57:33'),
('martin', '88Glam', '88Glam', 1, 2, '02-11-2021', '17:58:17'),
('martin', '88Glam', '88Glam', 1, 2, '02-11-2021', '17:58:34'),
('martin', '88Glam', '88Glam', 1, 1, '03-11-2021', '15:55:30'),
('martin', '88Glam', '88Glam', 1, 1, '03-11-2021', '15:56:06'),
('martin', '88Glam', '88Glam', 1, 1, '03-11-2021', '16:30:48'),
('martin', '88Glam', '88Glam', 1, 1, '03-11-2021', '16:36:32'),
('martin', '88Glam', '88Glam', 1, 1, '03-11-2021', '16:37:13'),
('martin', '88Glam', '88Glam', 1909, 2, '29-10-2021', '17:57:33'),
('martin', 'kai', '88Glam', 1, 2, '02-11-2021', '16:38:13'),
('martin', 'kai', '88Glam', 1, 3, '02-11-2021', '17:04:22'),
('martin', 'kai', '88Glam', 1, 4, '02-11-2021', '17:11:22'),
('martin', 'kai', '88Glam', 1, 2, '02-11-2021', '17:12:48'),
('martin', 'kai', '88Glam', 1, 2, '02-11-2021', '17:14:06'),
('martin', 'kai', '88Glam', 17, 2, '02-11-2021', '17:26:44'),
('martin', 'kai', '88Glam', 500, 3, '02-11-2021', '17:28:02'),
('martin', 'kai', '88Glam', 1, 3, '02-11-2021', '17:29:00'),
('martin', 'kai', '88Glam', 1, 5, '02-11-2021', '17:31:25'),
('martin', 'kai', '88Glam', 1, 5, '02-11-2021', '17:32:08'),
('martin', 'kai', '88Glam', 1, 3.6, '02-11-2021', '19:47:05'),
('martin', 'kai', '88Glam', 43, 3.6, '02-11-2021', '19:53:27'),
('martin', 'kai', '88Glam', 1, 3, '02-11-2021', '19:55:53'),
('martin', 'kai', '88Glam', 1, 4, '04-11-2021', '21:12:11'),
('martin', 'kai', '88Glam', 1, 3.6, '04-11-2021', '21:13:08'),
('vitor', '88Glam', '88Glam', 1, 1, '03-11-2021', '19:14:34'),
('vitor', 'kai', '88Glam', 1, 1, '03-11-2021', '19:15:09'),
('vitor', 'martin', '88Glam', 1, 1, '03-11-2021', '19:14:05');

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
  `date_posted` varchar(20) NOT NULL,
  `time_posted` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `buy_order`
--

INSERT INTO `buy_order` (`id`, `user_username`, `artist_username`, `quantity`, `siliqas_requested`, `date_posted`, `time_posted`) VALUES
(1, 'martin', '88Glam', 3281, 3, '02-11-2021', '17:26:44'),
(2, 'martin', '88Glam', 148, 5, '02-11-2021', '17:27:11');

-- --------------------------------------------------------

--
-- Table structure for table `campaign`
--

CREATE TABLE `campaign` (
  `id` int(11) NOT NULL,
  `artist_username` varchar(50) NOT NULL,
  `offering` varchar(20) NOT NULL,
  `date_posted` varchar(20) NOT NULL,
  `time_posted` varchar(10) NOT NULL,
  `date_expires` varchar(10) NOT NULL,
  `time_expires` varchar(10) NOT NULL,
  `type` varchar(10) NOT NULL,
  `minimum_ethos` float NOT NULL,
  `eligible_participants` int(11) NOT NULL,
  `winner` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `dummy`
--

CREATE TABLE `dummy` (
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dummy`
--

INSERT INTO `dummy` (`date`) VALUES
('0000-00-00 00:00:00'),
('2021-11-05 21:22:24');

-- --------------------------------------------------------

--
-- Table structure for table `inject_history`
--

CREATE TABLE `inject_history` (
  `id` int(11) NOT NULL,
  `artist_username` varchar(50) NOT NULL,
  `amount` int(11) NOT NULL,
  `date_injected` varchar(50) NOT NULL,
  `time_injected` varchar(20) NOT NULL,
  `comment` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `inject_history`
--

INSERT INTO `inject_history` (`id`, `artist_username`, `amount`, `date_injected`, `time_injected`, `comment`) VALUES
(1, '88Glam', 100000, '29-10-2021', '16:39:40', 'IPO'),
(2, '21 Savage', 150000, '01-11-2021', '14:46:02', 'IPO'),
(3, 'NAV', 1000, '01-11-2021', '14:46:14', 'IPO'),
(4, 'Drake', 30000, '01-11-2021', '14:46:28', 'IPO');

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
  `date_posted` varchar(20) NOT NULL,
  `time_posted` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sell_order`
--

INSERT INTO `sell_order` (`id`, `user_username`, `artist_username`, `selling_price`, `no_of_share`, `date_posted`, `time_posted`) VALUES
(1, 'martin', '88Glam', 6, 460, '02-11-2021', '18:01:27'),
(2, 'kai', '88Glam', 3.6, 27, '02-11-2021', '18:02:00'),
(3, 'kai', '88Glam', 1, 19, '02-11-2021', '19:54:28');

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
  ADD PRIMARY KEY (`user_username`,`seller_username`,`date_purchased`,`time_purchased`),
  ADD KEY `artist_buy_history_key` (`artist_username`),
  ADD KEY `seller_buy_history_key` (`seller_username`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
-- Constraints for table `buy_history`
--
ALTER TABLE `buy_history`
  ADD CONSTRAINT `artist_buy_history_key` FOREIGN KEY (`artist_username`) REFERENCES `account` (`username`),
  ADD CONSTRAINT `seller_buy_history_key` FOREIGN KEY (`seller_username`) REFERENCES `account` (`username`),
  ADD CONSTRAINT `user_buy_history_key` FOREIGN KEY (`user_username`) REFERENCES `account` (`username`);

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
CREATE DEFINER=`root`@`localhost` EVENT `log_artist_pps` ON SCHEDULE EVERY 1 MINUTE STARTS '2021-11-05 09:00:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL log_artist_pps$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
