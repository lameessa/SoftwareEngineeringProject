<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php
session_start();
$conn = mysqli_connect("localhost", "root", "root", "reesha");
$userID = $_SESSION['UserID'] ?? 'batool999';

$paymentComplete = false;

// إذا تم الإرسال
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    // هنا يتم حذف محتويات السلة (كأن الطلب تأكد)
    mysqli_query($conn, "DELETE FROM cart WHERE UserID='$userID'");
    $paymentComplete = true;
} else {
    // جلب عناصر السلة لعرضها قبل الدفع
    $query = "
        SELECT artwork.Title, artwork.Price
        FROM cart
        JOIN artwork ON cart.ArtworkID = artwork.ArtworkID
        WHERE cart.UserID = '$userID'
    ";
    $result = mysqli_query($conn, $query);
    $total = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        body {
            background-color: #1e1e1e;
            color: #f2e8d8;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        .checkout-container {
            max-width: 800px;
            margin: 60px auto;
            background-color: #1a1a1a;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }

        h2 {
            text-align: center;
            color: #f5e5ca;
        }

        .order-summary {
            margin-bottom: 30px;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 20px;
            background-color: #252525;
        }

        .order-summary h3 {
            margin-bottom: 15px;
            font-size: 20px;
            color: #e5d3b3;
        }

        .order-summary ul {
            list-style: none;
            padding-left: 0;
        }

        .order-summary li {
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        form input {
            padding: 12px;
            background-color: #2a2a2a;
            color: #f2e8d8;
            border: 1px solid #444;
            border-radius: 6px;
            font-size: 14px;
        }

        form input::placeholder {
            color: #aaa;
        }

        button[type="submit"] {
            margin-top: 10px;
            padding: 14px;
            background-color: #5c4024;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #513c2e;
        }

        .thank-you {
            text-align: center;
            margin-top: 40px;
            background-color: #252525;
            padding: 30px;
            border-radius: 10px;
            border: 1px solid #333;
        }

        .thank-you h2 {
            color: #f2e8d8;
        }

        .thank-you a {
            color: #f2e8d8;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="checkout-container">

        <?php if ($paymentComplete): ?>
            <div class="thank-you">
                <h2>Thank you!</h2>
                <p>Your payment has been received and your order is confirmed.</p>
                <a href="../Home/index.php">Back to Home</a>
            </div>
        <?php else: ?>
            <h2>Secure Checkout</h2>

            <div class="order-summary">
                <h3>Order Summary</h3>
                <ul>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <li><?= htmlspecialchars($row['Title']) ?> - $<?= $row['Price'] ?></li>
                        <?php $total += $row['Price']; ?>
                    <?php endwhile; ?>
                </ul>
                <p><strong>Total: $<?= $total ?></strong></p>
            </div>

            <form method="POST">
                <h3>Payment Information</h3>
                <input type="text" name="card_name" placeholder="Name on Card" required>
                <input type="text" name="card_number" placeholder="Card Number" required>
                <input type="text" name="expiry" placeholder="MM/YY" required>
                <input type="text" name="cvv" placeholder="CVV" required>
                <button type="submit" name="confirm_payment">Confirm Payment</button>
            </form>
        <?php endif; ?>

    </div>
</body>
</html>


