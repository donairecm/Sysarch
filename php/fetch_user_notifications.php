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

// Retrieve Logged-In Employee ID
$loggedInEmployeeId = $_SESSION['employee_id'];

// Determine Notification Table Name
$tableName = "notificationFor_" . $loggedInEmployeeId;

// Check if the notification table exists
$checkTableQuery = "SHOW TABLES LIKE '$tableName'";
$tableExists = $conn->query($checkTableQuery);

if ($tableExists->num_rows === 0) {
    http_response_code(404); // Not Found
    echo json_encode(['error' => "Notification table not found for user $loggedInEmployeeId"]);
    exit;
}

// Fetch Notifications
$fetchUserNotificationsQuery = "
    SELECT message, `read`, created_on 
    FROM `$tableName` 
    ORDER BY created_on DESC
";
$result = $conn->query($fetchUserNotificationsQuery);

if (!$result) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => "Failed to fetch notifications: " . $conn->error]);
    exit;
}

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = [
        'message' => $row['message'],
        'read' => $row['read'] ? true : false,
        'created_on' => $row['created_on']
    ];
}

// Count Unread Notifications
$unreadCountQuery = "SELECT COUNT(*) AS unread_count FROM `$tableName` WHERE `read` = FALSE";
$unreadResult = $conn->query($unreadCountQuery);
$unreadCount = $unreadResult ? $unreadResult->fetch_assoc()['unread_count'] : 0;

// Return JSON Response
echo json_encode([
    'notifications' => $notifications,
    'badgeCount' => $unreadCount,
    'success' => true
]);

// Close Database Connection
$conn->close();
?>
