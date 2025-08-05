<?php
session_start();
header('Content-Type: application/json');

// Validate request method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    exit;
}

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "hostel_management");

// Check the connection
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Retrieve and sanitize form data
$name = $_SESSION['full_name'] ?? '';
$email = $_SESSION['email'];
$room_number = trim($_POST['room_number'] ?? '');
$complaint_type = trim($_POST['complaint_type'] ?? '');
$description = trim($_POST['description'] ?? '');

// Validate form inputs
if (empty($room_number) || empty($complaint_type) || empty($description)) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

// Insert the complaint into the database
$stmt = $conn->prepare("INSERT INTO complaints (name, email, room_number, complaint_type, description, status) VALUES (?, ?, ?, ?, ?, ?)");
$status = "Pending"; // Default status for new complaints
$stmt->bind_param("ssssss", $name, $email, $room_number, $complaint_type, $description, $status);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Complaint submitted successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
