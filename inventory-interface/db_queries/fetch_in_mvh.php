<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'bestaluminumsalescorps_db';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Get the current date and start of the year
$currentDate = new DateTime('now'); // Today's date, e.g., Jan 17, 2025
$startOfYear = $currentDate->format('Y-01-01'); // Start of the year, e.g., Jan 1, 2025
$currentMonthStart = $currentDate->format('Y-m-01'); // Start of the current month, e.g., Jan 1, 2025

// Get monthly data for restocked (movement_type = 'restock')
$sqlRestockedMonthly = "SELECT MONTH(date_of_movement) AS month, SUM(quantity) AS total 
                        FROM inventory_movements 
                        WHERE date_of_movement BETWEEN '$startOfYear' AND '{$currentDate->format('Y-m-d')}' 
                        AND movement_type = 'restock'
                        GROUP BY MONTH(date_of_movement)";
$resultRestockedMonthly = $conn->query($sqlRestockedMonthly);

$restockedMonthly = array_fill(0, 12, 0); // Initialize array for all months
if ($resultRestockedMonthly && $resultRestockedMonthly->num_rows > 0) {
    while ($row = $resultRestockedMonthly->fetch_assoc()) {
        $restockedMonthly[(int)$row['month'] - 1] = (int)$row['total'];
    }
}

// Get daily data for restocked in the current month
$sqlRestockedDaily = "SELECT DAY(date_of_movement) AS day, SUM(quantity) AS total 
                      FROM inventory_movements 
                      WHERE date_of_movement BETWEEN '$currentMonthStart' AND '{$currentDate->format('Y-m-d')}' 
                      AND movement_type = 'restock'
                      GROUP BY DAY(date_of_movement)";
$resultRestockedDaily = $conn->query($sqlRestockedDaily);

$restockedDaily = []; // Initialize array for daily data
if ($resultRestockedDaily && $resultRestockedDaily->num_rows > 0) {
    while ($row = $resultRestockedDaily->fetch_assoc()) {
        $restockedDaily[(int)$row['day']] = (int)$row['total'];
    }
}

// Get monthly data for delivered (movement_type = 'sale')
$sqlDeliveredMonthly = "SELECT MONTH(date_of_movement) AS month, SUM(quantity) AS total 
                        FROM inventory_movements 
                        WHERE date_of_movement BETWEEN '$startOfYear' AND '{$currentDate->format('Y-m-d')}' 
                        AND movement_type = 'sale'
                        GROUP BY MONTH(date_of_movement)";
$resultDeliveredMonthly = $conn->query($sqlDeliveredMonthly);

$deliveredMonthly = array_fill(0, 12, 0); // Initialize array for all months
if ($resultDeliveredMonthly && $resultDeliveredMonthly->num_rows > 0) {
    while ($row = $resultDeliveredMonthly->fetch_assoc()) {
        $deliveredMonthly[(int)$row['month'] - 1] = (int)$row['total'];
    }
}

// Get daily data for delivered in the current month
$sqlDeliveredDaily = "SELECT DAY(date_of_movement) AS day, SUM(quantity) AS total 
                      FROM inventory_movements 
                      WHERE date_of_movement BETWEEN '$currentMonthStart' AND '{$currentDate->format('Y-m-d')}' 
                      AND movement_type = 'sale'
                      GROUP BY DAY(date_of_movement)";
$resultDeliveredDaily = $conn->query($sqlDeliveredDaily);

$deliveredDaily = []; // Initialize array for daily data
if ($resultDeliveredDaily && $resultDeliveredDaily->num_rows > 0) {
    while ($row = $resultDeliveredDaily->fetch_assoc()) {
        $deliveredDaily[(int)$row['day']] = (int)$row['total'];
    }
}

// Output JSON response
$response = [
    "restocked" => [
        "monthly" => $restockedMonthly,
        "daily" => $restockedDaily
    ],
    "delivered" => [
        "monthly" => $deliveredMonthly,
        "daily" => $deliveredDaily
    ]
];

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
