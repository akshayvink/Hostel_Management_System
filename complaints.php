<?php
session_start();
include '/ABPwebcodes/db_connection.php'; // Connect to the database

// Check if admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: adminlogin.html");
    exit();
}

// Update complaint status (if admin modifies it)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $complaint_id = intval($_POST['complaint_id']);
    $new_status = trim($_POST['status']);

    $stmt = $conn->prepare("UPDATE complaints SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $complaint_id);

    if ($stmt->execute()) {
        echo "<script>alert('Complaint status updated successfully'); window.location.href='complaints.php';</script>";
    } else {
        echo "<script>alert('Error updating complaint status'); window.location.href='admin_dashboard.php';</script>";
    }

    $stmt->close();
    $conn->close();
    exit;
}

// Fetch all complaints
$query = "SELECT * FROM complaints ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Complaint Handling</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #F5F5F5;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 10px;
            padding: 15px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #2B2B2B;
            font-size:27px;
            margin-bottom: 20px;
            margin-top:-30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background: #2B2B2B;
            color: white;
        }
        button {
            padding: 10px 15px;
            background: #2B2B2B;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #555;
        }

        .links{
        margin-top: 15px;
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
    <div class="container">
        <div class="links">
            <a href="admin_dashboard.php" class="back-btn">← Back</a>
        </div>
        <center><h2>Admin Complaint Handling</h2></center>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Room Number</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['complaint_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <form method="POST" >
                                <input type="hidden" name="complaint_id" value="<?php echo $row['id']; ?>">
                                <select name="status">
                                    <option value="Pending" <?php echo $row['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Resolved" <?php echo $row['status'] === 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
