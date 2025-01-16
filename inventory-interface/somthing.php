<?php
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

try {
    // Fetch all rows from the products table
    $query = "SELECT product_id, reorder_point FROM products";
    $result = $conn->query($query);

    if (!$result) {
        throw new Exception("Failed to fetch product data.");
    }

    // Initialize category arrays
    $overstocked = [];
    $critical = [];
    $low = [];
    $outOfStock = [];
    $normal = [];

    // Process each product
    while ($row = $result->fetch_assoc()) {
        $productId = $row['product_id'];
        $reorderPoint = (int)$row['reorder_point'];

        // Categorize and set quantity
        if (rand(1, 100) <= 20) { // 20% chance for Overstocked
            $quantity = rand(100, 150);
            $overstocked[] = ['product_id' => $productId, 'quantity' => $quantity];
        } elseif (rand(1, 100) <= 12) { // 12% chance for Out of Stock
            $quantity = 0;
            $outOfStock[] = ['product_id' => $productId, 'quantity' => $quantity];
        } elseif (rand(1, 100) <= 7) { // 7% chance for Critical
            $quantity = rand(1, $reorderPoint);
            $critical[] = ['product_id' => $productId, 'quantity' => $quantity];
        } elseif (rand(1, 100) <= 5) { // 5% chance for Low
            $quantity = rand(max(1, $reorderPoint - 20), $reorderPoint - 1);
            $low[] = ['product_id' => $productId, 'quantity' => $quantity];
        } else { // Remaining go to Normal
            $quantity = rand(21, 99); // Example range for normal quantities
            $normal[] = ['product_id' => $productId, 'quantity' => $quantity];
        }
    }

    // Prepare an update query for each category
    $updateQueries = [];

    foreach ([$overstocked, $outOfStock, $critical, $low, $normal] as $category) {
        foreach ($category as $product) {
            $updateQueries[] = sprintf(
                "UPDATE products SET quantity = %d WHERE product_id = %d;",
                $product['quantity'],
                $product['product_id']
            );
        }
    }

    // Execute each update query
    foreach ($updateQueries as $query) {
        if (!$conn->query($query)) {
            throw new Exception("Failed to update product data: " . $conn->error);
        }
    }

    // Return categorization results
    echo json_encode([
        'overstocked' => count($overstocked),
        'critical' => count($critical),
        'low' => count($low),
        'outOfStock' => count($outOfStock),
        'normal' => count($normal)
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    $conn->close();
}
