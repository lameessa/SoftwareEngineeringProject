<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/login.php");
    exit();
}

$userID = $_SESSION['user_id'];
$viewingOwnProfile = true;
$profileUserID = $userID;

// Database connection
$host = "localhost";
$dbUser = "root";
$dbPass = "root";
$dbName = "reesha";
$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if we're viewing someone else's profile
if (isset($_GET['artistID'])) {
    $viewedUserID = $_GET['artistID'];

    if ($viewedUserID != $userID) {
        $viewingOwnProfile = false;
        $profileUserID = $viewedUserID;
    }
}

// Get user info of the profile being viewed
$userQuery = "SELECT * FROM User WHERE UserID = ?";
$stmt = mysqli_prepare($conn, $userQuery);
mysqli_stmt_bind_param($stmt, "s", $profileUserID);
mysqli_stmt_execute($stmt);
$userResult = mysqli_stmt_get_result($stmt);

if ($userResult && mysqli_num_rows($userResult) > 0) {
    $user = mysqli_fetch_assoc($userResult);
    $fullName = $user['UserName'];
    $email = $user['Email'];
    $profilePic = $user['UserPic'] ? $user['UserPic'] : '../images/profile-placeholder.png';
} else {
    $fullName = "Unknown User";
    $email = "";
    $profilePic = '../images/profile-placeholder.png';
    $viewingOwnProfile = false;
}

// Handle deleting selected artworks
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_selected']) && $viewingOwnProfile) {
    if (!empty($_POST['selected_artworks'])) {
        $selected = $_POST['selected_artworks'];
        $selectedIDs = implode(',', array_map('intval', $selected));

        // First delete from Auction table (child), then from Artwork (parent)
        $deleteAuctionQuery = "DELETE FROM Auction WHERE ArtworkID IN ($selectedIDs)";
        mysqli_query($conn, $deleteAuctionQuery);

        $deleteArtworkQuery = "DELETE FROM Artwork WHERE ArtworkID IN ($selectedIDs) AND UserID = '$userID'";
        if (mysqli_query($conn, $deleteArtworkQuery)) {
            $message = "Selected artworks deleted successfully.";
        } else {
            $message = "Error deleting artworks: " . mysqli_error($conn);
        }
    } else {
        $message = "No artworks selected for deletion.";
    }
}

// Fetch artworks for the profile user
$artworksQuery = "SELECT * FROM Artwork WHERE UserID = '$profileUserID'";
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
        <p>@<?php echo htmlspecialchars($profileUserID); ?></p>
        <p><?php echo htmlspecialchars($email); ?></p>

        <?php if ($viewingOwnProfile): ?>
            <a href="SignOut.php" class="logout-link">Log Out</a>
        <?php endif; ?>
    </div>
</main>

<div class="divider"></div>

<section class="artworks">
    <h2>Collection</h2>
    <p class="collection-desc">
        <?php echo $viewingOwnProfile ? 'Explore your curated selection of artworks.' : "Explore a curated selection of " . htmlspecialchars($fullName) . "’s finest works."; ?>
    </p>

    <?php if (isset($message)) : ?>
        <p style="color: green;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="Profile.php<?php echo isset($_GET['artistID']) ? '?artistID=' . urlencode($_GET['artistID']) : ''; ?>">
        <div class="art-container">
            <div class="art-grid">
                <?php if ($hasArtworks): ?>
                    <?php while ($art = mysqli_fetch_assoc($artworksResult)) : ?>
                        <div class="art-item">
                            <?php if ($viewingOwnProfile): ?>
                                <input type="checkbox" name="selected_artworks[]" value="<?php echo $art['ArtworkID']; ?>" class="delete-checkbox">
                            <?php endif; ?>

                            <img src="<?php echo htmlspecialchars($art['ArtPic']); ?>" alt="<?php echo htmlspecialchars($art['Title']); ?>">
                            <h3><?php echo htmlspecialchars($art['Title']); ?></h3>
                            <p><?php echo htmlspecialchars($art['Descreption']); ?></p>
                            <p>Category: <?php echo htmlspecialchars($art['Category']); ?></p>
                            <p><?php echo htmlspecialchars($art['Size']); ?></p>
                            <p>$<?php echo htmlspecialchars($art['Price']); ?></p>
                            <p>Available</p>

                            <?php if ($viewingOwnProfile): ?>
                                <p>
                                    <a href="../Edit/Edit.php?artworkID=<?php echo $art['ArtworkID']; ?>" class="edit-icon">✎</a>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No artworks available at the moment.</p>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($viewingOwnProfile): ?>
            <div class="artwork-buttons">
                <button type="button" id="add-artwork" onclick="window.location.href='../addartwork/addartwork.php';">Add New Artwork</button>
                <button type="submit" id='delete-artwork' name="delete_selected" class="delete-btn">Delete Selected Artworks</button>
            </div>
        <?php endif; ?>
    </form>
</section>

<footer>
    <p>&copy; IT320 2025 Reesha. All rights reserved.</p>
</footer>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector(".logo").addEventListener("click", function () {
            window.location.href = "../Home/index.php";
        });

        document.querySelector(".icons a[href='../Wishlist/Wishlist.php']").addEventListener("click", function (e) {
            e.preventDefault();
            window.location.href = "../Wishlist/Wishlist.php";
        });

        document.querySelector(".icons a[href='Profile.php']").addEventListener("click", function (e) {
            e.preventDefault();
            window.location.href = "Profile.php";
        });
    });
</script>

</body>
</html>
