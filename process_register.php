<?php
include 'db_connection.php';

// Ensure JSON response
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = isset($_POST['first_name']) ? sanitize_input($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? sanitize_input($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
    $course = isset($_POST['course']) ? sanitize_input($_POST['course']) : '';
    $department = isset($_POST['department']) ? sanitize_input($_POST['department']) : '';
    $username = isset($_POST['username']) ? sanitize_input($_POST['username']) : '';
    $password = isset($_POST['password']) ? sanitize_input($_POST['password']) : '';
    $confirm_password = isset($_POST['confirm_password']) ? sanitize_input($_POST['confirm_password']) : '';

    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'All required fields must be filled.', 'error' => 'missing_fields']);
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

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid email address.', 'error' => 'invalid_email']);
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

    // Check if email already exists
    $email_check_query = "SELECT user_id FROM users WHERE email = ?";
    $email_check_stmt = $conn->prepare($email_check_query);
    
    if (!$email_check_stmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred.', 'error' => 'db_error']);
        exit();
    }

    $email_check_stmt->bind_param("s", $email);
    $email_check_stmt->execute();
    $email_check_result = $email_check_stmt->get_result();

    if ($email_check_result->num_rows > 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Email already registered. Please use another email.', 'error' => 'email_exists']);
        $email_check_stmt->close();
        exit();
    }
    $email_check_stmt->close();

    // Hash password
    $hashed_password = hash_password($password);

    // Insert new user
    $insert_query = "INSERT INTO users (username, password, email, first_name, last_name, course, department, user_type, phone, status) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, 'student', ?, 'active')";
    $insert_stmt = $conn->prepare($insert_query);

    if (!$insert_stmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error occurred.', 'error' => 'db_error', 'db_error_msg' => $conn->error]);
        exit();
    }

    $insert_stmt->bind_param("sssssss", $username, $hashed_password, $email, $first_name, $last_name, $course, $department, $phone);

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
        echo json_encode(['success' => false, 'message' => 'Failed to create account. Please try again.', 'error' => 'insert_error', 'db_error_msg' => $insert_stmt->error]);
    }

    $insert_stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.', 'error' => 'invalid_method']);
}
?>
