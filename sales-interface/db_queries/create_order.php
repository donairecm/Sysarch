<?php
header('Content-Type: application/json');
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Update as needed
$dbname = "bestaluminumsalescorps_db";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

// Check if the user is logged in
if (!isset($_SESSION['employee_id'])) {
    echo json_encode(["success" => false, "error" => "User not logged in."]);
    exit();
}

$employee_id = $_SESSION['employee_id'];
$orderItems = json_decode(file_get_contents('php://input'), true)['orderItems'];

// Insert a new row in sales_orders
$insertOrderSQL = "INSERT INTO sales_orders (status, created_on, managed_by) VALUES ('on_process', NOW(), ?)";
$stmt = $conn->prepare($insertOrderSQL);
$stmt->bind_param('i', $employee_id);
$stmt->execute();
$sales_order_id = $stmt->insert_id; // Get the ID of the newly inserted sales order

// Prepare to insert order items
$insertItemSQL = "INSERT INTO order_items (order_item_id, sales_order_id, product_id, quantity, total_price, created_on) VALUES (?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($insertItemSQL);

$order_item_id = 1;
$total_order_amount = 0;

foreach ($orderItems as $item) {
    $product_id = preg_replace('/^\D+/', '', $item['productId']); // Remove prefix
    $quantity = intval($item['quantity']);

    // Get product price
    $productPriceSQL = "SELECT price FROM products WHERE product_id = ?";
    $productStmt = $conn->prepare($productPriceSQL);
    $productStmt->bind_param('i', $product_id);
    $productStmt->execute();
    $productStmt->bind_result($price);
    $productStmt->fetch();
    $productStmt->close();

    $total_price = $price * $quantity;
    $total_order_amount += $total_price;

    $stmt->bind_param('iiiii', $order_item_id, $sales_order_id, $product_id, $quantity, $total_price);
    $stmt->execute();

    $order_item_id++;
}

// Update the total amount in sales_orders
$updateOrderSQL = "UPDATE sales_orders SET total_amount = ? WHERE sales_order_id = ?";
$updateStmt = $conn->prepare($updateOrderSQL);
$updateStmt->bind_param('di', $total_order_amount, $sales_order_id);
$updateStmt->execute();
$updateStmt->close();

// Insert into user_activities
$insertActivitySQL = "INSERT INTO user_activities (performed_by, activity_type, details, reference_id, date_of_activity) VALUES (?, 'sales', 'Created a sale order', ?, NOW())";
$activityStmt = $conn->prepare($insertActivitySQL);
$activityStmt->bind_param('ii', $employee_id, $sales_order_id);
$activityStmt->execute();
$activityStmt->close();

echo json_encode(['success' => true]);

$conn->close();
?>
