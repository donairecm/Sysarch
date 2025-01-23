<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = ""; // Update as needed
$dbname = "bestaluminumsalescorps_db"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// SQL query to fetch only product_id and product_name
$sql = "SELECT product_id, product_name FROM products";
$result = $conn->query($sql);

if ($result === false) {
    echo json_encode(["error" => "Query error: " . $conn->error]);
    exit();
}

$products = [];
while ($row = $result->fetch_assoc()) {
    // Format product_id as PRD-000
    $row['product_id'] = sprintf('PRD-%03d', $row['product_id']);
    $products[] = $row;
}

echo json_encode($products);
$conn->close();
?>