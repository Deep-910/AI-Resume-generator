
<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header('Access-Control-Allow-Credentials: true');
header("Content-Type: application/json; charset=UTF-8");

session_start();
$conn = new mysqli("localhost", "root", "", "waltzer");

if ($conn->connect_error) {
    die(json_encode(["result" => "Connection failed: " . $conn->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$phone = $data['phone'] ?? '';

if (empty($email) && empty($phone)) {
    echo json_encode(["result" => "Invalid input"]);
    exit();
}

$sql = "SELECT email FROM user WHERE email = ? OR phone = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $phone);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userEmail = $result->fetch_assoc();
    $_SESSION['email'] = $userEmail['email'];
    echo json_encode(["result" => "User Verified"]);
} else {
    echo json_encode(["result" => "Email or Phone Not Exist!"]);
}

$stmt->close();
$conn->close(); */
?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header('Access-Control-Allow-Credentials: true');
header("Content-Type: application/json; charset=UTF-8");

session_start();
$conn = new mysqli("localhost", "appuser", "waltzerW@312#", "waltzer");

if ($conn->connect_error) {
    die(json_encode(["result" => "Connection failed: " . $conn->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$phone = $data['phone'] ?? '';

if (empty($email) && empty($phone)) {
    echo json_encode(["result" => "Invalid input"]);
    exit();
}

$sql = "SELECT email FROM user WHERE (email = ? AND ? <> '') OR (phone = ? AND ? <> '')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $email, $email, $phone, $phone);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userEmail = $result->fetch_assoc();
    $_SESSION['email'] = $userEmail['email'];
    echo json_encode(["result" => "User Verified"]);
} else {
    echo json_encode(["result" => "Email or Phone Not Exist!"]);
}

$stmt->close();
$conn->close();
?>
