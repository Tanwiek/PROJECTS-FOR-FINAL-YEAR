<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php?redirect=reservation.php" . (isset($_GET['service_id']) ? "&service_id=" . $_GET['service_id'] : ""));
    exit;
}

// Fetch all services
$stmt = $pdo->query("SELECT * FROM Services");
$services = $stmt->fetchAll();

// Fetch all doctors
$stmt = $pdo->query("SELECT * FROM Medecins");
$doctors = $stmt->fetchAll();

$service_id_selected = isset($_GET['service_id']) ? $_GET['service_id'] : "";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver un RDV - Hôpital Plus</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <div class="logo">Hôpital Plus</div>
            <nav>
                <a href="index.php">Accueil</a>
                <a href="dashboard_patient.php">Mon Profil</a>
            </nav>
        </div>
    </header>

    <div class="container form-page">
        <div class="form-card">
            <h2>Prendre un Rendez-vous</h2>
            <p>Veuillez remplir les informations ci-dessous.</p>
            <form action="actions/reserve_action.php" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="nom" required placeholder="Votre nom">
                    </div>
                    <div class="form-group">
                        <label>Prénom</label>
                        <input type="text" name="prenom" required placeholder="Votre prénom">
                    </div>
                    <div class="form-group">
                        <label>Service</label>
                        <select name="service_id" required id="service_select">
                            <option value="">Choisir un service</option>
                            <?php foreach($services as $s): ?>
                                <option value="<?php echo $s['id']; ?>" <?php echo ($service_id_selected == $s['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($s['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Docteur</label>
                        <select name="medecin_id" required>
                            <option value="">Choisir un médecin</option>
                            <?php foreach($doctors as $d): ?>
                                <option value="<?php echo $d['id']; ?>">
                                    Dr. <?php echo htmlspecialchars($d['nom']); ?> (<?php echo htmlspecialchars($d['specialite']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="tel" name="telephone" required placeholder="+237 ...">
                    </div>
                    <div class="form-group">
                        <label>Date souhaitée</label>
                        <input type="date" name="date_rv" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Heure</label>
                        <input type="time" name="heure_rv" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Message (Optionnel)</label>
                    <textarea name="message" rows="4" placeholder="Description de vos symptômes..."></textarea>
                </div>
                <button type="submit" class="btn-primary">Confirmer la Réservation</button>
            </form>
        </div>
    </div>
</body>
</html>
