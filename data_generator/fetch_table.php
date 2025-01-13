<?php
require_once 'db_connection.php';

if (isset($_GET['table'])) {
    $allowedTables = [
        'sales_orders', 'order_item', 'reorder_requests', 
        'products', 'customers', 'supply_chain_orders', 
        'inventory_movements', 'user_activities', 'users' // Added 'users'
    ];

    $table = $_GET['table'];

    if (in_array($table, $allowedTables)) {
        $query = "SELECT * FROM $table";
        $result = $conn->query($query);

        if ($result) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode($data);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to fetch data"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Invalid table name"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "No table specified"]);
}
?>
