-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2025 at 07:45 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ewaste_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `ewaste_items`
--

CREATE TABLE `ewaste_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_type` varchar(100) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `item_condition` enum('working','partly_working','not_working') DEFAULT 'not_working',
  `quantity` int(11) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','scheduled','collected','recycled','disposed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ewaste_items`
--

INSERT INTO `ewaste_items` (`id`, `user_id`, `item_type`, `brand`, `model`, `item_condition`, `quantity`, `description`, `image_path`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'Laptop', 'dell', 'laptop', 'not_working', 1, 'not s working it\'s a damaged', '../uploads/ew_69195d3240be87.45034246.jpg', 'pending', '2025-11-16 05:12:18', NULL),
(2, 3, 'Laptop', 'dell', 'laptop', 'working', 1, 'ggarggrrggrgr', NULL, 'pending', '2025-11-16 12:09:56', NULL),
(3, 1, 'Laptop', 'dell', 'laptop', 'not_working', 1, 'thf', NULL, 'scheduled', '2025-11-17 06:40:30', '2025-11-17 06:40:59');

-- --------------------------------------------------------

--
-- Table structure for table `pickups`
--

CREATE TABLE `pickups` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pickup_date` date NOT NULL,
  `pickup_time` time NOT NULL,
  `pickup_address` text NOT NULL,
  `status` varchar(20) DEFAULT 'requested',
  `notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pickups`
--

INSERT INTO `pickups` (`id`, `user_id`, `pickup_date`, `pickup_time`, `pickup_address`, `status`, `notes`) VALUES
(1, 1, '2025-11-17', '12:11:00', 'hahhas', 'requested', 'jjsd');

-- --------------------------------------------------------

--
-- Table structure for table `pickup_items`
--

CREATE TABLE `pickup_items` (
  `id` int(11) NOT NULL,
  `pickup_id` int(11) NOT NULL,
  `ewaste_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pickup_items`
--

INSERT INTO `pickup_items` (`id`, `pickup_id`, `ewaste_item_id`, `quantity`) VALUES
(1, 1, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pickup_tracking`
--

CREATE TABLE `pickup_tracking` (
  `id` int(11) NOT NULL,
  `pickup_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `location` varchar(255) NOT NULL,
  `notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pickup_tracking`
--

INSERT INTO `pickup_tracking` (`id`, `pickup_id`, `status`, `location`, `notes`) VALUES
(1, 1, 'requested', '', 'Pickup requested by user');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('user','collector','admin') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `phone`, `address`, `role`, `is_active`, `created_at`) VALUES
(1, 'test user', 'testuser1@2gmail.com', '$2y$10$52SIhCwpbAwf/7QvlxjCnuHtnJUZpkr0nv3dmV0DNprSSTgrFzRiG', NULL, NULL, 'admin', 1, '2025-11-16 04:44:21'),
(2, 'prajwal sawarkar', 'prajwalsawarkar123@gmail.com', '$2y$10$0GyHzikeY/.7Y4G6UDXm/eUbTIpggsowLjwyZ6pH89znyW6eg0mA.', NULL, NULL, 'user', 1, '2025-11-16 04:46:11'),
(3, 'jay chimankar', 'jay@gmail.com', '$2y$10$2VRkeNzlEg27nFPSqyOaC.PFaUWy1acKdul2C5dAUk5qAbm6Uctbi', NULL, NULL, 'collector', 1, '2025-11-16 12:08:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ewaste_items`
--
ALTER TABLE `ewaste_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_items_user` (`user_id`);

--
-- Indexes for table `pickups`
--
ALTER TABLE `pickups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pickup_items`
--
ALTER TABLE `pickup_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pickup_tracking`
--
ALTER TABLE `pickup_tracking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ewaste_items`
--
ALTER TABLE `ewaste_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pickups`
--
ALTER TABLE `pickups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pickup_items`
--
ALTER TABLE `pickup_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pickup_tracking`
--
ALTER TABLE `pickup_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ewaste_items`
--
ALTER TABLE `ewaste_items`
  ADD CONSTRAINT `fk_items_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
