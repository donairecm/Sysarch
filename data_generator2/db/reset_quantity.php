<?php
require_once 'db_connection.php';

$query = "UPDATE products SET quantity = 170";
if ($conn->query($query)) {
    echo "Product quantities reset successfully.";
} else {
    http_response_code(500);
    echo "Error resetting quantities: " . $conn->error;
}
$conn->close();
?>
