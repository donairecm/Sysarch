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

// SQL query to fetch all products along with total_units_sold and supplier_name
$sql = "SELECT p.product_id, p.product_name, p.price, p.quantity, p.reorder_point, p.reorder_cost, p.product_status, p.last_restocked, p.stock_location, p.created_on, p.supplier_id, p.total_units_sold, s.supplier_name 
        FROM products p
        LEFT JOIN suppliers s ON p.supplier_id = s.supplier_id";
$result = $conn->query($sql);

if ($result === false) {
    echo json_encode(["error" => "Query error: " . $conn->error]);
    exit();
}

$products = [];
while ($row = $result->fetch_assoc()) {
    // Format product_id as PRD-000
    $row['product_id'] = sprintf('PRD-%03d', $row['product_id']);

    // Format created_on as "Jan 1, 2020 | 7:01am"
    $row['created_on'] = date("M j, Y | g:ia", strtotime($row['created_on']));

    // Format last_restocked as "Jan 1, 2020 | 7:01am"
    if (!empty($row['last_restocked'])) {
        $row['last_restocked'] = date("M j, Y | g:ia", strtotime($row['last_restocked']));
    } else {
        $row['last_restocked'] = null; // Handle cases where last_restocked might be empty
    }

    // Format price and reorder_cost with a peso sign and two decimal places
    $row['price'] = sprintf('₱%.2f', $row['price']);
    $row['reorder_cost'] = sprintf('₱%.2f', $row['reorder_cost']);

    $products[] = $row;
}

echo json_encode($products);

$conn->close();
?>
