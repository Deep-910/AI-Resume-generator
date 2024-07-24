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

// Check if the request is a POST request with files
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['img1']) && isset($_FILES['img2']) && isset($_FILES['img3']) && isset($_FILES['img4'])) {
   // $email       =  $_POST['email'] ?? '';
    $pname            =  $_POST['pname'] ?? '';
    $category         =  $_POST['category'] ?? '';
    $sku              =  $_POST['sku'] ?? '';
    $brand            =  $_POST['brand'] ?? '';
    $color            =  $_POST['color'] ?? '';
    $description      =  $_POST['description'] ?? '';
    $weight           =  $_POST['weight'] ?? '';
    $length           =  $_POST['length'] ?? '';
    $breadth          =  $_POST['breadth'] ?? '';
    $height           =  $_POST['height'] ?? '';
    $price            =  $_POST['price'] ?? '';
    $discount         =  $_POST['discount'] ?? ''; 
    $rate             =  $_POST['rate'] ?? '';
    $size             =  $_POST['size'] ?? '';

    $img1        =  $_FILES['img1']['name'] ?? '';
    $img2        =  $_FILES['img2']['name'] ?? '';
    $img3        =  $_FILES['img3']['name'] ?? '';
    $img4        =  $_FILES['img4']['name'] ?? '';

    $tmp1        =  $_FILES['img1']['tmp_name'] ?? '';
    $tmp2        =  $_FILES['img2']['tmp_name'] ?? '';
    $tmp3        =  $_FILES['img3']['tmp_name'] ?? '';
    $tmp4        =  $_FILES['img4']['tmp_name'] ?? '';

    $folder      =  $_SERVER['DOCUMENT_ROOT'].'/waltzify_copy/Backend/Database/Products/';
    $result      = "";
    
  //  if($email == "admin_ecommerce@gmail.com" || $email == "admin_smitha@gmail.com")
  //  {
    $upload1 = move_uploaded_file($tmp1, $folder . $img1);
    $upload2 = move_uploaded_file($tmp2, $folder . $img2);
    $upload3 = move_uploaded_file($tmp3, $folder . $img3);
    $upload4 = move_uploaded_file($tmp4, $folder . $img4);

    if ($upload1 && $upload2 && $upload3 && $upload4) {
        $query = "INSERT INTO products (pname,SKU, category, brand,color, description, img1, img2, img3, img4, weight,length,breadth,height, size, p_price,discount,p_rate) VALUES ( ?, ?, ?, ?, ?, ?, ?,?, ?, ?,?, ?,?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssssssssssssii",  $pname,$sku, $category, $brand,$color, $description, $img1, $img2, $img3, $img4,$weight,$length,$breadth,$height,$size ,$price, $discount,$rate);
        $res = $stmt->execute();

        if ($res) {
            $result = "Registered Successfully!";
        } else {
            $result = "Not Submitted, Please try again!";
        }
        $stmt->close();
    } else {
        $result = "File upload failed";
    }
   // }
    

    $conn->close();
    echo json_encode([["result" => $result]]);
} else {
    echo json_encode([["result" => "Invalid input"]]);
}
