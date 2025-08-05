<?php
$servername = "localhost";
$username = "root";  // Default XAMPP user
$password = "";      // Default is empty
$database = "hostel_management";  // Your database name

$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
