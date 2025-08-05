<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: adminlogin.html");
    exit();
}
include '/ABPwebcodes/db_connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            display: flex;
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            width: 250px;
            background: #2B2B2B;
            color: white;
            height: 200vh;
            padding: 20px;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            margin: 10px 0;
            padding: 10px;
            background: #444;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background: #555;
        }
        .main {
            flex: 1;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #343A40;
            color: white;
            padding: 20px;
            border-bottom: 4px solid #007BFF;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
        }
        .logout {
            text-decoration: none;
            color: white;
            background: #FF5757;
            padding: 10px 15px;
            border-radius: 5px;
        }
        .logout:hover {
            background: #FF4040;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .search-bar input {
            width: 300px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
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
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Navigation</h2>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="complaints.php">Complaints</a>
        <a href="announcements.php">Announcements</a>
        <a href="fee_approval.php">Fee Approval</a>
    </div>
    <div class="main">
        <div class="header">
            <h1>Welcome, Admin</h1>
            <a class="logout" href="adminlogin.html">Logout</a>
        </div>
        <h2>Dashboard</h2>
        <div class="search-bar">
            <form method="GET">
                <input type="text" name="search_hosteller" placeholder="Search Hosteller by Room Number">
                <button type="submit">Search</button>
            </form>
        </div>
        <?php
if (isset($_GET['search_hosteller'])) {
    $search = trim($_GET['search_hosteller']);
    $stmt = $conn->prepare("SELECT full_name, father_name, mother_name, phone, email, dob, hosteler_type, aadhar, bed_type, fee_status, room_number FROM hostelers WHERE room_number LIKE ?");
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo "<table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Father's Name</th>
                    <th>Mother's Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Date of Birth</th>
                    <th>Hosteler Type</th>
                    <th>Bed Type</th>
                    <th>Fee Status</th>
                    <th>Room Number</th>
                </tr>
            </thead>
            <tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['full_name']}</td>
                <td>{$row['father_name']}</td>
                <td>{$row['mother_name']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['email']}</td>
                <td>{$row['dob']}</td>
                <td>{$row['hosteler_type']}</td>
                <td>{$row['bed_type']}</td>
                <td>{$row['fee_status']}</td>
                <td>{$row['room_number']}</td>
              </tr>";
    }
    echo "</tbody></table>";
    $stmt->close();
}
?>
    </div>  
</body>
</html>