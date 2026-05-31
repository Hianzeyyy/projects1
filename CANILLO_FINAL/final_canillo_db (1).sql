-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 11, 2026 at 01:30 PM
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
-- Database: `final_canillo_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `connections`
--

CREATE TABLE `connections` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `curriculum`
--

CREATE TABLE `curriculum` (
  `id` int(11) NOT NULL,
  `year_level` int(1) NOT NULL,
  `semester` int(1) NOT NULL,
  `grade` varchar(10) DEFAULT '---',
  `status` varchar(50) DEFAULT 'In Progress',
  `course_code` varchar(30) NOT NULL,
  `description` varchar(255) NOT NULL,
  `units` decimal(3,2) NOT NULL,
  `term_taken` varchar(50) DEFAULT NULL,
  `pre_requisites` text DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `curriculum`
--

INSERT INTO `curriculum` (`id`, `year_level`, `semester`, `grade`, `status`, `course_code`, `description`, `units`, `term_taken`, `pre_requisites`, `remarks`) VALUES
(1, 1, 1, '2.50', 'Passed', 'URD_CC101_IT', 'Introduction to Computing', 3.00, '1S of 2022', 'None', 'Passed'),
(2, 1, 1, '2.00', 'Passed', 'URD_CC102_IT', 'Fundamentals of Programming', 3.00, '1S of 2022', 'None', 'Passed'),
(3, 1, 2, '3.00', 'Passed', 'URD_CC103_IT', 'Intermediate Programming', 3.00, '2S of 2023', 'URD_CC102_IT', 'Passed'),
(4, 2, 1, '2.25', 'Passed', 'URD_CC104_IT', 'Data Structures and Algorithms', 3.00, '1S of 2024', 'URD_CC103_IT', 'Passed'),
(5, 2, 2, '2.00', 'Passed', 'URD_CC105_IT', 'Information Management 1', 3.00, '2S of 2024', 'URD_CC104_IT', 'Passed'),
(6, 3, 1, '2.25', 'Passed', 'A_CC 106', 'Applications Development', 3.00, '1S of 2024-2025', 'CC 103', 'Completed'),
(7, 3, 1, '1.50', 'Passed', 'A_WS 101', 'Web Systems and Tech 1', 3.00, '1S of 2024-2025', 'CC 102', 'Excellent'),
(8, 3, 2, '---', 'In Progress', 'A_CAP 101', 'Capstone Project 1', 3.00, '2S of 2024-2025', 'CC 106', 'Ongoing'),
(9, 1, 1, '1.75', 'Passed', 'URD GE5', 'The Contemporary World', 3.00, '1S of 2022', '', 'Passed'),
(10, 1, 1, '3.00', 'Passed', 'URD GE6', 'Science, Technology and Society', 3.00, '1S of 2022', '', 'Passed'),
(11, 1, 1, '2.75', 'Passed', 'URD GE7', 'Mathematics in the Modern World', 3.00, '1S of 2023', '', 'Passed'),
(12, 1, 2, '2.00', 'Passed', 'URD GE1', 'Understanding the Self', 3.00, '2S of 2022', '', 'Passed'),
(13, 1, 2, '2.25', 'Passed', 'URD GE2', 'Readings in Philippine History', 3.00, '2S of 2022', '', 'Passed'),
(14, 2, 1, '2.00', 'Passed', 'URD GE4', 'Purposive Communication', 3.00, '1S of 2023', '', 'Passed'),
(15, 2, 2, '2.50', 'Passed', 'URD GE8', 'The Life and Works of Rizal', 3.00, '2S of 2023', '', 'Passed'),
(16, 4, 1, '---', 'Pending', 'A_CAP 102', 'Capstone Project 2', 3.00, NULL, 'A_CAP 101', NULL),
(17, 4, 1, '---', 'Pending', 'A_ELEC3', 'Elective 3 (Special Topics 1)', 3.00, NULL, 'A_ELEC1', NULL),
(18, 4, 1, '---', 'Pending', 'A_IAS 102', 'Information Assurance 2', 3.00, NULL, 'A_IAS 101', NULL),
(19, 4, 2, '---', 'Pending', 'A_INTERN 101', 'Internship (OJT/Practicum)', 6.00, NULL, 'None', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `my_portfolio`
--

CREATE TABLE `my_portfolio` (
  `project_id` int(11) NOT NULL,
  `project_name` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `technologies_used` varchar(255) DEFAULT NULL,
  `project_link` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `connections`
--
ALTER TABLE `connections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `curriculum`
--
ALTER TABLE `curriculum`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `my_portfolio`
--
ALTER TABLE `my_portfolio`
  ADD PRIMARY KEY (`project_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `connections`
--
ALTER TABLE `connections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `curriculum`
--
ALTER TABLE `curriculum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `my_portfolio`
--
ALTER TABLE `my_portfolio`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
