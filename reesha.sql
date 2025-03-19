-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 19, 2025 at 04:02 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

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
  `ArtworkID` int NOT NULL,
  `UserID` varchar(255) NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Descreption` text NOT NULL,
  `Category` varchar(255) NOT NULL,
  `Size` varchar(255) NOT NULL,
  `Price` decimal(10,0) NOT NULL,
  `ArtPic` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `artwork`
--

INSERT INTO `artwork` (`ArtworkID`, `UserID`, `UserName`, `Title`, `Descreption`, `Category`, `Size`, `Price`, `ArtPic`) VALUES
(1, 'Yasmin_', 'Yasmin Azzam', 'Distorta', 'A striking cubist composition, blending sharp geometry and soft hues to reveal fragmented beauty.', 'Cubism', '40x50', 480, '../images/Distorta.png'),
(2, 'batool999', 'Batool Aziz', 'Evanscent', 'Delicate lines and muted greens swirl into a fleeting vision, like a memory slipping away.', 'Expressionism', '20x25', 890, '../images/Evanscent.png'),
(3, 'royaa_', 'Roya Shirazi', 'Tsubasa (翼)', 'Scene of white cranes soaring over a bold red sun, framed by blooming trees and swirling clouds in a traditional Japanese style.', 'Expressionism', '10x15', 860, '../images/Tsubasa.png'),
(4, 'no0or', 'Noor Qamar', 'Swanmoon', 'A serene and mystical depiction of two swans glowing under moonlight on still, dark waters.', 'Realism', '10x15', 380, '../images/Swanmoon.png'),
(5, 'zaarina', 'Zarina parvaneh', 'Petals', 'White lilies in quiet bloom, capturing a moment of fragile beauty.', 'Realism', '20x25', 420, '../images/Petals.png'),
(6, 'Faris_Alami', 'Faris Alami', 'Ebb $ Flow', 'A vibrant, layered composition of swirling patterns and oceanic blues, evoking the constant motion and rhythm of nature.', 'Abstract', '40x50', 670, '../images/EbbFlow.png'),
(7, 'Lailaa', 'Laila Asmari', 'Family', 'A textured and minimalist scene of abstract human figures, evoking connection and togetherness.', 'Cubism', '28x36', 770, '../images/Family.png'),
(8, 'nad1r', 'Nadir Elbaz', 'Undercurrent', 'A wild fusion of bold colors and abstract forms, pulsing with raw, untamed energy.', 'Abstract', '28x36', 760, '../images/Undercurrent.png'),
(9, 'hiss', 'Hissah K', 'Feathers', 'A fluid exploration of color and form, capturing the delicate movement and texture of abstract feathers.', 'Abstract', '13x18', 440, '../images/Feathers.jpg'),
(10, 'Kh_1', 'Khalid Nassar', 'Crowded', 'A reflection on isolation, where one vivid soul stands out amidst a sea of anonymity.', 'Expressionism', '20x25', 320, '../images/Crowded.jpg'),
(11, 'Lamyaa', 'Lamya Hamad', 'Golden Meadow', 'An ode to nature’s quiet beauty, capturing a sunlit meadow in full bloom under an endless sky.', 'Realism', '10x15', 480, '../images/GoldenMeadow.jpg'),
(12, 'Rama_A', 'Rama A', 'Cubist Kat', 'A playful fusion of geometry and whimsy, capturing the quiet elegance of a cat through a cubist lens.', 'Cubism', '10x15', 180, '../images/CubistKat.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `Auction`
--

CREATE TABLE `Auction` (
  `AuctionID` int NOT NULL,
  `ArtworkID` int NOT NULL,
  `StartTime` datetime NOT NULL,
  `EndTime` datetime NOT NULL,
  `StartPrice` decimal(10,2) NOT NULL,
  `HighestBid` decimal(10,2) DEFAULT NULL,
  `CurrentBid` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Cart`
--

CREATE TABLE `Cart` (
  `CartID` int NOT NULL,
  `UserID` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `ArtworkID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `UserName`, `Email`, `UserPic`, `Password`) VALUES
('batool999', 'Batool Aziz', 'bbatool@gmail.com', '../images/ArtistPhoto2.jpg', 'Bb040620'),
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
-- Table structure for table `Wishlist`
--

CREATE TABLE `Wishlist` (
  `WishlistID` int NOT NULL,
  `UserID` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `ArtworkID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Indexes for table `Auction`
--
ALTER TABLE `Auction`
  ADD PRIMARY KEY (`AuctionID`),
  ADD KEY `ArtworkID` (`ArtworkID`);

--
-- Indexes for table `Cart`
--
ALTER TABLE `Cart`
  ADD PRIMARY KEY (`CartID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ArtworkID` (`ArtworkID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `Wishlist`
--
ALTER TABLE `Wishlist`
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
  MODIFY `ArtworkID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `Auction`
--
ALTER TABLE `Auction`
  MODIFY `AuctionID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Cart`
--
ALTER TABLE `Cart`
  MODIFY `CartID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Wishlist`
--
ALTER TABLE `Wishlist`
  MODIFY `WishlistID` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `artwork`
--
ALTER TABLE `artwork`
  ADD CONSTRAINT `artwork_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `Auction`
--
ALTER TABLE `Auction`
  ADD CONSTRAINT `auction_ibfk_1` FOREIGN KEY (`ArtworkID`) REFERENCES `Artwork` (`ArtworkID`) ON DELETE CASCADE;

--
-- Constraints for table `Cart`
--
ALTER TABLE `Cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`ArtworkID`) REFERENCES `Artwork` (`ArtworkID`) ON DELETE CASCADE;

--
-- Constraints for table `Wishlist`
--
ALTER TABLE `Wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`ArtworkID`) REFERENCES `Artwork` (`ArtworkID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
