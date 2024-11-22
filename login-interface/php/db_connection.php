<?php
$host = 'localhost';              // Server host
$user = 'root';                   // Default username for XAMPP
$password = '';                   // Default password for XAMPP (leave empty)
$database = 'bestaluminumsalescorp-db';  // Your database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";
?>

