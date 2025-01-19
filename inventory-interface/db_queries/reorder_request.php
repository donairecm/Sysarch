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

$employee_id = $_SESSION['employee_id'];

// Get input data
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['product_id'], $data['quantity'])) {
    echo json_encode(["success" => false, "error" => "Invalid input data."]);
    exit();
}

$product_id = $data['product_id']; // Un-prefixed product ID
$quantity = intval($data['quantity']); // Ensure the quantity is an integer
$date_of_request = date("Y-m-d H:i:s"); // Current date

// Insert into reorder_requests table
$stmt = $conn->prepare("INSERT INTO reorder_requests (product_id, quantity, requested_by, date_of_request) VALUES (?, ?, ?, ?)");
$stmt->bind_param("siss", $product_id, $quantity, $employee_id, $date_of_request);

if ($stmt->execute()) {
    $request_id = $stmt->insert_id; // Get the inserted request_id
    $stmt->close();

    // Insert into user_activities table
    $activity_type = "inventory";
    $details = "Requested a reorder for PRD-" . sprintf("%03d", $product_id); // Add the prefix back
    $date_of_activity = date("Y-m-d H:i:s"); // Current date and time

    $stmt = $conn->prepare("INSERT INTO user_activities (performed_by, activity_type, details, reference_id, date_of_activity) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $employee_id, $activity_type, $details, $request_id, $date_of_activity);

    if ($stmt->execute()) {
        $stmt->close();

        // Insert into supply_chain_orders table
        $source = "inventory_reorder";
        $stmt = $conn->prepare("INSERT INTO supply_chain_orders (source, related_id) VALUES (?, ?)");
        $stmt->bind_param("si", $source, $request_id);

        if ($stmt->execute()) {
            $stmt->close();
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "Failed to insert into supply_chain_orders: " . $stmt->error]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Failed to log user activity: " . $stmt->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Failed to insert reorder request: " . $stmt->error]);
}

$conn->close();
?>
