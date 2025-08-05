<?php
include '/ABPwebcodes/db_connection.php';

session_start();

// Check if the user has already booked a room
$stmt = $conn->prepare("SELECT room_number FROM hostelers WHERE id=?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user_room = $result->fetch_assoc();

// If the user has already booked a room, send an error response
if ($user_room && $user_room['room_number']) {
    echo json_encode(["error" => "You have already booked room " . $user_room['room_number'] . ". Multiple bookings are not allowed."]);
    exit();
}

if (isset($_GET['bed_type'])) {
    $bed_type = $_GET['bed_type'];

    // Fetch available rooms and their status
    $stmt = $conn->prepare("SELECT room_number, current_occupants, max_capacity FROM rooms WHERE room_type = ?");
    $stmt->bind_param("s", $bed_type);
    $stmt->execute();
    $result = $stmt->get_result();

    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = [
            "room_number" => $row['room_number'],
            "is_full" => ($row['current_occupants'] >= $row['max_capacity'])
        ];
    }

    echo json_encode($rooms);
} else {
    echo json_encode([]);
}
?>