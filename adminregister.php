<?php
include '/ABPwebcodes/db_connection.php';

// Handle form submission via AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = "";
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Password mismatch check
    if ($password !== $confirm_password) {
        $response = "<p style='color: red;'>Passwords do not match!</p>";
        echo $response;
        exit;
    }

    // Check if email or phone already exists
    $checkQuery = "SELECT * FROM admins WHERE email = ? OR phone = ?";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "ss", $email, $phone);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $response = "<p style='color: red;'>Email or Phone number already registered!</p>";
        echo $response;
        exit;
    }

    // All checks passed, insert admin
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO admins (name, phone, email, password) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $name, $phone, $email, $hashed_password);

    if (mysqli_stmt_execute($stmt)) {
        $response = "<p style='color: lightgreen;'>Registration successful! Redirecting to login...</p>";
    } else {
        $response = "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
    }

    echo $response;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        }

        .btn {
            background: white; 
            color:#2b2b2b;
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
            background: #d4d4d4; 
            color: white;
        }

        .links {
            margin-top: 15px;
            font-size: 14px;
        }

        .links a {
            color:#B3B3B3; 
            font-weight: 600;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            color: #B3B3B3; 
            display: block;
            margin-top: 8px;
            text-align: left;
        }

        #message {
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Admin Registration</h2>
    <form id="adminRegisterForm" method="POST">
        <div id="message"></div>

        <input type="text" style="background-color:#2B2B2B; color:white;" name="name" placeholder="Enter your name" required>
        <input type="tel" style="background-color:#2B2B2B; color:white;" name="phone" placeholder="Enter your phone number" required>
        <input type="email" style="background-color:#2B2B2B; color:white;" name="email" placeholder="Enter your email" required>

        <label>Password:</label>
        <input type="password" style="background-color:#2B2B2B; color:white;" name="password" placeholder="Enter your password" required>
        <input type="password" style="background-color:#2B2B2B; color:white;" name="confirm_password" placeholder="Confirm your password" required>

        <button type="submit" class="btn">Register</button>
    </form>

    <div class="links">
        <a href="adminlogin.html">Already have an account? Login</a>
    </div>
</div>

<!-- AJAX script to handle form submission without reload -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $("#adminRegisterForm").submit(function(event){
        event.preventDefault(); // Prevent full page reload

        $.ajax({
            type: "POST",
            url: "adminregister.php",
            data: $(this).serialize(),
            success: function(response){
                $("#message").html(response);

                if (response.toLowerCase().includes("successful")) {
                    setTimeout(function(){
                        window.location.href = "adminlogin.html";
                    }, 2000);
                }
            }
        });
    });
});
</script>

</body>
</html>
