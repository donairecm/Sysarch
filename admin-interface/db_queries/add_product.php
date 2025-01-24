<?php
header("Content-Type: application/json");
session_start(); // Start the session

// Ensure the user is logged in
if (!isset($_SESSION['employee_id'])) {
    echo json_encode(["success" => false, "error" => "User not authenticated."]);
    exit();
}

// Retrieve employee_id from the session
$employee_id_with_prefix = $_SESSION['employee_id'];

// Remove the prefix to get the numeric employee_id
$numeric_employee_id = (int)preg_replace('/^[A-Z]+-/', '', $employee_id_with_prefix);

// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // Update as needed
$dbname = "bestaluminumsalescorps_db"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Retrieve and decode JSON input
$data = json_decode(file_get_contents("php://input"), true);

$product_name = $data["product_name"] ?? null;
$quantity = $data["quantity"] ?? null;
$reorder_point = $data["reorder_point"] ?? null;
$price = $data["price"] ?? null;
$reorder_cost = $data["reorder_cost"] ?? null;
$stock_location = $data["stock_location"] ?? null;

if (!$product_name || !$quantity || !$reorder_point || !$price || !$reorder_cost || !$stock_location) {
    echo json_encode(["success" => false, "error" => "Invalid input."]);
    exit();
}

// Insert the product into the `products` table
$insertProductQuery = "
    INSERT INTO products (product_name, quantity, reorder_point, price, reorder_cost, stock_location, created_on)
    VALUES (
        '" . $conn->real_escape_string($product_name) . "',
        '" . $conn->real_escape_string($quantity) . "',
        '" . $conn->real_escape_string($reorder_point) . "',
        '" . $conn->real_escape_string($price) . "',
        '" . $conn->real_escape_string($reorder_cost) . "',
        '" . $conn->real_escape_string($stock_location) . "',
        NOW()
    )
";

if ($conn->query($insertProductQuery) === TRUE) {
    // Log the activity in `user_activities` table
    $activityDetails = "Added a new product";
    $activityQuery = "
        INSERT INTO user_activities (performed_by, activity_type, details, date_of_activity)
        VALUES (
            '" . $conn->real_escape_string($numeric_employee_id) . "',
            'inventory',
            '" . $conn->real_escape_string($activityDetails) . "',
            NOW()
        )
    ";
    if ($conn->query($activityQuery) === TRUE) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Error logging user activity: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Error adding product: " . $conn->error]);
}

// Close the connection
$conn->close();
?>
