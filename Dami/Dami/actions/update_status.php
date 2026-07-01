<?php
session_start();
require_once "../db.php";

if (!isset($_SESSION['user_id']) || ($_SESSION['role_id'] != 2 && $_SESSION['role_id'] != 3)) {
    die("Non autorisé.");
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];

    try {
        $stmt = $pdo->prepare("UPDATE RendezVous SET statut = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        if ($_SESSION['role_id'] == 3) {
            header("Location: ../dashboard_docteur.php?success=1");
        } else {
            header("Location: ../dashboard_hopital.php?success=1");
        }
    } catch (PDOException $e) {
        die("Erreur lors de la mise à jour : " . $e->getMessage());
    }
}
?>
