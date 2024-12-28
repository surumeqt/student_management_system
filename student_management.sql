-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2024 at 02:31 PM
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
-- Database: `student_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$DtfRTAt.x3ryqNUYeNLG7.BXv.ulDls8/YPNNO7yvRLoQpGYysg9.');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `course_availability` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `course_availability`) VALUES
(1, 'BS in COMPUTER SCIENCE', 2),
(2, 'BS in INFORMATION TECHNOLOGY', 5),
(3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 5);

-- --------------------------------------------------------

--
-- Table structure for table `student_202412162`
--

CREATE TABLE `student_202412162` (
  `id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_code` varchar(10) NOT NULL,
  `subject_units` int(11) NOT NULL,
  `grade` decimal(5,2) DEFAULT NULL,
  `date_recorded` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_202412162`
--

INSERT INTO `student_202412162` (`id`, `subject_name`, `subject_code`, `subject_units`, `grade`, `date_recorded`) VALUES
(1, 'Introduction to Multimedia Computing', 'EMC101', 3, NULL, '2024-12-14 12:23:02'),
(2, '2D Animation Principles', 'EMC102', 3, NULL, '2024-12-14 12:23:02'),
(3, 'Introduction to Multimedia Arts', 'BSEMC101', 3, NULL, '2024-12-14 12:23:02'),
(4, 'Creative Design Principles', 'BSEMC102', 3, NULL, '2024-12-14 12:23:02'),
(5, 'Fundamentals of Animation', 'BSEMC103', 3, NULL, '2024-12-14 12:23:02'),
(6, 'Basic Video Production', 'BSEMC104', 3, NULL, '2024-12-14 12:23:02'),
(7, 'Digital Photography', 'BSEMC105', 3, NULL, '2024-12-14 12:23:02');

-- --------------------------------------------------------

--
-- Table structure for table `student_202412268`
--

CREATE TABLE `student_202412268` (
  `id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_code` varchar(10) NOT NULL,
  `subject_units` int(11) NOT NULL,
  `grade` decimal(5,2) DEFAULT NULL,
  `date_recorded` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_202412268`
--

INSERT INTO `student_202412268` (`id`, `subject_name`, `subject_code`, `subject_units`, `grade`, `date_recorded`) VALUES
(1, 'Artificial Intelligence', 'CS401', 3, NULL, '2024-12-14 12:01:17'),
(2, 'Capstone Project', 'BSCS401', 3, NULL, '2024-12-14 12:01:17'),
(3, 'Human-Computer Interaction', 'BSCS402', 3, NULL, '2024-12-14 12:01:17'),
(4, 'Information Security', 'BSCS403', 3, NULL, '2024-12-14 12:01:17'),
(5, 'Big Data Analytics', 'BSCS404', 3, NULL, '2024-12-14 12:01:17'),
(6, 'Entrepreneurship in IT', 'BSCS405', 3, NULL, '2024-12-14 12:01:17');

-- --------------------------------------------------------

--
-- Table structure for table `student_credential`
--

CREATE TABLE `student_credential` (
  `studentID` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `status` enum('approved','pending','denied','dropped','notSet') DEFAULT 'notSet'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_credential`
--

INSERT INTO `student_credential` (`studentID`, `email`, `password`, `status`) VALUES
(202412162, 'Lelouchdouche@gmail.com', '$2y$10$7rNICwTgV5X6B02yT/ZPYOjIzyjhd.kFKDXxkzuKkOw/adlvAeNFi', 'pending'),
(202412268, 'esmenagohan1@gmail.com', '$2y$10$yymU1w3G9e40XJB7yOJyaO4S3O7Shosdl6/SlPD.MegK63BtIRKJi', 'pending'),
(202412536, 'leywinarthur442@gmail.com', '$2y$10$678rhVjh0ShijXoAhMmvNu5iDyyYyKfcaImXTvZg223RVDhH.s9H2', 'notSet'),
(202412885, 'esmenakenneth33@gmail.com', '$2y$10$NAYtnxubUpGMmBHJDSMMiuNV2r1xMGc4jt6USODamQkLf4gDVMTSK', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `student_profiles`
--

CREATE TABLE `student_profiles` (
  `studentID` int(11) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `place_of_birth` varchar(255) DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `citizenship` varchar(100) DEFAULT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `year_level` varchar(50) DEFAULT '1',
  `elementary_school` varchar(255) DEFAULT NULL,
  `elementary_graduation_year` varchar(4) DEFAULT NULL,
  `high_school` varchar(255) DEFAULT NULL,
  `high_school_graduation_year` varchar(4) DEFAULT NULL,
  `strand` varchar(50) DEFAULT NULL,
  `fathers_name` varchar(255) DEFAULT NULL,
  `fathers_occupation` varchar(255) DEFAULT NULL,
  `fathers_mobile` varchar(15) DEFAULT NULL,
  `mothers_name` varchar(255) DEFAULT NULL,
  `mothers_occupation` varchar(255) DEFAULT NULL,
  `mothers_mobile` varchar(15) DEFAULT NULL,
  `number_of_siblings` int(11) DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_phone` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reason_for_course` text NOT NULL,
  `status` enum('pending','approved','denied','dropped','notSet') DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_profiles`
--

INSERT INTO `student_profiles` (`studentID`, `last_name`, `first_name`, `middle_name`, `address`, `dob`, `place_of_birth`, `gender`, `citizenship`, `mobile_number`, `year_level`, `elementary_school`, `elementary_graduation_year`, `high_school`, `high_school_graduation_year`, `strand`, `fathers_name`, `fathers_occupation`, `fathers_mobile`, `mothers_name`, `mothers_occupation`, `mothers_mobile`, `number_of_siblings`, `guardian_name`, `guardian_phone`, `created_at`, `reason_for_course`, `status`, `course_id`) VALUES
(202412162, 'Setunass', 'John Kenneth', 'Almazan', '1067 Sampaloc comp. Santa Rita', '1212-12-21', 'lubao, pampanga', 'Male', 'filipino', '09508446560', '1', 'HALAMAN ELEM SCHOOL', '2009', 'SFILBHS', '2021', 'stem', 'sadas', 'das', 'ds', 'asdasd', 'dsdas', 'asdasd', 1, 'ds', 'asdad', '2024-12-14 12:18:31', 'dasfasfasfasfaaf', 'approved', 3),
(202412268, 'Setuna', 'John Kenneth', 'Almazan', '1067 Sampaloc comp. Santa Rita', '2002-11-04', 'lubao, pampanga', 'Male', 'filipino', '09508446560', '4', 'HALAMAN ELEM SCHOOL', '2009', 'SFILBHS', '2021', 'stem', 'dsadas', 'dasd', 'das', 'sdasdsa', 'asdas', 'dasdas', 21, 'dasdas', 'dasda', '2024-12-14 11:54:24', 'fassdasdasdas', 'approved', 1),
(202412536, 'majinbuusSAadas', 'romsdsaS', 'lagundi', '1067 Sampaloc comp. Santa Rita', '1111-02-12', 'lubao, pampanga', 'Male', 'asdasdas', '09508446560', '1', 'HALAMAN ELEM SCHOOL', '2009', 'SFILBHS', '2021', 'stem', 'dasdassdadsa', 'sadasd', 'adass', 'dasdas', 'dsadsa', 'dsadas', 21, 'asdas', 'sadasd', '2024-12-14 12:46:00', '', NULL, NULL),
(202412885, 'dragneelss', 'natsu', '\'dragon slayer\'', '1067 Sampaloc comp. Santa Rita', '4444-05-24', 'lubao, pampanga', 'Male', 'filipino', '09508446560', '1', 'HALAMAN ELEM SCHOOL', '2009', 'SFILBHS', '2021', 'stem', 'sdasda', 'sada', 'asd', 'sdas', 'sdasd', 'asdasd', 212, 'asdas', 'asdasd', '2024-12-14 12:27:09', 'asdasdasdas', 'pending', 3);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_code` varchar(10) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_units` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `course` varchar(100) NOT NULL,
  `year_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_code`, `subject_name`, `subject_units`, `course_id`, `course`, `year_level`) VALUES
(1, 'CS101', 'Introduction to Computer Science', 3, 1, 'BS in COMPUTER SCIENCE', 1),
(2, 'CS201', 'Data Structures', 3, 1, 'BS in COMPUTER SCIENCE', 2),
(3, 'CS301', 'Operating Systems', 4, 1, 'BS in COMPUTER SCIENCE', 3),
(4, 'CS401', 'Artificial Intelligence', 3, 1, 'BS in COMPUTER SCIENCE', 4),
(5, 'IT101', 'Introduction to Information Technology', 3, 2, 'BS in INFORMATION TECHNOLOGY', 1),
(6, 'IT102', 'Network Fundamentals', 4, 2, 'BS in INFORMATION TECHNOLOGY', 2),
(7, 'IT201', 'Database Management Systems', 3, 2, 'BS in INFORMATION TECHNOLOGY', 3),
(8, 'IT301', 'Cybersecurity', 3, 2, 'BS in INFORMATION TECHNOLOGY', 4),
(9, 'EMC101', 'Introduction to Multimedia Computing', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 1),
(10, 'EMC102', '2D Animation Principles', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 1),
(11, 'EMC201', '3D Modeling and Animation', 4, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 2),
(12, 'EMC202', 'Game Design Principles', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 2),
(13, 'EMC301', 'Advanced Game Development', 4, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 3),
(14, 'EMC302', 'Interactive Media', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 3),
(15, 'EMC401', 'Capstone Project in Multimedia', 6, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 4),
(16, 'EMC402', 'Digital Post-Production', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 4),
(17, 'BSCS101', 'Introduction to Computing', 3, 1, 'BS in COMPUTER SCIENCE', 1),
(18, 'BSCS102', 'Programming Fundamentals', 3, 1, 'BS in COMPUTER SCIENCE', 1),
(19, 'BSCS103', 'Discrete Mathematics', 3, 1, 'BS in COMPUTER SCIENCE', 1),
(20, 'BSCS104', 'Digital Logic Design', 3, 1, 'BS in COMPUTER SCIENCE', 1),
(21, 'BSCS105', 'Web Development Basics', 3, 1, 'BS in COMPUTER SCIENCE', 1),
(22, 'BSCS201', 'Data Structures and Algorithms', 3, 1, 'BS in COMPUTER SCIENCE', 2),
(23, 'BSCS202', 'Computer Organization and Architecture', 3, 1, 'BS in COMPUTER SCIENCE', 2),
(24, 'BSCS203', 'Operating Systems', 3, 1, 'BS in COMPUTER SCIENCE', 2),
(25, 'BSCS204', 'Database Management Systems', 3, 1, 'BS in COMPUTER SCIENCE', 2),
(26, 'BSCS205', 'Software Engineering', 3, 1, 'BS in COMPUTER SCIENCE', 2),
(27, 'BSIT101', 'Introduction to Information Technology', 3, 2, 'BS in INFORMATION TECHNOLOGY', 1),
(28, 'BSIT102', 'Computer Hardware Fundamentals', 3, 2, 'BS in INFORMATION TECHNOLOGY', 1),
(29, 'BSIT103', 'Fundamentals of Networking', 3, 2, 'BS in INFORMATION TECHNOLOGY', 1),
(30, 'BSIT104', 'Programming Basics', 3, 2, 'BS in INFORMATION TECHNOLOGY', 1),
(31, 'BSIT105', 'IT Mathematics', 3, 2, 'BS in INFORMATION TECHNOLOGY', 1),
(32, 'BSIT201', 'System Analysis and Design', 3, 2, 'BS in INFORMATION TECHNOLOGY', 2),
(33, 'BSIT202', 'Network Administration', 3, 2, 'BS in INFORMATION TECHNOLOGY', 2),
(34, 'BSIT203', 'Web Design and Development', 3, 2, 'BS in INFORMATION TECHNOLOGY', 2),
(35, 'BSIT204', 'IT Project Management', 3, 2, 'BS in INFORMATION TECHNOLOGY', 2),
(36, 'BSIT205', 'Cybersecurity Fundamentals', 3, 2, 'BS in INFORMATION TECHNOLOGY', 2),
(37, 'BSEMC101', 'Introduction to Multimedia Arts', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 1),
(38, 'BSEMC102', 'Creative Design Principles', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 1),
(39, 'BSEMC103', 'Fundamentals of Animation', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 1),
(40, 'BSEMC104', 'Basic Video Production', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 1),
(41, 'BSEMC105', 'Digital Photography', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 1),
(42, 'BSEMC201', '3D Modeling and Animation', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 2),
(43, 'BSEMC202', 'Game Design Fundamentals', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 2),
(44, 'BSEMC203', 'Interactive Media Design', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 2),
(45, 'BSEMC204', 'Sound Design and Production', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 2),
(46, 'BSEMC205', 'Web-Based Multimedia Applications', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 2),
(47, 'BSCS301', 'Artificial Intelligence', 3, 1, 'BS in COMPUTER SCIENCE', 3),
(48, 'BSCS302', 'Computer Networks', 3, 1, 'BS in COMPUTER SCIENCE', 3),
(49, 'BSCS303', 'Mobile Application Development', 3, 1, 'BS in COMPUTER SCIENCE', 3),
(50, 'BSCS304', 'Cloud Computing', 3, 1, 'BS in COMPUTER SCIENCE', 3),
(51, 'BSCS305', 'Advanced Database Systems', 3, 1, 'BS in COMPUTER SCIENCE', 3),
(52, 'BSCS401', 'Capstone Project', 3, 1, 'BS in COMPUTER SCIENCE', 4),
(53, 'BSCS402', 'Human-Computer Interaction', 3, 1, 'BS in COMPUTER SCIENCE', 4),
(54, 'BSCS403', 'Information Security', 3, 1, 'BS in COMPUTER SCIENCE', 4),
(55, 'BSCS404', 'Big Data Analytics', 3, 1, 'BS in COMPUTER SCIENCE', 4),
(56, 'BSCS405', 'Entrepreneurship in IT', 3, 1, 'BS in COMPUTER SCIENCE', 4),
(57, 'BSIT301', 'Enterprise Systems', 3, 2, 'BS in INFORMATION TECHNOLOGY', 3),
(58, 'BSIT302', 'IT Infrastructure Planning', 3, 2, 'BS in INFORMATION TECHNOLOGY', 3),
(59, 'BSIT303', 'Digital Forensics', 3, 2, 'BS in INFORMATION TECHNOLOGY', 3),
(60, 'BSIT304', 'Mobile Computing', 3, 2, 'BS in INFORMATION TECHNOLOGY', 3),
(61, 'BSIT305', 'Advanced Web Development', 3, 2, 'BS in INFORMATION TECHNOLOGY', 3),
(62, 'BSIT401', 'IT Research Project', 3, 2, 'BS in INFORMATION TECHNOLOGY', 4),
(63, 'BSIT402', 'Cloud and Virtualization', 3, 2, 'BS in INFORMATION TECHNOLOGY', 4),
(64, 'BSIT403', 'IT Governance and Compliance', 3, 2, 'BS in INFORMATION TECHNOLOGY', 4),
(65, 'BSIT404', 'Emerging Technologies', 3, 2, 'BS in INFORMATION TECHNOLOGY', 4),
(66, 'BSIT405', 'IT Leadership and Management', 3, 2, 'BS in INFORMATION TECHNOLOGY', 4),
(67, 'BSEMC301', 'Advanced 3D Animation', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 3),
(68, 'BSEMC302', 'Film and Video Editing', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 3),
(69, 'BSEMC303', 'Game Programming', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 3),
(70, 'BSEMC304', 'Interactive Web Media', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 3),
(71, 'BSEMC305', 'Digital Marketing Strategies', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 3),
(72, 'BSEMC401', 'Thesis in Multimedia Arts', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 4),
(73, 'BSEMC402', 'Virtual Reality Design', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 4),
(74, 'BSEMC403', 'Advanced Game Development', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 4),
(75, 'BSEMC404', 'Augmented Reality Applications', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 4),
(76, 'BSEMC405', 'Creative Entrepreneurship', 3, 3, 'BS in ENTERTAINMENT and MULTIMEDIA COMPUTING', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_202412162`
--
ALTER TABLE `student_202412162`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_202412268`
--
ALTER TABLE `student_202412268`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_credential`
--
ALTER TABLE `student_credential`
  ADD PRIMARY KEY (`studentID`);

--
-- Indexes for table `student_profiles`
--
ALTER TABLE `student_profiles`
  ADD PRIMARY KEY (`studentID`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`),
  ADD KEY `idx_year_level` (`year_level`),
  ADD KEY `fk_course_id` (`course_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_202412162`
--
ALTER TABLE `student_202412162`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `student_202412268`
--
ALTER TABLE `student_202412268`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `student_credential`
--
ALTER TABLE `student_credential`
  MODIFY `studentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202412886;

--
-- AUTO_INCREMENT for table `student_profiles`
--
ALTER TABLE `student_profiles`
  MODIFY `studentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202412886;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student_profiles`
--
ALTER TABLE `student_profiles`
  ADD CONSTRAINT `fk_studentID` FOREIGN KEY (`studentID`) REFERENCES `student_credential` (`studentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_profiles_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `fk_course_id` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
