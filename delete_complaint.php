<?php
include 'db_connection.php'; // Ensure this file contains the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaintId = $_POST['id'] ?? '';

    if ($complaintId) {
        $stmt = $conn->prepare("DELETE FROM complaints WHERE id = ?");
        $stmt->bind_param("i", $complaintId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Complaint deleted successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error deleting complaint!"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid complaint ID!"]);
    }
    $conn->close();
}
?>