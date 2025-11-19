<?php
include 'db_connection.php';
check_login();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $feedback_type = sanitize_input($_POST['feedback_type']);
    $department_office = sanitize_input($_POST['department_office']);
    $description = sanitize_input($_POST['description']);

    // Validate
    if (strlen($description) < 20) {
        echo json_encode(['success' => false, 'message' => 'Feedback must be at least 20 characters.']);
        exit();
    }

    // Generate reference number
    $reference_number = generate_reference_number('FEEDBACK');

    // Insert feedback
    $insert_query = "INSERT INTO feedback (user_id, reference_number, feedback_type, department_office, description) 
                     VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("issss", $user_id, $reference_number, $feedback_type, $department_office, $description);

    if ($stmt->execute()) {
        $feedback_id = $stmt->insert_id;

        // Log audit
        log_audit_action($user_id, 'feedback_submitted', 'feedback', $feedback_id);

        // Create notification
        create_notification($user_id, 'feedback_submitted', 'Feedback Submitted', 
                           'Your feedback ' . $reference_number . ' has been submitted successfully.');

        echo json_encode([
            'success' => true,
            'message' => 'Feedback submitted successfully',
            'reference_number' => $reference_number,
            'feedback_id' => $feedback_id
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit feedback. Please try again.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
