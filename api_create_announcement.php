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

    // Get form data
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $priority = isset($_POST['priority']) ? trim($_POST['priority']) : 'Medium';

    // Validate input
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

    // Insert announcement into database
    $insertQuery = "INSERT INTO announcements (title, description, priority, created_by, created_at) 
                    VALUES (?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($insertQuery);
    if (!$stmt) {
        ob_clean();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param('sssi', $title, $description, $priority, $user_id);
    if (!$stmt) {
        ob_clean();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Binding error: ' . $stmt->error]);
        exit;
    }

    if ($stmt->execute()) {
        $announcement_id = $conn->insert_id;

        // Log audit action
        log_audit_action($user_id, 'CREATE', 'announcements', $announcement_id, 'Created announcement: ' . $title);

        ob_clean();
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Announcement created successfully',
            'announcement_id' => $announcement_id
        ]);
    } else {
        ob_clean();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to create announcement: ' . $stmt->error]);
    }

    $stmt->close();
} catch (Exception $e) {
    ob_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
