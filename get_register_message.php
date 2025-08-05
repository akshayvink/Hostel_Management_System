<?php
session_start();

$response = ["message" => "", "type" => ""];

if (isset($_SESSION['register_success'])) {
    $response["message"] = $_SESSION['register_success'];
    $response["type"] = "success";
    unset($_SESSION['register_success']);
} elseif (isset($_SESSION['register_error'])) {
    $response["message"] = $_SESSION['register_error'];
    $response["type"] = "error";
    unset($_SESSION['register_error']);
}

echo json_encode($response);
?>
