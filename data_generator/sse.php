<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

// Database connection
require_once 'db_connection.php';

// Function to send data to the client
function sendData($conn) {
    $query = "SELECT * FROM sales_orders";
    $result = $conn->query($query);

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo "data: " . json_encode($data) . "\n\n";
    flush();
}

// Send updates every 5 seconds (this can be adjusted as needed)
while (true) {
    sendData($conn);
    sleep(5);
}
?>
