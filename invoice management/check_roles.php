<?php
require 'config/database.php';
$db = Database::getInstance();
echo "--- ROLES ---\n";
$stmt = $db->query("SELECT * FROM roles");
foreach($stmt->fetchAll() as $r) {
    echo $r['id'] . " | " . $r['name'] . "\n";
}
echo "\n--- USERS ---\n";
$stmt = $db->query("SELECT u.username, u.role_id, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id");
foreach($stmt->fetchAll() as $u) {
    echo $u['username'] . " | " . $u['role_id'] . " | " . $u['role_name'] . "\n";
}
