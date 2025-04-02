<?php
session_start();
include_once("../utils/notification_popup.php");
include_once("../utils/auto_cart_check.php");

date_default_timezone_set('Asia/Riyadh');

$host = "localhost";
$dbUser = "root";
$dbPass = "root";
$dbName = "reesha";

$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$artworkID = $_GET['id'] ?? null;
$userID = $_SESSION['user_id'] ?? null;

if (!$artworkID) {
    die("Artwork ID is missing.");
}

// Get artwork + auction + artist info
$query = "
    SELECT a.Title, a.Description, a.Size, a.Category, a.Price AS StartPrice, a.ArtPic,
           a.UserID AS OwnerID, u.UserName AS SellerName,
           au.AuctionID, au.StartTime, au.EndTime, au.HighestBid, au.HighestBidderID,
           ub.UserName AS BidderName
    FROM Artwork a
    JOIN Auction au ON a.ArtworkID = au.ArtworkID
    JOIN User u ON a.UserID = u.UserID
    LEFT JOIN User ub ON au.HighestBidderID = ub.UserID
    WHERE a.ArtworkID = '$artworkID'
";


$result = mysqli_query($conn, $query);
$art = mysqli_fetch_assoc($result);

if (!$art) {
    die("Artwork not found.");
}

// Get highest bidder username if exists
$highestBidderName = $art['BidderName'] ?? null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Auction Details</title>
    <link rel="stylesheet" href="AuctionDetails.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../images/logo.png" alt="Reesha">
            <span>Reesha ريشة</span>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="../Home/index.php">Home</a></li>
                <li><a href="../Search/Search.php">Search</a></li>
                <li><a href="../Auction/Auction.php">Auctions</a></li>
                <li><a href="../Cart/Cart.php">Cart</a></li>
            </ul>
            <div class="icons">
                <img src="../images/heart.png" alt="Wishlist" id="wishlist-header">
                <img src="../images/profile.png" alt="Profile" id="profile-header">
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
            <div class="left">
                <div class="content">
                    <h1 id="art-title"><?= htmlspecialchars($art['Title']) ?></h1>
                    <p id="description"><?= htmlspecialchars($art['Description']) ?></p>
                    <p id="artist"><?= htmlspecialchars($art['UserName']) ?></p>
                    <p>Size: <?= htmlspecialchars($art['Size']) ?></p>
                    <p>Category: <?= htmlspecialchars($art['Category']) ?></p>
                    <p id="timeleft" data-endtime="<?= $art['EndTime'] ?>">Loading...</p>
                    <p id="price">
    <?php if (is_null($art['HighestBidderID'])) : ?>
        <strong>Starting Price:</strong> $<?= number_format($art['StartPrice'], 2) ?>
    <?php else : ?>
        <strong>Highest bid:</strong> $<?= number_format($art['HighestBid'], 2) ?>
        <small>for (<?= htmlspecialchars($highestBidderName) ?>)</small>
    <?php endif; ?>
</p>


<?php
    $now = date("Y-m-d H:i:s");
    $isSeller = $userID === $art['OwnerID'];
    $auctionEnded = strtotime($art['EndTime']) <= time();
// Auto-add to cart if winner and auction ended


    
if ($auctionEnded && $userID === $art['HighestBidderID']) {
    $insertCartQuery = "INSERT INTO Cart (UserID, ArtworkID) VALUES ('$userID', '$artworkID')";
    if (mysqli_query($conn, $insertCartQuery)) {
        echo "<script>console.log('Artwork moved to cart for user $userID');</script>";
    } else {
        echo "<script>alert('Failed to insert into cart: " . mysqli_error($conn) . "');</script>";
    }
}




?>



<?php if ($isSeller): ?>
    <?php if ($auctionEnded): ?>
        <?php if ($art['HighestBidderID']) : ?>
            <p class="auction-status" style="color: #4caf50; font-weight: bold;">
                 Your artwork has been sold for $<?= number_format($art['HighestBid'], 2) ?>!
            </p>
        <?php else: ?>
            <p class="auction-status" style="color: #ff6666; font-weight: bold;">
                Your artwork hasn’t been sold. No bids were placed during the auction.
            </p>
        <?php endif; ?>
    <?php else: ?>
        <p class="auction-status" style="color: #ccc; font-style: italic;">
            Your auction is live. You’ll be notified when it ends.
        </p>
    <?php endif; ?>
<?php else: ?>
<?php if (!$auctionEnded): ?>
    <?php if ($userID): ?>
        <!-- ✅ Logged-in user can place a bid -->
        <form action="place_bid.php" method="POST">
            <input type="hidden" name="artwork_id" value="<?= $artworkID ?>">
            <label for="bidAmount">Place your bid:</label>
            <input type="number" name="bid_amount" id="bidAmount" placeholder="Enter bid amount" required step="0.01" min="0">
            <div class="buttons-container">
                <button type="submit" class="place-bid">Place Bid</button>
                <img src="../images/heart.png" alt="Wishlist" class="wishlist">
            </div>
        </form>
    <?php else: ?>
        <!-- 🚫 Not logged in — show a message and redirect -->
        <p class="auction-status" style="color: #ded0c8; font-weight: bold;">
            Please <a href="../Login/Login.php?redirected=true">log in</a> or <a href="../SignUp/SignUp.php?redirected=true">sign up</a> to place a bid.
        </p>
    <?php endif; ?>
<?php else: ?>
    <p class="auction-status" style="font-weight: bold; color: #ccc;">
        Auction has ended.
    </p>
<?php endif; ?>

<?php endif; ?>

                </div>
            </div>

            <div class="right">
                <div class="image-container">
                    <img id="artwork-image" src="<?= $art['ArtPic'] ?>" alt="Artwork" class="artwork-image">
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; IT320 2025 Reesha. All rights reserved.</p>
    </footer>

    <script>
        
        document.querySelector(".logo").addEventListener("click", () => {
            window.location.href = "../Home/index.php";
        });
        document.querySelector("#wishlist-header").addEventListener("click", () => {
            window.location.href = "../Wishlist/Wishlist.php";
        });
        document.querySelector("#profile-header").addEventListener("click", () => {
            window.location.href = "../Profile/Profile.php";
        });

        const timeLeftElement = document.getElementById("timeleft");
        const endTime = timeLeftElement.dataset.endtime;

        function calculateTimeLeft(endTime) {
            const now = new Date();
            const endDate = new Date(endTime);
            const timeDiff = endDate - now;

            if (timeDiff <= 0) {
                return "Auction has ended";
            }

            const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);

            return `${days}d ${hours}h ${minutes}m ${seconds}s`;
        }
        
        

        setInterval(() => {
            timeLeftElement.textContent = "Time Left: " + calculateTimeLeft(endTime);
        }, 1000);
        
            const end = new Date(endTime).getTime();
    const now = new Date().getTime();
    const diff = end - now;

    if (diff > 0) {
        setTimeout(() => {
            location.reload(); // reload page when auction ends
        }, diff);
    }
    </script>
</body>
</html>
