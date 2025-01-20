<?php
header("Content-Type: application/json");

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Update as needed
$dbname = "bestaluminumsalescorps_db";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

// Get input data
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['product_id'])) {
    echo json_encode(["error" => "Invalid input data."]);
    exit();
}

$product_id = $data['product_id'];

// Remove the prefix (e.g., "PRD-") from the product ID
$product_id_without_prefix = preg_replace('/^PRD-/', '', $product_id);

// Check if a reorder request for this product ID exists
$query = "SELECT 1 FROM reorder_requests WHERE product_id = ? AND completed_on IS NULL";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $product_id_without_prefix);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["exists" => true]);
} else {
    echo json_encode(["exists" => false]);
}

$stmt->close();
$conn->close();
?>
