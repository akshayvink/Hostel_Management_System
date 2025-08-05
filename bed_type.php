<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '/ABPwebcodes/db_connection.php';

if (!isset($_SESSION['email'])) {
    die("<script>alert('Session expired. Please login again.'); window.location.href='loginpage.php';</script>");
}

// Check if the user has already booked a room
// $stmt = $conn->prepare("SELECT room_number FROM hostelers WHERE email=?");
// $stmt->bind_param("s", $_SESSION['email']);
// $stmt->execute();
// $result = $stmt->get_result();
// $user_room = $result->fetch_assoc();

// if ($user_room && $user_room['room_number']) {
//     die("<script>alert('You have already booked room {$user_room['room_number']}. Multiple bookings are not allowed.'); window.location.href='homepagecheck.php';</script>");
// }

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_payment'])) {
    
        // Check if the user has already booked a room
        $stmt = $conn->prepare("SELECT room_number FROM hostelers WHERE email=?");
        $stmt->bind_param("s", $_SESSION['email']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_room = $result->fetch_assoc();
    
        if ($user_room && $user_room['room_number']) {
            echo "<script>alert('You have already booked room {$user_room['room_number']}. Multiple bookings are not allowed.'); window.location.href='homepagecheck.php';</script>";
            exit();
        }
    
        // Continue with rest of booking logic...
    
    $bed_type = $_POST['bed_type'] ?? '';
    $room_number = $_POST['room_number'] ?? '';
    $payment_status = "Pending";

    if (empty($room_number)) {
        echo "<script>alert('Please select a room before proceeding.'); window.location.href='homepagecheck.php';</script>";
        exit();
    }

    $stmt = $conn->prepare("SELECT max_capacity, current_occupants FROM rooms WHERE room_number = ?");
    $stmt->bind_param("s", $room_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $room_data = $result->fetch_assoc();

    if ($room_data['current_occupants'] >= $room_data['max_capacity']) {
        echo "<script>alert('Room is fully occupied! Choose another.'); window.location.href='homepagecheck.php';</script>";
        exit();
    }

    // Update hosteler record by email
    $stmt = $conn->prepare("UPDATE hostelers SET bed_type=?, fee_status=?, room_number=? WHERE email=?");
    $stmt->bind_param("ssss", $bed_type, $payment_status, $room_number, $_SESSION['email']);
    if (!$stmt->execute()) {
        die("<script>alert('Error submitting payment! Try again.'); window.location.href='homepagecheck.php';</script>");
    }

    // Update room occupancy
    $stmt = $conn->prepare("UPDATE rooms SET current_occupants = current_occupants + 1 WHERE room_number = ?");
    $stmt->bind_param("s", $room_number);
    if (!$stmt->execute()) {
        die("<script>alert('Error updating room occupancy! Try again.'); window.location.href='homepagecheck.php';</script>");
    }

    echo "<script>alert('Booking successful! Await admin approval.'); window.location.href='homepagecheck.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Bed Type</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; padding: 20px; }
        .bed-options { display: flex; gap: 20px; justify-content: center; margin-bottom: 20px; }
        .bed-option { padding: 20px; background: #2b2b2b; color: white; border-radius: 8px; cursor: pointer; width: 150px; text-align: center; }
        .bed-option i { font-size: 24px; margin: 5px; }
        .bed-option:hover { background: black; color:white; }
        .room-list { display: none; margin-top: 20px; text-align: center; }
        .room-box {
            padding: 15px;
            border-radius: 5px;
            display: inline-block;
            margin: 10px;
            cursor: pointer;
            font-weight: bold;
            background: #28a745;
            color: white;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .room-box.full { background: red; cursor: not-allowed; }
        .room-box:hover { background: #218838; }
        .room-box.selected {
            border: 3px solid #007BFF;
            background: #0056b3;
        }
        button {
            padding: 10px 15px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover { background: #0056b3; }
    </style>
    <script>
        function fetchRooms(type) {
            fetch('fetch_rooms.php?bed_type=' + encodeURIComponent(type))
                .then(response => response.json())
                .then(data => {
                    let roomContainer = document.getElementById('room_container');
                    roomContainer.innerHTML = "";

                    if (data.error) {
                        alert(data.error);
                        document.querySelector('.room-list').style.display = 'none';
                        return;
                    }

                    if (data.length === 0) {
                        alert("No available rooms for " + type + ". Please select another bed type.");
                        document.querySelector('.room-list').style.display = 'none';
                        return;
                    }

                    data.forEach(room => {
                        let roomBox = document.createElement("div");
                        roomBox.classList.add("room-box");
                        roomBox.textContent = room.room_number;
                        roomBox.setAttribute("data-room", room.room_number);

                        if (room.is_full) {
                            roomBox.classList.add("full");
                        } else {
                            roomBox.addEventListener("click", function () {
                                document.querySelectorAll(".room-box").forEach(box => box.classList.remove("selected"));
                                this.classList.add("selected");
                                document.getElementById("room_number").value = this.getAttribute("data-room");
                            });
                        }

                        roomContainer.appendChild(roomBox);
                    });

                    document.querySelector('.room-list').style.display = 'block';
                    document.getElementById('bed_type').value = type;
                })
                .catch(error => console.error("Error fetching rooms:", error));
        }
    </script>
</head>
<body>

<div class="bed-options">
    <div class="bed-option" onclick="fetchRooms('Double Bed')">
        <div><i class="fas fa-bed"></i><i class="fas fa-bed"></i></div>
        <div>Double Bed (3000/person)</div>
    </div>
    <div class="bed-option" onclick="fetchRooms('Triple Bed')">
        <div><i class="fas fa-bed"></i><i class="fas fa-bed"></i><i class="fas fa-bed"></i></div>
        <div>Triple Bed (2000/person)</div>
    </div>
</div>

<form method="POST">
    <div class="room-list">
        <h2>Select Room:</h2>
        <div id="room_container"></div>
        <input type="hidden" id="bed_type" name="bed_type">
        <input type="hidden" id="room_number" name="room_number">
        <button type="submit" name="submit_payment">Submit Payment</button>
    </div>
</form>

</body>
</html>
