<?php
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        die("Les mots de passe ne correspondent pas.");
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("INSERT INTO Utilisateurs (role_id, email, telephone, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([1, $email, $telephone, $hashed_password]);
        $user_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO Patients (user_id, telephone) VALUES (?, ?)");
        $stmt->execute([$user_id, $telephone]);

        $pdo->commit();
        header("Location: ../connexion.php?success=1");
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Erreur lors de l'inscription : " . $e->getMessage());
    }
}
?>
