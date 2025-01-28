<?php
header("Content-Type: application/json");
session_start(); // Start the session

// Ensure the user is authenticated
if (!isset($_SESSION['employee_id'])) {
    echo json_encode(["success" => false, "error" => "User not authenticated."]);
    exit();
}

// Retrieve the employee ID from the session
$employee_id_with_prefix = $_SESSION['employee_id'];

// Remove the prefix to get the numeric employee_id
$numeric_employee_id = (int)preg_replace('/^[A-Z]+-/', '', $employee_id_with_prefix);

// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // Update as needed
$dbname = "bestaluminumsalescorps_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Retrieve and decode JSON input
$data = json_decode(file_get_contents("php://input"), true);

$user_id = $data["user_id"] ?? null;
$new_password = $data["password"] ?? null;

if (!$user_id || !$new_password) {
    echo json_encode(["success" => false, "error" => "Invalid input."]);
    exit();
}

// Fetch the user role for the given `user_id`
$getUserRoleQuery = "SELECT user_role FROM users WHERE employee_id = ?";
$stmtRole = $conn->prepare($getUserRoleQuery);
$stmtRole->bind_param("i", $user_id);
$stmtRole->execute();
$resultRole = $stmtRole->get_result();

if ($resultRole->num_rows === 0) {
    echo json_encode(["success" => false, "error" => "User not found."]);
    $stmtRole->close();
    $conn->close();
    exit();
}

$userData = $resultRole->fetch_assoc();
$user_role = $userData["user_role"];

// Map user roles to prefixes
$rolePrefixes = [
    "sales_manager" => "SSM",
    "inventory_manager" => "IVM",
    "supply_chain_manager" => "SCM"
];

$prefix = $rolePrefixes[$user_role] ?? null;

if (!$prefix) {
    echo json_encode(["success" => false, "error" => "Invalid user role."]);
    $stmtRole->close();
    $conn->close();
    exit();
}

// Construct the full employee ID with prefix
$full_employee_id = $prefix . "-" . str_pad($user_id, 3, "0", STR_PAD_LEFT);

$stmtRole->close();

// Hash the new password
$password_hash = password_hash($new_password, PASSWORD_BCRYPT);

// Update the user's password in the database
$updatePasswordQuery = "UPDATE users SET password_hash = ? WHERE employee_id = ?";
$stmt = $conn->prepare($updatePasswordQuery);
$stmt->bind_param("si", $password_hash, $user_id);

if ($stmt->execute()) {
    // Log the password change in the `user_activities` table
    $activityDetails = "Changed password for employee_id $full_employee_id ";
    $logActivityQuery = "
        INSERT INTO user_activities (performed_by, activity_type, details, date_of_activity)
        VALUES (
            '" . $conn->real_escape_string($numeric_employee_id) . "',
            'admin',
            '" . $conn->real_escape_string($activityDetails) . "',
            NOW()
        )
    ";
    if ($conn->query($logActivityQuery) === TRUE) {
        echo json_encode(["success" => true, "message" => "Password updated and activity logged successfully."]);
    } else {
        echo json_encode(["success" => false, "error" => "Failed to log activity: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Failed to update password."]);
}

$stmt->close();
$conn->close();
?>
