-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2025 at 05:56 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_parking`
--

-- --------------------------------------------------------

--
-- Table structure for table `distance`
--

CREATE TABLE `distance` (
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `distance` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `distance`
--

INSERT INTO `distance` (`date`, `distance`) VALUES
('2019-09-15 23:54:43', 148),
('2019-09-15 23:54:46', 126),
('2019-09-15 23:54:49', 149),
('2019-09-15 23:54:52', 149),
('2019-09-15 23:54:55', 128),
('2019-09-15 23:54:58', 101),
('2019-09-15 23:55:01', 148),
('2019-09-15 23:55:04', 149),
('2019-09-15 23:55:07', 104),
('2019-09-16 00:55:42', 10),
('2019-09-16 00:55:45', 9),
('2019-09-16 00:55:48', 10),
('2019-09-16 00:55:51', 10);

-- --------------------------------------------------------

--
-- Table structure for table `gatelog`
--

CREATE TABLE `gatelog` (
  `LogID` bigint(20) NOT NULL,
  `GateType` varchar(10) DEFAULT NULL,
  `Action` varchar(10) DEFAULT NULL,
  `Time` datetime DEFAULT NULL,
  `TriggeredBy` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gatelog`
--

INSERT INTO `gatelog` (`LogID`, `GateType`, `Action`, `Time`, `TriggeredBy`) VALUES
(1, 'Đóng', 'Close', '2025-08-06 17:19:52', '5E68200E');

-- --------------------------------------------------------

--
-- Table structure for table `information`
--

CREATE TABLE `information` (
  `Email` text NOT NULL,
  `Password` text NOT NULL,
  `Name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `DateOfBirth` date NOT NULL,
  `Address` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `information`
--

INSERT INTO `information` (`Email`, `Password`, `Name`, `DateOfBirth`, `Address`) VALUES
('vinh.phan@eiu.edu.vn', '123456', 'Vinh', '2018-10-10', 'Binh Duong'),
('khang.vo.k3set@eiu.edu.vn', '123456', 'duykhang', '1995-01-29', 'Binh Duong'),
('thanh.tran.k2000@gmail.com', '123456', 'Thanh', '1994-09-27', 'BD-BB'),
('pvvinhbk@gmail.com', 'abc@123', 'Vinh Phan', '1984-12-08', 'Phu Hoa, TDM, BD');

-- --------------------------------------------------------

--
-- Table structure for table `monitor`
--

CREATE TABLE `monitor` (
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `temperature` int(11) NOT NULL,
  `humidity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `monitor`
--

INSERT INTO `monitor` (`date`, `temperature`, `humidity`) VALUES
('2019-09-10 19:35:19', 26, 58),
('2019-09-10 19:35:21', 26, 58),
('2019-09-10 19:35:24', 26, 58),
('2019-09-10 19:35:26', 26, 58),
('2019-09-10 19:35:29', 26, 58),
('2019-09-10 19:35:34', 26, 58),
('2019-09-10 19:35:39', 26, 58),
('2019-09-10 19:35:42', 26, 58),
('2019-09-10 19:35:44', 26, 58),
('2019-09-10 19:35:47', 26, 58),
('2019-09-10 19:35:52', 26, 58),
('2019-09-10 19:35:54', 26, 58),
('2019-09-10 19:35:57', 26, 58),
('2019-09-10 19:36:02', 26, 58),
('2019-09-10 19:36:05', 26, 58),
('2019-09-10 19:36:17', 26, 58),
('2019-09-10 19:36:20', 26, 58),
('2019-09-10 19:36:22', 26, 58),
('2019-09-10 19:36:30', 26, 58),
('2019-09-10 19:36:35', 26, 58),
('2019-09-10 19:36:38', 26, 58),
('2019-09-10 19:36:43', 26, 58),
('2019-09-10 19:36:48', 26, 59),
('2019-09-10 19:36:50', 26, 59),
('2019-09-10 19:36:56', 26, 58),
('2019-09-10 19:36:58', 26, 58),
('2019-09-10 19:37:01', 26, 58),
('2019-09-10 19:37:03', 26, 58),
('2019-09-10 19:37:06', 26, 58),
('2019-09-10 19:37:08', 26, 58),
('2019-09-10 19:37:11', 26, 58),
('2019-09-10 19:37:13', 26, 58),
('2019-09-10 19:37:16', 26, 58),
('2019-09-10 19:37:19', 26, 58);

-- --------------------------------------------------------

--
-- Table structure for table `mq2sensor`
--

CREATE TABLE `mq2sensor` (
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `mq2` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mq2sensor`
--

INSERT INTO `mq2sensor` (`date`, `mq2`) VALUES
('2019-09-04 02:55:03', 25000),
('2019-09-04 02:56:43', 26090),
('2019-09-04 02:56:48', 26090),
('2019-09-04 02:56:53', 26089),
('2019-09-04 02:56:58', 26090),
('2019-09-04 02:57:03', 26092),
('2019-09-04 02:57:08', 26090),
('2019-09-04 02:57:13', 25046),
('2019-09-04 02:57:18', 25881),
('2019-09-04 02:57:23', 25876),
('2019-09-04 02:57:28', 25866),
('2019-09-04 02:57:33', 25860),
('2019-09-04 02:57:39', 25859),
('2019-09-04 02:57:44', 25856),
('2019-09-04 02:57:49', 25856),
('2019-09-04 02:57:54', 25853);

-- --------------------------------------------------------

--
-- Table structure for table `parkinghistory`
--

CREATE TABLE `parkinghistory` (
  `HistoryID` bigint(20) NOT NULL,
  `RFID` varchar(20) DEFAULT NULL,
  `SlotID` int(11) DEFAULT NULL,
  `TimeIn` datetime DEFAULT NULL,
  `TimeOut` datetime DEFAULT NULL,
  `Duration` int(11) DEFAULT NULL,
  `Fee` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `parkingslot`
--

CREATE TABLE `parkingslot` (
  `SlotID` int(11) NOT NULL,
  `SlotCode` varchar(10) NOT NULL,
  `Area` varchar(5) DEFAULT NULL,
  `Status` tinyint(4) DEFAULT 0,
  `CurrentRFID` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `parkingslot`
--

INSERT INTO `parkingslot` (`SlotID`, `SlotCode`, `Area`, `Status`, `CurrentRFID`) VALUES
(1, '1', 'A', 0, NULL),
(2, '2', 'A', 0, NULL),
(3, '3', 'A', 0, NULL),
(4, '4', 'A', 0, NULL),
(5, '5', 'A', 0, NULL),
(6, '1', 'B', 0, NULL),
(7, '2', 'B', 0, NULL),
(8, '3', 'B', 0, NULL),
(9, '4', 'B', 0, NULL),
(10, '5', 'B', 0, NULL),
(11, '1', 'C', 0, NULL),
(12, '2', 'C', 0, NULL),
(13, '3', 'C', 0, NULL),
(14, '4', 'C', 0, NULL),
(15, '5', 'C', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rfidcard`
--

CREATE TABLE `rfidcard` (
  `RFID` varchar(20) NOT NULL,
  `OwnerName` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `VehiclePlate` varchar(15) DEFAULT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL,
  `Type` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rfidcard`
--

INSERT INTO `rfidcard` (`RFID`, `OwnerName`, `VehiclePlate`, `PhoneNumber`, `Type`) VALUES
('5E68200E', '', '', '', ''),
('90172383', '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gatelog`
--
ALTER TABLE `gatelog`
  ADD PRIMARY KEY (`LogID`);

--
-- Indexes for table `parkinghistory`
--
ALTER TABLE `parkinghistory`
  ADD PRIMARY KEY (`HistoryID`),
  ADD KEY `RFID` (`RFID`),
  ADD KEY `SlotID` (`SlotID`);

--
-- Indexes for table `parkingslot`
--
ALTER TABLE `parkingslot`
  ADD PRIMARY KEY (`SlotID`);

--
-- Indexes for table `rfidcard`
--
ALTER TABLE `rfidcard`
  ADD PRIMARY KEY (`RFID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gatelog`
--
ALTER TABLE `gatelog`
  MODIFY `LogID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `parkinghistory`
--
ALTER TABLE `parkinghistory`
  MODIFY `HistoryID` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parkingslot`
--
ALTER TABLE `parkingslot`
  MODIFY `SlotID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `parkinghistory`
--
ALTER TABLE `parkinghistory`
  ADD CONSTRAINT `parkinghistory_ibfk_1` FOREIGN KEY (`RFID`) REFERENCES `rfidcard` (`RFID`),
  ADD CONSTRAINT `parkinghistory_ibfk_2` FOREIGN KEY (`SlotID`) REFERENCES `parkingslot` (`SlotID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
