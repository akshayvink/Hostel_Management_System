<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'db_connection.php'; // Ensure connection to the database

// Fetch all announcements
$announcementQuery = "SELECT * FROM announcements ORDER BY created_at DESC";
$announcementResult = $conn->query($announcementQuery);

//fetch hosteler details

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
} else {
    echo "Email not found in session";
    exit();
}

include '/ABPwebcodes/db_connection.php';

$sql = "SELECT full_name, father_name, mother_name, phone, email, dob, room_number FROM hostelers WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $email); // 's' is the type for string

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the result and store it in variables
    $row = $result->fetch_assoc();
    $full_name = $row['full_name'];
    $father_name = $row['father_name'];
    $mother_name = $row['mother_name'];
    $phone = $row['phone'];
    $email = $row['email'];
    $dob = $row['dob'];  // Assuming 'dob' is stored as DATE in your database
    $room_number = $row['room_number'];  // ✅ Now included
} else {
    $full_name = $father_name = $mother_name = $phone = $email = $dob = $room_number = "N/A";
}

// Close the connection
$stmt->close();
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            background-color: #b3b3b3;
        }

        /* Sidebar */

        .sidebar-logo {
            font-size: 50px;
            color: #fff;
            margin: 20px 10px 20px 2px;
            display: block;
            cursor: pointer;
            transition: transform 0.3s ease;
            text-align: center;
            transform: translateX(10px);
            /* added */
        }


        .logo-link:hover .sidebar-logo {
            transform: scale(1.2);
        }


        .sidebar {
            position: fixed;
            height: 100vh;
            width: 250px;
            background-color: #2b2b2b;
            color: #fff;
            padding: 20px;
            margin-left: -40px;
            margin-top: -40px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            padding: 15px;
            margin: 10px 0;
            cursor: pointer;
            border-radius: 8px;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar ul li:hover,
        .sidebar ul li.active {
            background-color: #d4d4d4;
            color: #2b2b2b;
        }

        .sidebar-instagram {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: left;
            padding-left: 50px;
            font-size: 16px;
        }

        .sidebar-instagram a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .sidebar-instagram a:hover {
            color: #ff6b6b;
            /* light red on hover */
        }

        .sidebar-instagram i {
            margin-right: 5px;
        }




        /* ✅ Fix Topbar */
        .topbar {
            position: fixed;
            /* ✅ Keep it at the top */
            top: 0;
            left: 250px;
            /* ✅ Push it right of sidebar */
            width: calc(100% - 250px);
            /* ✅ Take full width except sidebar */
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #2b2b2b;
            padding: 15px 30px;
            color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            /* ✅ Ensure it stays above content */
        }

        .welcome-text h2 {
            color: #fff;
            margin: 0;
        }

        /* ✅ Fix Content Layout */
        .content {
            flex: 1;
            margin-left: 250px;
            /* ✅ Make space for sidebar */
            margin-top: 10px;
            /* ✅ Prevent content from hiding under topbar */
            padding: 20px;
            z-index: 1;
        }

        /* Hide all sections except active */
        .section {
            margin: 100px 200px 0 auto;
            display: none;
            width: 80%;
            max-width: 800px;
            text-align: left;
        }

        .section.active {
            display: block;
        }

        .section.active {
            display: block;
        }

        /* Right options */
        .right-options {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .right-options a {
            text-decoration: none;
            background-color: #2b2b2b;
            color: #fff;
            padding: 10px 15px;
            border-radius: 8px;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .right-options a:hover {
            background-color: #d4d4d4;
            color: #2b2b2b;
        }

        .right-options .logout-link {
            background-color: red;
            color: white;
        }

        .right-options .logout-link:hover {
            background-color: darkred;
            color: white;
        }

        .complaint-container {
            background-color: #2b2b2b;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 800px;
            margin: 20px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .complaint-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
            /* Creates space between form and history */
            width: 80%;
            max-width: 800px;
            margin: 20px auto;
        }

        .complaint-container h2 {
            color: #b3b3b3;
            font-size: 22px;
            margin-bottom: 10px;
            text-align: center;
        }

        .complaint-container p {
            text-align: center;
            font-size: 14px;
            color: #b3b3b3;
            margin-bottom: 20px;
        }

        .complaint-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .complaint-container label {
            font-weight: 600;
            color: #b3b3b3;
        }

        .complaint-container input,
        .complaint-container select,
        .complaint-container textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #d4d4d4;
            border-radius: 5px;
            font-size: 14px;
            background-color: #2b2b2b;
            color: #b3b3b3;
        }

        .complaint-container textarea {
            resize: vertical;
        }

        .complaint-container button {
            background-color: #2b2b2b;
            color: #b3b3b3;
            padding: 10px;
            border: 1px solid #d4d4d4;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .complaint-container button:hover {
            background-color: #444;
        }

        .complaint-history {
            margin-top: 40px;
            background: #d4d4d4;
            border-radius: 18px;
            width: 100%;
            max-width: 900px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-left: auto;
            margin-right: auto;
        }


        .complaint-history h3 {
            padding-top: 10px;
            text-align: center;
            color: #2b2b2b;
            /* Slightly darker text */
            margin-bottom: 15px;
            font-size: 20px;
        }

        .complaint-item {
            background: #2b2b2b;
            /* Dark background for contrast */
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            border: 6px solid #d4d4d4;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
            /* More noticeable shadow */
        }

        .complaint-item p {
            margin: 6px 0;
            font-size: 15px;
            color: #b3b3b3;
        }

        .complaint-item p strong {
            color: #d4d4d4;
        }


        /*room booking*/
        #room-booking h2 {
            color: #2b2b2b;
        }

        /*fee status*/
        #fee-status h2 {
            color: #2b2b2b;
        }

        .bed-options {
            margin-top: 60px;
            display: flex;
            gap: 20px;
            /* Space between the options */
            justify-content: center;
            /* Align side by side */
        }

        .bed-option {
            display: flex;
            flex-direction: column;
            /* Keep icon and label vertically aligned */
            justify-content: center;
            align-items: center;
            background-color: #2b2b2b;
            border: 2px solid #d4d4d4;
            border-radius: 12px;
            padding: 20px;
            /* Reduced padding */
            width: 300px;
            min-height: 200px;
            /* Ensure consistent height */
            text-align: center;
            color: #b3b3b3;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .bed-option i {
            font-size: 40px;
            margin-bottom: 10px;
            /* Space between icon and label */
            display: inline-block;
        }

        .bed-option i:nth-child(2) {
            margin-top: -10px;
            /* Add space between two icons for double bed */
        }

        .bed-label {
            font-size: 16px;
            margin-top: 8px;
            /* Space above the text */
        }

        .bed-option:hover {
            background-color: #d4d4d4;
            color: #2b2b2b;
        }

        .bed-option.selected {
            border: 3px solid #2b2b2b;
            box-shadow: 0 0 15px #2b2b2b;
        }

        .bed-icons {
            display: flex;
            gap: 5px;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
        }



        .container {
            margin: 20px;
            padding: 20px;
            background: #2b2b2b;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #fff;
            margin-bottom: 10px;
        }

        .announcement {
            background: #d4d4d4;
            color: #2b2b2b;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 15px;
            font-size: 16px;
            line-height: 1.6;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .timestamp {
            font-size: 14px;
            color: #555;
            margin-top: 10px;
            text-align: right;
        }

        #hosteler-details {
            background-color: #2b2b2b;
            padding: 15px;
            max-height: 430px;
            border-radius: 25px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 80%;
            max-width: 500px;
            margin-left: auto;
            margin-right: 320px;
            text-align: center;

        }

        #hosteler-details h2 {
            color: #fff;
            margin-top: 20px;
            margin-bottom: 30px;
            font-size: 30px;
            font-weight: bold;
            text-align: center;
        }

        #hosteler-details p {

            font-size: 16px;
            margin-bottom: 25px;
            color: #d4d4d4;
        }

        #hosteler-details p strong {
            font-weight: bold;
        }

        #about-hostel h2 {
            color: #2b2b2b;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <a href="hosteler_welcome.php" class="logo-link">
            <i class="fas fa-hotel sidebar-logo"></i>
        </a>
        <ul>
            <li data-section="hosteler-details"><i class="fas fa-user"></i> Hosteler Details</li>
            <li data-section="room-booking"><i class="fas fa-bed"></i> Room Booking</li>
            <li data-section="announcements"><i class="fas fa-bullhorn"></i> Announcements</li>
            <li data-section="complaint-box"><i class="fas fa-exclamation-circle"></i> Complaint Box</li>
            <li data-section="fee-status"><i class="fas fa-receipt"></i> Fee Status</li>
        </ul>

        <div class="sidebar-instagram">
            <a href="https://instagram.com/hhboyshostel" target="_blank">
                <i class="fab fa-instagram"></i>hhboyshostel</a>
        </div>
    </div>

    <div class="topbar">
        <div class="welcome-text">
            <?php
            if (isset($_SESSION['full_name'])) {
                echo "<h2>Welcome, " . htmlspecialchars($_SESSION['full_name']) . "</h2>";
            } else {
                echo "<h2>Hostel Management System</h2>";
            }
            ?>
        </div>

        <div class="right-options">
            <a href="#" onclick="showSection('about-hostel')">About Hostel</a>
            <a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="content">
        <div id="hosteler-details" class="section">
            <h2>Hosteler Details</h2>
            <p><strong>Full Name:</strong> <?php echo $full_name; ?></p>
            <p><strong>Father's Name:</strong> <?php echo $father_name; ?></p>
            <p><strong>Mother's Name:</strong> <?php echo $mother_name; ?></p>
            <p><strong>Phone Number:</strong> <?php echo $phone; ?></p>
            <p><strong>Email Address:</strong> <?php echo $email; ?></p>
            <p><strong>Date of Birth:</strong> <?php echo date("d-m-Y", strtotime($dob)); ?></p>

        </div>

        <div id="room-booking" class="section">
            <h2>
                <center>Room Booking</center>
            </h2>
            <p>
                <center>Select your preferred bed type:</center>
            </p>

            <div class="bed-options">
                <!-- <div class="bed-option" data-bed="double">
                <div class="bed-icons">
                    <i class="fas fa-bed"></i>
                    <i class="fas fa-bed"></i>
                </div>
                <div class="bed-label">Double Bed</div>
            </div>
            
            <div class="bed-option" data-bed="triple">
                <div class="bed-icons">
                    <i class="fas fa-bed"></i>
                    <i class="fas fa-bed"></i>
                    <i class="fas fa-bed"></i>
                </div>
                <div class="bed-label">Triple Bed</div>
            </div> -->
                <?php include 'bed_type.php'; ?>
            </div>


            <div id="bed-selection-output" class="selection-display"></div>
        </div>
        <div id="announcements" class="section">
            <div class="container">
                <h2>
                    <center>Announcements</center>
                </h2>
                <?php while ($row = $announcementResult->fetch_assoc()) { ?>
                    <div class="announcement">
                        <p><?php echo htmlspecialchars($row['message']); ?></p>
                        <div class="timestamp"><?php echo date("F j, Y, g:i a", strtotime($row['created_at'])); ?></div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div id="complaint-box" class="section">
            <div class="complaint-section">
                <div class="complaint-container">
                    <h2>Complaint Box</h2>
                    <p>Raise your issues or complaints here.</p>

                    <form action="submit_complaint.php" id="complaintForm">
                        <label for="name">Your Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['full_name'] ?? ''); ?>" required>

                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" required>

                        <label for="room">Room Number:</label>
                        <input type="text" id="room_number" name="room_number" value="<?php echo htmlspecialchars($room_number); ?>" readonly>

                        <label for="complaint_type">Complaint Type:</label>
                        <select id="complaint_type" name="complaint_type" required>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Food">Food</option>
                            <option value="Security">Security</option>
                            <option value="Other">Other</option>
                        </select>

                        <label for="description">Complaint Description:</label>
                        <textarea id="description" name="description" rows="4" required></textarea>

                        <button type="submit" name="submit">Submit Complaint</button>
                    </form>
                </div>
            </div>
            <div id="complaintHistory"></div>
        </div>
    </div>

    <div id="fee-status" class="section">
        <h2>Fee Status</h2>
        <p>Check your hostel fee payment details.</p>
        <?php include 'view_fee_status.php'; ?>
    </div>



    <div id="about-hostel" class="section">
        <h2>About Hostel</h2>
        <p>Learn more about our hostel facilities and regulations.</p>
        <br><br>
        <p><b>Our hostel provides a safe and comfortable living environment for students. We offer various amenities including:</b></p>
        <ul>
            <li>24/7 security</li>
            <li>Monday to Friday veg meals,Saturday and Sunday served with both veg and nonveg meals.</li>
            <li>24/7 water supply</li>
            <li>Common Bathroom and Washroom </li>
            <li>Regular cleaning services</li>
        </ul>
        <br><br>
        <p><b>Room details and availability:</b></p>
        <ul>
            <li>Room Type: 8 Double/12 Triple sharing</li>
            <li>Room Size: 250 square feet(each room)</li>
            <li>Room Facilities: Bed, Study Table, Chair, Wardrobe</li>
        </ul>
        <br><br>
        <p><b>Hostel Rules:</b></p>
        <ul>
            <li>Respect fellow residents and staff</li>
            <li>Maintain cleanliness in common areas</li>
            <li>Follow the check-in and check-out timings</li>
            <li>Report any maintenance issues promptly</li>
            <li>Adhere to the hostel's code of conduct</li>
        </ul>
        <br><br>
        <p><b>For any queries or issues, please contact the hostel management.</b></p>
        <ul>
            <li>Phone: +91 08618636554</li>
            <li>Instagram: @hhboyshostel</li>
        </ul>
        <br><br>
        <p><b>Food Menu:</b></p>
        <div style="overflow-x:auto;">
            <h2>Weekly Mess Timetable</h2>
            <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse: collapse; text-align: center;">
                <thead style="background-color: #f2f2f2;">
                    <tr>
                        <th>Day</th>
                        <th>Morning Breakfast</th>
                        <th>Night Lunch/ Dinner</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Monday</td>
                        <td>Idli with Sambar & Chutney + Tea</td>
                        <td>Chapati, Rice, Dal, Mixed Veg Curry</td>
                    </tr>
                    <tr>
                        <td>Tuesday</td>
                        <td>Poha + Tea</td>
                        <td>Rice, Rajma, Aloo Gobi</td>
                    </tr>
                    <tr>
                        <td>Wednesday</td>
                        <td>Upma with Coconut Chutney + Tea</td>
                        <td>Rice, Chole, Curd</td>
                    </tr>
                    <tr>
                        <td>Thursday</td>
                        <td>Aloo Chapati with Pickle + Tea</td>
                        <td> Rice, Dal Tadka, Cabbage Curry</td>
                    </tr>
                    <tr>
                        <td>Friday</td>
                        <td>Dosa with Sambar & Chutney + Tea</td>
                        <td>Chapati,Matar, Mix Veg</td>
                    </tr>
                    <tr>
                        <td>Saturday</td>
                        <td>Chapati with Aloo Sabzi + Tea</td>
                        <td> Rice, Moong Dal, Buttermilk</td>
                    </tr>
                    <tr>
                        <td>Sunday</td>
                        <td>Poha & Tea</td>
                        <td>Chapati, Rice, Chicken Curry (Non-Veg), Veg Pulao, Curd</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Room booking bed type selection
        $(document).ready(function() {
            $(".bed-option").click(function() {
                // Remove selection from all
                $(".bed-option").removeClass("selected");

                // Mark current one as selected
                $(this).addClass("selected");

                const bedType = $(this).data("bed");
                let message = "";

                if (bedType === "double") {
                    message = "You have selected a <strong>Double Bed</strong> room.";
                } else if (bedType === "triple") {
                    message = "You have selected a <strong>Triple Bed</strong> room.";
                }

                $("#bed-selection-output").html(message);
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            // ✅ Set default section
            showSection('hosteler-details');

            // ✅ Attach click event to sidebar menu items
            document.querySelectorAll('.sidebar ul li').forEach(item => {
                item.addEventListener("click", function() {
                    let sectionId = this.getAttribute("data-section");
                    showSection(sectionId);
                });
            });

            // ✅ Attach click event to "About Hostel" in the top bar
            document.querySelector(".right-options a[href='#']").addEventListener("click", function(e) {
                e.preventDefault();
                showSection('about-hostel');
            });
        });

        function showSection(sectionId) {
            console.log("Switching to:", sectionId); // Debugging

            // ✅ Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.style.display = "none"; // Hide all sections
                section.classList.remove('active'); // Remove 'active' class
            });

            // ✅ Show only the selected section
            let activeSection = document.getElementById(sectionId);
            if (activeSection) {
                activeSection.style.display = "block"; // Show
                activeSection.classList.add('active'); // Mark as active
            }

            // ✅ Remove 'active' class from all sidebar items
            document.querySelectorAll('.sidebar ul li').forEach(li => li.classList.remove('active'));

            // ✅ Highlight the clicked menu item
            let activeMenuItem = document.querySelector(`.sidebar ul li[data-section="${sectionId}"]`);
            if (activeMenuItem) {
                activeMenuItem.classList.add('active');
            }
        }

        $(document).ready(function() {
            // Function to load complaint history
            function loadComplaintHistory() {
                $("#complaintHistory").load("complaint_history.php");
            }

            loadComplaintHistory(); // Load history on page load

            // Submit complaint via AJAX
            $("#complaintForm").submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "submit_complaint.php",
                    type: "POST",
                    data: $("#complaintForm").serialize(),
                    dataType: "json",
                    success: function(response) {
                        console.log(response); // Log response
                        alert(response.message);
                        if (response.status === "success") {
                            $("#complaintForm")[0].reset();
                            loadComplaintHistory();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error, xhr.responseText);
                    }
                });

            });
        });
    </script>
</body>

</html>