<?php
// update_user_details.php

header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Update as needed
$dbname = "bestaluminumsalescorps_db";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

// Parse JSON request body
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['employee_id'], $data['updates']) || empty($data['updates'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request data.']);
    exit();
}

$employee_id = $data['employee_id'];
$updates = $data['updates'];
$new_password = $data['new_password'] ?? null; // Optional: Handle password update
$old_password = $data['old_password'] ?? null; // Optional: Verify old password

$response = [
    'success' => true,
    'updates' => [],
    'activities' => []
];

// Start transaction
$conn->begin_transaction();

try {
    // Fetch current user data
    $currentValuesSql = "SELECT * FROM users WHERE employee_id = ?";
    $currentValuesStmt = $conn->prepare($currentValuesSql);
    $currentValuesStmt->bind_param("i", $employee_id);
    $currentValuesStmt->execute();
    $currentValuesResult = $currentValuesStmt->get_result();
    $currentValues = $currentValuesResult->fetch_assoc();

    if (!$currentValues) {
        throw new Exception('User not found.');
    }

    // Hash new password if provided
    if (!empty($new_password)) {
        $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
        if (!$hashedPassword) {
            throw new Exception('Failed to hash the new password.');
        }
        error_log("Hashed password for update: $hashedPassword");

        // Add to updates array
        $updates['password_hash'] = $hashedPassword;
    }

    // Prepare the update statement dynamically
    $updateFields = [];
    foreach ($updates as $column => $value) {
        $updateFields[] = "$column = ?";
    }

    $updateSql = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE employee_id = ?";
    $stmt = $conn->prepare($updateSql);

    // Bind parameters dynamically
    $types = str_repeat('s', count($updates)) . 'i'; // `s` for strings, `i` for employee_id
    $values = array_values($updates);
    $values[] = $employee_id; // Add employee_id for the WHERE clause
    $stmt->bind_param($types, ...$values);

    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $response['updates'] = $updates;
    } else {
        throw new Exception('No rows updated in users table.');
    }

    // Log changes in `user_activities` table
    $logSql = "INSERT INTO user_activities (performed_by, activity_type, details, date_of_activity) VALUES (?, ?, ?, ?)";
    $logStmt = $conn->prepare($logSql);

    $columnMap = [
        'first_name' => 'first name',
        'middle_name' => 'middle name',
        'last_name' => 'last name',
        'email' => 'email',
        'phone_number_1' => '1st phone number',
        'phone_number_2' => '2nd phone number',
        'username' => 'username',
        'password_hash' => 'password'
    ];

    foreach ($updates as $column => $newValue) {
        if (isset($columnMap[$column])) {
            $oldValue = $currentValues[$column] ?? 'N/A';
            $details = $column === 'password_hash'
                ? "Updated user's password."
                : "Changed user's {$columnMap[$column]} from " . ($oldValue ?: 'N/A') . " to $newValue";

            // Log activity
            $performed_by = $employee_id;
            $activity_type = "account_changes";
            $date_of_activity = date('Y-m-d H:i:s');

            $logStmt->bind_param("isss", $performed_by, $activity_type, $details, $date_of_activity);
            $logStmt->execute();

            $response['activities'][] = $details;
        }
    }

    // Commit transaction
    $conn->commit();

    echo json_encode($response);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => 'Error updating user details: ' . $e->getMessage()]);
} finally {
    $currentValuesStmt->close();
    $stmt->close();
    $logStmt->close();
    $conn->close();
}
?>
