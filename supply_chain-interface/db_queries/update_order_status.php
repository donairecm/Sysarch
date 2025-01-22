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

// Retrieve the JSON payload
$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input['sc_order_id'], $input['new_status'], $input['employee_id'])) {
    echo json_encode(["success" => false, "error" => "Invalid input."]);
    exit();
}

// Reversing transformations for sc_order_id and handled_by
$sc_order_id = str_replace('SCO-', '', $input['sc_order_id']); // Remove 'SCO-' prefix
$new_status = strtolower(str_replace(['Pending', 'On process', 'In transit'], ['pending', 'on_process', 'in_transit'], $input['new_status']));

// Validate the order and current user permissions
$sqlCheck = "SELECT handled_by, status FROM supply_chain_orders WHERE sc_order_id = ?";
$stmt = $conn->prepare($sqlCheck);
$stmt->bind_param("i", $sc_order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "error" => "Order not found."]);
    $stmt->close();
    $conn->close();
    exit();
}

$order = $result->fetch_assoc();

// Ensure only the correct user can update non-pending orders
if ($order['status'] !== 'pending' && $order['handled_by'] != $employee_id) {
    echo json_encode(["success" => false, "error" => "You are not authorized to update this order."]);
    $stmt->close();
    $conn->close();
    exit();
}

// Update the order in the supply_chain_orders table
$sqlUpdate = "UPDATE supply_chain_orders 
              SET status = ?, 
                  handled_by = IF(handled_by IS NULL OR handled_by = '', ?, handled_by), 
                  accepted_on = IF(accepted_on IS NULL OR accepted_on = '', NOW(), accepted_on)
              WHERE sc_order_id = ?";
$stmtUpdate = $conn->prepare($sqlUpdate);
$stmtUpdate->bind_param("sii", $new_status, $employee_id, $sc_order_id);

if (!$stmtUpdate->execute()) {
    echo json_encode(["success" => false, "error" => "Failed to update order status: " . $stmtUpdate->error]);
    $stmtUpdate->close();
    $conn->close();
    exit();
}

// Log the action in the user_activities table
$activityType = "supply_chain";
$details = match ($new_status) {
    "on_process" => "Accepted SCO-" . sprintf("%03d", $sc_order_id),
    "in_transit" => "SCO-" . sprintf("%03d", $sc_order_id) . " out for delivery",
    "completed" => "SCO-" . sprintf("%03d", $sc_order_id) . " delivered",
    default => "Updated SCO-" . sprintf("%03d", $sc_order_id)
};

$sqlLog = "INSERT INTO user_activities (performed_by, activity_type, details, reference_id, date_of_activity)
           VALUES (?, ?, ?, ?, NOW())";
$stmtLog = $conn->prepare($sqlLog);

if (!$stmtLog) {
    echo json_encode(["success" => false, "error" => "Failed to prepare activity log query: " . $conn->error]);
    $conn->close();
    exit();
}

$stmtLog->bind_param("isss", $employee_id, $activityType, $details, $sc_order_id);

if (!$stmtLog->execute()) {
    echo json_encode(["success" => false, "error" => "Failed to log activity: " . $stmtLog->error]);
    $stmtLog->close();
    $conn->close();
    exit();
}

// Success response
echo json_encode(["success" => true, "message" => "Order status updated successfully."]);

// Close statements and connection
$stmtUpdate->close();
$stmtLog->close();
$conn->close();
?>
