<?php
/**
 * Password Hash Generator
 * Use this to generate bcrypt hashes for test accounts
 */

// Password to hash
$password1 = 'admin123';
$password2 = 'student123';

// Generate bcrypt hashes
$hash1 = password_hash($password1, PASSWORD_BCRYPT, ['cost' => 10]);
$hash2 = password_hash($password2, PASSWORD_BCRYPT, ['cost' => 10]);

echo "Generated Password Hashes:\n";
echo "==========================\n\n";

echo "For admin123:\n";
echo $hash1 . "\n\n";

echo "For student123:\n";
echo $hash2 . "\n\n";

echo "==========================\n";
echo "Copy-paste the hashes above into database.sql\n";
echo "==========================\n\n";

echo "SQL Update Commands:\n";
echo "==========================\n";
echo "UPDATE users SET password = '" . $hash1 . "' WHERE username = 'admin';\n";
echo "UPDATE users SET password = '" . $hash2 . "' WHERE username = 'student001';\n";
echo "==========================\n";

// Verify the hashes work
echo "\n\nVerification:\n";
echo "==========================\n";
echo "Verifying 'admin123' against hash: " . (password_verify('admin123', $hash1) ? 'SUCCESS ✓' : 'FAILED ✗') . "\n";
echo "Verifying 'student123' against hash: " . (password_verify('student123', $hash2) ? 'SUCCESS ✓' : 'FAILED ✗') . "\n";
echo "==========================\n";
?>
