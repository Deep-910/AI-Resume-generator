<?php
error_reporting(0);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header('Content-Type: application/json');

$conn = new mysqli("localhost", "appuser", "waltzerW@312#", "waltzer");

if ($conn === false) {
    die("ERROR: Couldn't connect" . mysqli_connect_error());
}

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case "DELETE":
        $review_id = $_GET['Id'];
        // Escape the review_id to prevent SQL injection
        $review_id = mysqli_real_escape_string($conn, $review_id);

        $query = "DELETE FROM user_review WHERE ReviewId = $review_id";
        if (mysqli_query($conn, $query)) {
            echo json_encode(["success" => "Review deleted successfully"]);
        } else {
            echo json_encode(["error" => "Error deleting Review: " . mysqli_error($conn)]);
        }
        break;
}

mysqli_close($conn);
