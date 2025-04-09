<?php
session_start();
include_once("../utils/notification_popup.php");
include_once("../utils/auto_cart_check.php");

// DB connection
$conn = mysqli_connect("localhost", "root", "root", "reesha");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$userID = $_SESSION['user_id'] ?? null;

// Add to Cart
if (isset($_GET['id']) && $_GET['action'] === 'add_to_cart') {
    if (!$userID) {
        header("Location: ../Login/Login.php");
        exit();
    }

    $artID = intval($_GET['id']);
    $check = mysqli_query($conn, "SELECT * FROM cart WHERE UserID = '$userID' AND ArtworkID = $artID");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['flash'] = "Artwork already exists in your cart.";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO cart (UserID, ArtworkID) VALUES ('$userID', $artID)");
        $_SESSION['flash'] = $insert ? "Artwork added to cart." : "Failed to add artwork to cart.";
    }
    header("Location: Search.php");
    exit;
}

// Add to Wishlist
if (isset($_GET['id']) && $_GET['action'] === 'add_to_wishlist') {
    if (!$userID) {
        header("Location: ../Login/Login.php");
        exit();
    }

    $artID = intval($_GET['id']);
    $check = mysqli_query($conn, "SELECT * FROM wishlist WHERE UserID = '$userID' AND ArtworkID = $artID");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['flash'] = "Artwork already exists in your wishlist.";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO wishlist (UserID, ArtworkID) VALUES ('$userID', $artID)");
        $_SESSION['flash'] = $insert ? "Artwork added to wishlist." : "Failed to add artwork to wishlist.";
    }
    header("Location: Search.php");
    exit;
}

// Flash message
$flashMessage = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search - Reesha</title>
    <link rel="stylesheet" href="SearchStyle.css">
    <style>
        .hidden { display: none; }
        .loading {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #666;
        }
        .error {
            color: red;
            text-align: center;
            padding: 20px;
        }
        #notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #333;
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            z-index: 1000;
        }
    </style>
</head>
<body>
<header>
    <div class="logo" onclick="window.location.href='../Home/index.php'">
        <img src="../images/logo.png" alt="Reesha">
        <span>Reesha ريشة</span>
    </div>
    <nav>
        <ul class="nav-links">
            <li><a href="../Home/index.php">Home</a></li>
            <li><a href="Search.php">Search</a></li>
            <li><a href="../Auction/Auction.php">Auctions</a></li>
            <li><a href="../Cart/Cart.php">Cart</a></li>
        </ul>
        <div class="icons">
            <img src="../images/heart.png" alt="Wishlist" onclick="window.location.href='../Wishlist/Wishlist.php'">
            <img src="../images/profile.png" alt="Profile" onclick="window.location.href='../Profile/Profile.php'">
        </div>
    </nav>
</header>

<div class="search-container">
    <input type="text" id="searchBar" placeholder="Search for artist...">
</div>

<div id="artist-profile" class="hidden">
    <div class="profile-container">
        <img id="artist-photo" src="" alt="Artist Photo">
        <h2 id="artist-name"></h2>
    </div>
</div>

<div class="content">
    <aside class="filters">
        <h3>Filters</h3>
        <label for="priceRange">Price Range:</label>
         <div class="price-range-container">
        <input type="range" id="priceRange" min="0" max="5000" step="50" value="5000">
        <span id="priceValue">$5000</span>
         </div>
        <label for="category">Category:</label>
        <select id="category">
            <option value="all">All</option>
            <option value="abstract">Abstract</option>
            <option value="Expressionism">Expressionism</option>
            <option value="Cubism">Cubism</option>
            <option value="Realism">Realism</option>
        </select>

        <label for="size">Size:</label>
        <select id="size">
            <option value="all">All</option>
            <option value="10x15">10x15 cm</option>
            <option value="13x18">13x18 cm</option>
            <option value="20x25">20x25 cm</option>
            <option value="28x36">28x36 cm</option>
            <option value="40x50">40x50 cm</option>
        </select>
    </aside>

    <section id="artworks-container" class="artworks"></section>
</div>

<div id="notification" class="hidden"></div>

<footer>
    <p>&copy; IT320 2025 Reesha. All rights reserved.</p>
</footer>

<script>
    const searchBar = document.getElementById("searchBar");
    const priceRange = document.getElementById("priceRange");
    const categoryFilter = document.getElementById("category");
    const sizeFilter = document.getElementById("size");
    const priceValue = document.getElementById("priceValue");
    const profileSection = document.getElementById("artist-profile");
    const profileName = document.getElementById("artist-name");
    const profilePhoto = document.getElementById("artist-photo");
    const artworksContainer = document.getElementById("artworks-container");
    const notification = document.getElementById("notification");

    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    function showNotification(message) {
        notification.textContent = message;
        notification.classList.remove('hidden');
        setTimeout(() => {
            notification.classList.add('hidden');
        }, 3000);
    }

    function fetchFilteredResults() {
        const params = new URLSearchParams({
            query: searchBar.value.trim(),
            maxPrice: priceRange.value,
            category: categoryFilter.value,
            size: sizeFilter.value
        });

        artworksContainer.innerHTML = '<div class="loading">Loading artworks...</div>';

        fetch(`search_api.php?${params}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) throw new Error(data.error);
                renderArtworks(data);
                updateArtistProfile(data);
            })
            .catch(error => {
                artworksContainer.innerHTML = `<div class="error">Error loading artworks: ${error.message}</div>`;
            });
    }

    function renderArtworks(artworks) {
        artworksContainer.innerHTML = '';
        artworks.forEach(art => {
            const item = document.createElement('div');
            item.className = 'art-item';

            const imagePath = decodeURIComponent(art.ArtPic).replace(/\\/g, '/');

            item.innerHTML = `
                <img src="${imagePath}" alt="${art.Title}" class="art-clickable" data-id="${art.ArtworkID}">
                <h4>${art.Title}</h4>
                <div class="details-container">
                    <span class="artist-name">${art.UserName}</span>
                    <span>$${art.Price}</span>
                </div>
                <div class="buttons-container">
                    <button class="add-to-cart" data-id="${art.ArtworkID}">Add to Cart</button>
                    <img src="../images/heart.png" class="wishlist-btn" alt="Wishlist" data-id="${art.ArtworkID}">
                </div>
            `;
            artworksContainer.appendChild(item);
        });
        setupEventListeners();
    }

    function setupEventListeners() {
        document.querySelectorAll('.art-clickable').forEach(img => {
            img.addEventListener('click', function () {
                const artId = this.getAttribute('data-id');
                window.location.href = `../ArtworkDetails/ArtworkDetails.php?id=${artId}`;
            });
        });

        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                const artId = this.getAttribute('data-id');
                window.location.href = `Search.php?action=add_to_cart&id=${artId}`;
            });
        });

        document.querySelectorAll('.wishlist-btn').forEach(heart => {
            heart.addEventListener('click', function (e) {
                e.stopPropagation();
                const artId = this.getAttribute('data-id');
                window.location.href = `Search.php?action=add_to_wishlist&id=${artId}`;
            });
        });
    }

    function updateArtistProfile(artworks) {
    const query = searchBar.value.trim().toLowerCase();
    const match = artworks.find(art => art.UserName.toLowerCase() === query);

    if (match) {
        profileName.textContent = match.UserName;
        profilePhoto.src = match.UserPic;
        profileSection.classList.remove('hidden');

        // Redirect using artist_id
        profileSection.onclick = () => {
            window.location.href = `../Profile/Profile.php?artistID=${match.ArtistID}`;
        };
    } else {
        profileSection.classList.add('hidden');
    }
}


  document.addEventListener("DOMContentLoaded", () => {
    <?php if ($flashMessage): ?>
        showNotification("<?= addslashes($flashMessage) ?>");
    <?php endif; ?>
});

    searchBar.addEventListener('input', debounce(fetchFilteredResults, 300));
    priceRange.addEventListener('input', () => {
        priceValue.textContent = `$${priceRange.value}`;
        fetchFilteredResults();
    });
    categoryFilter.addEventListener('change', fetchFilteredResults);
    sizeFilter.addEventListener('change', fetchFilteredResults);

    fetchFilteredResults();
</script>
</body>
</html>


