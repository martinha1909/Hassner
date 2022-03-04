-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2022 at 09:25 PM
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
  `password` varchar(60) NOT NULL,
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
('AaronAuracoCastillo', '$2y$10$6zj0gKP6Ippv9UN1TmyCeuNm3riHkTkoxsvlq1A9gu7q3Hs8n98hK', 'user', 47, 0, '0.0', '0.0', 0, 'aaronaraucocastillo@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('Al Lure', '$2y$10$5fzV7dVBevglni99U3TnOePLaH07Hg75gJvsqdhtF9hKAq4QbGjHm', 'artist', 100, 20, '200.0', '0.0', 20, '123@gmail.com', '', '', '', '', '', '', '', '', '', '', '5.5', 0, '0.0', '110.0', 0),
('AlexGuo', '$2y$10$artwt3hLrFrpK2W7Zuh8fOLnTOkK8UKle8GLAJcWwaFg8J6zFIPd6', 'user', 66, 0, '0.0', '0.0', 0, 'alexguoduoduo@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('AliYasarizare', '$2y$10$6iZ9Ew8NkuV5qcWQ6fCTNuJk89ah2ANGJxuOAWJF/v10RnPZuOLVa', 'user', 63, 0, '0.0', '0.0', 0, 'a.yasariz@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('AlviTabriz', '$2y$10$qzUOdm4z04R4Rr8J1JJOgeG1Bax/GGxVuKuPbPN9NICURz6DmU0QO', 'user', 102, 0, '0.0', '0.0', 0, 'alvi.tabriz@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('AshtonHandel', '$2y$10$UL5rXvxzS0v9IF6bFKisA.UpSPW4mZ0qxnq6B04v6DMBadOJoQc4C', 'user', 28, 0, '0.0', '0.0', 0, 'Ashton.handel1@ucalgary.ca', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('BenKrett', '$2y$10$ghv69fc09hjLG9AZi83.VO4VykmmUZxfcH2redz3wCUF43gdX2Is2', 'user', 69, 0, '0.0', '0.0', 0, 'Benkrett@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('ChristineRobinson', '$2y$10$uB7/.DLHszhgoOwVbwlPl.F2i2soWc4Qa2UEW7zbXojH8d/1kyeeK', 'user', 57, 0, '0.0', '0.0', 0, 'Toby.ocean@hotmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('DanielleJames', '$2y$10$hyCXJwrkpDHB1NA0PSSsW.2B9r3ISmtGCgMbyKlYAZtmfcnDDkPAi', 'user', 62, 0, '0.0', '0.0', 0, 'djame664@mtroyal.ca', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('DylanBaliski', '$2y$10$bH04jQCC1C3cna5iMIxQjemyte07nCPGD5cJFcBJ7pd4XYadj9gV2', 'user', 48, 0, '0.0', '0.0', 0, 'Dillofthepickle99@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('EbenezerUssher', '$2y$10$9uVaBoo4KaC8JRSaH5aTauiBcYTXot0sKivcwCwKHOX8tG7KaGZR6', 'user', 56, 0, '0.0', '0.0', 0, 'ussherebenezer@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('EmmanuelDougmawi', '$2y$10$aPNul6PHAMhNUOcCa8C7cu4pRxeqqqdJKKQVhsmrh9mrlhmXWT5AG', 'user', 30, 0, '0.0', '0.0', 0, 'Agbadvising@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('HamishCampbell', '$2y$10$AD2VRIVle0T.T1YLDPrMWuXyIiFrf36rtzc90zhbwggLLkLUqfR3y', 'user', 68, 0, '0.0', '0.0', 0, 'djhowla@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('HarshitSingh', '$2y$10$gUOoaVxH0pGReuw/cqYEeOVkeLtvc799fZNSkEgEgOVhDxbNXVevS', 'user', 51, 0, '0.0', '0.0', 0, 'harshit.y.singh@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('HaydnCannon', '$2y$10$QqP56uSZ1.qCeV0/jmYMFu9rJGAZh.H.LGHw69bl5cHdvPajuGRHO', 'user', 101, 0, '0.0', '0.0', 0, 'haydn_cannon@hotmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('JacksonSchneider', '$2y$10$bdKuYApeT4ELQMQWocRAOONCQ5qm9X12/gO.xmr3/Oy4hHywNfMCu', 'user', 54, 0, '0.0', '0.0', 0, 'jackson.schneider@ucalgary.ca', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('JanErikNaess', '$2y$10$6vtbRCkLK1jVt4XMeeMdF.ikz2CYZuEmqbAN8Gc8g36cWr.dMA0MW', 'user', 55, 0, '0.0', '0.0', 0, 'Naessjanerik@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('JerryTseng', '$2y$10$Cn8lpQKqJh5xIn3BKqcPluaI7BfZrdSoo5D7Vs7vp/ljz5s.o9mBm', 'user', 60, 0, '0.0', '0.0', 0, 'JerryTseng20@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('kai', '$2y$10$bwo7Ec1/b/5Kqalq5iIsWeo2lYrKwPj14c0nFrzlo2edcbVWmZ4sy', 'user', 0, 10, '15.0', '0.0', 0, '123@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('KaiMostat', '$2y$10$KyGhAx4tRSaLzPBIquIpNe8XXCX9f9yq2FYH1Mm1i09w9naGSb5mi', 'user', 103, 0, '0.0', '0.0', 0, 'Kaim2004@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('LiamSavage', '$2y$10$ItB8JM9T6U.oj9agpolT2.uZnowLxHL1.qgHt5vjpsblS48WTmNpO', 'user', 53, 0, '0.0', '0.0', 0, 'Liamsav00@yahoo.ca', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('martin', '$2y$10$zp5BCOh9q1dCs2Gf5HLtre3ZJo5rWMH0O/nQbSqLYDC4u0EnAyFUW', 'user', 1, 0, '85.0', '0.0', 0, 'minhvuha1909@gmail.com', '2240', 'Vu Ha (Martin)', 'Calgary', 'AB', 'T2N', '1111-2222-3333-4444', '12345', '123', '12345678', 'AAAABBCC', '0.0', 0, '0.0', '0.0', 0),
('MichaelaHoskin', '$2y$10$TqZ44LJ66zTjisGPeP0zQu2g685l84WgOgXbRZfJw1oGyyCCybOVG', 'user', 49, 0, '0.0', '0.0', 0, 'Michaela.Hoskin@ucalgary.ca', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('MuradIskandarov', '$2y$10$j5WJU.LUJHpXUs8cjgNlt.ekWcPUZy9wfhVyKzJynlejT3ftfaxkq', 'user', 46, 0, '0.0', '0.0', 0, 'murad.iskandarov@ucalgary.ca', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('NicsonDo', '$2y$10$aQ6KzAPPwa0oT.TrDNGHJOP5Bo45QXRoBVOD8tug8KepwDXSfLga2', 'user', 24, 0, '0.0', '0.0', 0, 'ndo@ucalgary.ca', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('NikolasDeLeeuw', '$2y$10$DnHDII18TD5QvCCXTUDm7ukfRWA6eRGpbVeSZrj2.GGKpat9yfSJ.', 'user', 34, 0, '0.0', '0.0', 0, 'Deleeuw.nikolas@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('NisargBhalani', '$2y$10$.pNUgoF.Jed.Arqws5taOOw.LeNAytmezH3rcyp3jQB8yufT9/opi', 'user', 65, 0, '0.0', '0.0', 0, 'nisarg.bhalani@ucalgary.ca', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('NoahAnthonyDMello', '$2y$10$HG5vk8.pSpAgLGmKtZnvUe.TRDmz16Ue3v8./ifBoMjbC7Bf/MxAS', 'user', 31, 0, '0.0', '0.0', 0, 'noahdmello104@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('NoahBradley', '$2y$10$Wn8hTHB9vjKQ/9/3yMz1UOHRfD1x8uyqV7MS3jvUjNwmNOS0ESL2G', 'user', 67, 0, '0.0', '0.0', 0, 'nbradleyjoe19@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('OwenBox', '$2y$10$68LORmayHsbTJmFXkRMbX.BQoKTzfA54PODx4dhZykUt4hCg0MiB6', 'user', 58, 0, '0.0', '0.0', 0, 'owensamuelbox@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('RaelaThiemann', '$2y$10$tuoU4BPrWLW5sngtZGm4gu4lCGLysVHPK0upctyeHvV.ne1WrgQta', 'user', 27, 0, '0.0', '0.0', 0, 'raelathiemann@yahoo.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('RheaLobo', '$2y$10$d2TLVRmEzPgBjAPUAMXU3ul0PlLWKIjjUrlqj2Eu27B7ZUJ1Aif5.', 'user', 32, 0, '0.0', '0.0', 0, 'letsbrheal@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('riley', '$2y$10$UdyBPMQ0n0FJDOdu.OgNzeYn6UEiF.PNCnbm0xJbWS90noz5VjVPW', 'user', 70, 0, '100.0', '0.0', 0, 'rileyjeromek@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('RyanTaylor', '$2y$10$S0Sm9DIMsouL1bBIpFrFLOtoG23BA2SKVE/lSWFJDzIVkSe2Xr2Ky', 'user', 59, 0, '0.0', '0.0', 0, 'Ryancraigt@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('SamWalker', '$2y$10$jEocmw1dKb7S9y0X6fB0o.OvXoJn5Tgl8NRmwhTqptgQJu5Xrcl96', 'user', 52, 0, '0.0', '0.0', 0, 'samuelwalker1111@yahoo.ca', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('ShannaHollingworth', '$2y$10$WTwgnFmbd6JYSfnjHwulfO5vR4m9yc85GwJW12J1g0NrGR9/0Z9JS', 'user', 64, 0, '0.0', '0.0', 0, 'Shanna.hollingwor1@ucalgary.ca', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('ShayAnderson', '$2y$10$JqAGYAIwm5VF4ssc1w705uZ.mvm5Bkul0m7hYLTuFN9HAjMa3xEQa', 'user', 33, 0, '0.0', '0.0', 0, 'shay_anderson@icloud.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('ThomsonMidzi', '$2y$10$/6QM5nz.c/XKnxq5Ubw3GeffphxNzs5/tTa73ihZ8xmUYV/QDrgwq', 'user', 61, 0, '0.0', '0.0', 0, 'thomsonmidzi1@gmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('vitor', '$2y$10$F8nL33dWlrOXWDu1.xE.QOZz68dE7wc4PxdBR7nijFFrw3EKPhpIy', 'user', 71, 10, '0.0', '0.0', 0, 'vitor@hotmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0),
('ZacharyWildman', '$2y$10$qSVQFJ4aRY7o8Kotm4PLb.jKJTTBiQiK777jGpyrBR.QD/QS/LDqq', 'user', 29, 0, '0.0', '0.0', 0, 'Zacharywildman@hotmail.com', '', '', '', '', '', '', '', '', '', '', '0.0', 0, '0.0', '0.0', 0);

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
('Al Lure', '18AL');

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

--
-- Dumping data for table `artist_shareholders`
--

INSERT INTO `artist_shareholders` (`user_username`, `artist_username`, `shares_owned`) VALUES
('kai', 'Al Lure', 10),
('martin', 'Al Lure', 0),
('riley', 'Al Lure', 0),
('vitor', 'Al Lure', 10);

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
('Al Lure', '11.0', '2022-02-22 21:44:57'),
('Al Lure', '11.0', '2022-02-22 21:59:52'),
('Al Lure', '11.0', '2022-02-22 22:00:30'),
('Al Lure', '11.0', '2022-02-22 22:00:35'),
('Al Lure', '11.0', '2022-02-22 22:00:40'),
('Al Lure', '11.0', '2022-02-22 22:00:45'),
('Al Lure', '11.0', '2022-02-22 22:00:50'),
('Al Lure', '11.0', '2022-02-22 22:00:55'),
('Al Lure', '11.0', '2022-02-22 22:01:00'),
('Al Lure', '11.0', '2022-02-22 22:01:30'),
('Al Lure', '11.0', '2022-02-22 22:01:35'),
('Al Lure', '11.0', '2022-02-22 22:01:40'),
('Al Lure', '11.0', '2022-02-22 22:01:45'),
('Al Lure', '11.0', '2022-02-22 22:01:50'),
('Al Lure', '11.0', '2022-02-22 22:01:55'),
('Al Lure', '11.0', '2022-02-22 22:02:00'),
('Al Lure', '11.0', '2022-02-22 22:02:05'),
('Al Lure', '11.0', '2022-02-22 22:02:10'),
('Al Lure', '10.0', '2022-02-22 22:14:29'),
('Al Lure', '10.0', '2022-02-22 23:14:29'),
('Al Lure', '10.0', '2022-02-23 00:14:29'),
('Al Lure', '10.0', '2022-02-23 01:14:29'),
('Al Lure', '10.0', '2022-02-23 02:14:29'),
('Al Lure', '10.0', '2022-02-23 03:14:29'),
('Al Lure', '10.0', '2022-02-23 04:14:29');

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
(33, 'riley', 'Al Lure', 'Al Lure', 10, '10.0', '2022-02-21 18:22:42'),
(34, 'martin', 'Al Lure', 'Al Lure', 10, '10.0', '2022-02-21 18:25:12'),
(35, 'kai', 'martin', 'Al Lure', 5, '10.0', '2022-02-21 18:28:08'),
(36, 'kai', 'riley', 'Al Lure', 5, '10.0', '2022-02-21 18:29:04'),
(37, 'vitor', 'kai', 'Al Lure', 5, '10.0', '2022-02-21 18:32:00'),
(38, 'martin', 'vitor', 'Al Lure', 2, '10.0', '2022-02-22 18:05:58'),
(39, 'kai', 'martin', 'Al Lure', 1, '10.0', '2022-02-22 18:12:42'),
(40, 'kai', 'martin', 'Al Lure', 2, '10.0', '2022-02-22 18:16:08'),
(41, 'kai', 'martin', 'Al Lure', 2, '10.0', '2022-02-22 18:27:10'),
(42, 'vitor', 'riley', 'Al Lure', 5, '10.0', '2022-02-22 18:27:46'),
(43, 'vitor', 'kai', 'Al Lure', 2, '10.0', '2022-02-22 18:32:05'),
(44, 'martin', 'kai', 'Al Lure', 7, '10.0', '2022-02-22 18:32:16'),
(45, 'martin', 'vitor', 'Al Lure', 1, '10.0', '2022-02-22 18:32:32'),
(46, 'riley', 'vitor', 'Al Lure', 3, '10.0', '2022-02-22 18:32:58'),
(47, 'riley', 'martin', 'Al Lure', 4, '10.0', '2022-02-22 18:34:54'),
(48, 'kai', 'martin', 'Al Lure', 6, '10.0', '2022-02-22 18:34:54'),
(49, 'kai', 'riley', 'Al Lure', 3, '10.0', '2022-02-22 18:35:23'),
(50, 'martin', 'riley', 'Al Lure', 1, '10.0', '2022-02-22 18:36:30'),
(51, 'martin', 'vitor', 'Al Lure', 6, '10.0', '2022-02-22 18:36:42'),
(52, 'martin', 'riley', 'Al Lure', 3, '10.0', '2022-02-22 18:36:51'),
(53, 'vitor', 'kai', 'Al Lure', 1, '10.0', '2022-02-22 18:40:19'),
(54, 'vitor', 'kai', 'Al Lure', 4, '10.0', '2022-02-22 18:47:02'),
(55, 'vitor', 'kai', 'Al Lure', 4, '10.0', '2022-02-22 18:47:14'),
(56, 'vitor', 'martin', 'Al Lure', 1, '10.0', '2022-02-22 18:47:49'),
(57, 'kai', 'martin', 'Al Lure', 1, '10.0', '2022-02-22 20:52:36'),
(58, 'kai', 'martin', 'Al Lure', 1, '10.0', '2022-02-22 20:54:50'),
(59, 'kai', 'martin', 'Al Lure', 3, '10.0', '2022-02-22 21:21:48'),
(60, 'kai', 'martin', 'Al Lure', 1, '5.5', '2022-02-22 22:10:21'),
(61, 'kai', 'martin', 'Al Lure', 3, '5.5', '2022-02-23 16:00:23');

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

--
-- Dumping data for table `buy_order`
--

INSERT INTO `buy_order` (`id`, `user_username`, `artist_username`, `quantity`, `siliqas_requested`, `buy_limit`, `buy_stop`, `date_posted`) VALUES
(105, 'martin', 'Al Lure', 12, '5.5', '-1.0', '-1.0', '2022-02-23 11:18:28'),
(106, 'martin', 'Al Lure', 7, '5.5', '-1.0', '-1.0', '2022-02-24 13:24:12');

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
  `minimum_ethos` decimal(10,1) NOT NULL,
  `eligible_participants` int(11) NOT NULL,
  `winner` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `campaign`
--

INSERT INTO `campaign` (`id`, `artist_username`, `offering`, `date_posted`, `date_expires`, `type`, `minimum_ethos`, `eligible_participants`, `winner`, `is_active`) VALUES
(4, 'Al Lure', 'NFT', '2022-02-22 18:02:19', '2022-02-22 18:05:00', 'raffle', '5.0', 0, 'kai', 0),
(5, 'Al Lure', '', '2022-02-22 18:09:57', '2022-02-22 18:15:00', 'raffle', '7.0', 0, NULL, 0),
(6, 'Al Lure', '', '2022-02-22 18:15:39', '2022-02-22 18:18:00', 'benchmark', '8.0', 0, NULL, 0),
(7, 'Al Lure', '', '2022-02-22 18:24:42', '2022-02-22 18:26:00', 'raffle', '1.0', 1, 'kai', 0),
(8, 'Al Lure', 'hello', '2022-02-22 18:25:04', '2022-02-22 18:28:00', 'raffle', '2.0', 0, 'kai', 0),
(9, 'Al Lure', '$$$', '2022-02-22 18:26:45', '2022-02-22 18:30:00', 'raffle', '10.0', 0, 'kai', 0),
(10, 'Al Lure', 'Signature', '2022-02-22 18:30:10', '2022-02-22 18:32:00', 'raffle', '1.0', 1, 'kai', 0),
(11, 'Al Lure', '$', '2022-02-22 18:31:51', '2022-02-22 18:35:00', 'raffle', '1.0', 1, 'vitor', 0),
(12, 'Al Lure', '', '2022-02-22 18:36:11', '2022-02-22 18:39:00', 'benchmark', '10.0', 0, NULL, 0),
(13, 'Al Lure', 'kljdlkj', '2022-02-22 20:50:10', '2022-02-22 20:52:00', 'benchmark', '9.0', 1, NULL, 0),
(14, 'Al Lure', '', '2022-02-22 20:54:06', '2022-02-22 20:55:00', 'benchmark', '8.0', 1, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `campaign_participant`
--

CREATE TABLE `campaign_participant` (
  `user_username` varchar(20) NOT NULL,
  `campaign_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `campaign_participant`
--

INSERT INTO `campaign_participant` (`user_username`, `campaign_id`) VALUES
('kai', 7),
('kai', 10),
('kai', 11),
('vitor', 13),
('vitor', 14);

-- --------------------------------------------------------

--
-- Table structure for table `hx_log`
--

CREATE TABLE `hx_log` (
  `id` int(11) NOT NULL,
  `log_level` varchar(20) NOT NULL,
  `log_type` varchar(20) NOT NULL,
  `log_msg` varchar(10000) NOT NULL,
  `log_file` varchar(20) NOT NULL,
  `log_line` int(11) NOT NULL,
  `date_logged` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hx_log`
--

INSERT INTO `hx_log` (`id`, `log_level`, `log_type`, `log_msg`, `log_file`, `log_line`, `date_logged`) VALUES
(1, 'debug', 'query', '[debug]-[query]-[logger.php@96]getAccountTypeFromUsername returned 1 entries\r\n', 'logger.php', 96, '2022-02-24 13:22:18'),
(2, 'debug', 'query', '[debug]-[query]-[logger.php@96]account_info data: {\"account_type\":\"user\"}\r\n', 'logger.php', 96, '2022-02-24 13:22:18'),
(3, 'info', 'login', '[info]-[login]-[logger.php@54]User martin just logged in\r\n', 'logger.php', 54, '2022-02-24 13:22:18'),
(4, 'debug', 'helper', '[debug]-[helper]-[logger.php@96]searchAccount returned 1 rows\r\n', 'logger.php', 96, '2022-02-24 13:22:19'),
(5, 'debug', 'query', '[debug]-[query]-[logger.php@96]searchAccount returned 1 entries\r\n', 'logger.php', 96, '2022-02-24 13:22:19'),
(6, 'debug', 'query', '[debug]-[query]-[logger.php@96]price_per_share data: {\"username\":\"Al Lure\",\"password\":\"$2y$10$5fzV7dVBevglni99U3TnOePLaH07Hg75gJvsqdhtF9hKAq4QbGjHm\",\"account_type\":\"artist\",\"id\":100,\"Shares\":20,\"balance\":\"200.0\",\"rate\":\"0.0\",\"Share_Distributed\":20,\"email\":\"123@gmail.com\",\"billing_address\":\"\",\"Full_name\":\"\",\"City\":\"\",\"State\":\"\",\"ZIP\":\"\",\"Card_number\":\"\",\"Transit_no\":\"\",\"Inst_no\":\"\",\"Account_no\":\"\",\"Swift\":\"\",\"price_per_share\":\"5.5\",\"Monthly_shareholder\":0,\"Income\":\"0.0\",\"Market_cap\":\"110.0\",\"shares_repurchase\":0}\r\n', 'logger.php', 96, '2022-02-24 13:22:19'),
(7, 'debug', 'helper', '[debug]-[helper]-[logger.php@96]searchAccount returned 1 rows\r\n', 'logger.php', 96, '2022-02-24 13:22:19'),
(8, 'debug', 'query', '[debug]-[query]-[logger.php@96]balance data: {\"username\":\"martin\",\"password\":\"$2y$10$zp5BCOh9q1dCs2Gf5HLtre3ZJo5rWMH0O\\/nQbSqLYDC4u0EnAyFUW\",\"account_type\":\"user\",\"id\":1,\"Shares\":0,\"balance\":\"85.0\",\"rate\":\"0.0\",\"Share_Distributed\":0,\"email\":\"minhvuha1909@gmail.com\",\"billing_address\":\"2240\",\"Full_name\":\"Vu Ha (Martin)\",\"City\":\"Calgary\",\"State\":\"AB\",\"ZIP\":\"T2N\",\"Card_number\":\"1111-2222-3333-4444\",\"Transit_no\":\"12345\",\"Inst_no\":\"123\",\"Account_no\":\"12345678\",\"Swift\":\"AAAABBCC\",\"price_per_share\":\"0.0\",\"Monthly_shareholder\":0,\"Income\":\"0.0\",\"Market_cap\":\"0.0\",\"shares_repurchase\":0}\r\n', 'logger.php', 96, '2022-02-24 13:22:19'),
(9, 'debug', 'helper', '[debug]-[helper]-[logger.php@96]getUserBalance returned 85 as a result\r\n', 'logger.php', 96, '2022-02-24 13:22:19'),
(10, 'debug', 'helper', '[debug]-[helper]-[logger.php@96]searchAccount returned 1 rows\r\n', 'logger.php', 96, '2022-02-24 13:24:07'),
(11, 'debug', 'query', '[debug]-[query]-[logger.php@96]balance data: {\"username\":\"martin\",\"password\":\"$2y$10$zp5BCOh9q1dCs2Gf5HLtre3ZJo5rWMH0O\\/nQbSqLYDC4u0EnAyFUW\",\"account_type\":\"user\",\"id\":1,\"Shares\":0,\"balance\":\"85.0\",\"rate\":\"0.0\",\"Share_Distributed\":0,\"email\":\"minhvuha1909@gmail.com\",\"billing_address\":\"2240\",\"Full_name\":\"Vu Ha (Martin)\",\"City\":\"Calgary\",\"State\":\"AB\",\"ZIP\":\"T2N\",\"Card_number\":\"1111-2222-3333-4444\",\"Transit_no\":\"12345\",\"Inst_no\":\"123\",\"Account_no\":\"12345678\",\"Swift\":\"AAAABBCC\",\"price_per_share\":\"0.0\",\"Monthly_shareholder\":0,\"Income\":\"0.0\",\"Market_cap\":\"0.0\",\"shares_repurchase\":0}\r\n', 'logger.php', 96, '2022-02-24 13:24:07'),
(12, 'debug', 'query', '[debug]-[query]-[logger.php@96]searchSharesInArtistShareHolders returned 1 entries\r\n', 'logger.php', 96, '2022-02-24 13:24:07'),
(13, 'debug', 'query', '[debug]-[query]-[logger.php@96]shares_owned data: {\"shares_owned\":0}\r\n', 'logger.php', 96, '2022-02-24 13:24:07'),
(14, 'debug', 'query', '[debug]-[query]-[logger.php@96]searchAccount returned 1 entries\r\n', 'logger.php', 96, '2022-02-24 13:24:07'),
(15, 'debug', 'query', '[debug]-[query]-[logger.php@96]price_per_share data: {\"username\":\"Al Lure\",\"password\":\"$2y$10$5fzV7dVBevglni99U3TnOePLaH07Hg75gJvsqdhtF9hKAq4QbGjHm\",\"account_type\":\"artist\",\"id\":100,\"Shares\":20,\"balance\":\"200.0\",\"rate\":\"0.0\",\"Share_Distributed\":20,\"email\":\"123@gmail.com\",\"billing_address\":\"\",\"Full_name\":\"\",\"City\":\"\",\"State\":\"\",\"ZIP\":\"\",\"Card_number\":\"\",\"Transit_no\":\"\",\"Inst_no\":\"\",\"Account_no\":\"\",\"Swift\":\"\",\"price_per_share\":\"5.5\",\"Monthly_shareholder\":0,\"Income\":\"0.0\",\"Market_cap\":\"110.0\",\"shares_repurchase\":0}\r\n', 'logger.php', 96, '2022-02-24 13:24:07'),
(16, 'debug', 'query', '[debug]-[query]-[logger.php@96]searchNumberOfShareDistributed returned 1 entries\r\n', 'logger.php', 96, '2022-02-24 13:24:07'),
(17, 'debug', 'query', '[debug]-[query]-[logger.php@96]ret data: {\"Share_Distributed\":20}\r\n', 'logger.php', 96, '2022-02-24 13:24:08'),
(18, 'debug', 'helper', '[debug]-[helper]-[logger.php@96]artist_share_distributed is 20\r\n', 'logger.php', 96, '2022-02-24 13:24:08'),
(19, 'debug', 'query', '[debug]-[query]-[logger.php@96]searchSharesInArtistShareHolders returned 1 entries\r\n', 'logger.php', 96, '2022-02-24 13:24:08'),
(20, 'debug', 'query', '[debug]-[query]-[logger.php@96]shares_owned data: {\"shares_owned\":0}\r\n', 'logger.php', 96, '2022-02-24 13:24:08'),
(21, 'debug', 'helper', '[debug]-[helper]-[logger.php@96]num_of_shares_invested is 0\r\n', 'logger.php', 96, '2022-02-24 13:24:08'),
(22, 'debug', 'helper', '[debug]-[helper]-[logger.php@96]searchAccount returned 1 rows\r\n', 'logger.php', 96, '2022-02-24 13:24:09'),
(23, 'debug', 'query', '[debug]-[query]-[logger.php@96]balance data: {\"username\":\"martin\",\"password\":\"$2y$10$zp5BCOh9q1dCs2Gf5HLtre3ZJo5rWMH0O\\/nQbSqLYDC4u0EnAyFUW\",\"account_type\":\"user\",\"id\":1,\"Shares\":0,\"balance\":\"85.0\",\"rate\":\"0.0\",\"Share_Distributed\":0,\"email\":\"minhvuha1909@gmail.com\",\"billing_address\":\"2240\",\"Full_name\":\"Vu Ha (Martin)\",\"City\":\"Calgary\",\"State\":\"AB\",\"ZIP\":\"T2N\",\"Card_number\":\"1111-2222-3333-4444\",\"Transit_no\":\"12345\",\"Inst_no\":\"123\",\"Account_no\":\"12345678\",\"Swift\":\"AAAABBCC\",\"price_per_share\":\"0.0\",\"Monthly_shareholder\":0,\"Income\":\"0.0\",\"Market_cap\":\"0.0\",\"shares_repurchase\":0}\r\n', 'logger.php', 96, '2022-02-24 13:24:09'),
(24, 'debug', 'query', '[debug]-[query]-[logger.php@96]searchAccount returned 1 entries\r\n', 'logger.php', 96, '2022-02-24 13:24:09'),
(25, 'debug', 'query', '[debug]-[query]-[logger.php@96]price_per_share data: {\"username\":\"Al Lure\",\"password\":\"$2y$10$5fzV7dVBevglni99U3TnOePLaH07Hg75gJvsqdhtF9hKAq4QbGjHm\",\"account_type\":\"artist\",\"id\":100,\"Shares\":20,\"balance\":\"200.0\",\"rate\":\"0.0\",\"Share_Distributed\":20,\"email\":\"123@gmail.com\",\"billing_address\":\"\",\"Full_name\":\"\",\"City\":\"\",\"State\":\"\",\"ZIP\":\"\",\"Card_number\":\"\",\"Transit_no\":\"\",\"Inst_no\":\"\",\"Account_no\":\"\",\"Swift\":\"\",\"price_per_share\":\"5.5\",\"Monthly_shareholder\":0,\"Income\":\"0.0\",\"Market_cap\":\"110.0\",\"shares_repurchase\":0}\r\n', 'logger.php', 96, '2022-02-24 13:24:09'),
(26, 'debug', 'query', '[debug]-[query]-[logger.php@96]searchNumberOfShareDistributed returned 1 entries\r\n', 'logger.php', 96, '2022-02-24 13:24:09'),
(27, 'debug', 'query', '[debug]-[query]-[logger.php@96]share_distributed data: {\"Share_Distributed\":20}\r\n', 'logger.php', 96, '2022-02-24 13:24:09'),
(28, 'debug', 'query', '[debug]-[query]-[logger.php@96]searchSharesInArtistShareHolders returned 1 entries\r\n', 'logger.php', 96, '2022-02-24 13:24:09'),
(29, 'debug', 'query', '[debug]-[query]-[logger.php@96]shares_owned data: {\"shares_owned\":0}\r\n', 'logger.php', 96, '2022-02-24 13:24:09'),
(30, 'debug', 'query', '[debug]-[query]-[logger.php@96]searchAccount returned 1 entries\r\n', 'logger.php', 96, '2022-02-24 13:24:12'),
(31, 'debug', 'query', '[debug]-[query]-[logger.php@96]price_per_share data: {\"username\":\"Al Lure\",\"password\":\"$2y$10$5fzV7dVBevglni99U3TnOePLaH07Hg75gJvsqdhtF9hKAq4QbGjHm\",\"account_type\":\"artist\",\"id\":100,\"Shares\":20,\"balance\":\"200.0\",\"rate\":\"0.0\",\"Share_Distributed\":20,\"email\":\"123@gmail.com\",\"billing_address\":\"\",\"Full_name\":\"\",\"City\":\"\",\"State\":\"\",\"ZIP\":\"\",\"Card_number\":\"\",\"Transit_no\":\"\",\"Inst_no\":\"\",\"Account_no\":\"\",\"Swift\":\"\",\"price_per_share\":\"5.5\",\"Monthly_shareholder\":0,\"Income\":\"0.0\",\"Market_cap\":\"110.0\",\"shares_repurchase\":0}\r\n', 'logger.php', 96, '2022-02-24 13:24:12'),
(32, 'debug', 'helper', '[debug]-[helper]-[logger.php@96]searchAccount returned 1 rows\r\n', 'logger.php', 96, '2022-02-24 13:24:12'),
(33, 'debug', 'query', '[debug]-[query]-[logger.php@96]balance data: {\"username\":\"martin\",\"password\":\"$2y$10$zp5BCOh9q1dCs2Gf5HLtre3ZJo5rWMH0O\\/nQbSqLYDC4u0EnAyFUW\",\"account_type\":\"user\",\"id\":1,\"Shares\":0,\"balance\":\"85.0\",\"rate\":\"0.0\",\"Share_Distributed\":0,\"email\":\"minhvuha1909@gmail.com\",\"billing_address\":\"2240\",\"Full_name\":\"Vu Ha (Martin)\",\"City\":\"Calgary\",\"State\":\"AB\",\"ZIP\":\"T2N\",\"Card_number\":\"1111-2222-3333-4444\",\"Transit_no\":\"12345\",\"Inst_no\":\"123\",\"Account_no\":\"12345678\",\"Swift\":\"AAAABBCC\",\"price_per_share\":\"0.0\",\"Monthly_shareholder\":0,\"Income\":\"0.0\",\"Market_cap\":\"0.0\",\"shares_repurchase\":0}\r\n', 'logger.php', 96, '2022-02-24 13:24:12'),
(34, 'debug', 'buy_shares', '[debug]-[buy_shares]-[logger.php@96]request quantity: 7, request price: 5.5\n\n\r\n', 'logger.php', 96, '2022-02-24 13:24:12'),
(35, 'debug', 'query', '[debug]-[query]-[logger.php@96]searchMatchingSellOrderNoLimitStop returned 0 entries\r\n', 'logger.php', 96, '2022-02-24 13:24:12'),
(36, 'debug', 'buy_shares', '[debug]-[buy_shares]-[logger.php@96]Request quantity before returning: 7\n\r\n', 'logger.php', 96, '2022-02-24 13:24:12'),
(37, 'info', 'buy_shares', '[info]-[buy_shares]-[logger.php@54]Buy order posted by user martin\r\n', 'logger.php', 54, '2022-02-24 13:24:12'),
(38, 'debug', 'helper', '[debug]-[helper]-[logger.php@96]searchAccount returned 1 rows\r\n', 'logger.php', 96, '2022-02-24 13:24:13'),
(39, 'debug', 'query', '[debug]-[query]-[logger.php@96]searchAccount returned 1 entries\r\n', 'logger.php', 96, '2022-02-24 13:24:13'),
(40, 'debug', 'query', '[debug]-[query]-[logger.php@96]price_per_share data: {\"username\":\"Al Lure\",\"password\":\"$2y$10$5fzV7dVBevglni99U3TnOePLaH07Hg75gJvsqdhtF9hKAq4QbGjHm\",\"account_type\":\"artist\",\"id\":100,\"Shares\":20,\"balance\":\"200.0\",\"rate\":\"0.0\",\"Share_Distributed\":20,\"email\":\"123@gmail.com\",\"billing_address\":\"\",\"Full_name\":\"\",\"City\":\"\",\"State\":\"\",\"ZIP\":\"\",\"Card_number\":\"\",\"Transit_no\":\"\",\"Inst_no\":\"\",\"Account_no\":\"\",\"Swift\":\"\",\"price_per_share\":\"5.5\",\"Monthly_shareholder\":0,\"Income\":\"0.0\",\"Market_cap\":\"110.0\",\"shares_repurchase\":0}\r\n', 'logger.php', 96, '2022-02-24 13:24:13'),
(41, 'debug', 'query', '[debug]-[query]-[logger.php@96]searchAccount returned 1 entries\r\n', 'logger.php', 96, '2022-02-24 13:24:13'),
(42, 'debug', 'query', '[debug]-[query]-[logger.php@96]price_per_share data: {\"username\":\"Al Lure\",\"password\":\"$2y$10$5fzV7dVBevglni99U3TnOePLaH07Hg75gJvsqdhtF9hKAq4QbGjHm\",\"account_type\":\"artist\",\"id\":100,\"Shares\":20,\"balance\":\"200.0\",\"rate\":\"0.0\",\"Share_Distributed\":20,\"email\":\"123@gmail.com\",\"billing_address\":\"\",\"Full_name\":\"\",\"City\":\"\",\"State\":\"\",\"ZIP\":\"\",\"Card_number\":\"\",\"Transit_no\":\"\",\"Inst_no\":\"\",\"Account_no\":\"\",\"Swift\":\"\",\"price_per_share\":\"5.5\",\"Monthly_shareholder\":0,\"Income\":\"0.0\",\"Market_cap\":\"110.0\",\"shares_repurchase\":0}\r\n', 'logger.php', 96, '2022-02-24 13:24:13'),
(43, 'debug', 'helper', '[debug]-[helper]-[logger.php@96]searchAccount returned 1 rows\r\n', 'logger.php', 96, '2022-02-24 13:24:13'),
(44, 'debug', 'query', '[debug]-[query]-[logger.php@96]balance data: {\"username\":\"martin\",\"password\":\"$2y$10$zp5BCOh9q1dCs2Gf5HLtre3ZJo5rWMH0O\\/nQbSqLYDC4u0EnAyFUW\",\"account_type\":\"user\",\"id\":1,\"Shares\":0,\"balance\":\"85.0\",\"rate\":\"0.0\",\"Share_Distributed\":0,\"email\":\"minhvuha1909@gmail.com\",\"billing_address\":\"2240\",\"Full_name\":\"Vu Ha (Martin)\",\"City\":\"Calgary\",\"State\":\"AB\",\"ZIP\":\"T2N\",\"Card_number\":\"1111-2222-3333-4444\",\"Transit_no\":\"12345\",\"Inst_no\":\"123\",\"Account_no\":\"12345678\",\"Swift\":\"AAAABBCC\",\"price_per_share\":\"0.0\",\"Monthly_shareholder\":0,\"Income\":\"0.0\",\"Market_cap\":\"0.0\",\"shares_repurchase\":0}\r\n', 'logger.php', 96, '2022-02-24 13:24:13'),
(45, 'debug', 'helper', '[debug]-[helper]-[logger.php@96]getUserBalance returned 85 as a result\r\n', 'logger.php', 96, '2022-02-24 13:24:13');

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
(44, 'Al Lure', 20, 'IPO', '2022-02-21 17:56:32');

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

--
-- Dumping data for table `sell_history`
--

INSERT INTO `sell_history` (`id`, `seller_username`, `buyer_username`, `artist_username`, `amount_sold`, `price_sold`, `date_sold`) VALUES
(24, 'Al Lure', 'riley', 'Al Lure', 10, '10.0', '2022-02-21 18:22:42'),
(25, 'Al Lure', 'martin', 'Al Lure', 10, '10.0', '2022-02-21 18:25:12'),
(26, 'martin', 'kai', 'Al Lure', 5, '10.0', '2022-02-21 18:28:08'),
(27, 'riley', 'kai', 'Al Lure', 5, '10.0', '2022-02-21 18:29:04'),
(28, 'kai', 'vitor', 'Al Lure', 5, '10.0', '2022-02-21 18:32:00'),
(29, 'vitor', 'martin', 'Al Lure', 2, '10.0', '2022-02-22 18:05:58'),
(30, 'martin', 'kai', 'Al Lure', 1, '10.0', '2022-02-22 18:12:42'),
(31, 'martin', 'kai', 'Al Lure', 2, '10.0', '2022-02-22 18:16:08'),
(32, 'martin', 'kai', 'Al Lure', 2, '10.0', '2022-02-22 18:27:10'),
(33, 'riley', 'vitor', 'Al Lure', 5, '10.0', '2022-02-22 18:27:46'),
(34, 'kai', 'vitor', 'Al Lure', 2, '10.0', '2022-02-22 18:32:05'),
(35, 'kai', 'martin', 'Al Lure', 7, '10.0', '2022-02-22 18:32:16'),
(36, 'vitor', 'martin', 'Al Lure', 1, '10.0', '2022-02-22 18:32:32'),
(37, 'vitor', 'riley', 'Al Lure', 3, '10.0', '2022-02-22 18:32:58'),
(38, 'martin', 'riley', 'Al Lure', 4, '10.0', '2022-02-22 18:34:54'),
(39, 'martin', 'kai', 'Al Lure', 6, '10.0', '2022-02-22 18:34:54'),
(40, 'riley', 'kai', 'Al Lure', 3, '10.0', '2022-02-22 18:35:23'),
(41, 'riley', 'martin', 'Al Lure', 1, '10.0', '2022-02-22 18:36:30'),
(42, 'vitor', 'martin', 'Al Lure', 6, '10.0', '2022-02-22 18:36:42'),
(43, 'riley', 'martin', 'Al Lure', 3, '10.0', '2022-02-22 18:36:51'),
(44, 'kai', 'vitor', 'Al Lure', 1, '10.0', '2022-02-22 18:40:19'),
(45, 'kai', 'vitor', 'Al Lure', 4, '10.0', '2022-02-22 18:47:02'),
(46, 'kai', 'vitor', 'Al Lure', 4, '10.0', '2022-02-22 18:47:14'),
(47, 'martin', 'vitor', 'Al Lure', 1, '10.0', '2022-02-22 18:47:49'),
(48, 'martin', 'kai', 'Al Lure', 1, '10.0', '2022-02-22 20:52:36'),
(49, 'martin', 'kai', 'Al Lure', 1, '10.0', '2022-02-22 20:54:50'),
(50, 'martin', 'kai', 'Al Lure', 3, '11.0', '2022-02-22 21:21:48'),
(51, 'martin', 'kai', 'Al Lure', 1, '5.5', '2022-02-22 22:10:21'),
(52, 'martin', 'kai', 'Al Lure', 3, '5.5', '2022-02-23 16:00:23');

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
-- Indexes for table `campaign_participant`
--
ALTER TABLE `campaign_participant`
  ADD PRIMARY KEY (`user_username`,`campaign_id`),
  ADD KEY `campaign_part_id` (`campaign_id`);

--
-- Indexes for table `hx_log`
--
ALTER TABLE `hx_log`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `buy_history`
--
ALTER TABLE `buy_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `buy_order`
--
ALTER TABLE `buy_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `campaign`
--
ALTER TABLE `campaign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `hx_log`
--
ALTER TABLE `hx_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `inject_history`
--
ALTER TABLE `inject_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `sell_history`
--
ALTER TABLE `sell_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `sell_order`
--
ALTER TABLE `sell_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=233;

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
-- Constraints for table `campaign_participant`
--
ALTER TABLE `campaign_participant`
  ADD CONSTRAINT `campaign_part_id` FOREIGN KEY (`campaign_id`) REFERENCES `campaign` (`id`),
  ADD CONSTRAINT `campaign_part_user` FOREIGN KEY (`user_username`) REFERENCES `account` (`username`);

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

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `log_artist_pps` ON SCHEDULE EVERY 5 SECOND STARTS '2022-02-22 22:00:10' ON COMPLETION NOT PRESERVE ENABLE DO CALL log_artist_pps$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
