<?php
session_start(); // ✅ Only once, at the top

$host = "localhost";
$dbUser = "root";
$dbPass = "root";
$dbName = "reesha";

$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$userID = $_SESSION['user_id'] ?? null;
$artworkID = $_POST['artwork_id'] ?? null;
$bidAmount = $_POST['bid_amount'] ?? null;

if (!$userID || !$artworkID || !$bidAmount) {
    die("Missing required data.");
}

// Get current auction info
$query = "
    SELECT AuctionID, StartPrice, CurrentBid, HighestBidderID 
    FROM Auction 
    WHERE ArtworkID = '$artworkID'
";
$result = mysqli_query($conn, $query);
$auction = mysqli_fetch_assoc($result);

if (!$auction) {
    die("Auction not found.");
}

$currentBid = $auction['CurrentBid'];
$highestBidderID = $auction['HighestBidderID'];
$validBid = false;

// 👑 Logic using CurrentBid
if ($bidAmount < $currentBid) {
    $errorMessage = "Your bid must be at least $$currentBid.";
} elseif ($bidAmount == $currentBid && is_null($highestBidderID)) {
    $validBid = true;
} elseif ($bidAmount > $currentBid) {
    $validBid = true;
} else {
    $errorMessage = "Your bid must be higher than the current bid.";
}

// ✅ If valid, update auction
if ($validBid) {
    $auctionID = $auction['AuctionID'];

    $updateQuery = "
        UPDATE Auction 
        SET 
            HighestBid = '$bidAmount',
            CurrentBid = '$bidAmount',
            HighestBidderID = '$userID'
        WHERE AuctionID = '$auctionID'
    ";

if (mysqli_query($conn, $updateQuery)) {
    header("Location: AuctionDetails.php?id=$artworkID");
    exit;
}
 else {
        die("Failed to update bid.");
    }
} else {
    $_SESSION['bid_error'] = $errorMessage;
    header("Location: AuctionDetails.php?id=$artworkID");
    exit;
}
?>
