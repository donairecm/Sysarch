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
        reference_id INT,
        message VARCHAR(255) NOT NULL,
        `read` BOOLEAN DEFAULT FALSE,
        created_on DATETIME NOT NULL
    )
";

if (!$conn->query($createTableQuery)) {
    die(json_encode(['error' => "Failed to create notification table: " . $conn->error]));
}

// Check if the user's notification table is empty
$checkEmptyQuery = "SELECT COUNT(*) as count FROM `$tableName`";
$emptyResult = $conn->query($checkEmptyQuery);
$emptyRow = $emptyResult->fetch_assoc();
$isEmpty = $emptyRow['count'] == 0;

// Prepare statement for inserting notifications
$insertQuery = "INSERT INTO `$tableName` (reference_id, message, created_on) VALUES (?, ?, ?)";
$insertStmt = $conn->prepare($insertQuery);

// Combine roles for notifications
$roles = ['inventory_manager', 'admin'];

foreach ($roles as $role) {
    // Fetch Notifications for each role
    $fetchNotificationsQuery = "SELECT id, message, created_on FROM notifications WHERE `for` = ?";
    $notificationStmt = $conn->prepare($fetchNotificationsQuery);
    $notificationStmt->bind_param('s', $role);
    $notificationStmt->execute();
    $notificationResult = $notificationStmt->get_result();

    if ($isEmpty) {
        // If user's table is empty, insert all notifications
        while ($row = $notificationResult->fetch_assoc()) {
            $referenceId = $row['id'];
            $message = $row['message'];
            $createdOn = $row['created_on'];

            $insertStmt->bind_param('iss', $referenceId, $message, $createdOn);
            if (!$insertStmt->execute()) {
                die(json_encode(['error' => "Failed to insert notification: " . $insertStmt->error]));
            }
        }
    } else {
        // Get the latest notification date in the user's table
        $latestDateQuery = "SELECT MAX(created_on) as latest_date FROM `$tableName`";
        $latestDateResult = $conn->query($latestDateQuery);
        $latestDateRow = $latestDateResult->fetch_assoc();
        $latestDate = $latestDateRow['latest_date'];

        // Insert only new notifications
        $latestInsertQuery = "INSERT INTO `$tableName` (reference_id, message, created_on)
                              SELECT id, message, created_on
                              FROM notifications
                              WHERE `for` = ? AND created_on > ? 
                              AND id NOT IN (SELECT reference_id FROM `$tableName`)";
        $latestInsertStmt = $conn->prepare($latestInsertQuery);
        $latestInsertStmt->bind_param('ss', $role, $latestDate);

        if (!$latestInsertStmt->execute()) {
            die(json_encode(['error' => "Failed to insert notification: " . $latestInsertStmt->error]));
        }
        $latestInsertStmt->close();
    }

    $notificationStmt->close();
}

// Output Success Message
echo json_encode(['success' => "Notifications updated for user $loggedInEmployeeId"]);

// Close Connections
$insertStmt->close();
$conn->close();
?>
