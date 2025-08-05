<?php
session_start();
include 'db_connection.php'; // Ensure database connection is included

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);  // ✅ Users log in with email
    $password = trim($_POST['password']);

    // Check if user exists using email
    $query = "SELECT * FROM hostelers WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            // ✅ Store phone number in session instead of hosteler_id
            $_SESSION['phone'] = $user['phone']; 
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['father_name'] = $user['father_name'];
            $_SESSION['mother_name'] = $user['mother_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['dob'] = $user['dob'];
            $_SESSION['gender'] = $user['gender'];
            $_SESSION['aadhar'] = $user['aadhar'];
        
            $stmt->close();
            $conn->close();
            
            header("Location: hosteler_welcome.php"); // Redirect to dashboard
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid email or password!";
            header("Location: hostelerlogin.html");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "User not found!";
        header("Location: hostelerlogin.html");
        exit();
    }
}
?>
