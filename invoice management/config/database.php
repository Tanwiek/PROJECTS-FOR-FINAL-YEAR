<?php
// config/database.php

define('DB_HOST', 'localhost');
define('DB_NAME', 'always_tech_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// URL de base dynamique pour les sous-répertoires
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script = $_SERVER['SCRIPT_NAME'] ?? '';
$scriptPath = str_replace('\\', '/', dirname($script));
$baseUrl = $protocol . "://" . $host . rtrim($scriptPath, '/');
define('BASE_URL', $baseUrl);

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ]
            );
        } catch (PDOException $e) {
            die("Échec de la connexion : " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }
}
