<?php
if (!isset($conn)) {
    $conn = mysqli_connect("localhost", "root", "root", "reesha");
    if (!$conn) {
        die("DB connection failed in auto_cart_check.php");
    }
}

$query = "
    SELECT au.ArtworkID, au.HighestBidderID, a.Title
    FROM Auction au
    JOIN Artwork a ON a.ArtworkID = au.ArtworkID
    LEFT JOIN Cart c ON au.ArtworkID = c.ArtworkID AND au.HighestBidderID = c.UserID
    WHERE au.EndTime <= NOW()
      AND au.HighestBidderID IS NOT NULL
      AND c.CartID IS NULL
";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $userID = $row['HighestBidderID'];
    $artworkID = $row['ArtworkID'];
    $artworkTitle = mysqli_real_escape_string($conn, $row['Title']);

    // 1. Add to Cart
    mysqli_query($conn, "INSERT INTO Cart (UserID, ArtworkID) VALUES ('$userID', '$artworkID')");

    // 2. Escape message properly
    $message = " Congratulations! You’ve won the auction for \"$artworkTitle\". Visit your cart to complete the payment.";

    $escapedMessage = mysqli_real_escape_string($conn, $message);

    // 3. Insert notification
    mysqli_query($conn, "INSERT INTO Notifications (UserID, Message) VALUES ('$userID', '$escapedMessage')");
}
?>
