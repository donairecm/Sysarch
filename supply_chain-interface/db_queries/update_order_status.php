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

if (!$input || !isset($input['sc_order_id'], $input['new_status'])) {
    echo json_encode(["success" => false, "error" => "Invalid input."]);
    exit();
}

// Reversing transformations for sc_order_id and handled_by
$sc_order_id = str_replace('SCO-', '', $input['sc_order_id']); // Remove 'SCO-' prefix
$new_status = strtolower(str_replace(['Pending', 'On process', 'In transit', 'Completed'], ['pending', 'on_process', 'in_transit', 'completed'], $input['new_status']));

// Fetch the order
$sqlCheck = "SELECT handled_by, status, related_id FROM supply_chain_orders WHERE sc_order_id = ?";
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
$related_id = $order['related_id'];

// Ensure only the correct user can update non-pending orders
if ($order['status'] !== 'pending' && $order['handled_by'] != $employee_id) {
    echo json_encode(["success" => false, "error" => "You are not authorized to update this order."]);
    $stmt->close();
    $conn->close();
    exit();
}

// Update the order in the supply_chain_orders table
$delivered_on = ($new_status === 'completed') ? date('Y-m-d H:i:s') : null;

$sqlUpdate = "UPDATE supply_chain_orders 
              SET status = ?, 
                  handled_by = IF(handled_by IS NULL OR handled_by = '', ?, handled_by), 
                  accepted_on = IF(accepted_on IS NULL OR accepted_on = '', NOW(), accepted_on),
                  delivered_on = ?
              WHERE sc_order_id = ?";
$stmtUpdate = $conn->prepare($sqlUpdate);
$stmtUpdate->bind_param("sisi", $new_status, $employee_id, $delivered_on, $sc_order_id);

if (!$stmtUpdate->execute()) {
    echo json_encode(["success" => false, "error" => "Failed to update order status: " . $stmtUpdate->error]);
    $stmtUpdate->close();
    $conn->close();
    exit();
}

// Handle completed status-specific updates
if ($new_status === 'completed') {
    // Update reorder_requests
    $sqlReorder = "SELECT product_id, quantity FROM reorder_requests WHERE request_id = ?";
    $stmtReorder = $conn->prepare($sqlReorder);
    $stmtReorder->bind_param("i", $related_id);
    $stmtReorder->execute();
    $resultReorder = $stmtReorder->get_result();

    if ($resultReorder->num_rows > 0) {
        $reorder = $resultReorder->fetch_assoc();
        $product_id = $reorder['product_id'];
        $quantity = $reorder['quantity'];

        // Update completed_on in reorder_requests
        $sqlUpdateReorder = "UPDATE reorder_requests SET completed_on = NOW() WHERE request_id = ?";
        $stmtUpdateReorder = $conn->prepare($sqlUpdateReorder);
        $stmtUpdateReorder->bind_param("i", $related_id);
        $stmtUpdateReorder->execute();

        // Update product quantity in products table
        $sqlUpdateProduct = "UPDATE products SET quantity = quantity + ? WHERE product_id = ?";
        $stmtUpdateProduct = $conn->prepare($sqlUpdateProduct);
        $stmtUpdateProduct->bind_param("is", $quantity, $product_id);
        $stmtUpdateProduct->execute();

        // Insert notification for inventory manager with created_on
        $notificationMessage = sprintf("PRD-%03d reorder received. Quantity received %d.", $product_id, $quantity);
        $sqlNotification = "INSERT INTO notifications (`for`, message, created_on) VALUES ('inventory_manager', ?, NOW())";
        $stmtNotification = $conn->prepare($sqlNotification);
        $stmtNotification->bind_param("s", $notificationMessage);
        $stmtNotification->execute();

        // Insert into inventory_movements table
        $movementType = 'restock';
        $sqlInventoryMovement = "INSERT INTO inventory_movements (product_id, quantity, movement_type, date_of_movement, reference_id) 
                                 VALUES (?, ?, ?, NOW(), ?)";
        $stmtInventoryMovement = $conn->prepare($sqlInventoryMovement);
        $stmtInventoryMovement->bind_param("iisi", $product_id, $quantity, $movementType, $related_id);
        $stmtInventoryMovement->execute();
    }

    $stmtReorder->close();
}

// Log the action in the user_activities table
$activityType = "supply_chain";
$details = "SCO-" . sprintf("%03d", $sc_order_id) . " delivered";

$sqlLog = "INSERT INTO user_activities (performed_by, activity_type, details, reference_id, date_of_activity)
           VALUES (?, ?, ?, ?, NOW())";
$stmtLog = $conn->prepare($sqlLog);

if ($stmtLog) {
    $stmtLog->bind_param("isss", $employee_id, $activityType, $details, $sc_order_id);
    $stmtLog->execute();
}

// Success response with an alert message
$alertMessage = "Order status successfully updated to: " . ucfirst($new_status) . ".";
echo json_encode(["success" => true, "message" => "Order status updated successfully.", "alert" => $alertMessage]);

// Close connections
$stmtUpdate->close();
$stmtLog->close();
$conn->close();
?>
