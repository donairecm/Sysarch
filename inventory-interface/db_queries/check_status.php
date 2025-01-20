<?php
header('Content-Type: application/json');

// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'bestaluminumsalescorps_db';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['error' => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Get input data
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['product_id'])) {
    echo json_encode(['error' => "Invalid input data."]);
    exit;
}

$product_id = $data['product_id'];

// Remove the prefix (e.g., "PRD-") from the product ID
$product_id_without_prefix = intval(str_replace('PRD-', '', $product_id));

// Query to get the latest request_id for the given product_id from reorder_requests
$sql = "SELECT request_id FROM reorder_requests WHERE product_id = ? ORDER BY date_of_request DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id_without_prefix);
$stmt->execute();
$stmt->bind_result($request_id);
$stmt->fetch();
$stmt->close();

// Check if a request_id was found
if (!$request_id) {
    echo json_encode(['status' => 'no_request_found']);
    $conn->close();
    exit;
}

// Query to get the status from supply_chain_orders using the found request_id
$sql = "SELECT status FROM supply_chain_orders WHERE related_id = ? AND source = 'inventory_reorder'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$stmt->bind_result($status);
$stmt->fetch();
$stmt->close();

$conn->close();

// Output the status
echo json_encode(['status' => $status]);
?>
