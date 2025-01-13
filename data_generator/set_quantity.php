<?php
require_once 'db_connection.php';

try {
    $query = "UPDATE products SET quantity = 120";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
