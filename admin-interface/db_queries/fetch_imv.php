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

// Query to fetch data from inventory_movements table along with product_name from products table
$query = "SELECT 
            im.product_id, 
            p.product_name, 
            im.quantity, 
            im.movement_type, 
            im.date_of_movement, 
            im.reference_id 
          FROM inventory_movements im
          JOIN products p ON im.product_id = p.product_id";

$result = $conn->query($query);

// Check if query executed successfully
if ($result === false) {
    echo json_encode(["error" => "Failed to execute query: " . $conn->error]);
    $conn->close();
    exit;
}

// Fetch all rows as an associative array
$inventoryMovements = [];
while ($row = $result->fetch_assoc()) {
    $inventoryMovements[] = $row;
}

// Return the data as JSON
echo json_encode(["inventory_movements" => $inventoryMovements]);

// Close the connection
$conn->close();
?>
