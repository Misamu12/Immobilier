<?php
require_once("../../config/config.php");

// Vérifier que l'admin est connecté
if (!isset($_SESSION["admin"])) {
    header("Location: ../connexion.php");
    exit;
}

// Valider/refuser un agent (optionnel, via GET)
if (isset($_GET['valider'])) {
    $id = intval($_GET['valider']);
    $stmt = $connexion->prepare("UPDATE commissionnaire SET statut_validation = 1 WHERE id_commissionnaire = ?");
    $stmt->execute([$id]);
    header("Location: commissaire.php?msg=valide");
    exit;
}
if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    // Supprimer l'agent et son compte utilisateur
    $stmt = $connexion->prepare("SELECT id_utilisateur FROM commissionnaire WHERE id_commissionnaire = ?");
    $stmt->execute([$id]);
    $id_utilisateur = $stmt->fetchColumn();
    if ($id_utilisateur) {
        $connexion->prepare("DELETE FROM commissionnaire WHERE id_commissionnaire = ?")->execute([$id]);
        $connexion->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ?")->execute([$id_utilisateur]);
    }
    header("Location: commissaire.php?msg=suppr");
    exit;
}

// Récupérer tous les commissionnaires
$stmt = $connexion->query("
    SELECT c.*, u.nom, u.email
    FROM commissionnaire c
    JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
    ORDER BY c.id_commissionnaire DESC
");
$agents = $stmt->fetchAll(PDO::FETCH_ASSOC);

$msg = "";
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === "valide") $msg = "Agent validé avec succès.";
    if ($_GET['msg'] === "suppr") $msg = "Agent supprimé.";
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion des agents immobiliers - Admin</title>
    <link rel="stylesheet" href="../css/styles.css" />
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>
<?php include "header.php"; ?>

<main>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="dashboard-title">
                <h1>Agents immobiliers</h1>
                <p>Liste de tous les commissionnaires inscrits</p>
            </div>
        </div>
        <div class="dashboard-content">
            <?php if ($msg): ?>
                <div class="alert-message"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>
                        <i data-lucide="users"></i>
                        Commissionnaires
                    </h3>
                </div>
                <div class="card-content">
                    <table class="full-width" style="border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>telephone</th>
                                <th>Numéro agrément</th>
                                <th>Statut</th>
                                <th>Date inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($agents as $a): ?>
                            <tr>
                                <td><?= $a['id_commissionnaire'] ?></td>
                                <td><?= htmlspecialchars($a['nom']) ?></td>
                                <td><?= htmlspecialchars($a['email']) ?></td>
                                <td><?= htmlspecialchars($a['telephone']) ?></td>
                                <td><?= htmlspecialchars($a['numéro_agrement']) ?></td>
                                <td>
                                    <?php if ($a['statut_validation']): ?>
                                        <span class="badge badge-success">Validé</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">En attente</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($a['date_inscription'] ?? '') ?></td>
                                <td>
                                    <?php if (!$a['statut_validation']): ?>
                                        <a href="commissaire.php?valider=<?= $a['id_commissionnaire'] ?>" class="btn btn-success btn-sm" title="Valider">
                                            <i data-lucide="check-circle"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="commissaire.php?supprimer=<?= $a['id_commissionnaire'] ?>" class="btn btn-danger btn-sm" title="Supprimer" onclick="return confirm('Supprimer cet agent ?');">
                                        <i data-lucide="trash-2"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if (count($agents) === 0): ?>
                        <p>Aucun agent trouvé.</p>
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