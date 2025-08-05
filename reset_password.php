<?php
session_start();
include 'db_connection.php'; // Database connection

// Check if OTP was verified
if (!isset($_SESSION['otp_verified']) || !isset($_SESSION['email'])) {
    echo "Access denied. Please verify OTP first.";
    exit();
}

// Handle password reset form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $email = $_SESSION['email'];
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT); // Encrypt password

        // Update password in the database
        $sql = "UPDATE hostelers SET password = ? WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $email);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            // Clear session
            session_destroy();
            header("Location: hostelerlogin.html"); // Redirect to login page
            exit();
        } else {
            $error = "Failed to reset password. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Happy Home Boys Hostel</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #B3B3B3; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #2B2B2B;
            padding: 50px;
            border-radius: 25px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 300px;
            border-top: 8px solid white;
        }
        h2 {
            color: #B3B3B3;
            font-weight: 600;
            margin-bottom: 20px;
        }
        input {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid white; 
            border-radius: 25px;
            font-size: 14px;
            outline: none;
            background-color: #2B2B2B; 
            color: white;
            text-align: center;
        }
        .btn {
            background: white; 
            color: #2b2b2b;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            margin-top: 10px;
        }
        .btn:hover {
            background: #D4D4D4; 
            color: white;
        }
        .links {
            margin-top: 15px;
            font-size: 14px;
        }
        .links a {
            color: #B3B3B3; 
            font-weight: 600;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
        p {
            color: white; 
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Reset Password</h2>
        <p>Enter a new password below.</p>
        
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

        <form action="" method="POST">
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit" class="btn">Reset Password</button>
        </form>
    </div>

</body>
</html>
