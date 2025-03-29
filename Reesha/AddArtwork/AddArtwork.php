<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Ensure the user is logged in
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


$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Sanitize and fetch form data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $listingType = $_POST['listing-type'];

    // Get the user full name from the User table
    $userQuery = "SELECT UserName FROM User WHERE UserID = '$userID'";
    $userResult = mysqli_query($conn, $userQuery);

    if (!$userResult || mysqli_num_rows($userResult) == 0) {
        $message = "User not found.";
    } else {
        $userRow = mysqli_fetch_assoc($userResult);
        $userName = $userRow['UserName'];

        // Handle image upload
        $image = $_FILES['image'];
        $targetDir = "../images/";

        // Validate uploaded file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($image['type'], $allowedTypes)) {
            $message = "Only JPG, JPEG, and PNG files are allowed.";
        } else {
            // Create a unique file name
            $fileExtension = pathinfo($image["name"], PATHINFO_EXTENSION);
            $fileName = "Artwork_" . uniqid() . "." . $fileExtension;
            $targetFile = $targetDir . $fileName;

            if (!move_uploaded_file($image["tmp_name"], $targetFile)) {
                $message = "Failed to upload artwork image.";
            } else {
                $imagePath = $targetFile;

                if ($listingType === "marketplace") {
                    $priceRaw = $_POST['price'];
                    $price = str_replace(['$', ' '], '', $priceRaw);

                    if (!is_numeric($price)) {
                        $message = "Invalid price format.";
                    } else {
                        $insertArtworkQuery = "INSERT INTO Artwork (UserID, UserName, Title, Description, Size, Price, Category, ArtPic)
                                               VALUES ('$userID', '$userName', '$title', '$description', '$size', '$price', '$category', '$imagePath')";

                        if (mysqli_query($conn, $insertArtworkQuery)) {
                            header("Location: ../Profile/Profile.php");
                            exit();
                        } else {
                            $message = "Error inserting artwork: " . mysqli_error($conn);
                        }
                    }

                } elseif ($listingType === "auction") {

                    $startingPriceRaw = $_POST['starting_price'];
                    $startingPrice = str_replace(['$', ' '], '', $startingPriceRaw);
                    $auctionEnd = $_POST['auction_end'];

                    if (!is_numeric($startingPrice)) {
                        $message = "Invalid starting price format.";
                    } elseif (empty($auctionEnd)) {
                        $message = "Auction end time is required.";
                    } else {
                        $currentDateTime = date("Y-m-d H:i:s");

                        $insertArtworkQuery = "INSERT INTO Artwork (UserID, UserName, Title, Description, Size, Price, Category, ArtPic)
                                               VALUES ('$userID', '$userName', '$title', '$description', '$size', '$startingPrice', '$category', '$imagePath')";

                        if (mysqli_query($conn, $insertArtworkQuery)) {
                            $artworkID = mysqli_insert_id($conn);

                            $insertAuctionQuery = "INSERT INTO Auction (ArtworkID, StartTime, EndTime, StartPrice, HighestBid, CurrentBid)
                                                   VALUES ('$artworkID', '$currentDateTime', '$auctionEnd', '$startingPrice', '$startingPrice', '$startingPrice')";

                            if (mysqli_query($conn, $insertAuctionQuery)) {
                                header("Location: ../Auction/Auction.php");
                                exit();
                            } else {
                                $message = "Error inserting auction: " . mysqli_error($conn);
                            }

                        } else {
                            $message = "Error inserting artwork for auction: " . mysqli_error($conn);
                        }
                    }

                } else {
                    $message = "Invalid listing type selected.";
                }
            }
        }
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REESHA - Add Artwork</title>
    <link rel="stylesheet" href="AddArtwork.css">
    <script defer src="AddArtwork.js"></script>
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

<section class="add-artwork-section">
    <div class="left-column">
        <div class="text-layer">
            <h2 class="text-light">SHARE YOUR ART</h2>
            <h2 class="text-medium">SHARE YOUR ART</h2>
            <h2 class="text-dark">SHARE YOUR ART</h2>
        </div>
    </div>

    <div class="form-container">
        <h1>Add Your Artwork</h1>

        <?php if (!empty($message)): ?>
            <p style="color:red; text-align:center;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form id="artwork-form" action="AddArtwork.php" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label for="artwork-image">Artwork's Picture</label>
                <input type="file" id="artwork-image" name="image" accept="image/*" onchange="handleImageUpload()" required>
            </div>

            <div id="image-preview-container">
                <div id="image-preview"></div>
            </div>

            <div class="form-group">
                <label for="artwork-title">Title</label>
                <input type="text" id="artwork-title" name="title" required>
            </div>

            <div class="form-group">
                <label for="artwork-category">Category</label>
                <select id="artwork-category" name="category" required>
                    <option value="" disabled selected>Choose a category</option>
                    <option value="abstract">Abstract</option>
                    <option value="Expressionism">Expressionism</option>
                    <option value="Cubism">Cubism</option>
                    <option value="Realism">Realism</option>
                </select>
            </div>

            <div class="form-group">
                <label for="artwork-size">Size</label>
                <select id="artwork-size" name="size" required>
                    <option value="" disabled selected>Choose a size</option>
                    <option value="10x15 cm">10x15 cm</option>
                    <option value="13x18 cm">13x18 cm</option>
                    <option value="20x25 cm">20x25 cm</option>
                    <option value="28x36 cm">28x36 cm</option>
                    <option value="40x50 cm">40x50 cm</option>
                </select>
            </div>

            <div class="form-group">
                <label for="artwork-description">Description</label>
                <textarea id="artwork-description" name="description" rows="4" required></textarea>
            </div>

            <div id="listing-type-section">
                <h3>Choose Listing Type</h3>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="listing-type" value="marketplace" onchange="toggleListingType()" required>
                        Sell in Marketplace
                    </label>
                    <label>
                        <input type="radio" name="listing-type" value="auction" onchange="toggleListingType()" required>
                        Start an Auction
                    </label>
                </div>
            </div>

            <div id="marketplace-fields" class="hidden">
                <div class="form-group">
                    <label for="artwork-price">Price</label>
                    <input type="text" id="artwork-price" name="price" placeholder="$0.00">
                </div>
            </div>

            <div id="auction-fields" class="hidden">
                <div class="form-group">
                    <label for="starting-price">Starting Price</label>
                    <input type="text" id="starting-price" name="starting_price" placeholder="$0.00">
                </div>
                <div class="form-group">
                    <label for="auction-end">Auction End Time</label>
                    <input type="datetime-local" id="auction-end" name="auction_end">
                </div>
            </div>

            <button type="submit" class="submit-btn">Add Artwork</button>
        </form>
    </div>
</section>

<footer>
    <p>&copy; IT320 2025 Reesha. All rights reserved.</p>
</footer>

</body>
</html>
