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

// Query to retrieve rows from supply_chain_orders where source is inventory_reorder and status is not completed
$sql = "SELECT status, related_id
        FROM supply_chain_orders 
        WHERE source = 'inventory_reorder' AND status != 'completed'";

$result = $conn->query($sql);

if ($result === false) {
    echo json_encode(['error' => "Query error: " . $conn->error]);
    $conn->close();
    exit;
}

// Prepare the array to hold the final data
$data = [];

// Fetch rows and use related_id to get product_id, quantity, and date_of_request from reorder_requests
while ($row = $result->fetch_assoc()) {
    $related_id = $row['related_id'];
    
    $reorderQuery = "SELECT product_id, quantity, date_of_request 
                     FROM reorder_requests 
                     WHERE request_id = $related_id";

    $reorderResult = $conn->query($reorderQuery);

    if ($reorderResult === false) {
        echo json_encode(['error' => "Query error: " . $conn->error]);
        $conn->close();
        exit;
    }

    while ($reorderRow = $reorderResult->fetch_assoc()) {
        $data[] = [
            'status' => $row['status'],
            'related_id' => sprintf('ORD-%03d', $related_id), // Format as ORD-001
            'product_id' => sprintf('PRD-%03d', $reorderRow['product_id']), // Format as PRD-001
            'quantity' => $reorderRow['quantity'],
            'date_of_request' => $reorderRow['date_of_request'] // Make sure this is included
        ];
    }
}

// Close connection
$conn->close();

// Output data as JSON
echo json_encode($data);
?>
