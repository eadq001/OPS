<?php
include 'db_connection.php';
check_admin();

header('Content-Type: application/json');

try {
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

    // Get statistics
    $statsQuery = "
        SELECT 
            COUNT(*) as total_complaints,
            SUM(CASE WHEN status IN ('Submitted', 'Under Review', 'In Progress') THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) as resolved
        FROM complaints
        WHERE DATE(created_at) BETWEEN ? AND ?
    ";
    
    $stmt = $conn->prepare($statsQuery);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $statsResult = $stmt->get_result()->fetch_assoc();

    // Get feedback count
    $feedbackQuery = "
        SELECT COUNT(*) as total_feedback
        FROM feedback
        WHERE DATE(created_at) BETWEEN ? AND ?
    ";
    
    $stmt = $conn->prepare($feedbackQuery);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $feedbackResult = $stmt->get_result()->fetch_assoc();

    // Get complaints by status
    $statusQuery = "
        SELECT status, COUNT(*) as count
        FROM complaints
        WHERE DATE(created_at) BETWEEN ? AND ?
        GROUP BY status
    ";
    
    $stmt = $conn->prepare($statusQuery);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $statusResult = $stmt->get_result();
    $statusBreakdown = $statusResult->fetch_all(MYSQLI_ASSOC);

    // Get complaints by type
    $typeQuery = "
        SELECT complaint_type as type, COUNT(*) as count
        FROM complaints
        WHERE DATE(created_at) BETWEEN ? AND ?
        GROUP BY complaint_type
    ";
    
    $stmt = $conn->prepare($typeQuery);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $typeResult = $stmt->get_result();
    $typeBreakdown = $typeResult->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'success' => true,
        'stats' => [
            'total_complaints' => (int)($statsResult['total_complaints'] ?? 0),
            'pending' => (int)($statsResult['pending'] ?? 0),
            'resolved' => (int)($statsResult['resolved'] ?? 0),
            'total_feedback' => (int)($feedbackResult['total_feedback'] ?? 0)
        ],
        'breakdown' => [
            'status' => $statusBreakdown,
            'type' => $typeBreakdown
        ]
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
