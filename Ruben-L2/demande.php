<?php
require_once("../config/config.php");

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: connexion.php");
    exit;
}

// Récupérer toutes les demandes de ce user
$stmt = $connexion->prepare("
    SELECT d.*, a.titre AS titre_annonce, a.lieu, a.type_bien, a.prix, a.id_annonce, u.nom AS nom_agent, c.telephone AS tel_agent
    FROM demande_logement d
    LEFT JOIN annonce a ON d.id_annonce = a.id_annonce
    LEFT JOIN commissionnaire c ON d.id_commissionnaire = c.id_commissionnaire
    LEFT JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
    WHERE d.id_utilisateur = ?
    ORDER BY d.date_demande DESC
");
$stmt->execute([$_SESSION["user_id"]]);
$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Mes demandes de logement</title>
    <link rel="stylesheet" href="css/styles.css" />
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>
<?php include "header.php"; ?>

<main>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="dashboard-title">
                <h1>Mes demandes de logement</h1>
                <p>Suivez l’état de vos demandes envoyées aux agents immobiliers</p>
            </div>
        </div>
        <div class="dashboard-content">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>
                        <i data-lucide="message-square"></i>
                        Mes demandes
                    </h3>
                </div>
                <div class="card-content">
                    <?php if (empty($demandes)): ?>
                        <div class="no-results-content">
                            <i data-lucide="search-x"></i>
                            <h3>Aucune demande envoyée</h3>
                            <p>Vous n’avez pas encore contacté d’agent immobilier.</p>
                        </div>
                    <?php else: ?>
                        <div class="requests-list">
                            <?php foreach ($demandes as $demande): ?>
                                <div class="request-item">
                                    <div class="request-info">
                                        <h4>
                                            <?php if ($demande['id_annonce']): ?>
                                                <a href="voir_annonce.php?id=<?= $demande['id_annonce'] ?>">
                                                    <?= htmlspecialchars($demande['titre_annonce']) ?>
                                                </a>
                                            <?php else: ?>
                                                <span>Annonce supprimée</span>
                                            <?php endif; ?>
                                        </h4>
                                        <p>
                                            <strong>Message :</strong>
                                            <?= nl2br(htmlspecialchars($demande['message'])) ?>
                                        </p>
                                        <div class="request-time">
                                            <span>
                                                Envoyée le <?= htmlspecialchars($demande['date_demande']) ?>
                                                • <strong><?= htmlspecialchars($demande['etat']) ?></strong>
                                            </span>
                                        </div>
                                        <div class="property-details">
                                            <?php if ($demande['lieu']): ?>
                                                <span><i data-lucide="map-pin"></i> <?= htmlspecialchars($demande['lieu']) ?></span>
                                            <?php endif; ?>
                                            <?php if ($demande['type_bien']): ?>
                                                <span><i data-lucide="home"></i> <?= htmlspecialchars($demande['type_bien']) ?></span>
                                            <?php endif; ?>
                                            <?php if ($demande['prix']): ?>
                                                <span><i data-lucide="dollar-sign"></i> <?= number_format($demande['prix'], 0, ',', ' ') ?> $</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="property-agent">
                                            <?php if ($demande['nom_agent']): ?>
                                                <span>
                                                    <i data-lucide="user"></i>
                                                    <?= htmlspecialchars($demande['nom_agent']) ?>
                                                    <?php if ($demande['tel_agent']): ?>
                                                        • <i data-lucide="phone"></i> <?= htmlspecialchars($demande['tel_agent']) ?>
                                                    <?php endif; ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($demande['reponse_commissionnaire']): ?>
                                            <div class="alert-message" style="margin-top:0.5rem;">
                                                <strong>Réponse de l’agent :</strong><br>
                                                <?= nl2br(htmlspecialchars($demande['reponse_commissionnaire'])) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="request-actions">
                                        <?php if ($demande['etat'] === 'en attente'): ?>
                                            <span class="badge badge-warning">En attente</span>
                                        <?php elseif ($demande['etat'] === 'acceptée'): ?>
                                            <span class="badge badge-success">Acceptée</span>
                                        <?php elseif ($demande['etat'] === 'refusée'): ?>
                                            <span class="badge badge-danger">Refusée</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
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