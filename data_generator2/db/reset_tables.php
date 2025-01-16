<?php
require_once 'db_connection.php';

$tables = [
    'customers',
    'inventory_movements',
    'order_items',
    'order_item_sequence',
    'reorder_requests',
    'sales_orders',
    'supply_chain_orders',
    'user_activities',
    'progress_log',
    'products_history'
];

// Truncate other tables
foreach ($tables as $table) {
    $query = "TRUNCATE TABLE $table";
    if (!$conn->query($query)) {
        http_response_code(500);
        echo "Error resetting table $table: " . $conn->error;
        $conn->close();
        exit;
    }
}

// Reset total_units_sold and total_revenue in the products table
$resetProductsQuery = "UPDATE products SET total_units_sold = 0, total_revenue = 0";
if (!$conn->query($resetProductsQuery)) {
    http_response_code(500);
    echo "Error resetting products table: " . $conn->error;
    $conn->close();
    exit;
}

echo "All tables reset successfully, and products table updated.";
$conn->close();
?>
