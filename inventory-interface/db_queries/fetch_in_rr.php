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

// Query to retrieve rows from supply_chain_orders where source is 'inventory_reorder' and status is not 'completed'
$sql = "SELECT status, related_id, handled_by 
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

// Fetch rows and use related_id to get data from reorder_requests
while ($row = $result->fetch_assoc()) {
    // Remove the prefix from related_id
    $related_id = intval(str_replace('RRD-', '', $row['related_id']));

    // Query to get request_id, requested_by, product_id, quantity, date_of_request from reorder_requests
    $reorderQuery = "SELECT request_id, requested_by, product_id, quantity, date_of_request 
                     FROM reorder_requests 
                     WHERE request_id = $related_id";

    $reorderResult = $conn->query($reorderQuery);

    if ($reorderResult === false) {
        echo json_encode(['error' => "Query error: " . $conn->error]);
        $conn->close();
        exit;
    }

    // Process each matching row
    while ($reorderRow = $reorderResult->fetch_assoc()) {
        // Remove the prefix from product_id
        $product_id = intval(str_replace('PRD-', '', $reorderRow['product_id']));

        // Query to get product_name from products
        $productQuery = "SELECT product_name 
                         FROM products 
                         WHERE product_id = " . $reorderRow['product_id'];

        $productResult = $conn->query($productQuery);

        if ($productResult === false) {
            echo json_encode(['error' => "Query error: " . $conn->error]);
            $conn->close();
            exit;
        }

        $productRow = $productResult->fetch_assoc();

        // Determine handled_by value
        $handled_by = $row['handled_by'] ? sprintf('SCM-%03d', intval(str_replace('SCM-', '', $row['handled_by']))) : 'Pending Acceptance';

        // Re-apply prefixes before adding to the data array
        $data[] = [
            'request_id' => sprintf('RRD-%03d', $related_id), // Re-add prefix to request_id
            'requested_by' => sprintf('IVM-%03d', intval(str_replace('IVM-', '', $reorderRow['requested_by']))), // Format prefix for requested_by
            'product_id' => sprintf('PRD-%03d', $product_id), // Re-add prefix to product_id
            'product_name' => $productRow['product_name'], // Product name from products
            'quantity' => $reorderRow['quantity'], // Quantity from reorder_requests
            'date_of_request' => date("M j, Y", strtotime($reorderRow['date_of_request'])), // Format date_of_request
            'handled_by' => $handled_by, // Handled by from supply_chain_orders or Pending Acceptance
            'status' => $row['status'] // Status from supply_chain_orders
        ];
    }
}

// Close connection
$conn->close();

// Output data as JSON
echo json_encode($data);
?>
