<?php
session_start();
$error = [];

if (isset($_POST['login'])) {
    require_once 'db_connection.php';

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username)) {
        $error['username'] = 'Please enter your username';
    }

    if (empty($password)) {
        $error['password'] = 'Please enter your password';
    }

    if (empty($error)) {
        $stmt = $conn->prepare("SELECT employee_id, password_hash, user_role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($employee_id, $password_hash, $user_role);
            $stmt->fetch();

            if (password_verify($password, $password_hash)) {
                // Store only the employee_id and user_role in session
                $_SESSION['employee_id'] = $employee_id;
                $_SESSION['user_role'] = $user_role;

                // Successful login, send a success response with redirect URL
                echo json_encode(['success' => true, 'redirect_url' => $user_role === 'super_admin' ? 'super_admin-interface/mainpage.php' : 'some_other_page.html']);
                exit;
            } else {
                // Incorrect password
                $error['password'] = 'You entered an incorrect password';
            }
        } else {
            // Username does not exist
            $error['username'] = 'Account credentials do not exist in our system';
        }
        $stmt->close();
    }

    // If there are errors, send them back in JSON format
    echo json_encode(['success' => false, 'errors' => $error]);
    exit;
}
?>



<script>
// Display server-side errors using JavaScript to trigger tooltips
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($error['username'])): ?>
        showTooltip(document.getElementById('username-error'), "<?php echo $error['username']; ?>");
    <?php endif; ?>
    
    <?php if (!empty($error['password'])): ?>
        showTooltip(document.getElementById('password-error'), "<?php echo $error['password']; ?>");
    <?php endif; ?>
});
</script>
