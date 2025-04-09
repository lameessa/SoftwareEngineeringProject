<?php
// Database connection
$host = "localhost";
$dbUser = "root";
$dbPass = "root";
$dbName = "reesha";

$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_GET['artworkID'])) {
    die("No artwork ID specified.");
}
$artworkID = $_GET['artworkID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = $_POST['price'];
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $availability = mysqli_real_escape_string($conn, $_POST['availability'] ?? 'available');

    $sql = "UPDATE artwork SET 
                Title = '$title',
                Descreption = '$description',
                Category = '$category',
                Price = '$price',
                Size = '$size',
                Availability = '$availability' 
            WHERE ArtworkID = '$artworkID'";


if (mysqli_query($conn, $sql)) {
    echo "<script>
        alert('Changes Saved Successfully!');
        window.location.href = '../ArtworkDetails/ArtworkDetails.php?id=$artworkID';
    </script>";
    exit;
} else {
        echo "Error updating artwork: " . mysqli_error($conn);
    }
}

$sql = "SELECT * FROM artwork WHERE ArtworkID = '$artworkID'";
$result = mysqli_query($conn, $sql);
$artwork = mysqli_fetch_assoc($result);

if (!$artwork) {
    die("Artwork not found.");
}

$imagePath = (!empty($artwork['ArtPic']) && file_exists($artwork['ArtPic'])) 
    ? $artwork['ArtPic'] 
    : '../images/default.png';
$availability = $artwork['Availability'] ?? 'available';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit - Reesha</title>
    <link rel="stylesheet" href="EditStyle.css">
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
            <img src="../images/profile.png" alt="Profile" id="profile-header">
        </div>
    </nav>
</header>

<main>
    <div class="container">
        <div class="left">
            <div class="content">
                <form id="editArtworkForm" method="POST">
                    <div class="form-group">
                        <label id="label-title" for="title">Title:</label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($artwork['Title']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description"><?= htmlspecialchars($artwork['Description']) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="category">Category:</label>
                        <select id="category" name="category">
                            <option value="abstract" <?= $artwork['Category'] == 'abstract' ? 'selected' : '' ?>>Abstract</option>
                            <option value="realism" <?= $artwork['Category'] == 'realism' ? 'selected' : '' ?>>Realism</option>
                            <option value="expressionism" <?= $artwork['Category'] == 'expressionism' ? 'selected' : '' ?>>Expressionism</option>
                            <option value="cubism" <?= $artwork['Category'] == 'cubism' ? 'selected' : '' ?>>Cubism</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="number" id="price" name="price" value="<?= htmlspecialchars($artwork['Price']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="size">Size:</label>
                        <input type="text" id="size" name="size" value="<?= htmlspecialchars($artwork['Size']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="availability">Availability:</label>
                        <select id="availability" name="availability">
                            <option value="available" <?= $availability == 'available' ? 'selected' : '' ?>>Available</option>
                            <option value="sold" <?= $availability == 'sold' ? 'selected' : '' ?>>Sold</option>
                        </select>
                    </div>

                    <div id="buttons">
                        <button type="submit" class="save-changes">Save Changes</button>
                        <button type="button" class="cancel-edit" id="cancelButton">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="right">
            <div class="image-container">
                <img src="<?= htmlspecialchars($imagePath) ?>" alt="Artwork Image">
            </div>
        </div>
    </div>
</main>

<footer>
    <p>&copy; IT320 2025 Reesha. All rights reserved.</p>
</footer>

<script>
document.addEventListener("DOMContentLoaded", function () {
    function addClickListener(selector, callback) {
        const elements = document.querySelectorAll(selector);
        elements.forEach(element => {
            if (element) {
                element.addEventListener("click", callback);
            }
        });
    }

    addClickListener(".logo", function () {
        window.location.href = "../Home/index.php";
    });

    addClickListener("#wishlist-header", function () {
        window.location.href = "../Wishlist/Wishlist.php";
    });

    addClickListener("#profile-header", function () {
        window.location.href = "../Profile/Profile.php";
    });
    
    document.getElementById("editArtworkForm").addEventListener("submit", function (event) {
        const confirmSave = confirm("Are you sure you want to save the changes?");
        if (!confirmSave) {
            event.preventDefault(); // cancel submission
        }
    });
    
    document.getElementById("cancelButton").addEventListener("click", function () {
        const confirmCancel = confirm("Are you sure you want to cancel? Unsaved changes will be lost.");
        if (confirmCancel) {
            window.location.href = "../ArtworkDetails/ArtworkDetails.php?id=<?= $artworkID ?>";
        }
    });

});
</script>
</body>
</html>
