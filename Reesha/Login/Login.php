<?php
session_start();

$host = "localhost";
$db = "reesha";
$user = "root";
$pass = "root";

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM user WHERE Email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['Password'])) {
            $_SESSION['email'] = $row['Email'];
            $_SESSION['username'] = $row['Username'];
            $_SESSION['user_id'] = $row['UserID']; // Optional but useful
            header("Location: ../Home/index.php");
            exit();
        } else {
            $error = "Incorrect username or password.";
        }
    } else {
        $error = "Incorrect username or password.";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

?>

<!-- HTML Login Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log in - Reesha</title>
    <link rel="stylesheet" href="LoginStyle.css">
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
            </ul>
        </nav>
    </header>

    <div class="login-container">
        <div class="login-box">
            <h2>Login to Reesha</h2>

            <?php if (!empty($error)): ?>
                <p style="color:#ded0c8; text-align:left; font-weight:bold;"><?= $error ?></p>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="your-email@gmail.com" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Your Password" required>
                </div>
                <div class="options">
                    <p>Don't have an account? <a href="../SignUp/SignUp.php" class="forgot-password">Sign Up</a></p> 
                </div>
                <button type="submit" class="login-btn">Log In</button>
            </form>
        </div>
        <div class="image-section"></div>
    </div>

    <footer>
        <p>&copy; IT320 2025 Reesha. All rights reserved.</p>
    </footer>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector(".logo").addEventListener("click", function () {
            window.location.href = "../Home/index.php";
        });
    });
    </script>
</body>
</html>



