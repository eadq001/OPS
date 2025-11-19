<?php
include 'db_connection.php';
check_login();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

try {
    // Get total submissions count
    $query = "SELECT COUNT(*) as total FROM (
        SELECT complaint_id FROM complaints WHERE user_id = ?
        UNION ALL
        SELECT feedback_id FROM feedback WHERE user_id = ?
    ) as all_submissions";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $total = $result->fetch_assoc()['total'];
    $stmt->close();

    // Get complaints by status
    $status_query = "SELECT status, COUNT(*) as count FROM complaints WHERE user_id = ? GROUP BY status";
    $stmt = $conn->prepare($status_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $pending = 0;
    $under_review = 0;
    $resolved = 0;
    
    while ($row = $result->fetch_assoc()) {
        switch ($row['status']) {
            case 'Submitted':
                $pending += $row['count'];
                break;
            case 'Under Review':
            case 'In Progress':
                $under_review += $row['count'];
                break;
            case 'Resolved':
                $resolved += $row['count'];
                break;
        }
    }
    $stmt->close();

    // Get unread notifications count
    $notif_query = "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = FALSE";
    $stmt = $conn->prepare($notif_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $unread_notifications = $stmt->get_result()->fetch_assoc()['count'];
    $stmt->close();

    // Get announcements
    $ann_query = "SELECT announcement_id, title, description, created_at FROM announcements WHERE status = 'active' ORDER BY created_at DESC LIMIT 3";
    $stmt = $conn->prepare($ann_query);
    $stmt->execute();
    $announcements = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Get recent submissions
    $recent_query = "
        SELECT complaint_id as id, reference_number, complaint_type as type, 'complaint' as type_code, status, created_at as date FROM complaints WHERE user_id = ?
        UNION ALL
        SELECT feedback_id as id, reference_number, feedback_type as type, 'feedback' as type_code, status, created_at as date FROM feedback WHERE user_id = ?
        ORDER BY date DESC LIMIT 5
    ";
    $stmt = $conn->prepare($recent_query);
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
    $recent_submissions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    echo json_encode([
        'success' => true,
        'total' => $total,
        'pending' => $pending,
        'under_review' => $under_review,
        'resolved' => $resolved,
        'unread_notifications' => $unread_notifications,
        'announcements' => $announcements,
        'recent_submissions' => $recent_submissions
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
