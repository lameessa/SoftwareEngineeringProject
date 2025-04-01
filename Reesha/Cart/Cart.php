<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 


session_start();
include_once("../utils/auto_cart_check.php");

$conn = mysqli_connect("localhost", "root", "root", "reesha");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$userID = $_SESSION['user_id'] ?? null;
if (!$userID) {
    header("Location: ../Login/Login.php");
    exit();
}

// حذف العمل من السلة
if (isset($_GET['remove'])) {
    $artID = intval($_GET['remove']);
    mysqli_query($conn, "DELETE FROM cart WHERE UserID='$userID' AND ArtworkID=$artID");
    $message = "Item removed from your cart.";
}

// جلب الأعمال من جدول artwork المرتبطة بـ cart
$query = "
    SELECT artwork.ArtworkID, artwork.Title, artwork.Price, artwork.ArtPic
    FROM cart
    JOIN artwork ON cart.ArtworkID = artwork.ArtworkID
    WHERE cart.UserID = '$userID'
";
$result = mysqli_query($conn, $query);

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart</title>

    <link rel="stylesheet" href="cart.css">
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
            <li><a href="Cart.php">Cart</a></li>
        </ul>
        <div class="icons">
           <img src="../images/heart.png" alt="Wishlist" id="wishlist-header" onclick="window.location.href='../Wishlist/Wishlist.php'">
            <img src="../images/profile.png" alt="Profile" id="profile-header" onclick="window.location.href='../Profile/Profile.php'" >
        </div>
    </nav>
</header>

<div class="container">
    <div class="cart-section">
        <div class="cart-items">
            <h2>My Cart</h2>

            <?php if (isset($message)): ?>
                <div class="cart-message"><?= $message ?></div>
            <?php endif; ?>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <?php $total += $row['Price']; ?>
                    <div class="cart-item">
                        <img src="<?= $row['ArtPic'] ?>" alt="<?= $row['Title'] ?>">
                        <div class="item-info">
                            <h3><?= $row['Title'] ?></h3>
                            <p><strong>Price:</strong> $<?= $row['Price'] ?></p>

                            <a href="Cart.php?remove=<?= $row['ArtworkID'] ?>" 
                               onclick="return confirm('Are you sure you want to remove this item?');"
                               class="delete-btn"
                               data-title="<?= htmlspecialchars($row['Title']) ?>">✖</a>

                        </div>

                    </div>
                        <div class="shipping">
                            <p><strong>Shipping method</strong></p>
                            <input type="radio" checked> Delivery Included
                        </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Your cart is currently empty.</p>
            <?php endif; ?>
        </div>

<div class="checkout-section">
    <p><strong>Order details</strong></p>
    <p><strong>Total:</strong> <span id="total-price" style="float: right;">$<?= $total ?></span></p>
    <input type="checkbox"> By clicking, you agree to our Terms & Conditions
    <a href="checkout.php" class="checkout-btn">Checkout</a>
</div>
    </div>
</div>

<footer>
    <p>&copy; IT320 2025 Reesha. All rights reserved.</p>
</footer>
</body>
</html>

