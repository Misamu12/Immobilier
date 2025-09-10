<?php
require_once("../../config/config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: connexion.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);
$stmt = $connexion->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ? AND rôle = 'utilisateur'");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur introuvable.");
}

$msg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"] ?? "");
    $email = trim($_POST["email"] ?? "");
    if ($nom && $email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $connexion->prepare("UPDATE utilisateur SET nom = ?, email = ? WHERE id_utilisateur = ?");
        $stmt->execute([$nom, $email, $id]);
        $msg = "Utilisateur modifié avec succès.";
        $user['nom'] = $nom;
        $user['email'] = $email;
    } else {
        $msg = "Champs invalides.";
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Modifier utilisateur</title>
    <link rel="stylesheet" href="../css/styles.css" />
</head>
<body>
<?php include "header.php"; ?>
<main>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Modifier utilisateur</h1>
        </div>
        <div class="dashboard-content">
            <?php if ($msg): ?>
                <div class="alert-message"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>
            <form method="post" class="property-form">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" required value="<?= htmlspecialchars($user['nom']) ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="utilisateur.php" class="btn btn-outline">Retour</a>
            </form>
        </div>
    </div>
</main>
</body>
</html>