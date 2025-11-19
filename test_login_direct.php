<?php
/**
 * Direct Login Test
 * Simulates what happens when you submit the login form
 */

// Simulate POST request
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['username'] = 'admin';
$_POST['password'] = 'admin123';
$_POST['remember'] = '0';

// Capture output
ob_start();

// Set up error handling
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo "\n[ERROR] $errstr in $errfile:$errline\n";
    return true;
});

try {
    include 'process_login.php';
    $output = ob_get_clean();
    
    echo "<pre>";
    echo "=== LOGIN PROCESS OUTPUT ===\n\n";
    echo $output;
    
    // Try to decode the JSON
    $data = json_decode($output, true);
    if ($data) {
        echo "\n\n=== PARSED JSON ===\n";
        print_r($data);
    } else {
        echo "\n\n=== JSON DECODE ERROR ===\n";
        echo "Last JSON error: " . json_last_error_msg() . "\n";
    }
    echo "</pre>";
} catch (Throwable $e) {
    ob_end_clean();
    echo "<pre>";
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString();
    echo "</pre>";
}
?>
