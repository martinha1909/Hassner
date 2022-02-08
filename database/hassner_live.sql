-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 08, 2022 at 01:05 AM
-- Server version: 10.6.5-MariaDB
-- PHP Version: 7.4.27

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
('kai', 'user', 'user', 4, 0, 0, 0, 0, '123@gmail.com', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0),
('martin', 'user', 'user', 1, 0, 0, 0, 0, 'martinvuha1909@gmail.com', '2240', 'Vu Ha (Martin)', 'Calgary', 'AB', 'T2N', '1111-2222-3333-4444', '12345', '123', '12345678', 'AAAABBCC', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `artist_account_data`
--

CREATE TABLE `artist_account_data` (
  `artist_username` varchar(20) NOT NULL,
  `ticker` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `artist_followers`
--

CREATE TABLE `artist_followers` (
  `artist_username` varchar(20) NOT NULL,
  `user_username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `artist_shareholders`
--

CREATE TABLE `artist_shareholders` (
  `user_username` varchar(20) NOT NULL,
  `artist_username` varchar(20) NOT NULL,
  `shares_owned` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `id` int(11) NOT NULL,
  `user_username` varchar(20) NOT NULL,
  `seller_username` varchar(20) NOT NULL,
  `artist_username` varchar(20) NOT NULL,
  `no_of_share_bought` int(11) NOT NULL,
  `price_per_share_when_bought` float NOT NULL,
  `date_purchased` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `is_from_injection` tinyint(1) NOT NULL,
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `buy_order`
--
ALTER TABLE `buy_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `campaign`
--
ALTER TABLE `campaign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `debug_log`
--
ALTER TABLE `debug_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `error_log`
--
ALTER TABLE `error_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `info_log`
--
ALTER TABLE `info_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inject_history`
--
ALTER TABLE `inject_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `sell_order`
--
ALTER TABLE `sell_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
