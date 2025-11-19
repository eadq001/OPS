<?php
// Set error handling before anything else
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Clear any output buffer
ob_start();

// Ensure JSON response FIRST
header('Content-Type: application/json; charset=utf-8');

try {
    include 'db_connection.php';
} catch (Exception $e) {
    ob_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed.', 'error' => 'db_connection_failed']);
    exit();
}

// Clear buffer before processing
ob_clean();

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Handle both multipart/form-data and application/x-www-form-urlencoded
        $course = isset($_POST['course']) ? sanitize_input($_POST['course']) : (isset($_GET['course']) ? sanitize_input($_GET['course']) : '');
        $department = isset($_POST['department']) ? sanitize_input($_POST['department']) : (isset($_GET['department']) ? sanitize_input($_GET['department']) : '');
        $username = isset($_POST['username']) ? sanitize_input($_POST['username']) : (isset($_GET['username']) ? sanitize_input($_GET['username']) : '');
        $password = isset($_POST['password']) ? sanitize_input($_POST['password']) : (isset($_GET['password']) ? sanitize_input($_GET['password']) : '');
        $confirm_password = isset($_POST['confirm_password']) ? sanitize_input($_POST['confirm_password']) : (isset($_GET['confirm_password']) ? sanitize_input($_GET['confirm_password']) : '');

        // Validation
        if (empty($username) || empty($password)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Username and password are required.', 'error' => 'missing_fields']);
            exit();
        }

        if (strlen($username) < 5 || strlen($username) > 20) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Username must be 5-20 characters.', 'error' => 'invalid_username_length']);
            exit();
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Username can only contain letters, numbers, and underscores.', 'error' => 'invalid_username_chars']);
            exit();
        }

        if ($password !== $confirm_password) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Passwords do not match.', 'error' => 'password_mismatch']);
            exit();
        }

        if (strlen($password) < 8) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long.', 'error' => 'weak_password']);
            exit();
        }

        // Check if username already exists
        $check_query = "SELECT user_id FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_query);
        
        if (!$check_stmt) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error occurred.', 'error' => 'db_error']);
            exit();
        }

        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Username already exists. Please choose another.', 'error' => 'username_exists']);
            $check_stmt->close();
            exit();
        }
        $check_stmt->close();

        // Hash password
        $hashed_password = hash_password($password);

        // Insert new user
        $insert_query = "INSERT INTO users (username, password, course, department, user_type, status) 
                         VALUES (?, ?, ?, ?, 'student', 'active')";
        $insert_stmt = $conn->prepare($insert_query);

        if (!$insert_stmt) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error occurred.', 'error' => 'db_error']);
            exit();
        }

        $insert_stmt->bind_param("ssss", $username, $hashed_password, $course, $department);

        if ($insert_stmt->execute()) {
            $user_id = $insert_stmt->insert_id;

            // Log audit
            log_audit_action($user_id, 'registration', 'users', $user_id);

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Account created successfully! Redirecting to login...',
                'user_id' => $user_id
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to create account. Please try again.', 'error' => 'insert_error']);
        }

        $insert_stmt->close();
    } catch (Exception $e) {
        http_response_code(500);
        error_log("Registration exception: " . $e->getMessage() . " | " . $e->getFile() . ":" . $e->getLine());
        echo json_encode(['success' => false, 'message' => 'An error occurred.', 'error' => 'exception']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.', 'error' => 'invalid_method']);
}
?>
