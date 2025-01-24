<?php
session_start();

header("Content-Type: application/json");

// Check if the user is logged in
if (!isset($_SESSION['employee_id'])) {
    echo json_encode(["success" => false, "error" => "User not logged in."]);
    exit;
}

// Retrieve employee_id from session and remove prefix
$employee_id_with_prefix = $_SESSION['employee_id'];
$employee_id = (int)preg_replace('/^[A-Z]+-/', '', $employee_id_with_prefix);

// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // Update as needed
$dbname = "bestaluminumsalescorps_db";

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Decode JSON input
$data = json_decode(file_get_contents("php://input"), true);
$request_id_prefixed = $data["request_id"] ?? null;
$product_id_prefixed = $data["product_id"] ?? null;

if (!$request_id_prefixed || !$product_id_prefixed) {
    echo json_encode(["success" => false, "error" => "Invalid input data."]);
    exit();
}

// Remove prefixes for internal operations
$request_id = (int)str_replace("RRD-", "", $request_id_prefixed);
$product_id = (int)str_replace("PRD-", "", $product_id_prefixed);

$current_datetime = date("Y-m-d H:i:s");

// Begin transaction
$conn->begin_transaction();

try {
    // Update the supply_chain_orders table
    $update_query = "UPDATE supply_chain_orders SET status = 'cancelled' WHERE related_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("i", $request_id);

    if (!$stmt->execute()) {
        throw new Exception("Failed to update supply_chain_orders: " . $stmt->error);
    }

    // Log the inventory activity
$activity_details = "Inventory updated: Cancelled reorder request {$request_id_prefixed} for product {$product_id_prefixed} by user {$employee_id_with_prefix}";
$activity_query = "
    INSERT INTO user_activities (performed_by, details, reference_id, date_of_activity) 
    VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($activity_query);
$stmt->bind_param("isis", $employee_id, $activity_details, $request_id, $current_datetime);

if (!$stmt->execute()) {
    throw new Exception("Failed to log inventory activity: " . $stmt->error);
}


    // Commit transaction
    $conn->commit();

    echo json_encode(["success" => true, "message" => "Reorder request successfully cancelled and logged."]);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();

    echo json_encode(["success" => false, "error" => $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}
?>
