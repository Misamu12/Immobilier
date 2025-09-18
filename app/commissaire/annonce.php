<?php
require_once("../../config/config.php");

// Vérifier si l'utilisateur est connecté et est un commissionnaire
if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? '') !== "commissionnaire") {
    header("Location: ../connexion.php");
    exit;
}

// Récupérer l'id du commissionnaire lié à cet utilisateur
$stmt = $connexion->prepare("SELECT id_commissionnaire FROM commissionnaire WHERE id_utilisateur = ?");
$stmt->execute([$_SESSION["user_id"]]);
$commissionnaire = $stmt->fetch(PDO::FETCH_ASSOC);
$id_commissionnaire = $commissionnaire ? $commissionnaire['id_commissionnaire'] : 0;

// Récupérer les annonces de ce commissionnaire
$stmt = $connexion->prepare("SELECT * FROM annonce WHERE id_commissionnaire = ? ORDER BY date_publication DESC");
$stmt->execute([$id_commissionnaire]);
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mes annonces - ImmobilierKin</title>
    <link rel="stylesheet" href="../css/styles.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>
    <?php include "header.php"; ?>

    <main>
        <div class="dashboard-container">
            <div class="dashboard-header">
                <div class="dashboard-title">
                    <h1>Mes annonces</h1>
                    <p>Gérez vos annonces immobilières</p>
                </div>
                <div class="dashboard-actions">
                    <a href="ajouter_annonce.php" class="btn btn-primary">
                        <i data-lucide="plus"></i>
                        Nouvelle annonce
                    </a>
                </div>
            </div>

            <div class="dashboard-content">
                <?php if (count($annonces) === 0): ?>
                    <div class="no-results">
                        <div class="no-results-content">
                            <i data-lucide="building-2"></i>
                            <h3>Aucune annonce trouvée</h3>
                            <p>Vous n'avez pas encore publié d'annonce.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="properties-grid">
                        <?php foreach ($annonces as $annonce): ?>
                            <div class="property-card">
                                <div class="property-image">
                                    <!-- Affiche la première photo si tu as une table photo_annonce -->
                                    <?php
                                    $stmtPhoto = $connexion->prepare("SELECT url_photo FROM photo_annonce WHERE id_annonce = ? LIMIT 1");
                                    $stmtPhoto->execute([$annonce['id_annonce']]);
                                    $photo = $stmtPhoto->fetchColumn();
                                    if ($photo):
                                    ?>
                                        <img src="<?= htmlspecialchars($photo) ?>" alt="Photo annonce" style="width:100%;height:100%;object-fit:cover;">
                                    <?php else: ?>
                                        <i data-lucide="image"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="property-content">
                                    <div class="property-price"><?= number_format($annonce['prix'], 0, ',', ' ') ?> $</div>
                                    <div class="property-title"><?= htmlspecialchars($annonce['titre']) ?></div>
                                    <div class="property-location">
                                        <i data-lucide="map-pin"></i>
                                        <?= htmlspecialchars($annonce['lieu']) ?>
                                    </div>
                                    <div class="property-details">
                                        <span class="detail-item">
                                            <i data-lucide="home"></i>
                                            <?= htmlspecialchars($annonce['type_bien']) ?>
                                        </span>
                                        <span class="detail-item">
                                            <i data-lucide="calendar"></i>
                                            <?= htmlspecialchars($annonce['date_publication']) ?>
                                        </span>
                                    </div>
                                    <div class="property-agent">
                                        <a href="modifier_annonce.php?id=<?= $annonce['id_annonce'] ?>" class="btn btn-outline btn-sm">Modifier</a>
                                        <a href="supprimer_annonce.php?id=<?= $annonce['id_annonce'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette annonce ?');">Supprimer</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <script>
      lucide.createIcons();
    </script>
</body>
</html>