-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2025 at 05:32 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pesodb`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `admin_id` int(11) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_careerhistory`
--

CREATE TABLE `tbl_careerhistory` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `still_in_role` tinyint(1) DEFAULT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_careerhistory`
--

INSERT INTO `tbl_careerhistory` (`id`, `user_id`, `job_title`, `company_name`, `start_date`, `end_date`, `still_in_role`, `description`) VALUES
(1, 12, '24rr', 'aa', '2025-02-04', '2025-02-19', 1, 'oo'),
(2, 12, 'yyy', 'miso', '2025-02-01', '0000-00-00', 1, 'rerwe'),
(3, 12, '9789', 'hu', '0000-00-00', '0000-00-00', NULL, ''),
(4, 12, 'jsjasghjg', '834hgh34g', '2025-02-01', '0000-00-00', NULL, ''),
(5, 12, 'jsjasghjg', '834hghhhhh34g', '2025-01-01', '0000-00-00', NULL, ''),
(6, 13, 'jsjasghjg', '834hghhhhh34g', '2024-01-13', '0000-00-00', 1, 'kapeaddd'),
(7, 13, 'ggg', '546545', '2025-02-13', '0000-00-00', 1, ''),
(8, 13, 'uu', 'ii', '2025-02-01', '0000-00-00', NULL, ''),
(9, 13, 'd', 'a', '2025-02-01', '0000-00-00', 1, 'wa'),
(10, 12, 'miso', 'miso', '2025-02-01', '2025-02-14', 1, 'wla'),
(11, 14, 'naqqqqqqqqqqqq', 'reeeeeeee', '2024-02-01', '2024-02-03', 0, 'werrr');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_certification`
--

CREATE TABLE `tbl_certification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `licence_name` varchar(255) NOT NULL,
  `issuing_organization` varchar(255) NOT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_company`
--

CREATE TABLE `tbl_company` (
  `company_id` int(11) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `companyName` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `companyNumber` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_company`
--

INSERT INTO `tbl_company` (`company_id`, `firstName`, `lastName`, `companyName`, `country`, `companyNumber`) VALUES
(1, '', '', 'adeson', '', 0),
(2, '', '', 'adeson', '', 0),
(3, '', '', 'adeson', '', 0),
(4, '', '', 'adeson', '', 0),
(5, 'ade', 'son', '', 'Philippines', 0),
(6, 'shan', 'shan', 'adeson', 'Philippines', 123456789);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_educback`
--

CREATE TABLE `tbl_educback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course` varchar(255) NOT NULL,
  `institution` varchar(255) NOT NULL,
  `ending_date` date NOT NULL,
  `course_highlights` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_educback`
--

INSERT INTO `tbl_educback` (`id`, `user_id`, `course`, `institution`, `ending_date`, `course_highlights`) VALUES
(1, 13, 'ds', 'f', '0000-00-00', 'nopommmmmmmmmmmm'),
(2, 13, 'aa', 'ff', '2025-02-20', 'jjjj'),
(3, 12, 'it', 'ls', '2025-02-11', 'wala'),
(4, 14, 'kqqq', 'hhrrrrrrrrrrrrrrrrrr', '2025-02-18', 'llyyyyyyyyyyy');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employee`
--

CREATE TABLE `tbl_employee` (
  `user_id` int(11) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `mobileNumber` varchar(20) NOT NULL,
  `create_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `relationship_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_employee`
--

INSERT INTO `tbl_employee` (`user_id`, `firstName`, `lastName`, `address`, `emailAddress`, `gender`, `mobileNumber`, `create_timestamp`, `relationship_status`) VALUES
(10, 'eric', 'eric', '', 'eric@gmail.com', '', '', '2025-02-12 04:38:54', ''),
(11, 'Mikco', 'Cueto', '', 'cuetomikco08@gmail.com', '', '', '2025-02-12 04:39:53', ''),
(12, 'justine', 'justine', 'aa', 'justine@gmail.com', 'dd', '111', '2025-02-12 05:33:23', ''),
(13, 'Mikco', 'Cueto', 'aaaa', 'cueto@gmail.com', 'a', '', '2025-02-13 04:43:07', ''),
(14, 'Mikco', 'Cueto', 'Sta. Maria SPC', 'c@gmail.com', 'Female', '099999999999', '2025-02-14 03:58:48', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_language`
--

CREATE TABLE `tbl_language` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `language_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_language`
--

INSERT INTO `tbl_language` (`id`, `user_id`, `language_name`) VALUES
(1, 13, 'tangalog'),
(2, 13, 'fff'),
(3, 12, 'tangalog'),
(4, 14, 'french');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_loginadmin`
--

CREATE TABLE `tbl_loginadmin` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_logincompany`
--

CREATE TABLE `tbl_logincompany` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_logincompany`
--

INSERT INTO `tbl_logincompany` (`id`, `company_id`, `emailAddress`, `password`, `salt`) VALUES
(1, 4, 'adeson@gmail.com', '$2y$10$h3vdK7cpQwMQJdw8QJMgD.UtjDbuwtkN9UY2iRVkhBnMELZTZ4aRi', '3d1a64c872938f48146fb600ec96c78c'),
(2, 5, 'adeson1@gmail.com', '$2y$10$/Cjt4cyNfgBbl3IatSK.xuoakBt327e1uJkMdic6xPAJD252LGqMW', 'a25c966579687b597ef4cd0e1f804918'),
(3, 6, 'shan@gmail.com', '$2y$10$vnZVehA.yLVcJ/zY/ySncOxjy2RjCPtnmm21zdyZW6IKKPlD0HDfq', '98b61b11f9f4b1d838c874721d3295b3');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_loginuser`
--

CREATE TABLE `tbl_loginuser` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_loginuser`
--

INSERT INTO `tbl_loginuser` (`id`, `user_id`, `emailAddress`, `password`, `salt`) VALUES
(9, 10, 'eric@gmail.com', '$2y$10$/d6fBmmnmsFRz.0CSlV7XOTp4CDpvAdBQ82R20Ks6dY2XpBlQOY3W', '0c01646028237de56270ee1677160546'),
(10, 11, 'cuetomikco08@gmail.com', '$2y$10$hTMLkqKv006/lwrlk1WcAOLUDchO2D6lM5uUSoy8ILswdNfUP1L5O', '753f35da13e88a872743ae37d867ac5a'),
(11, 12, 'justine@gmail.com', '$2y$10$.nbCJ8wHDZUmazJzvJhhkeFMSMOhgxoPFXa2x9GlmZJ8nMzrmgE5C', 'c2f763fb243319d7208bd24fc979bb91'),
(12, 13, 'cueto@gmail.com', '$2y$10$k3VtnDTQt35lRgxdzhzW4uQbMztzHzRpKe1tZgdoxmTOANh8K8Qpi', '8048c80311572e2c9e7ce1aba032413b'),
(13, 14, 'c@gmail.com', '$2y$10$fUXskg4AvXWTJZZZ5ILYz.h5MMuVOO3ccphPfYSh5gVh2d7WYrKma', '486ea598f824510eae98f97997aee1c9');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_resume`
--

CREATE TABLE `tbl_resume` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `resume_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_skills`
--

CREATE TABLE `tbl_skills` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `skill_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tbl_careerhistory`
--
ALTER TABLE `tbl_careerhistory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idhistory` (`user_id`);

--
-- Indexes for table `tbl_certification`
--
ALTER TABLE `tbl_certification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idcert` (`user_id`);

--
-- Indexes for table `tbl_company`
--
ALTER TABLE `tbl_company`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `tbl_educback`
--
ALTER TABLE `tbl_educback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ideduc` (`user_id`);

--
-- Indexes for table `tbl_employee`
--
ALTER TABLE `tbl_employee`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tbl_language`
--
ALTER TABLE `tbl_language`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idlanguage` (`user_id`);

--
-- Indexes for table `tbl_loginadmin`
--
ALTER TABLE `tbl_loginadmin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `adminlog` (`admin_id`);

--
-- Indexes for table `tbl_logincompany`
--
ALTER TABLE `tbl_logincompany`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `tbl_loginuser`
--
ALTER TABLE `tbl_loginuser`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idlogin` (`user_id`);

--
-- Indexes for table `tbl_resume`
--
ALTER TABLE `tbl_resume`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_skills`
--
ALTER TABLE `tbl_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_careerhistory`
--
ALTER TABLE `tbl_careerhistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_certification`
--
ALTER TABLE `tbl_certification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_company`
--
ALTER TABLE `tbl_company`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_educback`
--
ALTER TABLE `tbl_educback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_employee`
--
ALTER TABLE `tbl_employee`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_language`
--
ALTER TABLE `tbl_language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_loginadmin`
--
ALTER TABLE `tbl_loginadmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_logincompany`
--
ALTER TABLE `tbl_logincompany`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_loginuser`
--
ALTER TABLE `tbl_loginuser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_resume`
--
ALTER TABLE `tbl_resume`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_skills`
--
ALTER TABLE `tbl_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_careerhistory`
--
ALTER TABLE `tbl_careerhistory`
  ADD CONSTRAINT `idhistory` FOREIGN KEY (`user_id`) REFERENCES `tbl_employee` (`user_id`);

--
-- Constraints for table `tbl_certification`
--
ALTER TABLE `tbl_certification`
  ADD CONSTRAINT `idcert` FOREIGN KEY (`user_id`) REFERENCES `tbl_employee` (`user_id`);

--
-- Constraints for table `tbl_educback`
--
ALTER TABLE `tbl_educback`
  ADD CONSTRAINT `ideduc` FOREIGN KEY (`user_id`) REFERENCES `tbl_employee` (`user_id`);

--
-- Constraints for table `tbl_language`
--
ALTER TABLE `tbl_language`
  ADD CONSTRAINT `idlanguage` FOREIGN KEY (`user_id`) REFERENCES `tbl_employee` (`user_id`);

--
-- Constraints for table `tbl_loginadmin`
--
ALTER TABLE `tbl_loginadmin`
  ADD CONSTRAINT `adminlog` FOREIGN KEY (`admin_id`) REFERENCES `tbl_admin` (`admin_id`);

--
-- Constraints for table `tbl_logincompany`
--
ALTER TABLE `tbl_logincompany`
  ADD CONSTRAINT `company_id` FOREIGN KEY (`company_id`) REFERENCES `tbl_company` (`company_id`);

--
-- Constraints for table `tbl_loginuser`
--
ALTER TABLE `tbl_loginuser`
  ADD CONSTRAINT `idlogin` FOREIGN KEY (`user_id`) REFERENCES `tbl_employee` (`user_id`);

--
-- Constraints for table `tbl_resume`
--
ALTER TABLE `tbl_resume`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_employee` (`user_id`);

--
-- Constraints for table `tbl_skills`
--
ALTER TABLE `tbl_skills`
  ADD CONSTRAINT `id` FOREIGN KEY (`user_id`) REFERENCES `tbl_employee` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
