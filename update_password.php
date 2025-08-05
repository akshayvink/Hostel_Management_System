<?php
session_start();
include 'db_connection.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['otp_verified']) || !$_SESSION['otp_verified']) {
        echo "Unauthorized access!";
        exit();
    }

    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_SESSION['email'];

    if ($new_password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    // Hash new password before storing
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password in the database
    $update_query = "UPDATE hosteler SET password='$hashed_password' WHERE email='$email'";

    if (mysqli_query($conn, $update_query)) {
        echo "Password updated successfully!";
        session_destroy(); // Clear session
        header("Location: hostelerlogin.html"); // Redirect to login
        exit();
    } else {
        echo "Error updating password: " . mysqli_error($conn);
    }
}
?>
