<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php
$host = "localhost";
$dbUser = "root";
$dbPass = "root";
$dbName = "reesha";

$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $artworkID = $_GET['id'];
    $artworkID = intval($artworkID); // للحماية

    $sql = "SELECT * FROM artwork WHERE ArtworkID = $artworkID";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $art = mysqli_fetch_assoc($result);
    } else {
        die("artwork not found");
    }
} else {
    die("لم يتم تحديد العمل الفني.");
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Artwork Details</title>
    <link rel="stylesheet" href="ArtworkDetails.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="../images/logo.png" alt="Reesha">
        <span>Reesha ريشة</span>
    </div>
    <nav>
        <ul class="nav-links">
            <li><a href="../Home/index.html">Home</a></li>
            <li><a href="../Search/Search.html">Search</a></li>
            <li><a href="../Auction/Auction.html">Auctions</a></li>
            <li><a href="../Cart/Cart.html">Cart</a></li>
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
                <p>Description: <?= htmlspecialchars($art['Descreption']) ?></p>
                <p><?= htmlspecialchars($art['UserName']) ?></p>
                <p>Category: <?= htmlspecialchars($art['Category']) ?></p>
                <p id="price">Price: $<?= htmlspecialchars($art['Price']) ?></p>
                <p>Size: <?= htmlspecialchars($art['Size']) ?></p>
                <p>Available</p>
                <div class="buttons-container">
                    <button class="add-to-cart">Add to Cart</button>
                    <img src="../images/heart.png" alt="Wishlist" class="wishlist">
                </div>
            </div>
        </div>
        <div class="right">
            <div class="image-container">
                <img src="../images/<?= htmlspecialchars($art['ArtPic']) ?>" alt="Inner Image">
            </div>
        </div>
    </div>
</main>

<footer>
    <p>&copy; IT320 2025 Reesha. All rights reserved.</p>
</footer>
</body>
</html>

