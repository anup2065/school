-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2026 at 10:05 AM
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
-- Database: `school_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(2, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `due_date` date NOT NULL,
  `paid_date` date DEFAULT NULL,
  `status` enum('paid','pending') DEFAULT 'pending',
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fees`
--

INSERT INTO `fees` (`id`, `student_id`, `amount`, `due_date`, `paid_date`, `status`, `remarks`) VALUES
(1, 6, 50000.00, '2026-03-15', NULL, 'pending', ''),
(2, 12, 20000.00, '2026-03-15', NULL, 'pending', ''),
(3, 2, 50000.00, '2026-03-15', '2026-02-20', 'paid', '');

-- --------------------------------------------------------

--
-- Table structure for table `homework`
--

CREATE TABLE `homework` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class` varchar(10) NOT NULL,
  `section` varchar(5) DEFAULT NULL,
  `subject_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `due_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homework`
--

INSERT INTO `homework` (`id`, `teacher_id`, `class`, `section`, `subject_id`, `title`, `description`, `due_date`, `created_at`) VALUES
(1, 1, '12', 'H', 1, 'Permutation and Combination', 'Complete All the exercises of Permutation and Combination', '2026-03-25', '2026-02-21 02:45:31');

-- --------------------------------------------------------

--
-- Table structure for table `homework_submissions`
--

CREATE TABLE `homework_submissions` (
  `id` int(11) NOT NULL,
  `homework_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `submission_text` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `marks` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homework_submissions`
--

INSERT INTO `homework_submissions` (`id`, `homework_id`, `student_id`, `submission_text`, `file_path`, `submitted_at`, `marks`, `feedback`) VALUES
(1, 1, 2, '', 'uploads/1771643323_Proposal (Chapter 1).docx', '2026-02-21 03:08:43', 100, 'Excellent');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `posted_by` int(11) NOT NULL,
  `target_audience` enum('all','class') DEFAULT 'all',
  `class` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `title`, `content`, `posted_by`, `target_audience`, `class`, `created_at`) VALUES
(1, 'Physics Practical Exam', 'There will be physics practical exam for class 12 tomorrow sunday starting from 12 PM so prepare well for the exams\r\nBest of luck to everybody!', 2, 'class', '12', '2026-02-20 14:42:06');

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `id` int(11) NOT NULL,
  `parent_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`id`, `parent_id`, `name`, `email`, `phone`, `address`, `password`, `created_at`) VALUES
(1, '1', 'Raj Mani Ghimire', 'raj@gmail.com', '9842592547', 'Hokse', 'raj123', '2026-02-20 14:37:41');

-- --------------------------------------------------------

--
-- Table structure for table `parent_students`
--

CREATE TABLE `parent_students` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parent_students`
--

INSERT INTO `parent_students` (`id`, `parent_id`, `student_id`) VALUES
(3, 1, 4),
(4, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class` varchar(10) NOT NULL,
  `exam_name` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `marks` int(11) NOT NULL,
  `total_marks` int(11) NOT NULL,
  `entered_by` int(11) NOT NULL,
  `entered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `student_id`, `class`, `exam_name`, `subject`, `marks`, `total_marks`, `entered_by`, `entered_at`) VALUES
(2, 2, '12', 'Second Term Exam', 'Math', 72, 75, 1, '2026-02-20 14:54:34'),
(3, 6, '12', 'Second Term Exam', 'Math', 71, 75, 1, '2026-02-20 15:30:53'),
(4, 7, '12', 'Second Term Exam', 'Math', 70, 75, 1, '2026-02-20 15:30:53'),
(5, 12, '12', 'Second Term Exam', 'Math', 70, 75, 1, '2026-02-20 15:30:53'),
(6, 6, '12', 'Second Term Exam', 'Physics', 65, 75, 2, '2026-02-20 15:32:16'),
(7, 2, '12', 'Second Term Exam', 'Physics', 68, 75, 2, '2026-02-20 15:32:16'),
(8, 7, '12', 'Second Term Exam', 'Physics', 52, 75, 2, '2026-02-20 15:32:16'),
(9, 12, '12', 'Second Term Exam', 'Physics', 62, 75, 2, '2026-02-20 15:32:16'),
(10, 6, '12', 'Second Term Exam', 'Chemistry', 70, 75, 3, '2026-02-20 15:32:54'),
(11, 2, '12', 'Second Term Exam', 'Chemistry', 63, 75, 3, '2026-02-20 15:32:54'),
(12, 7, '12', 'Second Term Exam', 'Chemistry', 65, 75, 3, '2026-02-20 15:32:54'),
(13, 12, '12', 'Second Term Exam', 'Chemistry', 75, 75, 3, '2026-02-20 15:32:54'),
(14, 6, '12', 'Second Term Exam', 'Nepali', 66, 75, 4, '2026-02-20 15:33:45'),
(15, 2, '12', 'Second Term Exam', 'Nepali', 75, 75, 4, '2026-02-20 15:33:45'),
(16, 7, '12', 'Second Term Exam', 'Nepali', 68, 75, 4, '2026-02-20 15:33:45'),
(17, 12, '12', 'Second Term Exam', 'Nepali', 64, 75, 4, '2026-02-20 15:33:45'),
(18, 6, '12', 'Second Term Exam', 'English', 65, 75, 5, '2026-02-20 15:34:22'),
(19, 2, '12', 'Second Term Exam', 'English', 75, 75, 5, '2026-02-20 15:34:22'),
(20, 7, '12', 'Second Term Exam', 'English', 62, 75, 5, '2026-02-20 15:34:22'),
(21, 12, '12', 'Second Term Exam', 'English', 64, 75, 5, '2026-02-20 15:34:22'),
(22, 6, '12', 'Second Term Exam', 'Computer', 40, 50, 6, '2026-02-20 15:34:56'),
(23, 2, '12', 'Second Term Exam', 'Computer', 45, 50, 6, '2026-02-20 15:34:56'),
(24, 7, '12', 'Second Term Exam', 'Computer', 40, 50, 6, '2026-02-20 15:34:56'),
(25, 12, '12', 'Second Term Exam', 'Computer', 30, 50, 6, '2026-02-20 15:34:57'),
(26, 6, '12', 'First Term Exam ', 'Math', 71, 75, 1, '2026-02-21 04:05:34'),
(27, 2, '12', 'First Term Exam ', 'Math', 72, 75, 1, '2026-02-21 04:05:34'),
(28, 7, '12', 'First Term Exam ', 'Math', 69, 75, 1, '2026-02-21 04:05:34'),
(29, 12, '12', 'First Term Exam ', 'Math', 70, 75, 1, '2026-02-21 04:05:34'),
(30, 6, '12', 'First Term Exam ', 'Physics', 60, 75, 2, '2026-02-21 04:06:50'),
(31, 2, '12', 'First Term Exam ', 'Physics', 68, 75, 2, '2026-02-21 04:06:50'),
(32, 7, '12', 'First Term Exam ', 'Physics', 65, 75, 2, '2026-02-21 04:06:50'),
(33, 12, '12', 'First Term Exam ', 'Physics', 59, 75, 2, '2026-02-21 04:06:50'),
(34, 6, '12', 'First Term Exam ', 'Chemistry', 75, 75, 3, '2026-02-21 04:07:13'),
(35, 2, '12', 'First Term Exam ', 'Chemistry', 68, 75, 3, '2026-02-21 04:07:13'),
(36, 7, '12', 'First Term Exam ', 'Chemistry', 70, 75, 3, '2026-02-21 04:07:13'),
(37, 12, '12', 'First Term Exam ', 'Chemistry', 75, 75, 3, '2026-02-21 04:07:13'),
(38, 6, '12', 'First Term Exam', 'Nepali', 69, 75, 4, '2026-02-21 04:07:37'),
(39, 2, '12', 'First Term Exam ', 'Nepali', 67, 75, 4, '2026-02-21 04:07:37'),
(40, 7, '12', 'First Term Exam ', 'Nepali', 68, 75, 4, '2026-02-21 04:07:37'),
(41, 12, '12', 'First Term Exam ', 'Nepali', 65, 75, 4, '2026-02-21 04:07:37'),
(42, 6, '12', 'First Term Exam ', 'English', 75, 75, 5, '2026-02-21 04:07:56'),
(43, 2, '12', 'First Term Exam ', 'English', 74, 75, 5, '2026-02-21 04:07:56'),
(44, 7, '12', 'First Term Exam ', 'English', 73, 75, 5, '2026-02-21 04:07:56'),
(45, 12, '12', 'First Term Exam ', 'English', 72, 75, 5, '2026-02-21 04:07:56'),
(46, 6, '12', 'First Term Exam ', 'Computer', 43, 50, 6, '2026-02-21 04:08:27'),
(47, 2, '12', 'First Term Exam ', 'Computer', 50, 50, 6, '2026-02-21 04:08:27'),
(48, 7, '12', 'First Term Exam ', 'Computer', 45, 50, 6, '2026-02-21 04:08:27'),
(49, 12, '12', 'First Term Exam ', 'Computer', 44, 50, 6, '2026-02-21 04:08:27');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `class` varchar(10) NOT NULL,
  `section` varchar(5) DEFAULT NULL,
  `roll_no` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `name`, `email`, `phone`, `class`, `section`, `roll_no`, `address`, `password`, `created_at`) VALUES
(2, '1', 'Anup Ghimire', 'anup@gmail.com', '9123456789', '12', 'H', 824, 'Kathmandu', 'anup123', '2026-02-20 14:34:59'),
(3, '2', 'Aayush Ghimire', 'aayush@gmail.com', '9999999999', '9', 'A', 7, 'Khandbari', 'aayush123', '2026-02-20 14:35:38'),
(4, '3', 'Kulachandra Ghimire', 'Kulachandra@gmail.com', '8888888888', '10', 'A', 1, 'Sankhuwasabha', 'kulachandra123', '2026-02-20 14:40:03'),
(6, '4', 'Anmol Nepal', 'anmol@gmail.com', '1111111111', '12', 'H', 823, 'Jhapa', 'anmol123', '2026-02-20 14:56:42'),
(7, '5', 'Areej Karki', 'areej@gmail.com', '2222222222', '12', 'H', 830, 'Sunari', 'areej123', '2026-02-20 14:57:13'),
(12, '6', 'Borish Bhattarai', 'borish@gmail.com', '3333333333', '12', 'H', 832, 'Jhapa', 'borish123', '2026-02-20 15:13:26');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_name`) VALUES
(4, 'Chemistry'),
(2, 'Computer'),
(6, 'English'),
(1, 'Math'),
(5, 'Nepali'),
(3, 'Physics');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `teacher_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `qualification` varchar(200) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `teacher_id`, `name`, `email`, `phone`, `qualification`, `subject`, `address`, `password`, `created_at`) VALUES
(1, '123', 'Ram Rai', 'ram@gmail.com', '1111111112', 'PHD', 'Math', 'Khandbari', 'ram123', '2026-02-20 07:23:33'),
(2, '124', 'Physics Rai', 'physics@gmail.com', '1111111111', 'PHD', 'Physics', 'Bhaktapur', 'physics123', '2026-02-20 15:14:56'),
(3, '125', 'Chemistry Rai', 'chemistry@gmail.com', '1111111115', 'PHD', 'Chemistry', 'Kathmandu', 'chemistry123', '2026-02-20 15:25:44'),
(4, '126', 'Nepali Ghimire', 'nepali@gmail.com', '1111111116', 'PHD', 'Nepali ', 'Jhapa', 'nepali123', '2026-02-20 15:26:22'),
(5, '127', 'English Rai', 'english@gmail.com', '1111111117', 'PHD', 'English ', 'Pokhara', 'english123', '2026-02-20 15:26:56'),
(6, '128', 'Jaya Sundar Shilpakar', 'jaya@gmail.com', '1111111118', 'PHD', 'Computer', 'Bhaktapur', 'jaya123', '2026-02-20 15:27:58');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_classes`
--

CREATE TABLE `teacher_classes` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class` varchar(10) NOT NULL,
  `section` varchar(5) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_classes`
--

INSERT INTO `teacher_classes` (`id`, `teacher_id`, `class`, `section`, `subject`) VALUES
(1, 1, '12', 'H', 'Math'),
(2, 3, '12', 'H', 'Chemistry'),
(3, 5, '12', 'H', 'English'),
(4, 4, '12', 'H', 'Nepali'),
(5, 6, '12', 'H', 'Computer'),
(6, 2, '12', 'H', 'Physics');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `homework`
--
ALTER TABLE `homework`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `homework_submissions`
--
ALTER TABLE `homework_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `homework_id` (`homework_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posted_by` (`posted_by`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parent_id` (`parent_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `parent_students`
--
ALTER TABLE `parent_students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `entered_by` (`entered_by`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subject_name` (`subject_name`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teacher_id` (`teacher_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `teacher_classes`
--
ALTER TABLE `teacher_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `homework`
--
ALTER TABLE `homework`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `homework_submissions`
--
ALTER TABLE `homework_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `parent_students`
--
ALTER TABLE `parent_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `teacher_classes`
--
ALTER TABLE `teacher_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fees`
--
ALTER TABLE `fees`
  ADD CONSTRAINT `fees_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `homework`
--
ALTER TABLE `homework`
  ADD CONSTRAINT `homework_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `homework_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `homework_submissions`
--
ALTER TABLE `homework_submissions`
  ADD CONSTRAINT `homework_submissions_ibfk_1` FOREIGN KEY (`homework_id`) REFERENCES `homework` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `homework_submissions_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notices`
--
ALTER TABLE `notices`
  ADD CONSTRAINT `notices_ibfk_1` FOREIGN KEY (`posted_by`) REFERENCES `admin` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `parent_students`
--
ALTER TABLE `parent_students`
  ADD CONSTRAINT `parent_students_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `parent_students_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`entered_by`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_classes`
--
ALTER TABLE `teacher_classes`
  ADD CONSTRAINT `teacher_classes_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
