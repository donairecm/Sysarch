<?php
header("Content-Type: application/json");
session_start();

// Ensure the user is authenticated
if (!isset($_SESSION['employee_id'])) {
    echo json_encode(["success" => false, "error" => "User not authenticated."]);
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Update as needed
$dbname = "bestaluminumsalescorps_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Fetch users
$query = "SELECT employee_id, user_role, first_name, last_name, middle_name FROM users";
$result = $conn->query($query);

if ($result) {
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode(["success" => true, "users" => $users]);
} else {
    echo json_encode(["success" => false, "error" => "Failed to fetch users."]);
}

$conn->close();
?>
