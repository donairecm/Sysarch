<?php
require_once 'db_connection.php';

try {
    $sql = "
        SET FOREIGN_KEY_CHECKS = 0;
        DELETE FROM order_item;
        DELETE FROM sales_orders;
        DELETE FROM customers;
        DELETE FROM inventory_movements;
        DELETE FROM reorder_requests;
        DELETE FROM supply_chain_orders;
        DELETE FROM user_activities;
        DELETE FROM process_state;
        DELETE FROM counters;
        SET FOREIGN_KEY_CHECKS = 1;
    ";

    // Execute each statement individually for better error handling
    $statements = explode(";", $sql);
    foreach ($statements as $statement) {
        $trimmedStatement = trim($statement);
        if (!empty($trimmedStatement)) {
            if (!$conn->query($trimmedStatement)) {
                throw new Exception("Error executing query: {$conn->error}");
            }
        }
    }

    echo "Tables cleared successfully";
} catch (Exception $e) {
    http_response_code(500); // Set an error status code
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
