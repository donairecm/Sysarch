<?php
header("Content-Type: application/json");

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

// Simulate retrieving logged-in employeeID
// Replace this with actual logic to fetch employeeID from session or auth context
$employeeID = $profileData['employeeID'] ?? 1; // Example employeeID, replace with actual session data

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
$updateFields[] = "`updated_by` = '" . $conn->real_escape_string($employeeID) . "'";
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
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "Error creating snapshot: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Error fetching updated record for snapshot."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Error updating record: " . $conn->error]);
}

// Close the connection
$conn->close();
?>
