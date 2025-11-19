<?php
/**
 * Database Connection File
 * Dela Salle John Bosco College - Student Complaint & Feedback System
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'delasalle_complaints');
define('DB_PORT', 3306);

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array('success' => false, 'error' => 'Database Connection Failed: ' . $conn->connect_error)));
}

// Set charset to utf8
$conn->set_charset("utf8mb4");

// Set timezone
date_default_timezone_set('Asia/Manila');

// Session configuration - only if session not already started
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', 3600);
    ini_set('session.cookie_lifetime', 3600);
    session_start();
}

// Function to sanitize input
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = $conn->real_escape_string($data);
    return $data;
}

// Function to generate reference number
function generate_reference_number($type = 'COMPLAINT') {
    $timestamp = time();
    $random = rand(1000, 9999);
    $date = date('YmdHis');
    return $type . '-' . $date . '-' . $random;
}

// Function to log audit trail
function log_audit_action($user_id, $action, $table_name, $record_id, $old_values = null, $new_values = null) {
    global $conn;
    
    $ip_address = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Unknown';
    $old_values_json = json_encode($old_values);
    $new_values_json = json_encode($new_values);
    
    $query = "INSERT INTO audit_log (user_id, action, table_name, record_id, old_values, new_values, ip_address) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    // Type specifiers: i=integer, s=string
    $stmt->bind_param("iisssss", $user_id, $record_id, $action, $table_name, $old_values_json, $new_values_json, $ip_address);
    $stmt->execute();
    $stmt->close();
}

// Function to create notification
function create_notification($user_id, $type, $title, $message, $complaint_id = null, $feedback_id = null) {
    global $conn;
    
    $query = "INSERT INTO notifications (user_id, complaint_id, feedback_id, notification_type, title, message) 
              VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiisss", $user_id, $complaint_id, $feedback_id, $type, $title, $message);
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
}

// Function to check if user is logged in
function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

// Function to check if user is admin
function check_admin() {
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
        header("Location: student_dashboard.php");
        exit();
    }
}

// Function to get user data
function get_user_data($user_id) {
    global $conn;
    
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    return $user;
}

// Function to verify password
function verify_password($input_password, $stored_hash) {
    return password_verify($input_password, $stored_hash);
}

// Function to hash password
function hash_password($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
}

// Function to format date
function format_date($date, $format = 'M d, Y h:i A') {
    return date($format, strtotime($date));
}

// Error handling
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return false;
    }
    
    $error_log = "Error: $errstr in $errfile on line $errline (Error Code: $errno)";
    error_log($error_log);
    
    // Log errors but don't echo JSON - let the calling script handle output
    return true;
});

// Graceful shutdown function
register_shutdown_function(function() {
    global $conn;
    if ($conn) {
        $conn->close();
    }
});
?>
