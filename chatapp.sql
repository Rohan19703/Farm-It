-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2025 at 07:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chatapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` int(11) NOT NULL,
  `outgoing_msg_id` int(11) NOT NULL,
  `msg` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`, `timestamp`) VALUES
(1, 471279432, 890774977, 'hi', '2025-01-29 17:48:20'),
(2, 128330687, 890774977, 'hi', '2025-01-29 17:48:32'),
(3, 1486804786, 890774977, 'hi', '2025-01-29 17:48:40'),
(4, 1486804786, 890774977, 'https://www.google.com/maps?q=19.0349312,72.82688', '2025-01-29 17:48:46'),
(5, 1486804786, 890774977, 'https://www.google.com/maps?q=19.0349312,72.82688', '2025-01-29 17:49:29'),
(6, 471279432, 890774977, 'https://www.google.com/maps?q=19.0349312,72.82688', '2025-01-29 17:59:29'),
(7, 128330687, 890774977, 'https://www.google.com/maps?q=19.0349312,72.82688', '2025-01-29 18:01:21'),
(8, 128330687, 890774977, 'https://www.google.com/maps?q=19.0349312,72.82688', '2025-01-29 18:04:30'),
(9, 471279432, 890774977, 'https://www.google.com/maps?q=19.0332306,73.0274353', '2025-01-29 18:08:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `unique_id` int(200) NOT NULL,
  `fname` varchar(80) NOT NULL,
  `lname` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL,
  `img` varchar(80) NOT NULL,
  `status` varchar(20) NOT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `selected_crops` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `unique_id`, `fname`, `lname`, `email`, `password`, `img`, `status`, `qr_code`, `selected_crops`) VALUES
(18, 890774977, 'rohan', '(vendor)', 'rohan@gmail.com', '111', '1730037471Screenshot 2024-10-04 100628.png', 'Active now', '1730037471qr.png', 'Potato,Onion,Tomato'),
(19, 471279432, 'vinit', '(farmer)', 'vinitsurve4@gmail.com', '111', '1730037503Screenshot 2024-10-20 140559.png', 'Active now', '1730037503qr.png', NULL),
(20, 128330687, 'sahil', '(farmer)', 'sahil@gmail.com', '111', '1730037556Screenshot 2024-10-20 140526.png', 'Active now', '1730037556qr.png', NULL),
(21, 1486804786, 'sushant', '(vendor)', 'sushant@gmail.com', '111', '1730037591Screenshot 2024-10-20 140515.png', 'Active now', '1730037591qr.png', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
