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
function getStartOfMonth($currentDate) {
    return date("Y-m-01", strtotime($currentDate));
}

// Helper function to get the range for the same day last month
function getLastMonthRange($currentDate) {
    $lastMonthDate = date("Y-m-d", strtotime("-1 month", strtotime($currentDate)));
    $startOfLastMonth = date("Y-m-01", strtotime($lastMonthDate));
    $sameDayLastMonth = date("Y-m-d H:i:s", strtotime("-1 month", strtotime($currentDate)));
    return [$startOfLastMonth, $sameDayLastMonth];
}

// Get the current date and time
$currentDate = date("Y-m-d H:i:s");
$startOfMonth = getStartOfMonth($currentDate);
list($startOfLastMonth, $endOfLastMonth) = getLastMonthRange($currentDate);

// Function to reconstruct inventory state at a given date
function getInventoryAtDate($targetDate, $conn) {
    $query = "
        SELECT 
            p.product_id, 
            p.price,
            p.quantity - COALESCE(SUM(CASE WHEN im.date_of_movement <= '$targetDate' AND im.movement_type = 'sale' THEN im.quantity ELSE 0 END), 0)
            + COALESCE(SUM(CASE WHEN im.date_of_movement <= '$targetDate' AND im.movement_type = 'restock' THEN im.quantity ELSE 0 END), 0) AS adjusted_quantity
        FROM 
            products p
        LEFT JOIN 
            inventory_movements im ON p.product_id = im.product_id
        GROUP BY 
            p.product_id
    ";

    $result = $conn->query($query);
    $inventoryValue = 0;

    while ($row = $result->fetch_assoc()) {
        $inventoryValue += $row['adjusted_quantity'] * $row['price'];
    }

    return $inventoryValue;
}

function calculatePurchases($startDate, $endDate, $conn) {
    $query = "
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

    $result = $conn->query($query);
    $purchases = 0;

    while ($row = $result->fetch_assoc()) {
        $purchases += $row['quantity'] * $row['reorder_cost'];
    }

    return $purchases;
}

function calculateInventoryTurnover($startDate, $endDate, $conn) {
    // Get Beginning Inventory at the start date
    $beginningInventory = getInventoryAtDate($startDate, $conn);

    // Get Purchases during the date range
    $purchases = calculatePurchases($startDate, $endDate, $conn);

    // Get Ending Inventory at the end date
    $endingInventory = getInventoryAtDate($endDate, $conn);

    // Calculate Average Inventory, COGS, and Turnover
    $averageInventory = ($beginningInventory + $endingInventory) / 2;
    $cogs = $beginningInventory + $purchases - $endingInventory;
    $inventoryTurnover = $averageInventory > 0 ? $cogs / $averageInventory : 0;

    return $inventoryTurnover;
}

// Calculate turnovers for the current and last month
$currentMonthTurnover = calculateInventoryTurnover($startOfMonth, $currentDate, $conn);
$lastMonthTurnover = calculateInventoryTurnover($startOfLastMonth, $endOfLastMonth, $conn);

// Close the database connection
$conn->close();

// Output as JSON
header('Content-Type: application/json');
echo json_encode([
    'current_inventory_turnover' => $currentMonthTurnover,
    'last_inventory_turnover' => $lastMonthTurnover,
]);
?>
