-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 10, 2025 at 05:09 PM
-- Server version: 5.7.24
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
  `ArtPic` varchar(255) NOT NULL,
  `Availability` varchar(10) DEFAULT 'Available',
  `ListingType` varchar(20) DEFAULT 'Marketplace'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `artwork`
--

INSERT INTO `artwork` (`ArtworkID`, `UserID`, `UserName`, `Title`, `Description`, `Category`, `Size`, `Price`, `ArtPic`, `Availability`, `ListingType`) VALUES
(1, 'Yasmin_', 'Yasmin Azzam', 'Distorta', 'A striking cubist composition, blending sharp geometric forms with emotional resonance.', 'Cubism', '40x50', '480', '../images/Distorta.png', 'Available', 'Marketplace'),
(2, 'batool999', 'Batool Aziz', 'Evanscent', 'Delicate lines and muted greens swirl into a fleeting impression of emotion and memory.', 'Expressionism', '20x25', '890', '../images/Evanscent.png', 'Available', 'Auction'),
(3, 'royaa_', 'Roya Shirazi', 'Tsubasa (翼)', 'Scene of white cranes soaring over a bold red sun, capturing fleeting grace in motion.', 'Expressionism', '10x15', '860', '../images/Tsubasa.png', 'Sold', 'Marketplace'),
(4, 'no0or', 'Noor Qamar', 'Swanmoon', 'A serene and mystical depiction of two swans glowing beneath the moonlight.', 'Realism', '10x15', '380', '../images/Swanmoon.png', 'Available', 'Marketplace'),
(5, 'zaarina', 'Zarina parvaneh', 'Petals', 'White lilies in quiet bloom, capturing a moment of peace and gentle introspection.', 'Realism', '20x25', '420', '../images/Petals.png', 'Available', 'Marketplace'),
(6, 'Faris_Alami', 'Faris Alami', 'Ebb $ Flow', 'A vibrant, layered composition of swirling patterns and unexpected textures.', 'Abstract', '40x50', '670', '../images/EbbFlow.png', 'Available', 'Marketplace'),
(7, 'Lailaa', 'Laila Asmari', 'Family', 'A textured and minimalist scene of a cubist human connection frozen in time.', 'Cubism', '28x36', '770', '../images/Family.png', 'Available', 'Auction'),
(8, 'nad1r', 'Nadir Elbaz', 'Undercurrent', 'A wild fusion of bold colors and abstract forms, portraying chaos and movement.', 'Abstract', '28x36', '760', '../images/Undercurrent.png', 'Sold', 'Marketplace'),
(9, 'hiss', 'Hissah K', 'Feathers', 'A fluid exploration of color and form, capturing the weightlessness of flight.', 'Abstract', '13x18', '440', '../images/Feathers.jpg', 'Available', 'Auction'),
(10, 'Kh_1', 'Khalid Nassar', 'Crowded', 'A reflection on isolation, where one vivid soul stands against a blurred crowd.', 'Expressionism', '20x25', '320', '../images/Crowded.jpg', 'Sold', 'Marketplace'),
(11, 'Lamyaa', 'Lamya Hamad', 'Golden Meadow', 'An ode to nature’s quiet beauty, capturing a sunlit field with poetic softness.', 'Realism', '10x15', '480', '../images/GoldenMeadow.jpg', 'Available', 'Marketplace'),
(12, 'Rama_A', 'Rama A', 'Cubist Kat', 'A playful fusion of geometry and whimsy, capturing a curious feline in cubist style.', 'Cubism', '10x15', '180', '../images/CubistKat.jpg', 'Sold', 'Marketplace');

--
-- Triggers `artwork`
--
DELIMITER $$
CREATE TRIGGER `update_to_auction` AFTER UPDATE ON `artwork` FOR EACH ROW BEGIN
    -- Only insert into Auction table if ListingType was changed to 'Auction' and it wasn't already auction
    IF NEW.ListingType = 'Auction' AND OLD.ListingType != 'Auction' THEN
        INSERT INTO auction (ArtworkID, StartTime, EndTime, StartPrice, HighestBid, CurrentBid)
        VALUES (
            NEW.ArtworkID,
            NOW(),
            DATE_ADD(NOW(), INTERVAL 7 DAY),
            NEW.Price,
            NEW.Price,
            NEW.Price
        );
    END IF;
END
$$
DELIMITER ;

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
(33, 2, '2025-04-01 06:52:32', '2025-04-08 06:52:32', '890', '890', '890', NULL),
(35, 9, '2025-04-01 06:53:06', '2025-04-22 06:53:06', '440', '440', '440', NULL);

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
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `NotificationID` int(11) NOT NULL,
  `UserID` varchar(255) NOT NULL,
  `Message` text NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IsRead` tinyint(1) NOT NULL DEFAULT '0'
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
('batool999', 'Batool Aziz', 'bbatool@gmail.com', '../images/ArtistPhoto2.jpg', '$2y$10$1XWp3IV7/HrXFvczLHgEnOwGGo11vevx4N26VtrEGw6lq/CwlQM8O'),
('Faris_Alami', 'Faris Alami', 'fariss@gmail.com', '../images/ArtistPhoto6.jpg', '$2y$10$WZLzfH5cyQp8ET8acmid2.YKN.5vK7FuaNwZiAb3aLEdj13nqgpoe'),
('hiss', 'Hissah K', 'Hissah@gmail.com', '../images/ArtistPhoto1.jpg', '$2y$10$3xddv8x3b/5LuTbrfW0db.JeU.6GMj9rVyEBXJtrO6Wf5hZpPEr52'),
('Kh_1', 'Khalid Nassar', 'Khal1d@gmail.com', '../images/ArtistPhoto10.jpg', '$2y$10$JKowEPHp8B/JCPRTmGe8U.X1ZJ.J7ZsVRy5SOj/1wfg/vqQAsaO1y'),
('Lailaa', 'Laila Asmari', 'lailoo@yahoo.com', '../images/ArtistPhoto4.jpg', '$2y$10$Pr5hrJNk2b6z1PDXEU3Z5O/yEpSzr.l1A0HL27Dqi6MxwSLVlodxG'),
('Lamyaa', 'Lamya Hamad', 'lamya03@yahoo.com', '../images/ArtistPhoto11.jpg', '$2y$10$qGedl4Mwn5/Z7k3flzRvZuo6.COe8kinodlSPu627UV82.UnUEI8K'),
('nad1r', 'Nadir Elbaz', 'nad1r@hotmail.com', '../images/ArtistPhoto5.jpg', '$2y$10$KwqTr1M2m4g8AU1Iqmb0/umMK1MRyLe/r0hzAdv2vKujpFNhHO2lu'),
('no0or', 'Noor Qamar', 'no0rQ@gmail.com', '../images/ArtistPhoto3.jpg', '$2y$10$eTscEFbbkVPMpa5Ao8J5QOKSft6UWAbkjFmTxwxgSPEBfFamGiFXK'),
('Rama_A', 'Rama A', 'ramaA11@gmail.com', '../images/ArtistPhoto12.jpg', '$2y$10$hwDLyMHhUBUWI3ritWLchOJu.8KQ5AiP8VTXHfhK4XWBb2FagEeui'),
('royaa_', 'Roya Shirazi', 'roya1@gmail.com', '../images/ArtistPhoto7.jpg', '$2y$10$zAFn5BQ5IlK70T1gS4M.Fepum/YjUw91rTrbghe21K8gv0lveb5jG'),
('Yasmin_', 'Yasmin Azzam', 'yasAzzam@hotmail.com', '../images/ArtistPhoto9.jpg', '$2y$10$Pjj.ZfqEg/P6P8b6XpyDq.FfgckwHLxzQTqa8EDR.9zIAt0tQnZaO'),
('zaarina', 'Zarina Parvaneh', 'zzrna@gmail.com', '../images/ArtistPhoto8.jpg', '$2y$10$/EoJ/u7/bS/0meCYLpKl/.8aW1xND0GHZajlfRfhWe3Fr5.kRpH46');

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`NotificationID`),
  ADD KEY `UserID` (`UserID`);

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
  MODIFY `ArtworkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `auction`
--
ALTER TABLE `auction`
  MODIFY `AuctionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `CartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `NotificationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `WishlistID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

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
