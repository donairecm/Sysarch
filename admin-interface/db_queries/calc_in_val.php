<?php
$host = 'localhost'; // Server host
$user = 'root'; // Default username for XAMPP
$password = ''; // Default password for XAMPP (leave empty)
$database = 'bestaluminumsalescorps_db'; // Your database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

try {
    // Helper function to calculate this month's inventory value
    function calculateThisMonthInventory($conn) {
        $currentDate = new DateTime();
        $firstDayOfMonth = $currentDate->format('Y-m-01');
        $currentDay = $currentDate->format('Y-m-d');

        // Query to calculate inventory value for this month from the products table
        $query = "
            SELECT SUM(price * quantity) AS inventory_value 
            FROM products";
        $result = $conn->query($query);

        if ($result) {
            $row = $result->fetch_assoc();
            return $row['inventory_value'] ?? 0;
        } else {
            throw new Exception("Failed to execute query for this month's inventory value.");
        }
    }

    // Helper function to calculate last month's inventory value
    function calculateLastMonthInventory($conn) {
        $currentDate = new DateTime();
        $lastMonthStart = $currentDate->modify('-1 month')->format('Y-m-01');
        $lastMonthEnd = (new DateTime($lastMonthStart))->modify('+15 days')->format('Y-m-d'); // Last month's 16th day range
        $lastMonthSnapshotDate = (new DateTime($lastMonthStart))->modify('-1 day')->format('Y-m-d'); // Last day of the previous month

        // Step 1: Fetch the base data from the products_history table
        $snapshotQuery = "
            SELECT product_id, quantity, price 
            FROM products_history 
            WHERE snapshot_date = '$lastMonthSnapshotDate'";
        $snapshotResult = $conn->query($snapshotQuery);

        if (!$snapshotResult || $snapshotResult->num_rows === 0) {
            throw new Exception("No snapshot data found for date $lastMonthSnapshotDate.");
        }

        $inventoryData = [];
        while ($row = $snapshotResult->fetch_assoc()) {
            $inventoryData[$row['product_id']] = [
                'quantity' => $row['quantity'],
                'price' => $row['price']
            ];
        }

        // Step 2: Apply inventory movements within the date range
        $movementsQuery = "
            SELECT product_id, movement_type, quantity 
            FROM inventory_movements 
            WHERE date_of_movement BETWEEN '$lastMonthStart' AND '$lastMonthEnd'";
        $movementsResult = $conn->query($movementsQuery);

        if ($movementsResult) {
            while ($row = $movementsResult->fetch_assoc()) {
                $productId = $row['product_id'];
                $movementType = $row['movement_type'];
                $quantity = $row['quantity'];

                if (isset($inventoryData[$productId])) {
                    if ($movementType === 'sale') {
                        $inventoryData[$productId]['quantity'] -= $quantity;
                    } elseif ($movementType === 'restock') {
                        $inventoryData[$productId]['quantity'] += $quantity;
                    }
                }
            }
        } else {
            throw new Exception("Failed to fetch inventory movements for the date range.");
        }

        // Step 3: Calculate the final inventory value
        $lastMonthInventoryValue = 0;
        foreach ($inventoryData as $product) {
            $lastMonthInventoryValue += $product['quantity'] * $product['price'];
        }

        return $lastMonthInventoryValue;
    }

    // Calculate inventory values
    $thisMonthInventoryValue = calculateThisMonthInventory($conn);
    $lastMonthInventoryValue = calculateLastMonthInventory($conn);

    // Return the results as JSON
    echo json_encode([
        'thisMonthInventoryValue' => $thisMonthInventoryValue,
        'lastMonthInventoryValue' => $lastMonthInventoryValue
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>
