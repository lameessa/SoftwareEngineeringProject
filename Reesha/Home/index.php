<?php
session_start(); // Start the session
include_once("../utils/notification_popup.php");
include_once("../utils/auto_cart_check.php");

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']); // Assume 'user_id' is stored in session upon login
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Reesha</title>
    <link rel="stylesheet" href="indexStyle.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../images/logo.png" alt="Reesha">
            <span>Reesha ريشة</span>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="../Search/Search.php">Search</a></li>
                <li><a href="../Auction/Auction.php">Auctions</a></li>
                <li><a href="../Cart/Cart.php">Cart</a></li>
            </ul>
            <div class="icons">
                <img src="../images/heart.png" alt="Wishlist" id="wishlist-header">
                <img src="../images/profile.png" alt="Profile" id="profile-header" 
                     data-profile-link="<?= $is_logged_in ? '../Profile/Profile.php' : '../Login/Login.php'; ?>">
            </div>
        </nav>
    </header>

    <main class="hero">
        <div class="text-content">
            <p><span id="s1">Reesha's Vision</span> is to connect artists and collectors <br>
                in a world where every piece tells a story, every artist finds a voice, <br>
                and every collector discovers meaning in art. <br>
                <span id="s2">Reesha is more than a gallery—it’s a movement redefining art..</span>
                <?php if (!$is_logged_in): ?> 
                    <span id="s3"><a href="../Login/Login.php">Enter Your Gallery!</a></span>
                <?php endif; ?>
            </p>
        </div>
    </main>

    <div class="divider"></div>

    <footer>
        <p>&copy; IT320 2025 Reesha. All rights reserved.</p>
    </footer>

    <script>
document.addEventListener("DOMContentLoaded", function() {
    const isLoggedIn = <?= $is_logged_in ? 'true' : 'false' ?>;

    document.querySelector(".logo").addEventListener("click", function() {
        window.location.href = "index.php";
    });

    document.querySelector("#wishlist-header").addEventListener("click", function() {
        if (isLoggedIn) {
            window.location.href = "../Wishlist/Wishlist.php";
        } else {
            window.location.href = "../Login/Login.php";
        }
    });

    document.querySelector("#profile-header").addEventListener("click", function() {
        let profileLink = this.getAttribute("data-profile-link");
        window.location.href = profileLink;
    });

    document.querySelector(".nav-links li a[href='../Cart/Cart.php']").addEventListener("click", function(event) {
        if (!isLoggedIn) {
            event.preventDefault();
            window.location.href = "../Login/Login.php";
        }
    });
});
    </script>
</body>
</html>