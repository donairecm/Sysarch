<?php
require_once '../php/db_connection.php'; // Replace with your database connection file

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'];
    $current_employee_id = $data['exclude_id'];

    try {
        // Fetch the username of the current logged-in employee
        $stmt = $conn->prepare("SELECT username FROM users WHERE employee_id = ?");
        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("i", $current_employee_id);
        $stmt->execute();
        $stmt->bind_result($current_username);
        $stmt->fetch();
        $stmt->close();

        // Check if the provided username matches the current user's username
        if ($current_username === $username) {
            echo json_encode([
                'exists' => true,
                'message' => "You're already using this username :)"
            ]);
            exit;
        }

        // Check if the username is taken by another user
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND employee_id != ?");
        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("si", $username, $current_employee_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        echo json_encode(['exists' => $count > 0]);
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
}
?>
