<?php
require 'db.php';
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'] ?? 'client';
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($password)) {
        $error = "Tous les champs sont requis.";
    } else {
        // Check if email exists in ALL tables, assuming unique global email
        $tables = ['clients', 'secretaire', 'administrateurs'];
        $email_exists = false;
        foreach ($tables as $t) {
            $stmt = $pdo->prepare("SELECT id FROM $t WHERE email = :email");
            $stmt->execute(['email' => $email]);
            if ($stmt->rowCount() > 0) {
                $email_exists = true;
                break;
            }
        }

        if ($email_exists) {
            $error = "Cet email est déjà utilisé.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO clients (nom, prenom, email, telephone, password) VALUES (:nom, :prenom, :email, :telephone, :password)");
            $exec = $stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'telephone' => $telephone, 'password' => $hash]);

            if ($exec) {
                header("Location: connexion.php?registered=1");
                exit;
            } elseif (!$error) {
                $error = "Erreur lors de l'inscription.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - TMT SARL</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, #192a3a 0%, #101a24 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .register-card {
            background: white;
            padding: 30px;
            width: 100%;
            max-width: 450px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            text-align: center;
        }
        .logo-container {
            margin-bottom: 15px;
        }
        .logo-container img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            box-shadow: var(--shadow-sm);
        }
        h2 {
            font-size: 22px;
            margin-bottom: 15px;
            color: var(--text-main);
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .form-group {
            text-align: left;
        }
        .message {
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
        }
        .error { background: #fee2e2; color: #991b1b; }
        .success { background: #dcfce7; color: #166534; }
        .footer-links {
            margin-top: 25px;
            font-size: 14px;
            color: var(--text-muted);
        }
        .footer-links a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="logo-container">
            <img src="logo.jpeg" alt="TMT SARL Logo">
        </div>
        <h2>Créer un compte</h2>
        
        <?php if ($error): ?><div class="message error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        <?php if ($success): ?><div class="message success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
        
        <form method="post" action="inscription.php">
            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" class="form-input" placeholder="Votre nom" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" class="form-input" placeholder="Votre prénom" required>
                </div>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" class="form-input" placeholder="0123456789" required>
            </div>

            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="nom@exemple.com" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="Créez un mot de passe" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; margin-top: 10px;">S'inscrire</button>
            
            <div class="footer-links">
                Déjà un compte ? <a href="connexion.php">Se connecter</a>
                <div style="margin-top: 10px;">
                    <a href="accueil.php" style="font-weight: 400; color: var(--text-muted);">Retour à l'accueil</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
