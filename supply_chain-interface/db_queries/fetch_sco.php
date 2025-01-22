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

// SQL query to fetch required rows from supply_chain_orders and join with reorder_requests for product details
$sql = "
    SELECT 
        sco.sc_order_id, 
        sco.source, 
        sco.status, 
        sco.handled_by, 
        sco.accepted_on, 
        sco.delivered_on, 
        sco.details, 
        rr.product_id, 
        p.product_name, 
        rr.quantity AS requested_quantity
    FROM 
        supply_chain_orders sco
    LEFT JOIN 
        reorder_requests rr ON sco.related_id = rr.request_id
    LEFT JOIN 
        products p ON rr.product_id = p.product_id
    WHERE 
        sco.status NOT IN ('completed', 'cancelled')
    ORDER BY sco.sc_order_id DESC
";

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


    // Format accepted_on and delivered_on or display "...." if null
    $row['accepted_on'] = !empty($row['accepted_on']) 
        ? date('M j, Y | g:ia', strtotime($row['accepted_on'])) 
        : '....';

    $row['delivered_on'] = !empty($row['delivered_on']) 
        ? date('M j, Y | g:ia', strtotime($row['delivered_on'])) 
        : '....';

    // Include only necessary fields for display
    $supplyChainOrders[] = [
        'sc_order_id' => $row['sc_order_id'],
        'source' => $row['source'],
        'status' => $row['status'],
        'handled_by' => $row['handled_by'],
        'accepted_on' => $row['accepted_on'],
        'delivered_on' => $row['delivered_on'],
        'product_id' => $row['product_id'] ?? '....',
        'product_name' => $row['product_name'] ?? '....',
        'quantity' => $row['requested_quantity'] ?? '....',
    ];
}

// Output JSON response
echo json_encode($supplyChainOrders);

$conn->close();
?>
