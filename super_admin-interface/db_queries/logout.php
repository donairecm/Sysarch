<?php
session_start();
require_once '../../login-interface/php/db_connection.php';

// Check if the user is logged in
if (isset($_SESSION['employee_id'])) {
    $employee_id = $_SESSION['employee_id'];

    // Update the user's last logout time and status to offline
    $update_stmt = $conn->prepare("UPDATE users SET last_logout = NOW(), user_status = 'offline' WHERE employee_id = ?");
    $update_stmt->bind_param("s", $employee_id);
    $update_stmt->execute();
    $update_stmt->close();

    // Destroy the session
    session_unset();
    session_destroy();
}

// Redirect to the login page
header("Location: ../../index.php");
exit;
?>
