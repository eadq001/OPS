<?php
include 'db_connection.php';
check_admin();

header('Content-Type: application/json');

try {
    // Get all audit logs with user information
    $query = "
        SELECT 
            a.log_id,
            a.user_id,
            a.action,
            a.table_name,
            a.record_id,
            a.old_values,
            a.new_values,
            a.ip_address,
            a.created_at,
            u.username
        FROM audit_log a
        LEFT JOIN users u ON a.user_id = u.user_id
        ORDER BY a.created_at DESC
        LIMIT 1000
    ";
    
    $result = $conn->query($query);
    $logs = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'success' => true,
        'logs' => $logs
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
