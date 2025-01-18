<?php
header("Content-Type: application/json");

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

$product_id_with_prefix = $data["product_id"] ?? null;
$updates = $data["updates"] ?? null;

if (!$product_id_with_prefix || !$updates) {
    echo json_encode(["success" => false, "error" => "Invalid input."]);
    exit();
}

// Remove the "PRD-" prefix to get the numeric product_id
$product_id = (int)preg_replace('/^PRD-/', '', $product_id_with_prefix);

// Build the SQL update query dynamically
$updateFields = [];
foreach ($updates as $field => $value) {
    $updateFields[] = "`$field` = '" . $conn->real_escape_string($value) . "'";
}
$updateQuery = "UPDATE products SET " . implode(", ", $updateFields) . " WHERE product_id = $product_id";

// Execute the query
if ($conn->query($updateQuery) === TRUE) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Error updating record: " . $conn->error]);
}

// Close the connection
$conn->close();
?>
