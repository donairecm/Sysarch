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

// SQL query to fetch the required rows from supply_chain_orders while excluding completed and cancelled statuses
$sql = "SELECT sc_order_id, source, status, handled_by, accepted_on, delivered_on, details 
        FROM supply_chain_orders
        WHERE status NOT IN ('completed', 'cancelled')";
$result = $conn->query($sql);

if ($result === false) {
    echo json_encode(["error" => "Query error: " . $conn->error]);
    exit();
}

$supplyChainOrders = [];
while ($row = $result->fetch_assoc()) {
    // Format sc_order_id as SCO-000
    $row['sc_order_id'] = sprintf('SCO-%03d', $row['sc_order_id']);

    // Format source
    switch ($row['source']) {
        case 'inventory_reorder':
            $row['source'] = 'Reorder';
            break;
        case 'sales_order':
            $row['source'] = 'Delivery';
            break;
        default:
            $row['source'] = ucfirst($row['source']);
    }

    // Format status
    switch ($row['status']) {
        case 'pending':
            $row['status'] = 'Pending';
            break;
        case 'on_process':
            $row['status'] = 'On process';
            break;
        case 'in_transit':
            $row['status'] = 'In transit';
            break;
        default:
            $row['status'] = ucfirst($row['status']);
    }

    // Format handled_by with SCM-000 or display "...." if null
    $row['handled_by'] = !empty($row['handled_by']) 
        ? sprintf('SCM-%03d', $row['handled_by']) 
        : '....';

    // Include only necessary fields for display
    $supplyChainOrders[] = [
        'sc_order_id' => $row['sc_order_id'],
        'source' => $row['source'],
        'status' => $row['status'],
        'handled_by' => $row['handled_by'],
    ];
}

// Output JSON response
echo json_encode($supplyChainOrders);

$conn->close();
?>
