<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Hôpital Plus</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <h2>Connexion</h2>
            <p>Accédez à votre espace santé</p>
            
            <?php if(isset($_GET['error']) && $_GET['error'] == 'invalid'): ?>
                <div style="background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight: 600;">
                    Identifiants incorrects. Veuillez réessayer.
                </div>
            <?php endif; ?>

            <form action="actions/login_action.php" method="POST">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required placeholder="votre@email.com">
                </div>
                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn-primary w-100">Se Connecter</button>
            </form>
            <p class="auth-switch">Pas encore de compte ? <a href="inscription.php">S'inscrire</a></p>
            <a href="index.php" class="back-link">Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>
