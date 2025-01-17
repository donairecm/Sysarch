<?php
header('Content-Type: application/json');

// Database connection details
$host = 'localhost';
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password
$database = 'bestaluminumsalescorps_db';

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// SQL queries to fetch data with JOIN
$inventoryMovementsQuery = "
    SELECT 
        im.movement_id, 
        im.product_id, 
        p.product_name, 
        im.quantity, 
        im.date_of_movement
    FROM 
        inventory_movements im
    JOIN 
        products p ON im.product_id = p.product_id";

$reorderRequestsQuery = "
    SELECT 
        rr.request_id, 
        rr.product_id, 
        p.product_name, 
        rr.quantity, 
        rr.date_of_request, 
        rr.completed_on
    FROM 
        reorder_requests rr
    JOIN 
        products p ON rr.product_id = p.product_id";

// Helper function for date formatting
function formatDate($date) {
    return date("M j, Y", strtotime($date));
}

// Helper function for ID formatting
function formatId($prefix, $id) {
    $digitCount = max(3, strlen((string) $id)); // Ensure at least 3 digits
    return sprintf("%s-%0{$digitCount}d", $prefix, $id);
}

// Fetch data and build JSON response
$response = [
    "products" => [],
    "reorderrequests" => [] // Updated key to match JavaScript expectation
];

// Fetch inventory movements
if ($inventoryMovementsResult = $conn->query($inventoryMovementsQuery)) {
    while ($row = $inventoryMovementsResult->fetch_assoc()) {
        $row['movement_id'] = formatId('MOV', $row['movement_id']);
        $row['product_id'] = formatId('PRD', $row['product_id']);
        $row['date_of_movement'] = formatDate($row['date_of_movement']);
        $response["products"][] = $row;
    }
    $inventoryMovementsResult->free();
} else {
    $response["error"]["inventory_movements"] = $conn->error;
}

// Fetch reorder requests
if ($reorderRequestsResult = $conn->query($reorderRequestsQuery)) {
    while ($row = $reorderRequestsResult->fetch_assoc()) {
        $row['request_id'] = formatId('RRD', $row['request_id']);
        $row['product_id'] = formatId('PRD', $row['product_id']);
        $row['date_of_request'] = formatDate($row['date_of_request']);
        $row['completed_on'] = $row['completed_on'] ? formatDate($row['completed_on']) : null; // Handle NULL dates
        $response["reorderrequests"][] = $row; // Updated key to match JavaScript expectation
    }
    $reorderRequestsResult->free();
} else {
    $response["error"]["reorder_requests"] = $conn->error;
}

// Close the connection
$conn->close();

// Return the JSON response
echo json_encode($response);
?>
