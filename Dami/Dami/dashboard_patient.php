<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: connexion.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT r.*, s.nom as service_nom 
    FROM RendezVous r 
    LEFT JOIN Services s ON r.service_id = s.id 
    WHERE r.user_id = ? 
    ORDER BY r.date_rv DESC, r.heure_rv DESC");
$stmt->execute([$user_id]);
$appointments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Tableau de Bord - Hôpital Plus</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <div class="logo">Hôpital Plus</div>
            <nav>
                <a href="index.php">Accueil</a>
                <a href="actions/logout.php" class="btn">Déconnexion</a>
            </nav>
        </div>
    </header>

    <div class="container dashboard">
        <div class="welcome-box">
            <h1>Bienvenue, <?php echo explode('@', $_SESSION['email'])[0]; ?> !</h1>
            <p>Retrouvez ici l'historique de vos rendez-vous.</p>
            <a href="reservation.php" class="btn-primary">Prendre un nouveau RDV</a>
        </div>

        <div class="appointments-list">
            <h2>Mes Rendez-vous</h2>
            <?php if (empty($appointments)): ?>
                <p class="empty-msg">Vous n'avez pas encore de rendez-vous enregistrés.</p>
            <?php else: ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointments as $app): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($app['service_nom']); ?></td>
                                    <td><?php echo htmlspecialchars($app['date_rv']); ?></td>
                                    <td><?php echo htmlspecialchars($app['heure_rv']); ?></td>
                                    <td><span class="status <?php echo strtolower($app['statut']); ?>"><?php echo htmlspecialchars($app['statut']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
