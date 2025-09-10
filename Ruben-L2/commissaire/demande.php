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

// Récupérer toutes les demandes reçues
$stmt = $connexion->prepare("
    SELECT d.*, u.nom AS nom_demandeur, u.email AS email_demandeur, a.titre AS titre_annonce
    FROM demande_logement d
    LEFT JOIN utilisateur u ON d.id_utilisateur = u.id_utilisateur
    LEFT JOIN annonce a ON d.id_annonce = a.id_annonce
    WHERE d.id_commissionnaire = ?
    ORDER BY d.date_demande DESC
");
$stmt->execute([$id_commissionnaire]);
$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction utilitaire pour afficher le temps écoulé
function time_ago($date) {
    $timestamp = strtotime($date);
    $diff = time() - $timestamp;
    if ($diff < 60) return $diff . "s";
    if ($diff < 3600) return floor($diff/60) . "min";
    if ($diff < 86400) return floor($diff/3600) . "h";
    return floor($diff/86400) . "j";
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Demandes reçues - ImmobilierKin</title>
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
                <h1>Demandes reçues</h1>
                <p>Liste de toutes les demandes pour vos annonces</p>
            </div>
        </div>
        <div class="dashboard-content">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>
                        <i data-lucide="message-square"></i>
                        Toutes les demandes
                    </h3>
                </div>
                <div class="card-content">
                    <div class="requests-list">
                        <?php if (empty($demandes)): ?>
                            <p>Aucune demande reçue pour le moment.</p>
                        <?php else: ?>
                            <?php foreach ($demandes as $demande): ?>
                                <div class="request-item">
                                    <div class="request-info">
                                        <h4><?= htmlspecialchars($demande['titre_annonce'] ?? 'Annonce supprimée') ?></h4>
                                        <p><?= htmlspecialchars($demande['message'] ?? '') ?></p>
                                        <span class="request-time">
                                            Par <?= htmlspecialchars($demande['nom_demandeur']) ?> (<?= htmlspecialchars($demande['email_demandeur']) ?>)
                                            • <?= time_ago($demande['date_demande']) ?> •
                                            <strong><?= htmlspecialchars($demande['etat']) ?></strong>
                                        </span>
                                    </div>
                                    <div class="request-actions">
                                        <?php if ($demande['etat'] === 'en attente'): ?>
                                            <form method="post" action="traiter_demande.php" style="display:inline;">
                                                <input type="hidden" name="id_demande" value="<?= $demande['id_demande'] ?>">
                                                <input type="hidden" name="action" value="refuser">
                                                <button type="submit" class="btn btn-outline btn-sm">Refuser</button>
                                            </form>
                                            <form method="post" action="traiter_demande.php" style="display:inline;">
                                                <input type="hidden" name="id_demande" value="<?= $demande['id_demande'] ?>">
                                                <input type="hidden" name="action" value="accepter">
                                                <button type="submit" class="btn btn-primary btn-sm">Accepter</button>
                                            </form>
                                        <?php elseif ($demande['etat'] === 'acceptée'): ?>
                                            <span class="badge badge-success">Acceptée</span>
                                        <?php elseif ($demande['etat'] === 'refusée'): ?>
                                            <span class="badge badge-danger">Refusée</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
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