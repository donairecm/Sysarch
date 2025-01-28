<?php
// Mock simulation of update_user_details.php

header('Content-Type: application/json');

// Simulated input data
$json_input = json_encode([
    'employee_id' => 123,
    'updates' => [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com'
    ],
    'new_password' => 'new_secure_password123'
]);

// Parse JSON request body (simulated)
$data = json_decode($json_input, true);

if (!isset($data['employee_id'], $data['updates']) || empty($data['updates'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request data.']);
    exit();
}

$employee_id = $data['employee_id'];
$updates = $data['updates'];
$new_password = isset($data['new_password']) ? $data['new_password'] : null;

$response = [
    'success' => true,
    'updates' => [],
    'activities' => []
];

try {
    // Simulated: Fetch current user data
    $currentValues = [
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane.smith@example.com'
    ];

    // Simulated: Hash new password
    if (!empty($new_password)) {
        $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
        if (!$hashedPassword) {
            throw new Exception('Failed to hash the new password.');
        }
        $updates['password_hash'] = $hashedPassword;
        error_log("New password hashed successfully: $hashedPassword");
    }

    // Simulated: Prepare the update statement dynamically
    $updateFields = [];
    foreach ($updates as $column => $value) {
        $updateFields[] = "$column = '$value'";
    }

    // Print SQL simulation
    echo "Simulated SQL Query: UPDATE users SET " . implode(', ', $updateFields) . " WHERE employee_id = $employee_id;\n";

    // Simulated: Log changes in user_activities
    $columnMap = [
        'first_name' => 'first name',
        'last_name' => 'last name',
        'email' => 'email',
        'password_hash' => 'password'
    ];

    foreach ($updates as $column => $newValue) {
        if (isset($columnMap[$column])) {
            $oldValue = $currentValues[$column] ?? 'N/A';
            $details = $column === 'password_hash'
                ? "Updated user's password."
                : "Changed user's {$columnMap[$column]} from " . ($oldValue ?: 'N/A') . " to $newValue";

            $response['activities'][] = $details;
        }
    }

    // Output updates and activities
    $response['updates'] = $updates;
    echo json_encode($response, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error updating user details: ' . $e->getMessage()]);
}
?>
