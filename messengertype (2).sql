-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2020 at 11:38 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `messengertype`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `text`, `post_id`, `user_id`, `added_on`) VALUES
(1, 'wssup biroo', 4, 16, '2020-08-24 06:56:01'),
(2, 'hello', 4, 16, '2020-08-24 07:02:33'),
(3, 'wssup buddy', 4, 19, '2020-08-24 07:29:24'),
(4, 'yo biro', 2, 19, '2020-08-24 07:29:39'),
(16, 'heyaa', 5, 19, '2020-09-01 07:20:06');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`) VALUES
(3, 16, 4),
(4, 19, 4),
(5, 19, 2),
(6, 16, 3),
(7, 16, 2),
(9, 16, 1),
(11, 19, 7),
(12, 19, 8),
(13, 16, 5),
(14, 16, 9),
(18, 19, 10),
(19, 19, 5),
(20, 16, 7);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `from_id`, `to_id`, `message`, `status`) VALUES
(1, 1, 1, 'hi user1', 1),
(2, 1, 3, 'hi user3', 1),
(3, 3, 1, 'hi bro', 1),
(4, 1, 1, 'orkkrh', 1),
(5, 1, 3, 'jnj', 1),
(6, 3, 1, 'lkknsd', 1),
(7, 3, 1, 'asda', 1),
(8, 1, 1, 'helllo', 1),
(9, 1, 1, 'askndk,ansdasdnansdknaskdnkassndkasndknaskdnkassndkasnsdknasd', 1),
(10, 1, 7, 'hello', 1),
(11, 4, 1, 'hi hello biroo', 1),
(12, 1, 4, 'wssup babe', 0),
(13, 1, 4, 'wssup babe', 0),
(14, 14, 15, 'bhen k lode', 1),
(15, 19, 16, 'hello biroo', 1),
(16, 19, 19, 'fucko off', 1),
(17, 16, 19, 'dhtt gendu', 0);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `reciever` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `added_on` datetime NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `reciever`, `sender`, `added_on`, `status`) VALUES
(1, 1, 16, 19, '0000-00-00 00:00:00', 1),
(2, 1, 19, 16, '2020-08-26 09:44:14', 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `added_on` datetime NOT NULL,
  `likes` int(11) NOT NULL,
  `comments` int(11) NOT NULL,
  `tag` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `text`, `image`, `added_on`, `likes`, `comments`, `tag`) VALUES
(5, 16, 'Dk', '1598296124.png', '2020-08-24 09:08:44', 2, -1, 0),
(7, 19, 'nothing', '1598365623.png', '2020-08-25 04:27:03', 2, 0, 0),
(8, 19, 'whtssup', '1598365677.png', '2020-08-25 04:27:57', 1, 0, 16),
(10, 16, 'test1', '1598427854.jpeg', '2020-08-26 09:44:25', 1, 0, 19);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` bigint(20) NOT NULL,
  `profile_photo` varchar(255) NOT NULL DEFAULT 'blank.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `last_login`, `profile_photo`) VALUES
(16, 'dikshant', 'dikshant', '$2y$10$9IayGE0JJB.zrZBpooQrHOGxJKodLdov/UOJaA9J.Meeno58ABr/2', 1598979008, 'dikshant1598343528.png'),
(19, 'user1', 'user1', '$2y$10$9ng77cRYlAaNwnadN9wSbebGiA5QuqnyBQKmG4nHB4byoNdxqwNeW', 1599154683, 'user11599112266.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
