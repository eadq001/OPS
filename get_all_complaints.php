<?php
include 'db_connection.php';
check_admin();

header('Content-Type: application/json');

try {
    $query = "
        SELECT c.*, CONCAT(u.first_name, ' ', u.last_name) as student_name
        FROM complaints c
        JOIN users u ON c.user_id = u.user_id
        ORDER BY c.created_at DESC
    ";
    
    $result = $conn->query($query);
    $complaints = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'success' => true,
        'complaints' => $complaints
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
