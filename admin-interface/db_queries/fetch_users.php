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

// Query to fetch data from the user_activities table
$query = "SELECT 
            performed_by,
            activity_type,
            details,
            date_of_activity
          FROM user_activities";

$result = $conn->query($query);

// Check if query executed successfully
if ($result === false) {
    echo json_encode(["error" => "Failed to execute query: " . $conn->error]);
    $conn->close();
    exit;
}

// Fetch all rows as an associative array
$uaactivities = [];
while ($row = $result->fetch_assoc()) {
    // Add prefix to performed_by based on activity_type
    switch (strtolower($row['activity_type'])) {
        case 'sales':
            $row['performed_by'] = 'SSM-000' . $row['performed_by'];
            break;
        case 'supply_chain':
            $row['performed_by'] = 'SCM-000' . $row['performed_by'];
            break;
        case 'inventory':
            $row['performed_by'] = 'IVM-000' . $row['performed_by'];
            break;
    }
    $uaactivities[] = $row; // Add the modified row to the array
}

// Return the data as JSON
echo json_encode(["uaactivities" => $uaactivities]);

// Close the connection
$conn->close();
?>
