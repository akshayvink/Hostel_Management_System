<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure this path is correct
include 'db_connection.php';
session_start(); // Start session at the top

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Check if email exists in the database
    $query = "SELECT * FROM hostelers WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $otp = rand(100000, 999999); // Generate 6-digit OTP

        // Store OTP and timestamp in session
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_time'] = time(); // Store OTP generation time
        $_SESSION['email'] = $email;

        // Send OTP via email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'bhavishsuvarna00@gmail.com'; // Your Gmail
            $mail->Password = 'xarf nqir lbjn jtmk'; // Your Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('bhavishsuvarna00@gmail.com', 'Happy Home Boys Hostel');
            $mail->addAddress($email);
            $mail->Subject = "Your OTP Code";
            $mail->Body = "Your OTP code is: $otp\n\nThis OTP is valid for 5 minutes.";

            if ($mail->send()) {
                header("Location: verify_otp.php"); // Redirect after success
                exit();
            } else {
                $_SESSION['error'] = "Failed to send OTP. Please try again.";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        $_SESSION['error'] = "Email not registered!";
    }
    header("Location: forgot_password.php"); // Redirect back to forgot password page with error message
    exit();
}
?>
