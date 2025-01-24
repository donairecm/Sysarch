<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = ""; // Update as needed
$dbname = "bestaluminumsalescorps_db"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($data['sales_order_id'], $data['new_status'], $data['performed_by'], $data['details'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

$sales_order_id = intval($data['sales_order_id']);
$new_status = $data['new_status'];
$performed_by = intval($data['performed_by']);
$details = $data['details'];

// Get the current datetime
$date_of_activity = date('Y-m-d H:i:s');

try {
    $conn->begin_transaction();

    // Update the sales order status
    $updateOrderQuery = "UPDATE sales_orders SET status = ? WHERE sales_order_id = ?";
    $stmt = $conn->prepare($updateOrderQuery);
    $stmt->bind_param('si', $new_status, $sales_order_id);
    $stmt->execute();

    // Check if the new status is 'completed'
    if ($new_status === 'completed') {
        // Retrieve the order items for the given sales order
        $orderItemsQuery = "SELECT product_id, quantity FROM order_items WHERE sales_order_id = ?";
        $stmt = $conn->prepare($orderItemsQuery);
        $stmt->bind_param('i', $sales_order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Deduct quantities from products table, insert notifications, and populate inventory_movements table
        while ($row = $result->fetch_assoc()) {
            $product_id = intval($row['product_id']);
            $quantity = intval($row['quantity']);

            // Deduct quantity from products table
            $updateProductQuery = "UPDATE products SET quantity = quantity - ? WHERE product_id = ?";
            $stmt = $conn->prepare($updateProductQuery);
            $stmt->bind_param('ii', $quantity, $product_id);
            $stmt->execute();

            // Insert notification for inventory manager
            $notificationMessage = "Sale made on PRD-" . str_pad($product_id, 3, '0', STR_PAD_LEFT) . ". Quantity deducted $quantity.";
            $insertNotificationQuery = "INSERT INTO notifications (`for`, message, created_on) VALUES ('inventory_manager', ?, ?)";
            $stmt = $conn->prepare($insertNotificationQuery);
            $stmt->bind_param('ss', $notificationMessage, $date_of_activity);
            $stmt->execute();

            // Populate inventory_movements table
            $movementType = 'sale';
            $insertInventoryMovementQuery = "INSERT INTO inventory_movements (product_id, quantity, movement_type, date_of_movement, reference_id) 
                                             VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertInventoryMovementQuery);
            $stmt->bind_param('iissi', $product_id, $quantity, $movementType, $date_of_activity, $sales_order_id);
            $stmt->execute();
        }
    }

    // Insert into user_activities
    $insertActivityQuery = "INSERT INTO user_activities (performed_by, details, reference_id, date_of_activity)
                            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertActivityQuery);
    $stmt->bind_param('isis', $performed_by, $details, $sales_order_id, $date_of_activity);
    $stmt->execute();

    $conn->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
