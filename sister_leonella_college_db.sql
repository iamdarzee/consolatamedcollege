-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2024 at 01:22 PM
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
-- Database: `sister_leonella_college_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `password_hash`, `created_at`) VALUES
(42001, '$2y$10$sok922KkRG.ZgBR3CZ5pY.J5aruPE00d0y.cSZgk904CuTsG2mXeq', '2024-10-20 10:46:06');

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `log_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`log_id`, `admin_id`, `action`, `target_id`, `ip_address`, `timestamp`) VALUES
(1, 42001, 'delete_attempt', 2, '::1', '2024-10-20 10:50:39');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `courses_applied` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`courses_applied`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `requirements` text NOT NULL,
  `duration` varchar(100) NOT NULL,
  `fee` varchar(10) NOT NULL,
  `intake_start` date NOT NULL,
  `intake_end` date NOT NULL,
  `register_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `category`, `name`, `requirements`, `duration`, `fee`, `intake_start`, `intake_end`, `register_date`) VALUES
(1, 'Certificate in Emergency Medical Technician (EMT)', 'Emergency Medical Services Courses', 'KCSE Mean Grade D+ with a D in Biology.', ' 1 year', '45000.00', '2024-10-01', '2024-10-31', '2024-10-19 14:57:20'),
(2, 'Nursing Courses', 'Higher Diploma in Nephrology', 'BSC, KRCHN/KRN/MHP/KRNM, and 1 year of Clinical Experience.\r\n', '18 months', '67000.00', '2024-10-01', '2024-11-01', '2024-10-19 12:26:06'),
(3, 'Theatre Technology', 'Certificate in Perioperative Theatre Technology', 'KCSE D and Above.', '1 Year', '70000.00', '2024-10-01', '2024-10-31', '2024-10-19 14:25:22'),
(4, 'Nursing Courses', 'Diploma in Kenya Registered Nursing and Midwifery', 'KCSE C and Above with C and Above in Eng/Kis and Bio. C- and above in either Mat, Che, or Phy.', '3 Years', '180000.00', '2024-10-01', '2024-11-29', '2024-10-19 12:49:44'),
(5, 'Theatre Technology', 'Diploma in Perioperative Theatre Technology', 'KCSE C- and Above.\r\n', '2 1/2 Years', '80000.00', '2024-10-01', '2024-10-31', '2024-10-19 14:24:11'),
(6, 'Health Information Management Courses', 'Diploma in Health Records and Information Technology', 'KCSE Mean Grade C- with a C in Mathematics and English.', '2 years', '60000.00', '2024-10-01', '2024-10-31', '2024-10-19 14:55:53'),
(7, 'Laboratory Courses', 'Diploma in Medical Laboratory Technology', 'KCSE Mean Grade C- with a C in Biology and Chemistry.', '3 years', '75000.00', '2024-10-01', '2024-10-31', '2024-10-19 14:49:00'),
(8, 'Pharmacy Courses', 'Certificate in Pharmacy Assistance', 'KCSE Mean Grade C- with a minimum of C in Biology and Chemistry.', '1 year Fee', '50000.00', '2024-10-01', '2024-10-31', '2024-10-19 14:51:05'),
(9, 'Public Health Courses', 'Bachelor of Science in Public Health', 'KCSE Mean Grade C+ (or equivalent) with C in Biology and Mathematics.', '4 years', '250000.00', '2024-10-01', '2024-10-31', '2024-10-19 14:52:21'),
(10, 'Nursing Courses', 'Diploma in Kenya Registered Community Health Nurse', 'KCSE C and Above with C and Above in Eng/Kis and Bio. C- and above in either Mat, Che, or Phy.', '3 Years', '80000.00', '2024-10-01', '2024-11-01', '2024-10-19 12:27:43'),
(11, 'Nursing Courses', 'Higher Diploma in Critical Care Nursing', 'BSC, KRCHN/KRN/MHP/KRNM, and 1 year of Clinical Experience.', '18 months', '50000.00', '2024-10-01', '2024-11-01', '2024-10-19 11:53:27');

-- --------------------------------------------------------

--
-- Table structure for table `my_applications`
--

CREATE TABLE `my_applications` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `courses_applied` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`courses_applied`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `role` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `register_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `staff_name`, `email`, `phone_number`, `role`, `password`, `register_date`) VALUES
(1, 'Curtis Darzee', 'iamdarzee@gmail.com', '+254702819676', 'I.T', '$2y$10$Zxze7ov1FZdJd58lomAKv.XrynH1ePxKkdZ4COOLjYRvukHrJ/Oy6', '2024-06-05 07:59:19'),
(2, 'Fred Rick', 'fredrick@email.com', '+25467673452', 'HR Manager', '$2y$10$4H1.fEWcEUCY8fkFAOngtekKe1PmxAUtKfgyRLBGhKsTkSmOLK/vK', '2024-07-16 08:28:02'),
(3, 'Sister Mary', 'sistermary@gmail.com', '254722564235', 'Nurse', '$2y$10$qRrSoAZiqmyLjIjTVQ8OTePE5fhkipRioiy7kmdotmPhsPqwX1QjC', '2024-10-20 10:10:04');

-- --------------------------------------------------------

--
-- Table structure for table `student_accounts`
--

CREATE TABLE `student_accounts` (
  `account_id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `register_date` date DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `alt_phone` varchar(15) DEFAULT NULL,
  `emergency_name` varchar(255) DEFAULT NULL,
  `emergency_phone` varchar(15) DEFAULT NULL,
  `emergency_relationship` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(40) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `id_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_accounts`
--

INSERT INTO `student_accounts` (`account_id`, `email`, `register_date`, `phone_number`, `alt_phone`, `emergency_name`, `emergency_phone`, `emergency_relationship`, `password`, `address`, `name`, `dob`, `gender`, `id_number`) VALUES
(1, 'mambocurtis92@gmail.com', '2024-07-17', '254712684167', '254702819676', 'Zara Hadid', '254789908765', 'Mother', '$2y$10$f61m/4y2vf.S67RhXbRB7.dMF.t5XjQ.jrwKz8jbZKYh17QZXOVjK', 'XQ3P-5VJ Kisumu County', 'Curtis Odhiambo', '1992-11-21', 'Male', '31524005'),
(2, 'fred@matano.com', '2024-10-19', '254727657345', '254789345678', 'Joshua Matano', '25445678234', 'Father', '$2y$10$WnLcmVVZtibK5NUusfS9QOj3ZiyjqKiODv3X24m4PcKA6YIJ7dkhK', 'P.0 BOX 2345 Voi, Kenya', 'Fred Matano', '2004-11-21', 'male', '56786523'),
(3, 'monicadre@email.com', '2024-10-19', '254734234567', '2547078536785', 'Mary Dree', '25476897123', 'Mother', '$2y$10$VCLUdNxhntQ/HAasRtuQ6e6hxsT2h..u4iYDTO18jNw3cuSnlEb/q', 'P.O BOX 7865 Karatina, Kenya', 'Monica Dre', '2004-04-04', 'Male', '56789005'),
(4, 'tony@snow.com', '2024-10-20', '25467345123', '254745789654', 'Vero Snow', '25490765286', 'Mother', '$2y$10$lS0t4S8wStwRsieGGuCuLOeFkF2hwp7iK2dTyGIoIePKHj4bNuqZ.', 'Hunters Road, Kasarani', 'Tony Snow', '2006-06-06', 'male', '67674567');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `my_applications`
--
ALTER TABLE `my_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `student_accounts`
--
ALTER TABLE `student_accounts`
  ADD PRIMARY KEY (`account_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `my_applications`
--
ALTER TABLE `my_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `student_accounts`
--
ALTER TABLE `student_accounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`);

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_accounts` (`account_id`);

--
-- Constraints for table `my_applications`
--
ALTER TABLE `my_applications`
  ADD CONSTRAINT `my_applications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_accounts` (`account_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
