<?php
include 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = sanitize_input($_POST['identifier']);

    // Find user by username or email
    $query = "SELECT user_id, email, first_name FROM users WHERE (username = ? OR email = ?) AND status = 'active'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $user_id = $user['user_id'];
        $email = $user['email'];
        $first_name = $user['first_name'];

        // Generate reset token
        $reset_token = bin2hex(random_bytes(32));
        $token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store token (in a real app, use a password_reset_tokens table)
        // For this demo, we'll simulate email sending
        $reset_url = "http://localhost/OPS/reset_password.php?token=" . $reset_token . "&user_id=" . $user_id;

        // Simulate email sending (in production, use PHPMailer or similar)
        $email_body = "Dear $first_name,\n\n";
        $email_body .= "We received a request to reset your password. Click the link below to proceed:\n\n";
        $email_body .= $reset_url . "\n\n";
        $email_body .= "This link will expire in 1 hour.\n\n";
        $email_body .= "If you did not request this, please ignore this email.\n\n";
        $email_body .= "Best regards,\n";
        $email_body .= "DSJBC Portal Support Team";

        // In production, send actual email
        // mail($email, "Password Reset Request", $email_body);

        // For demo purposes, we'll just log it
        error_log("Password reset request for user $user_id: " . $reset_url);

        echo json_encode([
            'success' => true,
            'message' => 'Reset instructions have been sent to your registered email address. Please check your inbox.'
        ]);

        log_audit_action($user_id, 'password_reset_request', 'users', $user_id);
    } else {
        // Don't reveal if username/email exists (security best practice)
        echo json_encode([
            'success' => true,
            'message' => 'If an account exists with that username or email, you will receive reset instructions shortly.'
        ]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
