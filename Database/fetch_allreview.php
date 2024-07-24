<?php

/* header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "waltzer");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Fetch all products
$product_sql = "SELECT * FROM products";
$product_result = $conn->query($product_sql);

$products = [];
if ($product_result->num_rows > 0) {
    while ($product_row = $product_result->fetch_assoc()) {
        // Fetch reviews for each product
        $reviews_sql = "SELECT * FROM review WHERE productId = " . $product_row['Id'];
        $reviews_result = $conn->query($reviews_sql);

        $review_user_sql = "SELECT * FROM user_review JOIN user ON user_review.UserId = user.Id WHERE ProductId = " . $product_row['Id'];
        $query_result = $conn->query($review_user_sql);

        $reviews = [];
        $userReviews = [];
        
        if ($reviews_result->num_rows > 0) {
            while ($review_row = $reviews_result->fetch_assoc()) {
                $reviews[] = $review_row;
            }
        }
        
        else if ($query_result->num_rows > 0) {
            while ($user_row = $query_result->fetch_assoc()) {
                $userReviews[] = $user_row;
            }
        }
        $product_row['userReviews'] = $userReviews;
        $product_row['reviews'] = $reviews;
        
        $products[] = $product_row;
        
       
    }
}

echo json_encode($products);

$conn->close(); */
?>

<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "appuser", "waltzerW@312#", "waltzer");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$products = [];

if ($id > 0) {
    // Fetch the product details
    $product_sql = "SELECT * FROM products";
    $product_result = $conn->query($product_sql);

    if ($product_result->num_rows > 0) {
        $product_row = $product_result->fetch_assoc();

        // Fetch reviews for the product
        $reviews_sql = "SELECT user_review.*, user.* 
                        FROM user_review 
                        JOIN user ON user_review.UserId = user.Id 
                        WHERE ProductId = '$id'";
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

        $product_row['reviews'] = $reviews;
        $product_row['adminReviews'] = $adminReviews;

        $products[] = $product_row; // Wrap the product in an array
    } else {
        echo json_encode(["error" => "Product not found"]);
        exit;
    }
} else {
    // Fetch all products
    $product_sql = "SELECT * FROM products";
    $product_result = $conn->query($product_sql);

    if ($product_result->num_rows > 0) {
        while ($product_row = $product_result->fetch_assoc()) {
            // Fetch reviews for each product
            $reviews_sql = "SELECT user_review.*, user.* 
                            FROM user_review 
                            JOIN user ON user_review.UserId = user.Id 
                            WHERE ProductId = " . $product_row['Id'];
            $admin_review = "SELECT * FROM review WHERE productId = " . $product_row['Id'];
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

            $product_row['reviews'] = $reviews;
            $product_row['adminReviews'] = $adminReviews;
            $products[] = $product_row;
        }
    }
}

echo json_encode($products);

$conn->close();

?>
