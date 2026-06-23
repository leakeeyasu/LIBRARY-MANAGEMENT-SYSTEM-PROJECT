-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2026 at 07:41 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aku_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `publication_year` year(4) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `available` int(11) DEFAULT 1,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `isbn`, `category`, `publisher`, `publication_year`, `quantity`, `available`, `description`, `cover_image`, `added_by`, `created_at`) VALUES
(1, 'BIOLOGY', 'mendel', '00000000004', 'Biology', 'GB', 2017, 200, 197, 'BIOLOGY BOOK', NULL, 1, '2026-01-03 13:32:19'),
(3, 'maths', 'john', '00000000005', 'Computer Science', 'GB', 2026, 300, 300, 'maths', NULL, 1, '2026-01-03 17:10:03'),
(6, 'WEB PROGRAMMING', 'TEKESTE', '00000000001', 'Computer Science', 'mega', 2026, 100, 100, 'WEB PROGRAMMING', NULL, 1, '2026-01-09 19:27:12'),
(8, 'HISTORY', 'HAILE', '00000000003', 'History', 'mega', 2000, 300, 299, 'AFRICAN HISTORY BOOK!', NULL, 1, '2026-01-17 07:56:02'),
(12, 'web', 'mike', '00000000009', 'Computer Science', 'mega', 2026, 100, 99, 'web programming', NULL, 1, '2026-01-18 06:21:26'),
(13, 'civic', 'cgdhd', '978013235088667', 'History', 'dhjd', 2026, 100, 100, 'cici', NULL, 1, '2026-01-18 06:28:25');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `rating` int(11) DEFAULT NULL
) ;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `message`, `rating`, `created_at`, `is_read`) VALUES
(3, 3, 'good!', 4, '2026-01-09 22:20:31', 0),
(4, 9, 'we need additional time in the weekend?', 3, '2026-01-17 08:08:10', 0),
(5, 3, 'almost good!', 3, '2026-01-18 05:52:38', 0);

-- --------------------------------------------------------

--
-- Table structure for table `issued_books`
--

CREATE TABLE `issued_books` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `issued_by` int(11) NOT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `fine_amount` decimal(10,2) DEFAULT 0.00,
  `status` enum('issued','returned','overdue') DEFAULT 'issued'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `issued_books`
--

INSERT INTO `issued_books` (`id`, `book_id`, `user_id`, `issued_by`, `issue_date`, `due_date`, `return_date`, `fine_amount`, `status`) VALUES
(4, 1, 5, 1, '2026-01-09', '2026-01-23', '2026-01-09', '0.00', 'returned'),
(5, 1, 2, 1, '2026-01-09', '2026-01-23', '2026-01-09', '0.00', 'returned'),
(6, 1, 7, 1, '2026-01-09', '2026-01-23', '2026-01-09', '0.00', 'returned'),
(7, 1, 2, 1, '2026-01-09', '2026-01-23', '2026-01-09', '0.00', 'returned'),
(8, 1, 7, 1, '2026-01-09', '2026-01-23', '2026-01-09', '0.00', 'returned'),
(9, 1, 2, 3, '2026-01-09', '2026-01-23', '2026-01-10', '0.00', 'returned'),
(10, 3, 5, 1, '2026-01-10', '2026-01-24', '2026-01-17', '0.00', 'returned'),
(11, 8, 5, 1, '2026-01-17', '2026-01-31', '2026-01-17', '0.00', 'returned'),
(12, 1, 1, 3, '2026-01-17', '2026-01-31', '2026-01-17', '0.00', 'returned'),
(13, 6, 2, 1, '2026-01-17', '2026-01-31', '2026-01-18', '0.00', 'returned'),
(14, 1, 5, 1, '2026-01-17', '2026-01-31', '2026-01-17', '0.00', 'returned'),
(15, 1, 2, 3, '2026-01-17', '2026-01-31', '2026-01-17', '0.00', 'returned'),
(16, 6, 5, 1, '2026-01-17', '2026-01-31', '2026-01-18', '0.00', 'returned'),
(17, 1, 2, 1, '2026-01-18', '2026-02-01', NULL, '0.00', 'issued'),
(18, 1, 9, 3, '2026-01-18', '2026-02-01', NULL, '0.00', 'issued'),
(19, 1, 7, 3, '2026-01-18', '2026-02-01', NULL, '0.00', 'issued'),
(20, 12, 9, 1, '2026-01-18', '2026-02-01', NULL, '0.00', 'issued'),
(21, 8, 9, 3, '2026-01-18', '2026-02-01', NULL, '0.00', 'issued');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('admin','librarian','student') DEFAULT 'student',
  `profile_photo` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `role`, `profile_photo`, `phone`, `address`, `created_at`, `updated_at`, `is_active`) VALUES
(1, 'admin', 'admin@aku.edu', '$2y$10$lsaZ18b.6N4b4JH22fj1HOmYANcmD6RDPTjy9HRiGK1FsnMkUTm0q', 'Admin', 'admin', '696b4033810f9.png', '', '', '2026-01-03 13:15:37', '2026-01-17 07:54:27', 1),
(2, 'leake', 'leakeeyasu23@gmail.com', '$2y$10$.Q.bGGjrxtkUu8SrJCXhZueTCqpCSsk7ZSQRMChDNrWn/w1W7ZEF2', 'Leake Eyasu', 'librarian', '696c76096a4d3.jpg', '0945893411', 'Axum', '2026-01-03 13:24:52', '2026-01-18 06:17:55', 1),
(3, 'letsh', 'letsh1@aku.et', '$2y$10$kzI94HipstwvoIp6m41G5ugL2qZ4hkLgzn/rBv3du4zjER.2jL6PS', 'letsh', 'librarian', '696b49bbd59a3.png', '0989765400', 'Axum', '2026-01-03 16:40:10', '2026-01-17 08:35:07', 1),
(5, 'yonas', 'yonas@aku.et', '$2y$10$Oj.7Vk1awxd2RcJv.g8DpOzOgYylRf6tQOKagtN58/KuBpmV.37Hq', 'yonas', 'student', '69615ad29989f.jpg', '098987675', 'Axum', '2026-01-07 19:53:08', '2026-01-09 20:36:08', 1),
(7, 'tsegay', 'tsegay@aku.et', '$2y$10$P6JwO1PAO6PsGsmX5k6nB.6xARyBagclCm1M6x13aTjfTHlGiq7Vm', 'tsegay', 'student', 'default.png', '0998909890', 'axum', '2026-01-09 21:28:48', '2026-01-09 21:28:48', 1),
(9, 'melat', 'melat@aku.et', '$2y$10$1Y8m88n5va8pki70jdOV9OOqqhQvM5vl/0iV7q.yAAfcmu3tSem3S', 'melat niguse', 'student', '696b432d51932.jpg', '0923434534', 'adwa', '2026-01-17 08:07:09', '2026-01-17 08:08:29', 1),
(13, 'haile', 'hailea@ku.et', '$2y$10$72Zer.LuziJ69hMBHPvI5OYU5J3zuVbm.//YDpuF2WhhVxXGN/YB.', 'haile teferi', 'student', NULL, '', '', '2026-01-18 06:19:19', '2026-01-18 06:19:19', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `added_by` (`added_by`);

--
-- Indexes for table `issued_books`
--
ALTER TABLE `issued_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `issued_by` (`issued_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issued_books`
--
ALTER TABLE `issued_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `issued_books`
--
ALTER TABLE `issued_books`
  ADD CONSTRAINT `issued_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
  ADD CONSTRAINT `issued_books_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `issued_books_ibfk_3` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
