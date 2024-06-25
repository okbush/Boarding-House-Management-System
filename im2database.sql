-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2024 at 12:47 PM
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
-- Database: `im2database`
--

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `BillsID` int(11) NOT NULL,
  `Date` int(11) NOT NULL,
  `Description` varchar(100) NOT NULL,
  `Charges` int(11) NOT NULL,
  `BillRefNo` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `BillRefNo` varchar(14) NOT NULL,
  `OccupancyID` int(11) NOT NULL,
  `TenantAppID` int(11) NOT NULL,
  `BillingMonth` date NOT NULL,
  `BillDateIssued` date NOT NULL,
  `BillDueDate` date NOT NULL,
  `BillStatus` enum('Paid','Unpaid') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance`
--

CREATE TABLE `maintenance` (
  `MaintID` int(11) NOT NULL,
  `RoomID` varchar(50) NOT NULL,
  `MaintStatus` enum('Pending','Ongoing','Finished') NOT NULL,
  `MaintDesc` varchar(250) NOT NULL,
  `MaintCost` int(11) NOT NULL,
  `MaintDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `occtype`
--

CREATE TABLE `occtype` (
  `Occtype` varchar(25) NOT NULL,
  `OccRate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `occtype`
--

INSERT INTO `occtype` (`Occtype`, `OccRate`) VALUES
('Bedspacer', 600),
('Room(4 beds)', 2400),
('Room(6 beds)', 3600),
('Sharer', 0);

-- --------------------------------------------------------

--
-- Table structure for table `occupancy`
--

CREATE TABLE `occupancy` (
  `OccupancyID` int(11) NOT NULL,
  `OccDateStart` date NOT NULL,
  `OccDateEnd` date NOT NULL,
  `OccStatus` enum('Active','Inactive','Reserved','Evicted') NOT NULL,
  `OccLastModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `RoomID` varchar(50) NOT NULL,
  `Occtype` varchar(25) NOT NULL,
  `TenantAppID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `PaymentID` int(11) NOT NULL,
  `StaffID` int(11) NOT NULL,
  `PayAmount` int(11) NOT NULL,
  `PayDate` date NOT NULL,
  `ProofOfPayment` varchar(25) NOT NULL,
  `PayerFname` varchar(50) NOT NULL,
  `PayerLname` varchar(50) NOT NULL,
  `PayerMname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_balance`
--

CREATE TABLE `payment_balance` (
  `PaymentBalanceID` int(11) NOT NULL,
  `PrevBalance` int(11) NOT NULL,
  `CurrBalance` int(11) NOT NULL,
  `DateRecorded` date NOT NULL,
  `TimeRecorded` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `RoomID` varchar(50) NOT NULL,
  `RmType` enum('Shared','Solo','Empty') NOT NULL,
  `Capacity` enum('4','6') NOT NULL,
  `NumofTen` int(11) NOT NULL,
  `RoomStatus` enum('Empty','Partial','Full') NOT NULL,
  `RmLastModified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`RoomID`, `RmType`, `Capacity`, `NumofTen`, `RoomStatus`, `RmLastModified`) VALUES
('B1F1R100', 'Empty', '4', 0, 'Empty', '2024-06-14 12:21:48'),
('B1F1R101', 'Empty', '4', 0, 'Empty', '2024-06-14 12:22:15'),
('B1F1R102', 'Empty', '4', 0, 'Empty', '2024-06-14 12:22:21'),
('B1F1R103', 'Empty', '4', 0, 'Empty', '2024-06-14 12:22:27'),
('B1F1R104', 'Empty', '4', 0, 'Empty', '2024-06-14 12:22:32'),
('B1F1R105', 'Empty', '4', 0, 'Empty', '2024-06-14 12:22:36'),
('B1F2R100', 'Empty', '6', 0, 'Empty', '2024-06-14 12:22:44'),
('B1F2R101', 'Empty', '6', 0, 'Empty', '2024-06-14 12:22:49'),
('B1F2R102', 'Empty', '6', 0, 'Empty', '2024-06-14 12:22:52'),
('B1F2R103', 'Empty', '6', 0, 'Empty', '2024-06-14 12:22:57'),
('B1F2R104', 'Empty', '6', 0, 'Empty', '2024-06-14 12:23:00'),
('B1F2R105', 'Empty', '6', 0, 'Empty', '2024-06-14 12:23:03'),
('B2F1R100', 'Empty', '4', 0, 'Empty', '2024-06-14 12:24:32'),
('B2F1R101', 'Empty', '4', 0, 'Empty', '2024-06-14 12:24:36'),
('B2F1R102', 'Empty', '4', 0, 'Empty', '2024-06-14 12:24:40'),
('B2F1R103', 'Empty', '4', 0, 'Empty', '2024-06-14 12:24:45'),
('B2F1R104', 'Empty', '4', 0, 'Empty', '2024-06-14 12:24:50'),
('B2F1R105', 'Empty', '4', 0, 'Empty', '2024-06-14 12:24:53'),
('B2F2R100', 'Empty', '6', 0, 'Empty', '2024-06-14 12:25:02'),
('B2F2R101', 'Empty', '6', 0, 'Empty', '2024-06-14 12:25:13'),
('B2F2R102', 'Empty', '6', 0, 'Empty', '2024-06-14 12:27:20'),
('B2F2R103', 'Empty', '6', 0, 'Empty', '2024-06-14 12:27:29'),
('B2F2R104', 'Empty', '6', 0, 'Empty', '2024-06-14 12:27:32'),
('B2F2R105', 'Empty', '6', 0, 'Empty', '2024-06-14 12:27:36');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `StaffID` int(11) NOT NULL,
  `StaffFname` varchar(50) NOT NULL,
  `StaffLname` varchar(50) NOT NULL,
  `StaffMI` varchar(50) NOT NULL,
  `StaffRole` varchar(50) NOT NULL,
  `StaffEmail` varchar(250) NOT NULL,
  `StaffConNum` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant`
--

CREATE TABLE `tenant` (
  `TenantID` int(11) NOT NULL,
  `TenFname` varchar(50) NOT NULL,
  `TenLname` varchar(50) NOT NULL,
  `TenMname` varchar(50) NOT NULL,
  `TenHouseNum` int(10) NOT NULL,
  `TenSt` varchar(50) NOT NULL,
  `TenBarangay` varchar(50) NOT NULL,
  `TenCity` varchar(50) NOT NULL,
  `TenProv` varchar(50) NOT NULL,
  `TenConNum` varchar(11) NOT NULL,
  `TenEmail` varchar(250) NOT NULL,
  `TenBdate` date NOT NULL,
  `TenGender` enum('M','F') NOT NULL DEFAULT 'M',
  `EmConFname` varchar(50) NOT NULL,
  `EmConLname` varchar(50) NOT NULL,
  `EmConMname` varchar(50) NOT NULL,
  `EmConNum` varchar(11) NOT NULL,
  `DateCreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenant`
--

INSERT INTO `tenant` (`TenantID`, `TenFname`, `TenLname`, `TenMname`, `TenHouseNum`, `TenSt`, `TenBarangay`, `TenCity`, `TenProv`, `TenConNum`, `TenEmail`, `TenBdate`, `TenGender`, `EmConFname`, `EmConLname`, `EmConMname`, `EmConNum`, `DateCreated`) VALUES
(11, 'Test1', 'Test2', 'Test3', 25, 'Test4', 'Test5', 'Test6', 'Test7', '09177060872', 'elijahkahlilandres@gmail.com', '2024-06-15', 'M', 'Test8', 'Test9', 'Test0', '09177060872', '2024-06-24 04:03:58'),
(12, 'Daph', 'Knee', 'Kay', 25, 'Test4', 'Test5', 'Test6', 'Test7', '09177060872', 'daphdaph@gmail.com', '2024-06-11', 'M', 'The', 'Emergency', 'Person', '09177060872', '2024-06-24 04:03:58'),
(15, 'Paul', 'Seares', 'Pazon', 25, 'Test4', 'Test5', 'Test6', 'Test7', '09177060872', 'paul@gmail.com', '2026-10-24', 'M', 'Test8', 'Test9', 'Test0', '09177060872', '2024-06-24 04:03:58');

-- --------------------------------------------------------

--
-- Table structure for table `tenant_app`
--

CREATE TABLE `tenant_app` (
  `TenantAppID` int(11) NOT NULL,
  `AppQuantity` int(11) NOT NULL,
  `AppFee` int(11) NOT NULL,
  `AppFeeTotal` int(11) NOT NULL,
  `AppLastModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`BillsID`),
  ADD KEY `BillingBills` (`BillRefNo`);

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`BillRefNo`);

--
-- Indexes for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`MaintID`),
  ADD KEY `MaintRoom` (`RoomID`);

--
-- Indexes for table `occtype`
--
ALTER TABLE `occtype`
  ADD PRIMARY KEY (`Occtype`);

--
-- Indexes for table `occupancy`
--
ALTER TABLE `occupancy`
  ADD PRIMARY KEY (`OccupancyID`),
  ADD KEY `OccRoom` (`RoomID`),
  ADD KEY `OccType` (`Occtype`),
  ADD KEY `OccApp` (`TenantAppID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `PayStaff` (`StaffID`);

--
-- Indexes for table `payment_balance`
--
ALTER TABLE `payment_balance`
  ADD PRIMARY KEY (`PaymentBalanceID`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`RoomID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`StaffID`);

--
-- Indexes for table `tenant`
--
ALTER TABLE `tenant`
  ADD PRIMARY KEY (`TenantID`);

--
-- Indexes for table `tenant_app`
--
ALTER TABLE `tenant_app`
  ADD PRIMARY KEY (`TenantAppID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `BillsID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `MaintID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `occupancy`
--
ALTER TABLE `occupancy`
  MODIFY `OccupancyID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_balance`
--
ALTER TABLE `payment_balance`
  MODIFY `PaymentBalanceID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `StaffID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant`
--
ALTER TABLE `tenant`
  MODIFY `TenantID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tenant_app`
--
ALTER TABLE `tenant_app`
  MODIFY `TenantAppID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bill`
--
ALTER TABLE `bill`
  ADD CONSTRAINT `BillingBills` FOREIGN KEY (`BillRefNo`) REFERENCES `billing` (`BillRefNo`);

--
-- Constraints for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD CONSTRAINT `MaintRoom` FOREIGN KEY (`RoomID`) REFERENCES `rooms` (`RoomID`);

--
-- Constraints for table `occupancy`
--
ALTER TABLE `occupancy`
  ADD CONSTRAINT `OccApp` FOREIGN KEY (`TenantAppID`) REFERENCES `tenant_app` (`TenantAppID`),
  ADD CONSTRAINT `OccRoom` FOREIGN KEY (`RoomID`) REFERENCES `rooms` (`RoomID`),
  ADD CONSTRAINT `OccType` FOREIGN KEY (`Occtype`) REFERENCES `occtype` (`Occtype`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `PayStaff` FOREIGN KEY (`StaffID`) REFERENCES `staff` (`StaffID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
