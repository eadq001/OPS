<?php
/**
 * Fix Admin Password - Clean Version
 */

// Suppress output buffering to catch any stray output
ob_start();

include 'db_connection.php';

// Clear any output buffer
ob_clean();

// Correct hash for 'admin123'
$correct_hash = '$2y$10$jX9tl44swZczBE.TWcgiTO2M8ORrApu/tfrSz4q6EaGVwQ44XXtcK';

$query = "UPDATE users SET password = ? WHERE username = 'admin'";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("s", $correct_hash);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

// Return to login page
header('Location: login.php');
exit();
?>
