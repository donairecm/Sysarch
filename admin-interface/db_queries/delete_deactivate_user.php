<?php
header("Content-Type: application/json");
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['employee_id'])) {
    echo json_encode(["success" => false, "error" => "User not authenticated."]);
    exit();
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // Update as needed
$dbname = "bestaluminumsalescorps_db"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Retrieve and decode JSON input
$data = json_decode(file_get_contents("php://input"), true);
$action = $data["action"] ?? null;
$employee_id = $data["employee_id"] ?? null;

if (!$action || !$employee_id) {
    echo json_encode(["success" => false, "error" => "Invalid input."]);
    exit();
}

// Handle the action
if ($action === "delete") {
    $query = "DELETE FROM users WHERE employee_id = ?";
} elseif ($action === "deactivate") {
    $query = "UPDATE users SET user_status = 'deactivated' WHERE employee_id = ?";
} elseif ($action === "activate") { // New action for activating a user
    $query = "UPDATE users SET user_status = 'active' WHERE employee_id = ?";
} else {
    echo json_encode(["success" => false, "error" => "Invalid action."]);
    exit();
}

// Prepare and execute the query
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

// Close the connection
$conn->close();
?>
