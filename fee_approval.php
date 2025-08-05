<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: adminlogin.html");
    exit();
}
include '/ABPwebcodes/db_connection.php';

// Approve a payment if requested
if (isset($_GET['approve'])) {
    $user_id = $_GET['approve'];
    $stmt = $conn->prepare("UPDATE hostelers SET fee_status='Approved' WHERE id=?");
    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Payment approved successfully!'); window.location.href='fee_approval.php';</script>";
}

// Fetch pending payments
$pending = $conn->query("SELECT id, full_name, bed_type, fee_status FROM hostelers WHERE fee_status='Pending'");
if (!$pending) {
    die("Pending query failed: " . $conn->error);
}

// Fetch approved payments
$approved = $conn->query("SELECT id, full_name, bed_type, fee_status FROM hostelers WHERE fee_status='Approved'");
if (!$approved) {
    die("Approved query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Fee Approval</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        h1, h2 {
            text-align: center;
            color: #343a40;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #dee2e6;
            text-align: left;
        }
        th {
            background: #2b2b2b;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f1f1f1;
        }
        .approve-btn {
            padding: 8px 12px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
        }
        .approve-btn:hover {
            background: #218838;
        }
        .no-records {
            text-align: center;
            color: #6c757d;
        }

        .links{
        margin-top: 15px;
        margin-bottom: -70px;
        font-size: 25px;
    }

    .links a {
        color:#2b2b2b; 
        font-weight: 600;
        text-decoration: none;
    }
    .links a:hover {
        text-decoration: underline;
    }
    </style>
</head>
<body>
<div class="links">
            <a href="admin_dashboard.php" class="back-btn">← Back</a>
        </div>
    <h1>Fee Approval Dashboard</h1>

    <h2>Pending Payments</h2>
    <?php if ($pending->num_rows > 0): ?>
        <table>
            <tr>
                <th>User ID</th>
                <th>Full Name</th>
                <th>Bed Type</th>
                <th>Fee Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $pending->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= $row['bed_type'] ?></td>
                    <td><?= $row['fee_status'] ?></td>
                    <td><a href="?approve=<?= $row['id'] ?>" class="approve-btn">Approve</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="no-records">No pending payments found.</p>
    <?php endif; ?>

    <h2>Approved Payments</h2>
    <?php if ($approved->num_rows > 0): ?>
        <table>
            <tr>
                <th>User ID</th>
                <th>Full Name</th>
                <th>Bed Type</th>
                <th>Fee Status</th>
            </tr>
            <?php while ($row = $approved->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= $row['bed_type'] ?></td>
                    <td><?= $row['fee_status'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="no-records">No approved payments found.</p>
    <?php endif; ?>

</body>
</html>
