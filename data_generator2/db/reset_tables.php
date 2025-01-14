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
    'progress_log'
];

foreach ($tables as $table) {
    $query = "TRUNCATE TABLE $table";
    if (!$conn->query($query)) {
        http_response_code(500);
        echo "Error resetting table $table: " . $conn->error;
        $conn->close();
        exit;
    }
}

echo "All tables reset successfully.";
$conn->close();
?>
