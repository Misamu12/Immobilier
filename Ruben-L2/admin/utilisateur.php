<?php
require_once("../../config/config.php");

// Vérifier que l'admin est connecté
if (!isset($_SESSION["admin"])) {
    header("Location: connexion.php");
    exit;
}

// Suppression d'un utilisateur
if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    // On ne supprime pas les commissionnaires ici
    $stmt = $connexion->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ? AND rôle = 'utilisateur'");
    $stmt->execute([$id]);
    header("Location: utilisateur.php?msg=suppr");
    exit;
}

// Récupérer tous les utilisateurs (hors commissionnaires)
$stmt = $connexion->query("SELECT * FROM utilisateur WHERE rôle = 'utilisateur' ORDER BY date_inscription DESC");
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$msg = "";
if (isset($_GET['msg']) && $_GET['msg'] === "suppr") {
    $msg = "Utilisateur supprimé avec succès.";
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion des utilisateurs - Admin</title>
    <link rel="stylesheet" href="../css/styles.css" />
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>
<?php include "header.php"; ?>

<main>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="dashboard-title">
                <h1>Utilisateurs</h1>
                <p>Liste de tous les utilisateurs inscrits</p>
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
                        Utilisateurs
                    </h3>
                </div>
                <div class="card-content">
                    <table class="full-width" style="border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Date inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($utilisateurs as $u): ?>
                            <tr>
                                <td><?= $u['id_utilisateur'] ?></td>
                                <td><?= htmlspecialchars($u['nom']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['date_inscription']) ?></td>
                                <td>
                                    <a href="modifier_utilisateur.php?id=<?= $u['id_utilisateur'] ?>" class="btn btn-outline btn-sm" title="Modifier">
                                        <i data-lucide="edit"></i>
                                    </a>
                                    <a href="utilisateur.php?supprimer=<?= $u['id_utilisateur'] ?>" class="btn btn-danger btn-sm" title="Supprimer" onclick="return confirm('Supprimer cet utilisateur ?');">
                                        <i data-lucide="trash-2"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if (count($utilisateurs) === 0): ?>
                        <p>Aucun utilisateur trouvé.</p>
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