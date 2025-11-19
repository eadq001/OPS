<?php
/**
 * Debug Login Script
 * Use this to troubleshoot the 401 error
 */

include 'db_connection.php';

echo "=== LOGIN DEBUG SCRIPT ===\n\n";

// 1. Check database connection
echo "1. Database Connection: ";
if ($conn->connect_error) {
    echo "❌ FAILED: " . $conn->connect_error . "\n";
    exit;
} else {
    echo "✓ OK\n\n";
}

// 2. Check users table exists
echo "2. Users Table: ";
$result = $conn->query("SHOW TABLES LIKE 'users'");
if ($result->num_rows === 0) {
    echo "❌ Table does not exist\n";
    exit;
} else {
    echo "✓ OK\n\n";
}

// 3. List all users
echo "3. Users in Database:\n";
$result = $conn->query("SELECT user_id, username, user_type, status, LENGTH(password) as pwd_len, LEFT(password, 20) as pwd_start FROM users");
if ($result->num_rows === 0) {
    echo "   ❌ No users found\n";
} else {
    while ($row = $result->fetch_assoc()) {
        echo "   User ID: " . $row['user_id'] . "\n";
        echo "   Username: " . $row['username'] . "\n";
        echo "   Type: " . $row['user_type'] . "\n";
        echo "   Status: " . $row['status'] . "\n";
        echo "   Password Hash Length: " . $row['pwd_len'] . " chars\n";
        echo "   Password Hash Start: " . $row['pwd_start'] . "...\n";
        echo "   ---\n";
    }
}

echo "\n4. Test Password Verification:\n\n";

// Get the admin user
$user_result = $conn->query("SELECT * FROM users WHERE username = 'admin' LIMIT 1");
if ($user_result->num_rows === 0) {
    echo "❌ Admin user not found!\n";
    exit;
}

$user = $user_result->fetch_assoc();
echo "Found user: " . $user['username'] . "\n";
echo "Full password hash: " . $user['password'] . "\n\n";

// Test known good hash
$test_passwords = [
    'admin123',
    'admin123 ',
    ' admin123',
    'admin',
    '123456',
];

foreach ($test_passwords as $test_pwd) {
    $verify = password_verify($test_pwd, $user['password']);
    echo "Testing password: '" . $test_pwd . "' => " . ($verify ? "✓ MATCH" : "❌ NO MATCH") . "\n";
}

echo "\n5. Generate Correct Hash for admin123:\n";
$correct_hash = password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 10]);
echo "New hash: " . $correct_hash . "\n";
echo "Verify against 'admin123': " . (password_verify('admin123', $correct_hash) ? "✓ OK" : "❌ FAIL") . "\n";

echo "\n6. To fix, run this SQL:\n";
echo "UPDATE users SET password = '" . $correct_hash . "' WHERE username = 'admin';\n";

echo "\n=== END DEBUG ===\n";
?>
