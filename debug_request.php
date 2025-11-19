<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log all requests
$log = "=== REQUEST DEBUG ===\n";
$log .= "Method: " . $_SERVER['REQUEST_METHOD'] . "\n";
$log .= "Content-Type: " . ($_SERVER['CONTENT_TYPE'] ?? 'NOT SET') . "\n";
$log .= "POST data: " . json_encode($_POST) . "\n";
$log .= "GET data: " . json_encode($_GET) . "\n";
$log .= "REQUEST data: " . json_encode($_REQUEST) . "\n";

file_put_contents(__DIR__ . '/registration_debug.log', $log, FILE_APPEND);

echo "Debug logged. Check registration_debug.log";
?>
