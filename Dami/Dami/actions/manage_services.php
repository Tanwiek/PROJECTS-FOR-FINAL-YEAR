<?php
session_start();
require_once "../db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    die("Non autorisé.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $icone = $_POST['icone'];

    try {
        $stmt = $pdo->prepare("INSERT INTO Services (nom, description, icone) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $description, $icone]);
        header("Location: ../dashboard_hopital.php?success=1");
    } catch (PDOException $e) {
        die("Erreur lors de l'ajout du service : " . $e->getMessage());
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM Services WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: ../dashboard_hopital.php?success=deleted");
    } catch (PDOException $e) {
        die("Erreur lors de la suppression : " . $e->getMessage());
    }
}
?>
