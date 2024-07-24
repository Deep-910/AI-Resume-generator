<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = 'localhost';
$dbname = 'waltzer';
$username = 'appuser';
$password = 'waltzerW@312#';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Get categoryId from query parameter
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (empty($id)) {
    echo json_encode(['error' => 'Invalid category ID']);
    exit;
}

// Fetch category
try {
    // Log the categoryId for debugging
    error_log("Fetching category for Id: " . $id);

    $stmt = $pdo->prepare("SELECT * FROM category WHERE category_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $category = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch single category

    if ($category) {
        echo json_encode($category);
    } else {
        echo json_encode(['error' => 'No category found for this id']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to fetch category: ' . $e->getMessage()]);
}
