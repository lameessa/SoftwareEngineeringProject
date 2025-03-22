<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/login.php");
    exit();
}

$userID = $_SESSION['user_id'];

// Database connection
$host = "localhost";
$dbUser = "root";
$dbPass = "root";
$dbName = "reesha";
$port = '8889';

$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch user info from User table
$userQuery = "SELECT * FROM User WHERE UserID = '$userID'";
$userResult = mysqli_query($conn, $userQuery);

if ($userResult && mysqli_num_rows($userResult) > 0) {
    $user = mysqli_fetch_assoc($userResult);

    $fullName = $user['UserName'];
    $email = $user['Email'];
    $profilePic = $user['UserPic'] ? $user['UserPic'] : '../images/profile-placeholder.png'; // Fallback profile pic
    $bio=$user['Bio'];
} else {
    $fullName = "Unknown User";
    $email = "";
    $profilePic = '../images/profile-placeholder.png';
    $bio='';
}

// Handle deleting selected artworks
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_selected'])) {
    if (!empty($_POST['selected_artworks'])) {
        $selected = $_POST['selected_artworks'];
        $selectedIDs = implode(',', array_map('intval', $selected));

        $deleteQuery = "DELETE FROM Artwork WHERE ArtworkID IN ($selectedIDs) AND UserID = '$userID'";

        if (mysqli_query($conn, $deleteQuery)) {
            $message = "Selected artworks deleted successfully.";
        } else {
            $message = "Error deleting artworks: " . mysqli_error($conn);
        }
    } else {
        $message = "No artworks selected for deletion.";
    }
}

// Fetch artworks for the logged-in user
$artworksQuery = "SELECT * FROM Artwork WHERE UserID = '$userID'";
$artworksResult = mysqli_query($conn, $artworksQuery);
$hasArtworks = mysqli_num_rows($artworksResult) > 0;

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Reesha</title>
    <link rel="stylesheet" href="profile.css">
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
            <a href="../Wishlist/Wishlist.php"><img src="../images/heart.png" alt="Wishlist"></a>
            <a href="Profile.php"><img src="../images/profile.png" alt="Profile"></a>
        </div>
    </nav>
</header>

<main class="profile-container">
    <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="User Profile" class="profile-pic">

    <div class="profile-info">
        <h1><?php echo htmlspecialchars($fullName); ?></h1>
        <p>@<?php echo htmlspecialchars($userID); ?></p>
        <p><?php echo htmlspecialchars($email); ?></p>
        <p class="bio"><?php echo htmlspecialchars($bio); ?></p>
        <a href="SignOut.php" class="logout-link">Log Out</a>

    </div>
</main>

<div class="divider"></div>

<section class="artworks">
    <h2>Collection</h2>
    <p class="collection-desc">Explore a curated selection of <?php echo htmlspecialchars($fullName); ?> finest works, blending modern abstraction with classical influences.</p>

    <?php if (isset($message)) : ?>
        <p style="color: green;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="Profile.php">
        <div class="art-container">
            <div class="art-grid">
                <?php if ($hasArtworks): ?>
                    <?php while ($art = mysqli_fetch_assoc($artworksResult)) : ?>
                        <div class="art-item">
                            <input type="checkbox" name="selected_artworks[]" value="<?php echo $art['ArtworkID']; ?>" class="delete-checkbox">

                            <img src="<?php echo htmlspecialchars($art['ArtPic']); ?>" alt="<?php echo htmlspecialchars($art['Title']); ?>">

                            <h3><?php echo htmlspecialchars($art['Title']); ?></h3>

                            <p><?php echo htmlspecialchars($art['Description']); ?></p>
                            <p>Category: <?php echo htmlspecialchars($art['Category']); ?></p>
                            <p><?php echo htmlspecialchars($art['Size']); ?></p>
                            <p>$<?php echo htmlspecialchars($art['Price']); ?></p>
                            <p>Available</p>

                            <p>
                                <a href="../Edit/edit.php?artworkID=<?php echo $art['ArtworkID']; ?>" class="edit-icon">✎</a>
                            </p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                <p>No artworks available at the moment. Start adding your creations!</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="artwork-buttons">
            <button type="button" id="add-artwork" onclick="window.location.href='../Add/addartwork.php';">Add New Artwork</button>
            <button type="submit" id='delete-artwork' name="delete_selected" class="delete-btn">Delete Selected Artworks</button>
        </div>
    </form>
</section>

<footer>
    <p>&copy; IT320 2025 Reesha. All rights reserved.</p>
</footer>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Click logo redirects to home
        document.querySelector(".logo").addEventListener("click", function () {
            window.location.href = "../Home/index.php";
        });

        // Wishlist redirect
        document.querySelector(".icons a[href='../Wishlist/Wishlist.php']").addEventListener("click", function (e) {
            e.preventDefault();
            window.location.href = "../Wishlist/Wishlist.php";
        });

        // Profile redirect
        document.querySelector(".icons a[href='Profile.php']").addEventListener("click", function (e) {
            e.preventDefault();
            window.location.href = "Profile.php";
        });
    });
</script>

</body>
</html>
