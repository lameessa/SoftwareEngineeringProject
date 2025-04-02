<?php
if (!isset($conn)) {
    $conn = mysqli_connect("localhost", "root", "root", "reesha");
    if (!$conn) die("DB connection failed in notification_popup.php");
}

$notification = null;
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $notifQuery = "SELECT * FROM Notifications WHERE UserID = '$uid' AND IsRead = 0 ORDER BY CreatedAt DESC LIMIT 1";
    $notifResult = mysqli_query($conn, $notifQuery);

    if ($notifResult && mysqli_num_rows($notifResult) > 0) {
        $notification = mysqli_fetch_assoc($notifResult);
        $notifID = $notification['NotificationID'];
        mysqli_query($conn, "UPDATE Notifications SET IsRead = 1 WHERE NotificationID = '$notifID'");
    }
}
?>

<?php if ($notification): ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap');

        #notification-popup {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #222;
            color: white;
            padding: 1em 1.5em;
            border-radius: 15px;
            font-family: 'Playfair Display', serif;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
            z-index: 9999;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.6s forwards, fadeOut 0.6s 6s forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateY(30px);
            }
        }
    </style>

    <div id="notification-popup">
        <?= $notification['Message'] ?>
    </div>
<?php endif; ?>
