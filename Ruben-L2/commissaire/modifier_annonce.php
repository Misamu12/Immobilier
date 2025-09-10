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

// Récupérer l'annonce à modifier
$id_annonce = intval($_GET['id'] ?? 0);
$stmt = $connexion->prepare("SELECT * FROM annonce WHERE id_annonce = ? AND id_commissionnaire = ?");
$stmt->execute([$id_annonce, $id_commissionnaire]);
$annonce = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$annonce) {
    die("Annonce introuvable ou accès refusé.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = trim($_POST["titre"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $lieu = trim($_POST["lieu"] ?? "");
    $type_bien = $_POST["type_bien"] ?? "";
    $prix = floatval($_POST["prix"] ?? 0);

    if (!$titre || !$lieu || !$type_bien || !$prix) {
        $message = "Veuillez remplir tous les champs obligatoires.";
    } else {
        // Mise à jour de l'annonce
        $stmt = $connexion->prepare("UPDATE annonce SET titre=?, description=?, lieu=?, type_bien=?, prix=? WHERE id_annonce=? AND id_commissionnaire=?");
        $stmt->execute([$titre, $description, $lieu, $type_bien, $prix, $id_annonce, $id_commissionnaire]);

        // Gestion de la nouvelle photo principale (facultatif)
        if (!empty($_FILES['photo']['name'])) {
            $photo_name = uniqid() . "_" . basename($_FILES["photo"]["name"]);
            $target_dir = "../../uploads/";
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $target_file = $target_dir . $photo_name;
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                // Supprimer l'ancienne photo si besoin (optionnel)
                $stmt = $connexion->prepare("SELECT url_photo FROM photo_annonce WHERE id_annonce = ? LIMIT 1");
                $stmt->execute([$id_annonce]);
                $old_photo = $stmt->fetchColumn();
                if ($old_photo && file_exists("../../" . $old_photo)) {
                    unlink("../../" . $old_photo);
                }
                // Mettre à jour ou insérer la nouvelle photo
                $stmt = $connexion->prepare("SELECT id_photo FROM photo_annonce WHERE id_annonce = ?");
                $stmt->execute([$id_annonce]);
                if ($stmt->fetchColumn()) {
                    $stmt = $connexion->prepare("UPDATE photo_annonce SET url_photo=? WHERE id_annonce=?");
                    $stmt->execute(["uploads/" . $photo_name, $id_annonce]);
                } else {
                    $stmt = $connexion->prepare("INSERT INTO photo_annonce (id_annonce, url_photo) VALUES (?, ?)");
                    $stmt->execute([$id_annonce, "uploads/" . $photo_name]);
                }
            }
        }

        $message = "Annonce modifiée avec succès !";
        // Optionnel : rediriger vers la liste
        // header("Location: annonce.php?modif=1"); exit;
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifier une annonce - ImmobilierKin</title>
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
                    <h1>Modifier une annonce</h1>
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
                        <input type="text" id="titre" name="titre" required value="<?= htmlspecialchars($_POST['titre'] ?? $annonce['titre']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"><?= htmlspecialchars($_POST['description'] ?? $annonce['description']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="lieu">Lieu *</label>
                        <input type="text" id="lieu" name="lieu" required value="<?= htmlspecialchars($_POST['lieu'] ?? $annonce['lieu']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="type_bien">Type de bien *</label>
                        <select id="type_bien" name="type_bien" required>
                            <option value="">Sélectionnez...</option>
                            <option value="maison" <?= ((($_POST['type_bien'] ?? $annonce['type_bien']) === 'maison') ? 'selected' : '') ?>>Maison</option>
                            <option value="parcelle" <?= ((($_POST['type_bien'] ?? $annonce['type_bien']) === 'parcelle') ? 'selected' : '') ?>>Parcelle</option>
                            <option value="hôtel" <?= ((($_POST['type_bien'] ?? $annonce['type_bien']) === 'hôtel') ? 'selected' : '') ?>>Hôtel</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="prix">Prix ($) *</label>
                        <input type="number" id="prix" name="prix" min="0" required value="<?= htmlspecialchars($_POST['prix'] ?? $annonce['prix']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="photo">Photo principale (laisser vide pour ne pas changer)</label>
                        <input type="file" id="photo" name="photo" accept="image/*">
                        <?php
                        $stmt = $connexion->prepare("SELECT url_photo FROM photo_annonce WHERE id_annonce = ? LIMIT 1");
                        $stmt->execute([$id_annonce]);
                        $photo = $stmt->fetchColumn();
                        if ($photo):
                        ?>
                            <div>
                                <img src="../../<?= htmlspecialchars($photo) ?>" alt="Photo actuelle" style="max-width:150px;max-height:150px;">
                            </div>
                        <?php endif; ?>
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