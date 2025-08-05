<?php
session_start(); // Start the session
include 'db_connection.php'; // Ensure this file contains the database connection

$email = $_SESSION['email'] ?? '';

if ($email) {
    $query = "SELECT * FROM complaints WHERE email = '$email' ORDER BY created_at DESC";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='complaint-item' data-id='" . htmlspecialchars($row['id']) . "'>";
            echo "<p><strong>Type:</strong> " . htmlspecialchars($row['complaint_type']) . "</p>";
            echo "<p><strong>Description:</strong> " . htmlspecialchars($row['description']) . "</p>";
            echo "<p><strong>Status:</strong> " . htmlspecialchars($row['status']) . "</p>";
            echo "<p><strong>Date:</strong> " . htmlspecialchars($row['created_at']) . "</p>";
            echo "<button class='delete-complaint'>Delete</button>"; // Added delete button
            echo "</div>";
        }
    } else {
        echo "<p>No complaints found.</p>";
    }
} else {
    echo "<p>Please log in to see your complaints.</p>";
}
?>