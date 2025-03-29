<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "reesha";

// Connect to database
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die(json_encode(['error' => 'Connection failed: ' . mysqli_connect_error()]));
}

// Get filter parameters from request
$query = isset($_GET['query']) ? mysqli_real_escape_string($conn, $_GET['query']) : '';
$maxPrice = isset($_GET['maxPrice']) ? (int)$_GET['maxPrice'] : 5000;
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : 'all';
$size = isset($_GET['size']) ? mysqli_real_escape_string($conn, $_GET['size']) : 'all';

// Build SQL query with filters
$sql = "SELECT artwork.ArtworkID, artwork.*, user.UserPic, user.UserID AS ArtistID
        FROM artwork 
        JOIN user ON artwork.UserName = user.UserName
        WHERE artwork.Price <= $maxPrice";

if (!empty($query)) {
    $sql .= " AND LOWER(artwork.UserName) LIKE LOWER('%$query%')";
}

if ($category !== 'all') {
    $sql .= " AND artwork.Category = '$category'";
}

if ($size !== 'all') {
    $sql .= " AND artwork.Size = '$size'";
}

$result = mysqli_query($conn, $sql);
$artworks = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $artworks[] = $row;
    }
}

mysqli_close($conn);
echo json_encode($artworks);
?>
