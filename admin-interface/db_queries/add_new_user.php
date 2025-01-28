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

if (isset($data["action"]) && $data["action"] === "get_usernames") {
    // Fetch all usernames from the database
    $query = "SELECT username FROM users";
    $result = $conn->query($query);

    if ($result) {
        $usernames = [];
        while ($row = $result->fetch_assoc()) {
            $usernames[] = $row["username"];
        }
        echo json_encode(["success" => true, "usernames" => $usernames]);
    } else {
        echo json_encode(["success" => false, "error" => "Failed to fetch usernames."]);
    }
    $conn->close();
    exit();
}

// Handle user creation
$username = $data["username"] ?? null;
$password = $data["password"] ?? null;
$user_role_id = $data["user_role"] ?? null;

// Validate input
if (!$username || !$password || !$user_role_id) {
    echo json_encode(["success" => false, "error" => "Invalid input."]);
    exit();
}

// Map role ID to ENUM values in the database
$roleMapping = [
    "1" => "inventory_manager",
    "2" => "sales_manager",
    "3" => "supply_chain_manager"
];

$user_role = $roleMapping[$user_role_id] ?? null;

if (!$user_role) {
    echo json_encode(["success" => false, "error" => "Invalid role selected."]);
    exit();
}

// Hash the password
$password_hash = password_hash($password, PASSWORD_BCRYPT);

// Insert the user into the `users` table
$insertUserQuery = "
    INSERT INTO users (
        first_name, 
        last_name, 
        middle_name, 
        email,
        phone_number_1, 
        phone_number_2, 
        username, 
        password_hash, 
        user_role, 
        created_on
    ) VALUES (
        'change your',
        'Please',
        'name, Thank you',
        'changeyour.email@sample.com',
        '12345678987',
        '12345678987',
        '" . $conn->real_escape_string($username) . "',
        '" . $conn->real_escape_string($password_hash) . "',
        '" . $conn->real_escape_string($user_role) . "',
        NOW(),
    )
";

if ($conn->query($insertUserQuery) === TRUE) {
    // Log the activity in `user_activities` table
    $activityDetails = "Added a new user: " . $username;
    $activityQuery = "
        INSERT INTO user_activities (performed_by, activity_type, details, date_of_activity)
        VALUES (
            '" . $conn->real_escape_string($numeric_employee_id) . "',
            'admin',
            '" . $conn->real_escape_string($activityDetails) . "',
            NOW()
        )
    ";
    if ($conn->query($activityQuery) === TRUE) {
        echo json_encode(["success" => true, "message" => "User added successfully."]);
    } else {
        echo json_encode(["success" => false, "error" => "Error logging user activity: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Error adding user: " . $conn->error]);
}

// Close the connection
$conn->close();
?>
