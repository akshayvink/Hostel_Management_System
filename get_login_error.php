<?php
session_start();

$response = ["error" => ""];

if (isset($_SESSION['login_error'])) {
    $response["error"] = $_SESSION['login_error'];
    unset($_SESSION['login_error']); // Clear error after sending
}

echo json_encode($response);
?>
