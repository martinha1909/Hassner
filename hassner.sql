-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2021 at 10:04 PM
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
-- Database: `spotify`
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
  `balance` double NOT NULL,
  `rate` double NOT NULL,
  `Share_Distributed` int(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `billing_address` varchar(20) NOT NULL,
  `Full_name` varchar(20) NOT NULL,
  `City` varchar(20) NOT NULL,
  `State` varchar(20) NOT NULL,
  `ZIP` varchar(6) NOT NULL,
  `Card_number` varchar(20) NOT NULL,
  `Transit_no` varchar(5) NOT NULL,
  `Inst_no` varchar(3) NOT NULL,
  `Account_no` varchar(7) NOT NULL,
  `Swift` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`username`, `password`, `account_type`, `id`, `Shares`, `balance`, `rate`, `Share_Distributed`, `email`, `billing_address`, `Full_name`, `City`, `State`, `ZIP`, `Card_number`, `Transit_no`, `Inst_no`, `Account_no`, `Swift`) VALUES
('88Glam', 'artist', 'artist', 1, 6, 0, 0.30000000000000016, 8, '', '', '0', '', '', '', '', '', '', '', ''),
('admin', 'admin', 'admin', 6, 0, 0, 0, 0, '', '', '0', '', '', '', '', '', '', '', ''),
('admin0', 'admin', 'admin', 17, 0, 0, 0, 0, '', '', '0', '', '', '', '', '', '', '', ''),
('ayush', 'test', 'user', 4, 0, 0, 0, 0, '', '', '0', '', '', '', '', '', '', '', ''),
('chris', 'user', 'user', 5, 0, 0, 0, 0, '', '', '0', '', '', '', '', '', '', '', ''),
('Drake', 'artist', 'artist', 14, 0, 0, -0.02, 0, '', '', '0', '', '', '', '', '', '', '', ''),
('Eminem', 'artist', 'artist', 16, 2, 0, 0.126, 5, '', '', '0', '', '', '', '', '', '', '', ''),
('FRVRFRIDAY', 'artist', 'artist', 15, 0, 0, 0.013, 0, '', '', '0', '', '', '', '', '', '', '', ''),
('Kanye West', 'artist', 'artist', 13, 4, 0, 0.026, 8, '', '', '0', '', '', '', '', '', '', '', ''),
('kep', 'admin', 'admin', 12, 0, 0, 0, 0, '', '', '0', '', '', '', '', '', '', '', ''),
('kepwessner', '1234', 'user', 11, 0, 0, 0, 0, '', '', '0', '', '', '', '', '', '', '', ''),
('martin', 'user', 'user', 3, 0, 88292.9472, 0, 0, 'martinvuha1909@gmail.com', '2240', 'Vu Minh Ha', 'Calgary', 'AB', 'T2N3Z4', '1111-2222-3333-4444', '12345', '001', '1234567', 'AAAABBCCDDD'),
('Martin Ha', '123456', 'user', 9, 0, 0, 0, 0, '', '2240 Uxbridge Dr NW', '0', '', '', '', '', '', '', '', ''),
('maya', 'user', 'user', 18, 0, 1055.45, 0, 0, '123@gmail.com', '2240 Uxbridge Dr NW', 'Maya Flynn', 'Calgary', 'AB', 'T2N3Z4', '1111-2222-3333-4444', '', '', '', ''),
('Metro Booming', 'producer', 'producer', 7, 0, 0, 0, 0, '', '', '0', '', '', '', '', '', '', '', ''),
('NAV', 'artist', 'artist', 2, 0, 0, 0, 0, '', '', '0', '', '', '', '', '', '', '', ''),
('Polygon', 'producer', 'producer', 8, 0, 0, 0, 0, '', '', '0', '', '', '', '', '', '', '', ''),
('Travis Scott', 'artist', 'artist', 10, 0, 0, -0.174, 0, '', '', '0', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `album`
--

CREATE TABLE `album` (
  `name` varchar(50) NOT NULL,
  `no_of_songs` int(11) NOT NULL,
  `duration` float NOT NULL,
  `date_created` varchar(10) NOT NULL,
  `Published` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `album`
--

INSERT INTO `album` (`name`, `no_of_songs`, `duration`, `date_created`, `Published`) VALUES
('88Glam 2.5', 0, -0.4, 'April 10th', 1),
('88Glam Reloaded', 0, 0, 'April 14th', 1),
('Close to Heaven Far From God', 0, 0, '2021-05-27', 1),
('Emergency Tsunami', 0, 0, 'December 1', 0),
('Utopia', 1, 3.2, 'April 18th', 0);

-- --------------------------------------------------------

--
-- Table structure for table `album_song`
--

CREATE TABLE `album_song` (
  `album_name` varchar(50) NOT NULL,
  `song_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `artist_album`
--

CREATE TABLE `artist_album` (
  `artist_username` varchar(50) NOT NULL,
  `album_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `artist_album`
--

INSERT INTO `artist_album` (`artist_username`, `album_name`) VALUES
('88Glam', '88Glam 2.5'),
('88Glam', '88Glam Reloaded'),
('88Glam', 'Close to Heaven Far From God'),
('NAV', 'Emergency Tsunami'),
('Travis Scott', 'Utopia');

-- --------------------------------------------------------

--
-- Table structure for table `artist_per_share`
--

CREATE TABLE `artist_per_share` (
  `artist_username` varchar(50) NOT NULL,
  `price_per_share` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `artist_per_share`
--

INSERT INTO `artist_per_share` (`artist_username`, `price_per_share`) VALUES
('88Glam', 44.00000000000002),
('Drake', 1),
('Eminem', 52.400000000000006),
('FRVRFRIDAY', 2.2),
('Kanye West', 3.4000000000000004),
('Metro Booming', 1),
('NAV', 30),
('Travis Scott', 41.2);

-- --------------------------------------------------------

--
-- Table structure for table `artist_song`
--

CREATE TABLE `artist_song` (
  `artist_username` varchar(50) NOT NULL,
  `song_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `artist_song`
--

INSERT INTO `artist_song` (`artist_username`, `song_id`) VALUES
('88Glam', 1),
('88Glam', 2),
('88Glam', 3),
('88Glam', 4),
('88Glam', 12),
('88Glam', 13),
('88Glam', 14),
('NAV', 5),
('NAV', 6),
('Travis Scott', 9);

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `genre1` varchar(10) NOT NULL,
  `genre2` varchar(10) DEFAULT NULL,
  `genre3` varchar(10) DEFAULT NULL,
  `song_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

CREATE TABLE `playlist` (
  `name` varchar(50) NOT NULL,
  `user_username` varchar(50) NOT NULL,
  `no_of_songs` int(11) NOT NULL,
  `duration` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `playlist`
--

INSERT INTO `playlist` (`name`, `user_username`, `no_of_songs`, `duration`) VALUES
('Good playlist', 'martin', 2, 6.2),
('Rytm', 'kepwessner', 0, 0),
('Rytm', 'martin', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `playlist_song`
--

CREATE TABLE `playlist_song` (
  `playlist_name` varchar(50) NOT NULL,
  `song_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `playlist_song`
--

INSERT INTO `playlist_song` (`playlist_name`, `song_id`) VALUES
('Good playlist', 1),
('Good playlist', 2);

-- --------------------------------------------------------

--
-- Table structure for table `producer_song`
--

CREATE TABLE `producer_song` (
  `producer_username` varchar(50) NOT NULL,
  `song_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `producer_song`
--

INSERT INTO `producer_song` (`producer_username`, `song_id`) VALUES
('Metro Booming', 10),
('Metro Booming', 11),
('Polygon', 7);

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `user_username` varchar(50) NOT NULL,
  `song_id` int(11) NOT NULL,
  `comment` varchar(500) NOT NULL,
  `star_rating` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`user_username`, `song_id`, `comment`, `star_rating`) VALUES
('kepwessner', 1, 'This is a great song', 5),
('martin', 1, 'garbage', 0);

-- --------------------------------------------------------

--
-- Table structure for table `song`
--

CREATE TABLE `song` (
  `id` int(11) NOT NULL,
  `album_name` varchar(50) DEFAULT NULL,
  `no_of_plays` int(11) NOT NULL,
  `duration` float NOT NULL,
  `name` varchar(50) NOT NULL,
  `date_created` varchar(50) NOT NULL,
  `Published` int(10) NOT NULL,
  `Monthly_Listeners` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `song`
--

INSERT INTO `song` (`id`, `album_name`, `no_of_plays`, `duration`, `name`, `date_created`, `Published`, `Monthly_Listeners`) VALUES
(1, '88Glam 2.5', 3, 3.2, 'Kitchen Witch', 'April 16th, 2021', 1, 0),
(2, '88Glam 2.5', 1, 3, 'Ricardo', 'April 16th, 2021', 1, 0),
(3, '88Glam 2.5', 0, 3, 'Wet Dreams', 'May 5th, 2021', 1, 0),
(4, '88Glam 2.5', 0, 3.1, 'East to West', 'April 16th', 1, 0),
(5, NULL, 0, 2, 'Habits', 'April 12th', 0, 0),
(6, NULL, 0, 2.4, 'Hit', 'May 5th', 0, 0),
(7, NULL, 0, 4.2, 'Drift Away', 'April 1st', 0, 0),
(9, NULL, 0, 4, 'Antidote', 'April 17', 1, 0),
(10, NULL, 0, 4.2, 'Moovin\' Up', 'April 21st', 0, 0),
(11, NULL, 0, 3.4, 'Space Cadet', 'April 17th', 0, 0),
(12, '88Glam 2.5', 0, 3.4, 'Brand New', 'May 11th, 2021', 1, 0),
(13, NULL, 0, 3, 'swim', '2021-05-27', 1, 0),
(14, NULL, 0, 2, '12', '2021-05-28', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_artist_share`
--

CREATE TABLE `user_artist_share` (
  `user_username` varchar(50) NOT NULL,
  `artist_username` varchar(50) NOT NULL,
  `no_of_share_bought` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_artist_share`
--

INSERT INTO `user_artist_share` (`user_username`, `artist_username`, `no_of_share_bought`) VALUES
('martin', '88Glam', 6),
('martin', 'Eminem', 2),
('martin', 'Kanye West', 4);

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
-- Indexes for table `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `album_song`
--
ALTER TABLE `album_song`
  ADD PRIMARY KEY (`album_name`,`song_id`),
  ADD KEY `song_id_key_album` (`song_id`);

--
-- Indexes for table `artist_album`
--
ALTER TABLE `artist_album`
  ADD PRIMARY KEY (`artist_username`,`album_name`),
  ADD KEY `album_artist_album_key` (`album_name`);

--
-- Indexes for table `artist_per_share`
--
ALTER TABLE `artist_per_share`
  ADD PRIMARY KEY (`artist_username`);

--
-- Indexes for table `artist_song`
--
ALTER TABLE `artist_song`
  ADD PRIMARY KEY (`artist_username`,`song_id`),
  ADD KEY `song_id_key_artist1` (`song_id`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`genre1`),
  ADD KEY `song_id_key` (`song_id`);

--
-- Indexes for table `playlist`
--
ALTER TABLE `playlist`
  ADD PRIMARY KEY (`name`,`user_username`),
  ADD KEY `user_username_key_playlist` (`user_username`);

--
-- Indexes for table `playlist_song`
--
ALTER TABLE `playlist_song`
  ADD PRIMARY KEY (`playlist_name`,`song_id`),
  ADD KEY `song_id_key_playlist1` (`song_id`);

--
-- Indexes for table `producer_song`
--
ALTER TABLE `producer_song`
  ADD PRIMARY KEY (`producer_username`,`song_id`),
  ADD KEY `song_id_keu_producer` (`song_id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`user_username`,`song_id`),
  ADD KEY `song_id_key_rating` (`song_id`);

--
-- Indexes for table `song`
--
ALTER TABLE `song`
  ADD PRIMARY KEY (`id`),
  ADD KEY `album_key` (`album_name`);

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
-- Constraints for table `album_song`
--
ALTER TABLE `album_song`
  ADD CONSTRAINT `album_name_key_song` FOREIGN KEY (`album_name`) REFERENCES `album` (`name`),
  ADD CONSTRAINT `song_id_key_album` FOREIGN KEY (`song_id`) REFERENCES `song` (`id`);

--
-- Constraints for table `artist_album`
--
ALTER TABLE `artist_album`
  ADD CONSTRAINT `album_artist_album_key` FOREIGN KEY (`album_name`) REFERENCES `album` (`name`),
  ADD CONSTRAINT `album_artist_username_key` FOREIGN KEY (`artist_username`) REFERENCES `account` (`username`);

--
-- Constraints for table `artist_per_share`
--
ALTER TABLE `artist_per_share`
  ADD CONSTRAINT `artist_per_share_key` FOREIGN KEY (`artist_username`) REFERENCES `account` (`username`);

--
-- Constraints for table `artist_song`
--
ALTER TABLE `artist_song`
  ADD CONSTRAINT `artist_username_song` FOREIGN KEY (`artist_username`) REFERENCES `account` (`username`),
  ADD CONSTRAINT `song_id_key_artist1` FOREIGN KEY (`song_id`) REFERENCES `song` (`id`);

--
-- Constraints for table `genre`
--
ALTER TABLE `genre`
  ADD CONSTRAINT `song_id_key` FOREIGN KEY (`song_id`) REFERENCES `song` (`id`);

--
-- Constraints for table `playlist`
--
ALTER TABLE `playlist`
  ADD CONSTRAINT `user_username_key_playlist` FOREIGN KEY (`user_username`) REFERENCES `account` (`username`);

--
-- Constraints for table `playlist_song`
--
ALTER TABLE `playlist_song`
  ADD CONSTRAINT `playlist_name_foreign` FOREIGN KEY (`playlist_name`) REFERENCES `playlist` (`name`),
  ADD CONSTRAINT `song_id_key_playlist1` FOREIGN KEY (`song_id`) REFERENCES `song` (`id`);

--
-- Constraints for table `producer_song`
--
ALTER TABLE `producer_song`
  ADD CONSTRAINT `producer_username_key` FOREIGN KEY (`producer_username`) REFERENCES `account` (`username`),
  ADD CONSTRAINT `song_id_keu_producer` FOREIGN KEY (`song_id`) REFERENCES `song` (`id`);

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `song_id_key_rating` FOREIGN KEY (`song_id`) REFERENCES `song` (`id`),
  ADD CONSTRAINT `user_username_key_rating` FOREIGN KEY (`user_username`) REFERENCES `account` (`username`);

--
-- Constraints for table `song`
--
ALTER TABLE `song`
  ADD CONSTRAINT `album_key` FOREIGN KEY (`album_name`) REFERENCES `album` (`name`);

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
