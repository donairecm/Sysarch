<?php

// Global database connection
global $conn;
set_time_limit(0);

$startTime = microtime(true); // Store the start time in seconds since Unix epoch

ob_start();
echo "Current real-time: " . date('Y-m-d H:i:s');
ob_flush();
flush();

echo "Initializing script";
for ($i = 5; $i > 0; $i--) {
    echo "Starting in $i...";
    ob_flush();
    flush();
    sleep(1); // Pause for 1 second
}

// Function to log the last date in the database
function logCurrentDateToDB($currentDate)
{
    global $conn;

    $formattedDate = date('Y-m-d', $currentDate);
    $query = $conn->prepare("REPLACE INTO progress_log (id, last_logged_date) VALUES (1, ?)");
    $query->bind_param('s', $formattedDate);

    if (!$query->execute()) {
        die("Failed to log progress: " . $query->error);
    }

    echo "Progress saved for the last date: $formattedDate\n";
    ob_flush();
    flush();
}

// Function to get the last logged date from the database
function getSavedDateFromDB()
{
    global $conn;

    $query = "SELECT last_logged_date FROM progress_log WHERE id = 1";
    $result = $conn->query($query);

    if ($result && $row = $result->fetch_assoc()) {
        return strtotime($row['last_logged_date']);
    }

    return false;
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Helper function to generate a list of times between two hours
function generateTimeSlots($startHour, $endHour)
{
    $timeSlots = [];
    for ($hour = $startHour; $hour < $endHour; $hour++) {
        for ($minute = 0; $minute < 60; $minute += 1) { // 1-minute interval
            $timeSlots[] = sprintf('%02d:%02d:00', $hour, $minute);
        }
    }
    return $timeSlots;
}

// Helper function to generate random time between given hours
function generateRandomTime(&$timeSlots)
{
    if (empty($timeSlots)) {
        throw new Exception("No available timeslots for today.");
    }
    $randomIndex = array_rand($timeSlots);
    $randomTime = $timeSlots[$randomIndex];
    unset($timeSlots[$randomIndex]); // Remove the chosen time
    return $randomTime;
}

// Function to generate a random birthdate between two years
function generateRandomDate($startYear, $endYear)
{
    $timestamp = rand(strtotime("$startYear-01-01"), strtotime("$endYear-12-31"));
    return date('Y-m-d', $timestamp);
}

// Insert customers
function createCustomer($createdOn)
{
    global $conn;

    $firstName = "sample_first_name_" . rand(1000, 9999);
    $lastName = "sample_last_name_" . rand(1000, 9999);
    $email = "sample_email_" . rand(1000, 9999) . "@sample.com";
    $phoneNumber = "09" . rand(100000000, 999999999);
    $address = "sample_address_" . rand(1000, 9999);
    $birthDate = generateRandomDate(1995, 2004);
    $gender = rand(0, 1) ? 'Male' : 'Female';

    $query = $conn->prepare("INSERT INTO customers (first_name, last_name, email, phone_number, address, birth_date, gender, created_on) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $query->bind_param('ssssssss', $firstName, $lastName, $email, $phoneNumber, $address, $birthDate, $gender, $createdOn);

    if ($query->execute()) {
        return $conn->insert_id;
    } else {
        die("Customer creation failed: " . $query->error);
    }
}

// Insert sales order
function createSalesOrder($customerId, $totalAmount, $createdOn)
{
    global $conn;

    // Get a random sales manager
    $query = "SELECT employee_id FROM users WHERE user_role = 'sales_manager' ORDER BY RAND() LIMIT 1";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $managedBy = $row['employee_id'];
    } else {
        die("No sales manager found in the database. Please check the users table.");
    }

    $paymentMethods = ['cash', 'credit', 'debit', 'online'];
    $paymentMethod = $paymentMethods[array_rand($paymentMethods)];

    $query = $conn->prepare("INSERT INTO sales_orders (customer_id, total_amount, managed_by, payment_method, created_on) 
              VALUES (?, ?, ?, ?, ?)");
    $query->bind_param('idiss', $customerId, $totalAmount, $managedBy, $paymentMethod, $createdOn);

    if (!$query->execute()) {
        die("Sales order creation failed: " . $query->error);
    }

    $salesOrderId = $conn->insert_id;

    // Process order items and update stock
    $itemCount = rand(1, 3); // Number of items per order
    for ($i = 0; $i < $itemCount; $i++) {
        // Select a random product
        $query = "SELECT product_id, price, quantity, reorder_point FROM products ORDER BY RAND() LIMIT 1";
        $result = $conn->query($query);
        if (!$result || $result->num_rows === 0) {
            die("No products found in the database. Please check the products table.");
        }
        $product = $result->fetch_assoc();

        $productId = $product['product_id'];
        $price = $product['price'];
        $currentStock = $product['quantity'];
        $reorderPoint = $product['reorder_point'];

        // Generate a random quantity for the order item
        $quantity = rand(1, 30);

        // Ensure the quantity does not exceed available stock
        if ($quantity > $currentStock) {
            $quantity = $currentStock; // Limit to available stock
        }

        $totalPrice = $price * $quantity;
        $totalAmount += $totalPrice;

        // Insert order item
        $query = $conn->prepare("INSERT INTO order_items (sales_order_id, product_id, quantity, created_on) 
                  VALUES (?, ?, ?, ?)");
        $query->bind_param('iiis', $salesOrderId, $productId, $quantity, $createdOn);

        if (!$query->execute()) {
            die("Order item creation failed: " . $query->error);
        }

        // Deduct the ordered quantity from the product's stock
        $query = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE product_id = ?");
        $query->bind_param('ii', $quantity, $productId);

        if (!$query->execute()) {
            die("Failed to update product stock: " . $query->error);
        }

        // Check if the product's stock has reached the reorder point
        $currentStock -= $quantity;
        if ($currentStock <= $reorderPoint) {
            createReorderRequest($productId, $createdOn);
        }
    }

    // Update total amount in the sales order
    $query = $conn->prepare("UPDATE sales_orders SET total_amount = ? WHERE sales_order_id = ?");
    $query->bind_param('di', $totalAmount, $salesOrderId);

    if (!$query->execute()) {
        die("Failed to update sales order total: " . $query->error);
    }

    return $salesOrderId;
}

// Insert supply chain orders
function createSupplyChainOrder($source, $relatedId, $handledBy, $acceptedOn, $details)
{
    global $conn;

    $query = $conn->prepare("INSERT INTO supply_chain_orders (source, related_id, handled_by, accepted_on, details) 
              VALUES (?, ?, ?, ?, ?)");
    $query->bind_param('siiss', $source, $relatedId, $handledBy, $acceptedOn, $details);

    if (!$query->execute()) {
        die("Supply chain order creation failed: " . $query->error);
    }
}



// Reorder request creation
function createReorderRequest($productId, $requestedOn)
{
    global $conn;

    $query = "SELECT supplier_id FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $stmt->bind_result($supplierId);
    $stmt->fetch();
    $stmt->close();

    if (!$supplierId) {
        die("Supplier ID not found for product ID: $productId");
    }

    // Get a random employee for `requested_by`
    $roleChance = rand(1, 100);
    $userRole = $roleChance <= 5 ? 'admin' : 'inventory_manager';
    $query = "SELECT employee_id FROM users WHERE user_role = ? ORDER BY RAND() LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $userRole);
    $stmt->execute();
    $stmt->bind_result($requestedBy);
    $stmt->fetch();
    $stmt->close();

    if (!$requestedBy) {
        die("No user found with role: $userRole");
    }

    // Determine priority and reason
    $priorities = ['low', 'medium', 'high'];
    $priority = $priorities[array_rand($priorities)];
    $reorderReason = match ($priority) {
        'low' => 'Reached its reorder point',
        'medium' => 'For preparation on the following events',
        'high' => 'Stocks depleting very fast',
    };

    $quantity = 60;

    $query = $conn->prepare("INSERT INTO reorder_requests (product_id, quantity, requested_by, date_of_request, supplier_id, priority, reorder_reason) 
              VALUES (?, ?, ?, ?, ?, ?, ?)");
    $query->bind_param('iiissss', $productId, $quantity, $requestedBy, $requestedOn, $supplierId, $priority, $reorderReason);

    if (!$query->execute()) {
        die("Failed to create reorder request: " . $query->error);
    }

    // Trigger a supply chain order for the new reorder request
    $requestId = $conn->insert_id; // Get the ID of the newly inserted reorder request

    $handledByQuery = "SELECT employee_id FROM users WHERE user_role = 'supply_chain_manager' ORDER BY RAND() LIMIT 1";
    $handledByResult = $conn->query($handledByQuery);
    $handledBy = $handledByResult->fetch_assoc()['employee_id'] ?? null;

    if (!$handledBy) {
        die("No supply chain manager found. Check the users table.");
    }

    $acceptedOn = date('Y-m-d H:i:s', strtotime($requestedOn) + rand(20, 240));
    $routes = ['Route 1', 'Route 2', 'Route 3', 'Route 4', 'Route 5', 'Route 6'];
    $details = (rand(1, 100) <= 90) ? $routes[array_rand(['Route 1', 'Route 2'])] : $routes[array_rand($routes)];

    createSupplyChainOrder('inventory_reorder', $requestId, $handledBy, $acceptedOn, $details);
}




// Main script
$currentDate = getSavedDateFromDB() ?: strtotime('2020-01-01'); // Resume from saved date or start from Jan 1, 2020
$endDate = strtotime('2020-01-31');

while ($currentDate <= $endDate) {
    $month = date('F', $currentDate);
    $year = date('Y', $currentDate);
    $monthDays = date('t', $currentDate); // Number of days in the current month

    $timeSlots = generateTimeSlots(7, 21); // Available times for the day
    $daySales = rand(1, 3); // Sales per day logic

    for ($i = 0; $i < $daySales; $i++) {
        try {
            $time = generateRandomTime($timeSlots);
        } catch (Exception $e) {
            echo "No more available times for " . date('Y-m-d', $currentDate) . ". Skipping remaining sales.\n";
            break;
        }

        $createdOn = date('Y-m-d', $currentDate) . " $time";

        $timeSlots = array_filter($timeSlots, fn($slot) => $slot > $time);

        $useExistingCustomer = rand(1, 100) <= 40;

        if ($useExistingCustomer) {
            $query = "SELECT customer_id FROM customers ORDER BY RAND() LIMIT 1";
            $result = $conn->query($query);

            $customerId = ($result && $result->num_rows > 0) ? $result->fetch_assoc()['customer_id'] : createCustomer($createdOn);
        } else {
            $customerId = createCustomer($createdOn);
        }

        $salesOrderId = createSalesOrder($customerId, 0, $createdOn);

// 10% chance to insert a supply chain order for sales orders
if (rand(1, 100) <= 10) {
    $handledByQuery = "SELECT employee_id FROM users WHERE user_role = 'supply_chain_manager' ORDER BY RAND() LIMIT 1";
    $handledByResult = $conn->query($handledByQuery);
    $handledBy = $handledByResult->fetch_assoc()['employee_id'] ?? null;

    if (!$handledBy) {
        die("No supply chain manager found. Check the users table.");
    }

    $acceptedOn = date('Y-m-d H:i:s', strtotime($createdOn) + rand(20, 120));
    $routes = ['Route 1', 'Route 2', 'Route 3', 'Route 4', 'Route 5', 'Route 6'];
    $details = (rand(1, 100) <= 90) ? $routes[array_rand(['Route 1', 'Route 2'])] : $routes[array_rand($routes)];

    createSupplyChainOrder('sales_order', $salesOrderId, $handledBy, $acceptedOn, $details);
}

    }

    if (date('j', $currentDate) == $monthDays) {
        logCurrentDateToDB($currentDate);
    }

    $currentDate = strtotime('+1 day', $currentDate);
}

$executionTime = microtime(true) - $startTime;
echo "Execution Time: " . gmdate("H:i:s", $executionTime) . "\n";
$conn->close();
