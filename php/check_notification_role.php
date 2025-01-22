<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['employee_id'])) {
    header('Location: ../login-interface/index.php');
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
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

// Retrieve Logged-In Employee ID from Session
$loggedInEmployeeId = $_SESSION['employee_id'];

// Fetch User Role Based on Employee ID
$userQuery = "SELECT user_role FROM users WHERE employee_id = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param('i', $loggedInEmployeeId);
$userStmt->execute();
$userResult = $userStmt->get_result();

// Verify User Exists
if ($userResult->num_rows === 0) {
    die(json_encode(['error' => "Employee not found"]));
}

$userData = $userResult->fetch_assoc();
$userRole = $userData['user_role']; // Get user role

// Determine Notification Table Name
$tableName = "notificationFor_" . $loggedInEmployeeId;

// Create User-Specific Notification Table if it Does Not Exist
$createTableQuery = "
    CREATE TABLE IF NOT EXISTS `$tableName` (
        id INT AUTO_INCREMENT PRIMARY KEY,
        message VARCHAR(255) NOT NULL,
        `read` BOOLEAN DEFAULT FALSE,
        created_on DATETIME NOT NULL
    )
";

if (!$conn->query($createTableQuery)) {
    die(json_encode(['error' => "Failed to create notification table: " . $conn->error]));
}

// Fetch Notifications for User Role
$fetchNotificationsQuery = "SELECT id, message, created_on FROM notifications WHERE `for` = ?";
$notificationStmt = $conn->prepare($fetchNotificationsQuery);
$notificationStmt->bind_param('s', $userRole);
$notificationStmt->execute();
$notificationResult = $notificationStmt->get_result();

// Prepare Insert Statement for User's Notification Table
$insertQuery = "INSERT INTO `$tableName` (message, created_on) VALUES (?, ?)";
$insertStmt = $conn->prepare($insertQuery);

// Insert Notifications into User's Notification Table
while ($row = $notificationResult->fetch_assoc()) {
    $message = $row['message'];
    $createdOn = $row['created_on'];

    // Check if Notification Already Exists in User's Table
    $checkQuery = "SELECT id FROM `$tableName` WHERE created_on = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param('s', $createdOn);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    // Insert if Not Exists
    if ($checkResult->num_rows === 0) {
        $insertStmt->bind_param('ss', $message, $createdOn);
        if (!$insertStmt->execute()) {
            die(json_encode(['error' => "Failed to insert notification: " . $insertStmt->error]));
        }
    }
    $checkStmt->close();
}

// Output Success Message
echo json_encode(['success' => "Notifications updated for user $loggedInEmployeeId"]);

// Close Connections
$notificationStmt->close();
$insertStmt->close();
$conn->close();
?>
