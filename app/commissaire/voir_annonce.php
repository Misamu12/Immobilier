<?php
require_once("../../config/config.php");

// Vérifier que l'utilisateur est connecté (admin ou agent)
if (!isset($_SESSION["admin"]) && (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? '') !== "commissionnaire")) {
    header("Location: ../connexion.php");
    exit;
}

$id_annonce = intval($_GET['id'] ?? 0);

// Récupérer l'annonce et l'agent
$stmt = $connexion->prepare("
    SELECT a.*, u.nom AS nom_agent, u.email AS email_agent, c.telephone AS tel_agent
    FROM annonce a
    JOIN commissionnaire c ON a.id_commissionnaire = c.id_commissionnaire
    JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
    WHERE a.id_annonce = ?
");
$stmt->execute([$id_annonce]);
$annonce = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$annonce) {
    die("Annonce introuvable.");
}

// Récupérer les photos
$stmt = $connexion->prepare("SELECT url_photo FROM photo_annonce WHERE id_annonce = ?");
$stmt->execute([$id_annonce]);
$photos = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Voir annonce - ImmobilierKin</title>
    <link rel="stylesheet" href="../css/styles.css" />
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>
<?php
if (file_exists("../admin/header.php") && isset($_SESSION["admin"])) {
    include "../admin/header.php";
} else {
    include "header.php";
}
?>

<main>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="dashboard-title">
                <h1><?= htmlspecialchars($annonce['titre']) ?></h1>
                <p><?= htmlspecialchars($annonce['type_bien']) ?> à <?= htmlspecialchars($annonce['lieu']) ?></p>
            </div>
            <div class="dashboard-actions">
                <a href="../admin/annonce.php" class="btn btn-outline">
                    <i data-lucide="arrow-left"></i>
                    Retour aux annonces
                </a>
            </div>
        </div>
        <div class="dashboard-content">
            <div class="property-details-card">
                <div class="property-photos">
                    <?php if ($photos): ?>
                        <?php foreach ($photos as $photo): ?>
                            <img src="../../<?= htmlspecialchars($photo) ?>" alt="Photo annonce" style="max-width:200px;max-height:200px;margin:5px;">
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-photo">
                            <i data-lucide="image"></i>
                            <span>Aucune photo</span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="property-info">
                    <h2><?= htmlspecialchars($annonce['titre']) ?></h2>
                    <p><strong>Description :</strong><br><?= nl2br(htmlspecialchars($annonce['description'])) ?></p>
                    <p><strong>Type :</strong> <?= htmlspecialchars($annonce['type_bien']) ?></p>
                    <p><strong>Prix :</strong> <?= number_format($annonce['prix'], 0, ',', ' ') ?> $</p>
                    <p><strong>Lieu :</strong> <?= htmlspecialchars($annonce['lieu']) ?></p>
                    <p><strong>Date de publication :</strong> <?= htmlspecialchars($annonce['date_publication']) ?></p>
                    <hr>
                    <h3>Agent immobilier</h3>
                    <p><strong>Nom :</strong> <?= htmlspecialchars($annonce['nom_agent']) ?></p>
                    <p><strong>Email :</strong> <?= htmlspecialchars($annonce['email_agent']) ?></p>
                    <p><strong>telephone :</strong> <?= htmlspecialchars($annonce['tel_agent']) ?></p>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
  lucide.createIcons();
</script>
</body>
</html>