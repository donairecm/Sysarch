<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'bestaluminumsalescorps_db';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the date range
$startDate = '2025-01-01';
$endDate = '2025-01-17';

// Query to get total quantity sold for each product within the date range
$sql = "
    SELECT product_id, SUM(quantity) AS total_quantity
    FROM order_items
    WHERE created_on BETWEEN ? AND ?
    GROUP BY product_id
";

// Prepare and bind parameters
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();

// Update the total_units_sold column in the products table
$updateSql = "UPDATE products SET total_units_sold = ? WHERE product_id = ?";
$updateStmt = $conn->prepare($updateSql);

// Loop through the results and update the products table
while ($row = $result->fetch_assoc()) {
    $productId = $row['product_id'];
    $totalQuantity = $row['total_quantity'];
    
    // Bind the parameters and execute the update statement
    $updateStmt->bind_param('ii', $totalQuantity, $productId);
    $updateStmt->execute();
}

// Close the statements and connection
$stmt->close();
$updateStmt->close();
$conn->close();

echo "Total units sold updated successfully.";


