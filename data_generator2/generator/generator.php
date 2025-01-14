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

// Main script
$currentDate = getSavedDateFromDB() ?: strtotime('2020-01-01'); // Resume from saved date or start from Jan 1, 2020
$endDate = strtotime('2020-02-29');

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
    }

    if (date('j', $currentDate) == $monthDays) {
        logCurrentDateToDB($currentDate);
    }

    $currentDate = strtotime('+1 day', $currentDate);
}

$executionTime = microtime(true) - $startTime;
echo "Execution Time: " . gmdate("H:i:s", $executionTime) . "\n";
$conn->close();
