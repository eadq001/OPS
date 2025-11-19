<?php
// Test announcement creation
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start session BEFORE including db_connection
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_type'] = 'admin';

$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['title'] = 'Test Announcement';
$_POST['description'] = 'This is a test announcement';
$_POST['priority'] = 'High';

// Capture output
ob_start();

// Include the API
include 'api_create_announcement.php';

$output = ob_get_clean();
echo $output;
?>
