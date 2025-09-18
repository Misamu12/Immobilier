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

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = trim($_POST["titre"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $lieu = trim($_POST["lieu"] ?? "");
    $type_bien = $_POST["type_bien"] ?? "";
    $prix = floatval($_POST["prix"] ?? 0);
    $date_publication = date("Y-m-d");

    // Validation simple
    if (!$titre || !$lieu || !$type_bien || !$prix) {
        $message = "Veuillez remplir tous les champs obligatoires.";
    } else {
        // Insertion de l'annonce
        $stmt = $connexion->prepare("INSERT INTO annonce (titre, description, lieu, type_bien, prix, date_publication, id_commissionnaire) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titre, $description, $lieu, $type_bien, $prix, $date_publication, $id_commissionnaire]);
        $id_annonce = $connexion->lastInsertId();

        // Gestion de la photo principale (facultatif)
        if (!empty($_FILES['photo']['name'])) {
            $photo_name = uniqid() . "_" . basename($_FILES["photo"]["name"]);
            $target_dir = "../../uploads/";
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $target_file = $target_dir . $photo_name;
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                // Enregistrer le chemin dans la table photo_annonce
                $stmt = $connexion->prepare("INSERT INTO photo_annonce (id_annonce, url_photo) VALUES (?, ?)");
                $stmt->execute([$id_annonce, "uploads/" . $photo_name]);
            }
        }

        $message = "Annonce ajoutée avec succès !";
        // Optionnel : rediriger vers la liste
        // header("Location: annonce.php?success=1"); exit;
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ajouter une annonce - ImmobilierKin</title>
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
                    <h1>Ajouter une annonce</h1>
                    <p>Publiez un nouveau bien immobilier</p>
                </div>
                <div class="dashboard-actions">
                    <a href="annonce.php" class="btn btn-outline">
                        <i data-lucide="arrow-left"></i>
                        Retour à mes annonces
                    </a>
                </div>
            </div>
            <div class="dashboard-content">
                <?php if ($message): ?>
                    <div class="alert-message"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>
                <form class="property-form" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="titre">Titre de l'annonce *</label>
                        <input type="text" id="titre" name="titre" required value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="lieu">Lieu *</label>
                        <input type="text" id="lieu" name="lieu" required value="<?= htmlspecialchars($_POST['lieu'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="type_bien">Type de bien *</label>
                        <select id="type_bien" name="type_bien" required>
                            <option value="">Sélectionnez...</option>
                            <option value="maison" <?= (($_POST['type_bien'] ?? '') === 'maison') ? 'selected' : '' ?>>Maison</option>
                            <option value="parcelle" <?= (($_POST['type_bien'] ?? '') === 'parcelle') ? 'selected' : '' ?>>Parcelle</option>
                            <option value="hôtel" <?= (($_POST['type_bien'] ?? '') === 'hôtel') ? 'selected' : '' ?>>Hôtel</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="prix">Prix ($) *</label>
                        <input type="number" id="prix" name="prix" min="0" required value="<?= htmlspecialchars($_POST['prix'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="photo">Photo principale</label>
                        <input type="file" id="photo" name="photo" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="plus"></i>
                        Publier l'annonce
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