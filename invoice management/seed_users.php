<?php
// seed_users.php
require_once 'config/database.php';

try {
    $db = Database::getInstance();
    $sql = file_get_contents('sql/seed_users.sql');
    $db->exec($sql);
    echo "Users seeded successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
