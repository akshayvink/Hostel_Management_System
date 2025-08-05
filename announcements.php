<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: adminlogin.html");
    exit();
}
include '/ABPwebcodes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_announcement'])) {
    $announcement = trim($_POST['announcement']);
    if (!empty($announcement)) {
        $stmt = $conn->prepare("INSERT INTO announcements (message, created_at) VALUES (?, NOW())");
        $stmt->bind_param("s", $announcement);
        $stmt->execute();
        $stmt->close();
    }
}

$query = "SELECT * FROM announcements ORDER BY created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #F8F9FA;
            margin: 0;
            padding: 0;
        }
        .header {
            background: #343A40;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .container {
            margin: 20px auto;
            padding: 20px;
            background: white;
            max-width: 800px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #495057;
            margin-bottom: 20px;
        }
        form textarea {
            width: 100%;
            height: 100px;
            border: 1px solid #CED4DA;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 14px;
        }
        form button {
            padding: 10px 15px;
            background: green;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        form button:hover {
            background:darkgreen;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #DEE2E6;
            padding: 10px;
            text-align: left;
        }
        table th {
            background: #343A40;
            color: white;
        }
        table tr:nth-child(even) {
            background: #F8F9FA;
        }
        table tr:hover {
            background: #E9ECEF;
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
    <div class="header">
        <h1>Announcements</h1>
    </div>
    <div class="container">
    <div class="links">
            <a href="admin_dashboard.php" class="back-btn">← Back</a>
        </div>
        <h2>Create an Announcement</h2>
        <form method="POST">
            <textarea name="announcement" placeholder="Write your announcement here..."></textarea>
            <button type="submit" name="submit_announcement">Submit Announcement</button>
        </form>
        <h2>Announcement History</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Announcement</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>