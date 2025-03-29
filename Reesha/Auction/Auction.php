<?php
session_start();

$host = "localhost";
$dbUser = "root";
$dbPass = "root";
$dbName = "reesha";

$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$userID = $_SESSION['user_id'];

$trendingQuery = "
    SELECT a.ArtworkID, a.Title, a.ArtPic, a.Price, au.EndTime
    FROM Artwork a
    JOIN Auction au ON a.ArtworkID = au.ArtworkID
";
$trendingResult = mysqli_query($conn, $trendingQuery);

$userQuery = "
    SELECT a.ArtworkID, a.Title, a.ArtPic, a.Price, au.EndTime
    FROM Artwork a
    JOIN Auction au ON a.ArtworkID = au.ArtworkID
    WHERE a.UserID = '$userID'
";
$userResult = mysqli_query($conn, $userQuery);
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
            background: url('../images/auctionbackground.png') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 300px;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.8), transparent);
            z-index: 0;
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
        }

        .auction-card {
            background-color: #1e1e1e;
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
            color: #fff;
            margin: 0 auto;
            text-align: center;
            text-shadow: 0px 0px 10px rgba(0, 0, 0, 0.6);
            border-bottom: 1px solid #fff;
            display: inline-block;
            padding-bottom: 10px;
        }

        .auction-card .header p {
            color: #ded0c8;
            font-size: 1em;
            line-height: 1.6;
            margin-top: 20px;
            text-align: left;
        }

        .auction-card .tabs {
            padding: 0 30px 30px 30px;
        }
    </style>
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
            <div class="active-line"></div>
        </div>
        <hr>
        <div class="content">
            <div class="tab-content tab-content-active" data-tab="1">
                <div class="auction-row">
                    <?php while ($row = mysqli_fetch_assoc($trendingResult)) : ?>
                        <div class="auction-item">
                            <img src="<?php echo $row['ArtPic']; ?>" alt="<?php echo $row['Title']; ?>">
                            <p class="art-name"><?php echo $row['Title']; ?></p>
                            <p class="art-price">$<?php echo $row['Price']; ?></p>
                            <p class="time-left" data-endtime="<?php echo $row['EndTime']; ?>">Loading...</p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="tab-content" data-tab="2">
                <div class="auction-row">
                    <?php while ($row = mysqli_fetch_assoc($userResult)) : ?>
                        <div class="auction-item">
                            <img src="<?php echo $row['ArtPic']; ?>" alt="<?php echo $row['Title']; ?>">
                            <p class="art-name"><?php echo $row['Title']; ?></p>
                            <p class="art-price">$<?php echo $row['Price']; ?></p>
                            <p class="time-left" data-endtime="<?php echo $row['EndTime']; ?>">Loading...</p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<footer>
    <p>&copy; IT320 2025 Reesha. All rights reserved.</p>
</footer>

<script>
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
    });

    function setupTabs() {
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                const sidebar = button.parentElement;
                const tabs = sidebar.parentElement;
                const tabNumber = button.dataset.forTab;
                const tabActivate = tabs.querySelector(`.tab-content[data-tab="${tabNumber}"]`);

                sidebar.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('tab-btn-active'));
                tabs.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('tab-content-active'));

                button.classList.add('tab-btn-active');
                tabActivate.classList.add('tab-content-active');
            });
        });
    }

    function setupTimers() {
        document.querySelectorAll('.time-left').forEach(el => {
            const endTime = el.dataset.endtime;
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
</script>
</body>
</html>
