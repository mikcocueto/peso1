-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2025 at 03:39 AM
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

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `firstName`, `lastName`, `emailAddress`, `role`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', 'admin');

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
  `still_in_role` tinyint(1) DEFAULT 0,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_careerhistory`
--

INSERT INTO `tbl_careerhistory` (`id`, `user_id`, `job_title`, `company_name`, `start_date`, `end_date`, `still_in_role`, `description`) VALUES
(2, 12, 'OJT', 'miso', '2025-02-01', '0000-00-00', 1, 'now'),
(6, 13, 'jsjasghjg', '834hghhhhh34g', '2024-01-13', '0000-00-00', 1, 'kapeaddd'),
(7, 13, 'ggg', '546545', '2025-02-13', '0000-00-00', 1, ''),
(8, 13, 'uu', 'ii', '2025-02-01', '0000-00-00', NULL, ''),
(9, 13, 'd', 'a', '2025-02-01', '0000-00-00', 1, 'wa'),
(11, 14, 'naqqqqqqqqqqqq', 'reeeeeeee', '2024-02-01', '2024-02-03', 0, 'werrr'),
(12, 16, 'miso', 'miso', '2025-02-04', '2025-02-05', 0, 'qqqqqqqqq');

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

--
-- Dumping data for table `tbl_certification`
--

INSERT INTO `tbl_certification` (`id`, `user_id`, `licence_name`, `issuing_organization`, `issue_date`, `expiry_date`, `description`) VALUES
(1, 16, 'Ethical hackerss', 'ciscoss', '2025-02-04', '2025-02-05', '111');

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
  `companyNumber` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `comp_logo_dir` varchar(255) NOT NULL,
  `company_verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_company`
--

INSERT INTO `tbl_company` (`company_id`, `firstName`, `lastName`, `companyName`, `country`, `companyNumber`, `create_time`, `comp_logo_dir`, `company_verified`) VALUES
(4, '', '', 'adeson', '', 0, '2025-03-11 01:42:08', '', 0),
(5, 'ade', 'son', '', 'Philippines', 0, '2025-03-11 01:42:08', '', 0),
(6, 'shan', 'shan', 'adeson', 'Philippines', 123456789, '2025-03-11 01:42:08', '../db/images/company/logo/dole logo.png', 0),
(7, 'q', 'q', 'q', 'q', 1, '2025-03-11 01:42:08', '../db/images/company/logo/abstract-logo-design-for-any-corporate-brand-business-company-vector.jpg', 0),
(8, 'Joshua', 'Lita', '', 'Ph', 1, '2025-03-11 02:04:58', '', 0),
(9, 'Mikco', 'Cueto', '', 'Philippines', 1, '2025-03-11 02:06:11', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_comp_verification`
--

CREATE TABLE `tbl_comp_verification` (
  `id` int(11) NOT NULL,
  `comp_id` int(11) NOT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'pending',
  `dir_business_permit` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(4, 14, 'kqqq', 'hhrrrrrrrrrrrrrrrrrr', '2025-02-18', 'llyyyyyyyyyyy'),
(5, 16, 'it', 'lspu', '2025-02-01', 'wala');

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
(14, 'Mikco', 'Cueto', 'Sta. Maria SPC', 'c@gmail.com', 'Female', '099999999999', '2025-02-14 03:58:48', ''),
(15, 'q', 'q', '', 'q@gmail.com', '', '', '2025-02-17 05:23:39', ''),
(16, 'new', 'user', '', 'new@gmail.com', '', '', '2025-02-24 06:25:21', ''),
(17, 'shan', 'p', '', 'shan@gmail.com', '', '', '2025-03-07 03:25:23', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emp_cv`
--

CREATE TABLE `tbl_emp_cv` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `cv_file_name` varchar(255) NOT NULL,
  `cv_dir` varchar(255) NOT NULL,
  `upload_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_emp_cv`
--

INSERT INTO `tbl_emp_cv` (`id`, `emp_id`, `cv_file_name`, `cv_dir`, `upload_timestamp`) VALUES
(1, 12, 'Kennie Grades.pdf', '../db/pdf/emp_cv/', '2025-02-26 08:05:48'),
(7, 12, 'final-FOR LOST FILLING.pdf', '../db/pdf/emp_cv/', '2025-02-28 07:12:46'),
(8, 12, 'UPDATED DOCS.pdf', '../db/pdf/emp_cv/', '2025-02-28 08:27:21');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emp_saved_jobs`
--

CREATE TABLE `tbl_emp_saved_jobs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_emp_saved_jobs`
--

INSERT INTO `tbl_emp_saved_jobs` (`id`, `user_id`, `job_id`) VALUES
(1, 12, 1),
(2, 12, 2),
(7, 15, 1),
(8, 16, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emp_skills`
--

CREATE TABLE `tbl_emp_skills` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `skill_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_job_application`
--

CREATE TABLE `tbl_job_application` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `application_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_job_application`
--

INSERT INTO `tbl_job_application` (`id`, `emp_id`, `job_id`, `application_time`, `status`) VALUES
(1, 12, 5, '2025-02-28 05:54:33', 'pending'),
(3, 12, 3, '2025-02-28 08:24:27', 'pending'),
(6, 17, 3, '2025-03-07 07:37:58', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_job_category`
--

CREATE TABLE `tbl_job_category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_job_category`
--

INSERT INTO `tbl_job_category` (`category_id`, `category_name`) VALUES
(1, 'category test'),
(2, 'Accounting'),
(3, 'Construction'),
(4, 'Engineering');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_job_listing`
--

CREATE TABLE `tbl_job_listing` (
  `job_id` int(11) NOT NULL,
  `employer_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `requirements` text NOT NULL,
  `employment_type` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `salary_min` decimal(11,2) NOT NULL,
  `salary_max` decimal(11,2) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `category_id` int(11) NOT NULL,
  `posted_date` timestamp NULL DEFAULT NULL,
  `expiry_date` timestamp NULL DEFAULT NULL,
  `status` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_job_listing`
--

INSERT INTO `tbl_job_listing` (`job_id`, `employer_id`, `title`, `description`, `requirements`, `employment_type`, `location`, `salary_min`, `salary_max`, `currency`, `category_id`, `posted_date`, `expiry_date`, `status`) VALUES
(1, 7, 'ttest', 'dtest', 'rtest', 'Full-time', 'spc', 1.00, 2.00, 'php', 1, '2025-02-16 16:00:00', '2025-02-16 16:00:00', 'active'),
(2, 7, 'test2 title', 'test2 desc', 'test2 reqs', 'Internship', 'test2 loc', 122.00, 123.00, 'ddd', 1, '2025-02-16 16:00:00', '2025-02-27 16:00:00', 'active'),
(3, 6, 'wala22222222222', 'non', '89 yrs xp', 'Full-Time', '3', 300.00, 350.00, 'phps', 4, '2025-02-18 16:00:00', '2025-02-26 16:00:00', 'inactive'),
(4, 6, 'job 4', 'geng geng', 'madami frfr', 'Internship', '4', 4.00, 4.00, '4', 3, '2025-02-18 16:00:00', '2025-02-16 16:00:00', 'active'),
(5, 6, 'poso negro', '5 cent', '5tyrrrrrrrrrrr', 'Contract', 'dito', 5.00, 5.00, '$', 4, '2025-02-18 16:00:00', '2025-02-17 16:00:00', 'active'),
(7, 6, 'IT professional', 'magaling it', 'it maalam', 'Full time', 'san pablo', 12.00, 120.00, 'php', 4, '2025-02-18 16:00:00', '2025-02-25 16:00:00', 'active');

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
(3, 12, 'spanish'),
(4, 14, 'french'),
(5, 16, 'tangalog'),
(6, 12, 'en'),
(7, 12, 'saa');

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

--
-- Dumping data for table `tbl_loginadmin`
--

INSERT INTO `tbl_loginadmin` (`id`, `admin_id`, `emailAddress`, `password`) VALUES
(1, 1, 'admin@admin.com', 'admin');

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
(3, 6, 'shan@gmail.com', '$2y$10$wq4sgzuIPXJLHDsbMjbk6eQYFd.BhXnXVo3i9hqh/M.NQWfVL6ev.', '999312668b481428a3b67a6cb281c90d'),
(4, 7, 'q@gmail.com', '$2y$10$78acVWX/E7FaN6Id1y4FQurGB4ahoafy2zZBjnu95AYVpRsowiwa6', '881a3819116611b7de23d30d93f45960'),
(5, 8, 'lita@gmail.com', '$2y$10$M3GO6eXNLAqlRBMDn93IlOmRS59jR.4TrAjWf00.FXOW5XtpzybVO', '4570a471cd8c900d28a2a448eace6fcd'),
(6, 9, 'cueto@gmail.com', '$2y$10$aSs8vP.dO6Zb3JneONUHJuExXRZpbr9Njz19XsjfSGpJKvaqlz.rO', 'e6553904a0aa34a6d56674835a8874bf');

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
(11, 12, 'justine@gmail.com', '$2y$10$GSBTIdrXBY8Js7OCJKkEG.tamNV9J.aM3/U7R2hFZ0AQ.J9lk6hzW', 'ff9c76c3f6759c670e43981ec211c2da'),
(12, 13, 'cueto@gmail.com', '$2y$10$k3VtnDTQt35lRgxdzhzW4uQbMztzHzRpKe1tZgdoxmTOANh8K8Qpi', '8048c80311572e2c9e7ce1aba032413b'),
(13, 14, 'c@gmail.com', '$2y$10$fUXskg4AvXWTJZZZ5ILYz.h5MMuVOO3ccphPfYSh5gVh2d7WYrKma', '486ea598f824510eae98f97997aee1c9'),
(14, 15, 'q@gmail.com', '$2y$10$bjfjhWI0opTVQy7FnWki8.TS15bneUmQFWnnARkSspDC3BpgaFl2W', '6f6429bb85ba085f101e84969eb8ef6d'),
(15, 16, 'new@gmail.com', '$2y$10$aUnIVI9vPQdaeVcH5XFCSe93uu8PFE4U43tM0d8DGYRCk7vis.eWC', 'a5562417cf7f5a2a0bafad3075671d15'),
(16, 17, 'shan@gmail.com', '$2y$10$Lif.hyGIzgj1m/3JuNnKAue7fMZwXCNFShE50dPIrHZj2JE7D2lN2', '88f1714cbeab414b76dced3a7d3a9f7d');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_resume`
--

CREATE TABLE `tbl_resume` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `resume_file` varchar(255) NOT NULL
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
-- Indexes for table `tbl_comp_verification`
--
ALTER TABLE `tbl_comp_verification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_verID` (`comp_id`);

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
-- Indexes for table `tbl_emp_cv`
--
ALTER TABLE `tbl_emp_cv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_cv_key` (`emp_id`);

--
-- Indexes for table `tbl_emp_saved_jobs`
--
ALTER TABLE `tbl_emp_saved_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empsave` (`user_id`),
  ADD KEY `jobsave` (`job_id`);

--
-- Indexes for table `tbl_emp_skills`
--
ALTER TABLE `tbl_emp_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`user_id`);

--
-- Indexes for table `tbl_job_application`
--
ALTER TABLE `tbl_job_application`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_app_key` (`emp_id`),
  ADD KEY `job_app_key` (`job_id`);

--
-- Indexes for table `tbl_job_category`
--
ALTER TABLE `tbl_job_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tbl_job_listing`
--
ALTER TABLE `tbl_job_listing`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `cat fk` (`category_id`),
  ADD KEY `emp fk` (`employer_id`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_careerhistory`
--
ALTER TABLE `tbl_careerhistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_certification`
--
ALTER TABLE `tbl_certification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_company`
--
ALTER TABLE `tbl_company`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_comp_verification`
--
ALTER TABLE `tbl_comp_verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_educback`
--
ALTER TABLE `tbl_educback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_employee`
--
ALTER TABLE `tbl_employee`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_emp_cv`
--
ALTER TABLE `tbl_emp_cv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_emp_saved_jobs`
--
ALTER TABLE `tbl_emp_saved_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_emp_skills`
--
ALTER TABLE `tbl_emp_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_job_application`
--
ALTER TABLE `tbl_job_application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_job_category`
--
ALTER TABLE `tbl_job_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_job_listing`
--
ALTER TABLE `tbl_job_listing`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_language`
--
ALTER TABLE `tbl_language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_loginadmin`
--
ALTER TABLE `tbl_loginadmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_logincompany`
--
ALTER TABLE `tbl_logincompany`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_loginuser`
--
ALTER TABLE `tbl_loginuser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_resume`
--
ALTER TABLE `tbl_resume`
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
-- Constraints for table `tbl_comp_verification`
--
ALTER TABLE `tbl_comp_verification`
  ADD CONSTRAINT `company_verID` FOREIGN KEY (`comp_id`) REFERENCES `tbl_company` (`company_id`);

--
-- Constraints for table `tbl_educback`
--
ALTER TABLE `tbl_educback`
  ADD CONSTRAINT `ideduc` FOREIGN KEY (`user_id`) REFERENCES `tbl_employee` (`user_id`);

--
-- Constraints for table `tbl_emp_cv`
--
ALTER TABLE `tbl_emp_cv`
  ADD CONSTRAINT `emp_cv_key` FOREIGN KEY (`emp_id`) REFERENCES `tbl_employee` (`user_id`);

--
-- Constraints for table `tbl_emp_saved_jobs`
--
ALTER TABLE `tbl_emp_saved_jobs`
  ADD CONSTRAINT `empsave` FOREIGN KEY (`user_id`) REFERENCES `tbl_employee` (`user_id`),
  ADD CONSTRAINT `jobsave` FOREIGN KEY (`job_id`) REFERENCES `tbl_job_listing` (`job_id`);

--
-- Constraints for table `tbl_emp_skills`
--
ALTER TABLE `tbl_emp_skills`
  ADD CONSTRAINT `id` FOREIGN KEY (`user_id`) REFERENCES `tbl_employee` (`user_id`);

--
-- Constraints for table `tbl_job_application`
--
ALTER TABLE `tbl_job_application`
  ADD CONSTRAINT `emp_app_key` FOREIGN KEY (`emp_id`) REFERENCES `tbl_employee` (`user_id`),
  ADD CONSTRAINT `job_app_key` FOREIGN KEY (`job_id`) REFERENCES `tbl_job_listing` (`job_id`);

--
-- Constraints for table `tbl_job_listing`
--
ALTER TABLE `tbl_job_listing`
  ADD CONSTRAINT `cat fk` FOREIGN KEY (`category_id`) REFERENCES `tbl_job_category` (`category_id`),
  ADD CONSTRAINT `emp fk` FOREIGN KEY (`employer_id`) REFERENCES `tbl_company` (`company_id`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
