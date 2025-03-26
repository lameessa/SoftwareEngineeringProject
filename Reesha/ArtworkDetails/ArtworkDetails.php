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

$host = "localhost";
$dbUser = "root";
$dbPass = "root";
$dbName = "reesha";

$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$_SESSION['UserID'] = 'batool999';
$userID = $_SESSION['UserID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    

    $artID = intval($_POST['artwork_id']);
    
if (isset($_POST['add_to_cart'])) {
    $check = mysqli_query($conn, "SELECT * FROM cart WHERE UserID='$userID' AND ArtworkID=$artID");

    if (mysqli_num_rows($check) > 0) {
        $message = "This artwork is already in your cart.";
    } else {
        $sql = "INSERT INTO cart (UserID, ArtworkID) VALUES ('$userID', $artID)";
        if (mysqli_query($conn, $sql)) {
            $message = "Artwork successfully added to your cart.";
        } else {
            $message = "An error occurred while adding the artwork.";
        }
    }
}




if (isset($_POST['add_to_wishlist'])) {
    $check = mysqli_query($conn, "SELECT * FROM wishlist WHERE UserID='$userID' AND ArtworkID=$artID");

    if (mysqli_num_rows($check) > 0) {
        $message = "This artwork is already in your wishlist.";
    } else {
        $sql = "INSERT INTO wishlist (UserID, ArtworkID) VALUES ('$userID', $artID)";
        if (mysqli_query($conn, $sql)) {
            $message = "Artwork successfully added to your wishlist.";
        } else {
            $message = "An error occurred while adding the artwork to your wishlist.";
        }
    }
}

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
    die("no artwork selected");
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
                <p>Description: <?= htmlspecialchars($art['Descreption']) ?></p>
                <p><?= htmlspecialchars($art['UserName']) ?></p>
                <p>Category: <?= htmlspecialchars($art['Category']) ?></p>
                <p id="price">Price: $<?= htmlspecialchars($art['Price']) ?></p>
                <p>Size: <?= htmlspecialchars($art['Size']) ?></p>
                <p>Available</p>
 <?php if (isset($message)): ?>
    <div class="custom-toast">
        <?= $message ?>
    </div>
<?php endif; ?>

<div class="buttons-container">
    <form method="POST">
        <input type="hidden" name="artwork_id" value="<?= $art['ArtworkID'] ?>">
        <button type="submit" name="add_to_cart" class="add-to-cart">Add to Cart</button>
<button type="submit" name="add_to_wishlist" class="wishlist-btn">
    <img src="../images/heart.png" alt="Wishlist" class="wishlist-icon">
</button>

    </form>

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

