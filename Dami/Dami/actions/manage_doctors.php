<?php
session_start();
require_once "../db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    die("Non autorisé.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $email = $_POST['email'];
    $nom = $_POST['nom'];
    $specialite = $_POST['specialite'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        $pdo->beginTransaction();

        // 1. Create User
        $stmt = $pdo->prepare("INSERT INTO Utilisateurs (role_id, email, password) VALUES (?, ?, ?)");
        $stmt->execute([3, $email, $password]);
        $user_id = $pdo->lastInsertId();

        // 2. Create Doctor
        $stmt = $pdo->prepare("INSERT INTO Medecins (user_id, nom, specialite) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $nom, $specialite]);

        $pdo->commit();
        header("Location: ../dashboard_hopital.php?success=1");
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Erreur lors de l'ajout du médecin : " . $e->getMessage());
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $u_id = $_GET['user_id'];
    try {
        // Just delete the doctor entry, or the user as well? User as well for clean system.
        $stmt = $pdo->prepare("DELETE FROM Utilisateurs WHERE id = ?");
        $stmt->execute([$u_id]);
        header("Location: ../dashboard_hopital.php?success=deleted");
    } catch (PDOException $e) {
        die("Erreur lors de la suppression : " . $e->getMessage());
    }
}
?>
