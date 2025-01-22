<?php
header("Content-Type: application/json");
session_start(); // Start the session

// Ensure the user is logged in
if (!isset($_SESSION['employee_id'])) {
    echo json_encode(["success" => false, "error" => "User not authenticated."]);
    exit();
}

// Retrieve employee_id from the session
$employee_id_with_prefix = $_SESSION['employee_id'];

// Remove the prefix to get the numeric employee_id
$numeric_employee_id = (int)preg_replace('/^[A-Z]+-/', '', $employee_id_with_prefix);

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

$product_id_with_prefix = $data["product_id"] ?? null;
$updates = $data["updates"] ?? null;

if (!$product_id_with_prefix || !$updates) {
    echo json_encode(["success" => false, "error" => "Invalid input."]);
    exit();
}

// Remove the "PRD-" prefix to get the numeric product_id
$product_id = (int)preg_replace('/^PRD-/', '', $product_id_with_prefix);

// Build the SQL update query dynamically
$updateFields = [];
foreach ($updates as $field => $value) {
    $updateFields[] = "`$field` = '" . $conn->real_escape_string($value) . "'";
}

// Add `updated_by` and `updated_on` fields to the query
$updateFields[] = "`updated_by` = '" . $conn->real_escape_string($numeric_employee_id) . "'";
$updateFields[] = "`updated_on` = NOW()";

$updateQuery = "UPDATE products SET " . implode(", ", $updateFields) . " WHERE product_id = $product_id";

// Execute the update query
if ($conn->query($updateQuery) === TRUE) {
    // Fetch the updated row for snapshot
    $result = $conn->query("SELECT * FROM products WHERE product_id = $product_id");
    if ($result && $row = $result->fetch_assoc()) {
        // Prepare fields and values for the snapshot
        $fields = array_keys($row);
        $values = array_map(function($value) use ($conn) {
            return "'" . $conn->real_escape_string($value) . "'";
        }, array_values($row));
        
        // Add snapshot_date to the fields and values
        $fields[] = "snapshot_date";
        $values[] = "NOW()";

        // Insert the snapshot into products_history table
        $snapshotQuery = "INSERT INTO products_history (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $values) . ")";
        if ($conn->query($snapshotQuery) === TRUE) {
            // Insert the user activity log
            $activityDetails = "Made changes to $product_id_with_prefix";
            $activityQuery = "INSERT INTO user_activities (performed_by, activity_type, details, date_of_activity) 
                              VALUES (
                                  '" . $conn->real_escape_string($numeric_employee_id) . "',
                                  'inventory',
                                  '" . $conn->real_escape_string($activityDetails) . "',
                                  NOW()
                              )";
            if ($conn->query($activityQuery) === TRUE) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "Error logging user activity: " . $conn->error]);
            }
        } else {
            echo json_encode(["success" => false, "error" => "Error creating snapshot: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Error fetching updated record for snapshot: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Error updating record: " . $conn->error]);
}

// Close the connection
$conn->close();
?>
