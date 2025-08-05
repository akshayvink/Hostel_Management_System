<?php
session_start();
include 'db_connection.php'; // Ensure database connection is included

header('Content-Type: application/json'); // Set JSON response header

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

$email = $_SESSION['email']; // Get email from session

// Prepare the query to fetch hosteler details
$query = "SELECT full_name, father_name, mother_name, phone, email, dob FROM hostelers WHERE email = ?";
$stmt = $conn->prepare($query);

// Check if preparation was successful
if ($stmt === false) {
    echo json_encode(["error" => "Error preparing the query"]);
    exit();
}

// Bind parameters and execute the query
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the result and return JSON response
if ($row = $result->fetch_assoc()) {
    // Optionally format the date of birth before returning it
    $row['dob'] = date("d-m-Y", strtotime($row['dob'])); // Format DOB as "DD-MM-YYYY"
    echo json_encode($row); // Return hosteler details as JSON
} else {
    echo json_encode(["error" => "Hosteler details not found"]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
