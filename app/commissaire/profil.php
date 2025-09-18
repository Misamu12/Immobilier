<?php
require_once("../../config/config.php");

// Vérifier si l'utilisateur est connecté et est un commissionnaire
if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? '') !== "commissionnaire") {
    header("Location: ../connexion.php");
    exit;
}

// Récupérer les infos utilisateur
$stmt = $connexion->prepare("SELECT nom, email FROM utilisateur WHERE id_utilisateur = ?");
$stmt->execute([$_SESSION["user_id"]]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer les infos commissionnaire
$stmt = $connexion->prepare("SELECT numéro_agrement, telephone, adresse FROM commissionnaire WHERE id_utilisateur = ?");
$stmt->execute([$_SESSION["user_id"]]);
$agent = $stmt->fetch(PDO::FETCH_ASSOC);

$message = "";

// Traitement de la modification du profil
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telephone = trim($_POST["telephone"] ?? "");
    $adresse = trim($_POST["adresse"] ?? "");
    $numero_agrement = trim($_POST["numero_agrement"] ?? "");

    if (!$nom || !$email || !$telephone || !$numero_agrement) {
        $message = "Tous les champs marqués * sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Adresse email invalide.";
    } else {
        // Mettre à jour utilisateur
        $stmt = $connexion->prepare("UPDATE utilisateur SET nom = ?, email = ? WHERE id_utilisateur = ?");
        $stmt->execute([$nom, $email, $_SESSION["user_id"]]);
        // Mettre à jour commissionnaire
        $stmt = $connexion->prepare("UPDATE commissionnaire SET telephone = ?, adresse = ?, numéro_agrement = ? WHERE id_utilisateur = ?");
        $stmt->execute([$telephone, $adresse, $numero_agrement, $_SESSION["user_id"]]);
        $message = "Profil mis à jour avec succès !";
        // Rafraîchir les infos
        $user['nom'] = $nom;
        $user['email'] = $email;
        $agent['telephone'] = $telephone;
        $agent['adresse'] = $adresse;
        $agent['numéro_agrement'] = $numero_agrement;
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mon profil - ImmobilierKin</title>
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
                <h1>Mon profil</h1>
                <p>Gérez vos informations personnelles et d'agence</p>
            </div>
        </div>
        <div class="dashboard-content">
            <?php if ($message): ?>
                <div class="alert-message"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <form method="post" class="property-form">
                <div class="form-group">
                    <label for="nom">Nom complet *</label>
                    <input type="text" id="nom" name="nom" required value="<?= htmlspecialchars($user['nom'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="email">Adresse email *</label>
                    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="telephone">telephone *</label>
                    <input type="text" id="telephone" name="telephone" required value="<?= htmlspecialchars($agent['telephone'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="numero_agrement">Numéro d'agrément *</label>
                    <input type="text" id="numero_agrement" name="numero_agrement" required value="<?= htmlspecialchars($agent['numéro_agrement'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="adresse">Adresse de l'agence</label>
                    <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars($agent['adresse'] ?? '') ?>">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save"></i>
                    Enregistrer les modifications
                </button>
            </form>
        </div>
    </div>
</main>
<script>
  lucide.createIcons();
</script>
</body>
</html>