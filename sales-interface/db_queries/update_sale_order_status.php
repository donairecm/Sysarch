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
