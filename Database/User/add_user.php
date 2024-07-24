<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit();
}

$conn = new mysqli("localhost", "appuser", "waltzerW@312#", "waltzer");

if ($conn->connect_error) {
    echo json_encode([["result" => "Database connection failed: " . $conn->connect_error]]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gender = $_POST['gender'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    $query = "UPDATE user SET gender = ?, phone = ? WHERE Id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $gender, $phone, $id); // Note: 'ssi' indicates two strings and one integer

    if ($stmt->execute()) {
        $result = "Registered Successfully!";
    } else {
        $result = "Not Submitted, Please try again!";
    }

    $stmt->close();
    $conn->close();
    echo json_encode([["result" => $result]]);
} else {
    echo json_encode([["result" => "Invalid input"]]);
}
