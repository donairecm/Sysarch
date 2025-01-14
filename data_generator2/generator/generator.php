<?php

// Global database connection
global $conn;
set_time_limit(0);

ob_start();
// Current real-time
echo "Current real-time: " . date('Y-m-d H:i:s');
ob_flush();
flush();

// Initialization
echo "Initializing script";
for ($i = 5; $i > 0; $i--) {
    echo "Starting in $i...";
    ob_flush();
    flush();
    sleep(1); // Pause for 1 second
}

// Function to log the current date for continuation
function logCurrentDate($currentDate)
{
    file_put_contents('progress_log.txt', $currentDate);
    echo "Progress saved. You can resume from this date: " . date('Y-m-d', $currentDate);
    ob_flush();
    flush();
}

// Function to check for a saved log
function getSavedDate()
{
    if (file_exists('progress_log.txt')) {
        $savedDate = file_get_contents('progress_log.txt');
        return strtotime($savedDate);
    }
    return false;
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Helper function to generate random time between given hours
function generateRandomTime($startHour, $endHour)
{
    $randomHour = rand($startHour, $endHour - 1);
    $randomMinute = rand(0, 59);
    $randomSecond = rand(0, 59);
    return sprintf('%02d:%02d:%02d', $randomHour, $randomMinute, $randomSecond);
}

// Function to generate a random birthdate between two years
function generateRandomDate($startYear, $endYear)
{
    $timestamp = rand(strtotime("$startYear-01-01"), strtotime("$endYear-12-31"));
    return date('Y-m-d', $timestamp);
}

// Insert customers
function createCustomer($createdOn){
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

    if ($query->execute()) {
        return $conn->insert_id;
    } else {
        die("Sales order creation failed: " . $query->error);
    }
}

// Insert order items
function createOrderItems($salesOrderId, $createdOn)
{
    global $conn;
    $totalAmount = 0;
    $itemCount = rand(1, 3);

    for ($i = 0; $i < $itemCount; $i++) {
        // Select a random product
        $query = "SELECT product_id, price, quantity FROM products ORDER BY RAND() LIMIT 1";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $productId = $row['product_id'];
        $price = $row['price'];
        $currentStock = $row['quantity'];

        // Generate a random quantity for the order item
        $quantity = rand(1, 30);

        // Ensure the quantity does not exceed available stock
        if ($quantity > $currentStock) {
            $quantity = $currentStock; // Limit to available stock
        }

        $totalPrice = $price * $quantity;
        $totalAmount += $totalPrice;

        // Insert the order item
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
    }

    return $totalAmount;
}


// Main script
$currentDate = getSavedDate() ?: strtotime('2020-01-01'); // Resume from saved date or start from Jan 1, 2020
$endDate = strtotime('2020-12-31');

while ($currentDate <= $endDate) {
    $month = date('F', $currentDate);
    $year = date('Y', $currentDate);
    $monthDays = date('t', $currentDate); // Number of days in the current month

    $daySales = rand(1, 3); // Sales per day logic

    for ($i = 0; $i < $daySales; $i++) {
        $time = generateRandomTime(7, 21);
        $createdOn = date('Y-m-d', $currentDate) . " $time";

        $customerId = (rand(1, 100) <= 40)
            ? rand(1, 1000)
            : createCustomer($createdOn);

        $salesOrderId = createSalesOrder($customerId, 0, $createdOn);
        $totalAmount = createOrderItems($salesOrderId, $createdOn);

        $query = $conn->prepare("UPDATE sales_orders SET total_amount = ? WHERE sales_order_id = ?");
        $query->bind_param('di', $totalAmount, $salesOrderId);
        if (!$query->execute()) {
            die("Failed to update sales order total: " . $query->error);
        }
    }

    // End of the month logic
    if (date('j', $currentDate) == $monthDays) {
        echo "Finished generating data for $month in $year.";
        echo "Continue? (yes/no): ";
        $handle = fopen("php://stdin", "r");
        $input = trim(fgets($handle));
        fclose($handle);

        if (strtolower($input) === 'no') {
            logCurrentDate($currentDate);
            exit("Script stopped. Progress saved.");
        }
    }

    $currentDate = strtotime('+1 day', $currentDate);
}

echo "Data generation complete.";
ob_flush();
flush();

// Close the connection
$conn->close();
