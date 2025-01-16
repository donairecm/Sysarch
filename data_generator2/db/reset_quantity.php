<?php
require_once 'db_connection.php';

$query = "
    UPDATE products
    SET quantity = 
        CASE
            WHEN RAND() < 1/3 THEN FLOOR(80 + (RAND() * (85 - 80 + 1))) -- 80-85 with ~33% chance
            WHEN RAND() < 0.5 THEN FLOOR(85 + (RAND() * (90 - 85 + 1))) -- 85-90 with ~33% chance
            ELSE FLOOR(90 + (RAND() * (95 - 90 + 1))) -- 90-95 with ~34% chance
        END
";
if ($conn->query($query)) {
    echo "Product quantities updated successfully.";
} else {
    http_response_code(500);
    echo "Error updating quantities: " . $conn->error;
}
$conn->close();
?>
