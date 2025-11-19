<?php
include 'db_connection.php';
check_login();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

try {
    $query = "
        SELECT 
            complaint_id as id, 
            reference_number, 
            complaint_type as type_name, 
            status, 
            created_at as submitted_date, 
            updated_at as updated_date,
            'complaint' as type
        FROM complaints 
        WHERE user_id = ?
        UNION ALL
        SELECT 
            feedback_id as id, 
            reference_number, 
            feedback_type as type_name, 
            status, 
            created_at as submitted_date, 
            updated_at as updated_date,
            'feedback' as type
        FROM feedback 
        WHERE user_id = ?
        ORDER BY submitted_date DESC
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $submissions = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    echo json_encode([
        'success' => true,
        'submissions' => $submissions
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
