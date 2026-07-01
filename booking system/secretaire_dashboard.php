<?php
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'secretaire') {
    header("Location: connexion.php");
    exit;
}

$success = '';
$error = '';

// Handle cancellation
if (isset($_GET['cancel_id'])) {
    $stmt = $pdo->prepare("UPDATE rendezvous SET statut = 'Annulé', vu_par_client = 0 WHERE id = :id");
    if ($stmt->execute(['id' => $_GET['cancel_id']])) {
        $success = "Rendez-vous annulé.";
    }
}

// Fetch all appointments
$date_filter = $_GET['date'] ?? '';
$query = "SELECT r.*, c.nom as client_nom, c.prenom as client_prenom, s.nom as service_nom 
          FROM rendezvous r 
          JOIN clients c ON r.client_id = c.id 
          JOIN services s ON r.service_id = s.id";

if ($date_filter) {
    $query .= " WHERE r.date_rdv = :date";
}
$query .= " ORDER BY r.date_rdv DESC, r.heure_rdv DESC";

$stmt = $pdo->prepare($query);
if ($date_filter) {
    $stmt->execute(['date' => $date_filter]);
} else {
    $stmt->execute();
}
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Secrétaire - TMT SARL</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #f8fafc;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 280px;
            background: white;
            border-right: 1px solid #e2e8f0;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
        }
        .main-content {
            margin-left: 280px;
            flex-grow: 1;
            padding: 40px;
        }
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 50px;
            padding: 0 10px;
        }
        .sidebar-logo img { width: 50px; height: 50px; border-radius: 50%; }
        .sidebar-logo span { font-weight: 700; font-size: 1.2rem; color: var(--primary-color); }
        
        .nav-menu { list-style: none; flex-grow: 1; }
        .nav-item { margin-bottom: 10px; }
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.2s;
            font-weight: 500;
        }
        .nav-link:hover, .nav-link.active {
            background: var(--primary-light);
            color: var(--primary-color);
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: var(--shadow-sm);
            text-align: center;
        }
        .stat-card .value { font-size: 1.5rem; font-weight: 700; color: var(--text-main); }
        .stat-card .label { font-size: 14px; color: var(--text-muted); }

        .filter-bar {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; padding: 20px; }
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-logo">
            <img src="logo.jpeg" alt="Logo">
            <span>TMT SARL</span>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="accueil.php" class="nav-link">🏠 Accueil Site</a>
            </li>
            <li class="nav-item">
                <a href="secretaire_dashboard.php" class="nav-link active">📅 Rendez-vous</a>
            </li>
        </ul>
        <a href="logout.php" class="nav-link" style="color: #ef4444;">🚪 Déconnexion</a>
    </aside>

    <main class="main-content">
        <header class="dashboard-header">
            <div>
                <h1 style="font-size: 1.8rem; font-weight: 700; color: var(--text-main);">Gestion des Rendez-vous</h1>
                <p style="color: var(--text-muted);">Bienvenue sur votre tableau de bord secrétaire</p>
            </div>
            <a href="formulaire.php" class="btn btn-primary">+ Nouveau RDV</a>
        </header>

        <?php if ($success): ?>
            <div class="card" style="padding: 15px; background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; margin-bottom: 25px; text-align: center;">
                <strong>✓</strong> <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <div class="filter-bar">
            <form method="get" style="display: flex; align-items: center; gap: 15px;">
                <label style="font-size: 14px; font-weight: 600; color: var(--text-muted);">Filtrer par date :</label>
                <input type="date" name="date" class="form-input" style="width: auto; margin: 0;" value="<?php echo htmlspecialchars($date_filter); ?>">
                <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">Filtrer</button>
                <?php if ($date_filter): ?>
                    <a href="secretaire_dashboard.php" style="font-size: 14px; color: var(--primary-color);">Réinitialiser</a>
                <?php endif; ?>
            </form>
            <div style="font-size: 14px; color: var(--text-muted);">
                Total: <strong><?php echo count($appointments); ?></strong> rdv
            </div>
        </div>

        <div class="card" style="padding: 0; overflow: hidden;">
            <table>
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Service</th>
                        <th>Date & Heure</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($appointments)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">Aucun rendez-vous trouvé.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($appointments as $app): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 600;"><?php echo htmlspecialchars($app['client_nom'] . ' ' . $app['client_prenom']); ?></div>
                            </td>
                            <td><?php echo htmlspecialchars($app['service_nom']); ?></td>
                            <td>
                                <div><?php echo htmlspecialchars($app['date_rdv']); ?></div>
                                <div style="font-size: 12px; color: var(--text-muted);"><?php echo htmlspecialchars($app['heure_rdv']); ?></div>
                            </td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo ($app['statut'] == 'Confirmé' ? 'confirmed' : ($app['statut'] == 'Annulé' ? 'cancelled' : 'pending')); 
                                ?>">
                                    <?php echo $app['statut']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($app['statut'] !== 'Annulé'): ?>
                                    <a href="?cancel_id=<?php echo $app['id']; ?>" style="color: #ef4444; font-size: 13px; font-weight: 600; text-decoration: none;" onclick="return confirm('Annuler ce rdv ?')">Annuler</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>
