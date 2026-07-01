<?php
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: connexion.php");
    exit;
}

$success = '';
$error = '';

// Handle service management
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_service'])) {
    $nom = $_POST['nom'] ?? '';
    $desc = $_POST['description'] ?? '';
    if ($nom) {
        $stmt = $pdo->prepare("INSERT INTO services (nom, description) VALUES (:nom, :desc)");
        $stmt->execute(['nom' => $nom, 'desc' => $desc]);
        $success = "Service ajouté.";
    }
}

if (isset($_GET['delete_service'])) {
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = :id");
    $stmt->execute(['id' => $_GET['delete_service']]);
    $success = "Service supprimé.";
}

// Handle appointment status
if (isset($_GET['confirm_id'])) {
    $stmt = $pdo->prepare("UPDATE rendezvous SET statut = 'Confirmé', vu_par_client = 0 WHERE id = :id");
    $stmt->execute(['id' => $_GET['confirm_id']]);
    $success = "Rendez-vous confirmé.";
}

if (isset($_GET['cancel_id'])) {
    $stmt = $pdo->prepare("UPDATE rendezvous SET statut = 'Annulé', vu_par_client = 0 WHERE id = :id");
    $stmt->execute(['id' => $_GET['cancel_id']]);
    $success = "Rendez-vous annulé.";
}
// Handle user creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $role = $_POST['role'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? ''; // Only for clients
    $email = $_POST['email'] ?? '';
    $tel = $_POST['telephone'] ?? ''; // Only for clients
    $pass = $_POST['password'] ?? '';
    
    if ($role && $nom && $email && $pass) {
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
        
        try {
            if ($role === 'client') {
                $stmt = $pdo->prepare("INSERT INTO clients (nom, prenom, email, telephone, password) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$nom, $prenom, $email, $tel, $hashed_pass]);
            } elseif ($role === 'secretaire') {
                $stmt = $pdo->prepare("INSERT INTO secretaire (nom, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$nom, $email, $hashed_pass]);
            } elseif ($role === 'admin') {
                $stmt = $pdo->prepare("INSERT INTO administrateurs (nom, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$nom, $email, $hashed_pass]);
            }
            $success = "Nouvel utilisateur ($role) créé avec succès.";
        } catch (PDOException $e) {
            $error = "Erreur lors de la création : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}

// Fetch users
$clients = $pdo->query("SELECT * FROM clients ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$secretaires = $pdo->query("SELECT * FROM secretaire ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all services
$stmt = $pdo->query("SELECT * FROM services");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all appointments
$stmt = $pdo->query("SELECT r.*, c.nom as client_nom, c.prenom as client_prenom, s.nom as service_nom 
                     FROM rendezvous r 
                     JOIN clients c ON r.client_id = c.id 
                     JOIN services s ON r.service_id = s.id 
                     ORDER BY r.date_rdv DESC");
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - TMT SARL</title>
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
            z-index: 100;
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
            text-align: left;
            border-bottom: 4px solid transparent;
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-card.primary { border-bottom-color: var(--primary-color); }
        .stat-card.success { border-bottom-color: #10b981; }
        .stat-card.warning { border-bottom-color: #f59e0b; }
        
        .stat-value { font-size: 1.8rem; font-weight: 700; color: var(--text-main); margin-bottom: 5px; }
        .stat-label { font-size: 14px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }

        .tabs { margin-bottom: 30px; border-bottom: 1px solid #e2e8f0; display: flex; gap: 30px; }
        .tab { padding: 15px 5px; color: var(--text-muted); text-decoration: none; font-weight: 600; cursor: pointer; border-bottom: 3px solid transparent; }
        .tab.active { color: var(--primary-color); border-bottom-color: var(--primary-color); }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        
        @media (max-width: 1024px) {
            .grid-2 { grid-template-columns: 1fr; }
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
            <span>TMT ADMIN</span>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="accueil.php" class="nav-link">🏠 Accueil Site</a>
            </li>
            <li class="nav-item">
                <a href="admin_dashboard.php" class="nav-link active">📊 Dashboard</a>
            </li>
        </ul>
        <a href="logout.php" class="nav-link" style="color: #ef4444;">🚪 Déconnexion</a>
    </aside>

    <main class="main-content">
        <header class="dashboard-header">
            <div>
                <h1 style="font-size: 1.8rem; font-weight: 700; color: var(--text-main);">Panneau d'Administration</h1>
                <p style="color: var(--text-muted);">Gérez vos services, rendez-vous et utilisateurs</p>
            </div>
        </header>

        <?php if ($success): ?>
            <div class="card" style="padding: 15px; background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; margin-bottom: 25px; text-align: center;">
                <strong>✓</strong> <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="card" style="padding: 15px; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; margin-bottom: 25px; text-align: center;">
                <strong>⚠</strong> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="stat-value"><?php echo count($appointments); ?></div>
                <div class="stat-label">Rendez-vous</div>
            </div>
            <div class="stat-card success">
                <div class="stat-value"><?php echo count($clients); ?></div>
                <div class="stat-label">Clients</div>
            </div>
            <div class="stat-card warning">
                <div class="stat-value"><?php echo count($services); ?></div>
                <div class="stat-label">Services</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo count($secretaires); ?></div>
                <div class="stat-label">Secrétaires</div>
            </div>
        </div>

        <div class="grid-2">
            <!-- Services Management -->
            <div>
                <div class="card">
                    <h2 style="font-size: 1.2rem; margin-bottom: 25px; color: var(--text-main);">Ajouter un Service</h2>
                    <form method="post">
                        <div class="form-group">
                            <label>Nom du service</label>
                            <input type="text" name="nom" class="form-input" required placeholder="ex: Demande de Visa">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-input" rows="3" placeholder="Description du service..."></textarea>
                        </div>
                        <button type="submit" name="add_service" class="btn btn-primary" style="width: 100%;">Ajouter le Service</button>
                    </form>
                </div>

                <div class="card" style="margin-top: 30px; padding: 0; overflow: hidden;">
                    <div style="padding: 20px; border-bottom: 1px solid #e2e8f0;">
                        <h2 style="font-size: 1.2rem; margin: 0; color: var(--text-main);">Services Actifs</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($services as $srv): ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 600;"><?php echo htmlspecialchars($srv['nom']); ?></div>
                                    <div style="font-size: 12px; color: var(--text-muted);"><?php echo htmlspecialchars(substr($srv['description'], 0, 50)) . '...'; ?></div>
                                </td>
                                <td>
                                    <a href="?delete_service=<?php echo $srv['id']; ?>" style="color: #ef4444; font-size: 13px; font-weight: 600;" onclick="return confirm('Supprimer ?')">Supprimer</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- User Lists -->
            <div>
                <div class="card" style="margin-bottom: 30px;">
                    <h2 style="font-size: 1.2rem; margin-bottom: 25px; color: var(--text-main);">Créer un Utilisateur</h2>
                    <form method="post" id="createUserForm">
                        <div class="form-group">
                            <label>Rôle</label>
                            <select name="role" class="form-input" id="roleSelect" onchange="toggleFields()" required>
                                <option value="client">Client</option>
                                <option value="secretaire">Secrétaire</option>
                                <option value="admin">Administrateur</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="nom" class="form-input" required placeholder="Nom de famille">
                        </div>
                        <div id="clientFields">
                            <div class="form-group">
                                <label>Prénom</label>
                                <input type="text" name="prenom" class="form-input" placeholder="Prénom">
                            </div>
                            <div class="form-group">
                                <label>Téléphone</label>
                                <input type="text" name="telephone" class="form-input" placeholder="06XXXXXXXX">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-input" required placeholder="nom@exemple.com">
                        </div>
                        <div class="form-group">
                            <label>Mot de passe</label>
                            <input type="password" name="password" class="form-input" required placeholder="••••••••">
                        </div>
                        <button type="submit" name="add_user" class="btn btn-primary" style="width: 100%;">Créer l'utilisateur</button>
                    </form>
                    
                    <script>
                        function toggleFields() {
                            const role = document.getElementById('roleSelect').value;
                            const clientFields = document.getElementById('clientFields');
                            if (role === 'client') {
                                clientFields.style.display = 'block';
                            } else {
                                clientFields.style.display = 'none';
                            }
                        }
                        // Initialize on load
                        document.addEventListener('DOMContentLoaded', toggleFields);
                    </script>
                </div>

                <div class="card" style="padding: 0; overflow: hidden; height: auto;">
                    <div style="padding: 20px; border-bottom: 1px solid #e2e8f0;">
                        <h2 style="font-size: 1.2rem; margin: 0; color: var(--text-main);">Utilisateurs du Système</h2>
                    </div>
                    
                    <div style="padding: 20px;">
                        <h3 style="font-size: 0.9rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 15px;">Clients</h3>
                        <div style="max-height: 250px; overflow-y: auto; margin-bottom: 30px;">
                            <table style="font-size: 14px;">
                                <thead>
                                    <tr><th>Nom et Prénom</th><th>Email</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clients as $c): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($c['nom'] . ' ' . $c['prenom']); ?></td>
                                            <td style="color: var(--text-muted);"><?php echo htmlspecialchars($c['email']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <h3 style="font-size: 0.9rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 15px;">Secrétaires</h3>
                        <div style="max-height: 250px; overflow-y: auto;">
                            <table style="font-size: 14px;">
                                <thead>
                                    <tr><th>Nom</th><th>Email</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($secretaires as $s): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($s['nom']); ?></td>
                                            <td style="color: var(--text-muted);"><?php echo htmlspecialchars($s['email']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointments Table Full Width -->
        <div class="card" style="margin-top: 30px; padding: 0; overflow: hidden;">
            <div style="padding: 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-size: 1.2rem; margin: 0; color: var(--text-main);">Tous les Rendez-vous</h2>
            </div>
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
                            <div style="display: flex; gap: 10px;">
                                <?php if ($app['statut'] == 'En attente'): ?>
                                    <a href="?confirm_id=<?php echo $app['id']; ?>" style="color: #10b981; font-size: 13px; font-weight: 600; text-decoration: none;">Confirmer</a>
                                <?php endif; ?>
                                <?php if ($app['statut'] !== 'Annulé'): ?>
                                    <a href="?cancel_id=<?php echo $app['id']; ?>" style="color: #ef4444; font-size: 13px; font-weight: 600; text-decoration: none;" onclick="return confirm('Annuler ce rdv ?')">Annuler</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>
