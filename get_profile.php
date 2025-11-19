<?php
include 'db_connection.php';
check_login();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

try {
    $user = get_user_data($user_id);

    if ($user) {
        echo json_encode([
            'success' => true,
            'user' => $user
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'User not found'
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
