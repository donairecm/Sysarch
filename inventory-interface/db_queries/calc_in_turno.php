<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'bestaluminumsalescorps_db';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Helper function to get the start of the current month
function getStartOfMonth() {
    return date("Y-m-01");
}

// Helper function to get the same day last month
function getLastMonthRange() {
    $currentDate = date("Y-m-d");
    $lastMonthDate = date("Y-m-d", strtotime("-1 month", strtotime($currentDate)));
    $startOfLastMonth = date("Y-m-01", strtotime($lastMonthDate));
    $sameDayLastMonth = date("Y-m-d", strtotime("-1 month", strtotime($currentDate)));
    return [$startOfLastMonth, $sameDayLastMonth];
}

// Get the current date
$currentDate = date("Y-m-d");
$startOfMonth = getStartOfMonth();
list($startOfLastMonth, $endOfLastMonth) = getLastMonthRange();

function calculateInventoryTurnover($startDate, $endDate, $conn) {
    // Calculate Beginning Inventory
    $beginningInventoryQuery = "
        SELECT 
            p.product_id, 
            p.price,
            p.quantity + COALESCE(SUM(CASE WHEN im.movement_type = 'sale' THEN im.quantity ELSE 0 END), 0)
            - COALESCE(SUM(CASE WHEN im.movement_type = 'restock' THEN im.quantity ELSE 0 END), 0) AS adjusted_quantity
        FROM 
            products p
        LEFT JOIN 
            inventory_movements im ON p.product_id = im.product_id
            AND im.date_of_movement BETWEEN '$startDate' AND '$endDate'
        GROUP BY 
            p.product_id
    ";

    $beginningInventoryResult = $conn->query($beginningInventoryQuery);
    $beginningInventory = 0;

    while ($row = $beginningInventoryResult->fetch_assoc()) {
        $beginningInventory += $row['adjusted_quantity'] * $row['price'];
    }

    $purchasesQuery = "
        SELECT 
            im.product_id, 
            im.quantity, 
            p.reorder_cost
        FROM 
            inventory_movements im
        INNER JOIN 
            products p ON im.product_id = p.product_id
        WHERE 
            im.movement_type = 'restock'
            AND im.date_of_movement BETWEEN '$startDate' AND '$endDate'
    ";

    $purchasesResult = $conn->query($purchasesQuery);
    $purchases = 0;

    while ($row = $purchasesResult->fetch_assoc()) {
        $purchases += $row['quantity'] * $row['reorder_cost'];
    }

    $endingInventoryQuery = "
        SELECT 
            SUM(p.quantity * p.price) AS total_ending_inventory
        FROM 
            products p
    ";

    $endingInventoryResult = $conn->query($endingInventoryQuery);
    $endingInventoryRow = $endingInventoryResult->fetch_assoc();
    $endingInventory = $endingInventoryRow['total_ending_inventory'];

    $averageInventory = ($beginningInventory + $endingInventory) / 2;
    $cogs = $beginningInventory + $purchases - $endingInventory;
    $inventoryTurnover = $averageInventory > 0 ? $cogs / $averageInventory : 0;

    return $inventoryTurnover;
}

$currentMonthTurnover = calculateInventoryTurnover($startOfMonth, $currentDate, $conn);
$lastMonthTurnover = calculateInventoryTurnover($startOfLastMonth, $endOfLastMonth, $conn);

$conn->close();

header('Content-Type: application/json');
echo json_encode([
    'current_inventory_turnover' => $currentMonthTurnover,
    'last_inventory_turnover' => $lastMonthTurnover
]);
?>
