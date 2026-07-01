<?php 
session_start(); 
require_once "db.php";

// Fetch services from database
$stmt = $pdo->query("SELECT * FROM Services");
$services = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hôpital Plus - Accueil</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <div class="logo">Hôpital Plus</div>
            <nav>
                <a href="index.php">Accueil</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if($_SESSION['role_id'] == 1): ?>
                        <a href="dashboard_patient.php">Mon Profil</a>
                    <?php elseif($_SESSION['role_id'] == 2): ?>
                        <a href="dashboard_hopital.php">Admin</a>
                    <?php elseif($_SESSION['role_id'] == 3): ?>
                        <a href="dashboard_docteur.php">Espace Docteur</a>
                    <?php endif; ?>
                    <a href="actions/logout.php" class="btn">Déconnexion</a>
                <?php else: ?>
                    <a href="connexion.php">Connexion</a>
                    <a href="inscription.php" class="btn">S'inscrire</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h1>Des Soins de Qualité Pour Tous</h1>
            <p>Réservez votre rendez-vous en ligne avec nos meilleurs spécialistes.</p>
            <a href="#services" class="btn-primary">Voir Nos Services</a>
        </div>
    </section>

    <section id="services" class="services">
        <div class="container">
            <h2 class="section-title">Nos Services Spécialisés</h2>
            <div class="service-grid">
                <?php foreach($services as $service): ?>
                <div class="service-card">
                    <i class="<?php echo htmlspecialchars($service['icone']); ?>"></i>
                    <h3><?php echo htmlspecialchars($service['nom']); ?></h3>
                    <p><?php echo htmlspecialchars($service['description']); ?></p>
                    <a href="reservation.php?service_id=<?php echo $service['id']; ?>" class="btn-outline">Réserver</a>
                </div>
                <?php endforeach; ?>
                <?php if(empty($services)): ?>
                    <p class="empty-msg">Aucun service disponible pour le moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Hôpital Plus. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
