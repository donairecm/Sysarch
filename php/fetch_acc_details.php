<?php
session_start();
header("Content-Type: application/json");

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

// Check if the user is logged in
if (!isset($_SESSION['employee_id'])) {
    echo json_encode(["success" => false, "error" => "User not logged in."]);
    exit();
}

// Extract employee_id and remove prefix
$employee_id_with_prefix = $_SESSION['employee_id'];
$employee_id = preg_replace('/^[A-Z]+-/', '', $employee_id_with_prefix); // Remove prefix (e.g., "XXX-")

// Validate that the resulting employee_id is numeric
if (!is_numeric($employee_id)) {
    echo json_encode(["success" => false, "error" => "Invalid employee ID format."]);
    exit();
}

// Query to retrieve user data
$stmt = $conn->prepare("
    SELECT first_name, last_name, middle_name, email, phone_number_1, phone_number_2, username, password_hash
    FROM users
    WHERE employee_id = ?
");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    echo json_encode(["success" => true, "data" => $userData]);
} else {
    echo json_encode(["success" => false, "error" => "User data not found."]);
}

$stmt->close();
$conn->close();
?>
