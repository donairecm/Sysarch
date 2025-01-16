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

// Query to retrieve today's inventory movements with movement_id
$sql = "SELECT movement_id, product_id, quantity, movement_type AS activity, date_of_movement 
        FROM inventory_movements 
        WHERE DATE(date_of_movement) = CURDATE()";

$result = $conn->query($sql);

if ($result === false) {
    echo json_encode(['error' => "Query error: " . $conn->error]);
    $conn->close();
    exit;
}

// Fetch all results as an associative array
$data = $result->fetch_all(MYSQLI_ASSOC);

// Add prefixes and format the IDs
foreach ($data as &$row) {
    $row['movement_id'] = sprintf('MOV-%03d', $row['movement_id']); // Format as MOV-001
    $row['product_id'] = sprintf('PRD-%03d', $row['product_id']);   // Format as PRD-001
}

// Close connection
$conn->close();

// Output data as JSON
echo json_encode($data);
?>
