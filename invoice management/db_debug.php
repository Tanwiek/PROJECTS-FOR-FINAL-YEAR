<?php
require_once 'config/database.php';
$db = Database::getInstance();
$projects = $db->query("SELECT id, title, is_archived, archived_at, archived_by FROM projects")->fetchAll(PDO::FETCH_ASSOC);
file_put_contents('db_debug.txt', print_r($projects, true));
?>
