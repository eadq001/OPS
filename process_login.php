<?php
include 'db_connection.php';

// Ensure JSON response
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? sanitize_input($_POST['username']) : '';
    $password = isset($_POST['password']) ? sanitize_input($_POST['password']) : '';
    $remember = isset($_POST['remember']) ? 1 : 0;

    // Validation
    if (empty($username) || empty($password)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Username and password are required.', 'error' => 'missing_fields']);
        exit();
    }

    // Query to get user
    $query = "SELECT user_id, username, password, email, first_name, last_name, user_type, status FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred.', 'error' => 'db_error', 'db_error' => $conn->error]);
        exit();
    }

    $stmt->bind_param("s", $username);
    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Query execution failed.', 'error' => 'query_error']);
        $stmt->close();
        exit();
    }

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Check if account is active
        if ($user['status'] !== 'active') {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Account is inactive. Contact administrator.', 'error' => 'inactive_account']);
            $stmt->close();
            exit();
        }
        
        // Verify password
        if (verify_password($password, $user['password'])) {
            // Password is correct - set session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['user_type'] = $user['user_type'];
            
            // Set remember me cookie (7 days)
            if ($remember) {
                setcookie('remember_user', $username, time() + (7 * 24 * 60 * 60), '/', '', false, true);
            }

            // Log audit
            log_audit_action($user['user_id'], 'login', 'users', $user['user_id']);

            // Redirect based on user type
            $redirect = ($user['user_type'] === 'admin') ? 'admin_dashboard.php' : 'student_dashboard.php';

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Login successful',
                'redirect_to' => $redirect,
                'user_type' => $user['user_type']
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Invalid password.', 'error' => 'invalid_password']);
        }
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Username not found or account is inactive.', 'error' => 'user_not_found']);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.', 'error' => 'invalid_method']);
}
?>
