<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$conn = new mysqli("localhost", "appuser", "waltzerW@312#", "waltzer");

if ($conn->connect_error) {
    echo json_encode(["result" => "Database connection failed"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_GET['id'] ?? '';
    $cname = $_POST['categoryName'] ?? '';
    $img = $_FILES['image']['name'] ?? '';
    $tmp = $_FILES['image']['tmp_name'] ?? '';
    $result = "";

    if (empty($id)) {
        echo json_encode(["result" => "Category ID is required"]);
        exit();
    }

    $folder = $_SERVER['DOCUMENT_ROOT'] . '/waltzify_copy/Backend/Database/Category/';

    // Check if the category name or image is provided
    if (!empty($cname) || !empty($img)) {
        $updateFields = [];

        if (!empty($cname)) {
            $updateFields[] = "cname = '$cname'";
        }

        if (!empty($img)) {
            if (move_uploaded_file($tmp, $folder . $img)) {
                $updateFields[] = "image = '$img'";
            } else {
                echo json_encode(["result" => "File upload failed"]);
                exit();
            }
        }

        $updateQuery = "UPDATE category SET " . implode(', ', $updateFields) . " WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $result = "Category Updated Successfully!";
        } else {
            $result = "Error updating category";
        }

        $stmt->close();
    } else {
        $result = "No fields to update";
    }

    $conn->close();
    echo json_encode(["result" => $result]);
} else {
    echo json_encode(["result" => "Invalid input"]);
}
