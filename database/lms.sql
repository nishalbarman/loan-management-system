-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2023 at 07:36 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `49229_508560_student_loan_repayment`
--

CREATE TABLE `49229_508560_student_loan_repayment` (
  `id` int(255) NOT NULL,
  `paymentId` int(255) NOT NULL,
  `memberId` int(255) NOT NULL,
  `amount` float NOT NULL,
  `loanName` text NOT NULL,
  `status` text NOT NULL DEFAULT 'Unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `49229_508560_student_loan_repayment`
--

INSERT INTO `49229_508560_student_loan_repayment` (`id`, `paymentId`, `memberId`, `amount`, `loanName`, `status`) VALUES
(1, 1, 49229, 237.5, 'Student Loan', 'Unpaid'),
(2, 2, 49229, 237.5, 'Student Loan', 'Unpaid'),
(3, 3, 49229, 237.5, 'Student Loan', 'Unpaid'),
(4, 4, 49229, 237.5, 'Student Loan', 'Unpaid'),
(5, 5, 49229, 237.5, 'Student Loan', 'Unpaid'),
(6, 6, 49229, 237.5, 'Student Loan', 'Unpaid'),
(7, 7, 49229, 237.5, 'Student Loan', 'Unpaid'),
(8, 8, 49229, 237.5, 'Student Loan', 'Unpaid'),
(9, 9, 49229, 237.5, 'Student Loan', 'Unpaid'),
(10, 10, 49229, 237.5, 'Student Loan', 'Unpaid'),
(11, 11, 49229, 237.5, 'Student Loan', 'Unpaid'),
(12, 12, 49229, 237.5, 'Student Loan', 'Unpaid'),
(13, 13, 49229, 237.5, 'Student Loan', 'Unpaid'),
(14, 14, 49229, 237.5, 'Student Loan', 'Unpaid'),
(15, 15, 49229, 237.5, 'Student Loan', 'Unpaid'),
(16, 16, 49229, 237.5, 'Student Loan', 'Unpaid'),
(17, 17, 49229, 237.5, 'Student Loan', 'Unpaid'),
(18, 18, 49229, 237.5, 'Student Loan', 'Unpaid'),
(19, 19, 49229, 237.5, 'Student Loan', 'Unpaid'),
(20, 20, 49229, 237.5, 'Student Loan', 'Unpaid'),
(21, 21, 49229, 237.5, 'Student Loan', 'Unpaid'),
(22, 22, 49229, 237.5, 'Student Loan', 'Unpaid'),
(23, 23, 49229, 237.5, 'Student Loan', 'Unpaid'),
(24, 24, 49229, 237.5, 'Student Loan', 'Unpaid');

-- --------------------------------------------------------

--
-- Table structure for table `58970_928826_student_loan_repayment`
--

CREATE TABLE `58970_928826_student_loan_repayment` (
  `id` int(255) NOT NULL,
  `paymentId` int(255) NOT NULL,
  `memberId` int(255) NOT NULL,
  `amount` float NOT NULL,
  `loanName` text NOT NULL,
  `status` text NOT NULL DEFAULT 'Unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `58970_928826_student_loan_repayment`
--

INSERT INTO `58970_928826_student_loan_repayment` (`id`, `paymentId`, `memberId`, `amount`, `loanName`, `status`) VALUES
(1, 1, 58970, 267.5, 'Student Loan', 'Paid'),
(2, 2, 58970, 267.5, 'Student Loan', 'Unpaid'),
(3, 3, 58970, 267.5, 'Student Loan', 'Unpaid'),
(4, 4, 58970, 267.5, 'Student Loan', 'Unpaid'),
(5, 5, 58970, 267.5, 'Student Loan', 'Unpaid'),
(6, 6, 58970, 267.5, 'Student Loan', 'Unpaid'),
(7, 7, 58970, 267.5, 'Student Loan', 'Unpaid'),
(8, 8, 58970, 267.5, 'Student Loan', 'Unpaid'),
(9, 9, 58970, 267.5, 'Student Loan', 'Unpaid'),
(10, 10, 58970, 267.5, 'Student Loan', 'Unpaid'),
(11, 11, 58970, 267.5, 'Student Loan', 'Unpaid'),
(12, 12, 58970, 267.5, 'Student Loan', 'Unpaid');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(4) NOT NULL,
  `userName` varchar(15) NOT NULL,
  `password` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `userName`, `password`) VALUES
(1, 'admin', 'Admin@123');

-- --------------------------------------------------------

--
-- Table structure for table `applied_loans`
--

CREATE TABLE `applied_loans` (
  `id` int(255) NOT NULL,
  `memberId` int(255) NOT NULL,
  `loanTableName` varchar(255) NOT NULL,
  `loanName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applied_loans`
--

INSERT INTO `applied_loans` (`id`, `memberId`, `loanTableName`, `loanName`) VALUES
(12, 58970, '58970_928826_Student_Loan_repayment', 'Student Loan'),
(13, 49229, '49229_508560_Student_Loan_repayment', 'Student Loan');

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE `loan` (
  `loanId` int(6) NOT NULL,
  `memberId` int(8) NOT NULL,
  `loanType` varchar(15) NOT NULL,
  `income` int(8) NOT NULL,
  `amount` int(8) NOT NULL,
  `intereset` varchar(5) NOT NULL,
  `payment_term` int(3) NOT NULL,
  `total_paid` int(8) NOT NULL,
  `emi_per_month` int(8) NOT NULL,
  `bankStatementPhoto` varchar(250) DEFAULT NULL,
  `security` varchar(250) DEFAULT NULL,
  `posting_date` date NOT NULL,
  `status` varchar(15) NOT NULL,
  `adminRemark` varchar(100) NOT NULL,
  `adminRemarkDate` date NOT NULL,
  `balance` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `loan`
--

INSERT INTO `loan` (`loanId`, `memberId`, `loanType`, `income`, `amount`, `intereset`, `payment_term`, `total_paid`, `emi_per_month`, `bankStatementPhoto`, `security`, `posting_date`, `status`, `adminRemark`, `adminRemarkDate`, `balance`) VALUES
(42, 58970, 'Student Loan', 15000, 3000, '7', 1, 3210, 267, '', 'CERTIFICATE FROM GUIDE.docx', '2023-02-05', 'Pending', '', '0000-00-00', 15000),
(43, 49229, 'Student Loan', 150000, 5000, '7', 2, 5700, 237, '', 'Screenshot (339).png', '2023-02-06', 'Pending', '', '0000-00-00', 150000);

-- --------------------------------------------------------

--
-- Table structure for table `loantype`
--

CREATE TABLE `loantype` (
  `id` int(4) NOT NULL,
  `loanType` varchar(15) NOT NULL,
  `description` varchar(200) NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `loantype`
--

INSERT INTO `loantype` (`id`, `loanType`, `description`, `creationDate`) VALUES
(3, 'Student Loan', 'Student loan is a type of loan that is needed by many of the students, it helps the students to get higher education.', '2023-02-05 07:22:19');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id` int(5) NOT NULL,
  `memberId` int(8) NOT NULL,
  `fName` varchar(15) NOT NULL,
  `lName` varchar(15) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `occupation` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(15) NOT NULL,
  `address` varchar(12) NOT NULL,
  `county` varchar(20) NOT NULL,
  `district` varchar(20) NOT NULL,
  `location` varchar(20) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `dob` date NOT NULL,
  `regDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id`, `memberId`, `fName`, `lName`, `gender`, `phone`, `occupation`, `email`, `password`, `address`, `county`, `district`, `location`, `photo`, `dob`, `regDate`) VALUES
(16, 49229, 'Inja', 'Pro', 'M', '1234567890', 'other', 'inja@gmail.com', '@Prabal21', 'Nalbari', 'India', 'Nalbari', 'Assam', 'CERTIFICATE FROM GUIDE.docx', '2000-01-01', '2023-02-06');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(6) NOT NULL,
  `paymentId` varchar(20) NOT NULL,
  `memberId` int(8) NOT NULL,
  `fName` varchar(15) NOT NULL,
  `lName` varchar(15) NOT NULL,
  `amount` int(8) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `payment_date` date NOT NULL,
  `loanType` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `paymentId`, `memberId`, `fName`, `lName`, `amount`, `phone`, `payment_date`, `loanType`) VALUES
(23, '1', 58970, 'NISHAL', 'BARMAN', 3000, '09101114906', '2023-02-05', '58970_928826_Student_Loan_repayment');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `applied_loans`
--
ALTER TABLE `applied_loans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan`
--
ALTER TABLE `loan`
  ADD UNIQUE KEY `loanId` (`loanId`);

--
-- Indexes for table `loantype`
--
ALTER TABLE `loantype`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `applied_loans`
--
ALTER TABLE `applied_loans`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
  MODIFY `loanId` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `loantype`
--
ALTER TABLE `loantype`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
