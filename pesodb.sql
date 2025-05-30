-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2025 at 08:56 AM
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
(1, 'admin', 'administrator', 'admin@admin.com', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin_login`
--

CREATE TABLE `tbl_admin_login` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin_login`
--

INSERT INTO `tbl_admin_login` (`id`, `admin_id`, `emailAddress`, `password`) VALUES
(3, 1, 'admin@admin.com', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_comp_info`
--

CREATE TABLE `tbl_comp_info` (
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
-- Dumping data for table `tbl_comp_info`
--

INSERT INTO `tbl_comp_info` (`company_id`, `firstName`, `lastName`, `companyName`, `country`, `companyNumber`, `create_time`, `comp_logo_dir`, `company_verified`) VALUES
(4, '', '', 'adeson', '', 0, '2025-03-11 01:42:08', '', 0),
(5, 'ade', 'son', '', 'Philippines', 0, '2025-03-11 01:42:08', '', 0),
(6, 'ggg shan', 'khyle', 'PESO TEST', 'USA', 1111111111, '2025-05-22 09:53:10', '../db/images/company/logo/fds.png', 1),
(7, 'q', 'q', 'q', 'q', 1, '2025-03-11 01:42:08', '../db/images/company/logo/abstract-logo-design-for-any-corporate-brand-business-company-vector.jpg', 0),
(8, 'Joshua', 'Lita', 'lita corp', 'Ph', 1, '2025-03-12 04:48:14', '', 1),
(9, 'Mikco', 'Cueto', 'cueto', 'Philippines', 1, '2025-03-18 06:42:13', '../db/images/company/logo/spc.png', 0),
(10, 'shantest', 'tester', '', 'Philippines', 123456789, '2025-03-28 03:06:11', '', 0),
(11, 'Adeson', 'Macaraig', 'Frontline Business Solution, Inc.', 'Philippines', 321, '2025-04-02 01:37:50', '../db/images/company/logo/fbs.jpg', 1),
(12, 'Justine', 'De Castro', 'Toyota San Pablo Inc.', 'Philippines', 2147483647, '2025-04-02 01:45:31', '../db/images/company/logo/toyota.jpg', 1),
(13, 'test', 'now', 'Company X', 'ph', 911, '2025-04-11 01:30:26', '', 1),
(14, 'Eric', 'ric', 'Eric inc', 'Philippines', 91, '2025-04-11 01:58:36', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_comp_login`
--

CREATE TABLE `tbl_comp_login` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_comp_login`
--

INSERT INTO `tbl_comp_login` (`id`, `company_id`, `emailAddress`, `password`, `salt`) VALUES
(1, 4, 'adeson@gmail.com', '$2y$10$h3vdK7cpQwMQJdw8QJMgD.UtjDbuwtkN9UY2iRVkhBnMELZTZ4aRi', '3d1a64c872938f48146fb600ec96c78c'),
(2, 5, 'adeson1@gmail.com', '$2y$10$/Cjt4cyNfgBbl3IatSK.xuoakBt327e1uJkMdic6xPAJD252LGqMW', 'a25c966579687b597ef4cd0e1f804918'),
(3, 6, 'shan@gmail.com', '$2y$10$wq4sgzuIPXJLHDsbMjbk6eQYFd.BhXnXVo3i9hqh/M.NQWfVL6ev.', '999312668b481428a3b67a6cb281c90d'),
(4, 7, 'q@gmail.com', '$2y$10$78acVWX/E7FaN6Id1y4FQurGB4ahoafy2zZBjnu95AYVpRsowiwa6', '881a3819116611b7de23d30d93f45960'),
(5, 8, 'lita@gmail.com', '$2y$10$M3GO6eXNLAqlRBMDn93IlOmRS59jR.4TrAjWf00.FXOW5XtpzybVO', '4570a471cd8c900d28a2a448eace6fcd'),
(6, 9, 'cueto@gmail.com', '$2y$10$aSs8vP.dO6Zb3JneONUHJuExXRZpbr9Njz19XsjfSGpJKvaqlz.rO', 'e6553904a0aa34a6d56674835a8874bf'),
(7, 10, 'shanaaa@gmail.com', '$2y$10$E.p9unCI32EWMl7LMVWoJeC3Obj6jO.rEqhbczXJRGpBdy3CBl98S', '84d8b77a102382764687852622d3746e'),
(8, 11, 'adesonpogi@gmail.com', '$2y$10$oaRb.j3GvHldwY18xH2/i.fYhyIj22xus52eR8kJ2RJFPGpw6coEK', 'c2ac4c250617b00bc36c06d8cb39b277'),
(9, 12, 'justinepogi@gmail.com', '$2y$10$5nnAQATFOrpqhhM4F7ToNuNx0QVnn.uCtf3vO3W/ZR3L27B2fsqWe', 'eac93425256816109a2edc0ebc5cc85d'),
(10, 13, 'april11@gmail.com', '$2y$10$fbR7tp0Xz9SQ2aEPmhnZ8OsPO6T/gwQFJVoWd3oGWfeWaXgj4eYpW', '846b478b1e079f65116190bf574d4b9d'),
(11, 14, 'eric@gmail.com', '$2y$10$MiE2cn4i/cQv83sEgcwF5.RPMR6JEdDJ3Z5t53aCCESysPvIU2oSG', '1b15e69750298c8255530923c8b6a7d9');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_comp_verification`
--

CREATE TABLE `tbl_comp_verification` (
  `id` int(11) NOT NULL,
  `comp_id` int(11) NOT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'pending',
  `dir_business_permit` varchar(255) NOT NULL,
  `ver_time_stamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_comp_verification`
--

INSERT INTO `tbl_comp_verification` (`id`, `comp_id`, `status`, `dir_business_permit`, `ver_time_stamp`) VALUES
(25, 11, 'accepted', '../../db/pdf/comp_business_permit/qSGouZ2t_FOR LOST FILLING111.pdf', '2025-04-11 01:09:50'),
(26, 12, 'accepted', '../../db/pdf/comp_business_permit/okgQM3GZ_FOR LOST FILLING111.pdf', '2025-04-11 01:09:50'),
(27, 13, 'accepted', '../../db/pdf/comp_business_permit/SwADs5Mm_FOR LOST111 FILLING.pdf', '2025-04-11 01:27:06');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emp_careerhistory`
--

CREATE TABLE `tbl_emp_careerhistory` (
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
-- Dumping data for table `tbl_emp_careerhistory`
--

INSERT INTO `tbl_emp_careerhistory` (`id`, `user_id`, `job_title`, `company_name`, `start_date`, `end_date`, `still_in_role`, `description`) VALUES
(1, 17, 'SUPARMAN', 'SUPARMAN', '2025-03-02', '2025-03-30', 0, 'SUPARMAN'),
(2, 12, 'OJT', 'miso', '2025-02-01', '0000-00-00', 1, 'now'),
(6, 13, 'jsjasghjg', '834hghhhhh34g', '2024-01-13', '0000-00-00', 1, 'kapeaddd'),
(7, 13, 'ggg', '546545', '2025-02-13', '0000-00-00', 1, ''),
(8, 13, 'uu', 'ii', '2025-02-01', '0000-00-00', NULL, ''),
(9, 13, 'd', 'a', '2025-02-01', '0000-00-00', 1, 'wa'),
(11, 14, 'naqqqqqqqqqqqq', 'reeeeeeee', '2024-02-01', '2024-02-03', 0, 'werrr'),
(12, 16, 'miso', 'miso', '2025-02-04', '2025-02-05', 0, 'qqqqqqqqq'),
(13, 17, 'CEO', 'Shan inc.', '2016-01-04', '0000-00-00', 1, 'do itss'),
(14, 18, 'OJT', 'MIS', '2025-03-02', '2025-03-28', 1, ''),
(15, 19, 'miso', 'miso', '2025-05-01', '2025-05-02', 0, 'aa');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emp_category_preferences`
--

CREATE TABLE `tbl_emp_category_preferences` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_emp_category_preferences`
--

INSERT INTO `tbl_emp_category_preferences` (`id`, `emp_id`, `category_id`) VALUES
(5, 17, 6),
(6, 25, 1),
(7, 25, 2),
(8, 26, 5),
(9, 26, 6),
(10, 27, 2),
(11, 28, 2),
(12, 29, 2),
(13, 30, 2),
(14, 30, 3),
(15, 31, 1),
(16, 31, 2),
(17, 31, 3),
(18, 31, 4),
(19, 31, 5),
(20, 31, 6);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emp_certification`
--

CREATE TABLE `tbl_emp_certification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `licence_name` varchar(255) NOT NULL,
  `issuing_organization` varchar(255) NOT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_emp_certification`
--

INSERT INTO `tbl_emp_certification` (`id`, `user_id`, `licence_name`, `issuing_organization`, `issue_date`, `expiry_date`, `description`) VALUES
(1, 16, 'Ethical hackerss', 'ciscoss', '2025-02-04', '2025-02-05', '111'),
(2, 17, 'Ethical hackers', 'cisco', '2025-03-30', '2025-03-29', '0'),
(3, 17, 'unethical hax', 'fresco', '2025-03-31', '2025-02-26', 'wala naman'),
(4, 17, 'cert 3', 'ako', '2025-03-02', '2025-03-14', 'shan'),
(5, 17, '4', 'ciscoqqq', '2025-03-01', '2025-03-08', 'qwe');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emp_cv`
--

CREATE TABLE `tbl_emp_cv` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `cv_file_name` varchar(255) NOT NULL,
  `cv_name` varchar(255) DEFAULT NULL,
  `cv_dir` varchar(255) NOT NULL,
  `upload_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_emp_cv`
--

INSERT INTO `tbl_emp_cv` (`id`, `emp_id`, `cv_file_name`, `cv_name`, `cv_dir`, `upload_timestamp`) VALUES
(15, 17, 'R1Fu6T4r_KATHERINE CUETO-RESUME (1).pdf', '1', '../../db/pdf/emp_cv/', '2025-04-08 07:13:19'),
(16, 19, 'zsQd74Jl_IMRAD_Development of a Machine Learning Model Integrated in a Mobile App to.pdf', 'cv1', '../../db/pdf/emp_cv/', '2025-05-08 00:54:48'),
(17, 20, 'eDDobnVb_JMCY 3IN1.pdf', '11', '../../db/pdf/emp_cv/', '2025-05-08 04:10:17'),
(18, 21, 'phec1T6q_FOR LOST11 FILLING.pdf', 'cv1', '../../db/pdf/emp_cv/', '2025-05-14 06:26:39'),
(19, 27, 'f2733ca46f26a312_ADE-LABEL.pdf', 'Resume', '../../db/pdf/emp_cv/', '2025-05-22 06:27:45'),
(20, 28, 'a88357a7a1e9dcf2_cole1.pdf', 'Resume', '../../db/pdf/emp_cv/', '2025-05-22 06:33:01'),
(21, 29, 'aa78e6e6992612a2_cole1.pdf', 'Resume', '../../db/pdf/emp_cv/', '2025-05-22 06:54:17'),
(22, 30, '85459b9b806c727d_cole1.pdf', 'Resume', '../../db/pdf/emp_cv/', '2025-05-22 06:57:20'),
(23, 31, 'fe51d8a3ea7870cd_cole1.pdf', 'Resume', '../../db/pdf/emp_cv/', '2025-05-22 07:00:28');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emp_educback`
--

CREATE TABLE `tbl_emp_educback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course` varchar(255) NOT NULL,
  `institution` varchar(255) NOT NULL,
  `ending_date` date NOT NULL,
  `course_highlights` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_emp_educback`
--

INSERT INTO `tbl_emp_educback` (`id`, `user_id`, `course`, `institution`, `ending_date`, `course_highlights`) VALUES
(1, 17, 'BATMAN', 'BATMAN', '2025-03-02', 'BATMAN'),
(2, 18, 'BSN', 'LSPU-Sta. Cruz', '2027-08-20', 'Notepad'),
(3, 17, 'SUPARMAN', 'SUPARMAN', '2025-03-14', 'SUPARMAN');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emp_info`
--

CREATE TABLE `tbl_emp_info` (
  `user_id` int(11) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `mobileNumber` varchar(20) NOT NULL,
  `create_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `pfp_dir` varchar(255) NOT NULL,
  `age` int(3) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `highest_edu` varchar(32) NOT NULL,
  `years_of_experience` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_emp_info`
--

INSERT INTO `tbl_emp_info` (`user_id`, `firstName`, `lastName`, `address`, `emailAddress`, `gender`, `mobileNumber`, `create_timestamp`, `pfp_dir`, `age`, `birth_date`, `highest_edu`, `years_of_experience`) VALUES
(10, 'eric', 'eric', '', 'eric@gmail.com', '', '', '2025-02-12 04:38:54', '', 0, NULL, '', NULL),
(11, 'Mikco', 'Cueto', '', 'cuetomikco08@gmail.com', '', '', '2025-02-12 04:39:53', '', 0, NULL, '', NULL),
(12, 'justine', 'justine', 'aa', 'justine@gmail.com', 'dd', '111', '2025-02-12 05:33:23', '', 0, NULL, '', NULL),
(13, 'Mikco', 'Cueto', 'aaaa', 'cueto@gmail.com', 'a', '', '2025-02-13 04:43:07', '', 0, NULL, '', NULL),
(14, 'Mikco', 'Cueto', 'Sta. Maria SPC', 'c@gmail.com', 'Female', '099999999999', '2025-02-14 03:58:48', '', 0, NULL, '', NULL),
(15, 'q', 'q', '', 'q@gmail.com', '', '', '2025-02-17 05:23:39', '', 0, NULL, '', NULL),
(16, 'new', 'user', '', 'new@gmail.com', '', '', '2025-02-24 06:25:21', '', 0, NULL, '', NULL),
(17, 'shan', 'test', 'cavite', 'shan@gmail.com', 'male', '911', '2025-03-07 03:25:23', '', 0, NULL, '', NULL),
(18, 'MIKCO', 'Mikco', 'Purok 7 Brgy Sta Maria', '0321-2980@lspu.edu.ph', 'You Decide', '9076532552', '2025-03-18 04:05:12', '', 0, NULL, '', NULL),
(19, 'q', 'w', 'University of Santo Tomas, España Boulevard, Barangay 470, Sampaloc, Fourth District, Manila, Capital District, Metro Manila, 1008, Philippines', 'shan01@gmail.com', 'male', '09123456789', '2025-05-06 04:14:20', '', 24, '2000-11-02', '', NULL),
(20, 'q', 'w', 'Vape Play And Chill, 1733 C, F. Varona Street, Jade Garden Manila, Barangay 111, Tondo, First District, Manila, Capital District, Metro Manila, 1013, Philippines', 'shan02@gmail.com', 'male', '09123456789', '2025-05-06 04:17:51', '', 16, '2008-05-07', '', NULL),
(21, 'shan', '03', 'Park Hill Street, Damayang Lagi, New Manila, 4th District, Quezon City, Eastern Manila District, Metro Manila, 1112, Philippines', 'shan03@gmail.com', 'prefer_not_to_say', '09123456789', '2025-05-06 04:24:08', '', 21, '2003-05-07', 'Doctorate', 10),
(22, 'q', 'w', 'España Boulevard, Sampaloc, Fourth District, Manila, Capital District, Metro Manila, 1015, Philippines', 'shan04@gmail.com', 'male', '09123456789', '2025-05-06 05:00:26', '', 5, '2020-05-02', 'Diploma', 10),
(23, 'q', 'w', 'Bambang Market/Masangkay, Masangkay Street, 257, Tondo, Second District, Manila, Capital District, Metro Manila, 1003, Philippines', 'shan05@gmail.com', 'female', '09123456789', '2025-05-06 05:03:22', '', 2, '2023-05-01', 'Doctorate', 44),
(24, 'q', 'w', 'Maria Cristina Street, Barangay 494, Sampaloc, Fourth District, Manila, Capital District, Metro Manila, 1015, Philippines', 'shan06@gmail.com', 'female', '09123456789', '2025-05-06 05:05:53', '', 5, '2020-05-06', 'Bachelor\'s Degree', 10),
(25, 'q', 'q', 'Jose R. Reyes Memorial Medical Center, Rizal Avenue, Santa Cruz, Third District, Manila, Capital District, Metro Manila, 1003, Philippines', 'shan07@gmail.com', 'male', '09123456789', '2025-05-06 05:10:46', '', 1, '2023-05-11', 'Bachelor\'s Degree', 10),
(26, 'q', 'w', 'Quiricada Street, 257, Tondo, Second District, Manila, Capital District, Metro Manila, 1003, Philippines', 'shan08@gmail.com', 'prefer_not_to_say', '09123456789', '2025-05-06 05:13:14', '', 2, '2023-05-03', 'Master\'s Degree', 11),
(27, 'Shan', '11', 'Don Pepe Street, Santo Domingo, Santa Mesa Heights, 1st District, Quezon City, Eastern Manila District, Metro Manila, 1114, Philippines', 'shan11@gmail.com', 'male', '09586475914', '2025-05-22 06:27:45', '', -2, '2027-01-22', 'Master\'s Degree', 12),
(28, 'Shan', '12', 'Purok 1 Street, TLR Town Homes, Moonwalk, Parañaque District 2, Parañaque, Southern Manila District, Metro Manila, 1711, Philippines', 'shan12@gmail.com', 'prefer_not_to_say', '09456842647', '2025-05-22 06:33:01', '', 26, '1999-01-23', 'Bachelor\'s Degree', 15),
(29, 'shan', '13', 'Pier 12, Mel Lopez Boulevard, Barangay 111, Tondo, First District, Manila, Capital District, Metro Manila, 1012, Philippines', 'shan13@gmail.com', 'male', '09147258369', '2025-05-22 06:54:17', '', 23, '2002-05-22', 'Doctorate', 15),
(30, 'shan', '14', 'A. Rivera Street, Divisoria, Tondo, Second District, Manila, Capital District, Metro Manila, 1006, Philippines', 'shan14@gmail.com', 'male', '09147852369', '2025-05-22 06:57:20', '', 28, '1997-01-23', 'Doctorate', 15),
(31, 'shan', '15', '7-Eleven, Ilaya Street, San Sebastian, Tondo, First District, Manila, Capital District, Metro Manila, 1012, Philippines', 'shan15@gmail.com', 'male', '09147852369', '2025-05-22 07:00:28', '', 30, '1995-05-18', 'Doctorate', 15);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emp_language`
--

CREATE TABLE `tbl_emp_language` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `language_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_emp_language`
--

INSERT INTO `tbl_emp_language` (`id`, `user_id`, `language_name`) VALUES
(1, 13, 'tangalog'),
(2, 13, 'fff'),
(3, 12, 'spanish'),
(4, 14, 'french'),
(5, 16, 'tangalog'),
(6, 12, 'en'),
(7, 12, 'saa'),
(10, 12, 'Wow ang sipag'),
(20, 18, 'Spanish'),
(21, 18, 'Mandarin'),
(22, 18, 'Japanese'),
(23, 18, 'Filipino'),
(25, 17, 'english');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emp_login`
--

CREATE TABLE `tbl_emp_login` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_emp_login`
--

INSERT INTO `tbl_emp_login` (`id`, `user_id`, `emailAddress`, `password`, `salt`) VALUES
(9, 10, 'eric@gmail.com', '$2y$10$/d6fBmmnmsFRz.0CSlV7XOTp4CDpvAdBQ82R20Ks6dY2XpBlQOY3W', '0c01646028237de56270ee1677160546'),
(10, 11, 'cuetomikco08@gmail.com', '$2y$10$hTMLkqKv006/lwrlk1WcAOLUDchO2D6lM5uUSoy8ILswdNfUP1L5O', '753f35da13e88a872743ae37d867ac5a'),
(11, 12, 'justine@gmail.com', '$2y$10$GSBTIdrXBY8Js7OCJKkEG.tamNV9J.aM3/U7R2hFZ0AQ.J9lk6hzW', 'ff9c76c3f6759c670e43981ec211c2da'),
(12, 13, 'cueto@gmail.com', '$2y$10$k3VtnDTQt35lRgxdzhzW4uQbMztzHzRpKe1tZgdoxmTOANh8K8Qpi', '8048c80311572e2c9e7ce1aba032413b'),
(13, 14, 'c@gmail.com', '$2y$10$fUXskg4AvXWTJZZZ5ILYz.h5MMuVOO3ccphPfYSh5gVh2d7WYrKma', '486ea598f824510eae98f97997aee1c9'),
(14, 15, 'q@gmail.com', '$2y$10$bjfjhWI0opTVQy7FnWki8.TS15bneUmQFWnnARkSspDC3BpgaFl2W', '6f6429bb85ba085f101e84969eb8ef6d'),
(15, 16, 'new@gmail.com', '$2y$10$aUnIVI9vPQdaeVcH5XFCSe93uu8PFE4U43tM0d8DGYRCk7vis.eWC', 'a5562417cf7f5a2a0bafad3075671d15'),
(16, 17, 'shan@gmail.com', '$2y$10$Lif.hyGIzgj1m/3JuNnKAue7fMZwXCNFShE50dPIrHZj2JE7D2lN2', '88f1714cbeab414b76dced3a7d3a9f7d'),
(17, 18, 'mikco@gmail.com', '$2y$10$IZtDy.Uv/8GkR57lgrlYEeoZNHAMEMPFfXwPU2LEox0giNwwHjo3e', 'f6f6e65887253d2b56440b9d76394d42'),
(18, 19, 'shan01@gmail.com', '$2y$10$xmdYl7iqOSeNIO7s2oeyF.dOhAcGhQ4ge1kGj5iMwAB0j3Eq8xK7K', '515d9abe9f076dddb2e78ede937c69ee'),
(19, 20, 'shan02@gmail.com', '$2y$10$U5b.OeMKcWVow/I5xsVfz.rxfPZLfYGptBDXa/fHq/LZewqQCZUrS', '5a042acb8a74dec03486eacf37fb132d'),
(20, 21, 'shan03@gmail.com', '$2y$10$9DZuTLuQM32/L3jY19BiA.M/BV13K2.IP4/kCxa6EJVL1gLOCNj3W', '336d6a2ea4c4fff72c5ef401708ce539'),
(21, 22, 'shan04@gmail.com', '$2y$10$wpfS4Sn1prTEEuMK7YpbAeDUfVS8uqgezhFbSB3UlG0Fo4ovzRVfq', 'd91b7d27d20e456449b3b55f6b00829c'),
(22, 23, 'shan05@gmail.com', '$2y$10$r..jYFkUwFyKMhgIhnQvuO7hQjQt..oaHvOJo/6VkgJBJrn4q0P8q', '6dc915d6f0bd4c6bde8d742dffb05597'),
(23, 24, 'shan06@gmail.com', '$2y$10$TvzQAGENNQJ98kxdDhrTc.ZthqzWETPrqkh.25iYraIgKjuVHj5Em', '3633a5995802d8242054162abc9f8cbf'),
(24, 25, 'shan07@gmail.com', '$2y$10$5eWR91nnmrFHxPHLEWzrruXqfJcME73Gkzqlta37vVpu65j2TmdvK', 'b7bfa3e67a0d58150b764ab3d1d5601b'),
(25, 26, 'shan08@gmail.com', '$2y$10$KfbDOSK4K0cTqMpUsiYSdeVtek.bdOjusSPE55jjNfB7ExjCwqOKq', '69f33f16aed44bd25e3b3acb31b95309'),
(26, 27, 'shan11@gmail.com', '$2y$10$k5SStDs3fzz1C7sXEHMbZ.nFUFl.Z0V.e7PBC.jc2Ry1IxSu8iJPq', 'c7c25da1fb5480c0335ef17d75e542df'),
(27, 28, 'shan12@gmail.com', '$2y$10$WOj7woWBWTdlpJsOgxFUvusfaq0zRLvDm60vERZn0rfHLnSt/03pa', 'a7a68842341f9e3fecd335f2a62fc948'),
(28, 29, 'shan13@gmail.com', '$2y$10$eNSHldyX.CBN5A63icxgieTZyJMhJqG3We0hoEF0p/L5lr1Com5Ju', '7577e7028920149d6bc5a936e63549b4'),
(29, 30, 'shan14@gmail.com', '$2y$10$7i3QlViFjceChNtGRtgVlewuvqH1KwXicX/9EBFFqyAYkXl12PGa2', '262901aaa0ce862cc223b5b77ecc47f7'),
(30, 31, 'shan15@gmail.com', '$2y$10$arrJFVXO7YxipnYJvbL1/Og4Ha8j03CzI/17EHzGn6RI6erK79SXa', 'fe2af086c07edf1ef632e6e341c107b6');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emp_resume`
--

CREATE TABLE `tbl_emp_resume` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `resume_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(14, 17, 14),
(18, 17, 34);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emp_skills`
--

CREATE TABLE `tbl_emp_skills` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `skill_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_emp_skills`
--

INSERT INTO `tbl_emp_skills` (`id`, `user_id`, `skill_name`) VALUES
(2, 17, 'HTML');

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
(20, 17, 14, '2025-04-11 01:06:08', 'pending'),
(21, 19, 14, '2025-05-08 00:55:05', 'pending'),
(22, 17, 34, '2025-05-08 01:07:07', 'pending'),
(23, 17, 34, '2025-05-08 01:07:20', 'pending'),
(24, 17, 33, '2025-05-08 01:07:32', 'pending'),
(25, 17, 16, '2025-05-08 01:07:41', 'pending'),
(26, 17, 32, '2025-05-22 10:54:38', 'applied'),
(27, 19, 34, '2025-05-08 01:09:43', 'pending'),
(32, 19, 33, '2025-05-08 01:18:40', 'pending'),
(33, 19, 32, '2025-05-22 10:29:43', 'reviewed'),
(34, 19, 16, '2025-05-08 01:19:25', 'pending'),
(35, 17, 19, '2025-05-08 01:24:42', 'pending'),
(36, 20, 34, '2025-05-08 04:10:28', 'pending'),
(37, 21, 32, '2025-05-22 10:29:26', 'awaiting'),
(38, 27, 32, '2025-05-22 07:04:12', 'contacted'),
(39, 29, 32, '2025-05-22 07:04:18', 'hired'),
(40, 17, 17, '2025-05-26 01:36:25', 'applied');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_job_application_files`
--

CREATE TABLE `tbl_job_application_files` (
  `id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `file_inserted` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_job_application_files`
--

INSERT INTO `tbl_job_application_files` (`id`, `application_id`, `file_inserted`) VALUES
(7, 20, 'R1Fu6T4r_KATHERINE CUETO-RESUME (1).pdf'),
(8, 21, 'zsQd74Jl_IMRAD_Development of a Machine Learning Model Integrated in a Mobile App to.pdf'),
(9, 32, 'zsQd74Jl_IMRAD_Development of a Machine Learning Model Integrated in a Mobile App to.pdf'),
(10, 33, 'zsQd74Jl_IMRAD_Development of a Machine Learning Model Integrated in a Mobile App to.pdf'),
(11, 34, 'zsQd74Jl_IMRAD_Development of a Machine Learning Model Integrated in a Mobile App to.pdf'),
(12, 35, 'R1Fu6T4r_KATHERINE CUETO-RESUME (1).pdf'),
(13, 36, 'eDDobnVb_JMCY 3IN1.pdf'),
(14, 37, 'phec1T6q_FOR LOST11 FILLING.pdf'),
(15, 38, 'f2733ca46f26a312_ADE-LABEL.pdf'),
(16, 39, 'aa78e6e6992612a2_cole1.pdf'),
(17, 40, 'R1Fu6T4r_KATHERINE CUETO-RESUME (1).pdf');

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
(4, 'Engineering'),
(5, 'Healthcare'),
(6, 'Information Technology');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_job_coordinates`
--

CREATE TABLE `tbl_job_coordinates` (
  `id` int(11) NOT NULL,
  `coordinates` point NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_job_coordinates`
--

INSERT INTO `tbl_job_coordinates` (`id`, `coordinates`) VALUES
(1, 0x00000000010100000002ab1b08383f5e409758616c78332d40);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_job_listing`
--

CREATE TABLE `tbl_job_listing` (
  `job_id` int(11) NOT NULL,
  `employer_id` int(11) NOT NULL,
  `job_cover_img` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `requirements` text NOT NULL,
  `employment_type` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `coordinate_id` int(11) DEFAULT NULL,
  `salary_min` decimal(11,2) NOT NULL,
  `salary_max` decimal(11,2) NOT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `posted_date` timestamp NULL DEFAULT NULL,
  `expiry_date` timestamp NULL DEFAULT NULL,
  `status` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_job_listing`
--

INSERT INTO `tbl_job_listing` (`job_id`, `employer_id`, `job_cover_img`, `title`, `description`, `requirements`, `employment_type`, `location`, `coordinate_id`, `salary_min`, `salary_max`, `currency`, `category_id`, `posted_date`, `expiry_date`, `status`) VALUES
(3, 6, NULL, 'jab', 'qqqqqq', 'tyuqwerty', 'Contract', '32323', NULL, 310.00, 360.00, 'dollar', 2, '2025-02-18 16:00:00', '2025-02-27 16:00:00', 'paused'),
(4, 6, NULL, 'job 4', 'geng geng', 'madami frfr', 'Internship', '4', NULL, 4.00, 4.00, '4', 3, '2025-02-18 16:00:00', '2025-02-16 16:00:00', 'inactive'),
(5, 6, NULL, 'poso negro', '5 cent', '5tyrrrrrrrrrrr', 'Contract', 'dito', NULL, 5.00, 5.00, '$', 4, '2025-02-18 16:00:00', '2025-02-17 16:00:00', 'inactive'),
(7, 6, NULL, 'IT professional', 'magaling it', 'it maalam', 'Full time', 'san pablo', NULL, 12.00, 120.00, 'php', 4, '2025-02-18 16:00:00', '2025-02-25 16:00:00', 'inactive'),
(12, 9, NULL, 'mikco it', 'it', 'it', 'Part-Time', '3', NULL, 19.00, 80.00, 'p', 1, '2025-03-17 16:00:00', '2025-03-25 16:00:00', 'inactive'),
(13, 6, NULL, 'ass', 'a', 'b', 'Part-Time', 'san pablo cityyy', NULL, 1.00, 2.00, 'php', 2, '2025-03-26 16:00:00', '2025-03-28 16:00:00', 'paused'),
(14, 6, NULL, 'Software Engineer (Full Stack)', 'Develop and maintain scalable web applications using React.js, Node.js, and PostgreSQL.', '3+ years experience, JavaScript/TypeScript, Git, API development.', 'Full-Time', 'San Pablo City', NULL, 80.00, 110.00, 'php', 6, '2025-03-27 16:00:00', '2025-03-21 16:00:00', 'active'),
(15, 6, NULL, 'house', 'aa', 'ff', 'Full-time', 'san pablo cityyy', NULL, 1.00, 2.00, 'php', 1, '2025-03-27 16:00:00', '2025-03-27 16:00:00', 'paused'),
(16, 6, NULL, 'Web Developer', 'Must know how to debug using python, pycharm and html.', 'Resume\r\n5 years experienced\r\n', 'Internship', 'San Pablo City', NULL, 15000.00, 50000.00, 'php', 6, '2025-04-01 16:00:00', '2025-03-31 16:00:00', 'active'),
(17, 11, NULL, 'Digital Marketing Specialist', 'Plan and execute digital marketing campaigns, including SEO, PPC, and social media ads.', 'Google Ads, Facebook Ads, SEO, content writing.', 'Contract', 'San Pablo City', NULL, 30.00, 40.00, 'php', 4, '2025-04-01 16:00:00', '2025-04-24 16:00:00', 'active'),
(18, 12, NULL, 'Customer Service Representative', 'Provide customer support via chat, email, and phone. Maintain customer satisfaction.', 'Provide customer support via chat, email, and phone. Maintain customer satisfaction.', 'Full-Time', 'San Pablo City', NULL, 18.00, 22.00, 'php', 2, '2025-04-01 16:00:00', '2025-04-29 16:00:00', 'active'),
(19, 6, NULL, 'Registered Nurse (RN)', 'Provide patient care, monitor vital signs, and coordinate with doctors.', 'Valid RN license, BLS/CPR certification, 1+ year experience.', 'Part-Time', 'San Pablo City', NULL, 20000.00, 500000.00, 'PHP', 5, '2025-04-02 16:00:00', '2025-04-28 16:00:00', 'active'),
(20, 6, '5f93b1c5da49ce65_1744684439.png', 'Capitolyo', 'yeasss', 'wwwww', 'Full-Time', 'san pablo cityyy', NULL, 1.00, 2.00, 'php', 2, '2025-04-14 16:00:00', '2025-04-29 16:00:00', 'paused'),
(30, 6, 'f77dd11bbfe40706_1744701773.png', 'Capitolyo12', 'yeasss', 'wwwww', 'Part-Time', 'san pablo cityyy', NULL, 1.00, 2.00, 'php', 2, '2025-04-14 16:00:00', '2025-04-07 16:00:00', 'active'),
(31, 6, 'dc5b51784e9d5d4b_1745218193.png', 'Job Test na Naman', 'fill', 'out', 'Full-Time', 'Quezon', NULL, 1.00, 2.00, 'php', 2, '2025-04-20 16:00:00', '2025-04-23 16:00:00', 'paused'),
(32, 6, '0c2beb6d93de2f8f_1745995998.jpg', 'Accountant', 'accounting', '2 year experience', 'Full-Time', 'San Pablo City', NULL, 1.00, 2.00, 'php', 2, '2025-04-23 16:00:00', '2025-04-09 16:00:00', 'active'),
(33, 6, '84278cd2c84afb2c_1745982931.png', 'house keeping attendant', 'house keeper', '1 year exp', 'Part-Time', 'San Pablo City', NULL, 150.00, 300.00, 'php', 1, '2025-04-29 16:00:00', '2025-05-10 16:00:00', 'active'),
(34, 6, '3791244bde5cbe64_1746174257.jpg', 'Aircon Specialist', 'none', 'aircon', 'Contract', '609, Bilibid Viejo Street, 391, Quiapo, Third District, Manila, Capital District, Metro Manila, 1001, Philippines', 1, 1.00, 2.00, 'php', 6, '2025-05-01 16:00:00', '2025-05-27 16:00:00', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_job_message`
--

CREATE TABLE `tbl_job_message` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `comp_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `subject` varchar(64) NOT NULL,
  `message` text NOT NULL,
  `is_seen` tinyint(1) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_job_message`
--

INSERT INTO `tbl_job_message` (`id`, `emp_id`, `comp_id`, `application_id`, `subject`, `message`, `is_seen`, `timestamp`) VALUES
(1, 17, 6, 26, 'Interview', 'Contact us at g@gmail.com', 0, '2025-05-22 10:35:46'),
(2, 17, 6, 26, 'Interview', 'message me', 0, '2025-05-22 10:40:52'),
(3, 20, 6, 36, 'test', 'hehe', 0, '2025-05-22 11:19:49');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_job_notifications`
--

CREATE TABLE `tbl_job_notifications` (
  `notification_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_job_notifications`
--

INSERT INTO `tbl_job_notifications` (`notification_id`, `company_id`, `job_id`, `message`, `is_read`, `created_at`) VALUES
(1, 14, 14, 'A new application has been submitted for your job listing.', 0, '2025-05-08 00:55:05'),
(12, 6, 33, 'A new application has been submitted for your job listing.', 1, '2025-05-08 01:18:40'),
(13, 6, 32, 'A new application has been submitted for your job listing.', 1, '2025-05-08 01:18:48'),
(14, 6, 16, 'A new application has been submitted for your job listing.', 1, '2025-05-08 01:19:25'),
(15, 6, 19, 'A new application has been submitted for your job listing: Registered Nurse (RN).', 0, '2025-05-08 01:24:42'),
(16, 6, 34, 'A new application has been submitted for your job listing: Aircon Specialist.', 0, '2025-05-08 04:10:28'),
(17, 6, 32, 'A new application has been submitted for your job listing: Accountant.', 0, '2025-05-14 06:27:00'),
(18, 6, 32, 'A new application has been submitted for your job listing: Accountant.', 0, '2025-05-22 06:28:45'),
(19, 6, 32, 'A new application has been submitted for your job listing: Accountant.', 0, '2025-05-22 07:02:24'),
(20, 11, 17, 'A new application has been submitted for your job listing: Digital Marketing Specialist.', 0, '2025-05-26 01:36:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tbl_admin_login`
--
ALTER TABLE `tbl_admin_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `adminlogfk` (`admin_id`);

--
-- Indexes for table `tbl_comp_info`
--
ALTER TABLE `tbl_comp_info`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `tbl_comp_login`
--
ALTER TABLE `tbl_comp_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `tbl_comp_verification`
--
ALTER TABLE `tbl_comp_verification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comp_verID` (`comp_id`);

--
-- Indexes for table `tbl_emp_careerhistory`
--
ALTER TABLE `tbl_emp_careerhistory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empcareerfk` (`user_id`);

--
-- Indexes for table `tbl_emp_category_preferences`
--
ALTER TABLE `tbl_emp_category_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empcattoemp` (`emp_id`),
  ADD KEY `catpreftocat` (`category_id`);

--
-- Indexes for table `tbl_emp_certification`
--
ALTER TABLE `tbl_emp_certification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empcertfk` (`user_id`);

--
-- Indexes for table `tbl_emp_cv`
--
ALTER TABLE `tbl_emp_cv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empcvfk` (`emp_id`);

--
-- Indexes for table `tbl_emp_educback`
--
ALTER TABLE `tbl_emp_educback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empeducfk` (`user_id`);

--
-- Indexes for table `tbl_emp_info`
--
ALTER TABLE `tbl_emp_info`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tbl_emp_language`
--
ALTER TABLE `tbl_emp_language`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emplanguagefk` (`user_id`);

--
-- Indexes for table `tbl_emp_login`
--
ALTER TABLE `tbl_emp_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emploginfk` (`user_id`);

--
-- Indexes for table `tbl_emp_resume`
--
ALTER TABLE `tbl_emp_resume`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresumefk` (`user_id`);

--
-- Indexes for table `tbl_emp_saved_jobs`
--
ALTER TABLE `tbl_emp_saved_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empsavefk` (`user_id`),
  ADD KEY `jobsavefk` (`job_id`);

--
-- Indexes for table `tbl_emp_skills`
--
ALTER TABLE `tbl_emp_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empskillfk` (`user_id`);

--
-- Indexes for table `tbl_job_application`
--
ALTER TABLE `tbl_job_application`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empfk` (`emp_id`),
  ADD KEY `jobfk` (`job_id`);

--
-- Indexes for table `tbl_job_application_files`
--
ALTER TABLE `tbl_job_application_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_inserted_dir` (`application_id`);

--
-- Indexes for table `tbl_job_category`
--
ALTER TABLE `tbl_job_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tbl_job_coordinates`
--
ALTER TABLE `tbl_job_coordinates`
  ADD PRIMARY KEY (`id`),
  ADD SPATIAL KEY `coordinates` (`coordinates`);

--
-- Indexes for table `tbl_job_listing`
--
ALTER TABLE `tbl_job_listing`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `catfk` (`category_id`),
  ADD KEY `compfk` (`employer_id`),
  ADD KEY `coordinate_id` (`coordinate_id`);

--
-- Indexes for table `tbl_job_message`
--
ALTER TABLE `tbl_job_message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `comp_id` (`comp_id`),
  ADD KEY `emp_id` (`emp_id`);

--
-- Indexes for table `tbl_job_notifications`
--
ALTER TABLE `tbl_job_notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `job_id` (`job_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_admin_login`
--
ALTER TABLE `tbl_admin_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_comp_info`
--
ALTER TABLE `tbl_comp_info`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_comp_login`
--
ALTER TABLE `tbl_comp_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_comp_verification`
--
ALTER TABLE `tbl_comp_verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_emp_careerhistory`
--
ALTER TABLE `tbl_emp_careerhistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_emp_category_preferences`
--
ALTER TABLE `tbl_emp_category_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_emp_certification`
--
ALTER TABLE `tbl_emp_certification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_emp_cv`
--
ALTER TABLE `tbl_emp_cv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tbl_emp_educback`
--
ALTER TABLE `tbl_emp_educback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_emp_info`
--
ALTER TABLE `tbl_emp_info`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `tbl_emp_language`
--
ALTER TABLE `tbl_emp_language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_emp_login`
--
ALTER TABLE `tbl_emp_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_emp_resume`
--
ALTER TABLE `tbl_emp_resume`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_emp_saved_jobs`
--
ALTER TABLE `tbl_emp_saved_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_emp_skills`
--
ALTER TABLE `tbl_emp_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_job_application`
--
ALTER TABLE `tbl_job_application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `tbl_job_application_files`
--
ALTER TABLE `tbl_job_application_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_job_category`
--
ALTER TABLE `tbl_job_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_job_coordinates`
--
ALTER TABLE `tbl_job_coordinates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_job_listing`
--
ALTER TABLE `tbl_job_listing`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `tbl_job_message`
--
ALTER TABLE `tbl_job_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_job_notifications`
--
ALTER TABLE `tbl_job_notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_admin_login`
--
ALTER TABLE `tbl_admin_login`
  ADD CONSTRAINT `adminlogfk` FOREIGN KEY (`admin_id`) REFERENCES `tbl_admin` (`admin_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_comp_login`
--
ALTER TABLE `tbl_comp_login`
  ADD CONSTRAINT `company_id` FOREIGN KEY (`company_id`) REFERENCES `tbl_comp_info` (`company_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_comp_verification`
--
ALTER TABLE `tbl_comp_verification`
  ADD CONSTRAINT `comp_verID` FOREIGN KEY (`comp_id`) REFERENCES `tbl_comp_info` (`company_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_emp_careerhistory`
--
ALTER TABLE `tbl_emp_careerhistory`
  ADD CONSTRAINT `empcareerfk` FOREIGN KEY (`user_id`) REFERENCES `tbl_emp_info` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_emp_category_preferences`
--
ALTER TABLE `tbl_emp_category_preferences`
  ADD CONSTRAINT `catpreftocat` FOREIGN KEY (`category_id`) REFERENCES `tbl_job_category` (`category_id`),
  ADD CONSTRAINT `empcattoemp` FOREIGN KEY (`emp_id`) REFERENCES `tbl_emp_info` (`user_id`);

--
-- Constraints for table `tbl_emp_certification`
--
ALTER TABLE `tbl_emp_certification`
  ADD CONSTRAINT `empcertfk` FOREIGN KEY (`user_id`) REFERENCES `tbl_emp_info` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_emp_cv`
--
ALTER TABLE `tbl_emp_cv`
  ADD CONSTRAINT `empcvfk` FOREIGN KEY (`emp_id`) REFERENCES `tbl_emp_info` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_emp_educback`
--
ALTER TABLE `tbl_emp_educback`
  ADD CONSTRAINT `empeducfk` FOREIGN KEY (`user_id`) REFERENCES `tbl_emp_info` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_emp_language`
--
ALTER TABLE `tbl_emp_language`
  ADD CONSTRAINT `emplanguagefk` FOREIGN KEY (`user_id`) REFERENCES `tbl_emp_info` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_emp_login`
--
ALTER TABLE `tbl_emp_login`
  ADD CONSTRAINT `emploginfk` FOREIGN KEY (`user_id`) REFERENCES `tbl_emp_info` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_emp_resume`
--
ALTER TABLE `tbl_emp_resume`
  ADD CONSTRAINT `empresumefk` FOREIGN KEY (`user_id`) REFERENCES `tbl_emp_info` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_emp_saved_jobs`
--
ALTER TABLE `tbl_emp_saved_jobs`
  ADD CONSTRAINT `empsavefk` FOREIGN KEY (`user_id`) REFERENCES `tbl_emp_info` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `jobsavefk` FOREIGN KEY (`job_id`) REFERENCES `tbl_job_listing` (`job_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_emp_skills`
--
ALTER TABLE `tbl_emp_skills`
  ADD CONSTRAINT `empskillfk` FOREIGN KEY (`user_id`) REFERENCES `tbl_emp_info` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_job_application`
--
ALTER TABLE `tbl_job_application`
  ADD CONSTRAINT `empfk` FOREIGN KEY (`emp_id`) REFERENCES `tbl_emp_info` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `jobfk` FOREIGN KEY (`job_id`) REFERENCES `tbl_job_listing` (`job_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_job_application_files`
--
ALTER TABLE `tbl_job_application_files`
  ADD CONSTRAINT `file_inserted_dir` FOREIGN KEY (`application_id`) REFERENCES `tbl_job_application` (`id`);

--
-- Constraints for table `tbl_job_listing`
--
ALTER TABLE `tbl_job_listing`
  ADD CONSTRAINT `catfk` FOREIGN KEY (`category_id`) REFERENCES `tbl_job_category` (`category_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `compfk` FOREIGN KEY (`employer_id`) REFERENCES `tbl_comp_info` (`company_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_job_listing_ibfk_1` FOREIGN KEY (`coordinate_id`) REFERENCES `tbl_job_coordinates` (`id`);

--
-- Constraints for table `tbl_job_message`
--
ALTER TABLE `tbl_job_message`
  ADD CONSTRAINT `tbl_job_message_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `tbl_job_application` (`id`),
  ADD CONSTRAINT `tbl_job_message_ibfk_2` FOREIGN KEY (`comp_id`) REFERENCES `tbl_comp_info` (`company_id`),
  ADD CONSTRAINT `tbl_job_message_ibfk_3` FOREIGN KEY (`emp_id`) REFERENCES `tbl_emp_info` (`user_id`);

--
-- Constraints for table `tbl_job_notifications`
--
ALTER TABLE `tbl_job_notifications`
  ADD CONSTRAINT `tbl_job_notifications_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `tbl_comp_info` (`company_id`),
  ADD CONSTRAINT `tbl_job_notifications_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `tbl_job_listing` (`job_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
