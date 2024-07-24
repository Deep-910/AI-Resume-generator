<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$conn = new mysqli("localhost", "appuser", "waltzerW@312#", "waltzer");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* $sql = "SELECT * FROM products WHERE CURDATE() >= date ORDER BY Id DESC LIMIT 8 "; */
$sql = "SELECT 
    p.*, 
    IFNULL(AVG(ur.Rating), 0) as average_rating 
FROM 
    (SELECT 
        * 
     FROM 
        products 
     WHERE 
        CURDATE() >= date 
     ORDER BY 
        Id DESC 
     LIMIT 8) p
LEFT JOIN 
    user_review ur 
ON 
    p.Id = ur.ProductId 
GROUP BY 
    p.Id 
ORDER BY 
    p.Id DESC";


$result = $conn->query($sql);

$products = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close();
echo json_encode($products);
