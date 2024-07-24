<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$conn = new mysqli("localhost", "appuser", "waltzerW@312#", "waltzer");

if (mysqli_connect_error()) {
    echo json_encode([["result" => "Database connection failed"]]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'] ?? '';
    $number = $_POST['number'] ?? '';
    $pincode = $_POST['pincode'] ?? '';
    $state = $_POST['state'] ?? '';
    $addressOne = $_POST['addressOne'] ?? '';
    $addressTwo = $_POST['addressTwo'] ?? '';
    $addressThree = $_POST['addressThree'] ?? '';
    $city = $_POST['city'] ?? '';
    $landmark = $_POST['landmark'] ?? '';

    $result = "";

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Debugging: Print received data
    error_log("Received Data: " . print_r($_POST, true));

    $query = "INSERT INTO useraddress (UserId,FullName, Number, Pincode, State, Address1, Address2, Address3, City, Landmark) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssssssss", $id, $fullname, $number, $pincode, $state, $addressOne, $addressTwo, $addressThree, $city, $landmark);
    $res = $stmt->execute();

    if ($res) {
        $result = "Registered Successfully!";
    } else {
        $result = "Not Submitted, Please try again!";
    }
    $stmt->close();

    $conn->close();
    echo json_encode([["result" => $result]]);
} else {
    echo json_encode([["result" => "Invalid request method"]]);
}
?>

<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$conn = new mysqli("localhost", "root", "", "waltzer");

if (mysqli_connect_error()) {
    echo json_encode([["result" => "Database connection failed"]]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $fullname           =  $_POST['fullname'] ?? '';
    $number             =  $_POST['number'] ?? '';
    $pincode            =  $_POST['pincode'] ?? '';
    $state              =  $_POST['state'] ?? '';
    $addressOne         =  $_POST['addressOne'] ?? '';
    $addressTwo         =  $_POST['addressTwo'] ?? ''; 
    $addressThree       =  $_POST['addressThree'] ?? '';
    $city               =  $_POST['city'] ?? '';
    $landmark           =  $_POST['landmark'] ?? '';

    $result      = "";
   
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    $query = "INSERT INTO useraddress (UserId, FullName, Number, Pincode, State, Address1, Address2, Address3, City, Landmark) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssssssss", $id, $fullname, $number, $pincode, $state, $addressOne, $addressTwo, $addressThree, $city, $landmark);
    $res = $stmt->execute();

    if ($res) {
        $result = "Registered Successfully!";
    } else {
        $result = "Not Submitted, Please try again!";
    }
    $stmt->close();

    $conn->close();
    echo json_encode([["result" => $result]]);
} else {
    echo json_encode([["result" => "Invalid input"]]);
} */
?>

