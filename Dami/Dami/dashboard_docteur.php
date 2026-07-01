<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: connexion.php");
    exit;
}

// Get doctor's info
$stmt = $pdo->prepare("SELECT * FROM Medecins WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$doctor = $stmt->fetch();

if (!$doctor) die("Erreur : Médecin introuvable.");

// Fetch appointments for this doctor
$stmt = $pdo->prepare("SELECT r.*, s.nom as service_nom 
    FROM RendezVous r 
    LEFT JOIN Services s ON r.service_id = s.id
    WHERE r.medecin_id = ? 
    ORDER BY r.date_rv ASC, r.heure_rv ASC");
$stmt->execute([$doctor['id']]);
$appointments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Docteur - Hôpital Plus</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <div class="logo">Espace Docteur</div>
            <nav>
                <a href="index.php">Accueil</a>
                <a href="actions/logout.php" class="btn">Déconnexion</a>
            </nav>
        </div>
    </header>

    <div class="container dashboard">
        <div class="welcome-box">
            <h1>Bonjour, Dr. <?php echo htmlspecialchars($doctor['nom']); ?></h1>
            <p>Voici vos prochaines consultations.</p>
        </div>

        <div class="appointments-list">
            <h2>Mes Consultations</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Téléphone</th>
                            <th>Date/Heure</th>
                            <th>Message</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $app): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($app['nom'] . " " . $app['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($app['telephone']); ?></td>
                                <td><?php echo htmlspecialchars($app['date_rv'] . " " . $app['heure_rv']); ?></td>
                                <td><small><?php echo htmlspecialchars($app['message']); ?></small></td>
                                <td><span class="status <?php echo str_replace(' ', '-', strtolower($app['statut'])); ?>"><?php echo htmlspecialchars($app['statut']); ?></span></td>
                                <td>
                                    <?php if ($app['statut'] == 'en attente'): ?>
                                        <a href="actions/update_status.php?id=<?php echo $app['id']; ?>&status=confirmé" class="action-btn confirm" title="Accepter"><i class="fas fa-check"></i></a>
                                        <a href="actions/update_status.php?id=<?php echo $app['id']; ?>&status=annulé" class="action-btn cancel" title="Refuser"><i class="fas fa-times"></i></a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($appointments)): ?>
                            <tr><td colspan="6" class="empty-msg">Aucun rendez-vous pour le moment.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
