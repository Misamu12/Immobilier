<?php
require_once("../config/config.php");

if (!isset($_SESSION["user_id"])) {
    header("Location:connexion.php");
    exit;
}

$id_annonce = intval($_POST['id_annonce'] ?? 0);
$message = trim($_POST['message'] ?? '');

if ($id_annonce && $message) {
    // Trouver le commissionnaire lié à l'annonce
    $stmt = $connexion->prepare("SELECT id_commissionnaire FROM annonce WHERE id_annonce = ?");
    $stmt->execute([$id_annonce]);
    $id_commissionnaire = $stmt->fetchColumn();

    if ($id_commissionnaire) {
        $stmt = $connexion->prepare("INSERT INTO demande_logement (id_utilisateur, id_commissionnaire, id_annonce, message, date_demande, etat) VALUES (?, ?, ?, ?, NOW(), 'en attente')");
        $stmt->execute([$_SESSION["user_id"], $id_commissionnaire, $id_annonce, $message]);
        header("Location: demande.php?sent=1");
        exit;
    }
}

header("Location: rechercher.php?error=1");
exit;