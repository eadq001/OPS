<?php
include 'db_connection.php';

// Log the logout action
if (isset($_SESSION['user_id'])) {
    log_audit_action($_SESSION['user_id'], 'logout', 'users', $_SESSION['user_id']);
}

// Destroy session
session_destroy();

// Clear remember me cookie
setcookie('remember_user', '', time() - 3600, '/');

// Redirect to login
header("Location: login.php");
exit();
?>
