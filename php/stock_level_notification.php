<?php
// Database Configuration
$host = 'localhost'; // Server host
$user = 'root'; // Username
$password = ''; // Password
$database = 'bestaluminumsalescorps_db'; // Your database name

// Create Database Connection
$conn = new mysqli($host, $user, $password, $database);

// Check Database Connection
if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

// Query to Fetch Product Data
$query = "SELECT product_id, quantity, reorder_point FROM products";
$result = $conn->query($query);

// Prepare SQL Statements
$updateStmt = $conn->prepare(
    "UPDATE products SET stock_level = ? WHERE product_id = ?"
);
if (!$updateStmt) {
    die(json_encode(['error' => "Statement preparation failed: " . $conn->error]));
}

$insertNotificationStmt = $conn->prepare(
    "INSERT INTO notifications (`for`, message, created_on) VALUES (?, ?, ?)"
);
if (!$insertNotificationStmt) {
    die(json_encode(['error' => "Statement preparation failed: " . $conn->error]));
}

$fetchLatestNotificationStmt = $conn->prepare(
    "SELECT message FROM notifications WHERE message LIKE ? ORDER BY created_on DESC LIMIT 1"
);
if (!$fetchLatestNotificationStmt) {
    die(json_encode(['error' => "Statement preparation failed: " . $conn->error]));
}

// Update Stock Level and Insert Notifications
while ($row = $result->fetch_assoc()) {
    $productId = $row['product_id'];
    $quantity = $row['quantity'];
    $reorderPoint = $row['reorder_point'];
    $formattedProductId = sprintf('PRD-%03d', $productId); // Format product ID

    // Determine Stock Level
    if ($quantity > 100) {
        $stockLevel = 'overstock';
        $message = "$formattedProductId is overstocked. Current quantity $quantity";
    } elseif ($quantity >= ($reorderPoint - 20) && $quantity < $reorderPoint) {
        $stockLevel = 'low_stock';
        $message = "$formattedProductId is low on stock. Current quantity $quantity";
    } elseif ($quantity <= $reorderPoint && $quantity > 0) {
        $stockLevel = 'critical_stock';
        $message = "$formattedProductId is critically low. Current quantity $quantity";
    } elseif ($quantity == 0) {
        $stockLevel = 'out_of_stock';
        $message = "$formattedProductId is out of stock. Current quantity $quantity";
    } elseif ($quantity == $reorderPoint) {
        $stockLevel = 'reorder_point';
        $message = "$formattedProductId status changed to reorder point. Current quantity $quantity";
    } else {
        $stockLevel = 'normal_stock';
        $message = "$formattedProductId status changed to normal. Current quantity $quantity";
    }

    // Update the stock level in the database
    $updateStmt->bind_param('si', $stockLevel, $productId);
    if (!$updateStmt->execute()) {
        die(json_encode(['error' => "Update failed: " . $updateStmt->error]));
    }

    // Check if the latest notification matches the current stock level
    $likeMessage = "$formattedProductId%";
    $fetchLatestNotificationStmt->bind_param('s', $likeMessage);
    $fetchLatestNotificationStmt->execute();
    $fetchLatestNotificationStmt->bind_result($latestMessage);
    $fetchLatestNotificationStmt->fetch();
    $fetchLatestNotificationStmt->free_result(); // Free result after fetching

    $shouldInsertNotification = true;

    if ($latestMessage) {
        // Check if the latest notification already matches the current stock level
        if (
            ($stockLevel === 'low_stock' && strpos($latestMessage, "is low on stock") !== false) ||
            ($stockLevel === 'out_of_stock' && strpos($latestMessage, "is out of stock") !== false) ||
            ($stockLevel === 'critical_stock' && strpos($latestMessage, "is critically low") !== false) ||
            ($stockLevel === 'overstock' && strpos($latestMessage, "is overstocked") !== false)
        ) {
            $shouldInsertNotification = false;
        }
    }

    if ($shouldInsertNotification && $stockLevel !== 'normal_stock') {
        $createdOn = date('Y-m-d H:i:s'); // Current timestamp
    
        // Insert notification for inventory_manager
        $recipient = 'inventory_manager';
        $insertNotificationStmt->bind_param('sss', $recipient, $message, $createdOn);
        if (!$insertNotificationStmt->execute()) {
            die(json_encode(['error' => "Insert failed for inventory_manager: " . $insertNotificationStmt->error]));
        }
    
        // Insert notification for admin
        $recipient = 'admin';
        $insertNotificationStmt->bind_param('sss', $recipient, $message, $createdOn);
        if (!$insertNotificationStmt->execute()) {
            die(json_encode(['error' => "Insert failed for admin: " . $insertNotificationStmt->error]));
        }
    }
    
}

// Close Statements
$updateStmt->close();
$insertNotificationStmt->close();
$fetchLatestNotificationStmt->close();

// Output Success Message
echo json_encode(['message' => 'Stock levels updated and notifications inserted successfully']);

// Close Database Connection
$conn->close();
?>
