-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 08, 2022 at 11:43 PM
-- Server version: 5.7.33
-- PHP Version: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `katoo`
--

-- --------------------------------------------------------

--
-- Table structure for table `board_main`
--

CREATE TABLE `board_main` (
  `bm_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `bm_title` varchar(200) NOT NULL,
  `bm_detail` longtext NOT NULL,
  `cg_id` int(11) DEFAULT NULL,
  `bm_img` varchar(200) DEFAULT NULL,
  `bm_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `board_main`
--

INSERT INTO `board_main` (`bm_id`, `u_id`, `bm_title`, `bm_detail`, `cg_id`, `bm_img`, `bm_date`) VALUES
(1, 1, 'Test', 'ttttttt', 1, '142596370_109150097834443_12673755584965935_n.jpg', '2022-08-09 05:35:08'),
(2, 1, 'Test02', 'tttttt', 2, '142596370_109150097834443_12673755584965935_n.jpg', '2022-08-09 06:39:28');

-- --------------------------------------------------------

--
-- Table structure for table `board_sub`
--

CREATE TABLE `board_sub` (
  `bs_id` int(11) NOT NULL,
  `bm_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `bs_detail` varchar(200) NOT NULL,
  `bs_img` varchar(200) DEFAULT NULL,
  `bs_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `board_sub`
--

INSERT INTO `board_sub` (`bs_id`, `bm_id`, `u_id`, `bs_detail`, `bs_img`, `bs_date`) VALUES
(1, 1, 1, '1', '', '2022-08-09 05:35:53'),
(2, 1, 1, '2', '', '2022-08-09 05:35:55'),
(3, 1, 1, '3', '', '2022-08-09 05:38:50'),
(4, 1, 1, '4', 'image_2022-08-09_053943944.png', '2022-08-09 05:39:44');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `cg_id` int(11) NOT NULL,
  `cg_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cg_id`, `cg_name`) VALUES
(1, 'อนิเมะ'),
(2, 'หนัง');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `u_id` int(11) NOT NULL,
  `email` varchar(20) NOT NULL,
  `fullname` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `img` varchar(200) DEFAULT NULL,
  `type` int(1) NOT NULL DEFAULT '0' COMMENT '0 = member\r\n1 = admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`u_id`, `email`, `fullname`, `password`, `img`, `type`) VALUES
(1, 'user@user.com', 'ผู้ใช้ ฟ้ายิ้ม', '$2y$10$gtB5PbJPksJ/KziT4gxj4O9lj2i9bUuA.fwMQqGxZRmGkuX4s9Y/u', '142596370_109150097834443_12673755584965935_n.jpg', 0),
(2, 'admin@admin.com', 'ฟ้าใส ยิ้ม', '$2y$10$OAfvzuJPpPu1atTZUxpueOWNRZjzcj3NI8tA//FJKi/XZIPH9M2Bi', '142596370_109150097834443_12673755584965935_n.jpg', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `board_main`
--
ALTER TABLE `board_main`
  ADD PRIMARY KEY (`bm_id`);

--
-- Indexes for table `board_sub`
--
ALTER TABLE `board_sub`
  ADD PRIMARY KEY (`bs_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cg_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `board_main`
--
ALTER TABLE `board_main`
  MODIFY `bm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `board_sub`
--
ALTER TABLE `board_sub`
  MODIFY `bs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
