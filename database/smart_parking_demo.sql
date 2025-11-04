-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th8 20, 2025 lúc 09:14 AM
-- Phiên bản máy phục vụ: 10.4.24-MariaDB
-- Phiên bản PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `smart_parking`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `gatelog`
--

CREATE TABLE `gatelog` (
  `LogID` int(20) NOT NULL,
  `GateType` varchar(10) DEFAULT NULL,
  `Action` varchar(10) DEFAULT NULL,
  `Time` datetime DEFAULT NULL,
  `TriggeredBy` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `gatelog`
--

INSERT INTO `gatelog` (`LogID`, `GateType`, `Action`, `Time`, `TriggeredBy`) VALUES
(1, 'Đóng', 'Close', '2025-08-06 17:19:52', '5E68200E'),
(2, 'UNKNOWN', 'Open', '2025-08-18 17:24:24', 'SYSTEM'),
(3, 'UNKNOWN', 'Open', '2025-08-18 17:24:46', 'SYSTEM'),
(4, 'ENTRY', 'Close', '2025-08-18 17:25:35', 'SYSTEM'),
(5, 'ENTRY', 'Open', '2025-08-18 17:26:51', 'SYSTEM'),
(6, 'ENTRY', 'Open', '2025-08-18 17:28:50', 'SYSTEM'),
(7, 'ENTRY', 'Open', '2025-08-18 17:34:40', 'SYSTEM'),
(8, 'EXIT', 'ACCEPT', '2025-08-18 12:59:23', '90172383'),
(9, 'EXIT', 'Open', '2025-08-18 17:59:23', 'SYSTEM'),
(10, 'EXIT', 'OPEN', '2025-08-18 12:59:23', 'RFID'),
(11, 'EXIT', 'Close', '2025-08-18 17:59:26', 'SYSTEM'),
(12, 'EXIT', 'CLOSE', '2025-08-18 12:59:26', 'SYSTEM'),
(13, 'ENTRY', 'ACCEPT', '2025-08-18 13:00:08', '90172383'),
(14, 'ENTRY', 'Open', '2025-08-18 18:00:09', 'SYSTEM'),
(15, 'ENTRY', 'OPEN', '2025-08-18 13:00:09', 'RFID'),
(16, 'ENTRY', 'Close', '2025-08-18 18:00:12', 'SYSTEM'),
(17, 'ENTRY', 'CLOSE', '2025-08-18 13:00:12', 'SYSTEM'),
(18, 'ENTRY', 'REJECT', '2025-08-18 13:00:40', 'D3E9FD13'),
(19, 'ENTRY', 'REJECT', '2025-08-18 13:00:45', 'D3E9FD13'),
(20, 'ENTRY', 'ACCEPT', '2025-08-18 13:00:53', '90172383'),
(21, 'ENTRY', 'Open', '2025-08-18 18:00:53', 'SYSTEM'),
(22, 'ENTRY', 'OPEN', '2025-08-18 13:00:53', 'RFID'),
(23, 'ENTRY', 'Close', '2025-08-18 18:00:56', 'SYSTEM'),
(24, 'ENTRY', 'CLOSE', '2025-08-18 13:00:56', 'SYSTEM'),
(25, 'ENTRY', 'ACCEPT', '2025-08-18 13:01:18', '90172383'),
(26, 'ENTRY', 'REJECT', '2025-08-18 13:01:42', 'D3E9FD13'),
(27, 'EXIT', 'REJECT', '2025-08-18 13:02:40', 'F36E5428'),
(28, 'ENTRY', 'Open', '2025-08-20 10:54:56', 'SYSTEM'),
(29, 'ENTRY', 'Close', '2025-08-20 10:54:59', 'SYSTEM'),
(30, 'EXIT', 'Open', '2025-08-20 10:57:35', 'SYSTEM'),
(31, 'EXIT', 'Close', '2025-08-20 10:57:38', 'SYSTEM'),
(32, 'ENTRY', 'ACCEPT', '2025-08-20 09:01:10', '90172383'),
(33, 'ENTRY', 'Open', '2025-08-20 14:01:10', 'SYSTEM'),
(34, 'ENTRY', 'OPEN', '2025-08-20 09:01:10', 'MQTT'),
(35, 'ENTRY', 'Close', '2025-08-20 14:01:13', 'SYSTEM'),
(36, 'ENTRY', 'CLOSE', '2025-08-20 09:01:13', 'SYSTEM'),
(37, 'EXIT', 'ACCEPT', '2025-08-20 09:01:33', '90172383'),
(38, 'EXIT', 'Open', '2025-08-20 14:01:34', 'SYSTEM'),
(39, 'EXIT', 'OPEN', '2025-08-20 09:01:34', 'MQTT'),
(40, 'EXIT', 'Close', '2025-08-20 14:01:37', 'SYSTEM'),
(41, 'EXIT', 'CLOSE', '2025-08-20 09:01:37', 'SYSTEM'),
(42, 'EXIT', 'Open', '2025-08-20 14:01:41', 'SYSTEM'),
(43, 'EXIT', 'OPEN', '2025-08-20 09:01:41', 'RFID'),
(44, 'EXIT', 'Close', '2025-08-20 14:01:44', 'SYSTEM'),
(45, 'EXIT', 'CLOSE', '2025-08-20 09:01:44', 'SYSTEM'),
(46, 'EXIT', 'ACCEPT', '2025-08-20 09:01:49', '90172383'),
(47, 'EXIT', 'Open', '2025-08-20 14:01:49', 'SYSTEM'),
(48, 'EXIT', 'OPEN', '2025-08-20 09:01:49', 'RFID'),
(49, 'EXIT', 'Open', '2025-08-20 14:01:49', 'SYSTEM'),
(50, 'EXIT', 'OPEN', '2025-08-20 09:01:49', 'MQTT'),
(51, 'EXIT', 'Close', '2025-08-20 14:01:52', 'SYSTEM'),
(52, 'EXIT', 'CLOSE', '2025-08-20 09:01:52', 'SYSTEM'),
(53, 'EXIT', 'REJECT', '2025-08-20 09:01:53', '4E51625A'),
(54, 'EXIT', 'ACCEPT', '2025-08-20 09:01:56', '90172383'),
(55, 'EXIT', 'Open', '2025-08-20 14:01:56', 'SYSTEM'),
(56, 'EXIT', 'OPEN', '2025-08-20 09:01:56', 'MQTT'),
(57, 'EXIT', 'Close', '2025-08-20 14:01:59', 'SYSTEM'),
(58, 'EXIT', 'CLOSE', '2025-08-20 09:01:59', 'SYSTEM'),
(59, 'EXIT', 'Open', '2025-08-20 14:02:12', 'SYSTEM'),
(60, 'EXIT', 'OPEN', '2025-08-20 09:02:12', 'RFID'),
(61, 'EXIT', 'Close', '2025-08-20 14:02:15', 'SYSTEM'),
(62, 'EXIT', 'CLOSE', '2025-08-20 09:02:15', 'SYSTEM'),
(63, 'EXIT', 'ACCEPT', '2025-08-20 09:02:24', '90172383'),
(64, 'EXIT', 'Open', '2025-08-20 14:02:24', 'SYSTEM'),
(65, 'EXIT', 'OPEN', '2025-08-20 09:02:24', 'MQTT'),
(66, 'EXIT', 'Close', '2025-08-20 14:02:27', 'SYSTEM'),
(67, 'EXIT', 'CLOSE', '2025-08-20 09:02:27', 'SYSTEM'),
(68, 'EXIT', 'Open', '2025-08-20 14:02:36', 'SYSTEM'),
(69, 'EXIT', 'OPEN', '2025-08-20 09:02:36', 'RFID'),
(70, 'EXIT', 'Close', '2025-08-20 14:02:38', 'SYSTEM'),
(71, 'EXIT', 'CLOSE', '2025-08-20 09:02:39', 'SYSTEM'),
(72, 'EXIT', 'REJECT', '2025-08-20 09:02:48', '4E51625A'),
(73, 'EXIT', 'REJECT', '2025-08-20 09:02:49', '4E51625A'),
(74, 'EXIT', 'REJECT', '2025-08-20 09:02:51', '4E51625A'),
(75, 'EXIT', 'ACCEPT', '2025-08-20 09:02:53', '90172383'),
(76, 'EXIT', 'Open', '2025-08-20 14:02:53', 'SYSTEM'),
(77, 'EXIT', 'OPEN', '2025-08-20 09:02:54', 'MQTT'),
(78, 'EXIT', 'Close', '2025-08-20 14:02:57', 'SYSTEM'),
(79, 'EXIT', 'CLOSE', '2025-08-20 09:02:57', 'SYSTEM'),
(80, 'EXIT', 'REJECT', '2025-08-20 09:02:58', '4E51625A'),
(81, 'EXIT', 'ACCEPT', '2025-08-20 09:03:19', '90172383'),
(82, 'EXIT', 'Open', '2025-08-20 14:03:19', 'SYSTEM'),
(83, 'EXIT', 'OPEN', '2025-08-20 09:03:19', 'MQTT'),
(84, 'EXIT', 'Close', '2025-08-20 14:03:22', 'SYSTEM'),
(85, 'EXIT', 'CLOSE', '2025-08-20 09:03:22', 'SYSTEM'),
(86, 'EXIT', 'ACCEPT', '2025-08-20 09:03:58', '90172383'),
(87, 'EXIT', 'Open', '2025-08-20 14:03:58', 'SYSTEM'),
(88, 'EXIT', 'OPEN', '2025-08-20 09:03:58', 'MQTT'),
(89, 'EXIT', 'Close', '2025-08-20 14:04:01', 'SYSTEM'),
(90, 'EXIT', 'CLOSE', '2025-08-20 09:04:01', 'SYSTEM'),
(91, 'EXIT', 'ACCEPT', '2025-08-20 09:04:12', '90172383'),
(92, 'EXIT', 'Open', '2025-08-20 14:04:12', 'SYSTEM'),
(93, 'EXIT', 'OPEN', '2025-08-20 09:04:12', 'MQTT'),
(94, 'EXIT', 'Close', '2025-08-20 14:04:15', 'SYSTEM'),
(95, 'EXIT', 'CLOSE', '2025-08-20 09:04:15', 'SYSTEM'),
(96, 'EXIT', 'REJECT', '2025-08-20 09:04:17', '4E51625A');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `information`
--

CREATE TABLE `information` (
  `Email` text NOT NULL,
  `Password` text NOT NULL,
  `Name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `DateOfBirth` date NOT NULL,
  `Address` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `PhoneNumber` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Đang đổ dữ liệu cho bảng `information`
--

INSERT INTO `information` (`Email`, `Password`, `Name`, `DateOfBirth`, `Address`, `PhoneNumber`) VALUES
('vinh.phan@eiu.edu.vn', '123456', 'Vinh', '2018-10-10', 'Binh Duong', NULL),
('khang.vo.k3set@eiu.edu.vn', '123456', 'duykhang', '1995-01-29', 'Binh Duong', NULL),
('thanh.tran.k2000@gmail.com', '123456', 'Thanh', '1994-09-27', 'BD-BB', NULL),
('pvvinhbk@gmail.com', 'abc@123', 'Vinh Phan', '1984-12-08', 'Phu Hoa, TDM, BD', NULL),
('truongthivan2005@gmail.com', '123456', 'Trương Thị Vân', '0000-00-00', 'Thủ Dầu Một', '01636194138'),
('trang.p.cit21@eiu.edu.vn', '123456', 'Phạm Nguyễn Bảo Trang', '0000-00-00', 'Thủ Dầu Một', '071273912');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `parkinghistory`
--

CREATE TABLE `parkinghistory` (
  `HistoryID` int(20) NOT NULL,
  `RFID` varchar(20) DEFAULT NULL,
  `SlotID` int(11) DEFAULT NULL,
  `TimeIn` datetime DEFAULT NULL,
  `TimeOut` datetime DEFAULT NULL,
  `Duration` int(11) DEFAULT NULL,
  `Fee` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `parkinghistory`
--

INSERT INTO `parkinghistory` (`HistoryID`, `RFID`, `SlotID`, `TimeIn`, `TimeOut`, `Duration`, `Fee`) VALUES
(1, '5E68200E', 1, '2025-08-18 15:51:49', NULL, NULL, NULL),
(2, '90172383', 6, '2025-08-18 15:52:34', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `parkingslot`
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
(5, '1', 'B', 0, NULL),
(6, '2', 'B', 0, NULL),
(7, '3', 'B', 0, NULL),
(8, '4', 'B', 0, NULL),
(9, '1', 'C', 0, NULL),
(10, '2', 'C', 0, NULL),
(11, '3', 'C', 0, NULL),
(12, '4', 'C', 0, NULL),
(13, '1', 'D', 0, NULL),
(14, '2', 'D', 0, NULL),
(15, '3', 'D', 0, NULL),
(16, '4', 'D', 0, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `rfidcard`
--

CREATE TABLE `rfidcard` (
  `RFID` varchar(20) NOT NULL,
  `OwnerName` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `VehiclePlate` varchar(15) DEFAULT NULL,
  `Type` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `rfidcard`
--

INSERT INTO `rfidcard` (`RFID`, `OwnerName`, `VehiclePlate`, `Type`) VALUES
('5E68200E', 'Phạm Nguyễn Bảo Trang', '61K - 7813129', 'SUV'),
('90172383', 'Trương Thị Vân', '93A - 779312', 'Basic');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `gatelog`
--
ALTER TABLE `gatelog`
  ADD PRIMARY KEY (`LogID`);

--
-- Chỉ mục cho bảng `parkinghistory`
--
ALTER TABLE `parkinghistory`
  ADD PRIMARY KEY (`HistoryID`),
  ADD KEY `RFID` (`RFID`),
  ADD KEY `SlotID` (`SlotID`);

--
-- Chỉ mục cho bảng `parkingslot`
--
ALTER TABLE `parkingslot`
  ADD PRIMARY KEY (`SlotID`);

--
-- Chỉ mục cho bảng `rfidcard`
--
ALTER TABLE `rfidcard`
  ADD PRIMARY KEY (`RFID`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `gatelog`
--
ALTER TABLE `gatelog`
  MODIFY `LogID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT cho bảng `parkinghistory`
--
ALTER TABLE `parkinghistory`
  MODIFY `HistoryID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `parkingslot`
--
ALTER TABLE `parkingslot`
  MODIFY `SlotID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `parkinghistory`
--
ALTER TABLE `parkinghistory`
  ADD CONSTRAINT `parkinghistory_ibfk_1` FOREIGN KEY (`RFID`) REFERENCES `rfidcard` (`RFID`),
  ADD CONSTRAINT `parkinghistory_ibfk_2` FOREIGN KEY (`SlotID`) REFERENCES `parkingslot` (`SlotID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
