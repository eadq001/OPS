<?php
include 'db_connection.php';
check_admin();

header('Content-Type: application/json');

try {
    // Get statistics
    $total_complaints = $conn->query("SELECT COUNT(*) as count FROM complaints")->fetch_assoc()['count'];
    $pending_complaints = $conn->query("SELECT COUNT(*) as count FROM complaints WHERE status IN ('Submitted', 'Under Review')")->fetch_assoc()['count'];
    $total_feedback = $conn->query("SELECT COUNT(*) as count FROM feedback")->fetch_assoc()['count'];
    $resolved = $conn->query("SELECT COUNT(*) as count FROM complaints WHERE status = 'Resolved'")->fetch_assoc()['count'];

    // Get complaints by status for chart
    $statusQuery = "
        SELECT status, COUNT(*) as count
        FROM complaints
        GROUP BY status
    ";
    $statusResult = $conn->query($statusQuery);
    $statusLabels = [];
    $statusData = [];
    while ($row = $statusResult->fetch_assoc()) {
        $statusLabels[] = $row['status'];
        $statusData[] = (int)$row['count'];
    }

    // Get complaints by type for chart
    $typeQuery = "
        SELECT complaint_type, COUNT(*) as count
        FROM complaints
        GROUP BY complaint_type
    ";
    $typeResult = $conn->query($typeQuery);
    $typeLabels = [];
    $typeData = [];
    while ($row = $typeResult->fetch_assoc()) {
        $typeLabels[] = $row['complaint_type'];
        $typeData[] = (int)$row['count'];
    }

    // Get recent complaints
    $recent_query = "
        SELECT c.complaint_id, c.reference_number, c.complaint_type, c.status, c.created_at, 
               CONCAT(u.first_name, ' ', u.last_name) as student_name
        FROM complaints c
        JOIN users u ON c.user_id = u.user_id
        ORDER BY c.created_at DESC
        LIMIT 10
    ";
    $recent_complaints = $conn->query($recent_query)->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'success' => true,
        'stats' => [
            'total_complaints' => $total_complaints,
            'pending_complaints' => $pending_complaints,
            'total_feedback' => $total_feedback,
            'resolved' => $resolved
        ],
        'chart_data' => [
            'status' => [
                'labels' => count($statusLabels) > 0 ? $statusLabels : ['No Data'],
                'data' => count($statusData) > 0 ? $statusData : [0]
            ],
            'type' => [
                'labels' => count($typeLabels) > 0 ? $typeLabels : ['No Data'],
                'data' => count($typeData) > 0 ? $typeData : [0]
            ]
        ],
        'recent_complaints' => $recent_complaints
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
