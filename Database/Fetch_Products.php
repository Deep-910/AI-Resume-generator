
<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "appuser", "waltzerW@312#", "waltzer");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;


if ($id > 0) {
    // Fetch the product details
    $product_sql = "SELECT * FROM products WHERE Id = '$id'";
    $product_result = $conn->query($product_sql);

    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();

        // Fetch reviews for the product
        $reviews_sql = "SELECT * FROM user_review JOIN user ON user_review.UserId = user.Id WHERE ProductId = '$id'";
        $admin_review = "SELECT * FROM review WHERE productId = '$id'";
        $reviews_result = $conn->query($reviews_sql);
        $admin_result = $conn->query($admin_review);

        $reviews = [];
        if ($reviews_result->num_rows > 0) {
            while ($review_row = $reviews_result->fetch_assoc()) {
                $reviews[] = $review_row;
            }
        }
        $adminReviews = [];
        if ($admin_result->num_rows > 0) {

            while ($admin_row = $admin_result->fetch_assoc()) {
                $adminReviews[] = $admin_row;
            }
        }

        $product['reviews'] = $reviews;
        $product['adminReviews'] = $adminReviews;

        // Fetch related products based on the same category and brand
        $category = $product['category'];
        $name    = $product['pname'];

        /* $related_sql = "SELECT * FROM products WHERE category = '$category' AND Id != '$id' LIMIT 8"; */
        $related_sql = "SELECT 
    p.*, 
    IFNULL(AVG(ur.Rating), 0) as average_rating 
FROM 
    products p 
LEFT JOIN 
    user_review ur 
ON 
    p.Id = ur.ProductId 
WHERE 
    p.category = '$category' 
    AND p.Id != '$id' 
GROUP BY 
    p.Id 
LIMIT 8";

        $related_result = $conn->query($related_sql);

        $related_products = [];
        if ($related_result->num_rows > 0) {
            while ($related_row = $related_result->fetch_assoc()) {
                $related_products[] = $related_row;
            }
        }
        $product['related_products'] = $related_products;

        // Fetch color products

        $productName = $product['pname'];
        $color = $product['color'];
        $color_sql = "SELECT * FROM products WHERE pname = '$productName' AND color != '$color' AND color != '' AND Id != '$id'";
        $color_result = $conn->query($color_sql);
        $color_products = [];

        if ($color_result->num_rows > 0) {
            while ($color_row = $color_result->fetch_assoc()) {
                $color_products[] = $color_row;
            }
        }
        $product['color_products'] = $color_products;
        //Fetch Sizes and Prices of particular product

        $size_sql = "SELECT * FROM products WHERE pname = '$productName'";
        $size_result = $conn->query($size_sql);
        $size_products = [];

        if ($size_result->num_rows > 0) {
            while ($size_row = $size_result->fetch_assoc()) {
                $size_products[] = $size_row;
            }
        }
        $product['size_products'] = $size_products;


        echo json_encode($product);
    } else {
        echo json_encode(["error" => "Product not found"]);
    }
} else {
    echo json_encode(["error" => "Invalid product ID"]);
}

$conn->close();

?>