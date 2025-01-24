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

try {
    // Get the latest reorder request ID for the product
    $query = "SELECT request_id FROM reorder_requests WHERE product_id = ? AND completed_on IS NULL ORDER BY request_id DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $product_id_without_prefix);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["error" => "No reorder requests found for this product."]);
        $stmt->close();
        $conn->close();
        exit();
    }

    $row = $result->fetch_assoc();
    $latest_request_id = $row['request_id'];
    $stmt->close();

    // Check the status in the supply_chain_orders table
    $query = "SELECT status FROM supply_chain_orders WHERE request_id = ? ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $latest_request_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["error" => "No supply chain orders found for the latest reorder request."]);
        $stmt->close();
        $conn->close();
        exit();
    }

    $row = $result->fetch_assoc();
    $status = $row['status'];
    $stmt->close();

    // Validate the status
    if ($status === 'completed' || $status === 'cancelled') {
        echo json_encode(["allow" => true, "message" => "Operation allowed."]);
    } else {
        echo json_encode(["allow" => false, "message" => "Operation not allowed due to pending supply chain status."]);
    }

} catch (Exception $e) {
    echo json_encode(["error" => "An error occurred: " . $e->getMessage()]);
}

// Close the database connection
$conn->close();
?>
