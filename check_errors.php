<?php
echo "<pre>";
echo "=== PHP Error Log ===\n\n";

$error_log = ini_get('error_log');
if ($error_log && file_exists($error_log)) {
    echo "Error log location: $error_log\n";
    echo "Last 50 lines:\n";
    $lines = file($error_log);
    $last_lines = array_slice($lines, -50);
    echo implode('', $last_lines);
} else {
    echo "Error log not found at: $error_log\n";
}

echo "\n\n=== Local error.log ===\n";
$local_log = __DIR__ . '/error.log';
if (file_exists($local_log)) {
    echo file_get_contents($local_log);
} else {
    echo "Local error.log not found\n";
}

echo "\n\n=== Test Database Connection ===\n";
try {
    include 'db_connection.php';
    echo "✓ Database connection successful\n";
    
    // Test query
    $result = $conn->query("SELECT 1 as test");
    if ($result) {
        echo "✓ Query execution successful\n";
    }
    
    // Test password verification
    $user_result = $conn->query("SELECT * FROM users WHERE username = 'admin' LIMIT 1");
    if ($user_result && $user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        echo "✓ Admin user found\n";
        echo "Password hash: " . substr($user['password'], 0, 20) . "...\n";
        echo "Password verify test: " . (password_verify('admin123', $user['password']) ? "✓ PASS" : "✗ FAIL") . "\n";
    } else {
        echo "✗ Admin user not found\n";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n\n=== Function Tests ===\n";
if (function_exists('verify_password')) {
    echo "✓ verify_password() function exists\n";
} else {
    echo "✗ verify_password() function NOT found\n";
}

if (function_exists('sanitize_input')) {
    echo "✓ sanitize_input() function exists\n";
} else {
    echo "✗ sanitize_input() function NOT found\n";
}

if (function_exists('log_audit_action')) {
    echo "✓ log_audit_action() function exists\n";
} else {
    echo "✗ log_audit_action() function NOT found\n";
}

echo "</pre>";
?>
