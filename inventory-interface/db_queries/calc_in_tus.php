<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'bestaluminumsalescorps_db';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Sanitize inputs and prepare queries
$currentTotalUnitsSold = 0;
$sqlCurrent = "SELECT total_units_sold FROM products";
$resultCurrent = $conn->query($sqlCurrent);

if ($resultCurrent && $resultCurrent->num_rows > 0) {
    while ($row = $resultCurrent->fetch_assoc()) {
        $currentTotalUnitsSold += (int) $row['total_units_sold'];
    }
}

// Calculate last month's range
$currentDate = new DateTime('now');
$lastMonthEquivalentDay = (clone $currentDate)->modify('-1 month');
$lastMonthStart = $lastMonthEquivalentDay->format('Y-m-01');
$lastMonthDay = $lastMonthEquivalentDay->format('Y-m-d');
$prevMonthLastDate = (clone $lastMonthEquivalentDay)->modify('-1 day')->format('Y-m-t');

// Fetch total units sold from products_history table
$productsSoldLastMonth = [];
$sqlHistory = "SELECT product_id, total_units_sold FROM products_history WHERE snapshot_date = '$prevMonthLastDate'";
$resultHistory = $conn->query($sqlHistory);

if ($resultHistory && $resultHistory->num_rows > 0) {
    while ($row = $resultHistory->fetch_assoc()) {
        $productsSoldLastMonth[$row['product_id']] = (int) $row['total_units_sold'];
    }
}

// Fetch movements from inventory_movements table
$sqlMovements = "SELECT product_id, quantity FROM inventory_movements 
                 WHERE date_of_movement BETWEEN '$lastMonthStart' AND '$lastMonthDay' 
                 AND movement_type = 'sale'";
$resultMovements = $conn->query($sqlMovements);

if ($resultMovements && $resultMovements->num_rows > 0) {
    while ($row = $resultMovements->fetch_assoc()) {
        $productId = $row['product_id'];
        $quantity = (int) $row['quantity'];

        if (isset($productsSoldLastMonth[$productId])) {
            $productsSoldLastMonth[$productId] += $quantity;
        } else {
            $productsSoldLastMonth[$productId] = $quantity;
        }
    }
}

$lastMonthsTotalUnitsSold = array_sum($productsSoldLastMonth);

// Output JSON response
$response = [
    "current_total_units_sold" => $currentTotalUnitsSold,
    "last_months_total_units_sold" => $lastMonthsTotalUnitsSold,
    "last_month_time_range" => [
        "start_date" => $lastMonthStart,
        "end_date" => $lastMonthDay
    ]
];

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
