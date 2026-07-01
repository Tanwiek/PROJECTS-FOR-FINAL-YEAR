<?php
require 'config/database.php';
$db = Database::getInstance();
$stmt = $db->query("DESCRIBE projects");
foreach($stmt->fetchAll() as $c) {
    echo $c['Field'] . " | " . $c['Type'] . "\n";
}
