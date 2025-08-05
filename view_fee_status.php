<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '/ABPwebcodes/db_connection.php';

if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please log in first.'); window.location.href='loginpage.html';</script>";
    exit();
}

$email = $_SESSION['email'];

// Fetch user details from hostelers table
$stmt = $conn->prepare("SELECT full_name, room_number, fee_status FROM hostelers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('User not found.'); window.location.href='homepagecheck.php';</script>";
    exit();
}

$user = $result->fetch_assoc();
$room = $user['room_number'];
$fee_status = $user['fee_status'];

if (empty($room)) {
    $room = "Not Booked Yet";
}

if (empty($fee_status)) {
    $fee_status = "Not Paid";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fee Status</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
            text-align: center;
            padding: 40px;
        }
        .status-box {
            display: inline-block;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .status-box h2 {
            color: white;
        }
        .status {
            font-size: 18px;
            margin-top: 15px;
        }
        .status span {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="status-box">
        <h2>Hello, <?php echo htmlspecialchars($user['full_name']); ?></h2>
        <div class="status">Room Number: <span><?php echo htmlspecialchars($room); ?></span></div>
        <div class="status">Fee Status: <span>
            <?php
                if ($fee_status === "Pending") {
                    echo "Pending (Waiting for Admin Approval)";
                } else {
                    echo htmlspecialchars($fee_status);
                }
            ?>
        </span></div>
    </div>
</body>
</html>
