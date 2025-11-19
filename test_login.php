<?php
// Test the login process
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simulate the login POST request
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['username'] = 'admin';
$_POST['password'] = 'admin123';
$_POST['remember'] = '0';

// Include process_login.php
include 'process_login.php';
?>
