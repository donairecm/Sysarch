<?php
require_once '../php/db_connection.php'; // Replace with your database connection file

header('Content-Type: application/json'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'];
    $exclude_id = $data['exclude_id'];

    try {
        $stmt = $conn->prepare("SELECT username FROM users WHERE employee_id != ?");
        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("i", $exclude_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $usernames = [];

        while ($row = $result->fetch_assoc()) {
            $usernames[] = $row['username'];
        }

        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND employee_id != ?");
        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("si", $username, $exclude_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        // Log the results
        error_log("Employee ID used for search: $exclude_id");
        error_log("Username inputted by the user: $username");
        error_log("Table where the search happened: users");
        error_log("Results of usernames where the inputted was compared to: " . implode(', ', $usernames));

        echo json_encode(['exists' => $count > 0]);
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
}
?>
