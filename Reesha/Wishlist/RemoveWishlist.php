<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit();
}

// DB connection
$host = "localhost";
$dbUser = "root";
$dbPass = "root";
$dbName = "reesha";

$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);
if (!$conn) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle removal
if (isset($_POST['wishlist_id'])) {
    $wishlist_id = intval($_POST['wishlist_id']);

    $sql = "DELETE FROM Wishlist WHERE WishlistID = $wishlist_id AND UserID = '$user_id'";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to remove item"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
