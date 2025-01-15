<?php
$host = 'localhost';              // Server host
$user = 'root';                   // Default username for XAMPP
$password = '';                   // Default password for XAMPP (leave empty)
$database = 'bestaluminumsalescorps_db';  // Your database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";

// Check if the connection is established
if (!$conn) {
    echo json_encode(['error' => 'Database connection not established.']);
    exit;
}

try {
    // Query to calculate the total inventory value
    $query = "SELECT SUM(price * quantity) AS inventory_value FROM products";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $inventoryValue = $row['inventory_value'] ?? 0;
        echo json_encode(['inventoryValue' => $inventoryValue]);
    } else {
        echo json_encode(['error' => 'Failed to execute query.']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
