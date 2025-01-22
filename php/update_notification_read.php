<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['employee_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'User not logged in.']);
    exit;
}

// Database Configuration
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'bestaluminumsalescorps_db';

// Create Database Connection
$conn = new mysqli($host, $user, $password, $database);

// Check Connection
if ($conn->connect_error) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Get the Notification ID from the Request
$notificationId = $_POST['notificationId'] ?? null;
$loggedInEmployeeId = $_SESSION['employee_id'];

// Determine Notification Table Name
$tableName = "notificationFor_" . $loggedInEmployeeId;

// Update Notification as Read
// Update Notification as Read
if ($notificationId) {
    $updateReadQuery = "UPDATE `$tableName` SET `read` = TRUE WHERE id = ?";
    $stmt = $conn->prepare($updateReadQuery);
    $stmt->bind_param('i', $notificationId);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => "Failed to update notification: " . $stmt->error]);
    }
    $stmt->close();
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Notification ID not provided.']);
}


$conn->close();
?>
