<?php
session_start();
include 'db_connection.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $full_name = trim($_POST['full_name']);
    $father_name = trim($_POST['father_name']);
    $mother_name = trim($_POST['mother_name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $dob = trim($_POST['dob']);
    $hosteler_type = $_POST['hosteler_type'];
    $password_raw = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Password match check
    if ($password_raw !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Hash the password
    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    // Check for existing user
    $stmt = $conn->prepare("SELECT * FROM hostelers WHERE phone = ? OR email = ?");
    $stmt->bind_param("ss", $phone, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors[] = "Phone or Email already registered.";
    }

    // File upload directory setup
    $upload_dir = __DIR__ . "/uploads/";
    if (!file_exists($upload_dir)) {
        if (!mkdir($upload_dir, 0755, true)) {
            $errors[] = "Failed to create upload directory.";
        }
    }

    // File upload handler function
    function handle_file_upload($file, &$errors, $name, $upload_dir) {
        $tmp = $file['tmp_name'];
        $filename = $file['name'];
        $size = $file['size'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, ['pdf', 'jpg', 'jpeg'])) {
            $errors[] = "$name must be a PDF or JPG file.";
            return null;
        }
        if ($size > 2 * 1024 * 1024) {
            $errors[] = "$name must be under 2MB.";
            return null;
        }

        $new_name = uniqid($name . "_") . '.' . $ext;
        $destination = $upload_dir . $new_name;

        if (!move_uploaded_file($tmp, $destination)) {
            $errors[] = "Failed to upload $name.";
            return null;
        }

        return "uploads/" . $new_name; // Relative path for DB
    }

    // Process Aadhar card upload
    $aadhar_path = handle_file_upload($_FILES['aadhar'], $errors, "Aadhar_Card", $upload_dir);

    // Process ID card upload if present
    $id_card_path = null;
    if (isset($_FILES['id_card']) && $_FILES['id_card']['size'] > 0) {
        $id_card_path = handle_file_upload($_FILES['id_card'], $errors, "ID_Card", $upload_dir);
    }

    // If no errors, insert into database
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO hostelers (full_name, father_name, mother_name, phone, email, dob, hosteler_type, id_card, aadhar, password)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $full_name, $father_name, $mother_name, $phone, $email, $dob, $hosteler_type, $id_card_path, $aadhar_path, $password);

        if ($stmt->execute()) {
            $_SESSION['register_success'] = "✅ Successfully registered! Redirecting to login...";
        } else {
            $_SESSION['register_error'] = "Database error: " . $conn->error;
        }
    } else {
        $_SESSION['register_error'] = implode("<br>", $errors);
    }

    header("Location: hostelerregister.html");
    exit();
}
?>
