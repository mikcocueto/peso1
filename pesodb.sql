-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2025 at 10:27 AM
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
(6, 'ggg shan', 'khyle', 'FDS Asya', 'USA', 1111111111, '2025-04-02 01:21:33', '../db/images/company/logo/fds.png', 1),
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
(14, 18, 'OJT', 'MIS', '2025-03-02', '2025-03-28', 1, '');

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
(5, 17, 6);

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
(15, 17, 'R1Fu6T4r_KATHERINE CUETO-RESUME (1).pdf', '1', '../../db/pdf/emp_cv/', '2025-04-08 07:13:19');

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
  `pfp_dir` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_emp_info`
--

INSERT INTO `tbl_emp_info` (`user_id`, `firstName`, `lastName`, `address`, `emailAddress`, `gender`, `mobileNumber`, `create_timestamp`, `pfp_dir`) VALUES
(10, 'eric', 'eric', '', 'eric@gmail.com', '', '', '2025-02-12 04:38:54', ''),
(11, 'Mikco', 'Cueto', '', 'cuetomikco08@gmail.com', '', '', '2025-02-12 04:39:53', ''),
(12, 'justine', 'justine', 'aa', 'justine@gmail.com', 'dd', '111', '2025-02-12 05:33:23', ''),
(13, 'Mikco', 'Cueto', 'aaaa', 'cueto@gmail.com', 'a', '', '2025-02-13 04:43:07', ''),
(14, 'Mikco', 'Cueto', 'Sta. Maria SPC', 'c@gmail.com', 'Female', '099999999999', '2025-02-14 03:58:48', ''),
(15, 'q', 'q', '', 'q@gmail.com', '', '', '2025-02-17 05:23:39', ''),
(16, 'new', 'user', '', 'new@gmail.com', '', '', '2025-02-24 06:25:21', ''),
(17, 'shan', 'test', 'cavite', 'shan@gmail.com', 'male', '911', '2025-03-07 03:25:23', ''),
(18, 'MIKCO', 'Mikco', 'Purok 7 Brgy Sta Maria', '0321-2980@lspu.edu.ph', 'You Decide', '9076532552', '2025-03-18 04:05:12', '');

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
(17, 18, 'mikco@gmail.com', '$2y$10$IZtDy.Uv/8GkR57lgrlYEeoZNHAMEMPFfXwPU2LEox0giNwwHjo3e', 'f6f6e65887253d2b56440b9d76394d42');

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
(14, 17, 14);

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
(20, 17, 14, '2025-04-11 01:06:08', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_job_application_files`
--

CREATE TABLE `tbl_job_application_files` (
  `id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `file_inserted_dir` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_job_application_files`
--

INSERT INTO `tbl_job_application_files` (`id`, `application_id`, `file_inserted_dir`) VALUES
(7, 20, '../../db/pdf/application_files/R1Fu6T4r_KATHERINE CUETO-RESUME (1).pdf');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_emp_category_preferences`
--
ALTER TABLE `tbl_emp_category_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_emp_certification`
--
ALTER TABLE `tbl_emp_certification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_emp_cv`
--
ALTER TABLE `tbl_emp_cv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_emp_educback`
--
ALTER TABLE `tbl_emp_educback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_emp_info`
--
ALTER TABLE `tbl_emp_info`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_emp_language`
--
ALTER TABLE `tbl_emp_language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_emp_login`
--
ALTER TABLE `tbl_emp_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_emp_resume`
--
ALTER TABLE `tbl_emp_resume`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_emp_saved_jobs`
--
ALTER TABLE `tbl_emp_saved_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_emp_skills`
--
ALTER TABLE `tbl_emp_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_job_application`
--
ALTER TABLE `tbl_job_application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_job_application_files`
--
ALTER TABLE `tbl_job_application_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
