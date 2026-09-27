-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2026 at 12:20 PM
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
-- Database: `medicare`
--
CREATE DATABASE IF NOT EXISTS `medicare` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `medicare`;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` varchar(30) DEFAULT 'Pending',
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `doctor_id` (`doctor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `reason`, `status`, `updated_by`, `updated_at`) VALUES
(1, 2, 1, '2026-09-30', '14:00:00', 'Not well', 'Confirmed', NULL, '2026-09-27 14:01:12');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`) VALUES
(1, 'General Medicine'),
(2, 'Cardiology'),
(3, 'Pediatrics'),
(4, 'Dermatology'),
(5, 'Diagnostics'),
(6, 'Preventive Care');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

DROP TABLE IF EXISTS `doctors`;
CREATE TABLE IF NOT EXISTS `doctors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `department`, `specialization`, `phone`, `email`) VALUES
(1, 'Dr. Ananya Rao', 'General Medicine', 'General Physician', '9876543210', 'ananya@medicare.com'),
(2, 'Dr. Arjun Mehta', 'Cardiology', 'Cardiologist', '9876543211', 'arjun@medicare.com'),
(3, 'Dr. Meera Nair', 'Pediatrics', 'Pediatrician', '9876543212', 'meera@medicare.com'),
(4, 'Dr. Kavya Menon', 'Dermatology', 'Dermatologist', '9876543213', 'kavya@medicare.com'),
(5, 'Dr. Rahul Iyer', 'Diagnostics', 'Diagnostic Specialist', '9876543214', 'rahul@medicare.com'),
(6, 'Dr. Priya Sharma', 'Preventive Health', 'Preventive Medicine Specialist', '9876543215', 'priya@medicare.com');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
CREATE TABLE IF NOT EXISTS `patients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `user_id`, `phone`, `address`, `date_of_birth`) VALUES
(2, 3, '2345678901', 'no 2 ,raja street', '2002-06-12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('patient','doctor','admin') DEFAULT 'patient',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Admin', 'admin@medicare.com', '$2y$10$wupPyzM8ZbN8Pp1Au4fS6uClL0wFKWeBBwT/mdS6/SrUxQ/j4Uir6', 'admin'),
(3, 'Ray', 'ray@test.com', '$2y$10$8E9WBGvEQgvN7Dwk0cxQP.wGrigQvhMmiO.cni1uoV6c/5ixjoNz2', 'patient'),
(4, 'Dr. Ananya Rao', 'ananya@medicare.com', '$2y$10$wupPyzM8ZbN8Pp1Au4fS6uClL0wFKWeBBwT/mdS6/SrUxQ/j4Uir6', 'doctor'),
(5, 'Dr. Arjun Mehta', 'arjun@medicare.com', '$2y$10$wupPyzM8ZbN8Pp1Au4fS6uClL0wFKWeBBwT/mdS6/SrUxQ/j4Uir6', 'doctor'),
(6, 'Dr. Meera Nair', 'meera@medicare.com', '$2y$10$wupPyzM8ZbN8Pp1Au4fS6uClL0wFKWeBBwT/mdS6/SrUxQ/j4Uir6', 'doctor'),
(7, 'Dr. Kavya Menon', 'kavya@medicare.com', '$2y$10$wupPyzM8ZbN8Pp1Au4fS6uClL0wFKWeBBwT/mdS6/SrUxQ/j4Uir6', 'doctor'),
(8, 'Dr. Rahul Iyer', 'rahul@medicare.com', '$2y$12$gjxITNB9z6linHFyV80.WelSlOIUTLtzFaiS3UDXPCnPgzEM49aZy', 'doctor'),
(9, 'Dr. Priya Sharma', 'priya@medicare.com', '$2y$12$M1.Jyl2ExB2w4vL5h/1wX.BpukDn207kl0zrUvBkcCL.lLiqQNuDu', 'doctor');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
