-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2024 at 07:26 AM
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
-- Database: `vet_clinic`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'Sofea', '$2y$10$johCCSuK3NxF/jc3qJgeN.WbYP74R7eWnxEjABwic2NVnP/jpgPBi');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appointmentID` int(20) NOT NULL COMMENT 'A unique identifier for each appointment',
  `date` varchar(50) DEFAULT NULL,
  `time` varchar(50) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `phoneNo` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `speciesID` int(10) DEFAULT NULL COMMENT 'species -> speciesID',
  `reason` varchar(255) DEFAULT NULL,
  `statusID` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appointmentID`, `date`, `time`, `name`, `phoneNo`, `email`, `speciesID`, `reason`, `statusID`) VALUES
(26, '2023-10-26', '9:00 AM', 'Sofea Aliesya', '01121795006', 'aliesya2003@gmail.com', 1, 'Test', 2),
(27, '2023-10-26', '10:00 AM', 'Nani', '0182426017', 'liestiyani.nani@gmail.com', 1, 'Test', 2),
(28, '2023-10-26', '2:00 PM', 'Abd Aziz', '0182436550', 'aliesyanuraziz@gmail.com', 1, 'Test', 2),
(29, '2024-02-28', '2:00 PM', 'Jane Doe', '012-3456789', 'janedoe@example.com', 2, 'Injury', 2),
(30, '2024-02-29', '11:00 AM', 'Jane Doe', '012-3456789', 'janedoe@example.com', 2, 'Vaccination', 3);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryID` int(2) NOT NULL,
  `categoryName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryID`, `categoryName`) VALUES
(1, 'Medication'),
(2, 'Nutritional product');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `icNo` varchar(20) NOT NULL COMMENT 'The customer''s unique identification number',
  `name` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phoneNo` varchar(20) NOT NULL,
  `address` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`icNo`, `name`, `email`, `phoneNo`, `address`) VALUES
('030619-05-0122', 'Sofea Aliesya', 'aliesya2003@gmail.com', '011-21795006', 'Nilai, Negeri Sembilan');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `itemCode` varchar(10) NOT NULL COMMENT 'A unique identifier for each inventory item',
  `itemName` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `expiryDate` date DEFAULT NULL,
  `reorder_threshold` int(11) NOT NULL,
  `categoryID` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`itemCode`, `itemName`, `quantity`, `description`, `expiryDate`, `reorder_threshold`, `categoryID`) VALUES
('A101', 'Aatas Cat Food (Chicken)', 4, 'Cat Food', '2025-02-28', 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `loginID` int(10) NOT NULL COMMENT 'A unique identifier for each login entry',
  `nickname` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Stored using hashing for security'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`loginID`, `nickname`, `email`, `password`) VALUES
(3, 'Sofea Aliesya', 'aliesya2003@gmail.com', '$2y$10$KFa74.HPl3N.yQ.78qz.Qe1VJAHzl4u5TITzwYlhRoyn5kaRepYe6'),
(4, 'Jane Doe', 'janedoe@example.com', '$2y$10$oQaydt0yHJJNTgm4y8b6jelX4I.MUj1Qg48ykV9fz6NoDC4MghTWu');

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE `payment_method` (
  `paymentMethodID` int(11) NOT NULL COMMENT 'A unique identifier for each receipt',
  `method` varchar(255) DEFAULT NULL COMMENT 'Cash, Debit/Credit Card, E-wallets'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`paymentMethodID`, `method`) VALUES
(1, 'Cash'),
(2, 'Debit/Credit Card'),
(3, 'E-wallets');

-- --------------------------------------------------------

--
-- Table structure for table `pet_record`
--

CREATE TABLE `pet_record` (
  `recordID` int(20) NOT NULL COMMENT 'A unique identifier for each pet record entry',
  `icNo` varchar(20) DEFAULT NULL COMMENT 'customer -> icNo',
  `petName` varchar(50) DEFAULT NULL,
  `speciesID` int(10) DEFAULT NULL COMMENT 'species -> speciesID',
  `age` int(2) DEFAULT NULL,
  `gender` varchar(50) NOT NULL,
  `weight` decimal(10,0) NOT NULL,
  `petImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pet_record`
--

INSERT INTO `pet_record` (`recordID`, `icNo`, `petName`, `speciesID`, `age`, `gender`, `weight`, `petImage`) VALUES
(1, '030619-05-0122', 'Meow', 1, 7, 'Male', 5, '../Profile Picture/meow.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `receiptID` int(20) NOT NULL COMMENT 'A unique identifier for each receipt',
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `icNo` varchar(20) DEFAULT NULL COMMENT 'customer -> icNo',
  `totalAmount` decimal(10,2) DEFAULT NULL,
  `paymentMethodID` int(10) DEFAULT NULL COMMENT 'payment_method -> paymentMethodID',
  `id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipt`
--

INSERT INTO `receipt` (`receiptID`, `date`, `time`, `icNo`, `totalAmount`, `paymentMethodID`, `id`) VALUES
(1, '2024-02-28', NULL, '030619-05-0122', 80.00, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `receipt_items`
--

CREATE TABLE `receipt_items` (
  `receipt_itemsID` int(10) NOT NULL,
  `itemDescription` varchar(255) NOT NULL,
  `itemPrice` double NOT NULL,
  `receiptID` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipt_items`
--

INSERT INTO `receipt_items` (`receipt_itemsID`, `itemDescription`, `itemPrice`, `receiptID`) VALUES
(1, 'Vaccination', 80, 1);

-- --------------------------------------------------------

--
-- Table structure for table `species`
--

CREATE TABLE `species` (
  `speciesID` int(10) NOT NULL COMMENT 'A unique identifier for each species entry',
  `speciesName` varchar(10) NOT NULL COMMENT 'Cat/Dog'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `species`
--

INSERT INTO `species` (`speciesID`, `speciesName`) VALUES
(1, 'Cat'),
(2, 'Dog');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `statusID` int(2) NOT NULL,
  `statusName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`statusID`, `statusName`) VALUES
(1, 'Pending'),
(2, 'Confirmed'),
(3, 'Cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `vaccination`
--

CREATE TABLE `vaccination` (
  `id` int(10) NOT NULL,
  `vaccineType` varchar(255) NOT NULL,
  `vaccinationDate` varchar(50) NOT NULL,
  `nextVaccinationDate` varchar(50) NOT NULL,
  `recordID` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccination`
--

INSERT INTO `vaccination` (`id`, `vaccineType`, `vaccinationDate`, `nextVaccinationDate`, `recordID`) VALUES
(0, 'Feline Distemper', '2024-02-28', '2024-03-28', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointmentID`),
  ADD KEY `speciesID` (`speciesID`),
  ADD KEY `statusID` (`statusID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`icNo`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`itemCode`) USING BTREE,
  ADD KEY `fk_categoryID` (`categoryID`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`loginID`);

--
-- Indexes for table `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`paymentMethodID`);

--
-- Indexes for table `pet_record`
--
ALTER TABLE `pet_record`
  ADD PRIMARY KEY (`recordID`),
  ADD KEY `icNo` (`icNo`),
  ADD KEY `speciesID` (`speciesID`);

--
-- Indexes for table `receipt`
--
ALTER TABLE `receipt`
  ADD PRIMARY KEY (`receiptID`),
  ADD KEY `icNo` (`icNo`),
  ADD KEY `paymentMethodID` (`paymentMethodID`),
  ADD KEY `fk_id` (`id`);

--
-- Indexes for table `receipt_items`
--
ALTER TABLE `receipt_items`
  ADD PRIMARY KEY (`receipt_itemsID`),
  ADD KEY `fk_receiptID` (`receiptID`);

--
-- Indexes for table `species`
--
ALTER TABLE `species`
  ADD PRIMARY KEY (`speciesID`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`statusID`);

--
-- Indexes for table `vaccination`
--
ALTER TABLE `vaccination`
  ADD KEY `fk_recordID` (`recordID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointmentID` int(20) NOT NULL AUTO_INCREMENT COMMENT 'A unique identifier for each appointment', AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `loginID` int(10) NOT NULL AUTO_INCREMENT COMMENT 'A unique identifier for each login entry', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pet_record`
--
ALTER TABLE `pet_record`
  MODIFY `recordID` int(20) NOT NULL AUTO_INCREMENT COMMENT 'A unique identifier for each pet record entry', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `receipt`
--
ALTER TABLE `receipt`
  MODIFY `receiptID` int(20) NOT NULL AUTO_INCREMENT COMMENT 'A unique identifier for each receipt', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `receipt_items`
--
ALTER TABLE `receipt_items`
  MODIFY `receipt_itemsID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`speciesID`) REFERENCES `species` (`speciesID`),
  ADD CONSTRAINT `appointment_ibfk_3` FOREIGN KEY (`statusID`) REFERENCES `status` (`statusID`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `fk_categoryID` FOREIGN KEY (`categoryID`) REFERENCES `category` (`categoryID`);

--
-- Constraints for table `receipt`
--
ALTER TABLE `receipt`
  ADD CONSTRAINT `fk_id` FOREIGN KEY (`id`) REFERENCES `admin` (`id`);

--
-- Constraints for table `receipt_items`
--
ALTER TABLE `receipt_items`
  ADD CONSTRAINT `fk_receiptID` FOREIGN KEY (`receiptID`) REFERENCES `receipt` (`receiptID`);

--
-- Constraints for table `vaccination`
--
ALTER TABLE `vaccination`
  ADD CONSTRAINT `fk_recordID` FOREIGN KEY (`recordID`) REFERENCES `pet_record` (`recordID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
