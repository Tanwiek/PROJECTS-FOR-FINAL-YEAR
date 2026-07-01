<?php
session_start();
require_once "../db.php";

if (!isset($_SESSION['user_id'])) {
    die("Non autorisé.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $service_id = $_POST['service_id'];
    $medecin_id = $_POST['medecin_id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $telephone = $_POST['telephone'];
    $date_rv = $_POST['date_rv'];
    $heure_rv = $_POST['heure_rv'];
    $message = $_POST['message'];

    try {
        $stmt = $pdo->prepare("INSERT INTO RendezVous (user_id, service_id, medecin_id, nom, prenom, telephone, date_rv, heure_rv, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $service_id, $medecin_id, $nom, $prenom, $telephone, $date_rv, $heure_rv, $message]);
        if ($_SESSION['role_id'] == 1) {
            header("Location: ../dashboard_patient.php?success=1");
        } elseif ($_SESSION['role_id'] == 3) {
            header("Location: ../dashboard_docteur.php?success=1");
        } else {
            header("Location: ../dashboard_hopital.php?success=1");
        }
    } catch (PDOException $e) {
        die("Erreur lors de la réservation : " . $e->getMessage());
    }
}
?>
