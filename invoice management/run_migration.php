<?php
// run_migration.php
require_once 'config/database.php';
try {
    $db = Database::getInstance();
    $sql = file_get_contents('sql/update_archiving.sql');
    $db->exec($sql);
    echo "Migration successful.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
