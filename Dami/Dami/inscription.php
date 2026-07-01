<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Hôpital Plus</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <h2>Inscription</h2>
            <p>Créez votre compte patient</p>
            <form action="actions/signup_action.php" method="POST">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required placeholder="votre@email.com">
                </div>
                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="tel" name="telephone" required placeholder="+237 ...">
                </div>
                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" required placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label>Confirmer le mot de passe</label>
                    <input type="password" name="confirm_password" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn-primary w-100">S'inscrire</button>
            </form>
            <p class="auth-switch">Déjà un compte ? <a href="connexion.php">Se connecter</a></p>
            <a href="index.php" class="back-link">Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>
