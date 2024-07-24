<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$conn = new mysqli("localhost", "appuser", "waltzerW@312#", "waltzer");

if (mysqli_connect_error()) {
    echo json_encode(["result" => "Database connection failed"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $rating = $_POST['rating'] ?? '';
    $headline = $_POST['headline'] ?? '';
    $img = $_FILES['image']['name'] ?? '';
    $review = $_POST['review'] ?? '';
    $tmp = $_FILES['image']['tmp_name'] ?? '';
    
    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    $folder = $_SERVER['DOCUMENT_ROOT'].'/waltzify_copy/Backend/Database/User_Review/';
    $result = "";

    if (move_uploaded_file($tmp, $folder . $img)) {
        $query = "INSERT INTO user_review (UserId, ProductId, rating, heading, img, review) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiisss", $user_id, $id, $rating, $headline, $img, $review);
        $res = $stmt->execute();

        if ($res) {
            $result = "Review Added Successfully!";
        } else {
            $result = "Not Submitted, Please try again!";
        }
        $stmt->close();
    } else {
        $result = "File upload failed";
    }

    $conn->close();
    echo json_encode(["result" => $result]);
} else {
    echo json_encode(["result" => "Invalid input"]);
}
