<?php
require 'config/database.php';
$db = Database::getInstance();
$stmt = $db->query("SELECT id, title, is_archived, archived_at, archived_by FROM projects");
echo "ID | Title | is_archived | archived_at | archived_by\n";
foreach($stmt->fetchAll() as $p) {
    echo $p['id'] . " | " . $p['title'] . " | " . $p['is_archived'] . " | " . $p['archived_at'] . " | " . $p['archived_by'] . "\n";
}
