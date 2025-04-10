<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

include_once("../utils/notification_popup.php");
include_once("../utils/auto_cart_check.php");

$host = "localhost";
$dbUser = "root";
$dbPass = "root";
$dbName = "reesha";

$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$trendingQuery = "
    SELECT a.ArtworkID, a.Title, a.ArtPic, au.CurrentBid, au.EndTime
    FROM Artwork a
    JOIN Auction au ON a.ArtworkID = au.ArtworkID
    WHERE au.EndTime > NOW()
";
$trendingResult = mysqli_query($conn, $trendingQuery);

// Only load user's auctions if logged in
$userID = $_SESSION['user_id'] ?? null;
$userResult = false;

if ($userID) {
    $userQuery = "
        SELECT a.ArtworkID, a.Title, a.ArtPic, au.CurrentBid, au.EndTime, au.HighestBidderID
        FROM Artwork a
        JOIN Auction au ON a.ArtworkID = au.ArtworkID
        WHERE a.UserID = '$userID'
    ";
    $userResult = mysqli_query($conn, $userQuery);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REESHA - Auctions</title>
    <link rel="stylesheet" href="Auction.css">
    <style>
body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    position: relative;
    font-family: 'Playfair Display', serif;
    color: #ded0c8;
}


        .dynamic-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            filter: blur(15px) brightness(0.3);
            z-index: -1;
            transition: background-image 1s ease-in-out;
            background-image: url('../images/auctionbackground.png');
        }

        header {
            position: relative;
            width: 100%;
            z-index: 1;
            background: transparent;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1em 2em;
            color: white;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            padding: 0;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-size: 16px;
        }

        .logo span {
            color: white;
        }

        .icons img {
            width: 2em;
            height: 2em;
            border-radius: 10px;
            padding: 0 0.5em;
            cursor: pointer;
        }

        .auction-card {
            background-color: rgba(30, 30, 30, 0.85);
            margin: 100px auto 30px auto;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 1000px;
            position: relative;
            z-index: 2;
        }

        .auction-card .header {
            
            padding: 50px 50px 20px;
            text-align: center;
        }

        .auction-card .header h1 {
            font-size: 4em;
            color: #ded0c8;
            margin: 0 auto;
            text-shadow: 0px 0px 10px rgba(0, 0, 0, 0.6);
            border-bottom: 1px solid #fff;
            display: inline-block;
            padding-bottom: 10px;
        }

        .auction-card .header p {
            color: #ded0c8;
            font-size: 1em;
            margin-top: 20px;
            text-align: left;
        }


        /* Tab buttons */
        .tab-btn {
            background-color: transparent;
            border: none;
            padding: 10px 20px;
            font-size: 1.2em;
            color: #333;
            cursor: pointer;
            font-weight: normal;
            border-bottom: 2px solid #333;
            transition: all 0.3s ease;
            margin-right: 20px;
            font-family: serif;
        }

        /* Active tab button */
        .tab-btn-active {
            border-bottom: 2px solid #ded0c8;
            color: #ded0c8;
            z-index: 10;
        }

        /* Active line under the tabs */
        .active-line {
            height: 2px;
            background-color: transparent;
            transition: 0.3s ease;
        }

        .tab-content {
            display: none;
        }

        .tab-content-active {
            display: block;
        }

        .carousel-wrapper {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 2em 0;
        }

        .carousel-track {
            display: flex;
            transition: transform 0.6s ease-in-out;
            gap: 2em;
            will-change: transform;
        }

        .carousel-slide {
            flex: 0 0 80%;
            transform: scale(0.8);
            opacity: 0.5;
            transition: all 0.5s ease;
            pointer-events: none;
        }

        .carousel-slide.active {
            transform: scale(1);
            opacity: 1;
            pointer-events: auto;
        }

        .slide-content {
            display: block;
            text-align: center;
            color: white;
            background-color: rgba(30, 30, 30, 0.85);
            padding: 1em;
            border-radius: 20px;
            text-decoration: none;
        }

        .slide-content img {
            width: 100%;
            border-radius: 15px;
            max-height: 400px;
            object-fit: cover;
        }

        .carousel-arrow {
            background: rgba(255, 255, 255, 0.08);
            border: none;
            font-size: 2em;
            color: white;
            padding: 0.5em 0.8em;
            border-radius: 50%;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            z-index: 5;
        }

        .carousel-arrow:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: scale(1.15);
        }

        .no-trending-msg {
            color: #fff;
            font-size: 1.2em;
            text-align: center;
            margin: 2em 0;
            background-color: rgba(0,0,0,0.5);
            padding: 1em 2em;
            border-radius: 15px;
        }

        footer {
            color: white;
            text-align: center;
            margin-top: 3em;
        }
        
        .auction-card .tabs {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0 30px 30px 30px;
}

.sidebar {
    display: flex;
    justify-content: center;
    width: 100%;
}

.content {
    max-width: 900px;
    width: 100%;
}
.my-auctions {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 2em;
    margin-top: 2em;
}

.my-auctions .slide-content {
    width: 250px;
    background-color: rgba(30, 30, 30, 0.85);
    border-radius: 20px;
    padding: 1em;
    text-align: center;
    text-decoration: none;
}

.my-auctions .slide-content img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 15px;
}

.art-price {
    color: #ffcc00; /* elegant golden yellow */
    font-weight: bold;
    font-size: 1.1em;
    margin: 0.5em 0;
}

.time-left {
    font-size: 1em; /* just slightly bigger */
    color: #ded0c8;  /* softer and brighter than pure white */
    font-weight: 500;
    letter-spacing: 0.5px;
    margin-top: 0.3em;
    text-shadow: 0 0 5px rgba(255, 255, 255, 0.3);
}

.no-trending-msg a {
    color: #ffcc00; /* Elegant golden yellow */
    font-weight: bold;
    text-decoration: none; /* Remove underline */
    border-bottom: 2px solid #ffcc00; /* Add a bottom border */
    transition: color 0.3s ease, border-color 0.3s ease;
}

.no-trending-msg a:hover {
    color: #f4e02f; /* Slightly lighter yellow */
    border-color: #f4e02f; /* Change the bottom border on hover */
    text-decoration: underline; /* Add underline on hover */
}


    </style>
</head>
<body>
<div class="dynamic-bg"></div>

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

<div class="auction-card">
    <div class="header">
        <h1>Auction</h1>
        <p>
            Auctions are an exciting way to buy and sell art, where buyers place bids on artwork, and the highest bidder
            wins the item. At Reesha, we bring art lovers and collectors together to engage in competitive bidding,
            making the art buying experience more dynamic and thrilling.
        </p>
    </div>
    <section class="tabs">
        <div class="sidebar">
            <button class="tab-btn tab-btn-active" data-for-tab="1">Trending Artwork</button>
            <button class="tab-btn" data-for-tab="2">My Auctions</button>
        </div>
        <hr>
        <div class="content">
            <div class="tab-content tab-content-active" data-tab="1">
                <div class="auction-row">
                    <?php if (mysqli_num_rows($trendingResult) === 0): ?>
                        <p class="no-trending-msg">No trending auctions at the moment. Check back soon!</p>
                    <?php else: ?>
                        <div class="carousel-wrapper">
                            <button class="carousel-arrow left">&#10094;</button>
                            <div class="carousel-track">
                                <?php while ($row = mysqli_fetch_assoc($trendingResult)) : ?>
                                    <div class="carousel-slide">
                                        <a href="../AuctionDetails/AuctionDetails.php?id=<?php echo $row['ArtworkID']; ?>" class="slide-content">
                                            <img src="<?php echo $row['ArtPic']; ?>" alt="<?php echo $row['Title']; ?>">
                                            <p class="art-name"><?php echo $row['Title']; ?></p>
                                            <p class="art-price">$<?php echo number_format($row['CurrentBid'], 2); ?></p>
                                            <p class="time-left" data-endtime="<?php echo $row['EndTime']; ?>">Loading...</p>
                                        </a>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                            <button class="carousel-arrow right">&#10095;</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

<div class="tab-content" data-tab="2">
    <div class="auction-row my-auctions">
        <?php if (!$userID): ?>
            <p class="no-trending-msg">Please <a href="../Login/Login.php">log in</a> to view your auctions.</p>
        <?php elseif ($userResult && mysqli_num_rows($userResult) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($userResult)) : ?>
                <a href="../AuctionDetails/AuctionDetails.php?id=<?php echo $row['ArtworkID']; ?>" class="slide-content">
                    <img src="<?php echo $row['ArtPic']; ?>" alt="<?php echo $row['Title']; ?>">
                    <p class="art-name"><?php echo $row['Title']; ?></p>
                    <p class="art-price">$<?php echo number_format($row['CurrentBid'], 2); ?></p>
                    <p class="time-left" data-endtime="<?php echo $row['EndTime']; ?>">Loading...</p>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-trending-msg">You haven't listed any auctions yet.</p>
        <?php endif; ?>
    </div>
</div>


<footer>
    <p>&copy; IT320 2025 Reesha. All rights reserved.</p>
</footer>

<script>
    let isTrendingTab = true;

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector(".logo").addEventListener("click", function () {
            window.location.href = "../Home/index.php";
        });
        document.querySelector("#wishlist-header").addEventListener("click", function () {
            window.location.href = "../Wishlist/Wishlist.php";
        });
        document.querySelector("#profile-header").addEventListener("click", function () {
            window.location.href = "../Profile/Profile.php";
        });

        setupTabs();
        setupTimers();
        setupInfiniteCarousel();
    });

function setupTabs() {
    const bg = document.querySelector('.dynamic-bg');

    document.querySelectorAll('.tab-btn').forEach(button => {
        button.addEventListener('click', () => {
            const sidebar = button.parentElement;
            const tabs = sidebar.parentElement;
            const tabNumber = button.dataset.forTab;
            const tabActivate = tabs.querySelector(`.tab-content[data-tab="${tabNumber}"]`);

            // Switch active tab
            sidebar.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('tab-btn-active'));
            tabs.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('tab-content-active'));

            button.classList.add('tab-btn-active');
            tabActivate.classList.add('tab-content-active');

            // ✨ Background logic
            if (tabNumber === "1") {
    isTrendingTab = true;

    const activeSlide = document.querySelector('.carousel-slide.active img') ||
                        document.querySelector('.carousel-slide img');
    if (activeSlide) {
        bg.style.backgroundImage = `url('${activeSlide.src}')`;
    }
} else {
    isTrendingTab = false;
    bg.style.backgroundImage = `url('../images/auctionbackground.png')`;
}

        });
    });
}


    function setupTimers() {
        document.querySelectorAll('.time-left').forEach(el => {
            const endTime = el.dataset.endtime;
            if (!endTime) return;
            const interval = setInterval(() => {
                const now = new Date();
                const endDate = new Date(endTime);
                const timeDiff = endDate - now;
                if (timeDiff <= 0) {
                    el.textContent = "Auction has ended";
                    clearInterval(interval);
                    return;
                }
                const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);
                el.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            }, 1000);
        });
    }

    function setupInfiniteCarousel() {
        const track = document.querySelector('.carousel-track');
        if (!track || !document.querySelector('.carousel-slide')) return;

        let slides = Array.from(document.querySelectorAll('.carousel-slide'));
        const prevBtn = document.querySelector('.carousel-arrow.left');
        const nextBtn = document.querySelector('.carousel-arrow.right');

        const firstClone = slides[0].cloneNode(true);
        const lastClone = slides[slides.length - 1].cloneNode(true);
        firstClone.setAttribute('id', 'first-clone');
        lastClone.setAttribute('id', 'last-clone');
        track.appendChild(firstClone);
        track.insertBefore(lastClone, slides[0]);

        slides = Array.from(document.querySelectorAll('.carousel-slide'));
        let currentIndex = 1;
        const slideWidth = slides[0].offsetWidth + 32;

        function updateCarousel(animate = true) {
            slides.forEach(slide => slide.classList.remove('active'));
            slides[currentIndex].classList.add('active');
            track.style.transition = animate ? 'transform 0.6s ease-in-out' : 'none';
            const offset = (slideWidth * currentIndex) - ((track.offsetWidth - slideWidth) / 2);
            track.style.transform = `translateX(-${offset}px)`;

            const activeSlide = slides[currentIndex].querySelector('img');
if (isTrendingTab && activeSlide) {
    const bg = document.querySelector('.dynamic-bg');
    bg.style.backgroundImage = `url('${activeSlide.src}')`;
}

        }

        function moveToNext() {
            if (currentIndex >= slides.length - 1) return;
            currentIndex++;
            updateCarousel();
        }

        function moveToPrev() {
            if (currentIndex <= 0) return;
            currentIndex--;
            updateCarousel();
        }

        nextBtn.addEventListener('click', moveToNext);
        prevBtn.addEventListener('click', moveToPrev);

        track.addEventListener('transitionend', () => {
            if (slides[currentIndex].id === 'first-clone') {
                currentIndex = 1;
                updateCarousel(false);
            }
            if (slides[currentIndex].id === 'last-clone') {
                currentIndex = slides.length - 2;
                updateCarousel(false);
            }
        });

        setInterval(() => {
            moveToNext();
        }, 5000);

        updateCarousel(false);
    }
    

</script>
</body>
</html>
