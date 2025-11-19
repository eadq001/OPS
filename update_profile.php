<?php
include 'db_connection.php';
check_login();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $first_name = sanitize_input($_POST['first_name']);
    $last_name = sanitize_input($_POST['last_name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $course = sanitize_input($_POST['course']);
    $department = sanitize_input($_POST['department']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address']);
        exit();
    }

    // Check if email is already used by another user
    $email_check = "SELECT user_id FROM users WHERE email = ? AND user_id != ?";
    $stmt = $conn->prepare($email_check);
    $stmt->bind_param("si", $email, $user_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already in use']);
        $stmt->close();
        exit();
    }
    $stmt->close();

    // Update profile
    $update_query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, course = ?, department = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $phone, $course, $department, $user_id);

    if ($stmt->execute()) {
        // Update session
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['email'] = $email;

        log_audit_action($user_id, 'profile_updated', 'users', $user_id);

        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
    }

    $stmt->close();
}
?>
