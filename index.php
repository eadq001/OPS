<?php
// Redirect to login or dashboard based on session
include 'db_connection.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_type'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: student_dashboard.php");
    }
} else {
    header("Location: login.php");
}
exit();
?>
