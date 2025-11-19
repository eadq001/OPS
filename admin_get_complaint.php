<?php
include 'db_connection.php';
check_admin();

header('Content-Type: application/json');

$id = sanitize_input($_GET['id']);

try {
    $query = "
        SELECT c.*, u.first_name, u.last_name, u.email
        FROM complaints c
        JOIN users u ON c.user_id = u.user_id
        WHERE c.complaint_id = ?
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $complaint = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $complaint['student_name'] = $complaint['first_name'] . ' ' . $complaint['last_name'];

    $files_query = "SELECT * FROM complaint_files WHERE complaint_id = ?";
    $files_stmt = $conn->prepare($files_query);
    $files_stmt->bind_param("i", $id);
    $files_stmt->execute();
    $files = $files_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $files_stmt->close();

    $remarks_query = "SELECT * FROM admin_remarks WHERE complaint_id = ? ORDER BY created_at DESC";
    $remarks_stmt = $conn->prepare($remarks_query);
    $remarks_stmt->bind_param("i", $id);
    $remarks_stmt->execute();
    $remarks = $remarks_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $remarks_stmt->close();

    echo json_encode([
        'success' => true,
        'complaint' => $complaint,
        'files' => $files,
        'remarks' => $remarks
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
