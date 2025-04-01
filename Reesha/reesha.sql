-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 01, 2025 at 12:33 AM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reesha`
--

-- --------------------------------------------------------

--
-- Table structure for table `artwork`
--

CREATE TABLE `artwork` (
  `ArtworkID` int(11) NOT NULL,
  `UserID` varchar(255) NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Category` varchar(255) NOT NULL,
  `Size` varchar(255) NOT NULL,
  `Price` decimal(10,0) NOT NULL,
  `ArtPic` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `artwork`
--

INSERT INTO `artwork` (`ArtworkID`, `UserID`, `UserName`, `Title`, `Description`, `Category`, `Size`, `Price`, `ArtPic`) VALUES
(1, 'Yasmin_', 'Yasmin Azzam', 'Distorta', 'descreption1', 'Cubism', '40x50', '480', '../images/Distorta.png'),
(2, 'batool999', 'Batool Aziz', 'Evanscent', 'descreption2', 'Expressionism', '20x25', '890', '../images/Evanscent.png'),
(3, 'royaa_', 'Roya Shirazi', 'Tsubasa (翼)', 'descreption3', 'Expressionism', '10x15', '860', '../images/Tsubasa.png'),
(4, 'no0or', 'Noor Qamar', 'Swanmoon', 'descreption4', 'Realism', '10x15', '380', '../images/Swanmoon.png'),
(5, 'zaarina', 'Zarina parvaneh', 'Petals', 'descreption5', 'Realism', '20x25', '420', '../images/Petals.png'),
(6, 'Faris_Alami', 'Faris Alami', 'Ebb $ Flow', 'descreption6', 'Abstract', '40x50', '670', '../images/EbbFlow.png'),
(7, 'Lailaa', 'Laila Asmari', 'Family', 'descreption7', 'Cubism', '28x36', '770', '../images/Family.png'),
(8, 'nad1r', 'Nadir Elbaz', 'Undercurrent', 'Descreption8', 'Abstract', '28x36', '760', '../images/Undercurrent.png'),
(9, 'hiss', 'Hissah K', 'Feathers', 'Descreption9', 'Abstract', '13x18', '440', '../images/Feathers.jpg'),
(10, 'Kh_1', 'Khalid Nassar', 'Crowded', 'Descreption10', 'Expressionism', '20x25', '320', '../images/Crowded.jpg'),
(11, 'Lamyaa', 'Lamya Hamad', 'Golden Meadow', 'Descreption11', 'Realism', '10x15', '480', '../images/GoldenMeadow.jpg'),
(12, 'Rama_A', 'Rama A', 'Cubist Kat', 'Descreption12', 'Cubism', '10x15', '180', '../images/CubistKat.jpg'),
(30, 'batool999', 'Batool Aziz', 'hi', 'adfad', 'abstract', '10x15 cm', '500', '../images/Artwork_67e9f8f52ad6f.png'),
(31, 'Faris_Alami', 'Faris Alami', 'first', 'first try', 'abstract', '13x18 cm', '500', '../images/Artwork_67e9fba1555f6.png'),
(32, 'Faris_Alami', 'Faris Alami', 'second', 'qwdrwd', 'abstract', '13x18 cm', '500', '../images/Artwork_67e9fbc2ac36f.png');

-- --------------------------------------------------------

--
-- Table structure for table `auction`
--

CREATE TABLE `auction` (
  `AuctionID` int(11) NOT NULL,
  `ArtworkID` int(11) NOT NULL,
  `StartTime` datetime NOT NULL,
  `EndTime` datetime NOT NULL,
  `Currentbid` decimal(10,0) NOT NULL,
  `StartPrice` decimal(10,0) NOT NULL,
  `Highestbid` decimal(10,0) DEFAULT NULL,
  `HighestBidderID` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auction`
--

INSERT INTO `auction` (`AuctionID`, `ArtworkID`, `StartTime`, `EndTime`, `Currentbid`, `StartPrice`, `Highestbid`, `HighestBidderID`) VALUES
(14, 30, '2025-03-31 02:07:49', '2025-03-31 05:09:00', '600', '500', '600', 'Faris_Alami'),
(15, 31, '2025-03-31 02:19:13', '2025-04-30 05:19:00', '500', '500', '500', NULL),
(16, 32, '2025-03-31 02:19:46', '2025-04-01 05:19:00', '500', '500', '500', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `CartID` int(11) NOT NULL,
  `UserID` varchar(11) NOT NULL,
  `ArtworkID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` varchar(255) NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `UserPic` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `UserName`, `Email`, `UserPic`, `Password`) VALUES
('batool999', 'Batool Aziz', 'bbatool@gmail.com', '../images/ArtistPhoto2.jpg', '123'),
('Faris_Alami', 'Faris Alami', 'fariss@gmail.com', '../images/ArtistPhoto6.jpg', 'Faris&2001'),
('hiss', 'Hissah K', 'Hissah@gmail.com', '../images/ArtistPhoto1.jpg', 'hiss112233'),
('Kh_1', 'Khalid Nassar', 'Khal1d@gmail.com', '../images/ArtistPhoto10.jpg', 'Kh00112233'),
('Lailaa', 'Laila Asmari', 'lailoo@yahoo.com', '../images/ArtistPhoto4.jpg', 'Ll$1997'),
('Lamyaa', 'Lamya Hamad', 'lamya03@yahoo.com', '../images/ArtistPhoto11.jpg', 'Lha0310'),
('nad1r', 'Nadir Elbaz', 'nad1r@hotmail.com', '../images/ArtistPhoto5.jpg', 'Elbaz@123'),
('no0or', 'Noor Qamar', 'no0rQ@gmail.com', '../images/ArtistPhoto3.jpg', 'Noorq0005'),
('Rama_A', 'Rama A', 'ramaA11@gmail.com', '../images/ArtistPhoto12.jpg', 'ramaa0207!'),
('royaa_', 'Roya Shirazi', 'roya1@gmail.com', '../images/ArtistPhoto7.jpg', 'RS009988'),
('Yasmin_', 'Yasmin Azzam', 'yasAzzam@hotmail.com', '../images/ArtistPhoto9.jpg', 'Yasm1999'),
('zaarina', 'Zarina Parvaneh', 'zzrna@gmail.com', '../images/ArtistPhoto8.jpg', 'Zz112233!');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `WishlistID` int(11) NOT NULL,
  `UserID` varchar(255) NOT NULL,
  `ArtworkID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artwork`
--
ALTER TABLE `artwork`
  ADD PRIMARY KEY (`ArtworkID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `auction`
--
ALTER TABLE `auction`
  ADD PRIMARY KEY (`AuctionID`),
  ADD KEY `ArtworkID` (`ArtworkID`),
  ADD KEY `HighestBidderID` (`HighestBidderID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`CartID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ArtworkID` (`ArtworkID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`WishlistID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ArtworkID` (`ArtworkID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artwork`
--
ALTER TABLE `artwork`
  MODIFY `ArtworkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `auction`
--
ALTER TABLE `auction`
  MODIFY `AuctionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `CartID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `WishlistID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `artwork`
--
ALTER TABLE `artwork`
  ADD CONSTRAINT `artwork_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `auction`
--
ALTER TABLE `auction`
  ADD CONSTRAINT `auction_ibfk_1` FOREIGN KEY (`ArtworkID`) REFERENCES `artwork` (`ArtworkID`),
  ADD CONSTRAINT `auction_ibfk_2` FOREIGN KEY (`HighestBidderID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`ArtworkID`) REFERENCES `artwork` (`ArtworkID`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`ArtworkID`) REFERENCES `artwork` (`ArtworkID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
