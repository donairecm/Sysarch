<?php
require_once 'db_connection.php';

if (isset($_GET['table'])) {
    $table = $_GET['table'];

    // Sanitize table name to prevent SQL injection
    $allowedTables = ['sales_orders', 'order_items', 'supply_chain_orders', 'reorder_requests', 'inventory_movements', 'user_activities', 'products', 'customers', 'users', 'products_history'];
    if (!in_array($table, $allowedTables)) {
        echo '<p>Invalid table name.</p>';
        exit;
    }

    // Query the table data without a limit
    $query = "SELECT * FROM $table"; // Removed LIMIT clause
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        echo '<table><thead><tr>';
        $fields = $result->fetch_fields();
        foreach ($fields as $field) {
            echo '<th>' . htmlspecialchars($field->name) . '</th>';
        }
        echo '</tr></thead><tbody>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            foreach ($row as $value) {
                echo '<td>' . htmlspecialchars($value) . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>No data available for this table.</p>';
    }

    $conn->close();
} else {
    echo '<p>No table specified.</p>';
}
?>
