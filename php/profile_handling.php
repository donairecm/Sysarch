<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['employee_id'])) {
    header('Location: ../login-interface/index.php');
    exit;
}

// Ensure the global $conn variable is accessible
global $conn;

// Debug: Check if $conn is accessible
if (!isset($conn)) {
    die("Error: Database connection not available in profile_handling.php.");
}

// Retrieve the employee_id from the session
$employee_id = $_SESSION['employee_id'];

// Retrieve user details and role
$stmt = $conn->prepare("
    SELECT first_name, middle_name, last_name, profile_pic, profile_cover, user_role 
    FROM users 
    WHERE employee_id = ?
");
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$stmt->bind_result($first_name, $middle_name, $last_name, $profile_pic, $profile_cover, $user_role);
$stmt->fetch();
$stmt->close();

// Process the middle name to only display the first letter
$middle_initial = $middle_name ? strtoupper(substr($middle_name, 0, 1)) : '';

// Set default profile and cover pictures if none are found
$profile_pic = $profile_pic ?: 'uploads/profile/default_profile_picture.png';
$profile_cover = $profile_cover ?: 'uploads/cover/default_cover_picture.png';

$profilePicPath = '/best_aluminum_sales_corps/Sysarch/' . $profile_pic;
$profileCoverPath = '/best_aluminum_sales_corps/Sysarch/' . $profile_cover;

// Determine prefix based on role
$role_prefixes = [
    'inventory_manager' => 'IVM',
    'sales_manager' => 'SSM',
    'supply_chain_manager' => 'SCM',
    'super_admin' => 'SDM',
    'admin' => 'ADM',
    // Add more roles as needed
];
$prefix = isset($role_prefixes[$user_role]) ? $role_prefixes[$user_role] : '???';

// Format the employee_id with the prefix
$formatted_employee_id = sprintf('%s-%03d', $prefix, intval($employee_id));

// Return the data as an associative array
return [
    'firstName' => $first_name,
    'middleInitial' => $middle_initial,
    'lastName' => $last_name,
    'profilePic' => $profilePicPath,
    'profileCover' => $profileCoverPath,
    'employeeID' => $formatted_employee_id
];
?>
