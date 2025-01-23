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

// Set date range for filtering
$startDate = "2025-01-01";
$endDate = date("Y-m-d");

// Fetch sales orders within the date range
$sqlSalesOrders = "
    SELECT sales_order_id, total_amount, managed_by, created_on, status
    FROM sales_orders
    WHERE created_on BETWEEN ? AND ?
    ORDER BY FIELD(status, 'pending') DESC, created_on DESC
";
$stmtSalesOrders = $conn->prepare($sqlSalesOrders);
$stmtSalesOrders->bind_param("ss", $startDate, $endDate);
$stmtSalesOrders->execute();
$resultSalesOrders = $stmtSalesOrders->get_result();

$salesOrders = [];
while ($order = $resultSalesOrders->fetch_assoc()) {
    // Format the fields
    $formattedOrderId = "SID-" . str_pad($order['sales_order_id'], 3, '0', STR_PAD_LEFT); // Add SID prefix
    $formattedManagedBy = "SSM-" . str_pad($order['managed_by'], 3, '0', STR_PAD_LEFT); // Add SSM prefix
    $formattedTotalAmount = "₱" . number_format($order['total_amount'], 2); // Format amount with peso sign and commas
    $formattedCreatedOn = date("M j Y | g:ia", strtotime($order['created_on'])); // Format the date

    $salesOrders[$order['sales_order_id']] = [
        'sales_order_id' => $formattedOrderId,
        'total_amount' => $formattedTotalAmount,
        'managed_by' => $formattedManagedBy,
        'created_on' => $formattedCreatedOn,
        'status' => $order['status'],
        'order_items' => [], // Placeholder for associated order items
    ];
}
$stmtSalesOrders->close();

// Fetch order items and associate them with sales orders, including product name
$sqlOrderItems = "
    SELECT oi.order_item_id, oi.sales_order_id, oi.product_id, p.product_name, oi.quantity, oi.total_price
    FROM order_items oi
    LEFT JOIN products p ON oi.product_id = p.product_id
    WHERE oi.sales_order_id IN (" . implode(",", array_keys($salesOrders)) . ")
";
$resultOrderItems = $conn->query($sqlOrderItems);
if ($resultOrderItems === false) {
    echo json_encode(["error" => "Query error: " . $conn->error]);
    exit();
}
while ($item = $resultOrderItems->fetch_assoc()) {
    $salesOrderId = $item['sales_order_id'];
    if (isset($salesOrders[$salesOrderId])) {
        $salesOrders[$salesOrderId]['order_items'][] = [
            'order_item_id' => $item['order_item_id'],
            'product_id' => $item['product_id'],
            'product_name' => $item['product_name'], // Include the product name
            'quantity' => $item['quantity'],
            'total_price' => $item['total_price'],
        ];
    }
}

// Output JSON response
echo json_encode(array_values($salesOrders));
$conn->close();
?>