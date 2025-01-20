<?php
require_once '../php/db_connection.php'; // Replace with your database connection file

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'] ?? null;
    $email = $data['email'] ?? null;
    $current_employee_id = $data['exclude_id'];

    try {
        error_log("Gotten employee_id: " . $current_employee_id);
        error_log("Inputted username: " . $username);
        error_log("Inputted email: " . $email);

        // Check username validation
        if ($username) {
            $stmt = $conn->prepare("SELECT username FROM users WHERE employee_id = ?");
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("i", $current_employee_id);
            $stmt->execute();
            $stmt->bind_result($current_username);
            $stmt->fetch();
            $stmt->close();
            error_log("Searched table for username, current_username: " . $current_username);

            if ($current_username === $username) {
                echo json_encode([
                    'exists' => true,
                    'message' => "You're already using this username"
                ]);
                error_log("Matched username results: You're already using this username");
                exit;
            }

            $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND employee_id != ?");
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("si", $username, $current_employee_id);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
            error_log("Username count for other users: " . $count);

            if ($count > 0) {
                echo json_encode(['exists' => true, 'message' => 'Username already taken']);
                exit;
            }
        }

        // Check email validation
        if ($email) {
            $stmt = $conn->prepare("SELECT email FROM users WHERE employee_id = ?");
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("i", $current_employee_id);
            $stmt->execute();
            $stmt->bind_result($current_email);
            $stmt->fetch();
            $stmt->close();
            error_log("Searched table for email, current_email: " . $current_email);

            if ($current_email === $email) {
                echo json_encode([
                    'email_exists' => true,
                    'email_message' => "You're already using this email"
                ]);
                error_log("Matched email results: You're already using this email");
                exit;
            }

            $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND employee_id != ?");
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("si", $email, $current_employee_id);
            $stmt->execute();
            $stmt->bind_result($email_count);
            $stmt->fetch();
            $stmt->close();
            error_log("Email count for other users: " . $email_count);

            if ($email_count > 0) {
                echo json_encode([
                    'email_exists' => true,
                    'email_message' => 'Email already in use'
                ]);
                exit;
            }
        }

        echo json_encode(['exists' => false, 'email_exists' => false]);
    } catch (Exception $e) {
        error_log("Error occurred: " . $e->getMessage());
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
} else {
    error_log("Invalid request method");
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
}
?>
