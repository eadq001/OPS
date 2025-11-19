<?php
include 'db_connection.php';
check_login();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $notification_id = sanitize_input($_POST['notification_id']);

    $update_query = "UPDATE notifications SET is_read = TRUE, read_at = NOW() WHERE notification_id = ? AND user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $notification_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update notification']);
    }
    $stmt->close();
}
?>
