<?php
$host = 'localhost'; // Server host
$user = 'root'; // Username
$password = ''; // Password
$database = 'bestaluminumsalescorps_db'; // Your database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

try {
    // Query to get products data
    $query = "SELECT quantity, reorder_point FROM products";
    $result = $conn->query($query);

    if (!$result) {
        throw new Exception("Failed to fetch product data.");
    }

    // Initialize counters for stock categories
    $overstocked = 0;
    $normal = 0;
    $low = 0;
    $critical = 0;
    $outOfStock = 0;

    // Process each row
    while ($row = $result->fetch_assoc()) {
        $quantity = (int)$row['quantity'];
        $reorderPoint = (int)$row['reorder_point'];

        if ($quantity > 100) {
            $overstocked++;
        } elseif ($quantity >= ($reorderPoint - 20) && $quantity < $reorderPoint) {
            $low++;
        } elseif ($quantity <= $reorderPoint && $quantity > 0) {
            $critical++;
        } elseif ($quantity == 0) {
            $outOfStock++;
        } else {
            $normal++;
        }
    }

    // Return the data as JSON
    echo json_encode([
        'overstocked' => $overstocked,
        'normal' => $normal,
        'low' => $low,
        'critical' => $critical,
        'outOfStock' => $outOfStock
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
