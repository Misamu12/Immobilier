<?php
session_start();
require_once("../../config/config.php");

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? '') !== "commissionnaire") {
    header("Location: ../connexion.php");
    exit;
}

$id_annonce = intval($_GET['id'] ?? 0);

// Vérifier que l'annonce appartient bien à ce commissionnaire
$stmt = $connexion->prepare("
    SELECT a.id_annonce, p.url_photo
    FROM annonce a
    LEFT JOIN photo_annonce p ON a.id_annonce = p.id_annonce
    WHERE a.id_annonce = ? AND a.id_commissionnaire = (
        SELECT id_commissionnaire FROM commissionnaire WHERE id_utilisateur = ?
    )
");
$stmt->execute([$id_annonce, $_SESSION["user_id"]]);
$annonce = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$annonce) {
    die("Annonce introuvable ou accès refusé.");
}

// Supprimer la photo si elle existe
if ($annonce['url_photo'] && file_exists("../../" . $annonce['url_photo'])) {
    unlink("../../" . $annonce['url_photo']);
}

// Supprimer la photo_annonce
$stmt = $connexion->prepare("DELETE FROM photo_annonce WHERE id_annonce = ?");
$stmt->execute([$id_annonce]);

// Supprimer l'annonce
$stmt = $connexion->prepare("DELETE FROM annonce WHERE id_annonce = ?");
$stmt->execute([$id_annonce]);

header("Location: annonce.php?suppr=1");
exit;