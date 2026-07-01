<?php
require 'db.php';
$error = '';
$success = '';

if (isset($_GET['registered']) && $_GET['registered'] == 1) {
    $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $tables = [
            'clients' => 'client',
            'secretaire' => 'secretaire',
            'administrateurs' => 'admin'
        ];
        
        $logged_in = false;

        foreach ($tables as $table => $role) {
            $stmt = $pdo->prepare("SELECT * FROM $table WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_role'] = $role;

                if ($role === 'client') {
                    header("Location: accueil.php");
                } elseif ($role === 'secretaire') {
                    header("Location: secretaire_dashboard.php");
                } elseif ($role === 'admin') {
                    header("Location: admin_dashboard.php");
                }
                exit;
            }
        }
        
        if (!$logged_in) {
            $error = "Email ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - TMT SARL</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, #0ca3da 0%, #098cbd 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            text-align: center;
        }
        .logo-container {
            margin-bottom: 30px;
        }
        .logo-container img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            box-shadow: var(--shadow-sm);
            border: 3px solid white;
        }
        h2 {
            font-size: 24px;
            margin-bottom: 30px;
            color: var(--text-main);
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
    <div class="login-card">
        <div class="logo-container">
            <img src="logo.jpeg" alt="TMT SARL Logo">
        </div>
        <h2>Connexion</h2>

        <?php if ($error): ?><div class="message error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        <?php if ($success): ?><div class="message success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

        <form method="post" action="connexion.php">

            <div class="form-group">
                <label for="email">Adresse Email :</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="nom@exemple.com" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px;">Se connecter</button>
            
            <div class="footer-links">
                Pas encore inscrit ? <a href="inscription.php">Créer un compte</a>
                <div style="margin-top: 10px;">
                    <a href="accueil.php" style="font-weight: 400; color: var(--text-muted); border-bottom: 1px transparent;">Retour à l'accueil</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
