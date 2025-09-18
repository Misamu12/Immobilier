<?php
require_once("../../config/config.php");

// Vérifier que l'admin est connecté
if (!isset($_SESSION["admin"])) {
    header("Location: connexion.php");
    exit;
}

// Valider une annonce (exemple, si tu as un champ statut_validation dans annonce)
if (isset($_GET['valider'])) {
    $id = intval($_GET['valider']);
    $stmt = $connexion->prepare("UPDATE annonce SET statut_validation = 1 WHERE id_annonce = ?");
    $stmt->execute([$id]);
    header("Location: annonce.php?msg=valide");
    exit;
}

// Supprimer une annonce
if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    // Supprimer la photo si besoin
    $stmt = $connexion->prepare("SELECT url_photo FROM photo_annonce WHERE id_annonce = ?");
    $stmt->execute([$id]);
    $photo = $stmt->fetchColumn();
    if ($photo && file_exists("../" . $photo)) {
        unlink("../" . $photo);
    }
    $connexion->prepare("DELETE FROM photo_annonce WHERE id_annonce = ?")->execute([$id]);
    $connexion->prepare("DELETE FROM annonce WHERE id_annonce = ?")->execute([$id]);
    header("Location: annonce.php?msg=suppr");
    exit;
}

// Récupérer toutes les annonces avec infos agent
$stmt = $connexion->query("
    SELECT a.*, u.nom AS nom_agent, u.email AS email_agent, c.statut_validation AS statut_agent
    FROM annonce a
    JOIN commissionnaire c ON a.id_commissionnaire = c.id_commissionnaire
    JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
    ORDER BY a.date_publication DESC
");
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

$msg = "";
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === "valide") $msg = "Annonce validée avec succès.";
    if ($_GET['msg'] === "suppr") $msg = "Annonce supprimée.";
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion des annonces - Admin</title>
    <link rel="stylesheet" href="../css/styles.css" />
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>
<?php include "header.php"; ?>

<main>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="dashboard-title">
                <h1>Annonces</h1>
                <p>Liste de toutes les annonces publiées</p>
            </div>
        </div>
        <div class="dashboard-content">
            <?php if ($msg): ?>
                <div class="alert-message"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>
                        <i data-lucide="home"></i>
                        Annonces
                    </h3>
                </div>
                <div class="card-content">
                    <table class="full-width" style="border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Prix</th>
                                <th>Lieu</th>
                                <th>Agent</th>
                                <th>Email agent</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($annonces as $a): ?>
                            <tr>
                                <td><?= $a['id_annonce'] ?></td>
                                <td><?= htmlspecialchars($a['titre']) ?></td>
                                <td><?= htmlspecialchars($a['type_bien']) ?></td>
                                <td><?= number_format($a['prix'], 0, ',', ' ') ?> $</td>
                                <td><?= htmlspecialchars($a['lieu']) ?></td>
                                <td><?= htmlspecialchars($a['nom_agent']) ?></td>
                                <td><?= htmlspecialchars($a['email_agent']) ?></td>
                                <td><?= htmlspecialchars($a['date_publication']) ?></td>
                                <td>
                                    <?php if (isset($a['statut_validation']) && $a['statut_validation'] == 1): ?>
                                        <span class="badge badge-success">Validée</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">En attente</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!isset($a['statut_validation']) || $a['statut_validation'] == 0): ?>
                                        <a href="annonce.php?valider=<?= $a['id_annonce'] ?>" class="btn btn-success btn-sm" title="Valider">
                                            <i data-lucide="check-circle"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="annonce.php?supprimer=<?= $a['id_annonce'] ?>" class="btn btn-danger btn-sm" title="Supprimer" onclick="return confirm('Supprimer cette annonce ?');">
                                        <i data-lucide="trash-2"></i>
                                    </a>
                                    <a href="../commissaire/voir_annonce.php?id=<?= $a['id_annonce'] ?>" class="btn btn-outline btn-sm" title="Voir">
                                        <i data-lucide="eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if (count($annonces) === 0): ?>
                        <p>Aucune annonce trouvée.</p>
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