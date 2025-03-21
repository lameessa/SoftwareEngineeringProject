<?php
session_start();

// Database connection variables
$host = "localhost";
$dbUser = "root";          
$dbPass = "root";          
$dbName = "reesha";        
$port = '8889';              

// Create connection
$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName, $port);

//  Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Process form when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate user inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check if any field is empty (basic validation)
    if (empty($name) || empty($username) || empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        // Check if username already exists
        $checkQuery = "SELECT * FROM User WHERE UserName = '$username'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            $error = "Username already exists. Please choose another.";
        } else {
            //  Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Handle profile photo upload
            $profilePhoto = $_FILES['ProfilePhoto'];
            $targetDir = "../images/";
            $fileName = uniqid() . "_" . basename($profilePhoto["name"]);
            $targetFile = $targetDir . $fileName;

            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($profilePhoto["type"], $allowedTypes)) {
                $error = "Only JPG, JPEG, and PNG files are allowed.";
            } else {
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                if (!move_uploaded_file($profilePhoto["tmp_name"], $targetFile)) {
                    $error = "Failed to upload profile photo.";
                } else {
                    //  Insert the new user into the database
                    $insertQuery = "INSERT INTO User (UserName, Email, UserPic, Password)
                                    VALUES ('$username', '$email', '$fileName', '$hashedPassword')";

                    if (mysqli_query($conn, $insertQuery)) {
                        //  Save username in the session as user_id
                        $_SESSION['user_id'] = $username;

                        //  Redirect to Home page after successful signup
                        header("Location: ../Profile/Profile.php");
                        exit();
                    } else {
                        $error = "Error inserting user: " . mysqli_error($conn);
                    }
                }
            }
        }
    }
}

// Close connection at the end
mysqli_close($conn);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Reesha</title>
    <link rel="stylesheet" href="Signup.css">
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
    
    <main class="signup-container">
        <div class="signup-box">
            <div class="image"></div>
            <div class="form">
                <h2>Create an Account</h2>
                                <?php if (isset($error)) : ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endif; ?>
                    
                <form action="signup.php" method="POST" enctype="multipart/form-data">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
                    
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                    
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                    
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>

                    <label for="ProfilePhoto">Upload your profile photo</label>
                    <input type="file" id="ProfilePhoto" name="ProfilePhoto" required>

                    <button type="submit">Sign Up</button>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Reesha. All rights reserved.</p>
    </footer>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Redirect to index.php when clicking on the logo
        document.querySelector(".logo").addEventListener("click", function() {
            window.location.href = "../Home/index.php";
        });
      });
    </script>
</body>
</html>
