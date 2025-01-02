<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['employee_id'])) {
    header('Location: ../../login-interface/index.php');
    exit;
}

// Retrieve the employee_id from the session
$employee_id = $_SESSION['employee_id'];

// Fetch specific details from the database using the employee_id
require_once $_SERVER['DOCUMENT_ROOT'] . '/best_aluminum_sales_corps/Sysarch/login-interface/php/db_connection.php';

$stmt = $conn->prepare("SELECT first_name, middle_name, last_name, profile_pic, profile_cover FROM users WHERE employee_id = ?");
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$stmt->bind_result($first_name, $middle_name, $last_name, $profile_pic, $profile_cover);
$stmt->fetch();
$stmt->close();

// Process the middle name to only display the first letter
$middle_initial = $middle_name ? strtoupper(substr($middle_name, 0, 1)) : '';

// Set default profile and cover pictures if none are found
$profile_pic = $profile_pic ?: 'uploads/profile/default_profile_picture.png'; // Default profile picture
$profile_cover = $profile_cover ?: 'uploads/cover/default_cover_picture.png'; // Default cover picture

$profilePicPath = '/best_aluminum_sales_corps/Sysarch/' . $profile_pic;
$profileCoverPath = '/best_aluminum_sales_corps/Sysarch/' . $profile_cover;

// Return the data as an associative array
return [
    'firstName' => $first_name,
    'middleInitial' => $middle_initial,
    'lastName' => $last_name,
    'profilePic' => $profilePicPath,
    'profileCover' => $profileCoverPath,
    'employeeID' => $employee_id
];
?>
