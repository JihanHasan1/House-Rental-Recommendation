-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2023 at 05:43 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rentdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `flats`
--

CREATE TABLE `flats` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `posted_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flats`
--

INSERT INTO `flats` (`id`, `name`, `location`, `price`, `contact_number`, `image_path`, `posted_by`) VALUES
(10, 'Flat-1', 'Gulshan', 10000.00, '01547896523', 'uploads/64fac1d133119.jpg', 'Jihan'),
(11, 'Flat-2', 'Banani', 15000.00, '01856325478', 'uploads/64fac423a44e9.jpg', 'Jihan'),
(12, 'Flat-3', 'Baridhara', 20000.00, '01865247895', 'uploads/64fac463268ba.jpg', 'Jihan'),
(13, 'Flat-4', 'Banani', 12000.00, '01789654126', 'uploads/64fac53977880.jpg', 'Hasan'),
(14, 'Flat-5', 'Gulshan', 16000.00, '01875421325', 'uploads/64fac6534a3db.jpg', 'Hasan'),
(15, 'Flat-6', 'Banani', 24000.00, '01874521456', 'uploads/64fac7d611bd2.jpg', 'Hasan'),
(16, 'Flat-7', 'Gulshan', 23000.00, '01847568521', 'uploads/64fad06dbe619.jpg', 'Jihan'),
(17, 'Flat-8', 'Baridhara', 21000.00, '01963254125', 'uploads/64fad0943789c.jpg', 'Jihan'),
(18, 'Flat-8', 'Baridhara', 32000.00, '01876456263', 'uploads/64fadea8e0620.jpg', 'Jihan'),
(19, 'Flat-9', 'Khilgaon', 26500.00, '01672565432', 'uploads/64fadf4ee5f77.jpg', 'Jihan'),
(20, 'Flat-10', 'Bagicha', 21500.00, '01888888888', 'uploads/64fae055aa97c.jpg', 'foyej');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(6, 'Jihan', 'jihan@gmail.com', '$2y$10$VHac9I1SzpLgsjkBELGYoeV/MVk/afXfq0ypUi1GR0skaic.kJJZa'),
(7, 'Hasan', 'hasan@gmail.com', '$2y$10$1kcgcWKol1GQ1tGkZ7TK/uwh2ZrygsQfsp.baTduErBpWMHY.B3UK'),
(8, 'foyej ', 'salehin@gmail.com', '$2y$10$WCGqjVWxHPXHsawOG6M36ekqS9lwB45DypehDu6MQUWrEbCC82O5.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `flats`
--
ALTER TABLE `flats`
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
-- AUTO_INCREMENT for table `flats`
--
ALTER TABLE `flats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
