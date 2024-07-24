<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit();
}

// Database connection
$conn = new mysqli("localhost", "appuser", "waltzerW@312#", "waltzer");

if ($conn->connect_error) {
    die(json_encode([["result" => "Connection failed: " . $conn->connect_error]]));
}

// Read input data
$data = json_decode(file_get_contents("php://input"), true);
$password = $data['password'] ?? '';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode([["result" => "Session expired. Please try again."]]);
    exit();
}

// Validate password
if (empty($password)) {
    echo json_encode([["result" => "All fields are Required!"]]);
    exit();
}

// Get the user's email from the session
$email = $_SESSION['email'];

// Update password in the database
$stmt = $conn->prepare("UPDATE user SET password = ? WHERE email = ?");
if ($stmt) {
    $stmt->bind_param("ss", $password, $email);
    if ($stmt->execute()) {
        echo json_encode([["result" => "Password updated successfully!"]]);
        session_unset();
    } else {
        echo json_encode([["result" => "Not Submitted, Please try again!"]]);
    }
    $stmt->close();
} else {
    echo json_encode([["result" => "Failed to prepare statement."]]);
}

$conn->close();
