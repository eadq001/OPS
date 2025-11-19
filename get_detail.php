<?php
include 'db_connection.php';
check_login();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];
$id = sanitize_input($_GET['id']);
$type = sanitize_input($_GET['type']);

try {
    if ($type === 'complaint') {
        // Get complaint details
        $query = "
            SELECT c.*, u.first_name, u.last_name, u.email, u.course
            FROM complaints c
            JOIN users u ON c.user_id = u.user_id
            WHERE c.complaint_id = ? AND c.user_id = ?
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $details = $result->fetch_assoc();
        $stmt->close();

        if (!$details) {
            echo json_encode(['success' => false, 'error' => 'Complaint not found']);
            exit();
        }

        // Get files
        $files_query = "SELECT file_id, original_filename, stored_filename, file_size FROM complaint_files WHERE complaint_id = ?";
        $files_stmt = $conn->prepare($files_query);
        $files_stmt->bind_param("i", $id);
        $files_stmt->execute();
        $files = $files_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $files_stmt->close();

        $details['files'] = $files;
        $details['student_name'] = $details['first_name'] . ' ' . $details['last_name'];
        $details['submitted_date'] = $details['created_at'];

    } else {
        // Get feedback details
        $query = "
            SELECT f.*, u.first_name, u.last_name, u.email, u.course
            FROM feedback f
            JOIN users u ON f.user_id = u.user_id
            WHERE f.feedback_id = ? AND f.user_id = ?
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $details = $result->fetch_assoc();
        $stmt->close();

        if (!$details) {
            echo json_encode(['success' => false, 'error' => 'Feedback not found']);
            exit();
        }

        $details['files'] = [];
        $details['student_name'] = $details['first_name'] . ' ' . $details['last_name'];
        $details['submitted_date'] = $details['created_at'];
    }

    // Get remarks
    $remarks_query = "SELECT * FROM admin_remarks WHERE ";
    if ($type === 'complaint') {
        $remarks_query .= "complaint_id = ? ORDER BY created_at DESC";
    } else {
        $remarks_query .= "feedback_id = ? ORDER BY created_at DESC";
    }
    
    $remarks_stmt = $conn->prepare($remarks_query);
    $remarks_stmt->bind_param("i", $id);
    $remarks_stmt->execute();
    $remarks = $remarks_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $remarks_stmt->close();

    // Get status history
    $history_query = "SELECT * FROM status_history WHERE ";
    if ($type === 'complaint') {
        $history_query .= "complaint_id = ? ORDER BY changed_at ASC";
    } else {
        $history_query .= "feedback_id = ? ORDER BY changed_at ASC";
    }
    
    $history_stmt = $conn->prepare($history_query);
    $history_stmt->bind_param("i", $id);
    $history_stmt->execute();
    $history = $history_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $history_stmt->close();

    echo json_encode([
        'success' => true,
        'details' => $details,
        'remarks' => $remarks,
        'history' => $history
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
