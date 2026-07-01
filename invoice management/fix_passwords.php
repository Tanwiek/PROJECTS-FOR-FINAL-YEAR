<?php
// fix_passwords.php
require_once 'config/database.php';

$password = 'password';
$hash = password_hash($password, PASSWORD_BCRYPT);

try {
    $db = Database::getInstance();
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE 1");
    $stmt->execute([$hash]);
    echo "All passwords updated to 'password' with hash: $hash\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
