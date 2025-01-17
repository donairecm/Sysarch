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

// Get current date and time range for the current month
$currentDate = new DateTime('now'); // Jan 17, 2025
$currentMonthStart = $currentDate->format('Y-m-01'); // Jan 1, 2025
$currentMonthEnd = $currentDate->format('Y-m-d'); // Jan 17, 2025

// Calculate last month's range (e.g., Dec 1–17, 2024)
$lastMonthEquivalentDay = (clone $currentDate)->modify('-1 month'); // Dec 17, 2024
$lastMonthStart = $lastMonthEquivalentDay->format('Y-m-01'); // Dec 1, 2024
$lastMonthEnd = $lastMonthEquivalentDay->format('Y-m-d'); // Dec 17, 2024

// Get current total units restocked
$sqlCurrent = "SELECT SUM(quantity) AS total FROM inventory_movements 
               WHERE date_of_movement BETWEEN '$currentMonthStart' AND '$currentMonthEnd' 
               AND movement_type = 'restock'";
$resultCurrent = $conn->query($sqlCurrent);
$currentTotalUnitsRestocked = ($resultCurrent && $resultCurrent->num_rows > 0)
    ? (int) $resultCurrent->fetch_assoc()['total']
    : 0;

// Get last month's total units restocked
$sqlLastMonth = "SELECT SUM(quantity) AS total FROM inventory_movements 
                 WHERE date_of_movement BETWEEN '$lastMonthStart' AND '$lastMonthEnd' 
                 AND movement_type = 'restock'";
$resultLastMonth = $conn->query($sqlLastMonth);
$lastMonthTotalUnitsRestocked = ($resultLastMonth && $resultLastMonth->num_rows > 0)
    ? (int) $resultLastMonth->fetch_assoc()['total']
    : 0;

// Output JSON response
$response = [
    "currentTotalUnitsRestocked" => $currentTotalUnitsRestocked,
    "lastMonthTotalUnitsRestocked" => $lastMonthTotalUnitsRestocked,
    "current_time_range" => [
        "start_date" => $currentMonthStart,
        "end_date" => $currentMonthEnd
    ],
    "last_month_time_range" => [
        "start_date" => $lastMonthStart,
        "end_date" => $lastMonthEnd
    ]
];

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
