<?php
if (!isset($conn)) {
    $host = "localhost";
    $dbUser = "root";
    $dbPass = "root";
    $dbName = "reesha";
    $conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);

    if (!$conn) {
        die("DB connection failed in auto_cart_check.php");
    }
}

$query = "
    SELECT au.ArtworkID, au.HighestBidderID
    FROM Auction au
    LEFT JOIN Cart c ON au.ArtworkID = c.ArtworkID AND au.HighestBidderID = c.UserID
    WHERE au.EndTime <= NOW()
      AND au.HighestBidderID IS NOT NULL
      AND c.CartID IS NULL
";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $userID = $row['HighestBidderID'];
    $artworkID = $row['ArtworkID'];

    mysqli_query($conn, "INSERT INTO Cart (UserID, ArtworkID) VALUES ('$userID', '$artworkID')");
}
?>
