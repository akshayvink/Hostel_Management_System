<?php
session_start();
include 'db_connection.php'; // Ensure you have database connection

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "<div class='complaint-history'><p>Please log in to view your complaints.</p></div>";
    exit();
}

// Get logged-in user's email from session
$user_email = $_SESSION['email'];

// Fetch complaints for the logged-in user only
$query = "SELECT * FROM complaints WHERE email = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<div class='complaint-history'>";
    echo "<h3>Complaint History</h3>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<div class='complaint-item'>";
        echo "<p><strong>Room Number:</strong> " . htmlspecialchars($row['room_number']) . "</p>";
        echo "<p><strong>Complaint:</strong> " . htmlspecialchars($row['description']) . "</p>";
        echo "<p><strong>Status:</strong> " . htmlspecialchars($row['status']) . "</p>";
        echo "<p><strong>Date:</strong> " . $row['created_at'] . "</p>";
        echo "</div>";
    }
    
    echo "</div>";
} else {
    echo "<div class='complaint-history'><p>No complaints found.</p></div>";
}
?>
