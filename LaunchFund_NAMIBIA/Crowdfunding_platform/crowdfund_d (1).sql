-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2025 at 04:21 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crowdfund_d`
--

-- --------------------------------------------------------

--
-- Table structure for table `business_ideas`
--

CREATE TABLE `business_ideas` (
  `id` int(11) NOT NULL,
  `entrepreneur_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `funding_goal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `poster` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `business_ideas`
--

INSERT INTO `business_ideas` (`id`, `entrepreneur_id`, `user_id`, `category`, `title`, `description`, `funding_goal`, `created_at`, `poster`, `video`, `document`, `status`) VALUES
(72, NULL, 23, NULL, 'oooo', '1111', '1111.00', '2025-08-03 14:13:52', '688f6ea0bd7ee_Evans.jpg', 'uploads/videos/688f6ea0bdc75_15c452b065473cc06063de49b37f289a_1736873960047.mp4', '688f6ea0bda3f_Software Documentation Template.pdf', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `comment_replies`
--

CREATE TABLE `comment_replies` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `reply` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `idea_comments`
--

CREATE TABLE `idea_comments` (
  `id` int(11) NOT NULL,
  `idea_id` int(11) NOT NULL,
  `supporter_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reply` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `idea_comments`
--

INSERT INTO `idea_comments` (`id`, `idea_id`, `supporter_id`, `comment`, `created_at`, `reply`) VALUES
(7, 72, 24, 'wosieow', '2025-08-03 14:18:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pledges`
--

CREATE TABLE `pledges` (
  `id` int(11) NOT NULL,
  `idea_id` int(11) NOT NULL,
  `supporter_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `pledged_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pledges`
--

INSERT INTO `pledges` (`id`, `idea_id`, `supporter_id`, `amount`, `pledged_at`) VALUES
(30, 72, 24, '898.00', '2025-08-03 14:16:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('entrepreneur','supporter') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Joel Dila', 'jol@gmail.com', 'hashedpassword', 'entrepreneur', '2025-08-01 18:41:59'),
(20, 'Nghipunya', 'Besta@gmail.com', '$2y$10$pJZt8AoDAdI/QkCkNVnnfeUpbFwf.4UuUmZ2syOhSTKrUgMvp6/G6', 'supporter', '2025-08-01 19:27:06'),
(21, 'Julia Gabriel', 'gabraiel@gmail.com', '$2y$10$r8X7TsnEJJzWb4TA4SlmwOPASdnbj7Jcon.DjlsBz1VHxO5MslmEu', 'entrepreneur', '2025-08-01 19:30:14'),
(22, 'Ananias Mokaxwa', 'anani@gmail.com', '$2y$10$xtNMLZT4654NDSf5Pq9AReKFUqe1sI8L.9y3iZkVNMceO/enjgJY6', 'entrepreneur', '2025-08-02 04:46:44'),
(23, 'Kadhila Levi', 'livi@gmail.com', '$2y$10$p5Aex0xWE5r32N2fGKXUe.r6bp5y.TSpSbRScu8/.s8om9/7cMswy', 'entrepreneur', '2025-08-02 06:11:41'),
(24, 'Joel Dila', 'dj@gmail.com', '$2y$10$.CZELOBFzHoC.jAxSoicO.h.gFGhIRht5Wz1SeuwBxwxYDSq.ZDd6', 'supporter', '2025-08-02 07:34:37'),
(25, 'Desiree', 'desireesimasiku@gmail.com', '$2y$10$yklgsYPa8/gqYT8pCw.O8uY977YIWfDtGAG/Xr0DRD5v2wQKuTl4C', 'entrepreneur', '2025-08-02 10:43:13'),
(26, 'michael', 'michael@gmail.com', '$2y$10$WkbDq6.nHLPSbyTmtrYme.LwYHCdHrD9rQeJP1aLJ9jxcRrslTv/O', 'supporter', '2025-08-02 11:31:34'),
(27, 'Goerge Linus', 'linus@gmail.com', '$2y$10$Ox/lWsyqM/XdFH5JRw82m..YW/GlrAe0hLg6l1VhqjO7IFI1cKUna', 'entrepreneur', '2025-08-02 20:08:21'),
(28, 'Daniel Linis', 'daniel@gmail.com', '$2y$10$6ZQSeU.53Uq.DWNmuZra6O1k1QIA.N8eBIBpxR8hUraMKKj6sxLnC', 'supporter', '2025-08-03 06:37:25'),
(32, 'Daniel Linis', 'dani@gmail.com', '$2y$10$/QYIKr84lHgH5MPLea39be6z1WTbTagGKyrDXvC6vXMsQ4b8yPLYG', 'entrepreneur', '2025-08-03 06:38:22'),
(33, 'Erastus Beata', 'beata@gmail.com', '$2y$10$eHRt/r7b3JLVRQ2I2/O8/.szAXtNduGoJbyt8u8yz2LG96hQuQJz2', 'entrepreneur', '2025-08-03 10:37:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `business_ideas`
--
ALTER TABLE `business_ideas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `comment_replies`
--
ALTER TABLE `comment_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_id` (`comment_id`);

--
-- Indexes for table `idea_comments`
--
ALTER TABLE `idea_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idea_id` (`idea_id`),
  ADD KEY `supporter_id` (`supporter_id`);

--
-- Indexes for table `pledges`
--
ALTER TABLE `pledges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idea_id` (`idea_id`),
  ADD KEY `supporter_id` (`supporter_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `business_ideas`
--
ALTER TABLE `business_ideas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `comment_replies`
--
ALTER TABLE `comment_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `idea_comments`
--
ALTER TABLE `idea_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pledges`
--
ALTER TABLE `pledges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `business_ideas`
--
ALTER TABLE `business_ideas`
  ADD CONSTRAINT `business_ideas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comment_replies`
--
ALTER TABLE `comment_replies`
  ADD CONSTRAINT `comment_replies_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `idea_comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `idea_comments`
--
ALTER TABLE `idea_comments`
  ADD CONSTRAINT `idea_comments_ibfk_1` FOREIGN KEY (`idea_id`) REFERENCES `business_ideas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `idea_comments_ibfk_2` FOREIGN KEY (`supporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pledges`
--
ALTER TABLE `pledges`
  ADD CONSTRAINT `pledges_ibfk_1` FOREIGN KEY (`idea_id`) REFERENCES `business_ideas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pledges_ibfk_2` FOREIGN KEY (`supporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
