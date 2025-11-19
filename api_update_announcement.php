<?php
ob_start();
header('Content-Type: application/json');

try {
    include 'db_connection.php';
    
    // Check if user is admin
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
        ob_clean();
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    // Check if POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        exit;
    }

    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    $announcement_id = isset($data['announcement_id']) ? intval($data['announcement_id']) : 0;
    $title = isset($data['title']) ? trim($data['title']) : '';
    $description = isset($data['description']) ? trim($data['description']) : '';
    $priority = isset($data['priority']) ? trim($data['priority']) : 'Medium';

    // Validate input
    if (empty($announcement_id)) {
        ob_clean();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Announcement ID is required']);
        exit;
    }

    if (empty($title)) {
        ob_clean();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Title is required']);
        exit;
    }

    if (empty($description)) {
        ob_clean();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Description is required']);
        exit;
    }

    if (!in_array($priority, ['Low', 'Medium', 'High'])) {
        $priority = 'Medium';
    }

    // Get current user ID
    $user_id = $_SESSION['user_id'];

    // Check if announcement exists
    $checkQuery = "SELECT announcement_id FROM announcements WHERE announcement_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('i', $announcement_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        ob_clean();
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Announcement not found']);
        exit;
    }
    $stmt->close();

    // Update announcement
    $updateQuery = "UPDATE announcements SET title = ?, description = ?, priority = ? WHERE announcement_id = ?";
    $stmt = $conn->prepare($updateQuery);
    
    if (!$stmt) {
        ob_clean();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param('sssi', $title, $description, $priority, $announcement_id);

    if ($stmt->execute()) {
        // Log audit action
        log_audit_action($user_id, 'UPDATE', 'announcements', $announcement_id, 'Updated announcement: ' . $title);

        ob_clean();
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Announcement updated successfully']);
    } else {
        ob_clean();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to update announcement: ' . $stmt->error]);
    }

    $stmt->close();
} catch (Exception $e) {
    ob_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
