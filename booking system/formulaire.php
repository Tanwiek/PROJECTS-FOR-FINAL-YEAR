<?php
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

$error = '';
$success = '';

// Fetch services for the dropdown
$stmt = $pdo->query("SELECT id, nom FROM services");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pre-select service if passed in URL
$selected_service_id = $_GET['service_id'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_id = $_POST['service'] ?? '';
    $date = $_POST['date'] ?? '';
    $heure = $_POST['heure'] ?? '';
    $motif = $_POST['motif'] ?? '';
    $client_id = $_SESSION['user_id'];

    if (empty($service_id) || empty($date) || empty($heure) || empty($motif)) {
        $error = "Tous les champs sont requis.";
    } else {
        // Check if it's a holiday
        $stmt = $pdo->prepare("SELECT id FROM joursferies WHERE date_ferie = :date");
        $stmt->execute(['date' => $date]);
        if ($stmt->rowCount() > 0) {
            $error = "Impossible de réserver un rendez-vous un jour férié.";
        } else {
            // Check for double booking
            $stmt = $pdo->prepare("SELECT id FROM rendezvous WHERE date_rdv = :date AND heure_rdv = :heure AND statut != 'Annulé'");
            $stmt->execute(['date' => $date, 'heure' => $heure]);
            if ($stmt->rowCount() > 0) {
                $error = "Ce créneau est déjà réservé. Veuillez choisir une autre heure.";
            } else {
                // Insert appointment
                $stmt = $pdo->prepare("INSERT INTO rendezvous (client_id, service_id, date_rdv, heure_rdv, motif) VALUES (:client_id, :service_id, :date, :heure, :motif)");
                if ($stmt->execute([
                    'client_id' => $client_id,
                    'service_id' => $service_id,
                    'date' => $date,
                    'heure' => $heure,
                    'motif' => $motif
                ])) {
                    $success = "Votre rendez-vous a été enregistré avec succès !";
                } else {
                    $error = "Une erreur est survenue lors de l'enregistrement.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prendre un Rendez-vous - TMT SARL</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }
        .booking-card {
            background: white;
            padding: 40px;
            width: 100%;
            max-width: 600px;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        }
        .form-header {
            text-align: center;
            margin-bottom: 35px;
        }
        .form-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 3px solid var(--primary-color);
        }
        .form-header h2 {
            font-size: 1.8rem;
            color: var(--text-main);
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .message {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            text-align: center;
        }
        .error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        
        @media (max-width: 600px) {
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="booking-card">
        <div class="form-header">
            <img src="logo.jpeg" alt="TMT SARL Logo">
            <h2>Prendre un rendez-vous</h2>
            <p style="color: var(--text-muted); font-size: 14px;">Remplissez le formulaire pour réserver votre créneau</p>
        </div>
        
        <?php if ($error): ?><div class="message error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        <?php if ($success): ?><div class="message success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

        <form method="post" action="formulaire.php">
            <div class="form-group">
                <label for="service">Service souhaité</label>
                <select id="service" name="service" class="form-input" required>
                    <option value="">-- Sélectionnez un service --</option>
                    <?php foreach ($services as $service): ?>
                        <option value="<?php echo $service['id']; ?>" <?php echo ($selected_service_id == $service['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($service['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="date">Date de rendez-vous</label>
                    <input type="date" id="date" name="date" class="form-input" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label for="heure">Heure souhaitée</label>
                    <input type="time" id="heure" name="heure" class="form-input" required>
                </div>
            </div>

            <div class="form-group">
                <label for="motif">Motif ou détails supplémentaires</label>
                <textarea id="motif" name="motif" class="form-input" rows="4" placeholder="Expliquez brièvement votre besoin..." required></textarea>
            </div>

            <div style="display: flex; gap: 15px; margin-top: 10px;">
                <a href="accueil.php" class="btn btn-outline" style="flex: 1; text-align: center;">Annuler</a>
                <button type="submit" class="btn btn-primary" style="flex: 2;">Confirmer la réservation</button>
            </div>
        </form>
    </div>
</body>
</html>
