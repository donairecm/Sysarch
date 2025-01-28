<?php
header('Content-Type: application/json');

// Database connection details
$host = 'localhost';
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password
$database = 'bestaluminumsalescorps_db';

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Query to fetch data from the users table
$query = "SELECT 
            employee_id,
            last_name,
            first_name,
            middle_name,
            user_role,
            user_status,
            created_on,
            email,
            phone_number_1,
            phone_number_2,
            last_login,
            last_logout,
            updated_on,
            updated_by,
            username,
            password_hash
          FROM users";

$result = $conn->query($query);

// Check if query executed successfully
if ($result === false) {
    echo json_encode(["error" => "Failed to execute query: " . $conn->error]);
    $conn->close();
    exit;
}

// Fetch all rows as an associative array
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row; // Add the row to the array
}

// Return the data as JSON
echo json_encode(["users" => $users]);

// Close the connection
$conn->close();
?>
