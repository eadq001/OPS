<?php
include 'db_connection.php';
check_admin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaint_id = sanitize_input($_POST['complaint_id']);
    $new_status = sanitize_input($_POST['status']);
    $remarks_text = sanitize_input($_POST['remarks']);
    $admin_id = $_SESSION['user_id'];

    // Get old status
    $old_status_query = "SELECT status FROM complaints WHERE complaint_id = ?";
    $stmt = $conn->prepare($old_status_query);
    $stmt->bind_param("i", $complaint_id);
    $stmt->execute();
    $old_status = $stmt->get_result()->fetch_assoc()['status'];
    $stmt->close();

    // Update complaint status
    $update_query = "UPDATE complaints SET status = ?, updated_at = NOW() WHERE complaint_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $new_status, $complaint_id);
    $stmt->execute();
    $stmt->close();

    // Add admin remark
    $remark_query = "INSERT INTO admin_remarks (complaint_id, admin_id, remarks, status_update) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($remark_query);
    $stmt->bind_param("iiss", $complaint_id, $admin_id, $remarks_text, $new_status);
    $stmt->execute();
    $stmt->close();

    // Log status history
    $history_query = "INSERT INTO status_history (complaint_id, old_status, new_status, changed_by) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($history_query);
    $stmt->bind_param("issi", $complaint_id, $old_status, $new_status, $admin_id);
    $stmt->execute();
    $stmt->close();

    // Get student user_id to send notification
    $student_query = "SELECT user_id FROM complaints WHERE complaint_id = ?";
    $stmt = $conn->prepare($student_query);
    $stmt->bind_param("i", $complaint_id);
    $stmt->execute();
    $student_id = $stmt->get_result()->fetch_assoc()['user_id'];
    $stmt->close();

    // Create notification
    $notif_type = $new_status === 'Resolved' ? 'complaint_resolved' : 'complaint_updated';
    $notif_title = $new_status === 'Resolved' ? 'Complaint Resolved' : 'Complaint Status Updated';
    $notif_msg = "Your complaint has been updated to: $new_status";

    create_notification($student_id, $notif_type, $notif_title, $notif_msg, $complaint_id);

    log_audit_action($admin_id, 'complaint_updated', 'complaints', $complaint_id);

    echo json_encode(['success' => true, 'message' => 'Complaint updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
