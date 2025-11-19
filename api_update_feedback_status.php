<?php
header('Content-Type: application/json; charset=utf-8');

include 'db_connection.php';
check_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $feedback_id = isset($input['feedback_id']) ? intval($input['feedback_id']) : 0;
    $status = isset($input['status']) ? sanitize_input($input['status']) : '';
    
    if (!$feedback_id || !$status) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit();
    }
    
    $query = "UPDATE feedback SET status = ? WHERE feedback_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $feedback_id);
    
    if ($stmt->execute()) {
        // Log audit action
        log_audit_action($_SESSION['user_id'], 'update_feedback_status', 'feedback', $feedback_id);
        
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }
    
    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
