
<?php
session_start();
include 'db_connection.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = $_POST['otp'];

    // Check if OTP exists in session
    if (!isset($_SESSION['otp']) || !isset($_SESSION['email']) || !isset($_SESSION['otp_time'])) {
        $error = "Session expired. Please request a new OTP.";
    } else {
        // Check if OTP is expired (5 minutes = 300 seconds)
        if (time() - $_SESSION['otp_time'] > 300) {
            unset($_SESSION['otp']); // Remove expired OTP
            unset($_SESSION['otp_time']);
            $error = "OTP has expired. Please request a new one.";
        } else {
            // Validate OTP
            if ($entered_otp == $_SESSION['otp']) {
                $_SESSION['otp_verified'] = true; // OTP is correct
                header("Location: reset_password.php"); // Redirect to reset password page
                exit();
            } else {
                $error = "Invalid OTP. Please try again.";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Happy Home Boys Hostel</title>
    
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
        <h2>Enter OTP</h2>
        <p>Check your email for the OTP and enter it below.</p>
        
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

        <form action="" method="POST">
            <input type="text" name="otp" placeholder="Enter OTP" required>
            <button type="submit" class="btn">Verify OTP</button>
        </form>
        <div class="links">
            <a href="forgot_password.php">Resend OTP</a>
        </div>
    </div>

</body>
</html>
