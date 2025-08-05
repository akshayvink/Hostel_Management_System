<?php
session_start();
include '/ABPwebcodes/db_connection.php'; // Ensure database connection is included

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']); // Admin logs in using email
    $password = trim($_POST['password']); // Entered password from login form

    // Check if admin exists using email
    $query = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $admin['password'])) {
            // Store admin details in session
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_name'] = $admin['name'];

            $stmt->close();
            $conn->close();

            // Redirect to admin dashboard
            header("Location: admin_dashboard.php");
            exit(); // Prevent further execution
        } else {
            echo "<script>alert('Invalid email or password!'); window.location.href='adminlogin.html';</script>";
        }
    } else {
        echo "<script>alert('Admin not found!'); window.location.href='adminlogin.html';</script>";
    }
}
?>
