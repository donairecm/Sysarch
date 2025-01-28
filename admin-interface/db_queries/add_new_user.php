<?php
header("Content-Type: application/json");
session_start(); // Start the session

// Enable error reporting for debugging
ini_set('display_errors', 0); // Disable errors in the output (use logs instead)
ini_set('log_errors', 1); // Log errors to a file
error_reporting(E_ALL); // Report all PHP errors
$log_file = "/path/to/php-error.log"; // Change to a valid path on your server
ini_set('error_log', $log_file);

// Log debug messages
error_log("Debug: Script execution started");

// Ensure the user is logged in
if (!isset($_SESSION['employee_id'])) {
    error_log("Error: User not authenticated.");
    echo json_encode(["success" => false, "error" => "User not authenticated."]);
    exit();
}

// Retrieve employee_id from the session
$employee_id_with_prefix = $_SESSION['employee_id'];
error_log("Debug: Employee ID with prefix: $employee_id_with_prefix");

// Remove the prefix to get the numeric employee_id
$numeric_employee_id = (int)preg_replace('/^[A-Z]+-/', '', $employee_id_with_prefix);
error_log("Debug: Numeric Employee ID: $numeric_employee_id");

// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // Update as needed
$dbname = "bestaluminumsalescorps_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Error: Database connection failed - " . $conn->connect_error);
    echo json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

error_log("Debug: Database connected successfully");

// Retrieve and decode JSON input
$rawInput = file_get_contents("php://input");
$data = json_decode($rawInput, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("Error: Invalid JSON input - " . json_last_error_msg());
    echo json_encode(["success" => false, "error" => "Invalid JSON input."]);
    exit();
}

error_log("Debug: JSON input decoded successfully: " . print_r($data, true));

// Check if action is `get_usernames`
if (isset($data["action"]) && $data["action"] === "get_usernames") {
    error_log("Debug: Fetching usernames");

    // Fetch all usernames from the database
    $query = "SELECT username FROM users";
    $result = $conn->query($query);

    if ($result) {
        $usernames = [];
        while ($row = $result->fetch_assoc()) {
            $usernames[] = $row["username"];
        }
        error_log("Debug: Usernames fetched successfully");
        echo json_encode(["success" => true, "usernames" => $usernames]);
    } else {
        error_log("Error: Failed to fetch usernames - " . $conn->error);
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
    error_log("Error: Invalid input provided");
    echo json_encode(["success" => false, "error" => "Invalid input."]);
    exit();
}

error_log("Debug: Input validated successfully");

// Map role ID to ENUM values in the database
$roleMapping = [
    "1" => "inventory_manager",
    "2" => "sales_manager",
    "3" => "supply_chain_manager"
];

$user_role = $roleMapping[$user_role_id] ?? null;

if (!$user_role) {
    error_log("Error: Invalid role selected");
    echo json_encode(["success" => false, "error" => "Invalid role selected."]);
    exit();
}

// Hash the password
$password_hash = password_hash($password, PASSWORD_BCRYPT);
error_log("Debug: Password hashed successfully");

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
        created_on, 
        user_status
    ) VALUES (
        'John',
        'Demo',
        'Doe',
        'changeyour.email@sample.com',
        '12345678987',
        '12345678987',
        ?, ?, ?, NOW(), 'offline'
    )
";

$stmt = $conn->prepare($insertUserQuery);

if (!$stmt) {
    error_log("Error: Prepare statement failed - " . $conn->error);
    echo json_encode(["success" => false, "error" => "Error preparing query."]);
    exit();
}

$stmt->bind_param("sss", $username, $password_hash, $user_role);

if ($stmt->execute()) {
    error_log("Debug: User added successfully");

    // Log the activity in `user_activities` table
    $activityDetails = "Added a new user: " . $username;
    $activityQuery = "
        INSERT INTO user_activities (performed_by, activity_type, details, date_of_activity)
        VALUES (?, 'admin', ?, NOW())
    ";

    $activityStmt = $conn->prepare($activityQuery);

    if (!$activityStmt) {
        error_log("Error: Prepare statement for logging failed - " . $conn->error);
        echo json_encode(["success" => false, "error" => "Error preparing activity log query."]);
        exit();
    }

    $activityStmt->bind_param("is", $numeric_employee_id, $activityDetails);

    if ($activityStmt->execute()) {
        error_log("Debug: Activity logged successfully");
        echo json_encode(["success" => true, "message" => "User added successfully."]);
    } else {
        error_log("Error: Failed to log activity - " . $activityStmt->error);
        echo json_encode(["success" => false, "error" => "Error logging user activity."]);
    }
} else {
    error_log("Error: Failed to add user - " . $stmt->error);
    echo json_encode(["success" => false, "error" => "Error adding user."]);
}

// Close the connection
$conn->close();
?>
