<?php
header('Content-Type: text/html; charset=utf-8');
session_start();

$host = 'localhost';
$dbname = 'booking_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8mb4'");
} catch (PDOException $e) {
    die("Erreur de connexion a la base de donnees : " . $e->getMessage());
}
?>
