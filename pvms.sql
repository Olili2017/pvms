-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 04, 2018 at 01:48 PM
-- Server version: 5.7.19
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pvms`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(50) NOT NULL,
  `permissions` text,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`Id`, `alias`, `permissions`) VALUES
(1, 'administrator', '{\"admin\":1,\"display_queue\":1,\"add_patient\":1}'),
(2, 'receptionist', '{\"issue_bill\":1,\"add_patient\":1}'),
(3, 'doctor', '{\"can_diagnise\":1,\"display_queue\":1,\"add_patient\":1}'),
(4, 'laboratory', ''),
(5, 'pharmacist', '');

-- --------------------------------------------------------

--
-- Table structure for table `laboratory_test`
--

DROP TABLE IF EXISTS `laboratory_test`;
CREATE TABLE IF NOT EXISTS `laboratory_test` (
  `test_Id` int(11) NOT NULL AUTO_INCREMENT,
  `test_name` varchar(250) NOT NULL,
  `test_cost` int(11) NOT NULL,
  PRIMARY KEY (`test_Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `laboratory_test`
--

INSERT INTO `laboratory_test` (`test_Id`, `test_name`, `test_cost`) VALUES
(1, 'FBS', 5000),
(2, 'CBC', 20000);

-- --------------------------------------------------------

--
-- Table structure for table `medication`
--

DROP TABLE IF EXISTS `medication`;
CREATE TABLE IF NOT EXISTS `medication` (
  `med_id` int(11) NOT NULL AUTO_INCREMENT,
  `med_name` varchar(250) NOT NULL,
  `med_type` enum('tablet','ointment','injectable','oral liguid') DEFAULT NULL,
  `med_color` varchar(50) DEFAULT NULL,
  `med_cost` int(11) DEFAULT NULL,
  `med_status` enum('available','finished','deficient') DEFAULT NULL,
  PRIMARY KEY (`med_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `medication`
--

INSERT INTO `medication` (`med_id`, `med_name`, `med_type`, `med_color`, `med_cost`, `med_status`) VALUES
(1, 'panadol', 'tablet', 'white', 500, 'available'),
(2, 'amoxiline', 'tablet', 'green', 500, 'available'),
(3, 'quistodol', 'tablet', 'red', 500, 'available'),
(4, 'melaline', 'tablet', 'purple', 500, 'available'),
(5, 'conaxine', 'tablet', 'aqua', 500, 'available'),
(6, 'meladol', 'injectable', 'blue', 500, 'available'),
(7, 'teyp a', 'injectable', '#d78378', 3456, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
CREATE TABLE IF NOT EXISTS `patients` (
  `patient_Id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_name` varchar(250) NOT NULL,
  `phone_no` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `patient_address` varchar(250) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `age` int(11) NOT NULL,
  `marital_status` enum('married','single','divorsed','other') DEFAULT NULL,
  `nok` varchar(250) NOT NULL,
  `nok_contact` varchar(20) NOT NULL,
  `nok_relationship` enum('father','mother','sibling','friend','other') DEFAULT NULL,
  `discharged` tinyint(1) NOT NULL,
  PRIMARY KEY (`patient_Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_Id`, `patient_name`, `phone_no`, `email`, `patient_address`, `gender`, `age`, `marital_status`, `nok`, `nok_contact`, `nok_relationship`, `discharged`) VALUES
(1, 'obote milton', '0701446723`', 'milton@gmail.com', 'akokoro', 'Male', 78, 'married', 'idd amin', '0721782354', 'friend', 0),
(2, 'okello tito lutwa', '0923456798', 'lutwaa@gmail.com', 'luweero', 'Male', 55, 'divorsed', 'kizza besigye', '0981234651', 'other', 0),
(3, 'kakooza andrew', '257956378', 'andrewk@gmail.com', 'lukooli', 'Male', 53, 'married', 'wishare', '239874897', 'mother', 0);

-- --------------------------------------------------------

--
-- Table structure for table `queues`
--

DROP TABLE IF EXISTS `queues`;
CREATE TABLE IF NOT EXISTS `queues` (
  `queue_Id` int(11) NOT NULL AUTO_INCREMENT,
  `visit_Id` int(11) NOT NULL,
  `queue_atendant_id` int(11) NOT NULL,
  `queue_atendant_group` enum('doctor','laboratory','pharmacy') DEFAULT NULL,
  `queue_response` enum('finished','processing','served') DEFAULT NULL,
  PRIMARY KEY (`queue_Id`),
  KEY `doctor_handling_queue` (`queue_atendant_id`),
  KEY `queu_patient` (`visit_Id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `queues`
--

INSERT INTO `queues` (`queue_Id`, `visit_Id`, `queue_atendant_id`, `queue_atendant_group`, `queue_response`) VALUES
(5, 2, 1001, 'pharmacy', 'processing');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_Id` int(11) NOT NULL AUTO_INCREMENT,
  `user_fname` varchar(20) NOT NULL,
  `user_lname` varchar(20) NOT NULL,
  `user_alias` varchar(50) NOT NULL,
  `user_password` varchar(250) NOT NULL,
  `contact` varchar(13) DEFAULT NULL,
  `user_email` varchar(150) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('male','female') NOT NULL,
  `salt` varchar(50) DEFAULT NULL,
  `user_role` int(11) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_Id`),
  UNIQUE KEY `user_alias` (`user_alias`),
  KEY `user_role` (`user_role`)
) ENGINE=InnoDB AUTO_INCREMENT=1009 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_Id`, `user_fname`, `user_lname`, `user_alias`, `user_password`, `contact`, `user_email`, `dob`, `gender`, `salt`, `user_role`, `reg_date`) VALUES
(1000, 'daniel', 'olili', 'daniel', '^ˆH˜Ú(qQÐåoÆ)\'s`=\rj«½Ö*ïrBØ', '0772649119', 'olilidaniel48@gmail.com', '1994-03-04', 'male', '885c055a800cc57cfb6283388ef490ab', 1, '2018-03-17 04:22:16'),
(1001, 'alex', 'miiru', 'alex', 'Y”G\Z»*üÁYöÌt´õ¹˜ÚY³Êõ©ÁsÊÏÅ', '7567576576', 'hkehkj@hjhj.com', '2012-10-29', 'male', '3d4853a66fabed3062cf1a1755f94330', 3, '2018-03-30 20:53:49'),
(1002, 'maria', 'soreze', 'maria', 'Y”G\Z»*üÁYöÌt´õ¹˜ÚY³Êõ©ÁsÊÏÅ', '378687678', 'maria@example.com', '1976-08-28', 'female', '0c872dd70c19bb247488e57ea1e3c8cd', 2, '2018-04-04 07:27:12'),
(1003, 'ambose', 'kaleke', 'ambrose', 'Y”G\Z»*üÁYöÌt´õ¹˜ÚY³Êõ©ÁsÊÏÅ', '3478762387', 'ambkaleke@gmail.com', '1967-11-29', 'male', '72738a111ad528d067da63722d05c148', 3, '2018-04-04 07:35:03'),
(1004, 'okitui', 'Florence', 'doc', 'Y”G\Z»*üÁYöÌt´õ¹˜ÚY³Êõ©ÁsÊÏÅ', '456465465', 'doc@pvms.em', '1981-07-06', 'female', 'cca657593b89cbee0bfa375f90c4b72a', 3, '2018-04-04 13:21:24'),
(1005, 'Kalungi', 'Bright', 'rec', 'Y”G\Z»*üÁYöÌt´õ¹˜ÚY³Êõ©ÁsÊÏÅ', '65645354354', 'rec@pvms.em', '2015-06-02', 'female', '0cf999a642f877821ad06e569420440e', 2, '2018-04-04 13:22:37'),
(1006, 'Kisakye', 'stellah', 'lab', 'Y”G\Z»*üÁYöÌt´õ¹˜ÚY³Êõ©ÁsÊÏÅ', '65465463435', 'lab@pvms.em', '2022-05-07', 'male', 'be49395bdb48175eca020d9f03bb4fa5', 4, '2018-04-04 13:23:24'),
(1007, 'gulu', 'gerald', 'pharm', 'Y”G\Z»*üÁYöÌt´õ¹˜ÚY³Êõ©ÁsÊÏÅ', '7575454354', 'pharm@pvms.em', '2021-12-31', 'male', 'bf3e251e1d88d225e8068740eccb2c3b', 5, '2018-04-04 13:24:26'),
(1008, 'admin', 'two', 'admin', 'Y”G\Z»*üÁYöÌt´õ¹˜ÚY³Êõ©ÁsÊÏÅ', '6545345354', 'admin@pvms.em', '2016-01-04', 'male', '8047966d3c0b00d50d058711c530b095', 1, '2018-04-04 13:25:14');

-- --------------------------------------------------------

--
-- Table structure for table `visits`
--

DROP TABLE IF EXISTS `visits`;
CREATE TABLE IF NOT EXISTS `visits` (
  `visit_Id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_Id` int(11) NOT NULL,
  `doctor_Id` int(11) NOT NULL,
  `admition_executer_Id` int(11) NOT NULL,
  `lab_technician_id` int(11) DEFAULT NULL,
  `phamacist_Id` int(11) DEFAULT NULL,
  `visitStatus` enum('in queue','started','terminated') NOT NULL,
  `visitLabStatus` enum('none','returned','on-going') DEFAULT NULL,
  `visitStartTime` time DEFAULT NULL,
  `visitStartDate` date DEFAULT NULL,
  `startMiscroTime` varchar(11) DEFAULT NULL,
  `endMicroTime` varchar(11) DEFAULT NULL,
  `visitVitalTemperature` int(11) DEFAULT NULL,
  `visitVitalWeight` float DEFAULT NULL,
  `visitVitalHeight` float DEFAULT NULL,
  `visitVitalPressure` varchar(20) DEFAULT NULL,
  `visitVitalPulse` int(11) DEFAULT NULL,
  `visitNotes` text,
  `visitDiagnosis` varchar(250) DEFAULT NULL,
  `visit_medication_Id` int(11) DEFAULT NULL,
  PRIMARY KEY (`visit_Id`,`patient_Id`),
  UNIQUE KEY `visit_Id` (`visit_Id`),
  KEY `patient_Id` (`patient_Id`),
  KEY `apointed_doctor` (`doctor_Id`),
  KEY `user_that_admitted_patient` (`admition_executer_Id`),
  KEY `phamacist_Id` (`phamacist_Id`),
  KEY `visit_medication_Id` (`visit_medication_Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `visits`
--

INSERT INTO `visits` (`visit_Id`, `patient_Id`, `doctor_Id`, `admition_executer_Id`, `lab_technician_id`, `phamacist_Id`, `visitStatus`, `visitLabStatus`, `visitStartTime`, `visitStartDate`, `startMiscroTime`, `endMicroTime`, `visitVitalTemperature`, `visitVitalWeight`, `visitVitalHeight`, `visitVitalPressure`, `visitVitalPulse`, `visitNotes`, `visitDiagnosis`, `visit_medication_Id`) VALUES
(1, 1, 1001, 1000, NULL, NULL, 'started', NULL, '10:30:10', '2018-04-04', '1522827010', NULL, 39, 56, 5, '168 120', 78, 'partly hypertensive                ', NULL, NULL),
(2, 2, 1001, 1000, NULL, NULL, 'started', NULL, '04:02:36', '2018-04-04', '1522846956', NULL, 35, 67, 6, '120 80', 72, 'feels warm mostly                ', NULL, NULL),
(3, 3, 1003, 1000, NULL, NULL, 'terminated', NULL, '10:38:05', '2018-04-04', '1522827485', '1522847380', 37, 45, 4, '120 100', 72, 'immediate attention needed              ', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `visit_medication`
--

DROP TABLE IF EXISTS `visit_medication`;
CREATE TABLE IF NOT EXISTS `visit_medication` (
  `visit_med_Id` int(11) NOT NULL AUTO_INCREMENT,
  `visit_med_status` varchar(250) NOT NULL,
  `visit_med` text,
  `visit_med_dosage` varchar(250) DEFAULT NULL,
  `visit_med_duration` int(11) DEFAULT NULL,
  PRIMARY KEY (`visit_med_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `visit_test`
--

DROP TABLE IF EXISTS `visit_test`;
CREATE TABLE IF NOT EXISTS `visit_test` (
  `visit_test_Id` int(11) NOT NULL AUTO_INCREMENT,
  `visit_Id` int(11) NOT NULL,
  `lab_test_id` int(11) NOT NULL,
  `visit_test_comment` text,
  PRIMARY KEY (`visit_test_Id`),
  KEY `visit_Id` (`visit_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `medication`
--
ALTER TABLE `medication` ADD FULLTEXT KEY `med_name` (`med_name`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `queues`
--
ALTER TABLE `queues`
  ADD CONSTRAINT `doctor_handling_queue` FOREIGN KEY (`queue_atendant_id`) REFERENCES `users` (`user_Id`),
  ADD CONSTRAINT `queu_patient` FOREIGN KEY (`visit_Id`) REFERENCES `visits` (`visit_Id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`user_role`) REFERENCES `groups` (`Id`);

--
-- Constraints for table `visits`
--
ALTER TABLE `visits`
  ADD CONSTRAINT `apointed_doctor` FOREIGN KEY (`doctor_Id`) REFERENCES `users` (`user_Id`),
  ADD CONSTRAINT `user_that_admitted_patient` FOREIGN KEY (`admition_executer_Id`) REFERENCES `users` (`user_Id`),
  ADD CONSTRAINT `visits_ibfk_1` FOREIGN KEY (`patient_Id`) REFERENCES `patients` (`patient_Id`),
  ADD CONSTRAINT `visits_ibfk_2` FOREIGN KEY (`phamacist_Id`) REFERENCES `users` (`user_Id`),
  ADD CONSTRAINT `visits_ibfk_3` FOREIGN KEY (`visit_medication_Id`) REFERENCES `visit_medication` (`visit_med_Id`);

--
-- Constraints for table `visit_test`
--
ALTER TABLE `visit_test`
  ADD CONSTRAINT `visit_test_ibfk_1` FOREIGN KEY (`visit_Id`) REFERENCES `visits` (`visit_Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
