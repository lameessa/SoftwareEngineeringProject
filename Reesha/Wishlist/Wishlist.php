<?php
session_start();
include_once("../utils/notification_popup.php");
include_once("../utils/auto_cart_check.php");

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/Login.php");
    exit();
}

// Database connection
$host = "localhost";
$dbUser = "root";
$dbPass = "root";
$dbName = "reesha";

$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id']; // Assuming this is the UserID (varchar)

// Fetch wishlist with artwork details
$sql = "SELECT w.WishlistID, a.Title, a.UserName, a.Price, a.ArtPic 
        FROM Wishlist w
        JOIN artwork a ON w.ArtworkID = a.ArtworkID
        WHERE w.UserID = '$user_id'";

$result = mysqli_query($conn, $sql);
$wishlist_items = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $wishlist_items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wishlist - Reesha</title>
    <link rel="stylesheet" href="WishlistStyle.css">
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
                <img src="../images/profile.png" alt="Profile" id="profile-header" data-profile-link="../Profile/Profile.php">
            </div>
        </nav>
    </header>

    <main class="wishlist-container">
        <h1 class="wishlist-title">My Wishlist</h1>
        <p class="collection-desc">Save your favorite pieces, revisit them anytime, and bring home the art you love.</p>
        <div class="divider"></div>
        <div class="wishlist-grid">
            <?php if (!empty($wishlist_items)): ?>
                <?php foreach ($wishlist_items as $item): ?>
                    <div class="wishlist-item">
                        <img src="<?= htmlspecialchars($item['ArtPic']) ?>" alt="<?= htmlspecialchars($item['Title']) ?>">
                        <div class="art-wish-text">
                            <p id="art-details">
                                <span id="art-title"><?= htmlspecialchars($item['Title']) ?></span><br>
                                <span id="artist"><?= htmlspecialchars($item['UserName']) ?></span>‎ ‎ ‎ 
                                <span id="price">$<?= number_format($item['Price'], 0) ?></span>
                            </p>
                            <span class="remove-wishlist" data-wishlist-id="<?= $item['WishlistID'] ?>"> × </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty-wishlist" style="text-align:center;">Your wishlist is empty.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; IT320 2025 Reesha. All rights reserved.</p>
    </footer>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector(".logo").addEventListener("click", function() {
            window.location.href = "../Home/index.php";
        });

        document.querySelector("#profile-header").addEventListener("click", function() {
            window.location.href = this.getAttribute("data-profile-link");
        });

        document.querySelectorAll(".remove-wishlist").forEach(button => {
            button.addEventListener("click", function(e) {
                e.stopPropagation();
                const wishlistId = this.getAttribute("data-wishlist-id");

                if (confirm("Are you sure you want to remove this artwork from your wishlist?")) {
                    fetch("RemoveWishlist.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "wishlist_id=" + wishlistId
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert("Removed successfully.");
                            location.reload();
                        } else {
                            alert("Error: " + data.message);
                        }
                    });
                }
            });
        });
    });
    </script>
</body>
</html>
