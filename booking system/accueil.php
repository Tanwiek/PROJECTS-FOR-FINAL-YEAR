<?php
require 'db.php';

// Fetch services from database
try {
    $stmt = $pdo->query("SELECT * FROM services");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $services = []; // Fallback
}

// Fetch user's appointments if logged in
$my_appointments = [];
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'client') {
    $stmt = $pdo->prepare("SELECT r.*, s.nom as service_nom FROM rendezvous r JOIN services s ON r.service_id = s.id WHERE r.client_id = :id ORDER BY date_rdv DESC");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $my_appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle client cancellation
if (isset($_GET['cancel_rdv']) && isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'client') {
    $stmt = $pdo->prepare("UPDATE rendezvous SET statut = 'Annulé' WHERE id = :id AND client_id = :client_id");
    $stmt->execute(['id' => $_GET['cancel_rdv'], 'client_id' => $_SESSION['user_id']]);
    header("Location: accueil.php");
    exit;
}

// Handle notification dismissal
if (isset($_GET['read_notif']) && isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'client') {
    $stmt = $pdo->prepare("UPDATE rendezvous SET vu_par_client = 1 WHERE id = :id AND client_id = :client_id");
    $stmt->execute(['id' => $_GET['read_notif'], 'client_id' => $_SESSION['user_id']]);
    header("Location: accueil.php");
    exit;
}

// Fetch new notifications
$notifications = [];
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'client') {
    $stmt = $pdo->prepare("SELECT r.*, s.nom as service_nom FROM rendezvous r JOIN services s ON r.service_id = s.id WHERE r.client_id = :id AND r.vu_par_client = 0");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMT SARL - Votre Service de Réservation</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .hero {
            background: linear-gradient(rgba(12, 163, 218, 0.8), rgba(9, 140, 189, 0.8)), url('hero-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 20px;
            text-align: center;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            margin-bottom: 50px;
        }
        .hero h1 { color: white; font-size: 3rem; margin-bottom: 15px; }
        .hero p { font-size: 1.2rem; opacity: 0.9; }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 60px;
            background: white;
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .nav-logo { display: flex; align-items: center; gap: 15px; }
        .nav-logo img { width: 50px; height: 50px; border-radius: 50%; }
        .nav-logo span { font-weight: 700; font-size: 1.2rem; color: var(--primary-color); }
        
        .nav-links { display: flex; gap: 20px; align-items: center; }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        .service-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
        .service-card h3 { margin-bottom: 15px; color: var(--primary-color); }
        .service-card p { color: var(--text-muted); font-size: 14px; margin-bottom: 25px; flex-grow: 1; }
        
        .section-title {
            text-align: center;
            margin-bottom: 40px;
        }
        .section-title h2 { font-size: 2rem; position: relative; display: inline-block; padding-bottom: 10px; }
        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .appointment-card {
            border-left: 5px solid var(--primary-color);
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        @media (max-width: 768px) {
            .navbar { padding: 15px 20px; flex-direction: column; gap: 15px; }
            .hero h1 { font-size: 2rem; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-logo">
            <img src="logo.jpeg" alt="Logo">
            <span>TMT SARL</span>
        </div>
        <div class="nav-links">
            <a href="accueil.php" class="btn btn-outline" style="border:none;">Accueil</a>
            <?php if (isset($_SESSION['user_id'], $_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <a href="admin_dashboard.php" class="btn btn-primary">Dashboard Admin</a>
            <?php elseif (isset($_SESSION['user_id'], $_SESSION['user_role']) && $_SESSION['user_role'] === 'secretaire'): ?>
                <a href="secretaire_dashboard.php" class="btn btn-primary">Dashboard Secrétaire</a>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="btn btn-outline">Déconnexion</a>
            <?php else: ?>
                <a href="connexion.php" class="btn btn-outline">Connexion</a>
                <a href="inscription.php" class="btn btn-primary">S'inscrire</a>
            <?php endif; ?>
        </div>
    </nav>

    <header class="hero">
        <div class="container">
            <img src="logo.jpeg" alt="Logo" style="width: 120px; border-radius: 50%; margin-bottom: 20px; border: 4px solid white;">
            <h1>Bienvenue chez TMT SARL</h1>
            <p>Votre partenaire de confiance pour vos voyages et démarches administratives</p>
        </div>
    </header>

    <main class="container">
        
        <?php if (!empty($notifications)): ?>
        <section class="notifications" style="margin-bottom: 40px;">
            <?php foreach ($notifications as $notif): ?>
                <div class="card" style="background: <?php echo $notif['statut'] == 'Confirmé' ? '#dcfce7' : '#fee2e2'; ?>; border:none; display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <span style="color: <?php echo $notif['statut'] == 'Confirmé' ? '#166534' : '#991b1b'; ?>;">
                        <strong>🔔 Notification :</strong> Votre rdv pour "<?php echo htmlspecialchars($notif['service_nom']); ?>" a été <strong><?php echo strtolower($notif['statut']); ?></strong>.
                    </span>
                    <a href="?read_notif=<?php echo $notif['id']; ?>" class="btn" style="background: rgba(0,0,0,0.05); padding: 5px 15px;">Fermer</a>
                </div>
            <?php endforeach; ?>
        </section>
        <?php endif; ?>

        <section class="services-section">
            <div class="section-title">
                <h2>Nos Services</h2>
                <p style="color: var(--text-muted);">Choisissez le service dont vous avez besoin</p>
            </div>
            <div class="services-grid">
                <?php foreach ($services as $service): ?>
                <div class="card service-card">
                    <div>
                        <h3><?php echo htmlspecialchars($service['nom']); ?></h3>
                        <p><?php echo htmlspecialchars($service['description']); ?></p>
                    </div>
                    <a href="formulaire.php?service_id=<?php echo $service['id']; ?>" class="btn btn-primary" style="text-align: center;">Réserver maintenant</a>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <?php if (!empty($my_appointments)): ?>
        <section class="my-appointments" style="margin-top: 80px; margin-bottom: 80px;">
            <div class="section-title">
                <h2>Mes Rendez-vous</h2>
            </div>
            <div style="max-width: 800px; margin: 0 auto;">
                <?php foreach ($my_appointments as $app): ?>
                <div class="card appointment-card">
                    <div>
                        <h4 style="margin-bottom: 5px;"><?php echo htmlspecialchars($app['service_nom']); ?></h4>
                        <p style="font-size: 14px; color: var(--text-muted);">
                            📅 <?php echo htmlspecialchars($app['date_rdv']); ?> à <?php echo htmlspecialchars($app['heure_rdv']); ?>
                        </p>
                    </div>
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <span class="badge badge-<?php echo ($app['statut'] == 'Confirmé' ? 'confirmed' : ($app['statut'] == 'Annulé' ? 'cancelled' : 'pending')); ?>">
                            <?php echo $app['statut']; ?>
                        </span>
                        <?php if ($app['statut'] !== 'Annulé'): ?>
                            <a href="?cancel_rdv=<?php echo $app['id']; ?>" style="color: #ef4444; font-size: 14px; font-weight: 600;" onclick="return confirm('Annuler ce rdv ?')">Annuler</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

    </main>

    <footer style="background: #1e293b; color: white; padding: 60px 20px; text-align: center; margin-top: 80px;">
        <div class="container">
            <img src="logo.jpeg" alt="Logo" style="width: 60px; border-radius: 50%; margin-bottom: 20px; filter: grayscale(0.2);">
            <p style="font-weight: 600; margin-bottom: 10px;">TMT SARL</p>
            <p style="opacity: 0.7; font-size: 14px;">&copy; <?php echo date('Y'); ?> TMT SARL. "Votre voyage, notre mission !"</p>
        </div>
    </footer>

</body>
</html>
