<?php
include 'db_connection.php';
check_login();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $complaint_type = sanitize_input($_POST['complaint_type']);
    $course_department = sanitize_input($_POST['course_department']);
    $date_incident = sanitize_input($_POST['date_incident']);
    $description = sanitize_input($_POST['description']);
    
    // Validate
    if (strlen($description) < 20) {
        echo json_encode(['success' => false, 'message' => 'Description must be at least 20 characters.']);
        exit();
    }

    // Generate reference number
    $reference_number = generate_reference_number('COMPLAINT');

    // Insert complaint
    $insert_query = "INSERT INTO complaints (user_id, reference_number, complaint_type, course_department, description, date_of_incident) 
                     VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("isssss", $user_id, $reference_number, $complaint_type, $course_department, $description, $date_incident);

    if ($stmt->execute()) {
        $complaint_id = $stmt->insert_id;

        // Handle file uploads
        if (!empty($_FILES['evidence']['name'][0])) {
            $upload_dir = 'uploads/complaints/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            foreach ($_FILES['evidence']['name'] as $key => $filename) {
                $file_error = $_FILES['evidence']['error'][$key];
                $file_tmp = $_FILES['evidence']['tmp_name'][$key];
                $file_size = $_FILES['evidence']['size'][$key];

                if ($file_error === 0 && $file_size <= 5 * 1024 * 1024) {
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
                    
                    if (in_array(strtolower($ext), $allowed)) {
                        $new_filename = uniqid('complaint_' . $complaint_id . '_', true) . '.' . $ext;
                        $file_path = $upload_dir . $new_filename;

                        if (move_uploaded_file($file_tmp, $file_path)) {
                            $file_insert = "INSERT INTO complaint_files (complaint_id, original_filename, stored_filename, file_size, file_type) 
                                           VALUES (?, ?, ?, ?, ?)";
                            $file_stmt = $conn->prepare($file_insert);
                            $file_stmt->bind_param("issis", $complaint_id, $filename, $new_filename, $file_size, $ext);
                            $file_stmt->execute();
                            $file_stmt->close();
                        }
                    }
                }
            }
        }

        // Log audit
        log_audit_action($user_id, 'complaint_submitted', 'complaints', $complaint_id);

        // Create notification
        create_notification($user_id, 'complaint_submitted', 'Complaint Submitted', 
                           'Your complaint ' . $reference_number . ' has been submitted successfully.');

        echo json_encode([
            'success' => true,
            'message' => 'Complaint submitted successfully',
            'reference_number' => $reference_number,
            'complaint_id' => $complaint_id
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit complaint. Please try again.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
