<?php
session_start();
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM Utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role_id'] = $user['role_id'];

            if ($user['role_id'] == 1) {
                header("Location: ../dashboard_patient.php");
            } elseif ($user['role_id'] == 2) {
                header("Location: ../dashboard_hopital.php");
            } elseif ($user['role_id'] == 3) {
                header("Location: ../dashboard_docteur.php");
            }
        } else {
            header("Location: ../connexion.php?error=invalid");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: ../connexion.php?error=invalid");
        exit;
    }
}
?>
