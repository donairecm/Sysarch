<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'bestaluminumsalescorps_db';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

// Helper function to get the start and end dates of ranges
function getDateRanges() {
    $currentDate = new DateTime();
    $startOfCurrentMonth = $currentDate->format('Y-m-01');
    $currentDateStr = $currentDate->format('Y-m-d');

    $lastMonthDate = (new DateTime())->modify('-1 month')->format('Y-m-d');
    $startOfLastMonth = (new DateTime($lastMonthDate))->modify('first day of this month')->format('Y-m-d');

    return [
        'currentDate' => ['start' => $startOfCurrentMonth, 'end' => $currentDateStr],
        'lastMonthDate' => ['start' => $startOfLastMonth, 'end' => $lastMonthDate]
    ];
}

// Function to calculate inventory turnover
function calculateInventoryTurnover($conn, $startDate, $endDate, $snapshotDate) {
    // Fetch Beginning Inventory
    $beginningInventoryQuery = "
        SELECT SUM(quantity * price) AS beginning_inventory 
        FROM products_history 
        WHERE snapshot_date = '$snapshotDate'";
    $beginningInventoryResult = $conn->query($beginningInventoryQuery);
    $beginningInventory = $beginningInventoryResult->fetch_assoc()['beginning_inventory'] ?? 0;

    // Fetch Purchases during the period
    $purchasesQuery = "
        SELECT im.quantity, im.product_id, ph.reorder_cost 
        FROM inventory_movements im
        JOIN products_history ph ON im.product_id = ph.product_id
        WHERE im.movement_type = 'restock' 
          AND im.date_of_movement BETWEEN '$startDate' AND '$endDate'
          AND ph.snapshot_date = '$snapshotDate'";
    $purchasesResult = $conn->query($purchasesQuery);

    $purchases = 0;
    while ($row = $purchasesResult->fetch_assoc()) {
        $purchases += $row['quantity'] * $row['reorder_cost'];
    }

    // Fetch Ending Inventory
    $inventoryMovementsQuery = "
        SELECT im.product_id, im.movement_type, im.quantity, ph.quantity AS base_quantity, ph.price 
        FROM inventory_movements im
        JOIN products_history ph ON im.product_id = ph.product_id
        WHERE im.date_of_movement BETWEEN '$startDate' AND '$endDate'
          AND ph.snapshot_date = '$snapshotDate'";
    $inventoryMovementsResult = $conn->query($inventoryMovementsQuery);

    $endingInventoryData = [];
    while ($row = $inventoryMovementsResult->fetch_assoc()) {
        $productId = $row['product_id'];
        $currentQuantity = $row['base_quantity'];

        if ($row['movement_type'] == 'sale') {
            $currentQuantity -= $row['quantity'];
        } elseif ($row['movement_type'] == 'restock') {
            $currentQuantity += $row['quantity'];
        }

        $endingInventoryData[$productId] = $currentQuantity * $row['price'];
    }
    $endingInventory = array_sum($endingInventoryData);

    // Calculate COGS
    $cogs = $beginningInventory + $purchases - $endingInventory;

    // Calculate Average Inventory
    $averageInventory = ($beginningInventory + $endingInventory) / 2;

    // Calculate Inventory Turnover
    $inventoryTurnover = ($averageInventory > 0) ? $cogs / $averageInventory : 0;

    return [
        'inventoryTurnover' => $inventoryTurnover
    ];
}

// Main process
try {
    $dateRanges = getDateRanges();

    // Current Date Inventory Turnover
    $currentDateRange = $dateRanges['currentDate'];
    $currentSnapshotDate = (new DateTime($currentDateRange['start']))->modify('-1 day')->format('Y-m-d');
    $currentDateInventoryTurnover = calculateInventoryTurnover(
        $conn,
        $currentDateRange['start'],
        $currentDateRange['end'],
        $currentSnapshotDate
    );

    // Last Month Date Inventory Turnover
    $lastMonthEndDate = (new DateTime())->modify('-1 month')->format('Y-m-d');
    $lastMonthStart = (new DateTime($lastMonthEndDate))->modify('first day of this month')->format('Y-m-d');
    $lastMonthSnapshotDate = (new DateTime($lastMonthStart))->modify('-1 day')->format('Y-m-d');

    $lastMonthInventoryTurnover = calculateInventoryTurnover(
        $conn,
        $lastMonthStart,
        $lastMonthEndDate,
        $lastMonthSnapshotDate
    );

    // Return JSON Response
    echo json_encode([
        'current_inventory_turnover' => $currentDateInventoryTurnover['inventoryTurnover'],
        'last_inventory_turnover' => $lastMonthInventoryTurnover['inventoryTurnover']
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>
