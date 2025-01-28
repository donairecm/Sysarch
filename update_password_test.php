<?php
// Database credentials
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

// Employee ID and new password hash
$employee_id = 2;
$new_password_hash = '$2y$10$5CaO.mam1UYT7PVIUJ/2bOF2/jkN0oBQfpPmeOdvkgFlLJ.SmWv9i';

// Prepare and execute the SQL query
$sql = "UPDATE users SET password_hash = ? WHERE employee_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param('si', $new_password_hash, $employee_id);
    if ($stmt->execute()) {
        echo "Password updated successfully.";
    } else {
        echo "Error updating password: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}

// Close the connection
$conn->close();
?>
