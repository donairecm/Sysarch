<?php
header('Content-Type: application/json');
session_start();

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

// Check if a file is uploaded
if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["success" => false, "error" => "No file uploaded or upload error."]);
    exit();
}

// File properties
$file = $_FILES['profile_picture'];
$allowed_types = ['image/jpeg', 'image/png'];
$max_file_size = 5242880; // 5MB

// Validate file type
if (!in_array($file['type'], $allowed_types)) {
    echo json_encode(["success" => false, "error" => "Only JPG or PNG files are allowed."]);
    exit();
}

// Validate file size
if ($file['size'] > $max_file_size) {
    echo json_encode(["success" => false, "error" => "File size exceeds 5MB."]);
    exit();
}

// Retrieve the current profile picture path from the database
$sql = "SELECT profile_pic FROM users WHERE employee_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$stmt->bind_result($current_profile_pic);
$stmt->fetch();
$stmt->close();

// Define the directory for profile pictures (use the existing folder)
$uploads_dir = __DIR__ . '/../uploads/profile';

// Save file with a standardized name: `profile_employeeID.extension`
$file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$new_file_name = "profile_{$employee_id}." . $file_extension;
$upload_path = "$uploads_dir/$new_file_name";

// Move the uploaded file
if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
    echo json_encode(["success" => false, "error" => "Failed to save the file."]);
    exit();
}

// Delete the old profile picture if it's not the default picture
if ($current_profile_pic && $current_profile_pic !== 'uploads/profile/default_profile_picture.png') {
    $old_profile_path = __DIR__ . '/../' . $current_profile_pic;
    if (file_exists($old_profile_path)) {
        unlink($old_profile_path); // Delete the old file
    }
}

// Update the database with the new profile picture path
$relative_path = "uploads/profile/$new_file_name";
$sql = "UPDATE users SET profile_pic = ? WHERE employee_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $relative_path, $employee_id);

if ($stmt->execute()) {
    // Log the activity in the user_activities table
    $activity_type = 'account_changes';
    $details = "Changed user's profile picture";
    $date_of_activity = date('Y-m-d H:i:s');

    $log_sql = "INSERT INTO user_activities (performed_by, activity_type, details, date_of_activity) VALUES (?, ?, ?, ?)";
    $log_stmt = $conn->prepare($log_sql);
    $log_stmt->bind_param("ssss", $employee_id, $activity_type, $details, $date_of_activity);

    if ($log_stmt->execute()) {
        echo json_encode([
            "success" => true,
            "new_profile_picture" => '/best_aluminum_sales_corps/Sysarch/' . $relative_path,
        ]);
    } else {
        echo json_encode(["success" => false, "error" => "Failed to log activity: " . $log_stmt->error]);
    }

    $log_stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Failed to update the database: " . $conn->error]);
}

$stmt->close();
$conn->close();
?>
