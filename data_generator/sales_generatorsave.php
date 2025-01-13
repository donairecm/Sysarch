<?php

global $conn;
set_time_limit(0);

$startTime = microtime(true); // Start time measurement

echo "Script will start processing shortly...\n";
for ($i = 5; $i > 0; $i--) {
    echo "$i...\n";
    flush();
    ob_flush();
    sleep(1);
}
echo "Code is running...\n";

function generateId($prefix, $number, $paddingLength = 3) {
    return $prefix . "-" . str_pad($number, $paddingLength, '0', STR_PAD_LEFT);
}

function randomGender() {
    return mt_rand(0, 1) ? "Male" : "Female";
}

function addMinutesToTime($time, $minutes) {
    return date("H:i:s", strtotime("+$minutes minutes", strtotime($time)));
}

function fetchRandomId($conn, $table, $column, $condition = "") {
    $query = "SELECT $column FROM $table";
    if ($condition) {
        $query .= " WHERE $condition";
    }
    $query .= " ORDER BY RAND() LIMIT 1";
    $result = $conn->query($query);
    if ($result && $row = $result->fetch_assoc()) {
        return $row[$column];
    }
    return null;
}

function generateActivityId($counter) {
    return "ACT-" . str_pad($counter, 3, '0', STR_PAD_LEFT);
}

function logActivity($conn, $performedBy, $activityType, $referenceId, $createdOn, $details) {
    static $activityCounter = 1; // Counter to ensure unique IDs
    $activityId = generateActivityId($activityCounter++);
    
    $logQuery = "INSERT INTO user_activities (activity_id, performed_by, activity_type, reference_id, created_on, details)
                 VALUES ('$activityId', '$performedBy', '$activityType', '$referenceId', '$createdOn', '$details')";
    if (!$conn->query($logQuery)) {
        echo "Error logging activity for reference_id $referenceId: " . $conn->error . "\n";
    } else {
        echo "Activity logged with ID $activityId for reference_id $referenceId. Activity Type: $activityType\n";
    }
}

function triggerReorder($conn, $productId, $currentTime) {
    static $reorderCounter = 1; // Static counter for generating request IDs

    // Fetch the supplier_id, current quantity, and reorder_point for the product
    $productQuery = "SELECT supplier_id, quantity, reorder_point FROM products WHERE product_id = '$productId'";
    $productResult = $conn->query($productQuery);
    $supplierId = null;
    $quantity = 0;
    $reorderPoint = 0;

    if ($productResult && $row = $productResult->fetch_assoc()) {
        $supplierId = $row['supplier_id'];
        $quantity = $row['quantity'];
        $reorderPoint = $row['reorder_point'];
    }

    // Determine the new priority based on the quantity and reorder_point
    $priority = "low"; // Default priority
    if ($quantity <= $reorderPoint - 20 && $quantity > $reorderPoint - 40) {
        $priority = "medium";
    } elseif ($quantity <= $reorderPoint - 40) {
        $priority = "high";
    }

    // Check if a reorder request already exists for this product
    $existingRequestQuery = "SELECT priority FROM reorder_requests WHERE product_id = '$productId'";
    $existingRequestResult = $conn->query($existingRequestQuery);

    if ($existingRequestResult && $row = $existingRequestResult->fetch_assoc()) {
        $existingPriority = $row['priority'];
        // Update the priority if it has changed
        if ($existingPriority !== $priority) {
            $updateRequestQuery = "UPDATE reorder_requests SET priority = '$priority', date_of_request = '$currentTime'
                                   WHERE product_id = '$productId'";
            if (!$conn->query($updateRequestQuery)) {
                echo "Error updating reorder request for product_id $productId: " . $conn->error . "\n";
            } else {
                echo "Updated reorder request for product_id $productId. New Priority: $priority\n";
            }
        } else {
            echo "Reorder request for product_id $productId already exists with priority: $priority\n";
        }
    } else {
        // Fetch a random inventory manager or (rarely) an admin
        $requestedBy = fetchRandomId($conn, "users", "employee_id", "user_role = 'inventory_manager' OR (user_role = 'admin' AND RAND() < 0.001)");

        $quantityToReorder = 60; // Fixed reorder quantity

        // Insert a new reorder request
        $requestId = generateId("RID", $reorderCounter++);
        $insertRequestQuery = "INSERT INTO reorder_requests (request_id, product_id, quantity, supplier_id, requested_by, date_of_request, priority)
                               VALUES ('$requestId', '$productId', $quantityToReorder, '$supplierId', '$requestedBy', '$currentTime', '$priority')";
        if (!$conn->query($insertRequestQuery)) {
            echo "Error inserting reorder request for product_id $productId: " . $conn->error . "\n";
        } else {
            echo "New reorder request triggered for product_id $productId. Request ID: $requestId, Priority: $priority\n";

            // Define the details for the activity log
            $details = "Requested a reorder";

            // Log the reorder request activity
            logActivity($conn, $requestedBy, "inventory", $requestId, $currentTime, $details);
        }
    }
}

function processReorderRequestsForSCO($conn) {
    $reorderQuery = "SELECT * FROM reorder_requests WHERE NOT EXISTS (
                        SELECT 1 FROM supply_chain_orders 
                        WHERE source = 'reorder_requests' AND related_id = reorder_requests.request_id
                    )";
    $result = $conn->query($reorderQuery);

    static $scoCounter = 1;

    while ($result && $row = $result->fetch_assoc()) {
        $requestId = $row['request_id'];
        $dateOfRequest = $row['date_of_request'];
        $requestedBy = $row['requested_by'];

        $scoId = getNextSCOId($conn);
        $handledBy = fetchRandomId(
            $conn,
            "users",
            "employee_id",
            "
            user_role = 'supply_chain_manager' AND 
            employee_id NOT IN (
                SELECT handled_by 
                FROM supply_chain_orders 
                WHERE status != 'completed'
            )
            "
        );

        if (!$handledBy) {
            echo "No available supply chain manager to handle SCO for reorder_request $requestId.\n";
            continue;
        }

        $insertSCOQuery = "INSERT INTO supply_chain_orders (sc_order_id, source, related_id, status, handled_by, created_on)
                           VALUES ('$scoId', 'reorder_requests', '$requestId', 'pending', '$handledBy', '$dateOfRequest')";
        if (!$conn->query($insertSCOQuery)) {
            echo "Error inserting SCO for reorder_request $requestId: " . $conn->error . "\n";
            continue;
        }

        logActivity($conn, $handledBy, "supply-chain", $scoId, $dateOfRequest, "SCO-SD pending");
        echo "SCO $scoId created for reorder request $requestId.\n";

        logActivity($conn, $requestedBy, "inventory", $requestId, $dateOfRequest, "Requested a reorder");

        transitionSCO($conn, $scoId, $handledBy, $dateOfRequest);
    }
}

function populateInventoryMovements($conn) {
    static $movementCounter = 1;

    // Populate from order_item (sale movements)
    $orderItemsQuery = "
        SELECT oi.product_id, oi.quantity, oi.sales_order_id AS reference_id, MAX(oi.created_on) AS date_of_movement
        FROM order_item oi
        GROUP BY oi.sales_order_id, oi.product_id";
    $orderItemsResult = $conn->query($orderItemsQuery);

    while ($orderItemsResult && $row = $orderItemsResult->fetch_assoc()) {
        $movementId = generateId("MOV", $movementCounter++);
        $productId = $row['product_id'];
        $quantity = $row['quantity'];
        $movementType = "sale";
        $dateOfMovement = $row['date_of_movement'];
        $referenceId = $row['reference_id'];

        $insertQuery = "
            INSERT INTO inventory_movements (movement_id, product_id, quantity, movement_type, date_of_movement, reference_id)
            VALUES ('$movementId', '$productId', $quantity, '$movementType', '$dateOfMovement', '$referenceId')";
        if (!$conn->query($insertQuery)) {
            echo "Error inserting inventory movement (sale) for product_id $productId: " . $conn->error . "\n";
        } else {
            echo "Logged inventory movement: $movementId for product_id $productId (sale).\n";
        }
    }

    // Populate from reorder_requests (restock movements)
    $reorderRequestsQuery = "
        SELECT rr.product_id, rr.quantity, rr.completed_on AS date_of_movement, rr.request_id AS reference_id
        FROM reorder_requests rr
        WHERE rr.completed_on IS NOT NULL";
    $reorderRequestsResult = $conn->query($reorderRequestsQuery);

    while ($reorderRequestsResult && $row = $reorderRequestsResult->fetch_assoc()) {
        $movementId = generateId("MOV", $movementCounter++);
        $productId = $row['product_id'];
        $quantity = $row['quantity'];
        $movementType = "restock";
        $dateOfMovement = $row['date_of_movement'];
        $referenceId = $row['reference_id'];

        $insertQuery = "
            INSERT INTO inventory_movements (movement_id, product_id, quantity, movement_type, date_of_movement, reference_id)
            VALUES ('$movementId', '$productId', $quantity, '$movementType', '$dateOfMovement', '$referenceId')";
        if (!$conn->query($insertQuery)) {
            echo "Error inserting inventory movement (restock) for product_id $productId: " . $conn->error . "\n";
        } else {
            echo "Logged inventory movement: $movementId for product_id $productId (restock).\n";
        }
    }
}

function updateLastRestocked($conn) {
    $query = "
        SELECT rr.product_id, MAX(rr.completed_on) AS last_restocked
        FROM reorder_requests rr
        WHERE rr.completed_on IS NOT NULL
        GROUP BY rr.product_id
    ";
    $result = $conn->query($query);

    while ($result && $row = $result->fetch_assoc()) {
        $productId = $row['product_id'];
        $lastRestocked = $row['last_restocked'];

        $updateQuery = "UPDATE products SET last_restocked = '$lastRestocked' WHERE product_id = '$productId'";
        if (!$conn->query($updateQuery)) {
            echo "Error updating last_restocked for product_id $productId: " . $conn->error . "\n";
        } else {
            echo "Updated last_restocked for product_id $productId to $lastRestocked.\n";
        }
    }
}


function getNextSCOId($conn) {
    // Query to find the highest existing SCO ID
    $result = $conn->query("SELECT MAX(CAST(SUBSTRING(sc_order_id, 5) AS UNSIGNED)) AS max_id FROM supply_chain_orders");

    // Ensure $result is valid and fetch the row
    if ($result && $row = $result->fetch_assoc()) {
        $maxId = (int)$row['max_id']; // Get the max_id from the row
    } else {
        $maxId = 0; // Default to 0 if no results or query failed
    }

    // Generate the next SCO ID
    return "SCO-" . str_pad($maxId + 1, 3, '0', STR_PAD_LEFT);
}

function transitionSCO($conn, $scoId, $handledBy, $lastOrderItemCreatedOn) {
    $statusTransitions = [
        "pending" => [0, 0],                   // No delay for the initial 'pending' status
        "on_process" => [1 * 60, 5 * 60],      // Delay range for "on_process" status
        "in_transit" => [10 * 60, 30 * 60],    // Delay range for "in_transit" status
        "completed" => [45 * 60, 2 * 60 * 60]  // Delay range for "completed" status
    ];

    $sourceQuery = "SELECT source FROM supply_chain_orders WHERE sc_order_id = '$scoId'";
    $result = $conn->query($sourceQuery);
    $source = "unknown";
    if ($result && $row = $result->fetch_assoc()) {
        $source = $row['source']; // E.g., 'reorder_requests' or 'sales_orders'
    }

    $sourcePrefix = match ($source) {
        "reorder_requests" => "SCO-RR",
        "sales_orders" => "SCO-SD",
        default => "SCO"
    };

    $currentTime = strtotime($lastOrderItemCreatedOn);

    foreach ($statusTransitions as $newStatus => [$minTime, $maxTime]) {
        $delay = mt_rand($minTime, $maxTime);
        $currentTime += $delay;
        $newTimestamp = date("Y-m-d H:i:s", $currentTime);

        $updateQuery = "UPDATE supply_chain_orders SET status = '$newStatus', updated_on = '$newTimestamp' WHERE sc_order_id = '$scoId'";
        if ($conn->query($updateQuery)) {
            $details = match ($newStatus) {
                "pending" => "$sourcePrefix created",
                "on_process" => "$sourcePrefix accepted",
                "in_transit" => "$sourcePrefix out for delivery",
                "completed" => "$sourcePrefix delivered",
                default => "$sourcePrefix status updated"
            };

            logActivity($conn, $handledBy, "supply-chain", $scoId, $newTimestamp, $details);
            echo "SCO $scoId transitioned to $newStatus at $newTimestamp\n";

            // If the status is 'completed', handle product stock update for reorder_requests
            if ($newStatus === "completed" && $source === "reorder_requests") {
                $fetchRIDQuery = "SELECT related_id FROM supply_chain_orders WHERE sc_order_id = '$scoId'";
                $ridResult = $conn->query($fetchRIDQuery);

                if ($ridResult && $ridRow = $ridResult->fetch_assoc()) {
                    $relatedRID = $ridRow['related_id'];

                    $updateRIDQuery = "UPDATE reorder_requests SET completed_on = '$newTimestamp' WHERE request_id = '$relatedRID'";
                    if (!$conn->query($updateRIDQuery)) {
                        echo "Error updating `completed_on` for request_id $relatedRID: " . $conn->error . "\n";
                    } else {
                        echo "Updated `completed_on` for request_id $relatedRID to $newTimestamp.\n";

                        // Fetch the quantity and product_id for the completed reorder request
                        $fetchDetailsQuery = "SELECT product_id, quantity FROM reorder_requests WHERE request_id = '$relatedRID'";
                        $detailsResult = $conn->query($fetchDetailsQuery);

                        if ($detailsResult && $detailsRow = $detailsResult->fetch_assoc()) {
                            $productId = $detailsRow['product_id'];
                            $quantity = $detailsRow['quantity'];

                            // Update the product quantity
                            $updateProductQuery = "UPDATE products SET quantity = quantity + $quantity WHERE product_id = '$productId'";
                            if ($conn->query($updateProductQuery)) {
                                echo "Updated stock for product_id $productId. Added quantity: $quantity.\n";
                            } else {
                                echo "Error updating stock for product_id $productId: " . $conn->error . "\n";
                            }
                        } else {
                            echo "Error fetching details for request_id $relatedRID: " . $conn->error . "\n";
                        }
                    }
                }
            }
        } else {
            echo "Error updating status for SCO $scoId: " . $conn->error . "\n";
            break;
        }

        if ($newStatus === "completed") {
            break;
        }
    }
}

function processSalesOrderForSCO($conn, $salesOrderId, $lastOrderItemCreatedOn) {
    static $scoCounter = 1;

    if (mt_rand(1, 100) > 20) {
        return; // 20% chance to trigger SCO
    }

    $scoId = getNextSCOId($conn);
    $handledBy = fetchRandomId(
        $conn,
        "users",
        "employee_id",
        "
        user_role = 'supply_chain_manager' AND 
        employee_id NOT IN (
            SELECT handled_by 
            FROM supply_chain_orders 
            WHERE status != 'completed'
        )
        "
    );

    if (!$handledBy) {
        echo "No available supply chain manager to handle SCO for $salesOrderId.\n";
        return;
    }

    $insertSCOQuery = "INSERT INTO supply_chain_orders (sc_order_id, source, related_id, status, handled_by, created_on)
                       VALUES ('$scoId', 'sales_orders', '$salesOrderId', 'pending', '$handledBy', '$lastOrderItemCreatedOn')";
    if (!$conn->query($insertSCOQuery)) {
        echo "Error inserting SCO for $salesOrderId: " . $conn->error . "\n";
        return;
    }

    logActivity($conn, $handledBy, "supply-chain", $scoId, $lastOrderItemCreatedOn, "SCO pending");
    echo "SCO $scoId created for sales order $salesOrderId.\n";

    // Update user_activities for the SID
    $updateActivityQuery = "UPDATE user_activities 
                            SET details = 'Completed a sale and requested it for delivery' 
                            WHERE reference_id = '$salesOrderId' AND activity_type = 'sales'";
    $conn->query($updateActivityQuery);

    transitionSCO($conn, $scoId, $handledBy, $lastOrderItemCreatedOn);

}


$startDate = strtotime('2020-01-01 00:00:00');
$endDate = strtotime('2020-02-01 23:59:59');
$currentDate = $startDate;

$customerCounter = 1;
$salesOrderCounter = 1;
$orderItemCounter = 1;

$timeRanges = [
    "07:00:00",
    "10:00:00",
    "21:00:00" // End of day time
];

while ($currentDate <= $endDate) {
    processReorderRequestsForSCO($conn);
    populateInventoryMovements($conn);
    updateLastRestocked($conn);


    $month = (int)date("n", $currentDate);

    if ($month >= 3 && $month <= 8) {
        $dailySalesCount = (mt_rand(1, 100) <= 30) ? mt_rand(1, 4) : mt_rand(5, 10);
    } else {
        $dailySalesCount = (mt_rand(1, 100) <= 70) ? mt_rand(1, 4) : mt_rand(5, 10);
    }

    $currentTime = date("H:i:s", mt_rand(strtotime($timeRanges[0]), strtotime($timeRanges[1])));
    $endOfDayTime = $timeRanges[2];

    for ($j = 1; $j <= $dailySalesCount; $j++) {
        if (strtotime($currentTime) > strtotime($endOfDayTime)) {
            echo "Reached end of time range for the day. Skipping remaining sales.\n";
            break;
        }

        $createdOn = date("Y-m-d", $currentDate) . " $currentTime";

        $salesOrderId = generateId("SID", $salesOrderCounter);
        $totalAmount = 0; // Initialize total amount for this sales order

        $isNewCustomer = mt_rand(1, 100) <= 30;
        if ($isNewCustomer) {
            $customerId = generateId("CID", $customerCounter++);
            $firstName = "customer_name";
            $lastName = "sample" . $customerCounter;
            $email = "{$firstName}_{$lastName}@sample.com";
            $phoneNumber = "0927-252-" . str_pad(mt_rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            $address = "sample address no." . $customerCounter;
            $dob = date("m-d-Y", mt_rand(strtotime('1948-01-01'), strtotime('1995-12-31')));
            $gender = randomGender();

            $sql = "INSERT INTO customers (customer_id, first_name, last_name, email, phone_number, address, date_of_birth, gender, created_on)
                    VALUES ('$customerId', '$firstName', '$lastName', '$email', '$phoneNumber', '$address', '$dob', '$gender', '$createdOn')";
            $conn->query($sql);
        } else {
            $existingCustomerId = ($customerCounter > 1) ? mt_rand(1, $customerCounter - 1) : 1;
            $customerId = generateId("CID", $existingCustomerId);
        }

        $managedBy = fetchRandomId($conn, "users", "employee_id", "user_role = 'sales_manager' OR (user_role = 'admin' AND RAND() < 0.1)");
        $paymentMethodOptions = ["cash", "credit", "debit", "online"];
        $paymentMethod = $paymentMethodOptions[array_rand($paymentMethodOptions)];

        // Insert sales order without total_amount initially
        $sql = "INSERT INTO sales_orders (sales_order_id, customer_id, managed_by, payment_method, total_amount, created_on)
                VALUES ('$salesOrderId', '$customerId', '$managedBy', '$paymentMethod', 0, '$createdOn')";
        $conn->query($sql);

        $orderItemCount = mt_rand(1, 3);
        $orderItemCreatedOn = $createdOn; // Initialize orderItemCreatedOn with the same timestamp as SID

        for ($i = 1; $i <= $orderItemCount; $i++) {
            $orderItemId = generateId("OID", $orderItemCounter++);
            $productId = fetchRandomId($conn, "products", "product_id");

            if (!$productId) {
                echo "No product found for order item generation. Skipping...\n";
                continue;
            }

            // Retrieve the product's price and current stock from the database
            $productQuery = "SELECT price, quantity AS stock, reorder_point FROM products WHERE product_id = '$productId'";
            $productResult = $conn->query($productQuery);
            $productPrice = 0;
            $productStock = 0;
            $reorderPoint = 0;

            if ($productResult && $row = $productResult->fetch_assoc()) {
                $productPrice = $row['price'];
                $productStock = $row['stock'];
                $reorderPoint = $row['reorder_point'];
            }

            if ($productPrice <= 0 || $productStock <= 0) {
                echo "Invalid price or out of stock for product_id $productId. Skipping...\n";
                continue;
            }

            // Decide the quantity for this order item
            $quantity = (mt_rand(1, 100) <= 70) ? mt_rand(1, 10) : mt_rand(11, 15);

            // Check if enough stock is available
            if ($quantity > $productStock) {
                echo "Insufficient stock for product_id $productId. Available: $productStock, Requested: $quantity. Skipping...\n";
                continue;
            }

            // Deduct the quantity from the stock
            $newStock = $productStock - $quantity;
            $updateStockQuery = "UPDATE products SET quantity = $newStock WHERE product_id = '$productId'";
            if (!$conn->query($updateStockQuery)) {
                echo "Error updating stock for product_id $productId: " . $conn->error . "\n";
                continue;
            }

            // Trigger reorder if stock falls below or equals the reorder_point
            if ($newStock <= $reorderPoint) {
                triggerReorder($conn, $productId, $orderItemCreatedOn);
            }

            // Calculate total price for this item
            $totalPrice = $productPrice * $quantity; // Calculate total price for this item
            $totalAmount += $totalPrice; // Add to the sales order's total amount

            // Update the time for the current order item
            $orderItemCreatedOn = date("Y-m-d H:i:s", strtotime("+".mt_rand(1, 3)." minutes", strtotime($orderItemCreatedOn)));

            if (strtotime($orderItemCreatedOn) > strtotime(date("Y-m-d", $currentDate) . " $endOfDayTime")) {
                echo "Reached end of time range for order items. Stopping order generation.\n";
                break;
            }

            // Insert order item into the database
            $sql = "INSERT INTO order_item (order_item_id, sales_order_id, product_id, quantity, price, created_on)
                    VALUES ('$orderItemId', '$salesOrderId', '$productId', $quantity, $totalPrice, '$orderItemCreatedOn')";
            if (!$conn->query($sql)) {
                echo "Error inserting order item: " . $conn->error . "\n";
            }
        }

        // Update the total amount for the sales order
        $sql = "UPDATE sales_orders SET total_amount = $totalAmount WHERE sales_order_id = '$salesOrderId'";
        $conn->query($sql);

        // Log the sales order activity
        logActivity($conn, $managedBy, "sales", $salesOrderId, $orderItemCreatedOn, "Completed a sale");


        processSalesOrderForSCO($conn, $salesOrderId, $orderItemCreatedOn);
        populateInventoryMovements($conn);
        updateLastRestocked($conn);


        $gapType = mt_rand(1, 2);
        if ($gapType === 1) {
            $currentTime = addMinutesToTime($currentTime, mt_rand(5, 20));
        } else {
            $currentTime = addMinutesToTime($currentTime, mt_rand(60, 180));
        }

        $salesOrderCounter++;
    }

    $currentDate = strtotime("+1 day", $currentDate);
}

$conn->close();

$endTime = microtime(true); // End time measurement
$totalTime = $endTime - $startTime; // Calculate total runtime

echo "Data generation complete.\n";

// Convert total runtime to hours, minutes, and seconds
$hours = floor($totalTime / 3600);
$minutes = floor(($totalTime % 3600) / 60);
$seconds = round($totalTime % 60);

echo "Total runtime: {$hours} hour" . ($hours !== 1 ? "s" : "") . " {$minutes} minute" . ($minutes !== 1 ? "s" : "") . " {$seconds} second" . ($seconds !== 1 ? "s" : "") . ".\n";

flush();
ob_flush();
// working everything
?>
