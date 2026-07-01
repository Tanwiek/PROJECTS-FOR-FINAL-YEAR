<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: connexion.php");
    exit;
}

// Fetch all data
$appointments = $pdo->query("SELECT r.*, u.email, s.nom as service_nom, m.nom as medecin_nom 
    FROM RendezVous r 
    JOIN Utilisateurs u ON r.user_id = u.id 
    LEFT JOIN Services s ON r.service_id = s.id
    LEFT JOIN Medecins m ON r.medecin_id = m.id
    ORDER BY r.created_at DESC")->fetchAll();

$services = $pdo->query("SELECT * FROM Services")->fetchAll();
$doctors = $pdo->query("SELECT m.*, u.email FROM Medecins m JOIN Utilisateurs u ON m.user_id = u.id")->fetchAll();
$patients = $pdo->query("SELECT p.*, u.email, u.created_at FROM Patients p JOIN Utilisateurs u ON p.user_id = u.id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Hôpital Plus</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-nav { display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .admin-nav button { background: none; border: none; font-weight: 600; cursor: pointer; padding: 10px; }
        .admin-nav button.active { color: var(--primary); border-bottom: 2px solid var(--primary); }
        .section { display: none; }
        .section.active { display: block; }
        .form-inline { display: flex; gap: 10px; margin-bottom: 20px; background: #f8fafc; padding: 20px; border-radius: 8px; align-items: flex-end; }
        .form-inline .form-group { margin-bottom: 0; flex: 1; }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="container">
            <div class="logo">Admin Espace</div>
            <nav>
                <a href="index.php">Accueil</a>
                <a href="actions/logout.php" class="btn">Déconnexion</a>
            </nav>
        </div>
    </header>

    <div class="container dashboard">
        <div class="welcome-box">
            <h1>Panneau d'Administration</h1>
            <p>Gérez les services, les médecins et les utilisateurs.</p>
        </div>

        <div class="admin-nav">
            <button onclick="showSection('rdv')" class="active" id="btn-rdv">Rendez-vous</button>
            <button onclick="showSection('services')" id="btn-services">Services</button>
            <button onclick="showSection('doctors')" id="btn-doctors">Médecins</button>
            <button onclick="showSection('patients')" id="btn-patients">Patients</button>
        </div>

        <!-- Rendez-vous -->
        <div id="rdv" class="section active">
            <h2>Gestion des Rendez-vous</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Service</th>
                            <th>Médecin</th>
                            <th>Date/Heure</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $app): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($app['nom'] . " " . $app['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($app['service_nom']); ?></td>
                                <td>Dr. <?php echo htmlspecialchars($app['medecin_nom']); ?></td>
                                <td><?php echo htmlspecialchars($app['date_rv'] . " " . $app['heure_rv']); ?></td>
                                <td><span class="status <?php echo str_replace(' ', '-', strtolower($app['statut'])); ?>"><?php echo htmlspecialchars($app['statut']); ?></span></td>
                                <td>
                                    <a href="actions/update_status.php?id=<?php echo $app['id']; ?>&status=confirmé" class="action-btn confirm"><i class="fas fa-check"></i></a>
                                    <a href="actions/update_status.php?id=<?php echo $app['id']; ?>&status=annulé" class="action-btn cancel"><i class="fas fa-times"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Services -->
        <div id="services" class="section">
            <h2>Gérer les Services</h2>
            <form action="actions/manage_services.php" method="POST" class="form-inline">
                <div class="form-group">
                    <label>Nom du Service</label>
                    <input type="text" name="nom" required placeholder="Ex: Cardiologie">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <input type="text" name="description" required placeholder="Description courte">
                </div>
                <div class="form-group">
                    <label>Icône FontAwesome</label>
                    <input type="text" name="icone" value="fas fa-stethoscope">
                </div>
                <button type="submit" name="add" class="btn-primary">Ajouter</button>
            </form>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Icône</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $s): ?>
                            <tr>
                                <td><i class="<?php echo htmlspecialchars($s['icone']); ?>"></i></td>
                                <td><?php echo htmlspecialchars($s['nom']); ?></td>
                                <td><?php echo htmlspecialchars($s['description']); ?></td>
                                <td>
                                    <a href="actions/manage_services.php?delete=<?php echo $s['id']; ?>" class="action-btn cancel" onclick="return confirm('Supprimer ce service ?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Médecins -->
        <div id="doctors" class="section">
            <h2>Gérer les Médecins</h2>
            <form action="actions/manage_doctors.php" method="POST" class="form-inline">
                <div class="form-group">
                    <label>Email (Connexion)</label>
                    <input type="email" name="email" required placeholder="medecin@hopital.com">
                </div>
                <div class="form-group">
                    <label>Nom Complet</label>
                    <input type="text" name="nom" required placeholder="Dr. Dupont">
                </div>
                <div class="form-group">
                    <label>Spécialité</label>
                    <input type="text" name="specialite" required placeholder="Ex: Cardiologie">
                </div>
                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" required value="medecin123">
                </div>
                <button type="submit" name="add" class="btn-primary">Ajouter</button>
            </form>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Spécialité</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($doctors as $d): ?>
                            <tr>
                                <td>Dr. <?php echo htmlspecialchars($d['nom']); ?></td>
                                <td><?php echo htmlspecialchars($d['specialite']); ?></td>
                                <td><?php echo htmlspecialchars($d['email']); ?></td>
                                <td>
                                    <a href="actions/manage_doctors.php?delete=<?php echo $d['id']; ?>&user_id=<?php echo $d['user_id']; ?>" class="action-btn cancel" onclick="return confirm('Désactiver ce médecin ?')"><i class="fas fa-user-slash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Patients -->
        <div id="patients" class="section">
            <h2>Liste des Patients</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Date d'Inscription</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patients as $p): ?>
                            <tr>
                                <td>#<?php echo $p['id']; ?></td>
                                <td><?php echo htmlspecialchars($p['email']); ?></td>
                                <td><?php echo htmlspecialchars($p['telephone'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($p['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($patients)): ?>
                            <tr><td colspan="4" class="empty-msg">Aucun patient inscrit pour le moment.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function showSection(id) {
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.admin-nav button').forEach(b => b.classList.remove('active'));
            document.getElementById(id).classList.add('active');
            document.getElementById('btn-' + id).classList.add('active');
        }
    </script>
</body>
</html>
